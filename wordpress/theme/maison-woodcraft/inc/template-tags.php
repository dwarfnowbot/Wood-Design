<?php
/**
 * Template tags — the small building blocks the original components were made of.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a theme option (Customizer setting) with a default.
 *
 * @param string $key     Setting key without the `mw_` prefix.
 * @param mixed  $default Default value.
 * @return mixed
 */
function mw_option( $key, $default = '' ) {
	$value = get_theme_mod( 'mw_' . $key, $default );

	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Business details, defaulting to the original siteConfig.ts values.
 *
 * @param string $key Optional sub-key: phone, whatsapp, email, address, serviceArea, mapEmbedUrl.
 * @return mixed
 */
function mw_site( $key = null ) {
	$defaults = array(
		'brandName'     => mw_content( 'site.brandName', 'Maison Woodcraft' ),
		'brandTagline'  => mw_content( 'site.brandTagline', 'Custom Kitchens & Complete Home Woodwork' ),
		'tagline'       => mw_content( 'site.tagline', 'Designed for Living. Crafted for You.' ),
		'phone'         => mw_content( 'site.phone', '+92 300 0000000' ),
		'whatsapp'      => mw_content( 'site.whatsapp', '923000000000' ),
		'email'         => mw_content( 'site.email', '' ),
		'address'       => mw_content( 'site.address', '' ),
		'serviceArea'   => mw_content( 'site.serviceArea', '' ),
		'mapEmbedUrl'   => mw_content( 'site.mapEmbedUrl', '' ),
		'businessHours' => mw_content( 'site.businessHours', array() ),
		'social'        => mw_content( 'site.social', array() ),
	);

	$values = array(
		'brandName'     => mw_option( 'brand_name', $defaults['brandName'] ),
		'brandTagline'  => mw_option( 'brand_tagline', $defaults['brandTagline'] ),
		'tagline'       => $defaults['tagline'],
		'phone'         => mw_option( 'phone', $defaults['phone'] ),
		'whatsapp'      => mw_option( 'whatsapp', $defaults['whatsapp'] ),
		'email'         => mw_option( 'email', $defaults['email'] ),
		'address'       => mw_option( 'address', $defaults['address'] ),
		'serviceArea'   => mw_option( 'service_area', $defaults['serviceArea'] ),
		'mapEmbedUrl'   => mw_option( 'map_embed_url', $defaults['mapEmbedUrl'] ),
		'businessHours' => $defaults['businessHours'],
		'social'        => function_exists( 'mw_social_links' ) ? mw_social_links() : $defaults['social'],
	);

	if ( null === $key ) {
		return $values;
	}

	return isset( $values[ $key ] ) ? $values[ $key ] : '';
}

/**
 * Turn an original route ("/kitchens") into the matching WordPress URL.
 *
 * The demo importer creates every original page with the same slug, so the
 * theme's links keep working exactly like the original navigation.
 *
 * @param string $path Route or full URL.
 * @return string
 */
function mw_page_url( $path ) {
	if ( empty( $path ) ) {
		return home_url( '/' );
	}

	if ( 0 === strpos( $path, 'http' ) || 0 === strpos( $path, '#' ) || 0 === strpos( $path, 'mailto:' ) || 0 === strpos( $path, 'tel:' ) ) {
		return $path;
	}

	if ( '/' === $path ) {
		return home_url( '/' );
	}

	static $cache = array();
	if ( isset( $cache[ $path ] ) ) {
		return $cache[ $path ];
	}

	$slug = trim( $path, '/' );
	$page = get_page_by_path( $slug );

	$cache[ $path ] = $page ? get_permalink( $page ) : home_url( user_trailingslashit( $slug ) );

	return $cache[ $path ];
}

/**
 * Phone number as a dial link (+ escaped of spaces).
 *
 * @param string $phone Phone number.
 * @return string
 */
function mw_tel_link( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $phone );
}

/**
 * Build a WhatsApp click-to-chat link (siteConfig.ts `whatsappLink`).
 *
 * @param string $message Optional pre-filled message.
 * @return string
 */
function mw_whatsapp_link( $message = '' ) {
	$number = preg_replace( '/[^0-9]/', '', (string) mw_option( 'whatsapp', mw_site( 'whatsapp' ) ) );
	$base   = 'https://wa.me/' . $number;

	if ( $message ) {
		return $base . '?text=' . rawurlencode( $message );
	}

	return $base;
}

