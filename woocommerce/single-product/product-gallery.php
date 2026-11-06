<?php
/**
 * Single Product Gallery Component
 *
 * @package Negar_Shop
 * @version 1.0.0
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

$main_img_url  = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'large' ) : wc_placeholder_img_src( 'woocommerce_single' );
$main_full_url = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'full' ) : $main_img_url;
?>

<div class="negar-product-gallery relative flex flex-col" id="negar-product-gallery">
	<!-- Main Image Container -->
	<div class="negar-gallery-main relative rounded-2xl overflow-hidden border border-border bg-white p-6 shadow-sm flex items-center justify-center min-h-[380px] sm:min-h-[440px]">
		
		<!-- Badges -->
		<div class="absolute top-4 right-4 flex flex-col gap-2 z-10">
			<?php if ( $product->is_on_sale() ) : ?>
				<?php echo wp_kses_post( negar_shop_get_discount_badge( $product ) ); ?>
			<?php endif; ?>
			<?php if ( ! $product->is_in_stock() ) : ?>
				<span class="badge badge-stock text-xs font-bold py-1 px-2.5 rounded-full bg-slate-200 text-slate-700">
					<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
				</span>
			<?php endif; ?>
		</div>

		<!-- Zoom / Lightbox Trigger Button -->
		<button type="button" class="negar-zoom-btn absolute bottom-4 left-4 p-2.5 rounded-full bg-surface/90 hover:bg-surface border border-border text-dark shadow-sm hover:shadow transition-all focus:outline-none z-10" aria-label="<?php esc_attr_e( 'بزرگ‌نمایی تصویر', 'negar-shop' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="11" cy="11" r="8"/>
				<line x1="21" y1="21" x2="16.65" y2="16.65"/>
				<line x1="11" y1="8" x2="11" y2="14"/>
				<line x1="8" y1="11" x2="14" y2="11"/>
			</svg>
		</button>

		<!-- Main Image -->
		<img 
			id="negar-main-gallery-image"
			src="<?php echo esc_url( $main_img_url ); ?>" 
			data-zoom-src="<?php echo esc_url( $main_full_url ); ?>"
			alt="<?php echo esc_attr( $product->get_name() ); ?>"
			class="max-h-[380px] max-w-full w-auto h-auto object-contain transition-all duration-300 transform hover:scale-105"
		>
	</div>

	<!-- Thumbnails Carousel Strip -->
	<?php if ( count( $all_images ) > 1 ) : ?>
		<div class="negar-gallery-thumbs flex items-center gap-3 mt-4 overflow-x-auto pb-2 pr-1 scrollbar-thin">
			<?php foreach ( $all_images as $index => $img_id ) : ?>
				<?php
				$thumb_url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
				$large_url = wp_get_attachment_image_url( $img_id, 'large' );
				$full_url  = wp_get_attachment_image_url( $img_id, 'full' );
				$is_active = ( 0 === $index );
				?>
				<button 
					type="button" 
					class="negar-thumb-btn flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 bg-white p-1 transition-all <?php echo $is_active ? 'border-primary shadow-sm scale-105' : 'border-border hover:border-slate-400 opacity-70 hover:opacity-100'; ?>"
					data-large-src="<?php echo esc_url( $large_url ); ?>"
					data-full-src="<?php echo esc_url( $full_url ); ?>"
					aria-label="<?php echo esc_attr( sprintf( __( 'تصویر %d', 'negar-shop' ), $index + 1 ) ); ?>"
				>
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" class="w-full h-full object-contain">
				</button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<!-- Lightbox Modal -->
	<div class="negar-gallery-lightbox fixed inset-0 z-50 bg-dark/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300" id="negar-gallery-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'نمایش بزرگ تصویر', 'negar-shop' ); ?>">
		<button type="button" class="negar-close-lightbox absolute top-6 left-6 text-white hover:text-danger p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors focus:outline-none" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
		</button>
		<img id="negar-lightbox-img" src="<?php echo esc_url( $main_full_url ); ?>" alt="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl">
	</div>

</div>
