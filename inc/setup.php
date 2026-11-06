<?php
/**
 * Theme Setup and Configuration
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'negar_shop_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function negar_shop_setup() {
		/**
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'negar-shop', NEGAR_SHOP_DIR . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Let WordPress manage the document title.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 */
		add_theme_support( 'post-thumbnails' );

		// Custom image sizes for ecommerce cards and banners
		add_image_size( 'negar-product-card', 400, 400, true );
		add_image_size( 'negar-product-thumb', 100, 100, true );
		add_image_size( 'negar-banner-hero', 1200, 500, true );
		add_image_size( 'negar-banner-medium', 600, 320, true );

		/**
		 * Register Navigation Menus
		 */
		register_nav_menus(
			array(
				'primary'    => esc_html__( 'منوی ناوبری اصلی', 'negar-shop' ),
				'categories' => esc_html__( 'منوی دسته‌بندی‌ها (مگامنو)', 'negar-shop' ),
				'mobile'     => esc_html__( 'منوی موبایل', 'negar-shop' ),
				'footer-1'   => esc_html__( 'لینک‌های دسترسی سریع فوتر', 'negar-shop' ),
				'footer-2'   => esc_html__( 'لینک‌های خدمات و پشتیبانی فوتر', 'negar-shop' ),
			)
		);

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		/**
		 * Custom Logo Support
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 90,
				'width'       => 260,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/**
		 * Add theme support for selective refresh for widgets.
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for Block Styles.
		 */
		add_theme_support( 'wp-block-styles' );

		/**
		 * Responsive embedded content.
		 */
		add_theme_support( 'responsive-embeds' );
	}
endif;
add_action( 'after_setup_theme', 'negar_shop_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function negar_shop_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'negar_shop_content_width', 1200 );
}
add_action( 'after_setup_theme', 'negar_shop_content_width', 0 );

/**
 * Register Widget Areas / Sidebars (OPT-02)
 */
function negar_shop_widgets_init() {
	// Shop & Category Archive Sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'سایدبار فروشگاه و محصولات', 'negar-shop' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'ابزارک‌های این بخش در سایدبار فروشگاه و دسته‌بندی محصولات نمایش داده می‌شوند.', 'negar-shop' ),
			'before_widget' => '<div id="%1$s" class="shop-widget bg-white rounded-2xl border border-slate-100 p-5 shadow-xs mb-6 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="text-xs font-bold text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center justify-between">',
			'after_title'   => '</h4>',
		)
	);

	// Blog & Single Post Sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'سایدبار وبلاگ و مقالات', 'negar-shop' ),
			'id'            => 'sidebar-blog',
			'description'   => esc_html__( 'ابزارک‌های این بخش در سایدبار وبلاگ و صفحات داخلی مقالات نمایش داده می‌شوند.', 'negar-shop' ),
			'before_widget' => '<div id="%1$s" class="blog-widget bg-white rounded-2xl border border-slate-100 p-5 shadow-xs mb-6 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="text-xs font-bold text-slate-800 pb-3 mb-4 border-b border-slate-100 flex items-center justify-between">',
			'after_title'   => '</h4>',
		)
	);

	// Footer Columns 1 to 4
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: Column index */
				'name'          => sprintf( esc_html__( 'ستون فوتر %d', 'negar-shop' ), $i ),
				'id'            => 'footer-' . $i,
				/* translators: %d: Column index */
				'description'   => sprintf( esc_html__( 'ابزارک‌های ستون %d فوتر وب‌سایت.', 'negar-shop' ), $i ),
				'before_widget' => '<div id="%1$s" class="footer-widget space-y-3 %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="text-white text-sm font-bold mb-4 relative pb-2 after:content-[\'\'] after:absolute after:bottom-0 after:right-0 after:w-8 after:h-0.5 after:bg-rose-500">',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'negar_shop_widgets_init' );
