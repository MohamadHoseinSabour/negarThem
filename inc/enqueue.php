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

	// Icomoon Official Icon Fonts
	wp_enqueue_style(
		'kaveh-icomoon',
		NEGAR_SHOP_URI . '/assets/css/vendor/icomoon.css',
		array(),
		'5.0.0'
	);

	// Bootstrap 5 RTL Framework Utilities
	wp_enqueue_style(
		'bootstrap-rtl',
		NEGAR_SHOP_URI . '/assets/css/vendor/bootstrap.rtl.min.css',
		array( 'kaveh-icomoon' ),
		'5.3.0'
	);

	// Kaveh Product Cards Official CSS
	wp_enqueue_style(
		'kaveh-product-carts',
		NEGAR_SHOP_URI . '/assets/css/vendor/product-carts.min.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);

	// Kaveh Elementor Widgets Official CSS (Landing, Categories, Deals)
	wp_enqueue_style(
		'kaveh-landing',
		NEGAR_SHOP_URI . '/assets/css/vendor/landingnkkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-categories',
		NEGAR_SHOP_URI . '/assets/css/vendor/categoriesnkkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-popular',
		NEGAR_SHOP_URI . '/assets/css/vendor/popularproductsnkkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-banners',
		NEGAR_SHOP_URI . '/assets/css/vendor/bannernkkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-carousel-seventeen',
		NEGAR_SHOP_URI . '/assets/css/vendor/productscarouselseventeennkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-product-carts' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-productbox',
		NEGAR_SHOP_URI . '/assets/css/vendor/productboxnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-product-carts' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-buy-top-product',
		NEGAR_SHOP_URI . '/assets/css/vendor/buytopproductnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-product-carts' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-productspack',
		NEGAR_SHOP_URI . '/assets/css/vendor/productspacknkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-product-carts' ),
		NEGAR_SHOP_VERSION
	);

	// Tailwind Engine Compiled Utility CSS
	wp_enqueue_style(
		'negar-shop-tailwind',
		NEGAR_SHOP_URI . '/assets/css/tailwind.css',
		array( 'negar-shop-style', 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);

	// Swiper Slider CSS
	wp_enqueue_style(
		'swiper-bundle',
		NEGAR_SHOP_URI . '/assets/vendor/swiper/swiper-bundle.min.css',
		array(),
		'11.0.0'
	);

	// Design System Tokens & Base Component CSS
	wp_enqueue_style(
		'negar-shop-main',
		NEGAR_SHOP_URI . '/assets/css/main.css',
		array( 'negar-shop-tailwind', 'swiper-bundle' ),
		NEGAR_SHOP_VERSION
	);

	// Ensure Demo 43 primary brand color (#e90544) is globally active
	wp_add_inline_style(
		'negar-shop-main',
		':root { --kaveh-primary-color: #e90544 !important; --kaveh-primary-hover: #d0043c !important; --color-primary: #e90544 !important; --color-primary-hover: #d0043c !important; }'
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

	// Cart, Checkout & My Account Dedicated CSS
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'negar-shop-checkout-cart',
			NEGAR_SHOP_URI . '/assets/css/checkout-cart.css',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Content Pages Dedicated CSS (Blog, Single Post, About, Contact, 404, Search)
	if ( is_singular( 'post' ) || is_archive() || is_search() || is_404() || is_page_template( 'page-templates/page-about.php' ) || is_page_template( 'page-templates/page-contact.php' ) ) {
		wp_enqueue_style(
			'negar-shop-content-pages',
			NEGAR_SHOP_URI . '/assets/css/content-pages.css',
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

	// Modals, QuickView, Drawers & Toast CSS (MODAL-01 to MODAL-04)
	wp_enqueue_style(
		'negar-shop-modals',
		NEGAR_SHOP_URI . '/assets/css/modals.css',
		array( 'negar-shop-header-footer' ),
		NEGAR_SHOP_VERSION
	);

	// Swiper Slider JS
	wp_enqueue_script(
		'swiper-bundle',
		NEGAR_SHOP_URI . '/assets/vendor/swiper/swiper-bundle.min.js',
		array(),
		'11.0.0',
		true
	);

	// Main Vanilla JS Framework
	wp_enqueue_script(
		'negar-shop-main',
		NEGAR_SHOP_URI . '/assets/js/main.js',
		array( 'swiper-bundle' ),
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

	// Cart, Checkout & My Account Dedicated Script
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_script(
			'negar-shop-checkout-cart',
			NEGAR_SHOP_URI . '/assets/js/checkout-cart.js',
			array( 'negar-shop-header-footer' ),
			NEGAR_SHOP_VERSION,
			true
		);
	}

	// Threaded Comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Modals, QuickView, Wishlist, Compare & Toast Script (MODAL-01 to MODAL-04)
	wp_enqueue_script(
		'negar-shop-modals',
		NEGAR_SHOP_URI . '/assets/js/modals.js',
		array( 'negar-shop-main', 'negar-shop-header-footer' ),
		NEGAR_SHOP_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'negar_shop_scripts' );
