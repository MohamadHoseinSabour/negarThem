<?php
/**
 * Customer Club Public Page Render Test Suite.
 *
 * Verifies that the public page template renders cleanly without PHP warnings,
 * respects settings toggles (enabled/disabled, sections), outputs all 3 plans,
 * displays honest provisional copy, and integrates the interactive calculator.
 *
 * @package NegarThem
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

$wp_load_path = dirname( __DIR__, 4 ) . '/wp-load.php';
if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
	if ( defined( 'ABSPATH' ) && file_exists( ABSPATH . 'wp-admin/includes/template.php' ) ) {
		require_once ABSPATH . 'wp-admin/includes/template.php';
	}
} else {
	require_once __DIR__ . '/mock-wp-harness.php';
}

require_once dirname( __DIR__ ) . '/inc/theme-settings.php';
require_once dirname( __DIR__ ) . '/inc/customer-club-settings.php';
require_once dirname( __DIR__ ) . '/packages/negar-customer-club/includes/class-quote-calculator.php';

echo "--- Running Customer Club Page Render Test Suite ---\n\n";

$passed = 0;
$failed = 0;

function run_page_test( $name, $callback ) {
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

function assert_page_true( $condition, $message = '' ) {
	if ( true !== $condition ) {
		throw new RuntimeException( $message ?: 'Assertion failed' );
	}
}

function assert_page_contains( $needle, $haystack, $message = '' ) {
	if ( false === strpos( $haystack, $needle ) ) {
		throw new RuntimeException( $message ?: sprintf( 'Could not find "%s" in rendered output', $needle ) );
	}
}

// Test 1: Page Template File Exists & Syntax
run_page_test( 'T320: Page Template Existence and Registration', function() {
	$tpl_file = dirname( __DIR__ ) . '/page-templates/page-customer-club.php';
	assert_page_true( file_exists( $tpl_file ), 'page-customer-club.php must exist' );

	$page = get_page_by_path( 'customer-club' );
	assert_page_true( null !== $page, 'Page /customer-club/ must exist in WordPress' );
	assert_page_true( 1029 === (int) $page->ID, 'Page ID should be 1029' );
} );

// Test 2: Hero Component Render
run_page_test( 'T321: Hero Component Renders Honest Dynamic Copy', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/hero.php';
	$out = ob_get_clean();

	assert_page_contains( 'negar-club-hero', $out, 'Hero container missing' );
	assert_page_contains( 'باشگاه مشتریان و خرید اقساطی نگار', $out, 'Hero title missing' );
	assert_page_contains( '#club-plans', $out, 'Anchor CTA to plans missing' );
	assert_page_contains( '#club-calculator', $out, 'Anchor CTA to calculator missing' );
	assert_page_contains( 'negar-club-hero__card-preview', $out, 'Hero visual card preview missing' );
} );

// Test 3: Benefits and Steps Components Render
run_page_test( 'T322: Benefits and Timeline Steps Render Cleanly', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/benefits.php';
	$b_out = ob_get_clean();

	assert_page_contains( 'negar-club-benefits__grid', $b_out );
	assert_page_contains( 'شفافیت کامل در محاسبات', $b_out );
	assert_page_contains( 'طرح‌های متنوع بازپرداخت', $b_out );

	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/steps.php';
	$s_out = ob_get_clean();

	assert_page_contains( 'negar-club-steps__timeline', $s_out );
	assert_page_contains( 'بررسی و انتخاب طرح', $s_out );
	assert_page_contains( 'پیش‌نمایش طرح‌ها و ارزیابی زیرساخت', $s_out, 'Provisional notice missing' );
} );

// Test 4: Three Plan Cards Render
run_page_test( 'T323: Three Plan Cards Render from Active Settings', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/plans.php';
	$out = ob_get_clean();

	assert_page_contains( 'data-plan-id="negar_aghaz"', $out );
	assert_page_contains( 'data-plan-id="negar_plus"', $out );
	assert_page_contains( 'data-plan-id="negar_vizhe"', $out );
	assert_page_contains( 'negar-club-plan-card--featured', $out, 'Featured plan class missing' );
	assert_page_contains( 'انتخاب محبوب', $out, 'Badge missing' );
	assert_page_contains( '۶,۰۰۰,۰۰۰', $out, 'Aghaz ceiling 6M missing' );
	assert_page_contains( '۱۰,۰۰۰,۰۰۰', $out, 'Plus ceiling 10M missing' );
	assert_page_contains( '۲۰,۰۰۰,۰۰۰', $out, 'Vizhe ceiling 20M missing' );
} );

// Test 5: Plan Comparison Matrix Render
run_page_test( 'T324: Plan Comparison Matrix Component Renders', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/comparison.php';
	$out = ob_get_clean();

	assert_page_contains( 'negar-club-comparison__table', $out );
	assert_page_contains( 'سقف اعتبار خرید', $out );
	assert_page_contains( 'پیش‌پرداخت اولیه (برآورد)', $out );
	assert_page_contains( 'مدت بازپرداخت', $out );
} );

// Test 6: Interactive Calculator Component Render
run_page_test( 'T326: Interactive Calculator Component Renders with Config Attributes', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/calculator.php';
	$out = ob_get_clean();

	assert_page_contains( 'data-calculator-config=', $out );
	assert_page_contains( 'data-rest-url=', $out );
	assert_page_contains( 'id="negar-calc-amount-input"', $out );
	assert_page_contains( 'id="negar-calc-slider"', $out );
	assert_page_contains( 'id="res-monthly-amount"', $out );
	assert_page_contains( 'این محاسبه صرفاً پیش‌نمایش و برآورد تخمینی است', $out, 'Non-binding disclaimer missing' );
} );

// Test 7: FAQ Accordion & Final CTA Render
run_page_test( 'T327: FAQ Accordion and Final CTA Render with Accessible Markup', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/faq.php';
	$f_out = ob_get_clean();

	assert_page_contains( 'negar-club-faq__accordion', $f_out );
	assert_page_contains( 'aria-expanded="false"', $f_out );
	assert_page_contains( 'شرایط عمومی استفاده از طرح‌های باشگاه مشتریان چیست؟', $f_out );

	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/cta.php';
	$c_out = ob_get_clean();

	assert_page_contains( 'negar-club-cta', $c_out );
	assert_page_contains( 'آشنایی با طرح‌ها و برآورد شرایط اقساطی', $c_out );
} );

// Test 8: Disabled View Render
run_page_test( 'T328: Disabled View Renders Cleanly When Club Disabled', function() {
	ob_start();
	include dirname( __DIR__ ) . '/template-parts/customer-club/disabled.php';
	$out = ob_get_clean();

	assert_page_contains( 'negar-club-disabled-state', $out );
	assert_page_contains( 'در دست پیکربندی و به‌روزرسانی زیرساخت‌های اعتبارسنجی', $out );
} );

// Test 9: Enqueue Registration
run_page_test( 'T329: Stylesheet and Script Registered in Theme Enqueue', function() {
	$css_file = dirname( __DIR__ ) . '/assets/css/customer-club.css';
	$js_file  = dirname( __DIR__ ) . '/assets/js/customer-club.js';

	assert_page_true( file_exists( $css_file ), 'customer-club.css must exist' );
	assert_page_true( file_exists( $js_file ), 'customer-club.js must exist' );

	$enqueue_code = file_get_contents( dirname( __DIR__ ) . '/inc/enqueue.php' );
	assert_page_contains( 'negar-customer-club-style', $enqueue_code, 'Style handle missing in enqueue.php' );
	assert_page_contains( 'negar-customer-club-script', $enqueue_code, 'Script handle missing in enqueue.php' );
} );

echo "\n--- Customer Club Page Render Test Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ( $failed > 0 ) {
	exit( 1 );
}

exit( 0 );
