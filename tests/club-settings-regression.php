<?php
/**
 * Customer Club Theme Settings Regression Test Suite.
 *
 * Verifies Settings API tab registration, non-autoload schema defaults,
 * server-side sanitization boundaries, XSS neutralization, FAQ persistence,
 * and theme backup/restore integration without corrupting production data.
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
	// Fallback mock harness if running in isolated PHP environment
	require_once __DIR__ . '/mock-wp-harness.php';
}

require_once dirname( __DIR__ ) . '/inc/theme-settings.php';
require_once dirname( __DIR__ ) . '/inc/customer-club-settings.php';
require_once dirname( __DIR__ ) . '/inc/theme-backup.php';

echo "--- Running Customer Club Settings Regression Suite ---\n\n";

$passed = 0;
$failed = 0;

function run_test( $name, $callback ) {
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

function assert_true( $condition, $message = 'Condition failed to evaluate to true' ) {
	if ( true !== $condition ) {
		throw new RuntimeException( $message );
	}
}

function assert_equals( $expected, $actual, $message = '' ) {
	if ( $expected !== $actual ) {
		$msg = $message ?: sprintf( 'Expected %s, got %s', var_export( $expected, true ), var_export( $actual, true ) );
		throw new RuntimeException( $msg );
	}
}

// Capture current database snapshot for safe cleanup
$original_option_snapshot = get_option( NEGAR_THEME_CLUB_OPTION, null );

// Test 1: Tab Registration in Settings UI
run_test( 'T310: Tab Registration in negar_theme_settings_tabs()', function() {
	$tabs = negar_theme_settings_tabs();
	assert_true( isset( $tabs['customer_club'] ), 'Tab customer_club must exist in tabs array' );
	assert_equals( 'باشگاه مشتریان', $tabs['customer_club'], 'Tab title must match Persian translation' );
} );

// Test 2: Schema Defaults Completeness & Types
run_test( 'T311: Schema Defaults and Initial Configuration Structure', function() {
	$defaults = negar_theme_club_settings_defaults();

	assert_true( isset( $defaults['is_enabled'] ) && true === $defaults['is_enabled'], 'is_enabled default must be true' );
	assert_true( isset( $defaults['allow_applications'] ) && false === $defaults['allow_applications'], 'allow_applications default must be false for initial safety' );
	assert_true( isset( $defaults['plans'] ) && is_array( $defaults['plans'] ), 'plans must be an array' );

	// Check 3 fixed plans
	foreach ( array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' ) as $p_id ) {
		assert_true( isset( $defaults['plans'][ $p_id ] ), "Plan $p_id must exist in defaults" );
		assert_true( $defaults['plans'][ $p_id ]['initial_payment'] > 0, "$p_id initial payment must be positive" );
		assert_true( $defaults['plans'][ $p_id ]['ceiling'] >= $defaults['plans'][ $p_id ]['initial_payment'], "$p_id ceiling must be >= initial payment" );
		assert_true( $defaults['plans'][ $p_id ]['duration_months'] >= 1, "$p_id duration must be at least 1 month" );
		assert_true( is_array( $defaults['plans'][ $p_id ]['features'] ), "$p_id features must be an array" );
	}

	assert_equals( 3000000, $defaults['plans']['negar_aghaz']['initial_payment'], 'negar_aghaz initial payment must be 3M IRT' );
	assert_equals( 6000000, $defaults['plans']['negar_aghaz']['ceiling'], 'negar_aghaz ceiling must be 6M IRT' );
	assert_equals( 3, $defaults['plans']['negar_aghaz']['duration_months'], 'negar_aghaz duration must be 3 months' );

	assert_equals( 5000000, $defaults['plans']['negar_plus']['initial_payment'], 'negar_plus initial payment must be 5M IRT' );
	assert_equals( 10000000, $defaults['plans']['negar_plus']['ceiling'], 'negar_plus ceiling must be 10M IRT' );
	assert_equals( 6, $defaults['plans']['negar_plus']['duration_months'], 'negar_plus duration must be 6 months' );
	assert_true( $defaults['plans']['negar_plus']['is_featured'], 'negar_plus must be default featured' );

	assert_equals( 10000000, $defaults['plans']['negar_vizhe']['initial_payment'], 'negar_vizhe initial payment must be 10M IRT' );
	assert_equals( 20000000, $defaults['plans']['negar_vizhe']['ceiling'], 'negar_vizhe ceiling must be 20M IRT' );
	assert_equals( 6, $defaults['plans']['negar_vizhe']['duration_months'], 'negar_vizhe duration must be 6 months' );

	// Check sections visibility
	assert_true( is_array( $defaults['sections_visibility'] ), 'sections_visibility must be an array' );
	foreach ( array( 'hero', 'benefits', 'plans', 'comparison', 'calculator', 'timeline', 'faq', 'cta' ) as $s_id ) {
		assert_true( ! empty( $defaults['sections_visibility'][ $s_id ] ), "Section $s_id must be visible by default" );
	}

	// Check copy
	assert_true( is_array( $defaults['copy'] ), 'copy must be an array' );
	assert_true( ! empty( $defaults['copy']['hero_title'] ), 'hero_title must not be empty' );
	assert_true( ! empty( $defaults['copy']['disabled_notice'] ), 'disabled_notice must not be empty' );
	assert_equals( 4, count( $defaults['copy']['timeline_steps'] ), 'timeline_steps must have exactly 4 steps' );

	// Check FAQs
	assert_true( is_array( $defaults['faqs'] ), 'faqs must be an array' );
	assert_true( count( $defaults['faqs'] ) >= 4, 'faqs must have at least 4 default items' );
} );

// Test 3: Plan Configuration Sanitization
run_test( 'T312 & T313: Plan Configuration Sanitization and Feature Parsing', function() {
	$input = array(
		'is_enabled'         => '1',
		'allow_applications' => '1',
		'plans'              => array(
			'negar_aghaz' => array(
				'title'           => 'طرح آغاز نگار (سفارشی)',
				'subtitle'        => 'توضیحات تستی',
				'initial_payment' => '3500000',
				'ceiling'         => '7000000',
				'duration_months' => '4',
				'badge'           => 'ویژه نو عروسان',
				'is_featured'     => '1',
				'is_enabled'      => '1',
				'features'        => "ویژگی اول\nویژگی دوم\n  \nویژگی سوم",
			),
		),
	);

	$cleaned = negar_theme_sanitize_club_settings( $input );

	assert_true( $cleaned['is_enabled'], 'is_enabled must be boolean true' );
	assert_true( $cleaned['allow_applications'], 'allow_applications must be boolean true' );
	assert_equals( 'طرح آغاز نگار (سفارشی)', $cleaned['plans']['negar_aghaz']['title'] );
	assert_equals( 3500000, $cleaned['plans']['negar_aghaz']['initial_payment'] );
	assert_equals( 7000000, $cleaned['plans']['negar_aghaz']['ceiling'] );
	assert_equals( 4, $cleaned['plans']['negar_aghaz']['duration_months'] );
	assert_equals( 'ویژه نو عروسان', $cleaned['plans']['negar_aghaz']['badge'] );
	assert_true( $cleaned['plans']['negar_aghaz']['is_featured'] );
	assert_equals( array( 'ویژگی اول', 'ویژگی دوم', 'ویژگی سوم' ), $cleaned['plans']['negar_aghaz']['features'], 'Empty lines must be pruned' );
} );

// Test 4: Boundary Validation: Initial Payment > Ceiling Rejection
run_test( 'T316: Boundary Validation Rejection (initial_payment > ceiling)', function() {
	$input = array(
		'plans' => array(
			'negar_plus' => array(
				'title'           => 'نگار پلاس',
				'initial_payment' => '15000000', // 15M > 10M ceiling!
				'ceiling'         => '10000000',
			),
		),
	);

	$cleaned = negar_theme_sanitize_club_settings( $input );

	// Must reject invalid 15M and preserve previous valid value
	assert_true( $cleaned['plans']['negar_plus']['initial_payment'] <= $cleaned['plans']['negar_plus']['ceiling'], 'Sanitization must ensure initial_payment <= ceiling' );
	assert_equals( 5000000, $cleaned['plans']['negar_plus']['initial_payment'], 'Previous valid initial payment must be preserved' );
} );

// Test 5: XSS Sanitization in Text Fields
run_test( 'T316: XSS Neutralization in Titles and Copy', function() {
	$input = array(
		'copy' => array(
			'hero_title'    => '<script>alert("xss")</script>باشگاه مشتریان نگار',
			'hero_subtitle' => '<img src=x onerror=alert(1)>توضیحات ایمن',
		),
		'plans' => array(
			'negar_vizhe' => array(
				'title'    => 'نگار ویژه<style>body{display:none;}</style>',
				'subtitle' => '<b>توضیح</b><script>eval()</script>',
			),
		),
	);

	$cleaned = negar_theme_sanitize_club_settings( $input );

	assert_true( strpos( $cleaned['copy']['hero_title'], '<script>' ) === false, 'Script tags must be stripped from hero_title' );
	assert_true( strpos( $cleaned['copy']['hero_subtitle'], '<img' ) === false, 'Image tags with onerror must be sanitized' );
	assert_true( strpos( $cleaned['plans']['negar_vizhe']['title'], '<style>' ) === false, 'Style tags must be stripped' );
} );

// Test 6: FAQ Dynamic Repeater Sanitization
run_test( 'T315: Dynamic FAQ Repeater Add, Edit and Empty Pruning', function() {
	$input = array(
		'faqs' => array(
			array( 'question' => 'سوال اول؟', 'answer' => '<p>پاسخ اول با تگ مجاز</p>' ),
			array( 'question' => '  ', 'answer' => 'پاسخ سوال خالی' ), // Empty question should be pruned
			array( 'question' => 'سوال دوم؟', 'answer' => 'پاسخ دوم' ),
		),
	);

	$cleaned = negar_theme_sanitize_club_settings( $input );

	assert_equals( 2, count( $cleaned['faqs'] ), 'Empty question row must be pruned' );
	assert_equals( 'سوال اول؟', $cleaned['faqs'][0]['question'] );
	assert_equals( '<p>پاسخ اول با تگ مجاز</p>', $cleaned['faqs'][0]['answer'] );
	assert_equals( 'سوال دوم؟', $cleaned['faqs'][1]['question'] );
} );

// Test 7: Backup & Restore Integration (T317)
run_test( 'T317: Backup and Restore Group Registration & Validation', function() {
	$supported = negar_theme_backup_supported_groups();

	assert_true( isset( $supported[ NEGAR_THEME_CLUB_OPTION ] ), 'NEGAR_THEME_CLUB_OPTION must be registered in supported backup groups' );
	assert_equals( 'no', $supported[ NEGAR_THEME_CLUB_OPTION ]['autoload'], 'Backup group must be declared with autoload = no' );
	assert_equals( 'negar_theme_sanitize_club_settings', $supported[ NEGAR_THEME_CLUB_OPTION ]['sanitize'], 'Sanitizer must match club settings sanitizer' );

	// Test backup generation payload
	$backup_data = negar_theme_backup_generate_data();
	assert_true( isset( $backup_data['groups'][ NEGAR_THEME_CLUB_OPTION ] ), 'Generated backup data must contain customer club options' );

	// Test backup validation of customer club payload
	$club_backup = array(
		'format'     => NEGAR_THEME_BACKUP_FORMAT,
		'version'    => NEGAR_THEME_BACKUP_VERSION,
		'created_at' => gmdate( 'c' ),
		'site_url'   => home_url(),
		'groups'     => array(
			NEGAR_THEME_CLUB_OPTION => $backup_data['groups'][ NEGAR_THEME_CLUB_OPTION ],
		),
	);
	$validation = negar_theme_backup_validate_data( $club_backup );
	if ( ! $validation['valid'] ) {
		throw new RuntimeException( 'Validation failed: ' . implode( ' | ', $validation['errors'] ) );
	}
	assert_true( $validation['valid'], 'Backup data validation must pass for customer club' );
	assert_true( isset( $validation['clean_groups'][ NEGAR_THEME_CLUB_OPTION ] ), 'clean_groups must contain customer club options' );
} );

// Test 8: Non-Autoload Verification
run_test( 'T311: Non-Autoload Storage Verification in Database', function() {
	global $wpdb;
	if ( isset( $wpdb ) && method_exists( $wpdb, 'get_var' ) ) {
		negar_theme_ensure_club_option();
		$autoload = $wpdb->get_var(
			$wpdb->prepare( "SELECT autoload FROM {$wpdb->options} WHERE option_name = %s", NEGAR_THEME_CLUB_OPTION )
		);
		// On modern WP, non-autoload is stored as 'no' or 'off'
		if ( null !== $autoload ) {
			assert_true( in_array( $autoload, array( 'no', 'off', 0, '0' ), true ), "Option autoload must be 'no' or 'off', got: $autoload" );
		}
	}
} );

// Restore snapshot to ensure zero persistent side-effects
if ( null !== $original_option_snapshot ) {
	update_option( NEGAR_THEME_CLUB_OPTION, $original_option_snapshot );
}

echo "\n--- Customer Club Settings Regression Results ---\n";
echo "Total Tests: " . ( $passed + $failed ) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ( $failed > 0 ) {
	exit( 1 );
}

exit( 0 );
