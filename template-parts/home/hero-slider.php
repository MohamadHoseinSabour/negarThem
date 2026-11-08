<?php
/**
 * Hero Section (Hero Banner + Flash Deal Card) - Clean Semantic Markup
 * Reference: Kaveh Demo 43 Section 1 (.landing-nk)
 *
 * @package Negar_Shop
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_link  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$fc26_link  = $shop_link;

$fc_prod = get_page_by_path( 'بازی-ea-sports-fc-26-نسخه-استاندارد-مخصوص-ps5', OBJECT, 'product' );
if ( $fc_prod ) {
	$fc26_link = get_permalink( $fc_prod->ID );
}
?>
<div class="elementor-element elementor-element-229ccc7d e-con-full e-flex e-con e-parent e-lazyloaded" data-id="229ccc7d" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-6b94d804 elementor-widget elementor-widget-landingnkkaveh" data-id="6b94d804" data-element_type="widget" data-e-type="widget" data-widget_type="landingnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="landing-nk landing-nk-6b94d804 is-first-slide my-8 lg:my-14" data-landing-nk-id="6b94d804" data-landing-nk-carousel-loop="1" data-landing-nk-products-loop="0" data-landing-nk-btn-view-dynamic="1" data-landing-nk-ready="1">
				<div class="container">
					<div class="landing-nk__wrapper d-flex align-items-lg-center position-relative flex-column flex-lg-row">
						
						<!-- 1. Main Hero Carousel -->
						<div class="landing-nk__carousel w-100 position-relative d-flex align-items-center">
							<div class="sh sh-1 position-absolute top-50 translate-middle-y"></div>
							<div class="sh sh-2 position-absolute top-50 translate-middle-y"></div>
							<div class="sh sh-3 position-absolute top-50 translate-middle-y"></div>
							<div class="sh sh-4 position-absolute top-50 translate-middle-y"></div>
							<div class="sh sh-5 position-absolute top-50 translate-middle-y"></div>
							<div class="sh sh-6 position-absolute top-50 translate-middle-y"></div>
							<div class="cir cir-1 position-absolute top-50 translate-middle-y"></div>
							<div class="cir cir-2 position-absolute top-50 translate-middle-y"></div>

							<div class="swiper swiper-landing-nk swiper-landing-nk-6b94d804 w-100">
								<div class="swiper-wrapper">

									<!-- Slide 1 -->
									<div class="swiper-slide">
										<div class="landing-nk__carousel__item d-flex align-items-center">
											<div class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
												<span class="bg position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-1 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-2 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-3 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-4 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<svg xmlns="http://www.w3.org/2000/svg" width="48" height="24" fill="none" viewBox="0 0 48 24" class="shape shape-1 position-absolute start-0" aria-hidden="true">
													<path stroke="#fff" stroke-linecap="round" d="M11 18.5c1.737-5.368 11.368-16.284 36-17m-46 9C2.061 7.342 7.947.921 23 .5m-4 23c.675-2.21 4.421-6.705 14-7"></path>
												</svg>
												<svg xmlns="http://www.w3.org/2000/svg" width="48" height="25" fill="none" viewBox="0 0 48 25" class="shape shape-2 position-absolute start-100" aria-hidden="true">
													<path stroke="#fff" stroke-linecap="round" d="M37 6c-1.737 5.368-11.368 16.284-36 17m46-9c-1.061 3.158-6.947 9.579-22 10m4-23c-.675 2.21-4.421 6.705-14 7"></path>
												</svg>
												<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . '/landing-01-300x261-1.png' ); ?>" alt="<?php esc_attr_e( 'خرید برترین محصولات', 'negar-shop' ); ?>" width="337" height="293" class="d-block">
											</div>
											<div class="detail w-100">
												<div class="sup-title overflow-hidden">
													<div class="f-iran-yekan"><?php esc_html_e( 'خرید دسته‌های ایکس باکس', 'negar-shop' ); ?></div>
												</div>
												<div class="title overflow-hidden">
													<h2 class="f-iran-yekan mb-0"><?php esc_html_e( 'خرید برترین محصولاتــــــــ', 'negar-shop' ); ?></h2>
												</div>
												<a href="<?php echo esc_url( $shop_link ); ?>" class="btn d-flex align-items-center justify-content-center ms-auto">
													<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
													<i class="icon-chevron-left-2 lh-1"></i>
												</a>
											</div>
										</div>
									</div>

									<!-- Slide 2 -->
									<div class="swiper-slide">
										<div class="landing-nk__carousel__item d-flex align-items-center">
											<div class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
												<span class="bg position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-1 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-2 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-3 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<span class="circle circle-4 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
												<svg xmlns="http://www.w3.org/2000/svg" width="48" height="24" fill="none" viewBox="0 0 48 24" class="shape shape-1 position-absolute start-0" aria-hidden="true">
													<path stroke="#fff" stroke-linecap="round" d="M11 18.5c1.737-5.368 11.368-16.284 36-17m-46 9C2.061 7.342 7.947.921 23 .5m-4 23c.675-2.21 4.421-6.705 14-7"></path>
												</svg>
												<svg xmlns="http://www.w3.org/2000/svg" width="48" height="25" fill="none" viewBox="0 0 48 25" class="shape shape-2 position-absolute start-100" aria-hidden="true">
													<path stroke="#fff" stroke-linecap="round" d="M37 6c-1.737 5.368-11.368 16.284-36 17m46-9c-1.061 3.158-6.947 9.579-22 10m4-23c-.675 2.21-4.421 6.705-14 7"></path>
												</svg>
												<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . '/landing-01-300x261-1.png' ); ?>" alt="<?php esc_attr_e( 'کنسول‌های بازی نسل نهم', 'negar-shop' ); ?>" width="337" height="293" class="d-block">
											</div>
											<div class="detail w-100">
												<div class="sup-title overflow-hidden">
													<div class="f-iran-yekan"><?php esc_html_e( 'کنسول‌های بازی نسل نهم', 'negar-shop' ); ?></div>
												</div>
												<div class="title overflow-hidden">
													<h2 class="f-iran-yekan mb-0"><?php esc_html_e( 'تجربه بی‌نظیر گیمینگ', 'negar-shop' ); ?></h2>
												</div>
												<a href="<?php echo esc_url( $shop_link ); ?>" class="btn d-flex align-items-center justify-content-center ms-auto">
													<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
													<i class="icon-chevron-left-2 lh-1"></i>
												</a>
											</div>
										</div>
									</div>

								</div>

								<!-- Slider navigation buttons nested inside the crescents -->
								<div class="swiper-button-prev d-flex align-items-center justify-content-center" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'اسلاید قبلی', 'negar-shop' ); ?>">
									<i class="icon-chevron-right lh-1"></i>
								</div>
								<div class="swiper-button-next d-flex align-items-center justify-content-center" tabindex="0" role="button" aria-label="<?php esc_attr_e( 'اسلاید بعدی', 'negar-shop' ); ?>">
									<i class="icon-chevron-left lh-1"></i>
								</div>
							</div>
						</div>

						<!-- 2. Flash Deal Box (Left Box) -->
						<div class="landing-nk__products position-relative w-100">
							<div class="shape-bg position-absolute d-none d-lg-block"></div>
							<div class="shape-tr position-absolute d-none d-lg-block"></div>
							<div class="shape-br position-absolute d-none d-lg-block"></div>
							<div class="landing-nk__products__box position-relative">
								<div class="sh-top position-absolute top-0"></div>
								<div class="line-top position-absolute top-0 d-lg-none"></div>
								<div class="line-bottom-mb position-absolute bottom-0 end-0 w-100 d-lg-none bg-border"></div>
								<div class="sh-bottom position-absolute bottom-0 start-50 translate-middle-x d-none d-lg-block"></div>
								<div class="line-bottom position-absolute bottom-0 end-50 d-none d-lg-block"></div>
								<div class="shape-b position-absolute bottom-0 end-50 d-none d-lg-block"></div>
								<div class="offer position-absolute d-flex align-items-center">
									<div class="offer__text">
										<div class="sup-title f-iransans fw-light"><?php esc_html_e( 'تخــفیفـــــات', 'negar-shop' ); ?></div>
										<div class="title f-iransans fw-semibold"><?php esc_html_e( 'لـــحظه ایــ', 'negar-shop' ); ?></div>
									</div>
									<span class="offer__icon flex-shrink-0" aria-hidden="true"></span>
								</div>

								<div class="swiper swiper-landing-product-nk swiper-landing-product-nk-6b94d804">
									<div class="swiper-wrapper">
										<div class="swiper-slide" data-product-url="<?php echo esc_url( $fc26_link ); ?>" role="group" aria-label="<?php esc_attr_e( 'پیشنهاد شگفت‌انگیز', 'negar-shop' ); ?>">
											<div class="landing-nk__products__item position-relative d-flex align-items-center align-items-start flex-row flex-lg-column">
												<div class="discount position-absolute f-iran-yekan d-flex align-items-center justify-content-center">
													۳۵ <i class="icon-discount-4"></i>
												</div>
												<div class="image position-relative mx-lg-auto">
													<div class="bg position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<span class="cir cir-1 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
													<span class="cir cir-2 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
													<span class="cir cir-3 position-absolute top-50 start-50 translate-middle rounded-circle"></span>
													<svg xmlns="http://www.w3.org/2000/svg" width="42" height="22" fill="none" viewBox="0 0 42 22" class="position-absolute bottom-100 end-100 d-none d-lg-block" aria-hidden="true">
														<path stroke="#ddd" stroke-linecap="round" d="M10 17c1.496-4.737 9.79-14.368 31-15M1 10c.917-2.842 6-8.621 19-9m-3 20c.579-1.895 3.79-5.747 12-6"></path>
													</svg>
													<a href="<?php echo esc_url( $fc26_link ); ?>" class="d-block mx-auto">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . '/5a11a470554a92905e74facee6978604b2f3fd09_1754815528-300x300-removebg-preview-1.png' ); ?>" alt="<?php esc_attr_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>" width="122" height="134" class="d-block w-100">
													</a>
												</div>
												<div class="detail">
													<h2 class="title f-iran-yekan fw-semibold">
														<a href="<?php echo esc_url( $fc26_link ); ?>"><?php esc_html_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?></a>
													</h2>
													<div class="comments-rate d-none d-lg-flex align-items-center justify-content-between mb-3">
														<div class="comments f-iransans">
															<?php esc_html_e( '۲ نقد و بررسی', 'negar-shop' ); ?>
														</div>
														<div class="line flex-grow-1"></div>
														<div class="rate d-flex align-items-center gap-2 f-iransans">
															۴.۵ / ۵
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 18 18" aria-hidden="true">
																<path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M11.543 3.908 12.6 6.023c.143.292.525.57.848.63l1.912.314c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 13.56c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 8.94c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
																<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M6 3.75H1.5m2.25 10.5H1.5M2.25 9H1.5" opacity=".4"></path>
															</svg>
														</div>
													</div>
													<div class="price d-flex align-items-center justify-content-between gap-2">
														<del class="off fw-normal f-iransans text-uppercase">۶,۴۸۸,۱۰۰</del>
														<div class="price d-flex align-items-center f-iransans">
															۴,۱۹۹,۰۰۰
															<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="swiper-pagination"></div>
								</div>

								<div class="landing-nk__products__price price d-none d-lg-flex align-items-center justify-content-between gap-2 position-absolute">
									<del class="off fw-normal f-iransans text-uppercase">۶,۴۸۸,۱۰۰</del>
									<div class="price d-flex align-items-center f-iransans">
										۴,۱۹۹,۰۰۰
										<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
									</div>
								</div>
								<a class="btn-view d-none d-lg-flex align-items-center justify-content-center position-absolute top-100 end-50" aria-label="<?php esc_attr_e( 'مشاهده محصول', 'negar-shop' ); ?>" href="<?php echo esc_url( $fc26_link ); ?>">
									<i class="icon icon-chevron-left-2 btn-view__icon lh-1" aria-hidden="true"></i>
								</a>
							</div>
						</div>

					</div>
				</div>
			</section>
		</div>
	</div>
</div>
