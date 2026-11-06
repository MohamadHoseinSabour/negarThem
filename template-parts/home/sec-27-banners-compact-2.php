<?php
/**
 * Section 27: Dual Compact Promo Banners #5 (HOME-27)
 * Exact 1:1 Implementation matching Kaveh Demo 43 Widget (elementor-element-3c70d07e)
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

<div class="elementor-element elementor-element-3c70d07e e-flex e-con-boxed e-con e-parent e-lazyloaded negar-dual-banners-asymmetric my-6" data-id="3c70d07e" data-element_type="container" data-e-type="container">
	<div class="e-con-inner container">
		
		<!-- Right Banner (Wider: 773px / 59%) -->
		<div class="elementor-element elementor-element-6feae589 elementor-widget elementor-widget-image" data-id="6feae589" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="d-block w-100 h-100 overflow-hidden rounded-3xl group shadow-xs hover:shadow-md transition-all duration-300">
				<img loading="lazy" decoding="async" width="773" height="228" src="<?php echo esc_url( $banner_1 ); ?>" class="attachment-full size-full w-100 h-auto d-block group-hover:scale-102 transition-transform duration-300" alt="<?php esc_attr_e( 'تخفیف ویژه اول', 'negar-shop' ); ?>">
			</a>
		</div>

		<!-- Left Banner (Narrower: 538px / 41%) -->
		<div class="elementor-element elementor-element-2a90c2db elementor-widget elementor-widget-image" data-id="2a90c2db" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="d-block w-100 h-100 overflow-hidden rounded-3xl group shadow-xs hover:shadow-md transition-all duration-300">
				<img loading="lazy" decoding="async" width="538" height="227" src="<?php echo esc_url( $banner_2 ); ?>" class="attachment-full size-full w-100 h-auto d-block group-hover:scale-102 transition-transform duration-300" alt="<?php esc_attr_e( 'تخفیف ویژه دوم', 'negar-shop' ); ?>">
			</a>
		</div>

	</div>
</div>
