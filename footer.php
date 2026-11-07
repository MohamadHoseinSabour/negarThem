<?php
/**
 * The template for displaying the footer - 100% Matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer id="colophon" class="site-footer bg-white mt-8 md:mt-12 lg:mt-16">
		<?php
		/**
		 * Full Multi-Layer Light Footer matching Kaveh Demo 43 (#ag-theme-builder-footer)
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

<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden" class="kaveh-svg-sprite"><defs><g id="kaveh-toman-mark"><path d="M2.32 6.12C2.64 5.96 2.87 5.81 3.11 5.58C3.27 5.35 3.42 5.12 3.58 4.89C3.66 4.58 3.74 4.27 3.74 3.96H11.23C11.71 3.96 12.1 3.81 12.41 3.58C12.65 3.27 12.81 2.89 12.81 2.35V0.5H11.78V2.27C11.78 2.65 11.55 2.89 11.15 2.89H3.74V2.5C3.74 2.12 3.66 1.89 3.58 1.58C3.58 1.35 3.42 1.12 3.27 0.96C3.11 0.81 2.95 0.73 2.79 0.65C2.56 0.58 2.32 0.5 2.16 0.5C1.85 0.5 1.61 0.58 1.37 0.65C1.22 0.81 0.98 0.88 0.9 1.12C0.74 1.27 0.58 1.42 0.58 1.65C0.51 1.89 0.43 2.12 0.43 2.35C0.43 2.58 0.43 2.81 0.51 3.04C0.58 3.27 0.66 3.42 0.74 3.58C0.9 3.66 1.06 3.81 1.29 3.89C1.53 3.96 1.77 3.96 2.16 3.96H2.79C2.79 4.12 2.71 4.27 2.71 4.42C2.64 4.58 2.48 4.73 2.4 4.89C2.32 4.96 2.16 5.04 1.93 5.12C1.77 5.19 1.53 5.27 1.29 5.27H0.27V6.35H1.29C1.69 6.27 2 6.19 2.32 6.12ZM2.16 2.89C1.85 2.89 1.69 2.89 1.53 2.73C1.45 2.65 1.37 2.5 1.37 2.27C1.37 2.04 1.45 1.81 1.53 1.73C1.69 1.65 1.85 1.58 2.08 1.58C2.32 1.58 2.48 1.65 2.64 1.81C2.79 1.96 2.79 2.19 2.79 2.5V2.96H2.16V2.89Z"></path><path d="M10.44 0.5H7.45V1.42H10.44V0.5Z"></path><path d="M12.73 8.5C12.65 8.27 12.57 8.12 12.41 7.97C12.26 7.81 12.1 7.66 11.86 7.58C11.7 7.5 11.47 7.43 11.23 7.43C10.99 7.43 10.76 7.5 10.52 7.58C10.29 7.66 10.13 7.81 9.97 7.97C9.81 8.12 9.73 8.27 9.65 8.5C9.58 8.73 9.58 8.97 9.58 9.2V9.43C9.58 9.66 9.5 9.74 9.42 9.89C9.26 9.97 9.1 10.04 8.87 10.04H8.55C8.39 10.04 8.23 9.97 8.16 9.89C8.08 9.74 8 9.58 8 9.43V5.73H6.97V9.58C6.97 9.89 6.97 10.12 7.05 10.27C7.13 10.43 7.21 10.58 7.37 10.74C7.52 10.81 7.6 10.97 7.84 10.97C8 11.04 8.16 11.04 8.39 11.04H8.94C9.1 11.04 9.34 10.97 9.5 10.89C9.65 10.81 9.89 10.66 9.97 10.43C10.13 10.66 10.29 10.81 10.52 10.89C10.76 10.97 10.99 11.04 11.31 11.04C11.78 11.04 12.26 10.89 12.49 10.58C12.81 10.27 12.97 9.81 12.97 9.27C12.89 8.97 12.81 8.73 12.73 8.5ZM11.23 9.97C10.99 9.97 10.84 9.89 10.76 9.81C10.52 9.66 10.52 9.5 10.52 9.27C10.52 9.04 10.6 8.89 10.68 8.73C10.76 8.58 10.99 8.5 11.23 8.5C11.47 8.5 11.7 8.58 11.78 8.73C11.86 8.89 11.94 9.04 11.94 9.27C11.86 9.74 11.63 9.97 11.23 9.97Z"></path><path d="M4.92 8.5C4.92 8.73 4.92 8.89 4.84 9.12C4.76 9.27 4.69 9.43 4.61 9.58C4.45 9.73 4.29 9.81 4.13 9.89C3.98 9.97 3.74 10.04 3.42 10.04H2.71C2.48 10.04 2.24 10.04 2 9.97C1.85 9.81 1.69 9.73 1.61 9.58C1.53 9.43 1.37 9.27 1.37 9.12C1.29 8.97 1.29 8.73 1.29 8.5V7.66H0.27V8.58C0.27 9.35 0.51 9.97 0.9 10.43C1.29 10.89 1.85 11.12 2.64 11.12H3.42C3.82 11.12 4.13 11.04 4.45 10.89C4.76 10.74 5 10.58 5.24 10.35C5.47 10.12 5.63 9.81 5.71 9.5C5.79 9.2 5.87 8.89 5.87 8.5V5.73L4.84 5.66L4.92 8.5Z"></path><path d="M3.74 7.43H2.56V8.5H3.74V7.43Z"></path></g></defs></svg>

<?php wp_footer(); ?>

</body>
</html>
