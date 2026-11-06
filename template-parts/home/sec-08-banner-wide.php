<?php
/**
 * Section 08: Full-Width Hero Promo Banner #1 (HOME-08)
 * Reference: Demo 43 Section 7 (Top: 3533px, Height: ~268px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$banner_url = $upload_dir['baseurl'] . '/kaveh-demo/banner-03.png';
?>

<section class="negar-sec-08-wide-banner py-4" aria-label="<?php esc_attr_e( 'بنر ویژه تبلیغاتی', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="block rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
			<img src="<?php echo esc_url( $banner_url ); ?>" alt="<?php esc_attr_e( 'پیشنهاد ویژه دیجیتال', 'negar-shop' ); ?>" class="w-full h-auto object-cover max-h-[280px]">
		</a>
	</div>
</section>
