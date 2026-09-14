<?php
/**
 * Material swatches widget — the "Materials & Finishes" cards and the swatch
 * rows on the Kitchens / Wardrobes pages.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Swatches_Widget
 */
class MW_Swatches_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_swatches';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Material Swatches', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-swatch';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Swatches', 'maison-woodcraft' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Swatches', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'title',
						'label'   => __( 'Title', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'    => 'description',
						'label'   => __( 'Description', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXTAREA,
						'rows'    => 3,
						'default' => '',
					),
					array(
						'name'    => 'swatch_from',
						'label'   => __( 'Swatch colour (start)', 'maison-woodcraft' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#efe8db',
					),
					array(
						'name'    => 'swatch_to',
						'label'   => __( 'Swatch colour (end)', 'maison-woodcraft' ),
						'type'    => Controls_Manager::COLOR,
						'default' => '#b79c72',
					),
					array(
						'name'  => 'image',
						'label' => __( 'Image (instead of the colour swatch)', 'maison-woodcraft' ),
						'type'  => Controls_Manager::MEDIA,
					),
				),
				'title_field' => '{{{ title }}}',
				'default'     => array(),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = $this->clean_rows( $settings['items'] );

		if ( empty( $items ) ) {
			$items = mw_material_categories();
		}

		$this->section( 'swatches', array( 'items' => $items ) );
	}
}
