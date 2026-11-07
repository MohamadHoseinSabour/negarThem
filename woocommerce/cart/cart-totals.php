<?php
/**
 * Cart Totals Component - Exact Match to Demo 43 (cart-nk__left)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="cart-nk__left cart_totals bg-white p-4 rounded-4 shadow-sm border border-light-subtle <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h3 class="fs-6 fw-bold f-payda text-dark pb-3 mb-3 border-bottom">
		خلاصه فاکتور
	</h3>

	<div class="d-flex flex-column gap-2 mb-3">
		<!-- Subtotal -->
		<div class="d-flex align-items-center justify-content-between text-muted f-payda fs-7">
			<span>قیمت کالاها</span>
			<span class="text-dark fw-bold"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<!-- Coupons -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="d-flex align-items-center justify-content-between text-danger f-payda fs-7">
				<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span class="fw-bold"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<!-- Fees -->
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="d-flex align-items-center justify-content-between text-muted f-payda fs-7">
				<span><?php echo esc_html( $fee->name ); ?></span>
				<span class="text-dark fw-bold"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- Shipping -->
	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<div class="pt-3 border-top mb-3">
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

	<!-- Order Total -->
	<div class="cart-nk__left__total-price d-flex align-items-center justify-content-between pt-3 border-top mt-2 f-payda">
		<span class="fw-bold fs-6 text-dark">مبلغ قابل پرداخت</span>
		<span class="price fw-black fs-5 text-dark"><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	<!-- Proceed to checkout button -->
	<div class="wc-proceed-to-checkout mt-4">
		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-nk__left__btn checkout-button btn w-100 rounded-pill py-2.5 f-payda fw-bold text-white text-center d-block" style="background-color: var(--kaveh-primary-color, #e90544); box-shadow: 0 4px 14px rgba(233, 5, 68, 0.25);">
			ادامه جهت تسویه‌حساب
		</a>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>
