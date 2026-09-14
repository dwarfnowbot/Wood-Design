<?php
/**
 * Image library.
 *
 * The original website loads its photography from absolute Pexels URLs
 * (original-source/src/data/media.ts). The theme ships the exact same URLs so
 * the design is identical out of the box, and can copy them into the WordPress
 * Media Library (Appearance → Maison Woodcraft → Import Demo Content, or the
 * "Import images" action in the same screen) so every image becomes a normal,
 * replaceable WordPress attachment.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * The full image map: key => array( url, alt ).
 *
 * @return array
 */
function mw_media_map() {
	static $map = null;

	if ( null === $map ) {
		$file = MW_THEME_DIR . '/inc/content/media-map.json';
		$json = file_exists( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
		$data = $json ? json_decode( $json, true ) : array();
		$map  = isset( $data['images'] ) && is_array( $data['images'] ) ? $data['images'] : array();
	}

	return $map;
}

/**
 * One image entry.
 *
 * @param string $key Image key, e.g. 'heroKitchen' or 'kitchen.3'.
 * @return array{url:string,alt:string}|null
 */
function mw_image( $key ) {
	if ( empty( $key ) ) {
		return null;
	}

	// An absolute URL was passed through instead of a key.
	if ( 0 === strpos( $key, 'http' ) ) {
		return array(
			'url' => $key,
			'alt' => '',
		);
	}

	$map = mw_media_map();
	if ( isset( $map[ $key ] ) ) {
		return array(
			'url' => $map[ $key ]['url'],
			'alt' => isset( $map[ $key ]['alt'] ) ? $map[ $key ]['alt'] : '',
		);
	}

	return null;
}

/**
 * Image URL for a media key.
 *
 * Falls back to the original remote URL unless the image has been imported into
 * the Media Library, in which case the local attachment URL is used.
 *
 * @param string $key      Media key.
 * @param string $fallback Fallback URL.
 * @return string
 */
function mw_image_url( $key, $fallback = '' ) {
	$attachment_id = mw_attachment_id_for_key( $key );
	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}

	$image = mw_image( $key );
	return $image ? $image['url'] : $fallback;
}

/**
 * Alt text for a media key.
 *
 * @param string $key      Media key.
 * @param string $fallback Fallback alt text.
 * @return string
 */
function mw_image_alt( $key, $fallback = '' ) {
	$image = mw_image( $key );
	$alt   = $image && ! empty( $image['alt'] ) ? $image['alt'] : $fallback;

	return apply_filters( 'mw_image_alt', $alt, $key );
}

/**
 * Look up a Media Library attachment that was imported for a media key.
 *
 * @param string $key Media key.
 * @return int Attachment ID or 0.
 */
function mw_attachment_id_for_key( $key ) {
	if ( empty( $key ) ) {
		return 0;
	}

	$imported = get_option( 'mw_media_attachments', array() );
	if ( is_array( $imported ) && ! empty( $imported[ $key ] ) ) {
		$id = (int) $imported[ $key ];
		if ( $id && get_post( $id ) ) {
			return $id;
		}
	}

	return 0;
}

/**
 * 1×1 transparent GIF used as the initial src of lazy, JS-filled images.
 *
 * Keeps the markup valid and stops browsers from re-requesting the page.
 *
 * @return string
 */
function mw_placeholder_image() {
	return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}

/**
 * Build an <img> tag for a media key, mirroring the original markup
 * (object-fit: cover with the crop coming from CSS).
 *
 * @param string $key      Media key.
 * @param array  $args     Optional: class, sizes, loading, alt override, url override.
 * @return string
 */
