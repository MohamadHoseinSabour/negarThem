<?php
/**
 * Checkout Form Template - Exact Match to Demo 43 (cart-nk cart-nk-checkout)
 *
 * @package Negar_Shop
 * @version 2.3.0
 */

defined( 'ABSPATH' ) || exit;

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'برای تسویه‌حساب باید وارد حساب کاربری خود شوید.', 'negar-shop' ) ) );
	return;
}
?>

<section class="cart-nk cart-nk-checkout py-3 py-md-4">
	<div class="container">

		<!-- Steps Indicator: Step 2 Active -->
		<div class="cart-nk__steps d-flex align-items-center justify-content-between justify-content-md-center mb-4 mb-md-5">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-nk__steps__item completed d-flex align-items-center gap-2 f-payda text-decoration-none">
				<span class="step-badge"><i class="icon-check-3 lh-1"></i></span>
				<i class="icon-shopping-cart-5 lh-1 fs-5"></i>
				<span><?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?></span>
			</a>
			<div class="cart-step-line completed"></div>
			<div class="cart-nk__steps__item active d-flex align-items-center gap-2 f-payda">
				<span class="step-badge">۲</span>
				<i class="icon-pin lh-1 fs-5"></i>
				<span class="fw-bold"><?php esc_html_e( 'اطلاعات ارسال و پرداخت', 'negar-shop' ); ?></span>
			</div>
			<div class="cart-step-line"></div>
			<div class="cart-nk__steps__item d-flex align-items-center gap-2 f-payda">
				<span class="step-badge">۳</span>
				<i class="icon-tick-circle lh-1 fs-5"></i>
				<span><?php esc_html_e( 'تکمیل سفارش', 'negar-shop' ); ?></span>
			</div>
		</div>

		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

		<!-- Coupon Notice Slot -->
		<div class="cart-nk-coupon-notices mb-4" style="display: none;"></div>

		<form name="checkout" method="post" class="checkout woocommerce-checkout cart-nk__form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<div class="row">

				<!-- Right Column: Customer Details (8 cols) -->
				<?php if ( $checkout->get_checkout_fields() ) : ?>
					<div class="col-12 col-lg-8 mb-4 mb-lg-0" id="customer_details">
						
						<!-- Billing Card -->
						<div class="cart-nk__box bg-white p-3 p-md-4 rounded-4 shadow-sm border border-light-subtle mb-4">
							<div class="cart-nk__box__heading fs-6 fw-bold f-payda mb-4 pb-2 border-bottom text-dark d-flex align-items-center gap-2">
								<i class="icon-location-2 fs-5 text-primary"></i>
								<span>اطلاعات گیرنده و آدرس تحویل</span>
							</div>

							<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
							<div class="woocommerce-billing-fields">
								<?php do_action( 'woocommerce_checkout_billing' ); ?>
							</div>
						</div>

						<!-- Shipping Card -->
						<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>
							<div class="cart-nk__box bg-white p-3 p-md-4 rounded-4 shadow-sm border border-light-subtle">
								<div class="woocommerce-shipping-fields">
									<?php do_action( 'woocommerce_checkout_shipping' ); ?>
								</div>
								<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
							</div>
						<?php else : ?>
							<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<!-- Left Column: Order Review & Payments (4 cols, Sticky) -->
				<div class="col-12 col-lg-4">
					<div class="cart-nk__box bg-white p-3 p-md-4 rounded-4 shadow-sm border border-light-subtle sticky-top" style="top: 96px;">
						
						<div class="cart-nk__box__heading fs-6 fw-bold f-payda mb-3 pb-2 border-bottom text-dark d-flex align-items-center justify-content-between">
							<span>سفارش شما</span>
							<i class="icon-bag-2 fs-5 text-primary"></i>
						</div>

						<!-- Coupon Box in Checkout -->
						<?php if ( wc_coupons_enabled() ) : ?>
							<div class="cart-nk__offer-code mb-4 p-3 rounded-3 bg-light-subtle border border-light-subtle">
								<div class="d-flex align-items-center gap-2">
									<input type="text" id="kaveh-nk-coupon-input" class="form-control rounded-pill px-3 py-1 fs-7 f-payda" placeholder="کد تخفیف دارید؟">
									<button type="button" id="kaveh-nk-apply-coupon" class="btn btn-dark rounded-pill px-3 py-1 fs-7 f-payda flex-shrink-0">
										اعمال
									</button>
								</div>
							</div>
						<?php endif; ?>

						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

						<div id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

					</div>
				</div>

			</div>
		</form>

	</div>
</section>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
