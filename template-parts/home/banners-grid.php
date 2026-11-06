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

<section class="negar-banners-grid-section py-3" aria-label="<?php esc_attr_e( 'بنرهای تبلیغاتی دو ستونه اول', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

			<!-- Banner 1 -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-3xl overflow-hidden block h-[190px] sm:h-[220px] md:h-[240px] lg:h-[250px] max-h-[250px] shadow-xs hover:shadow-lg transition-all duration-300 bg-slate-100">
				<img
					src="<?php echo esc_url( $banner_base . '/banner-01.png' ); ?>"
					alt="<?php esc_attr_e( 'تخفیف ویژه کنسول و بازی', 'negar-shop' ); ?>"
					class="w-full h-full object-cover object-center group-hover:scale-102 transition-transform duration-500"
					loading="lazy"
				>
			</a>

			<!-- Banner 2 -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="relative group rounded-3xl overflow-hidden block h-[190px] sm:h-[220px] md:h-[240px] lg:h-[250px] max-h-[250px] shadow-xs hover:shadow-lg transition-all duration-300 bg-slate-100">
				<img
					src="<?php echo esc_url( $banner_base . '/banner-02-1.png' ); ?>"
					alt="<?php esc_attr_e( 'جشنواره خرید دیجیتال و موبایل', 'negar-shop' ); ?>"
					class="w-full h-full object-cover object-center group-hover:scale-102 transition-transform duration-500"
					loading="lazy"
				>
			</a>

		</div>
	</div>
</section>
