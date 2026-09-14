<?php
/**
 * CTA section widget — components/CTASection.tsx (espresso panel + two buttons).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/*
 * The base class only exists while Elementor's Widget_Base is loaded. Bail out
 * (instead of defining a class that would be half-built) if that is not the
 * case — the widget registration code skips missing classes.
 */
if ( ! class_exists( 'MW_Widget_Base' ) ) {
	return;
}

/**
 * Class MW_Cta_Widget
 */
class MW_Cta_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_cta';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'CTA Section', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Call to action', 'maison-woodcraft' ) ) );

		$this->add_control(
			'eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Start Your Project', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'label_block' => true,
				'default'     => __( 'Let’s design something made for your home', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Description', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'buttons',
			array(
				'label'       => __( 'Buttons', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'label',
						'label'   => __( 'Label', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => __( 'Request a Quote', 'maison-woodcraft' ),
					),
					array(
						'name'  => 'url',
						'label' => __( 'Link', 'maison-woodcraft' ),
						'type'  => Controls_Manager::URL,
					),
					array(
						'name'    => 'variant',
						'label'   => __( 'Style', 'maison-woodcraft' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'ghost',
						'options' => array(
							'ghost'     => __( 'Bronze (primary)', 'maison-woodcraft' ),
							'primary'   => __( 'Espresso', 'maison-woodcraft' ),
							'whatsapp'  => __( 'WhatsApp green', 'maison-woodcraft' ),
							'secondary' => __( 'Ivory outline', 'maison-woodcraft' ),
						),
					),
				),
				'title_field' => '{{{ label }}}',
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
		$buttons  = array();

		foreach ( $this->clean_rows( $settings['buttons'] ) as $row ) {
			$buttons[] = array(
				'label'   => $row['label'],
				'url'     => isset( $row['url']['url'] ) ? $row['url']['url'] : '',
				'variant' => $row['variant'],
				'new_tab' => ! empty( $row['url']['is_external'] ),
			);
		}

		$this->section(
			'cta',
			array(
				'eyebrow' => $settings['eyebrow'],
				'heading' => $settings['heading'],
				'text'    => $settings['text'],
				'buttons' => $buttons,
			)
		);
	}
}
