<?php
/**
 * Maison Woodcraft — theme bootstrap.
 *
 * The theme is a 1:1 conversion of the original React/Vite "Maison Woodcraft"
 * website into a WordPress + Elementor theme. Every section of the original
 * site is available both as a native PHP template (so the site works without
 * Elementor) and as an Elementor widget / editable layout blueprint.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

define( 'MW_THEME_VERSION', '1.0.0' );
define( 'MW_THEME_DIR', get_template_directory() );
define( 'MW_THEME_URI', get_template_directory_uri() );
define( 'MW_THEME_SLUG', 'maison-woodcraft' );

/**
 * Require a theme include.
 *
 * @param string $file Relative path inside /inc.
 */
function mw_require( $file ) {
	require_once MW_THEME_DIR . '/inc/' . $file;
}

/* -------------------------------------------------------------------------
 * Core
 * ---------------------------------------------------------------------- */
mw_require( 'setup.php' );          // Theme supports, menus, image sizes.
mw_require( 'enqueue.php' );        // Styles, scripts, fonts.
mw_require( 'content.php' );        // site-content.json loader + accessors.
mw_require( 'media.php' );          // Image library helpers.
mw_require( 'template-tags.php' );  // Buttons, icons, links, WhatsApp.
mw_require( 'blocks.php' );         // Section renderers (template parts loader).
mw_require( 'customizer.php' );     // Business details / options.
mw_require( 'post-types.php' );     // Projects CPT + taxonomy + meta.
mw_require( 'forms.php' );          // Working quote + contact forms.
mw_require( 'fallback-pages.php' ); // Native rendering of the original pages.

/* -------------------------------------------------------------------------
 * Integrations
 * ---------------------------------------------------------------------- */
mw_require( 'elementor/elementor.php' ); // Elementor / Elementor Pro support + widgets.
mw_require( 'demo/demo-importer.php' );  // One-click recreation of the original site.

if ( is_admin() ) {
	mw_require( 'admin.php' );
}

/**
 * Content width (kept for oEmbed and core blocks).
 */
function mw_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mw_content_width', 1280 );
}
add_action( 'after_setup_theme', 'mw_content_width', 0 );
