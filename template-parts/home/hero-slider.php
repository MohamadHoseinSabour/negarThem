<?php
/**
 * Hero Section (Hero Banner + Flash Deal Card) - Exact Match to Demo 43 (Image 2)
 * Reference: Demo 43 Section 1 (Top: 464px, Height: 475px)
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<section class="negar-hero-landing-section py-4 md:py-6" aria-label="<?php esc_attr_e( 'بنر اصلی و پیشنهاد لحظه‌ای', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Light Outer Wrapper Frame matching Demo 43 -->
		<div class="bg-[#edf2f7]/80 rounded-[36px] p-2.5 sm:p-4 border border-slate-200/60 shadow-xs">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">

				<!-- Right Column in RTL: Big Crimson Slider (8 Cols) -->
				<div class="lg:col-span-8 flex relative">
					<div class="relative w-full rounded-[28px] overflow-hidden bg-gradient-to-r from-rose-700 via-rose-600 to-[#e90544] text-white p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between shadow-lg shadow-rose-600/15 group min-h-[380px] lg:min-h-[440px]">
						
						<!-- Decorative concentric shapes & ambient blur -->
						<div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
						<div class="absolute -bottom-20 -left-20 w-80 h-80 rounded-full bg-black/15 blur-xl pointer-events-none"></div>

						<!-- Text and CTA -->
						<div class="relative z-10 flex flex-col items-start justify-center max-w-sm text-right mb-6 md:mb-0">
							<span class="inline-block text-xs sm:text-sm font-medium text-rose-100 mb-2">
								<?php esc_html_e( 'خرید دسته‌های ایکس باکس', 'negar-shop' ); ?>
							</span>
							
							<h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-6 tracking-tight">
								<?php esc_html_e( 'خرید برترین محصولاتــــــــ', 'negar-shop' ); ?>
							</h1>

							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-white">
								<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
							</a>
						</div>

						<!-- 3D Controller Image -->
						<div class="relative z-10 w-64 sm:w-80 md:w-96 flex items-center justify-center shrink-0">
							<img
								src="<?php echo esc_url( $kaveh_base . '/hero/hero-banner-main.png' ); ?>"
								alt="<?php esc_attr_e( 'دسته بازی ایکس باکس', 'negar-shop' ); ?>"
								class="w-full h-auto object-contain drop-shadow-2xl group-hover:scale-105 transition-transform duration-500"
								loading="eager"
								onerror="this.src='https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=600&auto=format&fit=crop&q=80'"
							>
						</div>

						<!-- Side Slider Navigation Circular Buttons (Nested in Notches) -->
						<div class="absolute inset-y-0 left-2.5 flex items-center z-20">
							<button type="button" class="w-9 h-9 rounded-full bg-white hover:bg-rose-50 text-rose-600 flex items-center justify-center shadow-md transition-all hover:scale-110" aria-label="بعدی">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
							</button>
						</div>
						<div class="absolute inset-y-0 right-2.5 flex items-center z-20">
							<button type="button" class="w-9 h-9 rounded-full bg-white hover:bg-rose-50 text-rose-600 flex items-center justify-center shadow-md transition-all hover:scale-110" aria-label="قبلی">
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
							</button>
						</div>

					</div>
				</div>

				<!-- Left Column in RTL: Flash Deal Card matching Image 2 (4 Cols) -->
				<div class="lg:col-span-4 flex relative">
					<div class="relative w-full rounded-[28px] bg-white border border-slate-100 p-5 sm:p-6 shadow-sm flex flex-col justify-between overflow-visible">
						
						<!-- Tilted Ribbon Badge in Top Corner -->
						<div class="flex items-center justify-between mb-2">
							<div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-rose-600 to-red-600 text-white text-xs font-bold px-3 py-1 rounded-2xl shadow-md shadow-rose-600/30">
								<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
								<span><?php esc_html_e( 'تخفیفات لحظه‌ای', 'negar-shop' ); ?></span>
							</div>

							<!-- Circular Discount Badge -->
							<span class="inline-flex items-center justify-center bg-rose-600 text-white text-xs font-black px-2.5 py-0.5 rounded-full shadow-xs">
								٪۳۵
							</span>
						</div>

						<!-- Product Image inside Concentric Circles -->
						<div class="relative w-44 sm:w-48 aspect-square mx-auto flex items-center justify-center my-2">
							<div class="absolute inset-0 rounded-full border border-slate-100 scale-100 pointer-events-none"></div>
							<div class="absolute inset-2 rounded-full border border-dashed border-slate-200/80 scale-95 pointer-events-none"></div>
							<div class="absolute inset-4 rounded-full bg-slate-50/80 pointer-events-none"></div>
							<img
								src="<?php echo esc_url( $kaveh_base . '/hero/hero-product-featured.png' ); ?>"
								alt="بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5"
								class="relative z-10 max-h-36 max-w-full object-contain hover:scale-105 transition-transform duration-300"
								loading="lazy"
								onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=400&auto=format&fit=crop&q=80'"
							>
						</div>

						<!-- Title -->
						<h3 class="text-xs sm:text-sm font-bold text-[#212341] leading-snug line-clamp-2 mb-2 text-center hover:text-rose-600 transition-colors">
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
								<?php esc_html_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>
							</a>
						</h3>

						<!-- Reviews & Rating Row -->
						<div class="flex items-center justify-between text-3xs text-slate-400 py-2 border-t border-slate-100 mb-2">
							<div class="flex items-center gap-1 text-amber-500 font-bold">
								<span>★</span>
								<span>۵/۴.۵</span>
							</div>
							<div class="line flex-grow h-px bg-slate-100 mx-2"></div>
							<span><?php esc_html_e( '۲ نقد و بررسی', 'negar-shop' ); ?></span>
						</div>

						<!-- Price Row -->
						<div class="flex items-center justify-between pt-1">
							<div class="text-xs font-black text-rose-600">
								<span>۴,۱۹۹,۰۰۰</span>
								<span class="text-3xs font-normal text-slate-500">تومان</span>
							</div>
							<del class="text-3xs text-slate-400 line-through">۶,۴۸۸,۱۰۰</del>
						</div>

						<!-- Circular Red Button at Bottom Center of Card -->
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-9 h-9 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-lg shadow-rose-600/30 absolute -bottom-4.5 left-1/2 -translate-x-1/2 z-20 transition hover:scale-110" aria-label="مشاهده محصول">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>

					</div>
				</div>

			</div>
		</div>

	</div>
</section>
