<?php
/**
 * Template Name: Contact Us / تماس با ما
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

$phone       = trim( (string) get_theme_mod( 'negar_shop_phone_hotline', '' ) );
$email       = trim( (string) get_theme_mod( 'negar_shop_contact_email', 'contact@example.com' ) );
$address     = trim( (string) get_theme_mod( 'negar_shop_contact_address', __( 'نشانی فروشگاه شما؛ قابل ویرایش از سفارشی‌سازی', 'negar-shop' ) ) );
$hours       = trim( (string) get_theme_mod( 'negar_shop_contact_hours', __( 'ساعت پاسخ‌گویی خود را اینجا وارد کنید', 'negar-shop' ) ) );
$map_url     = trim( (string) get_theme_mod( 'negar_shop_contact_map_url', '' ) );
$phone_href  = function_exists( 'negar_shop_phone_href' ) ? negar_shop_phone_href( $phone ) : preg_replace( '/[^0-9+]/', '', $phone );
$email_is_sample = 'contact@example.com' === strtolower( $email );

get_header();
?>

<main id="primary" class="site-main negar-content-page negar-contact-page">
	<div class="container">
		<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>">
			<ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li><li class="separator" aria-hidden="true">/</li><li class="current" aria-current="page"><?php the_title(); ?></li></ol>
		</nav>

		<section class="negar-showcase-hero">
			<div>
				<span class="negar-showcase-hero__eyebrow"><i class="icon-comments-2" aria-hidden="true"></i><?php esc_html_e( 'ارتباط با فروشگاه', 'negar-shop' ); ?></span>
				<h1><?php esc_html_e( 'از اینجا با ما در تماس باشید', 'negar-shop' ); ?></h1>
				<p><?php esc_html_e( 'برای ارسال پیام یا مشاهدهٔ راه‌های ارتباطی، اطلاعات زیر را بررسی کنید. اطلاعات نمونه از بخش سفارشی‌سازی قابل تغییر هستند.', 'negar-shop' ); ?></p>
			</div>
			<div class="negar-showcase-hero__mark" aria-hidden="true"><i class="icon-phone"></i></div>
		</section>

		<section class="negar-contact-grid" aria-label="<?php esc_attr_e( 'راه‌های تماس', 'negar-shop' ); ?>">
			<?php if ( $phone && $phone_href ) : ?>
				<a class="negar-contact-card" href="tel:<?php echo esc_attr( $phone_href ); ?>"><span class="negar-contact-card__icon"><i class="icon-phone" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'تماس تلفنی', 'negar-shop' ); ?></b><strong dir="ltr"><?php echo esc_html( $phone ); ?></strong></span></a>
			<?php else : ?>
				<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-phone" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'تماس تلفنی', 'negar-shop' ); ?></b><strong><?php esc_html_e( 'شمارهٔ تماس را در سفارشی‌سازی وارد کنید', 'negar-shop' ); ?></strong></span></div>
			<?php endif; ?>
			<?php if ( is_email( $email ) && ! $email_is_sample ) : ?>
				<a class="negar-contact-card" href="mailto:<?php echo esc_attr( $email ); ?>"><span class="negar-contact-card__icon"><i class="icon-email" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'ایمیل', 'negar-shop' ); ?></b><strong dir="ltr"><?php echo esc_html( $email ); ?></strong></span></a>
			<?php else : ?>
				<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-email" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'ایمیل', 'negar-shop' ); ?></b><strong dir="ltr"><?php echo esc_html( $email ); ?></strong><em><?php esc_html_e( 'نمونهٔ قابل ویرایش', 'negar-shop' ); ?></em></span></div>
			<?php endif; ?>
			<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-calendar" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'ساعات پاسخ‌گویی', 'negar-shop' ); ?></b><strong><?php echo esc_html( $hours ); ?></strong><em><?php esc_html_e( 'قابل ویرایش', 'negar-shop' ); ?></em></span></div>
			<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-location" aria-hidden="true"></i></span><span><b><?php esc_html_e( 'نشانی', 'negar-shop' ); ?></b><strong><?php echo esc_html( $address ); ?></strong><em><?php esc_html_e( 'قابل ویرایش', 'negar-shop' ); ?></em></span></div>
		</section>

		<section class="negar-contact-layout">
			<div class="negar-contact-form-box">
				<div class="negar-section-heading"><span><i class="icon-comments" aria-hidden="true"></i></span><div><h2><?php esc_html_e( 'ارسال پیام', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'پیام شما پس از ثبت، به مدیریت سایت ارسال می‌شود.', 'negar-shop' ); ?></p></div></div>
				<form id="negar-contact-form" novalidate>
					<p id="negar-contact-alert" class="hidden" role="status" aria-live="polite"></p>
					<div class="negar-contact-form__fields">
						<p><label for="contact-name"><?php esc_html_e( 'نام و نام خانوادگی', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="text" id="contact-name" name="name" autocomplete="name" required></p>
						<p><label for="contact-phone"><?php esc_html_e( 'شمارهٔ موبایل', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="tel" id="contact-phone" name="phone" dir="ltr" inputmode="tel" autocomplete="tel" required></p>
						<p class="negar-contact-form__wide"><label for="contact-subject"><?php esc_html_e( 'موضوع پیام یا شمارهٔ سفارش', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="text" id="contact-subject" name="subject" required></p>
						<p class="negar-contact-form__wide"><label for="contact-message"><?php esc_html_e( 'متن پیام', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><textarea id="contact-message" name="message" rows="6" required></textarea></p>
					</div>
					<button type="submit" id="negar-contact-submit" class="negar-contact-btn"><span><?php esc_html_e( 'ثبت و ارسال پیام', 'negar-shop' ); ?></span><i class="icon-arrow-left" aria-hidden="true"></i><span class="contact-spinner hidden animate-spin" aria-hidden="true"></span></button>
				</form>
			</div>

			<aside class="negar-map-box">
				<div class="negar-section-heading"><span><i class="icon-location" aria-hidden="true"></i></span><div><h2><?php esc_html_e( 'موقعیت فروشگاه', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'نقشه پس از وارد کردن نشانی embed واقعی در سفارشی‌سازی نمایش داده می‌شود.', 'negar-shop' ); ?></p></div></div>
				<div class="negar-map-frame">
					<?php if ( $map_url ) : ?>
						<iframe src="<?php echo esc_url( $map_url ); ?>" title="<?php esc_attr_e( 'نقشهٔ فروشگاه', 'negar-shop' ); ?>" loading="lazy" allowfullscreen></iframe>
					<?php else : ?>
						<div class="negar-map-placeholder"><i class="icon-location" aria-hidden="true"></i><strong><?php esc_html_e( 'جای نقشهٔ فروشگاه', 'negar-shop' ); ?></strong><span><?php esc_html_e( 'نمونهٔ قابل ویرایش از سفارشی‌سازی', 'negar-shop' ); ?></span></div>
					<?php endif; ?>
				</div>
			</aside>
		</section>
	</div>
</main>

<?php get_footer(); ?>
