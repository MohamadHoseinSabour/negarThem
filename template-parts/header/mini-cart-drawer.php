<?php
/**
 * Off-canvas Slide-out Mini Cart Drawer
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="negar-cart-drawer" class="negar-drawer offcanvas-drawer fixed top-0 bottom-0 left-0 w-full max-w-md bg-white shadow-2xl z-50 flex flex-col transition duration-300 transform -translate-x-full" aria-hidden="true">
	<!-- Drawer Header -->
	<div class="drawer-header p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
		<div class="flex items-center gap-2.5">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-rose-600">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
			<h3 class="text-base font-bold text-slate-800 m-0">
				<?php esc_html_e( 'سبد خرید شما', 'negar-shop' ); ?>
			</h3>
			<span class="negar-cart-count badge badge-discount text-xs">
				<?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
			</span>
		</div>
		<button type="button" class="drawer-close-btn w-8 h-8 rounded-full bg-white hover:bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 transition" aria-label="<?php esc_attr_e( 'بستن سبد خرید', 'negar-shop' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
	</div>

	<!-- Drawer Body: WooCommerce Mini-Cart Content -->
	<div class="drawer-body flex-1 overflow-y-auto p-5 widget_shopping_cart_content">
		<?php
		if ( function_exists( 'woocommerce_mini_cart' ) ) {
			woocommerce_mini_cart();
		} else {
			?>
			<div class="empty-cart-state text-center py-12">
				<p class="text-slate-500"><?php esc_html_e( 'ووکامرس فعال نیست.', 'negar-shop' ); ?></p>
			</div>
			<?php
		}
		?>
	</div>
</div>

<!-- Drawer Backdrop Overlay -->
<div id="negar-drawer-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden opacity-0 transition-opacity duration-300"></div>