/**
 * Default pre-filled WhatsApp message (WhatsAppButton.tsx).
 *
 * @return string
 */
function mw_whatsapp_default_message() {
	return mw_option(
		'whatsapp_message',
		"Hi, I'd like to know more about your custom woodwork services."
	);
}

/* -------------------------------------------------------------------------
 * Buttons
 * ---------------------------------------------------------------------- */

/**
 * Render one of the original Button variants.
 *
 * @param array $args {
 *     @type string $label   Button label.
 *     @type string $url     Link (original route or absolute URL).
 *     @type string $variant primary|secondary|outline|ghost|whatsapp|call.
 *     @type string $class   Extra classes.
 *     @type bool   $new_tab Open in a new tab.
 *     @type string $size    Optional: header|form|menu.
 *     @type string $tag     a|button.
 *     @type string $type    button type when tag is button.
 * }
 * @return string
 */
function mw_button_html( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'label'   => '',
			'url'     => '',
			'variant' => 'primary',
			'class'   => '',
			'new_tab' => false,
			'size'    => '',
			'tag'     => 'a',
			'type'    => 'button',
			'icon'    => '',
		)
	);

	if ( '' === $args['label'] ) {
		return '';
	}

	$classes = array( 'mw-btn', 'mw-btn--' . $args['variant'] );
	if ( $args['size'] ) {
		$classes[] = 'mw-btn--' . $args['size'];
	}
	if ( 'whatsapp' === $args['variant'] ) {
		$classes[] = 'mw-btn--whatsapp';
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	$classes = implode( ' ', array_unique( $classes ) );
	$icon    = '';

	if ( 'whatsapp' === $args['variant'] ) {
		$icon = mw_svg( 'whatsapp', 'mw-btn__icon' );
	} elseif ( $args['icon'] ) {
		$icon = mw_svg( $args['icon'], 'mw-btn__icon' );
	}

	if ( 'button' === $args['tag'] ) {
		return sprintf(
			'<button type="%1$s" class="%2$s">%3$s<span>%4$s</span></button>',
			esc_attr( $args['type'] ),
			esc_attr( $classes ),
			$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG.
			esc_html( $args['label'] )
		);
	}

	$target = $args['new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : '';

	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s<span>%5$s</span></a>',
		esc_attr( $classes ),
		esc_url( mw_page_url( $args['url'] ) ),
		$target, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute.
		$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG.
		esc_html( $args['label'] )
	);
}

/**
 * Echo a button.
 *
 * @param array $args See mw_button_html().
 */
function mw_button( $args ) {
	echo mw_button_html( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
}

/**
 * The inline WhatsApp button ("Chat on WhatsApp").
 *
 * @param array $args Optional: message, label, class.
 * @return string
 */
function mw_whatsapp_button_html( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'message' => mw_whatsapp_default_message(),
			'label'   => __( 'Chat on WhatsApp', 'maison-woodcraft' ),
			'class'   => '',
		)
	);

	return sprintf(
		'<a class="mw-btn mw-btn--whatsapp %3$s" href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span>%4$s</span></a>',
		esc_url( mw_whatsapp_link( $args['message'] ) ),
		mw_svg( 'whatsapp', 'mw-btn__icon' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_attr( $args['class'] ),
		esc_html( $args['label'] )
	);
}

/**
 * The floating WhatsApp bubble rendered in wp_footer.
 */
