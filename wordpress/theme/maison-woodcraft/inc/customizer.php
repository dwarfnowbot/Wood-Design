<?php
/**
 * Customizer — editable business information.
 *
 * These are the values the original project kept in src/data/siteConfig.ts.
 * Everything here is optional: the theme falls back to the original values.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function mw_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'mw_business',
		array(
			'title'       => __( 'Maison Woodcraft — Business Details', 'maison-woodcraft' ),
			'description' => __( 'Phone, WhatsApp, email, address and social links used across the header, footer, contact page and forms.', 'maison-woodcraft' ),
			'priority'    => 20,
		)
	);

	/* ---------------------------------------------------------------- Brand */
	$wp_customize->add_section(
		'mw_brand',
		array(
			'title' => __( 'Brand', 'maison-woodcraft' ),
			'panel' => 'mw_business',
		)
	);

	$brand_fields = array(
		'brand_name'    => array(
			'label'   => __( 'Brand name', 'maison-woodcraft' ),
			'default' => mw_content( 'site.brandName', 'Maison Woodcraft' ),
			'type'    => 'text',
		),
		'brand_tagline' => array(
			'label'   => __( 'Brand tagline', 'maison-woodcraft' ),
			'default' => mw_content( 'site.brandTagline', 'Custom Kitchens & Complete Home Woodwork' ),
			'type'    => 'text',
		),
		'logo_subtitle' => array(
			'label'       => __( 'Header logo subtitle', 'maison-woodcraft' ),
			'description' => __( 'Shown under the wordmark in the header (the original uses “Kitchens & Home Woodwork”).', 'maison-woodcraft' ),
			'default'     => 'Kitchens & Home Woodwork',
			'type'        => 'text',
		),
		'meta_description' => array(
			'label'   => __( 'Home page meta description', 'maison-woodcraft' ),
			'default' => 'Maison Woodcraft designs and builds custom kitchens, wardrobes and complete home woodwork for homeowners in Lahore. Book a consultation for your next project.',
			'type'    => 'textarea',
		),
	);

	foreach ( $brand_fields as $key => $field ) {
		$wp_customize->add_setting(
			'mw_' . $key,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => 'textarea' === $field['type'] ? 'sanitize_textarea_field' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'mw_' . $key,
			array(
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'section'     => 'mw_brand',
				'type'        => $field['type'],
			)
		);
	}

	/* -------------------------------------------------------------- Contact */
	$wp_customize->add_section(
		'mw_contact',
		array(
			'title' => __( 'Contact details', 'maison-woodcraft' ),
			'panel' => 'mw_business',
		)
	);

	$contact_fields = array(
		'phone'         => array(
			'label'   => __( 'Phone number', 'maison-woodcraft' ),
			'default' => mw_content( 'site.phone', '+92 300 0000000' ),
			'type'    => 'text',
		),
		'whatsapp'      => array(
			'label'       => __( 'WhatsApp number', 'maison-woodcraft' ),
			'description' => __( 'Digits only, with country code and no + or spaces — e.g. 923001234567.', 'maison-woodcraft' ),
			'default'     => mw_content( 'site.whatsapp', '923000000000' ),
			'type'        => 'text',
		),
		'email'         => array(
			'label'   => __( 'Email address', 'maison-woodcraft' ),
			'default' => mw_content( 'site.email', '' ),
			'type'    => 'email',
		),
		'address'       => array(
			'label'   => __( 'Studio / workshop address', 'maison-woodcraft' ),
			'default' => mw_content( 'site.address', '' ),
			'type'    => 'text',
		),
		'service_area'  => array(
			'label'   => __( 'Service area', 'maison-woodcraft' ),
			'default' => mw_content( 'site.serviceArea', '' ),
			'type'    => 'text',
		),
		'map_embed_url' => array(
			'label'       => __( 'Google Maps embed URL', 'maison-woodcraft' ),
			'description' => __( 'Paste the src of the Google Maps embed (Share → Embed a map).', 'maison-woodcraft' ),
			'default'     => mw_content( 'site.mapEmbedUrl', '' ),
			'type'        => 'url',
		),
	);

	foreach ( $contact_fields as $key => $field ) {
		$wp_customize->add_setting(
			'mw_' . $key,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => 'url' === $field['type'] ? 'esc_url_raw' : ( 'email' === $field['type'] ? 'sanitize_email' : 'sanitize_text_field' ),
			)
		);
		$wp_customize->add_control(
			'mw_' . $key,
			array(
				'label'       => $field['label'],
				'description' => isset( $field['description'] ) ? $field['description'] : '',
				'section'     => 'mw_contact',
				'type'        => 'url' === $field['type'] ? 'url' : $field['type'],
			)
		);
	}

	/* -------------------------------------------------------- WhatsApp button */
	$wp_customize->add_section(
		'mw_whatsapp',
		array(
			'title' => __( 'WhatsApp button', 'maison-woodcraft' ),
			'panel' => 'mw_business',
		)
	);

	$wp_customize->add_setting(
		'mw_whatsapp_float',
		array(
			'default'           => true,
			'sanitize_callback' => 'mw_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'mw_whatsapp_float',
		array(
			'label'       => __( 'Show the floating WhatsApp bubble', 'maison-woodcraft' ),
			'description' => __( 'The green bubble in the bottom-right corner of every page (as in the original site).', 'maison-woodcraft' ),
			'section'     => 'mw_whatsapp',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'mw_whatsapp_message',
		array(
			'default'           => "Hi, I'd like to know more about your custom woodwork services.",
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'mw_whatsapp_message',
		array(
			'label'       => __( 'Pre-filled WhatsApp message', 'maison-woodcraft' ),
			'description' => __( 'Used by the floating bubble and the inline "Chat on WhatsApp" buttons.', 'maison-woodcraft' ),
			'section'     => 'mw_whatsapp',
			'type'        => 'text',
		)
	);

	/* ---------------------------------------------------------------- Header */
	$wp_customize->add_section(
		'mw_header',
		array(
			'title' => __( 'Header', 'maison-woodcraft' ),
			'panel' => 'mw_business',
		)
	);

	$wp_customize->add_setting(
		'mw_header_cta_label',
		array(
			'default'           => __( 'Get a Quote', 'maison-woodcraft' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'mw_header_cta_label',
		array(
			'label'   => __( 'Header button label', 'maison-woodcraft' ),
			'section' => 'mw_header',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mw_header_cta_url',
		array(
			'default'           => '/get-a-quote',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'mw_header_cta_url',
		array(
			'label'       => __( 'Header button link', 'maison-woodcraft' ),
			'description' => __( 'Accepts a page path such as /get-a-quote or a full URL.', 'maison-woodcraft' ),
			'section'     => 'mw_header',
			'type'        => 'text',
		)
	);

	/* ----------------------------------------------------------------- Forms */
	$wp_customize->add_section(
		'mw_forms',
		array(
			'title'       => __( 'Forms & email', 'maison-woodcraft' ),
			'panel'       => 'mw_business',
			'description' => __( 'Where quote requests and contact messages are sent. Use an SMTP plugin (WP Mail SMTP, Post SMTP) so notifications are delivered reliably.', 'maison-woodcraft' ),
		)
	);

	$form_fields = array(
		'form_recipient_email' => array(
			'label'   => __( 'Send form notifications to', 'maison-woodcraft' ),
			'default' => get_option( 'admin_email' ),
			'sanitize' => 'sanitize_email',
			'type'    => 'email',
		),
		'form_subject_prefix'  => array(
			'label'   => __( 'Email subject prefix', 'maison-woodcraft' ),
			'default' => '[' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ']',
			'sanitize' => 'sanitize_text_field',
			'type'    => 'text',
		),
		'quote_success_message' => array(
			'label'   => __( 'Quote form success message', 'maison-woodcraft' ),
			'default' => '',
			'sanitize' => 'sanitize_textarea_field',
			'type'    => 'textarea',
		),
	);

	foreach ( $form_fields as $key => $field ) {
		$wp_customize->add_setting(
			'mw_' . $key,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => $field['sanitize'],
			)
		);
		$wp_customize->add_control(
			'mw_' . $key,
			array(
				'label'   => $field['label'],
				'section' => 'mw_forms',
				'type'    => $field['type'],
			)
		);
	}
}
add_action( 'customize_register', 'mw_customize_register' );

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function mw_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * The footer social links are read from the content file but can be edited.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function mw_customize_social( $wp_customize ) {
	$wp_customize->add_section(
		'mw_social',
		array(
			'title' => __( 'Social links', 'maison-woodcraft' ),
			'panel' => 'mw_business',
		)
	);

	$social = (array) mw_content( 'site.social', array() );
	foreach ( $social as $network => $url ) {
		$wp_customize->add_setting(
			'mw_social_' . $network,
			array(
				'default'           => $url,
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'mw_social_' . $network,
			array(
				'label'   => ucfirst( $network ),
				'section' => 'mw_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'mw_customize_social' );

/**
 * Social links with Customizer overrides applied.
 *
 * @return array
 */
function mw_social_links() {
	$social = (array) mw_content( 'site.social', array() );
	$output = array();

	foreach ( $social as $network => $url ) {
		$output[ $network ] = mw_option( 'social_' . $network, $url );
	}

	return $output;
}
