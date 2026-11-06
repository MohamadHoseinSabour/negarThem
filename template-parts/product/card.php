<?php
/**
 * Reusable Product Card Component
 *
 * @package Negar_Shop
 * @version 1.0.0
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
$discount_pct = function_exists( 'negar_shop_product_discount_percentage' ) ? negar_shop_product_discount_percentage( $product ) : 0;
?>

<div class="negar-product-card group relative bg-white rounded-2xl border border-slate-100 p-3 hover:shadow-card-hover transition duration-300 flex flex-col justify-between" data-product-id="<?php echo esc_attr( $product_id ); ?>">

	<!-- Card Top: Badges & Wishlist Trigger -->
	<div class="card-badges-wrap flex items-center justify-between gap-1 mb-2">
		<div class="flex items-center gap-1">
			<?php if ( ! $in_stock ) : ?>
				<span class="badge bg-slate-500 text-white text-3xs font-bold px-2 py-0.5 rounded-full">
					<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
				</span>
			<?php elseif ( $is_on_sale && $discount_pct > 0 ) : ?>
				<span class="badge badge-discount text-3xs font-bold px-2 py-0.5 rounded-full animate-pulse">
					<?php echo esc_html( $discount_pct . '٪ تخفیف' ); ?>
				</span>
			<?php endif; ?>
		</div>

		<div class="flex items-center gap-1">
			<!-- Compare Button -->
			<button type="button" class="btn-compare-toggle w-7 h-7 rounded-full bg-slate-50 hover:bg-sky-50 text-slate-400 hover:text-sky-500 flex items-center justify-center transition border border-slate-100" data-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'افزودن به مقایسه', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'افزودن به مقایسه', 'negar-shop' ); ?>">
				<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/>
				</svg>
			</button>

			<!-- Wishlist Button -->
			<button type="button" class="btn-wishlist-toggle w-7 h-7 rounded-full bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-500 flex items-center justify-center transition border border-slate-100" data-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'افزودن به علاقه‌مندی‌ها', 'negar-shop' ); ?>">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
				</svg>
			</button>
		</div>
	</div>

	<!-- Product Image & Quick Action Overlay -->
	<div class="card-image-wrap relative aspect-square rounded-xl overflow-hidden bg-slate-50 mb-3 flex items-center justify-center">
		<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="block w-full h-full">
			<?php
			if ( has_post_thumbnail( $product_id ) ) {
				echo get_the_post_thumbnail(
					$product_id,
					'negar-product-card',
					array( 'class' => 'w-full h-full object-cover transition duration-300 group-hover:scale-105' )
				);
			} elseif ( function_exists( 'wc_placeholder_img' ) ) {
				echo wc_placeholder_img( 'negar-product-card', array( 'class' => 'w-full h-full object-cover' ) );
			}
			?>
		</a>

		<!-- Quick View Floating Button -->
		<div class="quick-view-overlay absolute bottom-2 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 transition duration-200">
			<button type="button" class="btn-quickview bg-white/95 backdrop-blur-xs text-slate-800 hover:text-rose-600 px-3 py-1.5 rounded-lg text-2xs font-bold shadow-md flex items-center gap-1.5 border border-slate-200 transition" data-id="<?php echo esc_attr( $product_id ); ?>">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
					<circle cx="12" cy="12" r="3"></circle>
				</svg>
				<span><?php esc_html_e( 'مشاهده سریع', 'negar-shop' ); ?></span>
			</button>
		</div>
	</div>

	<!-- Product Content: Category & Title -->
	<div class="card-content mb-3">
		<?php
		$terms = get_the_terms( $product_id, 'product_cat' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
			?>
			<a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>" class="text-3xs font-medium text-slate-400 hover:text-rose-600 transition block mb-1">
				<?php echo esc_html( $terms[0]->name ); ?>
			</a>
		<?php endif; ?>

		<h3 class="product-title text-xs font-bold text-slate-800 line-clamp-2 leading-snug m-0">
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="hover:text-rose-600 transition">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h3>
	</div>

	<!-- Card Footer: Price & Add to Cart -->
	<div class="card-footer pt-2 border-t border-slate-50 flex items-center justify-between gap-2">
		<!-- Price -->
		<div class="price-wrap text-right">
			<div class="text-xs font-black text-rose-600 leading-tight">
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
