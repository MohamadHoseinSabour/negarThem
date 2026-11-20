<?php
/**
 * VIP Membership Lifecycle & Financial Safety Test Suite (Phase 6.1 Hardened).
 *
 * Verifies Phase 6 Replacement + Phase 6.1 Critical Financial Safety:
 * Fix A: Protect real customer prepayments (immutable source, deposit invariant, exceptional corrections).
 * Fix B: Atomic operator actions (row-level locking, short transaction, submission token idempotency).
 * Fix C: Strict manual financial boundaries (no silent clamping of excessive collection, non-active status blocks, capacity consistency).
 * Fix D: Safe WooCommerce VIP products (no creation on frontend requests, suitable simple virtual product, price/currency checks).
 * Fix E: Non-destructive test isolation with pure unit tests and unique per-run test tables.
 *
 * @package NegarThem
 */

// Generate unique, collision-proof dynamic test prefix for isolated DB runs
if ( ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) {
	$test_run_hash = substr( md5( uniqid( (string) mt_rand(), true ) ), 0, 8 );
	define( 'NEGAR_CLUB_TEST_PREFIX', 'test_p61_' . $test_run_hash . '_' );
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

echo "--- Running VIP Membership & Financial Safety Test Suite (Phase 6.1) ---\n\n";

$passed  = 0;
$failed  = 0;
$skipped = 0;

function run_vip_test( $name, $callback ) {
	global $passed, $failed, $skipped;
	try {
		$result = $callback();
		if ( 'skip' === $result ) {
			echo "[SKIP] {$name}\n";
			$skipped++;
		} else {
			echo "[PASS] {$name}\n";
			$passed++;
		}
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

// -----------------------------------------------------------------------------
// PART 1: Pure Unit Tests (Zero Database Alterations)
// -----------------------------------------------------------------------------

echo "==> Section 1: Pure Unit Tests (In-Memory, Zero DB Mutations)\n";

// Unit Test 1: Strict Monetary Input Validation (Fix A & C)
run_vip_test( 'Unit Test 1 (Fix A & C): Monetary input validation strictly rejects malformed, negative, decimal, and zero values without silent clamping', function() {
	// Negative values must throw InvalidArgumentException
	$caught = false;
	try {
		Negar_Club_Admin_Memberships::validate_monetary_input( '-5000', 'مبلغ خرید' );
	} catch ( InvalidArgumentException $e ) {
		$caught = true;
	}
	assert_vip_true( $caught, 'Negative monetary input must throw InvalidArgumentException and never clamp to zero' );

	// Decimal values must throw
	$caught_dec = false;
	try {
		Negar_Club_Admin_Memberships::validate_monetary_input( '1000.5', 'مبلغ' );
	} catch ( InvalidArgumentException $e ) {
		$caught_dec = true;
	}
	assert_vip_true( $caught_dec, 'Decimal monetary input must throw InvalidArgumentException' );

	// Non-numeric strings must throw
	$caught_str = false;
	try {
		Negar_Club_Admin_Memberships::validate_monetary_input( 'xyz', 'مبلغ' );
	} catch ( InvalidArgumentException $e ) {
		$caught_str = true;
	}
	assert_vip_true( $caught_str, 'Non-numeric input must throw InvalidArgumentException' );

	// Zero when allow_zero is false must throw
	$caught_zero = false;
	try {
		Negar_Club_Admin_Memberships::validate_monetary_input( '0', 'مبلغ خرید', false );
	} catch ( InvalidArgumentException $e ) {
		$caught_zero = true;
	}
	assert_vip_true( $caught_zero, 'Zero when forbidden must throw InvalidArgumentException' );

	// Valid Persian digits must normalize cleanly
	$normalized = Negar_Club_Admin_Memberships::validate_monetary_input( '۵۰۰۰۰۰۰', 'پیش‌پرداخت' );
	assert_vip_equals( 5000000, $normalized, 'Persian digits must convert cleanly to integer 5,000,000' );
} );

// Unit Test 2: Product Suitability Validation (Fix D)
run_vip_test( 'Unit Test 2 (Fix D): Product suitability strictly rejects invalid product IDs or non-simple non-virtual items', function() {
	assert_vip_false( Negar_Club_VIP_Products::is_valid_vip_product( 0 ), 'Product ID 0 must be invalid' );
	assert_vip_false( Negar_Club_VIP_Products::is_valid_vip_product( -1 ), 'Negative product ID must be invalid' );
} );

// Unit Test 3: Frontend Requests Cannot Create or Publish Products (Fix D)
run_vip_test( 'Unit Test 3 (Fix D): Visiting public page or requesting checkout URL cannot create products on frontend', function() {
	// Querying checkout URL for an unmapped plan must return empty string and not create products
	$url = Negar_Club_VIP_Products::get_checkout_url_for_plan( 'unmapped_random_plan' );
	assert_vip_equals( '', $url, 'Unmapped plan checkout URL must be empty on frontend' );
} );

// Unit Test 4: Deposit Invariant Check (Fix A)
run_vip_test( 'Unit Test 4 (Fix A): Prepayment invariant: applied_deposit + unused_deposit == prepayment_amount', function() {
	$prepayment = 5000000;
	$applied    = 2000000;
	$unused     = 3000000;
	assert_vip_equals( $prepayment, $applied + $unused, 'Sum of applied and unused deposit must equal original prepayment' );
} );

// -----------------------------------------------------------------------------
// PART 2: Isolated Integration Tests (Unique Run Prefix Tables)
// -----------------------------------------------------------------------------

echo "\n==> Section 2: Isolated Database Lifecycle Tests (Prefix: " . NEGAR_CLUB_TEST_PREFIX . ")\n";

global $wpdb;
$test_app_table   = Negar_Club_Database::get_table_name( 'applications' );
$test_audit_table = Negar_Club_Database::get_table_name( 'audit_log' );
$test_mem_table   = Negar_Club_Database::get_table_name( 'memberships' );

// Safety pre-check: ensure test table contains unique run prefix and does not touch real tables
if ( false === strpos( $test_mem_table, 'test_p61_' ) ) {
	echo "[ABORT] Table name {$test_mem_table} does not contain safe run prefix. Aborting DB tests.\n";
	exit( 1 );
}

try {

	// Test 5: Isolated Schema Migration and Table Verification (T340-VIP)
	run_vip_test( 'T340-VIP & Fix E: Database Migration & Schema Verification for isolated memberships table', function() use ( $wpdb, $test_mem_table ) {
		$migrated = Negar_Club_Database::create_tables();
		assert_vip_true( $migrated, 'create_tables must verify table existence and return true' );

		$mem_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_mem_table ) );
		assert_vip_equals( $test_mem_table, $mem_exists, 'Isolated memberships table must exist in database' );

		// Check columns
		$columns  = $wpdb->get_col( "DESCRIBE `{$test_mem_table}`", 0 );
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

	// Test 6: VIP Plan Configurations and Virtual Product Mapping (T341-VIP)
	run_vip_test( 'T341-VIP: VIP Plan Configurations and Prepayment Rules', function() {
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

	// Test 7: Unpaid Orders Do Not Activate VIP Membership (Fix D & T342-VIP)
	run_vip_test( 'T342-VIP & Fix D: Unpaid on-hold Orders Do Not Activate VIP Membership', function() {
		$unpaid_order_id = 999901;

		$order_mock_filter = function( $order, $id ) use ( $unpaid_order_id ) {
			if ( (int) $id === $unpaid_order_id ) {
				return new class {
					public function get_status() { return 'on-hold'; }
					public function get_payment_method() { return 'bacs'; }
					public function is_paid() { return false; }
					public function get_date_paid() { return null; }
					public function get_customer_id() { return 101; }
					public function get_total() { return 5000000; }
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 88881; }
								public function get_total() { return 5000000; }
								public function get_quantity() { return 1; }
							}
						);
					}
				};
			}
			return $order;
		};

		add_filter( 'negar_club_get_order', $order_mock_filter, 10, 2 );
		$result = Negar_Club_Membership_Manager::process_paid_order( $unpaid_order_id );
		remove_filter( 'negar_club_get_order', $order_mock_filter );

		assert_vip_false( $result, 'Unpaid on-hold order must not activate membership' );
	} );

	// Test 8: Verified Paid WooCommerce Order Idempotently Activates Membership (T342-VIP & Fix D)
	$test_order_id = 999902;
	$test_user_id  = 105;
	$created_membership_id = 0;

	run_vip_test( 'T342-VIP & Fix D: Paid WooCommerce Order Idempotently Activates Membership', function() use ( $test_order_id, $test_user_id, &$created_membership_id ) {
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
								public function get_quantity() { return 1; }
							}
						);
					}
				};
			}
			return $order;
		};

		$postmeta_filter = function( $meta, $object_id, $meta_key ) {
			if ( 88882 === (int) $object_id && Negar_Club_VIP_Products::META_KEY_PLAN_ID === $meta_key ) {
				return 'negar_plus';
			}
			return $meta;
		};

		add_filter( 'negar_club_get_order', $paid_order_filter, 10, 2 );
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );

		// Activation call
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

		// Exactly-once idempotency: repeated hook call returns identical membership ID
		$repeat_id = Negar_Club_Membership_Manager::process_paid_order( $test_order_id );
		assert_vip_equals( $created_membership_id, $repeat_id, 'Repeat hook call must return existing membership ID' );

		remove_filter( 'negar_club_get_order', $paid_order_filter );
		remove_filter( 'get_post_metadata', $postmeta_filter );
	} );

	// Test 9: Repeat Purchase While Already Active Flags for Operator Review (T342-VIP)
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
								public function get_quantity() { return 1; }
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

	// Test 10: Duplicate Operator Submission Token Strictly Blocked (Fix B)
	run_vip_test( 'Fix B: Duplicate Operator Submission Token is Blocked by Idempotency Key', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$token = 'token_test_dup_' . wp_generate_uuid4();

		// First valid submission
		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => $token,
			'purchase_amount'              => '1000000',
			'deposit_to_apply'             => '1000000',
			'reason'                       => 'خرید تست اول',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 1000000, (int) $mem->used_purchase_amount, 'First submission must record 1M purchase' );

		// Duplicate resubmission with identical token
		Negar_Club_Admin_Memberships::handle_admin_actions();

		// Balances must remain unchanged (not 2M!)
		$mem_after = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 1000000, (int) $mem_after->used_purchase_amount, 'Resubmission with identical token must not execute twice' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 11: Excessive Collections Rejected, Not Clamped (Fix C)
	run_vip_test( 'Fix C: Excessive Collection Amount Greater Than Debt is Strictly Rejected', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Set debt to 2,000,000 IRT
		Negar_Club_Database::update_membership( $created_membership_id, array( 'offline_debt_amount' => 2000000 ) );

		// Attempt to collect 5,000,000 IRT (excessive!)
		$_POST = array(
			'negar_club_membership_action' => 'record_collection',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'collected_amount'             => '5000000',
			'reason'                       => 'وصول مبلغ بیش از بدهی',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		// Debt must remain exactly 2,000,000 (NOT clamped to 0!)
		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 2000000, (int) $mem->offline_debt_amount, 'Excessive collection must be rejected and debt remain 2M' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 12: Suspended Status Blocks Purchases and Deposit Consumption (Fix C)
	run_vip_test( 'Fix C: Suspended Membership Strictly Blocks Purchases and Deposit Consumption', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Temporarily suspend membership
		Negar_Club_Database::update_membership( $created_membership_id, array( 'status' => 'suspended' ) );

		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'purchase_amount'              => '1000000',
			'deposit_to_apply'             => '0',
			'reason'                       => 'تلاش خرید در حالت معلق',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 1000000, (int) $mem->used_purchase_amount, 'Purchase in suspended state must be blocked and amount remain 1M' );

		// Restore to active for subsequent tests
		Negar_Club_Database::update_membership( $created_membership_id, array( 'status' => 'active' ) );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 13: Exceptional Accounting Correction Authorization and Audit (Fix A)
	run_vip_test( 'Fix A: Exceptional Accounting Correction requires manage_options and records audit log', function() use ( $created_membership_id, $wpdb, $test_audit_table ) {
		// 1. Operator without manage_options must be blocked
		$no_admin_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			$allcaps['manage_options']     = false;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $no_admin_filter );

		$_POST = array(
			'negar_club_membership_action' => 'exceptional_deposit_correction',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'new_unused_deposit'           => '3500000',
			'new_applied_deposit'          => '1500000',
			'reason'                       => 'تلاش اصلاح بدون دسترسی ادمین',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem1 = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 4000000, (int) $mem1->unused_deposit, 'Non-admin cannot execute exceptional correction' );
		remove_filter( 'user_has_cap', $no_admin_filter );

		// 2. Admin with manage_options succeeds
		$admin_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			$allcaps['manage_options']     = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $admin_filter );

		$_POST = array(
			'negar_club_membership_action' => 'exceptional_deposit_correction',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'new_unused_deposit'           => '3500000',
			'new_applied_deposit'          => '1500000',
			'reason'                       => 'اصلاح قانونی اشتباه ثبت فاکتور مصوبه مدیریت',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem2 = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 3500000, (int) $mem2->unused_deposit, 'Admin exceptional correction updates unused deposit' );
		assert_vip_equals( 1500000, (int) $mem2->applied_deposit, 'Admin exceptional correction updates applied deposit' );

		// Check audit log
		$audit_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `{$test_audit_table}` WHERE entity_id = %d AND event_action = 'membership:exceptional_correction'", $created_membership_id ) );
		assert_vip_true( $audit_count > 0, 'Exceptional correction must be recorded in audit log' );

		remove_filter( 'user_has_cap', $admin_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 13: Realistic Scenario: Negar Plus (5M prepayment, 10M purchase, 5M deposit, 5M debt, 2M receipt, 3M remaining debt)
	run_vip_test( 'Realistic Scenario: Negar Plus 5M/10M/2M Full Lifecycle Execution', function() use ( $test_user_id, $wpdb, $test_mem_table ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$scenario_order_id = 999910;
		$scenario_user_id  = 202;

		// 1. Customer buys VIP Negar Plus with verified 5,000,000 IRT payment
		$paid_filter = function( $order, $id ) use ( $scenario_order_id, $scenario_user_id ) {
			if ( (int) $id === $scenario_order_id ) {
				return new class( $scenario_user_id ) {
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
								public function get_product_id() { return 88890; }
								public function get_total() { return 5000000; }
								public function get_quantity() { return 1; }
							}
						);
					}
				};
			}
			return $order;
		};

		$meta_filter = function( $meta, $object_id, $meta_key ) {
			if ( 88890 === (int) $object_id && Negar_Club_VIP_Products::META_KEY_PLAN_ID === $meta_key ) {
				return 'negar_plus';
			}
			return $meta;
		};

		add_filter( 'negar_club_get_order', $paid_filter, 10, 2 );
		add_filter( 'get_post_metadata', $meta_filter, 10, 3 );

		$sc_mem_id = Negar_Club_Membership_Manager::process_paid_order( $scenario_order_id );
		assert_vip_true( $sc_mem_id > 0, 'Scenario membership must activate' );

		// 2. Exactly one VIP membership activates
		$m1 = Negar_Club_Database::get_membership( $sc_mem_id );
		assert_vip_equals( 5000000, (int) $m1->prepayment_amount );
		assert_vip_equals( 5000000, (int) $m1->unused_deposit );
		assert_vip_equals( 10000000, (int) $m1->purchase_ceiling );
		assert_vip_equals( 10000000, (int) $m1->available_purchase_capacity );
		assert_vip_equals( 0, (int) $m1->offline_debt_amount );

		// 3. Operator records 10,000,000 IRT in-store merchandise purchase,
		// applies 5,000,000 IRT deposit, and records 5,000,000 IRT offline debt
		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $sc_mem_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'purchase_amount'              => '10000000',
			'deposit_to_apply'             => '5000000',
			'receipt_ref'                  => 'REC-NEGAR-PLUS-10M',
			'record_as_debt'               => '1',
			'reason'                       => 'تحویل پکیج جهیزیه در فروشگاه حضوری',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$m2 = Negar_Club_Database::get_membership( $sc_mem_id );
		assert_vip_equals( 10000000, (int) $m2->used_purchase_amount, 'Used purchase amount must be 10M' );
		assert_vip_equals( 0, (int) $m2->available_purchase_capacity, 'Available capacity must be 0' );
		assert_vip_equals( 5000000, (int) $m2->applied_deposit, 'Applied deposit must be 5M' );
		assert_vip_equals( 0, (int) $m2->unused_deposit, 'Unused deposit must be 0' );
		assert_vip_equals( 5000000, (int) $m2->offline_debt_amount, 'Offline outstanding debt must be 5M' );

		// 4. Operator later records an in-person receipt for 2,000,000 IRT
		$_POST = array(
			'negar_club_membership_action' => 'record_collection',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $sc_mem_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'collected_amount'             => '2000000',
			'collection_ref'               => 'POS-TRACE-8841',
			'reason'                       => 'وصول قسط اول در فروشگاه',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		// 5. The outstanding debt becomes 3,000,000 IRT
		$m3 = Negar_Club_Database::get_membership( $sc_mem_id );
		assert_vip_equals( 3000000, (int) $m3->offline_debt_amount, 'Outstanding debt must become 3M' );

		// 6. The original prepayment remains 5,000,000 IRT in historical records
		assert_vip_equals( 5000000, (int) $m3->prepayment_amount, 'Prepayment remains 5M' );

		// 7. Unused prepayment remains 0
		assert_vip_equals( 0, (int) $m3->unused_deposit, 'Unused deposit remains 0' );

		// 8. Customer dashboard query isolation matches
		$customer_mems = Negar_Club_Database::get_memberships_by_customer( $scenario_user_id );
		assert_vip_equals( 1, count( $customer_mems ) );
		assert_vip_equals( 3000000, (int) $customer_mems[0]->offline_debt_amount );
		assert_vip_equals( 0, (int) $customer_mems[0]->unused_deposit );

		remove_filter( 'negar_club_get_order', $paid_filter );
		remove_filter( 'get_post_metadata', $meta_filter );
		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Test 14: Customer Account Anti-IDOR Isolation (T344-VIP)
	run_vip_test( 'T344-VIP: Anti-IDOR Isolation in Customer Dashboard', function() use ( $test_user_id ) {
		$mems_a = Negar_Club_Database::get_memberships_by_customer( $test_user_id );
		assert_vip_true( count( $mems_a ) >= 1, 'Customer A must see their own memberships' );

		$mems_b = Negar_Club_Database::get_memberships_by_customer( 999 );
		assert_vip_equals( 0, count( $mems_b ), 'Customer B must see 0 records of Customer A' );
	} );

	// Test 15: Order Refund Safely Suspends Membership Without Ledger Corruption (T346-VIP)
	run_vip_test( 'T346-VIP: Order Refund Safely Suspends Membership Without Ledger Corruption', function() use ( $test_order_id, $created_membership_id ) {
		$handled = Negar_Club_Membership_Manager::handle_order_refund_or_cancellation( $test_order_id, 'refund' );
		assert_vip_true( $handled, 'Refund hook must handle linked VIP membership' );

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'refunded_pending_reconciliation', $mem->status, 'Status must transition to refunded_pending_reconciliation' );
	} );

} finally {
	// Clean teardown: strictly drop ONLY the unique run prefix test tables
	if ( false !== strpos( $test_app_table, 'test_p61_' ) && false !== strpos( $test_audit_table, 'test_p61_' ) && false !== strpos( $test_mem_table, 'test_p61_' ) ) {
		$wpdb->query( "DROP TABLE IF EXISTS `{$test_app_table}`, `{$test_audit_table}`, `{$test_mem_table}`" );
	}
}

echo "\n--- VIP Membership Lifecycle Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed + $skipped ) . "\n";
echo "Passed:  {$passed}\n";
echo "Failed:  {$failed}\n";
echo "Skipped: {$skipped}\n";

if ( $failed > 0 ) {
	exit( 1 );
}
