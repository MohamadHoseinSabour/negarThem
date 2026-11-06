<?php
/**
 * The template for displaying the footer
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer id="colophon" class="site-footer bg-slate-900 text-slate-300 py-8 mt-auto">
		<div class="container text-center">
			<div class="site-info text-sm">
				<p class="m-0">
					<?php
					/* translators: 1: Current Year, 2: Site Title */
					printf(
						esc_html__( 'تمامی حقوق برای %1$s محفوظ است. © %2$s', 'negar-shop' ),
						esc_html( get_bloginfo( 'name' ) ),
						esc_html( date_i18n( 'Y' ) )
					);
					?>
				</p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
