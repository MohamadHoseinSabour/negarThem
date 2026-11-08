<?php
/**
 * Footer Features / Value Badges Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="footer-features-section py-8 bg-white border-b border-slate-100" aria-label="<?php esc_attr_e( 'مزایای خرید از فروشگاه', 'negar-shop' ); ?>">
	<div class="container">
		<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

			<!-- 1. Express Delivery -->
			<div class="feature-card flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
				<div class="w-12 h-12 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center shrink-0">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="1" y="3" width="15" height="13"></rect>
						<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
						<circle cx="5.5" cy="18.5" r="2.5"></circle>
						<circle cx="18.5" cy="18.5" r="2.5"></circle>
					</svg>
				</div>
				<div>
					<h4 class="text-xs font-bold text-slate-800 m-0"><?php esc_html_e( 'تحویل اکسپرس', 'negar-shop' ); ?></h4>
					<p class="text-2xs text-slate-500 m-0"><?php esc_html_e( 'ارسال سریع به سراسر کشور', 'negar-shop' ); ?></p>
				</div>
			</div>

			<!-- 2. Authentic Guarantee -->
			<div class="feature-card flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
				<div class="w-12 h-12 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center shrink-0">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						<polyline points="9 12 11 14 15 10"></polyline>
					</svg>
				</div>
				<div>
					<h4 class="text-xs font-bold text-slate-800 m-0"><?php esc_html_e( 'ضمانت اصالت', 'negar-shop' ); ?></h4>
					<p class="text-2xs text-slate-500 m-0"><?php esc_html_e( '۱۰۰٪ تضمین اصل بودن کالا', 'negar-shop' ); ?></p>
				</div>
			</div>

			<!-- 3. Return Guarantee -->
			<div class="feature-card flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
				<div class="w-12 h-12 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center shrink-0">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<polyline points="1 4 1 10 7 10"></polyline>
						<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
					</svg>
				</div>
				<div>
					<h4 class="text-xs font-bold text-slate-800 m-0"><?php esc_html_e( '۷ روز ضمانت بازگشت', 'negar-shop' ); ?></h4>
					<p class="text-2xs text-slate-500 m-0"><?php esc_html_e( 'امکان عودت بی‌قید و شرط', 'negar-shop' ); ?></p>
				</div>
			</div>

			<!-- 4. Support -->
			<div class="feature-card flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
				<div class="w-12 h-12 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center shrink-0">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
					</svg>
				</div>
				<div>
					<h4 class="text-xs font-bold text-slate-800 m-0"><?php esc_html_e( 'پشتیبانی ۲۴ ساعته', 'negar-shop' ); ?></h4>
					<p class="text-2xs text-slate-500 m-0"><?php esc_html_e( '۷ روز هفته پاسخگوی شما', 'negar-shop' ); ?></p>
				</div>
			</div>

			<!-- 5. Secure Payment -->
			<div class="feature-card flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 col-span-2 md:col-span-1">
				<div class="w-12 h-12 rounded-xl bg-rose-100/70 text-rose-700 flex items-center justify-center shrink-0">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="2" y="5" width="20" height="14" rx="2"></rect>
						<line x1="2" y1="10" x2="22" y2="10"></line>
					</svg>
				</div>
				<div>
					<h4 class="text-xs font-bold text-slate-800 m-0"><?php esc_html_e( 'پرداخت امن آنلاین', 'negar-shop' ); ?></h4>
					<p class="text-2xs text-slate-500 m-0"><?php esc_html_e( 'با کلیه کارت‌های عضو شتاب', 'negar-shop' ); ?></p>
				</div>
			</div>

		</div>
	</div>
</section>
