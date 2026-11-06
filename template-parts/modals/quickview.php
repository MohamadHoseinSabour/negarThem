<?php
/**
 * Quick View Modal Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="negar-modal-backdrop fixed inset-0 z-50 bg-dark/60 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300" id="negar-quickview-backdrop" aria-hidden="true"></div>

<div class="negar-quickview-modal fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95" id="negar-quickview-modal" role="dialog" aria-modal="true" aria-labelledby="quickview-title">
	<div class="bg-surface rounded-2xl border border-border shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto relative p-6 sm:p-8">
		
		<!-- Close Button -->
		<button type="button" class="negar-close-quickview absolute top-4 left-4 p-2 text-muted hover:text-dark focus:outline-none rounded-full hover:bg-slate-100 transition-colors" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
		</button>

		<h2 id="quickview-title" class="sr-only"><?php esc_html_e( 'نمایش سریع کالا', 'negar-shop' ); ?></h2>

		<!-- Dynamic Body Loaded via Ajax -->
		<div id="negar-quickview-body" class="min-h-[250px] flex items-center justify-center">
			<div class="flex items-center gap-2 text-muted text-xs">
				<svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
				<span><?php esc_html_e( 'در حال دریافت اطلاعات کالا...', 'negar-shop' ); ?></span>
			</div>
		</div>

	</div>
</div>
