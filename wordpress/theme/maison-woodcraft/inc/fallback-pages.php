<?php
/**
 * Native page rendering.
 *
 * Each page of the original site is recreated here section-by-section from
 * inc/content/site-content.json, using the same template parts the Elementor
 * widgets use. Two things come out of this:
 *
 *   1. the theme renders the original website immediately, with or without
 *      Elementor installed or configured,
 *   2. the demo importer's Elementor blueprints mirror this file closely, so the
 *      pages you get after "Import Demo Content" look like the originals.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Small section helpers
 * ---------------------------------------------------------------------- */

/**
 * Open a <section> with the original background/padding variants.
 *
 * @param array $args {
 *     @type string $bg        Background: '' | stone | espresso | ivory | white.
 *     @type string $size      Padding: '' | tight | medium | plain.
 *     @type string $wrap      Inner wrapper class, e.g. 'mw-container'.
 *     @type string $class     Extra section classes.
 * }
 */
function mw_section_open( $args = array() ) {
	$bg   = mw_arg( $args, 'bg', '' );
	$size = mw_arg( $args, 'size', '' );
	$wrap = mw_arg( $args, 'wrap', '' );

	$classes = 'mw-section';
	if ( $size ) {
		$classes .= ' mw-section--' . sanitize_html_class( $size );
	}
	if ( $bg ) {
		$classes .= ' mw-section--' . sanitize_html_class( $bg );
	}
	if ( ! empty( $args['class'] ) ) {
		$classes .= ' ' . $args['class'];
	}

	echo '<section class="' . esc_attr( $classes ) . '">';
	if ( $wrap ) {
		echo '<div class="' . esc_attr( $wrap ) . '">';
	}
}

/**
 * Close the section opened by mw_section_open().
 *
 * @param array $args Same array that was passed to mw_section_open().
 */
function mw_section_close( $args = array() ) {
	if ( mw_arg( $args, 'wrap', '' ) ) {
		echo '</div>';
	}
	echo '</section>';
}

/**
 * A heading followed by content, with the original 3rem/3.5rem rhythm.
 *
 * @param array    $heading_args Arguments for the heading template part.
 * @param callable $content      Callback that prints the section content.
 * @param string   $spacing      Wrapper class, default 'mw-mt-12'.
 */
function mw_heading_block( $heading_args, $content, $spacing = 'mw-mt-12' ) {
	mw_render( 'heading', $heading_args );
	echo '<div class="' . esc_attr( $spacing ) . '">';
	call_user_func( $content );
	echo '</div>';
}

/**
 * The ten Interior Woodwork categories with the image each one uses in the
 * original page component (InteriorWoodwork.tsx galleryMap).
 *
 * @return array
 */
function mw_interior_woodwork_items() {
	$map = array(
		'TV Units'                => 'living.1',
		'Media Walls'             => 'living.6',
		'Wall Panels'             => 'living.7',
		'Bathroom Vanities'       => 'vanity.0',
		'Shoe Cabinets'           => 'entryway.0',
		'Storage Cabinets'        => 'entryway.1',
		'Bedroom Woodwork'        => 'wardrobe.6',
		'Living Room Woodwork'    => 'living.3',
		'Custom Shelving'         => 'study.1',
		'Study / Workspace Units' => 'study.0',
	);

	$items = array();
	foreach ( (array) mw_content( 'interiorWoodwork.categories', array() ) as $index => $item ) {
		$item['image'] = isset( $map[ $item['title'] ] ) ? $map[ $item['title'] ] : 'living.' . ( $index % 8 );
		$items[]       = $item;
	}

	return $items;
}

/**
 * Kitchen styles with the gallery images the original page assigns
 * (images.kitchen[i % length]).
 *
 * @return array
 */
function mw_kitchen_style_items() {
	$items = array();
	foreach ( (array) mw_content( 'kitchen.styles', array() ) as $index => $item ) {
		$item['image'] = 'kitchen.' . ( $index % 8 );
		$items[]       = $item;
	}
	return $items;
}

/**
 * Wardrobe formats with the gallery images the original page assigns.
 *
 * @return array
 */
function mw_wardrobe_type_items() {
	$items = array();
	foreach ( (array) mw_content( 'wardrobe.types', array() ) as $index => $item ) {
		$item['image'] = 'wardrobe.' . ( $index % 8 );
		$items[]       = $item;
	}
	return $items;
}

