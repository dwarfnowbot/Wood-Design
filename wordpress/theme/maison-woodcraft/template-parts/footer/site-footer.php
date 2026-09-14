<?php
/**
 * Footer — components/Footer.tsx
 *
 * Four columns: brand + social, quick links, services, contact details, then the
 * copyright bar.
 *
 * @package Maison_Woodcraft
 */

$year  = gmdate( 'Y' );
$brand = mw_site( 'brandName' );
$social = (array) mw_site( 'social' );
?>
<footer class="mw-footer">
	<div class="mw-container">
		<div class="mw-footer__grid">
			<div class="mw-footer__col">
				<span class="mw-footer__brand"><?php echo esc_html( $brand ); ?></span>
				<p class="mw-footer__blurb"><?php echo esc_html( mw_content( 'footer.blurb', '' ) ); ?></p>
				<?php if ( $social ) : ?>
					<div class="mw-footer__social">
						<?php foreach ( $social as $network => $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $network ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="mw-footer__col">
				<h3 class="mw-footer__heading"><?php esc_html_e( 'Quick Links', 'maison-woodcraft' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_quick_links',
						'container'      => false,
						'menu_class'     => 'mw-footer__list',
						'depth'          => 1,
						'fallback_cb'    => 'mw_footer_quick_links_fallback',
					)
				);
				?>
			</div>

			<div class="mw-footer__col">
				<h3 class="mw-footer__heading"><?php esc_html_e( 'Services', 'maison-woodcraft' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_services',
						'container'      => false,
						'menu_class'     => 'mw-footer__list',
						'depth'          => 1,
						'fallback_cb'    => 'mw_footer_services_fallback',
					)
				);
				?>
			</div>

			<div class="mw-footer__col">
				<h3 class="mw-footer__heading"><?php esc_html_e( 'Contact', 'maison-woodcraft' ); ?></h3>
				<ul class="mw-footer__list">
					<?php if ( mw_site( 'address' ) ) : ?>
						<li><?php echo esc_html( mw_site( 'address' ) ); ?></li>
					<?php endif; ?>
					<?php if ( mw_site( 'serviceArea' ) ) : ?>
						<li><?php echo esc_html( sprintf( __( 'Serving %s', 'maison-woodcraft' ), mw_site( 'serviceArea' ) ) ); ?></li>
					<?php endif; ?>
					<?php if ( mw_site( 'phone' ) ) : ?>
						<li><a href="<?php echo esc_url( mw_tel_link( mw_site( 'phone' ) ) ); ?>"><?php echo esc_html( mw_site( 'phone' ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( mw_site( 'email' ) ) : ?>
						<li><a href="mailto:<?php echo esc_attr( mw_site( 'email' ) ); ?>"><?php echo esc_html( mw_site( 'email' ) ); ?></a></li>
					<?php endif; ?>
					<?php foreach ( (array) mw_site( 'businessHours' ) as $hours ) : ?>
						<li class="mw-footer__hours"><?php echo esc_html( mw_arg( $hours, 'day', '' ) . ': ' . mw_arg( $hours, 'hours', '' ) ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="mw-footer__bottom">
		<div class="mw-container">
			<div class="mw-footer__bottom-inner">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: year, 2: brand name. */
							__( '© %1$s %2$s. All rights reserved.', 'maison-woodcraft' ),
							$year,
							$brand
						)
					);
					?>
				</p>
				<p><?php echo esc_html( mw_content( 'footer.bottomNote', '' ) ); ?></p>
			</div>
		</div>
	</div>
</footer>
