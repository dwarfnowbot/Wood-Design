<?php
/**
 * Testimonial cards — components/TestimonialCard.tsx
 *
 * @package Maison_Woodcraft
 */

$items = mw_arg( $args, 'items', mw_testimonials() );
$note  = mw_arg( $args, 'note', '' );

if ( empty( $items ) ) {
	return;
}
?>
<div class="mw-cards">
	<?php foreach ( $items as $item ) : ?>
		<?php
		$quote    = mw_arg( $item, 'quote', '' );
		$name     = mw_arg( $item, 'name', '' );
		$location = mw_arg( $item, 'location', mw_arg( $item, 'role', '' ) );
		?>
		<div class="mw-testimonial">
			<?php echo mw_svg( 'quote', 'mw-testimonial__quote-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG. ?>
			<p class="mw-testimonial__quote"><?php echo esc_html( $quote ); ?></p>
			<?php if ( $name || $location ) : ?>
				<div class="mw-testimonial__footer">
					<?php if ( $name ) : ?>
						<p class="mw-testimonial__name"><?php echo esc_html( $name ); ?></p>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<p class="mw-testimonial__location"><?php echo esc_html( $location ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<?php if ( $note ) : ?>
	<p class="mw-note"><?php echo esc_html( $note ); ?></p>
<?php endif; ?>
