<?php
/**
 * Section heading — components/SectionHeading.tsx
 *
 * @package Maison_Woodcraft
 */

$eyebrow     = mw_arg( $args, 'eyebrow', '' );
$heading     = mw_arg( $args, 'heading', '' );
$description = mw_arg( $args, 'description', '' );
$align       = mw_arg( $args, 'align', 'left' );
$light       = (bool) mw_arg( $args, 'light', false );
$tag         = mw_arg( $args, 'tag', 'h2' );
$class       = mw_arg( $args, 'class', '' );

if ( ! $heading && ! $eyebrow && ! $description ) {
	return;
}

$classes = 'mw-heading';
if ( 'center' === $align ) {
	$classes .= ' mw-heading--center';
}
if ( $light ) {
	$classes .= ' mw-heading--light';
}
if ( $class ) {
	$classes .= ' ' . $class;
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( $eyebrow ) : ?>
		<span class="mw-heading__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
	<?php endif; ?>
	<?php if ( $heading ) : ?>
		<<?php echo esc_attr( $tag ); ?> class="mw-heading__title"><?php echo esc_html( $heading ); ?></<?php echo esc_attr( $tag ); ?>>
	<?php endif; ?>
	<?php if ( $description ) : ?>
		<p class="mw-heading__text"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>
</div>
