<?php
/**
 * Projects custom post type.
 *
 * The original "Selected Projects" are presentation concepts rendered from a
 * data file. In WordPress they become real, editable content:
 *
 *   - Projects (title, description, category, location, materials, gallery),
 *   - a Project Categories taxonomy matching the original filters
 *     (Kitchens, Wardrobes, Living Rooms, Bedrooms, TV Units, Complete Interiors),
 *   - Elementor support for the archive and single templates.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Projects post type and taxonomy.
 */
function mw_register_projects() {
	register_post_type(
		'mw_project',
		array(
			'labels'             => array(
				'name'                  => __( 'Projects', 'maison-woodcraft' ),
				'singular_name'         => __( 'Project', 'maison-woodcraft' ),
				'menu_name'             => __( 'Projects', 'maison-woodcraft' ),
				'add_new'               => __( 'Add Project', 'maison-woodcraft' ),
				'add_new_item'          => __( 'Add New Project', 'maison-woodcraft' ),
				'edit_item'             => __( 'Edit Project', 'maison-woodcraft' ),
				'new_item'              => __( 'New Project', 'maison-woodcraft' ),
				'view_item'             => __( 'View Project', 'maison-woodcraft' ),
				'search_items'          => __( 'Search Projects', 'maison-woodcraft' ),
				'not_found'             => __( 'No projects found', 'maison-woodcraft' ),
				'not_found_in_trash'    => __( 'No projects found in Trash', 'maison-woodcraft' ),
				'all_items'             => __( 'All Projects', 'maison-woodcraft' ),
			),
			'public'             => true,
			'has_archive'        => false, // The Projects page (with filters) is the archive.
			'rewrite'            => array(
				'slug'       => 'project',
				'with_front' => false,
			),
			'menu_position'      => 21,
			'menu_icon'          => 'dashicons-portfolio',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions', 'custom-fields' ),
			'show_in_rest'       => true,
			'taxonomies'         => array( 'mw_project_cat' ),
		)
	);

	register_taxonomy(
		'mw_project_cat',
		'mw_project',
		array(
			'labels'            => array(
				'name'          => __( 'Project Categories', 'maison-woodcraft' ),
				'singular_name' => __( 'Project Category', 'maison-woodcraft' ),
				'menu_name'     => __( 'Categories', 'maison-woodcraft' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'project-category',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'mw_register_projects' );

/**
 * Elementor needs to know the Projects post type is editable with it.
 *
 * @param array $post_types Supported post types.
 * @return array
 */
function mw_elementor_cpt_support( $post_types ) {
	$post_types[] = 'mw_project';
	return $post_types;
}
add_filter( 'elementor/utils/get_public_post_types', 'mw_elementor_cpt_support' );

/* -------------------------------------------------------------------------
 * Project meta
 * ---------------------------------------------------------------------- */

/**
 * Register the project meta boxes.
 */
function mw_project_meta_boxes() {
	add_meta_box(
		'mw-project-details',
		__( 'Project details', 'maison-woodcraft' ),
		'mw_project_meta_box',
		'mw_project',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'mw_project_meta_boxes' );

/**
 * Render the project details meta box.
 *
 * @param WP_Post $post Project.
 */
function mw_project_meta_box( $post ) {
	wp_nonce_field( 'mw_save_project', 'mw_project_nonce' );

	$location  = get_post_meta( $post->ID, '_mw_project_location', true );
	$materials = get_post_meta( $post->ID, '_mw_project_materials', true );
	$gallery   = get_post_meta( $post->ID, '_mw_project_gallery', true );
	$gallery   = is_array( $gallery ) ? $gallery : array_filter( array_map( 'intval', explode( ',', (string) $gallery ) ) );
	?>
	<p>
		<label for="mw_project_location"><strong><?php esc_html_e( 'Location', 'maison-woodcraft' ); ?></strong></label><br>
		<input type="text" class="widefat" id="mw_project_location" name="mw_project_location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'e.g. DHA Phase 6, Lahore', 'maison-woodcraft' ); ?>">
	</p>
	<p>
		<label for="mw_project_materials"><strong><?php esc_html_e( 'Materials & Finishes', 'maison-woodcraft' ); ?></strong></label><br>
		<input type="text" class="widefat" id="mw_project_materials" name="mw_project_materials" value="<?php echo esc_attr( $materials ); ?>" placeholder="<?php esc_attr_e( 'e.g. Walnut veneer, engineered stone counter', 'maison-woodcraft' ); ?>">
	</p>
	<p>
		<label for="mw_project_gallery"><strong><?php esc_html_e( 'Gallery attachment IDs', 'maison-woodcraft' ); ?></strong></label><br>
		<input type="text" class="widefat" id="mw_project_gallery" name="mw_project_gallery" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>" placeholder="12,34,56">
		<span class="description"><?php esc_html_e( 'Comma separated Media Library IDs. The Elementor "Project Gallery" widget can also be used on the project template.', 'maison-woodcraft' ); ?></span>
	</p>
	<?php
}

/**
 * Save the project meta.
 *
 * @param int $post_id Project ID.
 */
function mw_save_project_meta( $post_id ) {
	if ( ! isset( $_POST['mw_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mw_project_nonce'] ) ), 'mw_save_project' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'_mw_project_location'  => 'mw_project_location',
		'_mw_project_materials' => 'mw_project_materials',
	);

	foreach ( $fields as $meta_key => $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	if ( isset( $_POST['mw_project_gallery'] ) ) {
		$ids = array_filter( array_map( 'intval', explode( ',', sanitize_text_field( wp_unslash( $_POST['mw_project_gallery'] ) ) ) ) );
		update_post_meta( $post_id, '_mw_project_gallery', $ids );
	}
}
add_action( 'save_post_mw_project', 'mw_save_project_meta' );

/* -------------------------------------------------------------------------
 * Queries used by the theme + widgets
 * ---------------------------------------------------------------------- */

/**
 * Original media key of a project (set by the demo importer).
 *
 * Projects imported before the key was stored are matched through their
 * original source id (p1…p10) so every project keeps its own photograph.
 *
 * @param int $post_id Project ID.
 * @return string
 */
function mw_project_image_key( $post_id ) {
	$post_id = (int) $post_id;

	if ( ! $post_id ) {
		return '';
	}

	$key = (string) get_post_meta( $post_id, '_mw_project_image_key', true );
	if ( $key ) {
		return $key;
	}

	$source_id = (string) get_post_meta( $post_id, '_mw_project_source_id', true );
	if ( ! $source_id ) {
		return '';
	}

	foreach ( mw_projects() as $project ) {
		if ( mw_arg( $project, 'id', '' ) === $source_id ) {
			$key = (string) mw_arg( $project, 'image', '' );
			if ( $key ) {
				update_post_meta( $post_id, '_mw_project_image_key', $key );
			}
			return $key;
		}
	}

	return '';
}

/**
 * Fetch projects as card data (imported projects first, sample concepts as the
 * fallback so the design is never empty).
 *
 * @param array $args Optional: category, limit.
 * @return array
 */
function mw_get_project_cards( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'category' => '',
			'limit'    => 12,
		)
	);

	$query_args = array(
		'post_type'           => 'mw_project',
		'posts_per_page'      => $args['limit'],
		'orderby'             => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'ignore_sticky_posts' => true,
	);

	if ( $args['category'] && 'All' !== $args['category'] ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'mw_project_cat',
				'field'    => 'name',
				'terms'    => $args['category'],
			),
		);
	}

	$query = new WP_Query( $query_args );
	$cards = array();

	foreach ( $query->posts as $post ) {
		$terms    = get_the_terms( $post->ID, 'mw_project_cat' );
		$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
		$image    = get_the_post_thumbnail_url( $post->ID, 'mw-card' );

		/*
		 * No featured image yet (for example the demo import ran before the
		 * photographs could be copied into the Media Library): fall back to the
		 * project's own original photograph rather than an unrelated one.
		 */
		if ( ! $image ) {
			$image_key = mw_project_image_key( $post->ID );
			$image     = $image_key ? mw_image_url( $image_key ) : '';
		}

		if ( ! $image ) {
			$image = mw_image_url( 'living.3' );
		}

		$cards[] = array(
			'id'        => $post->ID,
			'title'     => get_the_title( $post ),
			'category'  => $category,
			'location'  => get_post_meta( $post->ID, '_mw_project_location', true ),
			'materials' => get_post_meta( $post->ID, '_mw_project_materials', true ),
			'description' => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 26 ),
			'image'     => $image,
			'image_id'  => get_post_thumbnail_id( $post->ID ),
			'url'       => get_permalink( $post ),
		);
	}

	if ( empty( $cards ) ) {
		// Fall back to the original sample concepts so the layout is never empty.
		$sample = mw_projects();
		$cards  = array_slice( $sample, 0, (int) $args['limit'] );
	}

	return $cards;
}

/**
 * Gallery of a project as image URLs, in the stored order.
 *
 * Uses Media Library attachments when the photographs were imported and falls
 * back to the original media keys otherwise.
 *
 * @param int $post_id Project ID.
 * @return array[] Each item: url, id, alt.
 */
function mw_project_gallery_images( $post_id ) {
	$post_id = (int) $post_id;
	$ids     = get_post_meta( $post_id, '_mw_project_gallery', true );
	$ids     = is_array( $ids ) ? $ids : array_filter( array_map( 'intval', explode( ',', (string) $ids ) ) );
	$items   = array();

	foreach ( $ids as $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'mw-card' );
		if ( $url ) {
			$items[] = array(
				'id'  => (int) $attachment_id,
				'url' => $url,
				'alt' => (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
			);
		}
	}

	if ( ! $items ) {
		$keys = get_post_meta( $post_id, '_mw_project_gallery_keys', true );
		$keys = is_array( $keys ) ? $keys : array_filter( array_map( 'trim', explode( ',', (string) $keys ) ) );

		foreach ( $keys as $key ) {
			$image = mw_resolve_image( $key, get_the_title( $post_id ) );
			if ( ! empty( $image['url'] ) ) {
				$items[] = $image;
			}
		}
	}

	return $items;
}
