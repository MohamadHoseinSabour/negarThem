<?php
/**
 * Empty Cart Page Template - Exact Match to Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="cart-nk">
	<div class="container">
		<div class="cart-nk__wrapper position-relative">
			<div class="cart-nk-empty d-flex flex-column align-items-center justify-content-center text-center py-5">
				<img decoding="async" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/empty-cart.png' ); ?>" alt="سبد خرید خالی" width="150" height="150" class="d-block mx-auto mb-4">
				<div class="text f-payda fw-semibold mb-4" style="font-size: 18px; color: #1e293b;">سبد خرید شما خالی است</div>
				<a class="cart-nk__left__btn btn d-flex align-items-center justify-content-center f-payda fw-bold return-to-shop" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="background-color: var(--kaveh-primary-color, #e90544); color: #ffffff; border-radius: 999px; padding: 12px 36px; text-decoration: none; font-size: 14px;">
					بازگشت به فروشگاه
				</a>
			</div>
		</div>
	</div>
</section>
