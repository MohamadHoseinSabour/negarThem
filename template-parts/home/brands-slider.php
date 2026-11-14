<?php
/**
 * Best-Selling Brands Carousel Component (HOME-20)
 * Replaced demonstration cards with genuine WooCommerce brands.
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brands = function_exists( 'negar_theme_get_brands' ) ? negar_theme_get_brands( true ) : array();
if ( empty( $brands ) ) {
	return;
}

$shop_url            = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$has_multiple_brands = count( $brands ) > 1;
?>

<div class="elementor-element elementor-element-7a921bf6 e-con-full e-flex e-con e-parent e-lazyloaded" data-id="7a921bf6" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-3f9b050c elementor-widget elementor-widget-brandscarouselsecondnkkaveh" data-id="3f9b050c" data-element_type="widget" data-e-type="widget" data-widget_type="brandscarouselsecondnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="brands-nk brands-nk-3f9b050c">
				<div class="container">
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold"><?php esc_html_e( 'برندهای', 'negar-shop' ); ?></span>
							<?php esc_html_e( 'منتخب', 'negar-shop' ); ?>
						</div>
						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'اصالت و تنوع برندها', 'negar-shop' ); ?></div>
						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal rounded-pill position-relative">
							<?php esc_html_e( 'مشاهده فروشگاه', 'negar-shop' ); ?>
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<div class="brands-nk__wrapper position-relative">
						<div class="swiper swiper-brands-nk swiper-nk swiper-visibled swiper-visibled-mobile overflow-visible" data-swiper='{"watchSlidesProgress":true,"autoHeight":true,"speed":600,"loop":false,"breakpoints":{"320":{"slidesPerView":2.1,"spaceBetween":12},"576":{"slidesPerView":2.1,"spaceBetween":12},"768":{"slidesPerView":3,"spaceBetween":12},"992":{"slidesPerView":4,"spaceBetween":24},"1200":{"slidesPerView":4,"spaceBetween":40}},"navigation":{"nextEl":".elementor-element-3f9b050c .swiper-brands-nk > .swiper-button-next","prevEl":".elementor-element-3f9b050c .swiper-brands-nk > .swiper-button-prev"}}'>
							<div class="swiper-wrapper">
								<?php foreach ( $brands as $index => $brand ) : ?>
									<div class="swiper-slide h-auto" role="group" aria-label="<?php echo esc_attr( ( $index + 1 ) . ' / ' . count( $brands ) ); ?>">
										<div class="brands-nk__item d-flex flex-column h-100 bg-white position-relative">
											<a href="<?php echo esc_url( $brand['link'] ); ?>" class="stretched-link" aria-label="<?php echo esc_attr( $brand['name'] ); ?>"></a>

											<div class="brand-logo-wrap d-flex align-items-center justify-content-center flex-grow-1 py-3">
												<img loading="lazy" decoding="async" src="<?php echo esc_url( $brand['logo'] ); ?>" alt="<?php echo esc_attr( $brand['name'] ); ?>" width="83" height="32" class="brand-logo d-block mx-auto" style="max-height: 48px; width: auto; object-fit: contain;">
											</div>

											<div class="name-brand d-flex align-items-center justify-content-between">
												<div class="title f-iran-yekan"><?php echo esc_html( $brand['name'] ); ?></div>
												<div class="line flex-grow-1"></div>
												<div class="brand f-iran-yekan fw-normal"><?php esc_html_e( 'برند', 'negar-shop' ); ?></div>
											</div>

											<div class="d-flex align-items-center justify-content-end gap-2 mt-auto">
												<span class="more d-flex align-items-center justify-content-center text-white rounded-pill position-relative">
													<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
												</span>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php if ( $has_multiple_brands ) : ?>
								<div class="swiper-button-prev rounded-circle d-none d-md-flex"><i class="icon-chevron-right lh-1"></i></div>
								<div class="swiper-button-next rounded-circle d-none d-md-flex"><i class="icon-chevron-left lh-1"></i></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
