<?php
/**
 * Customer Phone OTP Login & Registration Modal Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="negar-login-otp-modal" class="negar-modal-root fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200" aria-labelledby="negar-otp-modal-title" role="dialog" aria-modal="true">
	<div class="negar-modal-panel relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-8 transform transition-all text-right border border-slate-100" onclick="event.stopPropagation();">
		
		<!-- Close Button -->
		<button type="button" class="negar-modal-close absolute top-5 left-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" aria-label="<?php esc_attr_e( 'بستن پنجره', 'negar-shop' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
		</button>

		<!-- Modal Header with Icon -->
		<div class="text-center mb-6">
			<div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
			</div>
			<h2 id="negar-otp-modal-title" class="text-lg sm:text-xl font-black text-slate-900 mb-1 f-iran-yekan">
				<?php esc_html_e( 'ورود یا ثبت‌نام در نگارشاپ', 'negar-shop' ); ?>
			</h2>
			<p id="negar-otp-modal-desc" class="text-xs text-slate-500 font-medium">
				<?php esc_html_e( 'برای دسترسی به حساب کاربری، لطفاً شماره موبایل خود را وارد کنید.', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- Step 1: Phone Number Input Form -->
		<form id="negar-otp-step1-form" class="space-y-4">
			<div>
				<label for="negar-otp-phone-input" class="block text-2xs font-bold text-slate-700 mb-1.5">
					<?php esc_html_e( 'شماره موبایل', 'negar-shop' ); ?>
				</label>
				<div class="relative flex items-center">
					<input
						type="tel"
						id="negar-otp-phone-input"
						class="w-full bg-slate-50 border border-slate-200 focus:border-rose-600 focus:bg-white focus:ring-2 focus:ring-rose-500/10 rounded-2xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 outline-none text-left font-mono tracking-widest transition"
						placeholder="۰۹۱۲۳۴۵۶۷۸۹"
						dir="ltr"
						maxlength="11"
						required
						autocomplete="tel"
					>
					<span class="absolute right-3.5 text-slate-400">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
					</span>
				</div>
				<p class="text-3xs text-slate-400 mt-1.5 mr-1">
					<?php esc_html_e( 'کد تایید ۵ رقمی از طریق پیامک برای این شماره ارسال خواهد شد.', 'negar-shop' ); ?>
				</p>
			</div>

			<div id="negar-otp-step1-error" class="hidden text-xs text-rose-600 bg-rose-50 p-2.5 rounded-xl font-medium"></div>

			<button type="submit" id="negar-otp-step1-submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-3 px-4 rounded-2xl shadow-lg shadow-rose-600/25 transition-all flex items-center justify-center gap-2">
				<span><?php esc_html_e( 'دریافت کد تایید', 'negar-shop' ); ?></span>
				<span class="negar-otp-spinner hidden animate-spin">
					<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
				</span>
			</button>
		</form>

		<!-- Step 2: Verification Code Input Form (Initially Hidden) -->
		<form id="negar-otp-step2-form" class="space-y-4 hidden">
			<div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 flex items-center justify-between text-xs text-slate-600 mb-2">
				<span id="negar-otp-target-display" class="font-mono font-bold text-slate-800" dir="ltr"></span>
				<button type="button" id="negar-otp-back-btn" class="text-rose-600 hover:underline text-2xs font-bold">
					<?php esc_html_e( 'ویرایش شماره', 'negar-shop' ); ?>
				</button>
			</div>

			<div>
				<label for="negar-otp-code-input" class="block text-2xs font-bold text-slate-700 mb-1.5">
					<?php esc_html_e( 'کد تایید پیامک شده', 'negar-shop' ); ?>
				</label>
				<input
					type="text"
					id="negar-otp-code-input"
					class="w-full bg-slate-50 border border-slate-200 focus:border-rose-600 focus:bg-white focus:ring-2 focus:ring-rose-500/10 rounded-2xl px-4 py-3 text-center text-lg font-mono tracking-widest text-slate-800 outline-none transition"
					placeholder="— — — — —"
					dir="ltr"
					maxlength="5"
					required
					autocomplete="one-time-code"
				>
			</div>

			<div class="flex items-center justify-between text-xs text-slate-500">
				<span id="negar-otp-timer-wrap">
					<?php esc_html_e( 'ارسال مجدد کد در: ', 'negar-shop' ); ?>
					<span id="negar-otp-timer" class="font-mono font-bold text-rose-600">۰۱:۰۰</span>
				</span>
				<button type="button" id="negar-otp-resend-btn" class="hidden text-rose-600 hover:underline font-bold" disabled>
					<?php esc_html_e( 'ارسال مجدد کد', 'negar-shop' ); ?>
				</button>
			</div>

			<div id="negar-otp-step2-error" class="hidden text-xs text-rose-600 bg-rose-50 p-2.5 rounded-xl font-medium"></div>

			<button type="submit" id="negar-otp-step2-submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-3 px-4 rounded-2xl shadow-lg shadow-rose-600/25 transition-all flex items-center justify-center gap-2">
				<span><?php esc_html_e( 'ورود به حساب کاربری', 'negar-shop' ); ?></span>
				<span class="negar-otp-spinner hidden animate-spin">
					<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
				</span>
			</button>
		</form>

	</div>
</div>
