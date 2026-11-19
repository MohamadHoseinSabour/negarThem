<?php
/**
 * Customer Club Disabled View (when is_enabled is false).
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$disabled_notice = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'disabled_notice', '' ) : '';

if ( empty( $disabled_notice ) ) {
	$disabled_notice = __( 'بخش باشگاه مشتریان در حال حاضر در دست پیکربندی و به‌روزرسانی زیرساخت‌های اعتبارسنجی است.', 'negar-shop' );
}
?>

<div class="negar-club-disabled-state" role="region" aria-label="<?php esc_attr_e( 'وضعیت باشگاه مشتریان', 'negar-shop' ); ?>">
	<div class="negar-club-disabled-state__card">
		<div class="negar-club-disabled-state__icon" aria-hidden="true">
			<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
		</div>
		<h1 class="negar-club-disabled-state__title"><?php esc_html_e( 'باشگاه مشتریان نگار', 'negar-shop' ); ?></h1>
		<p class="negar-club-disabled-state__notice"><?php echo esc_html( $disabled_notice ); ?></p>
		<div class="negar-club-disabled-state__actions">
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>" class="negar-club-btn negar-club-btn--primary">
				<span><?php esc_html_e( 'بازگشت به فروشگاه', 'negar-shop' ); ?></span>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="negar-club-btn negar-club-btn--outline">
				<span><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></span>
			</a>
		</div>
	</div>
</div>
