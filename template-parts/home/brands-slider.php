<?php
/**
 * Best-Selling Brands Carousel Component (HOME-20)
 * Exact 1:1 match to Demo 43 brandscarouselsecondnkkaveh element (.elementor-element-7a921bf6 / .elementor-element-3f9b050c)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$brand_logo = $kaveh_base . '/brand-01-1.png';

$brands = array(
	array(
		'name'    => 'گرادور',
		'rate'    => '۳/۳',
		'stars'   => 3,
		'count'   => '۴۴',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
	array(
		'name'    => 'گرادور',
		'rate'    => '۴/۵',
		'stars'   => 5,
		'count'   => '۳۰',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
	array(
		'name'    => 'گرادور',
		'rate'    => '۱/۷',
		'stars'   => 2,
		'count'   => '۵۴',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
	array(
		'name'    => 'گرادور',
		'rate'    => '۳/۶',
		'stars'   => 4,
		'count'   => '۴۰',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
	array(
		'name'    => 'گرادور',
		'rate'    => '۳/۳',
		'stars'   => 3,
		'count'   => '۴۴',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
	array(
		'name'    => 'گرادور',
		'rate'    => '۳/۳',
		'stars'   => 3,
		'count'   => '۴۴',
		'logo'    => $brand_logo,
		'link'    => $shop_url,
	),
);
?>

<div class="elementor-element elementor-element-7a921bf6 e-con-full e-flex e-con e-parent e-lazyloaded" data-id="7a921bf6" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-3f9b050c elementor-widget elementor-widget-brandscarouselsecondnkkaveh" data-id="3f9b050c" data-element_type="widget" data-e-type="widget" data-widget_type="brandscarouselsecondnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="brands-nk brands-nk-3f9b050c">
				<div class="container">
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold"><?php esc_html_e( 'پرفروش', 'negar-shop' ); ?></span>
							<?php esc_html_e( 'ترین برندها', 'negar-shop' ); ?>
						</div>
						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'ضمانت قیمت و کیفیت', 'negar-shop' ); ?></div>
						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal rounded-pill position-relative">
							<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
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

											<div class="rate d-flex align-items-center justify-content-center gap-2 f-iransans mb-4">
												<div class="stars d-flex align-items-center justify-content-center gap-1">
													<?php for ( $s = 1; $s <= 5; $s++ ) : ?>
														<i class="icon-star-7 lh-1<?php echo ( $s > $brand['stars'] ) ? ' is-empty' : ''; ?>" aria-hidden="true"></i>
													<?php endfor; ?>
												</div>
												<?php echo esc_html( $brand['rate'] ); ?>
											</div>

											<img loading="lazy" decoding="async" src="<?php echo esc_url( $brand['logo'] ); ?>" alt="<?php echo esc_attr( $brand['name'] ); ?>" width="83" height="32" class="brand-logo d-block mx-auto">

											<div class="name-brand d-flex align-items-center justify-content-between">
												<div class="title f-iran-yekan"><?php echo esc_html( $brand['name'] ); ?></div>
												<div class="line flex-grow-1"></div>
												<div class="brand f-iran-yekan fw-normal"><?php esc_html_e( 'برند', 'negar-shop' ); ?></div>
											</div>

											<div class="d-flex align-items-center justify-content-between gap-2">
												<div class="products"><?php echo esc_html( $brand['count'] ); ?> <?php esc_html_e( 'محصول', 'negar-shop' ); ?></div>

												<span class="more d-flex align-items-center justify-content-center text-white rounded-pill position-relative">
													<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
												</span>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<div class="swiper-button-prev rounded-circle d-none d-md-flex"><i class="icon-chevron-right lh-1"></i></div>
							<div class="swiper-button-next rounded-circle d-none d-md-flex"><i class="icon-chevron-left lh-1"></i></div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