function mw_image_tag( $key, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class'   => '',
			'alt'     => '',
			'sizes'   => '(max-width: 1023px) 100vw, 640px',
			'loading' => 'lazy',
			'url'     => '',
			'width'   => '',
			'height'  => '',
		)
	);

	$attachment_id = mw_attachment_id_for_key( $key );
	$alt           = $args['alt'] ? $args['alt'] : mw_image_alt( $key, '' );

	if ( $attachment_id ) {
		$html = wp_get_attachment_image(
			$attachment_id,
			'large',
			false,
			array(
				'class'   => trim( 'mw-img ' . $args['class'] ),
				'alt'     => $alt,
				'sizes'   => $args['sizes'],
				'loading' => $args['loading'],
			)
		);
		if ( $html ) {
			return $html;
		}
	}

	$url = $args['url'] ? $args['url'] : mw_image_url( $key );
	if ( ! $url ) {
		return '';
	}

	$attributes = sprintf(
		'src="%s" alt="%s" class="mw-img %s" loading="%s" decoding="async"',
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $args['class'] ),
		esc_attr( $args['loading'] )
	);

	if ( $args['width'] ) {
		$attributes .= sprintf( ' width="%s"', esc_attr( $args['width'] ) );
	}
	if ( $args['height'] ) {
		$attributes .= sprintf( ' height="%s"', esc_attr( $args['height'] ) );
	}
	if ( $args['sizes'] ) {
		$attributes .= sprintf( ' sizes="%s"', esc_attr( $args['sizes'] ) );
	}

	return '<img ' . $attributes . ' />';
}

/**
 * Background image CSS value for a media key.
 *
 * @param string $key Media key.
 * @return string
 */
function mw_image_css( $key ) {
	$url = mw_image_url( $key );
	return $url ? sprintf( 'background-image:url(%s);', esc_url_raw( $url ) ) : '';
}

/**
 * Download every image in the map into the Media Library.
 *
 * Runs on the WordPress site (which has outbound internet access), not in the
 * build environment. Safe to re-run: existing downloads are skipped.
 *
 * @param bool $force Re-download even if the key already has an attachment.
 * @return array Summary: imported, skipped, failed, map of key => attachment ID.
 */
function mw_import_images( $force = false ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachments = get_option( 'mw_media_attachments', array() );
	$attachments = is_array( $attachments ) ? $attachments : array();

	$summary = array(
		'imported' => 0,
		'skipped'  => 0,
		'failed'   => 0,
		'errors'   => array(),
	);

	foreach ( mw_media_map() as $key => $image ) {
		if ( ! $force && ! empty( $attachments[ $key ] ) && get_post( $attachments[ $key ] ) ) {
			$summary['skipped']++;
			continue;
		}

		$attachment_id = mw_sideload_image( $image['url'], $key, isset( $image['alt'] ) ? $image['alt'] : '' );

		if ( is_wp_error( $attachment_id ) ) {
			$summary['failed']++;
			/* translators: 1: image key, 2: error message. */
			$summary['errors'][] = sprintf( __( '%1$s: %2$s', 'maison-woodcraft' ), $key, $attachment_id->get_error_message() );
			continue;
		}

		$attachments[ $key ] = (int) $attachment_id;
		$summary['imported']++;
	}

	update_option( 'mw_media_attachments', $attachments );
	$summary['attachments'] = $attachments;

	return $summary;
}

/**
 * Sideload a single remote image.
 *
 * @param string $url Remote image URL.
 * @param string $key Media key (used for the file name and alt text).
 * @param string $alt Alt text.
 * @return int|WP_Error Attachment ID or error.
 */
