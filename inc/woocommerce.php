<?php
/**
 * WooCommerce Compatibility and Hooks
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce theme support.
 */
function negar_shop_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 4,
				'min_rows'        => 2,
				'max_rows'        => 8,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 6,
			),
		)
	);

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'negar_shop_woocommerce_setup' );

/**
 * Remove default WooCommerce wrappers.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Custom WooCommerce opening wrapper.
 */
function negar_shop_woocommerce_wrapper_before() {
	?>
	<main id="primary" class="site-main woocommerce-main-wrapper py-6">
		<div class="container">
	<?php
}
add_action( 'woocommerce_before_main_content', 'negar_shop_woocommerce_wrapper_before', 10 );

/**
 * Custom WooCommerce closing wrapper.
 */
function negar_shop_woocommerce_wrapper_after() {
	?>
		</div>
	</main>
	<?php
}
add_action( 'woocommerce_after_main_content', 'negar_shop_woocommerce_wrapper_after', 10 );

/**
 * Update WooCommerce Cart Fragments via Ajax.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function negar_shop_cart_fragments( $fragments ) {
	$count    = ( WC()->cart && is_object( WC()->cart ) ) ? WC()->cart->get_cart_contents_count() : 0;
	$subtotal = ( WC()->cart && is_object( WC()->cart ) ) ? WC()->cart->get_cart_subtotal() : '';

	// 1. Desktop Main Header Cart Count Badge
	$fragments['span.negar-cart-count-header'] = '<span class="negar-cart-count-header absolute -top-1 -right-1 bg-white text-rose-600 border border-rose-600 rounded-full text-[10px] w-4.5 h-4.5 flex items-center justify-center font-black leading-none">' . esc_html( $count ) . '</span>';

	// 2. Mobile Header Cart Count Badge
	$fragments['span.negar-cart-count-mobile'] = '<span class="negar-cart-count-mobile negar-cart-count badge badge-discount absolute -top-1.5 -right-1.5 text-2xs px-1.5 py-0.5">' . esc_html( $count ) . '</span>';

	// 3. Mini Cart Drawer Header Count
	$fragments['span.negar-cart-count-drawer'] = '<span class="negar-cart-count-drawer badge badge-discount text-xs">' . esc_html( $count ) . '</span>';

	// 4. Cart Subtotal Price
	$fragments['span.negar-cart-total-price'] = '<span class="negar-cart-total-price">' . wp_kses_post( $subtotal ) . '</span>';

	// 5. Mini-Cart Drawer Body Content
	ob_start();
	if ( function_exists( 'woocommerce_mini_cart' ) ) {
		woocommerce_mini_cart();
	}
	$fragments['div.widget_shopping_cart_content'] = '<div class="drawer-body flex-1 overflow-y-auto p-5 widget_shopping_cart_content">' . ob_get_clean() . '</div>';

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'negar_shop_cart_fragments' );

/**
 * Keep a small first-party history of genuinely viewed products for the home page.
 *
 * @return void
 */
function negar_shop_track_recent_product_views() {
	if ( ! function_exists( 'is_product' ) || ! is_product() || headers_sent() ) {
		return;
	}

	$product_id = get_queried_object_id();
	if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
		return;
	}

	$cookie_name = 'negar_recent_products';
	$viewed_ids  = isset( $_COOKIE[ $cookie_name ] ) ? array_map( 'absint', explode( ',', wp_unslash( $_COOKIE[ $cookie_name ] ) ) ) : array();
	$viewed_ids  = array_values( array_unique( array_filter( $viewed_ids ) ) );
	$viewed_ids  = array_values( array_diff( $viewed_ids, array( $product_id ) ) );
	array_unshift( $viewed_ids, $product_id );
	$viewed_ids = array_slice( $viewed_ids, 0, 8 );
	$cookie     = implode( ',', $viewed_ids );

	setcookie( $cookie_name, $cookie, time() + MONTH_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
	$_COOKIE[ $cookie_name ] = $cookie;
}
add_action( 'template_redirect', 'negar_shop_track_recent_product_views' );

/**
 * Sitewide AJAX Add to Cart Handler (kaveh_ajax_add_to_cart)
 */
