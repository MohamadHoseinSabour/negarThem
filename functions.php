<?php
/**
 * Negar Shop theme functions and definitions
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Theme Constants
 */
define( 'NEGAR_SHOP_VERSION', '1.0.0' );
define( 'NEGAR_SHOP_DIR', get_template_directory() );
define( 'NEGAR_SHOP_URI', get_template_directory_uri() );

/**
 * Core Theme Setup & Supports
 */
require_once NEGAR_SHOP_DIR . '/inc/setup.php';

/**
 * Asset Enqueue & Font Loading
 */
require_once NEGAR_SHOP_DIR . '/inc/enqueue.php';

/**
 * Template Helper Functions & Escaping Tags
 */
require_once NEGAR_SHOP_DIR . '/inc/template-tags.php';

/**
 * WooCommerce Integration & Hooks
 */
if ( class_exists( 'WooCommerce' ) || file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
	require_once NEGAR_SHOP_DIR . '/inc/woocommerce.php';
}
