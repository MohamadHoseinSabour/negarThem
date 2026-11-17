<?php
/**
 * Section 17: Daily Deals Component (Purple Theme)
 * Reference: Demo 43 Section 16 (.popular-products-nk-375f8bd8)
 *
 * @package Negar_Shop
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wc_get_products' ) ) {
	return;
}

$upload_dir       = wp_upload_dir();
$kaveh_base       = trailingslashit( $upload_dir['baseurl'] ) . 'kaveh-demo';
$shop_link        = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$sale_link        = add_query_arg( 'on_sale', '1', $shop_link );
$previous_product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;

$banner_deal_img = file_exists( get_theme_file_path( 'assets/images/washing-machine-deal.webp' ) )
	? get_theme_file_uri( 'assets/images/washing-machine-deal.webp' )
	: ( $kaveh_base . '/washing-machine-deal.webp' );

// Target 5 Home Appliances for the second Daily Deals carousel
$target_keywords = array( 'HD9280', 'ECM2026', 'BS-1111', 'BGL8GOLDIR', 'HR7778' );
$sec17_products  = array();

foreach ( $target_keywords as $keyword ) {
	$matched_posts = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		's'              => $keyword,
	) );
	if ( ! empty( $matched_posts ) ) {
		$p_obj = wc_get_product( $matched_posts[0]->ID );
		if ( $p_obj && $p_obj->is_visible() ) {
			$sec17_products[] = $p_obj;
		}
	}
}

// Fallback to active sale products if needed
if ( empty( $sec17_products ) ) {
	$sec17_products = array_filter( wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 8,
			'on_sale' => true,
			'orderby' => 'date',
			'order'   => 'ASC',
			'return'  => 'objects',
		)
	), static function ( $p ) {
		return $p && $p->is_visible();
	} );
}

if ( empty( $sec17_products ) ) {
	return;
}
?>

<div class="elementor-element elementor-element-7ffd12cf e-con-full e-flex e-con e-parent" data-id="7ffd12cf" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-375f8bd8 elementor-widget elementor-widget-popularproductsnkkaveh" data-id="375f8bd8" data-element_type="widget" data-e-type="widget" data-widget_type="popularproductsnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="popular-products-nk popular-products-nk-375f8bd8 my-8 lg:my-16" aria-labelledby="negar-home-flash-deals-2-title">
				<div class="container">
					<div class="popular-products-nk__wrapper position-relative d-flex align-items-center overflow-hidden">
						
						<!-- Right Banner Panel (Purple Deals Promo) -->
						<div class="popular-products-nk__right position-relative" aria-labelledby="negar-home-flash-deals-2-title">
							<div class="image mx-auto position-relative">
								<img decoding="async" src="<?php echo esc_url( $banner_deal_img ); ?>" alt="<?php esc_attr_e( 'پیشنهاد تخفیفات روز', 'negar-shop' ); ?>" width="294" height="265" class="d-block">
							</div>
							<div class="title f-iran-yekan text-center position-relative mxw-fit mx-auto" id="negar-home-flash-deals-2-title">
								<?php esc_html_e( 'پیشنهاد', 'negar-shop' ); ?>
							</div>
							<div class="sub-title f-iran-yekan fw-normal text-center">
								<?php esc_html_e( 'تخفیفات روز', 'negar-shop' ); ?>
							</div>
							<a href="<?php echo esc_url( $sale_link ); ?>" class="btn btn-nk-white d-flex align-items-center justify-content-center fw-normal mx-auto" style="border-radius: 50rem;">
								<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
						</div>

						<!-- Left Products Carousel -->
						<div class="popular-products-nk__left negar-home-sale-carousel__body position-relative">
							<div id="negar-home-flash-deals-2-products" class="swiper swiper-negar-home-flash-deals-2 swiper-visible overflow-hidden" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":1.2,"spaceBetween":12},"576":{"slidesPerView":2.1,"spaceBetween":16},"768":{"slidesPerView":2.7,"spaceBetween":18},"992":{"slidesPerView":3.2,"spaceBetween":20},"1200":{"slidesPerView":3.2,"spaceBetween":24}}}'>
								<div class="swiper-wrapper">
									<?php foreach ( $sec17_products as $index => $sale_product ) : ?>
										<div class="swiper-slide h-auto" role="group" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' / ' . count( $sec17_products ) ); ?>">
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
		</div>
	</div>
</div>

<?php $GLOBALS['product'] = $previous_product; ?>
