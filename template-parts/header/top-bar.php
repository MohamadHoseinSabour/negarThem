<?php
/**
 * Top Bar Template Part - 100% Styled to match Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$announcement = get_theme_mod( 'negar_shop_topbar_announcement', 'هر چیزی که برای استایل کردن لازمه' );
$upload_dir   = wp_upload_dir();
$banner_img   = $upload_dir['basedir'] . '/kaveh-demo/kaveh-demo/banners/header-banner-1.png';
$banner_url   = $upload_dir['baseurl'] . '/kaveh-demo/kaveh-demo/banners/header-banner-1.png';
$has_banner   = file_exists( $banner_img );
?>

<div class="negar-topbar bg-gradient-to-r from-rose-800 via-rose-700 to-rose-800 text-white text-xs sm:text-sm py-2 px-0 shadow-xs relative overflow-hidden">
	<div class="container flex items-center justify-center relative z-10">
		
		<!-- Centered Announcement Banner matching Demo Screenshot -->
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="topbar-announcement flex items-center justify-center gap-4 sm:gap-6 hover:opacity-95 transition group py-0.5">
			
			<!-- Moon Icon (Left) -->
			<span class="text-base opacity-90 select-none">🌙</span>

			<!-- Center Pill: خوش‌پوش یلدا باش -->
			<span class="inline-flex items-center justify-center bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-black px-5 py-1 rounded-full backdrop-blur-xs shadow-xs tracking-wide">
				<?php esc_html_e( 'خوش‌پوش یلدا باش', 'negar-shop' ); ?>
			</span>

			<span class="text-white/60 font-light select-none">|</span>

			<!-- Text: هر چیزی که برای استایل کردن لازمه -->
			<span class="text-white/95 font-semibold text-xs sm:text-sm md:text-base tracking-tight">
				<?php echo esc_html( apply_filters( 'negar_shop_topbar_announcement', $announcement ) ); ?>
			</span>

			<!-- Watermelon Icon (Right) -->
			<span class="text-lg select-none">🍉</span>

		</a>

	</div>
</div>
