<?php
/**
 * Section 26: Multi-Category Products Grid #3 (HOME-26)
 * Reference: Demo 43 Section 25 (Top: 12547px, Height: ~554px)
 * Heading: "بر اساس مخاطبان پربازدید محصولات"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$visited3_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-26-visited-3 py-6" aria-label="<?php esc_attr_e( 'پربازدیدترین محصولات منتخب', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
			<div class="flex items-baseline gap-3">
				<h2 class="text-xl md:text-2xl font-black text-[#212341]">
					<?php esc_html_e( 'پربازدید ترین محصولات', 'negar-shop' ); ?>
				</h2>
				<span class="text-xs text-slate-400 font-medium hidden sm:inline">
					<?php esc_html_e( 'کنسول‌ها، لپ‌تاپ‌ها، موبایل و لوازم جانبی', 'negar-shop' ); ?>
				</span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
			<?php
			if ( $visited3_products->have_posts() ) :
				while ( $visited3_products->have_posts() ) :
					$visited3_products->the_post();
					?>
					<div class="product-grid-item">
						<?php get_template_part( 'template-parts/product/card' ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

	</div>
</section>
