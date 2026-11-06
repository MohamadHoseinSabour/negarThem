<?php
/**
 * Hero Section (Hero Banner + Flash Deal Card) - Using Exact Kaveh Demo Assets
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<section class="hero-section py-6 bg-slate-50">
	<div class="container grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

		<!-- Right Column: Big Crimson Hero Banner (8 Cols - matching Demo Image 2) -->
		<div class="lg:col-span-8 flex">
			<div class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-rose-700 via-rose-600 to-rose-600 text-white p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between shadow-xl shadow-rose-600/20 group">
				
				<!-- Decorative Background Radial Shapes -->
				<div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
				<div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-black/10 blur-xl pointer-events-none"></div>

				<!-- Content Column -->
				<div class="relative z-10 flex flex-col items-start justify-center max-w-sm text-right mb-6 md:mb-0">
					<span class="inline-block text-xs sm:text-sm font-medium text-rose-100 mb-2">
						<?php esc_html_e( 'خرید دسته‌های ایکس باکس', 'negar-shop' ); ?>
					</span>
					
					<h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-6">
						<?php esc_html_e( 'خرید برترین محصولات', 'negar-shop' ); ?>
					</h1>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-2 bg-white text-slate-900 hover:bg-rose-50 hover:text-rose-600 px-6 py-2.5 rounded-full font-bold text-xs sm:text-sm shadow-md transition-all hover:scale-105">
						<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
					</a>
				</div>

				<!-- 3D Controller Visual from Uploads -->
				<div class="relative z-10 w-64 sm:w-80 md:w-96 flex items-center justify-center shrink-0">
					<img
						src="<?php echo esc_url( $kaveh_base . '/hero/hero-banner-main.png' ); ?>"
						alt="<?php esc_attr_e( 'دسته بازی ایکس باکس', 'negar-shop' ); ?>"
						class="w-full h-auto object-contain drop-shadow-2xl group-hover:scale-105 transition-transform duration-500"
						loading="eager"
						onerror="this.src='https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=600&auto=format&fit=crop&q=80'"
					>
				</div>

				<!-- Left / Right Slider Arrows (matching Demo) -->
				<div class="absolute inset-y-0 left-4 flex items-center">
					<button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-rose-600 flex items-center justify-center backdrop-blur-xs transition" aria-label="بعدی">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
					</button>
				</div>
				<div class="absolute inset-y-0 right-4 flex items-center">
					<button type="button" class="w-8 h-8 rounded-full bg-white/20 hover:bg-white text-white hover:text-rose-600 flex items-center justify-center backdrop-blur-xs transition" aria-label="قبلی">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
					</button>
				</div>

			</div>
		</div>

		<!-- Left Column: Flash Deal Card (4 Cols - 34% width - matching Demo Image 2) -->
		<div class="lg:col-span-4 flex">
			<div class="relative w-full rounded-3xl bg-white border border-slate-100 p-6 shadow-xl flex flex-col justify-between">
				
				<!-- Top Badges Row -->
				<div class="flex items-center justify-between mb-3">
					<!-- Pill Badge: تخفیفات لحظه‌ای -->
					<span class="inline-flex items-center gap-1.5 bg-rose-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-xs">
						<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
						<span><?php esc_html_e( 'تخفیفات لحظه‌ای', 'negar-shop' ); ?></span>
					</span>

					<!-- Discount Badge: %۳۵ -->
					<span class="inline-flex items-center justify-center bg-rose-500 text-white text-xs font-black w-8 h-8 rounded-full shadow-xs">
						٪۳۵
					</span>
				</div>

				<!-- Product Image: EA Sports FC 24 PS5 -->
				<div class="relative w-full aspect-square max-h-48 mx-auto rounded-2xl overflow-hidden bg-slate-50 flex items-center justify-center mb-3 p-2">
					<img
						src="<?php echo esc_url( $kaveh_base . '/hero/hero-product-featured.png' ); ?>"
						alt="بازی EA Sports FC 24 نسخه استاندارد مخصوص PS5"
						class="max-h-full w-auto object-contain hover:scale-105 transition-transform duration-300"
						loading="lazy"
						onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=400&auto=format&fit=crop&q=80'"
					>
				</div>

				<!-- Rating Stars -->
				<div class="flex items-center gap-1.5 mb-1.5">
					<div class="flex text-amber-400 text-xs">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<span class="text-3xs text-slate-400 font-medium">۵/۴.۵ (۲ نقد و بررسی)</span>
				</div>

				<!-- Product Title -->
				<h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug line-clamp-2 mb-3 hover:text-rose-600 transition-colors">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php esc_html_e( 'بازی EA Sports FC 24 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>
					</a>
				</h3>

				<!-- Price Box & CTA Button -->
				<div class="pt-3 border-t border-slate-100 flex items-center justify-between">
					<div class="flex flex-col text-right">
						<span class="text-2xs text-slate-400 line-through">۶,۴۸۸,۶۰۰</span>
						<span class="text-sm sm:text-base font-black text-rose-600">۴,۱۹۹,۰۰۰ <span class="text-2xs font-normal">تومان</span></span>
					</div>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-sm bg-rose-600 hover:bg-rose-700 text-white rounded-xl py-1.5 px-3.5 font-bold text-xs shadow-xs transition">
						<?php esc_html_e( 'خرید فوری', 'negar-shop' ); ?>
					</a>
				</div>

			</div>
		</div>

	</div>
</section>
