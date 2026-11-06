<?php
/**
 * Thank You / Order Received Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="negar-thankyou-wrapper py-8">
	<div class="container mx-auto px-4 max-w-4xl">

		<?php if ( $order ) : ?>

			<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

			<?php if ( $order->has_status( 'failed' ) ) : ?>

				<!-- Order Failed State -->
				<div class="bg-surface border border-danger/30 rounded-2xl p-6 sm:p-8 text-center shadow-sm mb-8">
					<div class="w-16 h-16 mx-auto mb-4 rounded-full bg-danger/10 text-danger flex items-center justify-center">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
					</div>
					<h2 class="text-xl font-black text-danger mb-2"><?php esc_html_e( 'متأسفانه پرداخت سفارش انجام نشد.', 'negar-shop' ); ?></h2>
					<p class="text-sm text-muted mb-6"><?php esc_html_e( 'تراکنش ناموفق بود یا توسط بانک لغو شد. می‌توانید مجدداً اقدام به پرداخت نمایید.', 'negar-shop' ); ?></p>
					<div class="flex items-center justify-center gap-4">
						<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay btn btn-primary py-2.5 px-6 text-sm font-bold"><?php esc_html_e( 'تلاش مجدد برای پرداخت', 'negar-shop' ); ?></a>
						<?php if ( is_user_logged_in() ) : ?>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay btn btn-outline btn-sm py-2.5 px-6 text-sm font-bold"><?php esc_html_e( 'حساب کاربری من', 'negar-shop' ); ?></a>
						<?php endif; ?>
					</div>
				</div>

			<?php else : ?>

				<!-- Order Success State -->
				<div class="bg-surface border border-success/30 rounded-2xl p-6 sm:p-8 text-center shadow-sm mb-8">
					<div class="w-16 h-16 mx-auto mb-4 rounded-full bg-success/10 text-success flex items-center justify-center animate-bounce">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
					</div>
					<h2 class="text-xl sm:text-2xl font-black text-dark mb-2"><?php esc_html_e( 'از خرید شما سپاسگزاریم!', 'negar-shop' ); ?></h2>
					<p class="text-sm text-muted mb-6"><?php esc_html_e( 'سفارش شما با موفقیت ثبت شد و هم‌اکنون در مرحله پردازش قرار دارد.', 'negar-shop' ); ?></p>

					<!-- Stats Ribbon -->
					<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-xl bg-slate-50 border border-border text-right">
						<div>
							<span class="text-xs text-muted block mb-1"><?php esc_html_e( 'شماره سفارش:', 'negar-shop' ); ?></span>
							<strong class="text-sm font-bold text-dark font-mono"><?php echo esc_html( $order->get_order_number() ); ?></strong>
						</div>
						<div>
							<span class="text-xs text-muted block mb-1"><?php esc_html_e( 'تاریخ ثبت:', 'negar-shop' ); ?></span>
							<strong class="text-sm font-bold text-dark"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
						</div>
						<div>
							<span class="text-xs text-muted block mb-1"><?php esc_html_e( 'مبلغ پرداختی:', 'negar-shop' ); ?></span>
							<strong class="text-sm font-bold text-primary"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
						</div>
						<div>
							<span class="text-xs text-muted block mb-1"><?php esc_html_e( 'روش پرداخت:', 'negar-shop' ); ?></span>
							<strong class="text-sm font-bold text-dark"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</div>
					</div>

				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			
			<div class="bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			</div>

		<?php else : ?>

			<div class="bg-surface border border-border rounded-2xl p-8 text-center shadow-sm">
				<p class="text-base text-dark mb-4"><?php esc_html_e( 'از سفارش شما سپاسگزاریم. سفارش شما با موفقیت دریافت شد.', 'negar-shop' ); ?></p>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary py-2.5 px-6 text-sm font-bold"><?php esc_html_e( 'بازگشت به فروشگاه', 'negar-shop' ); ?></a>
			</div>

		<?php endif; ?>

	</div>
</div>
