<?php
/**
 * Trust strip — components/TrustStrip.tsx
 *
 * @package Maison_Woodcraft
 */

$items = mw_arg( $args, 'items', mw_trust_points() );
$icons = mw_arg( $args, 'icons', array() );

if ( empty( $items ) ) {
	return;
}
?>
<section class="mw-trust">
	<div class="mw-container">
		<div class="mw-trust__list">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$label = is_array( $item ) ? mw_arg( $item, 'text', mw_arg( $item, 'label', '' ) ) : $item;
				$icon  = null;
				if ( is_array( $item ) && ! empty( $item['icon'] ) ) {
					$icon = mw_svg( $item['icon'], 'mw-trust__icon' );
				} elseif ( isset( $icons[ $index ] ) ) {
					$icon = mw_svg( $icons[ $index ], 'mw-trust__icon' );
				} else {
					$icon = mw_trust_icon( $index );
				}
				?>
				<div class="mw-trust__item">
					<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG. ?>
					<span class="mw-trust__label"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
