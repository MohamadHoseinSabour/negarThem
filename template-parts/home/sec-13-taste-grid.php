<?php
/**
 * Section 13: Multi-Column Category Taste Grid (HOME-13)
 * Reference: Demo 43 Section 12 (Top: 5679px, Height: ~930px)
 * Heading: "با محصولات ما بروز باشید - باکس سلیقه شما"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$col1_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$col2_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );

$col3_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'meta_key'       => 'total_sales',
	'orderby'        => 'meta_value_num',
) );
if ( ! $col3_products->have_posts() ) {
	$col3_products = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
	) );
}
?>

<section class="negar-sec-13-taste-grid py-10 bg-slate-50/50" aria-label="<?php esc_attr_e( 'باکس سلیقه شما', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Section Header -->
		<div class="text-center max-w-xl mx-auto mb-10">
			<span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-full inline-block mb-2">
				<?php esc_html_e( 'با محصولات ما به‌روز باشید', 'negar-shop' ); ?>
			</span>
			<h2 class="text-2xl md:text-3xl font-black text-[#212341] flex items-center justify-center gap-2">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
				<span><?php esc_html_e( 'باکس سلیقه شما', 'negar-shop' ); ?></span>
			</h2>
			<p class="text-xs text-slate-500 mt-1">
				<?php esc_html_e( 'گلچینی از جدیدترین، پرفروش‌ترین و محبوب‌ترین گجت‌های هوشمند', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- 3-Column Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
			
			<!-- Column 1: New Releases -->
			<div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between">
				<div>
					<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
						<div>
							<h3 class="text-base font-black text-[#212341]"><?php esc_html_e( 'کالای دیجیتال', 'negar-shop' ); ?></h3>
							<span class="text-3xs font-medium text-slate-400"><?php esc_html_e( 'محصولات پیشنهادی از دسته', 'negar-shop' ); ?></span>
						</div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition shadow-xs">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
					<div class="divide-y divide-slate-100">
						<?php
						if ( $col1_products->have_posts() ) :
							while ( $col1_products->have_posts() ) :
								$col1_products->the_post();
								$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
								?>
								<a href="<?php the_permalink(); ?>" class="flex items-center gap-3.5 py-4 px-1 rounded-2xl hover:bg-slate-50/80 transition group">
									<div class="w-20 h-20 shrink-0 rounded-2xl bg-slate-50 p-2 flex items-center justify-center overflow-hidden border border-slate-100">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain group-hover:scale-105 transition' ) ); ?>
										<?php endif; ?>
									</div>
									<div class="flex-1 min-w-0">
										<h4 class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition truncate mb-1.5"><?php the_title(); ?></h4>
										<div class="flex items-center justify-between">
											<span class="text-3xs text-amber-500 font-bold flex items-center gap-0.5">★ ۴.۵</span>
											<div class="text-xs font-black text-rose-600"><?php echo wp_kses_post( $price_html ); ?></div>
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
			</div>

			<!-- Column 2: Editor's Pick -->
			<div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between">
				<div>
					<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
						<div>
							<h3 class="text-base font-black text-[#212341]"><?php esc_html_e( 'لوازم جانبی و هوشمند', 'negar-shop' ); ?></h3>
							<span class="text-3xs font-medium text-slate-400"><?php esc_html_e( 'محصولات پیشنهادی از دسته', 'negar-shop' ); ?></span>
						</div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition shadow-xs">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
					<div class="divide-y divide-slate-100">
						<?php
						if ( $col2_products->have_posts() ) :
							while ( $col2_products->have_posts() ) :
								$col2_products->the_post();
								$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
								?>
								<a href="<?php the_permalink(); ?>" class="flex items-center gap-3.5 py-4 px-1 rounded-2xl hover:bg-slate-50/80 transition group">
									<div class="w-20 h-20 shrink-0 rounded-2xl bg-slate-50 p-2 flex items-center justify-center overflow-hidden border border-slate-100">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain group-hover:scale-105 transition' ) ); ?>
										<?php endif; ?>
									</div>
									<div class="flex-1 min-w-0">
										<h4 class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition truncate mb-1.5"><?php the_title(); ?></h4>
										<div class="flex items-center justify-between">
											<span class="text-3xs text-amber-500 font-bold flex items-center gap-0.5">★ ۴.۸</span>
											<div class="text-xs font-black text-rose-600"><?php echo wp_kses_post( $price_html ); ?></div>
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
			</div>

			<!-- Column 3: Best Sellers -->
			<div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between">
				<div>
					<div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
						<div>
							<h3 class="text-base font-black text-[#212341]"><?php esc_html_e( 'کنسول و گیمینگ', 'negar-shop' ); ?></h3>
							<span class="text-3xs font-medium text-slate-400"><?php esc_html_e( 'محصولات پیشنهادی از دسته', 'negar-shop' ); ?></span>
						</div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center transition shadow-xs">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
					<div class="divide-y divide-slate-100">
						<?php
						if ( $col3_products->have_posts() ) :
							while ( $col3_products->have_posts() ) :
								$col3_products->the_post();
								$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
								?>
								<a href="<?php the_permalink(); ?>" class="flex items-center gap-3.5 py-4 px-1 rounded-2xl hover:bg-slate-50/80 transition group">
									<div class="w-20 h-20 shrink-0 rounded-2xl bg-slate-50 p-2 flex items-center justify-center overflow-hidden border border-slate-100">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain group-hover:scale-105 transition' ) ); ?>
										<?php endif; ?>
									</div>
									<div class="flex-1 min-w-0">
										<h4 class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition truncate mb-1.5"><?php the_title(); ?></h4>
										<div class="flex items-center justify-between">
											<span class="text-3xs text-amber-500 font-bold flex items-center gap-0.5">★ ۴.۹</span>
											<div class="text-xs font-black text-rose-600"><?php echo wp_kses_post( $price_html ); ?></div>
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
			</div>

		</div>

	</div>
</section>
