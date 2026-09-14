<?php
/**
 * Contact form widget — pages/Contact.tsx.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Contact_Form_Widget
 */
class MW_Contact_Form_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_contact_form';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Contact Form', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-vertical';
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
				'default'     => __( 'Send Us a Message', 'maison-woodcraft' ),
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
			'contact-form',
			array(
				'heading'        => $settings['heading'],
				'success_message' => $settings['success_message'],
			)
		);
	}
}
