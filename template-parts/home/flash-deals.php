<?php
/**
 * Flash Deals / Amazing Offers Slider Component (HOME-03)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Target end time: 48 hours from now
$end_timestamp = time() + ( 48 * 3600 );

$sale_products = array();
if ( class_exists( 'WooCommerce' ) ) {
	$product_ids_on_sale = wc_get_product_ids_on_sale();
	$query_args          = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
	);

	if ( ! empty( $product_ids_on_sale ) ) {
		$query_args['post__in'] = $product_ids_on_sale;
	}

	$sale_products = new WP_Query( $query_args );
}
?>

<section class="negar-flash-deals-section py-6" aria-label="<?php esc_attr_e( 'پیشنهادات شگفت‌انگیز', 'negar-shop' ); ?>">
	<div class="container">
		<div class="flash-deals-wrapper rounded-3xl bg-gradient-to-l from-rose-600 via-rose-600 to-red-700 p-4 md:p-6 text-white shadow-xl">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">

				<!-- 1. Lead Info & Realtime Countdown (3 Cols on Desktop) -->
				<div class="lg:col-span-3 flex flex-col items-center lg:items-start text-center lg:text-right">
					<div class="deals-badge flex items-center gap-2 mb-2">
						<svg class="text-amber-300 animate-bounce" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M12 2c1.1 0 2 .9 2 2 0 .7-.4 1.4-1 1.7V7c2.8 0 5 2.2 5 5 0 2.2-1.4 4.1-3.4 4.7.3.7.4 1.5.4 2.3 0 2.8-2.2 5-5 5s-5-2.2-5-5c0-.8.1-1.6.4-2.3C3.4 16.1 2 14.2 2 12c0-2.8 2.2-5 5-5V5.7c-.6-.3-1-1-1-1.7 0-1.1.9-2 2-2h4z"/>
						</svg>
						<span class="text-xs font-bold text-rose-200 tracking-wider"><?php esc_html_e( 'فرصت محدود', 'negar-shop' ); ?></span>
					</div>

					<h2 class="text-2xl md:text-3xl font-black text-white leading-tight mb-2">
						<?php esc_html_e( 'پیشنهاد شگفت‌انگیز', 'negar-shop' ); ?>
					</h2>

					<p class="text-xs text-rose-100 mb-5 leading-relaxed max-w-xs">
						<?php esc_html_e( 'تخفیف‌های استثنایی و لحظه‌ای روی برترین کالاهای دیجیتال و لوازم خانگی.', 'negar-shop' ); ?>
					</p>

					<!-- Countdown Timer Block -->
					<div class="negar-countdown flex items-center gap-2 mb-6 dir-ltr" data-endtime="<?php echo esc_attr( $end_timestamp ); ?>">
						<div class="timer-box bg-white/20 backdrop-blur-xs rounded-xl p-2 min-w-[50px] text-center border border-white/20 shadow-xs">
							<span class="countdown-val countdown-hours block text-lg font-black text-white leading-none">18</span>
							<span class="countdown-lbl block text-3xs text-rose-200 mt-1"><?php esc_html_e( 'ساعت', 'negar-shop' ); ?></span>
						</div>
						<span class="text-white font-bold text-lg leading-none">:</span>
						<div class="timer-box bg-white/20 backdrop-blur-xs rounded-xl p-2 min-w-[50px] text-center border border-white/20 shadow-xs">
							<span class="countdown-val countdown-minutes block text-lg font-black text-white leading-none">45</span>
							<span class="countdown-lbl block text-3xs text-rose-200 mt-1"><?php esc_html_e( 'دقیقه', 'negar-shop' ); ?></span>
						</div>
						<span class="text-white font-bold text-lg leading-none">:</span>
						<div class="timer-box bg-white/20 backdrop-blur-xs rounded-xl p-2 min-w-[50px] text-center border border-white/20 shadow-xs">
							<span class="countdown-val countdown-seconds block text-lg font-black text-white leading-none">30</span>
							<span class="countdown-lbl block text-3xs text-rose-200 mt-1"><?php esc_html_e( 'ثانیه', 'negar-shop' ); ?></span>
						</div>
					</div>

					<!-- View All Deals Button -->
					<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) . '?on_sale=1' : home_url( '/' ) ); ?>" class="btn bg-white hover:bg-rose-50 text-rose-700 font-bold text-xs py-2.5 px-5 rounded-xl shadow-md transition flex items-center gap-2">
						<span><?php esc_html_e( 'مشاهده همه تخفیف‌ها', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
							<polyline points="15 18 9 12 15 6"></polyline>
						</svg>
					</a>
				</div>

				<!-- 2. Products Carousel (9 Cols on Desktop) -->
				<div class="lg:col-span-9 relative">
					<div class="flash-deals-carousel-wrap relative overflow-hidden">
						<div class="flash-deals-carousel flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 no-scrollbar">
							<?php
							if ( ! empty( $sale_products ) && $sale_products->have_posts() ) :
								while ( $sale_products->have_posts() ) :
									$sale_products->the_post();
									?>
									<div class="flash-deal-item shrink-0 w-[220px] md:w-[240px] snap-start">
										<?php get_template_part( 'template-parts/product/card' ); ?>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
							else :
								// Fallback preview cards if WooCommerce is empty
								for ( $i = 1; $i <= 4; $i++ ) :
									?>
									<div class="flash-deal-item shrink-0 w-[220px] md:w-[240px] snap-start bg-white rounded-2xl p-3 text-slate-800 shadow-md">
										<div class="badge badge-discount text-3xs font-bold px-2 py-0.5 rounded-full inline-block mb-2">۳۵٪ تخفیف</div>
										<div class="w-full aspect-square bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
											<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
												<rect x="2" y="3" width="20" height="14" rx="2"></rect>
												<line x1="8" y1="21" x2="16" y2="21"></line>
												<line x1="12" y1="17" x2="12" y2="21"></line>
											</svg>
										</div>
										<h4 class="text-xs font-bold text-slate-800 mb-2 line-clamp-2">کالای شگفت‌انگیز نمونه <?php echo esc_html( $i ); ?></h4>
										<div class="flex items-center justify-between text-xs">
											<span class="text-slate-400 line-through text-3xs">۱,۲۰۰,۰۰۰ تومان</span>
											<span class="text-rose-600 font-bold">۷۸۰,۰۰۰ تومان</span>
										</div>
									</div>
									<?php
								endfor;
							endif;
							?>
						</div>
					</div>

					<!-- Carousel Left/Right Buttons -->
					<button type="button" class="deals-nav-next absolute top-1/2 -right-3 -translate-y-1/2 w-9 h-9 rounded-full bg-white text-slate-800 shadow-lg flex items-center justify-center hover:bg-slate-100 transition z-10" aria-label="<?php esc_attr_e( 'بعدی', 'negar-shop' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</button>
					<button type="button" class="deals-nav-prev absolute top-1/2 -left-3 -translate-y-1/2 w-9 h-9 rounded-full bg-white text-slate-800 shadow-lg flex items-center justify-center hover:bg-slate-100 transition z-10" aria-label="<?php esc_attr_e( 'قبلی', 'negar-shop' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</button>
				</div>

			</div>

		</div>
	</div>
</section>
