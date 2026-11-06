<?php
/**
 * Wishlist Off-Canvas Drawer Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="negar-wishlist-backdrop fixed inset-0 z-50 bg-dark/60 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300" id="negar-wishlist-backdrop" aria-hidden="true"></div>

<div class="negar-wishlist-drawer fixed top-0 bottom-0 left-0 w-[340px] max-w-[85vw] bg-surface z-50 flex flex-col shadow-2xl transition-transform duration-300 -translate-x-full overflow-hidden" id="negar-wishlist-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'لیست علاقه‌مندی‌ها', 'negar-shop' ); ?>">
	
	<!-- Header -->
	<div class="drawer-header flex items-center justify-between p-4 border-b border-border bg-slate-50">
		<span class="text-sm font-bold text-dark flex items-center gap-2">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-500">
				<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
			</svg>
			<span><?php esc_html_e( 'لیست علاقه‌مندی‌ها', 'negar-shop' ); ?></span>
		</span>
		<button type="button" class="negar-close-wishlist p-1 text-muted hover:text-dark focus:outline-none" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
		</button>
	</div>

	<!-- Content Body -->
	<div class="drawer-body p-4 flex-1 overflow-y-auto" id="negar-wishlist-items-container">
		<div class="py-12 text-center text-xs text-muted">
			<?php esc_html_e( 'در حال بارگذاری لیست علاقه‌مندی‌ها...', 'negar-shop' ); ?>
		</div>
	</div>

	<!-- Footer with Clear Button -->
	<div class="drawer-footer p-4 border-t border-border bg-slate-50 text-center">
		<button type="button" class="negar-clear-all-wishlist text-xs text-danger hover:underline font-medium">
			<?php esc_html_e( 'حذف همه موارد از علاقه‌مندی', 'negar-shop' ); ?>
		</button>
	</div>

</div>
