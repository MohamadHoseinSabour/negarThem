<?php
/**
 * Section 09: Accessories Showcase Carousel (HOME-09)
 * Reference: Demo 43 Section 8 (.elementor-element-39e1b751)
 * Exact 1:1 match to Demo 43 buy-accessories-nk widget
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$image_url  = $upload_dir['baseurl'] . '/kaveh-demo/image-01.png';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Query published products for accessories carousel
$args = array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'orderby'        => 'date',
	'order'          => 'DESC',
);
$products_query = new WP_Query( $args );
?>

<div class="elementor-element elementor-element-39e1b751 e-con-full e-flex e-con e-parent e-lazyloaded my-8 lg:my-12" data-id="39e1b751" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-48ed06a elementor-widget elementor-widget-productscarouselseventeennkkaveh w-full" data-id="48ed06a" data-element_type="widget" data-widget_type="productscarouselseventeennkkaveh.default">
		<div class="elementor-widget-container">
			<section class="buy-accessories-nk buy-accessories-nk-48ed06a position-relative" data-space-container="true">
				<div class="container max-w-[1320px] mx-auto px-3">
					<div class="row d-flex align-items-stretch">

						<!-- 1. Right Side Dedicated Promo Card (Desktop) -->
						<div class="buy-accessories-nk__right w-100 flex-fill flex-grow-0 flex-shrink-0 position-relative d-none d-lg-block">
							<div class="buy-accessories-nk__right__box position-relative h-100 d-flex flex-column" style="min-height: 410px;">
								<div class="title f-iran-yekan fw-bold text-center position-relative mxw-fit mx-auto mb-2">
									<?php esc_html_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="33" height="19" fill="none" viewBox="0 0 33 19" class="position-absolute top-0 end-100" aria-hidden="true">
										<path stroke="#000" stroke-linecap="round" d="M7.274 14.836c1.2-3.929 7.858-11.917 24.882-12.44M1.054 8.616c.65-2.292 4.256-6.952 13.477-7.257m-1.036 16.587c.45-1.637 2.946-4.965 9.33-5.184"></path>
									</svg>
								</div>
								<div class="sub-title f-iran-yekan fw-normal text-center position-relative mxw-fit mx-auto">
									<?php esc_html_e( 'انواع لوازم جانبیــــــــــــ ایفون', 'negar-shop' ); ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="33" height="18" fill="none" viewBox="0 0 33 18" class="position-absolute top-0 start-100" aria-hidden="true">
										<path stroke="#000" stroke-linecap="round" d="M25.739 3.864C24.539 7.793 17.88 15.78.857 16.304m31.102-6.22c-.65 2.292-4.256 6.952-13.477 7.257M19.518.754c-.45 1.637-2.946 4.965-9.33 5.183"></path>
									</svg>
								</div>
								<div class="image mt-auto position-relative">
									<img loading="lazy" decoding="async" src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>" width="272" height="264" class="d-block w-100">
									<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="none" viewBox="0 0 23 23" class="shape shape-1 position-absolute" aria-hidden="true">
										<path fill="#fff" d="m11.27.773 1.4 9.485 9.486 1.4-9.485 1.402-1.401 9.484-1.4-9.484-9.486-1.401 9.485-1.401L11.27.773Z"></path>
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="none" viewBox="0 0 23 23" class="shape shape-2 position-absolute" aria-hidden="true">
										<path fill="#fff" d="m11.27.773 1.4 9.485 9.486 1.4-9.485 1.402-1.401 9.484-1.4-9.484-9.486-1.401 9.485-1.401L11.27.773Z"></path>
									</svg>
								</div>
								<div class="buy-accessories-nk__right__box__more-wrap">
									<a href="<?php echo esc_url( $shop_url ); ?>" class="more d-flex align-items-center justify-content-center text-white" aria-label="<?php esc_attr_e( 'مشاهده بیشتر', 'negar-shop' ); ?>">
										<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- 2. Left Side Products Slider -->
						<div class="buy-accessories-nk__left w-100 flex-fill flex-grow-0 flex-shrink-0 position-relative">
							<div class="swiper swiper-buy-accessories-nk overflow-visible" data-swiper='{"autoHeight":false,"watchSlidesProgress":true,"breakpoints":{"320":{"slidesPerView":2,"spaceBetween":12},"576":{"slidesPerView":2,"spaceBetween":16},"992":{"slidesPerView":2.3,"spaceBetween":24},"1200":{"slidesPerView":2.9,"spaceBetween":24},"1400":{"slidesPerView":3.2,"spaceBetween":24}}}'>
								<div class="swiper-wrapper">

									<!-- Mobile-only Promo Card in first slide -->
									<div class="swiper-slide d-lg-none" style="min-width: 240px;">
										<div class="buy-accessories-nk__right w-100 flex-fill flex-grow-0 flex-shrink-0 position-relative">
											<div class="buy-accessories-nk__right__box position-relative h-100 d-flex flex-column" style="min-height: 410px;">
												<div class="title f-iran-yekan fw-bold text-center position-relative mxw-fit mx-auto mb-2">
													<?php esc_html_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="33" height="19" fill="none" viewBox="0 0 33 19" class="position-absolute top-0 end-100" aria-hidden="true">
														<path stroke="#000" stroke-linecap="round" d="M7.274 14.836c1.2-3.929 7.858-11.917 24.882-12.44M1.054 8.616c.65-2.292 4.256-6.952 13.477-7.257m-1.036 16.587c.45-1.637 2.946-4.965 9.33-5.184"></path>
													</svg>
												</div>
												<div class="sub-title f-iran-yekan fw-normal text-center position-relative mxw-fit mx-auto">
													<?php esc_html_e( 'انواع لوازم جانبیــــــــــــ ایفون', 'negar-shop' ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="33" height="18" fill="none" viewBox="0 0 33 18" class="position-absolute top-0 start-100" aria-hidden="true">
														<path stroke="#000" stroke-linecap="round" d="M25.739 3.864C24.539 7.793 17.88 15.78.857 16.304m31.102-6.22c-.65 2.292-4.256 6.952-13.477 7.257M19.518.754c-.45 1.637-2.946 4.965-9.33 5.183"></path>
													</svg>
												</div>
												<div class="image mt-auto position-relative">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'خرید لوازم جانبی', 'negar-shop' ); ?>" width="272" height="264" class="d-block w-100">
												</div>
												<div class="buy-accessories-nk__right__box__more-wrap">
													<a href="<?php echo esc_url( $shop_url ); ?>" class="more d-flex align-items-center justify-content-center text-white" aria-label="<?php esc_attr_e( 'مشاهده بیشتر', 'negar-shop' ); ?>">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
												</div>
											</div>
										</div>
									</div>

									<?php
									if ( $products_query->have_posts() ) :
										while ( $products_query->have_posts() ) :
											$products_query->the_post();
											global $product;
											if ( ! is_a( $product, 'WC_Product' ) ) {
												continue;
											}

											$prod_id        = $product->get_id();
											$prod_title     = $product->get_name();
											$prod_link      = get_permalink( $prod_id );
											$rating_raw     = (float) $product->get_average_rating();
											$rating_display = $rating_raw > 0 ? number_format( $rating_raw, 1 ) : '5.0';
											$regular_price  = (float) $product->get_regular_price();
											$sale_price     = (float) $product->get_sale_price();
											$is_on_sale     = $product->is_on_sale() && $regular_price > 0 && $sale_price > 0;
											$discount_pct   = $is_on_sale ? round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ) : 0;
											$active_price   = $is_on_sale ? $sale_price : ( $regular_price ?: (float) $product->get_price() );
											?>
											<div class="swiper-slide" style="min-width: 260px;">
												<div class="product-cart-thirty-one-nk h-100" style="min-height: 410px;">
													<div class="product-nk product-four-nk d-flex flex-column h-100 position-relative">

														<!-- Star Rating -->
														<div class="rate d-flex align-items-center justify-content-end gap-1 gap-md-2">
															<span>5/<?php echo esc_html( $rating_display ); ?></span>
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
																<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
																<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
															</svg>
														</div>

														<!-- Product Image -->
														<div class="image position-relative mxw-fit mx-auto">
															<a href="<?php echo esc_url( $prod_link ); ?>" class="d-block">
																<?php
																if ( has_post_thumbnail( $prod_id ) ) {
																	echo get_the_post_thumbnail( $prod_id, 'woocommerce_thumbnail', array( 'class' => 'd-block w-auto max-h-[154px] object-contain', 'loading' => 'lazy' ) );
																} else {
																	?>
																	<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $prod_title ); ?>" width="140" height="154" class="d-block w-auto" loading="lazy">
																	<?php
																}
																?>
															</a>
														</div>

														<!-- Title -->
														<h2 class="title line-clamp-2">
															<a href="<?php echo esc_url( $prod_link ); ?>"><?php echo esc_html( $prod_title ); ?></a>
														</h2>

														<!-- Footer: Cart Button + Price -->
														<div class="product-four-nk__footer d-flex align-items-end justify-content-between gap-2 mt-auto">
															<div class="add-to-cart buy-now d-flex align-items-center">
																<button type="button" name="add-to-cart" value="<?php echo esc_attr( $prod_id ); ?>" class="single_add_to_cart_button buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0" aria-label="<?php esc_attr_e( 'افزودن به سبد خرید', 'negar-shop' ); ?>">
																	<i class="icon icon-bag-6 lh-1" aria-hidden="true"></i>
																</button>
															</div>
															<div class="price">
																<?php if ( $is_on_sale ) : ?>
																	<div class="off d-flex align-items-center justify-content-between gap-1">
																		<del class="fw-light f-iransans"><?php echo esc_html( number_format( $regular_price ) ); ?></del>
																		<?php if ( $discount_pct > 0 ) : ?>
																			<span class="d-flex align-items-center justify-content-center gap-1">
																				<?php echo esc_html( $discount_pct ); ?>%
																				<i class="icon icon-discount-4 lh-1" aria-hidden="true"></i>
																			</span>
																		<?php endif; ?>
																	</div>
																<?php endif; ?>
																<div class="price d-flex align-items-center gap-1">
																	<?php echo esc_html( number_format( $active_price ) ); ?>
																	<span class="toman-symbol toman-symbol--modern lh-1">
																		<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg>
																	</span>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>
											<?php
										endwhile;
										wp_reset_postdata();
									endif;
									?>

								</div>
							</div>
						</div>

					</div>
				</div>
			</section>

			<!-- Space container height synchronizer script matching Demo 43 -->
			<script id="kaveh-seventeen-space-container">
			(function(){
				if (typeof window === 'undefined') return;
				function run(){
					if (typeof jQuery === 'undefined') return;
					jQuery(function($){
						function syncPromoCardHeights($root){
							var $promo = $root.find('.buy-accessories-nk__right .buy-accessories-nk__right__box').first();
							var $cards = $root.find('.buy-accessories-nk__left .swiper-slide > [class*="product-cart-"]');
							$promo.css('min-height', '');
							$cards.css('min-height', '');
							if (!$promo.length || !$promo.is(':visible') || !$cards.length || window.innerWidth < 992) {
								return;
							}
							var maxH = $promo.outerHeight() || 0;
							$cards.each(function(){
								maxH = Math.max(maxH, $(this).outerHeight() || 0);
							});
							if (maxH < 1) return;
							$promo.css('min-height', maxH + 'px');
							$cards.css('min-height', maxH + 'px');
						}
						function update(){
							$('.buy-accessories-nk[data-space-container]').each(function(){
								var $root = $(this);
								var $c = $root.children('.container').first();
								if (!$c.length) return;
								var space = window.innerWidth - $c.outerWidth();
								if (space < 0) space = 0;
								$root.css({
									'--space': space + 'px',
									'--r': (space / 2) + 'px',
									'--l': (space / 2) + 'px'
								});
								syncPromoCardHeights($root);
							});
						}

						// Initialize Swiper for accessories carousel
						if (typeof Swiper !== 'undefined') {
							var swiperEl = document.querySelector('.swiper-buy-accessories-nk');
							if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
								new Swiper(swiperEl, {
									slidesPerView: 2,
									spaceBetween: 12,
									watchSlidesProgress: true,
									breakpoints: {
										576: { slidesPerView: 2, spaceBetween: 16 },
										992: { slidesPerView: 2.3, spaceBetween: 24 },
										1200: { slidesPerView: 2.9, spaceBetween: 24 },
										1400: { slidesPerView: 3.2, spaceBetween: 24 }
									}
								});
							}
						}

						$(window).off('resize.kavehSeventeenSpace load.kavehSeventeenSpace')
							.on('resize.kavehSeventeenSpace load.kavehSeventeenSpace', update);
						update();
						setTimeout(update, 250);
						setTimeout(update, 800);
					});
				}
				if (document.readyState === 'complete' || document.readyState === 'interactive') {
					run();
				} else {
					document.addEventListener('DOMContentLoaded', run);
				}
			})();
			</script>

		</div>
	</div>
</div>
