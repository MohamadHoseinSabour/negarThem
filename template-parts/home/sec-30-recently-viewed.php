<?php
/**
 * Section 30: Recently Viewed Products (HOME-30)
 * Exact 1:1 match to Demo 43 topviewproductsthreefullnkkaveh element
 * (.elementor-element-37338312 / .elementor-element-73c2d3be)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$box_img    = $kaveh_base . '/box-03.png';

$items = array(
	array(
		'title' => 'هدفون بلوتوثی ناتینگ مدل CMF Buds 2',
		'image' => $kaveh_base . '/df649b7ee4285eb418555b08014ca497ff26b0be_1749393757-300x300-removebg-preview-1.png',
		'link'  => $shop_url,
	),
	array(
		'title' => 'هدست گیمینگ تسکو مدل GH 5141 Zonyx RGB',
		'image' => $kaveh_base . '/b12a165a30530e6743afc50a9a1980ec699494e6_1771680081-300x300-removebg-preview-1.png',
		'link'  => $shop_url,
	),
	array(
		'title' => 'هدفون بلوتوثی انکر مدل Liberty 4 Pro دارای نویز کن',
		'image' => $kaveh_base . '/7fc114c1a3c12afc0db1a26f1117e81c5a76a1c6_1786571883-removebg-preview-300x300.png',
		'link'  => $shop_url,
	),
	array(
		'title' => 'ساعت هوشمند اپل مدل SE Aluminum Case 44mm',
		'image' => $kaveh_base . '/15bbdc3cfde33fc112622d14bd61152cd86e9287_1738409004-300x300-removebg-preview-1.png',
		'link'  => $shop_url,
	),
	array(
		'title' => 'ساعت هوشمند 49 میلی متری اپل مدل Ultra 3 Natural Titanium Case با',
		'image' => $kaveh_base . '/128bfd71ad4017050659832b191c846cd050c5c1_1763052371-300x300-removebg-preview-1.png',
		'link'  => $shop_url,
	),
	array(
		'title' => 'ساعت هوشمند 46 میلی متری اپل مدل Series 11 Aluminum Case با ب',
		'image' => $kaveh_base . '/1cb72b186a7f24502e1bd066af4e2382a8caf099_1764429370-300x300-removebg-preview-1.png',
		'link'  => $shop_url,
	),
);
?>

<div class="elementor-element elementor-element-37338312 e-flex e-con-boxed e-con e-parent e-lazyloaded my-8 lg:my-12" data-id="37338312" data-element_type="container" data-e-type="container">
	<div class="e-con-inner container">
		<div class="elementor-element elementor-element-73c2d3be elementor-widget elementor-widget-topviewproductsthreefullnkkaveh w-100" data-id="73c2d3be" data-element_type="widget" data-e-type="widget" data-widget_type="topviewproductsthreefullnkkaveh.default">
			<div class="elementor-widget-container">
				<section class="top-view-products-three-full-nk top-view-products-three-full-nk-73c2d3be">
					<div class="container p-0">
						<div class="top-view-products-three-full-nk__wrapper position-relative">
							<div class="section-heading-one-nk d-flex align-items-center gap-2 flex-wrap">
								<div class="image position-relative d-none d-md-block">
									<img loading="lazy" decoding="async" src="<?php echo esc_url( $box_img ); ?>" alt="<?php esc_attr_e( 'باکس', 'negar-shop' ); ?>" width="68" height="53" class="d-block">
									<img loading="lazy" decoding="async" src="<?php echo esc_url( $box_img ); ?>" alt="" width="68" height="53" class="position-absolute" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" width="45" height="25" fill="none" viewBox="0 0 45 25" class="position-absolute end-0" aria-hidden="true">
										<rect width="44.004" height="5.642" x=".833" y="18.773" fill="currentColor" fill-opacity=".03" rx="2.821"></rect>
										<rect width="34.978" height="5.642" x="9.859" y="9.746" fill="currentColor" fill-opacity=".2" rx="2.821"></rect>
										<rect width="22.566" height="5.642" x="22.271" y=".72" fill="currentColor" fill-opacity=".13" rx="2.821"></rect>
									</svg>
								</div>

								<div class="title f-iran-yekan fw-bold">
									<span><?php esc_html_e( 'اخرین', 'negar-shop' ); ?></span>
									<?php esc_html_e( 'محصولات مشاهده شده', 'negar-shop' ); ?>
								</div>

								<div class="line flex-grow-1 d-flex align-items-center gap-1">
									<span class="flex-grow-1"></span>
									<span class="rounded-0"></span>
								</div>

								<div class="more d-flex align-items-center position-relative">
									<a href="<?php echo esc_url( $shop_url ); ?>" class="stretched-link" aria-label="<?php esc_attr_e( 'مشاهده همه', 'negar-shop' ); ?>"></a>
									<div class="d-none d-md-block">
										<div class="more__title f-iran-yekan fw-bold"><?php esc_html_e( 'محـصــــولات', 'negar-shop' ); ?></div>
										<div class="more__sub-title f-iran-yekan fw-normal"><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></div>
									</div>
									<span class="more__btn d-flex align-items-center justify-content-center position-relative">
										<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
									</span>
								</div>
							</div>

							<div class="top-view-products-three-full-nk__products position-relative has-nav">
								<div class="swiper swiper-top-view-products-three-full-nk swiper-nk both" data-swiper='{"autoHeight":true,"watchSlidesProgress":true,"watchOverflow":true,"spaceBetween":32,"breakpoints":{"320":{"slidesPerView":1.5,"spaceBetween":32},"576":{"slidesPerView":1.8,"spaceBetween":32},"768":{"slidesPerView":2,"spaceBetween":32},"992":{"slidesPerView":2.9,"spaceBetween":32},"1200":{"slidesPerView":3.2,"spaceBetween":32},"1400":{"slidesPerView":3.6,"spaceBetween":32}},"navigation":{"nextEl":".elementor-element-73c2d3be .top-view-products-three-full-nk__wrapper > .swiper-button-next","prevEl":".elementor-element-73c2d3be .top-view-products-three-full-nk__wrapper > .swiper-button-prev"}}' data-swiper-config='{"autoHeight":true,"watchSlidesProgress":true,"watchOverflow":true,"spaceBetween":32,"breakpoints":{"320":{"slidesPerView":1.5,"spaceBetween":32},"576":{"slidesPerView":1.8,"spaceBetween":32},"768":{"slidesPerView":2,"spaceBetween":32},"992":{"slidesPerView":2.9,"spaceBetween":32},"1200":{"slidesPerView":3.2,"spaceBetween":32},"1400":{"slidesPerView":3.6,"spaceBetween":32}},"navigation":{"nextEl":".elementor-element-73c2d3be .top-view-products-three-full-nk__wrapper > .swiper-button-next","prevEl":".elementor-element-73c2d3be .top-view-products-three-full-nk__wrapper > .swiper-button-prev"}}'>
									<div class="swiper-wrapper">
										<?php foreach ( $items as $item ) : ?>
											<div class="swiper-slide" role="group">
												<div class="top-view-products-three-full-nk__item d-flex align-items-center position-relative">
													<a href="<?php echo esc_url( $item['link'] ); ?>" class="image flex-fill flex-grow-0 flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle position-relative" aria-label="<?php echo esc_attr( $item['title'] ); ?>">
														<span class="img d-flex align-items-center justify-content-center rounded-circle">
															<img loading="lazy" decoding="async" src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" width="44" height="48" class="w-auto">
														</span>
													</a>
													<h2 class="title f-iran-yekan line-clamp-2 m-0 w-100">
														<a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
													</h2>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>

							<div class="swiper-button-prev rounded-circle d-none d-lg-flex" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'قبلی', 'negar-shop' ); ?>"><i class="icon-chevron-right lh-1" aria-hidden="true"></i></div>
							<div class="swiper-button-next rounded-circle d-none d-lg-flex" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'بعدی', 'negar-shop' ); ?>"><i class="icon-chevron-left lh-1" aria-hidden="true"></i></div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
