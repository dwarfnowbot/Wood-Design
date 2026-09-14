<?php
/**
 * Feature grid widget — "Why Choose Us" / value cards with an icon.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Feature_Grid_Widget
 */
class MW_Feature_Grid_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_feature_grid';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Feature Grid', 'maison-woodcraft' );
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
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Features', 'maison-woodcraft' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Features', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'step',
						'label'   => __( 'Number / eyebrow', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'    => 'title',
						'label'   => __( 'Title', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'  => 'description',
						'label' => __( 'Description', 'maison-woodcraft' ),
						'type'  => Controls_Manager::TEXTAREA,
						'rows'  => 4,
					),
				),
				'title_field' => '{{{ title }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'three',
				'options' => array(
					'two'   => __( '2 columns (original: Why Choose Us)', 'maison-woodcraft' ),
					'three' => __( '3 columns', 'maison-woodcraft' ),
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
			$items = mw_why_choose_us();
		}

		$this->section(
			'feature-grid',
			array(
				'items'   => $items,
				'columns' => $settings['columns'],
			)
		);
	}
}
