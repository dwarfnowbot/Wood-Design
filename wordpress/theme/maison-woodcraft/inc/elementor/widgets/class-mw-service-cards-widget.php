<?php
/**
 * Service cards widget — components/ServiceCard.tsx.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Service_Cards_Widget
 */
class MW_Service_Cards_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_service_cards';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Service Cards', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Cards', 'maison-woodcraft' ) ) );

		$this->add_control(
			'link_label',
			array(
				'label'   => __( 'Link label', 'maison-woodcraft' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Explore Service', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Cards', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'site',
				'options' => array(
					'site'   => __( 'The six services from the original site', 'maison-woodcraft' ),
					'custom' => __( 'Custom cards (edit below)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Custom cards', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'  => 'image',
						'label' => __( 'Image', 'maison-woodcraft' ),
						'type'  => Controls_Manager::MEDIA,
					),
					array(
						'name'    => 'title',
						'label'   => __( 'Title', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'  => 'text',
						'label' => __( 'Description', 'maison-woodcraft' ),
						'type'  => Controls_Manager::TEXTAREA,
						'rows'  => 3,
					),
					array(
						'name'  => 'url',
						'label' => __( 'Link', 'maison-woodcraft' ),
						'type'  => Controls_Manager::URL,
					),
				),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'custom' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( 'custom' === $settings['source'] && ! empty( $settings['items'] ) ) {
			$items = array();
			foreach ( $this->clean_rows( $settings['items'] ) as $row ) {
				$items[] = array(
					'image' => isset( $row['image']['url'] ) ? $row['image']['url'] : '',
					'title' => $row['title'],
					'text'  => $row['text'],
					'url'   => isset( $row['url']['url'] ) ? $row['url']['url'] : '',
				);
			}
		} else {
			$items = mw_services();
		}

		$this->section(
			'service-grid',
			array(
				'items'      => $items,
				'link_label' => $settings['link_label'],
			)
		);
	}
}
