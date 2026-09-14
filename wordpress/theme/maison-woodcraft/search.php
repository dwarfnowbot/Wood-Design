<?php
/**
 * Search results.
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
			printf(
				/* translators: %s: search term. */
				esc_html__( 'Search results for “%s”', 'maison-woodcraft' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
		<div class="mw-archive-header__text"><?php get_search_form(); ?></div>
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
			<?php the_posts_pagination( array( 'class' => 'mw-pagination' ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing matched your search. Try a different term.', 'maison-woodcraft' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
