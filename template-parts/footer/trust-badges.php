<?php
/**
 * Trust Badges (Enamad, Samandehi, Gateways)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="footer-trust-badges">
	<p class="text-xs text-slate-400 mb-3 leading-relaxed">
		<?php esc_html_e( 'دارای کلیه مجوزهای رسمی و گواهینامه‌های پرداخت امن بانکی', 'negar-shop' ); ?>
	</p>
	<div class="flex items-center gap-3">
		<!-- Enamad Box -->
		<div class="trust-badge-card bg-white p-2.5 rounded-xl border border-slate-700 flex flex-col items-center justify-center w-24 h-24 hover:border-rose-500 transition cursor-pointer">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2">
				<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
			</svg>
			<span class="text-3xs font-bold text-slate-800 mt-1.5"><?php esc_html_e( 'ای‌نماد ۵ ستاره', 'negar-shop' ); ?></span>
		</div>

		<!-- Samandehi Box -->
		<div class="trust-badge-card bg-white p-2.5 rounded-xl border border-slate-700 flex flex-col items-center justify-center w-24 h-24 hover:border-rose-500 transition cursor-pointer">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2">
				<circle cx="12" cy="12" r="10"></circle>
				<polyline points="12 6 12 12 16 14"></polyline>
			</svg>
			<span class="text-3xs font-bold text-slate-800 mt-1.5"><?php esc_html_e( 'نشان ملی ثبت', 'negar-shop' ); ?></span>
		</div>

		<!-- Shaparak Gateway Box -->
		<div class="trust-badge-card bg-white p-2.5 rounded-xl border border-slate-700 flex flex-col items-center justify-center w-24 h-24 hover:border-rose-500 transition cursor-pointer">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
				<rect x="2" y="5" width="20" height="14" rx="2"></rect>
				<line x1="2" y1="10" x2="22" y2="10"></line>
			</svg>
			<span class="text-3xs font-bold text-slate-800 mt-1.5"><?php esc_html_e( 'درگاه شاپرک', 'negar-shop' ); ?></span>
		</div>
	</div>
</div>