/**
 * Gallery items for a media group (kitchen / wardrobe …).
 *
 * @param string $group Group name.
 * @return array
 */
function mw_gallery_items( $group ) {
	$items = array();
	$index = 0;

	while ( true ) {
		$key   = $group . '.' . $index;
		$image = mw_image( $key );
		if ( ! $image ) {
			break;
		}
		$items[] = array(
			'image' => $key,
			'alt'   => $image['alt'],
		);
		$index++;
	}

	return $items;
}

/**
 * Slugs covered by mw_render_fallback_page().
 *
 * @return string[]
 */
function mw_fallback_page_slugs() {
	return array( 'home', 'about', 'kitchens', 'wardrobes', 'interior-woodwork', 'projects', 'materials-finishes', 'process', 'get-a-quote', 'contact' );
}

/**
 * Does this page slug have a native recreation?
 *
 * @param string $slug Page slug.
 * @return bool
 */
function mw_has_fallback_page( $slug ) {
	return in_array( $slug, mw_fallback_page_slugs(), true );
}

/**
 * Render one of the original pages natively.
 *
 * @param string $slug Page slug.
 * @return bool Whether a page was rendered.
 */
function mw_render_fallback_page( $slug ) {
	if ( ! mw_has_fallback_page( $slug ) ) {
		return false;
	}

	$copy = mw_page_copy( $slug, null, array() );

	switch ( $slug ) {
		case 'home':
			mw_render_home( $copy );
			break;
		case 'about':
			mw_render_about( $copy );
			break;
		case 'kitchens':
			mw_render_kitchens( $copy );
			break;
		case 'wardrobes':
			mw_render_wardrobes( $copy );
			break;
		case 'interior-woodwork':
			mw_render_interior_woodwork( $copy );
			break;
		case 'projects':
			mw_render_projects_page( $copy );
			break;
		case 'materials-finishes':
			mw_render_materials( $copy );
			break;
		case 'process':
			mw_render_process_page( $copy );
			break;
		case 'get-a-quote':
			mw_render_get_quote( $copy );
			break;
		case 'contact':
			mw_render_contact( $copy );
			break;
	}

	return true;
}

/* -------------------------------------------------------------------------
 * Page compositions (mirroring the original page components 1:1)
 * ---------------------------------------------------------------------- */

