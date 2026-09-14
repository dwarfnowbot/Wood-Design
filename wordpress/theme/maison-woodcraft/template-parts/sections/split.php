<?php
/**
 * Two-column text + image block.
 *
 * Used by the original Home intro, Kitchens intro/features, Wardrobes
 * intro/internal planning, Interior Woodwork intro, About intro and About
 * "where we work" sections — each of which is the same layout with a different
 * column order on desktop.
 *
 * @package Maison_Woodcraft
 */

$image          = mw_arg( $args, 'image', '' );
$alt            = mw_arg( $args, 'alt', '' );
$background     = mw_arg( $args, 'background', '' );
$extra_text     = mw_arg( $args, 'extra_text', '' );
$list_items     = mw_arg( $args, 'items', array() );
$checklist      = mw_arg( $args, 'checklist', array() );
$frame          = mw_arg( $args, 'frame', '4x5' );
$text_order     = mw_arg( $args, 'text_order', 'first' );     // DOM order: first|second
$image_lg_order = mw_arg( $args, 'image_lg_order', 'second' ); // desktop column: first|second
$inset_image    = mw_arg( $args, 'inset_image', mw_arg( $args, 'inset', '' ) );
$inset_alt      = mw_arg( $args, 'inset_alt', '' );
$class          = mw_arg( $args, 'class', '' );
$heading_args   = mw_arg( $args, 'heading', array() );

/* Elementor widgets pass a plain heading string plus a separate eyebrow. */
if ( is_string( $heading_args ) ) {
	$heading_args = '' !== $heading_args ? array( 'heading' => $heading_args ) : array();
}
if ( ! empty( $args['eyebrow'] ) && is_array( $heading_args ) ) {
	$heading_args['eyebrow'] = mw_arg( $heading_args, 'eyebrow', $args['eyebrow'] );
}
if ( ! empty( $args['heading'] ) && is_string( $args['heading'] ) && empty( $args['eyebrow'] ) ) {
	// Handled above.
}

/* Background modifier, mirroring the original section backgrounds. */
if ( '' === $class ) {
	$map = array(
		'stone'    => 'mw-section mw-section--stone',
		'espresso' => 'mw-section mw-section--espresso',
		'white'    => 'mw-section mw-section--plain mw-section--white',
		'plain'    => 'mw-section',
	);
	$class = isset( $map[ $background ] ) ? $map[ $background ] : 'mw-section';
}

/* "Image on the right" switch. */
if ( ! empty( $args['flip'] ) ) {
	$text_order     = 'first';
	$image_lg_order = 'second';
}
if ( ! empty( $args['text_order'] ) ) {
	$text_order = $args['text_order'];
}
$text           = mw_arg( $args, 'text', '' );
$button         = mw_arg( $args, 'button', array() );
$extra          = mw_arg( $args, 'extra', array() );

$text_classes = 'mw-split__text';
$media_classes = 'mw-split__media';

if ( 'second' === $text_order ) {
	$text_classes .= ' mw-order-2';
	$media_classes .= ' mw-order-1';
} else {
	$media_classes .= ' mw-order-2';
}

if ( 'first' === $image_lg_order ) {
	$text_classes .= ' mw-order-lg-2';
	$media_classes .= ' mw-order-lg-1';
} else {
	$text_classes .= ' mw-order-lg-1';
	$media_classes .= ' mw-order-lg-2';
}

$frame_class = 'mw-frame';
if ( 'video' === $frame ) {
	$frame_class .= ' mw-frame--16x9';
} elseif ( '4x3' === $frame ) {
	$frame_class .= ' mw-frame--4x3';
} elseif ( 'square' === $frame ) {
	$frame_class .= ' mw-frame--square';
} else {
	$frame_class .= ' mw-frame--4x5';
}
?>
<section class="<?php echo esc_attr( $class ); ?>">
	<div class="mw-container mw-split">
		<div class="<?php echo esc_attr( $text_classes ); ?>">
			<?php
			if ( ! empty( $heading_args ) ) {
				mw_render( 'heading', $heading_args );
			}
			?>
			<?php if ( $text ) : ?>
				<p class="mw-split__text-copy"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( $extra_text ) : ?>
				<p class="mw-split__text-copy"><?php echo esc_html( $extra_text ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $list_items ) ) : ?>
				<ul class="mw-split__list">
					<?php foreach ( $list_items as $list_item ) : ?>
						<li class="mw-split__list-item">
							<?php if ( mw_arg( $list_item, 'title', '' ) ) : ?>
								<span class="mw-split__list-title"><?php echo esc_html( mw_arg( $list_item, 'title', '' ) ); ?></span>
							<?php endif; ?>
							<?php if ( mw_arg( $list_item, 'description', mw_arg( $list_item, 'text', '' ) ) ) : ?>
								<span class="mw-split__list-text"><?php echo esc_html( mw_arg( $list_item, 'description', mw_arg( $list_item, 'text', '' ) ) ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( ! empty( $checklist ) ) : ?>
				<div class="mw-mt-8">
					<?php mw_render( 'checklist', array( 'items' => $checklist ) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $button['label'] ) ) : ?>
				<div class="mw-mt-8">
					<?php mw_button( $button ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $extra['section'] ) ) : ?>
				<div class="mw-mt-8">
					<?php mw_render( $extra['section'], isset( $extra['args'] ) ? $extra['args'] : array() ); ?>
				</div>
			<?php endif; ?>
			<?php
			$extra = mw_arg( $args, 'extra', array() );
			if ( ! empty( $extra['section'] ) ) :
				?>
				<div class="mw-mt-8">
					<?php mw_render( $extra['section'], isset( $extra['args'] ) ? $extra['args'] : array() ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="<?php echo esc_attr( $media_classes ); ?>">
			<div class="<?php echo esc_attr( $frame_class ); ?>">
				<?php
				echo mw_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$image,
					'',
					$alt,
					array( 'sizes' => '(max-width: 1023px) 100vw, 560px' )
				);
				?>
			</div>
			<?php if ( $inset_image ) : ?>
				<div class="mw-split__inset">
					<?php echo mw_image_html( $inset_image, '', $inset_alt, array( 'sizes' => '160px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
