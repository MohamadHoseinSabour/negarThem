<?php
/**
 * Promotional Middle Banners Grid Component (HOME-04) - Using Kaveh Demo Assets
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$banner_base = $upload_dir['baseurl'] . '/kaveh-demo/banners';
?>

<section class="negar-banners-grid-section py-6" aria-label="<?php esc_attr_e( 'بنرهای تبلیغاتی منتخب', 'negar-shop' ); ?>">
	<div class="container">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

			<!-- Banner 1 -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="banner-card group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.01] block">
				<img
					src="<?php echo esc_url( $banner_base . '/mid-banner-01.png' ); ?>"
					alt="<?php esc_attr_e( 'تخفیف ویژه کنسول و بازی', 'negar-shop' ); ?>"
					class="w-full h-auto object-cover"
					loading="lazy"
				>
			</a>

			<!-- Banner 2 -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="banner-card group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-[1.01] block">
				<img
					src="<?php echo esc_url( $banner_base . '/mid-banner-02.png' ); ?>"
					alt="<?php esc_attr_e( 'جشنواره خرید دیجیتال و موبایل', 'negar-shop' ); ?>"
					class="w-full h-auto object-cover"
					loading="lazy"
				>
			</a>

		</div>
	</div>
</section>
