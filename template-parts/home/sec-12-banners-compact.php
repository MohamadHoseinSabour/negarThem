<?php
/**
 * Section 12: Dual Compact Promo Banners #3 (HOME-12)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (elementor-element-26635d31)
 * Right banner: banner-04 (773px width, ~59%)
 * Left banner: banner-05 (538px width, ~41%)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$banner_1   = $upload_dir['baseurl'] . '/kaveh-demo/banner-04.png';
$banner_2   = $upload_dir['baseurl'] . '/kaveh-demo/banner-05.png';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<div class="elementor-element elementor-element-26635d31 e-flex e-con-boxed e-con e-parent e-lazyloaded negar-dual-banners-asymmetric my-6" data-id="26635d31" data-element_type="container" data-e-type="container">
	<div class="e-con-inner container">
		
		<!-- Right Banner (Wider: 773px / 59%) -->
		<div class="elementor-element elementor-element-57013a2f elementor-widget elementor-widget-image" data-id="57013a2f" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="d-block w-100 h-100 overflow-hidden rounded-3xl group shadow-xs hover:shadow-md transition-all duration-300">
				<img loading="lazy" decoding="async" width="773" height="228" src="<?php echo esc_url( $banner_1 ); ?>" class="attachment-full size-full w-100 h-auto d-block group-hover:scale-102 transition-transform duration-300" alt="<?php esc_attr_e( 'تخفیف ویژه کالای دیجیتال', 'negar-shop' ); ?>">
			</a>
		</div>

		<!-- Left Banner (Narrower: 538px / 41%) -->
		<div class="elementor-element elementor-element-2bf8a385 elementor-widget elementor-widget-image" data-id="2bf8a385" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="d-block w-100 h-100 overflow-hidden rounded-3xl group shadow-xs hover:shadow-md transition-all duration-300">
				<img loading="lazy" decoding="async" width="538" height="227" src="<?php echo esc_url( $banner_2 ); ?>" class="attachment-full size-full w-100 h-auto d-block group-hover:scale-102 transition-transform duration-300" alt="<?php esc_attr_e( 'پیشنهاد خرید لوازم جانبی', 'negar-shop' ); ?>">
			</a>
		</div>

	</div>
</div>
