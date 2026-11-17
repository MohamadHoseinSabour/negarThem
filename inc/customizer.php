<?php
/**
 * Theme Customizer Settings and Controls
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Theme Customizer Options.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function negar_shop_customize_register( $wp_customize ) {

	// --------------------------------------------------------------------------
	// Master Panel: Negar Shop Theme Settings
	// --------------------------------------------------------------------------
	$wp_customize->add_panel(
		'negar_shop_panel',
		array(
			'title'       => __( 'تنظیمات قالب نگار شاپ', 'negar-shop' ),
			'description' => __( 'مدیریت و سفارشی‌سازی رنگ‌بندی، هدر، شبکه‌های اجتماعی و فوتر فروشگاه', 'negar-shop' ),
			'priority'    => 25,
		)
	);

	// --------------------------------------------------------------------------
	// Section 1: Colors & Branding
	// --------------------------------------------------------------------------
	$wp_customize->add_section(
		'negar_shop_section_colors',
		array(
			'title'    => __( 'رنگ‌بندی فروشگاه', 'negar-shop' ),
			'panel'    => 'negar_shop_panel',
			'priority' => 10,
		)
	);

	// Primary Color
	$wp_customize->add_setting(
		'negar_shop_primary_color',
		array(
			'default'           => '#e90544',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'negar_shop_primary_color',
			array(
				'label'       => __( 'رنگ اصلی سازمانی (Primary)', 'negar-shop' ),
				'description' => __( 'رنگ پیش‌فرض دکمه‌ها، لینک‌ها و اِلمان‌های کلیدی', 'negar-shop' ),
				'section'     => 'negar_shop_section_colors',
				'settings'    => 'negar_shop_primary_color',
			)
		)
	);

	// Accent / Offer Color
	$wp_customize->add_setting(
		'negar_shop_accent_color',
		array(
			'default'           => '#c40439',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'negar_shop_accent_color',
			array(
				'label'       => __( 'رنگ تخفیف و پیشنهاد ویژه (Accent / Sale)', 'negar-shop' ),
				'description' => __( 'رنگ بج‌های تخفیف و باکس شگفت‌انگیزها', 'negar-shop' ),
				'section'     => 'negar_shop_section_colors',
				'settings'    => 'negar_shop_accent_color',
			)
		)
	);

	// --------------------------------------------------------------------------
	// Section 2: Header & Top Bar
	// --------------------------------------------------------------------------
	$wp_customize->add_section(
		'negar_shop_section_header',
		array(
			'title'    => __( 'تنظیمات هدر و نوار بالا', 'negar-shop' ),
			'panel'    => 'negar_shop_panel',
			'priority' => 20,
		)
	);

	// Top Bar Announcement
	$wp_customize->add_setting(
		'negar_shop_topbar_announcement',
		array(
			'default'           => __( 'به فروشگاه ما خوش آمدید', 'negar-shop' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_topbar_announcement',
		array(
			'label'       => __( 'متن اعلان نوار بالا (Announcement)', 'negar-shop' ),
			'description' => __( 'پیام اطلاع‌رسانی بالای هدر در تمامی صفحات', 'negar-shop' ),
			'section'     => 'negar_shop_section_header',
			'type'        => 'text',
		)
	);

	// Phone Hotline
	$wp_customize->add_setting(
		'negar_shop_phone_hotline',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_phone_hotline',
		array(
			'label'       => __( 'شماره تلفن پشتیبانی هدر', 'negar-shop' ),
			'description' => __( 'شماره تماس جهت نمایش در نوار بالای هدر', 'negar-shop' ),
			'section'     => 'negar_shop_section_header',
			'type'        => 'text',
		)
	);

	// Sticky Header Toggle
	$wp_customize->add_setting(
		'negar_shop_sticky_header',
		array(
			'default'           => true,
			'sanitize_callback' => 'negar_shop_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_sticky_header',
		array(
			'label'       => __( 'فعال‌سازی هدر چسبان (Sticky Header)', 'negar-shop' ),
			'description' => __( 'هدر هنگام اسکرول به سمت پایین ثابت باقی می‌ماند', 'negar-shop' ),
			'section'     => 'negar_shop_section_header',
			'type'        => 'checkbox',
		)
	);

	// --------------------------------------------------------------------------
	// Section 3: Social Media Links
	// --------------------------------------------------------------------------
	$wp_customize->add_section(
		'negar_shop_section_social',
		array(
			'title'    => __( 'شبکه‌های اجتماعی', 'negar-shop' ),
			'panel'    => 'negar_shop_panel',
			'priority' => 30,
		)
	);

	// Instagram URL
	$wp_customize->add_setting(
		'negar_shop_instagram_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_instagram_url',
		array(
			'label'   => __( 'لینک اینستاگرام (Instagram)', 'negar-shop' ),
			'section' => 'negar_shop_section_social',
			'type'    => 'url',
		)
	);

	// Telegram URL
	$wp_customize->add_setting(
		'negar_shop_telegram_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_telegram_url',
		array(
			'label'   => __( 'لینک تلگرام (Telegram)', 'negar-shop' ),
			'section' => 'negar_shop_section_social',
			'type'    => 'url',
		)
	);

	// WhatsApp URL
	$wp_customize->add_setting(
		'negar_shop_whatsapp_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_whatsapp_url',
		array(
			'label'   => __( 'لینک واتس‌اپ (WhatsApp)', 'negar-shop' ),
			'section' => 'negar_shop_section_social',
			'type'    => 'url',
		)
	);

	// LinkedIn URL
	$wp_customize->add_setting(
		'negar_shop_linkedin_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_linkedin_url',
		array(
			'label'   => __( 'لینک لینکدین (LinkedIn)', 'negar-shop' ),
			'section' => 'negar_shop_section_social',
			'type'    => 'url',
		)
	);

	// --------------------------------------------------------------------------
	// Section 4: Contact Page Details
	// --------------------------------------------------------------------------
	$wp_customize->add_section(
		'negar_shop_section_contact',
		array(
			'title'       => __( 'اطلاعات صفحه تماس', 'negar-shop' ),
			'description' => __( 'مقادیر نمونه هستند و باید پیش از انتشار با اطلاعات واقعی فروشگاه جایگزین شوند.', 'negar-shop' ),
			'panel'       => 'negar_shop_panel',
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'negar_shop_contact_email',
		array(
			'default'           => 'contact@example.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_contact_email',
		array(
			'label'       => __( 'ایمیل تماس', 'negar-shop' ),
			'description' => __( 'نمونه: contact@example.com', 'negar-shop' ),
			'section'     => 'negar_shop_section_contact',
			'type'        => 'email',
		)
	);

	$wp_customize->add_setting(
		'negar_shop_contact_address',
		array(
			'default'           => __( 'نشانی فروشگاه شما؛ قابل ویرایش از سفارشی‌سازی', 'negar-shop' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_contact_address',
		array(
			'label'   => __( 'نشانی فروشگاه', 'negar-shop' ),
			'section' => 'negar_shop_section_contact',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'negar_shop_contact_hours',
		array(
			'default'           => __( 'ساعت پاسخ‌گویی خود را اینجا وارد کنید', 'negar-shop' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_contact_hours',
		array(
			'label'   => __( 'ساعات پاسخ‌گویی', 'negar-shop' ),
			'section' => 'negar_shop_section_contact',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'negar_shop_contact_map_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_contact_map_url',
		array(
			'label'       => __( 'نشانی embed نقشه', 'negar-shop' ),
			'description' => __( 'تا وقتی URL واقعی وارد نشود، کارت نمونهٔ نقشه نمایش داده می‌شود.', 'negar-shop' ),
			'section'     => 'negar_shop_section_contact',
			'type'        => 'url',
		)
	);

	// --------------------------------------------------------------------------
	// Section 5: Footer & Copyright
	// --------------------------------------------------------------------------
	$wp_customize->add_section(
		'negar_shop_section_footer',
		array(
			'title'    => __( 'تنظیمات فوتر و کپی‌رایت', 'negar-shop' ),
			'panel'    => 'negar_shop_panel',
			'priority' => 40,
		)
	);

	// Footer About Description
	$wp_customize->add_setting(
		'negar_shop_footer_about',
		array(
			'default'           => __( 'برای مشاهده و مقایسه محصولات، از فروشگاه بازدید کنید.', 'negar-shop' ),
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_footer_about',
		array(
			'label'       => __( 'متن معرفی کوتاه فروشگاه در فوتر', 'negar-shop' ),
			'description' => __( 'توضیحات ستون اول فوتر زیر نام فروشگاه', 'negar-shop' ),
			'section'     => 'negar_shop_section_footer',
			'type'        => 'textarea',
		)
	);

	// Copyright Text
	$wp_customize->add_setting(
		'negar_shop_copyright_text',
		array(
			'default'           => __( 'تمامی حقوق مادی و معنوی این وب‌سایت محفوظ می‌باشد.', 'negar-shop' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'negar_shop_copyright_text',
		array(
			'label'       => __( 'متن کپی‌رایت پایین فوتر', 'negar-shop' ),
			'section'     => 'negar_shop_section_footer',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'negar_shop_customize_register' );

/**
 * Sanitize Checkbox Input.
 *
 * @param mixed $checked Input value.
 * @return bool
 */