/**
 * Home — pages/Home.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_home( $copy ) {
	$hero         = mw_arg( $copy, 'hero', array() );
	$intro        = mw_arg( $copy, 'intro', array() );
	$services     = mw_arg( $copy, 'services', array() );
	$projects     = mw_arg( $copy, 'projects', array() );
	$why          = mw_arg( $copy, 'why', array() );
	$process      = mw_arg( $copy, 'process', array() );
	$materials    = mw_arg( $copy, 'materials', array() );
	$testimonials = mw_arg( $copy, 'testimonials', array() );

	mw_render(
		'hero',
		array(
			'variant'  => 'home',
			'eyebrow'  => mw_arg( $hero, 'eyebrow', '' ),
			'title'    => mw_arg( $hero, 'heading', '' ),
			'text'     => mw_arg( $hero, 'text', '' ),
			'image'    => mw_arg( $hero, 'image', 'heroKitchen' ),
			'alt'      => mw_arg( $hero, 'alt', '' ),
			'buttons'  => mw_arg( $hero, 'buttons', array() ),
			'whatsapp' => (bool) mw_arg( $hero, 'whatsapp', false ),
		)
	);

	mw_render( 'trust-strip', array( 'items' => mw_trust_points() ) );

	mw_render(
		'split',
		array(
			'heading'        => array(
				'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
				'heading'     => mw_arg( $intro, 'heading', '' ),
				'description' => mw_arg( $intro, 'description', '' ),
			),
			'button'         => mw_arg( $intro, 'button', array() ),
			'image'          => mw_arg( $intro, 'image', 'introKitchen' ),
			'alt'            => mw_arg( $intro, 'alt', '' ),
			'inset_image'    => mw_arg( $intro, 'insetImage', '' ),
			'inset_alt'      => mw_arg( $intro, 'insetAlt', '' ),
			'text_order'     => 'second',
			'image_lg_order' => 'second',
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $services, 'eyebrow', '' ),
			'heading' => mw_arg( $services, 'heading', '' ),
		),
		function () {
			mw_render( 'service-grid', array( 'items' => mw_services() ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	echo '<div class="mw-flex-between">';
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $projects, 'eyebrow', '' ),
			'heading' => mw_arg( $projects, 'heading', '' ),
		)
	);
	$button = mw_arg( $projects, 'button', array() );
	if ( ! empty( $button['label'] ) ) {
		mw_button( $button );
	}
	echo '</div>';
	echo '<div class="mw-mt-12">';
	mw_render(
		'project-grid',
		array(
			'items'   => mw_get_project_cards( array( 'limit' => (int) mw_arg( $projects, 'count', 6 ) ) ),
			'filters' => false,
		)
	);
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'bg' => 'espresso', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $why, 'eyebrow', '' ),
			'heading' => mw_arg( $why, 'heading', '' ),
			'light'   => true,
		),
		function () {
			mw_render(
				'feature-grid',
				array(
					'items'   => mw_why_choose_us(),
					'variant' => 'light',
					'columns' => 'three',
				)
			);
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $process, 'eyebrow', '' ),
			'heading' => mw_arg( $process, 'heading', '' ),
		)
	);
	echo '<div class="mw-mt-14">';
	mw_render( 'process-timeline', array( 'steps' => mw_process_steps() ) );
	echo '</div>';
	$button = mw_arg( $process, 'button', array() );
	if ( ! empty( $button['label'] ) ) {
		echo '<div class="mw-mt-12">';
		mw_button( $button );
		echo '</div>';
	}
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	$link = mw_arg( $materials, 'link', array() );
	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $materials, 'eyebrow', '' ),
			'heading' => mw_arg( $materials, 'heading', '' ),
		),
		function () use ( $link ) {
			mw_render(
				'swatches',
				array(
					'items'   => mw_material_categories(),
					'compact' => true,
					'note'    => mw_materials_disclaimer(),
					'link'    => array(
						'label' => mw_arg( $link, 'label', '' ),
						'url'   => mw_arg( $link, 'path', '' ),
					),
				)
			);
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $testimonials, 'eyebrow', '' ),
			'heading' => mw_arg( $testimonials, 'heading', '' ),
		)
	);
	if ( mw_arg( $testimonials, 'note', '' ) ) {
		echo '<p class="mw-note">' . esc_html( $testimonials['note'] ) . '</p>';
	}
	echo '<div class="mw-mt-10">';
	mw_render( 'testimonials', array( 'items' => mw_testimonials() ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render( 'cta', array() );
}

/**
 * About — pages/About.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_about( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$approach = mw_arg( $copy, 'approach', array() );
	$area     = mw_arg( $copy, 'area', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'about' ),
		)
	);

	mw_render(
		'split',
		array(
			'heading' => array(
				'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
				'heading'     => mw_arg( $intro, 'heading', '' ),
				'description' => mw_arg( $intro, 'description', '' ),
			),
			'text'    => mw_arg( $intro, 'secondary', '' ),
			'image'   => mw_arg( $intro, 'image', 'aboutSecondary' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $approach, 'eyebrow', '' ),
			'heading' => mw_arg( $approach, 'heading', '' ),
		),
		function () use ( $approach ) {
			mw_render(
				'feature-grid',
				array(
					'items'   => mw_arg( $approach, 'points', array() ),
					'variant' => 'numbered',
					'columns' => 'two',
				)
			);
		},
		'mw-mt-14'
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render(
		'split',
		array(
			'heading'        => array(
				'eyebrow'     => mw_arg( $area, 'eyebrow', '' ),
				'heading'     => mw_arg( $area, 'heading', '' ),
				'description' => str_replace( '{serviceArea}', mw_site( 'serviceArea' ), mw_arg( $area, 'description', '' ) ),
			),
			'text'           => mw_arg( $area, 'secondary', '' ),
			'image'          => mw_arg( $area, 'image', 'kitchen.6' ),
			'alt'            => mw_arg( $area, 'alt', '' ),
			'frame'          => '4x3',
			'image_lg_order' => 'first',
		)
	);

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Kitchens — pages/Kitchens.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_kitchens( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$styles   = mw_arg( $copy, 'styles', array() );
	$features = mw_arg( $copy, 'features', array() );
	$gallery  = mw_arg( $copy, 'gallery', array() );
	$process  = mw_arg( $copy, 'process', array() );
	$faq      = mw_arg( $copy, 'faq', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'heroKitchen' ),
		)
	);

	mw_render(
		'split',
		array(
			'heading' => array(
				'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
				'heading'     => mw_arg( $intro, 'heading', '' ),
				'description' => mw_arg( $intro, 'description', '' ),
			),
			'button'  => mw_arg( $intro, 'button', array() ),
			'image'   => mw_arg( $intro, 'image', 'kitchen.3' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $styles, 'eyebrow', '' ),
			'heading' => mw_arg( $styles, 'heading', '' ),
		),
		function () {
			mw_render( 'card-grid', array( 'items' => mw_kitchen_style_items(), 'variant' => 'style' ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render(
		'split',
		array(
			'heading'        => array(
				'eyebrow' => mw_arg( $features, 'eyebrow', '' ),
				'heading' => mw_arg( $features, 'heading', '' ),
			),
			'extra'          => array(
				'section' => 'checklist',
				'args'    => array(
					'items'   => mw_content( 'kitchen.features', array() ),
					'columns' => 2,
				),
			),
			'image'          => mw_arg( $features, 'image', 'kitchen.5' ),
			'alt'            => mw_arg( $features, 'alt', '' ),
			'image_lg_order' => 'first',
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $gallery, 'eyebrow', '' ),
			'heading' => mw_arg( $gallery, 'heading', '' ),
		),
		function () {
			mw_render( 'gallery-grid', array( 'items' => mw_gallery_items( 'kitchen' ) ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $process, 'eyebrow', '' ),
			'heading' => mw_arg( $process, 'heading', '' ),
		)
	);
	echo '<div class="mw-mt-14">';
	mw_render( 'process-timeline', array( 'steps' => mw_process_steps() ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container mw-container--text' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $faq, 'eyebrow', '' ),
			'heading' => mw_arg( $faq, 'heading', '' ),
		),
		function () {
			mw_render(
				'faq',
				array(
					'items'      => mw_content( 'kitchen.faqs', array() ),
					'open_first' => true,
				)
			);
		},
		'mw-mt-10'
	);
	mw_section_close( array( 'wrap' => 'mw-container mw-container--text' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Wardrobes — pages/Wardrobes.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_wardrobes( $copy ) {
	$hero     = mw_arg( $copy, 'hero', array() );
	$intro    = mw_arg( $copy, 'intro', array() );
	$types    = mw_arg( $copy, 'types', array() );
	$internal = mw_arg( $copy, 'internal', array() );
	$benefits = mw_arg( $copy, 'benefits', array() );
	$gallery  = mw_arg( $copy, 'gallery', array() );
	$process  = mw_arg( $copy, 'process', array() );
	$faq      = mw_arg( $copy, 'faq', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'wardrobe.0' ),
		)
	);

	mw_render(
		'split',
		array(
			'heading' => array(
				'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
				'heading'     => mw_arg( $intro, 'heading', '' ),
				'description' => mw_arg( $intro, 'description', '' ),
			),
			'button'  => mw_arg( $intro, 'button', array() ),
			'image'   => mw_arg( $intro, 'image', 'wardrobe.2' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $types, 'eyebrow', '' ),
			'heading' => mw_arg( $types, 'heading', '' ),
		),
		function () {
			mw_render( 'card-grid', array( 'items' => mw_wardrobe_type_items(), 'variant' => 'style' ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render(
		'split',
		array(
			'heading'        => array(
				'eyebrow'     => mw_arg( $internal, 'eyebrow', '' ),
				'heading'     => mw_arg( $internal, 'heading', '' ),
				'description' => mw_arg( $internal, 'description', '' ),
			),
			'extra'          => array(
				'section' => 'checklist',
				'args'    => array(
					'items'   => mw_content( 'wardrobe.features', array() ),
					'columns' => 2,
				),
			),
			'image'          => mw_arg( $internal, 'image', 'wardrobe.5' ),
			'alt'            => mw_arg( $internal, 'alt', '' ),
			'image_lg_order' => 'first',
		)
	);

	mw_section_open( array( 'bg' => 'espresso', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $benefits, 'eyebrow', '' ),
			'heading' => mw_arg( $benefits, 'heading', '' ),
			'light'   => true,
		),
		function () {
			mw_render(
				'feature-grid',
				array(
					'items'   => array_slice( mw_why_choose_us(), 0, 3 ),
					'variant' => 'light',
					'columns' => 'three',
				)
			);
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $gallery, 'eyebrow', '' ),
			'heading' => mw_arg( $gallery, 'heading', '' ),
		),
		function () {
			mw_render( 'gallery-grid', array( 'items' => mw_gallery_items( 'wardrobe' ) ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $process, 'eyebrow', '' ),
			'heading' => mw_arg( $process, 'heading', '' ),
		)
	);
	echo '<div class="mw-mt-14">';
	mw_render( 'process-timeline', array( 'steps' => mw_process_steps() ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container mw-container--text' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $faq, 'eyebrow', '' ),
			'heading' => mw_arg( $faq, 'heading', '' ),
		),
		function () {
			mw_render(
				'faq',
				array(
					'items'      => mw_content( 'wardrobe.faqs', array() ),
					'open_first' => true,
				)
			);
		},
		'mw-mt-10'
	);
	mw_section_close( array( 'wrap' => 'mw-container mw-container--text' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Interior Woodwork — pages/InteriorWoodwork.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_interior_woodwork( $copy ) {
	$hero       = mw_arg( $copy, 'hero', array() );
	$intro      = mw_arg( $copy, 'intro', array() );
	$categories = mw_arg( $copy, 'categories', array() );
	$process    = mw_arg( $copy, 'process', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'living.6' ),
		)
	);

	mw_render(
		'split',
		array(
			'heading' => array(
				'eyebrow'     => mw_arg( $intro, 'eyebrow', '' ),
				'heading'     => mw_arg( $intro, 'heading', '' ),
				'description' => mw_arg( $intro, 'description', '' ),
			),
			'image'   => mw_arg( $intro, 'image', 'living.2' ),
			'alt'     => mw_arg( $intro, 'alt', '' ),
		)
	);

	mw_section_open( array( 'bg' => 'stone', 'wrap' => 'mw-container' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $categories, 'eyebrow', '' ),
			'heading' => mw_arg( $categories, 'heading', '' ),
		),
		function () {
			mw_render( 'card-grid', array( 'items' => mw_interior_woodwork_items(), 'variant' => 'category' ) );
		}
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open( array( 'wrap' => 'mw-container' ) );
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $process, 'eyebrow', '' ),
			'heading' => mw_arg( $process, 'heading', '' ),
		)
	);
	echo '<div class="mw-mt-14">';
	mw_render( 'process-timeline', array( 'steps' => mw_process_steps() ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Projects — pages/Projects.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_projects_page( $copy ) {
	$hero       = mw_arg( $copy, 'hero', array() );
	$categories = mw_arg( $copy, 'categories', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'living.3' ),
		)
	);

	mw_section_open(
		array(
			'size' => 'tight',
			'wrap' => 'mw-container',
		)
	);
	mw_render(
		'heading',
		array(
			'eyebrow'     => mw_arg( $categories, 'eyebrow', '' ),
			'heading'     => mw_arg( $categories, 'heading', '' ),
			'description' => mw_arg( $categories, 'description', '' ),
		)
	);
	mw_render(
		'project-grid',
		array(
			'items'   => mw_get_project_cards( array( 'limit' => 24 ) ),
			'filters' => true,
		)
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Materials & Finishes — pages/MaterialsFinishes.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_materials( $copy ) {
	$hero        = mw_arg( $copy, 'hero', array() );
	$overview    = mw_arg( $copy, 'overview', array() );
	$countertops = mw_arg( $copy, 'countertops', array() );
	$availability = mw_arg( $copy, 'availability', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'short' ),
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'kitchen.1' ),
		)
	);

	mw_section_open(
		array(
			'size' => 'medium',
			'wrap' => 'mw-container',
		)
	);
	mw_heading_block(
		array(
			'eyebrow'     => mw_arg( $overview, 'eyebrow', '' ),
			'heading'     => mw_arg( $overview, 'heading', '' ),
			'description' => mw_arg( $overview, 'description', '' ),
		),
		function () {
			mw_render( 'swatches', array( 'items' => mw_material_categories() ) );
		},
		'mw-mt-14'
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open(
		array(
			'bg'   => 'stone',
			'size' => 'medium',
			'wrap' => 'mw-container',
		)
	);
	mw_render(
		'split',
		array(
			'class'   => 'mw-section--plain',
			'heading' => array(
				'eyebrow'     => mw_arg( $countertops, 'eyebrow', '' ),
				'heading'     => mw_arg( $countertops, 'heading', '' ),
				'description' => mw_arg( $countertops, 'description', '' ),
			),
			'image'   => mw_arg( $countertops, 'image', 'kitchen.4' ),
			'alt'     => mw_arg( $countertops, 'alt', '' ),
			'frame'   => '4x3',
		)
	);
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_section_open(
		array(
			'size' => 'medium',
			'wrap' => 'mw-container',
		)
	);
	echo '<div class="mw-panel mw-panel--note">';
	echo '<h3 class="mw-panel__title">' . esc_html( mw_arg( $availability, 'heading', '' ) ) . '</h3>';
	echo '<p class="mw-panel__text">' . esc_html( mw_materials_disclaimer() ) . '</p>';
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Process — pages/Process.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_process_page( $copy ) {
	$hero  = mw_arg( $copy, 'hero', array() );
	$steps = mw_arg( $copy, 'steps', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'short' ),
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'living.4' ),
		)
	);

	mw_section_open( array( 'wrap' => 'mw-container mw-container--narrow' ) );
	mw_heading_block(
		array(
			'eyebrow' => mw_arg( $steps, 'eyebrow', '' ),
			'heading' => mw_arg( $steps, 'heading', '' ),
		),
		function () {
			mw_render(
				'process-timeline',
				array(
					'steps'  => mw_detailed_process_steps(),
					'layout' => 'vertical',
				)
			);
		},
		'mw-mt-16'
	);
	mw_section_close( array( 'wrap' => 'mw-container mw-container--narrow' ) );

	mw_render( 'cta', mw_arg( $copy, 'cta', array() ) );
}

/**
 * Get a Quote — pages/GetQuote.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_get_quote( $copy ) {
	$hero = mw_arg( $copy, 'hero', array() );
	$form = mw_arg( $copy, 'form', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'compact' ),
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'kitchen.2' ),
		)
	);

	mw_section_open(
		array(
			'size' => 'tight',
			'wrap' => 'mw-container mw-container--center',
		)
	);
	echo '<div class="mw-quote-layout">';
	echo '<div>';
	mw_render(
		'quote-form',
		array(
			'form'    => 'quote',
			'heading' => mw_arg( $form, 'heading', '' ),
			'submit'  => mw_arg( $form, 'submit', '' ),
			'note'    => mw_arg( $form, 'note', '' ),
		)
	);
	echo '</div>';
	mw_render( 'contact-panels', array( 'variant' => 'quote' ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container mw-container--center' ) );
}

/**
 * Contact — pages/Contact.tsx
 *
 * @param array $copy Page copy.
 */
