<?php
/**
 * Reusable phone OTP form.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'instance'    => 'default',
		'redirect_to' => home_url( '/' ),
	)
);

$instance    = sanitize_html_class( $args['instance'] );
$redirect_to = wp_validate_redirect( $args['redirect_to'], home_url( '/' ) );
$prefix      = 'negar-otp-' . $instance;
?>

<div class="negar-otp" data-negar-otp data-redirect-to="<?php echo esc_url( $redirect_to ); ?>">
	<form class="negar-otp__form" data-otp-step="phone" novalidate>
		<div class="negar-otp__field">
			<label for="<?php echo esc_attr( $prefix . '-phone' ); ?>"><?php esc_html_e( 'شمارهٔ موبایل', 'negar-shop' ); ?></label>
			<input type="tel" id="<?php echo esc_attr( $prefix . '-phone' ); ?>" data-otp-phone placeholder="۰۹۱۲۱۲۳۴۵۶۷" dir="ltr" inputmode="numeric" maxlength="11" autocomplete="tel" aria-describedby="<?php echo esc_attr( $prefix . '-phone-help' ); ?>" required>
			<p id="<?php echo esc_attr( $prefix . '-phone-help' ); ?>" class="negar-otp__hint"><?php esc_html_e( 'کد تأیید پنج‌رقمی از طریق پیامک ارسال می‌شود.', 'negar-shop' ); ?></p>
		</div>
		<p class="negar-otp__message hidden" data-otp-message="phone" role="alert"></p>
		<button type="submit" class="negar-otp__submit" data-otp-submit="phone"><span><?php esc_html_e( 'دریافت کد تأیید', 'negar-shop' ); ?></span><span class="negar-otp__spinner hidden" aria-hidden="true"></span></button>
	</form>

	<form class="negar-otp__form hidden" data-otp-step="code" novalidate>
		<div class="negar-otp__target"><span data-otp-target dir="ltr"></span><button type="button" data-otp-back><?php esc_html_e( 'ویرایش شماره', 'negar-shop' ); ?></button></div>
		<div class="negar-otp__field">
			<label for="<?php echo esc_attr( $prefix . '-code' ); ?>"><?php esc_html_e( 'کد تأیید پیامک‌شده', 'negar-shop' ); ?></label>
			<input type="text" id="<?php echo esc_attr( $prefix . '-code' ); ?>" data-otp-code placeholder="— — — — —" dir="ltr" inputmode="numeric" maxlength="5" autocomplete="one-time-code" required>
		</div>
		<div class="negar-otp__resend-row"><span data-otp-timer-wrap><?php esc_html_e( 'ارسال مجدد کد در ', 'negar-shop' ); ?><b data-otp-timer>۰۱:۰۰</b></span><button type="button" class="hidden" data-otp-resend disabled><?php esc_html_e( 'ارسال مجدد کد', 'negar-shop' ); ?></button></div>
		<p class="negar-otp__message hidden" data-otp-message="code" role="alert"></p>
		<button type="submit" class="negar-otp__submit" data-otp-submit="code"><span><?php esc_html_e( 'ورود به حساب کاربری', 'negar-shop' ); ?></span><span class="negar-otp__spinner hidden" aria-hidden="true"></span></button>
	</form>
</div>
