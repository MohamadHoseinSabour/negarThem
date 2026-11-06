<?php
/**
 * High-Fidelity Product Card Component - Matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 2.0.0
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
?>

<div class="negar-product-card group relative bg-white rounded-2xl border border-slate-100 p-3 hover:border-rose-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full" data-product-id="<?php echo esc_attr( $product_id ); ?>">

	<!-- Card Top: Discount Badge & Floating Actions -->
	<div class="card-badges-wrap relative flex items-center justify-between mb-2 z-10">
		<div>
			<?php if ( ! $in_stock ) : ?>
				<span class="badge bg-slate-500 text-white text-3xs font-bold px-2 py-0.5 rounded-md">
					<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
				</span>
			<?php elseif ( $is_on_sale && $discount_pct > 0 ) : ?>
				<span class="badge bg-rose-600 text-white text-3xs font-black px-2 py-0.5 rounded-md shadow-xs">
					<?php echo esc_html( $discount_pct . '٪' ); ?>
				</span>
			<?php else : ?>
				<span class="text-3xs text-transparent select-none">-</span>
			<?php endif; ?>
		</div>

		<!-- Action Icons overlay (shows or highlights on hover) -->
		<div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
			<!-- Compare Button -->
			<button type="button" class="btn-compare-toggle w-6 h-6 rounded-full bg-slate-50 hover:bg-sky-50 text-slate-400 hover:text-sky-600 flex items-center justify-center transition border border-slate-100 shadow-2xs" data-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'مقایسه', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'مقایسه', 'negar-shop' ); ?>">
				<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/>
				</svg>
			</button>

			<!-- Wishlist Button -->
			<button type="button" class="btn-wishlist-toggle w-6 h-6 rounded-full bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition border border-slate-100 shadow-2xs" data-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'علاقه‌مندی', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'علاقه‌مندی', 'negar-shop' ); ?>">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
				</svg>
			</button>
		</div>
	</div>

	<!-- Product Image: Object-Contain Clean Presentation -->
	<div class="card-image-wrap relative aspect-square rounded-xl bg-slate-50/70 p-2.5 flex items-center justify-center overflow-hidden mb-2.5 group">
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

		<!-- Quick View Button overlay -->
		<div class="quick-view-overlay absolute bottom-2 inset-x-2 flex justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 transform translate-y-1 group-hover:translate-y-0">
			<button type="button" class="btn-quickview bg-white/95 backdrop-blur-xs text-slate-800 hover:text-rose-600 px-3 py-1 rounded-lg text-3xs font-bold shadow-md flex items-center gap-1 border border-slate-200/80 transition" data-id="<?php echo esc_attr( $product_id ); ?>">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
					<circle cx="12" cy="12" r="3"></circle>
				</svg>
				<span><?php esc_html_e( 'مشاهده سریع', 'negar-shop' ); ?></span>
			</button>
		</div>
	</div>

	<!-- Title & Category -->
	<div class="card-content mb-2 flex-1 flex flex-col justify-start text-right">
		<?php
		$terms = get_the_terms( $product_id, 'product_cat' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
			?>
			<a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>" class="text-3xs font-medium text-slate-400 hover:text-rose-600 transition block mb-1 truncate">
				<?php echo esc_html( $terms[0]->name ); ?>
			</a>
		<?php endif; ?>

		<h3 class="product-title text-xs font-bold text-slate-800 leading-snug line-clamp-2 m-0 group-hover:text-rose-600 transition min-h-[34px]">
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="hover:text-rose-600 transition">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h3>

		<!-- Star Rating / Spec tag -->
		<div class="flex items-center gap-1 mt-1.5 text-3xs text-amber-500 font-bold">
			<span>★</span>
			<span class="text-slate-400 font-normal">۴.۸</span>
		</div>
	</div>

	<!-- Card Footer: 2-Tier Price & Add to Cart Loop -->
	<div class="card-footer pt-2 border-t border-slate-100 flex items-end justify-between gap-1 mt-auto">
		<!-- 2-Tier Price Block -->
		<div class="price-wrap text-right flex flex-col justify-end">
			<?php if ( $is_on_sale && $regular_p ) : ?>
				<span class="text-3xs text-slate-400 line-through leading-none block mb-1">
					<?php echo esc_html( number_format_i18n( (float) $regular_p ) ); ?>
				</span>
			<?php endif; ?>
			<div class="text-xs md:text-sm font-black text-rose-600 leading-tight">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</div>
		</div>

		<!-- Add to Cart Loop Button -->
		<div class="add-to-cart-wrap shrink-0">
			<?php
			if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
				woocommerce_template_loop_add_to_cart();
			}
			?>
		</div>
	</div>

</div>
