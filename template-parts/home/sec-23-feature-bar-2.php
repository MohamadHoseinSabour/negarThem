<?php
/**
 * Section 23: Horizontal Feature Bar #2 (HOME-23)
 * Reference: Demo 43 Section 22 (Top: 10828px, Height: ~323px)
 * Heading: "خرید برترین ساعتــــ INCIDIDUNT"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$bar2_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-23-feature-bar py-3 md:py-4" aria-label="<?php esc_attr_e( 'ساعت و گجت‌های برتر', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-3xl p-4 md:p-5 text-white overflow-hidden shadow-xl">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">
				
				<div class="lg:col-span-4 flex flex-col justify-center">
					<span class="text-xs font-bold text-indigo-400 tracking-wider mb-2">INCIDIDUNT UT LABORE ET DOLORE</span>
					<h3 class="text-xl md:text-2xl font-black text-white leading-snug mb-3">
						<?php esc_html_e( 'خرید برترین ساعت‌ها و اکسسوری دیجیتال', 'negar-shop' ); ?>
					</h3>
					<p class="text-xs text-slate-300 leading-relaxed mb-4">
						<?php esc_html_e( 'با بهترین متریال بند و حسگرهای پایش سلامت نسل جدید.', 'negar-shop' ); ?>
					</p>
					<div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-full transition shadow-md">
							<span><?php esc_html_e( 'مشاهده کلکسیون', 'negar-shop' ); ?></span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
				</div>

				<div class="lg:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
					<?php
					if ( $bar2_products->have_posts() ) :
						while ( $bar2_products->have_posts() ) :
							$bar2_products->the_post();
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
