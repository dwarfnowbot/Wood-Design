<?php
/**
 * Gallery grid — kitchen and wardrobe project galleries (first tile spans 2×2).
 *
 * @package Maison_Woodcraft
 */

$items         = mw_arg( $args, 'items', array() );
$feature_first = (bool) mw_arg( $args, 'feature_first', true );
$lightbox      = (bool) mw_arg( $args, 'lightbox', true );
$columns       = mw_arg( $args, 'columns', '' );

$classes = 'mw-gallery';
if ( $columns ) {
	$classes .= ' mw-gallery--' . sanitize_html_class( $columns );
}

if ( empty( $items ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php foreach ( $items as $index => $item ) : ?>
		<?php
		$image = is_array( $item ) ? mw_arg( $item, 'image', mw_arg( $item, 'url', '' ) ) : $item;
		$alt   = is_array( $item ) ? mw_arg( $item, 'alt', '' ) : '';
		$full  = mw_resolve_image( $image, $alt );
		$is_feature = ( $feature_first && 0 === $index );
		?>
		<a class="mw-gallery__item<?php echo $is_feature ? ' mw-gallery__item--feature' : ''; ?>" href="<?php echo esc_url( $full['url'] ); ?>"<?php echo $lightbox ? ' data-mw-lightbox="1"' : ''; ?>>
			<?php echo mw_image_html( $image, '', $alt, array( 'sizes' => '(max-width: 639px) 100vw, (max-width: 1023px) 50vw, 400px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	<?php endforeach; ?>
</div>
