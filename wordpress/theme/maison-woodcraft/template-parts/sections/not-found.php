<?php
/**
 * 404 — pages/NotFound.tsx
 *
 * @package Maison_Woodcraft
 */

$copy = mw_page_copy( 'notfound', null, array() );
?>
<div class="mw-404">
	<span class="mw-404__eyebrow"><?php echo esc_html( mw_arg( $copy, 'eyebrow', '404' ) ); ?></span>
	<h1 class="mw-404__title"><?php echo esc_html( mw_arg( $copy, 'heading', __( 'Page Not Found', 'maison-woodcraft' ) ) ); ?></h1>
	<p class="mw-404__text"><?php echo esc_html( mw_arg( $copy, 'text', '' ) ); ?></p>
	<?php
	mw_button(
		array(
			'label'   => mw_arg( mw_arg( $copy, 'button', array() ), 'label', __( 'Back to Home', 'maison-woodcraft' ) ),
			'url'     => mw_arg( mw_arg( $copy, 'button', array() ), 'path', '/' ),
			'variant' => 'primary',
		)
	);
	?>
</div>
