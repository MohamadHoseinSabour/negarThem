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

$is_vip_checkout   = class_exists( 'Negar_Club_VIP_Products' ) && Negar_Club_VIP_Products::is_vip_cart();
$vip_customer_data = $is_vip_checkout ? Negar_Club_VIP_Products::get_customer_vip_checkout_data() : array( 'is_complete' => false );
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
				<i class="<?php echo $is_vip_checkout ? 'icon-user' : 'icon-pin'; ?> lh-1 fs-5"></i>
				<span class="fw-bold"><?php echo $is_vip_checkout ? esc_html__( 'اطلاعات خریدار و پرداخت', 'negar-shop' ) : esc_html__( 'اطلاعات ارسال و پرداخت', 'negar-shop' ); ?></span>
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
								<i class="<?php echo $is_vip_checkout ? 'icon-user' : 'icon-location-2'; ?> fs-5 text-primary"></i>
								<span><?php echo $is_vip_checkout ? esc_html__( 'اطلاعات خریدار اشتراک VIP', 'negar-shop' ) : esc_html__( 'اطلاعات گیرنده و آدرس تحویل', 'negar-shop' ); ?></span>
							</div>

							<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

							<?php if ( $is_vip_checkout && ! empty( $vip_customer_data['is_complete'] ) ) : ?>
								<!-- Compact VIP Customer Profile Summary Card -->
								<div class="vip-checkout-summary-card p-3 p-md-4 rounded-3 border mb-3" style="background: #f8fafc; border-color: #e2e8f0;">
									<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
										<div class="d-flex align-items-center gap-2">
											<span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-7 f-payda">
												<i class="icon-check-3 me-1"></i> <?php esc_html_e( 'اطلاعات حساب کاربری تایید شده', 'negar-shop' ); ?>
											</span>
										</div>
										<span class="text-muted fs-8 f-payda">
											<?php esc_html_e( 'اشتراک VIP به این مشخصات اختصاص می‌یابد', 'negar-shop' ); ?>
										</span>
									</div>
									<div class="row g-3">
										<div class="col-12 col-sm-6">
											<div class="text-muted fs-8 mb-1 f-payda"><?php esc_html_e( 'نام و نام خانوادگی خریدار', 'negar-shop' ); ?></div>
											<div class="fw-bold text-dark fs-6 f-payda">
												<?php echo esc_html( $vip_customer_data['first_name'] . ' ' . $vip_customer_data['last_name'] ); ?>
											</div>
										</div>
										<div class="col-12 col-sm-6">
											<div class="text-muted fs-8 mb-1 f-payda"><?php esc_html_e( 'شماره همراه تاییدشده', 'negar-shop' ); ?></div>
											<div class="fw-bold text-dark fs-6 f-payda" dir="ltr" style="text-align: right;">
												<?php echo esc_html( $vip_customer_data['phone'] ); ?>
											</div>
										</div>
									</div>
								</div>

								<!-- Hidden inputs to submit known data through standard WooCommerce checkout POST -->
								<input type="hidden" name="billing_first_name" value="<?php echo esc_attr( $vip_customer_data['first_name'] ); ?>" />
								<input type="hidden" name="billing_last_name" value="<?php echo esc_attr( $vip_customer_data['last_name'] ); ?>" />
								<input type="hidden" name="billing_phone" value="<?php echo esc_attr( $vip_customer_data['phone'] ); ?>" />

							<?php else : ?>

								<?php if ( $is_vip_checkout && ( ! empty( $vip_customer_data['has_phone'] ) || ! empty( $vip_customer_data['has_first_name'] ) ) ) : ?>
									<div class="alert alert-info py-2 px-3 mb-3 fs-7 f-payda rounded-3 d-flex align-items-center gap-2">
										<i class="icon-info-circle fs-5 flex-shrink-0"></i>
										<span><?php esc_html_e( 'جهت فعال‌سازی عضویت VIP، لطفاً اطلاعات باقیمانده را وارد فرمایید.', 'negar-shop' ); ?></span>
									</div>
								<?php endif; ?>

								<div class="woocommerce-billing-fields">
									<?php do_action( 'woocommerce_checkout_billing' ); ?>
								</div>

							<?php endif; ?>
						</div>

						<!-- Shipping Card (Only for non-VIP when shipping is needed) -->
						<?php if ( ! $is_vip_checkout && WC()->cart->needs_shipping_address() === true ) : ?>
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
