<?php
/**
 * Product Tabs Carousel Component (HOME-05)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$newest_products    = array();
$bestseller_products = array();
$onsale_products    = array();

if ( class_exists( 'WooCommerce' ) ) {
	// 1. Newest
	$newest_products = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	// 2. Best Sellers
	$bestseller_products = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'meta_key'       => 'total_sales',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		)
	);

	// 3. On Sale
	$sale_ids = wc_get_product_ids_on_sale();
	$onsale_products = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'post__in'       => ! empty( $sale_ids ) ? $sale_ids : array( 0 ),
		)
	);
}
?>

<section class="negar-product-tabs-section py-6" aria-label="<?php esc_attr_e( 'محصولات منتخب فروشگاه', 'negar-shop' ); ?>">
	<div class="container">
		
		<!-- Section Header with Tabs Navigation -->
		<div class="tabs-header flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 pb-3 border-b border-slate-200">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
				<h2 class="text-base md:text-lg font-bold text-slate-800 m-0">
					<?php esc_html_e( 'منتخب محصولات نگار شاپ', 'negar-shop' ); ?>
				</h2>
			</div>

			<!-- Tab Buttons -->
			<div class="product-tab-buttons flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
				<button type="button" class="tab-trigger active text-xs font-bold px-4 py-2 rounded-lg transition" data-target="#tab-panel-newest">
					<?php esc_html_e( 'جدیدترین‌ها', 'negar-shop' ); ?>
				</button>
				<button type="button" class="tab-trigger text-xs font-bold px-4 py-2 rounded-lg transition" data-target="#tab-panel-bestseller">
					<?php esc_html_e( 'پرفروش‌ترین‌ها', 'negar-shop' ); ?>
				</button>
				<button type="button" class="tab-trigger text-xs font-bold px-4 py-2 rounded-lg transition" data-target="#tab-panel-onsale">
					<?php esc_html_e( 'تخفیف‌دارها', 'negar-shop' ); ?>
				</button>
			</div>
		</div>

		<!-- Tab Panels Container -->
		<div class="product-tab-panels">

			<!-- Panel 1: Newest -->
			<div id="tab-panel-newest" class="product-tab-panel active">
				<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
					<?php
					if ( ! empty( $newest_products ) && $newest_products->have_posts() ) :
						while ( $newest_products->have_posts() ) :
							$newest_products->the_post();
							get_template_part( 'template-parts/product/card' );
						endwhile;
						wp_reset_postdata();
					else :
						for ( $i = 1; $i <= 4; $i++ ) :
							?>
							<div class="sample-card bg-white p-4 rounded-2xl border border-slate-100 text-center">
								<div class="w-full aspect-square bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
									<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line></svg>
								</div>
								<h4 class="text-xs font-bold text-slate-800 mb-1">کالای جدید <?php echo esc_html( $i ); ?></h4>
								<span class="text-xs font-bold text-emerald-600">۹۵۰,۰۰۰ تومان</span>
							</div>
							<?php
						endfor;
					endif;
					?>
				</div>
			</div>

			<!-- Panel 2: Best Sellers -->
			<div id="tab-panel-bestseller" class="product-tab-panel hidden">
				<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
					<?php
					if ( ! empty( $bestseller_products ) && $bestseller_products->have_posts() ) :
						while ( $bestseller_products->have_posts() ) :
							$bestseller_products->the_post();
							get_template_part( 'template-parts/product/card' );
						endwhile;
						wp_reset_postdata();
					else :
						for ( $i = 1; $i <= 4; $i++ ) :
							?>
							<div class="sample-card bg-white p-4 rounded-2xl border border-slate-100 text-center">
								<div class="w-full aspect-square bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
									<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								</div>
								<h4 class="text-xs font-bold text-slate-800 mb-1">کالای پرفروش <?php echo esc_html( $i ); ?></h4>
								<span class="text-xs font-bold text-emerald-600">۱,۳۵۰,۰۰۰ تومان</span>
							</div>
							<?php
						endfor;
					endif;
					?>
				</div>
			</div>

			<!-- Panel 3: On Sale -->
			<div id="tab-panel-onsale" class="product-tab-panel hidden">
				<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
					<?php
					if ( ! empty( $onsale_products ) && $onsale_products->have_posts() ) :
						while ( $onsale_products->have_posts() ) :
							$onsale_products->the_post();
							get_template_part( 'template-parts/product/card' );
						endwhile;
						wp_reset_postdata();
					else :
						for ( $i = 1; $i <= 4; $i++ ) :
							?>
							<div class="sample-card bg-white p-4 rounded-2xl border border-slate-100 text-center">
								<div class="w-full aspect-square bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
									<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
								</div>
								<h4 class="text-xs font-bold text-slate-800 mb-1">کالای تخفیف‌دار <?php echo esc_html( $i ); ?></h4>
								<span class="text-xs font-bold text-rose-600">۷۹۰,۰۰۰ تومان</span>
							</div>
							<?php
						endfor;
					endif;
					?>
				</div>
			</div>

		</div>

	</div>
</section>
