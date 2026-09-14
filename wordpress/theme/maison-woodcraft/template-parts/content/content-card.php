<?php
/**
 * Generic content card used by the archive/blog fallbacks (the original site
 * has no blog, so this keeps posts on-brand if the client adds them).
 *
 * @package Maison_Woodcraft
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'mw-card mw-card--project' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="mw-card__media" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'mw-card', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
		</a>
	<?php endif; ?>
	<div class="mw-card__body">
		<h3 class="mw-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="mw-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<p class="mw-card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
		<a class="mw-tiwt mw-card__link" href="<?php the_permalink(); ?>">
			<span><?php esc_html_e( 'Read More', 'maison-woodcraft' ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
</article>
