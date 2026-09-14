<?php
/**
 * Base class for the theme's Elementor widgets.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

/**
 * Every Maison Woodcraft widget renders one of the theme's template parts, so
 * the Elementor output and the PHP fallback output are byte-for-byte the same.
 */
abstract class MW_Widget_Base extends \Elementor\Widget_Base {

	/**
	 * Widget category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'maison-woodcraft' );
	}

	/**
	 * Search keywords — help users find the widget.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'maison', 'woodcraft', 'woodwork', 'kitchen', 'wardrobe' );
	}

	/**
	 * Render one of the theme's section template parts.
	 *
	 * @param string $section Section slug.
	 * @param array  $args    Template arguments.
	 */
	protected function section( $section, $args = array() ) {
		mw_render( $section, $args );
	}

	/**
	 * Resolve a media control value into the array the template parts expect.
	 *
	 * @param array $settings Widget settings.
	 * @param string $key     Control key.
	 * @return array
	 */
	protected function image_arg( $settings, $key = 'image' ) {
		if ( empty( $settings[ $key ] ) ) {
			return array();
		}

		$image = $settings[ $key ];

		if ( is_string( $image ) ) {
			return array( 'url' => $image );
		}

		return array(
			'id'  => isset( $image['id'] ) ? $image['id'] : 0,
			'url' => isset( $image['url'] ) ? $image['url'] : '',
			'alt' => isset( $image['alt'] ) ? $image['alt'] : '',
		);
	}

	/**
	 * Repeater rows → template args, dropping rows the user left empty.
	 *
	 * @param array $rows Repeater rows.
	 * @return array
	 */
	protected function clean_rows( $rows ) {
		$clean = array();

		foreach ( (array) $rows as $row ) {
			$has_content = false;
			foreach ( $row as $value ) {
				if ( is_string( $value ) && '' !== trim( $value ) ) {
					$has_content = true;
					break;
				}
				if ( is_array( $value ) && ! empty( $value['url'] ) ) {
					$has_content = true;
					break;
				}
			}
			if ( $has_content ) {
				$clean[] = $row;
			}
		}

		return $clean;
	}

	/**
	 * Elementor link control → template arg.
	 *
	 * @param array  $settings Widget settings.
	 * @param string $key      Control key.
	 * @return array
	 */
	protected function link_arg( $settings, $key = 'link' ) {
		if ( empty( $settings[ $key ]['url'] ) ) {
			return array();
		}

		return array(
			'url'     => $settings[ $key ]['url'],
			'new_tab' => ! empty( $settings[ $key ]['is_external'] ),
		);
	}
}
