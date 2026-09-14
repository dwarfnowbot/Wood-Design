<?php
/**
 * Projects grid widget — components/ProjectCard.tsx + the Projects page filters
 * and "View Project" modal.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Project_Grid_Widget
 */
class MW_Project_Grid_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_project_grid';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Projects Grid', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-masonry';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Projects', 'maison-woodcraft' ) ) );

		$this->add_control(
			'source',
			array(
				'label'       => __( 'Source', 'maison-woodcraft' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cpt',
				'options'     => array(
					'cpt'    => __( 'Projects (imported content)', 'maison-woodcraft' ),
					'manual' => __( 'Custom cards (edit below)', 'maison-woodcraft' ),
				),
				'description' => __( 'Projects added under “Projects” in the WordPress admin appear here automatically.', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => __( 'Number of projects', 'maison-woodcraft' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'filters',
			array(
				'label'   => __( 'Show category filter buttons', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'   => __( 'Card link label', 'maison-woodcraft' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View Project', 'maison-woodcraft' ),
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
						'name'    => 'category',
						'label'   => __( 'Category', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => 'Kitchens',
					),
					array(
						'name'    => 'location',
						'label'   => __( 'Location', 'maison-woodcraft' ),
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
						'name'  => 'materials',
						'label' => __( 'Materials & Finishes', 'maison-woodcraft' ),
						'type'  => Controls_Manager::TEXT,
					),
					array(
						'name'  => 'url',
						'label' => __( 'Link (optional — otherwise the modal is used)', 'maison-woodcraft' ),
						'type'  => Controls_Manager::URL,
					),
				),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( 'manual' === $settings['source'] && ! empty( $settings['items'] ) ) {
			$items = array();
			foreach ( $this->clean_rows( $settings['items'] ) as $row ) {
				$items[] = array(
					'image'     => isset( $row['image']['url'] ) ? $row['image']['url'] : '',
					'title'     => $row['title'],
					'category'  => $row['category'],
					'location'  => $row['location'],
					'description' => $row['text'],
					'materials' => $row['materials'],
					'url'       => isset( $row['url']['url'] ) ? $row['url']['url'] : '',
				);
			}
		} else {
			$items = mw_get_project_cards( array( 'limit' => (int) $settings['limit'] ) );
		}

		$this->section(
			'project-grid',
			array(
				'items'      => $items,
				'filters'    => 'yes' === $settings['filters'],
				'link_label' => $settings['link_label'],
			)
		);
	}
}
