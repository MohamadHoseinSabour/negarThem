<?php
/**
 * Category Bubbles Component - 100% Exact Match to Demo 43 (categoriesnkkaveh)
 * Reference: Demo 43 Section 2
 *
 * @package Negar_Shop
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_link  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$category_links = array(
	'wearables'   => get_term_link( 'wearables', 'product_cat' ),
	'consoles'    => get_term_link( 'consoles', 'product_cat' ),
	'smartphones' => get_term_link( 'smartphones', 'product_cat' ),
	'phone-acc'   => get_term_link( 'phone-acc', 'product_cat' ),
);

foreach ( $category_links as $slug => $category_link ) {
	$category_links[ $slug ] = is_wp_error( $category_link ) ? $shop_link : $category_link;
}
?>
<div class="elementor-element elementor-element-619bae2e e-con-full e-flex e-con e-parent e-lazyloaded" data-id="619bae2e" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-57191199 elementor-widget elementor-widget-categoriesnkkaveh" data-id="57191199" data-element_type="widget" data-e-type="widget" data-widget_type="categoriesnkkaveh.default">
				<div class="elementor-widget-container">
							<section class="categories-nk categories-nk-57191199 my-10 lg:my-16">
			<div class="container">
									<div class="section-heading-one-nk d-flex align-items-center gap-2">
													<div class="title f-iran-yekan">
																	<span class="fw-bold">پرفروش</span>
																 ترین دسته بندی							</div>
						
													<div class="sub-title f-iran-yekan fw-normal d-none d-md-block">زمانت قیمت و کیفیت</div>
						
													<div class="line flex-grow-1 d-flex align-items-center gap-1">
								<span class="flex-grow-1"></span>
								<span></span>
							</div>
						
													<a href="<?php echo esc_url( $shop_link ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
								مشاهده بیشتر											<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
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
		
									<div class="swiper swiper-categories-nk swiper-visible swiper-visibled-mobile overflow-hidden overflow-visible-xs swiper-initialized swiper-horizontal swiper-pointer-events swiper-rtl swiper-watch-progress swiper-backface-hidden" data-swiper="{&quot;watchSlidesProgress&quot;:true,&quot;watchOverflow&quot;:true,&quot;breakpoints&quot;:{&quot;320&quot;:{&quot;slidesPerView&quot;:2.1,&quot;spaceBetween&quot;:12},&quot;576&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:12},&quot;768&quot;:{&quot;slidesPerView&quot;:3,&quot;spaceBetween&quot;:12},&quot;992&quot;:{&quot;slidesPerView&quot;:5,&quot;spaceBetween&quot;:12},&quot;1200&quot;:{&quot;slidesPerView&quot;:5,&quot;spaceBetween&quot;:12}}}" data-kaveh-swiper-mounted="1">
						<div class="swiper-wrapper" id="swiper-wrapper-ff94973cb950f8a8" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
																							<div class="swiper-slide swiper-slide-visible swiper-slide-active" role="group" aria-label="1 / 5" style="width: 249.6px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/category-01.png" ); ?>" alt="ساعت هوشمند" width="111" height="111">
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
																					<h2 class="title text-center fw-bold f-iran-yekan">ساعت هوشمند</h2>
																		<a href="<?php echo esc_url( $category_links['wearables'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="ساعت هوشمند">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
									</div>
								</div>
																							<div class="swiper-slide swiper-slide-visible swiper-slide-next" role="group" aria-label="2 / 5" style="width: 249.6px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/ChatGPT-Image-Sep-7-2026-08_26_32-PM-1-e1788800283621-1-266x300.png" ); ?>" alt="کنسول بازی" width="111" height="111">
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
																					<h2 class="title text-center fw-bold f-iran-yekan">کنسول بازی</h2>
																		<a href="<?php echo esc_url( $category_links['consoles'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="کنسول بازی">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
									</div>
								</div>
																							<div class="swiper-slide swiper-slide-visible" role="group" aria-label="3 / 5" style="width: 249.6px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/ChatGPT-Image-Sep-7-2026-08_34_23-PM-1-e1788800698777-1-295x300.png" ); ?>" alt="گوشی موبایل" width="111" height="111">
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
																					<h2 class="title text-center fw-bold f-iran-yekan">گوشی موبایل</h2>
																		<a href="<?php echo esc_url( $category_links['smartphones'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="گوشی موبایل">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
									</div>
								</div>
																							<div class="swiper-slide swiper-slide-visible" role="group" aria-label="4 / 5" style="width: 249.6px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/l7j5y-removebg-preview-e1788804006204-1-281x300.png" ); ?>" alt="ساعت هوشمند" width="111" height="111">
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
																					<h2 class="title text-center fw-bold f-iran-yekan">ساعت هوشمند</h2>
																		<a href="<?php echo esc_url( $category_links['wearables'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="ساعت هوشمند">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
									</div>
								</div>
																							<div class="swiper-slide swiper-slide-visible" role="group" aria-label="5 / 5" style="width: 249.6px; margin-left: 12px;">
									<div class="categories-nk__item h-100 d-flex flex-column align-items-center position-relative">
										<div class="image position-relative mx-auto">
											<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/9c9ei-removebg-preview-e1788804190536-1.png" ); ?>" alt="لوازم جانبی موبایل" width="111" height="111">
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
																					<h2 class="title text-center fw-bold f-iran-yekan">لوازم جانبی موبایل</h2>
																		<a href="<?php echo esc_url( $category_links['phone-acc'] ); ?>" class="btn btn-nk-danger-2 d-flex align-items-center justify-content-center position-relative p-0" aria-label="لوازم جانبی موبایل">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
									</div>
								</div>
													</div>
					<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
							</div>
		</section>
						</div>
				</div>
				</div>
