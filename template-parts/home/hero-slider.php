<?php
/**
 * Hero Section (Hero Banner + Flash Deal Card) - 100% Exact Match to Demo 43 (Image 2)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<section class="landing-nk py-6" aria-label="<?php esc_attr_e( 'بنر اصلی و پیشنهاد لحظه‌ای', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Outer Frame with Ice-Blue Backdrop extending behind slider and card -->
		<div class="landing-nk__wrapper flex flex-col lg:flex-row items-stretch gap-6">

			<!-- Right Column in RTL: Big Crimson Banner with Organic Cutout Notches -->
			<div class="flex-1 min-w-0 relative">
				<div class="landing-nk__carousel flex flex-col md:flex-row items-center justify-between text-white relative">
					
					<!-- Left Text Detail -->
					<div class="relative z-10 flex flex-col items-start justify-center max-w-sm text-right mb-6 md:mb-0">
						<span class="inline-block text-xs sm:text-sm font-medium text-rose-100 mb-2">
							<?php esc_html_e( 'خرید دسته‌های ایکس باکس', 'negar-shop' ); ?>
						</span>
						
						<h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-8 tracking-tight">
							<?php esc_html_e( 'خرید برترین محصولاتــــــــ', 'negar-shop' ); ?>
						</h1>

						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-white">
							<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>

					<!-- Right Image: Xbox Controller with Concentric Circles & Bubble Drop -->
					<div class="hero-circles-wrapper shrink-0 relative z-10">
						<span class="h-circle h-circle-1"></span>
						<span class="h-circle h-circle-2"></span>
						<span class="h-circle h-circle-3"></span>
						<span class="h-circle h-circle-4"></span>

						<!-- Decorative Corner Sparks SVG -->
						<svg xmlns="http://www.w3.org/2000/svg" width="48" height="24" fill="none" viewBox="0 0 48 24" class="absolute top-4 right-4 opacity-75">
							<path stroke="#fff" stroke-linecap="round" stroke-width="2" d="M1 12h10M37 12h10M24 1v10M24 13v10"/>
						</svg>

						<img
							src="<?php echo esc_url( $kaveh_base . '/hero-xbox-bubble.png' ); ?>"
							alt="<?php esc_attr_e( 'دسته بازی ایکس باکس', 'negar-shop' ); ?>"
							class="relative z-10 max-h-[290px] object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-105"
							loading="eager"
						>
					</div>

					<!-- Slider Navigation Buttons Nested Inside Cutout Notches -->
					<button type="button" class="landing-nk__btn-prev" aria-label="بعدی">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</button>
					<button type="button" class="landing-nk__btn-next" aria-label="قبلی">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</button>

				</div>
			</div>

			<!-- Left Column in RTL: Instant Flash Deal Card matching Image 2 (Width: ~295px) -->
			<div class="w-full lg:w-[295px] shrink-0 relative flex">
				<div class="landing-nk__deal-card w-full">
					
					<!-- Top Row: Ribbon Badge & Discount Pill -->
					<div class="flex items-center justify-between mb-2">
						<div class="deal-ribbon inline-flex items-center gap-1.5">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
							<span><?php esc_html_e( 'تخفیفات لحظه‌ای', 'negar-shop' ); ?></span>
						</div>

						<span class="inline-flex items-center justify-center bg-rose-600 text-white text-xs font-black px-2.5 py-0.5 rounded-full shadow-xs">
							٪ ۳۵
						</span>
					</div>

					<!-- Centered Game Product with Concentric Dashed Circles -->
					<div class="relative w-44 aspect-square mx-auto flex items-center justify-center my-3">
						<div class="absolute inset-0 rounded-full border border-slate-100 scale-100 pointer-events-none"></div>
						<div class="absolute inset-2 rounded-full border border-dashed border-slate-200 scale-95 pointer-events-none"></div>
						<div class="absolute inset-4 rounded-full bg-slate-50/80 pointer-events-none"></div>
						<img
							src="<?php echo esc_url( $kaveh_base . '/hero-game-fc26.png' ); ?>"
							alt="بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5"
							class="relative z-10 max-h-36 max-w-full object-contain hover:scale-105 transition-transform duration-300"
							loading="lazy"
						>
					</div>

					<!-- Title -->
					<h3 class="text-xs sm:text-sm font-bold text-[#212341] leading-snug line-clamp-2 mb-3 text-center hover:text-rose-600 transition-colors">
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
					<div class="flex items-center justify-between pt-1 mb-3">
						<div class="text-xs font-black text-rose-600 flex items-center gap-1">
							<span>۴,۱۹۹,۰۰۰</span>
							<span class="text-3xs font-normal text-slate-500">تومان</span>
						</div>
						<del class="text-3xs text-slate-400 line-through">۶,۴۸۸,۱۰۰</del>
					</div>

					<!-- Bottom Red Circular Button in Semicircular Cutout Notch -->
					<div class="relative w-full flex justify-center -mb-8">
						<a 
							href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
							class="w-10 h-10 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center shadow-lg shadow-rose-600/35 transition hover:scale-110" 
							aria-label="مشاهده محصول"
						>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>

				</div>
			</div>

		</div>

	</div>
</section>
