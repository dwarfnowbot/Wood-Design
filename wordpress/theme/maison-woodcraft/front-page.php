<?php
/**
 * Front page — the original Home page.
 *
 * Priority order:
 *   1. an Elementor-built front page (the demo importer creates one),
 *   2. Elementor Pro's "single" location template if one is assigned,
 *   3. the theme's native Home recreation.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();

$mw_rendered = false;

if ( have_posts() ) {
	the_post();

	if ( mw_page_uses_elementor( get_the_ID() ) ) {
		the_content();
		$mw_rendered = true;
	} elseif ( mw_elementor_do_location( 'single' ) ) {
		$mw_rendered = true;
	}
}

if ( ! $mw_rendered ) {
	mw_render_fallback_page( 'home' );
}

get_footer();
