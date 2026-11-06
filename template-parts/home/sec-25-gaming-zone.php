<?php
/**
 * Section 25: Gaming Zone Showcase (HOME-25)
 * Reference: Demo 43 Section 24 (Top: 11832px, Height: ~640px)
 * Heading: "محصول گیم - بازی Assassin’s Creed Shadows مخصوص PS5"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$game_banner = $upload_dir['baseurl'] . '/kaveh-demo/banner-03.png';

$game_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'rand',
) );
?>

<section class="negar-sec-25-gaming py-8" aria-label="<?php esc_attr_e( 'منطقه ویژه گیمینگ و کنسول', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 rounded-3xl p-6 md:p-8 text-white shadow-2xl overflow-hidden relative border border-slate-800">
			
			<div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-800">
				<div>
					<span class="text-xs font-bold text-rose-500 tracking-wider inline-block mb-1">
						<?php esc_html_e( 'GAMING ZONE', 'negar-shop' ); ?>
					</span>
					<h2 class="text-xl md:text-3xl font-black text-white">
						<?php esc_html_e( 'دنیای گیمینگ؛ بازی Assassin’s Creed Shadows و عناوین برتر', 'negar-shop' ); ?>
					</h2>
				</div>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-400 hover:text-rose-300 flex items-center gap-1 transition">
					<span><?php esc_html_e( 'مشاهده همه بازی‌ها', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
				
				<!-- Featured Big Game Card (5 Cols) -->
				<div class="lg:col-span-5 bg-slate-900/90 rounded-2xl p-5 border border-slate-700/60 shadow-lg flex flex-col justify-between">
					<div class="aspect-[16/10] rounded-xl overflow-hidden bg-slate-800 mb-4">
						<img src="<?php echo esc_url( $game_banner ); ?>" alt="<?php esc_attr_e( 'بازی Assassin’s Creed Shadows', 'negar-shop' ); ?>" class="w-full h-full object-cover">
					</div>
					<div>
						<div class="flex items-center gap-2 mb-2">
							<span class="bg-rose-600 text-white text-3xs font-bold px-2 py-0.5 rounded-full">PS5 Exclusive</span>
							<span class="text-amber-400 text-xs font-bold">★ ۴.۹</span>
						</div>
						<h3 class="text-base font-black text-white mb-2">
							<?php esc_html_e( 'بازی Assassin’s Creed Shadows نسخه استاندارد PS5', 'negar-shop' ); ?>
						</h3>
						<p class="text-xs text-slate-300 line-clamp-2 leading-relaxed mb-4">
							<?php esc_html_e( 'ورود به ژاپن فئودال با داستان حماسی نائوئه و یاسوکه در پیشرفته‌ترین جلوه‌های بصری نسل نهم.', 'negar-shop' ); ?>
						</p>
						<div class="flex items-center justify-between pt-3 border-t border-slate-800">
							<span class="text-sm font-black text-rose-400">۴,۲۹۰,۰۰۰ تومان</span>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
								<?php esc_html_e( 'خرید فوری', 'negar-shop' ); ?>
							</a>
						</div>
					</div>
				</div>

				<!-- Adjacent 4 Game Cards (7 Cols) -->
				<div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-2 gap-4">
					<?php
					if ( $game_products->have_posts() ) :
						while ( $game_products->have_posts() ) :
							$game_products->the_post();
							$price_html = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() )->get_price_html() : '';
							?>
							<div class="bg-white rounded-2xl p-4 text-slate-800 shadow-sm flex flex-col justify-between group hover:shadow-xl transition">
								<a href="<?php the_permalink(); ?>" class="block aspect-square rounded-xl bg-slate-50 p-2 mb-2 flex items-center justify-center overflow-hidden">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain group-hover:scale-105 transition' ) ); ?>
									<?php endif; ?>
								</a>
								<div>
									<h4 class="text-xs font-bold text-slate-800 line-clamp-2 mb-1 group-hover:text-rose-600 transition">
										<?php the_title(); ?>
									</h4>
									<div class="text-xs font-black text-rose-600">
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
