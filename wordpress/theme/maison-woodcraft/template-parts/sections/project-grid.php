<?php
/**
 * Project cards + category filter — components/ProjectCard.tsx and
 * pages/Projects.tsx.
 *
 * Projects imported into the Projects post type link to their own page; manual
 * items (e.g. the original sample concepts) open the original "View Project"
 * modal.
 *
 * @package Maison_Woodcraft
 */

$items      = mw_arg( $args, 'items', mw_projects() );
$show_filter = (bool) mw_arg( $args, 'filters', false );
$categories  = mw_arg( $args, 'categories', mw_project_categories() );
$limit       = (int) mw_arg( $args, 'limit', 0 );
$link_label  = mw_arg( $args, 'link_label', __( 'View Project', 'maison-woodcraft' ) );

if ( empty( $items ) ) {
	return;
}

if ( $limit > 0 ) {
	$items = array_slice( $items, 0, $limit );
}

if ( $show_filter ) :
	?>
	<div class="mw-filters" data-mw-filters>
		<?php foreach ( $categories as $category ) : ?>
			<button type="button" class="mw-filter<?php echo ( 'All' === $category ) ? ' is-active' : ''; ?>" data-mw-filter="<?php echo esc_attr( $category ); ?>">
				<?php echo esc_html( $category ); ?>
			</button>
		<?php endforeach; ?>
	</div>
	<?php
endif;
?>
<div class="mw-cards" data-mw-project-grid>
	<?php foreach ( $items as $item ) : ?>
		<?php
		$title    = mw_arg( $item, 'title', '' );
		$category = mw_arg( $item, 'category', '' );
		$location = mw_arg( $item, 'location', '' );
		$text     = mw_arg( $item, 'description', mw_arg( $item, 'text', '' ) );
		$materials = mw_arg( $item, 'materials', '' );
		$image    = mw_arg( $item, 'image', '' );
		$url      = mw_arg( $item, 'url', mw_arg( $item, 'permalink', '' ) );
		$data     = wp_json_encode(
			array(
				'title'     => $title,
				'category'  => $category,
				'location'  => $location,
				'text'      => $text,
				'materials' => $materials,
				'image'     => mw_resolve_image( $image )['url'],
			)
		);
		?>
		<article class="mw-card mw-card--project" data-mw-category="<?php echo esc_attr( $category ); ?>" data-mw-project="<?php echo esc_attr( $data ); ?>">
			<div class="mw-card__media">
				<?php echo mw_image_html( $image, '', $title, array( 'sizes' => '(max-width: 639px) 100vw, (max-width: 1023px) 50vw, 400px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $category ) : ?>
					<span class="mw-card__badge"><?php echo esc_html( $category ); ?></span>
				<?php endif; ?>
			</div>
			<div class="mw-card__body">
				<h3 class="mw-card__title"><?php echo esc_html( $title ); ?></h3>
				<?php if ( $location ) : ?>
					<p class="mw-card__meta"><?php echo esc_html( $location ); ?></p>
				<?php endif; ?>
				<p class="mw-card__text"><?php echo esc_html( $text ); ?></p>
				<?php if ( $url ) : ?>
					<a class="mw-tiwt mw-card__link" href="<?php echo esc_url( mw_page_url( $url ) ); ?>">
						<span><?php echo esc_html( $link_label ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php else : ?>
					<button type="button" class="mw-tiwt mw-card__link" data-mw-view-project>
						<span><?php echo esc_html( $link_label ); ?></span><?php echo mw_svg( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>
			</div>
		</article>
	<?php endforeach; ?>
</div>
<div class="mw-modal" data-mw-modal hidden>
	<div class="mw-modal__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Project details', 'maison-woodcraft' ); ?>">
		<button type="button" class="mw-modal__close" data-mw-modal-close aria-label="<?php esc_attr_e( 'Close project details', 'maison-woodcraft' ); ?>">&times;</button>
		<div class="mw-modal__media"><img src="<?php echo esc_url( mw_placeholder_image() ); ?>" alt="" /></div>
		<div class="mw-modal__body">
			<span class="mw-modal__category" data-mw-modal-category></span>
			<h3 class="mw-modal__title" data-mw-modal-title></h3>
			<p class="mw-modal__location" data-mw-modal-location></p>
			<p class="mw-modal__text" data-mw-modal-text></p>
			<div class="mw-modal__materials" data-mw-modal-materials hidden>
				<p class="mw-modal__materials-label"><?php esc_html_e( 'Materials &amp; Finishes', 'maison-woodcraft' ); ?></p>
				<p class="mw-modal__materials-text" data-mw-modal-materials-text></p>
			</div>
		</div>
	</div>
</div>
