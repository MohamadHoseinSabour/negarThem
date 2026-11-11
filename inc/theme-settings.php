<?php
/**
 * Theme settings foundation and admin screen.
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

define( 'NEGAR_THEME_SETTINGS_OPTION', 'negar_theme_options' );
define( 'NEGAR_THEME_SETTINGS_VERSION', 1 );

/**
 * Return the supported schema defaults.
 *
 * Defaults are deliberately separate from legacy Customizer values. The getter
 * uses a saved legacy value only when the new setting has never been saved.
 *
 * @return array<string, mixed>
 */
function negar_theme_settings_defaults() {
	return array(
		'schema_version' => NEGAR_THEME_SETTINGS_VERSION,
		'branding'       => array(),
		'texts'          => array(),
		'layout'         => array(),
		'colors'         => array(),
		'header'         => array(),
		'footer'         => array(),
		'contact'        => array(),
		'social'         => array(),
	);
}

/**
 * Return the storefront's canonical crimson defaults.
 *
 * These values match the existing visual design. They are deliberately not the
 * historical Customizer defaults, which were never the storefront defaults.
 *
 * @return array<string, string>
 */
function negar_theme_color_defaults() {
	return array(
		'primary'   => '#e90544',
		'secondary' => '#c40439',
	);
}

/**
 * Read the stored settings without filling absent keys with defaults.
 *
 * @return array<string, mixed>
 */
function negar_theme_settings_raw() {
	$settings = get_option( NEGAR_THEME_SETTINGS_OPTION, array() );

	return is_array( $settings ) ? $settings : array();
}

/**
 * Get a theme setting while preserving intentional empty strings.
 *
 * @param string $group Group name.
 * @param string $key Setting key.
 * @param mixed  $default Fallback value when no new or legacy value exists.
 * @param string $legacy_theme_mod Optional legacy Customizer key.
 * @return mixed
 */
function negar_theme_setting( $group, $key, $default = '', $legacy_theme_mod = '' ) {
	$settings = negar_theme_settings_raw();

	if ( isset( $settings[ $group ] ) && is_array( $settings[ $group ] ) && array_key_exists( $key, $settings[ $group ] ) ) {
		return $settings[ $group ][ $key ];
	}

	if ( $legacy_theme_mod ) {
		return get_theme_mod( $legacy_theme_mod, $default );
	}

	return $default;
}

/**
 * Resolve and validate a storefront color, including a legacy Customizer value.
 *
 * @param string $key Color key: primary or secondary.
 * @return string
 */
function negar_theme_color( $key ) {
	$defaults = negar_theme_color_defaults();
	$key      = array_key_exists( $key, $defaults ) ? $key : 'primary';
	$legacy   = 'primary' === $key ? 'negar_shop_primary_color' : 'negar_shop_accent_color';
	$value    = sanitize_hex_color( negar_theme_setting( 'colors', $key, $defaults[ $key ], $legacy ) );

	return $value ? $value : $defaults[ $key ];
}

/**
 * Return the display-only brand label without changing the WordPress site title.
 *
 * @return string
 */
function negar_theme_brand_label() {
	return (string) negar_theme_setting( 'branding', 'site_label', get_bloginfo( 'name' ) );
}

/**
 * Return the fixed, theme-owned homepage-section registry.
 *
 * Template paths are deliberately defined here rather than being saved in the
 * option, so an option value can never cause an arbitrary PHP file to load.
 * The order of this registry is the pre-Phase 3 front-page order.
 *
 * @return array<string, array<string, string>>
 */
