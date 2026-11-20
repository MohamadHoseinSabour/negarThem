<?php
/**
 * VIP Membership Lifecycle & Manual Credit Management Test Suite.
 *
 * Verifies Phase 6 Replacement:
 * 1. Isolated schema migration and memberships table verification.
 * 2. VIP product mapping and virtual attributes.
 * 3. Idempotent order payment listener and exactly-once activation.
 * 4. Repeat purchase safety (pending operator review flag).
 * 5. Operator manual purchase usage, deposit application, and debt recording.
 * 6. Bookkeeping boundary safeguards (no negative balances, capacity bounds).
 * 7. Offline installment collection logging without automated online orders.
 * 8. Customer account anti-IDOR data isolation.
 * 9. Order refund/cancellation safe suspension.
 *
 * @package NegarThem
 */

// Define isolated test prefix before any table resolution
if ( ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) {
	define( 'NEGAR_CLUB_TEST_PREFIX', 'test_' );
}

// Bootstrap WordPress
require_once dirname( __DIR__ ) . '/inc/theme-settings.php';
$wp_load_path = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . '/wp-load.php';
if ( ! file_exists( $wp_load_path ) ) {
	echo "[SKIP] wp-load.php not found at {$wp_load_path}. Exiting cleanly.\n";
	exit( 0 );
}
require_once $wp_load_path;

// Load Customer Club plugin files
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-database.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-roles.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-vip-products.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-membership-manager.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-customer-dashboard.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/admin/class-admin-memberships.php';

echo "--- Running VIP Membership & Manual Credit Lifecycle Test Suite (Phase 6) ---\n\n";

$passed = 0;
$failed = 0;

function run_vip_test( $name, $callback ) {
	global $passed, $failed;
	try {
		$callback();
		echo "[PASS] {$name}\n";
		$passed++;
	} catch ( Throwable $e ) {
		echo "[FAIL] {$name}: " . $e->getMessage() . "\n";
		echo "       File: " . $e->getFile() . ':' . $e->getLine() . "\n";
		$failed++;
	}
}

function assert_vip_true( $condition, $message = '' ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message ?: 'Expected condition to be true' );
	}
}

function assert_vip_false( $condition, $message = '' ) {
	if ( $condition ) {
		throw new RuntimeException( $message ?: 'Expected condition to be false' );
	}
}

function assert_vip_equals( $expected, $actual, $message = '' ) {
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message ?: sprintf( 'Expected %s, got %s', var_export( $expected, true ), var_export( $actual, true ) ) );
	}
}

global $wpdb;
$test_app_table   = Negar_Club_Database::get_table_name( 'applications' );
$test_audit_table = Negar_Club_Database::get_table_name( 'audit_log' );
$test_mem_table   = Negar_Club_Database::get_table_name( 'memberships' );

