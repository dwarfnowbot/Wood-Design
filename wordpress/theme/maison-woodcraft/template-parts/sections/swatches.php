<?php
/**
 * Material / finish swatches — materialCategories in the original project.
 *
 * @package Maison_Woodcraft
 */

$items   = mw_arg( $args, 'items', mw_material_categories() );
$compact = (bool) mw_arg( $args, 'compact', false );
$note    = mw_arg( $args, 'note', '' );
$link    = mw_arg( $args, 'link', array() );

if ( empty( $items ) ) {
	return;
}
?>
<div class="mw-swatches<?php echo $compact ? ' mw-swatches--compact' : ''; ?>">
	<?php foreach ( $items as $item ) : ?>
		<?php
		$title = mw_arg( $item, 'title', mw_arg( $item, 'label', '' ) );
		$text  = mw_arg( $item, 'description', mw_arg( $item, 'detail', mw_arg( $item, 'text', '' ) ) );
		$from  = mw_arg( $item, 'swatch_from', mw_arg( $item, 'from', '#efe8db' ) );
		$to    = mw_arg( $item, 'swatch_to', mw_arg( $item, 'to', mw_arg( $item, 'color', '#b79c72' ) ) );
		$image = mw_arg( $item, 'image', '' );
		?>
		<div class="mw-swatch-card">
			<?php if ( $image ) : ?>
				<div class="mw-swatch-card__swatch mw-swatch-card__swatch--image">
					<?php echo mw_image_html( $image, '', $title, array( 'sizes' => '320px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<div class="mw-swatch-card__swatch" style="background-image:linear-gradient(to bottom right,<?php echo esc_attr( $from ); ?>,<?php echo esc_attr( $to ); ?>);"></div>
			<?php endif; ?>
			<h3 class="mw-swatch-card__title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $text && ! $compact ) : ?>
				<p class="mw-swatch-card__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<?php if ( $note ) : ?>
	<p class="mw-swatches__note"><?php echo esc_html( $note ); ?></p>
<?php endif; ?>
<?php if ( ! empty( $link['label'] ) ) : ?>
	<div class="mw-swatches__link">
		<a class="mw-tiwt" href="<?php echo esc_url( mw_page_url( mw_arg( $link, 'url', '' ) ) ); ?>">
			<span><?php echo esc_html( $link['label'] ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
<?php endif; ?>
