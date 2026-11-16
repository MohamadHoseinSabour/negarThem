<?php
/**
 * Section 13: Curated Taste Box Carousel (HOME-13)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (productboxnkkaveh / your-taste-box-nk)
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

// Query the 8 main categories of the store (parent = 0)
$excluded_ids = function_exists( 'negar_shop_get_excluded_category_ids' )
	? negar_shop_get_excluded_category_ids()
	: array();

$main_categories = function_exists( 'get_terms' ) ? get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'parent'     => 0,
		'hide_empty' => false,
		'exclude'    => $excluded_ids,
	)
) : array();

$eligible_cats = array();
if ( ! empty( $main_categories ) && ! is_wp_error( $main_categories ) ) {
	foreach ( $main_categories as $m_cat ) {
		$eligible_cats[] = $m_cat;
	}
}

// Randomly choose 3 main categories
if ( count( $eligible_cats ) > 3 ) {
	shuffle( $eligible_cats );
	$chosen_cats = array_slice( $eligible_cats, 0, 3 );
} elseif ( ! empty( $eligible_cats ) ) {
	$chosen_cats = $eligible_cats;
} else {
	$chosen_cats = array();
}

$taste_categories = array();
if ( ! empty( $chosen_cats ) ) {
	foreach ( $chosen_cats as $cat ) {
		$cat_link = get_term_link( $cat );
		$cat_link = is_wp_error( $cat_link ) ? $shop_url : $cat_link;

		$cat_products = function_exists( 'wc_get_products' ) ? wc_get_products(
			array(
				'status'   => 'publish',
				'limit'    => 4,
				'orderby'  => 'rand',
				'category' => array( $cat->slug ),
			)
		) : array();

		$product_slots = array();
		if ( ! empty( $cat_products ) ) {
			$orig_count = count( $cat_products );
			for ( $i = 0; $i < 4; $i++ ) {
				$product_slots[] = $cat_products[ $i % $orig_count ];
			}
		} else {
			// Truthful empty slot state when no products exist
			for ( $i = 0; $i < 4; $i++ ) {
				$product_slots[] = false;
			}
		}

		$taste_categories[] = array(
			'title'    => $cat->name,
			'subtitle' => __( 'محصولات پیشنهادی از دسته', 'negar-shop' ),
			'link'     => $cat_link,
			'products' => $product_slots,
		);
	}
} else {
	for ( $b = 1; $b <= 3; $b++ ) {
		$taste_categories[] = array(
			'title'    => __( 'دسته‌بندی منتخب', 'negar-shop' ),
			'subtitle' => __( 'محصولات پیشنهادی از دسته', 'negar-shop' ),
			'link'     => $shop_url,
			'products' => array( false, false, false, false ),
		);
	}
}
?>

<div class="elementor-element elementor-element-7980ffc8 e-con-full e-flex e-con e-parent e-lazyloaded" data-id="7980ffc8" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-29ed9a57 elementor-widget elementor-widget-productboxnkkaveh" data-id="29ed9a57" data-element_type="widget" data-widget_type="productboxnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="your-taste-box-nk your-taste-box-nk-29ed9a57">
				<div class="container">
					<!-- Section Heading matching Demo 43 Exactly -->
					<div class="your-taste-box-nk__heading d-flex flex-column align-items-center text-center position-relative">
						<span class="heading-icon-wrap lh-1 d-flex align-items-center justify-content-center position-relative">
							<i class="icon icon-happy-emoji lh-1" aria-hidden="true"></i>
						</span>
						<div class="sup-title position-relative f-iran-yekan fw-normal"><?php esc_html_e( 'با محصولات ما بروز باشید', 'negar-shop' ); ?></div>
						<div class="title position-relative f-iran-yekan fw-bold">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" fill="none" viewBox="0 0 20 21" aria-hidden="true">
								<path stroke="#040404" stroke-width="2" d="M1.63 9.11 9.155 1M4.474 13.544l14.823-8.533M7.446 17.733l9.333-1.259"></path>
							</svg>
							<?php esc_html_e( 'باکس سلیقه شما', 'negar-shop' ); ?>
						</div>
					</div>

					<!-- Swiper Carousel matching Demo 43 Exactly -->
					<div class="swiper swiper-your-taste-box-nk overflow-hidden" data-swiper='{"watchSlidesProgress":true,"autoHeight":true,"pagination":{"el":".elementor-element-29ed9a57 .swiper-your-taste-box-nk .swiper-pagination","clickable":true},"breakpoints":{"320":{"slidesPerView":1,"spaceBetween":12},"576":{"slidesPerView":2,"spaceBetween":12},"992":{"slidesPerView":3,"spaceBetween":16},"1200":{"slidesPerView":3,"spaceBetween":24}}}'>
						<div class="swiper-wrapper">
							<?php foreach ( $taste_categories as $cat ) : ?>
								<div class="swiper-slide">
									<div class="your-taste-box-nk__box">
										<!-- Category Header -->
										<div class="your-taste-box-nk__box__heading d-flex align-items-center justify-content-between gap-2">
											<div>
												<div class="title fw-bold f-iran-yekan"><?php echo esc_html( $cat['title'] ); ?></div>
												<div class="sub-title fw-normal f-iran-yekan"><?php echo esc_html( $cat['subtitle'] ); ?></div>
											</div>
											<a href="<?php echo esc_url( $cat['link'] ); ?>" class="btn-more d-flex align-items-center justify-content-center position-relative text-white" aria-label="<?php echo esc_attr( $cat['title'] ); ?>">
												<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
											</a>
										</div>

										<!-- 2x2 Products Grid Inside Box -->
										<div class="your-taste-box-nk__box__products d-flex flex-wrap">
											<?php
											foreach ( $cat['products'] as $item ) :
												$product = false;
												if ( $item instanceof WC_Product ) {
													$product = $item;
												} elseif ( is_array( $item ) && isset( $item['slot'] ) && function_exists( 'negar_theme_product_pack_product' ) ) {
													$product = negar_theme_product_pack_product( 'taste-grid', $item['slot'], isset( $item['needles'] ) ? $item['needles'] : array() );
												}

												if ( $product ) {
													$item_title = $product->get_name();
													$item_url   = get_permalink( $product->get_id() );
													$img_id     = $product->get_image_id();
													$item_img   = $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '' );
													$rating     = (float) $product->get_average_rating();
													$item_rate  = $rating > 0 ? number_format_i18n( $rating, 1 ) : '';

													$is_on_sale         = $product->is_on_sale();
													$price_raw          = $product->get_price();
													$regular_price      = (float) $product->get_regular_price();
													$sale_price         = (float) $product->get_sale_price();
													$item_is_free       = false;
													$item_price         = '';
													$item_regular_price = '';
													$item_discount      = '';

													if ( $product->is_type( 'variable' ) ) {
														$min_price = $product->get_variation_price( 'min', true );
														$max_price = $product->get_variation_price( 'max', true );
														if ( '' !== $min_price && false !== $min_price ) {
															if ( (float) $min_price === 0.0 && (float) $max_price === 0.0 ) {
																$item_price   = __( 'رایگان', 'negar-shop' );
																$item_is_free = true;
															} elseif ( $min_price === $max_price ) {
																$item_price = number_format_i18n( (float) $min_price );
															} else {
																$item_price = sprintf( __( 'از %s', 'negar-shop' ), number_format_i18n( (float) $min_price ) );
															}
														}
													} elseif ( '' !== $price_raw ) {
														$price_val = (float) $price_raw;
														if ( $price_val > 0 ) {
															$item_price = number_format_i18n( $price_val );
														} else {
															$item_price   = __( 'رایگان', 'negar-shop' );
															$item_is_free = true;
														}
													}

													$is_real_sale = $is_on_sale && ! $item_is_free && ! $product->is_type( 'variable' )
														&& $regular_price > 0 && '' !== $product->get_sale_price() && $regular_price > $sale_price;

													if ( $is_real_sale ) {
														$item_regular_price = number_format_i18n( $regular_price );
														$item_discount      = (string) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
													}

													$item_colors = array();
													$color_attr  = $product->get_attribute( 'pa_color' );
													if ( ! empty( $color_attr ) && function_exists( 'wc_get_product_terms' ) ) {
														$color_terms = wc_get_product_terms( $product->get_id(), 'pa_color', array( 'fields' => 'all' ) );
														if ( ! empty( $color_terms ) && ! is_wp_error( $color_terms ) ) {
															foreach ( $color_terms as $cterm ) {
																$hex = get_term_meta( $cterm->term_id, 'product_attribute_color', true );
																if ( $hex && preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $hex ) ) {
																	$item_colors[] = array( 'bg' => $hex, 'title' => $cterm->name );
																}
															}
														}
													}
												} else {
													$item_title         = __( 'محصولی برای نمایش موجود نیست', 'negar-shop' );
													$item_url           = '';
													$item_img           = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '';
													$item_rate          = '';
													$item_price         = '';
													$item_regular_price = '';
													$item_discount      = '';
													$item_colors        = array();
												}
												?>
												<div class="your-taste-box-nk__box__products__col w-100 flex-grow-0 flex-shrink-0 flex-fill">
													<div class="your-taste-box-nk__box__products__item position-relative d-flex flex-column h-100<?php echo ! $product ? ' is-empty' : ''; ?>">
														
														<?php if ( ! empty( $item_colors ) ) : ?>
															<ul class="colors d-flex flex-wrap position-absolute start-0">
																<?php foreach ( $item_colors as $col ) : ?>
																	<li class="rounded-circle" style="background-color: <?php echo esc_attr( $col['bg'] ); ?>;" title="<?php echo esc_attr( $col['title'] ); ?>"></li>
																<?php endforeach; ?>
															</ul>
														<?php endif; ?>

														<?php if ( $product && $item_url ) : ?>
															<a href="<?php echo esc_url( $item_url ); ?>" class="image d-block mxw-fit mx-auto" aria-label="<?php echo esc_attr( $item_title ); ?>">
																<img loading="lazy" decoding="async" src="<?php echo esc_url( $item_img ); ?>" alt="<?php echo esc_attr( $item_title ); ?>" width="84" height="92" class="d-block w-auto">
															</a>
														<?php else : ?>
															<div class="image d-block mxw-fit mx-auto opacity-50">
																<img loading="lazy" decoding="async" src="<?php echo esc_url( $item_img ); ?>" alt="<?php echo esc_attr( $item_title ); ?>" width="84" height="92" class="d-block w-auto">
															</div>
														<?php endif; ?>

														<h2 class="title text-truncate f-iran-yekan fw-normal<?php echo ! $product ? ' text-muted' : ''; ?>">
															<?php if ( $product && $item_url ) : ?>
																<a href="<?php echo esc_url( $item_url ); ?>" class="text-reset text-decoration-none d-block text-truncate"><?php echo esc_html( $item_title ); ?></a>
															<?php else : ?>
																<span class="d-block text-truncate"><?php echo esc_html( $item_title ); ?></span>
															<?php endif; ?>
														</h2>

														<div class="rate-price d-flex align-items-center justify-content-between gap-2 mt-auto">
															<?php if ( ! $product ) : ?>
																<div class="empty-product-notice text-muted small f-iran-yekan w-100 text-center">
																	<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
																</div>
															<?php elseif ( ! empty( $item_discount ) ) : ?>
																<div class="off d-flex align-items-center justify-content-center text-white f-iran-yekan">
																	<?php echo esc_html( $item_discount ); ?>
																	<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
																</div>
																<div class="price">
																	<?php if ( ! empty( $item_regular_price ) ) : ?>
																		<div class="offer d-flex align-items-center justify-content-end">
																			<del class="f-iransans fw-light"><?php echo esc_html( $item_regular_price ); ?></del>
																			<span></span>
																		</div>
																	<?php endif; ?>
																	<?php if ( ! empty( $item_price ) ) : ?>
																		<div class="price d-flex align-items-center f-iransans gap-1">
																			<?php echo esc_html( $item_price ); ?>
																			<?php if ( ! $item_is_free ) : ?>
																				<span class="toman-symbol toman-symbol--modern lh-1">
																					<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
																						<use href="#kaveh-toman-mark"></use>
																					</svg>
																				</span>
																			<?php endif; ?>
																		</div>
																	<?php endif; ?>
																</div>
															<?php else : ?>
																<div class="rate d-flex align-items-center f-iransans">
																	<?php if ( ! empty( $item_rate ) ) : ?>
																		<?php echo esc_html( $item_rate ); ?>
																		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" fill="none" viewBox="0 0 16 17" aria-hidden="true">
																			<path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m10.205 3.727.935 1.87c.126.259.464.504.75.557l1.69.279c1.08.179 1.333.961.557 1.743l-1.32 1.32c-.218.219-.344.65-.271.961l.378 1.632c.298 1.286-.392 1.79-1.526 1.114l-1.584-.942c-.286-.172-.763-.172-1.048 0l-1.585.942c-1.134.67-1.823.172-1.525-1.114l.378-1.632c.073-.305-.053-.736-.272-.961l-1.32-1.32C3.668 7.401 3.92 6.618 5 6.433l1.69-.279c.286-.046.624-.298.75-.557l.935-1.87c.497-1.014 1.32-1.014 1.83 0Z"></path>
																			<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M5.305 3.588H1.326m1.989 9.283H1.326M1.99 8.23h-.664" opacity=".4"></path>
																		</svg>
																	<?php endif; ?>
																</div>
																<div class="price">
																	<?php if ( ! empty( $item_price ) ) : ?>
																		<div class="price d-flex align-items-center f-iransans gap-1">
																			<?php echo esc_html( $item_price ); ?>
																			<?php if ( ! $item_is_free ) : ?>
																				<span class="toman-symbol toman-symbol--modern lh-1">
																					<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
																						<use href="#kaveh-toman-mark"></use>
																					</svg>
																				</span>
																			<?php endif; ?>
																		</div>
																	<?php endif; ?>
																</div>
															<?php endif; ?>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- Pagination (Mobile/Tablet) -->
						<div class="swiper-pagination d-flex d-lg-none align-items-center justify-content-center position-relative top-0 start-0 bottom-0 end-0 w-auto h-auto mx-0"></div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<script>
(function() {
	function initTasteBoxSwiper() {
		if (typeof Swiper === 'undefined') {
			setTimeout(initTasteBoxSwiper, 100);
			return;
		}

		var swiperEl = document.querySelector('.elementor-element-29ed9a57 .swiper-your-taste-box-nk, .swiper-your-taste-box-nk');
		if (swiperEl && !swiperEl.classList.contains('swiper-initialized')) {
			new Swiper(swiperEl, {
				watchSlidesProgress: true,
				autoHeight: true,
				pagination: {
					el: swiperEl.querySelector('.swiper-pagination') || '.elementor-element-29ed9a57 .swiper-pagination',
					clickable: true
				},
				breakpoints: {
					320: { slidesPerView: 1, spaceBetween: 12 },
					576: { slidesPerView: 2, spaceBetween: 12 },
					992: { slidesPerView: 3, spaceBetween: 16 },
					1200: { slidesPerView: 3, spaceBetween: 24 }
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initTasteBoxSwiper);
	} else {
		initTasteBoxSwiper();
	}
})();
</script>
