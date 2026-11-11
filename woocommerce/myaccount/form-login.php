<?php
/**
 * Password-free account entry point.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
?>

<section class="negar-account-auth" aria-labelledby="negar-account-auth-title">
	<div class="negar-account-auth__card">
		<span class="negar-account-auth__eyebrow"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></span>
		<h1 id="negar-account-auth-title"><?php esc_html_e( 'ورود با شمارهٔ موبایل', 'negar-shop' ); ?></h1>
		<p><?php esc_html_e( 'برای مشاهدهٔ سفارش‌ها و مدیریت اطلاعات حساب، شمارهٔ موبایل خود را تأیید کنید.', 'negar-shop' ); ?></p>
		<?php
		get_template_part(
			'template-parts/auth/otp-form',
			null,
			array(
				'instance'    => 'account',
				'redirect_to' => $account_url,
			)
		);
		?>
	</div>
</section>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
