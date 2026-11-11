<?php
/**
 * Section 16: Single Featured Deal Spotlight Bar.
 *
 * Product data is read from WooCommerce so the spotlight never advertises a
 * stale image, price, rating, discount, or destination.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

$deal_product = false;
if ( function_exists( 'wc_get_products' ) ) {
	$deal_products = wc_get_products(
		array(
			'status'   => 'publish',
			'limit'    => -1,
			'category' => array( 'wearables' ),
			'orderby'  => 'date',
			'order'    => 'DESC',
		)
	);
	foreach ( $deal_products as $candidate ) {
		if ( false !== stripos( $candidate->get_name(), 'watch' ) || false !== strpos( $candidate->get_name(), 'ساعت' ) ) {
			$deal_product = $candidate;
			break;
		}
	}
}

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<section class="negar-sec-16-spotlight py-4" aria-label="<?php esc_attr_e( 'پیشنهاد اختصاصی روز', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="bg-gradient-to-l from-slate-100 via-rose-50/40 to-white border border-slate-200/80 rounded-3xl p-4 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-xs">
			<?php if ( $deal_product ) : ?>
				<div class="flex items-center gap-4 min-w-0">
					<a href="<?php echo esc_url( get_permalink( $deal_product->get_id() ) ); ?>" class="w-20 h-20 shrink-0 bg-white rounded-2xl p-2 border border-slate-200/60 shadow-xs flex items-center justify-center" aria-label="<?php echo esc_attr( $deal_product->get_name() ); ?>">
						<?php echo wp_kses_post( $deal_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-contain' ) ) ); ?>
					</a>
					<div class="min-w-0">
						<span class="text-3xs font-black text-rose-600 bg-rose-100/70 px-2 py-0.5 rounded-full inline-block mb-1">
							<?php esc_html_e( 'پیشنهاد طلایی روز', 'negar-shop' ); ?>
						</span>
						<h3 class="text-sm md:text-base font-black text-[#212341] line-clamp-2">
							<a href="<?php echo esc_url( get_permalink( $deal_product->get_id() ) ); ?>">
								<?php echo esc_html( $deal_product->get_name() ); ?>
							</a>
						</h3>
						<div class="flex flex-wrap items-center gap-3 mt-1">
							<?php if ( $deal_product->get_price_html() ) : ?>
								<span class="text-xs font-black text-rose-600"><?php echo wp_kses_post( $deal_product->get_price_html() ); ?></span>
							<?php endif; ?>
							<?php if ( $deal_product->get_average_rating() > 0 ) : ?>
								<span class="text-3xs font-bold text-amber-500 flex items-center gap-1" aria-label="<?php esc_attr_e( 'امتیاز محصول', 'negar-shop' ); ?>">
									<span aria-hidden="true">★</span><?php echo esc_html( number_format_i18n( (float) $deal_product->get_average_rating(), 1 ) ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="flex items-center gap-3 w-full md:w-auto justify-end">
					<a href="<?php echo esc_url( get_permalink( $deal_product->get_id() ) ); ?>" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-6 py-3 rounded-full transition shadow-md">
						<span><?php esc_html_e( 'خرید فوری', 'negar-shop' ); ?></span>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</a>
				</div>
			<?php else : ?>
				<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-right">
					<p class="text-sm font-bold text-slate-600 m-0"><?php esc_html_e( 'در حال حاضر محصول ویژه‌ای برای نمایش وجود ندارد.', 'negar-shop' ); ?></p>
					<a href="<?php echo esc_url( $shop_url ); ?>" class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-6 py-3 rounded-full transition shadow-md">
						<?php esc_html_e( 'مشاهده فروشگاه', 'negar-shop' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
