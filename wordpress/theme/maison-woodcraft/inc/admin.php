<?php
/**
 * Admin: the "Maison Woodcraft" setup screen.
 *
 * Gives a short, honest status report of everything the theme needs (Elementor,
 * theme locations, demo content, forms, media) and the buttons to run the demo
 * importer without touching the command line.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the setup screen under Appearance.
 */
function mw_admin_menu() {
	add_theme_page(
		__( 'Maison Woodcraft Setup', 'maison-woodcraft' ),
		__( 'Maison Woodcraft', 'maison-woodcraft' ),
		'edit_theme_options',
		'maison-woodcraft',
		'mw_admin_page'
	);
}
add_action( 'admin_menu', 'mw_admin_menu' );

/**
 * Current status of the theme's dependencies.
 *
 * @return array[]
 */
function mw_admin_status() {
	$status  = array();
	$pages   = mw_demo_pages();
	$missing = array();

	foreach ( $pages as $slug => $page ) {
		if ( ! get_page_by_path( $page['slug'] ) ) {
			$missing[] = $page['slug'];
		}
	}

	$status[] = array(
		'label' => __( 'Elementor', 'maison-woodcraft' ),
		'state' => mw_is_elementor_active() ? 'ok' : 'warn',
		'value' => mw_is_elementor_active()
			? sprintf( __( 'Active (version %s)', 'maison-woodcraft' ), ELEMENTOR_VERSION )
			: __( 'Not installed — the theme still renders every page natively.', 'maison-woodcraft' ),
	);

	$status[] = array(
		'label' => __( 'Elementor Pro (Theme Builder)', 'maison-woodcraft' ),
		'state' => class_exists( '\ElementorPro\Plugin' ) ? 'ok' : 'warn',
		'value' => class_exists( '\ElementorPro\Plugin' )
			? __( 'Active — you can replace the header, footer, single and archive templates.', 'maison-woodcraft' )
			: __( 'Optional. Without it the theme uses its own header and footer, which match the original design.', 'maison-woodcraft' ),
	);

	$status[] = array(
		'label' => __( 'Pages', 'maison-woodcraft' ),
		'state' => empty( $missing ) ? 'ok' : 'warn',
		'value' => empty( $missing )
			? sprintf( __( 'All %d pages exist.', 'maison-woodcraft' ), count( $pages ) )
			: sprintf( __( 'Missing: %s — run the importer.', 'maison-woodcraft' ), implode( ', ', $missing ) ),
	);

	$attachments = get_option( 'mw_media_attachments', array() );
	$status[]    = array(
		'label' => __( 'Photographs', 'maison-woodcraft' ),
		'state' => count( (array) $attachments ) >= 30 ? 'ok' : 'warn',
		'value' => sprintf(
			/* translators: %d: number of imported images. */
			__( '%d of 38 imported into the Media Library.', 'maison-woodcraft' ),
			count( (array) $attachments )
		),
	);

	$recipient = mw_option( 'forms_recipient', get_option( 'admin_email' ) );
	$status[]  = array(
		'label' => __( 'Form recipient', 'maison-woodcraft' ),
		'state' => is_email( $recipient ) ? 'ok' : 'error',
		'value' => $recipient ? $recipient : __( 'Not set — set one in Customize → Maison Woodcraft → Forms.', 'maison-woodcraft' ),
	);

	$entries = wp_count_posts( 'mw_entry' );
	$total   = isset( $entries->publish ) ? (int) $entries->publish : 0;
	$status[] = array(
		'label' => __( 'Form entries', 'maison-woodcraft' ),
		'state' => 'ok',
		'value' => sprintf(
			/* translators: %d: number of entries. */
			_n( '%d entry stored.', '%d entries stored.', $total, 'maison-woodcraft' ),
			$total
		),
	);

	$whatsapp = mw_option( 'whatsapp_number', '' );
	$status[] = array(
		'label' => __( 'WhatsApp number', 'maison-woodcraft' ),
		'state' => $whatsapp ? 'ok' : 'error',
		'value' => $whatsapp ? '+' . ltrim( preg_replace( '/\D/', '', $whatsapp ), '+' ) : __( 'Not set — set it in Customize → Maison Woodcraft → WhatsApp.', 'maison-woodcraft' ),
	);

	$status[] = array(
		'label' => __( 'Permalinks', 'maison-woodcraft' ),
		'state' => '' !== get_option( 'permalink_structure' ) ? 'ok' : 'warn',
		'value' => '' !== get_option( 'permalink_structure' )
			? get_option( 'permalink_structure' )
			: __( 'Plain — switch to "Post name" so /kitchens/, /projects/ … work.', 'maison-woodcraft' ),
	);

	$status[] = array(
		'label' => __( 'Menus', 'maison-woodcraft' ),
		'state' => has_nav_menu( 'primary' ) ? 'ok' : 'warn',
		'value' => has_nav_menu( 'primary' )
			? __( 'Primary, footer quick links and footer services assigned.', 'maison-woodcraft' )
			: __( 'Not assigned — assign them under Appearance → Menus, or run the importer.', 'maison-woodcraft' ),
	);

	return $status;
}

