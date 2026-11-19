<?php
/**
 * Plugin Name: Negar Customer Club & Installment Purchase
 * Plugin URI: https://github.com/MohamadHoseinSabour/negarThem
 * Description: Foundation for customer club quotation previews, installment calculation, and credit evaluation.
 * Version: 1.0.0
 * Author: Negar Themed Team
 * Text Domain: negar-club
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * WC requires at least: 8.0
 * WC tested up to: 10.5.2
 *
 * @package NegarCustomerClub
 */

defined( 'ABSPATH' ) || exit;

define( 'NEGAR_CLUB_VERSION', '1.0.0' );
define( 'NEGAR_CLUB_DIR', __DIR__ );

require_once NEGAR_CLUB_DIR . '/includes/class-quote-calculator.php';

// Register REST API route /wp-json/negar-club/v1/quote
add_action( 'rest_api_init', array( 'Negar_Club_Quote_Calculator', 'register_rest_routes' ) );

// Declare HPOS compatibility (High-Performance Order Storage)
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
