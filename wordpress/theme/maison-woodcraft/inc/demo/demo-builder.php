<?php
/**
 * Elementor blueprints for the demo pages.
 *
 * Each original page is expressed as native Elementor containers and the
 * theme's own widgets, so after "Import Demo Content" every section of every
 * page is editable in Elementor (text, images, links, repeaters, colours,
 * spacing) — nothing is locked inside a single HTML block.
 *
 * The compositions mirror inc/fallback-pages.php 1:1, so the Elementor pages
 * and the theme's PHP fallback look the same.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Low level helpers
 * ---------------------------------------------------------------------- */

/**
 * Random Elementor element id.
 *
 * @return string
 */
function mw_demo_id() {
	return substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 8 );
}

/**
 * A widget element.
 *
 * @param string $type     Widget type (the theme's widgets are mw_*).
 * @param array  $settings Widget settings.
 * @return array
 */
function mw_demo_widget( $type, $settings = array() ) {
	return array(
		'id'         => mw_demo_id(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
		'elements'   => array(),
	);
}

/**
 * A container element.
 *
 * @param array $children Child elements.
 * @param array $settings Container settings.
 * @return array
 */
function mw_demo_container( $children, $settings = array() ) {
	return array(
		'id'       => mw_demo_id(),
		'elType'   => 'container',
		'settings' => array_merge(
			array(
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'flex_gap'       => array(
					'column'   => '0',
					'row'      => '0',
					'unit'     => 'px',
					'size'     => 0,
					'isLinked' => true,
				),
			),
			$settings
		),
		'elements' => array_values( (array) $children ),
	);
}

/**
 * Padding setting array.
 *
 * @param int $vertical   Vertical padding.
 * @param int $horizontal Horizontal padding.
 * @return array
 */
function mw_demo_padding( $vertical, $horizontal ) {
	return array(
		'unit'     => 'px',
		'top'      => (string) $vertical,
		'right'    => (string) $horizontal,
		'bottom'   => (string) $vertical,
		'left'     => (string) $horizontal,
		'isLinked' => false,
	);
}

/**
 * The original section rhythm (py-20 sm:py-28 and the page variants).
 *
 * @param string $size default|tight|medium|plain.
 * @return array
 */
function mw_demo_section_padding( $size ) {
	$map = array(
		'default' => array( 'desktop' => 112, 'mobile' => 80 ),
		'tight'   => array( 'desktop' => 80, 'mobile' => 64 ),
		'medium'  => array( 'desktop' => 96, 'mobile' => 80 ),
		'plain'   => array( 'desktop' => 0, 'mobile' => 0 ),
	);

	if ( ! isset( $map[ $size ] ) ) {
		$size = 'default';
	}

	return array(
		'padding'        => mw_demo_padding( $map[ $size ]['desktop'], 32 ),
		'padding_tablet' => mw_demo_padding( $map[ $size ]['desktop'], 32 ),
		'padding_mobile' => mw_demo_padding( $map[ $size ]['mobile'], 20 ),
	);
}

/**
 * A full-width section with the original background, padding and container
 * width (mw-container = 1280px, narrow = 1024px, text = 896px, center = 1152px).
 *
 * @param array $children Child elements.
 * @param array $args     bg|size|width|class.
 * @return array
 */
function mw_demo_section( $children, $args = array() ) {
	$bg    = mw_arg( $args, 'bg', '' );
	$size  = mw_arg( $args, 'size', 'default' );
	$width = mw_arg( $args, 'width', 'default' );
	$class = mw_arg( $args, 'class', '' );

	$widths = array(
		'default' => 1280,
		'narrow'  => 1024,
		'text'    => 896,
		'center'  => 1152,
	);
	$max = isset( $widths[ $width ] ) ? $widths[ $width ] : $widths['default'];

	$settings = array_merge(
		array(
			'content_width'  => 'boxed',
			'boxed_width'    => array(
				'unit'  => 'px',
				'size'  => $max - 64,
				'sizes' => array(),
			),
			'flex_direction' => 'column',
			'flex_gap'       => array(
				'column'   => '0',
				'row'      => '64',
				'unit'     => 'px',
				'size'     => 64,
				'isLinked' => true,
			),
			'_css_classes'   => trim( 'mw-demo-section ' . $class ),
		),
		mw_demo_section_padding( $size )
	);

	if ( $bg ) {
		$colors = array(
			'stone'    => '#ece4d6',
			'espresso' => '#2b241d',
			'ivory'    => '#f7f3ec',
			'white'    => '#ffffff',
		);
		$settings['background_background'] = 'classic';
		$settings['background_color']      = isset( $colors[ $bg ] ) ? $colors[ $bg ] : $bg;
	}

	return mw_demo_container( $children, $settings );
}

/**
 * A row container (heading + button, two column page layouts, …).
 *
 * @param array $children Child elements.
 * @param array $args     justify|align.
 * @return array
 */
function mw_demo_row( $children, $args = array() ) {
	return mw_demo_container(
		$children,
		array(
			'content_width'         => 'full',
			'flex_direction'        => 'row',
			'flex_direction_mobile' => 'column',
			'flex_wrap'             => 'wrap',
			'flex_justify_content'  => mw_arg( $args, 'justify', 'space-between' ),
			'flex_align_items'      => mw_arg( $args, 'align', 'center' ),
			'flex_gap'              => array(
				'column'   => '32',
				'row'      => '32',
				'unit'     => 'px',
				'size'     => 32,
				'isLinked' => true,
			),
		)
	);
}

/**
 * A column inside a row: percentage width with a full-width mobile fallback.
 *
 * @param array $children Children.
 * @param int   $percent  Desktop width percentage.
 * @return array
 */
function mw_demo_col( $children, $percent ) {
	return mw_demo_container(
		$children,
		array(
			'content_width'  => 'full',
			'flex_direction' => 'column',
			'width'          => array(
				'unit'  => '%',
				'size'  => $percent,
				'sizes' => array(),
			),
			'width_mobile'   => array(
				'unit'  => '%',
				'size'  => 100,
				'sizes' => array(),
			),
			'flex_gap'       => array(
				'column'   => '0',
				'row'      => '40',
				'unit'     => 'px',
				'size'     => 40,
				'isLinked' => true,
			),
		)
	);
}

/* -------------------------------------------------------------------------
 * Content helpers
 * ---------------------------------------------------------------------- */

/**
 * An Elementor media setting from a media-map key or a URL.
 *
 * @param string $key Media key or URL.
 * @param string $alt Alt text.
 * @return array
 */
function mw_demo_image( $key, $alt = '' ) {
	if ( empty( $key ) ) {
		return array();
	}

	if ( preg_match( '#^https?://#', $key ) ) {
		return array(
			'url'    => $key,
			'id'     => 0,
			'alt'    => $alt,
			'source' => 'library',
			'size'   => '',
		);
	}

	$id  = mw_attachment_id_for_key( $key );
	$url = $id ? wp_get_attachment_image_url( $id, 'full' ) : mw_image_url( $key );

	if ( ! $url ) {
		return array();
	}

	return array(
		'url'    => $url,
		'id'     => $id,
		'alt'    => $alt ? $alt : mw_image_alt( $key ),
		'source' => 'library',
		'size'   => '',
	);
}

/**
 * A repeater row id.
 *
 * @return array
 */
function mw_demo_rid() {
	return array( '_id' => mw_demo_id() );
}

/**
 * Convert the original button objects into a hero/CTA repeater.
 *
 * @param array $buttons Buttons from site-content.json.
 * @return array
 */
function mw_demo_buttons( $buttons ) {
	$rows = array();

	foreach ( (array) $buttons as $button ) {
		$rows[] = array_merge(
			array(
				'label'   => mw_arg( $button, 'label', '' ),
				'url'     => array(
					'url'         => mw_arg( $button, 'path', mw_arg( $button, 'url', '' ) ),
					'is_external' => '',
					'nofollow'    => '',
				),
				'variant' => mw_arg( $button, 'variant', 'ghost' ),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * A heading widget from page copy.
 *
 * @param array $copy  Page copy section.
 * @param array $extra tag|align|light.
 * @return array
 */
function mw_demo_heading( $copy, $extra = array() ) {
	return mw_demo_widget(
		'mw_section_heading',
		array(
			'eyebrow'     => mw_arg( $copy, 'eyebrow', '' ),
			'heading'     => mw_arg( $copy, 'heading', '' ),
			'description' => mw_arg( $copy, 'description', '' ),
			'tag'         => mw_arg( $extra, 'tag', 'h2' ),
			'align'       => mw_arg( $extra, 'align', 'left' ),
			'light'       => ! empty( $extra['light'] ) ? 'yes' : '',
		)
	);
}

/**
 * Repeater rows from a plain list of strings (checklists).
 *
 * @param array $items Strings.
 * @return array
 */
function mw_demo_text_rows( $items ) {
	$rows = array();

	foreach ( (array) $items as $item ) {
		$rows[] = array_merge( array( 'text' => is_array( $item ) ? mw_arg( $item, 'text', '' ) : $item ), mw_demo_rid() );
	}

	return $rows;
}

/**
 * Repeater rows for the image card grid (kitchen styles, wardrobe types,
 * interior woodwork categories).
 *
 * @param array $items Items with title/description/image.
 * @return array
 */
function mw_demo_card_rows( $items ) {
	$rows = array();

	foreach ( (array) $items as $item ) {
		$rows[] = array_merge(
			array(
				'image' => mw_demo_image( mw_arg( $item, 'image', '' ), mw_arg( $item, 'title', '' ) ),
				'title' => mw_arg( $item, 'title', '' ),
				'text'  => mw_arg( $item, 'description', '' ),
				'url'   => array(
					'url'         => '',
					'is_external' => '',
					'nofollow'    => '',
				),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * Repeater rows for the swatches widget (materials & finishes).
 *
 * @param array $items Material categories.
 * @return array
 */
function mw_demo_swatch_rows( $items ) {
	$rows = array();

	foreach ( (array) $items as $item ) {
		$from = mw_arg( $item, 'swatch_from', '#efe8db' );
		$to   = mw_arg( $item, 'swatch_to', '#b79c72' );

		$rows[] = array_merge(
			array(
				'title'       => mw_arg( $item, 'title', '' ),
				'description' => mw_arg( $item, 'description', '' ),
				'swatch_from' => $from,
				'swatch_to'   => $to,
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * Repeater rows for the process timeline.
 *
 * @param array $steps Process steps.
 * @return array
 */
function mw_demo_step_rows( $steps ) {
	$rows = array();

	foreach ( (array) $steps as $step ) {
		$rows[] = array_merge(
			array(
				'number'      => mw_arg( $step, 'number', '' ),
				'title'       => mw_arg( $step, 'title', '' ),
				'description' => mw_arg( $step, 'description', '' ),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * Elementor gallery control value.
 *
 * @param array $items Gallery items.
 * @return array
 */
function mw_demo_gallery( $items ) {
	$images = array();

	foreach ( (array) $items as $item ) {
		$key   = is_array( $item ) ? mw_arg( $item, 'image', mw_arg( $item, 'url', '' ) ) : $item;
		$image = mw_demo_image( $key );

		if ( empty( $image['url'] ) ) {
			continue;
		}

		$images[] = array(
			'id'     => $image['id'],
			'url'    => $image['url'],
			'alt'    => mw_arg( $image, 'alt', '' ),
			'source' => 'library',
			'size'   => '',
			'_id'    => mw_demo_id(),
		);
	}

	return $images;
}

/**
 * Trust strip rows.
 *
 * @param array $points Trust points.
 * @return array
 */
function mw_demo_trust_rows( $points ) {
	$rows  = array();
	$icons = array( 'trust-1', 'trust-2', 'trust-3', 'trust-4' );

	foreach ( array_values( (array) $points ) as $index => $point ) {
		$rows[] = array_merge(
			array(
				'text' => is_array( $point ) ? mw_arg( $point, 'text', '' ) : $point,
				'icon' => isset( $icons[ $index ] ) ? $icons[ $index ] : 'trust-1',
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * Feature grid rows.
 *
 * @param array $items  Feature items.
 * @param bool  $numbered Whether to number them.
 * @return array
 */
function mw_demo_feature_rows( $items, $numbered = false ) {
	$rows = array();

	foreach ( array_values( (array) $items ) as $index => $item ) {
		$rows[] = array_merge(
			array(
				'step'        => $numbered ? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) : '',
				'title'       => mw_arg( $item, 'title', '' ),
				'description' => mw_arg( $item, 'description', '' ),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * FAQ rows.
 *
 * @param array $faqs FAQ items with q/a keys.
 * @return array
 */
function mw_demo_faq_rows( $faqs ) {
	$rows = array();

	foreach ( (array) $faqs as $faq ) {
		$rows[] = array_merge(
			array(
				'question' => mw_arg( $faq, 'q', mw_arg( $faq, 'question', '' ) ),
				'answer'   => mw_arg( $faq, 'a', mw_arg( $faq, 'answer', '' ) ),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/**
 * Testimonial rows.
 *
 * @param array $items Testimonials.
 * @return array
 */
function mw_demo_testimonial_rows( $items ) {
	$rows = array();

	foreach ( (array) $items as $item ) {
		$rows[] = array_merge(
			array(
				'quote'    => mw_arg( $item, 'quote', '' ),
				'name'     => mw_arg( $item, 'name', '' ),
				'location' => mw_arg( $item, 'location', '' ),
			),
			mw_demo_rid()
		);
	}

	return $rows;
}

/* -------------------------------------------------------------------------
 * Page blueprints (mirroring inc/fallback-pages.php)
 * ---------------------------------------------------------------------- */

/**
 * Elementor data for one of the original pages.
 *
 * @param string $slug Page slug.
 * @return array
 */
function mw_demo_page_data( $slug ) {
	$copy = mw_page_copy( $slug, null, array() );

	if ( ! is_array( $copy ) ) {
		return array();
	}

	switch ( $slug ) {
		case 'home':
			return mw_demo_home( $copy );
		case 'about':
			return mw_demo_about( $copy );
		case 'kitchens':
			return mw_demo_kitchens( $copy );
		case 'wardrobes':
			return mw_demo_wardrobes( $copy );
		case 'interior-woodwork':
			return mw_demo_interior_woodwork( $copy );
		case 'projects':
			return mw_demo_projects_page( $copy );
		case 'materials-finishes':
			return mw_demo_materials( $copy );
		case 'process':
			return mw_demo_process_page( $copy );
		case 'get-a-quote':
			return mw_demo_get_quote( $copy );
		case 'contact':
			return mw_demo_contact( $copy );
	}

	return array();
}

/**
 * Hero widget element.
 *
 * @param array $hero  Hero copy.
 * @param array $extra variant|height.
 * @return array
 */
function mw_demo_hero( $hero, $extra = array() ) {
	return mw_demo_widget(
		'mw_hero',
		array(
			'variant'          => mw_arg( $extra, 'variant', 'page' ),
			'height'           => mw_arg( $extra, 'height', '' ),
			'eyebrow'          => mw_arg( $hero, 'eyebrow', '' ),
			'heading'          => mw_arg( $hero, 'heading', '' ),
			'text'             => mw_arg( $hero, 'description', mw_arg( $hero, 'text', '' ) ),
			'image'            => mw_demo_image( mw_arg( $hero, 'image', '' ), mw_arg( $hero, 'alt', '' ) ),
			'alt'              => mw_arg( $hero, 'alt', '' ),
			'buttons'          => mw_demo_buttons( mw_arg( $hero, 'buttons', array() ) ),
			'whatsapp'         => ! empty( $hero['whatsapp'] ) ? 'yes' : '',
			'whatsapp_message' => mw_arg( $hero, 'whatsappMessage', '' ),
		)
	);
}

/**
 * Split widget element.
 *
 * @param array $args heading|text|image|button|checklist|flip|alt.
 * @return array
 */
function mw_demo_split( $args ) {
	$heading = mw_arg( $args, 'heading', array() );
	$button  = mw_arg( $args, 'button', array() );

	return mw_demo_widget(
		'mw_split',
		array(
			'eyebrow'     => mw_arg( $heading, 'eyebrow', '' ),
			'heading'     => mw_arg( $heading, 'heading', '' ),
			'description' => mw_arg( $heading, 'description', '' ),
			'text'        => mw_arg( $args, 'text', '' ),
			'extra_text'  => mw_arg( $args, 'extra_text', '' ),
			'image'       => mw_demo_image( mw_arg( $args, 'image', '' ), mw_arg( $args, 'alt', '' ) ),
			'alt'         => mw_arg( $args, 'alt', '' ),
			'inset'       => mw_demo_image( mw_arg( $args, 'inset_image', '' ), mw_arg( $args, 'inset_alt', '' ) ),
			'inset_alt'   => mw_arg( $args, 'inset_alt', '' ),
			'items'       => mw_demo_text_rows( mw_arg( $args, 'items', array() ) ),
			'checklist'   => mw_demo_text_rows( mw_arg( $args, 'checklist', array() ) ),
			'button'      => mw_arg( $button, 'label', '' ),
			'button_url'  => array(
				'url'         => mw_arg( $button, 'path', mw_arg( $button, 'url', '' ) ),
				'is_external' => '',
				'nofollow'    => '',
			),
			'flip'        => ! empty( $args['flip'] ) ? 'yes' : '',
			'background'  => mw_arg( $args, 'background', 'plain' ),
		)
	);
}

/**
 * CTA widget element.
 *
 * @param array $cta CTA copy.
 * @return array
 */
function mw_demo_cta( $cta ) {
	return mw_demo_widget(
		'mw_cta',
		array(
			'eyebrow' => mw_arg( $cta, 'eyebrow', '' ),
			'heading' => mw_arg( $cta, 'heading', '' ),
			'text'    => mw_arg( $cta, 'text', '' ),
			'buttons' => array(
				array_merge(
					array(
						'label'   => mw_arg( $cta, 'primaryLabel', __( 'Request a Quote', 'maison-woodcraft' ) ),
						'url'     => array(
							'url'         => mw_arg( $cta, 'primaryPath', '/get-a-quote' ),
							'is_external' => '',
							'nofollow'    => '',
						),
						'variant' => 'ghost',
					),
					mw_demo_rid()
				),
				array_merge(
					array(
						'label'   => __( 'Chat on WhatsApp', 'maison-woodcraft' ),
						'url'     => array(
							'url'         => '',
							'is_external' => '',
							'nofollow'    => '',
						),
						'variant' => 'whatsapp',
					),
					mw_demo_rid()
				),
			),
		)
	);
}

/**
 * Home — pages/Home.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_home( $copy ) {
	$hero         = mw_arg( $copy, 'hero', array() );
	$intro        = mw_arg( $copy, 'intro', array() );
	$services     = mw_arg( $copy, 'services', array() );
	$projects     = mw_arg( $copy, 'projects', array() );
	$why          = mw_arg( $copy, 'why', array() );
	$process      = mw_arg( $copy, 'process', array() );
	$materials    = mw_arg( $copy, 'materials', array() );
	$testimonials = mw_arg( $copy, 'testimonials', array() );

	$json = array();

	$json[] = mw_demo_hero( $hero, array( 'variant' => 'home' ) );

	$json[] = mw_demo_widget( 'mw_trust_strip', array( 'items' => mw_demo_trust_rows( mw_trust_points() ) ) );

	$intro_button = mw_arg( $intro, 'button', array() );
	$json[]       = mw_demo_widget(
		'mw_split',
		array(
			'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
			'heading'     => mw_arg( $intro, 'heading', '' ),
			'description' => mw_arg( $intro, 'description', '' ),
			'image'       => mw_demo_image( mw_arg( $intro, 'image', 'kitchen.0' ), mw_arg( $intro, 'alt', '' ) ),
			'alt'         => mw_arg( $intro, 'alt', '' ),
			'inset'       => mw_demo_image( mw_arg( $intro, 'insetImage', '' ), mw_arg( $intro, 'insetAlt', '' ) ),
			'inset_alt'   => mw_arg( $intro, 'insetAlt', '' ),
			'button'      => mw_arg( $intro_button, 'label', '' ),
			'button_url'  => array(
				'url'         => mw_arg( $intro_button, 'path', '' ),
				'is_external' => '',
				'nofollow'    => '',
			),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $services ),
			mw_demo_widget( 'mw_service_cards', array( 'source' => 'site', 'link_label' => __( 'Explore Service', 'maison-woodcraft' ) ) ),
		),
		array( 'bg' => 'stone' )
	);

	$projects_button = mw_arg( $projects, 'button', array() );
	$json[]          = mw_demo_section(
		array(
			mw_demo_row(
				array(
					mw_demo_heading( $projects ),
					mw_demo_widget(
						'mw_button',
						array(
							'label'   => mw_arg( $projects_button, 'label', '' ),
							'link'    => array(
								'url'         => mw_arg( $projects_button, 'path', '' ),
								'is_external' => '',
								'nofollow'    => '',
							),
							'variant' => mw_arg( $projects_button, 'variant', 'outline' ),
						)
					),
				)
			),
			mw_demo_widget(
				'mw_project_grid',
				array(
					'source'     => 'cpt',
					'limit'      => (int) mw_arg( $projects, 'count', 6 ),
					'filters'    => '',
					'link_label' => __( 'View Project', 'maison-woodcraft' ),
				)
			),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $why, array( 'light' => true ) ),
			mw_demo_widget(
				'mw_feature_grid',
				array(
					'items'   => mw_demo_feature_rows( mw_why_choose_us() ),
					'columns' => 'three',
				)
			),
		),
		array( 'bg' => 'espresso' )
	);

	$process_button = mw_arg( $process, 'button', array() );
	$json[]         = mw_demo_section(
		array(
			mw_demo_heading( $process ),
			mw_demo_widget( 'mw_process_timeline', array( 'layout' => 'horizontal', 'items' => mw_demo_step_rows( mw_process_steps() ) ) ),
			mw_demo_widget(
				'mw_button',
				array(
					'label'   => mw_arg( $process_button, 'label', '' ),
					'link'    => array(
						'url'         => mw_arg( $process_button, 'path', '' ),
						'is_external' => '',
						'nofollow'    => '',
					),
					'variant' => mw_arg( $process_button, 'variant', 'outline' ),
				)
			),
		)
	);

	$materials_link = mw_arg( $materials, 'link', array() );
	$json[]         = mw_demo_section(
		array(
			mw_demo_heading( $materials ),
			mw_demo_widget(
				'mw_swatches',
				array(
					'items'   => mw_demo_swatch_rows( mw_material_categories() ),
					'compact' => 'yes',
					'note'    => mw_materials_disclaimer(),
					'link'    => array(
						'label' => mw_arg( $materials_link, 'label', '' ),
						'url'   => mw_arg( $materials_link, 'path', '' ),
					),
				)
			),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $testimonials ),
			mw_demo_widget(
				'mw_testimonials',
				array(
					'note'  => mw_arg( $testimonials, 'note', '' ),
					'items' => mw_demo_testimonial_rows( mw_testimonials() ),
				)
			),
		)
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * About — pages/About.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_about( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$approach = mw_arg( $copy, 'approach', array() );
	$area     = mw_arg( $copy, 'area', array() );

	$json   = array();
	$json[] = mw_demo_hero( $hero, array( 'variant' => 'page' ) );

	$json[] = mw_demo_widget(
		'mw_split',
		array(
			'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
			'heading'     => mw_arg( $intro, 'heading', '' ),
			'description' => mw_arg( $intro, 'description', '' ),
			'text'        => mw_arg( $intro, 'secondary', '' ),
			'image'       => mw_demo_image( mw_arg( $intro, 'image', 'about' ), mw_arg( $intro, 'alt', '' ) ),
			'alt'         => mw_arg( $intro, 'alt', '' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $approach ),
			mw_demo_widget(
				'mw_feature_grid',
				array(
					'columns' => 'two',
					'items'   => mw_demo_feature_rows( mw_arg( $approach, 'points', array() ), true ),
				)
			),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_widget(
		'mw_split',
		array(
			'eyebrow'     => mw_arg( $area, 'eyebrow', '' ),
			'heading'     => mw_arg( $area, 'heading', '' ),
			'description' => str_replace( '{serviceArea}', mw_site( 'serviceArea' ), mw_arg( $area, 'description', '' ) ),
			'text'        => mw_arg( $area, 'secondary', '' ),
			'image'       => mw_demo_image( mw_arg( $area, 'image', 'kitchen.6' ), mw_arg( $area, 'alt', '' ) ),
			'alt'         => mw_arg( $area, 'alt', '' ),
			'flip'        => 'yes',
		)
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Kitchens — pages/Kitchens.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_kitchens( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$styles   = mw_arg( $copy, 'styles', array() );
	$features = mw_arg( $copy, 'features', array() );
	$gallery  = mw_arg( $copy, 'gallery', array() );
	$process  = mw_arg( $copy, 'process', array() );
	$faq      = mw_arg( $copy, 'faq', array() );

	$json   = array();
	$json[] = mw_demo_hero( $hero, array( 'variant' => 'page' ) );
	$json[] = mw_demo_split(
		array(
			'heading' => $intro,
			'button'  => mw_arg( $intro, 'button', array() ),
			'image'   => mw_arg( $intro, 'image', 'kitchen.3' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $styles ),
			mw_demo_widget(
				'mw_card_grid',
				array(
					'variant' => 'style',
					'items'   => mw_demo_card_rows( mw_kitchen_style_items() ),
				)
			),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_split(
		array(
			'heading'   => $features,
			'checklist' => mw_content( 'kitchen.features', array() ),
			'image'     => mw_arg( $features, 'image', 'kitchen.5' ),
			'alt'       => mw_arg( $features, 'alt', '' ),
			'flip'      => 'yes',
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $gallery ),
			mw_demo_widget( 'mw_gallery', array( 'images' => mw_demo_gallery( mw_gallery_items( 'kitchen' ) ) ) ),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $process ),
			mw_demo_widget( 'mw_process_timeline', array( 'layout' => 'horizontal', 'items' => mw_demo_step_rows( mw_process_steps() ) ) ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $faq ),
			mw_demo_widget(
				'mw_faq',
				array(
					'open_first' => 'yes',
					'items'      => mw_demo_faq_rows( mw_content( 'kitchen.faqs', array() ) ),
				)
			),
		),
		array(
			'bg'    => 'stone',
			'width' => 'text',
		)
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Wardrobes — pages/Wardrobes.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_wardrobes( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$types    = mw_arg( $copy, 'types', array() );
	$internal = mw_arg( $copy, 'internal', array() );
	$benefits = mw_arg( $copy, 'benefits', array() );
	$gallery  = mw_arg( $copy, 'gallery', array() );
	$process  = mw_arg( $copy, 'process', array() );
	$faq      = mw_arg( $copy, 'faq', array() );

	$json   = array();
	$json[] = mw_demo_hero( $hero, array( 'variant' => 'page' ) );
	$json[] = mw_demo_split(
		array(
			'heading' => $intro,
			'button'  => mw_arg( $intro, 'button', array() ),
			'image'   => mw_arg( $intro, 'image', 'wardrobe.2' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $types ),
			mw_demo_widget(
				'mw_card_grid',
				array(
					'variant' => 'style',
					'items'   => mw_demo_card_rows( mw_wardrobe_type_items() ),
				)
			),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_split(
		array(
			'heading'   => $internal,
			'checklist' => mw_content( 'wardrobe.features', array() ),
			'image'     => mw_arg( $internal, 'image', 'wardrobe.5' ),
			'alt'       => mw_arg( $internal, 'alt', '' ),
			'flip'      => 'yes',
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $benefits, array( 'light' => true ) ),
			mw_demo_widget(
				'mw_feature_grid',
				array(
					'columns' => 'three',
					'items'   => mw_demo_feature_rows( array_slice( mw_why_choose_us(), 0, 3 ) ),
				)
			),
		),
		array( 'bg' => 'espresso' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $gallery ),
			mw_demo_widget( 'mw_gallery', array( 'images' => mw_demo_gallery( mw_gallery_items( 'wardrobe' ) ) ) ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $process ),
			mw_demo_widget( 'mw_process_timeline', array( 'layout' => 'horizontal', 'items' => mw_demo_step_rows( mw_process_steps() ) ) ),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $faq ),
			mw_demo_widget(
				'mw_faq',
				array(
					'open_first' => 'yes',
					'items'      => mw_demo_faq_rows( mw_content( 'wardrobe.faqs', array() ) ),
				)
			),
		),
		array( 'width' => 'text' )
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Interior Woodwork — pages/InteriorWoodwork.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_interior_woodwork( $copy ) {
	$hero       = mw_arg( $copy, 'hero', array() );
	$intro      = mw_arg( $copy, 'intro', array() );
	$categories = mw_arg( $copy, 'categories', array() );
	$process    = mw_arg( $copy, 'process', array() );

	$json   = array();
	$json[] = mw_demo_hero( $hero, array( 'variant' => 'page' ) );
	$json[] = mw_demo_split(
		array(
			'heading' => $intro,
			'image'   => mw_arg( $intro, 'image', 'living.2' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $categories ),
			mw_demo_widget(
				'mw_card_grid',
				array(
					'variant' => 'category',
					'items'   => mw_demo_card_rows( mw_interior_woodwork_items() ),
				)
			),
		),
		array( 'bg' => 'stone' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $process ),
			mw_demo_widget( 'mw_process_timeline', array( 'layout' => 'horizontal', 'items' => mw_demo_step_rows( mw_process_steps() ) ) ),
		)
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Projects — pages/Projects.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_projects_page( $copy ) {
	$hero       = mw_arg( $copy, 'hero', array() );
	$categories = mw_arg( $copy, 'categories', array() );

	$json   = array();
	$json[] = mw_demo_hero( $hero, array( 'variant' => 'page' ) );

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $categories ),
			mw_demo_widget(
				'mw_project_grid',
				array(
					'source'     => 'cpt',
					'limit'      => 24,
					'filters'    => 'yes',
					'link_label' => __( 'View Project', 'maison-woodcraft' ),
				)
			),
		),
		array( 'size' => 'tight' )
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Materials & Finishes — pages/MaterialsFinishes.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_materials( $copy ) {
	$hero         = mw_arg( $copy, 'hero', array() );
	$overview     = mw_arg( $copy, 'overview', array() );
	$countertops  = mw_arg( $copy, 'countertops', array() );
	$availability = mw_arg( $copy, 'availability', array() );

	$json   = array();
	$json[] = mw_demo_hero(
		$hero,
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'short' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $overview ),
			mw_demo_widget( 'mw_swatches', array( 'items' => mw_demo_swatch_rows( mw_material_categories() ) ) ),
		),
		array( 'size' => 'medium' )
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_split(
				array(
					'heading' => $countertops,
					'image'   => mw_arg( $countertops, 'image', 'kitchen.4' ),
					'alt'     => mw_arg( $countertops, 'alt', '' ),
				)
			),
		),
		array(
			'bg'   => 'stone',
			'size' => 'medium',
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_widget(
				'mw_checklist',
				array(
					'title' => mw_arg( $availability, 'heading', '' ),
					'items' => mw_demo_text_rows( array( mw_materials_disclaimer() ) ),
				)
			),
		),
		array( 'size' => 'medium' )
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Process — pages/Process.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_process_page( $copy ) {
	$hero  = mw_arg( $copy, 'hero', array() );
	$steps = mw_arg( $copy, 'steps', array() );

	$json   = array();
	$json[] = mw_demo_hero(
		$hero,
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'short' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_heading( $steps ),
			mw_demo_widget(
				'mw_process_timeline',
				array(
					'layout' => 'vertical',
					'items'  => mw_demo_step_rows( mw_detailed_process_steps() ),
				)
			),
		),
		array( 'width' => 'narrow' )
	);

	$json[] = mw_demo_cta( mw_arg( $copy, 'cta', array() ) );

	return $json;
}

/**
 * Get a Quote — pages/GetQuote.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_get_quote( $copy ) {
	$hero = mw_arg( $copy, 'hero', array() );
	$form = mw_arg( $copy, 'form', array() );
	$aside = mw_arg( $copy, 'aside', array() );

	$json   = array();
	$json[] = mw_demo_hero(
		$hero,
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'compact' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_row(
				array(
					mw_demo_col(
						array(
							mw_demo_widget(
								'mw_quote_form',
								array(
									'heading'      => mw_arg( $form, 'heading', '' ),
									'show_details' => 'yes',
									'show_upload'  => 'yes',
								)
							),
						),
						64
					),
					mw_demo_col(
						array(
							mw_demo_widget(
								'mw_contact_panels',
								array(
									'variant'          => 'quote',
									'details'          => 'yes',
									'hours'            => 'yes',
									'whatsapp'         => 'yes',
									'whatsapp_message' => mw_arg( $aside, 'whatsappMessage', '' ),
								)
							),
						),
						33
					),
				),
				array( 'align' => 'flex-start' )
			),
		),
		array(
			'size'  => 'tight',
			'width' => 'center',
		)
	);

	return $json;
}

/**
 * Contact — pages/Contact.tsx.
 *
 * @param array $copy Page copy.
 * @return array
 */
function mw_demo_contact( $copy ) {
	$hero = mw_arg( $copy, 'hero', array() );
	$form = mw_arg( $copy, 'form', array() );

	$json   = array();
	$json[] = mw_demo_hero(
		$hero,
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'compact' ),
		)
	);

	$json[] = mw_demo_section(
		array(
			mw_demo_row(
				array(
					mw_demo_col(
						array(
							mw_demo_heading( $form ),
							mw_demo_widget( 'mw_contact_form', array( 'heading' => '' ) ),
						),
						58
					),
					mw_demo_col(
						array(
							mw_demo_widget(
								'mw_contact_panels',
								array(
									'variant'  => 'contact',
									'details'  => 'yes',
									'hours'    => 'yes',
									'social'   => 'yes',
									'map'      => 'yes',
									'whatsapp' => 'yes',
								)
							),
						),
						38
					),
				),
				array( 'align' => 'flex-start' )
			),
		),
		array( 'size' => 'tight' )
	);

	return $json;
}
