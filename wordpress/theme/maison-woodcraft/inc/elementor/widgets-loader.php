<?php
/**
 * Register the theme's Elementor widgets.
 *
 * Each widget renders the same template part as the PHP fallback, so a page
 * built in Elementor is visually identical to the original React page while
 * remaining fully editable (text, images, links, repeaters).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget class map: file => class.
 *
 * @return array
 */
function mw_elementor_widget_map() {
	return array(
		'class-mw-hero-widget.php'             => 'MW_Hero_Widget',
		'class-mw-heading-widget.php'          => 'MW_Heading_Widget',
		'class-mw-button-widget.php'           => 'MW_Button_Widget',
		'class-mw-image-widget.php'            => 'MW_Image_Widget',
		'class-mw-split-widget.php'            => 'MW_Split_Widget',
		'class-mw-trust-strip-widget.php'      => 'MW_Trust_Strip_Widget',
		'class-mw-service-cards-widget.php'    => 'MW_Service_Cards_Widget',
		'class-mw-card-grid-widget.php'        => 'MW_Card_Grid_Widget',
		'class-mw-feature-grid-widget.php'     => 'MW_Feature_Grid_Widget',
		'class-mw-checklist-widget.php'        => 'MW_Checklist_Widget',
		'class-mw-process-timeline-widget.php' => 'MW_Process_Timeline_Widget',
		'class-mw-testimonials-widget.php'     => 'MW_Testimonials_Widget',
		'class-mw-swatches-widget.php'         => 'MW_Swatches_Widget',
		'class-mw-gallery-widget.php'          => 'MW_Gallery_Widget',
		'class-mw-faq-widget.php'              => 'MW_Faq_Widget',
		'class-mw-cta-widget.php'              => 'MW_Cta_Widget',
		'class-mw-project-grid-widget.php'     => 'MW_Project_Grid_Widget',
		'class-mw-project-details-widget.php'  => 'MW_Project_Details_Widget',
		'class-mw-quote-form-widget.php'       => 'MW_Quote_Form_Widget',
		'class-mw-contact-form-widget.php'     => 'MW_Contact_Form_Widget',
		'class-mw-contact-panels-widget.php'   => 'MW_Contact_Panels_Widget',
	);
}

/**
 * Make sure the shared widget base class is loaded.
 *
 * `inc/elementor/class-mw-widget-base.php` returns early when Elementor's
 * Widget_Base is not available yet, and `require_once` will not run a file a
 * second time — so the class is declared here when needed.
 *
 * @return bool Whether MW_Widget_Base can be used.
 */
function mw_elementor_widget_base_ready() {
	if ( class_exists( 'MW_Widget_Base' ) ) {
		return true;
	}

	if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
		return false;
	}

	if ( ! class_exists( 'MW_Widget_Base' ) ) {
		// Elementor is loaded now: evaluate the base class definition.
		include MW_THEME_DIR . '/inc/elementor/class-mw-widget-base.php';
	}

	return class_exists( 'MW_Widget_Base' );
}

/**
 * Register the widgets with Elementor.
 *
 * @param object $widgets_manager Elementor widgets manager.
 */
function mw_register_elementor_widgets( $widgets_manager ) {
	/*
	 * The widget classes extend MW_Widget_Base, which only exists while
	 * Elementor's own Widget_Base is available. If the base class is missing the
	 * theme simply offers no widgets instead of dying with a "class not found"
	 * fatal error.
	 */
	if ( ! mw_elementor_widget_base_ready() ) {
		return;
	}

	foreach ( mw_elementor_widget_map() as $file => $class ) {
		$path = MW_THEME_DIR . '/inc/elementor/widgets/' . $file;

		if ( ! file_exists( $path ) ) {
			continue;
		}

		/*
		 * A single broken widget must never take the whole site down: if loading
		 * or registering one fails, the rest are still offered.
		 */
		try {
			require_once $path;

			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		} catch ( \Throwable $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'Maison Woodcraft: widget ' . $class . ' could not be registered — ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		}
	}
}
add_action( 'elementor/widgets/register', 'mw_register_elementor_widgets' );

/**
 * Elementor 3.4 and older used `elementor/widgets/widgets_registered`.
 *
 * @param object $widgets_manager Widgets manager.
 */
function mw_register_elementor_widgets_legacy( $widgets_manager ) {
	if ( ! mw_elementor_widget_base_ready() ) {
		return;
	}

	if ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
		foreach ( mw_elementor_widget_map() as $file => $class ) {
			$path = MW_THEME_DIR . '/inc/elementor/widgets/' . $file;

			if ( ! file_exists( $path ) ) {
				continue;
			}

			try {
				require_once $path;

				if ( class_exists( $class ) && method_exists( $widgets_manager, 'register_widget_type' ) ) {
					$widgets_manager->register_widget_type( new $class() );
				}
			} catch ( \Throwable $e ) {
				continue;
			}
		}
	}
}
add_action( 'elementor/widgets/widgets_registered', 'mw_register_elementor_widgets_legacy' );

/**
 * Load the widget base class before Elementor asks for widgets.
 */
function mw_elementor_preload() {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
		return;
	}
}
add_action( 'elementor/init', 'mw_elementor_preload' );
