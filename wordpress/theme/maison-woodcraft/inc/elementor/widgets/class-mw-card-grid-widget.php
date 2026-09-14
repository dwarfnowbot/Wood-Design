<?php
/**
 * Image card grid widget — Kitchen Styles, Wardrobe Types, Interior Woodwork
 * categories (the repeating card shells in the original pages).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Card_Grid_Widget
 */
class MW_Card_Grid_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_card_grid';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Image Card Grid', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Cards', 'maison-woodcraft' ) ) );

		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Card size', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style',
				'options' => array(
					'style'    => __( 'Large (Kitchen styles / Wardrobe types)', 'maison-woodcraft' ),
					'category' => __( 'Compact (Woodwork categories)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'   => __( 'Link label (optional)', 'maison-woodcraft' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'maison-woodcraft' ),
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
						'default' => __( 'Modern Kitchens', 'maison-woodcraft' ),
					),
					array(
						'name'  => 'text',
						'label' => __( 'Description', 'maison-woodcraft' ),
						'type'  => Controls_Manager::TEXTAREA,
						'rows'  => 3,
					),
					array(
						'name'  => 'url',
						'label' => __( 'Link (optional)', 'maison-woodcraft' ),
						'type'  => Controls_Manager::URL,
					),
				),
				'title_field' => '{{{ title }}}',
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
			$items[] = array(
				'image'       => isset( $row['image']['url'] ) ? $row['image']['url'] : '',
				'title'       => $row['title'],
				'description' => $row['text'],
				'url'         => isset( $row['url']['url'] ) ? $row['url']['url'] : '',
			);
		}

		$this->section(
			'card-grid',
			array(
				'items'      => $items,
				'variant'    => $settings['variant'],
				'link_label' => $settings['link_label'],
			)
		);
	}
}
