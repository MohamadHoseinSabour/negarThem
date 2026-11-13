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
		'slot'          => 'promo-1',
		'title'         => 'ساعت هوشمند سامسونگ مدل Galaxy Watch Ultra 47mm',
		'short_title'   => 'ساعت هوشمند سامسونگ مدل Galaxy Watch Ultra...',
		'image'         => $kaveh_base . '/3c0f8ff8a12c67f061136e8fafd65eb6f6c56d2e_1786985097-300x300-removebg-preview-1.png',
		'discount'      => '5',
		'rating'        => '5/4.9',
		'comments'      => '11',
		'regular_price' => '32,500,000',
		'sale_price'    => '30,875,000',
		'needles'       => array( 'Galaxy Watch Ultra', 'Galaxy Watch' ),
	),
	array(
		'slot'          => 'promo-2',
		'title'         => 'ساعت هوشمند اپل مدل Apple Watch Series 9 آلومینیوم 45mm',
		'short_title'   => 'ساعت هوشمند اپل مدل Watch Series 9...',
		'image'         => $kaveh_base . '/3c0f8ff8a12c67f061136e8fafd65eb6f6c56d2e_1787055103-300x300-removebg-preview-1.png',
		'discount'      => '3',
		'rating'        => '5/4.7',
		'comments'      => '7',
		'regular_price' => '24,000,000',
		'sale_price'    => '23,280,000',
		'needles'       => array( 'Watch Series 9', 'Series 9' ),
	),
);

