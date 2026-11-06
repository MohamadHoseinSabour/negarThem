<?php
/**
 * Cart Totals Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?> bg-surface border border-border rounded-2xl p-6 shadow-sm">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h3 class="text-base font-bold text-dark pb-3 mb-4 border-b border-border flex items-center justify-between">
		<span><?php esc_html_e( 'خلاصه فاکتور سفارش', 'negar-shop' ); ?></span>
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
	</h3>

	<table class="shop_table shop_table_responsive w-full text-xs sm:text-sm text-right mb-6" cellspacing="0">

		<!-- Subtotal -->
		<tr class="cart-subtotal border-b border-border/60 py-3">
			<th class="py-3 text-muted font-medium"><?php esc_html_e( 'قیمت کل کالاها', 'negar-shop' ); ?></th>
			<td class="py-3 text-dark font-bold text-left" data-title="<?php esc_attr_e( 'قیمت کل کالاها', 'negar-shop' ); ?>">
				<?php wc_cart_totals_subtotal_html(); ?>
			</td>
		</tr>

		<!-- Coupons -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-b border-border/60 text-danger">
				<th class="py-3 font-medium">
					<?php wc_cart_totals_coupon_label( $coupon ); ?>
				</th>
				<td class="py-3 font-bold text-left" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>">
					<?php wc_cart_totals_coupon_html( $coupon ); ?>
				</td>
			</tr>
		<?php endforeach; ?>

		<!-- Shipping -->
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
			<tr class="shipping border-b border-border/60">
				<th class="py-3 text-muted font-medium"><?php esc_html_e( 'هزینه ارسال', 'negar-shop' ); ?></th>
				<td class="py-3 text-left" data-title="<?php esc_attr_e( 'هزینه ارسال', 'negar-shop' ); ?>">
					<?php woocommerce_shipping_calculator(); ?>
				</td>
			</tr>
		<?php endif; ?>

		<!-- Fees -->
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee border-b border-border/60">
				<th class="py-3 text-muted font-medium"><?php echo esc_html( $fee->name ); ?></th>
				<td class="py-3 text-dark font-bold text-left" data-title="<?php echo esc_attr( $fee->name ); ?>">
					<?php wc_cart_totals_fee_html( $fee ); ?>
				</td>
			</tr>
		<?php endforeach; ?>

		<!-- Taxes -->
		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(تخمینی برای %s)', 'negar-shop' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
					?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-b border-border/60">
						<th class="py-3 text-muted font-medium"><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td class="py-3 text-dark font-bold text-left" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr class="tax-total border-b border-border/60">
					<th class="py-3 text-muted font-medium"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td class="py-3 text-dark font-bold text-left" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<!-- Total Payable -->
		<tr class="order-total pt-4 text-sm sm:text-base">
			<th class="py-4 text-dark font-black"><?php esc_html_e( 'مبلغ قابل پرداخت:', 'negar-shop' ); ?></th>
			<td class="py-4 text-primary font-black text-left text-base sm:text-lg" data-title="<?php esc_attr_e( 'مبلغ قابل پرداخت', 'negar-shop' ); ?>">
				<?php wc_cart_totals_order_total_html(); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<!-- Proceed to Checkout Action -->
	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
