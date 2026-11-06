<?php
/**
 * Cart Page Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="negar-cart-wrapper py-6">
	<div class="container mx-auto px-4">

		<div class="section-title mb-6 pb-3 border-b border-border">
			<h1 class="text-xl sm:text-2xl font-black text-dark flex items-center gap-2">
				<span class="w-2.5 h-6 bg-primary rounded-full inline-block"></span>
				<span><?php esc_html_e( 'سبد خرید شما', 'negar-shop' ); ?></span>
			</h1>
		</div>

		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

				<!-- Cart Items Column (Right in RTL, 8 cols) -->
				<div class="lg:col-span-8 w-full bg-surface border border-border rounded-2xl p-4 sm:p-6 shadow-sm">
					
					<div class="overflow-x-auto">
						<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents w-full text-right text-xs sm:text-sm" cellspacing="0">
							<thead>
								<tr class="border-b border-border text-muted font-bold pb-3">
									<th class="product-name py-3 pr-2" colspan="2"><?php esc_html_e( 'محصول', 'negar-shop' ); ?></th>
									<th class="product-price py-3"><?php esc_html_e( 'قیمت واحد', 'negar-shop' ); ?></th>
									<th class="product-quantity py-3"><?php esc_html_e( 'تعداد', 'negar-shop' ); ?></th>
									<th class="product-subtotal py-3"><?php esc_html_e( 'قیمت کل', 'negar-shop' ); ?></th>
									<th class="product-remove py-3 text-center"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-border/60">
								<?php do_action( 'woocommerce_before_cart_contents' ); ?>

								<?php
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

									if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
										$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
										?>
										<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> hover:bg-slate-50/50 transition-colors">

											<!-- Thumbnail -->
											<td class="product-thumbnail py-4 w-16 sm:w-20">
												<div class="w-16 h-16 rounded-xl overflow-hidden border border-border bg-white p-1">
													<?php
													$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
													if ( ! $product_permalink ) {
														echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													} else {
														printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													}
													?>
												</div>
											</td>

											<!-- Product Name & Meta -->
											<td class="product-name py-4 pr-3">
												<div class="font-bold text-dark hover:text-primary transition-colors">
													<?php
													if ( ! $product_permalink ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
													} else {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
													}

													do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

													// Meta data.
													echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

													// Backorder notification.
													if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification text-xs text-amber-600 mt-1">' . esc_html__( 'موجود در پیش‌خرید', 'negar-shop' ) . '</p>', $product_id ) );
													}
													?>
												</div>
											</td>

											<!-- Unit Price -->
											<td class="product-price py-4 font-medium text-dark" data-title="<?php esc_attr_e( 'قیمت واحد', 'negar-shop' ); ?>">
												<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											</td>

											<!-- Quantity Stepper -->
											<td class="product-quantity py-4" data-title="<?php esc_attr_e( 'تعداد', 'negar-shop' ); ?>">
												<div class="negar-cart-qty-box inline-flex items-center">
													<?php
													if ( $_product->is_sold_individually() ) {
														$min_quantity = 1;
														$max_quantity = 1;
													} else {
														$min_quantity = 0;
														$max_quantity = $_product->get_max_purchase_quantity();
													}

													$product_quantity = woocommerce_quantity_input(
														array(
															'input_name'   => "cart[{$cart_item_key}][qty]",
															'input_value'  => $cart_item['quantity'],
															'max_value'    => $max_quantity,
															'min_value'    => $min_quantity,
															'product_name' => $_product->get_name(),
														),
														$_product,
														false
													);

													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													?>
												</div>
											</td>

											<!-- Subtotal -->
											<td class="product-subtotal py-4 font-bold text-primary" data-title="<?php esc_attr_e( 'قیمت کل', 'negar-shop' ); ?>">
												<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											</td>

											<!-- Remove Action -->
											<td class="product-remove py-4 text-center">
												<?php
												echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="remove p-2 rounded-lg text-muted hover:text-danger hover:bg-danger/10 transition-colors inline-flex items-center justify-center" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></a>',
														esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
														/* translators: %s is the product name */
														esc_attr( sprintf( __( 'حذف %s از سبد خرید', 'negar-shop' ), wp_strip_all_tags( $_product->get_name() ) ) ),
														esc_attr( $product_id ),
														esc_attr( $_product->get_sku() )
													),
													$cart_item_key
												);
												?>
											</td>

										</tr>
										<?php
									}
								}
								?>

								<?php do_action( 'woocommerce_cart_contents' ); ?>

							</tbody>
						</table>
					</div>

					<!-- Cart Actions: Coupon & Update -->
					<div class="actions flex flex-wrap items-center justify-between gap-4 mt-6 pt-6 border-t border-border">
						
						<?php if ( wc_coupons_enabled() ) : ?>
							<div class="coupon flex items-center gap-2 w-full sm:w-auto">
								<label for="coupon_code" class="sr-only"><?php esc_html_e( 'کد تخفیف:', 'negar-shop' ); ?></label>
								<input type="text" name="coupon_code" class="input-text text-xs sm:text-sm p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all flex-1 sm:w-48" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'کد تخفیف دارید؟', 'negar-shop' ); ?>" />
								<button type="submit" class="button btn btn-outline btn-sm py-2.5 px-4 text-xs font-bold" name="apply_coupon" value="<?php esc_attr_e( 'اعمال کد', 'negar-shop' ); ?>"><?php esc_html_e( 'اعمال کد', 'negar-shop' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php endif; ?>

						<button type="submit" class="button btn btn-outline btn-sm py-2.5 px-4 text-xs font-bold mr-auto" name="update_cart" value="<?php esc_attr_e( 'به‌روزرسانی سبد خرید', 'negar-shop' ); ?>"><?php esc_html_e( 'به‌روزرسانی سبد', 'negar-shop' ); ?></button>

						<?php do_action( 'woocommerce_cart_actions' ); ?>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</div>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>

				</div>

				<!-- Cart Collaterals / Totals (Left in RTL, 4 cols, Sticky) -->
				<div class="cart-collaterals lg:col-span-4 w-full sticky top-24">
					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
					?>
				</div>

			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>

	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
