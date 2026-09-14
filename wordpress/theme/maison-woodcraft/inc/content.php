<?php
/**
 * Content accessors.
 *
 * Everything the original website says lives in inc/content/site-content.json,
 * which is generated from the original project's data files (see
 * wordpress/tools/build-content.mjs). Reading it from one place keeps the PHP
 * templates, the Elementor widgets and the generated Elementor layouts in sync.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load and cache the extracted site content.
 *
 * @return array
 */
function mw_content_data() {
	static $data = null;

	if ( null === $data ) {
		$file = MW_THEME_DIR . '/inc/content/site-content.json';
		$json = file_exists( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
		$data = $json ? json_decode( $json, true ) : array();
		if ( ! is_array( $data ) ) {
			$data = array();
		}
	}

	return $data;
}

/**
 * Read a value from the content file using dot notation.
 *
 * mw_content( 'site.brandName' ), mw_content( 'kitchen.faqs' ), ...
 *
 * @param string $path    Dot separated path.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function mw_content( $path = null, $default = null ) {
	$data = mw_content_data();

	if ( null === $path || '' === $path ) {
		return $data;
	}

	$value = $data;
	foreach ( explode( '.', $path ) as $key ) {
		if ( is_array( $value ) && array_key_exists( $key, $value ) ) {
			$value = $value[ $key ];
			continue;
		}
		return $default;
	}

	return ( null === $value || array() === $value ) ? $default : $value;
}

/**
 * Copy for one of the original pages ( hero, section headings, CTA … ).
 *
 * @param string $page Original route slug, e.g. 'kitchens' or 'get-a-quote'.
 * @param string $key  Optional sub-key, e.g. 'hero.heading'.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function mw_page_copy( $page, $key = null, $default = null ) {
	$path = 'pages.' . $page . ( null !== $key && '' !== $key ? '.' . $key : '' );
	return mw_content( $path, $default );
}

/**
 * The six services shown on the front page and in the footer
 * (services.ts in the original project).
 *
 * @return array
 */
function mw_services() {
	return mw_content( 'services', array() );
}

/**
 * Sample project concepts (projects.ts in the original project).
 *
 * @return array
 */
function mw_projects() {
	return mw_content( 'projects', array() );
}

/**
 * Project categories (projectCategories in the original project).
 *
 * @return string[]
 */
function mw_project_categories() {
	return mw_content( 'projectCategories', array() );
}

/**
 * The five step process used on the home page and service pages.
 *
 * @return array
 */
function mw_process_steps() {
	return mw_content( 'process', array() );
}

/**
 * The ten step detailed process used on the Process page.
 *
 * @return array
 */
function mw_detailed_process_steps() {
	return mw_content( 'detailedProcess', array() );
}

/**
 * Why clients choose us (six items).
 *
 * @return array
 */
function mw_why_choose_us() {
	return mw_content( 'whyChooseUs', array() );
}

/**
 * Trust strip points used directly below the home hero.
 *
 * @return string[]
 */
function mw_trust_points() {
	return mw_content( 'trustPoints', array() );
}

/**
 * Sample testimonials.
 *
 * @return array
 */
function mw_testimonials() {
	return mw_content( 'testimonials', array() );
}

/**
 * Material / finish categories with their swatch gradient colours.
 *
 * The original stores Tailwind classes (e.g. `from-[#d8c9ab] to-[#b79c72]`);
 * these are converted to a plain two-colour gradient here.
 *
 * @return array
 */
function mw_material_categories() {
	$items = mw_content( 'materialCategories', array() );

	foreach ( $items as $index => $item ) {
		$swatch = isset( $item['swatchClass'] ) ? $item['swatchClass'] : '';
		$colors = array();

		if ( preg_match_all( '/#([0-9a-fA-F]{3,8})/', $swatch, $matches ) ) {
			foreach ( $matches[1] as $hex ) {
				$colors[] = '#' . $hex;
			}
		}

		$items[ $index ]['swatch_from'] = isset( $colors[0] ) ? $colors[0] : '#efe8db';
		$items[ $index ]['swatch_to']   = isset( $colors[1] ) ? $colors[1] : '#b79c72';
	}

	return $items;
}

/**
 * The two newsletter/legal strings the original site repeats.
 *
 * @return string
 */
function mw_materials_disclaimer() {
	return mw_content( 'materialsDisclaimer', '' );
}

/**
 * Form field definitions (labels, placeholders, option lists) for the quote and
 * contact forms, straight from the original components.
 *
 * @param string $form 'quote' or 'contact'.
 * @return array
 */
function mw_form_fields( $form = 'quote' ) {
	return mw_content( 'forms.' . $form . '.fields', array() );
}
