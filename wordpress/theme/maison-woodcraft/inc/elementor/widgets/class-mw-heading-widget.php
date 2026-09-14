<?php
/**
 * Section heading widget — components/SectionHeading.tsx.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Heading_Widget
 */
class MW_Heading_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_section_heading';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Section Heading', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-heading';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Heading', 'maison-woodcraft' ) ) );

		$this->add_control(
			'eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => __( 'Our Expertise', 'maison-woodcraft' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'   => __( 'HTML tag', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => __( 'Alignment', 'maison-woodcraft' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'maison-woodcraft' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'maison-woodcraft' ),
						'icon'  => 'eicon-text-align-center',
					),
				),
			)
		);

		$this->add_control(
			'light',
			array(
				'label'   => __( 'Light text (dark backgrounds)', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
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
			'heading',
			array(
				'eyebrow'     => $settings['eyebrow'],
				'heading'     => $settings['heading'],
				'description' => $settings['description'],
				'tag'         => $settings['tag'],
				'align'       => $settings['align'],
				'light'       => 'yes' === $settings['light'],
			)
		);
	}
}
