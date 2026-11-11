<?php
/**
 * My Account Wrapper Template - Exact Match to Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="account-page-nk">
	<div class="container">
		<header class="account-page-nk__header">
			<div>
				<span><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></span>
				<h1><?php esc_html_e( 'مدیریت حساب و سفارش‌ها', 'negar-shop' ); ?></h1>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'ادامهٔ خرید', 'negar-shop' ); ?></a>
		</header>

		<div class="account-page-nk__layout">
			<aside class="account-page-nk__sidebar">
				<?php do_action( 'woocommerce_account_navigation' ); ?>
			</aside>

			<div class="account-page-nk__content">
				<div class="woocommerce-MyAccount-content">
					<?php do_action( 'woocommerce_account_content' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