function mw_render_contact( $copy ) {
	$hero = mw_arg( $copy, 'hero', array() );
	$form = mw_arg( $copy, 'form', array() );

	mw_render(
		'hero',
		array(
			'variant' => 'page',
			'height'  => mw_arg( $hero, 'height', 'compact' ),
			'eyebrow' => mw_arg( $hero, 'eyebrow', '' ),
			'title'   => mw_arg( $hero, 'heading', '' ),
			'text'    => mw_arg( $hero, 'description', '' ),
			'image'   => mw_arg( $hero, 'image', 'living.5' ),
		)
	);

	mw_section_open(
		array(
			'size' => 'tight',
			'wrap' => 'mw-container',
		)
	);
	echo '<div class="mw-contact-layout">';
	echo '<div>';
	mw_render(
		'heading',
		array(
			'eyebrow' => mw_arg( $form, 'eyebrow', '' ),
			'heading' => mw_arg( $form, 'heading', '' ),
		)
	);
	mw_render(
		'contact-form',
		array(
			'form'   => 'contact',
			'submit' => mw_arg( $form, 'submit', '' ),
		)
	);
	echo '</div>';
	mw_render( 'contact-panels', array( 'variant' => 'contact' ) );
	echo '</div>';
	mw_section_close( array( 'wrap' => 'mw-container' ) );
}
