<?php
/**
 * Sticky Bottom Add to Cart Bar - 100% Matching Demo 43
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$post_thumbnail_id = $product->get_image_id();
$thumb_url         = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'thumbnail' ) : wc_placeholder_img_src( 'woocommerce_single' );
?>

<div class="kav-single-product-bottom-nav-price bg-white position-fixed bottom-0 end-0 w-100 shadow-lg border-t border-slate-200 z-40 py-2.5 transition-all">
	<div class="container d-flex align-items-center justify-between gap-2 gap-sm-3">
		
		<!-- Thumbnail & Title -->
		<div class="info-product d-flex align-items-center gap-2 gap-lg-3 text-truncate">
			<div class="image flex-fill flex-grow-0 flex-shrink-0 d-flex align-items-center justify-content-center w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden p-1">
				<img loading="lazy" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="44" height="44" class="w-100 h-100 object-contain">
			</div>
			<div class="text-truncate">
				<div class="name letter-spacing-2 text-truncate text-xs sm:text-sm font-bold text-slate-800">
					<?php echo esc_html( $product->get_name() ); ?>
				</div>
			</div>
		</div>

		<!-- Price Output with Toman Symbol -->
		<div class="kav-single-product-info-payment-price d-flex align-items-center gap-2 ms-auto me-lg-4 kav-style5-bottom-price">
			<div class="d-flex align-items-center kav-style5-price-wrap">
				<div class="kav-style5-price-stack fw-bold letter-spacing-4 kav-style5-price-stack--simple d-flex align-items-center gap-1.5">
					<div class="price kav-style5-price-output text-rose-600 font-black text-sm sm:text-base">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Add To Cart Trigger Button -->
		<div class="d-flex align-items-center gap-2 kav-style5-bottom-actions shrink-0">
			<?php if ( $product->is_in_stock() ) : ?>
				<button type="button" class="kav-single-product-info-payment-btn btn btn-danger-6 d-flex align-items-center justify-content-center letter-spacing-2 kav-style5-trigger-add-to-cart bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full transition-all shadow-md shadow-rose-600/20" onclick="const f = document.querySelector('form.cart'); if(f) f.scrollIntoView({behavior:'smooth'}); const b = document.querySelector('form.cart .single_add_to_cart_button'); if(b) b.click();">
					<?php esc_html_e( 'افزودن به سبد خرید', 'negar-shop' ); ?>
				</button>
			<?php else : ?>
				<button type="button" class="btn btn-secondary text-xs sm:text-sm px-4 py-2 rounded-full cursor-not-allowed bg-slate-200 text-slate-500 font-bold" disabled>
					<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
				</button>
			<?php endif; ?>
		</div>

	</div>
</div>
