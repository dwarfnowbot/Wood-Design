<?php
/**
 * Theme setup.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports and navigation menus.
 */
function mw_setup() {
	load_theme_textdomain( 'maison-woodcraft', MW_THEME_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	/*
	 * Elementor: declare the theme's support for Elementor locations so that
	 * Elementor Pro's Theme Builder can replace the header/footer/single/archive
	 * templates. See inc/elementor/elementor.php.
	 */
	add_theme_support( 'elementor-page-title' );

	/* Image sizes that match the original design crops. */
	add_image_size( 'mw-card', 1200, 900, true );      // Card media (h-64/h-72 crops).
	add_image_size( 'mw-portrait', 1200, 1500, true );  // aspect-[4/5] frames.
	add_image_size( 'mw-hero', 1800, 1200, true );      // Full-bleed heroes.
	add_image_size( 'mw-square', 900, 900, true );      // Gallery tiles.

	register_nav_menus(
		array(
			'primary'            => __( 'Primary Menu (header)', 'maison-woodcraft' ),
			'footer_quick_links' => __( 'Footer — Quick Links', 'maison-woodcraft' ),
			'footer_services'    => __( 'Footer — Services', 'maison-woodcraft' ),
		)
	);

	/* Keep the generated markup close to the original, class-light output. */
	add_filter( 'nav_menu_css_class', 'mw_nav_menu_css_class', 10, 2 );
	add_filter( 'nav_menu_link_attributes', 'mw_nav_menu_link_attributes', 10, 2 );
}
add_action( 'after_setup_theme', 'mw_setup' );

/**
 * Add the original design's classes to menu items instead of the default list.
 *
 * @param string[] $classes Menu item classes.
 * @param WP_Post  $item    Menu item.
 * @return string[]
 */
function mw_nav_menu_css_class( $classes, $item ) {
	$classes = array_diff( $classes, array( 'menu-item', 'page_item' ) );
	$classes[] = 'mw-nav__item';
	if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
		$classes[] = 'is-active';
	}
	return $classes;
}

/**
 * Add the original nav link class to primary menu anchors.
 *
 * @param array   $atts Anchor attributes.
 * @param WP_Post $item Menu item.
 * @return array
 */
function mw_nav_menu_link_attributes( $atts, $item ) {
	if ( ! empty( $atts['class'] ) ) {
		$atts['class'] .= ' mw-nav__link';
	} else {
		$atts['class'] = 'mw-nav__link';
	}

	return $atts;
}

/**
 * Body classes that drive the original layout behaviour.
 *
 * `mw-header-over-hero` reproduces Header.tsx: the header is transparent only
 * on the home page (where a full-height hero sits behind it).
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function mw_body_class( $classes ) {
	$classes[] = 'mw-page';

	if ( mw_is_front_page() && ! mw_has_elementor_location( 'header' ) ) {
		$classes[] = 'mw-header-over-hero';
	}

	if ( ! mw_page_uses_elementor() ) {
		$classes[] = 'mw-classic-content';
	}

	if ( is_singular( 'mw_project' ) ) {
		$classes[] = 'mw-single-project';
	}

	return $classes;
}
add_filter( 'body_class', 'mw_body_class' );

/**
 * Whether the current request is the site front page.
 *
 * @return bool
 */
function mw_is_front_page() {
	return ( is_front_page() || is_home() ) && ! is_paged();
}

/**
 * Whether a page is built with Elementor data.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function mw_page_uses_elementor( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return false;
	}

	$edit_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );
	$data      = get_post_meta( $post_id, '_elementor_data', true );

	return ( 'builder' === $edit_mode && ! empty( $data ) );
}

/**
 * Title tag parts — brand name first, matching the original <title>.
 *
 * @param array $parts Title parts.
 * @return array
 */
function mw_document_title_parts( $parts ) {
	$brand = mw_option( 'brand_name', mw_content( 'site.brandName', 'Maison Woodcraft' ) );

	if ( mw_is_front_page() ) {
		$parts['title'] = $brand . ' | ' . mw_option( 'home_title_suffix', 'Custom Kitchens & Home Woodwork, Lahore' );
	} else {
		unset( $parts['tagline'] );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'mw_document_title_parts' );

/**
 * The original index.html meta description, reused for the front page.
 */
function mw_meta_description() {
	if ( ! mw_is_front_page() ) {
		return;
	}
	$description = mw_option(
		'meta_description',
		'Maison Woodcraft designs and builds custom kitchens, wardrobes and complete home woodwork for homeowners in Lahore. Book a consultation for your next project.'
	);
	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}
}
add_action( 'wp_head', 'mw_meta_description', 1 );

/**
 * Keep the front page a single page: WordPress turns /page/2/ into the blog
 * index, which the original site does not have.
 *
 * @param WP_Query $query Main query.
 */
function mw_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() && ! $query->is_front_page() ) {
		$query->set( 'posts_per_page', 9 );
	}

	if ( $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page', 'mw_project' ) );
	}
}
add_action( 'pre_get_posts', 'mw_pre_get_posts' );
