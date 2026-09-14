<?php
/**
 * FAQ widget — the accordion used on the Kitchens and Wardrobes pages.
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
 * Class MW_Faq_Widget
 */
class MW_Faq_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_faq';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'FAQ Accordion', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-accordion';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Questions', 'maison-woodcraft' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Questions', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'question',
						'label'   => __( 'Question', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'  => 'answer',
						'label' => __( 'Answer', 'maison-woodcraft' ),
						'type'  => Controls_Manager::TEXTAREA,
						'rows'  => 4,
					),
				),
				'title_field' => '{{{ question }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'open_first',
			array(
				'label'   => __( 'Open the first question by default', 'maison-woodcraft' ),
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
		$items    = $this->clean_rows( $settings['items'] );

		if ( empty( $items ) ) {
			$page  = is_page( 'wardrobes' ) ? 'wardrobes' : 'kitchens';
			$items = mw_page_copy( $page, 'faqs', array() );
		}

		$this->section(
			'faq',
			array(
				'items'      => $items,
				'open_first' => 'yes' === $settings['open_first'],
			)
		);
	}
}
