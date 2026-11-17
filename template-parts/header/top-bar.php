<?php
/**
 * Top Bar Template Part - 100% Styled to match Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$topbar_enabled = (int) negar_theme_setting( 'header', 'topbar_enabled', 1 );
if ( 0 === $topbar_enabled ) {
	return;
}

// 1. Text with background (pill / badge)
$tagline = (string) negar_theme_setting( 'header', 'topbar_tagline', '' );
if ( '' === $tagline ) {
	$tagline = (string) negar_theme_text( 'header', 'topbar_tagline' );
}
if ( '' === $tagline ) {
	$tagline = __( 'خوش‌پوش یلدا باش', 'negar-shop' );
}

// 2. Text without background (simple text)
$announcement = (string) negar_theme_setting( 'header', 'announcement', '' );
if ( '' === $announcement ) {
	$announcement = (string) negar_theme_text( 'header', 'topbar_announcement' );
}
if ( '' === $announcement ) {
	$announcement = __( 'هر چیزی که برای استایل کردن لازمه', 'negar-shop' );
}
$announcement = apply_filters( 'negar_shop_topbar_announcement', $announcement );

// 3. Icons (customizable, demo default: 🌙 on right, 🍉 on left)
$icon_right = negar_theme_setting( 'header', 'topbar_icon_right', '🌙' );
$icon_left  = negar_theme_setting( 'header', 'topbar_icon_left', '🍉' );

// 4. Link
$topbar_link = (string) negar_theme_setting( 'header', 'topbar_link', '' );
if ( empty( $topbar_link ) ) {
	$topbar_link = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}
?>

<div class="negar-topbar bg-gradient-to-r from-rose-800 via-rose-700 to-rose-800 text-white text-xs sm:text-sm py-0.5 px-0 shadow-xs relative overflow-hidden">
	<div class="container flex items-center justify-center relative z-10">
		
		<!-- Centered Announcement Banner -->
		<a href="<?php echo esc_url( $topbar_link ); ?>" class="topbar-announcement flex items-center justify-center gap-2.5 sm:gap-4 hover:opacity-95 transition group py-0.5">
			
			<!-- Moon / First Icon (Right in RTL) -->
			<?php if ( '' !== trim( (string) $icon_right ) ) : ?>
				<span class="negar-topbar-icon topbar-icon-start text-sm sm:text-base opacity-90 select-none inline-flex items-center leading-none"><?php echo esc_html( $icon_right ); ?></span>
			<?php endif; ?>

			<!-- Pill (with background): خوش‌پوش یلدا باش -->
			<?php if ( '' !== trim( $tagline ) ) : ?>
				<span class="inline-flex items-center justify-center bg-white/20 hover:bg-white/30 text-white text-xs sm:text-sm font-black px-3.5 py-0.5 rounded-full backdrop-blur-xs shadow-xs tracking-wide">
					<?php echo esc_html( $tagline ); ?>
				</span>
			<?php endif; ?>

			<?php if ( '' !== trim( $tagline ) && '' !== trim( $announcement ) ) : ?>
				<span class="text-white/60 font-light select-none">|</span>
			<?php endif; ?>

			<!-- Text (without background): هر چیزی که برای استایل کردن لازمه -->
			<?php if ( '' !== trim( $announcement ) ) : ?>
				<span class="text-white/95 font-semibold text-xs sm:text-sm md:text-base tracking-tight">
					<?php echo esc_html( $announcement ); ?>
				</span>
			<?php endif; ?>

			<!-- Watermelon / Second Icon (Left in RTL) -->
			<?php if ( '' !== trim( (string) $icon_left ) ) : ?>
				<span class="negar-topbar-icon topbar-icon-end text-sm sm:text-base select-none inline-flex items-center leading-none"><?php echo esc_html( $icon_left ); ?></span>
			<?php endif; ?>

		</a>

	</div>
</div>
