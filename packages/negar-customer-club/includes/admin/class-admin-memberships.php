<?php
/**
 * Operator VIP Management Admin Interface for Negar Customer Club.
 *
 * Provides a responsive, accessible Persian RTL dashboard for store operators
 * to search, filter, inspect, and manually record merchandise usage, deposit applications,
 * offline installment collections, and credit adjustments for VIP members.
 *
 * @package NegarCustomerClub
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Admin_Memberships {

	/**
	 * Register the WordPress admin menu page.
	 */
	public static function register_admin_menu() {
		if ( function_exists( 'WC' ) ) {
			add_submenu_page(
				'woocommerce',
				'مدیریت اعضای VIP باشگاه نگار',
				'اعضای VIP باشگاه',
				'negar_club_operator',
				'negar-club-memberships',
				array( __CLASS__, 'render_admin_page' )
			);
		} else {
			add_menu_page(
				'مدیریت اعضای VIP باشگاه نگار',
				'اعضای VIP باشگاه',
				'negar_club_operator',
				'negar-club-memberships',
				array( __CLASS__, 'render_admin_page' ),
				'dashicons-star-filled',
				57
			);
		}
	}

	/**
	 * Validate and sanitize strict integer monetary inputs.
	 *
	 * Rejects non-numeric, negative, decimal, or overflowing values.
	 * Never silently clamps invalid inputs to zero.
	 *
	 * @param mixed  $raw_value   Submitted value.
	 * @param string $field_label User-facing field name for error reporting.
	 * @param bool   $allow_zero  Whether zero is permitted.
	 * @return int Sanitized positive integer.
	 * @throws InvalidArgumentException When validation fails.
	 */
	public static function validate_monetary_input( $raw_value, $field_label, $allow_zero = false ) {
		if ( null === $raw_value || '' === trim( (string) $raw_value ) ) {
			throw new InvalidArgumentException( sprintf( 'لطفاً %s را وارد نمایید.', $field_label ) );
		}

		$clean = trim( (string) $raw_value );
		// Convert Persian and Arabic digits to Latin
		$clean = strtr( $clean, array(
			'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
			'۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
			'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
			'٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
		) );

		if ( ! is_numeric( $clean ) || preg_match( '/[^0-9\-]/', $clean ) ) {
			throw new InvalidArgumentException( sprintf( '%s باید یک عدد معتبر باشد.', $field_label ) );
		}

		$float_val = (float) $clean;
		if ( floor( $float_val ) != $float_val ) {
			throw new InvalidArgumentException( sprintf( '%s نباید دارای رقم اعشار باشد.', $field_label ) );
		}

		if ( $float_val < 0 ) {
			throw new InvalidArgumentException( sprintf( '%s نمی‌تواند منفی باشد.', $field_label ) );
		}

		if ( ! $allow_zero && 0.0 === $float_val ) {
			throw new InvalidArgumentException( sprintf( '%s باید بزرگ‌تر از صفر باشد.', $field_label ) );
		}

		if ( $float_val > 1000000000000.0 ) {
			throw new InvalidArgumentException( sprintf( '%s فراتر از سقف مجاز سیستم است.', $field_label ) );
		}

		return (int) $float_val;
	}

	/**
	 * Get Persian label for a membership status.
	 *
	 * @param string $status Status code.
	 * @return string Label.
	 */
	public static function get_status_label( $status ) {
		switch ( $status ) {
			case 'active':
				return 'فعال';
			case 'suspended':
				return 'معلق';
			case 'expired':
				return 'منقضی';
			case 'pending_operator_review':
				return 'در انتظار بررسی کارشناس';
			case 'refunded_pending_reconciliation':
				return 'استرداد / تعلیق مالی';
			default:
				return (string) $status;
		}
	}

	/**
	 * Handle admin form submissions for operator balance adjustments and actions.
	 */
	public static function handle_admin_actions() {
		if ( ( ! is_admin() && ! defined( 'NEGAR_CLUB_TEST_PREFIX' ) ) || ! isset( $_POST['negar_club_membership_action'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ) ) {
			wp_die( 'درخواست نامعتبر یا منقضی شده است. لطفاً صفحه را تجدید نمایید.' );
		}

		if ( ! Negar_Club_Roles::current_user_can_operate() ) {
			wp_die( 'شما دسترسی لازم برای مدیریت اعضای VIP را ندارید.' );
		}

		$action      = sanitize_key( (string) $_POST['negar_club_membership_action'] );
		$operator_id = get_current_user_id();

		// Action: Explicit authorized VIP product creation/mapping (Fix D)
		if ( 'setup_vip_products' === $action ) {
			$mapping = Negar_Club_VIP_Products::ensure_vip_products();
			if ( ! empty( $mapping ) ) {
				add_settings_error( 'negar_club_membership_notices', 'setup_success', sprintf( 'محصولات VIP با موفقیت در ووکامرس بررسی و همگام‌سازی شدند (%d محصول فعال).', count( $mapping ) ), 'success' );
			} else {
				add_settings_error( 'negar_club_membership_notices', 'setup_error', 'خطا در ایجاد یا بررسی محصولات VIP.', 'error' );
			}
			return;
		}

		$membership_id = (int) ( $_POST['membership_id'] ?? 0 );
		if ( $membership_id <= 0 ) {
			add_settings_error( 'negar_club_membership_notices', 'invalid_id', 'شناسه عضویت معتبر نمی‌باشد.', 'error' );
			return;
		}

		$reason = sanitize_text_field( (string) ( $_POST['reason'] ?? '' ) );
		if ( empty( $reason ) ) {
			add_settings_error( 'negar_club_membership_notices', 'missing_reason', 'ذکر دلیل یا توضیحات برای کلیه اقدامات دستی کارشناس الزامی است.', 'error' );
			return;
		}

		// Idempotency token check (Fix B): Prevent duplicate resubmission
		$submission_token = sanitize_text_field( (string) ( $_POST['negar_club_submission_token'] ?? '' ) );
		if ( empty( $submission_token ) ) {
			add_settings_error( 'negar_club_membership_notices', 'missing_submission_token', 'توکن یکتای ارسال عملیات الزامی است و نمی‌تواند خالی باشد.', 'error' );
			return;
		}

		if ( Negar_Club_Database::is_submission_token_used( $submission_token ) ) {
			add_settings_error( 'negar_club_membership_notices', 'duplicate_submission', 'این عملیات قبلاً ارسال و پردازش شده است. جهت جلوگیری از دوباره‌کاری، درخواست تکراری مسدود شد.', 'error' );
			return;
		}

		// Atomic Transaction with row-level locking (Fix B)
		Negar_Club_Database::begin_transaction();

		try {
			$membership = Negar_Club_Database::get_membership_for_update( $membership_id );
			if ( ! $membership ) {
				throw new Exception( 'عضویت مورد نظر یافت نشد.' );
			}

			// Durable DB claim for idempotency inside the transaction
			Negar_Club_Database::claim_operation_token( $submission_token, $action, $membership_id, $operator_id );

			$old_state = (array) $membership;

			switch ( $action ) {

				// 1. Record In-Store Merchandise Purchase
				case 'record_purchase':
					// Fix C: Block ordinary purchases for non-active memberships
					if ( 'active' !== $membership->status ) {
						throw new Exception( sprintf( 'ثبت خرید کالا برای عضویت در وضعیت «%s» مجاز نمی‌باشد. عضویت باید در وضعیت فعال باشد.', self::get_status_label( $membership->status ) ) );
					}

					$purchase_amount = self::validate_monetary_input( $_POST['purchase_amount'] ?? null, 'مبلغ خرید کالا', false );
					$receipt_ref     = sanitize_text_field( (string) ( $_POST['receipt_ref'] ?? '' ) );
					$apply_deposit   = isset( $_POST['deposit_to_apply'] ) && '' !== trim( (string) $_POST['deposit_to_apply'] )
						? self::validate_monetary_input( $_POST['deposit_to_apply'], 'کسر از بیعانه', true )
						: 0;
					$record_as_debt  = ! empty( $_POST['record_as_debt'] );

					if ( $purchase_amount > (int) $membership->available_purchase_capacity ) {
						throw new Exception( sprintf( 'مبلغ خرید (%s تومان) نمی‌تواند از ظرفیت خرید باقیمانده (%s تومان) بیشتر باشد.', number_format( $purchase_amount ), number_format( (int) $membership->available_purchase_capacity ) ) );
					}

					if ( $apply_deposit > (int) $membership->unused_deposit ) {
						throw new Exception( sprintf( 'مبلغ بیعانه اعمالی (%s تومان) نمی‌تواند از مانده پیش‌پرداخت (%s تومان) بیشتر باشد.', number_format( $apply_deposit ), number_format( (int) $membership->unused_deposit ) ) );
					}

					if ( $apply_deposit > $purchase_amount ) {
						throw new Exception( 'مبلغ بیعانه نمی‌تواند از کل مبلغ خرید کالا بیشتر باشد.' );
					}

					$new_used_purchase = (int) $membership->used_purchase_amount + $purchase_amount;
					$new_capacity      = (int) $membership->available_purchase_capacity - $purchase_amount;
					$new_applied_dep   = (int) $membership->applied_deposit + $apply_deposit;
					$new_unused_dep    = (int) $membership->unused_deposit - $apply_deposit;

					// Fix A: Prepayment invariant check
					if ( ( $new_applied_dep + $new_unused_dep ) !== (int) $membership->prepayment_amount ) {
						throw new Exception( 'ناسازگاری تراز بیعانه: مجموع بیعانه مصرف‌شده و باقیمانده با کل پیش‌پرداخت همخوانی ندارد.' );
					}

					$remaining_unpaid = $purchase_amount - $apply_deposit;
					$new_offline_debt = (int) $membership->offline_debt_amount + ( $record_as_debt ? $remaining_unpaid : 0 );

					$note_addition = sprintf(
						'ثبت خرید حضوری: %s تومان (رسید: %s). بیعانه کسر شده: %s تومان. بدهی اقساط افزوده شده: %s تومان. دلیل: %s',
						number_format( $purchase_amount ),
						$receipt_ref ?: 'ثبت نشده',
						number_format( $apply_deposit ),
						number_format( $record_as_debt ? $remaining_unpaid : 0 ),
						$reason
					);

					$updated = Negar_Club_Database::update_membership(
						$membership_id,
						array(
							'used_purchase_amount'        => $new_used_purchase,
							'available_purchase_capacity' => $new_capacity,
							'applied_deposit'             => $new_applied_dep,
							'unused_deposit'              => $new_unused_dep,
							'offline_debt_amount'         => $new_offline_debt,
							'notes'                       => $note_addition,
							'last_operator_id'            => $operator_id,
						)
					);

					if ( ! $updated ) {
						throw new Exception( 'خطا در ثبت اطلاعات خرید در پایگاه داده.' );
					}

					$audit_id = Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:record_purchase',
						$old_state,
						array(
							'purchase_amount' => $purchase_amount,
							'receipt_ref'     => $receipt_ref,
							'applied_deposit' => $apply_deposit,
							'record_as_debt'  => $record_as_debt,
							'new_debt'        => $new_offline_debt,
						),
						$operator_id,
						$reason
					);

					if ( false === $audit_id ) {
						throw new Exception( 'خطا در ثبت لاگ ممیزی عملیات در پایگاه داده.' );
					}

					if ( ! empty( $submission_token ) ) {
						Negar_Club_Database::record_submission_token( $submission_token, $operator_id );
					}

					Negar_Club_Database::commit();
					add_settings_error( 'negar_club_membership_notices', 'purchase_success', 'خرید حضوری کالا با موفقیت ثبت و ترازهای عضویت به‌روزرسانی شد.', 'success' );
					break;

				// 2. Apply Deposit Toward Offline Purchase
				case 'apply_deposit':
					// Fix C: Block for non-active memberships
					if ( 'active' !== $membership->status ) {
						throw new Exception( sprintf( 'امکان کسر بیعانه برای عضویت در وضعیت «%s» وجود ندارد.', self::get_status_label( $membership->status ) ) );
					}

					$deposit_amount = self::validate_monetary_input( $_POST['deposit_amount'] ?? null, 'مبلغ اعمال بیعانه', false );

					if ( $deposit_amount > (int) $membership->unused_deposit ) {
						throw new Exception( sprintf( 'مبلغ انتخابی (%s تومان) از مانده پیش‌پرداخت آزاد (%s تومان) بیشتر است.', number_format( $deposit_amount ), number_format( (int) $membership->unused_deposit ) ) );
					}

					$new_applied = (int) $membership->applied_deposit + $deposit_amount;
					$new_unused  = (int) $membership->unused_deposit - $deposit_amount;

					// Fix A: Prepayment invariant check
					if ( ( $new_applied + $new_unused ) !== (int) $membership->prepayment_amount ) {
						throw new Exception( 'ناسازگاری تراز بیعانه: مجموع بیعانه مصرف‌شده و باقیمانده با کل پیش‌پرداخت همخوانی ندارد.' );
					}

					$updated = Negar_Club_Database::update_membership(
						$membership_id,
						array(
							'applied_deposit'  => $new_applied,
							'unused_deposit'   => $new_unused,
							'notes'            => 'کسر پیش‌پرداخت: ' . number_format( $deposit_amount ) . ' تومان. دلیل: ' . $reason,
							'last_operator_id' => $operator_id,
						)
					);

					if ( ! $updated ) {
						throw new Exception( 'خطا در ثبت کسر بیعانه در پایگاه داده.' );
					}

					$audit_id = Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:apply_deposit',
						$old_state,
						array( 'applied_amount' => $deposit_amount, 'new_unused' => $new_unused ),
						$operator_id,
						$reason
					);

					if ( false === $audit_id ) {
						throw new Exception( 'خطا در ثبت لاگ ممیزی عملیات در پایگاه داده.' );
					}

					if ( ! empty( $submission_token ) ) {
						Negar_Club_Database::record_submission_token( $submission_token, $operator_id );
					}

					Negar_Club_Database::commit();
					add_settings_error( 'negar_club_membership_notices', 'deposit_applied', 'پیش‌پرداخت با موفقیت اعمال گردید.', 'success' );
					break;

				// 3. Record Offline Installment Collection Receipt
				case 'record_collection':
					$collected_amount = self::validate_monetary_input( $_POST['collected_amount'] ?? null, 'مبلغ قسط دریافتی', false );
					$collection_ref   = sanitize_text_field( (string) ( $_POST['collection_ref'] ?? '' ) );

					// Fix C: Reject collected amount greater than recorded debt. Never silently clamp!
					if ( $collected_amount > (int) $membership->offline_debt_amount ) {
						throw new Exception( sprintf(
							'مبلغ وصولی (%s تومان) نمی‌تواند از کل بدهی اقساط باقیمانده (%s تومان) بیشتر باشد. (ثبت مبلغ مازاد مسدود شد)',
							number_format( $collected_amount ),
							number_format( (int) $membership->offline_debt_amount )
						) );
					}

					$new_debt = (int) $membership->offline_debt_amount - $collected_amount;

					$note = sprintf(
						'ثبت دریافت قسط حضوری: %s تومان (رسید: %s). مانده بدهی اقساط: %s تومان. دلیل: %s',
						number_format( $collected_amount ),
						$collection_ref ?: 'ثبت نشده',
						number_format( $new_debt ),
						$reason
					);

					$updated = Negar_Club_Database::update_membership(
						$membership_id,
						array(
							'offline_debt_amount' => $new_debt,
							'notes'               => $note,
							'last_operator_id'    => $operator_id,
						)
					);

					if ( ! $updated ) {
						throw new Exception( 'خطا در ثبت وصول قسط در پایگاه داده.' );
					}

					$audit_id = Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:record_collection',
						$old_state,
						array( 'collected_amount' => $collected_amount, 'receipt_ref' => $collection_ref, 'remaining_debt' => $new_debt ),
						$operator_id,
						$reason
					);

					if ( false === $audit_id ) {
						throw new Exception( 'خطا در ثبت لاگ ممیزی عملیات در پایگاه داده.' );
					}

					if ( ! empty( $submission_token ) ) {
						Negar_Club_Database::record_submission_token( $submission_token, $operator_id );
					}

					Negar_Club_Database::commit();
					add_settings_error( 'negar_club_membership_notices', 'collection_success', 'دریافت قسط حضوری با موفقیت ثبت شد.', 'success' );
					break;

				// 4. Adjust Balances / Status / Limits (Routine adjustment)
				case 'adjust_balances':
					// Notice: unused_deposit is NOT editable here (Fix A). Prepayments remain strictly immutable.
					$new_ceiling = isset( $_POST['purchase_ceiling'] )
						? self::validate_monetary_input( $_POST['purchase_ceiling'], 'سقف خرید کالا', true )
						: (int) $membership->purchase_ceiling;

					$new_capacity = isset( $_POST['available_purchase_capacity'] )
						? self::validate_monetary_input( $_POST['available_purchase_capacity'], 'ظرفیت خرید باقیمانده', true )
						: (int) $membership->available_purchase_capacity;

					$new_debt = isset( $_POST['offline_debt_amount'] )
						? self::validate_monetary_input( $_POST['offline_debt_amount'], 'بدهی اقساط حضوری', true )
						: (int) $membership->offline_debt_amount;

					$new_status = sanitize_key( (string) ( $_POST['status'] ?? $membership->status ) );
					$allowed_statuses = array( 'active', 'suspended', 'expired', 'pending_operator_review', 'refunded_pending_reconciliation' );
					if ( ! in_array( $new_status, $allowed_statuses, true ) ) {
						$new_status = $membership->status;
					}

					// Fix C: Available purchase capacity cannot exceed ceiling
					if ( $new_capacity > $new_ceiling ) {
						throw new Exception( sprintf( 'ظرفیت خرید باقیمانده (%s تومان) نمی‌تواند از کل سقف خرید (%s تومان) بیشتر باشد.', number_format( $new_capacity ), number_format( $new_ceiling ) ) );
					}

					$updated = Negar_Club_Database::update_membership(
						$membership_id,
						array(
							'purchase_ceiling'            => $new_ceiling,
							'available_purchase_capacity' => $new_capacity,
							'offline_debt_amount'         => $new_debt,
							'status'                      => $new_status,
							'notes'                       => 'تنظیم دستی توسط کارشناس: ' . $reason,
							'last_operator_id'            => $operator_id,
						)
					);

					if ( ! $updated ) {
						throw new Exception( 'خطا در ثبت تنظیمات دستی ترازها در پایگاه داده.' );
					}

					$audit_id = Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:adjust_balances',
						$old_state,
						array(
							'ceiling'  => $new_ceiling,
							'capacity' => $new_capacity,
							'debt'     => $new_debt,
							'status'   => $new_status,
						),
						$operator_id,
						$reason
					);

					if ( false === $audit_id ) {
						throw new Exception( 'خطا در ثبت لاگ ممیزی عملیات در پایگاه داده.' );
					}

					if ( ! empty( $submission_token ) ) {
						Negar_Club_Database::record_submission_token( $submission_token, $operator_id );
					}

					Negar_Club_Database::commit();
					add_settings_error( 'negar_club_membership_notices', 'adjust_success', 'ترازها و وضعیت عضویت با موفقیت به‌روزرسانی شدند.', 'success' );
					break;

				// 5. Exceptional Accounting Correction (Fix A)
				case 'exceptional_deposit_correction':
					if ( ! current_user_can( 'manage_options' ) ) {
						throw new Exception( 'اصلاح استثنایی حسابداری تنها توسط مدیر ارشد مجاز است.' );
					}

					$new_unused_dep  = self::validate_monetary_input( $_POST['new_unused_deposit'] ?? null, 'مانده پیش‌پرداخت آزاد', true );
					$new_applied_dep = self::validate_monetary_input( $_POST['new_applied_deposit'] ?? null, 'پیش‌پرداخت کسر شده', true );

					// Prepayment conservation invariant: applied + unused <= verified prepayment
					if ( ( $new_unused_dep + $new_applied_dep ) > (int) $membership->prepayment_amount ) {
						throw new Exception( sprintf(
							'مجموع بیعانه آزاد (%s تومان) و مصرف‌شده (%s تومان) نمی‌تواند از کل پیش‌پرداخت تایید شده مشتری (%s تومان) بیشتر باشد.',
							number_format( $new_unused_dep ),
							number_format( $new_applied_dep ),
							number_format( (int) $membership->prepayment_amount )
						) );
					}

					$updated = Negar_Club_Database::update_membership(
						$membership_id,
						array(
							'unused_deposit'   => $new_unused_dep,
							'applied_deposit'  => $new_applied_dep,
							'notes'            => 'اصلاح استثنایی حسابداری: ' . $reason,
							'last_operator_id' => $operator_id,
						)
					);

					if ( ! $updated ) {
						throw new Exception( 'خطا در ثبت اصلاح حسابداری در پایگاه داده.' );
					}

					$audit_id = Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:exceptional_correction',
						$old_state,
						array(
							'old_unused'  => (int) $membership->unused_deposit,
							'new_unused'  => $new_unused_dep,
							'old_applied' => (int) $membership->applied_deposit,
							'new_applied' => $new_applied_dep,
						),
						$operator_id,
						$reason
					);

					if ( false === $audit_id ) {
						throw new Exception( 'خطا در ثبت لاگ ممیزی عملیات در پایگاه داده.' );
					}

					if ( ! empty( $submission_token ) ) {
						Negar_Club_Database::record_submission_token( $submission_token, $operator_id );
					}

					Negar_Club_Database::commit();
					add_settings_error( 'negar_club_membership_notices', 'correction_success', 'اصلاح استثنایی حسابداری با موفقیت در سوابق ممیزی ثبت گردید.', 'success' );
					break;

				default:
					throw new Exception( 'عملیات درخواستی معتبر نمی‌باشد.' );
			}
		} catch ( Exception $e ) {
			Negar_Club_Database::rollback();
			add_settings_error( 'negar_club_membership_notices', 'action_failed', $e->getMessage(), 'error' );
		}
	}

	/**
	 * Render the admin management view.
	 */
	public static function render_admin_page() {
		if ( ! Negar_Club_Roles::current_user_can_operate() ) {
			wp_die( 'شما اجازه دسترسی به این صفحه را ندارید.' );
		}

		$view_id = isset( $_GET['membership_id'] ) ? (int) $_GET['membership_id'] : 0;
		if ( $view_id > 0 ) {
			self::render_detail_view( $view_id );
		} else {
			self::render_list_view();
		}
	}

	/**
	 * Render the VIP members list view with filters and search.
	 */
	private static function render_list_view() {
		$current_status = sanitize_key( (string) ( $_GET['status'] ?? '' ) );
		$current_plan   = sanitize_key( (string) ( $_GET['plan_id'] ?? '' ) );
		$search_query   = sanitize_text_field( (string) ( $_GET['s'] ?? '' ) );

		$args = array(
			'status'  => $current_status,
			'plan_id' => $current_plan,
			'search'  => $search_query,
			'limit'   => 30,
		);

		$memberships = Negar_Club_Database::query_memberships( $args );

		?>
		<div class="wrap negar-club-admin-wrap" dir="rtl">
			<h1 class="wp-heading-inline">مدیریت اعضای VIP باشگاه مشتریان</h1>
			<hr class="wp-header-end">

			<?php settings_errors( 'negar_club_membership_notices' ); ?>

			<div class="negar-club-admin-card" style="margin-top: 15px; background: #fff; border: 1px solid #ccd0d4; padding: 15px; border-radius: 6px;">
				<form method="get" action="">
					<input type="hidden" name="page" value="negar-club-memberships">
					<div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
						<div>
							<label for="status-filter"><strong>وضعیت:</strong></label>
							<select name="status" id="status-filter">
								<option value="">همه وضعیت‌ها</option>
								<option value="active" <?php selected( $current_status, 'active' ); ?>>فعال</option>
								<option value="suspended" <?php selected( $current_status, 'suspended' ); ?>>معلق</option>
								<option value="expired" <?php selected( $current_status, 'expired' ); ?>>منقضی</option>
								<option value="pending_operator_review" <?php selected( $current_status, 'pending_operator_review' ); ?>>نیازمند بررسی</option>
								<option value="refunded_pending_reconciliation" <?php selected( $current_status, 'refunded_pending_reconciliation' ); ?>>استرداد / تعلیق مالی</option>
							</select>
						</div>

						<div>
							<label for="plan-filter"><strong>طرح:</strong></label>
							<select name="plan_id" id="plan-filter">
								<option value="">همه طرح‌ها</option>
								<option value="negar_aghaz" <?php selected( $current_plan, 'negar_aghaz' ); ?>>نگار آغاز</option>
								<option value="negar_plus" <?php selected( $current_plan, 'negar_plus' ); ?>>نگار پلاس</option>
								<option value="negar_vizhe" <?php selected( $current_plan, 'negar_vizhe' ); ?>>نگار ویژه</option>
							</select>
						</div>

						<div>
							<label for="search-input"><strong>جستجو:</strong></label>
							<input type="text" name="s" id="search-input" value="<?php echo esc_attr( $search_query ); ?>" placeholder="شماره سفارش، کاربر یا یادداشت...">
						</div>

						<div>
							<button type="submit" class="button button-secondary">اعمال فیلتر</button>
							<?php if ( ! empty( $current_status ) || ! empty( $current_plan ) || ! empty( $search_query ) ) : ?>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-memberships' ) ); ?>" class="button">پاکسازی فیلترها</a>
							<?php endif; ?>
						</div>
					</div>
				</form>
			</div>

			<div style="margin-top: 15px; display: flex; justify-content: flex-end;">
				<form method="post" action="">
					<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
					<input type="hidden" name="negar_club_membership_action" value="setup_vip_products">
					<button type="submit" class="button button-primary" onclick="return confirm('آیا از بررسی و همگام‌سازی محصولات مجازی VIP در ووکامرس اطمینان دارید؟');">
						همگام‌سازی محصولات VIP در ووکامرس
					</button>
				</form>
			</div>

			<table class="wp-list-table widefat fixed striped" style="margin-top: 15px;">
				<thead>
					<tr>
						<th style="width: 50px;">شناسه</th>
						<th>مشتری</th>
						<th>طرح VIP</th>
						<th>وضعیت</th>
						<th>پیش‌پرداخت</th>
						<th>سقف خرید مجاز</th>
						<th>خرید ثبت‌شده</th>
						<th>ظرفیت باقیمانده</th>
						<th>بیعانه مانده</th>
						<th>بدهی اقساط</th>
						<th>سفارش</th>
						<th>تاریخ عضویت</th>
						<th style="width: 120px;">عملیات</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $memberships ) ) : ?>
						<tr>
							<td colspan="13" style="text-align: center; padding: 25px; color: #666;">
								هیچ عضویت VIP مطابق با معیارهای جستجو یافت نشد.
							</td>
						</tr>
					<?php else : ?>
						<?php foreach ( $memberships as $mem ) :
							$user = get_userdata( (int) $mem->customer_id );
							$user_label = $user ? ( $user->display_name ?: $user->user_login ) : 'کاربر #' . $mem->customer_id;
							$plan_title = 'negar_aghaz' === $mem->plan_id ? 'نگار آغاز' : ( 'negar_plus' === $mem->plan_id ? 'نگار پلاس' : ( 'negar_vizhe' === $mem->plan_id ? 'نگار ویژه' : $mem->plan_id ) );
						?>
							<tr>
								<td><strong>#<?php echo esc_html( $mem->id ); ?></strong></td>
								<td>
									<strong><?php echo esc_html( $user_label ); ?></strong>
									<?php if ( $user && $user->user_email ) : ?>
										<br><small style="color: #666;"><?php echo esc_html( $user->user_email ); ?></small>
									<?php endif; ?>
								</td>
								<td><span class="badge"><?php echo esc_html( $plan_title ); ?></span></td>
								<td><?php echo self::render_status_badge( $mem->status ); ?></td>
								<td><?php echo esc_html( number_format( (int) $mem->prepayment_amount ) ); ?> تومان</td>
								<td><strong><?php echo esc_html( number_format( (int) $mem->purchase_ceiling ) ); ?> تومان</strong></td>
								<td><?php echo esc_html( number_format( (int) $mem->used_purchase_amount ) ); ?> تومان</td>
								<td style="color: green; font-weight: bold;"><?php echo esc_html( number_format( (int) $mem->available_purchase_capacity ) ); ?> تومان</td>
								<td><?php echo esc_html( number_format( (int) $mem->unused_deposit ) ); ?> تومان</td>
								<td style="color: #de1f38; font-weight: bold;"><?php echo esc_html( number_format( (int) $mem->offline_debt_amount ) ); ?> تومان</td>
								<td>
									<a href="<?php echo esc_url( admin_url( 'post.php?post=' . (int) $mem->order_id . '&action=edit' ) ); ?>" target="_blank">
										#<?php echo esc_html( $mem->order_id ); ?>
									</a>
								</td>
								<td><?php echo esc_html( substr( $mem->created_at, 0, 10 ) ); ?></td>
								<td>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-memberships&membership_id=' . (int) $mem->id ) ); ?>" class="button button-small button-primary">
										مدیریت
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render the detailed member view and manual transaction forms.
	 *
	 * @param int $membership_id Membership ID.
	 */
	private static function render_detail_view( $membership_id ) {
		$mem = Negar_Club_Database::get_membership( $membership_id );
		if ( ! $mem ) {
			echo '<div class="wrap"><p>عضویت یافت نشد.</p></div>';
			return;
		}

		$user = get_userdata( (int) $mem->customer_id );
		$plan_title = 'negar_aghaz' === $mem->plan_id ? 'نگار آغاز' : ( 'negar_plus' === $mem->plan_id ? 'نگار پلاس' : ( 'negar_vizhe' === $mem->plan_id ? 'نگار ویژه' : $mem->plan_id ) );

		?>
		<div class="wrap negar-club-admin-wrap" dir="rtl">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-memberships' ) ); ?>" class="button" style="margin-bottom: 10px;">&larr; بازگشت به لیست اعضا</a>
			<h1>مدیریت عضویت VIP #<?php echo esc_html( $mem->id ); ?> &mdash; <?php echo esc_html( $plan_title ); ?></h1>
			<hr class="wp-header-end">

			<?php settings_errors( 'negar_club_membership_notices' ); ?>

			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">

				<!-- Box 1: Customer & Account Info -->
				<div class="postbox" style="padding: 15px;">
					<h2>مشخصات عضو و سفارش ووکامرس</h2>
					<table class="form-table" style="margin: 0;">
						<tr>
							<th style="width: 140px;">مشتری:</th>
							<td>
								<strong><?php echo esc_html( $user ? ( $user->display_name ?: $user->user_login ) : 'کاربر #' . $mem->customer_id ); ?></strong>
								<?php if ( $user ) : ?>
									(شناسه: <?php echo esc_html( $user->ID ); ?> | ایمیل: <?php echo esc_html( $user->user_email ); ?>)
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th>طرح VIP:</th>
							<td><strong><?php echo esc_html( $plan_title ); ?></strong></td>
						</tr>
						<tr>
							<th>وضعیت عضویت:</th>
							<td><?php echo self::render_status_badge( $mem->status ); ?></td>
						</tr>
						<tr>
							<th>سفارش مبدأ:</th>
							<td>
								<a href="<?php echo esc_url( admin_url( 'post.php?post=' . (int) $mem->order_id . '&action=edit' ) ); ?>" target="_blank" class="button button-small">
									مشاهده سفارش #<?php echo esc_html( $mem->order_id ); ?>
								</a>
							</td>
						</tr>
						<tr>
							<th>مدت اعتبار:</th>
							<td><?php echo esc_html( $mem->term_months ); ?> ماه (تا <?php echo esc_html( $mem->valid_until ?: 'نامحدود' ); ?>)</td>
						</tr>
						<tr>
							<th>یادداشت‌های سیستم:</th>
							<td><em><?php echo esc_html( $mem->notes ?: 'بدون یادداشت' ); ?></em></td>
						</tr>
					</table>
				</div>

				<!-- Box 2: Financial Balances -->
				<div class="postbox" style="padding: 15px; background: #fafafa;">
					<h2>ترازهای مالی و ظرفیت خرید کالا</h2>
					<table class="form-table" style="margin: 0;">
						<tr>
							<th style="width: 170px;">پیش‌پرداخت نقدی پرداختی:</th>
							<td><strong style="font-size: 1.1em;"><?php echo esc_html( number_format( (int) $mem->prepayment_amount ) ); ?> تومان</strong></td>
						</tr>
						<tr>
							<th>پیش‌پرداخت کسر شده (بیعانه):</th>
							<td><?php echo esc_html( number_format( (int) $mem->applied_deposit ) ); ?> تومان</td>
						</tr>
						<tr>
							<th>مانده پیش‌پرداخت آزاد:</th>
							<td><strong style="color: #2271b1;"><?php echo esc_html( number_format( (int) $mem->unused_deposit ) ); ?> تومان</strong></td>
						</tr>
						<tr>
							<th>کل سقف مجاز خرید کالا:</th>
							<td><strong><?php echo esc_html( number_format( (int) $mem->purchase_ceiling ) ); ?> تومان</strong></td>
						</tr>
						<tr>
							<th>ارزش کالاهای خریداری‌شده:</th>
							<td><?php echo esc_html( number_format( (int) $mem->used_purchase_amount ) ); ?> تومان</td>
						</tr>
						<tr>
							<th>ظرفیت باقیمانده خرید:</th>
							<td><strong style="color: #46b450; font-size: 1.2em;"><?php echo esc_html( number_format( (int) $mem->available_purchase_capacity ) ); ?> تومان</strong></td>
						</tr>
						<tr>
							<th>مانده بدهی اقساط حضوری:</th>
							<td><strong style="color: #de1f38; font-size: 1.2em;"><?php echo esc_html( number_format( (int) $mem->offline_debt_amount ) ); ?> تومان</strong></td>
						</tr>
					</table>
				</div>

			</div>

			<!-- Manual Operations Section -->
			<h2 style="margin-top: 30px;">اقدامات اپراتور فروشگاه</h2>
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

				<!-- Form A: Record Offline Merchandise Purchase -->
				<div class="postbox" style="padding: 15px;">
					<h3>۱. ثبت خرید حضوری کالا</h3>
					<p style="color: #666;">ثبت خرید کالای لوازم خانگی در فروشگاه حضوری و کسر از سقف مجاز خرید.</p>
					<?php if ( 'active' !== $mem->status ) : ?>
						<div class="notice notice-warning inline" style="margin: 10px 0; padding: 8px 12px;">
							<p>این عضویت در وضعیت «<strong><?php echo esc_html( self::get_status_label( $mem->status ) ); ?></strong>» قرار دارد و امکان ثبت خرید یا کسر بیعانه تا فعال‌سازی مجدد مسدود است.</p>
						</div>
					<?php endif; ?>
					<form method="post" action="">
						<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
						<input type="hidden" name="negar_club_membership_action" value="record_purchase">
						<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">
						<input type="hidden" name="negar_club_submission_token" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">

						<table class="form-table">
							<tr>
								<th>مبلغ خرید کالا (تومان):</th>
								<td><input type="number" name="purchase_amount" max="<?php echo esc_attr( $mem->available_purchase_capacity ); ?>" <?php disabled( 'active' !== $mem->status ); ?> required class="regular-text"></td>
							</tr>
							<tr>
								<th>شماره فاکتور / رسید فروشگاه:</th>
								<td><input type="text" name="receipt_ref" placeholder="مثال: INV-1403-882" <?php disabled( 'active' !== $mem->status ); ?> class="regular-text"></td>
							</tr>
							<tr>
								<th>کسر از مانده بیعانه (تومان):</th>
								<td>
									<input type="number" name="deposit_to_apply" max="<?php echo esc_attr( $mem->unused_deposit ); ?>" value="0" <?php disabled( 'active' !== $mem->status ); ?> class="regular-text">
									<p class="description">حداکثر تا سقف مانده پیش‌پرداخت (<?php echo esc_html( number_format( (int) $mem->unused_deposit ) ); ?> تومان)</p>
								</td>
							</tr>
							<tr>
								<th>ثبت باقیمانده به عنوان بدهی اقساط:</th>
								<td>
									<label><input type="checkbox" name="record_as_debt" value="1" checked <?php disabled( 'active' !== $mem->status ); ?>> باقیمانده خرید پس از کسر بیعانه به بدهی اقساط حضوری افزوده شود.</label>
								</td>
							</tr>
							<tr>
								<th>دلیل / توضیحات کارشناس:</th>
								<td><textarea name="reason" rows="2" class="large-text" required placeholder="توضیح کالای تحویل شده یا شماره فاکتور" <?php disabled( 'active' !== $mem->status ); ?>></textarea></td>
							</tr>
						</table>
						<p><button type="submit" class="button button-primary" <?php disabled( 'active' !== $mem->status ); ?>>ثبت خرید حضوری</button></p>
					</form>
				</div>

				<!-- Form B: Record Offline Collection / Installment -->
				<div class="postbox" style="padding: 15px;">
					<h3>۲. ثبت دریافت قسط حضوری</h3>
					<p style="color: #666;">ثبت وصول نقدی، کارتخوان یا چک اقساط در فروشگاه فیزیکی و کاهش مانده بدهی مشتری.</p>
					<form method="post" action="">
						<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
						<input type="hidden" name="negar_club_membership_action" value="record_collection">
						<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">
						<input type="hidden" name="negar_club_submission_token" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">

						<table class="form-table">
							<tr>
								<th>مبلغ قسط دریافتی (تومان):</th>
								<td><input type="number" name="collected_amount" max="<?php echo esc_attr( $mem->offline_debt_amount ); ?>" required class="regular-text"></td>
							</tr>
							<tr>
								<th>شماره ارجاع / رسید کارتخوان:</th>
								<td><input type="text" name="collection_ref" placeholder="کد رهگیری پوز یا فیش بانکی" class="regular-text"></td>
							</tr>
							<tr>
								<th>دلیل / توضیحات کارشناس:</th>
								<td><textarea name="reason" rows="2" class="large-text" required placeholder="مثال: تسویه قسط اول به صورت کارتخوان"></textarea></td>
							</tr>
						</table>
						<p><button type="submit" class="button button-primary">ثبت دریافت قسط</button></p>
					</form>
				</div>

				<!-- Form C: Adjust Balances & Status -->
				<div class="postbox" style="padding: 15px; grid-column: 1 / -1;">
					<h3>۳. تنظیم دستی سقف خرید، وضعیت و بدهی اقساط</h3>
					<p style="color: #666;">اصلاح دستی و موردی سقف‌ها و وضعیت با ثبت دائمی در سابقه ممیزی. (پیش‌پرداخت پرداختی در سفارش ووکامرس غیرقابل‌تغییر است)</p>
					<form method="post" action="">
						<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
						<input type="hidden" name="negar_club_membership_action" value="adjust_balances">
						<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">
						<input type="hidden" name="negar_club_submission_token" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">

						<table class="form-table">
							<tr>
								<th>وضعیت عضویت:</th>
								<td>
									<select name="status">
										<option value="active" <?php selected( $mem->status, 'active' ); ?>>فعال (Active)</option>
										<option value="suspended" <?php selected( $mem->status, 'suspended' ); ?>>معلق (Suspended)</option>
										<option value="expired" <?php selected( $mem->status, 'expired' ); ?>>منقضی (Expired)</option>
										<option value="pending_operator_review" <?php selected( $mem->status, 'pending_operator_review' ); ?>>نیازمند بررسی (Pending Review)</option>
										<option value="refunded_pending_reconciliation" <?php selected( $mem->status, 'refunded_pending_reconciliation' ); ?>>استرداد مالی (Refunded)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>کل سقف مجاز خرید (تومان):</th>
								<td><input type="number" name="purchase_ceiling" value="<?php echo esc_attr( $mem->purchase_ceiling ); ?>" class="regular-text"></td>
							</tr>
							<tr>
								<th>ظرفیت باقیمانده خرید (تومان):</th>
								<td><input type="number" name="available_purchase_capacity" value="<?php echo esc_attr( $mem->available_purchase_capacity ); ?>" class="regular-text"></td>
							</tr>
							<tr>
								<th>بدهی اقساط حضوری (تومان):</th>
								<td><input type="number" name="offline_debt_amount" value="<?php echo esc_attr( $mem->offline_debt_amount ); ?>" class="regular-text"></td>
							</tr>
							<tr>
								<th>دلیل مستندسازی و ممیزی (الزامی):</th>
								<td><textarea name="reason" rows="2" class="large-text" required placeholder="ذکر دلیل تغییر تراز یا وضعیت"></textarea></td>
							</tr>
						</table>
						<p><button type="submit" class="button button-secondary">ذخیره تغییرات دستی</button></p>
					</form>
				</div>

				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<!-- Form D: Exceptional Accounting Correction (Fix A) -->
					<div class="postbox" style="padding: 15px; grid-column: 1 / -1; border: 1px solid #dba617; background: #fffcf0;">
						<h3 style="color: #856404; margin-top: 0;">۴. اصلاح استثنایی حسابداری و ودیعه (مدیریت ارشد)</h3>
						<p style="color: #666; font-size: 13px;">
							<strong>هشدار حسابداری:</strong> این بخش منحصراً جهت تصحیح اشتباهات دفتری یا مصالحه‌های حقوقی با نظارت مستقیم مدیریت است. مبلغ پرداختی در سفارش ووکامرس غیرقابل‌تغییر است و تغییرات در سوابق ممیزی با شناسه مدیر ثبت می‌گردد.
						</p>
						<form method="post" action="">
							<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
							<input type="hidden" name="negar_club_membership_action" value="exceptional_deposit_correction">
							<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">
							<input type="hidden" name="negar_club_submission_token" value="<?php echo esc_attr( wp_generate_uuid4() ); ?>">

							<table class="form-table">
								<tr>
									<th>مانده پیش‌پرداخت آزاد جدید (تومان):</th>
									<td><input type="number" name="new_unused_deposit" value="<?php echo esc_attr( $mem->unused_deposit ); ?>" required class="regular-text"></td>
								</tr>
								<tr>
									<th>پیش‌پرداخت کسر شده جدید (تومان):</th>
									<td><input type="number" name="new_applied_deposit" value="<?php echo esc_attr( $mem->applied_deposit ); ?>" required class="regular-text"></td>
								</tr>
								<tr>
									<th>دلیل مستندسازی مصوبه مدیریت (الزامی):</th>
									<td><textarea name="reason" rows="2" class="large-text" required placeholder="علت رسمی اصلاح دفتری حسابداری"></textarea></td>
								</tr>
							</table>
							<p><button type="submit" class="button button-secondary" onclick="return confirm('آیا از ثبت اصلاح استثنایی حسابداری اطمینان دارید؟');">ثبت اصلاح حسابداری</button></p>
						</form>
					</div>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	/**
	 * Render semantic status badge.
	 *
	 * @param string $status Membership status.
	 * @return string HTML badge.
	 */
	private static function render_status_badge( $status ) {
		switch ( $status ) {
			case 'active':
				return '<span style="background: #e7f7ed; color: #1e7e34; padding: 3px 8px; border-radius: 4px; font-weight: bold;">فعال</span>';
			case 'suspended':
				return '<span style="background: #fff3cd; color: #856404; padding: 3px 8px; border-radius: 4px; font-weight: bold;">معلق</span>';
			case 'expired':
				return '<span style="background: #e2e3e5; color: #383d41; padding: 3px 8px; border-radius: 4px; font-weight: bold;">منقضی</span>';
			case 'pending_operator_review':
				return '<span style="background: #cfe2ff; color: #084298; padding: 3px 8px; border-radius: 4px; font-weight: bold;">بررسی اپراتور</span>';
			case 'refunded_pending_reconciliation':
				return '<span style="background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-weight: bold;">استرداد / تعلیق مالی</span>';
			default:
				return '<span>' . esc_html( $status ) . '</span>';
		}
	}
}
