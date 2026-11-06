<?php
/**
 * Section 24: Best-Selling Smartwatches & Wearables Carousel (HOME-24)
 * Authentic 1:1 Implementation matching Kaveh Demo 43 Widget (productscarouselsixteennkkaveh / top-selling-products-two-nk)
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

// 1. Discount Showcase Items (Left Column Red Card - Smartwatch/Wearables)
$discount_items_sec24 = array(
	array(
		'title'         => 'ساعت هوشمند سامسونگ مدل Galaxy Watch Ultra 47mm',
		'short_title'   => 'ساعت هوشمند سامسونگ مدل Galaxy Watch Ultra...',
		'image'         => $kaveh_base . '/3c0f8ff8a12c67f061136e8fafd65eb6f6c56d2e_1786985097-300x300-removebg-preview-1.png',
		'discount'      => '5',
		'rating'        => '5/4.9',
		'comments'      => '11',
		'regular_price' => '32,500,000',
		'sale_price'    => '30,875,000',
		'keyword'       => 'Galaxy Watch',
	),
	array(
		'title'         => 'ساعت هوشمند اپل مدل Apple Watch Series 9 آلومینیوم 45mm',
		'short_title'   => 'ساعت هوشمند اپل مدل Watch Series 9...',
		'image'         => $kaveh_base . '/3c0f8ff8a12c67f061136e8fafd65eb6f6c56d2e_1787055103-300x300-removebg-preview-1.png',
		'discount'      => '3',
		'rating'        => '5/4.7',
		'comments'      => '7',
		'regular_price' => '24,000,000',
		'sale_price'    => '23,280,000',
		'keyword'       => 'Series 9',
	),
);

// 2. Carousel Products (Right Column)
$carousel_products_sec24 = array(
	array(
		'title'       => 'ساعت هوشمند امیزفیت مدل Balance با سنسور بیومتریک BioTracker',
		'image'       => $kaveh_base . '/3eef521897412a152c5b42551b623c91c41d90c0_1786388224-300x300-removebg-preview-1.png',
		'rating'      => '5/4.5',
		'price'       => '11,400,000',
		'regular_price'=> '12,200,000',
		'offer'       => '٪ 6',
		'colors'      => array( '#212529', '#6c757d' ),
		'keyword'     => 'Amazfit',
	),
	array(
		'title'       => 'مچ‌بند هوشمند شیائومی مدل Smart Band 8 Active',
		'image'       => $kaveh_base . '/26eb5a74fbf76467080d4a69a6a0dd6f9c5f1008_1786454574-300x300-removebg-preview-1.png',
		'rating'      => '5/4.6',
		'price'       => '1,290,000',
		'regular_price'=> '',
		'offer'       => '',
		'colors'      => array( '#000000', '#f8f9fa' ),
		'keyword'     => 'Band 8',
	),
	array(
		'title'       => 'ساعت هوشمند گارمین مدل Forerunner 265 نسخه مخصوص دوندگان',
		'image'       => $kaveh_base . '/6276e4189055b9e96d2779c652b748384f11a3a2_1786388736-300x300-removebg-preview-1.png',
		'rating'      => '5/4.9',
		'price'       => '29,800,000',
		'regular_price'=> '31,500,000',
		'offer'       => '٪ 5',
		'colors'      => array( '#0f172a', '#e11d48' ),
		'keyword'     => 'Garmin',
	),
	array(
		'title'       => 'ساعت هوشمند هایلو مدل Solar Plus RT3 با قابلیت مکالمه',
		'image'       => $kaveh_base . '/1149308650aa177c213e4175a23076419198dabf_1786296597-300x300-removebg-preview-1.png',
		'rating'      => '5/4.2',
		'price'       => '1,890,000',
		'regular_price'=> '',
		'offer'       => '',
		'colors'      => array( '#18181b', '#71717a' ),
		'keyword'     => 'Haylou',
	),
	array(
		'title'       => 'ساعت هوشمند کیسلکت مدل Kr Pro نسخه مجهز به صفحه نمایش Ultra AMOLED',
		'image'       => $kaveh_base . '/128bfd71ad4017050659832b191c846cd050c5c1_1763052371-300x300-removebg-preview-1.png',
		'rating'      => '5/4.3',
		'price'       => '2,950,000',
		'regular_price'=> '3,200,000',
		'offer'       => '٪ 8',
		'colors'      => array( '#0284c7', '#334155' ),
		'keyword'     => 'Kieslect',
	),
);
?>

<div class="elementor-element elementor-element-17ea5448 e-con-full e-flex e-con e-parent e-lazyloaded my-8" data-id="17ea5448" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-17ea5448-w elementor-widget elementor-widget-productscarouselsixteennkkaveh w-100" data-id="17ea5448-w" data-element_type="widget" data-widget_type="productscarouselsixteennkkaveh.default">
		<div class="elementor-widget-container">
			<section class="top-selling-products-two-nk top-selling-products-two-nk-17ea5448">
				<div class="container">
					
					<!-- Signature Section Heading -->
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold"><?php esc_html_e( 'پرفروش ترین', 'negar-shop' ); ?></span> <?php esc_html_e( 'ساعت هوشمند و گجت پوشیدنی', 'negar-shop' ); ?>
						</div>
						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block">
							<?php esc_html_e( 'تجربه ای نوین از برترین برندهای روز بازار', 'negar-shop' ); ?>
						</div>
						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
							<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<div class="row flex-row-reverse flex-md-row align-items-stretch">
						
						<!-- Carousel Column on Right (col-12 col-md-7 col-lg-8) -->
						<div class="col-12 col-md-7 col-lg-8">
							<div class="swiper swiper-top-selling-products-two-nk overflow-hidden w-100 h-100" data-swiper='{"autoHeight":false,"watchSlidesProgress":true,"breakpoints":{"320":{"slidesPerView":1.1,"spaceBetween":12},"576":{"slidesPerView":1.8,"spaceBetween":14},"992":{"slidesPerView":2.2,"spaceBetween":16},"1200":{"slidesPerView":2.5,"spaceBetween":20}}}' data-kaveh-swiper-mounted="1">
								<div class="swiper-wrapper">
									<?php foreach ( $carousel_products_sec24 as $idx => $prod ) : 
										$match = negar_get_matching_product_info( $prod['keyword'], $shop_url );
										$prod_url = $match['url'];
										$prod_id  = $match['id'] ? $match['id'] : ( 300 + $idx );
									?>
									<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $idx + 1 ) . ' / ' . count( $carousel_products_sec24 ) ); ?>">
										<div class="product-cart-thirty-three-nk h-100">
											<div class="product-nk product-six-nk d-flex flex-column h-100 position-relative">
												
												<?php if ( ! empty( $prod['offer'] ) ) : ?>
												<div class="offer position-absolute top-0 d-flex align-items-center justify-content-center f-iran-yekan text-white gap-1 gap-md-2">
													<?php echo esc_html( $prod['offer'] ); ?>
													<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
												</div>
												<?php endif; ?>

												<div class="product-six-nk__top d-flex align-items-center">
													<div class="rate d-flex align-items-center position-relative f-iransans ms-auto">
														<i class="icon icon-star-6 lh-1" aria-hidden="true"></i>
														<span class="lh-1"><?php echo esc_html( $prod['rating'] ); ?></span>
													</div>
													<button type="button" class="like wlm-button" aria-label="علاقه‌مندی" data-product-id="<?php echo esc_attr( $prod_id ); ?>" aria-pressed="false">
														<i class="icon-heart-8 lh-1" aria-hidden="true"></i>
													</button>
												</div>

												<div class="image position-relative">
													<a href="<?php echo esc_url( $prod_url ); ?>" class="img d-block mx-auto mxw-fit">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $prod['image'] ); ?>" alt="<?php echo esc_attr( $prod['title'] ); ?>" width="162" height="177" class="d-block w-auto">
													</a>
													<?php if ( ! empty( $prod['colors'] ) ) : ?>
													<ul class="colors d-flex flex-column position-absolute end-0">
														<?php foreach ( $prod['colors'] as $color ) : ?>
														<li class="rounded-circle" style="background-color: <?php echo esc_attr( $color ); ?>;"></li>
														<?php endforeach; ?>
													</ul>
													<?php endif; ?>
												</div>

												<h2 class="title fw-semibold f-iran-yekan line-clamp-2">
													<a href="<?php echo esc_url( $prod_url ); ?>"><?php echo esc_html( $prod['title'] ); ?></a>
												</h2>

												<div class="product-six-nk__footer d-flex align-items-end justify-content-between gap-2">
													<div class="add-to-cart buy-now d-flex align-items-center align-self-center">
														<button type="button" class="kaveh-variation-modal-trigger buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0" data-product_id="<?php echo esc_attr( $prod_id ); ?>" aria-label="افزودن به سبد خرید">
															<i class="icon icon-bag-6 lh-1" aria-hidden="true"></i>
														</button>
													</div>
													<div class="price">
														<?php if ( ! empty( $prod['regular_price'] ) ) : ?>
														<div class="off">
															<del class="fw-light f-iransans"><?php echo esc_html( $prod['regular_price'] ); ?></del>
														</div>
														<?php endif; ?>
														<div class="price d-flex align-items-center gap-1">
															<?php echo esc_html( $prod['price'] ); ?>
															<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>

						<!-- Red Discount Card Column on Left (col-12 col-md-5 col-lg-4) -->
						<div class="col-12 col-md-5 col-lg-4">
							<div class="top-selling-products-two-nk__discount position-relative h-100">
								<div class="swiper swiper-top-selling-products-two-nk__discount overflow-visible" data-swiper='{"autoHeight":true,"slidesPerView":1,"spaceBetween":0,"effect":"fade","fadeEffect":{"crossFade":true},"pagination":{"el":".elementor-element-17ea5448 .top-selling-products-two-nk__discount .swiper-pagination","clickable":true}}' data-kaveh-swiper-mounted="1">
									<div class="swiper-wrapper">
										<?php foreach ( $discount_items_sec24 as $d_idx => $d_item ) : 
											$d_match = negar_get_matching_product_info( $d_item['keyword'], $shop_url );
											$d_url = $d_match['url'];
										?>
										<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $d_idx + 1 ) . ' / ' . count( $discount_items_sec24 ) ); ?>">
											<div class="top-selling-products-two-nk__discount__item position-relative">
												<div class="off f-iran-yekan position-absolute">٪ <?php echo esc_html( $d_item['discount'] ); ?></div>
												<div class="image position-relative mxw-fit mx-auto">
													<div class="cir cir-1 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-2 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-3 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<a href="<?php echo esc_url( $d_url ); ?>" class="d-block">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $d_item['image'] ); ?>" alt="<?php echo esc_attr( $d_item['title'] ); ?>" width="175" height="175" class="d-block w-auto">
													</a>
												</div>
												<h2 class="title f-iran-yekan fw-semibold text-truncate">
													<a href="<?php echo esc_url( $d_url ); ?>"><?php echo esc_html( $d_item['short_title'] ); ?></a>
												</h2>
												<div class="comments-rate d-flex align-items-center">
													<span class="rate f-iransans fw-normal position-relative"><?php echo esc_html( $d_item['rating'] ); ?></span>
													<span class="line flex-grow-1"></span>
													<div class="comments f-iransans fw-normal d-flex align-items-center gap-1">
														<span><?php echo esc_html( $d_item['comments'] ); ?> نظر</span>
														<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 16 16" aria-hidden="true">
															<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M14.5 9.5a5.002 5.002 0 0 1-5 5H6l-3.5 2V14A5.002 5.002 0 0 1 1.5 9.5c0-2.76 2.24-5 5-5h3c2.76 0 5 2.24 5 5Z"></path>
															<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
														</svg>
													</div>
												</div>
												<div class="price d-flex align-items-center gap-2">
													<?php if ( ! empty( $d_item['regular_price'] ) ) : ?>
													<del class="f-iransans fw-normal"><?php echo esc_html( $d_item['regular_price'] ); ?></del>
													<?php endif; ?>
													<div class="price ms-auto f-iransans fw-semibold d-flex align-items-center gap-1">
														<span><?php echo esc_html( $d_item['sale_price'] ); ?></span>
														<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
													</div>
												</div>
												<a href="<?php echo esc_url( $d_url ); ?>" class="btn-more d-flex align-items-center justify-content-center position-absolute bottom-0 end-50" aria-label="مشاهده محصول">
													<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
												</a>
											</div>
										</div>
										<?php endforeach; ?>
									</div>
									<div class="swiper-pagination d-flex align-items-center justify-content-end position-absolute swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"></div>
									<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
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
	function initSellingProductsTwoSwiperSec24() {
		if (typeof Swiper === 'undefined') {
			setTimeout(initSellingProductsTwoSwiperSec24, 100);
			return;
		}

		// Initialize Main Product Carousel
		var mainSwiperEl = document.querySelector('.elementor-element-17ea5448 .swiper-top-selling-products-two-nk');
		if (mainSwiperEl && !mainSwiperEl.classList.contains('swiper-initialized')) {
			new Swiper(mainSwiperEl, {
				slidesPerView: 1.1,
				spaceBetween: 12,
				watchSlidesProgress: true,
				breakpoints: {
					320: { slidesPerView: 1.1, spaceBetween: 12 },
					576: { slidesPerView: 1.8, spaceBetween: 14 },
					992: { slidesPerView: 2.2, spaceBetween: 16 },
					1200: { slidesPerView: 2.5, spaceBetween: 20 }
				}
			});
		}

		// Initialize Discount Showcase Swiper
		var discountSwiperEl = document.querySelector('.elementor-element-17ea5448 .swiper-top-selling-products-two-nk__discount');
		if (discountSwiperEl && !discountSwiperEl.classList.contains('swiper-initialized')) {
			new Swiper(discountSwiperEl, {
				slidesPerView: 1,
				spaceBetween: 0,
				effect: 'fade',
				fadeEffect: { crossFade: true },
				pagination: {
					el: '.elementor-element-17ea5448 .top-selling-products-two-nk__discount .swiper-pagination',
					clickable: true
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSellingProductsTwoSwiperSec24);
	} else {
		initSellingProductsTwoSwiperSec24();
	}
})();
</script>
