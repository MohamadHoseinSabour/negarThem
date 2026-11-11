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
	$css             = ':root {';
	$css            .= '--color-primary: ' . esc_attr( $primary_color ) . ';';
	$css            .= '--negar-primary: ' . esc_attr( $primary_color ) . ';';
	$css            .= '--kaveh-primary-color: ' . esc_attr( $primary_color ) . ';';
	$css            .= '--wp--preset--color--primary: ' . esc_attr( $primary_color ) . ';';
	$css            .= '--color-primary-hover: ' . esc_attr( $secondary_color ) . ';';
	$css            .= '--kaveh-primary-hover: ' . esc_attr( $secondary_color ) . ';';
	$css            .= '--color-accent: ' . esc_attr( $secondary_color ) . ';';
	$css            .= '--color-secondary: ' . esc_attr( $secondary_color ) . ';';
	$css            .= '--wp--preset--color--secondary: ' . esc_attr( $secondary_color ) . ';';
	$css            .= '}';

	echo "\n<!-- Negar Shop theme colors -->\n";
	echo '<style id="negar-shop-theme-colors">' . wp_strip_all_tags( $css ) . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'negar_shop_customizer_css', 100 );
