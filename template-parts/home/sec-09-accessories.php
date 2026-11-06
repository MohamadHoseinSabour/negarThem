<?php
/**
 * Section 09: Accessories Multi-Column Grid (HOME-09)
 * Reference: Demo 43 Section 8 (Top: 3851px, Height: ~460px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$side_img   = $upload_dir['baseurl'] . '/kaveh-demo/image-01.png';

$products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-09-accessories py-6" aria-label="<?php esc_attr_e( 'خرید لوازم جانبی و بازی', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="bg-slate-50/70 border border-slate-200/60 rounded-3xl p-5 md:p-6">
			
			<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-200/80">
				<div>
					<h2 class="text-xl md:text-2xl font-black text-[#212341]">
						<?php esc_html_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>
					</h2>
					<p class="text-xs text-slate-500 font-medium mt-1">
						<?php esc_html_e( 'انواع لوازم جانبی و بازی‌های پرطرفدار کنسول', 'negar-shop' ); ?>
					</p>
				</div>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
					<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">
				<!-- Lead Banner Card (3 Cols) -->
				<div class="lg:col-span-3 flex justify-center">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="block rounded-2xl overflow-hidden hover:scale-105 transition-transform duration-300">
						<img src="<?php echo esc_url( $side_img ); ?>" alt="<?php esc_attr_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>" class="w-full h-auto max-h-[340px] object-contain">
					</a>
				</div>

				<!-- Products Grid (9 Cols) -->
				<div class="lg:col-span-9 grid grid-cols-2 sm:grid-cols-4 gap-4">
					<?php
					if ( $products->have_posts() ) :
						while ( $products->have_posts() ) :
							$products->the_post();
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

		</div>

	</div>
</section>
