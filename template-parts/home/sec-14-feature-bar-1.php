<?php
/**
 * Section 14: Horizontal Feature Bar #1 (HOME-14)
 * Reference: Demo 43 Section 13 (Top: 6659px, Height: ~323px)
 * Heading: "خرید برترین ساعتــــ / کنسول بازی سونی"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$feature_img = $upload_dir['baseurl'] . '/kaveh-demo/image-02.png';

$bar_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-14-feature-bar py-6" aria-label="<?php esc_attr_e( 'خرید برترین کنسول و ساعت', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-5 md:p-6 text-white overflow-hidden shadow-xl">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">
				
				<!-- Lead Banner Info (4 Cols) -->
				<div class="lg:col-span-4 flex flex-col justify-center">
					<span class="text-xs font-bold text-rose-400 tracking-wider mb-2">INCIDIDUNT UT LABORE ET DOLORE</span>
					<h3 class="text-xl md:text-2xl font-black text-white leading-snug mb-3">
						<?php esc_html_e( 'خرید برترین کنسول و ساعت‌های هوشمند', 'negar-shop' ); ?>
					</h3>
					<p class="text-xs text-slate-300 leading-relaxed mb-4">
						<?php esc_html_e( 'تجربه نسل جدید گیمینگ و اکسسوری‌های روز با گارانتی معتبر شرکتی.', 'negar-shop' ); ?>
					</p>
					<div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-5 py-2.5 rounded-full transition shadow-md">
							<span><?php esc_html_e( 'مشاهده کاتالوگ', 'negar-shop' ); ?></span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
				</div>

				<!-- Products Preview Cards (8 Cols) -->
				<div class="lg:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
					<?php
					if ( $bar_products->have_posts() ) :
						while ( $bar_products->have_posts() ) :
							$bar_products->the_post();
							$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
							?>
							<div class="bg-white rounded-2xl p-3 text-slate-800 shadow-sm flex flex-col justify-between group hover:shadow-lg transition">
								<a href="<?php the_permalink(); ?>" class="block aspect-square rounded-xl bg-slate-50 p-2 mb-2 flex items-center justify-center overflow-hidden">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain group-hover:scale-105 transition' ) ); ?>
									<?php endif; ?>
								</a>
								<div>
									<h4 class="text-2xs font-bold text-slate-800 line-clamp-2 mb-1 group-hover:text-rose-600 transition">
										<?php the_title(); ?>
									</h4>
									<div class="text-2xs font-black text-rose-600">
										<?php echo wp_kses_post( $price_html ); ?>
									</div>
								</div>
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
