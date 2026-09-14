<?php
/**
 * Archive template (categories, tags, taxonomies).
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="mw-archive-header">
	<div class="mw-container">
		<h1 class="mw-archive-header__title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				the_archive_title();
			}
			?>
		</h1>
		<?php the_archive_description( '<div class="mw-archive-header__text">', '</div>' ); ?>
	</div>
</div>

<div class="mw-section">
	<div class="mw-container">
		<?php if ( have_posts() ) : ?>
			<div class="mw-archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content-card' );
				endwhile;
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( 'Previous', 'maison-woodcraft' ),
					'next_text' => __( 'Next', 'maison-woodcraft' ),
					'class'     => 'mw-pagination',
				)
			);
			?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'maison-woodcraft' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
