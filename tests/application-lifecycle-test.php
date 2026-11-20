<?php
/**
 * Customer Club Application Lifecycle & Underwriting Test Suite.
 *
 * Verifies:
 * 1. Iranian National ID Modulo 11 validation with official test vectors.
 * 2. HMAC-SHA256 privacy hashing and zero plaintext leakage.
 * 3. Mobile phone number normalization and format validation.
 * 4. Idempotent database schema migration (wp_negar_club_applications & wp_negar_club_audit_log).
 * 5. Application submission validation, bounds checking, and duplicate prevention.
 * 6. Application state machine transitions and audit trail logging.
 * 7. Anti-IDOR customer data isolation.
 * 8. Scoped operator permissions (negar_club_operator).
 *
 * All created test records are strictly cleaned up on exit.
 *
 * @package NegarThem
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
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

echo "--- Running Customer Club Application Lifecycle Test Suite ---\n\n";

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

// Track IDs for clean teardown
$created_application_ids = array();
$created_audit_ids       = array();

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

// Test 4: Idempotent Database Schema Migration (T331)
run_lifecycle_test( 'T331: Idempotent Database Schema Migration', function() {
	global $wpdb;

	// Run migration twice to verify strict idempotency
	Negar_Club_Database::create_tables();
	Negar_Club_Database::create_tables();

	$app_table   = $wpdb->prefix . 'negar_club_applications';
	$audit_table = $wpdb->prefix . 'negar_club_audit_log';

	$app_exists   = $wpdb->get_var( "SHOW TABLES LIKE '{$app_table}'" );
	$audit_exists = $wpdb->get_var( "SHOW TABLES LIKE '{$audit_table}'" );

	assert_lifecycle_equals( $app_table, $app_exists, 'Applications table must exist' );
	assert_lifecycle_equals( $audit_table, $audit_exists, 'Audit log table must exist' );

	$version = get_option( 'negar_club_db_version' );
	assert_lifecycle_equals( '1.0.0', $version, 'DB version option must be 1.0.0' );
} );

// Test 5: Application Submission & Rejection Scenarios (T333)
run_lifecycle_test( 'T333: Application Submission & Validation Rules', function() {
	global $created_application_ids;

	// 1. Unauthenticated guest submission rejected with 401
	$guest_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09123456789',
			'national_id'       => '0010000003',
		),
		0 // Guest user_id
	);
	assert_lifecycle_true( is_wp_error( $guest_res ), 'Guest submission must return WP_Error' );
	assert_lifecycle_equals( 'UNAUTHORIZED', $guest_res->get_error_code() );
	assert_lifecycle_equals( 401, $guest_res->get_error_data()['status'] );

	// 2. Closed intake rejection (allow_applications is false by default)
	$closed_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09123456789',
			'national_id'       => '0010000003',
		),
		999901
	);
	assert_lifecycle_true( is_wp_error( $closed_res ), 'Closed intake submission must return WP_Error' );
	assert_lifecycle_equals( 'APPLICATIONS_CLOSED', $closed_res->get_error_code() );
	assert_lifecycle_equals( 403, $closed_res->get_error_data()['status'] );

	// Temporarily enable applications intake via filter
	$enable_intake_filter = function( $saved ) {
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$saved['is_enabled']         = true;
		$saved['allow_applications'] = true;
		return $saved;
	};
	add_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $enable_intake_filter );

	// 3. Invalid national ID rejected with 422
	$bad_nid_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09123456789',
			'national_id'       => '0010000006', // Invalid checksum
		),
		999901
	);
	assert_lifecycle_true( is_wp_error( $bad_nid_res ), 'Invalid national ID must be rejected' );
	assert_lifecycle_equals( 'INVALID_NATIONAL_ID', $bad_nid_res->get_error_code() );
	assert_lifecycle_equals( 422, $bad_nid_res->get_error_data()['status'] );

	// 4. Requested ceiling exceeding plan ceiling rejected with 422
	$bad_ceiling_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz', // Default ceiling is 6,000,000
			'requested_ceiling' => 50000000,     // 50,000,000 exceeds ceiling
			'contact_phone'     => '09123456789',
			'national_id'       => '0010000003',
		),
		999901
	);
	assert_lifecycle_true( is_wp_error( $bad_ceiling_res ), 'Ceiling excess must be rejected' );
	assert_lifecycle_equals( 'INVALID_CEILING', $bad_ceiling_res->get_error_code() );

	// 5. Valid application submission succeeds with 201 Created
	$valid_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09121111111',
			'national_id'       => '0010000003',
			'applicant_notes'   => 'درخواست تستی اتوماسیون',
		),
		999901
	);
	assert_lifecycle_false( is_wp_error( $valid_res ), 'Valid submission must succeed' );
	assert_lifecycle_true( $valid_res['success'] );
	$d = $valid_res['data'];
	assert_lifecycle_equals( 'pending_review', $d['status'], 'Initial status must be pending_review' );
	assert_lifecycle_true( 0 === strpos( $d['application_number'], 'APP-' ), 'Application number format valid' );

	global $wpdb;
	$app_row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}negar_club_applications WHERE application_number = %s", $d['application_number'] ) );
	if ( $app_row ) {
		$created_application_ids[] = (int) $app_row->id;
	}

	// 6. Duplicate active application attempt blocked with 409 Conflict
	$dup_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09122222222',
			'national_id'       => '0010000003', // Same national ID
		),
		999902
	);
	assert_lifecycle_true( is_wp_error( $dup_res ), 'Duplicate active application must be rejected' );
	assert_lifecycle_equals( 'DUPLICATE_ACTIVE_APPLICATION', $dup_res->get_error_code() );
	assert_lifecycle_equals( 409, $dup_res->get_error_data()['status'] );

	remove_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $enable_intake_filter );
} );

// Test 6: Application State Machine Transitions and Audit Logging (T334)
run_lifecycle_test( 'T334: Application State Machine Transitions & Audit Log', function() {
	global $wpdb, $created_application_ids;

	$filter = function( $saved ) {
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$saved['is_enabled']         = true;
		$saved['allow_applications'] = true;
		return $saved;
	};
	add_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $filter );

	// Submit a clean test application for state transitions
	$submit_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_plus',
			'requested_ceiling' => 8000000,
			'contact_phone'     => '09123333333',
			'national_id'       => '0790419904',
			'applicant_notes'   => 'تست ماشین وضعیت',
		),
		999903
	);
	assert_lifecycle_false( is_wp_error( $submit_res ), is_wp_error( $submit_res ) ? $submit_res->get_error_message() : '' );
	$app_num = $submit_res['data']['application_number'];

	$app_row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}negar_club_applications WHERE application_number = %s", $app_num ) );
	if ( $app_row ) {
		$created_application_ids[] = (int) $app_row->id;
	}

	// 1. Legal transition: pending_review -> under_review
	$t1 = Negar_Club_Application_Manager::transition_status( $app_num, 'under_review', 42, 'پرونده جهت استعلام بانکی ارجاع شد' );
	assert_lifecycle_false( is_wp_error( $t1 ), 'pending_review -> under_review must succeed' );
	assert_lifecycle_equals( 'under_review', $t1['data']['new_status'] );

	// 2. Legal transition: under_review -> approved
	$t2 = Negar_Club_Application_Manager::transition_status(
		$app_num,
		'approved',
		42,
		'مدارک تأیید شد و سقف ۸ میلیون تومان تخصیص یافت',
		array(
			'approved_ceiling' => 8000000,
			'validity_days'    => 14,
		)
	);
	assert_lifecycle_false( is_wp_error( $t2 ), 'under_review -> approved must succeed' );
	assert_lifecycle_equals( 'approved', $t2['data']['new_status'] );
	assert_lifecycle_equals( 8000000, $t2['data']['approved_ceiling'] );
	assert_lifecycle_true( ! empty( $t2['data']['expires_at'] ), 'expires_at must be populated' );

	// 3. Illegal transition: approved -> pending_review
	$t3 = Negar_Club_Application_Manager::transition_status( $app_num, 'pending_review', 42, 'تلاش غیرمجاز' );
	assert_lifecycle_true( is_wp_error( $t3 ), 'approved -> pending_review must be rejected' );
	assert_lifecycle_equals( 'INVALID_STATE_TRANSITION', $t3->get_error_code() );

	// 4. Verify audit log trail exists for transitions
	$audit_count = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}negar_club_audit_log WHERE entity_id = %d",
			$app_row->id
		)
	);
	assert_lifecycle_true( $audit_count >= 3, 'Audit log must record submit, under_review, and approved events' );

	remove_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $filter );
} );

// Test 7: Anti-IDOR Customer Authorization Isolation (T337)
run_lifecycle_test( 'T337: Anti-IDOR Authorization Guards', function() {
	global $wpdb, $created_application_ids;

	$filter = function( $saved ) {
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		$saved['is_enabled']         = true;
		$saved['allow_applications'] = true;
		return $saved;
	};
	add_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $filter );

	// Submit application belonging to User A (999905)
	$submit_res = Negar_Club_Application_Manager::submit_application(
		array(
			'plan_id'           => 'negar_aghaz',
			'requested_ceiling' => 5000000,
			'contact_phone'     => '09125555555',
			'national_id'       => '0010000011',
			'applicant_notes'   => 'درخواست کاربر الف',
		),
		999905
	);
	assert_lifecycle_false( is_wp_error( $submit_res ), is_wp_error( $submit_res ) ? $submit_res->get_error_message() : '' );
	$app_num = $submit_res['data']['application_number'];

	$app_row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}negar_club_applications WHERE application_number = %s", $app_num ) );
	if ( $app_row ) {
		$created_application_ids[] = (int) $app_row->id;
	}

	// 1. User B (999906) attempts to access User A's application -> 403 Forbidden (Anti-IDOR)
	$unauthorized_access = Negar_Club_Application_Manager::get_application_for_user( $app_num, 999906 );
	assert_lifecycle_true( is_wp_error( $unauthorized_access ), 'Cross-user access must be rejected' );
	assert_lifecycle_equals( 'ACCESS_DENIED', $unauthorized_access->get_error_code() );
	assert_lifecycle_equals( 403, $unauthorized_access->get_error_data()['status'] );

	// 2. User A (999905) accesses own application -> 200 OK
	$authorized_access = Negar_Club_Application_Manager::get_application_for_user( $app_num, 999905 );
	assert_lifecycle_false( is_wp_error( $authorized_access ), 'Owner access must succeed' );
	assert_lifecycle_equals( $app_num, $authorized_access['application_number'] );
	assert_lifecycle_false( isset( $authorized_access['national_id_hash'] ), 'National ID hash must never be returned' );

	remove_filter( 'option_' . NEGAR_THEME_CLUB_OPTION, $filter );
} );

// Test 8: Scoped Operator Permissions (T335)
run_lifecycle_test( 'T335: Scoped Operator Role & Capabilities', function() {
	// Register capabilities
	Negar_Club_Roles::register_capabilities();

	// Check capability constants
	assert_lifecycle_equals( 'negar_club_operator', Negar_Club_Roles::CAPABILITY_OPERATOR );

	// Non-operator customer user ID cannot operate
	assert_lifecycle_false( Negar_Club_Roles::current_user_can_operate( 999905 ), 'Customer user cannot operate' );
} );

// Clean up created fixtures
if ( ! empty( $created_application_ids ) ) {
	global $wpdb;
	$ids_in = implode( ',', array_map( 'intval', $created_application_ids ) );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}negar_club_applications WHERE id IN ({$ids_in})" );
	$wpdb->query( "DELETE FROM {$wpdb->prefix}negar_club_audit_log WHERE entity_type = 'club_application' AND entity_id IN ({$ids_in})" );
}

echo "\n--- Customer Club Application Lifecycle Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: {$passed}\n";
echo "Failed: {$failed}\n";

if ( $failed > 0 ) {
	exit( 1 );
}
