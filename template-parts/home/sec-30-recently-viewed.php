<?php
/**
 * Recently viewed products carousel.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_product' ) ) {
	return;
}

$recent_ids = isset( $_COOKIE['negar_recent_products'] ) ? array_map( 'absint', explode( ',', wp_unslash( $_COOKIE['negar_recent_products'] ) ) ) : array();
$recent_ids = array_slice( array_values( array_unique( array_filter( $recent_ids ) ) ), 0, 8 );
$products   = array();

foreach ( $recent_ids as $recent_id ) {
	$product = wc_get_product( $recent_id );
	if ( $product && $product->is_visible() ) {
		$products[] = $product;
	}
}

if ( empty( $products ) ) {
	return;
}

$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
?>

<section class="negar-recently-viewed my-8 lg:my-12" aria-labelledby="negar-recently-viewed-title">
	<div class="container">
		<div class="section-heading-one-nk d-flex align-items-center gap-2">
			<div class="title f-iran-yekan" id="negar-recently-viewed-title">
				<span class="fw-bold"><?php esc_html_e( 'آخرین', 'negar-shop' ); ?></span>
				<?php esc_html_e( 'محصولات مشاهده‌شده', 'negar-shop' ); ?>
			</div>
			<div class="line flex-grow-1 d-flex align-items-center gap-1" aria-hidden="true"><span class="flex-grow-1"></span><span></span></div>
		</div>

		<div class="position-relative">
			<div id="negar-recently-viewed-products" class="swiper swiper-negar-recently-viewed overflow-hidden" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":1.2,"spaceBetween":12},"576":{"slidesPerView":2.1,"spaceBetween":16},"768":{"slidesPerView":3,"spaceBetween":18},"992":{"slidesPerView":4,"spaceBetween":20},"1200":{"slidesPerView":5,"spaceBetween":24}},"navigation":{"nextEl":".negar-recently-viewed .swiper-button-next","prevEl":".negar-recently-viewed .swiper-button-prev"}}'>
				<div class="swiper-wrapper">
					<?php foreach ( $products as $index => $recent_product ) : ?>
						<div class="swiper-slide h-auto" role="group" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' / ' . count( $products ) ); ?>">
							<?php
							$GLOBALS['product'] = $recent_product;
							get_template_part( 'template-parts/product/card' );
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<button type="button" class="swiper-button-prev rounded-circle d-none d-md-flex" aria-label="<?php esc_attr_e( 'محصول قبلی', 'negar-shop' ); ?>" aria-controls="negar-recently-viewed-products"><i class="icon-chevron-right lh-1" aria-hidden="true"></i></button>
			<button type="button" class="swiper-button-next rounded-circle d-none d-md-flex" aria-label="<?php esc_attr_e( 'محصول بعدی', 'negar-shop' ); ?>" aria-controls="negar-recently-viewed-products"><i class="icon-chevron-left lh-1" aria-hidden="true"></i></button>
		</div>
	</div>
</section>

<?php $GLOBALS['product'] = $previous_product; ?>
