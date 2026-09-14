<?php
/**
 * Button widget — components/Button.tsx and WhatsAppButton.tsx.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

/**
 * Class MW_Button_Widget
 */
class MW_Button_Widget extends MW_Widget_Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mw_button';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Button (all styles)', 'maison-woodcraft' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'mw_content', array( 'label' => __( 'Button', 'maison-woodcraft' ) ) );

		$this->add_control(
			'label',
			array(
				'label'       => __( 'Label', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Request a Quote', 'maison-woodcraft' ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'maison-woodcraft' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Page path (/get-a-quote) or full URL', 'maison-woodcraft' ),
				'default'     => array( 'url' => '/get-a-quote' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'variant',
			array(
				'label'   => __( 'Style', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ghost',
				'options' => array(
					'ghost'     => __( 'Bronze (primary CTA)', 'maison-woodcraft' ),
					'primary'   => __( 'Espresso', 'maison-woodcraft' ),
					'secondary' => __( 'Ivory outline (on dark)', 'maison-woodcraft' ),
					'outline'   => __( 'Dark outline', 'maison-woodcraft' ),
					'whatsapp'  => __( 'WhatsApp green', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_control(
			'whatsapp_message',
			array(
				'label'       => __( 'WhatsApp pre-filled message', 'maison-woodcraft' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => array( 'variant' => 'whatsapp' ),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'Size', 'maison-woodcraft' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''       => __( 'Default', 'maison-woodcraft' ),
					'header' => __( 'Compact (header)', 'maison-woodcraft' ),
					'form'   => __( 'Large (forms)', 'maison-woodcraft' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'maison-woodcraft' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'maison-woodcraft' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'maison-woodcraft' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'maison-woodcraft' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mw-widget' => 'text-align: {{VALUE}};',
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
		$link     = $this->link_arg( $settings );

		echo '<div class="mw-widget mw-btn-row mw-btn-row--inline">';

		if ( 'whatsapp' === $settings['variant'] ) {
			echo mw_whatsapp_button_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'label'   => $settings['label'],
					'message' => $settings['whatsapp_message'],
				)
			);
		} else {
			mw_button(
				array(
					'label'   => $settings['label'],
					'url'     => isset( $link['url'] ) ? $link['url'] : '',
					'variant' => $settings['variant'],
					'size'    => $settings['size'],
					'new_tab' => ! empty( $link['new_tab'] ),
				)
			);
		}

		echo '</div>';
	}
}
