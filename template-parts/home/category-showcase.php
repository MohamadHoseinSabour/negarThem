<?php
/**
 * Category Showcase Component (Vertical Banner + Products) (HOME-07)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$showcase_products = array();
if ( class_exists( 'WooCommerce' ) ) {
	$showcase_products = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'orderby'        => 'rand',
		)
	);
}
?>

<section class="negar-category-showcase-section py-6" aria-label="<?php esc_attr_e( 'ویترین دسته‌بندی منتخب', 'negar-shop' ); ?>">
	<div class="container">
		
		<div class="showcase-box rounded-3xl bg-slate-50 border border-slate-200/80 p-5 md:p-6 shadow-sm">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

				<!-- 1. Vertical Campaign Banner (3 Cols on Desktop) -->
				<div class="lg:col-span-3 rounded-2xl bg-gradient-to-br from-emerald-800 via-teal-800 to-slate-900 p-6 text-white flex flex-col justify-between shadow-md relative overflow-hidden">
					<div class="relative z-10">
						<span class="badge bg-emerald-500 text-white text-3xs font-bold px-2.5 py-1 rounded-full inline-block mb-3">
							<?php esc_html_e( 'دسته برگزیده ماه', 'negar-shop' ); ?>
						</span>
						<h3 class="text-xl md:text-2xl font-black text-white m-0 leading-tight mb-2">
							<?php esc_html_e( 'دنیای صوت و هدفون‌های پیشرفته', 'negar-shop' ); ?>
						</h3>
						<p class="text-xs text-teal-100 m-0 leading-relaxed">
							<?php esc_html_e( 'بهترین برندهای روز دنیا با گارانتی تعویض و ضمانت بهترین کیفیت پخش صدا.', 'negar-shop' ); ?>
						</p>
					</div>

					<div class="relative z-10 pt-6">
						<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="btn bg-white hover:bg-emerald-50 text-emerald-800 text-xs font-bold py-2.5 px-4 rounded-xl shadow-md transition flex items-center justify-between w-full">
							<span><?php esc_html_e( 'مشاهده همه کالاها', 'negar-shop' ); ?></span>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
						</a>
					</div>
				</div>

				<!-- 2. Products Grid (9 Cols on Desktop) -->
				<div class="lg:col-span-9 flex items-center">
					<div class="w-full grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
						<?php
						if ( ! empty( $showcase_products ) && $showcase_products->have_posts() ) :
							while ( $showcase_products->have_posts() ) :
								$showcase_products->the_post();
								get_template_part( 'template-parts/product/card' );
							endwhile;
							wp_reset_postdata();
						else :
							for ( $i = 1; $i <= 4; $i++ ) :
								?>
								<div class="sample-card bg-white p-3 rounded-2xl border border-slate-100 text-center shadow-xs">
									<div class="w-full aspect-square bg-slate-100 rounded-xl mb-3 flex items-center justify-center text-slate-400">
										<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
									</div>
									<h4 class="text-xs font-bold text-slate-800 mb-1">هدفون بلوتوثی نمونه <?php echo esc_html( $i ); ?></h4>
									<span class="text-xs font-bold text-emerald-600">۱,۱۵۰,۰۰۰ تومان</span>
								</div>
								<?php
							endfor;
						endif;
						?>
					</div>
				</div>

			</div>
		</div>

	</div>
</section>