try {

	// Test 1: Isolated Schema Migration and Table Verification (T340-VIP)
	run_vip_test( 'T340-VIP: Database Migration & Schema Verification for wp_negar_club_memberships', function() use ( $wpdb, $test_mem_table ) {
		$migrated = Negar_Club_Database::create_tables();
		assert_vip_true( $migrated, 'create_tables must verify table existence and return true' );

		$mem_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_mem_table ) );
		assert_vip_equals( $test_mem_table, $mem_exists, 'Isolated memberships table must exist in database' );

		// Check columns
		$columns = $wpdb->get_col( "DESCRIBE `{$test_mem_table}`", 0 );
		$required = array(
			'customer_id', 'plan_id', 'order_id',
			'prepayment_amount', 'applied_deposit', 'unused_deposit',
			'purchase_ceiling', 'used_purchase_amount', 'available_purchase_capacity',
			'offline_debt_amount', 'term_months', 'status', 'valid_until', 'notes',
		);
		foreach ( $required as $col ) {
			assert_vip_true( in_array( $col, $columns, true ), "Column {$col} must exist in memberships table" );
		}
	} );

	// Test 2: VIP Plan Product Mapping and Configuration (T341-VIP)
	run_vip_test( 'T341-VIP: VIP Plan Configurations and Virtual Product Mapping', function() {
		$configs = Negar_Club_VIP_Products::get_plan_configs();
		assert_vip_true( isset( $configs['negar_aghaz'] ), 'negar_aghaz plan must be configured' );
		assert_vip_true( isset( $configs['negar_plus'] ), 'negar_plus plan must be configured' );
		assert_vip_true( isset( $configs['negar_vizhe'] ), 'negar_vizhe plan must be configured' );

		assert_vip_equals( 3000000, (int) $configs['negar_aghaz']['prepayment'] );
		assert_vip_equals( 6000000, (int) $configs['negar_aghaz']['ceiling'] );

		assert_vip_equals( 5000000, (int) $configs['negar_plus']['prepayment'] );
		assert_vip_equals( 10000000, (int) $configs['negar_plus']['ceiling'] );

		assert_vip_equals( 10000000, (int) $configs['negar_vizhe']['prepayment'] );
		assert_vip_equals( 20000000, (int) $configs['negar_vizhe']['ceiling'] );
	} );

	// Test 3: Unpaid and Deferred On-Hold Orders Block Automatic Activation
	run_vip_test( 'T342-VIP: Unpaid Orders Do Not Activate VIP Membership', function() {
		$unpaid_order_id = 999901;

		// Mock filter to simulate unpaid/on-hold order
		$order_mock_filter = function( $order, $id ) use ( $unpaid_order_id ) {
			if ( (int) $id === $unpaid_order_id ) {
				$mock = new class {
					public function get_status() { return 'on-hold'; }
					public function get_payment_method() { return 'bacs'; }
					public function is_paid() { return false; }
					public function get_date_paid() { return null; }
					public function get_customer_id() { return 101; }
					public function get_total() { return 5000000; }
					public function get_items() {
						$item = new class {
							public function get_product_id() { return 88881; }
							public function get_total() { return 5000000; }
						};
						return array( $item );
					}
				};
				return $mock;
			}
			return $order;
		};

		add_filter( 'negar_club_get_order', $order_mock_filter, 10, 2 );

		// Simulate checking unpaid order
		$result = Negar_Club_Membership_Manager::process_paid_order( $unpaid_order_id );
		remove_filter( 'negar_club_get_order', $order_mock_filter );

		assert_vip_false( $result, 'Unpaid on-hold order must not activate membership' );
	} );

	// Test 4: Verified Paid Order Idempotently Activates Exactly-Once Membership
	$test_order_id = 999902;
	$test_user_id  = 105;
	$created_membership_id = 0;

	run_vip_test( 'T342-VIP: Paid WooCommerce Order Idempotently Activates Membership', function() use ( $test_order_id, $test_user_id, &$created_membership_id ) {
		$paid_order_filter = function( $order, $id ) use ( $test_order_id, $test_user_id ) {
			if ( (int) $id === $test_order_id ) {
				return new class( $test_user_id ) {
					private $uid;
					public function __construct( $uid ) { $this->uid = $uid; }
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_customer_id() { return $this->uid; }
					public function get_total() { return 5000000; }
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 88882; }
								public function get_total() { return 5000000; }
							}
						);
					}
				};
			}
			return $order;
		};

		// Mock product meta to associate product 88882 with 'negar_plus'
		$postmeta_filter = function( $meta, $object_id, $meta_key ) {
			if ( 88882 === (int) $object_id && Negar_Club_VIP_Products::META_KEY_PLAN_ID === $meta_key ) {
				return 'negar_plus';
			}
			return $meta;
		};

		add_filter( 'negar_club_get_order', $paid_order_filter, 10, 2 );
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );

		// First activation call
		$created_membership_id = Negar_Club_Membership_Manager::process_paid_order( $test_order_id );
		assert_vip_true( $created_membership_id > 0, 'process_paid_order must return inserted membership ID' );

		// Verify stored record
		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_true( (bool) $mem, 'Stored membership row must exist' );
		assert_vip_equals( $test_user_id, (int) $mem->customer_id );
		assert_vip_equals( 'negar_plus', $mem->plan_id );
		assert_vip_equals( 5000000, (int) $mem->prepayment_amount );
		assert_vip_equals( 5000000, (int) $mem->unused_deposit );
		assert_vip_equals( 10000000, (int) $mem->purchase_ceiling );
		assert_vip_equals( 10000000, (int) $mem->available_purchase_capacity );
		assert_vip_equals( 'active', $mem->status );

		// Exactly-once idempotency: repeat call with same order ID must return same ID without inserting new row
		$repeat_id = Negar_Club_Membership_Manager::process_paid_order( $test_order_id );
		assert_vip_equals( $created_membership_id, $repeat_id, 'Repeat hook call must return existing membership ID' );

		remove_filter( 'negar_club_get_order', $paid_order_filter );
		remove_filter( 'get_post_metadata', $postmeta_filter );
	} );

	// Test 5: Repeat Purchase While Already Active Flags for Operator Review
	run_vip_test( 'T342-VIP: Repeat Purchase While Active Flags for Review', function() use ( $test_user_id ) {
		$second_order_id = 999903;

		$second_order_filter = function( $order, $id ) use ( $second_order_id, $test_user_id ) {
			if ( (int) $id === $second_order_id ) {
				return new class( $test_user_id ) {
					private $uid;
					public function __construct( $uid ) { $this->uid = $uid; }
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_customer_id() { return $this->uid; }
					public function get_total() { return 3000000; }
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 88883; }
								public function get_total() { return 3000000; }
							}
						);
					}
				};
			}
			return $order;
		};

		$second_meta_filter = function( $meta, $object_id, $meta_key ) {
			if ( 88883 === (int) $object_id && Negar_Club_VIP_Products::META_KEY_PLAN_ID === $meta_key ) {
				return 'negar_aghaz';
			}
			return $meta;
		};

		add_filter( 'negar_club_get_order', $second_order_filter, 10, 2 );
		add_filter( 'get_post_metadata', $second_meta_filter, 10, 3 );

		$second_mem_id = Negar_Club_Membership_Manager::process_paid_order( $second_order_id );
		assert_vip_true( $second_mem_id > 0, 'Second paid order must create distinct membership record' );

		$second_mem = Negar_Club_Database::get_membership( $second_mem_id );
		assert_vip_equals( 'pending_operator_review', $second_mem->status, 'Repeat purchase while active must be flagged for operator review' );

		remove_filter( 'negar_club_get_order', $second_order_filter );
		remove_filter( 'get_post_metadata', $second_meta_filter );
	} );

	// Test 6: Operator Manual In-Store Purchase Record & Deposit Application (T343-VIP)
	run_vip_test( 'T343-VIP: Operator Records In-Store Purchase and Applies Deposit', function() use ( $created_membership_id, $wpdb, $test_mem_table, $test_audit_table ) {
		wp_set_current_user( 1 );

		// Mock operator user capability
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Simulate operator action: purchase_amount = 4,000,000 IRT, apply deposit = 2,000,000 IRT
		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'purchase_amount'              => 4000000,
			'deposit_to_apply'             => 2000000,
			'receipt_ref'                  => 'REC-1403-101',
			'record_as_debt'               => '1',
			'reason'                       => 'تحویل یخچال در فروشگاه حضوری',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 4000000, (int) $mem->used_purchase_amount, 'Used purchase amount must be 4M' );
		assert_vip_equals( 6000000, (int) $mem->available_purchase_capacity, 'Available capacity must be 6M (10M - 4M)' );
		assert_vip_equals( 2000000, (int) $mem->applied_deposit, 'Applied deposit must be 2M' );
		assert_vip_equals( 3000000, (int) $mem->unused_deposit, 'Unused deposit balance must be 3M (5M - 2M)' );
		assert_vip_equals( 2000000, (int) $mem->offline_debt_amount, 'Offline debt must be 2M (4M - 2M)' );

		// Verify audit log entry
		$audit_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$test_audit_table}` WHERE entity_id = %d AND event_action = 'membership:record_purchase'", $created_membership_id ) );
		assert_vip_true( $audit_count > 0, 'Audit log entry must be recorded for manual operator purchase' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 7: Operator Boundary Safeguards: Exceeding Available Capacity Rejected
	run_vip_test( 'T343-VIP: Purchase Exceeding Capacity Is Strictly Rejected', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );

		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Current available capacity is 6,000,000 IRT. Attempting 8,000,000 IRT must fail.
		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'purchase_amount'              => 8000000,
			'deposit_to_apply'             => 0,
			'reason'                       => 'خرید بیش از سقف مجاز',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		// Capacity must remain unchanged at 6M
		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 6000000, (int) $mem->available_purchase_capacity, 'Capacity must remain 6M without mutation' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 8: Operator Records Offline Installment Collection Receipt (T343-VIP)
	run_vip_test( 'T343-VIP: Operator Records Offline Installment Collection Receipt', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );

		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Debt is currently 2,000,000 IRT. Collect 1,000,000 IRT in store.
		$_POST = array(
			'negar_club_membership_action' => 'record_collection',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'collected_amount'             => 1000000,
			'collection_ref'               => 'POS-TRACE-4491',
			'reason'                       => 'وصول قسط اول کارتخوان',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 1000000, (int) $mem->offline_debt_amount, 'Offline debt must reduce to 1M (2M - 1M)' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 9: Customer Account Anti-IDOR & Membership Query Isolation (T344-VIP)
	run_vip_test( 'T344-VIP: Anti-IDOR Isolation in Customer Dashboard', function() use ( $test_user_id ) {
		// Customer A
		$mems_a = Negar_Club_Database::get_memberships_by_customer( $test_user_id );
		assert_vip_true( count( $mems_a ) >= 1, 'Customer A must see their own memberships' );

		// Customer B (different user ID)
		$mems_b = Negar_Club_Database::get_memberships_by_customer( 999 );
		assert_vip_equals( 0, count( $mems_b ), 'Customer B must see 0 records of Customer A' );
	} );

	// Test 10: Order Refund Safely Suspends Membership (T346-VIP)
	run_vip_test( 'T346-VIP: Order Refund Safely Suspends Membership Without Ledger Corruption', function() use ( $test_order_id, $created_membership_id ) {
		$handled = Negar_Club_Membership_Manager::handle_order_refund_or_cancellation( $test_order_id, 'refund' );
		assert_vip_true( $handled, 'Refund hook must handle linked VIP membership' );

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'refunded_pending_reconciliation', $mem->status, 'Status must transition to refunded_pending_reconciliation' );
		// Recorded in-store purchase data remains intact and not erased
		assert_vip_equals( 4000000, (int) $mem->used_purchase_amount, 'Recorded physical purchase must be preserved' );
	} );

} finally {
	// Clean teardown: drop isolated test tables
	$wpdb->query( "DROP TABLE IF EXISTS `{$test_app_table}`, `{$test_audit_table}`, `{$test_mem_table}`" );
}

echo "\n--- VIP Membership Lifecycle Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: {$passed}\n";
echo "Failed: {$failed}\n";

if ( $failed > 0 ) {
	exit( 1 );
}
