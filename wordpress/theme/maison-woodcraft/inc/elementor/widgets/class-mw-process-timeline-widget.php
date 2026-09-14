<?php
/**
 * Process timeline widget — components/ProcessTimeline.tsx.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Process_Timeline_Widget
 */
class MW_Process_Timeline_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_process_timeline';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Process Timeline', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Steps', 'maison-woodcraft' ) ) );

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'Horizontal steps (home, kitchens, wardrobes)', 'maison-woodcraft' ),
					'vertical'   => __( 'Vertical timeline (process page)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Steps', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'number',
						'label'   => __( 'Step number label', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '01',
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
				'title_field' => '{{{ number }}} — {{{ title }}}',
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
		$items    = $this->clean_rows( $settings['items'] );

		if ( empty( $items ) ) {
			$items = mw_process_steps();
		}

		$this->section(
			'process-timeline',
			array(
				'steps'  => $items,
				'layout' => $settings['layout'],
			)
		);
	}
}
