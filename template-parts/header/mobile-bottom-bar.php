<?php
/**
 * Mobile Sticky Bottom App Bar Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="negar-mobile-bottom-bar md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 py-2 px-3 shadow-lg" aria-label="<?php esc_attr_e( 'ناوبری سریع موبایل', 'negar-shop' ); ?>">
	<div class="flex items-center justify-around">

		<!-- 1. Home -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bottom-bar-item flex flex-col items-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo is_front_page() ? 'text-rose-600 font-bold' : ''; ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
				<polyline points="9 22 9 12 15 12 15 22"></polyline>
			</svg>
			<span class="text-3xs"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></span>
		</a>

		<!-- 2. Categories / Shop -->
		<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="bottom-bar-item flex flex-col items-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo ( function_exists( 'is_shop' ) && is_shop() ) ? 'text-rose-600 font-bold' : ''; ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<rect x="3" y="3" width="7" height="7"></rect>
				<rect x="14" y="3" width="7" height="7"></rect>
				<rect x="14" y="14" width="7" height="7"></rect>
				<rect x="3" y="14" width="7" height="7"></rect>
			</svg>
			<span class="text-3xs"><?php esc_html_e( 'دسته‌ها', 'negar-shop' ); ?></span>
		</a>

		<!-- 3. Cart Trigger -->
		<button type="button" class="bottom-bar-item negar-cart-trigger flex flex-col items-center gap-1 text-slate-500 hover:text-rose-600 transition relative">
			<div class="relative">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
					<line x1="3" y1="6" x2="21" y2="6"></line>
					<path d="M16 10a4 4 0 0 1-8 0"></path>
				</svg>
				<span class="negar-cart-count badge badge-discount absolute -top-1.5 -right-2 text-3xs px-1 py-0">
					<?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
				</span>
			</div>
			<span class="text-3xs"><?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?></span>
		</button>

		<!-- 4. Wishlist -->
		<a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" class="bottom-bar-item flex flex-col items-center gap-1 text-slate-500 hover:text-rose-600 transition">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
			</svg>
			<span class="text-3xs"><?php esc_html_e( 'علاقه‌مندی', 'negar-shop' ); ?></span>
		</a>

		<!-- 5. User Account -->
		<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>" class="bottom-bar-item flex flex-col items-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo ( function_exists( 'is_account_page' ) && is_account_page() ) ? 'text-rose-600 font-bold' : ''; ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
				<circle cx="12" cy="7" r="4"></circle>
			</svg>
			<span class="text-3xs"><?php esc_html_e( 'حساب من', 'negar-shop' ); ?></span>
		</a>

	</div>
</nav>
