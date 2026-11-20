<?php
/**
 * Plugin Name: Negar Customer Club & Installment Purchase
 * Plugin URI: https://github.com/MohamadHoseinSabour/negarThem
 * Description: Foundation for customer club quotation previews, installment calculation, application underwriting, and credit evaluation.
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

define( 'NEGAR_CLUB_VERSION', '1.2.0' );
define( 'NEGAR_CLUB_DB_VERSION', '1.2.0' );
define( 'NEGAR_CLUB_DIR', __DIR__ );

// Core components
require_once NEGAR_CLUB_DIR . '/includes/class-quote-calculator.php';
require_once NEGAR_CLUB_DIR . '/includes/class-validator.php';
require_once NEGAR_CLUB_DIR . '/includes/class-database.php';
require_once NEGAR_CLUB_DIR . '/includes/class-roles.php';
require_once NEGAR_CLUB_DIR . '/includes/class-vip-products.php';
require_once NEGAR_CLUB_DIR . '/includes/class-membership-manager.php';
require_once NEGAR_CLUB_DIR . '/includes/class-customer-dashboard.php';
require_once NEGAR_CLUB_DIR . '/includes/class-application-manager.php';
require_once NEGAR_CLUB_DIR . '/includes/admin/class-admin-applications.php';
require_once NEGAR_CLUB_DIR . '/includes/admin/class-admin-memberships.php';

// Plugin lifecycle activation hooks
register_activation_hook( __FILE__, array( 'Negar_Club_Database', 'create_tables' ) );
register_activation_hook( __FILE__, array( 'Negar_Club_Roles', 'register_capabilities' ) );

// Auto-run schema migration if db_version is outdated or missing
add_action(
	'plugins_loaded',
	function() {
		Negar_Club_Roles::register_capabilities();
		Negar_Club_VIP_Products::init();
		Negar_Club_Membership_Manager::init();
		Negar_Club_Customer_Dashboard::init();
		if ( get_option( 'negar_club_db_version' ) !== NEGAR_CLUB_DB_VERSION ) {
			Negar_Club_Database::create_tables();
		}
	}
);

// Register REST API routes
add_action( 'rest_api_init', array( 'Negar_Club_Quote_Calculator', 'register_rest_routes' ) );
add_action( 'rest_api_init', array( 'Negar_Club_Application_Manager', 'register_rest_routes' ) );

// Admin management UI
if ( is_admin() ) {
	add_action( 'admin_menu', array( 'Negar_Club_Admin_Applications', 'register_admin_menu' ) );
	add_action( 'admin_init', array( 'Negar_Club_Admin_Applications', 'handle_admin_actions' ) );
	add_action( 'admin_menu', array( 'Negar_Club_Admin_Memberships', 'register_admin_menu' ) );
	add_action( 'admin_init', array( 'Negar_Club_Admin_Memberships', 'handle_admin_actions' ) );
}

// Declare HPOS compatibility (High-Performance Order Storage)
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
