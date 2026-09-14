<?php
/**
 * Hero — pages/Home.tsx (home) and components/PageHero.tsx (interior pages).
 *
 * @package Maison_Woodcraft
 */

$variant  = mw_arg( $args, 'variant', 'page' );      // home | page
$height   = mw_arg( $args, 'height', '' );            // short | compact
$eyebrow  = mw_arg( $args, 'eyebrow', '' );
$title    = mw_arg( $args, 'title', '' );
$text     = mw_arg( $args, 'text', '' );
$image    = mw_arg( $args, 'image', '' );
$alt      = mw_arg( $args, 'alt', '' );
$buttons  = mw_arg( $args, 'buttons', array() );
$whatsapp = (bool) mw_arg( $args, 'whatsapp', false );
$whatsapp_message = mw_arg( $args, 'whatsapp_message', '' );

$classes = 'mw-hero mw-hero--' . sanitize_html_class( $variant );
if ( $height ) {
	$classes .= ' mw-hero--' . sanitize_html_class( $height );
}
?>
<section class="<?php echo esc_attr( $classes ); ?>">
	<?php
	echo mw_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper.
		$image,
		'mw-hero__media',
		$alt,
		array(
			'loading'  => 'eager',
			'sizes'    => '100vw',
			'priority' => true,
		)
	);
	?>
	<div class="mw-hero__overlay"></div>
	<div class="mw-hero__inner mw-container">
		<?php if ( $eyebrow ) : ?>
			<span class="mw-hero__eyebrow animate-fade-up"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h1 class="mw-hero__title animate-fade-up-delay-1"><?php echo esc_html( $title ); ?></h1>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<p class="mw-hero__text animate-fade-up-delay-2"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>
		<?php if ( $buttons || $whatsapp ) : ?>
			<div class="mw-btn-row mw-hero__actions animate-fade-up-delay-3">
				<?php
				foreach ( (array) $buttons as $index => $button ) {
					$label  = mw_arg( $button, 'label', isset( $button['text'] ) ? $button['text'] : '' );
					$url    = mw_arg( $button, 'url', mw_arg( $button, 'link', mw_arg( $button, 'path', '' ) ) );
					$type   = mw_arg( $button, 'variant', 0 === $index ? 'ghost' : 'secondary' );
					$target = mw_arg( $button, 'new_tab', false );

					if ( 'whatsapp' === $type ) {
						echo mw_whatsapp_button_html( array( 'message' => mw_arg( $button, 'message', '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						continue;
					}

					mw_button(
						array(
							'label'   => $label,
							'url'     => $url,
							'variant' => $type,
							'new_tab' => $target,
						)
					);
				}

				if ( $whatsapp ) {
					echo mw_whatsapp_button_html( array( 'message' => $whatsapp_message ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
