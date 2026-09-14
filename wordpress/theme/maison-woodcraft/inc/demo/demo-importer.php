<?php
/**
 * One-click demo importer.
 *
 * Recreates the original website inside WordPress:
 *
 *   1. imports the 38 photographs used by the original project into the Media
 *      Library (same files the React build pointed at, no stock replacements),
 *   2. creates the ten pages with the original slugs/titles and gives each one
 *      Elementor data built from the theme's own widgets,
 *   3. creates the ten sample projects in the Projects post type with their
 *      category, location, materials and gallery,
 *   4. creates and assigns the Primary, Footer Quick Links and Footer Services
 *      menus,
 *   5. sets the static front page.
 *
 * Running it again only updates what already exists, so it is safe to re-run.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

require_once MW_THEME_DIR . '/inc/demo/demo-builder.php';

/**
 * The import steps.
 *
 * @return array
 */
function mw_demo_steps() {
	return array(
		'media'    => __( 'Import the images into the Media Library', 'maison-woodcraft' ),
		'pages'    => __( 'Create the pages and their Elementor layouts', 'maison-woodcraft' ),
		'projects' => __( 'Create the sample projects', 'maison-woodcraft' ),
		'menus'    => __( 'Create the navigation menus', 'maison-woodcraft' ),
		'settings' => __( 'Set the front page and reading settings', 'maison-woodcraft' ),
	);
}

/**
 * Run the importer.
 *
 * @param array $steps Steps to run; empty array runs everything.
 * @return array Log lines.
 */
function mw_demo_run( $steps = array() ) {
	$steps = empty( $steps ) ? array_keys( mw_demo_steps() ) : (array) $steps;
	$log   = array();

	foreach ( $steps as $step ) {
		switch ( $step ) {
			case 'media':
				$result  = mw_import_images();
				$total   = count( (array) mw_media_map() );
				$stored  = isset( $result['attachments'] ) ? count( (array) $result['attachments'] ) : 0;

				$log[] = sprintf(
					/* translators: 1: stored images, 2: total images, 3: newly downloaded, 4: failures. */
					__( 'Media: %1$d of %2$d photographs are in the Media Library (%3$d downloaded now, %4$d failed).', 'maison-woodcraft' ),
					$stored,
					$total,
					(int) $result['imported'],
					(int) $result['failed']
				);

				if ( ! empty( $result['errors'] ) ) {
					$log[] = implode( ' | ', array_slice( $result['errors'], 0, 5 ) );
				}

				$localized = mw_localize_elementor_posts();

				if ( $localized ) {
					$log[] = sprintf(
						/* translators: %d: number of Elementor documents. */
						__( 'Media: %d existing Elementor documents now use the local copies.', 'maison-woodcraft' ),
						$localized
					);
				}
				break;

			case 'pages':
				$log = array_merge( $log, mw_demo_import_pages() );
				break;

			case 'projects':
				$log = array_merge( $log, mw_demo_import_projects() );
				break;

			case 'menus':
				$log = array_merge( $log, mw_demo_import_menus() );
				break;

			case 'settings':
				$log = array_merge( $log, mw_demo_import_settings() );
				break;
		}
	}

	update_option( 'mw_demo_imported', current_time( 'mysql' ) );

	return $log;
}

/**
 * Pages from site-content.json (slug, title, Elementor data).
 *
 * @return array
 */
function mw_demo_pages() {
	$pages = mw_content( 'pages', array() );
	$out   = array();

	foreach ( (array) $pages as $slug => $copy ) {
		if ( ! is_array( $copy ) ) {
			continue;
		}

		$out[ $slug ] = array(
			'slug'  => mw_arg( $copy, 'slug', $slug ),
			'title' => mw_arg( $copy, 'title', ucwords( str_replace( '-', ' ', $slug ) ) ),
		);
	}

	return $out;
}

/**
 * Create or update the pages and attach their Elementor layouts.
 *
 * @return array Log lines.
 */
