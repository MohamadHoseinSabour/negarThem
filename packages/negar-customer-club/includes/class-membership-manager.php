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
		self::process_paid_order( $order_id );
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
	 * @param int $order_id WooCommerce Order ID.
	 * @return int|false Activated membership ID or false.
	 */
	public static function process_paid_order( $order_id ) {
		$order_id = (int) $order_id;
		if ( $order_id <= 0 || ! function_exists( 'wc_get_order' ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}

		// 1. Payment Verification Check
		$status = $order->get_status();
		$payment_method = $order->get_payment_method();

		// For deferred/offline methods (bacs, cheque, cod), require explicit completed status
		$deferred_gateways = array( 'bacs', 'cheque', 'cod' );
		if ( in_array( $payment_method, $deferred_gateways, true ) ) {
			if ( 'completed' !== $status ) {
				return false; // Cash has not been physically collected yet
			}
		} else {
			// Online/electronic gateway: must be completed or verified processing
			$is_paid = method_exists( $order, 'is_paid' ) ? $order->is_paid() : false;
			$date_paid = method_exists( $order, 'get_date_paid' ) ? $order->get_date_paid() : null;

			if ( 'completed' !== $status && ! ( 'processing' === $status && ( $is_paid || ! empty( $date_paid ) ) ) ) {
				return false; // Not a confirmed paid order
			}
		}

		// 2. Scan line items for Customer Club VIP product
		$matched_plan_id   = '';
		$prepayment_amount = 0;

		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_product_id();
			$plan_id    = Negar_Club_VIP_Products::get_product_plan_id( $product_id );

			if ( ! empty( $plan_id ) ) {
				$matched_plan_id   = $plan_id;
				$prepayment_amount = (int) $item->get_total();
				break;
			}
		}

		if ( empty( $matched_plan_id ) ) {
			return false; // Not a VIP membership order
		}

		// If line item total is 0, fallback to order total
		if ( $prepayment_amount <= 0 ) {
			$prepayment_amount = (int) $order->get_total();
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

		// 4. Exactly-Once Idempotency Check: Order ID must be unique
		$existing = Negar_Club_Database::get_membership_by_order( $order_id );
		if ( $existing ) {
			return (int) $existing->id; // Already activated, strictly idempotent
		}

		// 5. Check if customer already has an active VIP membership
		$active_mem = Negar_Club_Database::get_active_membership_by_customer( $customer_id );

		// 6. Plan configuration & limits
		$plan_conf = Negar_Club_VIP_Products::get_plan_configs( $matched_plan_id );
		$purchase_ceiling = isset( $plan_conf['ceiling'] ) && (int) $plan_conf['ceiling'] > 0
			? (int) $plan_conf['ceiling']
			: ( $prepayment_amount * 2 );

		$term_months = isset( $plan_conf['duration_months'] ) && (int) $plan_conf['duration_months'] > 0
			? (int) $plan_conf['duration_months']
			: 3;

		$valid_until = date( 'Y-m-d H:i:s', strtotime( "+{$term_months} months", current_time( 'timestamp' ) ) );

		// Status decision: if customer already has an active membership, flag for operator review
		$membership_status = $active_mem ? 'pending_operator_review' : 'active';
		$membership_notes  = $active_mem
			? sprintf( 'خرید مجدد اشتراک VIP در حالی که مشتری دارای اشتراک فعال #%d است. نیازمند بررسی و ادغام دستی اپراتور.', (int) $active_mem->id )
			: sprintf( 'فعال‌سازی خودکار اشتراک VIP پس از تایید پرداخت سفارش ووکامرس #%d', $order_id );

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
			return false;
		}

		// Audit logging
		Negar_Club_Database::log_audit(
			'club_membership',
			$membership_id,
			'membership:activate',
			null,
			$membership_data,
			$customer_id,
			$membership_notes
		);

		return (int) $membership_id;
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
		$new_status = 'refund' === $action ? 'refunded_pending_reconciliation' : 'suspended';

		$new_notes = sprintf(
			'سفارش ووکامرس شماره #%d %s شد. وضعیت عضویت به حالت تعلیق درآمد. استفاده از مانده پیش‌پرداخت تا بررسی حسابداری مسدود است.',
			$order_id,
			'refund' === $action ? 'مسترد' : 'لغو'
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
