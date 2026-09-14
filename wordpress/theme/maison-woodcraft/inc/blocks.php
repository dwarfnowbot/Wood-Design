<?php
/**
 * Section renderers.
 *
 * Every visual block of the original website exists once as a template part in
 * /template-parts/sections. Both worlds use them:
 *
 *   - the PHP page templates (so the theme renders the original site even
 *     before/without Elementor), and
 *   - the Elementor widgets shipped by the theme (so the same markup, and
 *     therefore the same design, is produced when editing in Elementor).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a section template part.
 *
 * @param string $section Section slug (file name without extension).
 * @param array  $args    Arguments made available to the template as $args.
 * @param bool   $echo    Whether to echo or return the markup.
 * @return string|void
 */
function mw_render( $section, $args = array(), $echo = true ) {
	$candidates = array(
		'template-parts/sections/' . $section . '.php',
		'template-parts/forms/' . $section . '.php',
		'template-parts/' . $section . '.php',
	);

	$file = '';

	foreach ( $candidates as $candidate ) {
		$located = locate_template( $candidate );

		if ( $located ) {
			$file = $located;
			break;
		}
	}

	if ( ! $file ) {
		return $echo ? null : '';
	}

	if ( ! $echo ) {
		ob_start();
	}

	load_template( $file, false, $args );

	if ( ! $echo ) {
		return ob_get_clean();
	}
}

/**
 * Read an argument with a default.
 *
 * @param array  $args    Arguments.
 * @param string $key     Key.
 * @param mixed  $default Default.
 * @return mixed
 */
function mw_arg( $args, $key, $default = '' ) {
	return isset( $args[ $key ] ) && '' !== $args[ $key ] ? $args[ $key ] : $default;
}

/**
 * Normalise an image argument: returns array( url, alt, id ).
 *
 * Accepts either a media key ("kitchen.1") or a full URL, and also the array
 * Elementor image controls return.
 *
 * @param mixed  $value   Image value.
 * @param string $alt     Fallback alt text.
 * @return array
 */
function mw_resolve_image( $value, $alt = '' ) {
	$result = array(
		'url' => '',
		'alt' => $alt,
		'id'  => 0,
	);

	if ( is_array( $value ) ) {
		if ( ! empty( $value['url'] ) ) {
			$result['url'] = $value['url'];
		}
		if ( ! empty( $value['alt'] ) ) {
			$result['alt'] = $value['alt'];
		}
		if ( ! empty( $value['id'] ) ) {
			$result['id'] = (int) $value['id'];
		}
		return $result;
	}

	if ( ! is_string( $value ) || '' === $value ) {
		return $result;
	}

	$attachment_id = mw_attachment_id_for_key( $value );
	if ( $attachment_id ) {
		$result['id']  = $attachment_id;
		$result['url'] = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( ! $result['alt'] ) {
			$result['alt'] = mw_image_alt( $value );
		}
		return $result;
	}

	$image = mw_image( $value );
	if ( $image ) {
		$result['url'] = $image['url'];
		if ( ! $result['alt'] ) {
			$result['alt'] = $image['alt'];
		}
		return $result;
	}

	$result['url'] = $value;

	return $result;
}

/**
 * Print an <img> for a resolved image (or the original remote URL).
 *
 * @param mixed  $value     Image value (key, URL or Elementor array).
 * @param string $class     CSS class.
 * @param string $alt       Fallback alt text.
 * @param array  $extra     loading, sizes, width, height, fetchpriority.
 * @return string
 */
function mw_image_html( $value, $class = '', $alt = '', $extra = array() ) {
	$image = mw_resolve_image( $value, $alt );

	if ( ! $image['url'] ) {
		return '';
	}

	$defaults = array(
		'loading' => 'lazy',
		'sizes'   => '(max-width: 1023px) 100vw, 50vw',
		'width'   => '',
		'height'  => '',
		'priority' => false,
	);
	$extra = wp_parse_args( $extra, $defaults );

	if ( $image['id'] ) {
		$html = wp_get_attachment_image(
			$image['id'],
			'large',
			false,
			array(
				'class'   => trim( $class ),
				'alt'     => $image['alt'],
				'loading' => $extra['loading'],
				'sizes'   => $extra['sizes'],
			)
		);
		if ( $html ) {
			return $html;
		}
	}

	$attributes = sprintf(
		'src="%s" alt="%s" loading="%s" decoding="async"',
		esc_url( $image['url'] ),
		esc_attr( $image['alt'] ),
		esc_attr( $extra['loading'] )
	);

	if ( $class ) {
		$attributes .= sprintf( ' class="%s"', esc_attr( $class ) );
	}
	if ( $extra['width'] ) {
		$attributes .= sprintf( ' width="%s"', esc_attr( $extra['width'] ) );
	}
	if ( $extra['height'] ) {
		$attributes .= sprintf( ' height="%s"', esc_attr( $extra['height'] ) );
	}
	if ( $extra['sizes'] ) {
		$attributes .= sprintf( ' sizes="%s"', esc_attr( $extra['sizes'] ) );
	}
	if ( $extra['priority'] ) {
		$attributes .= ' fetchpriority="high"';
	}

	return '<img ' . $attributes . ' />';
}
