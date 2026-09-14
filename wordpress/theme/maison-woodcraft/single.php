<?php
/**
 * Single post.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	if ( mw_page_uses_elementor( get_the_ID() ) ) {
		the_content();
	} else {
		?>
		<div class="mw-section">
			<div class="mw-container mw-container--text">
				<?php mw_render( 'heading', array( 'heading' => get_the_title(), 'tag' => 'h1' ) ); ?>
				<p class="mw-note"><?php echo esc_html( get_the_date() ); ?></p>
				<div class="mw-prose mw-mt-8">
					<?php
					if ( has_post_thumbnail() ) {
						echo '<div class="mw-frame mw-frame--16x9 mw-mt-8">';
						the_post_thumbnail( 'mw-hero', array( 'alt' => esc_attr( get_the_title() ) ) );
						echo '</div>';
					}
					the_content();
					wp_link_pages();
					?>
				</div>
			</div>
		</div>
		<?php
	}

endwhile;

get_footer();
