<?php
/**
 * Section 21: Latest product reviews (HOME-21).
 *
 * This section intentionally renders approved WooCommerce reviews only. It must
 * never be populated with sample testimonials or fictional customer stories.
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$reviews = get_comments(
	array(
		'number'  => 3,
		'orderby' => 'comment_date_gmt',
		'order'   => 'DESC',
		'status'  => 'approve',
		'type'    => 'review',
	)
);
?>

<section class="negar-sec-21-testimonials py-10 bg-slate-50/70 border-y border-slate-200/60" aria-label="<?php esc_attr_e( 'تازه‌ترین دیدگاه‌های محصولات', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="text-center max-w-xl mx-auto mb-8">
			<span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-full inline-block mb-2">
				<?php esc_html_e( 'دیدگاه‌های ثبت‌شده', 'negar-shop' ); ?>
			</span>
			<h2 class="text-2xl md:text-3xl font-black text-[#212341]">
				<?php esc_html_e( 'تازه‌ترین دیدگاه‌های محصولات', 'negar-shop' ); ?>
			</h2>
			<p class="text-xs text-slate-500 mt-1">
				<?php esc_html_e( 'این بخش از دیدگاه‌های تأییدشده‌ی ووکامرس به‌روز می‌شود.', 'negar-shop' ); ?>
			</p>
		</div>

		<?php if ( ! empty( $reviews ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<?php foreach ( $reviews as $review ) : ?>
					<?php
					$product      = function_exists( 'wc_get_product' ) ? wc_get_product( $review->comment_post_ID ) : false;
					$rating       = max( 0, min( 5, (int) get_comment_meta( $review->comment_ID, 'rating', true ) ) );
					$author       = get_comment_author( $review );
					$review_text  = wp_trim_words( wp_strip_all_tags( $review->comment_content ), 32, '…' );
					$product_name = $product ? $product->get_name() : '';
					$product_url  = $product ? $product->get_permalink() : '';
					?>
					<article class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-lg transition-shadow duration-300 flex flex-col justify-between">
						<div>
							<?php if ( $rating > 0 ) : ?>
								<div class="flex items-center gap-1 text-amber-400 mb-4" aria-label="<?php echo esc_attr( sprintf( __( 'امتیاز %d از ۵', 'negar-shop' ), $rating ) ); ?>">
									<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
										<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
									<?php endfor; ?>
								</div>
							<?php endif; ?>

							<p class="text-xs text-slate-600 leading-relaxed italic mb-6">«<?php echo esc_html( $review_text ); ?>»</p>
						</div>

						<div class="flex items-center gap-3 pt-4 border-t border-slate-100">
							<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
								<?php echo wp_kses_post( get_avatar( $review, 96, '', $author, array( 'class' => 'w-full h-full object-cover' ) ) ); ?>
							</div>
							<div class="min-w-0">
								<h3 class="text-xs font-black text-[#212341]"><?php echo esc_html( $author ); ?></h3>
								<?php if ( $product_name && $product_url ) : ?>
									<a class="text-3xs text-slate-400 font-medium hover:text-rose-600 transition-colors line-clamp-1" href="<?php echo esc_url( $product_url ); ?>">
										<?php echo esc_html( $product_name ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="max-w-xl mx-auto rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
				<svg class="mx-auto mb-3 text-slate-400" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M21 15a4 4 0 0 1-4 4H7l-4 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path></svg>
				<p class="text-sm font-bold text-slate-700"><?php esc_html_e( 'هنوز دیدگاهی برای نمایش ثبت نشده است.', 'negar-shop' ); ?></p>
				<p class="mt-1 text-xs text-slate-500"><?php esc_html_e( 'پس از تأیید نخستین دیدگاه محصول، این بخش به‌صورت خودکار به‌روز می‌شود.', 'negar-shop' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
