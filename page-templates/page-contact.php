<?php
/**
 * Template Name: Contact Us / تماس با ما
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

$default_address = __( 'نشانی فروشگاه شما؛ قابل ویرایش از سفارشی‌سازی', 'negar-shop' );
$default_hours   = __( 'ساعت پاسخ‌گویی خود را اینجا وارد کنید', 'negar-shop' );
$phone           = trim( (string) negar_theme_setting( 'contact', 'phone', '', 'negar_shop_phone_hotline' ) );
$email           = trim( (string) negar_theme_setting( 'contact', 'email', 'contact@example.com', 'negar_shop_contact_email' ) );
$address         = trim( (string) negar_theme_setting( 'contact', 'address', $default_address, 'negar_shop_contact_address' ) );
$hours           = trim( (string) negar_theme_setting( 'contact', 'hours', $default_hours, 'negar_shop_contact_hours' ) );
$map_url         = trim( (string) negar_theme_setting( 'contact', 'map_url', '', 'negar_shop_contact_map_url' ) );
$map_open_url    = str_replace( '/maps/iframe/', '/maps/', $map_url );
if ( preg_match( '/marker=([0-9.\-]+)(?:,|%2C)([0-9.\-]+)/i', $map_url, $coords ) ) {
	$map_open_url = ( '31.3207561' === $coords[1] && '48.6837418' === $coords[2] )
		? 'https://nshn.ir/cf_bkRkyGBbPau'
		: sprintf( 'https://neshan.org/maps/@%s,%s,17z', $coords[1], $coords[2] );
}
$phone_href      = function_exists( 'negar_shop_phone_href' ) ? negar_shop_phone_href( $phone ) : preg_replace( '/[^0-9+]/', '', $phone );
$email_is_sample   = 'contact@example.com' === strtolower( $email );
$hours_is_sample   = '' === $hours || $hours === $default_hours;
$address_is_sample = '' === $address || $address === $default_address;

get_header();
?>

<main id="primary" class="site-main negar-content-page negar-contact-page">
	<div class="container">
		<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>">
			<ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li><li class="separator" aria-hidden="true">/</li><li class="current" aria-current="page"><?php the_title(); ?></li></ol>
		</nav>

		<section class="negar-showcase-hero">
			<div>
				<span class="negar-showcase-hero__eyebrow"><i class="icon-comments-2" aria-hidden="true"></i><?php echo esc_html( negar_theme_text( 'contact', 'hero_eyebrow' ) ); ?></span>
				<h1><?php echo esc_html( negar_theme_text( 'contact', 'hero_title' ) ); ?></h1>
				<p><?php echo esc_html( negar_theme_text( 'contact', 'hero_description' ) ); ?></p>
			</div>
			<div class="negar-showcase-hero__mark" aria-hidden="true"><i class="icon-phone"></i></div>
		</section>

		<section class="negar-contact-grid" aria-label="<?php esc_attr_e( 'راه‌های تماس', 'negar-shop' ); ?>">
			<?php if ( $phone && $phone_href ) : ?>
				<a class="negar-contact-card" href="tel:<?php echo esc_attr( $phone_href ); ?>"><span class="negar-contact-card__icon"><i class="icon-phone" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'phone_card_label' ) ); ?></b><strong dir="ltr"><?php echo esc_html( $phone ); ?></strong></span></a>
			<?php else : ?>
				<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-phone" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'phone_card_label' ) ); ?></b><strong><?php esc_html_e( 'شمارهٔ تماس را در سفارشی‌سازی وارد کنید', 'negar-shop' ); ?></strong></span></div>
			<?php endif; ?>
			<?php if ( is_email( $email ) && ! $email_is_sample ) : ?>
				<a class="negar-contact-card" href="mailto:<?php echo esc_attr( $email ); ?>"><span class="negar-contact-card__icon"><i class="icon-email" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'email_card_label' ) ); ?></b><strong dir="ltr"><?php echo esc_html( $email ); ?></strong></span></a>
			<?php else : ?>
				<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-email" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'email_card_label' ) ); ?></b><strong dir="ltr"><?php echo esc_html( $email ); ?></strong><em><?php esc_html_e( 'نمونهٔ قابل ویرایش', 'negar-shop' ); ?></em></span></div>
			<?php endif; ?>
			<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-calendar" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'hours_card_label' ) ); ?></b><strong><?php echo esc_html( $hours ); ?></strong><?php if ( $hours_is_sample ) : ?><em><?php esc_html_e( 'قابل ویرایش', 'negar-shop' ); ?></em><?php endif; ?></span></div>
			<div class="negar-contact-card"><span class="negar-contact-card__icon"><i class="icon-location" aria-hidden="true"></i></span><span><b><?php echo esc_html( negar_theme_text( 'contact', 'address_card_label' ) ); ?></b><strong><?php echo esc_html( $address ); ?></strong><?php if ( $address_is_sample ) : ?><em><?php esc_html_e( 'قابل ویرایش', 'negar-shop' ); ?></em><?php endif; ?></span></div>
		</section>

		<section class="negar-contact-layout">
			<div class="negar-contact-form-box">
				<div class="negar-section-heading"><span><i class="icon-comments" aria-hidden="true"></i></span><div><h2><?php echo esc_html( negar_theme_text( 'contact', 'form_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'contact', 'form_description' ) ); ?></p></div></div>
				<form id="negar-contact-form" novalidate>
					<p id="negar-contact-alert" class="hidden" role="status" aria-live="polite"></p>
					<div class="negar-contact-form__fields">
						<p><label for="contact-name"><?php esc_html_e( 'نام و نام خانوادگی', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="text" id="contact-name" name="name" autocomplete="name" required></p>
						<p><label for="contact-phone"><?php esc_html_e( 'شمارهٔ موبایل', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="tel" id="contact-phone" name="phone" dir="ltr" inputmode="tel" autocomplete="tel" required></p>
						<p class="negar-contact-form__wide"><label for="contact-subject"><?php esc_html_e( 'موضوع پیام یا شمارهٔ سفارش', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><input type="text" id="contact-subject" name="subject" required></p>
						<p class="negar-contact-form__wide"><label for="contact-message"><?php esc_html_e( 'متن پیام', 'negar-shop' ); ?> <span aria-hidden="true">*</span></label><textarea id="contact-message" name="message" rows="6" required></textarea></p>
					</div>
					<button type="submit" id="negar-contact-submit" class="negar-contact-btn"><span><?php echo esc_html( negar_theme_text( 'contact', 'form_submit' ) ); ?></span><i class="icon-arrow-left" aria-hidden="true"></i><span class="contact-spinner hidden animate-spin" aria-hidden="true"></span></button>
				</form>
			</div>

			<aside class="negar-map-box">
				<div class="negar-section-heading"><span><i class="icon-location" aria-hidden="true"></i></span><div><h2><?php echo esc_html( negar_theme_text( 'contact', 'map_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'contact', 'map_description' ) ); ?></p></div></div>
				<div class="negar-map-frame">
					<?php if ( $map_url ) : ?>
						<iframe src="<?php echo esc_url( $map_url ); ?>" title="<?php esc_attr_e( 'نقشهٔ فروشگاه', 'negar-shop' ); ?>" loading="lazy" allowfullscreen></iframe>
					<?php else : ?>
						<div class="negar-map-placeholder"><i class="icon-location" aria-hidden="true"></i><strong><?php echo esc_html( negar_theme_text( 'contact', 'map_placeholder_title' ) ); ?></strong><span><?php echo esc_html( negar_theme_text( 'contact', 'map_placeholder_description' ) ); ?></span></div>
					<?php endif; ?>
				</div>
				<?php if ( $map_open_url ) : ?>
					<p class="mt-3 mb-0 text-center">
						<a href="<?php echo esc_url( $map_open_url ); ?>" target="_blank" rel="noopener noreferrer" class="negar-contact-btn" style="display:inline-flex;justify-content:center;width:100%;text-decoration:none;">
							<span><?php esc_html_e( 'مسیریابی روی نقشه نشان', 'negar-shop' ); ?></span>
							<i class="icon-arrow-left" aria-hidden="true"></i>
						</a>
					</p>
				<?php endif; ?>
			</aside>
		</section>
	</div>
</main>

<?php get_footer(); ?>
