<?php
/**
 * CTA band — components/CTASection.tsx
 *
 * @package Maison_Woodcraft
 */

$heading       = mw_arg( $args, 'heading', __( "Let's Create a Space That Feels Like Yours.", 'maison-woodcraft' ) );
$text          = mw_arg( $args, 'text', 'Planning a new kitchen, wardrobe, or complete home woodwork project? Tell us about your space and requirements.' );
$primary_label = mw_arg( $args, 'primary_label', __( 'Request a Quote', 'maison-woodcraft' ) );
$primary_url   = mw_arg( $args, 'primary_url', '/get-a-quote' );
$image         = mw_arg( $args, 'image', 'living.3' );
$alt           = mw_arg( $args, 'alt', __( 'Elegant custom woodwork interior', 'maison-woodcraft' ) );
$whatsapp      = (bool) mw_arg( $args, 'whatsapp', true );
$message       = mw_arg( $args, 'whatsapp_message', '' );
$eyebrow       = mw_arg( $args, 'eyebrow', '' );
$buttons       = mw_arg( $args, 'buttons', array() );
?>
<section class="mw-cta">
	<?php
	echo mw_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$image,
		'mw-cta__media',
		$alt,
		array( 'sizes' => '100vw' )
	);
	?>
	<div class="mw-cta__overlay"></div>
	<div class="mw-cta__inner mw-container">
		<?php if ( $eyebrow ) : ?>
			<span class="mw-cta__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<h2 class="mw-cta__title"><?php echo esc_html( $heading ); ?></h2>
		<p class="mw-cta__text"><?php echo esc_html( $text ); ?></p>
		<div class="mw-btn-row mw-cta__actions">
			<?php
			if ( ! empty( $buttons ) ) {
				foreach ( $buttons as $button ) {
					$variant = mw_arg( $button, 'variant', 'ghost' );

					if ( 'whatsapp' === $variant ) {
						echo mw_whatsapp_button_html( array( 'label' => mw_arg( $button, 'label', '' ), 'message' => mw_arg( $button, 'message', '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						continue;
					}

					mw_button(
						array(
							'label'   => mw_arg( $button, 'label', '' ),
							'url'     => mw_arg( $button, 'url', '' ),
							'variant' => $variant,
							'new_tab' => (bool) mw_arg( $button, 'new_tab', false ),
						)
					);
				}
			} else {
				mw_button(
					array(
						'label'   => $primary_label,
						'url'     => $primary_url,
						'variant' => 'ghost',
					)
				);

				if ( $whatsapp ) {
					echo mw_whatsapp_button_html( array( 'message' => $message ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			?>
		</div>
	</div>
</section>
