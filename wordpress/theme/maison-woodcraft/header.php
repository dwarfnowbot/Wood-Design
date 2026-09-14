<?php
/**
 * Site header.
 *
 * Elementor Pro's Theme Builder takes over when a header template is assigned to
 * the current page; otherwise the theme's own header is used.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#mw-content"><?php esc_html_e( 'Skip to content', 'maison-woodcraft' ); ?></a>

<div class="mw-site">
	<?php
	if ( ! mw_elementor_do_location( 'header' ) ) {
		get_template_part( 'template-parts/header/site-header' );
	}
	?>

	<main id="mw-content" class="mw-main">
