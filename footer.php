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
		 * 2. Full Multi-Layer Light Footer matching Kaveh Demo 43 (FTR-03, FTR-04, FTR-05)
		 */
		get_template_part( 'template-parts/footer/columns' );
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

// Quick View Modal (MODAL-01)
get_template_part( 'template-parts/modals/quickview' );

// Wishlist Off-Canvas Drawer (MODAL-02)
get_template_part( 'template-parts/modals/wishlist-drawer' );

// Product Compare Modal (MODAL-03)
get_template_part( 'template-parts/modals/compare-modal' );

// Toast Notification Alerts Container (MODAL-04)
get_template_part( 'template-parts/modals/toast' );
?>

<?php wp_footer(); ?>

</body>
</html>
