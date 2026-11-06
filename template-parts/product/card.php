<?php
/**
 * Product Card Component - Exact Match to Kaveh Demo 43 (product-nk)
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( empty( $product ) || ! is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$product_id   = $product->get_id();
$is_on_sale   = $product->is_on_sale();
$in_stock     = $product->is_in_stock();
$regular_p    = $product->get_regular_price();
$sale_p       = $product->get_sale_price();
$discount_pct = function_exists( 'negar_shop_product_discount_percentage' ) ? negar_shop_product_discount_percentage( $product ) : 0;
if ( ! $discount_pct && $is_on_sale && $regular_p && $sale_p ) {
	$discount_pct = round( ( ( (float) $regular_p - (float) $sale_p ) / (float) $regular_p ) * 100 );
}

$rating = $product->get_average_rating();
if ( empty( $rating ) || floatval( $rating ) <= 0 ) {
	$rating = '4.5';
} else {
	$rating = number_format( (float) $rating, 1 );
}
?>

<div class="product-nk product-one-nk group relative bg-white rounded-[26px] md:rounded-[30px] p-4 md:p-5 border border-slate-100 hover:border-rose-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full" data-product-id="<?php echo esc_attr( $product_id ); ?>">

	<!-- 1. Top Bar: Wishlist Heart & Attribute Badge -->
	<div class="like-size flex items-center justify-between gap-2 mb-2 z-10">
		<button type="button" class="btn-wishlist-toggle w-7 h-7 rounded-full bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition border border-slate-100 shadow-2xs" data-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'علاقه‌مندی', 'negar-shop' ); ?>">
			<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
			</svg>
		</button>

		<?php if ( ! $in_stock ) : ?>
			<span class="text-3xs font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
				<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
			</span>
		<?php elseif ( $is_on_sale && $discount_pct > 0 ) : ?>
			<span class="text-3xs font-black text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-full">
				<?php echo esc_html( $discount_pct . '٪ تخفیف' ); ?>
			</span>
		<?php else : ?>
			<span class="text-3xs font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full">
				<?php esc_html_e( 'اصلی', 'negar-shop' ); ?>
			</span>
		<?php endif; ?>
	</div>

	<!-- 2. Product Image: Clean Centered Contain -->
	<div class="image relative aspect-square rounded-2xl bg-slate-50/60 p-3 flex items-center justify-center overflow-hidden mb-3 group-hover:bg-slate-50/90 transition-colors">
		<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="block w-full h-full flex items-center justify-center">
			<?php
			if ( has_post_thumbnail( $product_id ) ) {
				echo get_the_post_thumbnail(
					$product_id,
					'medium',
					array( 'class' => 'max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105' )
				);
			} elseif ( function_exists( 'wc_placeholder_img' ) ) {
				echo wc_placeholder_img( 'medium', array( 'class' => 'max-h-full max-w-full object-contain' ) );
			}
			?>
		</a>
	</div>

	<!-- 3. Title (2-Line Clamp) -->
	<div class="mb-3">
		<h2 class="title text-xs md:text-sm font-bold text-[#212341] leading-snug line-clamp-2 m-0 group-hover:text-rose-600 transition min-h-[38px]">
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h2>
	</div>

	<!-- 4. Price & Rate Row (Exact Demo 43 Layout) -->
	<div class="price-rate flex items-center justify-between gap-1 mt-auto pt-2 border-t border-slate-100">
		<div class="price flex flex-col justify-end text-right">
			<?php if ( $is_on_sale && $regular_p ) : ?>
				<div class="flex items-center gap-1.5 leading-none mb-1">
					<del class="text-3xs text-slate-400 font-light line-through">
						<?php echo esc_html( number_format_i18n( (float) $regular_p ) ); ?>
					</del>
				</div>
			<?php endif; ?>
			<div class="text-xs md:text-sm font-black text-rose-600 flex items-center gap-1">
				<span><?php echo esc_html( number_format_i18n( (float) $product->get_price() ) ); ?></span>
				<span class="text-3xs font-medium text-slate-500">تومان</span>
			</div>
		</div>

		<div class="line flex-grow h-px bg-slate-100 mx-2 hidden sm:block"></div>

		<!-- Star Rating -->
		<div class="rate flex items-center gap-1 text-3xs font-bold text-slate-700 shrink-0">
			<span>5/<?php echo esc_html( $rating ); ?></span>
			<svg width="13" height="13" viewBox="0 0 24 24" fill="#FFC700" stroke="#FFC700" stroke-width="1.5">
				<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
			</svg>
		</div>
	</div>

</div>
