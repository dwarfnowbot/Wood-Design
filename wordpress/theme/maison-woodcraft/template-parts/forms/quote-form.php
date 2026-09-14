<?php
/**
 * Project inquiry form — pages/GetQuote.tsx
 *
 * The form posts to admin-post.php (works without JavaScript) and is
 * progressively enhanced: assets/js/theme.js submits it over the REST API so the
 * original "Thank You" panel appears without a page reload.
 *
 * @package Maison_Woodcraft
 */

$fields  = mw_arg( $args, 'fields', mw_form_fields( 'quote' ) );
$form    = mw_arg( $args, 'form', 'quote' );
$copy    = mw_page_copy( 'get-a-quote', 'form', array() );
$submit  = mw_arg( $args, 'submit', mw_arg( $copy, 'submit', __( 'Submit Inquiry', 'maison-woodcraft' ) ) );
$note    = mw_arg( $args, 'note', mw_arg( $copy, 'note', '' ) );
$description = mw_arg( $args, 'description', '' );
$state   = mw_form_state( $form );
$show_details = (bool) mw_arg( $args, 'show_details', true );
$show_upload  = (bool) mw_arg( $args, 'show_upload', true );
$type    = mw_arg( $args, 'form_type', 'quote' );
$content = mw_arg( $args, 'success_message', mw_option( 'quote_success_message', '' ) );
$success = array(
	'heading'         => mw_arg( $args, 'success_heading', mw_arg( mw_arg( $copy, 'success', array() ), 'heading', __( 'Thank You', 'maison-woodcraft' ) ) ),
	'text'            => $content ? $content : mw_arg( mw_arg( $copy, 'success', array() ), 'text', '' ),
	'whatsappMessage' => mw_arg( mw_arg( $copy, 'success', array() ), 'whatsappMessage', '' ),
	'whatsappLabel'   => mw_arg( mw_arg( $copy, 'success', array() ), 'whatsappLabel', __( 'Chat on WhatsApp', 'maison-woodcraft' ) ),
);
$whatsapp_success = mw_arg( $success, 'whatsappMessage', '' );

if ( 'success' === $state['status'] ) {
	?>
	<div class="mw-form-success">
		<h2 class="mw-form-success__title"><?php echo esc_html( $success['heading'] ); ?></h2>
		<p class="mw-form-success__text"><?php echo esc_html( $success['text'] ); ?></p>
		<div class="mw-form-success__actions">
			<?php
			echo mw_whatsapp_button_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'message' => $success['whatsappMessage'],
					'label'   => $success['whatsappLabel'],
				)
			);
			?>
		</div>
	</div>
	<?php
	return;
}
?>
<?php if ( $state['message'] ) : ?>
	<div class="mw-form__message mw-form__message--<?php echo esc_attr( $state['status'] ); ?> is-visible"><?php echo esc_html( $state['message'] ); ?></div>
<?php endif; ?>
<form class="mw-form" method="post" action="<?php echo esc_url( mw_form_action_url() ); ?>" data-mw-form data-mw-form-type="<?php echo esc_attr( $form ); ?>" data-mw-success-title="<?php echo esc_attr( $success['heading'] ); ?>" enctype="multipart/form-data">
	<?php mw_form_hidden_fields( $form ); ?>

	<?php if ( ! empty( $args['heading'] ) ) : ?>
		<?php
		mw_render(
			'heading',
			array(
				'heading'     => $args['heading'],
				'description' => $description,
				'align'       => 'left',
			)
		);
		?>
	<?php elseif ( $description ) : ?>
		<p class="mw-form__description"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>

	<div class="mw-form__grid">
		<?php
		mw_render_form_field( 'fullName', $fields, $form );
		mw_render_form_field( 'phone', $fields, $form );
		mw_render_form_field( 'whatsapp', $fields, $form );
		mw_render_form_field( 'email', $fields, $form );
		mw_render_form_field( 'projectType', $fields, $form );
		if ( $show_details ) {
			mw_render_form_field( 'location', $fields, $form );
			mw_render_form_field( 'budget', $fields, $form );
			mw_render_form_field( 'stage', $fields, $form );
		}
		?>
		<div class="mw-form__full">
			<?php mw_render_form_field( 'message', $fields, $form ); ?>
		</div>
		<?php if ( $show_upload && isset( $fields['reference'] ) ) : ?>
			<div class="mw-form__full">
				<?php mw_render_form_field( 'reference', $fields, $form ); ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( isset( $fields['contactMethod'] ) ) : ?>
		<?php mw_render_form_field( 'contactMethod', $fields, $form ); ?>
	<?php endif; ?>

	<?php if ( $note ) : ?>
		<p class="mw-form__note"><?php echo esc_html( $note ); ?></p>
	<?php endif; ?>

	<div class="mw-form__message" data-mw-form-message aria-live="polite"></div>

	<?php if ( $whatsapp_success ) : ?>
		<div class="mw-form__whatsapp-template" hidden data-mw-whatsapp-success>
			<?php
			echo mw_whatsapp_button_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'message' => $whatsapp_success,
					'label'   => mw_arg( mw_arg( $copy, 'success', array() ), 'whatsappLabel', __( 'Chat on WhatsApp', 'maison-woodcraft' ) ),
				)
			);
			?>
		</div>
	<?php endif; ?>

	<button type="submit" class="mw-btn mw-btn--primary mw-btn--form"><?php echo esc_html( $submit ); ?></button>
</form>
