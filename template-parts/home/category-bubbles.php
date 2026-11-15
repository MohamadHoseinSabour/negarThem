<?php
/**
 * Category Bubbles Component - Selected Main Categories (Home Appliances)
 * Reference: Demo 43 Section 2
 *
 * @package Negar_Shop
 * @version 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_link = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Initial 6 storefront priority main categories in specified order
$priority_categories = array(
	'major-appliances'    => array(
		'fallback_name' => 'لوازم خانگی بزرگ',
		'fallback_img'  => 'cat-1793876649-728.png',
	),
	'electric-cooking'    => array(
		'fallback_name' => 'لوازم برقی پختوپز',
		'fallback_img'  => 'cat-1793876649-179.png',
	),
	'food-preparation'    => array(
		'fallback_name' => 'لوازم آمادهسازی غذا',
		'fallback_img'  => 'cat-1793876649-992.png',
	),
	'beverage-breakfast'  => array(
		'fallback_name' => 'نوشیدنیساز و صبحانه',
		'fallback_img'  => 'cat-1793876650-373.png',
	),
	'cleaning-ironing'    => array(
		'fallback_name' => 'نظافت و اتوکشی',
		'fallback_img'  => 'cat-1793876650-919.png',
	),
	'cooling-heating-air' => array(
		'fallback_name' => 'سرمایش، گرمایش و تهویه',
		'fallback_img'  => 'cat-1793876649-597.png',
	),
);

$upload_dir = wp_upload_dir();
$upload_base = $upload_dir['baseurl'] . '/2026/11/';

$bubbles = array();
foreach ( $priority_categories as $slug => $meta ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	$name = ( $term && ! is_wp_error( $term ) ) ? $term->name : $meta['fallback_name'];
	$link = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : $shop_link;

	$image_url = '';
	if ( $term && ! is_wp_error( $term ) ) {
		$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $thumb_id ) {
			$image_url = wp_get_attachment_image_url( $thumb_id, 'full' );
		}
	}
	if ( empty( $image_url ) ) {
		$image_url = $upload_base . $meta['fallback_img'];
	}

	$bubbles[] = array(
		'slug'  => $slug,
		'name'  => $name,
		'link'  => $link,
		'image' => $image_url,
	);
}
?>
<div class="elementor-element elementor-element-619bae2e e-con-full e-flex e-con e-parent e-lazyloaded" data-id="619bae2e" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-57191199 elementor-widget elementor-widget-categoriesnkkaveh" data-id="57191199" data-element_type="widget" data-e-type="widget" data-widget_type="categoriesnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="categories-nk categories-nk-57191199 my-10 lg:my-16">
				<div class="container">
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold">دسته‌بندی‌های</span> منتخب
						</div>

						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'ضمانت اصالت و برترین برندهای لوازم خانگی', 'negar-shop' ); ?></div>

						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>

						<a href="<?php echo esc_url( $shop_link ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
							<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" fill="none" viewBox="0 0 17 18" aria-hidden="true" focusable="false" style="position:absolute;width:0;height:0;overflow:hidden;">
						<defs>
							<linearGradient id="a_category_57191199" x1=".948" x2="14.325" y1="14.662" y2="1.285" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
							<linearGradient id="b_category_57191199" x1="3.623" x2="9.866" y1="17.337" y2="11.094" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
							<linearGradient id="c_category_57191199" x1="8.082" x2="16.108" y1="17.337" y2="9.311" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
							<linearGradient id="a_category_2_57191199" x1="15.835" x2="2.458" y1="14.398" y2="1.022" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
							<linearGradient id="b_category_2_57191199" x1="13.16" x2="6.918" y1="17.073" y2="10.831" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
							<linearGradient id="c_category_2_57191199" x1="8.701" x2=".675" y1="17.073" y2="9.048" gradientUnits="userSpaceOnUse">
								<stop></stop>
								<stop offset="1" stop-opacity=".1"></stop>
							</linearGradient>
						</defs>
					</svg>

					<div class="swiper swiper-categories-nk swiper-visible swiper-visibled-mobile overflow-hidden overflow-visible-xs swiper-initialized swiper-horizontal swiper-pointer-events swiper-rtl swiper-watch-progress swiper-backface-hidden" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":2.1,"spaceBetween":12},"576":{"slidesPerView":3,"spaceBetween":12},"768":{"slidesPerView":3,"spaceBetween":12},"992":{"slidesPerView":5,"spaceBetween":12},"1200":{"slidesPerView":6,"spaceBetween":12}}}' data-kaveh-swiper-mounted="1">
						<div class="swiper-wrapper" id="swiper-wrapper-ff94973cb950f8a8" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
							<?php
							$total_bubbles = count( $bubbles );
							foreach ( $bubbles as $idx => $bubble ) :
								$slide_index = $idx + 1;
								$is_first = 0 === $idx;
								$slide_classes = array( 'swiper-slide', 'swiper-slide-visible' );
								if ( $is_first ) {
									$slide_classes[] = 'swiper-slide-active';
								} elseif ( 1 === $idx ) {
									$slide_classes[] = 'swiper-slide-next';
								}
								?>
								<div class="<?php echo esc_attr( implode( ' ', $slide_classes ) ); ?>" role="group" aria-label="<?php echo esc_attr( "{$slide_index} / {$total_bubbles}" ); ?>" style="width: 200px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $bubble['image'] ); ?>" alt="<?php echo esc_attr( $bubble['name'] ); ?>" width="111" height="111" style="object-fit: contain;">
											<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" fill="none" viewBox="0 0 17 18" class="shape shape-1 position-absolute end-100">
												<path stroke="url(#a_category_57191199)" stroke-linecap="round" d="M.595 14.308 13.97.93"></path>
												<path stroke="url(#b_category_57191199)" stroke-linecap="round" d="m3.27 16.983 6.242-6.242"></path>
												<path stroke="url(#c_category_57191199)" stroke-linecap="round" d="m7.729 16.983 8.026-8.026"></path>
											</svg>
											<svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" fill="none" viewBox="0 0 17 18" class="shape shape-2 position-absolute start-100">
												<path stroke="url(#a_category_2_57191199)" stroke-linecap="round" d="M16.188 14.045 2.812.668"></path>
												<path stroke="url(#b_category_2_57191199)" stroke-linecap="round" d="M13.514 16.72 7.27 10.478"></path>
												<path stroke="url(#c_category_2_57191199)" stroke-linecap="round" d="M9.055 16.72 1.029 8.694"></path>
											</svg>
										</div>
										<h2 class="title text-center fw-bold f-iran-yekan"><?php echo esc_html( $bubble['name'] ); ?></h2>
										<a href="<?php echo esc_url( $bubble['link'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="<?php echo esc_attr( $bubble['name'] ); ?>">
											<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
										</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
