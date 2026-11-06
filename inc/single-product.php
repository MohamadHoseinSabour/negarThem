<?php
/**
 * Single Product Helper Functions & Data Providers
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get structured specifications for a product.
 *
 * @param WC_Product $product WooCommerce product object.
 * @return array List of specs with 'label' and 'value'.
 */
function negar_shop_get_product_specs( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return array();
	}

	$specs = array();

	// SKU
	if ( $product->get_sku() ) {
		$specs[] = array(
			'label' => __( 'شناسه کالا (SKU)', 'negar-shop' ),
			'value' => $product->get_sku(),
		);
	}

	// Weight
	if ( $product->has_weight() ) {
		$specs[] = array(
			'label' => __( 'وزن', 'negar-shop' ),
			'value' => sprintf( '%s %s', $product->get_weight(), get_option( 'woocommerce_weight_unit' ) ),
		);
	}

	// Dimensions
	if ( $product->has_dimensions() ) {
		$specs[] = array(
			'label' => __( 'ابعاد (طول × عرض × ارتفاع)', 'negar-shop' ),
			'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
		);
	}

	// Custom Attributes
	$attributes = $product->get_attributes();
	foreach ( $attributes as $attribute ) {
		if ( empty( $attribute ) ) {
			continue;
		}

		if ( $attribute->is_taxonomy() ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
			if ( ! empty( $terms ) ) {
				$specs[] = array(
					'label' => wc_attribute_label( $attribute->get_name() ),
					'value' => implode( '، ', $terms ),
				);
			}
		} else {
			$specs[] = array(
				'label' => $attribute->get_name(),
				'value' => implode( '، ', $attribute->get_options() ),
			);
		}
	}

	return $specs;
}

/**
 * Get product trust and warranty assurance perks.
 *
 * @return array List of perks.
 */
function negar_shop_get_trust_perks() {
	return array(
		array(
			'icon'        => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
			'title'       => __( 'ضمانت اصالت کالا', 'negar-shop' ),
			'description' => __( '۱۰۰٪ تضمین کالای اصل و اورجینال', 'negar-shop' ),
		),
		array(
			'icon'        => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>',
			'title'       => __( '۷ روز ضمانت بازگشت', 'negar-shop' ),
			'description' => __( 'امکان تعویض یا عودت کالا بدون قید و شرط', 'negar-shop' ),
		),
		array(
			'icon'        => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>',
			'title'       => __( 'تحویل اکسپرس و سریع', 'negar-shop' ),
			'description' => __( 'ارسال امن با بسته‌بندی ویژه و بیمه مرسوله', 'negar-shop' ),
		),
		array(
			'icon'        => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
			'title'       => __( 'گارانتی معتبر شرکتی', 'negar-shop' ),
			'description' => __( 'دارای ۱۸ تا ۲۴ ماه خدمات پس از فروش رسمی', 'negar-shop' ),
		),
	);
}
