<?php
/**
 * Canonical Identity & Field Validator for Negar Customer Club.
 *
 * Implements official Iranian National ID Modulo 11 validation,
 * privacy-preserving HMAC hashing, phone number normalization,
 * and monetary boundary checks.
 *
 * @package NegarCustomerClub
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Validator {

	/**
	 * Convert Persian and Arabic digits to ASCII digits.
	 *
	 * @param string $str Raw input string.
	 * @return string Cleaned ASCII string.
	 */
	public static function normalize_digits( $str ) {
		$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
		$arabic  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
		$ascii   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

		$cleaned = trim( (string) $str );
		$cleaned = str_replace( $persian, $ascii, $cleaned );
		$cleaned = str_replace( $arabic, $ascii, $cleaned );

		return $cleaned;
	}

	/**
	 * Validate an Iranian National ID (کد ملی) using the official Modulo 11 algorithm.
	 *
	 * Specifications:
	 * 1. Normalized to ASCII digits.
	 * 2. Exactly 10 digits with leading zeros preserved.
	 * 3. Rejects repeated identical digits (e.g. 0000000000, 1111111111, ..., 9999999999).
	 * 4. Modulo 11 checksum calculation:
	 *    Sum = sum( d[i] * (10 - i) ) for i in 0..8
	 *    Remainder = Sum % 11
	 *    If Remainder < 2  => check_digit == Remainder
	 *    If Remainder >= 2 => check_digit == 11 - Remainder
	 *
	 * @param string|int $code National ID string or number.
	 * @return bool True if mathematically valid, false otherwise.
	 */
	public static function validate_national_id( $code ) {
		$clean = self::normalize_digits( (string) $code );
		$clean = preg_replace( '/\D/', '', $clean );

		// Must be exactly 10 numeric characters
		if ( strlen( $clean ) !== 10 ) {
			return false;
		}

		// Reject identical repeated digits (0000000000, 1111111111, ..., 9999999999)
		if ( preg_match( '/^(\d)\1{9}$/', $clean ) ) {
			return false;
		}

		// Modulo 11 calculation
		$sum = 0;
		for ( $i = 0; $i < 9; $i++ ) {
			$sum += ( (int) $clean[ $i ] ) * ( 10 - $i );
		}

		$remainder   = $sum % 11;
		$check_digit = (int) $clean[9];

		if ( $remainder < 2 ) {
			return $check_digit === $remainder;
		}

		return $check_digit === ( 11 - $remainder );
	}

	/**
	 * Generate a deterministic one-way privacy hash of the national ID.
	 *
	 * Uses HMAC-SHA256 keyed with WordPress auth salt to prevent plaintext
	 * storage or disclosure while allowing duplicate detection.
	 *
	 * @param string|int $code National ID.
	 * @return string 64-character hex hash or empty string if invalid.
	 */
	public static function hash_national_id( $code ) {
		$clean = self::normalize_digits( (string) $code );
		$clean = preg_replace( '/\D/', '', $clean );

		if ( strlen( $clean ) !== 10 ) {
			return '';
		}

		$salt = function_exists( 'wp_salt' ) ? wp_salt( 'auth' ) : 'negar_default_auth_salt_fallback_key';

		return hash_hmac( 'sha256', $clean, $salt );
	}

	/**
	 * Validate and normalize Iranian mobile phone numbers.
	 *
	 * Accepts standard formats:
	 * - 09123456789
	 * - +989123456789
	 * - 00989123456789
	 * - 9123456789
	 *
	 * Normalizes to canonical format: 09XXXXXXXXX (11 digits).
	 *
	 * @param string $phone Raw phone number.
	 * @return string|false Normalized 11-digit phone or false if invalid.
	 */
	public static function validate_phone( $phone ) {
		$clean = self::normalize_digits( (string) $phone );
		$clean = preg_replace( '/[^\d\+]/', '', $clean );

		// Normalize international prefixes
		if ( 0 === strpos( $clean, '+98' ) ) {
			$clean = '0' . substr( $clean, 3 );
		} elseif ( 0 === strpos( $clean, '0098' ) ) {
			$clean = '0' . substr( $clean, 4 );
		} elseif ( 10 === strlen( $clean ) && '9' === $clean[0] ) {
			$clean = '0' . $clean;
		}

		if ( preg_match( '/^09\d{9}$/', $clean ) ) {
			return $clean;
		}

		return false;
	}

	/**
	 * Validate monetary amount within specified minimum and maximum bounds.
	 *
	 * @param int|float|string $amount Raw amount in Toman.
	 * @param int              $min    Minimum allowable value.
	 * @param int              $max    Maximum allowable value.
	 * @return int|false Validated integer Toman or false if out of bounds.
	 */
	public static function validate_amount( $amount, $min = 0, $max = 10000000000 ) {
		$clean = self::normalize_digits( (string) $amount );
		$clean = preg_replace( '/[^\d]/', '', $clean );

		if ( '' === $clean ) {
			return false;
		}

		$val = (int) $clean;
		if ( $val < $min || $val > $max ) {
			return false;
		}

		return $val;
	}
}
