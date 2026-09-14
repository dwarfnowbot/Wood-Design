<?php
/**
 * Project details widget — the fields of a single project (category, location,
 * materials, gallery). Drop it into an Elementor single-project template.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Project_Details_Widget
 */
class MW_Project_Details_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_project_details';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Project Details', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-info';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Details', 'maison-woodcraft' ) ) );

		$this->add_control(
			'show_meta',
			array(
				'label'   => __( 'Show the details panel', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_gallery',
			array(
				'label'   => __( 'Show the gallery', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'gallery_ids',
			array(
				'label'       => __( 'Override gallery (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::GALLERY,
				'description' => __( 'Leave empty to use the images attached to the project.', 'maison-woodcraft' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$post_id  = get_the_ID();

		$this->section(
			'project-details',
			array(
				'post_id'     => $post_id,
				'show_meta'   => 'yes' === $settings['show_meta'],
				'show_gallery' => 'yes' === $settings['show_gallery'],
				'gallery'     => ! empty( $settings['gallery_ids'] ) ? wp_list_pluck( $settings['gallery_ids'], 'id' ) : array(),
			)
		);
	}
}
