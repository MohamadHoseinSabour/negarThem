<?php
/**
 * Promotional Middle Banners Grid Component (HOME-04)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="negar-banners-grid-section py-6" aria-label="<?php esc_attr_e( 'بنرهای تبلیغاتی منتخب', 'negar-shop' ); ?>">
	<div class="container">
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

			<!-- Banner 1 -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="banner-card group relative rounded-2xl overflow-hidden p-6 bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 text-white min-h-[160px] flex flex-col justify-between shadow-xs border border-indigo-950/40">
				<div>
					<span class="badge bg-white/20 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
						<?php esc_html_e( 'تجهیزات گیمینگ', 'negar-shop' ); ?>
					</span>
					<h3 class="text-base font-black text-white m-0 leading-snug">
						<?php esc_html_e( 'مانیتور و کنسول بازی', 'negar-shop' ); ?>
					</h3>
					<p class="text-2xs text-indigo-200 m-0 mt-1"><?php esc_html_e( 'تخفیف ویژه تا ۳۰٪', 'negar-shop' ); ?></p>
				</div>
				<div class="flex items-center gap-1 text-xs font-bold text-indigo-300 group-hover:text-white transition group-hover:translate-x-[-4px]">
					<span><?php esc_html_e( 'خرید کالاها', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
			</a>

			<!-- Banner 2 -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="banner-card group relative rounded-2xl overflow-hidden p-6 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 text-white min-h-[160px] flex flex-col justify-between shadow-xs border border-emerald-950/40">
				<div>
					<span class="badge bg-white/20 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
						<?php esc_html_e( 'لوازم دیجیتال', 'negar-shop' ); ?>
					</span>
					<h3 class="text-base font-black text-white m-0 leading-snug">
						<?php esc_html_e( 'لپ‌تاپ‌های مهندسی و اداری', 'negar-shop' ); ?>
					</h3>
					<p class="text-2xs text-emerald-200 m-0 mt-1"><?php esc_html_e( 'با گارانتی معتبر شرکتی', 'negar-shop' ); ?></p>
				</div>
				<div class="flex items-center gap-1 text-xs font-bold text-emerald-300 group-hover:text-white transition group-hover:translate-x-[-4px]">
					<span><?php esc_html_e( 'مشاهده مدل‌ها', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
			</a>

			<!-- Banner 3 -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="banner-card group relative rounded-2xl overflow-hidden p-6 bg-gradient-to-br from-rose-900 via-pink-900 to-slate-900 text-white min-h-[160px] flex flex-col justify-between shadow-xs border border-rose-950/40">
				<div>
					<span class="badge bg-white/20 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
						<?php esc_html_e( 'صدا و موسیقی', 'negar-shop' ); ?>
					</span>
					<h3 class="text-base font-black text-white m-0 leading-snug">
						<?php esc_html_e( 'اسپیکر و ساندبار خانگی', 'negar-shop' ); ?>
					</h3>
					<p class="text-2xs text-rose-200 m-0 mt-1"><?php esc_html_e( 'صدای شفاف و باکیفیت', 'negar-shop' ); ?></p>
				</div>
				<div class="flex items-center gap-1 text-xs font-bold text-rose-300 group-hover:text-white transition group-hover:translate-x-[-4px]">
					<span><?php esc_html_e( 'انتخاب اسپیکر', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
			</a>

			<!-- Banner 4 -->
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="banner-card group relative rounded-2xl overflow-hidden p-6 bg-gradient-to-br from-amber-900 via-amber-800 to-yellow-950 text-white min-h-[160px] flex flex-col justify-between shadow-xs border border-amber-950/40">
				<div>
					<span class="badge bg-white/20 backdrop-blur-xs text-white text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-1.5">
						<?php esc_html_e( 'لوازم جانبی', 'negar-shop' ); ?>
					</span>
					<h3 class="text-base font-black text-white m-0 leading-snug">
						<?php esc_html_e( 'پاوربانک و شارژر سریع', 'negar-shop' ); ?>
					</h3>
					<p class="text-2xs text-amber-200 m-0 mt-1"><?php esc_html_e( 'تضمین اصالت و سلامت باتری', 'negar-shop' ); ?></p>
				</div>
				<div class="flex items-center gap-1 text-xs font-bold text-amber-300 group-hover:text-white transition group-hover:translate-x-[-4px]">
					<span><?php esc_html_e( 'خرید شارژر', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
			</a>

		</div>
	</div>
</section>
