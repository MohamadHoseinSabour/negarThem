<?php
/**
 * Single Product Gallery Component - 100% Matching Demo 43
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

$product_id        = $product->get_id();
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
	<ul class="kav-single-product-image-icons d-none d-lg-flex flex-column gap-3 position-absolute">
		<li>
			<button type="button" class="wlm-button p-0 border-0 bg-transparent lh-1" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'علاقه مندی', 'negar-shop' ); ?>" aria-pressed="false">
				<i class="icon-heart-7 lh-1" aria-hidden="true"></i>
			</button>
		</li>
		<li data-bs-toggle="modal" data-bs-target="#modal-kav-share" role="button" tabindex="0" title="<?php esc_attr_e( 'اشتراک گذاری', 'negar-shop' ); ?>">
			<i class="icon-share-4 lh-1"></i>
		</li>
		<li data-bs-toggle="modal" data-bs-target="#modal-kav-video" role="button" tabindex="0" title="<?php esc_attr_e( 'ویدیو محصول', 'negar-shop' ); ?>">
			<i class="icon-video-library lh-1"></i>
		</li>
		<li data-bs-toggle="modal" data-bs-target="#modal-kav-gallery" role="button" tabindex="0" title="<?php esc_attr_e( 'گالری تصویر', 'negar-shop' ); ?>">
			<i class="icon-image-download lh-1"></i>
		</li>
		<li>
			<button type="button" class="compare-button kav-style5-icon-btn p-0 border-0 bg-transparent lh-1 add-to-compare" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'برای مقایسه اضافه کنید', 'negar-shop' ); ?>">
				<i class="icon-comparison lh-1" aria-hidden="true"></i>
			</button>
		</li>
	</ul>

	<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1; transition: opacity .25s ease-in-out;">
		<div class="kav-single-product-image-large position-relative overflow-hidden mb-3">
			<div class="bg position-absolute top-0 start-0"></div>
			<?php if ( $discount_pct > 0 ) : ?>
				<div class="discount kav-style5-discount-badge kav-style5-gallery-discount position-absolute text-white top-0 start-0 rounded-pill d-flex align-items-center justify-content-center fw-bold letter-spacing-4" data-default-discount="<?php echo esc_attr( $discount_pct ); ?>">
					<?php echo esc_html( $discount_pct ); ?>%
				</div>
			<?php else : ?>
				<div class="discount kav-style5-discount-badge kav-style5-gallery-discount position-absolute text-white top-0 start-0 rounded-pill d-flex align-items-center justify-content-center fw-bold letter-spacing-4 d-none" data-default-discount="0"></div>
			<?php endif; ?>

			<div class="image woocommerce-product-gallery__wrapper">
				<div class="woocommerce-product-gallery__image">
					<img fetchpriority="high"
						src="<?php echo esc_url( $main_img_url ); ?>"
						alt="<?php echo esc_attr( $product->get_name() ); ?>"
						width="300"
						height="300"
						class="d-block mx-auto wp-post-image kav-style5-main-image"
						data-large_image="<?php echo esc_url( $main_img_url ); ?>"
						data-default-src="<?php echo esc_url( $main_img_url ); ?>"
					>
				</div>
			</div>
		</div>

		<?php if ( ! empty( $all_images ) ) : ?>
			<div class="kav-single-product-image-images d-flex justify-content-between gap-2">
				<div class="d-flex align-items-center justify-content-center overflow-hidden" data-bs-toggle="modal" data-bs-target="#modal-kav-gallery" role="button" tabindex="0" aria-label="<?php esc_attr_e( 'گالری', 'negar-shop' ); ?>">
					<i class="icon-more-horiz lh-1"></i>
				</div>
				<?php foreach ( $all_images as $idx => $img_id ) :
					$thumb_src = wp_get_attachment_image_url( $img_id, 'thumbnail' );
					$full_src  = wp_get_attachment_image_url( $img_id, 'full' );
				?>
					<div class="d-flex align-items-center justify-content-center overflow-hidden kav-style5-thumb" data-bs-toggle="modal" data-bs-target="#modal-kav-gallery" data-full-src="<?php echo esc_url( $full_src ); ?>" role="button" tabindex="0">
						<img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="64" height="64" class="w-100 h-100">
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="d-none d-lg-block">
		<div class="kav-single-product-note-alert d-flex align-items-center fw-normal letter-spacing-2">
			<i class="icon-alert-triangle"></i>
			<?php esc_html_e( 'این محصول به وسیله تولید کننده اصلی(برند) تولید شده و دارای ضمانت اصالت است.', 'negar-shop' ); ?>
		</div>
		<div class="kav-single-product-note-ques d-flex align-items-center fw-normal letter-spacing-2">
			<i class="icon-question"></i>
			<?php esc_html_e( 'امکان برگشت کالا با دلیل "انصراف از خرید" تنها در صورتی مورد قبول است که پلمب کالا باز نشده باشد.', 'negar-shop' ); ?>
		</div>
	</div>
</div>
