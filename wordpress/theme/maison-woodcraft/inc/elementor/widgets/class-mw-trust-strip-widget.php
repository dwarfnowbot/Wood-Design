<?php
/**
 * Trust strip widget — components/TrustStrip.tsx (Icons + points row).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Trust_Strip_Widget
 */
class MW_Trust_Strip_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_trust_strip';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Trust Strip', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Points', 'maison-woodcraft' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Points', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'text',
						'label'   => __( 'Text', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => __( 'Custom-Built Designs', 'maison-woodcraft' ),
					),
					array(
						'name'    => 'icon',
						'label'   => __( 'Icon', 'maison-woodcraft' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'trust-1',
						'options' => array(
							'trust-1' => __( 'House', 'maison-woodcraft' ),
							'trust-2' => __( 'Star', 'maison-woodcraft' ),
							'trust-3' => __( 'Craftsmanship', 'maison-woodcraft' ),
							'trust-4' => __( 'Installation', 'maison-woodcraft' ),
						),
					),
				),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'text' => 'Custom-Built Designs',
						'icon' => 'trust-1',
					),
					array(
						'text' => 'Quality Materials',
						'icon' => 'trust-2',
					),
					array(
						'text' => 'Precision Craftsmanship',
						'icon' => 'trust-3',
					),
					array(
						'text' => 'Professional Installation',
						'icon' => 'trust-4',
					),
				),
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
			$items = mw_trust_points();
		}

		$this->section( 'trust-strip', array( 'items' => $items ) );
	}
}
