<?php
/**
 * Section 16: Single Featured Deal Spotlight Bar (HOME-16)
 * Reference: Demo 43 Section 15 (Top: 7649px, Height: ~193px)
 * Title: "ساعت هوشمند اپل مدل SE Aluminum Case 44mm"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$deal_img   = $upload_dir['baseurl'] . '/kaveh-demo/products/iphone-17-pro-max.png';
?>

<section class="negar-sec-16-spotlight py-4" aria-label="<?php esc_attr_e( 'پیشنهاد اختصاصی روز', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="bg-gradient-to-l from-slate-100 via-rose-50/40 to-white border border-slate-200/80 rounded-3xl p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xs">
			
			<div class="flex items-center gap-4">
				<div class="w-20 h-20 shrink-0 bg-white rounded-2xl p-2 border border-slate-200/60 shadow-xs flex items-center justify-center">
					<img src="<?php echo esc_url( $deal_img ); ?>" alt="<?php esc_attr_e( 'ساعت هوشمند اپل SE', 'negar-shop' ); ?>" class="w-full h-full object-contain">
				</div>
				<div>
					<span class="text-3xs font-black text-rose-600 bg-rose-100/70 px-2 py-0.5 rounded-full inline-block mb-1">
						<?php esc_html_e( 'پیشنهاد طلایی روز', 'negar-shop' ); ?>
					</span>
					<h3 class="text-sm md:text-base font-black text-[#212341]">
						<?php esc_html_e( 'ساعت هوشمند اپل مدل SE Aluminum Case 44mm', 'negar-shop' ); ?>
					</h3>
					<div class="flex items-center gap-3 mt-1">
						<span class="text-xs font-black text-rose-600">۴۴,۴۹۰,۰۰۰ تومان</span>
						<span class="text-3xs text-slate-400 line-through">۴۹,۰۰۰,۰۰۰ تومان</span>
						<span class="text-3xs font-bold text-amber-500 flex items-center gap-1">★ ۴.۸</span>
					</div>
				</div>
			</div>

			<div class="flex items-center gap-3 w-full md:w-auto justify-end">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-6 py-3 rounded-full transition shadow-md">
					<span><?php esc_html_e( 'خرید فوری با تخفیف', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</div>

		</div>
	</div>
</section>
