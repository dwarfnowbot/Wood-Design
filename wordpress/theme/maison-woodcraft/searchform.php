<?php
/**
 * Search form.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;
?>
<form role="search" method="get" class="mw-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="mw-visually-hidden" for="mw-search-field"><?php esc_html_e( 'Search for:', 'maison-woodcraft' ); ?></label>
	<input class="mw-input" type="search" id="mw-search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'maison-woodcraft' ); ?>">
	<button class="mw-btn mw-btn--primary" type="submit"><?php esc_html_e( 'Search', 'maison-woodcraft' ); ?></button>
</form>
