<?php
/**
 * Process timeline — components/ProcessTimeline.tsx (horizontal) and
 * pages/Process.tsx (vertical).
 *
 * @package Maison_Woodcraft
 */

$steps  = mw_arg( $args, 'steps', mw_arg( $args, 'items', mw_process_steps() ) );
$layout = mw_arg( $args, 'layout', 'horizontal' ); // horizontal | vertical
$columns = mw_arg( $args, 'columns', '' );          // '', three, two

if ( empty( $steps ) ) {
	return;
}

$classes = 'mw-process' . ( 'vertical' === $layout ? ' mw-process--vertical' : '' );
if ( $columns ) {
	$classes .= ' mw-process--' . sanitize_html_class( $columns );
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( 'vertical' === $layout ) : ?>
		<div class="mw-process__track" aria-hidden="true"></div>
	<?php else : ?>
		<div class="mw-process__line" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="mw-process__list">
		<?php foreach ( $steps as $step ) : ?>
			<?php
			$number   = mw_arg( $step, 'number', mw_arg( $step, 'step', '' ) );
			$title    = mw_arg( $step, 'title', '' );
			$text     = mw_arg( $step, 'description', mw_arg( $step, 'text', '' ) );
			$tag      = mw_arg( $step, 'heading_tag', 'h3' );
			?>
			<div class="mw-process__step">
				<div class="mw-process__number"><?php echo esc_html( $number ); ?></div>
				<div class="mw-process__body">
					<<?php echo esc_attr( $tag ); ?> class="mw-process__title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $tag ); ?>>
					<p class="mw-process__text"><?php echo esc_html( $text ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