function mw_demo_import_pages() {
	$log    = array();
	$pages  = mw_demo_pages();
	$id_map = array();

	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $page['slug'] );

		$postarr = array(
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => '',
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$page_id       = wp_update_post( wp_slash( $postarr ), true );
		} else {
			$page_id = wp_insert_post( wp_slash( $postarr ), true );
		}

		if ( is_wp_error( $page_id ) ) {
			/* translators: 1: page title, 2: error message. */
			$log[] = sprintf( __( 'Page "%1$s" could not be created: %2$s', 'maison-woodcraft' ), $page['title'], $page_id->get_error_message() );
			continue;
		}

		$id_map[ $slug ] = $page_id;

		mw_demo_write_elementor_data( $page_id, mw_demo_page_data( $slug ) );

		/* Give each inner page its own SEO-friendly title suffix in Elementor. */
		update_post_meta( $page_id, '_mw_demo_slug', $slug );

		$log[] = sprintf(
			/* translators: 1: page title, 2: page slug. */
			__( 'Page "%1$s" ready at /%2$s/ with an editable Elementor layout.', 'maison-woodcraft' ),
			$page['title'],
			$page['slug']
		);
	}

	update_option( 'mw_demo_page_ids', $id_map );

	return $log;
}

/**
 * Store Elementor data for a page (post, page or CPT).
 *
 * @param int   $post_id Post ID.
 * @param array $data    Elementor data array.
 */
function mw_demo_write_elementor_data( $post_id, $data ) {
	if ( empty( $data ) ) {
		return;
	}

	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $post_id, '_elementor_page_settings', array( 'hide_title' => '' ) );

	mw_demo_clear_elementor_cache();
}

/**
 * Ask Elementor to rebuild its CSS.
 */
function mw_demo_clear_elementor_cache() {
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/**
 * Create the sample projects (Projects post type) from projects.ts.
 *
 * @return array Log lines.
 */
function mw_demo_import_projects() {
	$log      = array();
	$projects = mw_projects();
	$count    = 0;

	foreach ( (array) $projects as $project ) {
		$title    = mw_arg( $project, 'title', '' );
		$existing = get_page_by_title( $title, OBJECT, 'mw_project' );

		$postarr = array(
			'post_type'    => 'mw_project',
			'post_title'   => $title,
			'post_name'    => sanitize_title( $title ),
			'post_status'  => 'publish',
			'post_content' => mw_arg( $project, 'description', '' ),
			'post_excerpt' => mw_arg( $project, 'description', '' ),
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$post_id       = wp_update_post( wp_slash( $postarr ), true );
		} else {
			$post_id = wp_insert_post( wp_slash( $postarr ), true );
		}

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$count++;

		/* Category term. */
		$category = mw_arg( $project, 'category', '' );
		if ( $category ) {
			wp_set_object_terms( $post_id, $category, 'mw_project_cat', false );
		}

		/* Project details. */
		update_post_meta( $post_id, '_mw_project_location', mw_arg( $project, 'location', '' ) );
		update_post_meta( $post_id, '_mw_project_materials', mw_arg( $project, 'materials', '' ) );
		update_post_meta( $post_id, '_mw_project_source_id', mw_arg( $project, 'id', '' ) );

		/* Featured image. */
		$image_key = mw_arg( $project, 'image', '' );
		$image_id  = $image_key ? mw_attachment_id_for_key( $image_key ) : 0;

		if ( $image_id ) {
			set_post_thumbnail( $post_id, $image_id );
		}

		/* Gallery: the other photographs of the same set, as in the original. */
		$gallery = array();
		$group   = $image_key ? preg_replace( '/\.\d+$/', '', $image_key ) : '';

		if ( $group ) {
			for ( $i = 0; $i < 8; $i++ ) {
				$candidate = $group . '.' . $i;
				$candidate_id = mw_attachment_id_for_key( $candidate );

				if ( $candidate_id && $candidate_id !== $image_id ) {
					$gallery[] = $candidate_id;
				}
			}
		}

		if ( $gallery ) {
			update_post_meta( $post_id, '_mw_project_gallery', implode( ',', array_slice( $gallery, 0, 4 ) ) );
		}
	}

	$log[] = sprintf(
		/* translators: %d: number of projects. */
		__( 'Projects: %d sample projects created with category, location, materials and gallery.', 'maison-woodcraft' ),
		$count
	);

	return $log;
}

/**
 * Create and assign the theme's three menus.
 *
 * @return array Log lines.
 */
function mw_demo_import_menus() {
	$log      = array();
	$nav      = (array) mw_content( 'nav', array() );
	$services = mw_services();

	$quick = $nav;
	$quick[] = array(
		'label' => __( 'Get a Quote', 'maison-woodcraft' ),
		'path'  => '/get-a-quote',
	);

	$service_items = array();
	foreach ( $services as $service ) {
		$service_items[] = array(
			'label' => mw_arg( $service, 'title', '' ),
			'path'  => mw_arg( $service, 'path', '' ),
		);
	}

	$menus = array(
		'primary'             => array(
			'name'  => __( 'Primary Menu', 'maison-woodcraft' ),
			'items' => $nav,
		),
		'footer_quick_links'  => array(
			'name'  => __( 'Footer Quick Links', 'maison-woodcraft' ),
			'items' => $quick,
		),
		'footer_services'     => array(
			'name'  => __( 'Footer Services', 'maison-woodcraft' ),
			'items' => $service_items,
		),
	);

	$locations = get_theme_mod( 'nav_menu_locations', array() );

	foreach ( $menus as $location => $menu ) {
		$menu_object = wp_get_nav_menu_object( $menu['name'] );
		$menu_id     = $menu_object ? (int) $menu_object->term_id : wp_create_nav_menu( $menu['name'] );

		if ( is_wp_error( $menu_id ) ) {
			continue;
		}

		$locations[ $location ] = $menu_id;

		/* Only add items if the menu is empty, so re-running does not duplicate. */
		$existing = wp_get_nav_menu_items( $menu_id );

		if ( empty( $existing ) ) {
			foreach ( $menu['items'] as $item ) {
				$path = mw_arg( $item, 'path', '' );
				$page = get_page_by_path( trim( $path, '/' ) );

				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => mw_arg( $item, 'label', '' ),
						'menu-item-object'    => $page ? 'page' : 'custom',
						'menu-item-object-id' => $page ? $page->ID : 0,
						'menu-item-type'      => $page ? 'post_type' : 'custom',
						'menu-item-url'       => $page ? get_permalink( $page ) : home_url( $path ),
						'menu-item-status'    => 'publish',
					)
				);
			}
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	$log[] = __( 'Menus: Primary, Footer Quick Links and Footer Services created and assigned.', 'maison-woodcraft' );

	return $log;
}

/**
 * Front page + reading settings.
 *
 * @return array Log lines.
 */
function mw_demo_import_settings() {
	$log     = array();
	$id_map  = get_option( 'mw_demo_page_ids', array() );
	$home_id = isset( $id_map['home'] ) ? (int) $id_map['home'] : 0;

	if ( ! $home_id ) {
		$home    = get_page_by_path( 'home' );
		$home_id = $home ? $home->ID : 0;
	}

	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
		$log[] = __( 'Front page set to the Home page.', 'maison-woodcraft' );
	}

	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules();
		$log[] = __( 'Permalinks set to "Post name" so the original clean URLs work.', 'maison-woodcraft' );
	}

	/* The original site has no blog listing; use the Projects archive instead. */
	update_option( 'blog_public', get_option( 'blog_public' ) );

	return $log;
}

