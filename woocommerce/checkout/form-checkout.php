<?php
/**
 * Checkout Form Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'برای تسویه‌حساب باید وارد حساب کاربری خود شوید.', 'negar-shop' ) ) );
	return;
}
?>

<div class="negar-checkout-wrapper py-6">
	<div class="container mx-auto px-4">

		<div class="section-title mb-6 pb-3 border-b border-border">
			<h1 class="text-xl sm:text-2xl font-black text-dark flex items-center gap-2">
				<span class="w-2.5 h-6 bg-primary rounded-full inline-block"></span>
				<span><?php esc_html_e( 'تسویه‌حساب و ثبت نهایی سفارش', 'negar-shop' ); ?></span>
			</h1>
		</div>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

				<!-- Customer Billing & Shipping Column (Right in RTL, 7 cols) -->
				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="lg:col-span-7 w-full flex flex-col gap-6" id="customer_details">
						
						<!-- Billing Fields Card -->
						<div class="bg-surface border border-border rounded-2xl p-6 shadow-sm">
							<h3 class="text-base font-bold text-dark pb-3 mb-4 border-b border-border flex items-center gap-2">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
								<span><?php esc_html_e( 'اطلاعات گیرنده و نشانی تحویل', 'negar-shop' ); ?></span>
							</h3>
							<div class="woocommerce-billing-fields">
								<?php do_action( 'woocommerce_checkout_billing' ); ?>
							</div>
						</div>

						<!-- Shipping Fields Card -->
						<div class="bg-surface border border-border rounded-2xl p-6 shadow-sm">
							<div class="woocommerce-shipping-fields">
								<?php do_action( 'woocommerce_checkout_shipping' ); ?>
							</div>
						</div>

					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>

				<!-- Order Review & Payments Column (Left in RTL, 5 cols, Sticky) -->
				<div class="lg:col-span-5 w-full sticky top-24">
					
					<div class="bg-surface border border-border rounded-2xl p-6 shadow-sm">
						
						<h3 id="order_review_heading" class="text-base font-bold text-dark pb-3 mb-4 border-b border-border flex items-center gap-2">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
							<span><?php esc_html_e( 'خلاصه سفارش و انتخاب درگاه', 'negar-shop' ); ?></span>
						</h3>

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
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
