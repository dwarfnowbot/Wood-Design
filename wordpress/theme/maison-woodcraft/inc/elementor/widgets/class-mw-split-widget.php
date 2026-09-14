<?php
/**
 * Two-column media + text section widget (the "story", "craft", "process
 * detail" blocks used on almost every original page).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Split_Widget
 */
class MW_Split_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_split';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Image + Text Section', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-box';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Content', 'maison-woodcraft' ) ) );

		$this->add_control(
			'eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'extra_text',
			array(
				'label'       => __( 'Second paragraph (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'maison-woodcraft' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_control(
			'inset',
			array(
				'label'       => __( 'Floating inset image (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'The small overlapping image used on the About page.', 'maison-woodcraft' ),
			)
		);

		$this->add_control(
			'inset_alt',
			array(
				'label'       => __( 'Inset image alt text', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'alt',
			array(
				'label'       => __( 'Image alt text', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Heading description', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'label_block' => true,
				'default'     => '',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Text list (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
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
				),
				'title_field' => '{{{ title }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'checklist',
			array(
				'label'       => __( 'Checklist (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array(
					array(
						'name'    => 'text',
						'label'   => __( 'Item', 'maison-woodcraft' ),
						'type'    => Controls_Manager::TEXT,
						'default' => '',
					),
				),
				'title_field' => '{{{ text }}}',
				'default'     => array(),
			)
		);

		$this->add_control(
			'button',
			array(
				'label'   => __( 'Button (optional)', 'maison-woodcraft' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'button_url',
			array(
				'label'     => __( 'Button link', 'maison-woodcraft' ),
				'type'      => Controls_Manager::URL,
				'condition' => array( 'button!' => '' ),
			)
		);

		$this->add_control(
			'flip',
			array(
				'label'   => __( 'Image on the right', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'background',
			array(
				'label'   => __( 'Background', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'plain',
				'options' => array(
					'plain'    => __( 'Plain (ivory)', 'maison-woodcraft' ),
					'stone'    => __( 'Stone', 'maison-woodcraft' ),
					'espresso' => __( 'Espresso (dark)', 'maison-woodcraft' ),
					'white'    => __( 'White', 'maison-woodcraft' ),
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
		$items    = array();
		$checks   = array();

		foreach ( $this->clean_rows( $settings['items'] ) as $row ) {
			$items[] = array(
				'title'       => $row['title'],
				'description' => $row['text'],
			);
		}

		foreach ( $this->clean_rows( $settings['checklist'] ) as $row ) {
			$checks[] = $row['text'];
		}

		$args = array(
			'heading'     => array(
				'eyebrow'     => $settings['eyebrow'],
				'heading'     => $settings['heading'],
				'description' => $settings['description'],
			),
			'text'        => $settings['text'],
			'extra_text'  => $settings['extra_text'],
			'image'       => $this->image_arg( $settings ),
			'alt'         => $settings['alt'],
			'inset_image' => $this->image_arg( $settings, 'inset' ),
			'inset_alt'   => $settings['inset_alt'],
			'items'       => $items,
			'checklist'   => $checks,
			'flip'        => 'yes' === $settings['flip'],
			'background'  => $settings['background'],
			'button'      => array(
				'label' => $settings['button'],
				'url'   => isset( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '',
			),
		);

		$this->section( 'split', $args );
	}
}
