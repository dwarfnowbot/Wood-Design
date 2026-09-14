<?php
/**
 * Single project details — used by single-mw_project.php and the
 * "Project Details" Elementor widget.
 *
 * @package Maison_Woodcraft
 */

$post_id      = mw_arg( $args, 'post_id', get_the_ID() );
$show_meta    = (bool) mw_arg( $args, 'show_meta', true );
$show_gallery = (bool) mw_arg( $args, 'show_gallery', true );
$gallery_override = mw_arg( $args, 'gallery', array() );
$location = get_post_meta( $post_id, '_mw_project_location', true );
$materials = get_post_meta( $post_id, '_mw_project_materials', true );
$terms    = get_the_terms( $post_id, 'mw_project_cat' );
$gallery  = get_post_meta( $post_id, '_mw_project_gallery', true );
$gallery  = is_array( $gallery ) ? $gallery : array_filter( array_map( 'intval', explode( ',', (string) $gallery ) ) );

if ( ! empty( $gallery_override ) ) {
	$gallery = $gallery_override;
}
if ( ! $show_gallery ) {
	$gallery = array();
}

$category = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
?>
<div class="mw-project-hero">
	<?php
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, 'mw-hero', array( 'alt' => esc_attr( get_the_title( $post_id ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
</div>
<div class="mw-project-layout">
	<div class="mw-project-content">
		<?php if ( $category ) : ?>
			<p class="mw-modal__category"><?php echo esc_html( $category ); ?></p>
		<?php endif; ?>
		<h1 class="mw-modal__title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
		<?php if ( $location ) : ?>
			<p class="mw-modal__location"><?php echo esc_html( $location ); ?></p>
		<?php endif; ?>
		<div class="mw-prose-content">
			<?php echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ) ); ?>
		</div>
	</div>
	<?php if ( $show_meta ) : ?>
	<aside class="mw-project-meta">
		<?php if ( $category ) : ?>
			<div class="mw-project-meta__row">
				<span class="mw-project-meta__label"><?php esc_html_e( 'Category', 'maison-woodcraft' ); ?></span>
				<span class="mw-project-meta__value"><?php echo esc_html( $category ); ?></span>
			</div>
		<?php endif; ?>
		<?php if ( $location ) : ?>
			<div class="mw-project-meta__row">
				<span class="mw-project-meta__label"><?php esc_html_e( 'Location', 'maison-woodcraft' ); ?></span>
				<span class="mw-project-meta__value"><?php echo esc_html( $location ); ?></span>
			</div>
		<?php endif; ?>
		<?php if ( $materials ) : ?>
			<div class="mw-project-meta__row">
				<span class="mw-project-meta__label"><?php esc_html_e( 'Materials &amp; Finishes', 'maison-woodcraft' ); ?></span>
				<span class="mw-project-meta__value"><?php echo esc_html( $materials ); ?></span>
			</div>
		<?php endif; ?>
		<div class="mw-project-meta__row">
			<a class="mw-tiwt" href="<?php echo esc_url( mw_page_url( '/projects' ) ); ?>">
				<span><?php esc_html_e( 'All Projects', 'maison-woodcraft' ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>
	</aside>
	<?php endif; ?>
</div>
<?php if ( $gallery ) : ?>
	<div class="mw-project-gallery">
		<?php foreach ( $gallery as $attachment_id ) : ?>
			<a href="<?php echo esc_url( wp_get_attachment_image_url( $attachment_id, 'full' ) ); ?>" data-mw-lightbox="1">
				<?php echo wp_get_attachment_image( $attachment_id, 'mw-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
