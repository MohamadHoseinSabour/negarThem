<?php
/**
 * VIP Membership Lifecycle & Order Listener for Negar Customer Club.
 *
 * Listens to genuine WooCommerce payment completion hooks, idempotently activates
 * VIP prepayment memberships, enforces single active membership rules, and safely
 * suspends memberships upon order refund or cancellation without double crediting.
 *
 * @package NegarCustomerClub
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Membership_Manager {

	/**
	 * Accounting notice: VIP purchases are advances toward future merchandise.
	 */
	const ACCOUNTING_NOTICE = 'توجه حسابداری: پیش‌پرداخت طرح‌های VIP به منزله پیش‌دریافت وجه بابت خرید کالاهای آتی بوده و کارمزد عضویت مجزا تلقی نمی‌گردد. صدور فاکتور رسمی و اعمال مالیات بر ارزش افزوده در زمان تحویل کالای فیزیکی در فروشگاه حضوری صورت می‌پذیرد.';

	/**
	 * Register WooCommerce order status hooks.
	 */
	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Verified payment hooks
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'handle_order_completed' ), 10, 2 );
		add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'handle_order_processing' ), 10, 2 );
		add_action( 'woocommerce_payment_complete', array( __CLASS__, 'handle_payment_complete' ), 10, 1 );

		// Cancellation and refund hooks
		add_action( 'woocommerce_order_status_refunded', array( __CLASS__, 'handle_order_refunded' ), 10, 2 );
		add_action( 'woocommerce_order_status_cancelled', array( __CLASS__, 'handle_order_cancelled' ), 10, 2 );
		add_action( 'woocommerce_order_partially_refunded', array( __CLASS__, 'handle_order_partially_refunded' ), 10, 2 );
	}

	/**
	 * Handle order status completed.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order    Order object.
	 */
	public static function handle_order_completed( $order_id, $order = null ) {
		self::process_paid_order( $order_id );
	}

	/**
	 * Handle order status processing.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order    Order object.
	 */
	public static function handle_order_processing( $order_id, $order = null ) {
		self::process_paid_order( $order_id );
	}

	/**
	 * Handle native payment complete hook.
	 *
	 * @param int $order_id Order ID.
	 */
	public static function handle_payment_complete( $order_id ) {
		$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : null;
		if ( $order && method_exists( $order, 'update_meta_data' ) ) {
			$order->update_meta_data( '_negar_club_payment_confirmed', current_time( 'mysql' ) );
			$order->save();
		}
		self::process_paid_order( $order_id, $order );
	}

	/**
	 * Handle order refund hook.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order    Order object.
	 */
	public static function handle_order_refunded( $order_id, $order = null ) {
		self::handle_order_refund_or_cancellation( $order_id, 'refund' );
	}

	/**
	 * Handle order partial refund hook.
	 *
	 * @param int $order_id  Order ID.
	 * @param int $refund_id Refund ID.
	 */
	public static function handle_order_partially_refunded( $order_id, $refund_id = 0 ) {
		self::handle_order_refund_or_cancellation( $order_id, 'partial_refund' );
	}

	/**
	 * Handle order cancellation hook.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order    Order object.
	 */
	public static function handle_order_cancelled( $order_id, $order = null ) {
		self::handle_order_refund_or_cancellation( $order_id, 'cancel' );
	}

	/**
	 * Process a potentially paid order for VIP membership activation.
	 *
	 * Idempotent:
	 * 1. Verifies order exists and is genuinely paid.
	 * 2. Scans line items for VIP products with _negar_club_plan_id meta.
	 * 3. Enforces unique database constraint on order_id to prevent duplicate activations.
	 * 4. Flags repeat purchases for operator review if an active membership exists.
	 * 5. Records full audit trail.
	 *
	 * @param int|object $order_or_id WooCommerce Order ID or Order object.
	 * @param object|null $order_obj   Optional order object if ID was passed first.
	 * @return int|false Activated membership ID or false.
	 */
	public static function process_paid_order( $order_or_id, $order_obj = null ) {
		$order = null;
		$order_id = 0;

		if ( is_object( $order_or_id ) ) {
			$order    = $order_or_id;
			$order_id = method_exists( $order, 'get_id' ) ? (int) $order->get_id() : (int) ( $order->id ?? 0 );
		} elseif ( is_object( $order_obj ) ) {
			$order    = $order_obj;
			$order_id = (int) $order_or_id;
		} else {
			$order_id = (int) $order_or_id;
			if ( function_exists( 'wc_get_order' ) ) {
				$order = wc_get_order( $order_id );
			}
		}

		if ( function_exists( 'apply_filters' ) ) {
			$order = apply_filters( 'negar_club_get_order', $order, $order_id );
		}

		if ( ! $order || $order_id <= 0 ) {
			return false;
		}

		$is_flagged_for_review = false;
		$review_reason         = '';

		// 1. Payment Verification Check
		$status = $order->get_status();
		$payment_method = $order->get_payment_method();

		// For deferred/offline methods (bacs, cheque, cod), require explicit completed status and confirmed receipt
		$deferred_gateways = array( 'bacs', 'cheque', 'cod' );
		if ( in_array( $payment_method, $deferred_gateways, true ) ) {
			$has_offline_receipt = ( method_exists( $order, 'get_transaction_id' ) && ! empty( $order->get_transaction_id() ) )
				|| ( method_exists( $order, 'get_meta' ) && (bool) $order->get_meta( '_negar_club_offline_receipt_confirmed' ) );

			if ( 'completed' === $status && ! $has_offline_receipt ) {
				$is_flagged_for_review = true;
				$review_reason         = 'سفارش با روش پرداخت معوق ثبت گردیده و نیازمند تایید فیش واریزی توسط کارشناس است.';
			} elseif ( 'completed' !== $status ) {
				return false; // Cash has not been physically collected yet
			}
		} else {
			// Online/electronic gateway: must be confirmed paid (is_paid or date_paid present)
			$is_paid   = method_exists( $order, 'is_paid' ) ? $order->is_paid() : false;
			$date_paid = method_exists( $order, 'get_date_paid' ) ? $order->get_date_paid() : null;

			if ( ! $is_paid && empty( $date_paid ) ) {
				return false; // Not a confirmed paid order
			}

			if ( 'completed' !== $status && 'processing' !== $status ) {
				return false;
			}

			$has_online_signal = ( method_exists( $order, 'get_transaction_id' ) && ! empty( $order->get_transaction_id() ) )
				|| ( method_exists( $order, 'get_meta' ) && (bool) $order->get_meta( '_negar_club_payment_confirmed' ) );

			if ( ! $has_online_signal ) {
				$is_flagged_for_review = true;
				$review_reason         = 'سفارش درگاه آنلاین فاقد شناسه تراکنش معتبر است و نیازمند تایید دستی کارشناس می‌باشد.';
			}
		}

		// Verify currency consistency
		$order_currency = method_exists( $order, 'get_currency' ) ? $order->get_currency() : '';
		if ( function_exists( 'get_woocommerce_currency' ) ) {
			$store_currency = get_woocommerce_currency();
			if ( ! empty( $order_currency ) && ! empty( $store_currency ) && $order_currency !== $store_currency ) {
				Negar_Club_Database::log_audit(
					'wc_order',
					$order_id,
					'membership:currency_mismatch_blocked',
					null,
					array( 'order_currency' => $order_currency, 'store_currency' => $store_currency ),
					0,
					'ارز سفارش با ارز پیش‌فرض فروشگاه همخوانی ندارد.'
				);
				return false;
			}
		}

		// 2. Scan line items for Customer Club VIP product
		$matched_plan_id       = '';
		$matched_product_id    = 0;
		$prepayment_amount     = 0;
		$vip_item_count        = 0;
		$regular_item_count    = 0;
		$vip_line_qty          = 0;

		foreach ( $order->get_items() as $item ) {
			$product_id = method_exists( $item, 'get_product_id' ) ? $item->get_product_id() : 0;
			$plan_id    = Negar_Club_VIP_Products::get_product_plan_id( $product_id );

			if ( ! empty( $plan_id ) ) {
				$vip_item_count++;
				$vip_line_qty      += method_exists( $item, 'get_quantity' ) ? (int) $item->get_quantity() : 1;
				$matched_plan_id    = $plan_id;
				$matched_product_id = $product_id;
				$prepayment_amount  = method_exists( $item, 'get_total' ) ? (int) $item->get_total() : 0;
			} else {
				$regular_item_count++;
			}
		}

		if ( empty( $matched_plan_id ) || 0 === $vip_item_count ) {
			return false; // Not a VIP membership order
		}

		// Validate product suitability (must be simple virtual, not downloadable)
		if ( ! Negar_Club_VIP_Products::is_valid_vip_product( $matched_product_id ) ) {
			Negar_Club_Database::log_audit(
				'wc_order',
				$order_id,
				'membership:invalid_product_blocked',
				null,
				array( 'product_id' => $matched_product_id ),
				0,
				'محصول فاقد شرایط محصول مجازی ساده VIP است.'
			);
			return false;
		}

		// Prepayment amount calculation: never credit more customer prepayment than verified paid
		$order_total = method_exists( $order, 'get_total' ) ? (int) $order->get_total() : (int) ( $order->total ?? 0 );
		if ( $prepayment_amount <= 0 ) {
			$prepayment_amount = $order_total;
		} else {
			$prepayment_amount = min( $prepayment_amount, $order_total );
		}

		if ( $prepayment_amount <= 0 ) {
			return false; // Invalid financial amount
		}

		// Quantity check: exactly 1 VIP product and no mixed merchandise
		if ( 1 !== $vip_line_qty || $vip_item_count > 1 || $regular_item_count > 0 ) {
			$is_flagged_for_review = true;
			$qty_note              = 'سفارش شامل اقلام غیرمجاز، کالاهای متعدد یا تعداد بیش از ۱ اشتراک VIP است و نیازمند بررسی کارشناس می‌باشد.';
			$review_reason         = ! empty( $review_reason ) ? $review_reason . ' | ' . $qty_note : $qty_note;
		}

		// Check expected price vs actual paid prepayment (discounts / coupons guard)
		$plan_conf      = Negar_Club_VIP_Products::get_plan_configs( $matched_plan_id );
		$expected_price = isset( $plan_conf['prepayment'] ) ? (int) $plan_conf['prepayment'] : 0;

		if ( $expected_price > 0 && $prepayment_amount < $expected_price ) {
			$is_flagged_for_review = true;
			$disc_note             = sprintf(
				'مبلغ پرداختی (%s تومان) به دلیل اعمال تخفیف یا کوپن کمتر از قیمت مصوب طرح (%s تومان) است. نیازمند بررسی کارشناس.',
				number_format( $prepayment_amount ),
				number_format( $expected_price )
			);
			$review_reason         = ! empty( $review_reason ) ? $review_reason . ' | ' . $disc_note : $disc_note;
		}

		// 3. Customer Verification Check
		$customer_id = (int) $order->get_customer_id();
		if ( $customer_id <= 0 ) {
			// Cannot link VIP membership to a guest user without account
			Negar_Club_Database::log_audit(
				'wc_order',
				$order_id,
				'membership:guest_activation_blocked',
				null,
				array( 'order_id' => $order_id, 'plan_id' => $matched_plan_id ),
				0,
				'سفارش VIP متعلق به کاربر مهمان بوده و فعال‌سازی خودکار مسدود گردید.'
			);
			return false;
		}

		// 4. Exactly-Once Idempotency & Atomic Concurrency Guard
		Negar_Club_Database::begin_transaction();

		try {
			// Idempotency: Order ID must be unique
			$existing = Negar_Club_Database::get_membership_by_order( $order_id );
			if ( $existing ) {
				Negar_Club_Database::commit();
				return (int) $existing->id; // Already activated, strictly idempotent
			}

			// Atomic Check with row lock for active membership (prevents simultaneous active memberships)
			$active_mem = Negar_Club_Database::get_active_membership_by_customer_for_update( $customer_id );

			// 5. Plan configuration & limits
			$purchase_ceiling = isset( $plan_conf['ceiling'] ) && (int) $plan_conf['ceiling'] > 0
				? (int) $plan_conf['ceiling']
				: ( $prepayment_amount * 2 );

			$term_months = isset( $plan_conf['duration_months'] ) && (int) $plan_conf['duration_months'] > 0
				? (int) $plan_conf['duration_months']
				: 3;

			$valid_until = date( 'Y-m-d H:i:s', strtotime( "+{$term_months} months", current_time( 'timestamp' ) ) );

			// Status decision
			if ( $active_mem ) {
				$membership_status = 'pending_operator_review';
				$membership_notes  = sprintf( 'خرید مجدد اشتراک VIP در حالی که مشتری دارای اشتراک فعال #%d است. نیازمند بررسی و ادغام دستی اپراتور.', (int) $active_mem->id );
			} elseif ( $is_flagged_for_review ) {
				$membership_status = 'pending_operator_review';
				$membership_notes  = $review_reason;
			} else {
				$membership_status = 'active';
				$membership_notes  = sprintf( 'فعال‌سازی خودکار اشتراک VIP پس از تایید پرداخت سفارش ووکامرس #%d', $order_id );
			}

			$membership_data = array(
				'customer_id'                 => $customer_id,
				'plan_id'                     => $matched_plan_id,
				'order_id'                    => $order_id,
				'prepayment_amount'           => $prepayment_amount,
				'applied_deposit'             => 0,
				'unused_deposit'              => $prepayment_amount,
				'purchase_ceiling'            => $purchase_ceiling,
				'used_purchase_amount'        => 0,
				'available_purchase_capacity' => $purchase_ceiling,
				'offline_debt_amount'         => 0,
				'term_months'                 => $term_months,
				'status'                      => $membership_status,
				'valid_until'                 => $valid_until,
				'notes'                       => $membership_notes,
			);

			$membership_id = Negar_Club_Database::create_membership( $membership_data );
			if ( ! $membership_id ) {
				throw new Exception( 'Failed to create membership record in database.' );
			}

			// Audit logging
			$audit_id = Negar_Club_Database::log_audit(
				'club_membership',
				$membership_id,
				'membership:activate',
				null,
				$membership_data,
				$customer_id,
				$membership_notes
			);

			if ( false === $audit_id ) {
				throw new Exception( 'Failed to log membership activation audit.' );
			}

			Negar_Club_Database::commit();
			return (int) $membership_id;
		} catch ( Exception $e ) {
			Negar_Club_Database::rollback();
			return false;
		}
	}

	/**
	 * Handle order refund or cancellation safely without double-crediting.
	 *
	 * @param int    $order_id Order ID.
	 * @param string $action   'refund' or 'cancel'.
	 * @return bool True if handled.
	 */
	public static function handle_order_refund_or_cancellation( $order_id, $action = 'refund' ) {
		$order_id = (int) $order_id;
		if ( $order_id <= 0 ) {
			return false;
		}

		$membership = Negar_Club_Database::get_membership_by_order( $order_id );
		if ( ! $membership ) {
			return false; // Not linked to any VIP membership
		}

		$old_state = (array) $membership;
		$new_status = in_array( $action, array( 'refund', 'partial_refund' ), true ) ? 'refunded_pending_reconciliation' : 'suspended';

		$action_label = 'refund' === $action ? 'مسترد' : ( 'partial_refund' === $action ? 'شامل استرداد جزئی' : 'لغو' );
		$new_notes = sprintf(
			'سفارش ووکامرس شماره #%d %s شد. وضعیت عضویت به حالت تعلیق درآمد. استفاده از مانده پیش‌پرداخت تا بررسی حسابداری مسدود است.',
			$order_id,
			$action_label
		);

		$updated = Negar_Club_Database::update_membership(
			$membership->id,
			array(
				'status' => $new_status,
				'notes'  => $new_notes,
			)
		);

		if ( $updated ) {
			Negar_Club_Database::log_audit(
				'club_membership',
				$membership->id,
				'membership:' . sanitize_key( $action ),
				$old_state,
				array( 'status' => $new_status, 'notes' => $new_notes ),
				0,
				'تعلیق خودکار عضویت VIP به دلیل ' . ( 'refund' === $action ? 'استرداد' : 'لغو' ) . ' سفارش ووکامرس'
			);
			return true;
		}

		return false;
	}
}
