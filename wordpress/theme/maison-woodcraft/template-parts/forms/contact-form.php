<?php
/**
 * Contact form — pages/Contact.tsx
 *
 * Posts to admin-post.php (works without JavaScript) and is progressively
 * enhanced: assets/js/theme.js submits it over the REST API so the original
 * "Message Sent" panel appears without a page reload.
 *
 * @package Maison_Woodcraft
 */

$fields  = mw_arg( $args, 'fields', mw_form_fields( 'contact' ) );
$form    = mw_arg( $args, 'form', 'contact' );
$copy    = mw_page_copy( 'contact', 'form', array() );
$submit  = mw_arg( $args, 'submit', mw_arg( $copy, 'submit', __( 'Send Message', 'maison-woodcraft' ) ) );
$state   = mw_form_state( $form );
$content = mw_arg( $args, 'success_message', '' );
$success = array(
	'heading' => mw_arg( $args, 'success_heading', mw_arg( mw_arg( $copy, 'success', array() ), 'heading', __( 'Message Sent', 'maison-woodcraft' ) ) ),
	'text'    => $content ? $content : mw_arg( mw_arg( $copy, 'success', array() ), 'text', '' ),
);
?>
<?php if ( 'success' === $state['status'] ) : ?>
	<div class="mw-form-success mw-form-success--compact">
		<h3 class="mw-form-success__title"><?php echo esc_html( $success['heading'] ); ?></h3>
		<p class="mw-form-success__text"><?php echo esc_html( $success['text'] ); ?></p>
	</div>
<?php else : ?>
	<?php if ( $state['message'] ) : ?>
		<div class="mw-form__message mw-form__message--<?php echo esc_attr( $state['status'] ); ?> is-visible"><?php echo esc_html( $state['message'] ); ?></div>
	<?php endif; ?>
	<form class="mw-form mw-form--contact" method="post" action="<?php echo esc_url( mw_form_action_url() ); ?>" data-mw-form data-mw-form-type="<?php echo esc_attr( $form ); ?>" data-mw-success-title="<?php echo esc_attr( $success['heading'] ); ?>">
		<?php mw_form_hidden_fields( $form ); ?>

		<?php if ( ! empty( $args['heading'] ) ) : ?>
			<?php mw_render( 'heading', array( 'heading' => $args['heading'], 'tag' => 'h3' ) ); ?>
		<?php endif; ?>
		<div class="mw-form__grid mw-form__grid--contact">
			<?php
			mw_render_form_field( 'fullName', $fields, $form );
			mw_render_form_field( 'phone', $fields, $form );
			?>
			<div class="mw-form__full">
				<?php mw_render_form_field( 'email', $fields, $form ); ?>
			</div>
			<div class="mw-form__full">
				<?php mw_render_form_field( 'message', $fields, $form ); ?>
			</div>
		</div>
		<div class="mw-form__message" data-mw-form-message aria-live="polite"></div>
		<button type="submit" class="mw-btn mw-btn--primary mw-btn--form"><?php echo esc_html( $submit ); ?></button>
	</form>
<?php endif; ?>