// 2. Carousel Products (Right Column)
$carousel_products_sec24 = array(
	array(
		'slot'         => 'carousel-1',
		'title'        => 'ساعت هوشمند امیزفیت مدل Balance با سنسور بیومتریک BioTracker',
		'image'        => $kaveh_base . '/3eef521897412a152c5b42551b623c91c41d90c0_1786388224-300x300-removebg-preview-1.png',
		'rating'       => '5/4.5',
		'price'        => '11,400,000',
		'regular_price'=> '12,200,000',
		'offer'        => '٪ 6',
		'colors'       => array( '#212529', '#6c757d' ),
		'needles'      => array( 'Balance', 'Amazfit' ),
	),
	array(
		'slot'         => 'carousel-2',
		'title'        => 'مچ‌بند هوشمند شیائومی مدل Smart Band 8 Active',
		'image'        => $kaveh_base . '/26eb5a74fbf76467080d4a69a6a0dd6f9c5f1008_1786454574-300x300-removebg-preview-1.png',
		'rating'       => '5/4.6',
		'price'        => '1,290,000',
		'regular_price'=> '',
		'offer'        => '',
		'colors'       => array( '#000000', '#f8f9fa' ),
		'needles'      => array( 'Smart Band 8', 'Band 8' ),
	),
	array(
		'slot'         => 'carousel-3',
		'title'        => 'ساعت هوشمند گارمین مدل Forerunner 265 نسخه مخصوص دوندگان',
		'image'        => $kaveh_base . '/6276e4189055b9e96d2779c652b748384f11a3a2_1786388736-300x300-removebg-preview-1.png',
		'rating'       => '5/4.9',
		'price'        => '29,800,000',
		'regular_price'=> '31,500,000',
		'offer'        => '٪ 5',
		'colors'       => array( '#0f172a', '#e11d48' ),
		'needles'      => array( 'Forerunner 265', 'Garmin' ),
	),
	array(
		'slot'         => 'carousel-4',
		'title'        => 'ساعت هوشمند هایلو مدل Solar Plus RT3 با قابلیت مکالمه',
		'image'        => $kaveh_base . '/1149308650aa177c213e4175a23076419198dabf_1786296597-300x300-removebg-preview-1.png',
		'rating'       => '5/4.2',
		'price'        => '1,890,000',
		'regular_price'=> '',
		'offer'        => '',
		'colors'       => array( '#18181b', '#71717a' ),
		'needles'      => array( 'Solar Plus RT3', 'Haylou' ),
	),
	array(
		'slot'         => 'carousel-5',
		'title'        => 'ساعت هوشمند کیسلکت مدل Kr Pro نسخه مجهز به صفحه نمایش Ultra AMOLED',
		'image'        => $kaveh_base . '/128bfd71ad4017050659832b191c846cd050c5c1_1763052371-300x300-removebg-preview-1.png',
		'rating'       => '5/4.3',
		'price'        => '2,950,000',
		'regular_price'=> '3,200,000',
		'offer'        => '٪ 8',
		'colors'       => array( '#0284c7', '#334155' ),
		'needles'      => array( 'Kr Pro', 'Kieslect' ),
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
						
						<!-- Carousel Column on Right (col-6 col-sm-8 col-lg-9) -->
						<div class="col-6 col-sm-8 col-lg-9">
							<div class="swiper swiper-top-selling-products-two-nk overflow-hidden w-100 h-100" data-swiper='{"autoHeight":false,"watchSlidesProgress":true,"breakpoints":{"320":{"slidesPerView":1,"spaceBetween":16},"576":{"slidesPerView":2,"spaceBetween":12},"992":{"slidesPerView":2.7,"spaceBetween":16},"1200":{"slidesPerView":3,"spaceBetween":24}}}' data-kaveh-swiper-mounted="1">
								<div class="swiper-wrapper">
									<?php foreach ( $carousel_products_sec24 as $idx => $prod ) : 
										$product = function_exists( 'negar_theme_product_pack_product' )
											? negar_theme_product_pack_product( 'smartwatches-2', $prod['slot'], $prod['needles'] )
											: false;

										if ( $product ) {
											$prod_title       = $product->get_name();
											$prod_short_title = wp_trim_words( $prod_title, 8, '...' );
											$prod_url         = get_permalink( $product->get_id() );
											$prod_id          = $product->get_id();
											$img_id           = $product->get_image_id();
											$prod_img         = $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '' );
											$rating           = (float) $product->get_average_rating();
											$prod_rating      = $rating > 0 ? '5/' . number_format_i18n( $rating, 1 ) : '';

											$is_on_sale       = $product->is_on_sale();
											$price            = (float) $product->get_price();
											$regular_price    = (float) $product->get_regular_price();
											$sale_price       = (float) $product->get_sale_price();

											$prod_price         = $price > 0 ? number_format_i18n( $price ) : '';
											$prod_regular_price = ( $is_on_sale && $regular_price > $price && $regular_price > 0 ) ? number_format_i18n( $regular_price ) : '';
											$prod_offer         = ( $is_on_sale && $regular_price > $sale_price && $regular_price > 0 )
												? '٪ ' . (string) round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 )
												: '';

											$prod_colors = array();
											$color_attr  = $product->get_attribute( 'pa_color' );
											if ( ! empty( $color_attr ) && function_exists( 'wc_get_product_terms' ) ) {
												$color_terms = wc_get_product_terms( $product->get_id(), 'pa_color', array( 'fields' => 'all' ) );
												if ( ! empty( $color_terms ) && ! is_wp_error( $color_terms ) ) {
													foreach ( $color_terms as $cterm ) {
														$hex = get_term_meta( $cterm->term_id, 'product_attribute_color', true );
														if ( $hex && preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $hex ) ) {
															$prod_colors[] = array(
																'color' => $hex,
																'name'  => $cterm->name,
															);
														}
													}
												}
											}
										} else {
											$prod_title         = __( 'محصولی برای نمایش موجود نیست', 'negar-shop' );
											$prod_short_title   = $prod_title;
											$prod_url           = '';
											$prod_id            = 0;
											$prod_img           = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '';
											$prod_rating        = '';
											$prod_price         = '';
											$prod_regular_price = '';
											$prod_offer         = '';
											$prod_colors        = array();
										}
									?>
									<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $idx + 1 ) . ' / ' . count( $carousel_products_sec24 ) ); ?>">
										<div class="product-cart-thirty-three-nk h-100">
											<div class="product-nk product-six-nk d-flex flex-column h-100 position-relative<?php echo ! $product ? ' is-empty' : ''; ?>">
												
												<?php if ( ! empty( $prod_offer ) ) : ?>
												<div class="offer position-absolute top-0 d-flex align-items-center justify-content-center f-iran-yekan text-white gap-1 gap-md-2">
													<?php echo esc_html( $prod_offer ); ?>
													<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
												</div>
												<?php endif; ?>

												<?php if ( ! empty( $prod_rating ) ) : ?>
												<div class="rate d-flex align-items-center gap-1 gap-md-2 position-absolute">
													<?php echo esc_html( $prod_rating ); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
													</svg>
												</div>
												<?php endif; ?>

												<div class="image position-relative<?php echo ! $product ? ' opacity-50' : ''; ?>">
													<?php if ( ! empty( $prod_colors ) ) : ?>
													<ul class="colors d-flex flex-column position-absolute">
														<?php foreach ( $prod_colors as $c ) : 
															$c_val = is_array( $c ) ? $c['color'] : $c;
															$c_name = is_array( $c ) ? $c['name'] : '';
														?>
														<li class="rounded-circle" style="background-color: <?php echo esc_attr( $c_val ); ?>;" <?php if ( $c_name ) echo 'title="' . esc_attr( $c_name ) . '"'; ?>></li>
														<?php endforeach; ?>
													</ul>
													<?php endif; ?>
													<?php if ( $product && $prod_url ) : ?>
														<a href="<?php echo esc_url( $prod_url ); ?>" class="d-block">
															<img loading="lazy" decoding="async" src="<?php echo esc_url( $prod_img ); ?>" alt="<?php echo esc_attr( $prod_title ); ?>" width="143" height="156" class="d-block w-auto mx-auto">
														</a>
													<?php else : ?>
														<div class="d-block">
															<img loading="lazy" decoding="async" src="<?php echo esc_url( $prod_img ); ?>" alt="<?php echo esc_attr( $prod_title ); ?>" width="143" height="156" class="d-block w-auto mx-auto">
														</div>
													<?php endif; ?>
												</div>

												<h2 class="title f-iran-yekan line-clamp-2<?php echo ! $product ? ' text-muted' : ''; ?>">
													<?php if ( $product && $prod_url ) : ?>
														<a href="<?php echo esc_url( $prod_url ); ?>"><?php echo esc_html( $prod_short_title ); ?></a>
													<?php else : ?>
														<span><?php echo esc_html( $prod_short_title ); ?></span>
													<?php endif; ?>
												</h2>

												<div class="product-six-nk__footer d-flex align-items-end justify-content-between gap-2">
													<?php if ( $product ) : ?>
														<div class="add-to-cart buy-now d-flex align-items-center align-self-center">
															<?php if ( $product->is_type( 'variable' ) ) : ?>
																<a href="<?php echo esc_url( $prod_url ); ?>" class="buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0" aria-label="<?php echo esc_attr__( 'مشاهده و انتخاب گزینه‌ها', 'negar-shop' ); ?>">
																	<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
																</a>
															<?php elseif ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
																<button type="button" class="kaveh-variation-modal-trigger buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0" data-product_id="<?php echo esc_attr( $prod_id ); ?>" aria-label="<?php echo esc_attr__( 'افزودن به سبد خرید', 'negar-shop' ); ?>">
																	<i class="icon icon-bag-6 lh-1" aria-hidden="true"></i>
																</button>
															<?php else : ?>
																<button type="button" class="buy-now__btn d-flex align-items-center justify-content-center rounded-circle flex-fill flex-grow-0 flex-shrink-0 disabled" disabled aria-label="<?php echo esc_attr__( 'ناموجود', 'negar-shop' ); ?>">
																	<i class="icon icon-bag-6 lh-1 opacity-50" aria-hidden="true"></i>
																</button>
															<?php endif; ?>
														</div>
														<div class="price">
															<?php if ( ! empty( $prod_regular_price ) ) : ?>
															<div class="off">
																<del class="fw-light f-iransans"><?php echo esc_html( $prod_regular_price ); ?></del>
															</div>
															<?php endif; ?>
															<?php if ( ! empty( $prod_price ) ) : ?>
															<div class="price d-flex align-items-center gap-1">
																<?php echo esc_html( $prod_price ); ?>
																<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
															</div>
															<?php endif; ?>
														</div>
													<?php else : ?>
														<div class="price ms-auto">
															<span class="text-muted small f-iran-yekan"><?php esc_html_e( 'ناموجود', 'negar-shop' ); ?></span>
														</div>
													<?php endif; ?>
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
								<div class="swiper swiper-top-selling-products-two-nk__discount swiper-fade" data-swiper='{"autoHeight":true,"slidesPerView":1,"spaceBetween":0,"effect":"fade","fadeEffect":{"crossFade":true},"pagination":{"el":".elementor-element-17ea5448 .top-selling-products-two-nk__discount .swiper-pagination","clickable":true}}' data-kaveh-swiper-mounted="1">
									<div class="swiper-wrapper">
									<?php foreach ( $discount_items_sec24 as $d_idx => $d_item ) :
											$d_product = function_exists( 'negar_theme_product_pack_product' )
												? negar_theme_product_pack_product( 'smartwatches-2', $d_item['slot'], $d_item['needles'] )
												: false;

											if ( $d_product ) {
												$d_title          = $d_product->get_name();
												$d_short_title    = wp_trim_words( $d_title, 7, '...' );
												$d_url            = get_permalink( $d_product->get_id() );
												$d_img_id         = $d_product->get_image_id();
												$d_img            = $d_img_id ? wp_get_attachment_image_url( $d_img_id, 'woocommerce_thumbnail' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '' );
												$d_is_on_sale     = $d_product->is_on_sale();
												$d_price          = (float) $d_product->get_price();
												$d_reg_price      = (float) $d_product->get_regular_price();
												$d_sale_price_val = (float) $d_product->get_sale_price();

												$d_discount      = ( $d_is_on_sale && $d_reg_price > $d_sale_price_val && $d_reg_price > 0 )
													? (string) round( ( ( $d_reg_price - $d_sale_price_val ) / $d_reg_price ) * 100 )
													: '';
												$d_regular_price = ( $d_is_on_sale && $d_reg_price > $d_price && $d_reg_price > 0 ) ? number_format_i18n( $d_reg_price ) : '';
												$d_sale_price    = $d_price > 0 ? number_format_i18n( $d_price ) : '';
												$d_rating_val    = (float) $d_product->get_average_rating();
												$d_rating        = $d_rating_val > 0 ? '5/' . number_format_i18n( $d_rating_val, 1 ) : '';
												$d_comments_cnt  = $d_product->get_review_count();
												$d_comments      = $d_comments_cnt > 0 ? number_format_i18n( $d_comments_cnt ) : '';
											} else {
												$d_title         = __( 'محصولی برای نمایش موجود نیست', 'negar-shop' );
												$d_short_title   = $d_title;
												$d_url           = '';
												$d_img           = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '';
												$d_discount      = '';
												$d_rating        = '';
												$d_comments      = '';
												$d_regular_price = '';
												$d_sale_price    = '';
											}
										?>
										<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( ( $d_idx + 1 ) . ' / ' . count( $discount_items_sec24 ) ); ?>">
											<div class="top-selling-products-two-nk__discount__item position-relative<?php echo ! $d_product ? ' is-empty' : ''; ?>">
												<?php if ( ! empty( $d_discount ) ) : ?>
												<div class="off f-iran-yekan position-absolute">٪ <?php echo esc_html( $d_discount ); ?></div>
												<?php endif; ?>
												<div class="image position-relative mxw-fit mx-auto<?php echo ! $d_product ? ' opacity-50' : ''; ?>">
													<div class="cir cir-1 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-2 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<div class="cir cir-3 position-absolute top-50 start-50 translate-middle rounded-circle"></div>
													<?php if ( $d_product && $d_url ) : ?>
														<a href="<?php echo esc_url( $d_url ); ?>" class="d-block">
															<img loading="lazy" decoding="async" src="<?php echo esc_url( $d_img ); ?>" alt="<?php echo esc_attr( $d_title ); ?>" width="147" height="147" class="d-block w-auto">
														</a>
													<?php else : ?>
														<div class="d-block">
															<img loading="lazy" decoding="async" src="<?php echo esc_url( $d_img ); ?>" alt="<?php echo esc_attr( $d_title ); ?>" width="147" height="147" class="d-block w-auto">
														</div>
													<?php endif; ?>
												</div>
												<h2 class="title f-iran-yekan fw-semibold text-truncate<?php echo ! $d_product ? ' text-muted' : ''; ?>">
													<?php if ( $d_product && $d_url ) : ?>
														<a href="<?php echo esc_url( $d_url ); ?>"><?php echo esc_html( $d_short_title ); ?></a>
													<?php else : ?>
														<span><?php echo esc_html( $d_short_title ); ?></span>
													<?php endif; ?>
												</h2>
												<?php if ( $d_product ) : ?>
													<div class="comments-rate d-none d-md-flex align-items-center">
														<?php if ( ! empty( $d_comments ) ) : ?>
															<div class="comments f-iransans">
																<?php echo esc_html( $d_comments ); ?> نظر
															</div>
														<?php endif; ?>
														<div class="line flex-grow-1"></div>
														<?php if ( ! empty( $d_rating ) ) : ?>
														<div class="rate d-flex align-items-center justify-content-end gap-1 gap-md-2">
															<?php echo esc_html( $d_rating ); ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
																<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
																<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
															</svg>
														</div>
														<?php endif; ?>
													</div>
													<div class="price d-flex align-items-center gap-2">
														<?php if ( ! empty( $d_regular_price ) ) : ?>
														<del class="f-iransans fw-normal"><?php echo esc_html( $d_regular_price ); ?></del>
														<?php endif; ?>
														<?php if ( ! empty( $d_sale_price ) ) : ?>
														<div class="price ms-auto f-iransans fw-semibold d-flex align-items-center gap-1">
															<span><?php echo esc_html( $d_sale_price ); ?></span>
															<span class="toman-symbol toman-symbol--modern lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
														<?php endif; ?>
													</div>
													<a href="<?php echo esc_url( $d_url ); ?>" class="btn-more d-flex align-items-center justify-content-center position-absolute bottom-0 end-50" aria-label="مشاهده محصول">
														<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
													</a>
												<?php else : ?>
													<div class="price d-flex align-items-center justify-content-center my-3">
														<span class="text-muted small f-iran-yekan"><?php esc_html_e( 'ناموجود', 'negar-shop' ); ?></span>
													</div>
												<?php endif; ?>
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