function mw_sideload_image( $url, $key, $alt = '' ) {
	$tmp = download_url( $url, 60 );

	if ( is_wp_error( $tmp ) ) {
		return $tmp;
	}

	$slug = sanitize_file_name( str_replace( '.', '-', $key ) );
	$name = $slug . '-maison-woodcraft.jpg';

	$file = array(
		'name'     => $name,
		'type'     => 'image/jpeg',
		'tmp_name' => $tmp,
		'error'    => 0,
		'size'     => filesize( $tmp ),
	);

	$attachment_id = media_handle_sideload( $file, 0, $alt, array( 'post_excerpt' => $alt ) );

	if ( is_wp_error( $attachment_id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return $attachment_id;
	}

	update_post_meta( $attachment_id, '_mw_media_key', $key );
	if ( $alt ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	}

	return $attachment_id;
}

/**
 * Replace original image URLs inside generated Elementor data with the local
 * attachment IDs once the images have been imported.
 *
 * @param string $json Elementor data JSON.
 * @return string
 */
function mw_localize_elementor_images( $json ) {
	$attachments = get_option( 'mw_media_attachments', array() );
	if ( empty( $attachments ) || ! is_array( $attachments ) ) {
		return $json;
	}

	$data = json_decode( $json, true );
	if ( ! is_array( $data ) ) {
		return $json;
	}

	$data = mw_walk_elementor_images( $data, $attachments );

	return wp_json_encode( $data );
}

/**
 * Localise the images inside every stored Elementor document.
 *
 * Used after the media import so pages that were built while the images were
 * still remote (or built by hand) point at the local Media Library files.
 *
 * @return int Number of documents updated.
 */
function mw_localize_elementor_posts() {
	global $wpdb;

	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s",
			'_elementor_data'
		)
	);

	if ( empty( $rows ) ) {
		return 0;
	}

	$updated = 0;

	foreach ( $rows as $row ) {
		$json = is_string( $row->meta_value ) ? $row->meta_value : '';

		if ( '' === $json ) {
			continue;
		}

		$localized = mw_localize_elementor_images( $json );

		if ( $localized === $json ) {
			continue;
		}

		update_post_meta( (int) $row->post_id, '_elementor_data', wp_slash( $localized ) );
		$updated++;
	}

	if ( $updated ) {
		mw_demo_clear_elementor_cache();
	}

	return $updated;
}

/**
 * Recursively swap original image URLs for imported attachment data inside a
 * decoded Elementor element tree.
 *
 * @param array $elements    Elementor elements.
 * @param array $attachments Media key => attachment ID.
 * @return array
 */
function mw_walk_elementor_images( $elements, $attachments ) {
	foreach ( $elements as $index => $element ) {
		if ( ! is_array( $element ) ) {
			continue;
		}

		if ( isset( $element['settings'] ) && is_array( $element['settings'] ) ) {
			$elements[ $index ]['settings'] = mw_localize_image_settings( $element['settings'], $attachments );
		}

		if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
			$elements[ $index ]['elements'] = mw_walk_elementor_images( $element['elements'], $attachments );
		}
	}

	return $elements;
}

/**
 * Swap any image control value that points at one of the original image URLs.
 *
 * @param array $settings    Element settings.
 * @param array $attachments Media key => attachment ID.
 * @return array
 */
function mw_localize_image_settings( $settings, $attachments ) {
	foreach ( $settings as $key => $value ) {
		if ( is_array( $value ) && isset( $value['url'] ) && is_string( $value['url'] ) ) {
			$media_key = mw_media_key_for_url( $value['url'] );
			if ( $media_key && ! empty( $attachments[ $media_key ] ) ) {
				$attachment_id = (int) $attachments[ $media_key ];
				$local_url     = wp_get_attachment_image_url( $attachment_id, 'full' );
				if ( $local_url ) {
					$settings[ $key ]['id']  = $attachment_id;
					$settings[ $key ]['url'] = $local_url;
				}
			}
			continue;
		}

		if ( is_array( $value ) ) {
			$settings[ $key ] = mw_localize_image_settings( $value, $attachments );
		}
	}

	return $settings;
}

/**
 * Reverse lookup: which media key does this URL belong to?
 *
 * @param string $url Image URL.
 * @return string|false
 */
function mw_media_key_for_url( $url ) {
	static $by_url = null;

	if ( null === $by_url ) {
		$by_url = array();
		foreach ( mw_media_map() as $key => $image ) {
			$by_url[ $image['url'] ] = $key;
		}
	}

	// Ignore query string differences.
	$normalized = strtok( $url, '?' );
	foreach ( $by_url as $mapped_url => $key ) {
		if ( 0 === strpos( $mapped_url, $normalized ) ) {
			return $key;
		}
	}

	return false;
}
