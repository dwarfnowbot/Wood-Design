<?php
/**
 * Single project.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( mw_page_uses_elementor( get_the_ID() ) ) {
		the_content();
	} elseif ( mw_elementor_do_location( 'single' ) ) {
		// Handled by an Elementor Pro template.
	} else {
		?>
		<div class="mw-section">
			<div class="mw-container">
				<?php mw_render( 'project-details', array( 'post_id' => get_the_ID() ) ); ?>
			</div>
		</div>
		<?php
		mw_render( 'cta', mw_page_copy( 'projects', 'cta', array() ) );
	}

endwhile;

get_footer();
