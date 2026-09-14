<?php
/**
 * FAQ accordion — pages/Kitchens.tsx and pages/Wardrobes.tsx.
 *
 * @package Maison_Woodcraft
 */

$items      = mw_arg( $args, 'items', array() );
$open_first = (bool) mw_arg( $args, 'open_first', mw_arg( $args, 'first_open', true ) );

if ( empty( $items ) ) {
	return;
}
?>
<div class="mw-faq" data-mw-faq>
	<?php foreach ( $items as $index => $item ) : ?>
		<?php
		$question = mw_arg( $item, 'q', mw_arg( $item, 'question', '' ) );
		$answer   = mw_arg( $item, 'a', mw_arg( $item, 'answer', '' ) );
		$is_open  = ( $open_first && 0 === $index );
		$id       = 'mw-faq-' . wp_unique_id();
		?>
		<div class="mw-faq__item">
			<h3 class="mw-faq__heading">
				<button type="button" class="mw-faq__question" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $id ); ?>">
					<span class="mw-faq__question-text"><?php echo esc_html( $question ); ?></span>
					<span class="mw-faq__icon" aria-hidden="true"><?php echo $is_open ? '&minus;' : '+'; ?></span>
				</button>
			</h3>
			<div class="mw-faq__answer" id="<?php echo esc_attr( $id ); ?>"<?php echo $is_open ? '' : ' hidden'; ?>>
				<?php echo esc_html( $answer ); ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
