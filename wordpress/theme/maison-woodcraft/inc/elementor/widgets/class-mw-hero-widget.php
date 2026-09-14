<?php
/**
 * Hero widget — the original home hero and interior page heroes.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Hero_Widget
 */
class MW_Hero_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_hero';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Hero (Home / Page)', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-rollover';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'mw_content',
			array( 'label' => __( 'Hero', 'maison-woodcraft' ) )
		);

		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Style', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'page',
				'options' => array(
					'page' => __( 'Interior page (60vh, bottom aligned)', 'maison-woodcraft' ),
					'home' => __( 'Home (full screen)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'height',
			array(
				'label'     => __( 'Height', 'maison-woodcraft' ),
				'type'      => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => __( 'Default (60vh / 420px min)', 'maison-woodcraft' ),
					'short'   => __( 'Short (50vh / 380px min)', 'maison-woodcraft' ),
					'compact' => __( 'Compact (46vh / 340px min)', 'maison-woodcraft' ),
				),
				'condition' => array( 'variant' => 'page' ),
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Title', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 2,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Description', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Background image', 'maison-woodcraft' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'alt',
			array(
				'label'       => __( 'Image alt text', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
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
						'default' => __( 'Book a Consultation', 'maison-woodcraft' ),
					),
					array(
						'name'        => 'url',
						'label'       => __( 'Link', 'maison-woodcraft' ),
						'type'        => Controls_Manager::URL,
						'default'     => array( 'url' => '' ),
						'placeholder' => __( 'https://your-link.com — or a page path like /get-a-quote', 'maison-woodcraft' ),
					),
					array(
						'name'    => 'variant',
						'label'   => __( 'Style', 'maison-woodcraft' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'ghost',
						'options' => array(
							'ghost'     => __( 'Bronze (primary)', 'maison-woodcraft' ),
							'primary'   => __( 'Espresso', 'maison-woodcraft' ),
							'secondary' => __( 'Ivory outline', 'maison-woodcraft' ),
							'outline'   => __( 'Dark outline', 'maison-woodcraft' ),
						),
					),
				),
				'title_field' => '{{{ label }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'whatsapp',
			array(
				'label'   => __( 'Show WhatsApp button', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'whatsapp_message',
			array(
				'label'       => __( 'WhatsApp message', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => array( 'whatsapp' => 'yes' ),
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

		foreach ( (array) $settings['buttons'] as $row ) {
			$buttons[] = array(
				'label'   => $row['label'],
				'url'     => isset( $row['url']['url'] ) ? $row['url']['url'] : '',
				'variant' => $row['variant'],
				'new_tab' => ! empty( $row['url']['is_external'] ),
			);
		}

		$this->section(
			'hero',
			array(
				'variant'   => $settings['variant'],
				'height'    => $settings['height'],
				'eyebrow'   => $settings['eyebrow'],
				'title'     => $settings['heading'],
				'text'      => $settings['text'],
				'image'     => $this->image_arg( $settings ),
				'alt'       => $settings['alt'],
				'buttons'   => $buttons,
				'whatsapp'  => 'yes' === $settings['whatsapp'],
				'whatsapp_message' => $settings['whatsapp_message'],
			)
		);
	}
}
