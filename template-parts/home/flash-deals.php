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


if ( negar_theme_product_section_is_configured( 'flash-deals' ) ) {
	$sale_query = new WP_Query(
		negar_theme_product_section_query_args(
			'flash-deals',
			array( 'post_type' => 'product', 'post_status' => 'publish' ),
			wc_get_product_ids_on_sale()
		)
	);
	$sale_products = array();
	while ( $sale_query->have_posts() ) {
		$sale_query->the_post();
		$product = wc_get_product( get_the_ID() );
		if ( $product && $product->is_visible() ) {
			$sale_products[] = $product;
		}
	}
	wp_reset_postdata();
} else {
$sale_products = array_filter( wc_get_products(
	array(
		'status'  => 'publish',
		'limit'    => 8,
		'on_sale'  => true,
		'orderby'  => 'date',
		'order'    => 'DESC',
		'return'   => 'objects',
	)
), static function ( $product ) {
	return $product && $product->is_visible();
} );
}

if ( empty( $sale_products ) ) {
	return;
}

$sale_url         = add_query_arg( 'on_sale', '1', wc_get_page_permalink( 'shop' ) );
$upload_dir       = wp_upload_dir();
$kaveh_base       = trailingslashit( $upload_dir['baseurl'] ) . 'kaveh-demo';
$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;

$banner_deal_img = file_exists( get_theme_file_path( 'assets/images/washing-machine-deal.webp' ) )
	? get_theme_file_uri( 'assets/images/washing-machine-deal.webp' )
	: ( $kaveh_base . '/washing-machine-deal.webp' );
?>

<section class="negar-home-sale-carousel popular-products-nk popular-products-nk-6ef6dd31 my-8 lg:my-16" aria-labelledby="negar-home-sale-carousel-title">
	<div class="container">
		<div class="popular-products-nk__wrapper position-relative d-flex align-items-center overflow-hidden">
			<div class="popular-products-nk__right position-relative" aria-labelledby="negar-home-sale-carousel-title">
				<div class="image mx-auto position-relative">
					<img loading="lazy" decoding="async" src="<?php echo esc_url( $banner_deal_img ); ?>" alt="<?php esc_attr_e( 'پیشنهاد تخفیفات روز', 'negar-shop' ); ?>" width="294" height="265" class="d-block">
				</div>
				<div class="title f-iran-yekan text-center position-relative mxw-fit mx-auto" id="negar-home-sale-carousel-title">
					<?php esc_html_e( 'پیشنهاد', 'negar-shop' ); ?>
				</div>
				<div class="sub-title f-iran-yekan fw-normal text-center">
					<?php esc_html_e( 'تخفیفات روز', 'negar-shop' ); ?>
				</div>
				<a href="<?php echo esc_url( $sale_url ); ?>" class="btn btn-nk-white d-flex align-items-center justify-content-center fw-normal mx-auto" style="border-radius: 50rem;">
					<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
					<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
				</a>
			</div>

			<div class="popular-products-nk__left negar-home-sale-carousel__body position-relative">
			<div id="negar-home-sale-products" class="swiper swiper-negar-home-sale-products swiper-visible overflow-hidden" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":1.2,"spaceBetween":12},"576":{"slidesPerView":2.1,"spaceBetween":16},"768":{"slidesPerView":2.7,"spaceBetween":18},"992":{"slidesPerView":3.2,"spaceBetween":20},"1200":{"slidesPerView":3.2,"spaceBetween":24}}}'>
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
			</div>
		</div>
	</div>
</section>

<?php $GLOBALS['product'] = $previous_product; ?>
