<?php
/**
 * Cart Page Template - Exact Match to Demo 43 (cart-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<section class="cart-nk py-4">
	<div class="container">

		<!-- Steps Indicator -->
		<div class="cart-nk__steps d-flex align-items-center justify-content-center mb-5 gap-3 gap-md-5">
			<div class="cart-nk__steps__item active d-flex align-items-center gap-2 f-payda">
				<i class="icon-bag-2 lh-1 fs-5"></i>
				<span class="fw-bold">سبد خرید</span>
			</div>
			<div class="cart-nk__steps__item d-flex align-items-center gap-2 f-payda text-muted">
				<i class="icon-card-pos lh-1 fs-5"></i>
				<span>جزئیات پرداخت</span>
			</div>
			<div class="cart-nk__steps__item d-flex align-items-center gap-2 f-payda text-muted">
				<i class="icon-tick-circle lh-1 fs-5"></i>
				<span>تکمیل سفارش</span>
			</div>
		</div>

		<div class="cart-nk__wrapper position-relative">
			<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<div class="row">
					<!-- Right Column: Cart Items (8 cols) -->
					<div class="col-12 col-lg-8 mb-4 mb-lg-0">
						<div class="cart-nk__box bg-white p-4 rounded-4 shadow-sm border border-light-subtle">
							<div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
								<h2 class="fs-6 fw-bold f-payda text-dark m-0">
									اقلام سبد خرید (<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?> کالا)
								</h2>
								<a href="<?php echo esc_url( wc_get_cart_url() . '?empty-cart=yes' ); ?>" class="text-muted text-decoration-none fs-7 f-payda d-none">
									حذف همه
								</a>
							</div>

							<ul class="cart-nk__items list-unstyled p-0 m-0 d-flex flex-column gap-3">
								<?php do_action( 'woocommerce_before_cart_contents' ); ?>

								<?php
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

									if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
										$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
										$qty_input_id      = 'cart-qty-' . esc_attr( $cart_item_key );
										?>
										<li class="woocommerce-cart-form__cart-item p-3 rounded-3 border border-light-subtle d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 bg-light-subtle">
											
											<!-- Product Image & Title -->
											<div class="info d-flex align-items-center gap-3">
												<div class="image flex-shrink-0 bg-white p-1 rounded-3 border border-light-subtle" style="width: 72px; height: 72px;">
													<?php
													$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail', array( 'class' => 'w-100 h-100 object-fit-contain' ) ), $cart_item, $cart_item_key );
													if ( ! $product_permalink ) {
														echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													} else {
														printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													}
													?>
												</div>
												<div class="d-flex flex-column">
													<h3 class="title fs-6 fw-bold f-payda m-0">
														<?php
														if ( ! $product_permalink ) {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
														} else {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="text-dark text-decoration-none hover-primary">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
														}
														?>
													</h3>
													<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												</div>
											</div>

											<!-- Quantity Stepper & Price -->
											<div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-4">
												
												<!-- Stepper -->
												<div class="d-flex align-items-center border border-light-subtle rounded-pill bg-white px-2 py-1">
													<button type="button" class="btn btn-sm p-0 text-muted lh-1" data-kaveh-qty data-type="increment" data-target="#<?php echo esc_attr( $qty_input_id ); ?>" aria-label="افزودن">
														<i class="icon-plus fs-7"></i>
													</button>
													
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
															'classes'      => array( 'input-text', 'qty', 'text', 'border-0', 'text-center', 'fw-bold', 'f-payda', 'p-0', 'bg-transparent' ),
															'input_id'     => $qty_input_id,
														),
														$_product,
														false
													);

													echo $product_quantity; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													?>

													<button type="button" class="btn btn-sm p-0 text-muted lh-1" data-kaveh-qty data-type="decrement" data-target="#<?php echo esc_attr( $qty_input_id ); ?>" aria-label="کاهش">
														<i class="icon-minus fs-7"></i>
													</button>
												</div>

												<!-- Subtotal & Remove -->
												<div class="d-flex align-items-center gap-3">
													<div class="price fw-bold text-dark fs-6 f-payda">
														<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>

													<?php
													echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														'woocommerce_cart_item_remove_link',
														sprintf(
															'<a href="%s" class="remove btn btn-sm p-1 text-danger rounded-circle hover-bg-light" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="icon-trash-2 fs-6"></i></a>',
															esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
															/* translators: %s is the product name */
															esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $_product->get_name() ) ) ),
															esc_attr( $product_id ),
															esc_attr( $_product->get_sku() )
														),
														$cart_item_key
													);
													?>
												</div>
											</div>

										</li>
										<?php
									}
								}
								?>

								<?php do_action( 'woocommerce_cart_contents' ); ?>
							</ul>

							<!-- Coupon & Update Cart -->
							<div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4 pt-3 border-top">
								<?php if ( wc_coupons_enabled() ) { ?>
									<div class="coupon d-flex align-items-center gap-2">
										<input type="text" name="coupon_code" class="form-control rounded-pill px-3 py-1 fs-7 f-payda" id="coupon_code" value="" placeholder="کد تخفیف" style="max-width: 180px;">
										<button type="submit" class="btn btn-outline-dark rounded-pill px-3 py-1 fs-7 f-payda" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">
											اعمال کد
										</button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								<?php } ?>

								<button type="submit" class="btn btn-outline-secondary rounded-pill px-4 py-1 fs-7 f-payda" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
									به‌روزرسانی سبد خرید
								</button>

								<?php do_action( 'woocommerce_cart_actions' ); ?>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div>

							<?php do_action( 'woocommerce_after_cart_contents' ); ?>
						</div>
					</div>

					<!-- Left Column: Order Summary (4 cols) -->
					<div class="col-12 col-lg-4">
						<?php woocommerce_cart_totals(); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</form>
		</div>

	</div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
