<?php
/**
 * Working forms.
 *
 * The original site shipped two front-end-only forms (they never submitted
 * anywhere). Here they are real:
 *
 *   - submissions are stored as "Form Entries" in the WordPress admin,
 *   - notifications are sent with wp_mail() (plug in an SMTP plugin for
 *     reliable delivery — see docs/FORMS-AND-EMAIL.md),
 *   - submissions arrive over the WordPress REST API (AJAX, no page reload) with
 *     a plain POST fallback to admin-post.php when JavaScript is unavailable,
 *   - spam is filtered with a honeypot, a time trap, nonce verification and a
 *     simple per-IP rate limit.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

const MW_FORM_NONCE   = 'mw_form_submit';
const MW_ENTRY_POST   = 'mw_entry';

/**
 * Register the private "Form Entries" post type and the REST route.
 */
function mw_forms_init() {
	register_post_type(
		MW_ENTRY_POST,
		array(
			'labels'            => array(
				'name'          => __( 'Form Entries', 'maison-woodcraft' ),
				'singular_name' => __( 'Form Entry', 'maison-woodcraft' ),
				'menu_name'     => __( 'Form Entries', 'maison-woodcraft' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'       => 'mw-theme-settings',
			'capability_type'   => 'post',
			'capabilities'      => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'      => true,
			'supports'          => array( 'title', 'editor' ),
			'menu_icon'         => 'dashicons-email-alt',
			'rewrite'           => false,
			'exclude_from_search' => true,
		)
	);

	register_post_status(
		'mw_unread',
		array(
			'label'                     => _x( 'Unread', 'form entry status', 'maison-woodcraft' ),
			'public'                    => false,
			'internal'                  => true,
			'protected'                 => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
		)
	);
}
add_action( 'init', 'mw_forms_init' );

/**
 * The REST route used by the AJAX submissions.
 */
function mw_forms_register_rest() {
	register_rest_route(
		'mw/v1',
		'/form',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'mw_forms_rest_submit',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'mw_forms_register_rest' );

/**
 * REST handler.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function mw_forms_rest_submit( $request ) {
	$result = mw_process_form( $request->get_params(), $request->get_file_params() );

	return new WP_REST_Response(
		array(
			'success' => ! empty( $result['success'] ),
			'message' => $result['message'],
		),
		! empty( $result['success'] ) ? 200 : 400
	);
}

/**
 * Non-JS fallback: admin-post.php handler.
 */
function mw_forms_handle_post() {
	$result = mw_process_form( $_POST, $_FILES ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified inside mw_process_form().

	$form_type = isset( $_POST['mw_form_type'] ) ? sanitize_key( wp_unslash( $_POST['mw_form_type'] ) ) : 'quote';
	$return    = isset( $_POST['mw_return_url'] ) ? esc_url_raw( wp_unslash( $_POST['mw_return_url'] ) ) : home_url( '/' );

	$token = wp_generate_password( 12, false );
	set_transient( 'mw_form_msg_' . $token, $result, 5 * MINUTE_IN_SECONDS );

	$redirect = add_query_arg(
		array(
			'mw_form'      => ( ! empty( $result['success'] ) ? 'success' : 'error' ),
			'mw_form_type' => $form_type,
			'mw_form_msg'  => $token,
		),
		$return
	);

	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_mw_form', 'mw_forms_handle_post' );
add_action( 'admin_post_nopriv_mw_form', 'mw_forms_handle_post' );

/**
 * Validate, store and email a submission.
 *
 * @param array $data  Request data ($_POST or REST params).
 * @param array $files Uploaded files ($_FILES).
 * @return array{success:bool,message:string,entry_id:int,errors:array}
 */
function mw_process_form( $data, $files = array() ) {
	$form_type = isset( $data['mw_form_type'] ) ? sanitize_key( $data['mw_form_type'] ) : 'quote';
	$form_type = in_array( $form_type, array( 'quote', 'contact' ), true ) ? $form_type : 'quote';

	$fail = function ( $message, $errors = array() ) use ( $form_type ) {
		return array(
			'success'  => false,
			'message'  => $message,
			'entry_id' => 0,
			'errors'   => $errors,
			'form'     => $form_type,
		);
	};

	/* Nonce. */
	$nonce = isset( $data['mw_form_nonce'] ) ? sanitize_text_field( wp_unslash( $data['mw_form_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, MW_FORM_NONCE ) ) {
		return $fail( __( 'Your session expired. Please reload the page and try again.', 'maison-woodcraft' ) );
	}

	/* Honeypot: a hidden field real visitors never fill in. */
	if ( ! empty( $data['mw_website'] ) ) {
		return $fail( __( 'Submission blocked.', 'maison-woodcraft' ) );
	}

	/* Time trap: a human cannot submit the form in under 2 seconds. */
	$started = isset( $data['mw_started_at'] ) ? (int) $data['mw_started_at'] : 0;
	if ( $started && ( time() - $started ) < 2 ) {
		return $fail( __( 'Submission blocked.', 'maison-woodcraft' ) );
	}

	/* Rate limit: 5 submissions per IP per 10 minutes. */
	$ip  = mw_get_client_ip();
	$key = 'mw_form_rate_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= 5 ) {
		return $fail( __( 'Too many submissions. Please try again later.', 'maison-woodcraft' ) );
	}
	set_transient( $key, $hits + 1, 10 * MINUTE_IN_SECONDS );

	/* Collect + validate the known fields. */
	$fields = mw_form_fields( $form_type );
	$values = array();

	foreach ( $fields as $name => $field ) {
		$type = isset( $field['type'] ) ? $field['type'] : 'text';

		if ( 'file' === $type ) {
			continue;
		}

		if ( 'radio' === $type ) {
			$value = isset( $data[ $name ] ) ? sanitize_text_field( wp_unslash( $data[ $name ] ) ) : '';
			if ( $value && ! empty( $field['options'] ) && ! in_array( $value, $field['options'], true ) ) {
				$value = '';
			}
			$values[ $name ] = $value;
			continue;
		}

		$raw   = isset( $data[ $name ] ) ? wp_unslash( $data[ $name ] ) : '';
		$value = 'email' === $type ? sanitize_email( $raw ) : ( 'textarea' === $type ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw ) );

		if ( 'select' === $type && $value && ! empty( $field['options'] ) && ! in_array( $value, $field['options'], true ) ) {
			$value = '';
		}

		if ( ! empty( $field['required'] ) && '' === trim( (string) $value ) ) {
			return $fail(
				sprintf(
					/* translators: %s: field label. */
					__( 'Please fill in the required field: %s', 'maison-woodcraft' ),
					isset( $field['label'] ) ? $field['label'] : $name
				)
			);
		}

		$values[ $name ] = $value;
	}

	if ( ! empty( $values['email'] ) && ! is_email( $values['email'] ) ) {
		return $fail( __( 'Please enter a valid email address.', 'maison-woodcraft' ) );
	}

	if ( empty( $values['email'] ) && empty( $values['phone'] ) ) {
		return $fail( __( 'Please leave either a phone number or an email address so we can reply.', 'maison-woodcraft' ) );
	}

	/* Store the entry. */
	$title = sprintf(
		/* translators: 1: form label, 2: sender name. */
		__( '%1$s — %2$s', 'maison-woodcraft' ),
		'quote' === $form_type ? __( 'Quote request', 'maison-woodcraft' ) : __( 'Contact message', 'maison-woodcraft' ),
		! empty( $values['fullName'] ) ? $values['fullName'] : __( 'Website visitor', 'maison-woodcraft' )
	);

	$body_lines = array();
	foreach ( $fields as $name => $field ) {
		if ( 'file' === ( isset( $field['type'] ) ? $field['type'] : '' ) ) {
			continue;
		}
		if ( isset( $values[ $name ] ) && '' !== $values[ $name ] ) {
			$body_lines[] = $field['label'] . ': ' . $values[ $name ];
		}
	}

	$entry_id = wp_insert_post(
		array(
			'post_type'   => MW_ENTRY_POST,
			'post_status' => 'mw_unread',
			'post_title'  => $title,
			'post_content' => implode( "\n", $body_lines ),
		),
		true
	);

	if ( is_wp_error( $entry_id ) ) {
		return $fail( __( 'Something went wrong while saving your message. Please try again.', 'maison-woodcraft' ) );
	}

	update_post_meta( $entry_id, '_mw_form_type', $form_type );
	update_post_meta( $entry_id, '_mw_ip', $ip );
	update_post_meta( $entry_id, '_mw_user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );
	foreach ( $values as $name => $value ) {
		if ( '' !== $value ) {
			update_post_meta( $entry_id, '_mw_' . $name, $value );
		}
	}

	/* Attachments (reference images / plans). */
	$attachments = mw_handle_form_uploads( $form_type, $entry_id );
	foreach ( $attachments['ids'] as $attachment_id ) {
		update_post_meta( $entry_id, '_mw_attachment', $attachment_id );
	}

	/* Notify the studio. */
	$email_sent = mw_send_form_notification( $form_type, $values, $attachments, $entry_id );

	if ( ! $email_sent && ! mw_option( 'form_silent_failure', false ) ) {
		update_post_meta( $entry_id, '_mw_email_failed', current_time( 'mysql' ) );
	}

	$success_message = 'quote' === $form_type
		? mw_option( 'quote_success_message', mw_page_copy( 'get-a-quote', 'form.success.text', __( 'We\'ve received your inquiry. Our team will review your requirements and contact you shortly to discuss the next steps.', 'maison-woodcraft' ) ) )
		: mw_page_copy( 'contact', 'form.success.text', __( 'Thank you for reaching out. We\'ll get back to you as soon as possible.', 'maison-woodcraft' ) );

	/**
	 * Fires after a form submission was stored.
	 *
	 * @param int    $entry_id  Entry post ID.
	 * @param string $form_type quote|contact.
	 * @param array  $values    Sanitised field values.
	 */
	do_action( 'mw_form_submitted', $entry_id, $form_type, $values );

	return array(
		'success'  => true,
		'message'  => $success_message,
		'entry_id' => $entry_id,
		'errors'   => array(),
		'form'     => $form_type,
	);
}

/**
 * Handle "Upload Reference Images or Plans".
 *
 * @param string $form_type Form type.
 * @param int    $entry_id  Entry ID.
 * @return array{ids:int[],files:array}
 */
function mw_handle_form_uploads( $form_type, $entry_id ) {
	$result = array(
		'ids'   => array(),
		'files' => array(),
	);

	if ( empty( $_FILES['reference'] ) || empty( $_FILES['reference']['name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in mw_process_form().
		return $result;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$fields = mw_form_fields( $form_type );
	$accept = isset( $fields['reference']['accept'] ) ? $fields['reference']['accept'] : 'image/*,.pdf';
	$allowed = apply_filters(
		'mw_form_upload_mime_types',
		array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
			'webp'         => 'image/webp',
			'gif'          => 'image/gif',
			'pdf'          => 'application/pdf',
		)
	);
	$max_size = (int) apply_filters( 'mw_form_upload_max_size', 8 * MB_IN_BYTES );

	$files = $_FILES['reference']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$names = is_array( $files['name'] ) ? $files['name'] : array( $files['name'] );
	$count = min( count( $names ), 8 );

	for ( $i = 0; $i < $count; $i++ ) {
		$file = array(
			'name'     => $names[ $i ],
			'type'     => is_array( $files['type'] ) ? $files['type'][ $i ] : $files['type'],
			'tmp_name' => is_array( $files['tmp_name'] ) ? $files['tmp_name'][ $i ] : $files['tmp_name'],
			'error'    => is_array( $files['error'] ) ? $files['error'][ $i ] : $files['error'],
			'size'     => is_array( $files['size'] ) ? $files['size'][ $i ] : $files['size'],
		);

		if ( empty( $file['name'] ) || ! empty( $file['error'] ) ) {
			continue;
		}

		if ( $file['size'] > $max_size ) {
			continue;
		}

		$check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed );
		if ( empty( $check['type'] ) || ! in_array( $check['type'], $allowed, true ) ) {
			continue;
		}

		$attachment_id = media_handle_sideload( $file, $entry_id, sprintf( /* translators: %s: form type. */ __( 'Reference file for %s submission', 'maison-woodcraft' ), $form_type ) );

		if ( is_wp_error( $attachment_id ) ) {
			continue;
		}

		$result['ids'][]   = (int) $attachment_id;
		$result['files'][] = get_attached_file( $attachment_id );
	}

	return $result;
}

/**
 * Email the submission to the studio address.
 *
 * @param string $form_type  Form type.
 * @param array  $values     Sanitised values.
 * @param array  $attachments Uploads.
 * @param int    $entry_id   Entry ID.
 * @return bool
 */
function mw_send_form_notification( $form_type, $values, $attachments, $entry_id ) {
	$recipient = mw_option( 'form_recipient_email', get_option( 'admin_email' ) );
	$recipient = apply_filters( 'mw_form_recipient', $recipient, $form_type );

	$subject_prefix = mw_option( 'form_subject_prefix', '[' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ']' );
	$subject        = trim( $subject_prefix . ' ' . ( 'quote' === $form_type ? __( 'New quote request', 'maison-woodcraft' ) : __( 'New contact message', 'maison-woodcraft' ) ) );

	if ( ! empty( $values['fullName'] ) ) {
		$subject .= ' — ' . $values['fullName'];
	}

	$lines = array();
	foreach ( $values as $name => $value ) {
		if ( '' === $value ) {
			continue;
		}
		$lines[] = ucwords( trim( preg_replace( '/(?<!^)[A-Z]/', ' $0', $name ) ) ) . ': ' . $value;
	}

	$lines[] = '';
	$lines[] = __( 'Submitted from:', 'maison-woodcraft' ) . ' ' . home_url( '/' );
	$lines[] = __( 'Entry ID:', 'maison-woodcraft' ) . ' ' . $entry_id;

	$body = apply_filters( 'mw_form_email_body', implode( "\n", $lines ), $values, $form_type, $entry_id );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( ! empty( $values['email'] ) ) {
		$headers[] = 'Reply-To: ' . $values['email'];
	}

	$sent = wp_mail( $recipient, $subject, $body, $headers, $attachments['files'] );

	/**
	 * Fires after the notification email was attempted.
	 *
	 * @param bool   $sent     Whether wp_mail() reported success.
	 * @param int    $entry_id Entry ID.
	 * @param string $form_type Form type.
	 */
	do_action( 'mw_form_email_sent', $sent, $entry_id, $form_type );

	return (bool) $sent;
}

/**
 * The form action URL (admin-post.php), used as the no-JS fallback.
 *
 * @return string
 */
function mw_form_action_url() {
	return admin_url( 'admin-post.php' );
}

/**
 * Hidden inputs every form needs.
 *
 * @param string $form_type Form type.
 */
function mw_form_hidden_fields( $form_type ) {
	wp_nonce_field( MW_FORM_NONCE, 'mw_form_nonce', false );

	printf( '<input type="hidden" name="action" value="mw_form">' );
	printf( '<input type="hidden" name="mw_form_type" value="%s">', esc_attr( $form_type ) );
	printf( '<input type="hidden" name="mw_started_at" value="%d">', esc_attr( time() ) );
	printf( '<input type="hidden" name="mw_return_url" value="%s">', esc_url( mw_current_url() ) );

	// Honeypot — hidden from humans, tempting for bots.
	printf(
		'<p class="mw-form__honeypot"><label for="mw-website-%1$s">%2$s</label><input type="text" id="mw-website-%1$s" name="mw_website" value="" tabindex="-1" autocomplete="off"></p>',
		esc_attr( $form_type ),
		esc_html__( 'Leave this field empty', 'maison-woodcraft' )
	);
}

/**
 * Render one field from the original form definitions.
 *
 * @param string $name      Field name.
 * @param array  $fields    Field definitions.
 * @param string $form_type Form type (for unique IDs).
 */
function mw_render_form_field( $name, $fields, $form_type = 'quote' ) {
	if ( empty( $fields[ $name ] ) ) {
		return;
	}

	$field     = $fields[ $name ];
	$type      = isset( $field['type'] ) ? $field['type'] : 'text';
	$label     = isset( $field['label'] ) ? $field['label'] : $name;
	$id        = 'mw-' . $form_type . '-' . $name;
	$required  = ! empty( $field['required'] );
	$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$value       = '';

	echo '<div class="mw-field mw-field--' . esc_attr( $type ) . '">';

	if ( 'radio' === $type ) {
		echo '<span class="mw-label">' . esc_html( $label ) . '</span>';
		echo '<div class="mw-form__radios">';
		foreach ( (array) $field['options'] as $index => $option ) {
			printf(
				'<label class="mw-radio" for="%1$s-%2$d"><input type="radio" id="%1$s-%2$d" name="%3$s" value="%4$s"%5$s>%6$s</label>',
				esc_attr( $id ),
				(int) $index,
				esc_attr( $name ),
				esc_attr( $option ),
				0 === $index ? ' checked' : '',
				esc_html( $option )
			);
		}
		echo '</div></div>';
		return;
	}

	printf(
		'<label class="mw-label" for="%s">%s%s</label>',
		esc_attr( $id ),
		esc_html( $label ),
		$required ? ' <span class="mw-required" aria-hidden="true">*</span>' : ''
	);

	switch ( $type ) {
		case 'textarea':
			printf(
				'<textarea class="mw-input" id="%1$s" name="%2$s" rows="5" placeholder="%3$s"%4$s>%5$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $placeholder ),
				$required ? ' required' : '',
				esc_textarea( $value )
			);
			break;

		case 'select':
			printf(
				'<select class="mw-input" id="%1$s" name="%2$s"%3$s><option value="" disabled%4$s>%5$s</option>',
				esc_attr( $id ),
				esc_attr( $name ),
				$required ? ' required' : '',
				$value ? '' : ' selected',
				esc_html( $placeholder )
			);
			foreach ( (array) $field['options'] as $option ) {
				printf(
					'<option value="%1$s"%2$s>%1$s</option>',
					esc_attr( $option ),
					selected( $value, $option, false )
				);
			}
			echo '</select>';
			break;

		case 'file':
			printf(
				'<input class="mw-input" type="file" id="%1$s" name="%2$s"%3$s accept="%4$s">',
				esc_attr( $id ),
				esc_attr( $name ),
				! empty( $field['multiple'] ) ? ' multiple' : '',
				esc_attr( isset( $field['accept'] ) ? $field['accept'] : 'image/*,.pdf' )
			);
			break;

		default:
			printf(
				'<input class="mw-input" type="%1$s" id="%2$s" name="%3$s" placeholder="%4$s" value="%5$s"%6$s>',
				esc_attr( 'tel' === $type ? 'tel' : $type ),
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $placeholder ),
				esc_attr( $value ),
				$required ? ' required' : ''
			);
			break;
	}

	echo '</div>';
}

/**
 * Current form state, so templates can show the original success panel or an
 * error message after a non-JS submission.
 *
 * @param string $form_type Form type.
 * @return array{status:string,message:string}
 */
function mw_form_state( $form_type = 'quote' ) {
	if ( empty( $_GET['mw_form'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return array(
			'status'  => '',
			'message' => '',
		);
	}

	$status = sanitize_key( wp_unslash( $_GET['mw_form'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$form   = isset( $_GET['mw_form_type'] ) ? sanitize_key( wp_unslash( $_GET['mw_form_type'] ) ) : $form_type; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( $form !== $form_type ) {
		return array(
			'status'  => '',
			'message' => '',
		);
	}

	$message = '';
	if ( ! empty( $_GET['mw_form_msg'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$token   = sanitize_text_field( wp_unslash( $_GET['mw_form_msg'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$stored  = get_transient( 'mw_form_msg_' . $token );
		$message = is_array( $stored ) && ! empty( $stored['message'] ) ? $stored['message'] : '';
	}

	$success = ( 'success' === $status );

	return array(
		'status'  => $success ? 'success' : 'error',
		'message' => $success && ! $message ? __( 'Thank you — your message has been sent.', 'maison-woodcraft' ) : $message,
	);
}

/**
 * Client IP address (privacy friendly: only used for rate limiting).
 *
 * @return string
 */
function mw_get_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	return apply_filters( 'mw_form_client_ip', $ip );
}

/* -------------------------------------------------------------------------
 * Admin: entries list
 * ---------------------------------------------------------------------- */

/**
 * Show the key submission fields in the entries list table.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function mw_entries_columns( $columns ) {
	return array(
		'cb'        => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'title'     => __( 'Entry', 'maison-woodcraft' ),
		'mw_type'   => __( 'Form', 'maison-woodcraft' ),
		'mw_phone'  => __( 'Phone', 'maison-woodcraft' ),
		'mw_email'  => __( 'Email', 'maison-woodcraft' ),
		'date'      => __( 'Received', 'maison-woodcraft' ),
	);
}
add_filter( 'manage_' . MW_ENTRY_POST . '_posts_columns', 'mw_entries_columns' );

/**
 * Render the custom columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Entry ID.
 */
function mw_entries_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'mw_type':
			$type = get_post_meta( $post_id, '_mw_form_type', true );
			echo esc_html( 'quote' === $type ? __( 'Quote request', 'maison-woodcraft' ) : __( 'Contact message', 'maison-woodcraft' ) );
			break;
		case 'mw_phone':
			echo esc_html( get_post_meta( $post_id, '_mw_phone', true ) );
			break;
		case 'mw_email':
			$email = get_post_meta( $post_id, '_mw_email', true );
			if ( $email ) {
				printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
			}
			break;
	}
}
add_action( 'manage_' . MW_ENTRY_POST . '_posts_custom_column', 'mw_entries_column_content', 10, 2 );

/**
 * Mark an entry as read when opened.
 *
 * @param int $post_id Entry ID.
 */
function mw_entry_mark_read( $post_id ) {
	if ( MW_ENTRY_POST !== get_post_type( $post_id ) ) {
		return;
	}
	if ( 'mw_unread' === get_post_status( $post_id ) ) {
		wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			)
		);
	}
}
add_action( 'edit_form_top', 'mw_entry_mark_read' );

/**
 * Show the full submission on the entry edit screen.
 */
function mw_entry_meta_box() {
	add_meta_box(
		'mw-entry-details',
		__( 'Submission details', 'maison-woodcraft' ),
		'mw_entry_meta_box_render',
		MW_ENTRY_POST,
		'normal',
		'high'
	);

	add_meta_box(
		'mw-entry-files',
		__( 'Attachments', 'maison-woodcraft' ),
		'mw_entry_files_render',
		MW_ENTRY_POST,
		'side'
	);
}
add_action( 'add_meta_boxes', 'mw_entry_meta_box' );

/**
 * Render the submission details meta box.
 *
 * @param WP_Post $post Entry.
 */
function mw_entry_meta_box_render( $post ) {
	$form_type = get_post_meta( $post->ID, '_mw_form_type', true );
	$fields    = mw_form_fields( $form_type ? $form_type : 'quote' );

	echo '<table class="widefat striped"><tbody>';
	foreach ( $fields as $name => $field ) {
		if ( 'file' === ( isset( $field['type'] ) ? $field['type'] : '' ) ) {
			continue;
		}
		$value = get_post_meta( $post->ID, '_mw_' . $name, true );
		printf(
			'<tr><th style="width:220px">%s</th><td>%s</td></tr>',
			esc_html( $field['label'] ),
			esc_html( $value )
		);
	}

	$ip = get_post_meta( $post->ID, '_mw_ip', true );
	if ( $ip ) {
		printf(
			'<tr><th>%s</th><td>%s</td></tr>',
			esc_html__( 'IP address', 'maison-woodcraft' ),
			esc_html( $ip )
		);
	}
	if ( get_post_meta( $post->ID, '_mw_email_failed', true ) ) {
		printf(
			'<tr><th>%s</th><td class="mw-admin-error">%s</td></tr>',
			esc_html__( 'Notification email', 'maison-woodcraft' ),
			esc_html__( 'wp_mail() reported a failure. Configure SMTP (WP Mail SMTP, Post SMTP…) and resend.', 'maison-woodcraft' )
		);
	}
	echo '</tbody></table>';
}

/**
 * Render the attachments meta box.
 *
 * @param WP_Post $post Entry.
 */
function mw_entry_files_render( $post ) {
	$attachments = get_post_meta( $post->ID, '_mw_attachment' );
	if ( empty( $attachments ) ) {
		echo '<p>' . esc_html__( 'No files uploaded.', 'maison-woodcraft' ) . '</p>';
		return;
	}

	echo '<ul>';
	foreach ( $attachments as $attachment_id ) {
		$url = wp_get_attachment_url( $attachment_id );
		if ( $url ) {
			printf( '<li><a href="%s" target="_blank" rel="noopener">%s</a></li>', esc_url( $url ), esc_html( basename( $url ) ) );
		}
	}
	echo '</ul>';
}

/**
 * Unread entry count bubble in the admin menu.
 */
function mw_entries_unread_bubble() {
	global $menu;
	$count = new WP_Query(
		array(
			'post_type'      => MW_ENTRY_POST,
			'post_status'    => 'mw_unread',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	$total = (int) $count->found_posts;
	if ( ! $total ) {
		return;
	}

	foreach ( $menu as $index => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=' . MW_ENTRY_POST === $item[2] ) {
			$menu[ $index ][0] .= ' <span class="update-plugins count-' . $total . '"><span class="plugin-count">' . number_format_i18n( $total ) . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
	}
}
add_action( 'admin_menu', 'mw_entries_unread_bubble', 999 );
