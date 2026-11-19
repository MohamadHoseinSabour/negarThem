<?php
/**
 * Customer Club Quote Calculator Unit Test Suite.
 *
 * Verifies exact integer arithmetic, remainder allocation to first installment,
 * ceiling excess handling (Option B), minimum amount boundary checks,
 * and Persian/Arabic digit normalization.
 *
 * @package NegarThem
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

$wp_load_path = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
} else {
	require_once __DIR__ . '/mock-wp-harness.php';
}

require_once dirname( __DIR__ ) . '/inc/theme-settings.php';
require_once dirname( __DIR__ ) . '/inc/customer-club-settings.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-quote-calculator.php';

echo "--- Running Quote Calculator Unit Test Suite ---\n\n";

$passed = 0;
$failed = 0;

function run_calc_test( $name, $callback ) {
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

function assert_calc_true( $condition, $message = '' ) {
	if ( true !== $condition ) {
		throw new RuntimeException( $message ?: 'Condition failed' );
	}
}

function assert_calc_equals( $expected, $actual, $message = '' ) {
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message ?: sprintf( 'Expected %s, got %s', var_export( $expected, true ), var_export( $actual, true ) ) );
	}
}

// Test 1: Digit Normalization
run_calc_test( 'T325: Persian and Arabic Digit Normalization', function() {
	assert_calc_equals( '1234567890', Negar_Club_Quote_Calculator::normalize_digits( '۱۲۳۴۵۶۷۸۹۰' ) );
	assert_calc_equals( '1234567890', Negar_Club_Quote_Calculator::normalize_digits( '١٢٣٤٥٦٧٨٩٠' ) );
	assert_calc_equals( '15000000', Negar_Club_Quote_Calculator::normalize_digits( '۱۵,۰۰۰,۰۰۰ تومان' ) );
} );

// Test 2: Standard Divisible Amount (negar_aghaz, 6M purchase, 3M initial, 3M financed over 3 months = 1M/month)
run_calc_test( 'T325: Divisible Calculation Parity (negar_aghaz)', function() {
	$res = Negar_Club_Quote_Calculator::calculate( 6000000, 'negar_aghaz' );
	assert_calc_true( $res['success'], 'Calculation must succeed' );
	$d = $res['data'];

	assert_calc_equals( 6000000, $d['purchase_amount'] );
	assert_calc_equals( 3000000, $d['initial_payment'] );
	assert_calc_equals( 0, $d['additional_upfront'] );
	assert_calc_equals( 3000000, $d['total_upfront'] );
	assert_calc_equals( 3000000, $d['financed_principal'] );
	assert_calc_equals( 3, $d['installments_count'] );
	assert_calc_equals( 0, $d['remainder_amount'] );
	assert_calc_equals( 1000000, $d['first_installment_amount'] );
	assert_calc_equals( 1000000, $d['subsequent_installment_amount'] );
	assert_calc_equals( 3, count( $d['schedule_preview'] ) );
	assert_calc_equals( 1000000, $d['schedule_preview'][0]['total'] );
	assert_calc_equals( 1000000, $d['schedule_preview'][1]['total'] );
	assert_calc_equals( 1000000, $d['schedule_preview'][2]['total'] );
} );

// Test 3: Non-Divisible Remainder Allocation (negar_plus: 5M initial, 6 months term. Purchase 8M -> financed 3M. 3,000,000 / 6 = 500,000. Let's test 8,000,002 IRT -> financed 3,000,002 / 6 = 500,000 remainder 2)
run_calc_test( 'T325: Deterministic Remainder Allocation to First Installment (CLUB-BD-022)', function() {
	// Purchase 8,000,002 IRT on negar_plus (5M initial, 6 months)
	// Financed = 3,000,002 IRT
	// 3,000,002 / 6 = 500,000 remainder 2
	// First installment = 500,002 IRT
	// Subsequent installments (2..6) = 500,000 IRT each
	// Sum of installments: 500,002 + 5 * 500,000 = 3,000,002 IRT (100% exact parity!)
	$res = Negar_Club_Quote_Calculator::calculate( 8000002, 'negar_plus' );
	assert_calc_true( $res['success'], 'Calculation must succeed' );
	$d = $res['data'];

	assert_calc_equals( 3000002, $d['financed_principal'] );
	assert_calc_equals( 6, $d['installments_count'] );
	assert_calc_equals( 500000, $d['base_installment_amount'] );
	assert_calc_equals( 2, $d['remainder_amount'] );
	assert_calc_equals( 500002, $d['first_installment_amount'] );
	assert_calc_equals( 500000, $d['subsequent_installment_amount'] );

	$total_schedule = 0;
	foreach ( $d['schedule_preview'] as $row ) {
		$total_schedule += $row['total'];
	}
	assert_calc_equals( $d['financed_principal'], $total_schedule, 'Total schedule must exactly match financed principal' );
	assert_calc_equals( $d['purchase_amount'], $d['total_upfront'] + $total_schedule, 'Upfront + installments must exactly match purchase amount' );
} );

// Test 4: Amount Exceeds Purchase Ceiling (Option B per CLUB-BD-006)
run_calc_test( 'T325: Ceiling Excess Handling Option B (CLUB-BD-006)', function() {
	// negar_plus has 10M ceiling and 5M initial payment. Financed principal is capped at 10M - 5M = 5M.
	// Purchase 14M IRT -> excess 4M IRT added to upfront.
	// Initial = 5M, additional_upfront = 4M, total_upfront = 9M.
	// Financed principal = 5M IRT over 6 months.
	// Total customer obligation = 9M upfront + 5M installments = 14M IRT.
	$res = Negar_Club_Quote_Calculator::calculate( 14000000, 'negar_plus' );
	assert_calc_true( $res['success'] );
	$d = $res['data'];

	assert_calc_equals( 14000000, $d['purchase_amount'] );
	assert_calc_equals( 10000000, $d['purchase_ceiling'] );
	assert_calc_equals( 5000000, $d['initial_payment'] );
	assert_calc_equals( 4000000, $d['additional_upfront'] );
	assert_calc_equals( 9000000, $d['total_upfront'] );
	assert_calc_equals( 5000000, $d['financed_principal'] );
	assert_calc_true( ! empty( $d['validation_notices'] ), 'Validation notice must be set for excess upfront' );
	assert_calc_equals( 14000000, $d['total_upfront'] + $d['financed_principal'] );
} );

// Test 5: Amount Below Minimum Rejection (CLUB-BD-005)
run_calc_test( 'T325: Amount Below Initial Payment Rejection (CLUB-BD-005)', function() {
	// negar_plus initial is 5M. Submitting 4M must fail with AMOUNT_BELOW_MINIMUM.
	$res = Negar_Club_Quote_Calculator::calculate( 4000000, 'negar_plus' );
	assert_calc_true( false === $res['success'], 'Calculation must fail for amount < initial payment' );
	assert_calc_equals( 'AMOUNT_BELOW_MINIMUM', $res['code'] );
} );

// Test 6: Invalid Plan ID
run_calc_test( 'T325: Invalid or Inactive Plan ID Rejection', function() {
	$res = Negar_Club_Quote_Calculator::calculate( 5000000, 'non_existent_plan' );
	assert_calc_true( false === $res['success'] );
	assert_calc_equals( 'INVALID_PLAN', $res['code'] );
} );

echo "\n--- Quote Calculator Unit Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ( $failed > 0 ) {
	exit( 1 );
}

exit( 0 );
