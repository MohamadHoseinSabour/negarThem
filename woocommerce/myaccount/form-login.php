<?php
/**
 * Login & Registration Auth Form Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable_registration = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="negar-auth-wrapper py-8">
	<div class="container mx-auto px-4 max-w-4xl">

		<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

		<div class="grid grid-cols-1 <?php echo $enable_registration ? 'md:grid-cols-2' : 'max-w-md mx-auto'; ?> gap-8 items-start">

			<!-- Login Card -->
			<div class="auth-card bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">
				
				<div class="auth-header mb-6 pb-4 border-b border-border">
					<h2 class="text-lg sm:text-xl font-black text-dark mb-1 flex items-center gap-2">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
						<span><?php esc_html_e( 'ورود به حساب کاربری', 'negar-shop' ); ?></span>
					</h2>
					<p class="text-xs text-muted"><?php esc_html_e( 'اگر قبلاً در سایت عضو شده‌اید، وارد شوید.', 'negar-shop' ); ?></p>
				</div>

				<form class="woocommerce-form woocommerce-form-login login flex flex-col gap-4 text-xs sm:text-sm" method="post">

					<?php do_action( 'woocommerce_login_form_start' ); ?>

					<div class="form-row">
						<label for="username" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'نام کاربری یا ایمیل', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
					</div>

					<div class="form-row">
						<div class="flex items-center justify-between mb-1.5">
							<label for="password" class="font-bold text-dark block"><?php esc_html_e( 'کلمه عبور', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-xs text-primary hover:underline font-medium"><?php esc_html_e( 'فراموشی رمز؟', 'negar-shop' ); ?></a>
						</div>
						<div class="relative flex items-center">
							<input class="woocommerce-Input woocommerce-Input--text input-text w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr" type="password" name="password" id="password" autocomplete="current-password" required />
						</div>
					</div>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<div class="form-row flex items-center justify-between mt-1">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme inline-flex items-center gap-2 cursor-pointer">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox rounded border-border text-primary focus:ring-primary h-4 w-4" name="rememberme" type="checkbox" id="rememberme" value="forever" />
							<span class="text-xs text-muted"><?php esc_html_e( 'مرا به خاطر بسپار', 'negar-shop' ); ?></span>
						</label>
					</div>

					<div class="form-row mt-2">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn btn-primary w-full py-3 text-sm font-bold shadow-md hover:shadow-lg transition-all" name="login" value="<?php esc_attr_e( 'ورود به حساب', 'negar-shop' ); ?>">
							<?php esc_html_e( 'ورود به حساب', 'negar-shop' ); ?>
						</button>
					</div>

					<?php do_action( 'woocommerce_login_form_end' ); ?>

				</form>

			</div>

			<!-- Registration Card -->
			<?php if ( $enable_registration ) : ?>
				<div class="auth-card bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">
					
					<div class="auth-header mb-6 pb-4 border-b border-border">
						<h2 class="text-lg sm:text-xl font-black text-dark mb-1 flex items-center gap-2">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
							<span><?php esc_html_e( 'عضویت جدید در فروشگاه', 'negar-shop' ); ?></span>
						</h2>
						<p class="text-xs text-muted"><?php esc_html_e( 'اگر کاربر جدید هستید، ثبت‌نام کنید.', 'negar-shop' ); ?></p>
					</div>

					<form method="post" class="woocommerce-form woocommerce-form-register register flex flex-col gap-4 text-xs sm:text-sm" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
							<div class="form-row">
								<label for="reg_username" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'نام کاربری', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
							</div>
						<?php endif; ?>

						<div class="form-row">
							<label for="reg_email" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'آدرس ایمیل', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
						</div>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
							<div class="form-row">
								<label for="reg_password" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'کلمه عبور', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr" name="password" id="reg_password" autocomplete="new-password" required />
							</div>
						<?php else : ?>
							<p class="text-xs text-muted leading-relaxed"><?php esc_html_e( 'لینک تعیین کلمه عبور به ایمیل شما ارسال خواهد شد.', 'negar-shop' ); ?></p>
						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<div class="form-row mt-2">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn btn-outline btn-primary w-full py-3 text-sm font-bold transition-all" name="register" value="<?php esc_attr_e( 'ثبت‌نام در سایت', 'negar-shop' ); ?>">
								<?php esc_html_e( 'عضویت در سایت', 'negar-shop' ); ?>
							</button>
						</div>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>

				</div>
			<?php endif; ?>

		</div>

		<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

	</div>
</div>
