<?php
/**
 * Page template.
 *
 * Pages created by the demo importer are built with Elementor and render
 * exactly like the original site. Any other page falls back to a on-brand
 * content layout, or to the native recreation of the matching original page
 * (based on its slug).
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
	} elseif ( mw_has_fallback_page( get_post_field( 'post_name' ) ) ) {
		mw_render_fallback_page( get_post_field( 'post_name' ) );
	} else {
		?>
		<div class="mw-section">
			<div class="mw-container mw-container--text">
				<?php mw_render( 'heading', array( 'heading' => get_the_title(), 'tag' => 'h1' ) ); ?>
				<div class="mw-prose mw-mt-8">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
		<?php
	}

endwhile;

get_footer();
