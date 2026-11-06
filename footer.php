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
	<footer id="colophon" class="site-footer">
		<?php
		/**
		 * 1. Value Guarantee Badges (FTR-01)
		 */
		get_template_part( 'template-parts/footer/features' );

		/**
		 * 2. Newsletter Signup Section (FTR-02)
		 */
		get_template_part( 'template-parts/footer/newsletter' );

		/**
		 * 3. 4-Column Footer Links & Info (FTR-03 & FTR-04)
		 */
		get_template_part( 'template-parts/footer/columns' );

		/**
		 * 4. Copyright, Socials & Back to Top (FTR-05)
		 */
		get_template_part( 'template-parts/footer/copyright' );
		?>
	</footer>
</div><!-- #page -->

<?php
/**
 * Global Modals and Drawers
 */
// Slide-out Mini Cart Drawer (FLOW-01)
get_template_part( 'template-parts/header/mini-cart-drawer' );

// Mobile Navigation Drawer (HDR-05)
get_template_part( 'template-parts/header/mobile-drawer' );

// Mobile Bottom Sticky App Bar (HDR-05)
get_template_part( 'template-parts/header/mobile-bottom-bar' );
?>

<?php wp_footer(); ?>

</body>
</html>
