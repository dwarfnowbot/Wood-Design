<?php
/**
 * Quote form widget — pages/GetQuote.tsx. Renders the working form (entries are
 * stored in WordPress and emailed), with every label editable in Elementor.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Quote_Form_Widget
 */
class MW_Quote_Form_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_quote_form';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Quote Request Form', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Form', 'maison-woodcraft' ) ) );

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Tell Us About Your Project', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'success_message',
			array(
				'label'       => __( 'Success message', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'label_block' => true,
				'default'     => '',
				'description' => __( 'Leave empty to use the message from Customize → Maison Woodcraft → Forms.', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'show_details',
			array(
				'label'   => __( 'Show project details fields', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_upload',
			array(
				'label'   => __( 'Show reference image upload', 'maison-woodcraft' ),
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

		$this->section(
			'quote-form',
			array(
				'heading'        => $settings['heading'],
				'description'    => $settings['description'],
				'success_message' => $settings['success_message'],
				'show_details'   => 'yes' === $settings['show_details'],
				'show_upload'    => 'yes' === $settings['show_upload'],
			)
		);
	}
}
