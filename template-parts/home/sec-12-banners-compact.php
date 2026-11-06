<?php
/**
 * Section 12: Dual Compact Promo Banners #3 (HOME-12)
 * Reference: Demo 43 Section 11 (Top: 5358px, Height: ~246px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$banner_1   = $upload_dir['baseurl'] . '/kaveh-demo/banner-04.png';
$banner_2   = $upload_dir['baseurl'] . '/kaveh-demo/banner-05.png';
?>

<section class="negar-sec-12-compact-banners py-4" aria-label="<?php esc_attr_e( 'بنرهای تبلیغاتی فشرده', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
			
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 block max-h-[220px]">
				<img src="<?php echo esc_url( $banner_1 ); ?>" alt="<?php esc_attr_e( 'تخفیف ویژه کالای دیجیتال', 'negar-shop' ); ?>" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
			</a>

			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 block max-h-[220px]">
				<img src="<?php echo esc_url( $banner_2 ); ?>" alt="<?php esc_attr_e( 'پیشنهاد خرید لوازم جانبی', 'negar-shop' ); ?>" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">
			</a>

		</div>
	</div>
</section>
