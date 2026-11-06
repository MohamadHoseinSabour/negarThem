<?php
/**
 * Section 10: 2-Column Banner Grid #2 (HOME-10)
 * Reference: Demo 43 Section 9 (Top: 4361px, Height: ~274px)
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$banner_1   = $upload_dir['baseurl'] . '/kaveh-demo/banner-01.png';
$banner_2   = $upload_dir['baseurl'] . '/kaveh-demo/banner-02-1.png';
?>

<section class="negar-sec-10-banners py-4" aria-label="<?php esc_attr_e( 'بنرهای تخفیف دو ستونه', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
			
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 block aspect-[2/1] sm:aspect-[2.3/1] bg-slate-100">
				<img src="<?php echo esc_url( $banner_1 ); ?>" alt="<?php esc_attr_e( 'تخفیف ویژه اول', 'negar-shop' ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
			</a>

			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 block aspect-[2/1] sm:aspect-[2.3/1] bg-slate-100">
				<img src="<?php echo esc_url( $banner_2 ); ?>" alt="<?php esc_attr_e( 'تخفیف ویژه دوم', 'negar-shop' ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
			</a>

		</div>
	</div>
</section>
