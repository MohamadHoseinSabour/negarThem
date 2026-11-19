<?php
/**
 * Customer Club Closing Conversion CTA Banner.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$cta_title  = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'cta_title', __( 'آشنایی با طرح‌ها و برآورد شرایط اقساطی', 'negar-shop' ) ) : __( 'آشنایی با طرح‌ها و برآورد شرایط اقساطی', 'negar-shop' );
$cta_sub    = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'cta_subtitle', '' ) : '';
$cta_button = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'cta_button_text', __( 'مشاهده طرح‌های اقساطی', 'negar-shop' ) ) : __( 'مشاهده طرح‌های اقساطی', 'negar-shop' );

if ( empty( $cta_sub ) ) {
	$cta_sub = __( 'شرایط و مبالغ طرح‌ها را بررسی فرمایید. فرآیند پذیرش درخواست‌ها پس از اتمام فاز اعتبارسنجی فعال خواهد شد.', 'negar-shop' );
}
?>

<section class="negar-club-cta" aria-labelledby="club-cta-heading">
	<div class="negar-club-cta__inner">
		<div class="negar-club-cta__content">
			<h2 id="club-cta-heading" class="negar-club-cta__title"><?php echo esc_html( $cta_title ); ?></h2>
			<p class="negar-club-cta__subtitle"><?php echo esc_html( $cta_sub ); ?></p>
		</div>
		<div class="negar-club-cta__actions">
			<a href="#club-plans" class="negar-club-btn negar-club-btn--primary">
				<span><?php echo esc_html( $cta_button ); ?></span>
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="negar-club-btn negar-club-btn--outline">
				<span><?php esc_html_e( 'تماس با کارشناسان', 'negar-shop' ); ?></span>
			</a>
		</div>
	</div>
</section>