function negar_shop_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === (bool) $checked ) ? true : false );
}

/**
 * Output Custom CSS from Customizer settings into wp_head.
 */
function negar_shop_customizer_css() {
	$primary_color   = negar_theme_color( 'primary' );
	$secondary_color = negar_theme_color( 'secondary' );
	$font_primary    = function_exists( 'negar_theme_font_stack' ) ? negar_theme_font_stack( 'font_family' ) : "'Vazirmatn', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
	$font_heading    = function_exists( 'negar_theme_font_stack' ) ? negar_theme_font_stack( 'heading_font_family' ) : "'Vazirmatn', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
	$base_size       = function_exists( 'negar_theme_base_font_size' ) ? negar_theme_base_font_size() : 16;
	$heading_scale   = function_exists( 'negar_theme_heading_scale' ) ? negar_theme_heading_scale() : 'normal';

	$p_attr = esc_attr( $primary_color );
	$s_attr = esc_attr( $secondary_color );

	$css  = ':root {';
	$css .= '--color-primary: ' . $p_attr . ';';
	$css .= '--negar-primary: ' . $p_attr . ';';
	$css .= '--kaveh-primary-color: ' . $p_attr . ';';
	$css .= '--wp--preset--color--primary: ' . $p_attr . ';';
	$css .= '--color-primary-hover: ' . $s_attr . ';';
	$css .= '--kaveh-primary-hover: ' . $s_attr . ';';
	$css .= '--color-accent: ' . $s_attr . ';';
	$css .= '--color-secondary: ' . $s_attr . ';';
	$css .= '--wp--preset--color--secondary: ' . $s_attr . ';';
	$css .= '--color-primary-light: color-mix(in srgb, ' . $p_attr . ' 8%, #ffffff);';
	$css .= '--color-accent-light: color-mix(in srgb, ' . $s_attr . ' 8%, #ffffff);';
	$css .= '--color-accent-hover: ' . $s_attr . ';';
	$css .= '--ppnk-card-glow: ' . $p_attr . ';';
	$css .= '--negar-checkout-primary: ' . $p_attr . ';';
	$css .= '--negar-checkout-primary-hover: ' . $s_attr . ';';
	$css .= '--font-primary: ' . $font_primary . ';';
	$css .= '--font-heading: ' . $font_heading . ';';
	$css .= '}';
	$css .= 'body { font-size: ' . absint( $base_size ) . 'px; }';

	if ( 'compact' === $heading_scale ) {
		$css .= 'h1, .h1 { font-size: 1.6875rem; }';
		$css .= 'h2, .h2 { font-size: 1.35rem; }';
		$css .= 'h3, .h3 { font-size: 1.125rem; }';
		$css .= 'h4, .h4 { font-size: 1rem; }';
		$css .= 'h5, .h5 { font-size: 0.9rem; }';
		$css .= 'h6, .h6 { font-size: 0.8rem; }';
	} elseif ( 'large' === $heading_scale ) {
		$css .= 'h1, .h1 { font-size: 2.0625rem; }';
		$css .= 'h2, .h2 { font-size: 1.65rem; }';
		$css .= 'h3, .h3 { font-size: 1.375rem; }';
		$css .= 'h4, .h4 { font-size: 1.2375rem; }';
		$css .= 'h5, .h5 { font-size: 1.1rem; }';
		$css .= 'h6, .h6 { font-size: 0.9625rem; }';
	}

	// 1. Buttons, category actions and section headers.
	$css .= '.btn-nk-danger, .btn-nk-danger-2, .categories-nk__item .btn-nk-danger-2, .categories-nk__item .btn, .section-heading-one-nk .btn, .section-heading-one-nk .btn-nk-danger, .section-heading-one-nk .more__btn, .top-products-nk .section-heading-one-nk .more__btn, .articles-nk .btn-nk-danger, .brands-nk .btn-nk-danger, .top-selling-products .btn-nk-danger, .top-selling-products-two-nk .btn-nk-danger, .top-view-products-four-nk .btn-nk-danger { background-color: var(--color-primary) !important; color: #ffffff !important; box-shadow: 0 4px 14px color-mix(in srgb, var(--color-primary) 28%, transparent) !important; }';
	$css .= '.btn-nk-danger:hover, .btn-nk-danger:focus, .btn-nk-danger-2:hover, .btn-nk-danger-2:focus, .categories-nk__item .btn-nk-danger-2:hover, .section-heading-one-nk .btn:hover, .section-heading-one-nk .btn-nk-danger:hover, .section-heading-one-nk .more:hover .more__btn, .top-products-nk .section-heading-one-nk .more:hover .more__btn, .articles-nk .btn-nk-danger:hover, .brands-nk .btn-nk-danger:hover, .top-selling-products .btn-nk-danger:hover, .top-selling-products-two-nk .btn-nk-danger:hover, .top-view-products-four-nk .btn-nk-danger:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; box-shadow: 0 6px 18px color-mix(in srgb, var(--color-primary-hover) 35%, transparent) !important; }';

	// 2. Promotional banners and CTAs.
	$css .= '.banners-nk__item .detail .more { background-color: var(--color-primary) !important; color: #ffffff !important; box-shadow: 0 10px 24px -6px color-mix(in srgb, var(--color-primary) 42%, transparent) !important; }';
	$css .= '.banners-nk__item .detail .more:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; box-shadow: 0 14px 28px -6px color-mix(in srgb, var(--color-primary-hover) 48%, transparent) !important; }';

	// 3. Product cards: action buttons, likes, badges, timers, add-to-cart, buy-now.
	$css .= '.popular-products-nk .product-one-nk { --ppnk-card-glow: var(--color-primary) !important; }';
	$css .= '.popular-products-nk .product-nk .like:hover, .popular-products-nk .product-nk .like:focus, .popular-products-nk .product-nk .like.wlm-button--active { color: var(--color-primary) !important; }';
	$css .= '.popular-products-nk .product-nk .title a:hover, .product-cart-thirty-nk .product-two-nk .title a:hover, .product-cart-thirty-one-nk .product-four-nk .title a:hover, .product-cart-thirty-two-nk .product-nk .title a:hover, .product-cart-thirty-three-nk .product-nk .title a:hover { color: var(--color-primary-hover) !important; }';
	$css .= '.popular-products-nk .product-nk .price .off span, .product-cart-thirty-nk .product-nk .price .off span, .product-cart-thirty-one-nk .product-nk .price .off span, .product-cart-thirty-two-nk .product-nk .price .off span, .product-cart-thirty-three-nk .product-six-nk .offer { background-color: var(--color-primary) !important; color: #ffffff !important; }';
	$css .= '.product-cart-thirty-nk .product-two-nk .timer { background-color: var(--color-primary) !important; color: #ffffff !important; }';
	$css .= '.product-cart-thirty-nk .product-two-nk::before { background-image: radial-gradient(13.71% 12.89% at 79% 99.23%, var(--color-primary) 0%, transparent 100%) !important; }';
	$css .= '.product-cart-thirty-nk .product-two-nk .add-to-cart, .product-cart-thirty-one-nk .buy-now__btn, .product-cart-thirty-two-nk .product-five-nk .add-to-cart, .product-cart-thirty-three-nk .buy-now__btn, .add-to-cart-wrap .button, .product-nk .add-to-cart { background-color: var(--color-primary) !important; color: #ffffff !important; }';
	$css .= '.product-cart-thirty-nk .product-two-nk .add-to-cart:hover, .product-cart-thirty-one-nk .buy-now__btn:hover, .product-cart-thirty-two-nk .product-five-nk .add-to-cart:hover, .product-cart-thirty-three-nk .buy-now__btn:hover, .add-to-cart-wrap .button:hover, .product-nk .add-to-cart:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }';
	$css .= '.product-cart-thirty-one-nk .buy-now .sub-text { color: var(--color-primary) !important; }';

	// 4. Daily deals wrapper & contrast buttons.
	$css .= '.popular-products-nk__wrapper { background-image: linear-gradient(247.81deg, var(--color-primary) 0.24%, var(--color-primary-hover) 64.49%) !important; box-shadow: rgba(255, 255, 255, 0.2) -20px 14px 44px inset, 0 16px 40px color-mix(in srgb, var(--color-primary) 25%, transparent) !important; }';
	$css .= '.btn-nk-white { color: var(--color-primary) !important; }';
	$css .= '.btn-nk-white:hover { background-color: var(--color-primary-light) !important; }';

	// 5. Stories & headings.
	$css .= '.story-shop__item .image { background: linear-gradient(180deg, var(--color-primary) 0%, var(--color-primary-hover) 100%) !important; }';
	$css .= '.upperstorytitle { background-color: var(--color-primary) !important; box-shadow: 0 2px 8px color-mix(in srgb, var(--color-primary) 40%, transparent) !important; }';
	$css .= '.section-heading-one-nk .title .highlight, .section-heading-one-nk .title .fw-bold, .section-heading-one-nk .title span, .top-products-nk .section-heading-one-nk .title span, .top-products-nk .section-heading-one-nk .more__title, .top-products-nk .section-heading-one-nk .more:hover .more__sub-title { color: var(--color-primary) !important; }';
	$css .= '.section-heading-one-nk .line span:last-child, .top-products-nk .section-heading-one-nk .line span:last-child { background-color: var(--color-primary) !important; }';

	// 6. Single product page.
	$css .= '.kav-single-product-style5-cart button.single_add_to_cart_button, .product-buy-now-btn, .single_add_to_cart_button { background-color: var(--color-primary) !important; color: #ffffff !important; }';
	$css .= '.kav-single-product-style5-cart button.single_add_to_cart_button:hover, .product-buy-now-btn:hover, .single_add_to_cart_button:hover { background-color: var(--color-primary-hover) !important; color: #ffffff !important; }';
	$css .= '.kav-single-product-info-payment-price .price, .kav-single-product-tabs li.active i, .kav-single-product-specs-more { color: var(--color-primary) !important; }';
	$css .= '.kav-single-product-specs-more:hover { color: var(--color-primary-hover) !important; }';

	// 7. Navigation, header, bottom bar, accounts.
	$css .= '.negar-category-mega__all, .negar-category-mega__sale-card a, .negar-mobile-bottom-bar .bottom-bar-item:hover, .negar-mobile-bottom-bar .bottom-bar-item.is-active, .negar-category-mega__parent:hover, .negar-category-mega__child a:hover { color: var(--color-primary) !important; }';
	$css .= '.category-menu-btn:focus-visible, .negar-category-mega a:focus-visible { outline-color: var(--color-primary) !important; }';
	$css .= '.negar-category-mega__sale-card { border-color: var(--color-primary) !important; }';
	$css .= '.account-page-nk__sidebar .woocommerce-MyAccount-navigation a:hover, .account-page-nk__sidebar .woocommerce-MyAccount-navigation .is-active a, .account-page-nk__header span, .negar-account-auth__eyebrow { color: var(--color-primary) !important; background-color: var(--color-primary-light) !important; }';

	// 8. Theme Accent & Tailwind Rose Harmonization for theme-branded elements.
	$css .= '.bg-rose-600, .bg-rose-500, .category-menu-btn { background-color: var(--color-primary) !important; }';
	$css .= '.hover\:bg-rose-700:hover, .hover\:bg-rose-600:hover, .category-menu-btn:hover { background-color: var(--color-primary-hover) !important; }';
	$css .= '.text-rose-600, .text-rose-500 { color: var(--color-primary) !important; }';
	$css .= '.hover\:text-rose-600:hover, .hover\:text-rose-500:hover, .group:hover .group-hover\:text-rose-600 { color: var(--color-primary-hover) !important; }';
	$css .= '.bg-rose-50, .bg-rose-100, .bg-rose-100\/70 { background-color: var(--color-primary-light) !important; }';
	$css .= '.border-rose-100, .border-rose-200, .border-rose-300 { border-color: color-mix(in srgb, var(--color-primary) 20%, transparent) !important; }';
	$css .= '.hover\:border-rose-300:hover { border-color: color-mix(in srgb, var(--color-primary) 40%, transparent) !important; }';

	echo "\n<!-- Negar Shop theme design tokens -->\n";
	echo '<style id="negar-shop-theme-colors">' . wp_strip_all_tags( $css ) . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'negar_shop_customizer_css', 100 );