function negar_shop_ajax_add_to_cart() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'ووکامرس فعال نیست.', 'negar-shop' ) ) );
	}

	$product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$quantity     = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
	$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
	$variations   = array();

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'شناسه محصول نامعتبر است.', 'negar-shop' ) ) );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product || ! $product->is_purchasable() ) {
		wp_send_json_error( array( 'message' => __( 'این محصول قابل خریداری نیست.', 'negar-shop' ) ) );
	}

	// Collect variable attributes
	foreach ( $_POST as $key => $value ) {
		if ( 0 === strpos( $key, 'attribute_' ) ) {
			$variations[ sanitize_text_field( $key ) ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	// If variable product without variation_id, attempt to match or reject
	if ( $product->is_type( 'variable' ) && ! $variation_id ) {
		$data_store = WC_Data_Store::load( 'product' );
		$matched_id = $data_store->find_matching_product_variation( $product, $variations );
		if ( $matched_id ) {
			$variation_id = $matched_id;
		} else {
			wp_send_json_error(
				array(
					'error'       => true,
					'product_url' => get_permalink( $product_id ),
					'message'     => __( 'لطفاً تمامی گزینه‌های محصول را انتخاب کنید.', 'negar-shop' ),
				)
			);
		}
	}

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

	if ( $passed_validation ) {
		$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );

		if ( $cart_item_key ) {
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			// Gather refreshed fragments
			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();

			$fragments = apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="drawer-body flex-1 overflow-y-auto p-5 widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			);

			wp_send_json(
				array(
					'error'       => false,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
					'fragments'   => $fragments,
					'cart_hash'   => WC()->cart->get_cart_hash(),
				)
			);
		}
	}

	// Capture errors from WooCommerce notices
	$notices = wc_get_notices( 'error' );
	$message = ! empty( $notices ) ? wp_strip_all_tags( $notices[0]['notice'] ) : __( 'افزودن به سبد خرید با خطا مواجه شد.', 'negar-shop' );
	wc_clear_notices();

	wp_send_json(
		array(
			'error'       => true,
			'product_url' => get_permalink( $product_id ),
			'message'     => $message,
		)
	);
}
add_action( 'wp_ajax_kaveh_ajax_add_to_cart', 'negar_shop_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_kaveh_ajax_add_to_cart', 'negar_shop_ajax_add_to_cart' );

/**
 * Sitewide AJAX Cart Quantity Update Handler (kaveh_ajax_update_cart_kk)
 */
function negar_shop_ajax_update_cart_quantity() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'ووکامرس فعال نیست.', 'negar-shop' ) ) );
	}

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
	$quantity      = isset( $_POST['quantity'] ) ? floatval( $_POST['quantity'] ) : 0;

	if ( ! empty( $cart_item_key ) ) {
		if ( $quantity <= 0 ) {
			WC()->cart->remove_cart_item( $cart_item_key );
		} else {
			WC()->cart->set_quantity( $cart_item_key, $quantity );
		}

		WC()->cart->calculate_totals();

		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$fragments = apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.widget_shopping_cart_content' => '<div class="drawer-body flex-1 overflow-y-auto p-5 widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		);

		wp_send_json(
			array(
				'error'     => false,
				'fragments' => $fragments,
				'cart_hash' => WC()->cart->get_cart_hash(),
			)
		);
	}

	wp_send_json_error( array( 'message' => __( 'شناسه آیتم سبد خرید نامعتبر است.', 'negar-shop' ) ) );
}
add_action( 'wp_ajax_kaveh_ajax_update_cart_kk', 'negar_shop_ajax_update_cart_quantity' );
add_action( 'wp_ajax_nopriv_kaveh_ajax_update_cart_kk', 'negar_shop_ajax_update_cart_quantity' );

/**
 * Sitewide AJAX Cart Item Remove Handler (kaveh_ajax_update_cart_kk_remove)
 */
function negar_shop_ajax_remove_cart_item() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'ووکامرس فعال نیست.', 'negar-shop' ) ) );
	}

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';

	if ( ! empty( $cart_item_key ) ) {
		WC()->cart->remove_cart_item( $cart_item_key );
		WC()->cart->calculate_totals();

		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$fragments = apply_filters(
			'woocommerce_add_to_cart_fragments',
			array(
				'div.widget_shopping_cart_content' => '<div class="drawer-body flex-1 overflow-y-auto p-5 widget_shopping_cart_content">' . $mini_cart . '</div>',
			)
		);

		wp_send_json(
			array(
				'error'     => false,
				'fragments' => $fragments,
				'cart_hash' => WC()->cart->get_cart_hash(),
			)
		);
	}

	wp_send_json_error( array( 'message' => __( 'شناسه آیتم سبد خرید نامعتبر است.', 'negar-shop' ) ) );
}
add_action( 'wp_ajax_kaveh_ajax_update_cart_kk_remove', 'negar_shop_ajax_remove_cart_item' );
add_action( 'wp_ajax_nopriv_kaveh_ajax_update_cart_kk_remove', 'negar_shop_ajax_remove_cart_item' );

/**
 * Add custom body classes for WooCommerce flows.
 *
 * @param array $classes Body classes.
 * @return array
 */
function negar_shop_body_classes( $classes ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'kav-single-product-style-5-page';
		$classes[] = 'kaveh-modern-toman';
	}
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$classes[] = 'kaveh-woo-flow-style-2';
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$classes[] = 'kaveh-woo-flow-style-2';
	}
	return $classes;
}
add_filter( 'body_class', 'negar_shop_body_classes' );
