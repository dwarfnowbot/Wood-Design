<?php
/**
 * Bullet feature list — kitchen & wardrobe feature lists.
 *
 * @package Maison_Woodcraft
 */

$items   = mw_arg( $args, 'items', array() );
$title   = mw_arg( $args, 'title', '' );
$columns = (int) mw_arg( $args, 'columns', 2 );

if ( empty( $items ) ) {
	return;
}
?><?php if ( $title ) : ?>
	<h3 class="mw-checklist__title"><?php echo esc_html( $title ); ?></h3>
<?php endif; ?>
<ul class="mw-checklist<?php echo 2 === $columns ? ' mw-checklist--two' : ''; ?>">
	<?php foreach ( $items as $item ) : ?>
		<li class="mw-checklist__item">
			<span class="mw-checklist__dot" aria-hidden="true"></span>
			<?php echo esc_html( is_array( $item ) ? mw_arg( $item, 'text', mw_arg( $item, 'label', '' ) ) : $item ); ?>
		</li>
	<?php endforeach; ?>
</ul>
