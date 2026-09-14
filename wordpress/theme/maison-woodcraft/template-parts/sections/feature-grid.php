<?php
/**
 * Feature grid — "Why Clients Choose Us" (Home/Wardrobes/Kitchens) and the
 * numbered "Our Approach" list on the About page.
 *
 * @package Maison_Woodcraft
 */

$items    = mw_arg( $args, 'items', mw_why_choose_us() );
$variant  = mw_arg( $args, 'variant', '' );       // light | numbered
$columns  = mw_arg( $args, 'columns', 'three' );  // two | three
$numbered = ( 'numbered' === $variant );

if ( empty( $items ) ) {
	return;
}

$classes = 'mw-features';
if ( 'light' === $variant ) {
	$classes .= ' mw-features--light';
}
if ( $numbered ) {
	$classes .= ' mw-features--numbered';
}
if ( 'three' === $columns && ! $numbered ) {
	$classes .= ' mw-features--three';
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php foreach ( $items as $index => $item ) : ?>
		<?php
		$title = mw_arg( $item, 'title', '' );
		$text  = mw_arg( $item, 'description', mw_arg( $item, 'text', '' ) );
		$index_label = mw_arg( $item, 'number', str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) );
		?>
		<div class="mw-features__item">
			<?php if ( $numbered ) : ?>
				<span class="mw-features__index"><?php echo esc_html( $index_label ); ?></span>
				<div class="mw-features__content">
					<h3 class="mw-features__title"><?php echo esc_html( $title ); ?></h3>
					<p class="mw-features__text"><?php echo esc_html( $text ); ?></p>
				</div>
			<?php else : ?>
				<h3 class="mw-features__title"><?php echo esc_html( $title ); ?></h3>
				<p class="mw-features__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
