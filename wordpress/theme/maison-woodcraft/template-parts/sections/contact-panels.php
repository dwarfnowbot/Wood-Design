<?php
/**
 * Contact information panels — pages/Contact.tsx and the Get a Quote aside.
 *
 * @package Maison_Woodcraft
 */

$variant  = mw_arg( $args, 'variant', 'contact' ); // contact | quote
$details  = mw_arg( $args, 'details', true );
$hours    = mw_arg( $args, 'hours', true );
$social   = mw_arg( $args, 'social', true );
$map      = mw_arg( $args, 'map', true );
$whatsapp = (bool) mw_arg( $args, 'whatsapp', true );
$message  = mw_arg( $args, 'whatsapp_message', '' );

$phone = mw_site( 'phone' );
$email = mw_site( 'email' );

if ( 'quote' === $variant ) :
	?>
	<aside class="mw-aside-stack">
		<div class="mw-panel mw-panel--stone">
			<h3 class="mw-panel__title"><?php echo esc_html( mw_page_copy( 'get-a-quote', 'aside.heading', __( 'Prefer to Talk Directly?', 'maison-woodcraft' ) ) ); ?></h3>
			<div class="mw-panel__list">
				<?php if ( $phone ) : ?>
					<a href="<?php echo esc_url( mw_tel_link( $phone ) ); ?>">&#128222; <?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>">&#9993;&#65039; <?php echo esc_html( $email ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $whatsapp ) : ?>
				<div class="mw-panel__actions">
					<?php
					echo mw_whatsapp_button_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array( 'message' => $message ? $message : mw_page_copy( 'get-a-quote', 'aside.whatsappMessage', '' ) )
					);
					?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $hours ) : ?>
			<div class="mw-panel">
				<h3 class="mw-panel__title"><?php echo esc_html( mw_page_copy( 'get-a-quote', 'aside.hoursHeading', __( 'Business Hours', 'maison-woodcraft' ) ) ); ?></h3>
				<?php foreach ( (array) mw_site( 'businessHours' ) as $item ) : ?>
					<div class="mw-panel__row">
						<span><?php echo esc_html( mw_arg( $item, 'day', '' ) ); ?></span>
						<span><?php echo esc_html( mw_arg( $item, 'hours', '' ) ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</aside>
	<?php
	return;
endif;
?>
<div class="mw-aside-stack">
	<?php if ( $details ) : ?>
		<div class="mw-panel mw-panel--stone">
			<h3 class="mw-panel__title"><?php echo esc_html( mw_page_copy( 'contact', 'details.heading', __( 'Contact Details', 'maison-woodcraft' ) ) ); ?></h3>
			<ul class="mw-panel__list">
				<?php if ( mw_site( 'address' ) ) : ?>
					<li>&#128205; <?php echo esc_html( mw_site( 'address' ) ); ?></li>
				<?php endif; ?>
				<?php if ( mw_site( 'serviceArea' ) ) : ?>
					<li>&#128736;&#65039; <?php echo esc_html( sprintf( __( 'Serving %s', 'maison-woodcraft' ), mw_site( 'serviceArea' ) ) ); ?></li>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<li><a href="<?php echo esc_url( mw_tel_link( $phone ) ); ?>">&#128222; <?php echo esc_html( $phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<li><a href="mailto:<?php echo esc_attr( $email ); ?>">&#9993;&#65039; <?php echo esc_html( $email ); ?></a></li>
				<?php endif; ?>
			</ul>
			<?php if ( $whatsapp ) : ?>
				<div class="mw-panel__actions"><?php echo mw_whatsapp_button_html( array( 'message' => $message ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $hours ) : ?>
		<div class="mw-panel">
			<h3 class="mw-panel__title"><?php echo esc_html( mw_page_copy( 'contact', 'hours.heading', __( 'Business Hours', 'maison-woodcraft' ) ) ); ?></h3>
			<?php foreach ( (array) mw_site( 'businessHours' ) as $item ) : ?>
				<div class="mw-panel__row">
					<span><?php echo esc_html( mw_arg( $item, 'day', '' ) ); ?></span>
					<span><?php echo esc_html( mw_arg( $item, 'hours', '' ) ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php $socials = (array) mw_site( 'social' ); ?>
	<?php if ( $social && $socials ) : ?>
		<div class="mw-panel">
			<h3 class="mw-panel__title"><?php echo esc_html( mw_page_copy( 'contact', 'social.heading', __( 'Follow Us', 'maison-woodcraft' ) ) ); ?></h3>
			<div class="mw-panel__social">
				<?php foreach ( $socials as $network => $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $network ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $map && mw_site( 'mapEmbedUrl' ) ) : ?>
		<div class="mw-map">
			<iframe
				title="<?php echo esc_attr( mw_page_copy( 'contact', 'map.heading', __( 'Studio location map', 'maison-woodcraft' ) ) ); ?>"
				src="<?php echo esc_url( mw_site( 'mapEmbedUrl' ) ); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen></iframe>
		</div>
	<?php endif; ?>
</div>
