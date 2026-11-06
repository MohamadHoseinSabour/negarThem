<?php
/**
 * Footer Newsletter Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="footer-newsletter-section py-8 bg-slate-900 text-white" aria-label="<?php esc_attr_e( 'عضویت در خبرنامه تخفیف‌ها', 'negar-shop' ); ?>">
	<div class="container flex flex-col md:flex-row items-center justify-between gap-6">

		<!-- Text Information -->
		<div class="newsletter-meta text-center md:text-right">
			<h3 class="text-lg md:text-xl font-bold text-white mb-1 flex items-center justify-center md:justify-start gap-2">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-rose-400">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
					<polyline points="22,6 12,13 2,6"></polyline>
				</svg>
				<span><?php esc_html_e( 'از جدیدترین تخفیف‌ها و حراجی‌ها باخبر شوید!', 'negar-shop' ); ?></span>
			</h3>
			<p class="text-xs text-slate-400 m-0">
				<?php esc_html_e( 'با ثبت ایمیل یا شماره همراه خود، کدهای تخفیف اختصاصی را زودتر از دیگران دریافت کنید.', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- Form -->
		<div class="newsletter-form-wrap w-full md:w-auto md:min-w-[420px]">
			<form class="negar-newsletter-form flex items-center rounded-xl bg-slate-800 border border-slate-700 p-1 focus-within:border-rose-500 transition" onsubmit="event.preventDefault(); window.NegarShop && window.NegarShop.toast ? window.NegarShop.toast('با تشکر! اشتراک شما در خبرنامه ثبت گردید.', 'success') : alert('با تشکر!');">
				<input
					type="email"
					name="newsletter_email"
					class="w-full bg-transparent border-0 px-4 py-2 text-xs text-white placeholder-slate-400 outline-none"
					placeholder="<?php esc_attr_e( 'ایمیل یا شماره همراه خود را وارد کنید...', 'negar-shop' ); ?>"
					required
				>
				<button type="submit" class="btn btn-primary btn-sm rounded-lg px-5 py-2.5 font-bold text-xs shrink-0">
					<?php esc_html_e( 'عضویت', 'negar-shop' ); ?>
				</button>
			</form>
		</div>

	</div>
</section>
