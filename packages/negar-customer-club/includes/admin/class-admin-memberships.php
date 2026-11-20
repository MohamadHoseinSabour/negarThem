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

		$action        = sanitize_key( (string) $_POST['negar_club_membership_action'] );
		$membership_id = (int) ( $_POST['membership_id'] ?? 0 );
		$operator_id   = get_current_user_id();

		if ( $membership_id <= 0 ) {
			add_settings_error( 'negar_club_membership_notices', 'invalid_id', 'شناسه عضویت معتبر نمی‌باشد.', 'error' );
			return;
		}

		$membership = Negar_Club_Database::get_membership( $membership_id );
		if ( ! $membership ) {
			add_settings_error( 'negar_club_membership_notices', 'not_found', 'عضویت مورد نظر یافت نشد.', 'error' );
			return;
		}

		$old_state = (array) $membership;
		$reason    = sanitize_text_field( (string) ( $_POST['reason'] ?? '' ) );

		if ( empty( $reason ) ) {
			add_settings_error( 'negar_club_membership_notices', 'missing_reason', 'ذکر دلیل یا توضیحات برای کلیه اقدامات دستی کارشناس الزامی است.', 'error' );
			return;
		}

		switch ( $action ) {

			// 1. Record In-Store Merchandise Purchase
			case 'record_purchase':
				$purchase_amount = max( 0, (int) ( $_POST['purchase_amount'] ?? 0 ) );
				$receipt_ref     = sanitize_text_field( (string) ( $_POST['receipt_ref'] ?? '' ) );
				$apply_deposit   = max( 0, (int) ( $_POST['deposit_to_apply'] ?? 0 ) );
				$record_as_debt  = ! empty( $_POST['record_as_debt'] );

				if ( $purchase_amount <= 0 ) {
					add_settings_error( 'negar_club_membership_notices', 'invalid_purchase_amount', 'مبلغ خرید باید بزرگ‌تر از صفر باشد.', 'error' );
					return;
				}

				if ( $purchase_amount > (int) $membership->available_purchase_capacity ) {
					add_settings_error( 'negar_club_membership_notices', 'exceeds_capacity', sprintf( 'مبلغ خرید (%s تومان) نمی‌تواند از ظرفیت خرید باقیمانده (%s تومان) بیشتر باشد.', number_format( $purchase_amount ), number_format( (int) $membership->available_purchase_capacity ) ), 'error' );
					return;
				}

				if ( $apply_deposit > (int) $membership->unused_deposit ) {
					add_settings_error( 'negar_club_membership_notices', 'exceeds_deposit', sprintf( 'مبلغ بیعانه اعمالی (%s تومان) نمی‌تواند از مانده پیش‌پرداخت (%s تومان) بیشتر باشد.', number_format( $apply_deposit ), number_format( (int) $membership->unused_deposit ) ), 'error' );
					return;
				}

				if ( $apply_deposit > $purchase_amount ) {
					add_settings_error( 'negar_club_membership_notices', 'deposit_exceeds_purchase', 'مبلغ بیعانه نمی‌تواند از کل مبلغ خرید کالا بیشتر باشد.', 'error' );
					return;
				}

				$new_used_purchase = (int) $membership->used_purchase_amount + $purchase_amount;
				$new_capacity      = (int) $membership->available_purchase_capacity - $purchase_amount;
				$new_applied_dep   = (int) $membership->applied_deposit + $apply_deposit;
				$new_unused_dep    = (int) $membership->unused_deposit - $apply_deposit;

				$remaining_unpaid  = $purchase_amount - $apply_deposit;
				$new_offline_debt  = (int) $membership->offline_debt_amount + ( $record_as_debt ? $remaining_unpaid : 0 );

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

				if ( $updated ) {
					Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:record_purchase',
						$old_state,
						array(
							'purchase_amount'   => $purchase_amount,
							'receipt_ref'       => $receipt_ref,
							'applied_deposit'   => $apply_deposit,
							'record_as_debt'    => $record_as_debt,
							'new_debt'          => $new_offline_debt,
						),
						$operator_id,
						$reason
					);
					add_settings_error( 'negar_club_membership_notices', 'purchase_success', 'خرید حضوری کالا با موفقیت ثبت و ترازهای عضویت به‌روزرسانی شد.', 'success' );
				}
				break;

			// 2. Apply Deposit Toward Offline Purchase
			case 'apply_deposit':
				$deposit_amount = max( 0, (int) ( $_POST['deposit_amount'] ?? 0 ) );
				if ( $deposit_amount <= 0 ) {
					add_settings_error( 'negar_club_membership_notices', 'invalid_deposit_amount', 'مبلغ اعمال بیعانه باید بزرگ‌تر از صفر باشد.', 'error' );
					return;
				}

				if ( $deposit_amount > (int) $membership->unused_deposit ) {
					add_settings_error( 'negar_club_membership_notices', 'exceeds_deposit', 'مبلغ انتخابی از مانده پیش‌پرداخت آزاد بیشتر است.', 'error' );
					return;
				}

				$new_applied = (int) $membership->applied_deposit + $deposit_amount;
				$new_unused  = (int) $membership->unused_deposit - $deposit_amount;

				$updated = Negar_Club_Database::update_membership(
					$membership_id,
					array(
						'applied_deposit'  => $new_applied,
						'unused_deposit'   => $new_unused,
						'notes'            => 'کسر پیش‌پرداخت: ' . number_format( $deposit_amount ) . ' تومان. دلیل: ' . $reason,
						'last_operator_id' => $operator_id,
					)
				);

				if ( $updated ) {
					Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:apply_deposit',
						$old_state,
						array( 'applied_amount' => $deposit_amount, 'new_unused' => $new_unused ),
						$operator_id,
						$reason
					);
					add_settings_error( 'negar_club_membership_notices', 'deposit_applied', 'پیش‌پرداخت با موفقیت اعمال گردید.', 'success' );
				}
				break;

			// 3. Record Offline Installment Collection Receipt
			case 'record_collection':
				$collected_amount = max( 0, (int) ( $_POST['collected_amount'] ?? 0 ) );
				$collection_ref   = sanitize_text_field( (string) ( $_POST['collection_ref'] ?? '' ) );

				if ( $collected_amount <= 0 ) {
					add_settings_error( 'negar_club_membership_notices', 'invalid_collected_amount', 'مبلغ دریافتی باید بزرگ‌تر از صفر باشد.', 'error' );
					return;
				}

				$new_debt = max( 0, (int) $membership->offline_debt_amount - $collected_amount );

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

				if ( $updated ) {
					Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:record_collection',
						$old_state,
						array( 'collected_amount' => $collected_amount, 'receipt_ref' => $collection_ref, 'remaining_debt' => $new_debt ),
						$operator_id,
						$reason
					);
					add_settings_error( 'negar_club_membership_notices', 'collection_success', 'دریافت قسط حضوری با موفقیت ثبت شد.', 'success' );
				}
				break;

			// 4. Adjust Balances / Status / Limits
			case 'adjust_balances':
				$new_ceiling  = isset( $_POST['purchase_ceiling'] ) ? max( 0, (int) $_POST['purchase_ceiling'] ) : (int) $membership->purchase_ceiling;
				$new_capacity = isset( $_POST['available_purchase_capacity'] ) ? max( 0, (int) $_POST['available_purchase_capacity'] ) : (int) $membership->available_purchase_capacity;
				$new_unused   = isset( $_POST['unused_deposit'] ) ? max( 0, (int) $_POST['unused_deposit'] ) : (int) $membership->unused_deposit;
				$new_debt     = isset( $_POST['offline_debt_amount'] ) ? max( 0, (int) $_POST['offline_debt_amount'] ) : (int) $membership->offline_debt_amount;
				$new_status   = sanitize_key( (string) ( $_POST['status'] ?? $membership->status ) );

				$allowed_statuses = array( 'active', 'suspended', 'expired', 'pending_operator_review', 'refunded_pending_reconciliation' );
				if ( ! in_array( $new_status, $allowed_statuses, true ) ) {
					$new_status = $membership->status;
				}

				$updated = Negar_Club_Database::update_membership(
					$membership_id,
					array(
						'purchase_ceiling'            => $new_ceiling,
						'available_purchase_capacity' => $new_capacity,
						'unused_deposit'              => $new_unused,
						'offline_debt_amount'         => $new_debt,
						'status'                      => $new_status,
						'notes'                       => 'تنظیم دستی توسط کارشناس: ' . $reason,
						'last_operator_id'            => $operator_id,
					)
				);

				if ( $updated ) {
					Negar_Club_Database::log_audit(
						'club_membership',
						$membership_id,
						'membership:adjust_balances',
						$old_state,
						array(
							'ceiling'  => $new_ceiling,
							'capacity' => $new_capacity,
							'deposit'  => $new_unused,
							'debt'     => $new_debt,
							'status'   => $new_status,
						),
						$operator_id,
						$reason
					);
					add_settings_error( 'negar_club_membership_notices', 'adjust_success', 'ترازها و وضعیت عضویت با موفقیت به‌روزرسانی شدند.', 'success' );
				}
				break;
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
					<form method="post" action="">
						<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
						<input type="hidden" name="negar_club_membership_action" value="record_purchase">
						<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">

						<table class="form-table">
							<tr>
								<th>مبلغ خرید کالا (تومان):</th>
								<td><input type="number" name="purchase_amount" max="<?php echo esc_attr( $mem->available_purchase_capacity ); ?>" required class="regular-text"></td>
							</tr>
							<tr>
								<th>شماره فاکتور / رسید فروشگاه:</th>
								<td><input type="text" name="receipt_ref" placeholder="مثال: INV-1403-882" class="regular-text"></td>
							</tr>
							<tr>
								<th>کسر از مانده بیعانه (تومان):</th>
								<td>
									<input type="number" name="deposit_to_apply" max="<?php echo esc_attr( $mem->unused_deposit ); ?>" value="0" class="regular-text">
									<p class="description">حداکثر تا سقف مانده پیش‌پرداخت (<?php echo esc_html( number_format( (int) $mem->unused_deposit ) ); ?> تومان)</p>
								</td>
							</tr>
							<tr>
								<th>ثبت باقیمانده به عنوان بدهی اقساط:</th>
								<td>
									<label><input type="checkbox" name="record_as_debt" value="1" checked> باقیمانده خرید پس از کسر بیعانه به بدهی اقساط حضوری افزوده شود.</label>
								</td>
							</tr>
							<tr>
								<th>دلیل / توضیحات کارشناس:</th>
								<td><textarea name="reason" rows="2" class="large-text" required placeholder="توضیح کالای تحویل شده یا شماره فاکتور"></textarea></td>
							</tr>
						</table>
						<p><button type="submit" class="button button-primary">ثبت خرید حضوری</button></p>
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

						<table class="form-table">
							<tr>
								<th>مبلغ قسط دریافتی (تومان):</th>
								<td><input type="number" name="collected_amount" required class="regular-text"></td>
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
					<h3>۳. تنظیم دستی ترازها، وضعیت و سقف خرید</h3>
					<p style="color: #666;">اصلاح دستی و موردی ترازها با ثبت دائمی در سابقه ممیزی.</p>
					<form method="post" action="">
						<?php wp_nonce_field( 'negar_club_operator_membership_action', 'negar_club_membership_nonce' ); ?>
						<input type="hidden" name="negar_club_membership_action" value="adjust_balances">
						<input type="hidden" name="membership_id" value="<?php echo esc_attr( $mem->id ); ?>">

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
								<th>مانده پیش‌پرداخت بیعانه (تومان):</th>
								<td><input type="number" name="unused_deposit" value="<?php echo esc_attr( $mem->unused_deposit ); ?>" class="regular-text"></td>
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
