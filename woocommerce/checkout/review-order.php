<?php
/**
 * Review Order Table Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<table class="shop_table woocommerce-checkout-review-order-table w-full text-xs sm:text-sm text-right mb-6" cellspacing="0">
	<thead>
		<tr class="border-b border-border text-muted font-bold">
			<th class="product-name py-3 pr-1"><?php esc_html_e( 'محصول', 'negar-shop' ); ?></th>
			<th class="product-total py-3 text-left pl-1"><?php esc_html_e( 'قیمت جزء', 'negar-shop' ); ?></th>
		</tr>
	</thead>
	<tbody class="divide-y divide-border/60">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> hover:bg-slate-50/50">
					<td class="product-name py-3 pr-1">
						<span class="font-bold text-dark"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></span>
						<span class="text-xs text-muted mr-1 font-mono">&times; <?php echo esc_html( $cart_item['quantity'] ); ?></span>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="product-total py-3 text-left pl-1 font-medium text-dark">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal border-t border-border/80">
			<th class="py-3 text-muted font-medium pr-1"><?php esc_html_e( 'قیمت کل کالاها', 'negar-shop' ); ?></th>
			<td class="py-3 text-dark font-bold text-left pl-1"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> text-danger">
				<th class="py-3 font-medium pr-1"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td class="py-3 font-bold text-left pl-1"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th class="py-3 text-muted font-medium pr-1"><?php echo esc_html( $fee->name ); ?></th>
				<td class="py-3 text-dark font-bold text-left pl-1"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th class="py-3 text-muted font-medium pr-1"><?php echo esc_html( $tax->label ); ?></th>
						<td class="py-3 text-dark font-bold text-left pl-1"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th class="py-3 text-muted font-medium pr-1"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td class="py-3 text-dark font-bold text-left pl-1"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total border-t border-border pt-4 text-sm sm:text-base">
			<th class="py-4 text-dark font-black pr-1"><?php esc_html_e( 'مبلغ قابل پرداخت:', 'negar-shop' ); ?></th>
			<td class="py-4 text-rose-600 font-black text-left pl-1 text-base sm:text-lg"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
