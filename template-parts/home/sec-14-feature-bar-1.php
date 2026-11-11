<?php
/**
 * Section 14: Horizontal Feature Bar #1 (HOME-14)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (buytopproductnkkaveh / buy-top-product-nk)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$theme_img  = NEGAR_SHOP_URI . '/assets/images/new-demo/image-02.png';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

if ( ! function_exists( 'negar_get_buy_top_product_info' ) ) {
	/**
	 * Only expose a card when its product still exists in WooCommerce.
	 */
	function negar_get_buy_top_product_info( $slug_fragment, $fallback_url ) {
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

$top_products = array(
	array(
		'number'  => '۱',
		'title'   => 'کنسول بازی سونی مدل PlayStation 5 Slim ظرفیت 1 ترابایت',
		'img'     => $kaveh_base . '/1149308650aa177c213e4175a23076419198dabf_1786296597-300x300-removebg-preview-1.png',
		'price'   => '169.300.000',
		'rate'    => '5/4.5',
		'keyword' => 'PlayStation 5 Slim',
	),
	array(
		'number'  => '۲',
		'title'   => 'بازی Ghost of Yotei مخصوص PS5',
		'img'     => $kaveh_base . '/26eb5a74fbf76467080d4a69a6a0dd6f9c5f1008_1786454574-300x300-removebg-preview-1.png',
		'price'   => '9.290.000',
		'rate'    => '5/3.5',
		'keyword' => 'Ghost of Yotei',
	),
	array(
		'number'  => '۳',
		'title'   => 'بازی مدل  بازی Mortal Kombat 1 مخصوص PS5',
		'img'     => $kaveh_base . '/92d9b95b55d4cf336cee4cf408c8908caf74c7af_1786388232-300x300-removebg-preview-1.png',
		'price'   => '4.700.000',
		'rate'    => '5/5.0',
		'keyword' => 'Mortal Kombat',
	),
	array(
		'number'  => '۴',
		'title'   => 'بازی Ghostrunner 2 مخصوص PS5',
		'img'     => $kaveh_base . '/3eef521897412a152c5b42551b623c91c41d90c0_1786388224-300x300-removebg-preview-1.png',
		'price'   => '3.990.000',
		'rate'    => '5/2.0',
		'keyword' => 'Ghostrunner',
	),
);
?>

<div class="elementor-element elementor-element-7ef157bd e-flex e-con-boxed e-con e-parent my-6" data-id="7ef157bd" data-element_type="container" data-e-type="container">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="elementor-element elementor-element-61868f3 elementor-widget elementor-widget-buytopproductnkkaveh" data-id="61868f3" data-element_type="widget" data-widget_type="buytopproductnkkaveh.default">
			<div class="elementor-widget-container">
				<section class="buy-top-product-nk position-relative buy-top-product-nk-61868f3">
					<div class="buy-top-product-nk__wrapper d-flex align-items-center gap-lg-3 flex-wrap flex-lg-nowrap">
						<div class="text">
							<div class="title f-iran-yekan">خرید برترین ساعتــــ</div>
							<div class="sub-title f-iran-yekan text-uppercase">incididunt ut labore et dolore magna aliqua</div>
						</div>

						<div class="buy-top-product-nk__image ms-auto flex-fill flex-grow-0 flex-shrink-0">
							<img loading="lazy" decoding="async" src="<?php echo esc_url( $theme_img ); ?>" alt="خرید برترین ساعتــــ" width="291" height="290">
						</div>

						<div class="buy-top-product-nk__products w-100 position-relative flex-fill flex-grow-0 flex-shrink-0">
							<div class="swiper swiper-buy-top-product-nk m-0" data-swiper='{"effect":"fade","fadeEffect":{"crossFade":true},"autoHeight":true,"slidesPerView":1,"spaceBetween":0,"speed":600,"loop":true,"pagination":{"el":".elementor-element-61868f3 .swiper-buy-top-product-nk .swiper-pagination","type":"bullets","clickable":true}}'>
								<div class="swiper-wrapper">
									<?php foreach ( $top_products as $item ) :
										$info = negar_get_buy_top_product_info( $item['keyword'], $shop_url );
										if ( ! $info['id'] ) {
											continue;
										}
										?>
										<div class="swiper-slide">
											<div class="buy-top-product-nk__item bg-white d-flex align-items-center">
												<div class="number f-iran-yekan fw-bold"><?php echo esc_html( $item['number'] ); ?></div>
												<div class="image d-flex align-items-center justify-content-center rounded-circle position-relative flex-fill flex-grow-0 flex-shrink-0">
													<div class="img d-flex align-items-center justify-content-center rounded-circle">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" width="39" height="43">
													</div>
												</div>
												<div class="detail w-100">
													<h2 class="title f-iran-yekan line-clamp-2">
														<a href="<?php echo esc_url( $info['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
													</h2>
													<div class="price-rate d-flex align-items-center justify-content-between gap-2">
														<div class="price d-flex align-items-center f-iransans fw-bold gap-2">
															<span class="price__current d-inline-flex align-items-center gap-2">
																<span class="price__amount"><?php echo esc_html( $item['price'] ); ?></span>
																<span class="toman-symbol toman-symbol--modern currency-symbol lh-1">
																	<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
																		<use href="#kaveh-toman-mark"></use>
																	</svg>
																</span>
															</span>
														</div>
														<div class="line flex-grow-1"></div>
														<div class="rate d-flex align-items-center f-iransans">
															<?php echo esc_html( $item['rate'] ); ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 18 18" aria-hidden="true">
																<path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M11.543 3.908 12.6 6.023c.143.292.525.57.848.63l1.912.314c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 13.56c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 8.94c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
																<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M6 3.75H1.5m2.25 10.5H1.5M2.25 9H1.5" opacity=".4"></path>
															</svg>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="swiper-bottom d-flex align-items-center justify-content-center justify-content-lg-end">
									<div class="swiper-pagination d-flex align-items-center position-relative top-0 start-0 bottom-0 end-0 m-0 w-auto h-auto"></div>
									<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-more d-none d-lg-flex align-items-center justify-content-center" aria-label="مشاهده بیشتر">
										<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

<script>
(function() {
	function initBuyTopProductSwiper() {
		if (typeof Swiper === 'undefined') {
			setTimeout(initBuyTopProductSwiper, 100);
			return;
		}

		var swipers = document.querySelectorAll('.swiper-buy-top-product-nk');
		swipers.forEach(function(swiperEl) {
			if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
				var parentWidget = swiperEl.closest('.elementor-widget-buytopproductnkkaveh') || swiperEl.closest('.buy-top-product-nk') || swiperEl.parentElement;
				var paginationEl = parentWidget ? parentWidget.querySelector('.swiper-pagination') : null;

				new Swiper(swiperEl, {
					effect: 'fade',
					fadeEffect: { crossFade: true },
					autoHeight: true,
					slidesPerView: 1,
					spaceBetween: 0,
					speed: 600,
					loop: true,
					pagination: {
						el: paginationEl,
						type: 'bullets',
						clickable: true
					}
				});
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initBuyTopProductSwiper);
	} else {
		initBuyTopProductSwiper();
	}
})();
</script>
