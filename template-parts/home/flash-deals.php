<?php
/**
 * Daily Deals Component - 100% Exact Match to Demo 43 (popularproductsnkkaveh)
 * Reference: Demo 43 Section 3 (.popular-products-nk)
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
?>
<div class="elementor-element elementor-element-2ab93256 e-con-full e-flex e-con e-parent" data-id="2ab93256" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-6ef6dd31 elementor-widget elementor-widget-popularproductsnkkaveh" data-id="6ef6dd31" data-element_type="widget" data-e-type="widget" data-widget_type="popularproductsnkkaveh.default">
				<div class="elementor-widget-container">
							<section class="popular-products-nk popular-products-nk-6ef6dd31">
			<div class="container">
				<div class="popular-products-nk__wrapper position-relative d-flex align-items-center overflow-hidden">
					<div class="line position-absolute top-0 end-0 w-100 h-100"></div>
					<div class="shape position-absolute top-0 end-0 w-100 h-100"></div>
					<div class="border-bg position-absolute bottom-0 d-none d-lg-block"></div>
													<div class="popular-products-nk__right w-100 position-relative d-none d-md-block">
			<div class="image mx-auto position-relative">
				<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/popular-products.png" ); ?>" alt="پیشنهاد" width="294" height="265" class="d-block">
				<svg xmlns="http://www.w3.org/2000/svg" width="42" height="22" fill="none" viewBox="0 0 42 22" class="shape shape-1 position-absolute">
					<path stroke="#fff" stroke-linecap="round" d="M10 17.14c1.496-4.737 9.79-14.369 31-15m-40 8c.917-2.842 6-8.621 19-9m-3 20c.579-1.895 3.79-5.748 12-6"></path>
				</svg>
				<svg xmlns="http://www.w3.org/2000/svg" width="42" height="23" fill="none" viewBox="0 0 42 23" class="shape shape-2 position-absolute">
					<path stroke="#fff" stroke-linecap="round" d="M32 6c-1.496 4.737-9.79 14.368-31 15m40-8c-.917 2.842-6 8.621-19 9m4-21c-.917 2.842-6 8.621-19 9"></path>
				</svg>
			</div>
							<div class="title f-iran-yekan text-center position-relative mxw-fit mx-auto">
					پیشنهاد											<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="position-absolute">
							<defs>
								<clipPath id="ppnk_badge_6ef6dd31_clip" transform="translate(100.992 100.963)">
									<path d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z"></path>
								</clipPath>
							</defs>
							<foreignObject x="-100.992" y="-100.963" width="237.955" height="237.955">
								<div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(52px);clip-path:url(#ppnk_badge_6ef6dd31_clip);height:100%;width:100%"></div>
							</foreignObject>
							<path opacity="0.42" d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z" fill="white"></path>
							<path d="M22.5 24C21.66 24 20.985 23.325 20.985 22.5C20.985 21.675 21.66 21 22.485 21C23.31 21 23.985 21.675 23.985 22.5C23.985 23.325 23.325 24 22.5 24Z" fill="white"></path>
							<path d="M13.515 15C12.675 15 12 14.325 12 13.5C12 12.675 12.675 12 13.5 12C14.325 12 15 12.675 15 13.5C15 14.325 14.34 15 13.515 15Z" fill="white"></path>
							<path d="M13.5 23.625C13.215 23.625 12.93 23.52 12.705 23.295C12.27 22.86 12.27 22.1399 12.705 21.7049L21.705 12.7049C22.14 12.2699 22.86 12.2699 23.295 12.7049C23.73 13.1399 23.73 13.86 23.295 14.295L14.295 23.295C14.07 23.52 13.785 23.625 13.5 23.625Z" fill="white"></path>
						</svg>
									</div>
										<div class="sub-title f-iran-yekan fw-normal text-center">تخفیفات روز</div>
										<a href="<?php echo esc_url( $shop_link ); ?>" class="btn btn-nk-white d-flex align-items-center justify-content-center fw-normal mx-auto" style="border-radius: 50rem;">
					<span class="d-none d-md-block">مشاهده بیشتر</span>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
					</div>
												<div class="popular-products-nk__left w-100 position-relative">
						<div class="swiper swiper-products-popular swiper-visible swiper-visibled-mobile overflow-hidden" data-swiper="{&quot;autoHeight&quot;:false,&quot;watchSlidesProgress&quot;:true,&quot;breakpoints&quot;:{&quot;320&quot;:{&quot;slidesPerView&quot;:2.1,&quot;spaceBetween&quot;:8},&quot;768&quot;:{&quot;slidesPerView&quot;:1.8,&quot;spaceBetween&quot;:10},&quot;992&quot;:{&quot;slidesPerView&quot;:3.2,&quot;spaceBetween&quot;:10},&quot;1200&quot;:{&quot;slidesPerView&quot;:3.2,&quot;spaceBetween&quot;:10},&quot;1400&quot;:{&quot;slidesPerView&quot;:3.2,&quot;spaceBetween&quot;:10}}}">
							<div class="swiper-wrapper">
																	<div class="swiper-slide d-md-none">
												<div class="popular-products-nk__right w-100 position-relative d-md-none">
			<div class="image mx-auto position-relative">
				<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/popular-products.png" ); ?>" alt="پیشنهاد" width="294" height="265" class="d-block">
				<svg xmlns="http://www.w3.org/2000/svg" width="42" height="22" fill="none" viewBox="0 0 42 22" class="shape shape-1 position-absolute">
					<path stroke="#fff" stroke-linecap="round" d="M10 17.14c1.496-4.737 9.79-14.369 31-15m-40 8c.917-2.842 6-8.621 19-9m-3 20c.579-1.895 3.79-5.748 12-6"></path>
				</svg>
				<svg xmlns="http://www.w3.org/2000/svg" width="42" height="23" fill="none" viewBox="0 0 42 23" class="shape shape-2 position-absolute">
					<path stroke="#fff" stroke-linecap="round" d="M32 6c-1.496 4.737-9.79 14.368-31 15m40-8c-.917 2.842-6 8.621-19 9m4-21c-.917 2.842-6 8.621-19 9"></path>
				</svg>
			</div>
							<div class="title f-iran-yekan text-center position-relative mxw-fit mx-auto">
					پیشنهاد											<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="position-absolute">
							<defs>
								<clipPath id="ppnk_badge_6ef6dd31_mb_clip" transform="translate(100.992 100.963)">
									<path d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z"></path>
								</clipPath>
							</defs>
							<foreignObject x="-100.992" y="-100.963" width="237.955" height="237.955">
								<div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(52px);clip-path:url(#ppnk_badge_6ef6dd31_mb_clip);height:100%;width:100%"></div>
							</foreignObject>
							<path opacity="0.42" d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z" fill="white"></path>
							<path d="M22.5 24C21.66 24 20.985 23.325 20.985 22.5C20.985 21.675 21.66 21 22.485 21C23.31 21 23.985 21.675 23.985 22.5C23.985 23.325 23.325 24 22.5 24Z" fill="white"></path>
							<path d="M13.515 15C12.675 15 12 14.325 12 13.5C12 12.675 12.675 12 13.5 12C14.325 12 15 12.675 15 13.5C15 14.325 14.34 15 13.515 15Z" fill="white"></path>
							<path d="M13.5 23.625C13.215 23.625 12.93 23.52 12.705 23.295C12.27 22.86 12.27 22.1399 12.705 21.7049L21.705 12.7049C22.14 12.2699 22.86 12.2699 23.295 12.7049C23.73 13.1399 23.73 13.86 23.295 14.295L14.295 23.295C14.07 23.52 13.785 23.625 13.5 23.625Z" fill="white"></path>
						</svg>
									</div>
										<div class="sub-title f-iran-yekan fw-normal text-center">تخفیفات روز</div>
										<a href="<?php echo esc_url( $shop_link ); ?>" class="btn btn-nk-white d-flex align-items-center justify-content-center fw-normal mx-auto" style="border-radius: 50rem;">
					<span class="d-none d-md-block">مشاهده بیشتر</span>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
					</div>
											</div>
																									<div class="swiper-slide">
												<div class="product-nk product-one-nk position-relative d-flex flex-column h-100">
							<div class="like-size d-flex align-items-center gap-2 justify-content-between">
													<button type="button" class="like wlm-button" aria-label="علاقه مندی" data-product-id="113" aria-pressed="false">
			<i class="icon-heart-8 lh-1" aria-hidden="true"></i>
		</button>
																		<div class="size d-flex align-items-center justify-content-center rounded-pill fw-normal f-plus-sans text-uppercase" title="256 گیگابایت">
							<span class="size-text">256 گیگابایت</span>
						</div>
									</div>
						<div class="image position-relative">
				<a href="<?php echo esc_url( $shop_link ); ?>" class="img d-block mx-auto mxw-fit">
					<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/89c0e7b3f400348a3add26684b7ece5084e4361f_1777115891-300x300-removebg-preview-1.png" ); ?>" alt="گوشی موبایل سامسونگ مدل Galaxy S25 FE دو سیم کارت" width="126" height="138" class="d-block w-auto">
				</a>
									<ul class="colors d-flex flex-column position-absolute start-0">
													<li>
								<a href="<?php echo esc_url( $shop_link ); ?>" class="rounded-circle d-block" aria-label="مشکی" title="مشکی" style="background-color: #000000;"></a>
							</li>
											</ul>
							</div>
			<h2 class="title fw-semibold f-iran-yekan line-clamp-2">
				<a href="<?php echo esc_url( $shop_link ); ?>">گوشی موبایل سامسونگ مدل Galaxy S25 FE دو...</a>
			</h2>
			<div class="price-rate d-flex align-items-center mt-auto">
				<div class="price">
								<div class="off d-flex align-items-center justify-content-between gap-1">
				<del class="fw-light f-iransans">175,000,000</del>
									<span class="d-flex align-items-center justify-content-center f-iran-yekan gap-1">
						7						<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
					</span>
							</div>
					<div class="price d-flex align-items-center gap-1">
			162,999,000			<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>		</div>
						</div>
									<div class="line flex-grow-1"></div>
					<div class="rate d-flex align-items-center gap-1 gap-md-2 f-iransans">
						5/4.0						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
							<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
							<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
						</svg>
					</div>
							</div>
		</div>
											</div>
																	<div class="swiper-slide">
												<div class="product-nk product-one-nk position-relative d-flex flex-column h-100">
							<div class="like-size d-flex align-items-center gap-2 justify-content-between">
													<button type="button" class="like wlm-button" aria-label="علاقه مندی" data-product-id="180" aria-pressed="false">
			<i class="icon-heart-8 lh-1" aria-hidden="true"></i>
		</button>
																		<div class="size d-flex align-items-center justify-content-center rounded-pill fw-normal f-plus-sans text-uppercase" title="256 گیگابایت">
							<span class="size-text">256 گیگابایت</span>
						</div>
									</div>
						<div class="image position-relative">
				<a href="<?php echo esc_url( $shop_link ); ?>" class="img d-block mx-auto mxw-fit">
					<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . "/ec57ce03f2a92834058f502174b1a2aeffc9a580_1759666637-300x300-removebg-preview-1.png" ); ?>" alt="گوشی موبایل اپل مدل iPhone 17 Pro Max ZAA" width="126" height="138" class="d-block w-auto">
				</a>
									<ul class="colors d-flex flex-column position-absolute start-0">
													<li>
								<a href="<?php echo esc_url( $shop_link ); ?>" class="rounded-circle d-block" aria-label="نقره ای" title="نقره ای" style="background-color: #c0c0c0;"></a>
							</li>
													<li>
								<a href="<?php echo esc_url( $shop_link ); ?>" class="rounded-circle d-block" aria-label="آبی تیره" title="آبی تیره" style="background-color: #00008b;"></a>
							</li>
													<li>
								<a href="<?php echo esc_url( $shop_link ); ?>" class="rounded-circle d-block" aria-label="نارنجی" title="نارنجی" style="background-color: #ffa500;"></a>
							</li>
											</ul>
							</div>
			<h2 class="title fw-semibold f-iran-yekan line-clamp-2">
				<a href="<?php echo esc_url( $shop_link ); ?>">گوشی موبایل اپل مدل iPhone 17 Pro Max...</a>
			</h2>
			<div class="price-rate d-flex align-items-center mt-auto">
				<div class="price">
								<div class="off d-flex align-items-center justify-content-between gap-1">
				<del class="fw-light f-iransans">419,999,000</del>
									<span class="d-flex align-items-center justify-content-center f-iran-yekan gap-1">
						2						<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
					</span>
							</div>
					<div class="price d-flex align-items-center gap-1">
			409,999,000			<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>		</div>
						</div>
									<div class="line flex-grow-1"></div>
					<div class="rate d-flex align-items-center gap-1 gap-md-2 f-iransans">
						5/4.0						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
							<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
							<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
						</svg>
					</div>
							</div>
		</div>
											</div>
															</div>
						</div>
					</div>
				</div>
			</div>
		</section>
						</div>
				</div>
				</div>