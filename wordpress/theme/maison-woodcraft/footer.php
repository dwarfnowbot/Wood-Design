<?php
/**
 * Site footer.
 *
 * @package Maison_Woodcraft
 */

defined( 'ABSPATH' ) || exit;
?>
	</main>

	<?php
	if ( ! mw_elementor_do_location( 'footer' ) ) {
		get_template_part( 'template-parts/footer/site-footer' );
	}
	?>
</div>

<?php wp_footer(); ?>
</body>
</html>
