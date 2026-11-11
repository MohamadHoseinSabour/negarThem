<?php
/**
 * Mobile Header Template Part
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
?>
<div class="negar-mobile-header md:hidden bg-white border-b border-slate-200 sticky top-0 z-30" style="padding-top: max(0.5rem, env(safe-area-inset-top, 0px));">
	<!-- Top Bar in Mobile -->
	<div class="px-4 py-3 flex items-center justify-between gap-3">
		<!-- Hamburger Menu Trigger -->
		<button type="button" class="mobile-menu-trigger w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-700" aria-label="<?php esc_attr_e( 'منوی ناوبری موبایل', 'negar-shop' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
				<line x1="4" y1="7" x2="20" y2="7"></line>
				<line x1="4" y1="12" x2="20" y2="12"></line>
				<line x1="4" y1="17" x2="14" y2="17"></line>
			</svg>
		</button>

		<!-- Brand Logo -->
		<div class="mobile-logo flex-1 flex items-center justify-center text-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="inline-flex items-center justify-center">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( negar_theme_brand_label() ); ?>" width="120" height="32" class="h-8 w-auto object-contain d-inline-block" style="height: 32px !important; max-height: 32px !important; width: auto !important; min-width: 60px;">
			</a>
		</div>

		<!-- Cart Trigger Icon -->
		<button type="button" class="negar-cart-trigger w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-700 relative" aria-label="<?php esc_attr_e( 'سبد خرید', 'negar-shop' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
			<span class="negar-cart-count-mobile negar-cart-count badge badge-discount absolute -top-1.5 -right-1.5 text-2xs px-1.5 py-0.5">
				<?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
			</span>
		</button>
	</div>

	<!-- Mobile Search Row -->
	<div class="px-4 pb-3 relative header-search-wrap">
		<form role="search" method="get" class="negar-ajax-search-form flex items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-rose-600 focus-within:bg-white transition" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input
				type="search"
				name="s"
				class="negar-search-input w-full bg-transparent border-0 px-3.5 py-2 text-xs text-slate-800 placeholder-slate-400 outline-none"
				placeholder="<?php echo esc_attr( negar_theme_text( 'header', 'mobile_search_placeholder' ) ); ?>"
				autocomplete="off"
				required
			>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<span class="search-spinner hidden p-1.5 text-rose-500 animate-spin flex items-center justify-center shrink-0">
				<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
			</span>
			<button type="submit" class="p-2 text-slate-400 hover:text-rose-600" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</button>
		</form>
		<!-- Mobile AJAX Results Dropdown -->
		<div class="negar-ajax-search-results hidden absolute top-full right-4 left-4 mt-1 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50"></div>
	</div>
</div>
