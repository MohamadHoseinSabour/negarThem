<?php
/**
 * Single Product Gallery Component - Matching Kaveh Demo 43 (Style 5)
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
$attachment_ids    = $product->get_gallery_image_ids();

$all_images = array();
if ( $post_thumbnail_id ) {
	$all_images[] = $post_thumbnail_id;
}
if ( ! empty( $attachment_ids ) ) {
	$all_images = array_unique( array_merge( $all_images, $attachment_ids ) );
}

$main_img_url  = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'full' ) : wc_placeholder_img_src( 'woocommerce_single' );
$main_thumb    = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'woocommerce_thumbnail' ) : $main_img_url;
$product_id    = $product->get_id();
$is_on_sale    = $product->is_on_sale();
$discount_pct  = 0;
if ( $is_on_sale && $product->get_regular_price() && $product->get_sale_price() ) {
	$reg  = (float) $product->get_regular_price();
	$sale = (float) $product->get_sale_price();
	if ( $reg > 0 ) {
		$discount_pct = round( ( ( $reg - $sale ) / $reg ) * 100 );
	}
}
?>

<div class="kav-single-product-image position-relative">
	<!-- Vertical Floating Action Icons (Desktop) -->
	<ul class="kav-single-product-image-icons d-none d-lg-flex flex-column gap-3 position-absolute">
		<li>
			<button type="button" class="wlm-button p-0 border-0 bg-transparent lh-1" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'علاقه‌مندی', 'negar-shop' ); ?>">
				<i class="icon-heart-7 lh-1" aria-hidden="true"></i>
			</button>
		</li>
		<li data-bs-toggle="modal" data-bs-target="#modal-kav-share" title="<?php esc_attr_e( 'اشتراک‌گذاری', 'negar-shop' ); ?>">
			<i class="icon-share-4 lh-1"></i>
		</li>
		<li data-bs-toggle="modal" data-bs-target="#modal-kav-gallery" title="<?php esc_attr_e( 'گالری تصاویر', 'negar-shop' ); ?>">
			<i class="icon-image-download lh-1"></i>
		</li>
		<li>
			<button type="button" class="compare-button kav-style5-icon-btn p-0 border-0 bg-transparent lh-1 add-to-compare" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'افزودن به مقایسه', 'negar-shop' ); ?>">
				<i class="icon-comparison lh-1" aria-hidden="true"></i>
			</button>
		</li>
	</ul>

	<!-- Large Main Image with Magnify Zoom -->
	<div class="woocommerce-product-gallery images" style="opacity: 1; transition: opacity 0.25s ease-in-out;">
		<div class="kav-single-product-image-large position-relative overflow-hidden mb-3">
			<div class="bg position-absolute top-0 start-0"></div>
			
			<?php if ( $discount_pct > 0 ) : ?>
				<div class="discount kav-style5-discount-badge kav-style5-gallery-discount position-absolute text-white top-0 start-0 rounded-pill d-flex align-items-center justify-content-center fw-bold letter-spacing-4">
					<?php echo esc_html( $discount_pct ); ?>%
				</div>
			<?php endif; ?>

			<div class="image woocommerce-product-gallery__wrapper">
				<div class="woocommerce-product-gallery__image">
					<img
						src="<?php echo esc_url( $main_img_url ); ?>"
						data-magnify-src="<?php echo esc_url( $main_img_url ); ?>"
						alt="<?php echo esc_attr( $product->get_name() ); ?>"
						width="500"
						height="500"
						class="d-block mx-auto wp-post-image kav-style5-main-image zoom"
						style="display: block; max-width: 100%; height: auto;"
					>
				</div>
			</div>
		</div>

		<!-- Thumbnails Carousel / Strip -->
		<?php if ( ! empty( $all_images ) && count( $all_images ) > 1 ) : ?>
			<div class="kav-single-product-image-images d-flex justify-content-start gap-2 flex-wrap">
				<?php foreach ( $all_images as $index => $img_id ) :
					$thumb_url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
					$full_url  = wp_get_attachment_image_url( $img_id, 'full' );
				?>
					<div
						class="d-flex align-items-center justify-content-center overflow-hidden kav-style5-thumb <?php echo ( 0 === $index ) ? 'active' : ''; ?>"
						data-full-src="<?php echo esc_url( $full_url ); ?>"
						role="button"
						tabindex="0"
						style="width: 64px; height: 64px; border-radius: 12px; border: 1px solid #e2e8f0; cursor: pointer; padding: 4px;"
					>
						<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" width="56" height="56" class="w-100 h-100 object-contain">
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<!-- Guarantee & Notice Notes Below Gallery -->
	<div class="d-none d-lg-block mt-4">
		<div class="kav-single-product-note-alert d-flex align-items-center fw-normal letter-spacing-2 mb-2 p-2.5 rounded-xl bg-slate-50 text-slate-600 text-xs gap-2">
			<i class="icon-shield-check text-rose-600 text-sm"></i>
			<span><?php esc_html_e( 'ضمانت اصالت و سلامت فیزیکی کالا با مهلت تست ۷ روزه', 'negar-shop' ); ?></span>
		</div>
		<div class="kav-single-product-note-ques d-flex align-items-center fw-normal letter-spacing-2 p-2.5 rounded-xl bg-slate-50 text-slate-600 text-xs gap-2">
			<i class="icon-info-circle text-blue-600 text-sm"></i>
			<span><?php esc_html_e( 'امکان برگشت کالا با شرایط انصراف از خرید در صورت باز نشدن پلمب', 'negar-shop' ); ?></span>
		</div>
	</div>
</div>
