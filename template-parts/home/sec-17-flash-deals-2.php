<?php
/**
 * Section 17: Flash Deals Countdown Carousel #2 (HOME-17)
 * Reference: Demo 43 Section 16 (Top: 7892px, Height: ~568px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sale_products_2 = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 6,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-17-flash-deals-2 py-6" aria-label="<?php esc_attr_e( 'پیشنهادات تخفیف شگفت‌انگیز سری دوم', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="rounded-3xl bg-gradient-to-l from-rose-700 via-rose-600 to-red-600 p-4 md:p-6 text-white shadow-xl">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">

				<!-- Lead Info & Countdown (3 Cols) -->
				<div class="lg:col-span-3 flex flex-col items-center lg:items-start text-center lg:text-right">
					<div class="flex items-center gap-2 mb-2">
						<svg class="text-amber-300 animate-pulse" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
							<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
						</svg>
						<span class="text-xs font-bold text-rose-200 tracking-wider"><?php esc_html_e( 'تخفیف شگفت‌انگیز روز', 'negar-shop' ); ?></span>
					</div>

					<h2 class="text-2xl md:text-3xl font-black text-white leading-tight mb-2">
						<?php esc_html_e( 'پیشنهاد تخفیفات روز', 'negar-shop' ); ?>
					</h2>
					<p class="text-xs text-rose-100/90 leading-relaxed mb-4">
						<?php esc_html_e( 'فرصت طلایی خرید با کمترین قیمت بازار به همراه ارسال سریع', 'negar-shop' ); ?>
					</p>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-1.5 text-xs font-bold bg-white text-rose-600 hover:bg-rose-50 px-5 py-2.5 rounded-full transition shadow-md">
						<span><?php esc_html_e( 'مشاهده همه تخفیف‌ها', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</a>
				</div>

				<!-- Products Carousel (9 Cols) -->
				<div class="lg:col-span-9 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
					<?php
					if ( $sale_products_2->have_posts() ) :
						while ( $sale_products_2->have_posts() ) :
							$sale_products_2->the_post();
							?>
							<div class="flash-deal-card-wrap">
								<?php get_template_part( 'template-parts/product/card' ); ?>
							</div>
							<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>
				</div>

			</div>

		</div>
	</div>
</section>
