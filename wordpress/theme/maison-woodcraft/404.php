<?php
/**
 * 404 page — the original NotFound component.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;

get_header();

mw_render( 'not-found' );

get_footer();
