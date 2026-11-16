<?php
/**
 * Section 07: Best-Selling Smartwatches & Mobile Carousel (HOME-07)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (productscarouselsixteennkkaveh / top-selling-products-two-nk)
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

// Only render cards for real WooCommerce products. Returning the shop URL for
// an unmatched demo item sent customers to the wrong destination.
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
			'url' => '',
		);
	}
}

// 1. Discount Showcase Items (Left Column Red Card)
$discount_items = array(
	array(
		'title'         => 'اسپرسوساز مباشی مدل ECM2026 فشار بخار ۲۰ بار پمپ ایتالیا',
		'short_title'   => 'اسپرسوساز مباشی مدل ECM2026...',
		'image'         => $kaveh_base . '/espresso-discount-card.webp',
		'discount'      => '15',
		'rating'        => '5/4.8',
		'comments'      => '14',
		'regular_price' => '5,200,000',
		'sale_price'    => '4,420,000',
		'keyword'       => 'ECM2026',
	),
	array(
		'title'         => 'چای‌ساز روهمی بوش مدل BS-1111 با قوری پیرکس',
		'short_title'   => 'چای‌ساز روهمی بوش مدل BS-1111...',
		'image'         => $kaveh_base . '/tea-maker-discount-card.webp',
		'discount'      => '10',
		'rating'        => '5/4.6',
		'comments'      => '8',
		'regular_price' => '3,800,000',
		'sale_price'    => '3,420,000',
		'keyword'       => 'BS-1111',
	),
);

// 2. Main Carousel Products (Right Column)
$carousel_products = array(
	array(
		'title'         => 'اسپرسوساز مباشی مدل ECM2026 فشار بخار ۲۰ بار پمپ ایتالیا',
		'short_title'   => 'اسپرسوساز مباشی مدل ECM2026',
		'image'         => $kaveh_base . '/espresso-discount-card.webp',
		'offer'         => '15',
		'rating'        => '5/4.8',
		'regular_price' => '5,200,000',
		'price'         => '4,420,000',
		'colors'        => array(
			array( 'name' => 'مشکی', 'color' => '#000000' ),
			array( 'name' => 'استیل', 'color' => '#c0c0c0' ),
			array( 'name' => 'قرمز', 'color' => '#b22222' ),
		),
		'keyword'       => 'ECM2026',
	),
	array(
		'title'         => 'چای‌ساز روهمی بوش مدل BS-1111 با قوری پیرکس',
		'short_title'   => 'چای‌ساز روهمی بوش مدل BS-1111',
		'image'         => $kaveh_base . '/tea-maker-discount-card.webp',
		'offer'         => '10',
		'rating'        => '5/4.6',
		'regular_price' => '3,800,000',
		'price'         => '3,420,000',
		'colors'        => array(
			array( 'name' => 'مشکی استیل', 'color' => '#2b2b2b' ),
			array( 'name' => 'سفید', 'color' => '#ffffff' ),
		),
		'keyword'       => 'BS-1111',
	),
	array(
		'title'         => 'آسیاب قهوه خانگی دلونگی مدل KG79 دندانه‌ای برقی',
		'short_title'   => 'آسیاب قهوه خانگی دلونگی KG79',
		'image'         => $upload_dir['baseurl'] . '/2026/11/coffee-grinders.webp',
		'offer'         => '8',
		'rating'        => '5/4.7',
		'regular_price' => '4,100,000',
		'price'         => '3,770,000',
		'colors'        => array(
			array( 'name' => 'مشکی مات', 'color' => '#1a1a1a' ),
		),
		'keyword'       => 'KG79',
	),
	array(
		'title'         => 'توستر نان دو شیار فیلیپس مدل HD2581 با ۸ درجه برشته‌کنندگی',
		'short_title'   => 'توستر نان فیلیپس مدل HD2581',
		'image'         => $upload_dir['baseurl'] . '/2026/11/bread-toasters.webp',
		'offer'         => '12',
		'rating'        => '5/4.5',
		'regular_price' => '2,350,000',
		'price'         => '2,060,000',
		'colors'        => array(
			array( 'name' => 'سفید', 'color' => '#ffffff' ),
			array( 'name' => 'مشکی', 'color' => '#000000' ),
		),
		'keyword'       => 'HD2581',
	),
	array(
		'title'         => 'آب‌سردکن و آب‌جوش‌کن ایستگاهی ایستکول مدل TM-SW438',
		'short_title'   => 'آب‌سردکن ایستکول مدل TM-SW438',
		'image'         => $upload_dir['baseurl'] . '/2026/11/water-dispensers.webp',
		'offer'         => '',
		'rating'        => '5/4.4',
		'regular_price' => '',
		'price'         => '11,800,000',
		'colors'        => array(
			array( 'name' => 'سفید', 'color' => '#ffffff' ),
			array( 'name' => 'نقره‌ای', 'color' => '#c0c0c0' ),
		),
		'keyword'       => 'TM-SW438',
	),
	array(
		'title'         => 'سرخ‌کن بدون روغن فیلیپس مدل HD9280 سایز XL دیجیتال',
		'short_title'   => 'سرخ‌کن بدون روغن فیلیپس HD9280',
		'image'         => $upload_dir['baseurl'] . '/2026/11/air-fryers.webp',
		'offer'         => '14',
		'rating'        => '5/4.9',
		'regular_price' => '8,900,000',
		'price'         => '7,650,000',
		'colors'        => array(
			array( 'name' => 'مشکی براق', 'color' => '#111111' ),
		),
		'keyword'       => 'HD9280',
	),
	array(
		'title'         => 'ساندویچ‌ساز و گریل ۴ خانه کنوود مدل SM650 با صفحات متحرک',
		'short_title'   => 'ساندویچ‌ساز و گریل کنوود SM650',
		'image'         => $upload_dir['baseurl'] . '/2026/11/electric-grills-sandwich-makers.webp',
		'offer'         => '',
		'rating'        => '5/4.6',
		'regular_price' => '',
		'price'         => '4,500,000',
		'colors'        => array(
			array( 'name' => 'نقره‌ای مشکی', 'color' => '#808080' ),
		),
		'keyword'       => 'SM650',
	),
);
?>

<div class="elementor-element elementor-element-145e3133 e-con-full e-flex e-con e-parent e-lazyloaded my-8" data-id="145e3133" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-6b1a7cb3 elementor-widget elementor-widget-productscarouselsixteennkkaveh w-100" data-id="6b1a7cb3" data-element_type="widget" data-e-type="widget" data-widget_type="productscarouselsixteennkkaveh.default">
		<div class="elementor-widget-container">
			<section class="top-selling-products-two-nk top-selling-products-two-nk-6b1a7cb3">
				<div class="container">
					
					<!-- Signature Section Heading -->
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold">پرفروش ترین</span> نوشیدنی‌ساز و قهوه‌ساز
						</div>
						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block">
							انواع اسپرسوساز، چای‌ساز، قهوه‌ساز و کتری برقی
						</div>
						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
							مشاهده بیشتر
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<div class="row flex-row-reverse flex-md-row align-items-stretch">
						
						<!-- Carousel Column on Right (col-6 col-sm-8 col-lg-9) -->
						<div class="col-6 col-sm-8 col-lg-9">
							<div class="swiper swiper-top-selling-products-two-nk overflow-hidden w-100 h-100" data-swiper='{"autoHeight":false,"watchSlidesProgress":true,"breakpoints":{"320":{"slidesPerView":1,"spaceBetween":16},"576":{"slidesPerView":2,"spaceBetween":12},"992":{"slidesPerView":2.7,"spaceBetween":16},"1200":{"slidesPerView":3,"spaceBetween":24}}}' data-kaveh-swiper-mounted="1">
								<div class="swiper-wrapper">
									<?php foreach ( $carousel_products as $idx => $prod ) : 
										$match = negar_get_matching_product_info( $prod['keyword'], $shop_url );
										if ( ! $match['id'] ) {
											continue;
										}
										$prod_url = $match['url'];
										$prod_id  = $match['id'];
									?>
									<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $idx + 1 ) . ' / ' . count( $carousel_products ) ); ?>">
										<div class="product-cart-thirty-three-nk h-100">
											<div class="product-nk product-six-nk d-flex flex-column h-100 position-relative">
												
												<?php if ( ! empty( $prod['offer'] ) ) : ?>
												<div class="offer position-absolute top-0 d-flex align-items-center justify-content-center f-iran-yekan text-white gap-1 gap-md-2">
													<?php echo esc_html( $prod['offer'] ); ?>
													<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
												</div>
												<?php endif; ?>

												<div class="rate d-flex align-items-center gap-1 gap-md-2 position-absolute">
													<?php echo esc_html( $prod['rating'] ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
													</svg>
												</div>

												<div class="image position-relative">
													<?php if ( ! empty( $prod['colors'] ) ) : ?>
													<ul class="colors d-flex flex-column position-absolute">
														<?php foreach ( $prod['colors'] as $c ) : ?>
														<li class="rounded-circle" style="background-color: <?php echo esc_attr( $c['color'] ); ?>;" title="<?php echo esc_attr( $c['name'] ); ?>"></li>
														<?php endforeach; ?>
													</ul>
													<?php endif; ?>
													<a href="<?php echo esc_url( $prod_url ); ?>" class="d-block">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $prod['image'] ); ?>" alt="<?php echo esc_attr( $prod['title'] ); ?>" width="143" height="156" class="d-block w-auto mx-auto">
													</a>
												</div>

												<h2 class="title f-iran-yekan line-clamp-2">
													<a href="<?php echo esc_url( $prod_url ); ?>"><?php echo esc_html( $prod['short_title'] ); ?></a>
												</h2>

												<div class="product-six-nk__footer d-flex align-items-end justify-content-between gap-2">
													<div class="add-to-cart buy-now d-flex align-items-center align-self-center">
														<button type="button" class="kaveh-variation-modal-trigger buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0" data-product_id="<?php echo esc_attr( $prod_id ); ?>" aria-label="افزودن به سبد خرید">
															<i class="icon-bag-6 lh-1" aria-hidden="true"></i>
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
								<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
							</div>
						</div>

						<!-- Red Discount Card Column on Left (col-6 col-sm-4 col-lg-3) -->
						<div class="col-6 col-sm-4 col-lg-3">
							<div class="top-selling-products-two-nk__discount position-relative h-100">
								<div class="swiper swiper-top-selling-products-two-nk__discount swiper-fade" data-swiper='{"autoHeight":true,"slidesPerView":1,"spaceBetween":0,"effect":"fade","fadeEffect":{"crossFade":true},"pagination":{"el":".elementor-element-6b1a7cb3 .top-selling-products-two-nk__discount .swiper-pagination","clickable":true}}' data-kaveh-swiper-mounted="1">
									<div class="swiper-wrapper">
									<?php foreach ( $discount_items as $d_idx => $d_item ) :
											$d_match = negar_get_matching_product_info( $d_item['keyword'], $shop_url );
											if ( ! $d_match['id'] ) {
												continue;
											}
											$d_url = $d_match['url'];
										?>
										<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $d_idx + 1 ) . ' / ' . count( $discount_items ) ); ?>">
											<div class="top-selling-products-two-nk__discount__item position-relative">
												<div class="off f-iran-yekan position-absolute">٪ <?php echo esc_html( $d_item['discount'] ); ?></div>
												<div class="image position-relative mxw-fit mx-auto">
													<div class="cir cir-1 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-2 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-3 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<a href="<?php echo esc_url( $d_url ); ?>" class="d-block">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $d_item['image'] ); ?>" alt="<?php echo esc_attr( $d_item['title'] ); ?>" width="147" height="147" class="d-block w-auto">
													</a>
												</div>
												<h2 class="title f-iran-yekan fw-semibold text-truncate">
													<a href="<?php echo esc_url( $d_url ); ?>"><?php echo esc_html( $d_item['short_title'] ); ?></a>
												</h2>
												<div class="comments-rate d-none d-md-flex align-items-center">
													<div class="comments f-iransans">
														<?php echo esc_html( $d_item['comments'] ); ?> نظر
													</div>
													<div class="line flex-grow-1"></div>
													<div class="rate d-flex align-items-center justify-content-end gap-1 gap-md-2">
														<?php echo esc_html( $d_item['rating'] ); ?>
														<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
															<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
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
	function initSellingProductsTwoSwiper() {
		if (typeof Swiper === 'undefined') {
			setTimeout(initSellingProductsTwoSwiper, 100);
			return;
		}

		// Initialize Main Product Carousel
		var mainSwiperEl = document.querySelector('.elementor-element-6b1a7cb3 .swiper-top-selling-products-two-nk');
		if (mainSwiperEl && !mainSwiperEl.classList.contains('swiper-initialized')) {
			new Swiper(mainSwiperEl, {
				slidesPerView: 1,
				spaceBetween: 16,
				watchSlidesProgress: true,
				breakpoints: {
					320: { slidesPerView: 1, spaceBetween: 16 },
					576: { slidesPerView: 2, spaceBetween: 12 },
					992: { slidesPerView: 2.7, spaceBetween: 16 },
					1200: { slidesPerView: 3, spaceBetween: 24 }
				}
			});
		}

		// Initialize Discount Showcase Swiper
		var discountSwiperEl = document.querySelector('.elementor-element-6b1a7cb3 .swiper-top-selling-products-two-nk__discount');
		if (discountSwiperEl && !discountSwiperEl.classList.contains('swiper-initialized')) {
			new Swiper(discountSwiperEl, {
				slidesPerView: 1,
				spaceBetween: 0,
				effect: 'fade',
				fadeEffect: { crossFade: true },
				pagination: {
					el: '.elementor-element-6b1a7cb3 .top-selling-products-two-nk__discount .swiper-pagination',
					clickable: true
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSellingProductsTwoSwiper);
	} else {
		initSellingProductsTwoSwiper();
	}
})();
</script>