function negar_theme_home_section_registry() {
	return array(
		'story-circles'              => array( 'label' => __( 'استوری‌ها', 'negar-shop' ), 'template' => 'template-parts/home/story-circles' ),
		'hero-slider'                => array( 'label' => __( 'اسلایدر آغازین', 'negar-shop' ), 'template' => 'template-parts/home/hero-slider' ),
		'category-bubbles'           => array( 'label' => __( 'دسته‌بندی‌های سریع', 'negar-shop' ), 'template' => 'template-parts/home/category-bubbles' ),
		'flash-deals'                => array( 'label' => __( 'پیشنهادهای شگفت‌انگیز', 'negar-shop' ), 'template' => 'template-parts/home/flash-deals' ),
		'product-tabs'               => array( 'label' => __( 'تب‌های محصولات', 'negar-shop' ), 'template' => 'template-parts/home/product-tabs' ),
		'banners-grid'               => array( 'label' => __( 'بنرهای دو ستونه ۱', 'negar-shop' ), 'template' => 'template-parts/home/banners-grid' ),
		'sec-07-smartwatches-1'      => array( 'label' => __( 'ساعت‌های هوشمند ۱', 'negar-shop' ), 'template' => 'template-parts/home/sec-07-smartwatches-1' ),
		'sec-08-banner-wide'         => array( 'label' => __( 'بنر عریض ۱', 'negar-shop' ), 'template' => 'template-parts/home/sec-08-banner-wide' ),
		'sec-09-accessories'         => array( 'label' => __( 'لوازم جانبی', 'negar-shop' ), 'template' => 'template-parts/home/sec-09-accessories' ),
		'sec-10-banners-2col'        => array( 'label' => __( 'بنرهای دو ستونه ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-10-banners-2col' ),
		'sec-11-smartwatches-spec'   => array( 'label' => __( 'ساعت و صوت با مشخصات', 'negar-shop' ), 'template' => 'template-parts/home/sec-11-smartwatches-spec' ),
		'sec-12-banners-compact'     => array( 'label' => __( 'بنرهای فشرده ۱', 'negar-shop' ), 'template' => 'template-parts/home/sec-12-banners-compact' ),
		'sec-13-taste-grid'          => array( 'label' => __( 'شبکهٔ سلیقه‌ها', 'negar-shop' ), 'template' => 'template-parts/home/sec-13-taste-grid' ),
		'sec-14-feature-bar-1'       => array( 'label' => __( 'نوار ویژگی ۱', 'negar-shop' ), 'template' => 'template-parts/home/sec-14-feature-bar-1' ),
		'sec-15-visited-products-2'  => array( 'label' => __( 'محصولات پربازدید ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-15-visited-products-2' ),
		'sec-16-apple-watch-deal'    => array( 'label' => __( 'پیشنهاد ساعت اپل', 'negar-shop' ), 'template' => 'template-parts/home/sec-16-apple-watch-deal' ),
		'sec-17-flash-deals-2'       => array( 'label' => __( 'پیشنهادهای شگفت‌انگیز ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-17-flash-deals-2' ),
		'sec-18-banners-2col'        => array( 'label' => __( 'بنرهای دو ستونه ۳', 'negar-shop' ), 'template' => 'template-parts/home/sec-18-banners-2col' ),
		'sec-19-gaming-laptop'       => array( 'label' => __( 'لپ‌تاپ گیمینگ', 'negar-shop' ), 'template' => 'template-parts/home/sec-19-gaming-laptop' ),
		'brands-slider'              => array( 'label' => __( 'برندهای محبوب', 'negar-shop' ), 'template' => 'template-parts/home/brands-slider' ),
		'sec-21-testimonials'        => array( 'label' => __( 'دیدگاه‌های تأییدشده', 'negar-shop' ), 'template' => 'template-parts/home/sec-21-testimonials' ),
		'sec-22-flash-deals-audio'   => array( 'label' => __( 'پیشنهادهای صوتی', 'negar-shop' ), 'template' => 'template-parts/home/sec-22-flash-deals-audio' ),
		'sec-23-feature-bar-2'       => array( 'label' => __( 'نوار ویژگی ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-23-feature-bar-2' ),
		'sec-24-smartwatches-2'      => array( 'label' => __( 'ساعت‌های هوشمند ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-24-smartwatches-2' ),
		'sec-25-gaming-zone'         => array( 'label' => __( 'منطقهٔ گیمینگ', 'negar-shop' ), 'template' => 'template-parts/home/sec-25-gaming-zone' ),
		'sec-26-visited-products-3'  => array( 'label' => __( 'محصولات پربازدید ۳', 'negar-shop' ), 'template' => 'template-parts/home/sec-26-visited-products-3' ),
		'sec-27-banners-compact-2'   => array( 'label' => __( 'بنرهای فشرده ۲', 'negar-shop' ), 'template' => 'template-parts/home/sec-27-banners-compact-2' ),
		'sec-28-mobile-spotlight'    => array( 'label' => __( 'ویترین موبایل', 'negar-shop' ), 'template' => 'template-parts/home/sec-28-mobile-spotlight' ),
		'sec-29-banners-2col'        => array( 'label' => __( 'بنرهای دو ستونه ۴', 'negar-shop' ), 'template' => 'template-parts/home/sec-29-banners-2col' ),
		'sec-30-recently-viewed'     => array( 'label' => __( 'محصولات اخیراً دیده‌شده', 'negar-shop' ), 'template' => 'template-parts/home/sec-30-recently-viewed' ),
		'sec-31-video-reviews'       => array( 'label' => __( 'بررسی‌های ویدیویی', 'negar-shop' ), 'template' => 'template-parts/home/sec-31-video-reviews' ),
		'sec-32-banners-2col'        => array( 'label' => __( 'بنرهای دو ستونه ۵', 'negar-shop' ), 'template' => 'template-parts/home/sec-32-banners-2col' ),
		'blog-posts'                 => array( 'label' => __( 'مقالات وبلاگ', 'negar-shop' ), 'template' => 'template-parts/home/blog-posts' ),
		'sec-34-feature-bar-3'       => array( 'label' => __( 'نوار ویژگی ۳', 'negar-shop' ), 'template' => 'template-parts/home/sec-34-feature-bar-3' ),
		'sec-35-trust-marquee'       => array( 'label' => __( 'نوار اعتماد و ارسال', 'negar-shop' ), 'template' => 'template-parts/home/sec-35-trust-marquee' ),
	);
}

/**
 * Return the default visible layout in the original template order.
 *
 * @return array<int, array{id: string, enabled: bool}>
 */
function negar_theme_home_layout_default() {
	$layout = array();

	foreach ( array_keys( negar_theme_home_section_registry() ) as $id ) {
		$layout[] = array(
			'id'      => $id,
			'enabled' => true,
		);
	}

	return $layout;
}

/**
 * Normalize stored or submitted layout rows against the fixed registry.
 *
 * Unknown and duplicate IDs are dropped. Missing known IDs are appended and
 * enabled so that an update introducing a new section remains safe and visible.
 *
 * @param mixed $layout Candidate layout rows.
 * @return array<int, array{id: string, enabled: bool}>
 */
function negar_theme_normalize_home_layout( $layout ) {
	$registry = negar_theme_home_section_registry();
	$clean    = array();
	$seen     = array();

	if ( is_array( $layout ) ) {
		foreach ( $layout as $row ) {
			if ( ! is_array( $row ) || ! isset( $row['id'] ) ) {
				continue;
			}

			$id = sanitize_key( $row['id'] );
			if ( ! isset( $registry[ $id ] ) || isset( $seen[ $id ] ) ) {
				continue;
			}

			$seen[ $id ] = true;
			$clean[]     = array(
				'id'      => $id,
				'enabled' => ! isset( $row['enabled'] ) || ! in_array( $row['enabled'], array( false, 0, '0', 'false' ), true ),
			);
		}
	}

	foreach ( array_keys( $registry ) as $id ) {
		if ( ! isset( $seen[ $id ] ) ) {
			$clean[] = array(
				'id'      => $id,
				'enabled' => true,
			);
		}
	}

	return $clean;
}

/**
 * Return the safe homepage layout, falling back to the original order.
 *
 * @return array<int, array{id: string, enabled: bool}>
 */
function negar_theme_home_layout() {
	$settings = negar_theme_settings_raw();
	$layout   = isset( $settings['layout']['sections'] ) ? $settings['layout']['sections'] : array();

	return negar_theme_normalize_home_layout( $layout );
}

/**
 * Render enabled homepage sections from the fixed registry.
 *
 * @return void
 */
function negar_theme_render_home_sections() {
	$registry = negar_theme_home_section_registry();

	foreach ( negar_theme_home_layout() as $section ) {
		if ( empty( $section['enabled'] ) || ! isset( $registry[ $section['id'] ] ) ) {
			continue;
		}

		// The comment is a non-visual, stable verification marker; no wrapper changes layout or Swiper behavior.
		echo "\n<!-- negar-home-section:" . esc_html( $section['id'] ) . " -->\n";
		get_template_part( $registry[ $section['id'] ]['template'] );
	}
}

/**
 * Return the Phase 2 whitelist of static, theme-owned frontend copy.
 *
 * WordPress page content, WooCommerce product data, customer content and
 * transactional states intentionally do not appear in this registry.
 *
 * @return array<string, array<string, mixed>>
 */
function negar_theme_text_registry() {
	return array(
		'header'    => array(
			'label'       => __( 'هدر و جستجو', 'negar-shop' ),
			'description' => __( 'برچسب‌های ثابت رابط هدر؛ نام دسته‌ها و نام محصولات از وردپرس و ووکامرس خوانده می‌شوند.', 'negar-shop' ),
			'fields'      => array(
				'desktop_search_placeholder' => array( 'label' => __( 'راهنمای جستجو در دسکتاپ', 'negar-shop' ), 'default' => __( 'جستجوی محصول...', 'negar-shop' ), 'type' => 'text' ),
				'mobile_search_placeholder'  => array( 'label' => __( 'راهنمای جستجو در موبایل', 'negar-shop' ), 'default' => __( 'جستجوی نام کالا یا برند...', 'negar-shop' ), 'type' => 'text' ),
				'login_label'                 => array( 'label' => __( 'برچسب ورود', 'negar-shop' ), 'default' => __( 'ورود / ثبت‌نام', 'negar-shop' ), 'type' => 'text' ),
				'cart_label'                  => array( 'label' => __( 'برچسب سبد خرید', 'negar-shop' ), 'default' => __( 'سبد خرید', 'negar-shop' ), 'type' => 'text' ),
				'topbar_tagline'              => array( 'label' => __( 'نوار بالا › برچسب مناسبتی', 'negar-shop' ), 'default' => __( 'خوش‌پوش یلدا باش', 'negar-shop' ), 'type' => 'text' ),
				'mega_heading'                => array( 'label' => __( 'مگامنو › عنوان دسته‌ها', 'negar-shop' ), 'default' => __( 'دسته‌بندی محصولات', 'negar-shop' ), 'type' => 'text' ),
				'mega_all_products_label'     => array( 'label' => __( 'مگامنو › برچسب همهٔ محصولات', 'negar-shop' ), 'default' => __( 'همهٔ محصولات', 'negar-shop' ), 'type' => 'text' ),
				'mega_hint'                   => array( 'label' => __( 'مگامنو › راهنما', 'negar-shop' ), 'default' => __( 'انتخاب هر دسته شما را به آرشیو آن می‌برد.', 'negar-shop' ), 'type' => 'textarea' ),
				'mega_sale_kicker'            => array( 'label' => __( 'مگامنو › پیشنهاد فروش › برچسب', 'negar-shop' ), 'default' => __( 'پیشنهاد روز', 'negar-shop' ), 'type' => 'text' ),
				'mega_sale_title'             => array( 'label' => __( 'مگامنو › پیشنهاد فروش › عنوان', 'negar-shop' ), 'default' => __( 'تخفیفات روز', 'negar-shop' ), 'type' => 'text' ),
				'mega_sale_description'       => array( 'label' => __( 'مگامنو › پیشنهاد فروش › توضیح', 'negar-shop' ), 'default' => __( 'محصولات تخفیف‌دار فروشگاه را ببینید.', 'negar-shop' ), 'type' => 'textarea' ),
				'mega_sale_action'            => array( 'label' => __( 'مگامنو › پیشنهاد فروش › برچسب دکمه', 'negar-shop' ), 'default' => __( 'مشاهدهٔ تخفیف‌ها', 'negar-shop' ), 'type' => 'text' ),
			),
		),
		'home'      => array(
			'label'       => __( 'صفحهٔ اصلی › اسلایدر آغازین', 'negar-shop' ),
			'description' => __( 'فقط متن‌های ثابت اسلایدها و پیشنهاد ویژه قابل ویرایش هستند؛ محصول، قیمت، موجودی و امتیاز از ووکامرس می‌آیند.', 'negar-shop' ),
			'fields'      => array(
				'hero_slide_one_eyebrow' => array( 'label' => __( 'اسلاید اول › متن کوچک', 'negar-shop' ), 'default' => __( 'خرید دسته‌های ایکس باکس', 'negar-shop' ), 'type' => 'text' ),
				'hero_slide_one_title'   => array( 'label' => __( 'اسلاید اول › عنوان', 'negar-shop' ), 'default' => __( 'خرید برترین محصولاتــــــــ', 'negar-shop' ), 'type' => 'text' ),
				'hero_slide_one_cta'     => array( 'label' => __( 'اسلاید اول › برچسب دکمه', 'negar-shop' ), 'default' => __( 'مشاهده بیشتر', 'negar-shop' ), 'type' => 'text' ),
				'hero_slide_two_eyebrow' => array( 'label' => __( 'اسلاید دوم › متن کوچک', 'negar-shop' ), 'default' => __( 'کنسول‌های بازی نسل نهم', 'negar-shop' ), 'type' => 'text' ),
				'hero_slide_two_title'   => array( 'label' => __( 'اسلاید دوم › عنوان', 'negar-shop' ), 'default' => __( 'تجربه بی‌نظیر گیمینگ', 'negar-shop' ), 'type' => 'text' ),
				'hero_slide_two_cta'     => array( 'label' => __( 'اسلاید دوم › برچسب دکمه', 'negar-shop' ), 'default' => __( 'مشاهده بیشتر', 'negar-shop' ), 'type' => 'text' ),
				'flash_deals_eyebrow'    => array( 'label' => __( 'پیشنهاد ویژه › متن کوچک', 'negar-shop' ), 'default' => __( 'تخــفیفـــــات', 'negar-shop' ), 'type' => 'text' ),
				'flash_deals_title'      => array( 'label' => __( 'پیشنهاد ویژه › عنوان', 'negar-shop' ), 'default' => __( 'لـــحظه ایــ', 'negar-shop' ), 'type' => 'text' ),
			),
		),
		'footer'    => array(
			'label'       => __( 'فوتر › ستون‌ها و راه‌های ارتباطی', 'negar-shop' ),
			'description' => __( 'متن‌های ثابت فوتر قابل ویرایش‌اند؛ اطلاعات تماس و شبکه‌های اجتماعی در زبانهٔ هدر، فوتر و تماس می‌مانند.', 'negar-shop' ),
			'fields'      => array(
				'quick_links_heading'        => array( 'label' => __( 'دسترسی سریع › عنوان', 'negar-shop' ), 'default' => __( 'دسترسی سریع', 'negar-shop' ), 'type' => 'text' ),
				'customer_services_heading'  => array( 'label' => __( 'خدمات مشتریان › عنوان', 'negar-shop' ), 'default' => __( 'خدمات مشتریان', 'negar-shop' ), 'type' => 'text' ),
				'contact_heading'            => array( 'label' => __( 'ارتباط با فروشگاه › عنوان', 'negar-shop' ), 'default' => __( 'ارتباط با فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'contact_description'        => array( 'label' => __( 'ارتباط با فروشگاه › توضیح', 'negar-shop' ), 'default' => __( 'برای دریافت راهنمایی یا پیگیری درخواست‌ها، از صفحه تماس با ما استفاده کنید.', 'negar-shop' ), 'type' => 'textarea' ),
				'support_cta'                => array( 'label' => __( 'ارتباط با فروشگاه › برچسب پیام', 'negar-shop' ), 'default' => __( 'ارسال پیام به پشتیبانی', 'negar-shop' ), 'type' => 'text' ),
				'phone_support_label'        => array( 'label' => __( 'پشتیبانی تلفنی › برچسب', 'negar-shop' ), 'default' => __( 'پشتیبانی تلفنی', 'negar-shop' ), 'type' => 'text' ),
				'contact_card_heading'        => array( 'label' => __( 'کارت تماس › عنوان', 'negar-shop' ), 'default' => __( 'تماس با ما', 'negar-shop' ), 'type' => 'text' ),
				'contact_card_description'    => array( 'label' => __( 'کارت تماس › توضیح', 'negar-shop' ), 'default' => __( 'برای ارسال پیام یا دریافت راهنمایی، از فرم تماس با فروشگاه استفاده کنید.', 'negar-shop' ), 'type' => 'textarea' ),
				'contact_card_action'         => array( 'label' => __( 'کارت تماس › برچسب دکمه', 'negar-shop' ), 'default' => __( 'صفحه تماس با ما', 'negar-shop' ), 'type' => 'text' ),
				'social_empty_notice'         => array( 'label' => __( 'شبکه‌های اجتماعی › پیام خالی', 'negar-shop' ), 'default' => __( 'نشانی شبکه‌های اجتماعی هنوز ثبت نشده است.', 'negar-shop' ), 'type' => 'textarea' ),
				'social_prompt'              => array( 'label' => __( 'شبکه‌های اجتماعی › راهنما', 'negar-shop' ), 'default' => __( 'در شبکه‌های اجتماعی همراه ما باشید', 'negar-shop' ), 'type' => 'text' ),
			),
		),
		'contact'   => array(
			'label'       => __( 'صفحهٔ تماس', 'negar-shop' ),
			'description' => __( 'این بخش فقط کپی رابط صفحهٔ تماس را کنترل می‌کند؛ نشانی، ساعت، ایمیل، تلفن و نقشه در زبانهٔ هدر، فوتر و تماس مدیریت می‌شوند.', 'negar-shop' ),
			'fields'      => array(
				'hero_eyebrow'    => array( 'label' => __( 'سربرگ › برچسب', 'negar-shop' ), 'default' => __( 'ارتباط با فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'hero_title'      => array( 'label' => __( 'سربرگ › عنوان', 'negar-shop' ), 'default' => __( 'از اینجا با ما در تماس باشید', 'negar-shop' ), 'type' => 'text' ),
				'hero_description'=> array( 'label' => __( 'سربرگ › توضیح', 'negar-shop' ), 'default' => __( 'برای ارسال پیام یا مشاهدهٔ راه‌های ارتباطی، اطلاعات زیر را بررسی کنید. اطلاعات نمونه از بخش سفارشی‌سازی قابل تغییر هستند.', 'negar-shop' ), 'type' => 'textarea' ),
				'form_title'      => array( 'label' => __( 'فرم پیام › عنوان', 'negar-shop' ), 'default' => __( 'ارسال پیام', 'negar-shop' ), 'type' => 'text' ),
				'form_description'=> array( 'label' => __( 'فرم پیام › توضیح', 'negar-shop' ), 'default' => __( 'پیام شما پس از ثبت، به مدیریت سایت ارسال می‌شود.', 'negar-shop' ), 'type' => 'textarea' ),
				'form_submit'     => array( 'label' => __( 'فرم پیام › برچسب دکمه', 'negar-shop' ), 'default' => __( 'ثبت و ارسال پیام', 'negar-shop' ), 'type' => 'text' ),
				'phone_card_label' => array( 'label' => __( 'کارت تماس › برچسب تلفن', 'negar-shop' ), 'default' => __( 'تماس تلفنی', 'negar-shop' ), 'type' => 'text' ),
				'email_card_label' => array( 'label' => __( 'کارت تماس › برچسب ایمیل', 'negar-shop' ), 'default' => __( 'ایمیل', 'negar-shop' ), 'type' => 'text' ),
				'hours_card_label' => array( 'label' => __( 'کارت تماس › برچسب ساعت', 'negar-shop' ), 'default' => __( 'ساعات پاسخ‌گویی', 'negar-shop' ), 'type' => 'text' ),
				'address_card_label' => array( 'label' => __( 'کارت تماس › برچسب نشانی', 'negar-shop' ), 'default' => __( 'نشانی', 'negar-shop' ), 'type' => 'text' ),
				'map_title'       => array( 'label' => __( 'نقشه › عنوان', 'negar-shop' ), 'default' => __( 'موقعیت فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'map_description' => array( 'label' => __( 'نقشه › توضیح', 'negar-shop' ), 'default' => __( 'نقشه پس از وارد کردن نشانی embed واقعی در سفارشی‌سازی نمایش داده می‌شود.', 'negar-shop' ), 'type' => 'textarea' ),
				'map_placeholder_title' => array( 'label' => __( 'نقشه › عنوان هنگام خالی بودن', 'negar-shop' ), 'default' => __( 'جای نقشهٔ فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'map_placeholder_description' => array( 'label' => __( 'نقشه › توضیح هنگام خالی بودن', 'negar-shop' ), 'default' => __( 'نمونهٔ قابل ویرایش از سفارشی‌سازی', 'negar-shop' ), 'type' => 'textarea' ),
			),
		),
		'about'     => array(
			'label'       => __( 'صفحهٔ دربارهٔ ما', 'negar-shop' ),
			'description' => __( 'محتوای اصلی برگه همچنان از ویرایشگر وردپرس خوانده می‌شود؛ اینجا فقط کپی‌های ثابت قالب مدیریت می‌شوند.', 'negar-shop' ),
			'fields'      => array(
				'hero_eyebrow'     => array( 'label' => __( 'سربرگ › برچسب', 'negar-shop' ), 'default' => __( 'دربارهٔ فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'hero_description' => array( 'label' => __( 'سربرگ › توضیح', 'negar-shop' ), 'default' => __( 'داستان، ارزش‌ها و اطلاعات فروشگاه را از مدیریت این برگه تکمیل کنید تا مشتریان با شیوهٔ فعالیت شما آشنا شوند.', 'negar-shop' ), 'type' => 'textarea' ),
				'intro_label'      => array( 'label' => __( 'معرفی › برچسب', 'negar-shop' ), 'default' => __( 'معرفی فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'empty_content'    => array( 'label' => __( 'معرفی › متن هنگام خالی بودن برگه', 'negar-shop' ), 'default' => __( 'متن معرفی، داستان شکل‌گیری و اطلاعات واقعی فروشگاه را از ویرایشگر این برگه وارد کنید. این بخش عمداً هیچ ادعای نمونه‌ای دربارهٔ خدمات یا مجوزها ندارد.', 'negar-shop' ), 'type' => 'textarea' ),
				'guide_title'      => array( 'label' => __( 'راهنما › عنوان', 'negar-shop' ), 'default' => __( 'صفحه‌ای قابل شخصی‌سازی', 'negar-shop' ), 'type' => 'text' ),
				'guide_description'=> array( 'label' => __( 'راهنما › توضیح', 'negar-shop' ), 'default' => __( 'برای حفظ شفافیت، اطلاعات فروشگاه، مجوزها و تعهدات فقط پس از ثبت جزئیات واقعی نمایش داده شوند.', 'negar-shop' ), 'type' => 'textarea' ),
				'products_card_title' => array( 'label' => __( 'کارت محصولات › عنوان', 'negar-shop' ), 'default' => __( 'محصولات', 'negar-shop' ), 'type' => 'text' ),
				'products_card_description' => array( 'label' => __( 'کارت محصولات › توضیح', 'negar-shop' ), 'default' => __( 'برای بررسی کالاها و دسته‌بندی‌های فروشگاه به بخش محصولات بروید.', 'negar-shop' ), 'type' => 'textarea' ),
				'products_card_action' => array( 'label' => __( 'کارت محصولات › برچسب دکمه', 'negar-shop' ), 'default' => __( 'مشاهدهٔ محصولات', 'negar-shop' ), 'type' => 'text' ),
				'support_card_title' => array( 'label' => __( 'کارت پشتیبانی › عنوان', 'negar-shop' ), 'default' => __( 'پشتیبانی', 'negar-shop' ), 'type' => 'text' ),
				'support_card_description' => array( 'label' => __( 'کارت پشتیبانی › توضیح', 'negar-shop' ), 'default' => __( 'راه‌های ارتباطی و فرم پیام در صفحهٔ تماس در دسترس است.', 'negar-shop' ), 'type' => 'textarea' ),
				'support_card_action' => array( 'label' => __( 'کارت پشتیبانی › برچسب دکمه', 'negar-shop' ), 'default' => __( 'ارتباط با فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'orders_card_title' => array( 'label' => __( 'کارت سفارش‌ها › عنوان', 'negar-shop' ), 'default' => __( 'سفارش‌ها', 'negar-shop' ), 'type' => 'text' ),
				'orders_card_description' => array( 'label' => __( 'کارت سفارش‌ها › توضیح', 'negar-shop' ), 'default' => __( 'وضعیت سفارش‌های ثبت‌شده از حساب کاربری قابل مشاهده است.', 'negar-shop' ), 'type' => 'textarea' ),
				'orders_card_action' => array( 'label' => __( 'کارت سفارش‌ها › برچسب دکمه', 'negar-shop' ), 'default' => __( 'حساب کاربری', 'negar-shop' ), 'type' => 'text' ),
			),
		),
		'not_found' => array(
			'label'       => __( 'صفحهٔ ۴۰۴', 'negar-shop' ),
			'description' => __( 'متن راهنمای بازیابی مسیر قابل ویرایش است؛ نام دسته‌های پیشنهادی از ووکامرس می‌آید.', 'negar-shop' ),
			'fields'      => array(
				'title'              => array( 'label' => __( 'عنوان', 'negar-shop' ), 'default' => __( 'متأسفانه صفحه مورد نظر یافت نشد!', 'negar-shop' ), 'type' => 'text' ),
				'description'        => array( 'label' => __( 'توضیح', 'negar-shop' ), 'default' => __( 'صفحه‌ای که به دنبال آن بودید ممکن است حذف شده باشد، نام آن تغییر یافته باشد یا موقتاً در دسترس نباشد.', 'negar-shop' ), 'type' => 'textarea' ),
				'search_placeholder' => array( 'label' => __( 'راهنمای جستجو', 'negar-shop' ), 'default' => __( 'جستجوی محصول یا مقاله مورد نظر...', 'negar-shop' ), 'type' => 'text' ),
				'home_action'        => array( 'label' => __( 'برچسب بازگشت به خانه', 'negar-shop' ), 'default' => __( 'بازگشت به صفحه اصلی', 'negar-shop' ), 'type' => 'text' ),
				'shop_action'        => array( 'label' => __( 'برچسب مشاهدهٔ محصولات', 'negar-shop' ), 'default' => __( 'مشاهده محصولات فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'categories_prompt'  => array( 'label' => __( 'راهنمای دسته‌های پیشنهادی', 'negar-shop' ), 'default' => __( 'یا از دسته‌بندی‌های پرطرفدار زیر دیدن فرمایید:', 'negar-shop' ), 'type' => 'textarea' ),
			),
		),
	);
}

/**
 * Read a static, theme-owned text value while preserving an explicit empty value.
 *
 * @param string $component Registry component name.
 * @param string $key Registry field key.
 * @return string
 */
function negar_theme_text( $component, $key ) {
	$registry = negar_theme_text_registry();

	if ( ! isset( $registry[ $component ]['fields'][ $key ] ) ) {
		return '';
	}

	$settings = negar_theme_settings_raw();
	if ( isset( $settings['texts'][ $component ] ) && is_array( $settings['texts'][ $component ] ) && array_key_exists( $key, $settings['texts'][ $component ] ) ) {
		return (string) $settings['texts'][ $component ][ $key ];
	}

	return (string) $registry[ $component ]['fields'][ $key ]['default'];
}

/**
 * Register the versioned option with the Settings API.
 */
function negar_theme_register_settings() {
	register_setting(
		'negar_theme_settings',
		NEGAR_THEME_SETTINGS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_settings',
			'default'           => negar_theme_settings_defaults(),
		)
	);
}
add_action( 'admin_init', 'negar_theme_register_settings' );

/**
 * Sanitize only the submitted group, preserving all other saved groups.
 *
 * @param mixed $submitted Raw option input supplied by Settings API.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_settings( $submitted ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		add_settings_error( 'negar_theme_settings', 'negar_theme_settings_forbidden', __( 'اجازهٔ ذخیرهٔ تنظیمات را ندارید.', 'negar-shop' ), 'error' );
		return negar_theme_settings_raw();
	}

	$submitted = is_array( $submitted ) ? wp_unslash( $submitted ) : array();
	$current   = negar_theme_settings_raw();
	$tab       = isset( $_POST['negar_theme_settings_tab'] ) ? sanitize_key( wp_unslash( $_POST['negar_theme_settings_tab'] ) ) : '';
	$allowed   = array( 'general', 'texts', 'layout', 'colors', 'header_footer' );

	if ( ! in_array( $tab, $allowed, true ) ) {
		return $current;
	}

	$current['schema_version'] = NEGAR_THEME_SETTINGS_VERSION;

	if ( 'general' === $tab ) {
		$current['branding']['site_label'] = isset( $submitted['branding']['site_label'] ) ? sanitize_text_field( $submitted['branding']['site_label'] ) : '';
	}

	if ( 'texts' === $tab ) {
		foreach ( negar_theme_text_registry() as $component => $section ) {
			foreach ( $section['fields'] as $key => $field ) {
				if ( ! isset( $submitted['texts'][ $component ] ) || ! is_array( $submitted['texts'][ $component ] ) || ! array_key_exists( $key, $submitted['texts'][ $component ] ) ) {
					continue;
				}

				$value = $submitted['texts'][ $component ][ $key ];
				$value = is_scalar( $value ) ? $value : '';
				$current['texts'][ $component ][ $key ] = 'textarea' === $field['type'] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
			}
		}
	}

	if ( 'layout' === $tab ) {
		$registry = negar_theme_home_section_registry();
		$order    = isset( $submitted['layout']['order'] ) && is_array( $submitted['layout']['order'] ) ? $submitted['layout']['order'] : array();
		$enabled  = isset( $submitted['layout']['enabled'] ) && is_array( $submitted['layout']['enabled'] ) ? $submitted['layout']['enabled'] : array();
		$rows     = array();

		foreach ( $order as $id ) {
			$id = sanitize_key( $id );
			if ( ! isset( $registry[ $id ] ) ) {
				continue;
			}

			$rows[] = array(
				'id'      => $id,
				'enabled' => isset( $enabled[ $id ] ) && '1' === (string) $enabled[ $id ],
			);
		}

		$current['layout']['sections'] = negar_theme_normalize_home_layout( $rows );
	}

	if ( 'colors' === $tab ) {
		foreach ( array( 'primary', 'secondary' ) as $key ) {
			$value = isset( $submitted['colors'][ $key ] ) ? sanitize_hex_color( $submitted['colors'][ $key ] ) : '';
			$current['colors'][ $key ] = $value ? $value : '';
		}
	}

	if ( 'header_footer' === $tab ) {
		$current['header']['announcement'] = isset( $submitted['header']['announcement'] ) ? sanitize_text_field( $submitted['header']['announcement'] ) : '';
		$current['contact']['phone']       = isset( $submitted['contact']['phone'] ) ? sanitize_text_field( $submitted['contact']['phone'] ) : '';
		$current['contact']['email']       = isset( $submitted['contact']['email'] ) ? sanitize_email( $submitted['contact']['email'] ) : '';
		$current['contact']['address']     = isset( $submitted['contact']['address'] ) ? sanitize_textarea_field( $submitted['contact']['address'] ) : '';
		$current['contact']['hours']       = isset( $submitted['contact']['hours'] ) ? sanitize_text_field( $submitted['contact']['hours'] ) : '';
		$current['contact']['map_url']     = isset( $submitted['contact']['map_url'] ) ? esc_url_raw( $submitted['contact']['map_url'] ) : '';
		$current['footer']['about']        = isset( $submitted['footer']['about'] ) ? wp_kses_post( $submitted['footer']['about'] ) : '';
		$current['footer']['copyright']    = isset( $submitted['footer']['copyright'] ) ? sanitize_text_field( $submitted['footer']['copyright'] ) : '';

		foreach ( array( 'instagram', 'telegram', 'whatsapp', 'linkedin' ) as $network ) {
			$current['social'][ $network ] = isset( $submitted['social'][ $network ] ) ? esc_url_raw( $submitted['social'][ $network ] ) : '';
		}
	}

	add_settings_error( 'negar_theme_settings', 'negar_theme_settings_saved', __( 'تنظیمات با موفقیت ذخیره شد.', 'negar-shop' ), 'updated' );

	return $current;
}

/**
 * Register the Settings > Theme Settings destination.
 */
function negar_theme_add_settings_page() {
	add_options_page(
		__( 'تنظیمات قالب نگار', 'negar-shop' ),
		__( 'تنظیمات قالب نگار', 'negar-shop' ),
		'manage_options',
		'negar-theme-settings',
		'negar_theme_render_settings_page'
	);
}
add_action( 'admin_menu', 'negar_theme_add_settings_page' );

/**
 * Load layout-management assets only for this settings page.
 *
 * @param string $hook_suffix Current administration screen hook.
 * @return void
 */
function negar_theme_enqueue_settings_assets( $hook_suffix ) {
	if ( 'settings_page_negar-theme-settings' !== $hook_suffix ) {
		return;
	}

	$style_path  = NEGAR_SHOP_DIR . '/assets/css/theme-settings-admin.css';
	$script_path = NEGAR_SHOP_DIR . '/assets/js/theme-settings-layout.js';

	wp_enqueue_style(
		'negar-theme-settings-admin',
		NEGAR_SHOP_URI . '/assets/css/theme-settings-admin.css',
		array(),
		file_exists( $style_path ) ? (string) filemtime( $style_path ) : NEGAR_SHOP_VERSION
	);
	wp_enqueue_script(
		'negar-theme-settings-layout',
		NEGAR_SHOP_URI . '/assets/js/theme-settings-layout.js',
		array(),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : NEGAR_SHOP_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'negar_theme_enqueue_settings_assets' );

/**
 * Return the static tab registry. Later phases add tab content without changing URLs.
 *
 * @return array<string, string>
 */
function negar_theme_settings_tabs() {
	return array(
		'general'         => __( 'عمومی و برند', 'negar-shop' ),
		'texts'           => __( 'مدیریت متن‌ها', 'negar-shop' ),
		'layout'          => __( 'چیدمان صفحهٔ اصلی', 'negar-shop' ),
		'products'        => __( 'بخش‌های محصول', 'negar-shop' ),
		'banners'         => __( 'بنرها و پکیج‌ها', 'negar-shop' ),
		'stories'         => __( 'استوری‌ها', 'negar-shop' ),
		'brands'          => __( 'برندها', 'negar-shop' ),
		'colors'          => __( 'رنگ و تایپوگرافی', 'negar-shop' ),
		'header_footer'   => __( 'هدر، فوتر و تماس', 'negar-shop' ),
		'sms'             => __( 'سامانهٔ پیامکی', 'negar-shop' ),
		'backup'          => __( 'پشتیبان‌گیری و درون‌ریزی', 'negar-shop' ),
	);
}

/**
 * Render one field in a native WordPress settings table.
 *
 * @param string $id Field ID.
 * @param string $label Label.
 * @param string $name Form name.
 * @param string $value Field value.
 * @param string $type Field type.
 * @param string $description Help text.
 */
function negar_theme_settings_field( $id, $label, $name, $value, $type = 'text', $description = '' ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td>
			<?php if ( 'textarea' === $type ) : ?>
				<textarea class="large-text" rows="4" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
			<?php elseif ( 'email' === $type || 'url' === $type || 'color' === $type ) : ?>
				<input class="regular-text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<?php else : ?>
				<input class="regular-text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
			<?php endif; ?>
			<?php if ( $description ) : ?><p class="description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
		</td>
	</tr>
	<?php
}

/**
 * Render the fixed homepage-section layout controls.
 *
 * @return void
 */
function negar_theme_render_layout_fields() {
	$registry = negar_theme_home_section_registry();
	$layout   = negar_theme_home_layout();
	?>
	<fieldset class="negar-layout-manager" aria-describedby="negar-layout-help">
		<legend class="screen-reader-text"><?php esc_html_e( 'چیدمان بخش‌های صفحهٔ اصلی', 'negar-shop' ); ?></legend>
		<p id="negar-layout-help" class="description"><?php esc_html_e( 'برای جابه‌جایی با ماوس، هر ردیف را بکشید. برای استفاده با صفحه‌کلید، دکمه‌های «بالا» و «پایین» هر ردیف را انتخاب کنید. غیرفعال‌کردن یک ردیف، آن بخش را بدون تغییر داده‌هایش از صفحهٔ اصلی پنهان می‌کند.', 'negar-shop' ); ?></p>
		<p id="negar-layout-status" class="screen-reader-text" aria-live="polite"></p>
		<ol id="negar-layout-sections" class="negar-layout-sections" dir="rtl">
			<?php foreach ( $layout as $index => $section ) : ?>
				<?php $entry = $registry[ $section['id'] ]; ?>
				<li class="negar-layout-item" data-section-id="<?php echo esc_attr( $section['id'] ); ?>" draggable="true">
					<span class="negar-layout-item__handle" aria-hidden="true">☷</span>
					<input type="hidden" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[layout][order][]" value="<?php echo esc_attr( $section['id'] ); ?>">
					<label class="negar-layout-item__title" for="negar-layout-enabled-<?php echo esc_attr( $section['id'] ); ?>">
						<input id="negar-layout-enabled-<?php echo esc_attr( $section['id'] ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[layout][enabled][<?php echo esc_attr( $section['id'] ); ?>]" type="checkbox" value="1" <?php checked( ! empty( $section['enabled'] ) ); ?>>
						<span><?php echo esc_html( $entry['label'] ); ?></span>
					</label>
					<span class="negar-layout-item__id" aria-hidden="true"><?php echo esc_html( $section['id'] ); ?></span>
					<div class="negar-layout-item__actions">
						<button class="button-link negar-layout-move" type="button" data-direction="up" aria-label="<?php echo esc_attr( sprintf( __( 'انتقال %s به بالا', 'negar-shop' ), $entry['label'] ) ); ?>" <?php disabled( 0 === $index ); ?>><?php esc_html_e( 'بالا', 'negar-shop' ); ?></button>
						<button class="button-link negar-layout-move" type="button" data-direction="down" aria-label="<?php echo esc_attr( sprintf( __( 'انتقال %s به پایین', 'negar-shop' ), $entry['label'] ) ); ?>" <?php disabled( count( $layout ) - 1 === $index ); ?>><?php esc_html_e( 'پایین', 'negar-shop' ); ?></button>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</fieldset>
	<?php
}

/**
 * Render the settings admin page.
 */
function negar_theme_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'اجازهٔ دسترسی به این صفحه را ندارید.', 'negar-shop' ) );
	}

	$tabs       = negar_theme_settings_tabs();
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
	$active_tab = array_key_exists( $active_tab, $tabs ) ? $active_tab : 'general';
	?>
	<div class="wrap negar-theme-settings" dir="rtl">
		<h1><?php esc_html_e( 'تنظیمات قالب نگار', 'negar-shop' ); ?></h1>
		<p class="description"><?php esc_html_e( 'تنظیمات فقط پس از ذخیره روی فروشگاه اعمال می‌شوند. مقادیر قدیمی سفارشی‌ساز تا زمان ثبت یک جایگزین حفظ می‌شوند.', 'negar-shop' ); ?></p>
		<style>
			.negar-theme-settings { max-width: 1100px; }
			.negar-theme-settings .nav-tab-wrapper { margin: 24px 0 18px; border-bottom: 1px solid #c3c4c7; display: flex; flex-wrap: wrap; gap: 4px; }
			.negar-theme-settings .nav-tab { float: none; margin: 0; border-bottom: 0; }
			.negar-theme-settings .form-table th { width: 230px; }
			.negar-theme-settings .negar-not-ready { padding: 18px; background: #fff; border: 1px solid #dcdcde; border-right: 4px solid #2271b1; }
			@media (max-width: 782px) { .negar-theme-settings .form-table th { width: auto; } .negar-theme-settings .nav-tab { flex: 1 1 45%; text-align: center; } }
		</style>
		<nav class="nav-tab-wrapper" aria-label="<?php esc_attr_e( 'بخش‌های تنظیمات قالب', 'negar-shop' ); ?>">
			<?php foreach ( $tabs as $tab_id => $tab_label ) : ?>
				<a class="nav-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'negar-theme-settings', 'tab' => $tab_id ), admin_url( 'options-general.php' ) ) ); ?>" <?php echo $active_tab === $tab_id ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $tab_label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php settings_errors( 'negar_theme_settings' ); ?>
		<?php if ( in_array( $active_tab, array( 'general', 'texts', 'layout', 'colors', 'header_footer' ), true ) ) : ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'negar_theme_settings' ); ?>
				<input type="hidden" name="negar_theme_settings_tab" value="<?php echo esc_attr( $active_tab ); ?>">
				<?php if ( 'layout' === $active_tab ) : ?>
					<?php negar_theme_render_layout_fields(); ?>
				<?php else : ?>
				<table class="form-table" role="presentation">
					<?php if ( 'general' === $active_tab ) : ?>
						<?php negar_theme_settings_field( 'negar-site-label', __( 'نام نمایشی فروشگاه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[branding][site_label]', negar_theme_brand_label(), 'text', __( 'در بخش‌های نمایشی قالب استفاده می‌شود و نام سایت وردپرس را تغییر نمی‌دهد.', 'negar-shop' ) ); ?>
					<?php elseif ( 'texts' === $active_tab ) : ?>
						<?php foreach ( negar_theme_text_registry() as $component => $section ) : ?>
							<tr class="negar-theme-settings__section"><td colspan="2"><h2><?php echo esc_html( $section['label'] ); ?></h2><p class="description"><?php echo esc_html( $section['description'] ); ?></p></td></tr>
							<?php foreach ( $section['fields'] as $key => $field ) : ?>
								<?php negar_theme_settings_field( 'negar-text-' . $component . '-' . $key, $field['label'], NEGAR_THEME_SETTINGS_OPTION . '[texts][' . $component . '][' . $key . ']', negar_theme_text( $component, $key ), $field['type'] ); ?>
							<?php endforeach; ?>
						<?php endforeach; ?>
					<?php elseif ( 'colors' === $active_tab ) : ?>
						<?php negar_theme_settings_field( 'negar-primary-color', __( 'رنگ اصلی', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[colors][primary]', negar_theme_color( 'primary' ), 'color', __( 'برای دکمه‌ها، لینک‌ها و اجزای کلیدی قالب.', 'negar-shop' ) ); ?>
						<?php negar_theme_settings_field( 'negar-secondary-color', __( 'رنگ ثانویه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[colors][secondary]', negar_theme_color( 'secondary' ), 'color', __( 'برای پیشنهادها و اجزای مکمل قالب.', 'negar-shop' ) ); ?>
					<?php else : ?>
						<?php
						negar_theme_settings_field( 'negar-announcement', __( 'متن نوار بالا', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[header][announcement]', negar_theme_setting( 'header', 'announcement', __( 'به فروشگاه ما خوش آمدید', 'negar-shop' ), 'negar_shop_topbar_announcement' ) );
						negar_theme_settings_field( 'negar-phone', __( 'تلفن تماس', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[contact][phone]', negar_theme_setting( 'contact', 'phone', '', 'negar_shop_phone_hotline' ) );
						negar_theme_settings_field( 'negar-email', __( 'ایمیل تماس', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[contact][email]', negar_theme_setting( 'contact', 'email', 'contact@example.com', 'negar_shop_contact_email' ), 'email' );
						negar_theme_settings_field( 'negar-address', __( 'نشانی فروشگاه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[contact][address]', negar_theme_setting( 'contact', 'address', '', 'negar_shop_contact_address' ), 'textarea' );
						negar_theme_settings_field( 'negar-hours', __( 'ساعت پاسخ‌گویی', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[contact][hours]', negar_theme_setting( 'contact', 'hours', '', 'negar_shop_contact_hours' ) );
						negar_theme_settings_field( 'negar-map-url', __( 'نشانی نقشه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[contact][map_url]', negar_theme_setting( 'contact', 'map_url', '', 'negar_shop_contact_map_url' ), 'url' );
						negar_theme_settings_field( 'negar-footer-about', __( 'معرفی کوتاه فوتر', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[footer][about]', negar_theme_setting( 'footer', 'about', '', 'negar_shop_footer_about' ), 'textarea' );
						negar_theme_settings_field( 'negar-copyright', __( 'متن کپی‌رایت', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[footer][copyright]', negar_theme_setting( 'footer', 'copyright', '', 'negar_shop_copyright_text' ) );
						foreach ( array( 'instagram' => 'Instagram', 'telegram' => 'Telegram', 'whatsapp' => 'WhatsApp', 'linkedin' => 'LinkedIn' ) as $network => $label ) {
							negar_theme_settings_field( 'negar-' . $network, $label, NEGAR_THEME_SETTINGS_OPTION . '[social][' . $network . ']', negar_theme_setting( 'social', $network, '', 'negar_shop_' . $network . '_url' ), 'url' );
						}
						?>
					<?php endif; ?>
				</table>
				<?php endif; ?>
				<?php submit_button( __( 'ذخیرهٔ تنظیمات', 'negar-shop' ) ); ?>
			</form>
		<?php else : ?>
			<div class="negar-not-ready" role="status">
				<strong><?php esc_html_e( 'این بخش در مرحلهٔ بعدی فعال می‌شود.', 'negar-shop' ); ?></strong>
				<p><?php esc_html_e( 'چارچوب ذخیره‌سازی و مجوزهای لازم آماده است. تا تکمیل اتصال امن به بخش نمایش فروشگاه، این صفحه داده‌ای ذخیره نمی‌کند.', 'negar-shop' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
