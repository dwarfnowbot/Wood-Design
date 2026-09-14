<?php
/**
 * Contact panels widget — the Contact page info cards (details, hours, map,
 * socials) plus the WhatsApp row.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Contact_Panels_Widget
 */
class MW_Contact_Panels_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_contact_panels';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Contact Details & Map', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Contact details', 'maison-woodcraft' ) ) );

		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Style', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'contact',
				'options' => array(
					'contact' => __( 'Contact page (details, hours, socials, map)', 'maison-woodcraft' ),
					'quote'   => __( 'Get a Quote aside (phone, email, hours)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'details',
			array(
				'label'   => __( 'Show the contact details panel', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'hours',
			array(
				'label'   => __( 'Show the opening hours panel', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'social',
			array(
				'label'     => __( 'Show the social links panel', 'maison-woodcraft' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'variant' => 'contact' ),
			)
		);

		$this->add_control(
			'map',
			array(
				'label'     => __( 'Show the map', 'maison-woodcraft' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'variant' => 'contact' ),
				'description' => __( 'Uses the Google Maps embed address from Customize → Maison Woodcraft → Contact.', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'whatsapp',
			array(
				'label'   => __( 'Show the WhatsApp button', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'whatsapp_message',
			array(
				'label'       => __( 'WhatsApp message', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
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
			'contact-panels',
			array(
				'variant'          => $settings['variant'],
				'details'          => 'yes' === $settings['details'],
				'hours'            => 'yes' === $settings['hours'],
				'social'           => 'yes' === $settings['social'],
				'map'              => 'yes' === $settings['map'],
				'whatsapp'         => 'yes' === $settings['whatsapp'],
				'whatsapp_message' => $settings['whatsapp_message'],
			)
		);
	}
}
