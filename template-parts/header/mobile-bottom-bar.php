<?php
/**
 * Mobile Sticky Bottom App Bar Template Part
 *
 * Minimalist 3-item floating island: Home, Wishlist, Account/Login.
 *
 * @package Negar_Shop
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_home     = is_front_page();
$is_wishlist = ( function_exists( 'is_page' ) && is_page( 'wishlist' ) );
$is_account  = ( function_exists( 'is_account_page' ) && is_account_page() );
?>
<nav class="negar-mobile-bottom-bar md:hidden fixed z-40 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-900/10 py-2 px-3" style="bottom: calc(10px + env(safe-area-inset-bottom, 0px)); left: 16px; right: 16px; max-width: 440px; margin: 0 auto;" aria-label="<?php esc_attr_e( 'ناوبری سریع موبایل', 'negar-shop' ); ?>">
	<div class="grid grid-cols-3 items-center text-center">

		<!-- 1. Home — خانه -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bottom-bar-item flex flex-col items-center justify-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo $is_home ? 'is-active text-rose-600 font-bold' : ''; ?>" aria-label="<?php esc_attr_e( 'خانه', 'negar-shop' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
				<polyline points="9 22 9 12 15 12 15 22"></polyline>
			</svg>
			<span class="text-3xs font-medium leading-tight"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></span>
		</a>

		<!-- 2. Wishlist — علاقه‌مندی‌ها -->
		<a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" class="bottom-bar-item negar-wishlist-trigger flex flex-col items-center justify-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo $is_wishlist ? 'is-active text-rose-600 font-bold' : ''; ?>" aria-label="<?php esc_attr_e( 'علاقه‌مندی‌ها', 'negar-shop' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
			</svg>
			<span class="text-3xs font-medium leading-tight"><?php esc_html_e( 'علاقه‌مندی‌ها', 'negar-shop' ); ?></span>
		</a>

		<!-- 3. User Account / Login — حساب کاربری / ورود -->
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>" class="bottom-bar-item flex flex-col items-center justify-center gap-1 text-slate-500 hover:text-rose-600 transition <?php echo $is_account ? 'is-active text-rose-600 font-bold' : ''; ?>" aria-label="<?php esc_attr_e( 'حساب کاربری', 'negar-shop' ); ?>">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
				<span class="text-3xs font-medium leading-tight"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></span>
			</a>
		<?php else : ?>
			<button type="button" class="bottom-bar-item negar-login-trigger flex flex-col items-center justify-center gap-1 text-slate-500 hover:text-rose-600 transition" aria-label="<?php esc_attr_e( 'ورود به حساب', 'negar-shop' ); ?>">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
				<span class="text-3xs font-medium leading-tight"><?php esc_html_e( 'ورود', 'negar-shop' ); ?></span>
			</button>
		<?php endif; ?>

	</div>
</nav>
