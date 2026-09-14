<?php
/**
 * Service cards grid — components/ServiceCard.tsx (services.ts items).
 *
 * @package Maison_Woodcraft
 */

$items = mw_arg( $args, 'items', mw_services() );
$link_label = mw_arg( $args, 'link_label', __( 'Explore Service', 'maison-woodcraft' ) );

if ( empty( $items ) ) {
	return;
}
?>
<div class="mw-cards">
	<?php foreach ( $items as $item ) : ?>
		<?php
		$image = isset( $item['image'] ) ? $item['image'] : mw_arg( $item, 'image_key', '' );
		$title = mw_arg( $item, 'title', '' );
		$text  = mw_arg( $item, 'shortDescription', mw_arg( $item, 'text', '' ) );
		$url   = mw_arg( $item, 'path', mw_arg( $item, 'url', '' ) );
		?>
		<article class="mw-card mw-card--service">
			<div class="mw-card__media">
				<?php echo mw_image_html( $image, '', $title, array( 'sizes' => '(max-width: 639px) 100vw, (max-width: 1023px) 50vw, 400px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="mw-card__body">
				<h3 class="mw-card__title"><?php echo esc_html( $title ); ?></h3>
				<p class="mw-card__text"><?php echo esc_html( $text ); ?></p>
				<?php if ( $url ) : ?>
					<a class="mw-tiwt mw-card__link" href="<?php echo esc_url( mw_page_url( $url ) ); ?>">
						<span><?php echo esc_html( $link_label ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>
			</div>
		</article>
	<?php endforeach; ?>
</div>
