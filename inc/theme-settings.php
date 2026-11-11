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
				'map_title'       => array( 'label' => __( 'نقشه › عنوان', 'negar-shop' ), 'default' => __( 'موقعیت فروشگاه', 'negar-shop' ), 'type' => 'text' ),
				'map_description' => array( 'label' => __( 'نقشه › توضیح', 'negar-shop' ), 'default' => __( 'نقشه پس از وارد کردن نشانی embed واقعی در سفارشی‌سازی نمایش داده می‌شود.', 'negar-shop' ), 'type' => 'textarea' ),
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
	$allowed   = array( 'general', 'texts', 'colors', 'header_footer' );

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
		<?php if ( in_array( $active_tab, array( 'general', 'texts', 'colors', 'header_footer' ), true ) ) : ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'negar_theme_settings' ); ?>
				<input type="hidden" name="negar_theme_settings_tab" value="<?php echo esc_attr( $active_tab ); ?>">
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
