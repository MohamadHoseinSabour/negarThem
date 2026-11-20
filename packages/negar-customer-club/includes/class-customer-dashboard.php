<?php
/**
 * WooCommerce My Account VIP Customer Dashboard for Negar Customer Club.
 *
 * Implements the customer-facing VIP portal in WooCommerce My Account (/my-account/customer-club/),
 * displaying real active membership status, deposit balances, permitted purchase ceilings,
 * available capacities, and clear non-confusing guidance on in-store merchandise purchases.
 *
 * @package NegarCustomerClub
 * @version 1.2.0
 */

defined( 'ABSPATH' ) || exit;

class Negar_Club_Customer_Dashboard {

	/**
	 * Endpoint slug.
	 */
	const ENDPOINT = 'customer-club';

	/**
	 * Initialize endpoint hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'add_endpoints' ) );
		add_filter( 'query_vars', array( __CLASS__, 'add_query_vars' ), 0 );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'add_menu_item' ), 40 );
		add_action( 'woocommerce_account_' . self::ENDPOINT . '_endpoint', array( __CLASS__, 'render_dashboard_endpoint' ) );
	}

	/**
	 * Register custom rewrite endpoint.
	 */
	public static function add_endpoints() {
		add_rewrite_endpoint( self::ENDPOINT, EP_ROOT | EP_PAGES );
	}

	/**
	 * Add endpoint to query variables.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public static function add_query_vars( $vars ) {
		$vars[] = self::ENDPOINT;
		return $vars;
	}

	/**
	 * Add «باشگاه مشتریان (VIP)» to WooCommerce My Account menu.
	 *
	 * @param array $items Existing account menu items.
	 * @return array Modified menu items.
	 */
	public static function add_menu_item( $items ) {
		$new_items = array();

		foreach ( $items as $key => $label ) {
			if ( 'customer-logout' === $key ) {
				$new_items[ self::ENDPOINT ] = __( 'باشگاه مشتریان (VIP)', 'negar-club' );
			}
			$new_items[ $key ] = $label;
		}

		if ( ! isset( $new_items[ self::ENDPOINT ] ) ) {
			$new_items[ self::ENDPOINT ] = __( 'باشگاه مشتریان (VIP)', 'negar-club' );
		}

		return $new_items;
	}

