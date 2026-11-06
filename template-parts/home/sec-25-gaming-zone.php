<?php
/**
 * Section 25: Gaming Combo Pack Showcase (HOME-25)
 * Exact 1:1 match to Demo 43 productspacknkkaveh element (.elementor-element-384c8dd7 / .elementor-element-61f16945)
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

// Image assets
$pack_banner    = $kaveh_base . '/product-pack.png';
$kala_pack_icon = $kaveh_base . '/kala-pack.png';

$img_shadows = $kaveh_base . '/f2fa44a96ae03ef062fe6a69ff6d1cbf2029eb79_1786388228-300x300-removebg-preview-1.png';
$img_mirage  = $kaveh_base . '/6276e4189055b9e96d2779c652b748384f11a3a2_1786388736-300x300-removebg-preview-1.png';
$img_fc26    = $kaveh_base . '/5a11a470554a92905e74facee6978604b2f3fd09_1754815528-300x300-removebg-preview-1.png';
$img_yotei   = $kaveh_base . '/26eb5a74fbf76467080d4a69a6a0dd6f9c5f1008_1786454574-300x300-removebg-preview-1.png';

// Fallback to shop or single product if exists
$fc26_prod = get_page_by_path( 'ea-sports-fc-24-ps5', OBJECT, 'product' );
$fc26_link = $fc26_prod ? get_permalink( $fc26_prod->ID ) : $shop_url;
$shadows_link = $shop_url;
$mirage_link  = $shop_url;
$yotei_link   = $shop_url;
?>

<div class="elementor-element elementor-element-384c8dd7 e-con-full e-flex e-con e-parent e-lazyloaded" data-id="384c8dd7" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-61f16945 elementor-widget elementor-widget-productspacknkkaveh" data-id="61f16945" data-element_type="widget" data-e-type="widget" data-widget_type="productspacknkkaveh.default">
		<div class="elementor-widget-container">
			<section class="products-pack-nk">
				<div class="container">
					<div class="products-pack-nk__wrapper">
						<div class="row flex-column-reverse flex-lg-row gap-4 gap-lg-0">
							<div class="col-lg-8 products-pack-nk__media">
								<div class="products-pack-nk__image position-relative">
									<div class="products-pack-nk__image__bg position-absolute top-0 start-0 w-100 h-100" aria-hidden="true"></div>
									<img loading="lazy" decoding="async" src="<?php echo esc_url( $pack_banner ); ?>" alt="<?php esc_attr_e( 'پک گیمینگ', 'negar-shop' ); ?>" width="881" height="585" class="d-block w-100 position-relative">

									<!-- Hotspot 1: Assassin’s Creed Shadows (Bottom Left) -->
									<div class="products-pack-nk__image__product position-absolute one line-layout--vertical ppnk-hs-61f16945-0" style="--hs-top:auto;--hs-right:auto;--hs-bottom:13%;--hs-left:10%;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:72px;--hs-h:141px;--line-w:58px;--line-h:133px;--line-origin:100% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:-25deg;--box-x:-10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover">
										<div class="line position-absolute top-0 start-0"></div>
										<div class="circle-wrap position-absolute bottom-0 end-0">
											<div class="circle rounded-circle"></div>
											<div class="products-pack-nk__image__product__box top bg-white d-flex align-items-center position-absolute bottom-100 end-0 box-align-end">
												<a href="<?php echo esc_url( $shadows_link ); ?>" class="stretched-link position-absolute top-0 end-0 w-100 h-100" aria-label="<?php esc_attr_e( 'بازی Assassin’s Creed Shadows مخصوص PS5', 'negar-shop' ); ?>"></a>
												<div class="image rounded-circle position-relative d-flex align-items-center justify-content-center flex-fill flex-grow-0 flex-shrink-0">
													<div class="img rounded-circle d-flex align-items-center justify-content-center">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_shadows ); ?>" alt="<?php esc_attr_e( 'بازی Assassin’s Creed Shadows مخصوص PS5', 'negar-shop' ); ?>" width="41" height="45" class="w-auto">
													</div>
												</div>
												<div class="detail w-100 flex-fill flex-grow-0 flex-shrink-0">
													<h2 class="title f-iran-yekan line-clamp-2 mb-0"><?php esc_html_e( 'بازی Assassin’s Creed Shadows مخصوص PS5', 'negar-shop' ); ?></h2>
													<div class="price d-flex flex-column align-items-stretch gap-1">
														<div class="price d-flex align-items-center gap-2">
															۵,۷۵۰,۰۰۰<span class="toman-symbol toman-symbol--modern currency-symbol lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- Hotspot 2: Assassins Creed Mirage (Bottom Right) -->
									<div class="products-pack-nk__image__product position-absolute two line-layout--vertical-mirror ppnk-hs-61f16945-1" style="--hs-top:auto;--hs-right:13%;--hs-bottom:4%;--hs-left:auto;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:72px;--hs-h:141px;--line-w:58px;--line-h:133px;--line-origin:0% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:25deg;--box-x:10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover">
										<div class="line position-absolute top-0 start-0"></div>
										<div class="circle-wrap position-absolute bottom-0 start-0">
											<div class="circle rounded-circle"></div>
											<div class="products-pack-nk__image__product__box top bg-white d-flex align-items-center position-absolute bottom-100 start-0 box-align-start">
												<a href="<?php echo esc_url( $mirage_link ); ?>" class="stretched-link position-absolute top-0 end-0 w-100 h-100" aria-label="<?php esc_attr_e( 'بازی Assassins Creed Mirage مخصوص PS5', 'negar-shop' ); ?>"></a>
												<div class="image rounded-circle position-relative d-flex align-items-center justify-content-center flex-fill flex-grow-0 flex-shrink-0">
													<div class="img rounded-circle d-flex align-items-center justify-content-center">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_mirage ); ?>" alt="<?php esc_attr_e( 'بازی Assassins Creed Mirage مخصوص PS5', 'negar-shop' ); ?>" width="41" height="45" class="w-auto">
													</div>
												</div>
												<div class="detail w-100 flex-fill flex-grow-0 flex-shrink-0">
													<h2 class="title f-iran-yekan line-clamp-2 mb-0"><?php esc_html_e( 'بازی Assassins Creed Mirage مخصوص PS5', 'negar-shop' ); ?></h2>
													<div class="price d-flex flex-column align-items-stretch gap-1">
														<div class="price d-flex align-items-center gap-2">
															۴,۳۶۲,۵۰۰<span class="toman-symbol toman-symbol--modern currency-symbol lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- Hotspot 3: EA Sports FC 26 (Top Right) -->
									<div class="products-pack-nk__image__product position-absolute three line-layout--horizontal ppnk-hs-61f16945-2" style="--hs-top:20%;--hs-right:27%;--hs-bottom:auto;--hs-left:auto;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:141px;--hs-h:72px;--line-w:133px;--line-h:58px;--line-origin:100% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:25deg;--box-x:10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover">
										<div class="line position-absolute top-0 start-0"></div>
										<div class="circle-wrap position-absolute start-0">
											<div class="circle rounded-circle"></div>
											<div class="products-pack-nk__image__product__box bottom bg-white d-flex align-items-center position-absolute top-100 start-0 box-align-start">
												<a href="<?php echo esc_url( $fc26_link ); ?>" class="stretched-link position-absolute top-0 end-0 w-100 h-100" aria-label="<?php esc_attr_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>"></a>
												<div class="image rounded-circle position-relative d-flex align-items-center justify-content-center flex-fill flex-grow-0 flex-shrink-0">
													<div class="img rounded-circle d-flex align-items-center justify-content-center">
														<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_fc26 ); ?>" alt="<?php esc_attr_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>" width="41" height="45" class="w-auto">
													</div>
												</div>
												<div class="detail w-100 flex-fill flex-grow-0 flex-shrink-0">
													<h2 class="title f-iran-yekan line-clamp-2 mb-0"><?php esc_html_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?></h2>
													<div class="price d-flex flex-column align-items-stretch gap-1 has-sale">
														<div class="off d-flex align-items-center flex-wrap">
															<del class="f-iransans fw-light">۶,۴۸۸,۱۰۰</del>
															<div class="offer f-iran-yekan text-white d-flex align-items-center justify-content-center gap-1">
																۳۵<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
															</div>
														</div>
														<div class="price d-flex align-items-center gap-2">
															۴,۱۹۹,۰۰۰<span class="toman-symbol toman-symbol--modern currency-symbol lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Left Column: Products Sidebar -->
							<div class="col-lg-4">
								<div class="products-pack-nk__products position-relative h-100">
									<div class="bg position-absolute d-none d-lg-block"></div>
									<div class="products-pack-nk__products__heading d-flex align-items-center justify-content-between gap-2">
										<img loading="lazy" decoding="async" src="<?php echo esc_url( $kala_pack_icon ); ?>" alt="<?php esc_attr_e( 'محصول گیم', 'negar-shop' ); ?>" width="56" height="38">
										<div class="products-pack-nk__products__heading__category f-iran-yekan"><?php esc_html_e( 'محصول گیم', 'negar-shop' ); ?></div>
									</div>

									<div class="products-pack-nk__products__items d-flex flex-column scrollbar" data-scrollbar-after-effect="true">
										<!-- Item 1: Assassin’s Creed Shadows -->
										<div class="products-pack-nk__products__item">
											<div class="info d-flex align-items-center">
												<a href="<?php echo esc_url( $shadows_link ); ?>" class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_shadows ); ?>" alt="<?php esc_attr_e( 'بازی Assassin’s Creed Shadows مخصوص PS5', 'negar-shop' ); ?>" width="63" height="70" class="d-block w-auto">
												</a>
												<h2 class="title f-iran-yekan fw-semibold line-clamp-2 mb-0">
													<a href="<?php echo esc_url( $shadows_link ); ?>"><?php esc_html_e( 'بازی Assassin’s Creed Shadows مخصوص PS5', 'negar-shop' ); ?></a>
												</h2>
											</div>
											<div class="bottom d-flex align-items-center">
												<div class="line flex-grow-1"></div>
												<div class="rate d-flex align-items-center gap-2 f-iransans">
													<span class="rate__text d-inline-flex align-items-center">
														<span class="rate__max">۵</span>
														<span class="rate__sep">/</span>
														<span class="rate__value">۳</span>
													</span>
													<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 21 21" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="m13.574 4.91 1.19 2.382c.161.33.592.643.955.71l2.155.355c1.377.228 1.698 1.225.71 2.222l-1.682 1.68c-.279.28-.44.829-.346 1.226l.481 2.078c.38 1.639-.498 2.28-1.943 1.42l-2.019-1.2c-.363-.22-.971-.22-1.335 0l-2.019 1.2c-1.444.852-2.323.219-1.943-1.42l.482-2.078c.093-.389-.068-.938-.346-1.225l-1.682-1.681c-.988-.989-.667-1.986.71-2.222l2.154-.355c.363-.06.794-.38.955-.71l1.191-2.382c.634-1.292 1.681-1.292 2.332 0Z"/>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M7.33 4.733H2.263M4.796 16.56H2.262m.845-5.914h-.845" opacity=".4"/>
													</svg>
												</div>
											</div>
										</div>

										<!-- Item 2: Assassins Creed Mirage -->
										<div class="products-pack-nk__products__item">
											<div class="info d-flex align-items-center">
												<a href="<?php echo esc_url( $mirage_link ); ?>" class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_mirage ); ?>" alt="<?php esc_attr_e( 'بازی Assassins Creed Mirage مخصوص PS5', 'negar-shop' ); ?>" width="63" height="70" class="d-block w-auto">
												</a>
												<h2 class="title f-iran-yekan fw-semibold line-clamp-2 mb-0">
													<a href="<?php echo esc_url( $mirage_link ); ?>"><?php esc_html_e( 'بازی Assassins Creed Mirage مخصوص PS5', 'negar-shop' ); ?></a>
												</h2>
											</div>
											<div class="bottom d-flex align-items-center">
												<div class="line flex-grow-1"></div>
												<div class="rate d-flex align-items-center gap-2 f-iransans">
													<span class="rate__text d-inline-flex align-items-center">
														<span class="rate__max">۵</span>
														<span class="rate__sep">/</span>
														<span class="rate__value">۴</span>
													</span>
													<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 21 21" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="m13.574 4.91 1.19 2.382c.161.33.592.643.955.71l2.155.355c1.377.228 1.698 1.225.71 2.222l-1.682 1.68c-.279.28-.44.829-.346 1.226l.481 2.078c.38 1.639-.498 2.28-1.943 1.42l-2.019-1.2c-.363-.22-.971-.22-1.335 0l-2.019 1.2c-1.444.852-2.323.219-1.943-1.42l.482-2.078c.093-.389-.068-.938-.346-1.225l-1.682-1.681c-.988-.989-.667-1.986.71-2.222l2.154-.355c.363-.06.794-.38.955-.71l1.191-2.382c.634-1.292 1.681-1.292 2.332 0Z"/>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M7.33 4.733H2.263M4.796 16.56H2.262m.845-5.914h-.845" opacity=".4"/>
													</svg>
												</div>
											</div>
										</div>

										<!-- Item 3: EA Sports FC 26 -->
										<div class="products-pack-nk__products__item">
											<div class="info d-flex align-items-center">
												<a href="<?php echo esc_url( $fc26_link ); ?>" class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_fc26 ); ?>" alt="<?php esc_attr_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?>" width="63" height="70" class="d-block w-auto">
												</a>
												<h2 class="title f-iran-yekan fw-semibold line-clamp-2 mb-0">
													<a href="<?php echo esc_url( $fc26_link ); ?>"><?php esc_html_e( 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5', 'negar-shop' ); ?></a>
												</h2>
											</div>
											<div class="bottom d-flex align-items-center">
												<div class="line flex-grow-1"></div>
												<div class="rate d-flex align-items-center gap-2 f-iransans">
													<span class="rate__text d-inline-flex align-items-center">
														<span class="rate__max">۵</span>
														<span class="rate__sep">/</span>
														<span class="rate__value">۴.۵</span>
													</span>
													<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 21 21" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="m13.574 4.91 1.19 2.382c.161.33.592.643.955.71l2.155.355c1.377.228 1.698 1.225.71 2.222l-1.682 1.68c-.279.28-.44.829-.346 1.226l.481 2.078c.38 1.639-.498 2.28-1.943 1.42l-2.019-1.2c-.363-.22-.971-.22-1.335 0l-2.019 1.2c-1.444.852-2.323.219-1.943-1.42l.482-2.078c.093-.389-.068-.938-.346-1.225l-1.682-1.681c-.988-.989-.667-1.986.71-2.222l2.154-.355c.363-.06.794-.38.955-.71l1.191-2.382c.634-1.292 1.681-1.292 2.332 0Z"/>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M7.33 4.733H2.263M4.796 16.56H2.262m.845-5.914h-.845" opacity=".4"/>
													</svg>
												</div>
											</div>
										</div>

										<!-- Item 4: Ghost of Yotei -->
										<div class="products-pack-nk__products__item">
											<div class="info d-flex align-items-center">
												<a href="<?php echo esc_url( $yotei_link ); ?>" class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
													<img loading="lazy" decoding="async" src="<?php echo esc_url( $img_yotei ); ?>" alt="<?php esc_attr_e( 'بازی Ghost of Yotei مخصوص PS5', 'negar-shop' ); ?>" width="63" height="70" class="d-block w-auto">
												</a>
												<h2 class="title f-iran-yekan fw-semibold line-clamp-2 mb-0">
													<a href="<?php echo esc_url( $yotei_link ); ?>"><?php esc_html_e( 'بازی Ghost of Yotei مخصوص PS5', 'negar-shop' ); ?></a>
												</h2>
											</div>
											<div class="bottom d-flex align-items-center">
												<div class="line flex-grow-1"></div>
												<div class="rate d-flex align-items-center gap-2 f-iransans">
													<span class="rate__text d-inline-flex align-items-center">
														<span class="rate__max">۵</span>
														<span class="rate__sep">/</span>
														<span class="rate__value">۳.۵</span>
													</span>
													<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="none" viewBox="0 0 21 21" aria-hidden="true">
														<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="m13.574 4.91 1.19 2.382c.161.33.592.643.955.71l2.155.355c1.377.228 1.698 1.225.71 2.222l-1.682 1.68c-.279.28-.44.829-.346 1.226l.481 2.078c.38 1.639-.498 2.28-1.943 1.42l-2.019-1.2c-.363-.22-.971-.22-1.335 0l-2.019 1.2c-1.444.852-2.323.219-1.943-1.42l.482-2.078c.093-.389-.068-.938-.346-1.225l-1.682-1.681c-.988-.989-.667-1.986.71-2.222l2.154-.355c.363-.06.794-.38.955-.71l1.191-2.382c.634-1.292 1.681-1.292 2.332 0Z"/>
														<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M7.33 4.733H2.263M4.796 16.56H2.262m.845-5.914h-.845" opacity=".4"/>
													</svg>
												</div>
											</div>
										</div>
									</div>

									<!-- Order Summary Box -->
									<div class="products-pack-nk__products__order d-flex align-items-center gap-3">
										<div class="counter d-flex flex-column align-items-center f-iran-yekan fw-normal">
											<span>۴</span>
											<?php esc_html_e( 'کالا', 'negar-shop' ); ?>
										</div>
										<div class="price ms-auto">
											<div class="off d-flex align-items-center justify-content-end gap-1">
												<del class="f-iransans fw-light">۲۵,۸۹۰,۶۰۰</del>
												<span class="d-flex align-items-center justify-content-center gap-1 f-iran-yekan">
													۹<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
												</span>
											</div>
											<div class="price f-iransans d-flex align-items-center justify-content-end">
												۲۳,۶۰۱,۵۰۰<span class="toman-symbol toman-symbol--modern currency-symbol lh-1"><svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg></span>
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
