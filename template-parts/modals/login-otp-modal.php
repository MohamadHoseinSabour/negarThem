<?php
/**
 * Customer phone OTP login modal.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="negar-login-otp-modal" class="negar-modal-root fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-200" aria-labelledby="negar-otp-modal-title" role="dialog" aria-modal="true">
	<div class="negar-modal-panel relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-8 text-right border border-slate-100" role="document">
		<button type="button" class="negar-modal-close absolute top-5 left-5 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" aria-label="<?php esc_attr_e( 'بستن پنجره', 'negar-shop' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
		</button>

		<header class="text-center mb-6">
			<div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center" aria-hidden="true">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</div>
			<h2 id="negar-otp-modal-title" class="text-lg sm:text-xl font-black text-slate-900 mb-1 f-iran-yekan"><?php esc_html_e( 'ورود یا ثبت‌نام در نگار شاپ', 'negar-shop' ); ?></h2>
			<p class="text-xs text-slate-500 font-medium"><?php esc_html_e( 'حساب کاربری با تأیید شمارهٔ موبایل شما ایجاد یا وارد می‌شود.', 'negar-shop' ); ?></p>
		</header>

		<?php
		get_template_part(
			'template-parts/auth/otp-form',
			null,
			array(
				'instance'    => 'modal',
				'redirect_to' => home_url( '/' ),
			)
		);
		?>
	</div>
</div>
