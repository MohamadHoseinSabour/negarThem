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
	wp_enqueue_style(
		'kaveh-brandscarouselsecond',
		NEGAR_SHOP_URI . '/assets/css/vendor/brandscarouselsecondnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-product-carts' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-topviewproductsthreefull',
		NEGAR_SHOP_URI . '/assets/css/vendor/topviewproductsthreefullnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-icomoon' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-videos',
		NEGAR_SHOP_URI . '/assets/css/vendor/videosnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-icomoon' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-articles',
		NEGAR_SHOP_URI . '/assets/css/vendor/articlesnkkaveh.css',
		array( 'bootstrap-rtl', 'kaveh-icomoon' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-features',
		NEGAR_SHOP_URI . '/assets/css/vendor/featureskaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);

	// Kaveh Official Header & MegaMenu Styles
	wp_enqueue_style(
		'kaveh-megamenu',
		NEGAR_SHOP_URI . '/assets/css/vendor/kavehmegamenu.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-megamenu-three',
		NEGAR_SHOP_URI . '/assets/css/vendor/megamenuthreekaveh.css',
		array( 'kaveh-megamenu' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-cart-header',
		NEGAR_SHOP_URI . '/assets/css/vendor/cartkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-auth-header',
		NEGAR_SHOP_URI . '/assets/css/vendor/loginandsignupkaveh.css',
		array( 'bootstrap-rtl' ),
		NEGAR_SHOP_VERSION
	);
	wp_enqueue_style(
		'kaveh-search-header',
		NEGAR_SHOP_URI . '/assets/css/vendor/searchkaveh.css',
		array( 'bootstrap-rtl' ),
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
			'kaveh-nouislider',
			NEGAR_SHOP_URI . '/assets/css/vendor/nouislider.min.css',
			array( 'bootstrap-rtl' ),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'kaveh-term-description',
			NEGAR_SHOP_URI . '/assets/css/vendor/kaveh-term-description.css',
			array( 'bootstrap-rtl' ),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'negar-shop-shop',
			NEGAR_SHOP_URI . '/assets/css/shop.css',
			array( 'negar-shop-header-footer', 'kaveh-nouislider' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Single Product Dedicated CSS
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style(
			'kaveh-single-product-main',
			NEGAR_SHOP_URI . '/assets/css/vendor/main-single-product.min.css',
			array( 'bootstrap-rtl' ),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'kaveh-magnify',
			NEGAR_SHOP_URI . '/assets/css/vendor/magnify.css',
			array(),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'kaveh-select2',
			NEGAR_SHOP_URI . '/assets/css/vendor/select2.min.css',
			array(),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'negar-shop-single-product',
			NEGAR_SHOP_URI . '/assets/css/single-product.css',
			array( 'negar-shop-header-footer', 'kaveh-single-product-main' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Cart, Checkout & My Account Dedicated CSS
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'kaveh-main-cart-checkout',
			NEGAR_SHOP_URI . '/assets/css/vendor/main-cart-checkout.min.css',
			array( 'bootstrap-rtl' ),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'negar-shop-checkout-cart',
			NEGAR_SHOP_URI . '/assets/css/checkout-cart.css',
			array( 'negar-shop-header-footer', 'kaveh-main-cart-checkout' ),
			NEGAR_SHOP_VERSION
		);
	}

	// Content Pages Dedicated CSS (Blog, Single Post, About, Contact, 404, Search)
	if ( is_singular( 'post' ) || is_archive() || is_search() || is_404() || is_page_template( 'page-templates/page-about.php' ) || is_page_template( 'page-templates/page-contact.php' ) ) {
		wp_enqueue_style(
			'kaveh-main-blog',
			NEGAR_SHOP_URI . '/assets/css/vendor/main-blog.min.css',
			array( 'bootstrap-rtl' ),
			NEGAR_SHOP_VERSION
		);
		wp_enqueue_style(
			'negar-shop-content-pages',
			NEGAR_SHOP_URI . '/assets/css/content-pages.css',
			array( 'negar-shop-header-footer', 'kaveh-main-blog' ),
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

	// Kaveh Official Global Header & Cart/Auth Scripts
	wp_enqueue_script(
		'kaveh-megamenu-js',
		NEGAR_SHOP_URI . '/assets/js/vendor/kavehmegamenu.js',
		array( 'jquery' ),
		NEGAR_SHOP_VERSION,
		true
	);
	wp_enqueue_script(
		'kaveh-ajax-cart-js',
		NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-ajax-cart.js',
		array( 'jquery' ),
		NEGAR_SHOP_VERSION,
		true
	);
	wp_enqueue_script(
		'kaveh-login-ajax-js',
		NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-login-ajax.js',
		array( 'jquery' ),
		NEGAR_SHOP_VERSION,
		true
	);

	// Header & Footer Interactive Script
	wp_enqueue_script(
		'negar-shop-header-footer',
		NEGAR_SHOP_URI . '/assets/js/header-footer.js',
		array( 'negar-shop-main', 'kaveh-megamenu-js', 'kaveh-ajax-cart-js' ),
		NEGAR_SHOP_VERSION,
		true
	);

	// Homepage Interactivity Script
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script(
			'kaveh-videos-script',
			NEGAR_SHOP_URI . '/assets/js/vendor/videosnkkaveh.js',
			array( 'jquery', 'swiper-bundle' ),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_enqueue_script(
			'negar-shop-home',
			NEGAR_SHOP_URI . '/assets/js/home.js',
			array( 'negar-shop-header-footer', 'swiper-bundle', 'kaveh-videos-script' ),
			NEGAR_SHOP_VERSION,
			true
		);
	}

	// Shop & Archive Interactive Script
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script(
			'kaveh-nouislider-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/nouislider.min.js',
			array(),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_enqueue_script(
			'kaveh-ajax-shop-method-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-ajax-shop-method.js',
			array( 'jquery', 'kaveh-nouislider-js' ),
			NEGAR_SHOP_VERSION,
			true
		);

		global $wpdb;
		$prices = $wpdb->get_row( "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_p, MAX(CAST(meta_value AS DECIMAL(10,2))) as max_p FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value > 0" );
		$lowest_price  = ( $prices && $prices->min_p ) ? (int) $prices->min_p : 10000;
		$highest_price = ( $prices && $prices->max_p ) ? (int) $prices->max_p : 100000000;

		wp_localize_script(
			'kaveh-ajax-shop-method-js',
			'kavehShopData',
			array(
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'ksiteurl'          => admin_url( 'admin-ajax.php' ),
				'fallback_url'      => admin_url( 'admin-ajax.php' ),
				'shop_filter_nonce' => wp_create_nonce( 'kaveh_shop_filter_nonce' ),
				'highestPrice'      => $highest_price,
				'lowestPrice'       => $lowest_price,
				'currency_code'     => get_woocommerce_currency(),
				'archiveContext'    => array(
					'is_shop'  => is_shop(),
					'taxonomy' => is_tax() ? get_queried_object()->taxonomy : '',
					'term_id'  => is_tax() ? get_queried_object_id() : 0,
				),
				'invalid_response'  => 'پاسخی از سرور دریافت نشد.',
				'filter_error'      => 'خطایی در فیلتر محصولات رخ داد.',
			)
		);

		wp_enqueue_script(
			'negar-shop-shop',
			NEGAR_SHOP_URI . '/assets/js/shop.js',
			array( 'negar-shop-header-footer', 'kaveh-ajax-shop-method-js' ),
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
			'kaveh-magnify-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/jquery.magnify.js',
			array( 'jquery' ),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_enqueue_script(
			'kaveh-product-zoom-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-product-zoom.js',
			array( 'jquery', 'kaveh-magnify-js' ),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_enqueue_script(
			'kaveh-main-single-product-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/main-single-product.min.js',
			array( 'jquery', 'swiper-bundle' ),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_enqueue_script(
			'negar-shop-single-product',
			NEGAR_SHOP_URI . '/assets/js/single-product.js',
			array( 'negar-shop-header-footer', 'kaveh-main-single-product-js', 'kaveh-product-zoom-js' ),
			NEGAR_SHOP_VERSION,
			true
		);
	}

	// Cart, Checkout & My Account Dedicated Script
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_script(
			'kaveh-cart-style-2-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-cart-style-2.js',
			array( 'jquery' ),
			NEGAR_SHOP_VERSION,
			true
		);
		wp_localize_script(
			'kaveh-cart-style-2-js',
			'kavehCartNkStrings',
			array(
				'couponEmpty' => 'لطفاً کد تخفیف را وارد کنید.',
				'couponError' => 'خطایی در اعمال کد تخفیف رخ داد. لطفاً مجدداً تلاش کنید.',
			)
		);

		wp_enqueue_script(
			'kaveh-cart-page-ajax-js',
			NEGAR_SHOP_URI . '/assets/js/vendor/kaveh-cart-page-ajax.js',
			array( 'jquery', 'kaveh-cart-style-2-js' ),
			NEGAR_SHOP_VERSION,
			true
		);

		wp_enqueue_script(
			'negar-shop-checkout-cart',
			NEGAR_SHOP_URI . '/assets/js/checkout-cart.js',
			array( 'negar-shop-header-footer', 'kaveh-cart-style-2-js' ),
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