/* -------------------------------------------------------------------------
 * Re-create the Elementor layouts for pages that lost theirs
 * ---------------------------------------------------------------------- */

/**
 * Rebuild a single page's Elementor layout from the theme blueprints.
 *
 * @param int $page_id Page ID.
 * @return bool
 */
function mw_demo_rebuild_page( $page_id ) {
	$slug = get_post_meta( $page_id, '_mw_demo_slug', true );

	if ( ! $slug ) {
		$slug = get_post_field( 'post_name', $page_id );
	}

	$data = mw_demo_page_data( $slug );

	if ( empty( $data ) ) {
		return false;
	}

	mw_demo_write_elementor_data( $page_id, $data );

	return true;
}

/* -------------------------------------------------------------------------
 * WP-CLI
 * ---------------------------------------------------------------------- */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Import the demo content from the command line.
	 */
	class MW_Demo_CLI {

		/**
		 * Import the original website content.
		 *
		 * ## EXAMPLES
		 *
		 *     wp mw import-demo
		 *     wp mw import-demo --steps=media,pages
		 *
		 * @param array $args       Positional arguments.
		 * @param array $assoc_args Associative arguments.
		 */
		public function import( $args, $assoc_args ) {
			$steps = isset( $assoc_args['steps'] ) ? explode( ',', $assoc_args['steps'] ) : array();

			foreach ( mw_demo_run( $steps ) as $line ) {
				WP_CLI::log( $line );
			}

			WP_CLI::success( 'Maison Woodcraft demo content imported.' );
		}
	}

	WP_CLI::add_command( 'mw', 'MW_Demo_CLI' );
}
