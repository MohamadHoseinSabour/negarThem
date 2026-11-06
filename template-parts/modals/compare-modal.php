<?php
/**
 * Compare Modal Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="negar-compare-backdrop fixed inset-0 z-50 bg-dark/60 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300" id="negar-compare-backdrop" aria-hidden="true"></div>

<div class="negar-compare-modal fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300 transform scale-95" id="negar-compare-modal" role="dialog" aria-modal="true" aria-labelledby="compare-modal-title">
	<div class="bg-surface rounded-2xl border border-border shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto relative p-6 sm:p-8">
		
		<!-- Header -->
		<div class="flex items-center justify-between pb-4 mb-6 border-b border-border">
			<h2 id="compare-modal-title" class="text-base sm:text-lg font-bold text-dark flex items-center gap-2">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/></svg>
				<span><?php esc_html_e( 'مقایسه محصولات منتخب', 'negar-shop' ); ?></span>
			</h2>
			<button type="button" class="negar-close-compare p-1 text-muted hover:text-dark focus:outline-none" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
			</button>
		</div>

		<!-- Dynamic Body -->
		<div id="negar-compare-table-container" class="min-h-[200px]">
			<div class="py-12 text-center text-xs text-muted">
				<?php esc_html_e( 'در حال بارگذاری جدول مقایسه...', 'negar-shop' ); ?>
			</div>
		</div>

	</div>
</div>
