<?php
/**
 * Main Header Component - 100% Matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme_logo_url = get_template_directory_uri() . '/assets/images/logo.svg';
$upload_dir     = wp_upload_dir();
$logo_svg       = $upload_dir['basedir'] . '/kaveh-demo/kaveh-demo/general/logo.svg';
$logo_url       = file_exists( $logo_svg ) ? $upload_dir['baseurl'] . '/kaveh-demo/kaveh-demo/general/logo.svg' : $theme_logo_url;
$has_logo       = true;
?>

<div class="negar-main-header py-3.5 bg-white border-b border-slate-100">
	<div class="container flex items-center justify-between gap-4 lg:gap-8">

		<!-- 1. Right: Logo & Branding -->
		<div class="header-logo shrink-0 flex items-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="135" height="40" class="h-9 w-auto object-contain kavehlogost d-block" style="width: 135px !important; height: auto !important; max-height: 40px !important;">
			</a>
		</div>

		<!-- 2. Center: Search Bar with Nested Red Category Button -->
		<div class="header-search flex-1 max-w-xl lg:max-w-2xl relative hidden md:block">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative flex items-center bg-slate-100/90 hover:bg-slate-100 focus-within:bg-white rounded-full border border-slate-200/70 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-500/10 transition-all p-1">
				
				<!-- Nested Category Pill Button (Right side inside input in RTL) -->
				<div class="relative shrink-0">
					<button type="button" class="category-menu-btn flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-full transition-all shadow-xs">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
							<path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/>
						</svg>
						<span><?php esc_html_e( 'دسته‌بندی', 'negar-shop' ); ?></span>
						<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
					</button>
				</div>

				<!-- Search Input -->
				<input
					type="search"
					name="s"
					class="negar-search-input flex-1 bg-transparent border-0 px-4 py-1.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 outline-none"
					placeholder="<?php esc_attr_e( 'جستجوی محصول...', 'negar-shop' ); ?>"
					autocomplete="off"
					required
				>
				<input type="hidden" name="post_type" value="product">

				<!-- Submit / Search Icon Button (Left side inside input in RTL) -->
				<button type="submit" class="search-submit p-2 text-slate-400 hover:text-rose-600 transition flex items-center justify-center shrink-0" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</form>

			<!-- Live AJAX Results Dropdown -->
			<div class="negar-ajax-search-results hidden absolute top-full right-0 left-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50"></div>
		</div>

		<!-- 3. Left: User Account & Cart (Matching Demo Screenshot 1:1) -->
		<div class="header-actions flex items-center gap-3 shrink-0">

			<!-- User Account Pill Button -->
			<div class="header-action-item">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="flex items-center gap-2 py-1.5 px-3.5 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition border border-slate-200 text-xs font-bold shadow-2xs">
					<span><?php echo is_user_logged_in() ? esc_html( wp_get_current_user()->display_name ) : esc_html__( 'ورود / ثبت‌نام', 'negar-shop' ); ?></span>
					<div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 overflow-hidden shrink-0">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
					</div>
				</a>
			</div>

			<!-- Cart Button with Red Circle + Label (Matching Demo) -->
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<div class="header-action-item">
					<button type="button" class="negar-cart-trigger flex items-center gap-2 group cursor-pointer" aria-label="<?php esc_attr_e( 'سبد خرید', 'negar-shop' ); ?>">
						
						<!-- Red Circle with Bag Icon -->
						<div class="relative w-9 h-9 rounded-full bg-rose-600 group-hover:bg-rose-700 text-white flex items-center justify-center shadow-md shadow-rose-600/30 transition-transform group-hover:scale-105 shrink-0">
							<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
								<line x1="3" y1="6" x2="21" y2="6"></line>
								<path d="M16 10a4 4 0 0 1-8 0"></path>
							</svg>
							
							<!-- Badge Count -->
							<span class="negar-cart-count absolute -top-1 -right-1 bg-white text-rose-600 border border-rose-600 rounded-full text-[10px] w-4.5 h-4.5 flex items-center justify-center font-black leading-none">
								<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
							</span>
						</div>

						<span class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition hidden sm:inline-block">
							<?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?>
						</span>

					</button>
				</div>
			<?php endif; ?>

		</div>

	</div>
</div>
