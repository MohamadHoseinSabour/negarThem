<?php
/**
 * Hero Slider and Side Banners Component (HOME-01)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="negar-hero-section py-6" aria-label="<?php esc_attr_e( 'اسلایدر اصلی و بنرهای ویژه', 'negar-shop' ); ?>">
	<div class="container">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

			<!-- 1. Wide Hero Slider (8 Cols on Desktop) -->
			<div class="lg:col-span-8">
				<div class="hero-slider-container relative rounded-2xl overflow-hidden bg-slate-900 text-white min-h-[320px] md:min-h-[420px] flex items-center shadow-md">
					
					<!-- Slides Wrapper -->
					<div class="hero-slides-wrapper w-full h-full relative">

						<!-- Slide 1 -->
						<div class="hero-slide active absolute inset-0 flex items-center p-6 md:p-12 bg-gradient-to-l from-slate-950/90 via-slate-900/60 to-transparent z-10 transition-opacity duration-500">
							<div class="slide-content max-w-md text-right">
								<span class="badge badge-discount text-2xs font-bold px-3 py-1 mb-3 inline-block rounded-full">
									<?php esc_html_e( 'جشنواره تخفیف‌های پاییزی', 'negar-shop' ); ?>
								</span>
								<h2 class="text-2xl md:text-4xl font-black text-white leading-tight mb-3">
									<?php esc_html_e( 'جدیدترین گجت‌های هوشمند با ۴۰٪ تخفیف', 'negar-shop' ); ?>
								</h2>
								<p class="text-xs md:text-sm text-slate-300 mb-6 leading-relaxed">
									<?php esc_html_e( 'بهترین انتخاب برای دوستداران تکنولوژی، ساعت‌های هوشمند، هندزفری‌های بی‌سیم با گارانتی اصالت کالا.', 'negar-shop' ); ?>
								</p>
								<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="btn btn-primary btn-lg rounded-xl shadow-lg shadow-emerald-900/40 font-bold inline-flex items-center gap-2">
									<span><?php esc_html_e( 'خرید و مشاهده محصولات', 'negar-shop' ); ?></span>
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
										<polyline points="15 18 9 12 15 6"></polyline>
									</svg>
								</a>
							</div>
						</div>

						<!-- Slide 2 -->
						<div class="hero-slide opacity-0 pointer-events-none absolute inset-0 flex items-center p-6 md:p-12 bg-gradient-to-l from-emerald-950/90 via-emerald-900/60 to-transparent z-10 transition-opacity duration-500">
							<div class="slide-content max-w-md text-right">
								<span class="badge badge-stock text-2xs font-bold px-3 py-1 mb-3 inline-block rounded-full">
									<?php esc_html_e( 'محصولات پرفروش سال', 'negar-shop' ); ?>
								</span>
								<h2 class="text-2xl md:text-4xl font-black text-white leading-tight mb-3">
									<?php esc_html_e( 'لوازم خانگی و آشپزخانه مدرن', 'negar-shop' ); ?>
								</h2>
								<p class="text-xs md:text-sm text-slate-200 mb-6 leading-relaxed">
									<?php esc_html_e( 'ارسال رایگان برای خریدهای اول با کد تخفیف اختصاصی NEGAR-FIRST.', 'negar-shop' ); ?>
								</p>
								<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="btn btn-accent btn-lg rounded-xl shadow-lg font-bold inline-flex items-center gap-2">
									<span><?php esc_html_e( 'مشاهده حراجی‌ها', 'negar-shop' ); ?></span>
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
										<polyline points="15 18 9 12 15 6"></polyline>
									</svg>
								</a>
							</div>
						</div>

					</div>

					<!-- Slider Navigation Dots & Controls -->
					<div class="hero-slider-controls absolute bottom-4 left-6 z-20 flex items-center gap-2">
						<button type="button" class="hero-nav-prev w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition" aria-label="<?php esc_attr_e( 'اسلاید قبلی', 'negar-shop' ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</button>
						<div class="hero-dots flex items-center gap-1.5">
							<span class="hero-dot active w-6 h-2 rounded-full bg-emerald-500 transition-all cursor-pointer" data-slide="0"></span>
							<span class="hero-dot w-2 h-2 rounded-full bg-white/40 transition-all cursor-pointer" data-slide="1"></span>
						</div>
						<button type="button" class="hero-nav-next w-8 h-8 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition" aria-label="<?php esc_attr_e( 'اسلاید بعدی', 'negar-shop' ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
								<polyline points="15 18 9 12 15 6"></polyline>
							</svg>
						</button>
					</div>

				</div>
			</div>

			<!-- 2. Two Side Banners (4 Cols on Desktop) -->
			<div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">

				<!-- Banner 1 -->
				<div class="promo-banner-card flex-1 relative rounded-2xl overflow-hidden bg-gradient-to-bl from-amber-500 to-orange-600 p-6 text-white min-h-[150px] md:min-h-[195px] flex flex-col justify-between shadow-xs group">
					<div class="banner-text relative z-10">
						<span class="badge bg-black/30 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
							<?php esc_html_e( 'تخفیف شگفت‌انگیز', 'negar-shop' ); ?>
						</span>
						<h3 class="text-lg md:text-xl font-black text-white m-0 leading-snug">
							<?php esc_html_e( 'ساعت هوشمند و دستبند ورزشی', 'negar-shop' ); ?>
						</h3>
						<p class="text-xs text-amber-100 m-0 mt-1"><?php esc_html_e( 'تا ۵۰٪ تخفیف طلایی', 'negar-shop' ); ?></p>
					</div>
					<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="relative z-10 text-xs font-bold text-white flex items-center gap-1 group-hover:translate-x-[-4px] transition">
						<span><?php esc_html_e( 'مشاهده کالاهای تخفیفی', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</a>
				</div>

				<!-- Banner 2 -->
				<div class="promo-banner-card flex-1 relative rounded-2xl overflow-hidden bg-gradient-to-bl from-teal-600 to-emerald-800 p-6 text-white min-h-[150px] md:min-h-[195px] flex flex-col justify-between shadow-xs group">
					<div class="banner-text relative z-10">
						<span class="badge bg-black/30 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
							<?php esc_html_e( 'پیشنهاد اختصاصی', 'negar-shop' ); ?>
						</span>
						<h3 class="text-lg md:text-xl font-black text-white m-0 leading-snug">
							<?php esc_html_e( 'انواع هندزفری و هدست بی‌سیم', 'negar-shop' ); ?>
						</h3>
						<p class="text-xs text-teal-100 m-0 mt-1"><?php esc_html_e( 'تضمین بهترین قیمت بازار', 'negar-shop' ); ?></p>
					</div>
					<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="relative z-10 text-xs font-bold text-white flex items-center gap-1 group-hover:translate-x-[-4px] transition">
						<span><?php esc_html_e( 'همین حالا خرید کنید', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</a>
				</div>

			</div>

		</div>
	</div>
</section>
