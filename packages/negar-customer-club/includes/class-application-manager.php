<?php
/**
 * Application Lifecycle & State Machine Manager for Negar Customer Club.
 *
 * Implements customer application submission, atomic concurrency protection,
 * state machine transitions with optimistic concurrency locking,
 * anti-IDOR authorization guards, audit trail emission, and secure REST endpoints.
 *
 * @package NegarCustomerClub
 * @version 1.1.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Application_Manager {

	// Canonical Status Constants
	const STATUS_PENDING_REVIEW = 'pending_review';
	const STATUS_UNDER_REVIEW   = 'under_review';
	const STATUS_APPROVED       = 'approved';
	const STATUS_REJECTED       = 'rejected';
	const STATUS_EXPIRED        = 'expired';
	const STATUS_CANCELLED      = 'cancelled';

	/**
	 * State machine allowed transitions matrix.
	 *
	 * Terminal states (rejected, expired, cancelled) have zero allowed transitions.
	 *
	 * @var array<string, array<string>>
	 */
	protected static $allowed_transitions = array(
		self::STATUS_PENDING_REVIEW => array(
			self::STATUS_UNDER_REVIEW,
			self::STATUS_APPROVED,
			self::STATUS_REJECTED,
			self::STATUS_CANCELLED,
		),
		self::STATUS_UNDER_REVIEW   => array(
			self::STATUS_APPROVED,
			self::STATUS_REJECTED,
			self::STATUS_CANCELLED,
		),
		self::STATUS_APPROVED       => array(
			self::STATUS_EXPIRED,
			self::STATUS_CANCELLED,
		),
		self::STATUS_REJECTED       => array(),
		self::STATUS_EXPIRED        => array(),
		self::STATUS_CANCELLED      => array(),
	);

	/**
	 * Get human-readable Persian label for an application status.
	 *
	 * @param string $status Canonical status code.
	 * @return string
	 */
	public static function get_status_label( $status ) {
		$labels = array(
			self::STATUS_PENDING_REVIEW => 'در انتظار بررسی مدارک',
			self::STATUS_UNDER_REVIEW   => 'در دست بررسی',
			self::STATUS_APPROVED       => 'تأیید شده',
			self::STATUS_REJECTED       => 'رد شده',
			self::STATUS_EXPIRED        => 'منقضی شده',
			self::STATUS_CANCELLED      => 'لغو شده',
		);

		return $labels[ $status ] ?? $status;
	}

	/**
	 * Check whether a state transition is permitted by the state machine.
	 *
	 * @param string $current_status Current status.
	 * @param string $new_status     Target status.
	 * @return bool
	 */
	public static function is_transition_allowed( $current_status, $new_status ) {
		if ( ! isset( self::$allowed_transitions[ $current_status ] ) ) {
			return false;
		}

		return in_array( $new_status, self::$allowed_transitions[ $current_status ], true );
	}

	/**
	 * Generate a unique application number.
	 * Format: APP-YYYYMMDD-XXXX
	 *
	 * @return string
	 */
	public static function generate_application_number() {
		global $wpdb;

		$table = Negar_Club_Database::get_table_name( 'applications' );
		$date  = gmdate( 'Ymd' );

		for ( $i = 0; $i < 10; $i++ ) {
			$random = strtoupper( wp_generate_password( 4, false ) );
			$number = "APP-{$date}-{$random}";

			$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM `{$table}` WHERE application_number = %s LIMIT 1", $number ) );
			if ( ! $existing ) {
				return $number;
			}
		}

		return 'APP-' . $date . '-' . wp_rand( 1000, 9999 );
	}

	/**
	 * Submit a new customer club credit application.
	 *
	 * @param array<string, mixed> $data    Application data.
	 * @param int                  $user_id Submitting user ID.
	 * @return array<string, mixed>|WP_Error
	 */
	public static function submit_application( $data, $user_id ) {
		global $wpdb;

		$user_id = (int) $user_id;
		if ( $user_id <= 0 ) {
			return new WP_Error( 'UNAUTHORIZED', 'جهت ثبت درخواست عضویت باید وارد حساب کاربری خود شوید.', array( 'status' => 401 ) );
		}

		// 1. Check if club and applications intake are enabled in theme settings (Fail-closed Fix D)
		$club_enabled = function_exists( 'negar_theme_club_setting' ) ? (bool) negar_theme_club_setting( 'is_enabled', null, false ) : false;
		$allow_apply  = function_exists( 'negar_theme_club_setting' ) ? (bool) negar_theme_club_setting( 'allow_applications', null, false ) : false;

		if ( ! $club_enabled || ! $allow_apply ) {
			return new WP_Error(
				'APPLICATIONS_CLOSED',
				'در حال حاضر امکان ثبت درخواست جدید در باشگاه مشتریان غیرفعال است.',
				array( 'status' => 403 )
			);
		}

		// 2. Validate Plan
		$plan_id = sanitize_key( (string) ( $data['plan_id'] ?? '' ) );
		if ( empty( $plan_id ) ) {
			return new WP_Error( 'MISSING_PLAN', 'شناسه طرح اقساطی الزامی است.', array( 'status' => 400 ) );
		}

		$defaults  = function_exists( 'negar_theme_club_settings_defaults' ) ? negar_theme_club_settings_defaults() : array();
		$all_plans = function_exists( 'negar_theme_club_setting' ) ? negar_theme_club_setting( 'plans', null, array() ) : ( $defaults['plans'] ?? array() );
		$plan      = $all_plans[ $plan_id ] ?? ( $defaults['plans'][ $plan_id ] ?? null );

		if ( ! $plan || empty( $plan['is_enabled'] ) ) {
			return new WP_Error( 'INVALID_PLAN', 'طرح انتخابی نامعتبر یا غیرفعال است.', array( 'status' => 422 ) );
		}

		$plan_ceiling = isset( $plan['ceiling'] ) ? (int) $plan['ceiling'] : 0;
		$plan_title   = ! empty( $plan['title'] ) ? (string) $plan['title'] : $plan_id;

		// 3. Validate Requested Ceiling
		$raw_ceiling       = $data['requested_ceiling'] ?? 0;
		$requested_ceiling = Negar_Club_Validator::validate_amount( $raw_ceiling, 1, $plan_ceiling );
		if ( false === $requested_ceiling ) {
			return new WP_Error(
				'INVALID_CEILING',
				sprintf( 'سقف درخواستی باید عددی مثبت و حداکثر برابر با سقف طرح (%s تومان) باشد.', number_format( $plan_ceiling ) ),
				array( 'status' => 422 )
			);
		}

		// 4. Validate Iranian National ID (Modulo 11)
		$raw_national_id = $data['national_id'] ?? '';
		if ( ! Negar_Club_Validator::validate_national_id( $raw_national_id ) ) {
			return new WP_Error(
				'INVALID_NATIONAL_ID',
				'کد ملی وارد شده نامعتبر است (کنترل رقم چک مودولو ۱۱ تأیید نشد).',
				array( 'status' => 422 )
			);
		}

		$national_id_hash = Negar_Club_Validator::hash_national_id( $raw_national_id );

		// 5. Validate Contact Phone
		$raw_phone     = $data['contact_phone'] ?? '';
		$contact_phone = Negar_Club_Validator::validate_phone( $raw_phone );
		if ( false === $contact_phone ) {
			return new WP_Error(
				'INVALID_PHONE',
				'شماره همراه وارد شده نامعتبر است. فرمت صحیح: ۰۹xxxxxxxxx',
				array( 'status' => 422 )
			);
		}

		// 6. Pre-check Duplicate Active Applications & Information Disclosure Protection (Fix A & Fix B)
		$table_applications = Negar_Club_Database::get_table_name( 'applications' );

		$existing_active = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, user_id, application_number, status FROM `{$table_applications}`
				 WHERE (active_lock_user = %s OR active_lock_nid = %s)
				 LIMIT 1",
				(string) $user_id,
				(string) $national_id_hash
			)
		);

		if ( $existing_active ) {
			// Fix A: If active application belongs to the current user, inform them of their own application
			if ( (int) $existing_active->user_id === $user_id ) {
				return new WP_Error(
					'DUPLICATE_ACTIVE_APPLICATION',
					sprintf(
						'شما قبلاً یک درخواست فعال یا در حال بررسی (شماره %s با وضعیت %s) دارید. امکان ثبت درخواست همزمان وجود ندارد.',
						$existing_active->application_number,
						self::get_status_label( $existing_active->status )
					),
					array(
						'status'             => 409,
						'application_number' => $existing_active->application_number,
					)
				);
			} else {
				// Fix A: If active application belongs to a DIFFERENT user account, NEVER disclose metadata!
				return new WP_Error(
					'DUPLICATE_APPLICATION_RESTRICTED',
					'امکان ثبت درخواست برای این اطلاعات هویتی وجود ندارد. در صورت نیاز به بررسی، لطفاً با پشتیبانی فروشگاه تماس حاصل فرمایید.',
					array( 'status' => 409 )
				);
			}
		}

		// 7. Sanitize optional notes
		$applicant_notes = isset( $data['applicant_notes'] ) ? sanitize_textarea_field( (string) $data['applicant_notes'] ) : '';
		if ( mb_strlen( $applicant_notes ) > 1000 ) {
			$applicant_notes = mb_substr( $applicant_notes, 0, 1000 );
		}

		$income_bracket = isset( $data['income_bracket'] ) ? sanitize_text_field( (string) $data['income_bracket'] ) : null;

		// 8. Generate unique application number and insert with active lock tokens (Fix B)
		$application_number = self::generate_application_number();
		$now                = current_time( 'mysql' );

		$insert_data = array(
			'application_number' => $application_number,
			'user_id'            => $user_id,
			'plan_id'            => $plan_id,
			'status'             => self::STATUS_PENDING_REVIEW,
			'requested_ceiling'  => $requested_ceiling,
			'approved_ceiling'   => 0,
			'national_id_hash'   => $national_id_hash,
			'active_lock_user'   => (string) $user_id,
			'active_lock_nid'    => (string) $national_id_hash,
			'income_bracket'     => $income_bracket,
			'contact_phone'      => $contact_phone,
			'applicant_notes'    => $applicant_notes,
			'operator_notes'     => null,
			'customer_message'   => null,
			'reviewed_by'        => null,
			'reviewed_at'        => null,
			'expires_at'         => null,
			'created_at'         => $now,
			'updated_at'         => $now,
		);

		$format = array( '%s', '%d', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s' );

		// Suppress errors temporarily to gracefully catch concurrent MySQL duplicate key exceptions
		$wpdb->suppress_errors( true );
		$inserted = $wpdb->insert( $table_applications, $insert_data, $format );
		$wpdb->suppress_errors( false );

		if ( false === $inserted ) {
			// Catch atomic MySQL unique constraint conflict (Fix B)
			if ( false !== strpos( $wpdb->last_error, 'uq_active_user' ) || false !== strpos( $wpdb->last_error, 'uq_active_nid' ) || false !== strpos( $wpdb->last_error, 'Duplicate entry' ) ) {
				return new WP_Error(
					'DUPLICATE_ACTIVE_APPLICATION',
					'یک درخواست فعال دیگر به صورت همزمان ثبت شده یا در حال پردازش است.',
					array( 'status' => 409 )
				);
			}
			return new WP_Error( 'DB_ERROR', 'خطا در ثبت اطلاعات درخواست در پایگاه داده.', array( 'status' => 500 ) );
		}

		$application_id = (int) $wpdb->insert_id;

		// 9. Append Audit Log Entry
		Negar_Club_Database::log_audit(
			'club_application',
			$application_id,
			'application:submit',
			null,
			array(
				'status'             => self::STATUS_PENDING_REVIEW,
				'application_number' => $application_number,
				'plan_id'            => $plan_id,
				'requested_ceiling'  => $requested_ceiling,
			),
			$user_id,
			'ثبت درخواست اولیه عضویت در باشگاه اقساطی توسط مشتری'
		);

		return array(
			'success' => true,
			'data'    => array(
				'application_number' => $application_number,
				'status'             => self::STATUS_PENDING_REVIEW,
				'status_label'       => self::get_status_label( self::STATUS_PENDING_REVIEW ),
				'plan_id'            => $plan_id,
				'plan_title'         => $plan_title,
				'requested_ceiling'  => $requested_ceiling,
				'created_at'         => current_time( 'c' ),
				'message'            => 'درخواست شما با موفقیت ثبت شد و در صف بررسی کارشناسان قرار گرفت.',
			),
		);
	}

	/**
	 * Transition an application to a new status following state machine rules.
	 *
	 * Uses optimistic concurrency locking (conditional status check) to prevent
	 * competing operator transitions from overwriting one another.
	 *
	 * @param string|int           $app_id_or_number Application ID or application_number.
	 * @param string               $new_status       Target status.
	 * @param int                  $actor_id         ID of operator/user making the transition.
	 * @param string               $operator_notes   Mandatory reason or internal explanatory notes.
	 * @param array<string, mixed> $additional_data  Optional payload (approved_ceiling, validity_days, customer_message).
	 * @return array<string, mixed>|WP_Error
	 */
	public static function transition_status( $app_id_or_number, $new_status, $actor_id, $operator_notes = '', $additional_data = array() ) {
		global $wpdb;

		$table = Negar_Club_Database::get_table_name( 'applications' );

		// 1. Fetch current application
		if ( is_numeric( $app_id_or_number ) ) {
			$app = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE id = %d LIMIT 1", (int) $app_id_or_number ) );
		} else {
			$app = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE application_number = %s LIMIT 1", sanitize_text_field( $app_id_or_number ) ) );
		}

		if ( ! $app ) {
			return new WP_Error( 'NOT_FOUND', 'درخواست مورد نظر یافت نشد.', array( 'status' => 404 ) );
		}

		$current_status = $app->status;

		// 2. Validate state machine transition
		if ( ! self::is_transition_allowed( $current_status, $new_status ) ) {
			return new WP_Error(
				'INVALID_STATE_TRANSITION',
				sprintf(
					'امکان تغییر وضعیت از "%s" به "%s" طبق قوانین سامانه وجود ندارد.',
					self::get_status_label( $current_status ),
					self::get_status_label( $new_status )
				),
				array( 'status' => 422 )
			);
		}

		$now              = current_time( 'mysql' );
		$update_data      = array(
			'status'     => $new_status,
			'updated_at' => $now,
		);
		$approved_ceiling = (int) $app->approved_ceiling;

		// 3. Status-specific validation & handling (Fix C)
		if ( self::STATUS_APPROVED === $new_status ) {
			$raw_ceiling = $additional_data['approved_ceiling'] ?? $app->requested_ceiling;
			$ceiling_val = Negar_Club_Validator::validate_amount( $raw_ceiling, 1, 10000000000 );
			if ( false === $ceiling_val ) {
				return new WP_Error( 'INVALID_CEILING', 'سقف اعتبار تأیید شده باید یک مبلغ مثبت معتبر باشد.', array( 'status' => 422 ) );
			}

			// Validate against plan ceiling (Fix C)
			$defaults     = function_exists( 'negar_theme_club_settings_defaults' ) ? negar_theme_club_settings_defaults() : array();
			$all_plans    = function_exists( 'negar_theme_club_setting' ) ? negar_theme_club_setting( 'plans', null, array() ) : ( $defaults['plans'] ?? array() );
			$plan         = $all_plans[ $app->plan_id ] ?? ( $defaults['plans'][ $app->plan_id ] ?? null );
			$plan_ceiling = isset( $plan['ceiling'] ) ? (int) $plan['ceiling'] : 0;

			if ( $plan_ceiling > 0 && $ceiling_val > $plan_ceiling ) {
				return new WP_Error(
					'EXCESSIVE_CEILING',
					sprintf( 'سقف مصوب (%s تومان) نمی‌تواند از سقف مجاز طرح (%s تومان) فراتر رود.', number_format( $ceiling_val ), number_format( $plan_ceiling ) ),
					array( 'status' => 422 )
				);
			}

			// Validate against requested ceiling (Fix C)
			if ( (int) $app->requested_ceiling > 0 && $ceiling_val > (int) $app->requested_ceiling ) {
				return new WP_Error(
					'EXCESSIVE_CEILING',
					sprintf( 'سقف مصوب (%s تومان) نمی‌تواند از سقف درخواستی مشتری (%s تومان) فراتر رود.', number_format( $ceiling_val ), number_format( $app->requested_ceiling ) ),
					array( 'status' => 422 )
				);
			}

			// Validate validity duration (between 1 and 90 days)
			$raw_validity_days = isset( $additional_data['validity_days'] ) ? (int) $additional_data['validity_days'] : 14;
			if ( $raw_validity_days <= 0 || $raw_validity_days > 90 ) {
				return new WP_Error( 'INVALID_VALIDITY_DAYS', 'مدت اعتبار مصوب باید بین ۱ تا ۹۰ روز باشد.', array( 'status' => 422 ) );
			}

			$approved_ceiling              = $ceiling_val;
			$expires_at                    = gmdate( 'Y-m-d H:i:s', time() + ( $raw_validity_days * DAY_IN_SECONDS ) );
			$update_data['approved_ceiling'] = $approved_ceiling;
			$update_data['reviewed_by']     = (int) $actor_id;
			$update_data['reviewed_at']     = $now;
			$update_data['expires_at']      = $expires_at;

			// Public message for customer
			$update_data['customer_message'] = sanitize_textarea_field( (string) ( $additional_data['customer_message'] ?? sprintf( 'درخواست شما برای خرید اقساطی تا سقف %s تومان مورد تأیید قرار گرفت.', number_format( $approved_ceiling ) ) ) );
		} elseif ( self::STATUS_REJECTED === $new_status ) {
			if ( empty( trim( (string) $operator_notes ) ) ) {
				return new WP_Error( 'MISSING_REASON', 'درج علت رد درخواست توسط کارشناس الزامی است.', array( 'status' => 422 ) );
			}
			$update_data['reviewed_by']      = (int) $actor_id;
			$update_data['reviewed_at']      = $now;
			$update_data['customer_message'] = sanitize_textarea_field( (string) ( $additional_data['customer_message'] ?? 'متأسفانه مدارک و شرایط هویتی شما برای طرح انتخابی مورد تأیید قرار نگرفت.' ) );
		}

		if ( ! empty( $operator_notes ) ) {
			$update_data['operator_notes'] = sanitize_textarea_field( (string) $operator_notes );
		}

		// In terminal states, release active lock tokens to allow future applications (Fix B)
		if ( in_array( $new_status, array( self::STATUS_REJECTED, self::STATUS_CANCELLED, self::STATUS_EXPIRED ), true ) ) {
			$update_data['active_lock_user'] = null;
			$update_data['active_lock_nid']  = null;
		}

		// 4. Conditional State Transition / Optimistic Concurrency Guard (Fix C)
		$set_clauses = array();
		$query_args  = array();
		foreach ( $update_data as $col => $val ) {
			if ( null === $val ) {
				$set_clauses[] = "`{$col}` = NULL";
			} else {
				$set_clauses[] = "`{$col}` = %s";
				$query_args[]  = $val;
			}
		}
		$set_sql = implode( ', ', $set_clauses );

		$query_args[] = (int) $app->id;
		$query_args[] = $current_status;

		$affected = $wpdb->query(
			$wpdb->prepare(
				"UPDATE `{$table}` SET {$set_sql} WHERE `id` = %d AND `status` = %s",
				$query_args
			)
		);

		if ( false === $affected ) {
			return new WP_Error( 'DB_UPDATE_ERROR', 'خطا در به‌روزرسانی وضعیت درخواست.', array( 'status' => 500 ) );
		}

		if ( 0 === $affected ) {
			// Verify if the status changed concurrently
			$fresh = $wpdb->get_row( $wpdb->prepare( "SELECT status FROM `{$table}` WHERE `id` = %d", (int) $app->id ) );
			if ( $fresh && $fresh->status !== $current_status ) {
				return new WP_Error(
					'CONCURRENT_STATE_CONFLICT',
					sprintf( 'وضعیت این درخواست همزمان توسط کارشناس دیگری به "%s" تغییر یافته است.', self::get_status_label( $fresh->status ) ),
					array( 'status' => 409 )
				);
			}
		}

		// 5. Append audit log ONLY for successfully committed transition (Fix C)
		$audit_id = Negar_Club_Database::log_audit(
			'club_application',
			(int) $app->id,
			"application:{$new_status}",
			array( 'status' => $current_status ),
			array(
				'status'           => $new_status,
				'approved_ceiling' => $approved_ceiling,
				'reviewed_by'      => (int) $actor_id,
			),
			(int) $actor_id,
			$operator_notes
		);

		return array(
			'success' => true,
			'data'    => array(
				'application_number' => $app->application_number,
				'previous_status'    => $current_status,
				'new_status'         => $new_status,
				'status_label'       => self::get_status_label( $new_status ),
				'approved_ceiling'   => $approved_ceiling,
				'reviewed_by'        => (int) $actor_id,
				'expires_at'         => $update_data['expires_at'] ?? $app->expires_at,
				'audit_event_id'     => $audit_id,
				'message'            => sprintf( 'وضعیت درخواست با موفقیت به "%s" تغییر یافت.', self::get_status_label( $new_status ) ),
			),
		);
	}

	/**
	 * Query application details for a user with strict anti-IDOR isolation and note privacy.
	 *
	 * Internal operator notes are never disclosed to customers (Fix A).
	 *
	 * @param string|int $app_id_or_number Application ID or application_number.
	 * @param int        $requesting_user_id ID of requesting user.
	 * @return array<string, mixed>|WP_Error
	 */
	public static function get_application_for_user( $app_id_or_number, $requesting_user_id ) {
		global $wpdb;

		$table = Negar_Club_Database::get_table_name( 'applications' );

		if ( is_numeric( $app_id_or_number ) ) {
			$app = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE id = %d LIMIT 1", (int) $app_id_or_number ) );
		} else {
			$app = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table}` WHERE application_number = %s LIMIT 1", sanitize_text_field( $app_id_or_number ) ) );
		}

		if ( ! $app ) {
			return new WP_Error( 'NOT_FOUND', 'درخواست مورد نظر یافت نشد.', array( 'status' => 404 ) );
		}

		$requesting_user_id = (int) $requesting_user_id;
		$is_owner           = ( (int) $app->user_id === $requesting_user_id );
		$is_operator        = Negar_Club_Roles::current_user_can_operate( $requesting_user_id );

		// Anti-IDOR Authorization Check
		if ( ! $is_owner && ! $is_operator ) {
			return new WP_Error(
				'ACCESS_DENIED',
				'شما مجاز به مشاهده این درخواست نمی‌باشید.',
				array( 'status' => 403 )
			);
		}

		$response = array(
			'application_number' => $app->application_number,
			'plan_id'            => $app->plan_id,
			'status'             => $app->status,
			'status_label'       => self::get_status_label( $app->status ),
			'requested_ceiling'  => (int) $app->requested_ceiling,
			'approved_ceiling'   => (int) $app->approved_ceiling,
			'contact_phone'      => $app->contact_phone,
			'applicant_notes'    => $app->applicant_notes,
			'customer_message'   => $app->customer_message,
			'reviewed_at'        => $app->reviewed_at,
			'expires_at'         => $app->expires_at,
			'created_at'         => $app->created_at,
		);

		// Expose private operator notes ONLY to authorized operators (Fix A)
		if ( $is_operator ) {
			$response['operator_notes'] = $app->operator_notes;
		}

		return $response;
	}

	/**
	 * Retrieve all applications belonging to a specific customer.
	 *
	 * Private operator notes are completely omitted from customer responses (Fix A).
	 *
	 * @param int $user_id Customer user ID.
	 * @return array<array<string, mixed>>
	 */
	public static function get_user_applications( $user_id ) {
		global $wpdb;

		$table   = Negar_Club_Database::get_table_name( 'applications' );
		$user_id = (int) $user_id;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT application_number, plan_id, status, requested_ceiling, approved_ceiling,
				        customer_message, reviewed_at, expires_at, created_at
				 FROM `{$table}`
				 WHERE user_id = %d
				 ORDER BY created_at DESC",
				$user_id
			)
		);

		if ( ! $rows ) {
			return array();
		}

		$results = array();
		foreach ( $rows as $row ) {
			$results[] = array(
				'application_number' => $row->application_number,
				'plan_id'            => $row->plan_id,
				'status'             => $row->status,
				'status_label'       => self::get_status_label( $row->status ),
				'requested_ceiling'  => (int) $row->requested_ceiling,
				'approved_ceiling'   => (int) $row->approved_ceiling,
				'customer_message'   => $row->customer_message,
				'reviewed_at'        => $row->reviewed_at,
				'expires_at'         => $row->expires_at,
				'created_at'         => $row->created_at,
			);
		}

		return $results;
	}

	/**
	 * Query applications for operator administration review with search and filtering.
	 *
	 * @param array<string, mixed> $args Filter parameters (status, search, page, per_page).
	 * @return array<string, mixed>
	 */
	public static function get_admin_applications( $args = array() ) {
		global $wpdb;

		$table    = Negar_Club_Database::get_table_name( 'applications' );
		$status   = ! empty( $args['status'] ) ? sanitize_key( $args['status'] ) : '';
		$search   = ! empty( $args['search'] ) ? sanitize_text_field( $args['search'] ) : '';
		$page     = max( 1, (int) ( $args['page'] ?? 1 ) );
		$per_page = max( 1, min( 100, (int) ( $args['per_page'] ?? 20 ) ) );
		$offset   = ( $page - 1 ) * $per_page;

		$where_clauses = array( '1=1' );
		$query_params  = array();

		if ( ! empty( $status ) && 'all' !== $status ) {
			$where_clauses[] = 'status = %s';
			$query_params[]  = $status;
		}

		if ( ! empty( $search ) ) {
			$where_clauses[] = '(application_number LIKE %s OR contact_phone LIKE %s)';
			$like            = '%' . $wpdb->esc_like( $search ) . '%';
			$query_params[]  = $like;
			$query_params[]  = $like;
		}

		$where_sql = implode( ' AND ', $where_clauses );

		// Count total
		if ( ! empty( $query_params ) ) {
			$count_sql = $wpdb->prepare( "SELECT COUNT(*) FROM `{$table}` WHERE {$where_sql}", $query_params );
		} else {
			$count_sql = "SELECT COUNT(*) FROM `{$table}` WHERE {$where_sql}";
		}
		$total = (int) $wpdb->get_var( $count_sql );

		// Select rows
		$order_sql = 'ORDER BY created_at DESC LIMIT %d OFFSET %d';
		$query_params[] = $per_page;
		$query_params[] = $offset;

		$select_sql = $wpdb->prepare(
			"SELECT id, application_number, user_id, plan_id, status, requested_ceiling, approved_ceiling,
			        contact_phone, income_bracket, applicant_notes, operator_notes, customer_message,
			        reviewed_by, reviewed_at, expires_at, created_at, updated_at
			 FROM `{$table}`
			 WHERE {$where_sql}
			 {$order_sql}",
			$query_params
		);

		$rows = $wpdb->get_results( $select_sql );

		$items = array();
		if ( $rows ) {
			foreach ( $rows as $row ) {
				$user_name = '';
				if ( function_exists( 'get_userdata' ) ) {
					$u = get_userdata( (int) $row->user_id );
					if ( $u ) {
						$user_name = $u->display_name ?: $u->user_login;
					}
				}

				$items[] = array(
					'id'                 => (int) $row->id,
					'application_number' => $row->application_number,
					'user_id'            => (int) $row->user_id,
					'user_name'          => $user_name,
					'plan_id'            => $row->plan_id,
					'status'             => $row->status,
					'status_label'       => self::get_status_label( $row->status ),
					'requested_ceiling'  => (int) $row->requested_ceiling,
					'approved_ceiling'   => (int) $row->approved_ceiling,
					'contact_phone'      => $row->contact_phone,
					'income_bracket'     => $row->income_bracket,
					'applicant_notes'    => $row->applicant_notes,
					'operator_notes'     => $row->operator_notes,
					'customer_message'   => $row->customer_message,
					'reviewed_by'        => (int) $row->reviewed_by,
					'reviewed_at'        => $row->reviewed_at,
					'expires_at'         => $row->expires_at,
					'created_at'         => $row->created_at,
					'updated_at'         => $row->updated_at,
				);
			}
		}

		return array(
			'items'       => $items,
			'total'       => $total,
			'total_pages' => (int) ceil( $total / $per_page ),
			'page'        => $page,
			'per_page'    => $per_page,
		);
	}

	/**
	 * Register REST API routes for customer applications and operator review.
	 *
	 * Idempotent: protects against double registration (Fix D).
	 *
	 * @return void
	 */
	public static function register_rest_routes() {
		static $routes_registered = false;
		if ( $routes_registered ) {
			return;
		}
		$routes_registered = true;

		// 1. Submit Application
		register_rest_route(
			'negar-club/v1',
			'/applications',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( __CLASS__, 'handle_rest_submit' ),
					'permission_callback' => function() {
						return is_user_logged_in();
					},
				),
			)
		);

		// 2. Customer Application History (Anti-IDOR)
		register_rest_route(
			'negar-club/v1',
			'/my-applications',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( __CLASS__, 'handle_rest_my_applications' ),
					'permission_callback' => function() {
						return is_user_logged_in();
					},
				),
			)
		);

		// 3. Single Application Status Query (Anti-IDOR)
		register_rest_route(
			'negar-club/v1',
			'/applications/(?P<application_number>[a-zA-Z0-9\-_]+)',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( __CLASS__, 'handle_rest_get_application' ),
					'permission_callback' => function() {
						return is_user_logged_in();
					},
				),
			)
		);

		// 4. Operator Review Transition
		register_rest_route(
			'negar-club/v1',
			'/operator/review',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( __CLASS__, 'handle_rest_operator_review' ),
					'permission_callback' => array( 'Negar_Club_Roles', 'current_user_can_operate' ),
				),
			)
		);

		// 5. Operator Application List
		register_rest_route(
			'negar-club/v1',
			'/admin/applications',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( __CLASS__, 'handle_rest_admin_list' ),
					'permission_callback' => array( 'Negar_Club_Roles', 'current_user_can_operate' ),
				),
			)
		);
	}

	/**
	 * REST handler: Submit Application.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_submit( $request ) {
		$params = $request->get_json_params();
		if ( empty( $params ) || ! is_array( $params ) ) {
			$params = $request->get_params();
		}

		$user_id = get_current_user_id();
		$result  = self::submit_application( $params, $user_id );

		if ( is_wp_error( $result ) ) {
			$status = $result->get_error_data()['status'] ?? 400;
			return new WP_REST_Response(
				array(
					'success' => false,
					'code'    => $result->get_error_code(),
					'message' => $result->get_error_message(),
					'data'    => $result->get_error_data(),
				),
				$status
			);
		}

		return new WP_REST_Response( $result, 201 );
	}

	/**
	 * REST handler: Customer Application History.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_my_applications( $request ) {
		$user_id      = get_current_user_id();
		$applications = self::get_user_applications( $user_id );

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $applications,
			),
			200
		);
	}

	/**
	 * REST handler: Single Application Query.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_get_application( $request ) {
		$app_number = $request->get_param( 'application_number' );
		$user_id    = get_current_user_id();

		$result = self::get_application_for_user( $app_number, $user_id );
		if ( is_wp_error( $result ) ) {
			$status = $result->get_error_data()['status'] ?? 400;
			return new WP_REST_Response(
				array(
					'success' => false,
					'code'    => $result->get_error_code(),
					'message' => $result->get_error_message(),
				),
				$status
			);
		}

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $result,
			),
			200
		);
	}

	/**
	 * REST handler: Operator Review Transition.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_operator_review( $request ) {
		$params = $request->get_json_params();
		if ( empty( $params ) || ! is_array( $params ) ) {
			$params = $request->get_params();
		}

		$app_number      = sanitize_text_field( (string) ( $params['application_number'] ?? '' ) );
		$transition_verb = sanitize_key( (string) ( $params['transition'] ?? '' ) );
		$operator_notes  = sanitize_textarea_field( (string) ( $params['operator_notes'] ?? '' ) );
		$actor_id        = get_current_user_id();

		// Map transition verbs to canonical statuses
		$target_status = '';
		if ( 'approve' === $transition_verb ) {
			$target_status = self::STATUS_APPROVED;
		} elseif ( 'reject' === $transition_verb ) {
			$target_status = self::STATUS_REJECTED;
		} elseif ( 'cancel' === $transition_verb ) {
			$target_status = self::STATUS_CANCELLED;
		} elseif ( 'under_review' === $transition_verb ) {
			$target_status = self::STATUS_UNDER_REVIEW;
		} else {
			$target_status = $transition_verb;
		}

		$result = self::transition_status( $app_number, $target_status, $actor_id, $operator_notes, $params );

		if ( is_wp_error( $result ) ) {
			$status = $result->get_error_data()['status'] ?? 400;
			return new WP_REST_Response(
				array(
					'success' => false,
					'code'    => $result->get_error_code(),
					'message' => $result->get_error_message(),
				),
				$status
			);
		}

		return new WP_REST_Response( $result, 200 );
	}

	/**
	 * REST handler: Operator List Applications.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function handle_rest_admin_list( $request ) {
		$args = array(
			'status'   => $request->get_param( 'status' ),
			'search'   => $request->get_param( 'search' ),
			'page'     => $request->get_param( 'page' ),
			'per_page' => $request->get_param( 'per_page' ),
		);

		$result = self::get_admin_applications( $args );

		return new WP_REST_Response(
			array(
				'success' => true,
				'data'    => $result,
			),
			200
		);
	}
}
