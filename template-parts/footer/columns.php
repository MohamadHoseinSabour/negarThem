<?php
/**
 * Footer Columns and Navigation Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="footer-columns-section py-12 bg-slate-950 text-slate-300 text-sm">
	<div class="container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

		<!-- Column 1: Store Intro & Contacts -->
		<div class="footer-col">
			<div class="footer-brand mb-4">
				<span class="text-xl font-black text-white tracking-tight flex items-center gap-2">
					<span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
					<?php bloginfo( 'name' ); ?>
				</span>
			</div>
			<p class="text-xs text-slate-400 leading-relaxed mb-4">
				<?php echo esc_html( apply_filters( 'negar_shop_footer_about', __( 'فروشگاه اینترنتی نگار شاپ، مرجع تخصصی خرید آنلاین با تضمین اصالت، بهترین قیمت و ارسال اکسپرس به تمام نقاط کشور.', 'negar-shop' ) ) ); ?>
			</p>
			<ul class="footer-contacts list-none m-0 p-0 space-y-2 text-xs text-slate-400">
				<li class="flex items-center gap-2">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-500 shrink-0">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
						<circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span><?php esc_html_e( 'تهران، خیابان آزادی، برج نوآوری، طبقه ۴', 'negar-shop' ); ?></span>
				</li>
				<li class="flex items-center gap-2">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-500 shrink-0">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
					</svg>
					<span class="dir-ltr text-right">۰۲۱-۱۲۳۴۵۶۷۸</span>
				</li>
				<li class="flex items-center gap-2">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald-500 shrink-0">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
						<polyline points="22,6 12,13 2,6"></polyline>
					</svg>
					<span>info@example.com</span>
				</li>
			</ul>
		</div>

		<!-- Column 2: Quick Links -->
		<div class="footer-col">
			<h4 class="text-white text-sm font-bold mb-4 relative pb-2 after:content-[''] after:absolute after:bottom-0 after:right-0 after:w-8 after:h-0.5 after:bg-emerald-500">
				<?php esc_html_e( 'دسترسی سریع', 'negar-shop' ); ?>
			</h4>
			<?php
			if ( has_nav_menu( 'footer-1' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'footer-1',
						'container'      => false,
						'menu_class'     => 'footer-menu-list list-none m-0 p-0 space-y-2 text-xs text-slate-400',
					)
				);
			else :
				?>
				<ul class="footer-menu-list list-none m-0 p-0 space-y-2 text-xs text-slate-400">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></a></li>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'فروشگاه و محصولات', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'حساب کاربری من', 'negar-shop' ); ?></a></li>
					<?php endif; ?>
					<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'مجله اینترنتی و وبلاگ', 'negar-shop' ); ?></a></li>
				</ul>
				<?php
			endif;
			?>
		</div>

		<!-- Column 3: Customer Service & Policies -->
		<div class="footer-col">
			<h4 class="text-white text-sm font-bold mb-4 relative pb-2 after:content-[''] after:absolute after:bottom-0 after:right-0 after:w-8 after:h-0.5 after:bg-emerald-500">
				<?php esc_html_e( 'خدمات مشتریان', 'negar-shop' ); ?>
			</h4>
			<?php
			if ( has_nav_menu( 'footer-2' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'footer-2',
						'container'      => false,
						'menu_class'     => 'footer-menu-list list-none m-0 p-0 space-y-2 text-xs text-slate-400',
					)
				);
			else :
				?>
				<ul class="footer-menu-list list-none m-0 p-0 space-y-2 text-xs text-slate-400">
					<li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'پرسش‌های متداول (FAQ)', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shipping-policy' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'رویه ارسال سفارش‌ها', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/return-policy' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'شرایط بازگشت کالا', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'حریم خصوصی کاربران', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="hover:text-emerald-400 transition"><?php esc_html_e( 'قوانین و مقررات خرید', 'negar-shop' ); ?></a></li>
				</ul>
				<?php
			endif;
			?>
		</div>

		<!-- Column 4: Trust Badges (Enamad & Payments) -->
		<div class="footer-col">
			<h4 class="text-white text-sm font-bold mb-4 relative pb-2 after:content-[''] after:absolute after:bottom-0 after:right-0 after:w-8 after:h-0.5 after:bg-emerald-500">
				<?php esc_html_e( 'نمادهای اعتماد الکترونیکی', 'negar-shop' ); ?>
			</h4>
			<?php get_template_part( 'template-parts/footer/trust-badges' ); ?>
		</div>

	</div>
</div>