function mw_whatsapp_float() {
	if ( ! mw_option( 'whatsapp_float', true ) ) {
		return;
	}

	$number = preg_replace( '/[^0-9]/', '', (string) mw_site( 'whatsapp' ) );
	if ( ! $number ) {
		return;
	}

	printf(
		'<a class="mw-whatsapp-float" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
		esc_url( mw_whatsapp_link( mw_whatsapp_default_message() ) ),
		esc_attr__( 'Chat with us on WhatsApp', 'maison-woodcraft' ),
		mw_svg( 'whatsapp' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
add_action( 'wp_footer', 'mw_whatsapp_float' );

/* -------------------------------------------------------------------------
 * Inline SVG library (paths taken verbatim from the original components)
 * ---------------------------------------------------------------------- */

/**
 * Get one of the original SVG icons.
 *
 * @param string $name  Icon name.
 * @param string $class Optional class attribute.
 * @return string
 */
function mw_svg( $name, $class = '' ) {
	$class_attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	switch ( $name ) {
		case 'whatsapp':
			return '<svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true" focusable="false"' . $class_attr . '><path d="M16.02 3C9.4 3 4 8.38 4 15c0 2.36.64 4.57 1.76 6.47L4 29l7.73-1.7A11.9 11.9 0 0 0 16.02 27C22.65 27 28 21.62 28 15S22.65 3 16.02 3Zm0 21.68c-1.99 0-3.85-.56-5.44-1.53l-.39-.23-4.58 1.01 1-4.48-.25-.4A9.63 9.63 0 0 1 6.34 15c0-5.34 4.35-9.68 9.68-9.68S25.7 9.66 25.7 15s-4.35 9.68-9.68 9.68Zm5.36-7.26c-.29-.15-1.72-.85-1.99-.95-.27-.1-.46-.15-.66.15-.2.29-.76.95-.93 1.15-.17.2-.34.22-.63.07-.29-.15-1.23-.45-2.34-1.44-.87-.77-1.45-1.72-1.62-2.01-.17-.29-.02-.45.13-.6.13-.13.29-.34.44-.51.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.51-.07-.15-.66-1.59-.9-2.17-.24-.57-.48-.5-.66-.51h-.56c-.2 0-.51.07-.78.37-.27.29-1.02 1-1.02 2.44s1.05 2.83 1.19 3.03c.15.2 2.07 3.16 5.02 4.43.7.3 1.25.48 1.68.61.7.22 1.34.19 1.85.11.56-.08 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.2-.55-.34Z"/></svg>';

		case 'quote':
			return '<svg viewBox="0 0 32 24" fill="currentColor" aria-hidden="true" focusable="false"' . $class_attr . '><path d="M0 24V14.4C0 6.4 4.8 1.2 13.2 0l1.2 3.6C9.6 4.8 7.2 7.8 7.2 12h6v12H0Zm18 0V14.4c0-8 4.8-13.2 13.2-14.4l1.2 3.6c-4.8 1.2-7.2 4.2-7.2 8.4h6v12H18Z"/></svg>';

		case 'arrow':
			return '<span aria-hidden="true">&rarr;</span>';

		/* Trust strip icons (TrustStrip.tsx) */
		case 'trust-1':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M4 21V7l8-4 8 4v14M9 21v-6h6v6"/></svg>';
		case 'trust-2':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5L12 3z"/></svg>';
		case 'trust-3':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M14.7 6.3a4 4 0 0 1-5.66 5.66L4 17l3 3 5.04-5.04a4 4 0 0 1 5.66-5.66l-3-3z"/></svg>';
		case 'trust-4':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M9 12l2 2 4-4M5 6l2-2h10l2 2v12l-2 2H7l-2-2V6z"/></svg>';

		case 'phone':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M4 4h4l2 5-3 2a12 12 0 0 0 6 6l2-3 5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 3 5a1 1 0 0 1 1-1z"/></svg>';

		case 'mail':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M3 6h18v12H3z"/><path d="M3 7l9 6 9-6"/></svg>';

		case 'pin':
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"' . $class_attr . ' aria-hidden="true" focusable="false"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>';
	}

	return '';
}

/**
 * Trust strip icon by index (the original cycles through four icons).
 *
 * @param int $index Item index.
 * @return string
 */
function mw_trust_icon( $index ) {
	return mw_svg( 'trust-' . ( ( (int) $index % 4 ) + 1 ) );
}

/**
 * Format a project location that starts with the original sample prefix.
 *
 * @param string $location Location string.
 * @return string
 */
function mw_project_location( $location ) {
	return $location;
}

/* -------------------------------------------------------------------------
 * URLs
 * ---------------------------------------------------------------------- */

/**
 * The current request URL without query arguments.
 *
 * @return string
 */
function mw_current_url() {
	$url = home_url( add_query_arg( array() ) );
	return strtok( $url, '?' );
}

/* -------------------------------------------------------------------------
 * Menu fallbacks (used before the Primary / footer menus are created, so the
 * navigation matches the original site out of the box).
 * ---------------------------------------------------------------------- */

/**
 * Primary menu fallback — the original navLinks list.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function mw_primary_menu_fallback( $args = array() ) {
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'mw-nav__list';
	$items = array();

	foreach ( (array) mw_content( 'nav', array() ) as $link ) {
		$label = mw_arg( $link, 'label', '' );
		$url   = mw_page_url( mw_arg( $link, 'path', '/' ) );
		$path  = mw_arg( $link, 'path', '/' );
		$is_current = ( '/' === $path && mw_is_front_page() ) || ( '/' !== $path && is_page( trim( $path, '/' ) ) );

		$items[] = sprintf(
			'<li class="mw-nav__item%6$s"><a class="mw-nav__link" href="%1$s"%4$s>%5$s</a></li>',
			esc_url( $url ),
			'',
			'',
			$is_current ? ' aria-current="page"' : '',
			esc_html( $label ),
			$is_current ? ' is-active' : ''
		);
	}

	printf(
		'<ul class="%s">%s</ul>',
		esc_attr( $class ),
		implode( '', $items ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	);
}

/**
 * Footer "Quick Links" fallback — navLinks plus Get a Quote, as in Footer.tsx.
 */
function mw_footer_quick_links_fallback() {
	$items = array();

	foreach ( (array) mw_content( 'nav', array() ) as $link ) {
		$items[] = sprintf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( mw_page_url( mw_arg( $link, 'path', '/' ) ) ),
			esc_html( mw_arg( $link, 'label', '' ) )
		);
	}

	$items[] = sprintf(
		'<li><a href="%1$s">%2$s</a></li>',
		esc_url( mw_page_url( '/get-a-quote' ) ),
		esc_html__( 'Get a Quote', 'maison-woodcraft' )
	);

	echo '<ul class="mw-footer__list">' . implode( '', $items ) . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/**
 * Footer "Services" fallback — the six services from the original site.
 */
function mw_footer_services_fallback() {
	$items = array();

	foreach ( mw_services() as $service ) {
		$items[] = sprintf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( mw_page_url( mw_arg( $service, 'path', '/' ) ) ),
			esc_html( mw_arg( $service, 'title', '' ) )
		);
	}

	echo '<ul class="mw-footer__list">' . implode( '', $items ) . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/* -------------------------------------------------------------------------
 * Menu fallbacks
 *
 * Until the user assigns menus, the header and footer render the original
 * navigation straight from the site content, so the design is never empty.
 * ---------------------------------------------------------------------- */

/**
 * Fallback for the primary menu (original navLinks).
 *
 * @param array $args wp_nav_menu() arguments.
 */
function mw_primary_menu_fallback( $args = array() ) {
	$menu_class = mw_arg( (array) $args, 'menu_class', 'mw-nav__list' );
	$menu_id    = mw_arg( (array) $args, 'menu_id', '' );
	$items      = array();

	foreach ( (array) mw_content( 'nav', array() ) as $link ) {
		$path  = mw_arg( $link, 'path', '/' );
		$items[] = sprintf(
			'<li class="mw-nav__item%1$s"><a class="mw-nav__link" href="%2$s"%3$s>%4$s</a></li>',
			mw_page_url( $path ) === mw_current_url() ? ' is-active' : '',
			esc_url( mw_page_url( $path ) ),
			mw_page_url( $path ) === mw_current_url() ? ' aria-current="page"' : '',
			esc_html( mw_arg( $link, 'label', '' ) )
		);
	}

	printf(
		'<ul%1$s class="%2$s">%3$s</ul>',
		$menu_id ? ' id="' . esc_attr( $menu_id ) . '"' : '',
		esc_attr( $menu_class ),
		implode( '', $items ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	);
}

/**
 * Fallback for the footer "Quick Links" menu — navLinks + Get a Quote,
 * exactly as in components/Footer.tsx.
 */
function mw_footer_quick_links_fallback() {
	$links = (array) mw_content( 'nav', array() );
	$links[] = array(
		'label' => __( 'Get a Quote', 'maison-woodcraft' ),
		'path'  => '/get-a-quote',
	);

	$items = array();
	foreach ( $links as $link ) {
		$items[] = sprintf(
			'<li><a href="%s">%s</a></li>',
			esc_url( mw_page_url( mw_arg( $link, 'path', '/' ) ) ),
			esc_html( mw_arg( $link, 'label', '' ) )
		);
	}

	echo '<ul class="mw-footer__list">' . implode( '', $items ) . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}

/**
 * Fallback for the footer "Services" menu — the six services.
 */
function mw_footer_services_fallback() {
	$items = array();

	foreach ( mw_services() as $service ) {
		$items[] = sprintf(
			'<li><a href="%s">%s</a></li>',
			esc_url( mw_page_url( mw_arg( $service, 'path', '/' ) ) ),
			esc_html( mw_arg( $service, 'title', '' ) )
		);
	}

	echo '<ul class="mw-footer__list">' . implode( '', $items ) . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
}
