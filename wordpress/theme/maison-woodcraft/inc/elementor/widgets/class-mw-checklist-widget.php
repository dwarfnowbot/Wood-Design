<?php
/**
 * Checklist widget — the "Features Included" / "What's included" lists.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Checklist_Widget
 */
class MW_Checklist_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_checklist';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Checklist', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'List', 'maison-woodcraft' ) ) );

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Features Included', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'text',
						'label'   => __( 'Text', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXTAREA,
						'rows'    => 2,
						'default' => '',
					),
				),
				'title_field' => '{{{ text }}}',
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
		$items    = array();

		foreach ( $this->clean_rows( $settings['items'] ) as $row ) {
			$items[] = $row['text'];
		}

		$this->section(
			'checklist',
			array(
				'title' => $settings['title'],
				'items' => $items,
			)
		);
	}
}
