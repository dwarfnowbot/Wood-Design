<?php
/**
 * Elementor integration.
 *
 * The theme is built to work *with* Elementor rather than around it:
 *
 *   - Elementor can edit pages and the Projects post type,
 *   - Elementor Pro's Theme Builder can replace the header, footer, single and
 *     archive templates (the theme registers all core locations and calls
 *     elementor_theme_do_location()),
 *   - every original component exists as a native Elementor widget that renders
 *     the same markup as the PHP templates (see inc/elementor/widgets),
 *   - the theme's own header/footer are used as the fallback so the site looks
 *     complete before any template is assigned.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

require_once MW_THEME_DIR . '/inc/elementor/class-mw-widget-base.php';
require_once MW_THEME_DIR . '/inc/elementor/widgets-loader.php';

/**
 * Is Elementor active (and a supported version)?
 *
 * @return bool
 */
function mw_is_elementor_active() {
	return did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' );
}

/**
 * Declare Elementor compatibility.
 */
function mw_elementor_setup() {
	/*
	 * Location support: lets Elementor Pro's Theme Builder replace the theme's
	 * header, footer, single and archive templates.
	 */
	add_theme_support( 'elementor' );

	/* Keep Elementor's default page templates available. */
	add_theme_support( 'elementor-header-footer' );
}
add_action( 'after_setup_theme', 'mw_elementor_setup' );

/**
 * Register the theme's locations with Elementor Pro.
 *
 * @param object $manager Locations manager.
 */
function mw_register_elementor_locations( $manager ) {
	if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
		$manager->register_all_core_location();
	}
}
add_action( 'elementor/theme/register_locations', 'mw_register_elementor_locations' );

/**
 * Render an Elementor Pro location (header/footer/single/archive) if one is
 * assigned to the current page.
 *
 * @param string $location Location name.
 * @return bool Whether a template was rendered.
 */
function mw_elementor_do_location( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) ) {
		return (bool) elementor_theme_do_location( $location );
	}

	return false;
}

/**
 * Whether an Elementor Pro template exists for a location (used to decide
 * whether the theme should add its own header spacer).
 *
 * @param string $location Location name.
 * @return bool
 */
function mw_has_elementor_location( $location ) {
	if ( ! mw_is_elementor_active() || ! function_exists( 'elementor_theme_do_location' ) ) {
		return false;
	}

	if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
		return false;
	}

	try {
		$module     = \ElementorPro\Modules\ThemeBuilder\Module::instance();
		$conditions = method_exists( $module, 'get_conditions_manager' ) ? $module->get_conditions_manager() : null;

		if ( ! $conditions || ! method_exists( $conditions, 'get_documents_for_location' ) ) {
			return false;
		}

		$documents = $conditions->get_documents_for_location( $location );

		return ! empty( $documents );
	} catch ( \Throwable $e ) {
		return false;
	}
}

/* -------------------------------------------------------------------------
 * Elementor editor helpers
 * ---------------------------------------------------------------------- */

/**
 * Register the theme's widget category.
 *
 * @param object $elements_manager Elementor elements manager.
 */
function mw_elementor_widget_categories( $elements_manager ) {
	$elements_manager->add_category(
		'maison-woodcraft',
		array(
			'title' => __( 'Maison Woodcraft', 'maison-woodcraft' ),
			'icon'  => 'eicon-nerd',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'mw_elementor_widget_categories' );

/**
 * The theme's design tokens, exposed to Elementor's global settings.
 *
 * These values are the original project's Tailwind palette (index.css). They are
 * offered as the theme's default kit values; the importable Elementor kit in
 * wordpress/elementor-templates applies them globally.
 *
 * @return array
 */
function mw_design_tokens() {
	return array(
		'colors' => array(
			'ivory'         => '#f7f3ec',
			'ivory-dark'    => '#efe8db',
			'stone'         => '#ece4d6',
			'stone-dark'    => '#ddd2bd',
			'espresso'      => '#2b241d',
			'espresso-light' => '#4a4038',
			'charcoal'      => '#2a2a28',
			'walnut'        => '#6b4a34',
			'walnut-dark'   => '#4f3626',
			'bronze'        => '#a9814f',
			'bronze-light'  => '#c9a877',
			'champagne'     => '#d9c6a5',
			'whatsapp'      => '#25d366',
		),
		'fonts'  => array(
			'serif' => array(
				'family' => 'Cormorant Garamond',
				'stack'  => '"Cormorant Garamond", "Georgia", serif',
			),
			'sans'  => array(
				'family' => 'Jost',
				'stack'  => '"Jost", "Helvetica Neue", Arial, sans-serif',
			),
		),
		'layout' => array(
			'container' => 1280,
			'radius'    => array( 'xl' => 12, '2xl' => 16, 'pill' => 999 ),
		),
	);
}

/**
 * Enqueue a small stylesheet in the editor so the theme's own widgets look
 * right while editing.
 */
function mw_elementor_editor_assets() {
	wp_enqueue_style( 'mw-components-editor', MW_THEME_URI . '/assets/css/components.css', array(), MW_THEME_VERSION );
}
add_action( 'elementor/editor/after_enqueue_styles', 'mw_elementor_editor_assets' );

/**
 * Make sure the Pages list shows which pages are built with Elementor (handy
 * right after importing the demo content).
 *
 * @param array   $columns Columns.
 * @param WP_Post $post    Post.
 * @return array
 */
function mw_pages_columns_editor( $columns ) {
	$columns['mw_elementor'] = __( 'Elementor', 'maison-woodcraft' );
	return $columns;
}
add_filter( 'manage_pages_columns', 'mw_pages_columns_editor' );

/**
 * Render the Elementor column.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function mw_pages_column_editor( $column, $post_id ) {
	if ( 'mw_elementor' !== $column ) {
		return;
	}

	echo mw_page_uses_elementor( $post_id )
		? '<span class="dashicons dashicons-yes-alt" style="color:#1e7c3a"></span>'
		: '<span class="dashicons dashicons-minus" style="color:#a7aaad"></span>';
}
add_action( 'manage_pages_custom_column', 'mw_pages_column_editor', 10, 2 );
