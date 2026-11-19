<?php
/**
 * Customer Club Hero Section.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$hero_title    = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'hero_title', __( 'باشگاه مشتریان و خرید اقساطی نگار', 'negar-shop' ) ) : __( 'باشگاه مشتریان و خرید اقساطی نگار', 'negar-shop' );
$hero_subtitle = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'hero_subtitle', '' ) : '';
$hero_cta      = function_exists( 'negar_theme_club_setting' ) ? (string) negar_theme_club_setting( 'copy', 'hero_cta_text', __( 'مشاهده و انتخاب طرح‌ها', 'negar-shop' ) ) : __( 'مشاهده و انتخاب طرح‌ها', 'negar-shop' );

if ( empty( $hero_subtitle ) ) {
	$hero_subtitle = __( 'آشنایی با شرایط و برآورد اولیه‌ی طرح‌های خرید اقساطی لوازم خانگی و پیگیری وضعیت عضویت در باشگاه مشتریان.', 'negar-shop' );
}
?>

<section class="negar-club-hero" aria-labelledby="club-hero-heading">
	<div class="negar-club-hero__inner">
		<div class="negar-club-hero__content">
			<div class="negar-club-hero__badge">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
				<span><?php esc_html_e( 'باشگاه اعضای نگار شاپ', 'negar-shop' ); ?></span>
			</div>
			<h1 id="club-hero-heading" class="negar-club-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
			<p class="negar-club-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<div class="negar-club-hero__actions">
				<a href="#club-plans" class="negar-club-btn negar-club-btn--primary">
					<span><?php echo esc_html( $hero_cta ); ?></span>
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
				<a href="#club-calculator" class="negar-club-btn negar-club-btn--outline">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="2" width="16" height="20" rx="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="8" y1="10" x2="16" y2="10"></line><line x1="8" y1="14" x2="10" y2="14"></line><line x1="14" y1="14" x2="16" y2="14"></line><line x1="8" y1="18" x2="10" y2="18"></line><line x1="14" y1="18" x2="16" y2="18"></line></svg>
					<span><?php esc_html_e( 'محاسبه‌گر پیش‌نمایش اقساط', 'negar-shop' ); ?></span>
				</a>
			</div>
			<div class="negar-club-hero__trust">
				<div class="negar-club-hero__trust-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
					<span><?php esc_html_e( 'برآورد شفاف و دقیق مبالغ', 'negar-shop' ); ?></span>
				</div>
				<div class="negar-club-hero__trust-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
					<span><?php esc_html_e( '۳ طرح متنوع متناسب با بودجه', 'negar-shop' ); ?></span>
				</div>
				<div class="negar-club-hero__trust-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
					<span><?php esc_html_e( 'مشاهده آنلاین وضعیت سررسیدها', 'negar-shop' ); ?></span>
				</div>
			</div>
		</div>
		<div class="negar-club-hero__visual" aria-hidden="true">
			<div class="negar-club-hero__card-preview">
				<div class="negar-club-hero__card-top">
					<div class="negar-club-hero__card-logo">
						<span class="negar-club-hero__card-dot"></span>
						<strong><?php esc_html_e( 'کارت اعتباری باشگاه', 'negar-shop' ); ?></strong>
					</div>
					<span class="negar-club-hero__card-tier"><?php esc_html_e( 'نگار پلاس', 'negar-shop' ); ?></span>
				</div>
				<div class="negar-club-hero__card-chip"></div>
				<div class="negar-club-hero__card-numbers">•••• •••• •••• ۹۸۴۲</div>
				<div class="negar-club-hero__card-bottom">
					<div>
						<span><?php esc_html_e( 'سقف اعتبار برآورد', 'negar-shop' ); ?></span>
						<strong>۱۰,۰۰۰,۰۰۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
					</div>
					<div>
						<span><?php esc_html_e( 'بازپرداخت', 'negar-shop' ); ?></span>
						<strong>۶ <?php esc_html_e( 'ماهه', 'negar-shop' ); ?></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
