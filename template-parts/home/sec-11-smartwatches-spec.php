<?php
/**
 * Section 11: Smartwatch & Audio Showcase with Technical Specs (HOME-11)
 * Reference: Demo 43 Section 10 (Top: 4685px, Height: ~598px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query_args = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$spec_products = new WP_Query( $query_args );
?>

<section class="negar-sec-11-showcase py-6" aria-label="<?php esc_attr_e( 'ساعت و گجت‌های هوشمند برتر', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
			<div class="flex items-baseline gap-3">
				<h2 class="text-xl md:text-2xl font-black text-[#212341]">
					<?php esc_html_e( 'پرفروش ترین ساعت هوشمند و کالای دیجیتال', 'negar-shop' ); ?>
				</h2>
				<span class="text-xs text-slate-400 font-medium hidden sm:inline">
					<?php esc_html_e( 'ضمانت اصالت و سلامت فیزیکی کالا', 'negar-shop' ); ?>
				</span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
			<?php
			if ( $spec_products->have_posts() ) :
				while ( $spec_products->have_posts() ) :
					$spec_products->the_post();
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
