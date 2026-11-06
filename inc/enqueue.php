<?php
/**
 * Enqueue scripts and styles
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add resource hints for font CDNs
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function negar_shop_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'negar_shop_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function negar_shop_scripts() {
	// Google Fonts - Vazirmatn with weights 300, 400, 500, 600, 700, 800
	wp_enqueue_style(
		'negar-shop-fonts',
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap',
		array(),
		null
	);

	// Main Stylesheet
	wp_enqueue_style(
		'negar-shop-style',
		get_stylesheet_uri(),
		array(),
		NEGAR_SHOP_VERSION
	);

	// Design System Tokens & Base Component CSS
	wp_enqueue_style(
		'negar-shop-main',
		NEGAR_SHOP_URI . '/assets/css/main.css',
		array( 'negar-shop-style' ),
		NEGAR_SHOP_VERSION
	);

	// Header and Footer Dedicated CSS
	wp_enqueue_style(
		'negar-shop-header-footer',
		NEGAR_SHOP_URI . '/assets/css/header-footer.css',
		array( 'negar-shop-main' ),
		NEGAR_SHOP_VERSION
	);

	// Homepage Dedicated CSS
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'negar-shop-home',
			NEGAR_SHOP_URI . '/assets/css/home.css',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Shop & Archive Dedicated CSS
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_style(
			'negar-shop-shop',
			NEGAR_SHOP_URI . '/assets/css/shop.css',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Single Product Dedicated CSS
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style(
			'negar-shop-single-product',
			NEGAR_SHOP_URI . '/assets/css/single-product.css',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION
		);
	}

	// RTL Stylesheet
	if ( is_rtl() ) {
		wp_enqueue_style(
			'negar-shop-rtl',
			NEGAR_SHOP_URI . '/rtl.css',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Main Vanilla JS Framework
	wp_enqueue_script(
		'negar-shop-main',
		NEGAR_SHOP_URI . '/assets/js/main.js',
		array(),
		NEGAR_SHOP_VERSION,
		true
	);

	// Localize Parameters
	wp_localize_script(
		'negar-shop-main',
		'negarShopParams',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'negar_shop_nonce' ),
			'isRtl'      => is_rtl(),
			'themeUri'   => NEGAR_SHOP_URI,
			'i18n'       => array(
				'addedToCart'       => esc_html__( 'کالا با موفقیت به سبد خرید افزوده شد.', 'negar-shop' ),
				'addedToWishlist'   => esc_html__( 'به لیست علاقه‌مندی‌ها اضافه شد.', 'negar-shop' ),
				'removedWishlist'   => esc_html__( 'از لیست علاقه‌مندی‌ها حذف شد.', 'negar-shop' ),
				'loading'           => esc_html__( 'در حال بارگذاری...', 'negar-shop' ),
				'error'             => esc_html__( 'خطایی رخ داد. لطفاً مجدداً تلاش کنید.', 'negar-shop' ),
				'searchPlaceholder' => esc_html__( 'جستجوی نام کالا، برند یا دسته‌بندی...', 'negar-shop' ),
			),
		)
	);

	// Header & Footer Interactive Script
	wp_enqueue_script(
		'negar-shop-header-footer',
		NEGAR_SHOP_URI . '/assets/js/header-footer.js',
		array( 'negar-shop-main' ),
		NEGAR_SHOP_VERSION,
		true
	);

	// Homepage Interactivity Script
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script(
			'negar-shop-home',
			NEGAR_SHOP_URI . '/assets/js/home.js',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION,
			true
		);
	}

	// Shop & Archive Interactive Script
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script(
			'negar-shop-shop',
			NEGAR_SHOP_URI . '/assets/js/shop.js',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION,
			true
		);

		wp_localize_script(
			'negar-shop-shop',
			'negarShopVars',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'negar_shop_nonce' ),
			)
		);
	}

	// Single Product Interactive Script
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script(
			'negar-shop-single-product',
			NEGAR_SHOP_URI . '/assets/js/single-product.js',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION,
			true
		);
	}

	// Threaded Comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'negar_shop_scripts' );
