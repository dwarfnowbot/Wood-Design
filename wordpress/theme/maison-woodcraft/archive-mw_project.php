<?php
/**
 * Projects archive (only used if the archive is enabled with a filter such as
 * `add_filter( 'mw_projects_has_archive', '__return_true' )`; the theme renders
 * the projects grid on the "Projects" page by default).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();

$mw_copy = mw_page_copy( 'projects', null, array() );
?>
<div class="mw-archive-header">
	<div class="mw-container">
		<h1 class="mw-archive-header__title"><?php post_type_archive_title(); ?></h1>
		<div class="mw-archive-header__text"><?php echo esc_html( mw_arg( mw_arg( $mw_copy, 'categories', array() ), 'description', '' ) ); ?></div>
	</div>
</div>

<div class="mw-section">
	<div class="mw-container">
		<?php
		mw_render(
			'project-grid',
			array(
				'items'   => mw_get_project_cards( array( 'limit' => 24 ) ),
				'filters' => true,
			)
		);
		?>
	</div>
</div>

<?php
get_footer();
