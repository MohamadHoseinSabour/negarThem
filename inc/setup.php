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
