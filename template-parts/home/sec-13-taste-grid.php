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

if ( ! function_exists( 'negar_get_taste_product_info' ) ) {
	/**
	 * Helper to find WooCommerce product ID and permalink if available
	 */
	function negar_get_taste_product_info( $slug_fragment, $fallback_url ) {
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

// 4 Category Boxes matching Demo 43 Exactly
$taste_categories = array(
	// Box 1: Digital Products / Laptops
	array(
		'title'    => 'کالای دیجیتال',
		'subtitle' => 'محصولات پیشنهادی از دسته',
		'link'     => $shop_url,
		'products' => array(
			array(
				'slot'          => 'box-1-1',
				'title'         => 'لپ تاپ 16 اینچی ایسوس مدل TUF Gaming A16 FA608PP-Ryzen',
				'img'           => $kaveh_base . '/230ce545eaa1028a10622044bde2cbfdf2969235_1785675702-removebg-preview-300x300.png',
				'rate'          => '4.0',
				'price'         => '580,000,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#808080', 'title' => 'خاکستری' ),
				),
				'needles'       => array( 'TUF Gaming' ),
			),
			array(
				'slot'          => 'box-1-2',
				'title'         => 'لپ تاپ 15.3 اینچی لنوو مدل Legion 5 15IRX10-i7',
				'img'           => $kaveh_base . '/203a083f03f1506a710c1e6a88c71e1919e20a76_1786268142-300x300-removebg-preview-1-1.png',
				'rate'          => '4.0',
				'price'         => '586,000,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#000000', 'title' => 'مشکی' ),
				),
				'needles'       => array( 'Legion 5' ),
			),
			array(
				'slot'          => 'box-1-3',
				'title'         => 'لپ تاپ 16 اینچی ایسوس مدل WQXGA 240Hz',
				'img'           => $kaveh_base . '/3c0f8ff8a12c67f061136e8fafd65eb6f6c56d2e_1786985097-300x300-removebg-preview-1.png',
				'rate'          => '5.0',
				'price'         => '839,000,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#808080', 'title' => 'خاکستری' ),
				),
				'needles'       => array( 'ROG Strix', 'WQXGA' ),
			),
			array(
				'slot'          => 'box-1-4',
				'title'         => 'لپ‌تاپ 17 اینچی ام اس آی مدل Vector 17 HX AI A2XWJG-Core',
				'img'           => $kaveh_base . '/04124d815f3ee9d9adb91f219ad4b45d298b8408_1779002328-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '858,900,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#808080', 'title' => 'خاکستری' ),
				),
				'needles'       => array( 'Vector' ),
			),
		),
	),

	// Box 2: Smartphones / Mobile
	array(
		'title'    => 'موبایل',
		'subtitle' => 'محصولات پیشنهادی از دسته',
		'link'     => $shop_url,
		'products' => array(
			array(
				'slot'          => 'box-2-1',
				'title'         => 'گوشی موبایل اپل مدل iPhone 17 Pro Max ZAA',
				'img'           => $kaveh_base . '/ec57ce03f2a92834058f502174b1a2aeffc9a580_1759666637-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '409,999,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#c0c0c0', 'title' => 'نقره ای' ),
					array( 'bg' => '#00008b', 'title' => 'آبی تیره' ),
					array( 'bg' => '#ffa500', 'title' => 'نارنجی' ),
				),
				'needles'       => array( 'iPhone 17' ),
			),
			array(
				'slot'          => 'box-2-2',
				'title'         => 'گوشی موبایل اپل مدل iPhone 15 Ch',
				'img'           => $kaveh_base . '/f4c0e5f4199abda603494b54e0655cbd0447405f_1765624240-300x300-removebg-preview-1.png',
				'rate'          => '3.5',
				'price'         => '214,999,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#1e73be', 'title' => 'آبی' ),
				),
				'needles'       => array( 'iPhone 15' ),
			),
			array(
				'slot'          => 'box-2-3',
				'title'         => 'گوشی موبایل اپل مدل iPhone 16 CH دو سیم کارت ظرفیت 128 گیگابایت',
				'img'           => $kaveh_base . '/5af568b4909435c6a215ad5e728e902db234b673_1736597759-300x300-removebg-preview-1.png',
				'rate'          => '4.5',
				'price'         => '269,990,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#ff69b4', 'title' => 'صورتی' ),
					array( 'bg' => '#000000', 'title' => 'مشکی' ),
					array( 'bg' => '#1e73be', 'title' => 'آبی' ),
				),
				'needles'       => array( 'iPhone 16' ),
			),
			array(
				'slot'          => 'box-2-4',
				'title'         => 'گوشی موبایل سامسونگ مدل Galaxy S25 FE دو سیم کارت',
				'img'           => $kaveh_base . '/89c0e7b3f400348a3add26684b7ece5084e4361f_1777115891-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '162,999,000',
				'regular_price' => '175,000,000',
				'discount'      => '7',
				'colors'        => array(
					array( 'bg' => '#000000', 'title' => 'مشکی' ),
				),
				'needles'       => array( 'Galaxy S25' ),
			),
		),
	),

	// Box 3: Console Games
	array(
		'title'    => 'بازی های کنسول خانگی',
		'subtitle' => 'محصولات پیشنهادی از دسته',
		'link'     => $shop_url,
		'products' => array(
			array(
				'slot'          => 'box-3-1',
				'title'         => 'بازی Gran Turismo 7 مخصوص PS5',
				'img'           => $kaveh_base . '/e2a1ec7e564e1d9555bdf7a48e62cc138ff24cd0_1786388718-removebg-preview-300x300-1.png',
				'rate'          => '4.3',
				'price'         => '6,374,200',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(),
				'needles'       => array( 'Gran Turismo' ),
			),
			array(
				'slot'          => 'box-3-2',
				'title'         => 'بازی Horizon Forbidden West نسخه کامل مخصوص PS5',
				'img'           => $kaveh_base . '/86f41915edf5a7e926e2ae0bbd19a2d0ba9c5d06_1786388241-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '7,700,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(),
				'needles'       => array( 'Horizon Forbidden' ),
			),
			array(
				'slot'          => 'box-3-3',
				'title'         => 'بازی Ghostrunner 2 مخصوص PS5',
				'img'           => $kaveh_base . '/3eef521897412a152c5b42551b623c91c41d90c0_1786388224-300x300-removebg-preview-1.png',
				'rate'          => '2.0',
				'price'         => '3,990,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(),
				'needles'       => array( 'Ghostrunner' ),
			),
			array(
				'slot'          => 'box-3-4',
				'title'         => 'بازی مدل بازی Mortal Kombat 1 مخصوص PS5',
				'img'           => $kaveh_base . '/92d9b95b55d4cf336cee4cf408c8908caf74c7af_1786388232-300x300-removebg-preview-1.png',
				'rate'          => '5.0',
				'price'         => '4,700,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(),
				'needles'       => array( 'Mortal Kombat' ),
			),
		),
	),

	// Box 4: Additional Category (Mobile Phones)
	array(
		'title'    => 'گوشی موبایل',
		'subtitle' => 'محصولات پیشنهادی از دسته',
		'link'     => $shop_url,
		'products' => array(
			array(
				'slot'          => 'box-4-1',
				'title'         => 'گوشی موبایل اپل مدل iPhone 17 Pro Max ZAA',
				'img'           => $kaveh_base . '/ec57ce03f2a92834058f502174b1a2aeffc9a580_1759666637-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '409,999,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#c0c0c0', 'title' => 'نقره ای' ),
					array( 'bg' => '#00008b', 'title' => 'آبی تیره' ),
					array( 'bg' => '#ffa500', 'title' => 'نارنجی' ),
				),
				'needles'       => array( 'iPhone 17' ),
			),
			array(
				'slot'          => 'box-4-2',
				'title'         => 'گوشی موبایل اپل مدل iPhone 15 Ch',
				'img'           => $kaveh_base . '/f4c0e5f4199abda603494b54e0655cbd0447405f_1765624240-300x300-removebg-preview-1.png',
				'rate'          => '3.5',
				'price'         => '214,999,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#1e73be', 'title' => 'آبی' ),
				),
				'needles'       => array( 'iPhone 15' ),
			),
			array(
				'slot'          => 'box-4-3',
				'title'         => 'گوشی موبایل اپل مدل iPhone 16 CH دو سیم کارت ظرفیت 128 گیگابایت',
				'img'           => $kaveh_base . '/5af568b4909435c6a215ad5e728e902db234b673_1736597759-300x300-removebg-preview-1.png',
				'rate'          => '4.5',
				'price'         => '269,990,000',
				'regular_price' => '',
				'discount'      => '',
				'colors'        => array(
					array( 'bg' => '#ff69b4', 'title' => 'صورتی' ),
					array( 'bg' => '#000000', 'title' => 'مشکی' ),
					array( 'bg' => '#1e73be', 'title' => 'آبی' ),
				),
				'needles'       => array( 'iPhone 16' ),
			),
			array(
				'slot'          => 'box-4-4',
				'title'         => 'گوشی موبایل سامسونگ مدل Galaxy S25 FE دو سیم کارت',
				'img'           => $kaveh_base . '/89c0e7b3f400348a3add26684b7ece5084e4361f_1777115891-300x300-removebg-preview-1.png',
				'rate'          => '4.0',
				'price'         => '162,999,000',
				'regular_price' => '175,000,000',
				'discount'      => '7',
				'colors'        => array(
					array( 'bg' => '#000000', 'title' => 'مشکی' ),
				),
				'needles'       => array( 'Galaxy S25' ),
			),
		),
	),
);
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
												$product = function_exists( 'negar_theme_product_pack_product' )
													? negar_theme_product_pack_product( 'taste-grid', $item['slot'], $item['needles'] )
													: false;

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
