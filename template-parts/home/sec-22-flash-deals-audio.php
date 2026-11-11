<?php
/**
 * Section 22: Daily Deals Component (Purple Theme) - 100% Exact Match to Demo 43 (popularproductsnkkaveh)
 * Reference: Demo 43 Section 21 (.popular-products-nk-5e78b899)
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
$sale_link  = add_query_arg( 'on_sale', '1', $shop_link );

if ( ! function_exists( 'negar_get_matching_product_info' ) ) {
	function negar_get_matching_product_info( $slug_fragment, $fallback_url ) {
		$prods = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			's'              => $slug_fragment,
		) );
		if ( ! empty( $prods ) ) {
			return array(
				'id'  => $prods[0]->ID,
				'url' => get_permalink( $prods[0]->ID ),
			);
		}
		return array(
			'id'  => 0,
			'url' => $fallback_url,
		);
	}
}

// 5 Demo 43 Products for Purple Daily Deals
$products_sec22 = array(
	array(
		'title'         => 'هدفون بلوتوثی انکر مدل Liberty 4 Pro',
		'short_title'   => 'هدفون بلوتوثی انکر مدل Liberty 4 Pro',
		'image'         => $kaveh_base . '/7fc114c1a3c12afc0db1a26f1117e81c5a76a1c6_1786571883-removebg-preview-300x300.png',
		'size'          => '580 میلی‌آمپر‌ساعت',
		'rating'        => '5/4.0',
		'regular_price' => '',
		'price'         => '13,980,000',
		'offer'         => '',
		'colors'        => array( '#40e0d0', '#20c997', '#008b8b', '#000000', '#009b77', '#1e73be', '#81d742', '#ffffff' ),
		'keyword'       => 'Liberty 4',
	),
	array(
		'title'         => 'گوشی موبایل اپل مدل iPhone 17 Pro Max ZAA دو سیم کارت',
		'short_title'   => 'گوشی موبایل اپل مدل iPhone 17 Pro Max...',
		'image'         => $kaveh_base . '/ec57ce03f2a92834058f502174b1a2aeffc9a580_1759666637-300x300-removebg-preview-1.png',
		'size'          => '256 گیگابایت',
		'rating'        => '5/4.0',
		'regular_price' => '419,999,000',
		'price'         => '409,999,000',
		'offer'         => '2',
		'colors'        => array( '#c0c0c0', '#00008b', '#ffa500' ),
		'keyword'       => 'iPhone 17',
	),
	array(
		'title'         => 'گوشی موبایل سامسونگ مدل Galaxy S25 FE دو سیم کارت',
		'short_title'   => 'گوشی موبایل سامسونگ مدل Galaxy S25 FE دو...',
		'image'         => $kaveh_base . '/89c0e7b3f400348a3add26684b7ece5084e4361f_1777115891-300x300-removebg-preview-1.png',
		'size'          => '256 گیگابایت',
		'rating'        => '5/4.0',
		'regular_price' => '175,000,000',
		'price'         => '162,999,000',
		'offer'         => '7',
		'colors'        => array( '#000000' ),
		'keyword'       => 'Galaxy S25',
	),
	array(
		'title'         => 'ساعت هوشمند اپل مدل SE Aluminum Case 44mm',
		'short_title'   => 'ساعت هوشمند اپل مدل SE Aluminum Case 44mm',
		'image'         => $kaveh_base . '/15bbdc3cfde33fc112622d14bd61152cd86e9287_1738409004-300x300-removebg-preview-1.png',
		'size'          => '32 گیگابایت',
		'rating'        => '5/4.0',
		'regular_price' => '',
		'price'         => '44,490,000',
		'offer'         => '',
		'colors'        => array( '#f5f0e6', '#000000' ),
		'keyword'       => 'Watch SE',
	),
	array(
		'title'         => 'ساعت هوشمند 46 میلی متری اپل مدل Series 10',
		'short_title'   => 'ساعت هوشمند 46 میلی متری اپل مدل Series...',
		'image'         => $kaveh_base . '/1cb72b186a7f24502e1bd066af4e2382a8caf099_1764429370-300x300-removebg-preview-1.png',
		'size'          => '46 میلی‌متر',
		'rating'        => '5/4.5',
		'regular_price' => '',
		'price'         => '68,800,000',
		'offer'         => '',
		'colors'        => array( '#808080', '#b76e79', '#000000', '#c0c0c0' ),
		'keyword'       => 'Series 10',
	),
);
?>

<div class="elementor-element elementor-element-6255df64 e-con-full e-flex e-con e-parent" data-id="6255df64" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-5e78b899 elementor-widget elementor-widget-popularproductsnkkaveh" data-id="5e78b899" data-element_type="widget" data-e-type="widget" data-widget_type="popularproductsnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="popular-products-nk popular-products-nk-5e78b899 my-10 lg:my-16">
				<div class="container">
					<div class="popular-products-nk__wrapper position-relative d-flex align-items-center overflow-hidden">
						
						<!-- Background decorative SVGs -->
						<div class="line position-absolute top-0 end-0 w-100 h-100"></div>
						<div class="shape position-absolute top-0 end-0 w-100 h-100"></div>
						<div class="border-bg position-absolute bottom-0 d-none d-lg-block"></div>

						<!-- Right Banner Panel (Apple Watch Purple Promo) -->
						<div class="popular-products-nk__right position-relative">
							<div class="image mx-auto position-relative">
								<img loading="lazy" decoding="async" src="<?php echo esc_url( $kaveh_base . '/popular-products.png' ); ?>" alt="پیشنهاد" width="294" height="265" class="d-block">
								<svg xmlns="http://www.w3.org/2000/svg" width="42" height="22" fill="none" viewBox="0 0 42 22" class="shape shape-1 position-absolute">
									<path stroke="#fff" stroke-linecap="round" d="M10 17.14c1.496-4.737 9.79-14.369 31-15m-40 8c.917-2.842 6-8.621 19-9m-3 20c.579-1.895 3.79-5.748 12-6"/>
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" width="42" height="23" fill="none" viewBox="0 0 42 23" class="shape shape-2 position-absolute">
									<path stroke="#fff" stroke-linecap="round" d="M32 6c-1.496 4.737-9.79 14.368-31 15m40-8c-.917 2.842-6 8.621-19 9m4-21c-.917 2.842-6 8.621-19 9"/>
								</svg>
							</div>
							<div class="title f-iran-yekan text-center position-relative mxw-fit mx-auto">
								<?php esc_html_e( 'پیشنهاد', 'negar-shop' ); ?>
								<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="position-absolute">
									<defs>
										<clipPath id="ppnk_badge_5e78b899_clip" transform="translate(100.992 100.963)">
											<path d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z"></path>
										</clipPath>
									</defs>
									<foreignObject x="-100.992" y="-100.963" width="237.955" height="237.955">
										<div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(52px);clip-path:url(#ppnk_badge_5e78b899_clip);height:100%;width:100%"></div>
									</foreignObject>
									<path opacity="0.42" d="M5.98499 21.9899L3.70501 19.71C2.77501 18.78 2.77501 17.25 3.70501 16.32L5.98499 14.0399C6.37499 13.6499 6.68999 12.8849 6.68999 12.3449V9.11999C6.68999 7.79999 7.77 6.71993 9.09 6.71993H12.315C12.855 6.71993 13.62 6.40497 14.01 6.01497L16.29 3.73495C17.22 2.80495 18.75 2.80495 19.68 3.73495L21.96 6.01497C22.35 6.40497 23.115 6.71993 23.655 6.71993H26.88C28.2 6.71993 29.28 7.79999 29.28 9.11999V12.3449C29.28 12.8849 29.595 13.6499 29.985 14.0399L32.265 16.32C33.195 17.25 33.195 18.78 32.265 19.71L29.985 21.9899C29.595 22.3799 29.28 23.1449 29.28 23.6849V26.91C29.28 28.23 28.2 29.3099 26.88 29.3099H23.655C23.115 29.3099 22.35 29.625 21.96 30.015L19.68 32.295C18.75 33.225 17.22 33.225 16.29 32.295L14.01 30.015C13.62 29.625 12.855 29.3099 12.315 29.3099H9.09C7.77 29.3099 6.68999 28.23 6.68999 26.91V23.6849C6.68999 23.1299 6.37499 22.3649 5.98499 21.9899Z" fill="#ffffff"></path>
									<path d="M22.5 24C21.66 24 20.985 23.325 20.985 22.5C20.985 21.675 21.66 21 22.485 21C23.31 21 23.985 21.675 23.985 22.5C23.985 23.325 23.325 24 22.5 24Z" fill="#ffffff"></path>
									<path d="M13.515 15C12.675 15 12 14.325 12 13.5C12 12.675 12.675 12 13.5 12C14.325 12 15 12.675 15 13.5C15 14.325 14.34 15 13.515 15Z" fill="#ffffff"></path>
									<path d="M13.5 23.625C13.215 23.625 12.93 23.52 12.705 23.295C12.27 22.86 12.27 22.1399 12.705 21.7049L21.705 12.7049C22.14 12.2699 22.86 12.2699 23.295 12.7049C23.73 13.1399 23.73 13.86 23.295 14.295L14.295 23.295C14.07 23.52 13.785 23.625 13.5 23.625Z" fill="#ffffff"></path>
								</svg>
							</div>
							<div class="sub-title f-iran-yekan fw-normal text-center">
								<?php esc_html_e( 'تخفیفات روز', 'negar-shop' ); ?>
							</div>
							<a href="<?php echo esc_url( $sale_link ); ?>" class="btn btn-nk-white d-flex align-items-center justify-content-center fw-normal mx-auto" style="border-radius: 50rem;">
								<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
						</div>

						<!-- Left Products Carousel -->
						<div class="popular-products-nk__left position-relative">
							<div class="swiper swiper-products-popular swiper-visible swiper-visibled-mobile overflow-hidden" data-swiper='{"autoHeight":false,"watchSlidesProgress":true,"breakpoints":{"320":{"slidesPerView":1.5,"spaceBetween":10},"768":{"slidesPerView":2.2,"spaceBetween":12},"992":{"slidesPerView":2.8,"spaceBetween":14},"1200":{"slidesPerView":3.2,"spaceBetween":14}}}' data-kaveh-swiper-mounted="1">
								<div class="swiper-wrapper">
									<?php foreach ( $products_sec22 as $idx => $prod ) : 
										$match = negar_get_matching_product_info( $prod['keyword'], $shop_link );
										if ( ! $match['id'] ) {
											continue;
										}
										$prod_url = $match['url'];
										$prod_id  = $match['id'];
									?>
									<div class="swiper-slide">
										<div class="product-nk product-one-nk position-relative d-flex flex-column h-100">
											
											<!-- Top: Wishlist & Size -->
											<div class="like-size d-flex align-items-center gap-2 justify-content-between">
												<button type="button" class="like wlm-button" aria-label="<?php esc_attr_e( 'علاقه‌مندی', 'negar-shop' ); ?>" data-product-id="<?php echo esc_attr( $prod_id ); ?>" aria-pressed="false">
													<i class="icon icon-heart-8 lh-1" aria-hidden="true"></i>
												</button>
												<?php if ( ! empty( $prod['size'] ) ) : ?>
												<div class="size d-flex align-items-center justify-content-center rounded-pill fw-normal f-plus-sans text-uppercase" title="<?php echo esc_attr( $prod['size'] ); ?>">
													<span class="size-text"><?php echo esc_html( $prod['size'] ); ?></span>
												</div>
												<?php endif; ?>
											</div>

											<!-- Image & Colors -->
											<div class="image position-relative">
												<a href="<?php echo esc_url( $prod_url ); ?>" class="img d-block mx-auto mxw-fit">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $prod['image'] ); ?>" alt="<?php echo esc_attr( $prod['title'] ); ?>" width="126" height="138" class="d-block w-auto">
												</a>
												<?php if ( ! empty( $prod['colors'] ) ) : ?>
												<ul class="colors d-flex flex-column position-absolute start-0">
													<?php foreach ( $prod['colors'] as $c ) : 
														$c_val = is_array( $c ) ? $c['color'] : $c;
													?>
													<li>
														<a href="<?php echo esc_url( $prod_url ); ?>" class="rounded-circle d-block" style="background-color: <?php echo esc_attr( $c_val ); ?>;"></a>
													</li>
													<?php endforeach; ?>
												</ul>
												<?php endif; ?>
											</div>

											<!-- Title -->
											<h2 class="title fw-semibold f-iran-yekan line-clamp-2">
												<a href="<?php echo esc_url( $prod_url ); ?>"><?php echo esc_html( $prod['short_title'] ); ?></a>
											</h2>

											<!-- Price & Rating -->
											<div class="price-rate d-flex align-items-center mt-auto">
												<div class="price">
													<?php if ( ! empty( $prod['regular_price'] ) ) : ?>
													<div class="off d-flex align-items-center justify-content-between gap-1">
														<del class="fw-light f-iransans"><?php echo esc_html( $prod['regular_price'] ); ?></del>
														<?php if ( ! empty( $prod['offer'] ) ) : ?>
														<span class="d-flex align-items-center justify-content-center f-iran-yekan gap-1">
															<?php echo esc_html( $prod['offer'] ); ?><i class="icon-discount-4 lh-1" aria-hidden="true"></i>
														</span>
														<?php endif; ?>
													</div>
													<?php endif; ?>
													<div class="price d-flex align-items-center gap-1">
														<?php echo esc_html( $prod['price'] ); ?>
														<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
													</div>
												</div>
												<div class="line flex-grow-1"></div>
												<div class="rate d-flex align-items-center gap-1 gap-md-2 f-iransans">
													<?php echo esc_html( $prod['rating'] ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
													</svg>
												</div>
											</div>

										</div>
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

<script>
(function() {
	function initSec22PopularSwiper() {
		if (typeof Swiper === 'undefined') {
			setTimeout(initSec22PopularSwiper, 100);
			return;
		}
		var swiperEl = document.querySelector('.popular-products-nk-5e78b899 .swiper-products-popular');
		if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
			new Swiper(swiperEl, {
				watchSlidesProgress: true,
				slidesPerView: 1.5,
				spaceBetween: 10,
				breakpoints: {
					320: { slidesPerView: 1.5, spaceBetween: 10 },
					768: { slidesPerView: 2.2, spaceBetween: 12 },
					992: { slidesPerView: 2.8, spaceBetween: 14 },
					1200: { slidesPerView: 3.2, spaceBetween: 14 }
				}
			});
		}
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSec22PopularSwiper);
	} else {
		initSec22PopularSwiper();
	}
})();
</script>
