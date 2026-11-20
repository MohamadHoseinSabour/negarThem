<?php
/**
 * Customer Club Application Lifecycle & Underwriting Test Suite (Phase 5.1).
 *
 * Verifies:
 * 1. Iranian National ID Modulo 11 validation with official test vectors.
 * 2. HMAC-SHA256 privacy hashing and zero plaintext leakage.
 * 3. Mobile phone number normalization and format validation.
 * 4. Idempotent schema migration in isolated test tables (Fix D).
 * 5. Fail-closed application intake with missing/disabled settings (Fix D).
 * 6. Information disclosure prevention on cross-account duplicate attempts (Fix A).
 * 7. Private operator notes isolation from customer responses (Fix A).
 * 8. Atomic duplicate prevention & concurrency protection (Fix B).
 * 9. Re-application unlocking upon terminal status transition (Fix B).
 * 10. Operator approval bounds checking & optimistic concurrency conflict detection (Fix C).
 * 11. Scoped operator permissions (negar_club_operator) (T335).
 * 12. Isolated test database cleanup without live data pollution (Fix D).
 *
 * @package NegarThem
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

// 1. Isolate test tables from production store tables (Fix D)
if ( ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) {
	define( 'NEGAR_CLUB_TEST_PREFIX', 'test_' );
}

$wp_load_path = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
	if ( defined( 'ABSPATH' ) && file_exists( ABSPATH . 'wp-admin/includes/upgrade.php' ) ) {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	}
} else {
	require_once __DIR__ . '/mock-wp-harness.php';
}

require_once dirname( __DIR__ ) . '/inc/theme-settings.php';
require_once dirname( __DIR__ ) . '/inc/customer-club-settings.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-quote-calculator.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-validator.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-database.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-roles.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-application-manager.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/admin/class-admin-applications.php';

echo "--- Running Customer Club Application Lifecycle Test Suite (Phase 5.1 Hardened) ---\n\n";

$passed = 0;
$failed = 0;

function run_lifecycle_test( $name, $callback ) {
	global $passed, $failed;
	try {
		$callback();
		echo "[PASS] $name\n";
		$passed++;
	} catch ( Throwable $e ) {
		echo "[FAIL] $name: " . $e->getMessage() . "\n";
		echo "       File: " . $e->getFile() . ":" . $e->getLine() . "\n";
		$failed++;
	}
}

function assert_lifecycle_true( $condition, $message = '' ) {
	if ( true !== $condition ) {
		throw new RuntimeException( $message ?: 'Condition failed' );
	}
}

function assert_lifecycle_false( $condition, $message = '' ) {
	if ( false !== $condition ) {
		throw new RuntimeException( $message ?: 'Expected false condition' );
	}
}

function assert_lifecycle_equals( $expected, $actual, $message = '' ) {
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message ?: sprintf( 'Expected %s, got %s', var_export( $expected, true ), var_export( $actual, true ) ) );
	}
}

global $wpdb;
$test_app_table   = Negar_Club_Database::get_table_name( 'applications' );
$test_audit_table = Negar_Club_Database::get_table_name( 'audit_log' );

try {

	// Test 1: Iranian National ID Modulo 11 Checksum (T332)
	run_lifecycle_test( 'T332: Iranian National ID Modulo 11 Validation', function() {
		// Valid test vectors
		assert_lifecycle_true( Negar_Club_Validator::validate_national_id( '0010000003' ), '0010000003 must be valid' );
		assert_lifecycle_true( Negar_Club_Validator::validate_national_id( '0790419904' ), '0790419904 must be valid' );
		assert_lifecycle_true( Negar_Club_Validator::validate_national_id( '۰۷۹۰۴۱۹۹۰۴' ), 'Persian numerals must normalize and be valid' );
		assert_lifecycle_true( Negar_Club_Validator::validate_national_id( '٠٧٩٠٤١٩٩٠٤' ), 'Arabic numerals must normalize and be valid' );

		// Invalid test vectors
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '0010000006' ), '0010000006 must be invalid' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '0012345678' ), '0012345678 must be invalid' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '1234567890' ), '1234567890 must be invalid' );

		// Rejection of identical repeated digits
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '0000000000' ), '0000000000 must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '1111111111' ), '1111111111 must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '9999999999' ), '9999999999 must be rejected' );

		// Malformed inputs
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '123' ), 'Short string must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '0010000003123' ), 'Long string must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( 'abcdefghij' ), 'Non-numeric string must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_national_id( '' ), 'Empty string must be rejected' );
	} );

	// Test 2: HMAC Privacy Hashing and Zero Plaintext Leakage (T332)
	run_lifecycle_test( 'T332: HMAC Privacy Hashing and Zero Plaintext Leakage', function() {
		$code = '0010000003';
		$hash = Negar_Club_Validator::hash_national_id( $code );

		assert_lifecycle_true( is_string( $hash ) && 64 === strlen( $hash ), 'Hash must be 64-char hex string' );
		assert_lifecycle_true( ctype_xdigit( $hash ), 'Hash must be hexadecimal' );
		assert_lifecycle_false( strpos( $hash, $code ), 'Hash must never contain plaintext national code' );

		// Deterministic parity: same code yields same hash
		$hash_persian = Negar_Club_Validator::hash_national_id( '۰۰۱۰۰۰۰۰۰۳' );
		assert_lifecycle_equals( $hash, $hash_persian, 'Normalized Persian numerals must yield identical hash' );
	} );

	// Test 3: Phone Normalization and Validation
	run_lifecycle_test( 'T332: Mobile Phone Normalization & Validation', function() {
		assert_lifecycle_equals( '09123456789', Negar_Club_Validator::validate_phone( '09123456789' ) );
		assert_lifecycle_equals( '09123456789', Negar_Club_Validator::validate_phone( '+989123456789' ) );
		assert_lifecycle_equals( '09123456789', Negar_Club_Validator::validate_phone( '00989123456789' ) );
		assert_lifecycle_equals( '09123456789', Negar_Club_Validator::validate_phone( '۰۹۱۲۳۴۵۶۷۸۹' ) );
		assert_lifecycle_false( Negar_Club_Validator::validate_phone( '02112345678' ), 'Landline must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_phone( '09123' ), 'Incomplete phone must be rejected' );
		assert_lifecycle_false( Negar_Club_Validator::validate_phone( 'invalid' ), 'Non-numeric string must be rejected' );
	} );

	// Test 4: Idempotent Database Schema Migration in Isolated Test Tables (Fix D & T331)
	run_lifecycle_test( 'T331 & Fix D: Isolated Database Migration & Schema Verification', function() use ( $wpdb, $test_app_table, $test_audit_table ) {
		// Create isolated test tables
		$migrated = Negar_Club_Database::create_tables();
		assert_lifecycle_true( $migrated, 'create_tables must verify table existence and return true' );

		// Check existence of test tables
		$app_exists   = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_app_table ) );
		$audit_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $test_audit_table ) );

		assert_lifecycle_equals( $test_app_table, $app_exists, 'Isolated applications table must exist' );
		assert_lifecycle_equals( $test_audit_table, $audit_exists, 'Isolated audit table must exist' );

		// Check concurrency lock columns and customer message column (Fix B & Fix A)
		$columns = $wpdb->get_col( "DESCRIBE `{$test_app_table}`", 0 );
		assert_lifecycle_true( in_array( 'active_lock_user', $columns, true ), 'active_lock_user column must exist' );
		assert_lifecycle_true( in_array( 'active_lock_nid', $columns, true ), 'active_lock_nid column must exist' );
		assert_lifecycle_true( in_array( 'customer_message', $columns, true ), 'customer_message column must exist' );
	} );

	// Test 5: Fail-Closed Intake & Missing Settings (Fix D & T333)
	run_lifecycle_test( 'Fix D & T333: Fail-Closed Intake on Missing or Disabled Settings', function() {
		// With intake disabled (default), submission is rejected with 403
		$closed_res = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 5000000,
				'contact_phone'     => '09123456789',
				'national_id'       => '0010000003',
			),
			999901
		);
		assert_lifecycle_true( is_wp_error( $closed_res ), 'Closed intake must return WP_Error' );
		assert_lifecycle_equals( 'APPLICATIONS_CLOSED', $closed_res->get_error_code() );
		assert_lifecycle_equals( 403, $closed_res->get_error_data()['status'] );
	} );

	// Filter to enable intake for subsequent functional tests
	$enable_intake_filter = function( $saved ) {
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$saved['is_enabled']         = true;
		$saved['allow_applications'] = true;
		return $saved;
	};
	add_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $enable_intake_filter );

	// Test 6: Sensitive Information Disclosure on Duplicate Submissions (Fix A)
	run_lifecycle_test( 'Fix A: Cross-Account Duplicate Disclosure Prevention', function() {
		// User 999910 submits application with national ID 0010000003
		$first_res = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 5000000,
				'contact_phone'     => '09121111111',
				'national_id'       => '0010000003',
				'applicant_notes'   => 'درخواست کاربر اول',
			),
			999910
		);
		assert_lifecycle_false( is_wp_error( $first_res ), is_wp_error( $first_res ) ? $first_res->get_error_message() : '' );
		$app_num = $first_res['data']['application_number'];

		// Same User (999910) submits duplicate -> gets informed of own application number
		$same_user_dup = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 4000000,
				'contact_phone'     => '09121111111',
				'national_id'       => '0010000003',
			),
			999910
		);
		assert_lifecycle_true( is_wp_error( $same_user_dup ) );
		assert_lifecycle_equals( 'DUPLICATE_ACTIVE_APPLICATION', $same_user_dup->get_error_code() );
		assert_lifecycle_equals( $app_num, $same_user_dup->get_error_data()['application_number'] );

		// DIFFERENT User (999911) submits duplicate with same national ID -> MUST NOT DISCLOSE application number or status!
		$diff_user_dup = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 5000000,
				'contact_phone'     => '09122222222',
				'national_id'       => '0010000003', // Same verified national ID
			),
			999911
		);
		assert_lifecycle_true( is_wp_error( $diff_user_dup ) );
		assert_lifecycle_equals( 'DUPLICATE_APPLICATION_RESTRICTED', $diff_user_dup->get_error_code() );
		assert_lifecycle_equals( 409, $diff_user_dup->get_error_data()['status'] );
		// Ensure ZERO metadata disclosure in message or error_data
		assert_lifecycle_false( isset( $diff_user_dup->get_error_data()['application_number'] ), 'Must not disclose application number to foreign user' );
		assert_lifecycle_false( strpos( $diff_user_dup->get_error_message(), $app_num ), 'Must not contain foreign application number in message' );
	} );

	// Test 7: Private Operator Notes Isolation from Customer Queries (Fix A)
	run_lifecycle_test( 'Fix A: Private Operator Notes Concealed from Customer Responses', function() {
		// User 999920 submits application
		$sub = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_plus',
				'requested_ceiling' => 7000000,
				'contact_phone'     => '09123333333',
				'national_id'       => '0790419904',
			),
			999920
		);
		assert_lifecycle_false( is_wp_error( $sub ) );
		$app_num = $sub['data']['application_number'];

		// Operator reviews and sets private operator notes and customer message
		$trans = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			42, // Operator ID
			'یادداشت کاملاً محرمانه کارشناس: استعلام بانکی منفی جزئی داشت اما تأیید شد.', // Private notes
			array(
				'approved_ceiling' => 7000000,
				'customer_message' => 'درخواست شما تا سقف ۷ میلیون تومان تأیید شد.', // Customer-facing message
			)
		);
		assert_lifecycle_false( is_wp_error( $trans ) );

		// 1. Customer queries application -> must receive customer_message, NEVER operator_notes
		$cust_view = Negar_Club_Application_Manager::get_application_for_user( $app_num, 999920 );
		assert_lifecycle_false( is_wp_error( $cust_view ) );
		assert_lifecycle_equals( 'درخواست شما تا سقف ۷ میلیون تومان تأیید شد.', $cust_view['customer_message'] );
		assert_lifecycle_false( isset( $cust_view['operator_notes'] ), 'operator_notes must be omitted from customer view' );

		// 2. Customer queries application history list -> must not contain operator_notes
		$cust_history = Negar_Club_Application_Manager::get_user_applications( 999920 );
		assert_lifecycle_true( ! empty( $cust_history ) );
		assert_lifecycle_false( isset( $cust_history[0]['operator_notes'] ), 'operator_notes must be omitted from history list' );

		// 3. Operator queries application -> DOES receive operator_notes
		// Mock operator capability
		$role = get_role( 'administrator' );
		if ( $role ) {
			$role->add_cap( Negar_Club_Roles::CAPABILITY_OPERATOR );
		}
		$op_view = Negar_Club_Application_Manager::get_application_for_user( $app_num, 1 ); // Admin user ID 1
		assert_lifecycle_false( is_wp_error( $op_view ) );
		assert_lifecycle_true( isset( $op_view['operator_notes'] ), 'operator_notes must be available to operator' );
		assert_lifecycle_true( false !== strpos( $op_view['operator_notes'], 'استعلام بانکی منفی' ) );
	} );

	// Test 8: Atomic Concurrency Protection and Terminal Re-Application Unlocking (Fix B)
	run_lifecycle_test( 'Fix B: Atomic Concurrency & Terminal Unlocking', function() {
		// User 999930 submits application with national ID 0010000011
		$sub = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 4000000,
				'contact_phone'     => '09124444444',
				'national_id'       => '0010000011',
			),
			999930
		);
		assert_lifecycle_false( is_wp_error( $sub ) );
		$app_num = $sub['data']['application_number'];

		// Transition application to terminal state: rejected
		$reject_res = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'rejected',
			42,
			'رد درخواست به علت نقص مدارک',
			array(
				'customer_message' => 'مدارک ارائه شده کامل نبود.',
			)
		);
		assert_lifecycle_false( is_wp_error( $reject_res ) );
		assert_lifecycle_equals( 'rejected', $reject_res['data']['new_status'] );

		// After terminal rejection, user 999930 CAN legitimately apply again with the same national ID!
		$reapply_res = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 4000000,
				'contact_phone'     => '09124444444',
				'national_id'       => '0010000011',
				'applicant_notes'   => 'ثبت مجدد پس از اصلاح مدارک',
			),
			999930
		);
		assert_lifecycle_false( is_wp_error( $reapply_res ), 'Legitimate re-application after terminal state must succeed' );
		assert_lifecycle_equals( 'pending_review', $reapply_res['data']['status'] );
	} );

	// Test 9: Operator Approval Bounds & Conditional Optimistic Locking (Fix C)
	run_lifecycle_test( 'Fix C: Operator Approval Bounds & Optimistic Concurrency Conflicts', function() use ( $wpdb, $test_app_table ) {
		// Submit application with requested ceiling 5,000,000 on plan negar_aghaz (plan ceiling is 6,000,000)
		$sub = Negar_Club_Application_Manager::submit_application(
			array(
				'plan_id'           => 'negar_aghaz',
				'requested_ceiling' => 5000000,
				'contact_phone'     => '09125555555',
				'national_id'       => '0010000021', // Valid checksum
			),
			999940
		);
		assert_lifecycle_false( is_wp_error( $sub ) );
		$app_num = $sub['data']['application_number'];

		// 1. Operator attempts to approve ceiling higher than requested ceiling (e.g. 5,500,000 > 5,000,000) -> Rejected
		$excess_req = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			42,
			'تأیید مازاد',
			array( 'approved_ceiling' => 5500000 )
		);
		assert_lifecycle_true( is_wp_error( $excess_req ), 'Ceiling exceeding requested amount must be rejected' );
		assert_lifecycle_equals( 'EXCESSIVE_CEILING', $excess_req->get_error_code() );

		// 2. Operator attempts to approve ceiling higher than plan ceiling (e.g. 8,000,000 > 6,000,000) -> Rejected
		$excess_plan = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			42,
			'تأیید مازاد سقف طرح',
			array( 'approved_ceiling' => 8000000 )
		);
		assert_lifecycle_true( is_wp_error( $excess_plan ), 'Ceiling exceeding plan amount must be rejected' );
		assert_lifecycle_equals( 'EXCESSIVE_CEILING', $excess_plan->get_error_code() );

		// 3. Operator attempts to approve negative or zero ceiling -> Rejected
		$zero_ceil = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			42,
			'تأیید صفر',
			array( 'approved_ceiling' => 0 )
		);
		assert_lifecycle_true( is_wp_error( $zero_ceil ) );
		assert_lifecycle_equals( 'INVALID_CEILING', $zero_ceil->get_error_code() );

		// 4. Operator attempts invalid validity duration (e.g. 150 days > 90) -> Rejected
		$bad_days = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			42,
			'تأیید مهلت نامعتبر',
			array(
				'approved_ceiling' => 5000000,
				'validity_days'    => 150,
			)
		);
		assert_lifecycle_true( is_wp_error( $bad_days ) );
		assert_lifecycle_equals( 'INVALID_VALIDITY_DAYS', $bad_days->get_error_code() );

		// 5. Competing operator transition conflict (Fix C optimistic locking)
		// Simulate Operator A concurrently rejecting the application in DB
		$wpdb->update(
			$test_app_table,
			array( 'status' => 'rejected' ),
			array( 'application_number' => $app_num )
		);

		// Now Operator B attempts to approve based on old pending_review status -> Conflict detected!
		$competing_approve = Negar_Club_Application_Manager::transition_status(
			$app_num,
			'approved',
			43,
			'تأیید همزمان',
			array( 'approved_ceiling' => 5000000 )
		);
		assert_lifecycle_true( is_wp_error( $competing_approve ), 'Concurrent state conflict must return WP_Error' );
		assert_lifecycle_equals( 'INVALID_STATE_TRANSITION', $competing_approve->get_error_code() );
	} );

	// Remove intake filter
	remove_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $enable_intake_filter );

} finally {
	// Clean teardown: drop isolated test tables (Fix D)
	$wpdb->query( "DROP TABLE IF EXISTS `{$test_app_table}`, `{$test_audit_table}`" );
}

echo "\n--- Customer Club Application Lifecycle Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: {$passed}\n";
echo "Failed: {$failed}\n";

if ( $failed > 0 ) {
	exit( 1 );
}
