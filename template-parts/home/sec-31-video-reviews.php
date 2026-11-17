<?php
/**
 * Section 31: Product video reviews (HOME-31).
 *
 * Video reviews may only be shown when they come from a verified WordPress
 * Media Library video source. Falls back to a truthful empty state when no
 * verified videos are published.
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_link     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$video_reviews = function_exists( 'negar_theme_get_video_reviews' ) ? negar_theme_get_video_reviews() : array();
$swiper_config = wp_json_encode(
	array(
		'slidesPerView'       => 4,
		'spaceBetween'        => 24,
		'watchSlidesProgress' => true,
		'autoHeight'          => true,
		'observer'            => true,
		'observeParents'      => true,
		'breakpoints'         => array(
			320  => array(
				'slidesPerView' => 1.35,
				'spaceBetween'  => 12,
			),
			576  => array(
				'slidesPerView' => 2,
				'spaceBetween'  => 14,
			),
			992  => array(
				'slidesPerView' => 3,
				'spaceBetween'  => 18,
			),
			1200 => array(
				'slidesPerView' => 4,
				'spaceBetween'  => 24,
			),
		),
	)
);
?>

<div class="elementor-element elementor-element-1a06770f e-con-full e-flex e-con e-parent e-lazyloaded" data-id="1a06770f" data-element_type="container">
	<div class="elementor-element elementor-element-39f2483d elementor-widget elementor-widget-videosnkkaveh" data-id="39f2483d" data-element_type="widget" data-widget_type="videosnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="videos-nk videos-nk-39f2483d" aria-labelledby="negar-video-reviews-title">
				<div class="container">
					<div class="videos-nk__wrapper position-relative">
						<div class="icon lh-1 d-flex align-items-center justify-content-center text-white position-absolute top-0" aria-hidden="true">
							<i class="icon icon-play-4 lh-1" aria-hidden="true"></i>
						</div>

						<div class="section-heading-one-nk d-flex align-items-center gap-2">
							<div class="title f-iran-yekan" id="negar-video-reviews-title"><?php esc_html_e( 'ویدیوهای بررسی محصول', 'negar-shop' ); ?></div>
							<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'محتوای تأییدشده‌ی فروشگاه', 'negar-shop' ); ?></div>
							<div class="line flex-grow-1 d-flex align-items-center gap-1" aria-hidden="true"><span class="flex-grow-1"></span><span></span></div>
							<a href="<?php echo esc_url( $shop_link ); ?>" class="btn d-flex align-items-center justify-content-center fw-normal rounded-pill position-relative">
								<?php esc_html_e( 'مشاهده محصولات', 'negar-shop' ); ?>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
						</div>

						<?php if ( empty( $video_reviews ) ) : ?>
							<div class="videos-nk__empty rounded-4 border border-slate-200 bg-white p-5 p-md-6 text-center" role="status">
								<div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-slate-100 text-slate-500" style="width: 3.5rem; height: 3.5rem;">
									<i class="icon-play-4 lh-1" aria-hidden="true"></i>
								</div>
								<p class="mb-1 fw-bold text-slate-800"><?php esc_html_e( 'هنوز ویدیوی بررسی تأییدشده‌ای برای نمایش نداریم.', 'negar-shop' ); ?></p>
								<p class="mb-0 small text-slate-500"><?php esc_html_e( 'پس از انتشار محتوای واقعی و تأییدشده، این بخش به‌روزرسانی می‌شود.', 'negar-shop' ); ?></p>
							</div>
						<?php else : ?>
							<div class="swiper swiper-videos-nk overflow-hidden" data-swiper-config="<?php echo esc_attr( $swiper_config ); ?>">
								<div class="swiper-wrapper" aria-live="polite">
									<?php foreach ( $video_reviews as $index => $review ) : ?>
										<?php
										$play_label = sprintf(
											/* translators: %s: video review title */
											__( 'پخش ویدیوی %s', 'negar-shop' ),
											$review['title']
										);
										$meta_line = trim(
											implode(
												' • ',
												array_filter(
													array(
														$review['publisher_name'],
														$review['published_date'],
													)
												)
											)
										);
										?>
										<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( sprintf( '%1$d / %2$d', $index + 1, count( $video_reviews ) ) ); ?>">
											<article class="videos-nk__item">
												<button
													type="button"
													class="image position-relative videos-nk__play"
													data-video-type="upload"
													data-video-src="<?php echo esc_url( $review['video_url'] ); ?>"
													data-video-poster="<?php echo esc_url( $review['poster_url'] ); ?>"
													data-video-orientation="<?php echo esc_attr( $review['orientation'] ); ?>"
													data-video-title="<?php echo esc_attr( $review['title'] ); ?>"
													data-product-title="<?php echo esc_attr( $review['product_title'] ); ?>"
													data-product-link="<?php echo esc_url( $review['product_link'] ); ?>"
													data-cta-label="<?php echo esc_attr( $review['cta_label'] ); ?>"
													aria-label="<?php echo esc_attr( $play_label ); ?>"
												>
													<?php if ( '' !== $review['poster_url'] ) : ?>
														<img
															loading="lazy"
															decoding="async"
															src="<?php echo esc_url( $review['poster_url'] ); ?>"
															alt="<?php echo esc_attr( $review['title'] ); ?>"
															width="294"
															height="460"
															class="d-block w-100 videos-nk__poster"
														>
													<?php endif; ?>

													<i class="icon-play-4 rounded-circle d-flex align-items-center justify-content-center position-absolute text-white lh-1" aria-hidden="true"></i>
												</button>

												<?php if ( '' !== $review['duration'] ) : ?>
													<div class="videos-nk__duration" aria-hidden="true"><?php echo esc_html( $review['duration'] ); ?></div>
												<?php endif; ?>

												<div class="content">
													<div class="videos-nk__blur-layer" aria-hidden="true"></div>
													<div class="videos-nk__content-inner">
														<?php if ( '' !== $review['badge'] ) : ?>
															<div class="videos-nk__badge" aria-hidden="true"><?php echo esc_html( $review['badge'] ); ?></div>
														<?php endif; ?>
														<div class="author d-flex align-items-center">
															<?php if ( '' !== $review['avatar_url'] ) : ?>
																<img
																	loading="lazy"
																	decoding="async"
																	src="<?php echo esc_url( $review['avatar_url'] ); ?>"
																	alt="<?php echo esc_attr( $review['publisher_name'] ); ?>"
																	width="40"
																	height="40"
																	class="rounded-circle flex-shrink-0 videos-nk__publisher-img"
																>
															<?php else : ?>
																<span class="videos-nk__publisher-badge rounded-circle flex-shrink-0 d-inline-flex align-items-center justify-content-center" aria-hidden="true">
																	<i class="icon-play-4 lh-1"></i>
																</span>
															<?php endif; ?>

															<div class="detail min-w-0">
																<a href="<?php echo esc_url( $review['product_link'] ); ?>" class="name f-iransans fw-semibold">
																	<?php echo esc_html( $review['title'] ); ?>
																</a>
																<?php if ( '' !== $meta_line ) : ?>
																	<div class="date f-iran-yekan fw-normal"><?php echo esc_html( $meta_line ); ?></div>
																<?php endif; ?>
															</div>
														</div>

														<a href="<?php echo esc_url( $review['product_link'] ); ?>" class="more d-flex align-items-center justify-content-between gap-2">
															<span class="min-w-0">
																<span class="title f-iran-yekan fw-bold d-block"><?php echo esc_html( $review['cta_label'] ); ?></span>
																<?php if ( '' !== $review['product_title'] ) : ?>
																	<span class="all f-iran-yekan fw-normal videos-nk__product-name"><?php echo esc_html( $review['product_title'] ); ?></span>
																<?php endif; ?>
															</span>
															<i class="icon icon-chevron-left-2 d-flex align-items-center justify-content-center position-relative text-white lh-1" aria-hidden="true"></i>
														</a>
													</div>
												</div>
											</article>
										</div>
									<?php endforeach; ?>
								</div>
								<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $video_reviews ) ) : ?>
					<div class="videos-nk-popup" id="videos-nk-popup-39f2483d" hidden aria-hidden="true">
						<div class="videos-nk-popup__backdrop" data-videos-nk-close></div>
						<div class="videos-nk-popup__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'پخش‌کننده ویدیوی بررسی محصول', 'negar-shop' ); ?>">
							<div class="videos-nk-popup__stage">
								<span class="videos-nk-popup__accent" aria-hidden="true"></span>
								<button type="button" class="videos-nk-popup__close" data-videos-nk-close aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>">
									<svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true" focusable="false">
										<path d="M1.5 1.5l9 9M10.5 1.5l-9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
									</svg>
								</button>
								<div class="videos-nk-popup__player" data-videos-nk-player></div>
								<div class="videos-nk-popup__footer" data-videos-nk-footer hidden>
									<div class="videos-nk-popup__meta">
										<span class="videos-nk-popup__title" data-videos-nk-title></span>
										<span class="videos-nk-popup__product" data-videos-nk-product></span>
									</div>
									<a href="<?php echo esc_url( $shop_link ); ?>" class="videos-nk-popup__cta" data-videos-nk-cta>
										<?php esc_html_e( 'مشاهده و خرید', 'negar-shop' ); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</section>
		</div>
	</div>
</div>
