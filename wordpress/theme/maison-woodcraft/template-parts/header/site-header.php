<?php
/**
 * Header — components/Header.tsx
 *
 * Fixed header, transparent over the home hero, solid after scrolling (JS adds
 * `.is-solid`), with the original wordmark, primary navigation, quote CTA and
 * animated hamburger + mobile panel.
 *
 * @package Maison_Woodcraft
 */

$brand      = mw_site( 'brandName' );
$brand_tag  = mw_site( 'brandTagline' );
$phone      = mw_site( 'phone' );
$cta_label  = mw_option( 'header_cta_label', __( 'Get a Quote', 'maison-woodcraft' ) );
$cta_url    = mw_option( 'header_cta_url', '/get-a-quote' );
?>
<header id="mw-header" class="mw-header" data-mw-header>
	<div class="mw-header__inner mw-container">
		<a class="mw-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<span class="mw-logo__media"><?php the_custom_logo(); ?></span>
			<?php else : ?>
				<span class="mw-logo__name"><?php echo esc_html( $brand ); ?></span>
				<?php if ( $brand_tag ) : ?>
					<span class="mw-logo__tag"><?php echo esc_html( mw_option( 'logo_subtitle', 'Kitchens & Home Woodwork' ) ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</a>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => 'nav',
				'container_class' => 'mw-nav',
				'container_aria_label' => __( 'Primary menu', 'maison-woodcraft' ),
				'menu_class'     => 'mw-nav__list',
				'depth'          => 2,
				'fallback_cb'    => 'mw_primary_menu_fallback',
			)
		);
		?>

		<div class="mw-header__cta">
			<?php
			mw_button(
				array(
					'label'   => $cta_label,
					'url'     => $cta_url,
					'variant' => 'ghost',
					'size'    => 'header',
				)
			);
			?>
		</div>

		<button type="button" class="mw-burger" aria-label="<?php esc_attr_e( 'Toggle menu', 'maison-woodcraft' ); ?>" aria-expanded="false" aria-controls="mw-mobile-menu" data-mw-burger>
			<span class="mw-burger__line"></span>
			<span class="mw-burger__line"></span>
			<span class="mw-burger__line"></span>
		</button>
	</div>

	<div class="mw-mobile-menu" id="mw-mobile-menu" data-mw-mobile-menu>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mw-mobile-menu__list',
				'depth'          => 1,
				'fallback_cb'    => 'mw_primary_menu_fallback',
			)
		);
		?>
		<div class="mw-mobile-menu__actions">
			<?php
			mw_button(
				array(
					'label'   => $cta_label,
					'url'     => $cta_url,
					'variant' => 'ghost',
					'size'    => 'menu',
				)
			);
			if ( $phone ) {
				mw_button(
					array(
						/* translators: %s: phone number. */
						'label'   => sprintf( __( 'Call %s', 'maison-woodcraft' ), $phone ),
						'url'     => mw_tel_link( $phone ),
						'variant' => 'call',
						'size'    => 'menu',
					)
				);
			}
			?>
		</div>
	</div>
</header>
