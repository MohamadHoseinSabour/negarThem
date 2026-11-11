<?php
/**
 * My Account Dashboard Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
?>

<div class="negar-account-dashboard">

	<!-- Welcome Banner -->
	<div class="welcome-card bg-gradient-to-l from-primary/10 via-primary/5 to-transparent border border-primary/20 rounded-2xl p-6 sm:p-8 mb-8">
		<h2 class="text-lg sm:text-xl font-black text-dark mb-2">
			<?php
			printf(
				/* translators: 1: user display name */
				esc_html__( 'سلام %s، خوش آمدید', 'negar-shop' ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>'
			);
			?>
		</h2>
		<p class="text-xs sm:text-sm text-muted leading-relaxed">
			<?php
			printf(
				/* translators: 1: orders URL 2: addresses URL 3: account details URL */
				wp_kses_post( __( 'از طریق پیشخوان کاربری خود می‌توانید <a href="%1$s" class="text-primary font-bold hover:underline">آخرین سفارش‌ها</a> را مشاهده کنید، <a href="%2$s" class="text-primary font-bold hover:underline">نشانی‌های ارسال و صورت‌حساب</a> را مدیریت نمایید و <a href="%3$s" class="text-primary font-bold hover:underline">مشخصات حساب</a> خود را ویرایش کنید.', 'negar-shop' ) ),
				esc_url( wc_get_endpoint_url( 'orders' ) ),
				esc_url( wc_get_endpoint_url( 'edit-address' ) ),
				esc_url( wc_get_endpoint_url( 'edit-account' ) )
			);
			?>
		</p>
	</div>

	<!-- Quick Action Cards Grid -->
	<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
		
		<!-- Orders Card -->
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="quick-card bg-surface border border-border hover:border-primary rounded-xl p-5 shadow-xs hover:shadow-md transition-all flex items-center gap-4 group">
			<div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			</div>
			<div>
				<h4 class="text-sm font-bold text-dark group-hover:text-primary transition-colors"><?php esc_html_e( 'سفارش‌های من', 'negar-shop' ); ?></h4>
				<span class="text-xs text-muted"><?php esc_html_e( 'پیگیری وضعیت مرسوله', 'negar-shop' ); ?></span>
			</div>
		</a>

		<!-- Addresses Card -->
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="quick-card bg-surface border border-border hover:border-primary rounded-xl p-5 shadow-xs hover:shadow-md transition-all flex items-center gap-4 group">
			<div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
			</div>
			<div>
				<h4 class="text-sm font-bold text-dark group-hover:text-primary transition-colors"><?php esc_html_e( 'نشانی‌های تحویل', 'negar-shop' ); ?></h4>
				<span class="text-xs text-muted"><?php esc_html_e( 'مدیریت آدرس‌های ثبت‌شده', 'negar-shop' ); ?></span>
			</div>
		</a>

		<!-- Profile Details Card -->
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="quick-card bg-surface border border-border hover:border-primary rounded-xl p-5 shadow-xs hover:shadow-md transition-all flex items-center gap-4 group">
			<div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
			</div>
			<div>
				<h4 class="text-sm font-bold text-dark group-hover:text-primary transition-colors"><?php esc_html_e( 'جزئیات حساب کاربری', 'negar-shop' ); ?></h4>
				<span class="text-xs text-muted"><?php esc_html_e( 'ویرایش نام و اطلاعات حساب', 'negar-shop' ); ?></span>
			</div>
		</a>

	</div>

</div>
