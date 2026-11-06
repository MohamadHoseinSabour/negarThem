<?php
/**
 * Section 30: Recently Viewed Products Carousel (HOME-30)
 * Reference: Demo 43 Section 29 (Top: 14567px, Height: ~309px)
 * Heading: "آخرین محصولات مشاهده شده"
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$recent_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
?>

<section class="negar-sec-30-recent py-4 bg-slate-50/50" aria-label="<?php esc_attr_e( 'آخرین محصولات مشاهده شده', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/70">
			<div class="flex items-baseline gap-3">
				<h2 class="text-lg md:text-xl font-black text-[#212341]">
					<?php esc_html_e( 'آخرین محصولات مشاهده شده', 'negar-shop' ); ?>
				</h2>
				<span class="text-xs text-slate-400 font-medium hidden sm:inline">
					<?php esc_html_e( 'بر اساس سلیقه و جستجوهای اخیر شما', 'negar-shop' ); ?>
				</span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- Compact Cards Row -->
		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
			<?php
			if ( $recent_products->have_posts() ) :
				while ( $recent_products->have_posts() ) :
					$recent_products->the_post();
					$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
					?>
					<a href="<?php the_permalink(); ?>" class="bg-white rounded-2xl p-2.5 border border-slate-200/70 hover:border-rose-200 hover:shadow-md transition group flex flex-col justify-between">
						<div class="aspect-square rounded-xl bg-slate-50 p-2 flex items-center justify-center overflow-hidden mb-2">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium', array( 'class' => 'max-h-full max-w-full object-contain group-hover:scale-105 transition' ) ); ?>
							<?php endif; ?>
						</div>
						<div class="text-right">
							<h3 class="text-2xs font-bold text-slate-800 line-clamp-1 group-hover:text-rose-600 transition mb-1">
								<?php the_title(); ?>
							</h3>
							<div class="text-2xs font-black text-rose-600">
								<?php echo wp_kses_post( $price_html ); ?>
							</div>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

	</div>
</section>