/**
 * Render the setup screen.
 */
function mw_admin_page() {
	$tokens = mw_design_tokens();
	$notice = get_transient( 'mw_demo_notice' );

	if ( $notice ) {
		delete_transient( 'mw_demo_notice' );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Maison Woodcraft', 'maison-woodcraft' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php echo esc_html( $notice ); ?></strong></p>
			</div>
		<?php endif; ?>

		<div class="mw-admin-card">
			<h2><?php esc_html_e( 'Install the original website content', 'maison-woodcraft' ); ?></h2>
			<p class="mw-admin-note">
				<?php esc_html_e( 'The importer recreates the complete original website: the ten pages with their Elementor layouts, the 38 photographs, the sample projects and the navigation menus. It only creates what is missing, so it is safe to run twice.', 'maison-woodcraft' ); ?>
			</p>
			<ul class="mw-admin-status">
				<?php foreach ( mw_demo_steps() as $key => $label ) : ?>
					<li>
						<label>
							<input type="checkbox" class="mw-demo-step" value="<?php echo esc_attr( $key ); ?>" checked>
							<?php echo esc_html( $label ); ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="mw-admin-actions">
				<input type="hidden" name="action" value="mw_demo_import">
				<input type="hidden" name="mw_steps" id="mw-demo-steps" value="">
				<?php wp_nonce_field( 'mw_demo_import' ); ?>
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Import Demo Content', 'maison-woodcraft' ); ?></button>
				<span class="description"><?php esc_html_e( 'Importing the photographs needs an internet connection on the server.', 'maison-woodcraft' ); ?></span>
			</form>
			<script>
				document.querySelector('form.mw-admin-actions').addEventListener('submit', function () {
					var steps = [];
					document.querySelectorAll('.mw-demo-step:checked').forEach(function (box) {
						steps.push(box.value);
					});
					document.getElementById('mw-demo-steps').value = steps.join(',');
				});
			</script>
		</div>

		<div class="mw-admin-card">
			<h2><?php esc_html_e( 'Status', 'maison-woodcraft' ); ?></h2>
			<ul class="mw-admin-status">
				<?php foreach ( mw_admin_status() as $row ) : ?>
					<li>
						<strong><?php echo esc_html( $row['label'] ); ?>:</strong>
						<span class="mw-status-<?php echo esc_attr( $row['state'] ); ?>"><?php echo esc_html( $row['value'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="mw-admin-actions">
				<a class="button" href="<?php echo esc_url( add_query_arg( 'autofocus[panel]', 'mw_business', admin_url( 'customize.php' ) ) ); ?>">
					<?php esc_html_e( 'Business details (Customizer)', 'maison-woodcraft' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=mw_entry' ) ); ?>">
					<?php esc_html_e( 'Form entries', 'maison-woodcraft' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=mw_project' ) ); ?>">
					<?php esc_html_e( 'Projects', 'maison-woodcraft' ); ?>
				</a>
				<?php if ( mw_is_elementor_active() ) : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library&tabs_group=theme' ) ); ?>">
						<?php esc_html_e( 'Elementor Theme Builder', 'maison-woodcraft' ); ?>
					</a>
				<?php else : ?>
					<a class="button" href="<?php echo esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) ); ?>">
						<?php esc_html_e( 'Install Elementor', 'maison-woodcraft' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="mw-admin-card">
			<h2><?php esc_html_e( 'Design system', 'maison-woodcraft' ); ?></h2>
			<p class="mw-admin-note">
				<?php esc_html_e( 'These are the original colours and font stacks. They are applied in Elementor through the theme\'s global defaults — set the same values in Elementor → Site Settings → Global Colors / Global Fonts, or leave the widgets as they are: each one already uses them.', 'maison-woodcraft' ); ?>
			</p>
			<div class="mw-admin-color-grid">
				<?php foreach ( $tokens['colors'] as $name => $color ) : ?>
					<div class="mw-admin-color">
						<span class="swatch" style="background-color:<?php echo esc_attr( $color ); ?>"></span>
						<span><?php echo esc_html( $name . ' — ' . $color ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
			<ul class="mw-admin-status">
				<li><strong><?php esc_html_e( 'Serif', 'maison-woodcraft' ); ?>:</strong> <?php echo esc_html( $tokens['fonts']['serif']['stack'] ); ?></li>
				<li><strong><?php esc_html_e( 'Sans', 'maison-woodcraft' ); ?>:</strong> <?php echo esc_html( $tokens['fonts']['sans']['stack'] ); ?></li>
				<li><strong><?php esc_html_e( 'Container', 'maison-woodcraft' ); ?>:</strong> <?php echo esc_html( $tokens['layout']['container'] . 'px' ); ?></li>
			</ul>
		</div>

		<div class="mw-admin-card">
			<h2><?php esc_html_e( 'Email delivery', 'maison-woodcraft' ); ?></h2>
			<p class="mw-admin-note">
				<?php esc_html_e( 'WordPress sends the form notifications with wp_mail(). For reliable delivery install an SMTP plugin (WP Mail SMTP, FluentSMTP or Post SMTP) and connect it to your hosting mailbox or a service such as Brevo, Mailgun or SendGrid. Every entry is also stored under Form Entries, so nothing is lost if email fails.', 'maison-woodcraft' ); ?>
			</p>
			<div class="mw-admin-actions">
				<a class="button" href="<?php echo esc_url( admin_url( 'plugin-install.php?s=smtp&tab=search&type=term' ) ); ?>">
					<?php esc_html_e( 'Find an SMTP plugin', 'maison-woodcraft' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Handle the import form.
 */
function mw_admin_handle_import() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to import demo content.', 'maison-woodcraft' ), 403 );
	}

	check_admin_referer( 'mw_demo_import' );

	$steps = isset( $_POST['mw_steps'] ) ? array_map( 'sanitize_key', explode( ',', wp_unslash( $_POST['mw_steps'] ) ) ) : array();
	$log   = mw_demo_run( $steps );

	set_transient( 'mw_demo_notice', implode( ' ', $log ), 60 );

	wp_safe_redirect( admin_url( 'themes.php?page=maison-woodcraft' ) );
	exit;
}
add_action( 'admin_post_mw_demo_import', 'mw_admin_handle_import' );

/* -------------------------------------------------------------------------
 * Page list conveniences
 * ---------------------------------------------------------------------- */

/**
 * Add a "Rebuild Elementor layout" row action to pages created by the importer.
 *
 * @param array   $actions Row actions.
 * @param WP_Post $post    Post.
 * @return array
 */
function mw_admin_page_row_actions( $actions, $post ) {
	if ( 'page' !== $post->post_type || ! current_user_can( 'edit_post', $post->ID ) ) {
		return $actions;
	}

	if ( get_post_meta( $post->ID, '_mw_demo_slug', true ) ) {
		$url = wp_nonce_url(
			add_query_arg(
				array(
					'action'  => 'mw_rebuild_page',
					'post'    => $post->ID,
				),
				admin_url( 'admin-post.php' )
			),
			'mw_rebuild_page_' . $post->ID
		);

		$actions['mw_rebuild'] = sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html__( 'Rebuild demo layout', 'maison-woodcraft' ) );
	}

	return $actions;
}
add_filter( 'page_row_actions', 'mw_admin_page_row_actions', 10, 2 );

/**
 * Rebuild one page's Elementor layout (restores the original sections after a
 * page has been emptied in the editor).
 */
function mw_admin_handle_rebuild() {
	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;

	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'You are not allowed to edit this page.', 'maison-woodcraft' ), 403 );
	}

	check_admin_referer( 'mw_rebuild_page_' . $post_id );

	$rebuilt = mw_demo_rebuild_page( $post_id );

	set_transient(
		'mw_demo_notice',
		$rebuilt
			? __( 'The original layout has been rebuilt for this page.', 'maison-woodcraft' )
			: __( 'There is no stored layout for this page.', 'maison-woodcraft' ),
		60
	);

	wp_safe_redirect( admin_url( 'themes.php?page=maison-woodcraft' ) );
	exit;
}
add_action( 'admin_post_mw_rebuild_page', 'mw_admin_handle_rebuild' );

/**
 * Remind the user to run the importer right after the theme is activated.
 */
function mw_admin_activation_notice() {
	if ( get_option( 'mw_demo_imported' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info is-dismissible"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html__( 'Maison Woodcraft:', 'maison-woodcraft' ),
		esc_html__( 'import the original website content to get the ten pages, the photographs, the projects and the menus.', 'maison-woodcraft' ),
		esc_url( admin_url( 'themes.php?page=maison-woodcraft' ) ),
		esc_html__( 'Open the setup screen', 'maison-woodcraft' )
	);
}
add_action( 'admin_notices', 'mw_admin_activation_notice' );
