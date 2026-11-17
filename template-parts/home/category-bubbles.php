<?php
/**
 * Category Bubbles Component - Selected Main Categories (Home Appliances)
 * Reference: Demo 43 Section 2
 *
 * @package Negar_Shop
 * @version 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_link = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Initial 6 storefront priority main categories in specified order
$priority_categories = array(
	'major-appliances'    => array(
		'fallback_name' => 'لوازم خانگی سنگین',
		'fallback_img'  => 'cat-1793876649-728.png',
	),
	'electric-cooking'    => array(
		'fallback_name' => 'لوازم برقی پختوپز',
		'fallback_img'  => 'cat-1793876649-179.png',
	),
	'food-preparation'    => array(
		'fallback_name' => 'لوازم آمادهسازی غذا',
		'fallback_img'  => 'cat-1793876649-992.png',
	),
	'beverage-breakfast'  => array(
		'fallback_name' => 'نوشیدنیساز و صبحانه',
		'fallback_img'  => 'cat-1793876650-373.png',
	),
	'cleaning-ironing'    => array(
		'fallback_name' => 'نظافت و اتوکشی',
		'fallback_img'  => 'cat-1793876650-919.png',
	),
	'cooling-heating-air' => array(
		'fallback_name' => 'سرمایش، گرمایش و تهویه',
		'fallback_img'  => 'cat-1793876649-597.png',
	),
);

$upload_dir = wp_upload_dir();
$upload_base = $upload_dir['baseurl'] . '/2026/11/';

$bubbles = array();
foreach ( $priority_categories as $slug => $meta ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	$name = ( $term && ! is_wp_error( $term ) ) ? $term->name : $meta['fallback_name'];
	$link = ( $term && ! is_wp_error( $term ) ) ? get_term_link( $term ) : $shop_link;

	$image_url = '';
	if ( $term && ! is_wp_error( $term ) ) {
		$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $thumb_id ) {
			$image_url = wp_get_attachment_image_url( $thumb_id, 'full' );
		}
	}
	if ( empty( $image_url ) ) {
		$image_url = $upload_base . $meta['fallback_img'];
	}

	$bubbles[] = array(
		'slug'  => $slug,
		'name'  => $name,
		'link'  => $link,
		'image' => $image_url,
	);
}

$category_icons = array(
	'major-appliances'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="3"></rect><line x1="5" y1="10" x2="19" y2="10"></line><line x1="9" y1="6" x2="9" y2="7.5"></line><line x1="9" y1="13.5" x2="9" y2="16.5"></line></svg>',
	'electric-cooking'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"></rect><rect x="6.5" y="8.5" width="8.5" height="7" rx="1"></rect><circle cx="18" cy="9.5" r="0.8" fill="currentColor" stroke="none"></circle><circle cx="18" cy="13" r="0.8" fill="currentColor" stroke="none"></circle><line x1="6" y1="19" x2="6" y2="20.5"></line><line x1="18" y1="19" x2="18" y2="20.5"></line></svg>',
	'food-preparation'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><path d="M7 4h10l-1.5 9.5a2 2 0 0 1-2 1.5h-3a2 2 0 0 1-2-1.5L7 4Z"></path><path d="M17 6h2a1.5 1.5 0 0 1 1.5 1.5v2A1.5 1.5 0 0 1 19 11h-2.3"></path><path d="M8 15h8l1 5H7l1-5Z"></path><line x1="10" y1="2" x2="14" y2="2"></line></svg>',
	'beverage-breakfast'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><path d="M5 10h11v5a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5v-5Z"></path><path d="M16 11h2a2.5 2.5 0 0 1 0 5h-2.2"></path><path d="M8 6.5c0-1 .8-1.5.8-2.5"></path><path d="M12 6.5c0-1 .8-1.5.8-2.5"></path></svg>',
	'cleaning-ironing'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6L12 3Z"></path><path d="M18.5 15.5l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8.8-2.2Z"></path><path d="M5.5 15l.7 1.8L8 17.5l-1.8.7-.7 1.8-.7-1.8L3 17.5l1.8-.7.7-1.8Z"></path></svg>',
	'cooling-heating-air' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h11a3 3 0 1 0-3-3"></path><path d="M3 12h15a3 3 0 1 1-3 3"></path><path d="M3 16h8a2.5 2.5 0 1 1-2.5 2.5"></path></svg>',
);
$default_icon = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect></svg>';
?>
<div class="elementor-element elementor-element-619bae2e e-con-full e-flex e-con e-parent e-lazyloaded" data-id="619bae2e" data-element_type="container" data-e-type="container">
	<div class="elementor-element elementor-element-57191199 elementor-widget elementor-widget-categoriesnkkaveh" data-id="57191199" data-element_type="widget" data-e-type="widget" data-widget_type="categoriesnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="categories-nk categories-nk-57191199 my-10 lg:my-16">
				<div class="container">
					<div class="section-heading-one-nk d-flex align-items-center gap-2">
						<div class="title f-iran-yekan">
							<span class="fw-bold">دسته‌بندی‌های</span> منتخب
						</div>

						<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'ضمانت اصالت و برترین برندهای لوازم خانگی', 'negar-shop' ); ?></div>

						<div class="line flex-grow-1 d-flex align-items-center gap-1">
							<span class="flex-grow-1"></span>
							<span></span>
						</div>

						<a href="<?php echo esc_url( $shop_link ); ?>" class="btn btn-nk-danger d-flex align-items-center justify-content-center fw-normal position-relative">
							<?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?>
							<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
						</a>
					</div>

					<div class="swiper swiper-categories-nk swiper-visible swiper-visibled-mobile overflow-hidden overflow-visible-xs swiper-horizontal swiper-rtl" data-swiper='{"watchSlidesProgress":true,"watchOverflow":true,"breakpoints":{"320":{"slidesPerView":2.1,"spaceBetween":12},"576":{"slidesPerView":3,"spaceBetween":14},"768":{"slidesPerView":3.2,"spaceBetween":16},"992":{"slidesPerView":5,"spaceBetween":16},"1200":{"slidesPerView":5,"spaceBetween":18}}}'>
						<div class="swiper-wrapper" aria-live="polite">
							<?php
							$total_bubbles = count( $bubbles );
							foreach ( $bubbles as $idx => $bubble ) :
								$slide_index   = $idx + 1;
								$is_red_card   = ( 1 === ( $idx % 2 ) );
								$variant_class = $is_red_card ? 'categories-nk__item--red' : 'categories-nk__item--white';
								$icon_svg      = isset( $category_icons[ $bubble['slug'] ] ) ? $category_icons[ $bubble['slug'] ] : $default_icon;
								?>
								<div class="swiper-slide" role="group" aria-label="<?php echo esc_attr( "{$slide_index} / {$total_bubbles}" ); ?>">
									<article class="categories-nk__item <?php echo esc_attr( $variant_class ); ?>">
										<a href="<?php echo esc_url( $bubble['link'] ); ?>" class="categories-nk__link" aria-label="<?php echo esc_attr( $bubble['name'] ); ?>">
											<span class="categories-nk__icon" aria-hidden="true">
												<?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static trusted SVG icon markup. ?>
											</span>

											<div class="categories-nk__media">
												<img loading="lazy" decoding="async" src="<?php echo esc_url( $bubble['image'] ); ?>" alt="<?php echo esc_attr( $bubble['name'] ); ?>" width="184" height="152">
											</div>

											<h2 class="categories-nk__title f-iran-yekan"><?php echo esc_html( $bubble['name'] ); ?></h2>

											<div class="categories-nk__footer" aria-hidden="true">
												<span class="categories-nk__arrow">
													<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
														<polyline points="15 18 9 12 15 6"></polyline>
													</svg>
												</span>
											</div>
										</a>
									</article>
								</div>
							<?php endforeach; ?>
						</div>
						<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
