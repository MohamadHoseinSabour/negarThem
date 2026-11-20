<?php
/**
 * Operator Review Admin Interface for Negar Customer Club.
 *
 * Provides a responsive, accessible Persian RTL dashboard for operators
 * to review, search, approve, or reject customer credit applications.
 *
 * @package NegarCustomerClub
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Admin_Applications {

	/**
	 * Register the WordPress admin menu page.
	 *
	 * @return void
	 */
	public static function register_admin_menu() {
		// Register under WooCommerce menu if available, else as dedicated top-level menu
		if ( function_exists( 'WC' ) ) {
			add_submenu_page(
				'woocommerce',
				'درخواست‌های اقساطی باشگاه مشتریان',
				'درخواست‌های اقساطی',
				'negar_club_operator',
				'negar-club-applications',
				array( __CLASS__, 'render_admin_page' )
			);
		} else {
			add_menu_page(
				'درخواست‌های اقساطی باشگاه نگار',
				'درخواست‌های اقساطی',
				'negar_club_operator',
				'negar-club-applications',
				array( __CLASS__, 'render_admin_page' ),
				'dashicons-id',
				56
			);
		}
	}

	/**
	 * Handle admin form submissions for operator reviews.
	 *
	 * @return void
	 */
	public static function handle_admin_actions() {
		if ( ! is_admin() || ! isset( $_POST['negar_club_admin_action'] ) ) {
			return;
		}

		if ( ! check_admin_referer( 'negar_club_operator_review', 'negar_club_nonce' ) ) {
			wp_die( 'درخواست نامعتبر یا منقضی شده است. لطفاً صفحه را تجدید نمایید.' );
		}

		if ( ! Negar_Club_Roles::current_user_can_operate() ) {
			wp_die( 'شما دسترسی لازم برای این عملیات را ندارید.' );
		}

		$action     = sanitize_key( (string) $_POST['negar_club_admin_action'] );
		$app_number = sanitize_text_field( (string) ( $_POST['application_number'] ?? '' ) );
		$actor_id   = get_current_user_id();
		$notes      = sanitize_textarea_field( (string) ( $_POST['operator_notes'] ?? '' ) );

		if ( empty( $app_number ) ) {
			add_settings_error( 'negar_club_messages', 'missing_app', 'شماره درخواست معتبر نمی‌باشد.', 'error' );
			return;
		}

		if ( 'approve' === $action ) {
			$raw_ceiling = $_POST['approved_ceiling'] ?? 0;
			$ceiling     = Negar_Club_Validator::validate_amount( $raw_ceiling );
			if ( false === $ceiling ) {
				add_settings_error( 'negar_club_messages', 'invalid_ceiling', 'سقف اعتبار تأیید شده نامعتبر است.', 'error' );
				return;
			}

			$validity_days = max( 1, (int) ( $_POST['validity_days'] ?? 14 ) );

			$result = Negar_Club_Application_Manager::transition_status(
				$app_number,
				Negar_Club_Application_Manager::STATUS_APPROVED,
				$actor_id,
				$notes,
				array(
					'approved_ceiling' => $ceiling,
					'validity_days'    => $validity_days,
				)
			);

			if ( is_wp_error( $result ) ) {
				add_settings_error( 'negar_club_messages', 'transition_error', $result->get_error_message(), 'error' );
			} else {
				add_settings_error(
					'negar_club_messages',
					'transition_success',
					sprintf( 'درخواست شماره %s با موفقیت تأیید شد و سقف %s تومان اختصاص یافت.', esc_html( $app_number ), number_format( $ceiling ) ),
					'success'
				);
			}
		} elseif ( 'reject' === $action ) {
			if ( empty( trim( $notes ) ) ) {
				add_settings_error( 'negar_club_messages', 'missing_reason', 'درج علت رد درخواست توسط کارشناس الزامی است.', 'error' );
				return;
			}

			$result = Negar_Club_Application_Manager::transition_status(
				$app_number,
				Negar_Club_Application_Manager::STATUS_REJECTED,
				$actor_id,
				$notes
			);

			if ( is_wp_error( $result ) ) {
				add_settings_error( 'negar_club_messages', 'transition_error', $result->get_error_message(), 'error' );
			} else {
				add_settings_error(
					'negar_club_messages',
					'transition_success',
					sprintf( 'درخواست شماره %s با موفقیت رد شد.', esc_html( $app_number ) ),
					'success'
				);
			}
		}
	}

	/**
	 * Render the Persian RTL admin management screen.
	 *
	 * @return void
	 */
	public static function render_admin_page() {
		if ( ! Negar_Club_Roles::current_user_can_operate() ) {
			wp_die( 'شما اجازه مشاهده این صفحه را ندارید.' );
		}

		$current_status = sanitize_key( (string) ( $_GET['status'] ?? 'all' ) );
		$search_query   = sanitize_text_field( (string) ( $_GET['s'] ?? '' ) );
		$paged          = max( 1, (int) ( $_GET['paged'] ?? 1 ) );

		$data = Negar_Club_Application_Manager::get_admin_applications(
			array(
				'status'   => $current_status,
				'search'   => $search_query,
				'page'     => $paged,
				'per_page' => 20,
			)
		);

		$items       = $data['items'];
		$total       = $data['total'];
		$total_pages = $data['total_pages'];

		$status_counts = self::get_status_counts();
		?>
		<div class="wrap negar-club-admin-wrap" dir="rtl" style="text-align: right; font-family: inherit;">
			<h1 class="wp-heading-inline" style="margin-bottom: 16px;">
				<span class="dashicons dashicons-id" style="font-size: 28px; width: 28px; height: 28px; vertical-align: middle; margin-left: 8px;"></span>
				بررسی درخواست‌های عضویت باشگاه اقساطی نگار
			</h1>

			<?php settings_errors( 'negar_club_messages' ); ?>

			<!-- Status Filter Pills -->
			<ul class="subsubsub" style="float: none; margin: 12px 0 16px 0;">
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-applications' ) ); ?>" class="<?php echo 'all' === $current_status ? 'current' : ''; ?>">
						همه <span class="count">(<?php echo (int) $status_counts['all']; ?>)</span>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-applications&status=pending_review' ) ); ?>" class="<?php echo 'pending_review' === $current_status ? 'current' : ''; ?>">
						در انتظار بررسی <span class="count">(<?php echo (int) $status_counts['pending_review']; ?>)</span>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-applications&status=approved' ) ); ?>" class="<?php echo 'approved' === $current_status ? 'current' : ''; ?>">
						تأیید شده <span class="count">(<?php echo (int) $status_counts['approved']; ?>)</span>
					</a> |
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=negar-club-applications&status=rejected' ) ); ?>" class="<?php echo 'rejected' === $current_status ? 'current' : ''; ?>">
						رد شده <span class="count">(<?php echo (int) $status_counts['rejected']; ?>)</span>
					</a>
				</li>
			</ul>

			<!-- Search Form -->
			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" style="margin-bottom: 16px;">
				<input type="hidden" name="page" value="negar-club-applications" />
				<?php if ( 'all' !== $current_status ) : ?>
					<input type="hidden" name="status" value="<?php echo esc_attr( $current_status ); ?>" />
				<?php endif; ?>
				<p class="search-box" style="float: left; position: static; margin: 0 0 12px 0;">
					<label class="screen-reader-text" for="negar-club-search-input">جستجوی درخواست‌ها:</label>
					<input type="search" id="negar-club-search-input" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="شماره درخواست یا شماره همراه..." />
					<input type="submit" id="search-submit" class="button" value="جستجو" />
				</p>
			</form>

			<div style="clear: both;"></div>

			<!-- Applications Table -->
			<table class="wp-list-table widefat fixed striped table-view-list" style="border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<thead>
					<tr>
						<th style="width: 140px;">شماره درخواست</th>
						<th style="width: 140px;">کاربر متقاضی</th>
						<th style="width: 120px;">شماره همراه</th>
						<th style="width: 110px;">طرح</th>
						<th style="width: 130px;">سقف درخواستی</th>
						<th style="width: 130px;">سقف مصوب</th>
						<th style="width: 130px;">وضعیت</th>
						<th style="width: 150px;">تاریخ ثبت</th>
						<th style="width: 180px;">اقدام کارشناس</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $items ) ) : ?>
						<tr>
							<td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">
								هیچ درخواستی با معیارهای انتخابی یافت نشد.
							</td>
						</tr>
					<?php else : ?>
						<?php foreach ( $items as $item ) : ?>
							<?php
							$badge_style = 'padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; display: inline-block;';
							if ( 'pending_review' === $item['status'] ) {
								$badge_style .= ' background: #fef3c7; color: #92400e; border: 1px solid #fde68a;';
							} elseif ( 'approved' === $item['status'] ) {
								$badge_style .= ' background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;';
							} elseif ( 'rejected' === $item['status'] ) {
								$badge_style .= ' background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;';
							} else {
								$badge_style .= ' background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;';
							}
							?>
							<tr>
								<td>
									<strong><code><?php echo esc_html( $item['application_number'] ); ?></code></strong>
								</td>
								<td>
									<?php echo esc_html( $item['user_name'] ?: 'کاربر شناسه #' . $item['user_id'] ); ?>
								</td>
								<td>
									<span dir="ltr"><?php echo esc_html( $item['contact_phone'] ); ?></span>
								</td>
								<td>
									<?php echo esc_html( $item['plan_id'] ); ?>
								</td>
								<td>
									<strong><?php echo number_format( (int) $item['requested_ceiling'] ); ?></strong> <small>تومان</small>
								</td>
								<td>
									<?php if ( $item['approved_ceiling'] > 0 ) : ?>
										<strong style="color: #166534;"><?php echo number_format( (int) $item['approved_ceiling'] ); ?></strong> <small>تومان</small>
									<?php else : ?>
										<span style="color: #94a3b8;">—</span>
									<?php endif; ?>
								</td>
								<td>
									<span style="<?php echo esc_attr( $badge_style ); ?>">
										<?php echo esc_html( $item['status_label'] ); ?>
									</span>
								</td>
								<td>
									<span title="<?php echo esc_attr( $item['created_at'] ); ?>">
										<?php echo esc_html( $item['created_at'] ); ?>
									</span>
								</td>
								<td>
									<?php if ( 'pending_review' === $item['status'] || 'under_review' === $item['status'] ) : ?>
										<details style="position: relative; display: inline-block;">
											<summary class="button button-small button-primary" style="cursor: pointer;">
												بررسی و اقدام
											</summary>
											<div style="position: absolute; left: 0; top: 100%; z-index: 1000; width: 320px; background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); margin-top: 6px;">
												<h4 style="margin: 0 0 10px 0; font-size: 13px;">اقدام روی درخواست <?php echo esc_html( $item['application_number'] ); ?></h4>
												
												<!-- Approve Form -->
												<form method="post" action="" style="margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
													<?php wp_nonce_field( 'negar_club_operator_review', 'negar_club_nonce' ); ?>
													<input type="hidden" name="negar_club_admin_action" value="approve" />
													<input type="hidden" name="application_number" value="<?php echo esc_attr( $item['application_number'] ); ?>" />
													
													<label style="display: block; font-size: 11px; margin-bottom: 4px; font-weight: 600;">سقف مصوب (تومان):</label>
													<input type="number" name="approved_ceiling" value="<?php echo esc_attr( $item['requested_ceiling'] ); ?>" style="width: 100%; margin-bottom: 8px;" required />
													
													<label style="display: block; font-size: 11px; margin-bottom: 4px; font-weight: 600;">مدت اعتبار (روز):</label>
													<input type="number" name="validity_days" value="14" style="width: 100%; margin-bottom: 8px;" />
													
													<label style="display: block; font-size: 11px; margin-bottom: 4px; font-weight: 600;">یادداشت کارشناس:</label>
													<textarea name="operator_notes" rows="2" style="width: 100%; margin-bottom: 8px;" placeholder="علت یا توضیحات تأیید..."></textarea>
													
													<button type="submit" class="button button-primary button-small" style="width: 100%; background: #166534; border-color: #166534;">
														✓ تأیید درخواست
													</button>
												</form>

												<!-- Reject Form -->
												<form method="post" action="">
													<?php wp_nonce_field( 'negar_club_operator_review', 'negar_club_nonce' ); ?>
													<input type="hidden" name="negar_club_admin_action" value="reject" />
													<input type="hidden" name="application_number" value="<?php echo esc_attr( $item['application_number'] ); ?>" />
													
													<label style="display: block; font-size: 11px; margin-bottom: 4px; font-weight: 600; color: #991b1b;">دلیل رد درخواست (الزامی):</label>
													<textarea name="operator_notes" rows="2" style="width: 100%; margin-bottom: 8px;" placeholder="دلیل عدم احراز شرایط..." required></textarea>
													
													<button type="submit" class="button button-secondary button-small" style="width: 100%; color: #991b1b; border-color: #fca5a5;">
														✕ رد درخواست
													</button>
												</form>
											</div>
										</details>
									<?php else : ?>
										<small style="color: #64748b;">
											<?php if ( ! empty( $item['operator_notes'] ) ) : ?>
												<span title="<?php echo esc_attr( $item['operator_notes'] ); ?>">
													<?php echo esc_html( mb_strimwidth( $item['operator_notes'], 0, 30, '...' ) ); ?>
												</span>
											<?php else : ?>
												—
											<?php endif; ?>
										</small>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>

			<!-- Pagination -->
			<?php if ( $total_pages > 1 ) : ?>
				<div class="tablenav" style="margin-top: 12px;">
					<div class="tablenav-pages">
						<span class="displaying-num"><?php echo esc_html( sprintf( '%d درخواست', $total ) ); ?></span>
						<?php
						echo paginate_links(
							array(
								'base'      => add_query_arg( 'paged', '%#%' ),
								'format'    => '',
								'prev_text' => '&laquo; قبلی',
								'next_text' => 'بعدی &raquo;',
								'total'     => $total_pages,
								'current'   => $paged,
							)
						);
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Retrieve counts per status for filter tabs.
	 *
	 * @return array<string, int>
	 */
	protected static function get_status_counts() {
		global $wpdb;

		$table = $wpdb->prefix . 'negar_club_applications';

		$results = array(
			'all'            => 0,
			'pending_review' => 0,
			'approved'       => 0,
			'rejected'       => 0,
		);

		$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
		$results['all'] = $total;

		$rows = $wpdb->get_results( "SELECT status, COUNT(*) as cnt FROM {$table} GROUP BY status" );
		if ( $rows ) {
			foreach ( $rows as $row ) {
				if ( isset( $results[ $row->status ] ) ) {
					$results[ $row->status ] = (int) $row->cnt;
				}
			}
		}

		return $results;
	}
}
