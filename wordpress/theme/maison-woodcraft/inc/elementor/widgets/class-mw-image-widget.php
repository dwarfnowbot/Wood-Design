<?php
/**
 * Framed image widget — the aspect-ratio image frames the original design uses
 * (aspect-[4/5] portraits, 4/3 and 16/9 media, square gallery tiles).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Image_Widget
 */
class MW_Image_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_image';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Framed Image', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Image', 'maison-woodcraft' ) ) );

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'maison-woodcraft' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => \Elementor\Utils::get_placeholder_image_src() ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'alt',
			array(
				'label'       => __( 'Alt text', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);

		$this->add_control(
			'frame',
			array(
				'label'   => __( 'Frame', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4x5',
				'options' => array(
					'4x5'    => __( 'Portrait 4:5 (original design image)', 'maison-woodcraft' ),
					'4x3'    => __( 'Landscape 4:3', 'maison-woodcraft' ),
					'16x9'   => __( 'Wide 16:9', 'maison-woodcraft' ),
					'square' => __( 'Square', 'maison-woodcraft' ),
					'none'   => __( 'No crop', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link (optional)', 'maison-woodcraft' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Page path or full URL', 'maison-woodcraft' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$image    = $this->image_arg( $settings );
		$frame    = $settings['frame'];
		$link     = $this->link_arg( $settings );

		$frame_class = 'mw-frame';
		if ( in_array( $frame, array( '4x5', '4x3', '16x9', 'square' ), true ) ) {
			$frame_class .= ' mw-frame--' . $frame;
		} else {
			$frame_class = 'mw-image-plain';
		}

		echo '<div class="mw-widget ' . esc_attr( $frame_class ) . '">';

		if ( ! empty( $link['url'] ) ) {
			printf( '<a href="%s">', esc_url( mw_page_url( $link['url'] ) ) );
		}

		echo mw_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$image,
			'',
			$settings['alt'],
			array( 'sizes' => '(max-width: 1023px) 100vw, 640px' )
		);

		if ( ! empty( $link['url'] ) ) {
			echo '</a>';
		}

		echo '</div>';
	}
}
