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

$banner = negar_theme_banner_item_data( 'wide-banner-one' );
if ( null === $banner ) {
	return;
}
?>

<section class="negar-sec-08-wide-banner py-4" aria-label="<?php esc_attr_e( 'بنر ویژه تبلیغاتی', 'negar-shop' ); ?>" data-negar-banner-record="wide-banner-one">
	<div class="container max-w-[1320px] mx-auto px-3">
		<a href="<?php echo esc_url( $banner['link'] ); ?>" class="block rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
			<img src="<?php echo esc_url( $banner['image_url'] ); ?>" alt="<?php echo esc_attr( $banner['alt'] ); ?>" class="w-full h-auto object-cover max-h-[280px]">
		</a>
	</div>
</section>
