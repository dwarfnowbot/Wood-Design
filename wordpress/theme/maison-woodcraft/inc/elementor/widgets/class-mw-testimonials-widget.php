<?php
/**
 * Testimonials widget — components/TestimonialCard.tsx (uses Elementor's own
 * Testimonial widget markup options are not enough here: the original card has
 * the quote icon, star row and service label).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Testimonials_Widget
 */
class MW_Testimonials_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_testimonials';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Testimonials', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Testimonials', 'maison-woodcraft' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Testimonials', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'quote',
						'label'   => __( 'Quote', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXTAREA,
						'rows'    => 4,
						'default' => '',
					),
					array(
						'name'    => 'name',
						'label'   => __( 'Name', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
					array(
						'name'    => 'location',
						'label'   => __( 'Location', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
				),
				'title_field' => '{{{ name }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'note',
			array(
				'label'       => __( 'Note under the heading', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
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
				'quote'    => $row['quote'],
				'name'     => $row['name'],
				'location' => $row['location'],
			);
		}

		if ( empty( $items ) ) {
			$items = mw_testimonials();
		}

		$this->section(
			'testimonials',
			array(
				'items' => $items,
				'note'  => $settings['note'],
			)
		);
	}
}
