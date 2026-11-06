<?php
/**
 * Promotional Middle Banners Grid Component #1 (HOME-06)
 * Reference: Demo 43 Section 5 (Top: 2603px, Height: ~274px)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir  = wp_upload_dir();
$banner_base = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<?php
/**
 * Promotional Middle Banners Grid Component #1 (HOME-06)
 * Reference: Demo 43 Section 5 (.elementor-element-4e969361)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir  = wp_upload_dir();
$banner_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_link   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<div class="elementor-element elementor-element-4e969361 e-flex e-con-boxed e-con e-parent e-lazyloaded my-6" data-id="4e969361" data-element_type="container" data-e-type="container">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

			<!-- Banner 1: Headphones (Right Column in RTL) -->
			<div class="elementor-element elementor-element-1e016233 e-con-full e-flex e-con e-child" data-id="1e016233" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-60d1115b elementor-widget elementor-widget-bannernkkaveh w-full" data-id="60d1115b" data-element_type="widget" data-widget_type="bannernkkaveh.default">
					<div class="elementor-widget-container">
						<section class="banners-nk banners-nk-60d1115b">
							<div class="banners-nk__item position-relative">
								<div class="detail">
									<a href="<?php echo esc_url( $shop_link ); ?>" class="more d-flex align-items-center justify-content-center rounded-pill" aria-label="<?php esc_attr_e( 'مشاهده بیشتر', 'negar-shop' ); ?>">
										<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
									</a>
									<div class="title f-iran-yekan fw-bold">
										<?php esc_html_e( 'محل قرار گیری بنر', 'negar-shop' ); ?>
									</div>
									<div class="sub-title f-iran-yekan fw-normal">
										<?php esc_html_e( 'همین الان با تخفیف خرید کنید', 'negar-shop' ); ?>
									</div>
								</div>
								<img src="<?php echo esc_url( $banner_base . '/banner-01.png' ); ?>" alt="<?php esc_attr_e( 'محل قرار گیری بنر', 'negar-shop' ); ?>" width="218" height="245" class="banner-image position-absolute bottom-0" loading="lazy">
							</div>
						</section>
					</div>
				</div>
			</div>

			<!-- Banner 2: Powerbank (Left Column in RTL) -->
			<div class="elementor-element elementor-element-71ced218 e-con-full e-flex e-con e-child" data-id="71ced218" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-447c3bc1 elementor-widget elementor-widget-bannernkkaveh w-full" data-id="447c3bc1" data-element_type="widget" data-widget_type="bannernkkaveh.default">
					<div class="elementor-widget-container">
						<section class="banners-nk banners-nk-447c3bc1">
							<div class="banners-nk__item position-relative">
								<div class="detail">
									<a href="<?php echo esc_url( $shop_link ); ?>" class="more d-flex align-items-center justify-content-center rounded-pill" aria-label="<?php esc_attr_e( 'مشاهده بیشتر', 'negar-shop' ); ?>">
										<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
									</a>
									<div class="title f-iran-yekan fw-bold">
										<?php esc_html_e( 'محل قرار گیری بنر', 'negar-shop' ); ?>
									</div>
									<div class="sub-title f-iran-yekan fw-normal">
										<?php esc_html_e( 'همین الان با تخفیف خرید کنید', 'negar-shop' ); ?>
									</div>
								</div>
								<img src="<?php echo esc_url( $banner_base . '/banner-02-1.png' ); ?>" alt="<?php esc_attr_e( 'محل قرار گیری بنر', 'negar-shop' ); ?>" width="218" height="245" class="banner-image position-absolute bottom-0" loading="lazy">
							</div>
						</section>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
