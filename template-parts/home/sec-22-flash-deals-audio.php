<?php
/**
 * Section 22: Audio & Headphones Flash Deals Carousel (HOME-22)
 * Reference: Demo 43 Section 21 (Top: 10210px, Height: ~568px)
 * Heading: "پیشنهاد تخفیفات روز - صوتی و هدفون"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$audio_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-22-audio-deals py-6" aria-label="<?php esc_attr_e( 'تخفیف شگفت‌انگیز صوتی و هدفون', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="rounded-3xl bg-gradient-to-l from-rose-700 via-rose-600 to-red-600 p-4 md:p-6 text-white shadow-xl">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">

				<div class="lg:col-span-3 flex flex-col items-center lg:items-start text-center lg:text-right">
					<span class="text-xs font-bold text-amber-300 tracking-wider mb-2"><?php esc_html_e( '۵۸۰ میلی‌آمپر‌ساعت باتری', 'negar-shop' ); ?></span>
					<h2 class="text-2xl md:text-3xl font-black text-white leading-tight mb-2">
						<?php esc_html_e( 'پیشنهاد تخفیفات روز صوتی', 'negar-shop' ); ?>
					</h2>
					<p class="text-xs text-rose-100/90 leading-relaxed mb-4">
						<?php esc_html_e( 'بهترین هندزفری‌ها و هدفون‌های نویزکنسلینگ با گارانتی تعویض', 'negar-shop' ); ?>
					</p>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-1.5 text-xs font-bold bg-white text-rose-600 hover:bg-rose-50 px-5 py-2.5 rounded-full transition shadow-md">
						<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</a>
				</div>

				<div class="lg:col-span-9 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
					<?php
					if ( $audio_products->have_posts() ) :
						while ( $audio_products->have_posts() ) :
							$audio_products->the_post();
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
