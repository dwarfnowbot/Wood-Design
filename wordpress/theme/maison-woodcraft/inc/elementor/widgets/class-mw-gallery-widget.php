<?php
/**
 * Gallery grid widget — the materials / woodwork galleries (with a lightbox).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Gallery_Widget
 */
class MW_Gallery_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_gallery';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Gallery Grid', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Gallery', 'maison-woodcraft' ) ) );

		$this->add_control(
			'images',
			array(
				'label' => __( 'Images', 'maison-woodcraft' ),
				'type'  => Controls_Manager::GALLERY,
			)
		);

		$this->add_control(
			'feature_first',
			array(
				'label'   => __( 'Make the first image a large tile', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'label'   => __( 'Open in a lightbox', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = array();

		foreach ( (array) $settings['images'] as $image ) {
			$items[] = array(
				'id'  => isset( $image['id'] ) ? $image['id'] : 0,
				'url' => isset( $image['url'] ) ? $image['url'] : '',
				'alt' => isset( $image['alt'] ) ? $image['alt'] : '',
			);
		}

		$this->section(
			'gallery-grid',
			array(
				'items'         => $items,
				'feature_first' => 'yes' === $settings['feature_first'],
				'lightbox'      => 'yes' === $settings['lightbox'],
			)
		);
	}
}
