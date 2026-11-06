<?php
/**
 * WooCommerce Compatibility and Hooks
 *
 * @package Negar_Shop
 * @version 1.0.0
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
 * Update WooCommerce Cart Fragment via Ajax.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function negar_shop_cart_fragments( $fragments ) {
	ob_start();
	?>
	<span class="negar-cart-count badge badge-discount">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
	$fragments['.negar-cart-count'] = ob_get_clean();

	ob_start();
	?>
	<span class="negar-cart-total-price">
		<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
	</span>
	<?php
	$fragments['.negar-cart-total-price'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'negar_shop_cart_fragments' );
