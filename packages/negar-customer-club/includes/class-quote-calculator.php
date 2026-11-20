<?php
/**
 * Canonical Stateless Quote Calculator for Negar Customer Club.
 *
 * Implements deterministic integer-only calculation of installment quotes,
 * upfront payment breakdowns, financed principal, and schedule previews.
 *
 * Strictly stateless and read-only: zero database writes, zero financial orders.
 *
 * @package NegarCustomerClub
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Quote_Calculator {

	/**
	 * Convert Persian and Arabic digits to standard ASCII digits.
	 *
	 * Strips allowed currency words (تومان, ریال, etc.) and grouping separators (commas, spaces).
	 * Rejects strings containing invalid alphabetic or symbolic characters by returning an empty string.
	 *
	 * @param string|int|float $input Raw numeric string.
	 * @return string Normalized digit string, or empty string on malformed/mixed input.
	 */
	public static function normalize_digits( $input ) {
		$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
		$arabic  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
		$ascii   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

		$str = trim( (string) $input );
		$str = str_replace( $persian, $ascii, $str );
		$str = str_replace( $arabic, $ascii, $str );

		// Strip permitted currency labels
		$cleaned = str_ireplace( array( 'تومان', 'ریال', 'toman', 'irt', 'irr' ), '', $str );

		// Strip permitted grouping characters: commas, Persian commas, spaces
		$cleaned = str_replace( array( ',', '،', ' ' ), '', $cleaned );

		// If input contains non-digit characters (e.g. letters, symbols), treat as invalid
		if ( '' !== $cleaned && ! ctype_digit( $cleaned ) ) {
			return '';
		}

		return $cleaned;
	}

	/**
	 * Calculate installment quote for a given purchase amount and plan.
	 *
	 * @param int                  $purchase_amount Purchase amount in integer Toman (IRT).
	 * @param string               $plan_id         Plan ID ('negar_aghaz', 'negar_plus', 'negar_vizhe').
	 * @param array<string, mixed> $plan_override   Optional plan configuration override.
	 * @return array<string, mixed>
	 */
	public static function calculate( $purchase_amount, $plan_id, $plan_override = array() ) {
		$purchase_amount = (int) $purchase_amount;
		$plan_id         = sanitize_key( (string) $plan_id );

		// 1. Retrieve plan definition
		$plan = null;
		if ( ! empty( $plan_override ) && is_array( $plan_override ) ) {
			$plan = $plan_override;
		} else {
			$defaults = function_exists( 'negar_theme_club_settings_defaults' ) ? negar_theme_club_settings_defaults() : array();
			$all_plans = function_exists( 'negar_theme_club_setting' ) ? negar_theme_club_setting( 'plans', null, array() ) : ( $defaults['plans'] ?? array() );
			if ( ! empty( $all_plans[ $plan_id ] ) ) {
				$plan = $all_plans[ $plan_id ];
			} elseif ( ! empty( $defaults['plans'][ $plan_id ] ) ) {
				$plan = $defaults['plans'][ $plan_id ];
			}
		}

		if ( ! $plan || empty( $plan['is_enabled'] ) ) {
			return array(
				'success' => false,
				'code'    => 'INVALID_PLAN',
				'message' => 'طرح اقساطی انتخاب‌شده نامعتبر یا غیرفعال است.',
			);
		}

		$title           = ! empty( $plan['title'] ) ? (string) $plan['title'] : $plan_id;
		$initial_payment = isset( $plan['initial_payment'] ) ? (int) $plan['initial_payment'] : 0;
		$ceiling         = isset( $plan['ceiling'] ) ? (int) $plan['ceiling'] : 0;
		$duration_months = isset( $plan['duration_months'] ) ? (int) $plan['duration_months'] : 1;
		if ( $duration_months < 1 ) {
			$duration_months = 1;
		}

		// 2. Validate amount limits (CLUB-BD-005)
		if ( $purchase_amount < $initial_payment || $purchase_amount <= 0 ) {
			return array(
				'success' => false,
				'code'    => 'AMOUNT_BELOW_MINIMUM',
				'message' => sprintf(
					'مبلغ خرید نمی‌تواند از مبلغ پیش‌پرداخت طرح (%s تومان) کمتر باشد.',
					number_format( $initial_payment )
				),
				'data'    => array(
					'min_purchase'    => $initial_payment,
					'initial_payment' => $initial_payment,
				),
			);
		}

		// Max allowable safe input: 10,000,000,000 IRT (10 Billion Toman)
		if ( $purchase_amount > 10000000000 ) {
			return array(
				'success' => false,
				'code'    => 'AMOUNT_EXCEEDS_MAXIMUM_SAFE',
				'message' => 'مبلغ وارد شده بیش از حد مجاز سامانه است.',
			);
		}

		// 3. Compute Upfront & Financed Principal (CLUB-BD-006 Option B)
		$validation_notices = array();
		$policy_flags       = array(
			'NO_INTEREST_PROVISIONAL_MODEL',
			'REMAINDER_ALLOCATED_TO_FIRST_INSTALLMENT_PROPOSED_BD022',
		);

		if ( $purchase_amount <= $ceiling ) {
			$additional_upfront = 0;
			$total_upfront      = $initial_payment;
			$financed_principal = $purchase_amount - $initial_payment;
		} else {
			// Excess above ceiling is collected upfront
			$additional_upfront = $purchase_amount - $ceiling;
			$total_upfront      = $initial_payment + $additional_upfront;
			$financed_principal = $ceiling - $initial_payment;
			$policy_flags[]     = 'OPTION_B_EXCESS_UPFRONT_PROPOSED_BD006';
			$validation_notices[] = sprintf(
				'مبلغ انتخابی بیش از سقف خرید طرح (%s تومان) است. مازاد سقف (%s تومان) به پیش‌پرداخت افزوده می‌شود.',
				number_format( $ceiling ),
				number_format( $additional_upfront )
			);
		}

		if ( $financed_principal < 0 ) {
			$financed_principal = 0;
		}

		// 4. Compute Installment Breakdown with Deterministic Remainder Allocation (CLUB-BD-022)
		$installments_count = $duration_months;
		if ( $financed_principal > 0 && $installments_count > 0 ) {
			$base_installment = (int) intdiv( $financed_principal, $installments_count );
			$remainder        = (int) ( $financed_principal % $installments_count );
		} else {
			$base_installment = 0;
			$remainder        = 0;
		}

		$first_installment      = $base_installment + $remainder;
		$subsequent_installment = $base_installment;

		// 5. Generate schedule preview
		$schedule_preview = array();
		for ( $seq = 1; $seq <= $installments_count; $seq++ ) {
			$inst_amount = ( 1 === $seq ) ? $first_installment : $subsequent_installment;
			$schedule_preview[] = array(
				'sequence'  => $seq,
				'principal' => $inst_amount,
				'charge'    => 0,
				'total'     => $inst_amount,
			);
		}

		return array(
			'success' => true,
			'data'    => array(
				'plan_id'                       => $plan_id,
				'plan_title'                    => $title,
				'purchase_amount'               => $purchase_amount,
				'purchase_ceiling'              => $ceiling,
				'initial_payment'               => $initial_payment,
				'additional_upfront'            => $additional_upfront,
				'total_upfront'                 => $total_upfront,
				'financed_principal'            => $financed_principal,
				'total_disclosed_charges'       => 0,
				'total_payable'                 => $purchase_amount,
				'installments_count'            => $installments_count,
				'base_installment_amount'       => $base_installment,
				'remainder_amount'              => $remainder,
				'first_installment_amount'      => $first_installment,
				'subsequent_installment_amount' => $subsequent_installment,
				'schedule_preview'              => $schedule_preview,
				'validation_notices'            => $validation_notices,
				'is_binding'                    => false,
				'is_provisional'                => true,
				'disclaimer_text'               => 'این محاسبه صرفاً پیش‌نمایش و برآورد تخمینی است و هیچ‌گونه تعهد مالی یا تأیید اعتبار تلقی نمی‌گردد.',
				'policy_flags'                  => $policy_flags,
			),
		);
	}

	/**
	 * Register public REST API endpoints for quote calculation.
	 *
	 * @return void
	 */
	public static function register_rest_routes() {
		static $registered = false;
		if ( $registered ) {
			return;
		}
		$registered = true;

		register_rest_route(
			'negar-club/v1',
			'/quote',
			array(
				array(
					'methods'             => array( 'GET', 'POST' ),
					'callback'            => array( __CLASS__, 'handle_rest_quote' ),
					'permission_callback' => '__return_true',
					'args'                => array(
						'purchase_amount' => array(
							'required'          => true,
							'validate_callback' => function( $param ) {
								$clean = Negar_Club_Quote_Calculator::normalize_digits( $param );
								return is_numeric( $clean ) && (int) $clean > 0;
							},
							'sanitize_callback' => function( $param ) {
								return (int) Negar_Club_Quote_Calculator::normalize_digits( $param );
							},
						),
						'plan_id'         => array(
							'required'          => true,
							'validate_callback' => function( $param ) {
								return is_string( $param ) && '' !== trim( $param );
							},
							'sanitize_callback' => 'sanitize_key',
						),
					),
				),
			)
		);
	}

	/**
	 * REST API handler for /wp-json/negar-club/v1/quote.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_quote( $request ) {
		$raw_amount = $request->get_param( 'purchase_amount' );
		$clean_amount = (int) self::normalize_digits( $raw_amount );
		$plan_id      = sanitize_key( (string) $request->get_param( 'plan_id' ) );

		if ( $clean_amount <= 0 ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'code'    => 'INVALID_AMOUNT',
					'message' => 'مبلغ خرید باید یک رقم مثبت معتبر باشد.',
				),
				400
			);
		}

		if ( empty( $plan_id ) ) {
			return new WP_REST_Response(
				array(
					'success' => false,
					'code'    => 'MISSING_PLAN',
					'message' => 'شناسه طرح مشخص نشده است.',
				),
				400
			);
		}

		$result = self::calculate( $clean_amount, $plan_id );

		if ( empty( $result['success'] ) ) {
			$status_code = ( 'AMOUNT_BELOW_MINIMUM' === ( $result['code'] ?? '' ) ) ? 422 : 400;
			return new WP_REST_Response( $result, $status_code );
		}

		return new WP_REST_Response( $result, 200 );
	}
}
