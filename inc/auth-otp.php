<?php
/**
 * Customer Phone OTP Authentication Module
 * Provides seamless in-page mobile OTP login and customer account creation.
 * Hardened for release: Zero OTP disclosure in client AJAX responses.
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize Iranian Phone Number & Convert Persian/Arabic Digits
 *
 * @param string $phone Raw phone string.
 * @return string|false Canonical 11-digit string (09XXXXXXXXX) or false on invalid.
 */
function negar_normalize_phone( $phone ) {
	if ( empty( $phone ) || ! is_string( $phone ) ) {
		return false;
	}

	// 1. Convert Persian and Arabic digits to ASCII Latin digits
	$persian_digits = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$arabic_digits  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$latin_digits   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

	$normalized = str_replace( $persian_digits, $latin_digits, $phone );
	$normalized = str_replace( $arabic_digits, $latin_digits, $normalized );

	// 2. Remove all spaces, dashes, parentheses and non-digit chars except plus
	$normalized = preg_replace( '/[^\d+]/', '', $normalized );

	// 3. Normalize international +98 or 98 prefixes to leading 0
	if ( str_starts_with( $normalized, '+98' ) ) {
		$normalized = '0' . substr( $normalized, 3 );
	} elseif ( str_starts_with( $normalized, '98' ) ) {
		$normalized = '0' . substr( $normalized, 2 );
	} elseif ( str_starts_with( $normalized, '9' ) && 10 === strlen( $normalized ) ) {
		$normalized = '0' . $normalized;
	}

	// 4. Validate exact Iranian mobile regex: 09 followed by 9 digits
	if ( ! preg_match( '/^09[0-9]{9}$/', $normalized ) ) {
		return false;
	}

	return $normalized;
}

/**
 * AJAX Handler: Send OTP Code to Mobile Number
 * Security Rule: Never disclose OTP code in browser responses.
 */
function negar_ajax_send_otp() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$raw_phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$phone     = negar_normalize_phone( $raw_phone );

	if ( ! $phone ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'شماره موبایل وارد شده معتبر نمی‌باشد. لطفاً شماره‌ای مانند ۰۹۱۲۳۴۵۶۷۸۹ وارد کنید.', 'negar-shop' ),
		) );
	}

	$phone_hash   = md5( $phone );
	$cooldown_key = 'negar_otp_cool_' . $phone_hash;
	$hourly_key   = 'negar_otp_hour_' . $phone_hash;

	// Cooldown check (60 seconds)
	if ( get_transient( $cooldown_key ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'لطفاً کمی صبر کنید و پس از اتمام شمارش معکوس دوباره امتحان نمایید.', 'negar-shop' ),
		) );
	}

	// Hourly rate-limit check (Max 5 attempts per hour)
	$hourly_attempts = (int) get_transient( $hourly_key );
	if ( $hourly_attempts >= 5 ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً یک ساعت دیگر تلاش کنید.', 'negar-shop' ),
		) );
	}

	// Generate secure 5-digit verification code
	$code = (string) wp_rand( 10000, 99999 );

	// Store cryptographically hashed code in transient for 120 seconds (2 minutes)
	$code_hash = wp_hash( $code );
	set_transient( 'negar_otp_code_' . $phone_hash, $code_hash, 120 );
	set_transient( $cooldown_key, true, 60 );
	set_transient( $hourly_key, $hourly_attempts + 1, HOUR_IN_SECONDS );
	delete_transient( 'negar_otp_attempts_' . $phone_hash );

	// Filter hook for real SMS provider integration (e.g. Kavenegar, FarazSMS)
	$dispatched = apply_filters( 'negar_dispatch_otp_sms', false, $phone, $code );

	// Isolated automated test transport:
	// ONLY enabled when explicitly configured via NEGAR_TEST_OTP_TRANSPORT constant or CI env
	$is_test_transport = ( defined( 'NEGAR_TEST_OTP_TRANSPORT' ) && true === constant( 'NEGAR_TEST_OTP_TRANSPORT' ) )
		|| ( '1' === getenv( 'NEGAR_TEST_OTP_TRANSPORT' ) );

	if ( ! $dispatched && $is_test_transport ) {
		// In test mode, record dispatch status without disclosing code in public HTTP response
		set_transient( 'negar_test_transport_active_' . $phone_hash, true, 120 );
		$dispatched = true;
	}

	// Honest failure status when SMS delivery was not successful
	if ( ! $dispatched ) {
		// Clean up stored OTP transient on dispatch failure
		delete_transient( 'negar_otp_code_' . $phone_hash );
		delete_transient( $cooldown_key );
		wp_send_json_error( array(
			'message' => esc_html__( 'سامانه پیامکی در دسترس نمی‌باشد. لطفاً لحظاتی دیگر تلاش فرمایید یا با پشتیبانی تماس بگیرید.', 'negar-shop' ),
		) );
	}

	// Response payload - NEVER discloses code or mock_code to browser client
	$response_data = array(
		'message'  => esc_html__( 'کد تایید ۵ رقمی برای شماره شما ارسال شد.', 'negar-shop' ),
		'cooldown' => 60,
		'phone'    => $phone,
	);

	wp_send_json_success( $response_data );
}
add_action( 'wp_ajax_negar_auth_send_otp', 'negar_ajax_send_otp' );
add_action( 'wp_ajax_nopriv_negar_auth_send_otp', 'negar_ajax_send_otp' );

