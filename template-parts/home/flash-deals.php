<?php
/**
 * Daily Deals Component (HOME-04) - Exact Match to Kaveh Demo 43
 * Reference: Demo 43 Section 3 (Top: 1449px, Height: ~568px)
 * Heading: "پیشنهاد / تخفیفات روز"
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$deals_banner = $upload_dir['baseurl'] . '/kaveh-demo/popular-products.png';

$sale_products = array();
if ( class_exists( 'WooCommerce' ) ) {
	$product_ids_on_sale = wc_get_product_ids_on_sale();
	$query_args          = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'orderby'        => 'rand',
	);

	if ( ! empty( $product_ids_on_sale ) ) {
		$query_args['post__in'] = $product_ids_on_sale;
	}

	$sale_products = new WP_Query( $query_args );
	if ( ! $sale_products->have_posts() ) {
		$sale_products = new WP_Query( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
		) );
	}
}
?>

<section class="negar-popular-products-section py-6" aria-label="<?php esc_attr_e( 'پیشنهاد تخفیفات روز', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="popular-products-nk__wrapper">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
				
				<!-- Right Column in RTL: 3D Illustration & "پیشنهاد تخفیفات روز" (3 Cols) -->
				<div class="lg:col-span-3 flex flex-col items-center text-center">
					<div class="relative w-44 md:w-52 aspect-square mb-2 flex items-center justify-center">
						<img src="<?php echo esc_url( $deals_banner ); ?>" alt="<?php esc_attr_e( 'پیشنهاد روز', 'negar-shop' ); ?>" class="max-w-full max-h-full object-contain filter drop-shadow-2xl">
					</div>

					<div class="relative flex items-center justify-center gap-2 mb-1">
						<h2 class="text-2xl md:text-3xl font-black text-white m-0 tracking-tight">
							<?php esc_html_e( 'پیشنهاد', 'negar-shop' ); ?>
						</h2>
						<!-- 8-Pointed Star Badge -->
						<span class="w-8 h-8 rounded-xl bg-white/25 backdrop-blur-md flex items-center justify-center text-white shadow-xs">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 5.3 5.6.8-4 4.1 1 5.8-5-2.8-5 2.8 1-5.8-4-4.1 5.6-.8L12 2z"></path></svg>
						</span>
					</div>

					<span class="text-sm md:text-base font-bold text-rose-100/90 mb-4 block">
						<?php esc_html_e( 'تخفیفات روز', 'negar-shop' ); ?>
					</span>

					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-white">
						<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</a>
				</div>

				<!-- Left Column in RTL: 4-Item Product Cards Slider/Grid (9 Cols) -->
				<div class="lg:col-span-9 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
					<?php
					if ( ! empty( $sale_products ) && $sale_products->have_posts() ) :
						while ( $sale_products->have_posts() ) :
							$sale_products->the_post();
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
