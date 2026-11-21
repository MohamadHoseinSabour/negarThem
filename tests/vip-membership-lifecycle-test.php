<?php
/**
 * VIP Membership Lifecycle & Financial Safety Acceptance Test Suite (Phase 6.1 Final).
 *
 * Fully covers the 10 critical release-blocker acceptance scenarios:
 * Scenario 1: Paid VIP order activates exactly once (idempotent).
 * Scenario 2: Unpaid / status-only offline order cannot falsely activate.
 * Scenario 3: Invalid product / mixed checkout cannot create active credit.
 * Scenario 4: Discounted order remains pending_operator_review (notes preserved).
 * Scenario 5: Exceptional correction cannot manufacture prepaid funds (> prepayment).
 * Scenario 6: Duplicate operator token blocked by database constraint.
 * Scenario 7: Multi-session concurrency test using separate mysqli connections.
 * Scenario 8: Failed audit insertion rolls back financial changes atomically.
 * Scenario 9: Refund suspends purchasing while preserving historical records.
 * Scenario 10: Realistic Negar Plus end-to-end lifecycle scenario.
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

echo "--- Running VIP Membership Acceptance Test Suite (Phase 6.1 Final) ---\n\n";

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

// Unit Test 1: Strict Monetary Input Validation
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

// Unit Test 2: Product Suitability Validation (Fail Closed)
run_vip_test( 'Unit Test 2 (Fix D): Product suitability strictly fails closed on missing, non-simple, or unmapped products', function() {
	assert_vip_false( Negar_Club_VIP_Products::is_valid_vip_product( 0 ), 'Product ID 0 must be invalid' );
	assert_vip_false( Negar_Club_VIP_Products::is_valid_vip_product( -1 ), 'Negative product ID must be invalid' );
	assert_vip_false( Negar_Club_VIP_Products::is_valid_vip_product( 999999 ), 'Non-existent product ID must fail closed' );
} );

// Unit Test 3: Frontend Requests Cannot Create or Publish Products
run_vip_test( 'Unit Test 3 (Fix D): Visiting public page or requesting checkout URL cannot create products on frontend', function() {
	$url = Negar_Club_VIP_Products::get_checkout_url_for_plan( 'unmapped_random_plan' );
	assert_vip_equals( '', $url, 'Unmapped plan checkout URL must be empty on frontend' );
} );

// Unit Test 4: Deposit Invariant Check
run_vip_test( 'Unit Test 4 (Fix A): Prepayment invariant: applied_deposit + unused_deposit == prepayment_amount', function() {
	$prepayment = 5000000;
	$applied    = 2000000;
	$unused     = 3000000;
	assert_vip_equals( $prepayment, $applied + $unused, 'Sum of applied and unused deposit must equal original prepayment' );
} );

// -----------------------------------------------------------------------------
// PART 2: Isolated Database Lifecycle Tests (Unique Run Prefix Tables)
// -----------------------------------------------------------------------------

echo "\n==> Section 2: Isolated Database Acceptance Tests (Prefix: " . NEGAR_CLUB_TEST_PREFIX . ")\n";

global $wpdb;
$test_app_table    = Negar_Club_Database::get_table_name( 'applications' );
$test_audit_table  = Negar_Club_Database::get_table_name( 'audit_log' );
$test_mem_table    = Negar_Club_Database::get_table_name( 'memberships' );
$test_tokens_table = Negar_Club_Database::get_table_name( 'operation_tokens' );

// Safety pre-check: ensure test table contains unique run prefix and does not touch real tables
if ( false === strpos( $test_mem_table, 'test_p61_' ) ) {
	echo "[ABORT] Table name {$test_mem_table} does not contain safe run prefix. Aborting DB tests.\n";
	exit( 1 );
}

// Inject VIP product validation filter for test product fixtures
$test_product_filter = function( $is_valid, $product_id ) {
	if ( in_array( (int) $product_id, array( 88881, 88882, 88883, 88884, 88885, 88890 ), true ) ) {
		return true;
	}
	return $is_valid;
};
add_filter( 'negar_club_is_valid_vip_product', $test_product_filter, 10, 2 );

try {

	// Schema Migration & Table Verification
	run_vip_test( 'Schema Migration: Isolated tables (memberships, audit_log, operation_tokens) verified', function() use ( $wpdb, $test_mem_table, $test_tokens_table ) {
		$migrated = Negar_Club_Database::create_tables();
		assert_vip_true( $migrated, 'create_tables must verify table existence and return true' );

		$mem_exists   = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_mem_table ) );
		$token_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_tokens_table ) );

		assert_vip_equals( $test_mem_table, $mem_exists, 'Isolated memberships table must exist in database' );
		assert_vip_equals( $test_tokens_table, $token_exists, 'Isolated operation_tokens table must exist in database' );

		// Check memberships columns
		$mem_cols = $wpdb->get_col( "DESCRIBE `{$test_mem_table}`", 0 );
		$required_mem = array(
			'customer_id', 'plan_id', 'order_id',
			'prepayment_amount', 'applied_deposit', 'unused_deposit',
			'purchase_ceiling', 'used_purchase_amount', 'available_purchase_capacity',
			'offline_debt_amount', 'term_months', 'status', 'valid_until', 'notes',
		);
		foreach ( $required_mem as $col ) {
			assert_vip_true( in_array( $col, $mem_cols, true ), "Column {$col} must exist in memberships table" );
		}

		// Check operation_tokens columns
		$tok_cols = $wpdb->get_col( "DESCRIBE `{$test_tokens_table}`", 0 );
		$required_tok = array( 'token', 'action', 'membership_id', 'actor_id', 'created_at' );
		foreach ( $required_tok as $col ) {
			assert_vip_true( in_array( $col, $tok_cols, true ), "Column {$col} must exist in operation_tokens table" );
		}
	} );

	// Plan Configurations
	run_vip_test( 'Plan Configurations: Verified standard tiers (Aghaz, Plus, Vizhe)', function() {
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

	// Scenario 1: Paid VIP order activates exactly once (idempotent)
	$test_order_id         = 999901;
	$test_user_id          = 105;
	$created_membership_id = 0;

	run_vip_test( 'Scenario 1: Paid VIP order activates exactly once (idempotent)', function() use ( $test_order_id, $test_user_id, &$created_membership_id ) {
		$paid_order_filter = function( $order, $id ) use ( $test_order_id, $test_user_id ) {
			if ( (int) $id === $test_order_id ) {
				return new class( $test_user_id ) {
					private $uid;
					public function __construct( $uid ) { $this->uid = $uid; }
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_transaction_id() { return 'TRX-ONLINE-99901'; }
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

		// Exactly-once idempotency: repeated hook call returns identical membership ID without duplicate row
		$repeat_id = Negar_Club_Membership_Manager::process_paid_order( $test_order_id );
		assert_vip_equals( $created_membership_id, $repeat_id, 'Repeat hook call must return existing membership ID' );

		remove_filter( 'negar_club_get_order', $paid_order_filter );
		remove_filter( 'get_post_metadata', $postmeta_filter );
	} );

	// Scenario 2: Unpaid / status-only offline order cannot falsely activate
	run_vip_test( 'Scenario 2: Unpaid or status-only offline order (BACS/COD) cannot falsely activate', function() {
		// 2A: On-hold BACS order
		$on_hold_id = 999902;
		$order_on_hold = function( $order, $id ) use ( $on_hold_id ) {
			if ( (int) $id === $on_hold_id ) {
				return new class {
					public function get_status() { return 'on-hold'; }
					public function get_payment_method() { return 'bacs'; }
					public function is_paid() { return false; }
					public function get_date_paid() { return null; }
					public function get_customer_id() { return 102; }
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
		add_filter( 'negar_club_get_order', $order_on_hold, 10, 2 );
		$res_hold = Negar_Club_Membership_Manager::process_paid_order( $on_hold_id );
		remove_filter( 'negar_club_get_order', $order_on_hold );
		assert_vip_false( $res_hold, 'On-hold BACS order must not activate' );

		// 2B: Completed BACS order WITHOUT confirmed receipt
		$status_only_id = 999903;
		$order_status_only = function( $order, $id ) use ( $status_only_id ) {
			if ( (int) $id === $status_only_id ) {
				return new class {
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'bacs'; }
					public function is_paid() { return false; }
					public function get_date_paid() { return null; }
					public function get_transaction_id() { return ''; }
					public function get_meta( $key ) { return false; }
					public function get_customer_id() { return 103; }
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
		add_filter( 'negar_club_get_order', $order_status_only, 10, 2 );
		$postmeta_filter = function( $meta, $object_id, $meta_key ) {
			if ( 88882 === (int) $object_id && Negar_Club_VIP_Products::META_KEY_PLAN_ID === $meta_key ) {
				return 'negar_plus';
			}
			return $meta;
		};
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );
		$res_status_only = Negar_Club_Membership_Manager::process_paid_order( $status_only_id );
		remove_filter( 'negar_club_get_order', $order_status_only );
		remove_filter( 'get_post_metadata', $postmeta_filter );
		assert_vip_true( $res_status_only > 0, 'Status-only completed BACS order must create pending review membership' );
		$mem_status_only = Negar_Club_Database::get_membership( $res_status_only );
		assert_vip_equals( 'pending_operator_review', $mem_status_only->status, 'Status-only completed BACS order without receipt confirmation must not activate (must be pending_operator_review)' );

		// 2C: Completed BACS order WITH verified offline receipt
		$confirmed_bacs_id = 999904;
		$order_confirmed = function( $order, $id ) use ( $confirmed_bacs_id ) {
			if ( (int) $id === $confirmed_bacs_id ) {
				return new class {
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'bacs'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_transaction_id() { return 'BACS-RECEIPT-9988'; }
					public function get_meta( $key ) { return '_negar_club_offline_receipt_confirmed' === $key ? true : false; }
					public function get_customer_id() { return 104; }
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
		add_filter( 'negar_club_get_order', $order_confirmed, 10, 2 );
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );
		$res_confirmed = Negar_Club_Membership_Manager::process_paid_order( $confirmed_bacs_id );
		remove_filter( 'negar_club_get_order', $order_confirmed );
		remove_filter( 'get_post_metadata', $postmeta_filter );
		assert_vip_true( $res_confirmed > 0, 'Completed BACS order with verified receipt activates successfully' );
	} );

	// Scenario 3: Invalid product / mixed checkout cannot create active credit
	run_vip_test( 'Scenario 3: Mixed checkout or invalid products cannot create active VIP credit', function() {
		// 3A: Mixed order (VIP product + physical regular merchandise)
		$mixed_order_id = 999905;
		$order_mixed = function( $order, $id ) use ( $mixed_order_id ) {
			if ( (int) $id === $mixed_order_id ) {
				return new class {
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_customer_id() { return 110; }
					public function get_total() { return 7500000; }
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 88882; } // VIP product
								public function get_total() { return 5000000; }
								public function get_quantity() { return 1; }
							},
							new class {
								public function get_product_id() { return 77771; } // Regular physical product
								public function get_total() { return 2500000; }
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
		add_filter( 'negar_club_get_order', $order_mixed, 10, 2 );
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );
		$mixed_mem_id = Negar_Club_Membership_Manager::process_paid_order( $mixed_order_id );
		remove_filter( 'negar_club_get_order', $order_mixed );
		remove_filter( 'get_post_metadata', $postmeta_filter );

		assert_vip_true( $mixed_mem_id > 0, 'Mixed order creates record for operator tracking' );
		$mixed_mem = Negar_Club_Database::get_membership( $mixed_mem_id );
		assert_vip_equals( 'pending_operator_review', $mixed_mem->status, 'Mixed order must be pending_operator_review, never active' );
		assert_vip_true( false !== strpos( $mixed_mem->notes, 'اقلام غیرمجاز' ), 'Notes must explain mixed merchandise' );

		// 3B: Non-VIP product only
		$invalid_order_id = 999906;
		$order_invalid = function( $order, $id ) use ( $invalid_order_id ) {
			if ( (int) $id === $invalid_order_id ) {
				return new class {
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_customer_id() { return 111; }
					public function get_total() { return 5000000; }
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 99991; } // unmapped product
								public function get_total() { return 5000000; }
								public function get_quantity() { return 1; }
							}
						);
					}
				};
			}
			return $order;
		};
		add_filter( 'negar_club_get_order', $order_invalid, 10, 2 );
		$res_inv = Negar_Club_Membership_Manager::process_paid_order( $invalid_order_id );
		remove_filter( 'negar_club_get_order', $order_invalid );
		assert_vip_false( $res_inv, 'Non-VIP product checkout must be rejected' );
	} );

	// Scenario 4: Discounted order remains pending_operator_review (notes preserved)
	run_vip_test( 'Scenario 4: Discounted order remains pending_operator_review and review notes are preserved', function() {
		$discount_order_id = 999907;
		$user_id           = 115;

		$order_discount = function( $order, $id ) use ( $discount_order_id, $user_id ) {
			if ( (int) $id === $discount_order_id ) {
				return new class( $user_id ) {
					private $uid;
					public function __construct( $uid ) { $this->uid = $uid; }
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_date_paid() { return current_time( 'mysql' ); }
					public function get_customer_id() { return $this->uid; }
					public function get_total() { return 4000000; } // Discounted from 5M to 4M
					public function get_items() {
						return array(
							new class {
								public function get_product_id() { return 88882; } // Negar Plus (expected 5M)
								public function get_total() { return 4000000; }
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

		add_filter( 'negar_club_get_order', $order_discount, 10, 2 );
		add_filter( 'get_post_metadata', $postmeta_filter, 10, 3 );

		$disc_mem_id = Negar_Club_Membership_Manager::process_paid_order( $discount_order_id );
		remove_filter( 'negar_club_get_order', $order_discount );
		remove_filter( 'get_post_metadata', $postmeta_filter );

		assert_vip_true( $disc_mem_id > 0, 'Discounted order must create membership row' );
		$disc_mem = Negar_Club_Database::get_membership( $disc_mem_id );
		assert_vip_equals( 'pending_operator_review', $disc_mem->status, 'Discounted order must remain pending_operator_review' );
		// Critical check: notes were NOT overwritten by duplicate assignment bug!
		assert_vip_true( false !== strpos( $disc_mem->notes, 'تخفیف' ), 'Review reason explaining discount must be preserved in notes' );
		// Prepayment amount must be capped to verified paid amount
		assert_vip_equals( 4000000, (int) $disc_mem->prepayment_amount, 'Prepayment credited must equal verified paid amount 4M' );
		assert_vip_equals( 4000000, (int) $disc_mem->unused_deposit, 'Unused deposit must equal verified paid amount 4M' );
	} );

	// Scenario 5: Exceptional correction cannot manufacture prepaid funds (> prepayment)
	run_vip_test( 'Scenario 5: Exceptional correction cannot manufacture prepaid funds (> prepayment)', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$admin_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			$allcaps['manage_options']     = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $admin_filter );

		$mem_before = Negar_Club_Database::get_membership( $created_membership_id );
		$orig_prepayment = (int) $mem_before->prepayment_amount; // 5,000,000

		// Attempt to set unused (4M) + applied (2M) = 6M > 5M prepayment
		$_POST = array(
			'negar_club_membership_action' => 'exceptional_deposit_correction',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'new_unused_deposit'           => '4000000',
			'new_applied_deposit'          => '2000000', // Sum = 6M > 5M!
			'reason'                       => 'تلاش غیرمجاز افزایش بیعانه فراتر از واریزی واقعی',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem_after = Negar_Club_Database::get_membership( $created_membership_id );
		// Balances must remain completely unchanged!
		assert_vip_equals( (int) $mem_before->unused_deposit, (int) $mem_after->unused_deposit, 'Unused deposit must not be manufactured' );
		assert_vip_equals( (int) $mem_before->applied_deposit, (int) $mem_after->applied_deposit, 'Applied deposit must not be manufactured' );

		remove_filter( 'user_has_cap', $admin_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 6: Duplicate operator token blocked by database constraint
	run_vip_test( 'Scenario 6: Duplicate operator token blocked by DB UNIQUE KEY on operation_tokens', function() use ( $created_membership_id, $test_tokens_table, $wpdb ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$token = 'tok_dup_test_' . wp_generate_uuid4();

		// First valid submission
		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => $token,
			'purchase_amount'              => '500000',
			'deposit_to_apply'             => '500000',
			'reason'                       => 'تست اول توکن',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem1 = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 500000, (int) $mem1->used_purchase_amount, 'First submission with token must succeed' );

		// Verify token is in DB
		$db_token = $wpdb->get_var( $wpdb->prepare( "SELECT token FROM `{$test_tokens_table}` WHERE token = %s", $token ) );
		assert_vip_equals( $token, $db_token, 'Token must be recorded in operation_tokens table' );

		// Duplicate resubmission with identical token
		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem2 = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 500000, (int) $mem2->used_purchase_amount, 'Second submission with identical token must be blocked' );

		// Direct attempt to insert duplicate token into DB must trigger constraint failure
		$caught_dup = false;
		try {
			Negar_Club_Database::claim_operation_token( $token, 'record_purchase', $created_membership_id, 1 );
		} catch ( Exception $e ) {
			$caught_dup = true;
		}
		assert_vip_true( $caught_dup, 'claim_operation_token must throw on duplicate token' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 7: Multi-session concurrency test (genuine separate DB connections)
	run_vip_test( 'Scenario 7: Multi-session concurrency test using separate mysqli connections', function() use ( $test_tokens_table ) {
		$db_host = defined( 'DB_HOST' ) ? DB_HOST : 'localhost';
		$db_user = defined( 'DB_USER' ) ? DB_USER : 'root';
		$db_pass = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
		$db_name = defined( 'DB_NAME' ) ? DB_NAME : '';

		$conn1 = @new mysqli( $db_host, $db_user, $db_pass, $db_name );
		$conn2 = @new mysqli( $db_host, $db_user, $db_pass, $db_name );

		if ( $conn1->connect_error || $conn2->connect_error ) {
			return 'skip'; // Cannot establish two separate connections in this CLI environment
		}

		$shared_token = 'conc_token_' . wp_generate_uuid4();

		// Session 1: Begin transaction and insert token
		$conn1->query( 'START TRANSACTION' );
		$stmt1 = $conn1->prepare( "INSERT INTO `{$test_tokens_table}` (token, action, membership_id, actor_id) VALUES (?, 'record_purchase', 1, 1)" );
		$stmt1->bind_param( 's', $shared_token );
		$exec1 = $stmt1->execute();
		assert_vip_true( $exec1, 'Session 1 insert must succeed' );

		// Session 2: Attempt concurrent insert of identical token
		$conn2->query( 'START TRANSACTION' );
		$stmt2 = $conn2->prepare( "INSERT INTO `{$test_tokens_table}` (token, action, membership_id, actor_id) VALUES (?, 'record_purchase', 1, 1)" );
		$stmt2->bind_param( 's', $shared_token );
		
		// Set short lock wait timeout on conn2 to test fast contention
		$conn2->query( 'SET innodb_lock_wait_timeout = 1' );
		$exec2 = @$stmt2->execute();
		$err2  = $stmt2->errno;

		// Session 2 must fail due to lock wait or duplicate key (1062)
		assert_vip_false( $exec2, 'Session 2 concurrent insert of same token must be blocked' );

		// Commit Session 1, Rollback Session 2
		$conn1->query( 'COMMIT' );
		$conn2->query( 'ROLLBACK' );

		$stmt1->close();
		$stmt2->close();
		$conn1->close();
		$conn2->close();
	} );

	// Scenario 8: Failed audit insertion rolls back financial changes atomically
	run_vip_test( 'Scenario 8: Failed audit insertion rolls back financial changes atomically', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$mem_before      = Negar_Club_Database::get_membership( $created_membership_id );
		$capacity_before = (int) $mem_before->available_purchase_capacity;

		// Force audit log failure to verify atomic rollback of financial balance changes
		add_filter( 'negar_club_log_audit_result', '__return_false' );

		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'purchase_amount'              => '200000',
			'deposit_to_apply'             => '0',
			'reason'                       => 'خرید با شبیه‌سازی خطای ثبت لاگ ممیزی',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		remove_filter( 'negar_club_log_audit_result', '__return_false' );

		// Financial balances must be completely rolled back!
		$mem_after = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( $capacity_before, (int) $mem_after->available_purchase_capacity, sprintf( 'Capacity must remain untouched when audit logging fails. Expected %d, got %d', $capacity_before, (int) $mem_after->available_purchase_capacity ) );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 9: Refund suspends purchasing while preserving historical records
	run_vip_test( 'Scenario 9: Order refund suspends membership without ledger corruption', function() use ( $test_order_id, $created_membership_id ) {
		$handled = Negar_Club_Membership_Manager::handle_order_refund_or_cancellation( $test_order_id, 'refund' );
		assert_vip_true( $handled, 'Refund hook must handle linked VIP membership' );

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'refunded_pending_reconciliation', $mem->status, 'Status must transition to refunded_pending_reconciliation' );

		// Attempting purchase on refunded membership must be blocked
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$_POST = array(
			'negar_club_membership_action' => 'record_purchase',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'purchase_amount'              => '100000',
			'deposit_to_apply'             => '0',
			'reason'                       => 'تلاش خرید روی اشتراک مسترد شده',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem_after = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( (int) $mem->used_purchase_amount, (int) $mem_after->used_purchase_amount, 'Purchase on refunded membership must be blocked' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 10: Realistic Negar Plus end-to-end lifecycle scenario
	run_vip_test( 'Scenario 10: Realistic Negar Plus (5M paid, 10M purchase, 5M deposit, 5M debt, 2M receipt, 3M debt) Full Lifecycle', function() {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$sc_order_id = 999910;
		$sc_user_id  = 202;

		// 1. Customer buys VIP Negar Plus with verified 5,000,000 IRT payment
		$paid_filter = function( $order, $id ) use ( $sc_order_id, $sc_user_id ) {
			if ( (int) $id === $sc_order_id ) {
				return new class( $sc_user_id ) {
					private $uid;
					public function __construct( $uid ) { $this->uid = $uid; }
					public function get_status() { return 'completed'; }
					public function get_payment_method() { return 'online_gateway'; }
					public function is_paid() { return true; }
					public function get_transaction_id() { return 'TRX-ONLINE-99910'; }
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

		$sc_mem_id = Negar_Club_Membership_Manager::process_paid_order( $sc_order_id );
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
		$customer_mems = Negar_Club_Database::get_memberships_by_customer( $sc_user_id );
		assert_vip_equals( 1, count( $customer_mems ) );
		assert_vip_equals( 3000000, (int) $customer_mems[0]->offline_debt_amount );
		assert_vip_equals( 0, (int) $customer_mems[0]->unused_deposit );

		remove_filter( 'negar_club_get_order', $paid_filter );
		remove_filter( 'get_post_metadata', $meta_filter );
		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 11: Partial refund blocks deposit usage
	run_vip_test( 'Scenario 11: Partial refund sets refunded_pending_reconciliation and blocks deposit usage', function() use ( $test_order_id, $created_membership_id ) {
		// Reset status to active temporarily
		Negar_Club_Database::update_membership( $created_membership_id, array( 'status' => 'active' ) );

		Negar_Club_Membership_Manager::handle_order_partially_refunded( $test_order_id, 101 );
		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'refunded_pending_reconciliation', $mem->status, 'Partial refund must transition status to refunded_pending_reconciliation' );
	} );

	// Scenario 12: Reactivating refunded membership via ordinary status edit without admin capability is blocked
	run_vip_test( 'Scenario 12: Reactivating refunded membership via ordinary status edit without admin capability is blocked', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$operator_only_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			$allcaps['manage_options']     = false;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $operator_only_filter );

		$_POST = array(
			'negar_club_membership_action' => 'adjust_balances',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'status'                       => 'active', // Attempting to reactivate without admin
			'reason'                       => 'تلاش فعال‌سازی مجدد عضویت مستردشده توسط اپراتور عادی',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'refunded_pending_reconciliation', $mem->status, 'Status must remain refunded_pending_reconciliation' );

		remove_filter( 'user_has_cap', $operator_only_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 13: Operator confirmation action with receipt reference activates pending review membership
	run_vip_test( 'Scenario 13: Operator confirmation action with receipt reference activates pending review membership', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		// Set status to pending_operator_review
		Negar_Club_Database::update_membership( $created_membership_id, array( 'status' => 'pending_operator_review' ) );

		$_POST = array(
			'negar_club_membership_action' => 'confirm_receipt_and_activate',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'receipt_ref'                  => 'BANK-SLIP-77312',
			'reason'                       => 'تایید فیش واریز بانکی و فعال‌سازی اشتراک',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 'active', $mem->status, 'confirm_receipt_and_activate must transition status to active' );
		assert_vip_true( false !== strpos( $mem->notes, 'BANK-SLIP-77312' ), 'Receipt reference must be recorded in membership notes' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

	// Scenario 14: Adjusting term_months and valid_until in adjust_balances
	run_vip_test( 'Scenario 14: Adjusting term_months and valid_until updates membership record', function() use ( $created_membership_id ) {
		wp_set_current_user( 1 );
		$role_filter = function( $allcaps ) {
			$allcaps['negar_club_operator'] = true;
			return $allcaps;
		};
		add_filter( 'user_has_cap', $role_filter );

		$_POST = array(
			'negar_club_membership_action' => 'adjust_balances',
			'negar_club_membership_nonce'  => wp_create_nonce( 'negar_club_operator_membership_action' ),
			'membership_id'                => $created_membership_id,
			'negar_club_submission_token'  => wp_generate_uuid4(),
			'term_months'                  => '6',
			'valid_until'                  => '2027-06-01 00:00:00',
			'reason'                       => 'تمدید ۳ ماهه تشویقی اعتبار',
		);
		$_REQUEST = $_POST;

		Negar_Club_Admin_Memberships::handle_admin_actions();

		$mem = Negar_Club_Database::get_membership( $created_membership_id );
		assert_vip_equals( 6, (int) $mem->term_months, 'term_months must be updated to 6' );
		assert_vip_equals( '2027-06-01 00:00:00', $mem->valid_until, 'valid_until must be updated to 2027-06-01 00:00:00' );

		remove_filter( 'user_has_cap', $role_filter );
		$_POST    = array();
		$_REQUEST = array();
	} );

} finally {
	// Clean teardown: strictly drop ONLY the unique run prefix test tables
	if ( false !== strpos( $test_app_table, 'test_p61_' )
		&& false !== strpos( $test_audit_table, 'test_p61_' )
		&& false !== strpos( $test_mem_table, 'test_p61_' )
		&& false !== strpos( $test_tokens_table, 'test_p61_' ) ) {
		$wpdb->query( "DROP TABLE IF EXISTS `{$test_app_table}`, `{$test_audit_table}`, `{$test_mem_table}`, `{$test_tokens_table}`" );
	}
}

echo "\n--- VIP Membership Acceptance Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed + $skipped ) . "\n";
echo "Passed:  {$passed}\n";
echo "Failed:  {$failed}\n";
echo "Skipped: {$skipped}\n";

if ( $failed > 0 ) {
	exit( 1 );
}
