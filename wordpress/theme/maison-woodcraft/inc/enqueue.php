<?php
/**
 * Assets.
 *
 * The original site loaded two Google fonts (Cormorant Garamond + Jost) and a
 * single Tailwind stylesheet. The theme mirrors that with one stylesheet, a
 * small vanilla-JS file and the same two font families.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end styles and scripts.
 */
function mw_enqueue_assets() {
	$version = MW_THEME_VERSION;
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$version = (string) filemtime( MW_THEME_DIR . '/style.css' );
	}

	/* Fonts — identical families/weights to the original project. */
	$fonts = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,500&family=Jost:wght@300;400;500;600;700&display=swap';

	wp_enqueue_style( 'mw-fonts', $fonts, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URL.
	wp_enqueue_style( 'mw-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_style( 'mw-components', MW_THEME_URI . '/assets/css/components.css', array( 'mw-style' ), $version );

	wp_enqueue_script( 'mw-theme', MW_THEME_URI . '/assets/js/theme.js', array(), $version, true );

	wp_localize_script(
		'mw-theme',
		'mwTheme',
		array(
			'restUrl'  => esc_url_raw( rest_url( 'mw/v1/form' ) ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'homeUrl'  => home_url( '/' ),
			'isHome'   => mw_is_front_page() ? '1' : '0',
			'i18n'     => array(
				'sending' => __( 'Sending…', 'maison-woodcraft' ),
				'error'   => __( 'Something went wrong. Please try again.', 'maison-woodcraft' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mw_enqueue_assets' );

/**
 * Preconnect to Google Fonts (as in the original index.html).
 *
 * @param array  $urls          Resource hints.
 * @param string $relation_type Relation type.
 * @return array
 */
function mw_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'mw_resource_hints', 10, 2 );

/**
 * Admin styles for the entries screens and the theme settings page.
 *
 * @param string $hook Current admin page.
 */
function mw_admin_assets( $hook ) {
	wp_enqueue_style( 'mw-admin', MW_THEME_URI . '/assets/css/admin.css', array(), MW_THEME_VERSION );
}
add_action( 'admin_enqueue_scripts', 'mw_admin_assets' );
