<?php
/**
 * Modals & Dynamic Features Ajax Handler (QuickView, Wishlist, Compare)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Quick View Ajax Request.
 */
function negar_shop_ajax_quickview() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'شناسه محصول نامعتبر است.', 'negar-shop' ) ) );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'محصول یافت نشد.', 'negar-shop' ) ) );
	}

	global $post;
	$post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $post );

	ob_start();
	?>
	<div class="negar-quickview-content grid grid-cols-1 md:grid-cols-2 gap-6 items-start text-right">
		
		<!-- Gallery / Image Column -->
		<div class="quickview-gallery relative rounded-xl overflow-hidden bg-slate-50 border border-border p-4 flex items-center justify-center min-h-[300px]">
			<?php if ( $product->is_on_sale() ) : ?>
				<div class="absolute top-3 right-3 z-10">
					<?php echo wp_kses_post( negar_shop_get_discount_badge( $product ) ); ?>
				</div>
			<?php endif; ?>
			<?php echo $product->get_image( 'large', array( 'class' => 'max-h-[280px] w-auto h-auto object-contain mx-auto' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<!-- Details Column -->
		<div class="quickview-details flex flex-col justify-between">
			<div>
				<div class="text-xs text-muted mb-1">
					<?php echo wc_get_product_category_list( $product->get_id(), '، ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<h3 class="text-lg font-bold text-dark mb-2 leading-snug">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="hover:text-primary transition-colors">
						<?php echo esc_html( $product->get_name() ); ?>
					</a>
				</h3>

				<!-- Price -->
				<div class="text-lg font-black text-primary mb-3">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</div>

				<!-- Short Description -->
				<div class="text-xs text-muted leading-relaxed line-clamp-3 mb-4">
					<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>

			<!-- Buy Box / Add to Cart -->
			<div class="negar-quickview-cart pt-4 border-t border-border">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>

			<!-- View Full Details Link -->
			<div class="mt-4 pt-3 border-t border-border text-center">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1">
					<span><?php esc_html_e( 'مشاهده صفحه کامل و مشخصات فنی کالا', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
				</a>
			</div>
		</div>

	</div>
	<?php
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_negar_shop_quickview', 'negar_shop_ajax_quickview' );
add_action( 'wp_ajax_nopriv_negar_shop_quickview', 'negar_shop_ajax_quickview' );

/**
 * Handle Wishlist Cards Retrieval via Ajax.
 */
function negar_shop_ajax_get_wishlist_cards() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$raw_ids = array();
	if ( isset( $_POST['ids'] ) ) {
		$raw_ids = is_array( $_POST['ids'] ) ? $_POST['ids'] : explode( ',', (string) $_POST['ids'] );
	} elseif ( isset( $_POST['ids[]'] ) ) {
		$raw_ids = is_array( $_POST['ids[]'] ) ? $_POST['ids[]'] : explode( ',', (string) $_POST['ids[]'] );
	}
	$ids = array_filter( array_map( 'absint', $raw_ids ) );

	if ( empty( $ids ) ) {
		ob_start();
		?>
		<div class="negar-empty-wishlist py-12 text-center">
			<div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-muted">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
			</div>
			<h4 class="text-sm font-bold text-dark mb-1"><?php esc_html_e( 'لیست علاقه‌مندی‌های شما خالی است!', 'negar-shop' ); ?></h4>
			<p class="text-xs text-muted mb-4"><?php esc_html_e( 'کالاهای مورد علاقه خود را با زدن علامت قلب به این لیست اضافه کنید.', 'negar-shop' ); ?></p>
		</div>
		<?php
		$html = ob_get_clean();
		wp_send_json_success( array( 'html' => $html, 'count' => 0 ) );
	}

	$products = wc_get_products( array( 'include' => $ids ) );
	ob_start();
	?>
	<div class="flex flex-col divide-y divide-border/60">
		<?php foreach ( $products as $prod ) : ?>
			<div class="wishlist-item flex items-center justify-between gap-3 py-3" data-product-id="<?php echo esc_attr( $prod->get_id() ); ?>">
				<div class="flex items-center gap-3 min-w-0">
					<a href="<?php echo esc_url( $prod->get_permalink() ); ?>" class="w-14 h-14 rounded-lg overflow-hidden border border-border bg-white p-1 flex-shrink-0">
						<?php echo $prod->get_image( 'thumbnail', array( 'class' => 'w-full h-full object-contain' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
					<div class="min-w-0">
						<h5 class="text-xs font-bold text-dark truncate mb-1">
							<a href="<?php echo esc_url( $prod->get_permalink() ); ?>" class="hover:text-primary transition-colors"><?php echo esc_html( $prod->get_name() ); ?></a>
						</h5>
						<span class="text-xs font-black text-primary block"><?php echo wp_kses_post( $prod->get_price_html() ); ?></span>
					</div>
				</div>
				<div class="flex items-center gap-2 flex-shrink-0">
					<a href="<?php echo esc_url( $prod->add_to_cart_url() ); ?>" class="btn btn-primary btn-sm p-2 rounded-lg text-xs" aria-label="<?php esc_attr_e( 'خرید', 'negar-shop' ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
					</a>
					<button type="button" class="negar-remove-wishlist-item text-muted hover:text-danger p-2" data-product-id="<?php echo esc_attr( $prod->get_id() ); ?>" aria-label="<?php esc_attr_e( 'حذف', 'negar-shop' ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
					</button>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html, 'count' => count( $products ) ) );
}
add_action( 'wp_ajax_negar_shop_get_wishlist_cards', 'negar_shop_ajax_get_wishlist_cards' );
add_action( 'wp_ajax_nopriv_negar_shop_get_wishlist_cards', 'negar_shop_ajax_get_wishlist_cards' );

/**
 * Handle Compare Matrix Retrieval via Ajax.
 */
function negar_shop_ajax_get_compare_data() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$raw_ids = array();
	if ( isset( $_POST['ids'] ) ) {
		$raw_ids = is_array( $_POST['ids'] ) ? $_POST['ids'] : explode( ',', (string) $_POST['ids'] );
	} elseif ( isset( $_POST['ids[]'] ) ) {
		$raw_ids = is_array( $_POST['ids[]'] ) ? $_POST['ids[]'] : explode( ',', (string) $_POST['ids[]'] );
	}
	$ids = array_slice( array_filter( array_map( 'absint', $raw_ids ) ), 0, 4 );

	if ( empty( $ids ) ) {
		ob_start();
		?>
		<div class="py-12 text-center">
			<h4 class="text-sm font-bold text-dark mb-2"><?php esc_html_e( 'هیچ کالایی برای مقایسه انتخاب نشده است!', 'negar-shop' ); ?></h4>
			<p class="text-xs text-muted"><?php esc_html_e( 'برای مقایسه، دکمه مقایسه را روی حداقل ۲ کالا بزنید.', 'negar-shop' ); ?></p>
		</div>
		<?php
		$html = ob_get_clean();
		wp_send_json_success( array( 'html' => $html, 'count' => 0 ) );
	}

	$products = wc_get_products( array( 'include' => $ids ) );
	// Collect all unique attribute names across compared products
	$all_attributes = array();
	foreach ( $products as $prod ) {
		$attrs = $prod->get_attributes();
		foreach ( $attrs as $attr_key => $attr_obj ) {
			$label = wc_attribute_label( $attr_key, $prod );
			if ( ! isset( $all_attributes[ $attr_key ] ) ) {
				$all_attributes[ $attr_key ] = $label;
			}
		}
	}

	ob_start();
	?>
	<div class="overflow-x-auto">
		<table class="w-full text-right text-xs sm:text-sm border-collapse">
			<tbody>
				<!-- Row 1: Image & Remove -->
				<tr class="border-b border-border">
					<th class="py-3 px-4 bg-slate-50 font-bold text-muted w-32"><?php esc_html_e( 'تصویر محصول', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-3 px-4 text-center">
							<div class="relative inline-block w-24 h-24 mb-2">
								<?php echo $prod->get_image( 'thumbnail', array( 'class' => 'w-full h-full object-contain' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<button type="button" class="negar-remove-compare-item absolute -top-2 -right-2 bg-danger text-white rounded-full p-1" data-product-id="<?php echo esc_attr( $prod->get_id() ); ?>" aria-label="<?php esc_attr_e( 'حذف', 'negar-shop' ); ?>">
									<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
								</button>
							</div>
							<h5 class="text-xs font-bold text-dark line-clamp-2"><?php echo esc_html( $prod->get_name() ); ?></h5>
						</td>
					<?php endforeach; ?>
				</tr>

				<!-- Row 2: Price -->
				<tr class="border-b border-border">
					<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'قیمت', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-3 px-4 text-center font-black text-primary">
							<?php echo wp_kses_post( $prod->get_price_html() ); ?>
						</td>
					<?php endforeach; ?>
				</tr>

				<!-- Row 3: Stock Status -->
				<tr class="border-b border-border">
					<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'وضعیت انبار', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-3 px-4 text-center">
							<?php echo $prod->is_in_stock() ? '<span class="text-success font-bold">' . esc_html__( 'موجود', 'negar-shop' ) . '</span>' : '<span class="text-danger font-bold">' . esc_html__( 'ناموجود', 'negar-shop' ) . '</span>'; ?>
						</td>
					<?php endforeach; ?>
				</tr>

				<!-- Row 4: Categories -->
				<tr class="border-b border-border">
					<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'دسته‌بندی', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-3 px-4 text-center text-xs">
							<?php echo wc_get_product_category_list( $prod->get_id(), '، ' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					<?php endforeach; ?>
				</tr>

				<!-- Row 5: Brand -->
				<?php if ( taxonomy_exists( 'product_brand' ) ) : ?>
					<tr class="border-b border-border">
						<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'برند', 'negar-shop' ); ?></th>
						<?php foreach ( $products as $prod ) : ?>
							<td class="py-3 px-4 text-center text-xs">
								<?php
								$brand_terms = get_the_terms( $prod->get_id(), 'product_brand' );
								echo ( ! empty( $brand_terms ) && ! is_wp_error( $brand_terms ) ) ? esc_html( $brand_terms[0]->name ) : '—';
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endif; ?>

				<!-- Row 6: SKU -->
				<tr class="border-b border-border">
					<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'کد کالا (SKU)', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-3 px-4 text-center font-mono text-muted">
							<?php echo esc_html( $prod->get_sku() ? $prod->get_sku() : '—' ); ?>
						</td>
					<?php endforeach; ?>
				</tr>

				<!-- Dynamic Attributes Rows -->
				<?php foreach ( $all_attributes as $attr_key => $attr_label ) : ?>
					<tr class="border-b border-border">
						<th class="py-3 px-4 bg-slate-50 font-bold text-muted"><?php echo esc_html( $attr_label ); ?></th>
						<?php foreach ( $products as $prod ) : ?>
							<td class="py-3 px-4 text-center text-xs">
								<?php
								$attr_val = $prod->get_attribute( $attr_key );
								echo esc_html( ! empty( $attr_val ) ? $attr_val : '—' );
								?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>

				<!-- Action Buttons -->
				<tr>
					<th class="py-4 px-4 bg-slate-50 font-bold text-muted"><?php esc_html_e( 'عملیات', 'negar-shop' ); ?></th>
					<?php foreach ( $products as $prod ) : ?>
						<td class="py-4 px-4 text-center">
							<a href="<?php echo esc_url( $prod->add_to_cart_url() ); ?>" class="btn btn-primary btn-sm py-2 px-4 text-xs font-bold inline-block">
								<?php esc_html_e( 'خرید کالا', 'negar-shop' ); ?>
							</a>
						</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html, 'count' => count( $products ) ) );
}
add_action( 'wp_ajax_negar_shop_get_compare_data', 'negar_shop_ajax_get_compare_data' );
add_action( 'wp_ajax_nopriv_negar_shop_get_compare_data', 'negar_shop_ajax_get_compare_data' );
