<?php
/**
 * Sticky Bottom Add to Cart Bar - 100% Matching Demo 43
 *
 * @package Negar_Shop
 * @version 2.5.0
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

<div class="kav-single-product-bottom-nav-price bg-white position-fixed bottom-0 end-0 w-100">
	<div class="container d-flex align-items-center gap-2 gap-sm-3">
		<div class="info-product d-flex align-items-center gap-2 gap-lg-4 text-truncate">
			<div class="image flex-fill flex-grow-0 flex-shrink-0 d-flex align-items-center justify-content-center">
				<img loading="lazy" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="57" height="70">
			</div>
			<div class="text-truncate">
				<div class="name letter-spacing-2 text-truncate mb-lg-2"><?php echo esc_html( $product->get_name() ); ?></div>
			</div>
		</div>
		<div class="kav-single-product-info-payment-price d-none d-lg-flex align-items-center justify-content-between gap-2 ms-auto me-lg-0 kav-style5-bottom-price">
			<div class="d-flex align-items-center kav-style5-price-wrap">
				<div class="kav-style5-price-stack fw-bold letter-spacing-4 kav-style5-price-stack--simple">
					<div class="price kav-style5-price-output">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center gap-2 kav-style5-bottom-actions ms-auto ms-lg-0">
			<?php if ( $product->is_in_stock() ) : ?>
				<button type="button" class="kav-single-product-info-payment-btn btn btn-danger-6 d-flex align-items-center justify-content-center letter-spacing-2 kav-style5-trigger-add-to-cart" onclick="const f=document.querySelector('form.cart'); if(f) { f.scrollIntoView({behavior:'smooth'}); const b=f.querySelector('.single_add_to_cart_button'); if(b) b.click(); }">
					<?php esc_html_e( 'افزودن به سبد خرید', 'negar-shop' ); ?>
				</button>
			<?php else : ?>
				<button type="button" class="btn btn-secondary disabled" disabled><?php esc_html_e( 'ناموجود', 'negar-shop' ); ?></button>
			<?php endif; ?>
		</div>
	</div>
</div>
