<?php
/**
 * Top Bar Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="negar-topbar bg-slate-900 text-slate-300 text-xs py-2 border-b border-slate-800">
	<div class="container flex items-center justify-between">
		<!-- Announcement Message -->
		<div class="topbar-announcement flex items-center gap-2">
			<span class="badge badge-discount text-2xs py-0.5 px-2 font-medium">
				<?php esc_html_e( 'پیشنهاد ویژه', 'negar-shop' ); ?>
			</span>
			<span class="text-slate-300">
				<?php echo esc_html( apply_filters( 'negar_shop_topbar_announcement', __( 'ارسال رایگان برای سفارش‌های بالای ۱,۰۰۰,۰۰۰ تومان در سراسر کشور', 'negar-shop' ) ) ); ?>
			</span>
		</div>

		<!-- Links and Support Hotline -->
		<div class="topbar-meta hidden md:flex items-center gap-6">
			<ul class="topbar-links flex items-center gap-4 list-none m-0 p-0 text-slate-400">
				<li>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="hover:text-white transition">
						<?php esc_html_e( 'پیگیری سفارش', 'negar-shop' ); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="hover:text-white transition">
						<?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="hover:text-white transition">
						<?php esc_html_e( 'درباره ما', 'negar-shop' ); ?>
					</a>
				</li>
			</ul>

			<!-- Phone Support -->
			<a href="tel:02112345678" class="hotline flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-semibold dir-ltr">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
				</svg>
				<span>۰۲۱-۱۲۳۴۵۶۷۸</span>
			</a>
		</div>
	</div>
</div>
