<?php
/**
 * Login & Registration Auth Form Template - 1:1 Match to Demo 43 (auth__page__twelve)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enable_registration = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="auth__body bg__four py-4">
	<div class="auth__page">
		<div class="auth__page__wrapper">
			<div class="auth__page__twelve auth__mx__auto loadlogin">

				<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

				<div class="auth__page__twelve__wrapper auth__relative">
					<!-- Tabs -->
					<ul class="auth__page__twelve__tabs auth__flex auth__items__center auth__content__center list-unstyled p-0 mb-4">
						<li class="active auth__ease__in__out" data-auth-toggle="tab" data-auth-target="login">
							ورود به حساب
						</li>
						<?php if ( $enable_registration ) : ?>
							<li class="auth__ease__in__out" data-auth-toggle="tab" data-auth-target="register">
								ثبت‌نام جدید
							</li>
						<?php endif; ?>
					</ul>

					<!-- Content -->
					<div class="auth__page__twelve__content">
						<div class="auth__tab__content">

							<!-- Login Tab Content -->
							<div id="login" class="active auth__tab__pane">
								<img src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/user-avatar.svg' ); ?>" alt="کاربر" width="105" height="105" class="auth__block auth__mx__auto mb-4">
								
								<form class="woocommerce-form woocommerce-form-login login" method="post">
									<?php do_action( 'woocommerce_login_form_start' ); ?>

									<div class="auth__page__form__group position-relative mb-3">
										<input type="text" class="auth__page__form__control auth__ease__in__out w-100" name="username" id="username" autocomplete="username" placeholder="نام کاربری یا ایمیل ..." value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
									</div>

									<div class="auth__page__form__group position-relative mb-3">
										<input type="password" class="auth__page__form__control auth__ease__in__out w-100" name="password" id="password" autocomplete="current-password" placeholder="کلمه عبور ..." required>
									</div>

									<div class="d-flex align-items-center justify-content-between mb-4">
										<label class="woocommerce-form__label woocommerce-form-login__rememberme d-flex align-items-center gap-2 m-0 fs-7 text-muted cursor-pointer">
											<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
											<span>مرا به خاطر بسپار</span>
										</label>
										<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="link fs-7 text-muted text-decoration-none">
											فراموشی رمز؟
										</a>
									</div>

									<?php do_action( 'woocommerce_login_form' ); ?>
									<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

									<button type="submit" class="auth__page__btn auth__page__btn__success auth__w__fit auth__block w-100 border-0 fw-bold py-2 rounded-3 text-white" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>" style="background-color: var(--kaveh-primary-color, #e90544);">
										ورود به حساب کاربری
									</button>

									<?php do_action( 'woocommerce_login_form_end' ); ?>
								</form>
							</div>

							<?php if ( $enable_registration ) : ?>
								<!-- Register Tab Content -->
								<div id="register" class="auth__tab__pane">
									<img src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/user-avatar.svg' ); ?>" alt="کاربر" width="105" height="105" class="auth__block auth__mx__auto mb-4">

									<form class="woocommerce-form woocommerce-form-register register" method="post" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
										<?php do_action( 'woocommerce_register_form_start' ); ?>

										<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
											<div class="auth__page__form__group position-relative mb-3">
												<input type="text" class="auth__page__form__control auth__ease__in__out w-100" name="username" id="reg_username" autocomplete="username" placeholder="نام کاربری ..." value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required>
											</div>
										<?php endif; ?>

										<div class="auth__page__form__group position-relative mb-3">
											<input type="email" class="auth__page__form__control auth__ease__in__out w-100" name="email" id="reg_email" autocomplete="email" placeholder="آدرس ایمیل ..." value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required>
										</div>

										<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
											<div class="auth__page__form__group position-relative mb-3">
												<input type="password" class="auth__page__form__control auth__ease__in__out w-100" name="password" id="reg_password" autocomplete="new-password" placeholder="یک رمز عبور قوی ..." required>
											</div>
										<?php else : ?>
											<p class="fs-7 text-muted mb-3">
												رمز عبور به نشانی ایمیل شما ارسال خواهد شد.
											</p>
										<?php endif; ?>

										<?php do_action( 'woocommerce_register_form' ); ?>
										<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

										<button type="submit" class="auth__page__btn auth__page__btn__success auth__w__fit auth__block w-100 border-0 fw-bold py-2 rounded-3 text-white" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" style="background-color: var(--kaveh-primary-color, #e90544);">
											ایجاد حساب کاربری
										</button>

										<?php do_action( 'woocommerce_register_form_end' ); ?>
									</form>
								</div>
							<?php endif; ?>

						</div>
					</div>
				</div>

				<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var tabBtns = document.querySelectorAll('[data-auth-toggle="tab"]');
	tabBtns.forEach(function(btn) {
		btn.addEventListener('click', function() {
			var target = this.getAttribute('data-auth-target');
			tabBtns.forEach(function(b) { b.classList.remove('active'); });
			this.classList.add('active');

			document.querySelectorAll('.auth__tab__pane').forEach(function(pane) {
				pane.classList.remove('active');
			});
			var targetPane = document.getElementById(target);
			if (targetPane) {
				targetPane.classList.add('active');
			}
		});
	});
});
</script>
