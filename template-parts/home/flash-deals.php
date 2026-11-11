<?php
/**
 * Home sale products carousel.
 *
 * Products and links intentionally come from WooCommerce. A product card is
 * never rendered with a generic shop URL as its destination.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_products' ) ) {
	return;
}

$sale_products = wc_get_products(
	array(
		'status'  => 'publish',
		'limit'    => 8,
		'on_sale'  => true,
		'orderby'  => 'date',
		'order'    => 'DESC',
		'return'   => 'objects',
	)
);

if ( empty( $sale_products ) ) {
	return;
}

$sale_url         = add_query_arg( 'on_sale', '1', wc_get_page_permalink( 'shop' ) );
$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
?>

<section class="negar-home-sale-carousel my-8" aria-labelledby="negar-home-sale-carousel-title">
	<div class="container">
		<div class="section-heading-one-nk d-flex align-items-center gap-2">
			<div class="title f-iran-yekan" id="negar-home-sale-carousel-title">
				<span class="fw-bold"><?php esc_html_e( 'تخفیفات', 'negar-shop' ); ?></span>
				<?php esc_html_e( 'روز', 'negar-shop' ); ?>
			</div>
			<div class="sub-title f-iran-yekan fw-normal d-none d-md-block">
				<?php esc_html_e( 'قیمت و موجودی واقعی فروشگاه', 'negar-shop' ); ?>
			</div>
			<div class="line flex-grow-1 d-flex align-items-center gap-1" aria-hidden="true"><span class="flex-grow-1"></span><span></span></div>
			<a href="<?php echo esc_url( $sale_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
				<?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?>
				<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
			</a>
		</div>

		<div class="negar-home-sale-carousel__body position-relative">
			<div id="negar-home-sale-products" class="swiper swiper-negar-home-sale-products overflow-hidden" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":1.2,"spaceBetween":12},"576":{"slidesPerView":2.1,"spaceBetween":16},"768":{"slidesPerView":3,"spaceBetween":18},"992":{"slidesPerView":4,"spaceBetween":20},"1200":{"slidesPerView":5,"spaceBetween":24}},"navigation":{"nextEl":".negar-home-sale-carousel .swiper-button-next","prevEl":".negar-home-sale-carousel .swiper-button-prev"}}'>
				<div class="swiper-wrapper">
					<?php foreach ( $sale_products as $index => $sale_product ) : ?>
						<div class="swiper-slide h-auto" role="group" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' / ' . count( $sale_products ) ); ?>">
							<?php
							$GLOBALS['product'] = $sale_product;
							get_template_part( 'template-parts/product/card' );
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<button type="button" class="swiper-button-prev rounded-circle d-none d-md-flex" aria-label="<?php esc_attr_e( 'محصول قبلی', 'negar-shop' ); ?>" aria-controls="negar-home-sale-products"><i class="icon-chevron-right lh-1" aria-hidden="true"></i></button>
			<button type="button" class="swiper-button-next rounded-circle d-none d-md-flex" aria-label="<?php esc_attr_e( 'محصول بعدی', 'negar-shop' ); ?>" aria-controls="negar-home-sale-products"><i class="icon-chevron-left lh-1" aria-hidden="true"></i></button>
		</div>
	</div>
</section>

<?php $GLOBALS['product'] = $previous_product; ?>