/**
 * AJAX Handler: Verify OTP Code & Authenticate Customer
 */
function negar_ajax_verify_otp() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$raw_phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$raw_code  = isset( $_POST['code'] ) ? sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '';

	$phone = negar_normalize_phone( $raw_phone );
	if ( ! $phone ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'شماره موبایل نامعتبر است.', 'negar-shop' ),
		) );
	}

	// Normalize code digits
	$persian_digits = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$arabic_digits  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$latin_digits   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	$code = str_replace( $persian_digits, $latin_digits, $raw_code );
	$code = str_replace( $arabic_digits, $latin_digits, $code );
	$code = preg_replace( '/\D/', '', $code );

	$phone_hash   = md5( $phone );
	$code_key     = 'negar_otp_code_' . $phone_hash;
	$attempts_key = 'negar_otp_attempts_' . $phone_hash;

	$stored_hash = get_transient( $code_key );
	$attempts    = (int) get_transient( $attempts_key );

	if ( $attempts >= 5 ) {
		delete_transient( $code_key );
		wp_send_json_error( array(
			'message' => esc_html__( 'تعداد تلاش‌های ناموفق بیش از حد مجاز است. لطفاً کد جدیدی دریافت کنید.', 'negar-shop' ),
		) );
	}

	if ( ! $stored_hash ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'کد تایید منقضی شده یا ثبت نشده است. لطفاً مجدداً درخواست دهید.', 'negar-shop' ),
		) );
	}

	// Constant-time hash comparison prevents timing side-channels
	if ( ! hash_equals( (string) $stored_hash, (string) wp_hash( $code ) ) ) {
		set_transient( $attempts_key, $attempts + 1, 120 );
		wp_send_json_error( array(
			'message' => esc_html__( 'کد وارد شده اشتباه است. لطفاً دقت فرمایید.', 'negar-shop' ),
		) );
	}

	// OTP is valid - clear transients
	delete_transient( $code_key );
	delete_transient( $attempts_key );
	delete_transient( 'negar_test_transport_active_' . $phone_hash );

	// Find or Create WordPress / WooCommerce User
	$user = null;

	// 1. Look up by billing_phone meta
	$user_query = new WP_User_Query( array(
		'meta_key'   => 'billing_phone',
		'meta_value' => $phone,
		'number'     => 1,
	) );
	$found_users = $user_query->get_results();

	if ( ! empty( $found_users ) ) {
		$user = $found_users[0];
	} else {
		// 2. Look up by user_login matching phone ONLY if associated with this verified phone
		$candidate = get_user_by( 'login', $phone );
		if ( $candidate ) {
			$saved_phone = get_user_meta( $candidate->ID, 'billing_phone', true );
			if ( empty( $saved_phone ) || $saved_phone === $phone ) {
				$user = $candidate;
			}
		}
	}

	// 3. If no user found, create a new customer
	if ( ! $user ) {
		$username = 'user_' . substr( $phone, -7 );
		if ( username_exists( $username ) ) {
			$username .= '_' . wp_rand( 10, 99 );
		}

		$email    = $phone . '@negarshop.local';
		$password = wp_generate_password( 24, true );

		if ( function_exists( 'wc_create_new_customer' ) ) {
			$new_user_id = wc_create_new_customer( $email, $username, $password );
		} else {
			$new_user_id = wp_create_user( $username, $password, $email );
		}

		if ( is_wp_error( $new_user_id ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'خطا در ثبت نام کاربر جدید.', 'negar-shop' ),
			) );
		}

		update_user_meta( $new_user_id, 'billing_phone', $phone );
		update_user_meta( $new_user_id, 'first_name', esc_html__( 'کاربر گرامی', 'negar-shop' ) );

		$user = get_user_by( 'id', $new_user_id );
	}

	// 4. Set authentication cookies
	wp_clear_auth_cookie();
	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, true );

	if ( function_exists( 'wc_set_customer_auth_cookie' ) ) {
		wc_set_customer_auth_cookie( $user->ID );
	}

	do_action( 'wp_login', $user->user_login, $user );

	wp_send_json_success( array(
		'message'      => esc_html__( 'ورود شما با موفقیت انجام شد.', 'negar-shop' ),
		'display_name' => $user->display_name,
		'redirect_url' => home_url( '/' ),
	) );
}
add_action( 'wp_ajax_negar_auth_verify_otp', 'negar_ajax_verify_otp' );
add_action( 'wp_ajax_nopriv_negar_auth_verify_otp', 'negar_ajax_verify_otp' );