	/**
	 * Render customer dashboard endpoint view.
	 *
	 * Enforces Anti-IDOR: only accesses data belonging to get_current_user_id().
	 */
	public static function render_dashboard_endpoint() {
		if ( ! is_user_logged_in() ) {
			echo '<p>' . esc_html__( 'لطفاً برای مشاهده وضعیت عضویت ابتدا وارد شوید.', 'negar-club' ) . '</p>';
			return;
		}

		$customer_id = get_current_user_id();
		$memberships = Negar_Club_Database::get_memberships_by_customer( $customer_id );
		$active_mem  = Negar_Club_Database::get_active_membership_by_customer( $customer_id );

		?>
		<div class="negar-vip-account-dashboard" dir="rtl" style="font-family: inherit;">

			<?php if ( $active_mem ) :
				$plan_title = 'negar_aghaz' === $active_mem->plan_id ? 'نگار آغاز' : ( 'negar_plus' === $active_mem->plan_id ? 'نگار پلاس' : ( 'negar_vizhe' === $active_mem->plan_id ? 'نگار ویژه' : $active_mem->plan_id ) );
			?>
				<!-- Active VIP Member Card -->
				<div class="vip-header-card" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 25px; border-radius: 16px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
					<div style="position: absolute; left: -20px; top: -20px; width: 120px; height: 120px; background: rgba(222, 31, 56, 0.15); border-radius: 50%; filter: blur(30px);"></div>
					<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
						<div>
							<div style="display: inline-block; background: #de1f38; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 8px;">
								عضو VIP باشگاه نگار
							</div>
							<h2 style="margin: 0; color: #fff; font-size: 1.4em;">طرح <?php echo esc_html( $plan_title ); ?></h2>
							<p style="margin: 5px 0 0; color: #94a3b8; font-size: 13px;">
								شماره سفارش فعال‌سازی: #<?php echo esc_html( $active_mem->order_id ); ?> | تاریخ عضویت: <?php echo esc_html( substr( $active_mem->created_at, 0, 10 ) ); ?>
							</p>
						</div>
						<div style="text-align: left;">
							<?php if ( 'active' === $active_mem->status ) : ?>
								<span style="background: #10b981; color: #fff; padding: 6px 14px; border-radius: 8px; font-weight: bold; font-size: 13px;">فعال</span>
							<?php elseif ( 'suspended' === $active_mem->status ) : ?>
								<span style="background: #f59e0b; color: #fff; padding: 6px 14px; border-radius: 8px; font-weight: bold; font-size: 13px;">معلق</span>
							<?php else : ?>
								<span style="background: #64748b; color: #fff; padding: 6px 14px; border-radius: 8px; font-weight: bold; font-size: 13px;"><?php echo esc_html( $active_mem->status ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Financial Metrics Grid -->
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 25px;">

					<!-- Card 1: Prepayment Deposit -->
					<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
						<div style="color: #64748b; font-size: 13px; margin-bottom: 6px;">پیش‌پرداخت نقدی پرداختی</div>
						<div style="font-size: 1.3em; font-weight: bold; color: #0f172a;">
							<?php echo esc_html( number_format( (int) $active_mem->prepayment_amount ) ); ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">تومان</span>
						</div>
						<hr style="border: 0; border-top: 1px dashed #e2e8f0; margin: 12px 0;">
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748b;">
							<span>بیعانه مصرف‌شده:</span>
							<strong><?php echo esc_html( number_format( (int) $active_mem->applied_deposit ) ); ?> تومان</strong>
						</div>
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #0284c7; margin-top: 6px; font-weight: bold;">
							<span>مانده بیعانه آزاد:</span>
							<span><?php echo esc_html( number_format( (int) $active_mem->unused_deposit ) ); ?> تومان</span>
						</div>
					</div>

					<!-- Card 2: Purchase Capacity -->
					<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
						<div style="color: #64748b; font-size: 13px; margin-bottom: 6px;">سقف مجاز خرید کالا</div>
						<div style="font-size: 1.3em; font-weight: bold; color: #0f172a;">
							<?php echo esc_html( number_format( (int) $active_mem->purchase_ceiling ) ); ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">تومان</span>
						</div>
						<hr style="border: 0; border-top: 1px dashed #e2e8f0; margin: 12px 0;">
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748b;">
							<span>خرید ثبت‌شده:</span>
							<strong><?php echo esc_html( number_format( (int) $active_mem->used_purchase_amount ) ); ?> تومان</strong>
						</div>
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #16a34a; margin-top: 6px; font-weight: bold;">
							<span>ظرفیت باقیمانده خرید:</span>
							<span><?php echo esc_html( number_format( (int) $active_mem->available_purchase_capacity ) ); ?> تومان</span>
						</div>
					</div>

					<!-- Card 3: Offline Debt & Term -->
					<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
						<div style="color: #64748b; font-size: 13px; margin-bottom: 6px;">مانده بدهی اقساط حضوری</div>
						<div style="font-size: 1.3em; font-weight: bold; color: #de1f38;">
							<?php echo esc_html( number_format( (int) $active_mem->offline_debt_amount ) ); ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">تومان</span>
						</div>
						<hr style="border: 0; border-top: 1px dashed #e2e8f0; margin: 12px 0;">
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748b;">
							<span>مدت بازپرداخت:</span>
							<strong><?php echo esc_html( $active_mem->term_months ); ?> ماه</strong>
						</div>
						<div style="display: flex; justify-content: space-between; font-size: 12px; color: #64748b; margin-top: 6px;">
							<span>مهلت اعتبار:</span>
							<span><?php echo esc_html( $active_mem->valid_until ? substr( $active_mem->valid_until, 0, 10 ) : 'نامحدود' ); ?></span>
						</div>
					</div>

				</div>

				<!-- Customer In-Store Purchasing Notice -->
				<div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
					<h3 style="margin-top: 0; font-size: 1.05em; color: #1e293b; display: flex; align-items: center; gap: 8px;">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#de1f38" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
						راهنمای استفاده از اشتراک و خرید اقساطی لوازم خانگی
					</h3>
					<ul style="margin: 0; padding-right: 20px; color: #475569; font-size: 13px; line-height: 1.8;">
						<li><strong>سقف خرید:</strong> سقف خرید مجاز به منزله کیف پول نقدی یا اعتبار هدیه نبوده و حداکثر ارزش کالاهایی است که می‌توانید با مراجعه حضوری به فروشگاه نگار انتخاب و خریداری فرمایید.</li>
						<li><strong>پیش‌پرداخت واریزی:</strong> مبلغ پیش‌پرداختی که آنلاین پرداخت نموده‌اید به عنوان سپرده و بیعانه خرید کالا نزد فروشگاه محفوظ بوده و هنگام خرید حضوری از فاکتور کالای انتخابی شما کسر می‌گردد.</li>
						<li><strong>تسویه اقساط:</strong> کلیه تسویه‌حساب‌ها و پرداخت اقساط باقیمانده به صورت حضوری در محل فروشگاه و با هماهنگی کارشناسان انجام می‌پذیرد.</li>
					</ul>
				</div>

				<?php if ( count( $memberships ) > 1 ) : ?>
					<!-- Past Memberships History -->
					<h3>تاریخچه عضویت‌های پیشین</h3>
					<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
						<thead>
							<tr>
								<th>شناسه</th>
								<th>طرح</th>
								<th>پیش‌پرداخت</th>
								<th>سقف خرید</th>
								<th>وضعیت</th>
								<th>تاریخ</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $memberships as $m ) :
								$p_title = 'negar_aghaz' === $m->plan_id ? 'نگار آغاز' : ( 'negar_plus' === $m->plan_id ? 'نگار پلاس' : ( 'negar_vizhe' === $m->plan_id ? 'نگار ویژه' : $m->plan_id ) );
							?>
								<tr>
									<td>#<?php echo esc_html( $m->id ); ?></td>
									<td><?php echo esc_html( $p_title ); ?></td>
									<td><?php echo esc_html( number_format( (int) $m->prepayment_amount ) ); ?> تومان</td>
									<td><?php echo esc_html( number_format( (int) $m->purchase_ceiling ) ); ?> تومان</td>
									<td><?php echo esc_html( $m->status ); ?></td>
									<td><?php echo esc_html( substr( $m->created_at, 0, 10 ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>

			<?php else : ?>

				<!-- Empty State / Invitation Card -->
				<div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 35px 25px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
					<div style="width: 60px; height: 60px; background: rgba(222, 31, 56, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
						<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#de1f38" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
					</div>
					<h2 style="font-size: 1.3em; color: #0f172a; margin-bottom: 8px;">شما هنوز عضو باشگاه مشتریان VIP نیستید</h2>
					<p style="color: #64748b; max-width: 500px; margin: 0 auto 20px; font-size: 14px; line-height: 1.7;">
						با پرداخت آنلاین پیش‌پرداخت طرح‌های VIP باشگاه نگار، از سقف مجاز خرید اقساطی لوازم خانگی بهره‌مند شده و مبلغ پیش‌پرداخت خود را هنگام خرید حضوری در فروشگاه کسر فرمایید.
					</p>
					<a href="<?php echo esc_url( home_url( '/customer-club/' ) ); ?>" class="button button-primary" style="background: #de1f38; border-color: #de1f38; color: #fff; padding: 10px 24px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block;">
						مشاهده و خرید طرح‌های VIP
					</a>
				</div>

			<?php endif; ?>

		</div>
		<?php
	}
}
