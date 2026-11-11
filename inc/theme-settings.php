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
		'colors'         => array(),
		'header'         => array(),
		'footer'         => array(),
		'contact'        => array(),
		'social'         => array(),
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
 * Return the display-only brand label without changing the WordPress site title.
 *
 * @return string
 */
function negar_theme_brand_label() {
	return (string) negar_theme_setting( 'branding', 'site_label', get_bloginfo( 'name' ) );
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
	$allowed   = array( 'general', 'colors', 'header_footer' );

	if ( ! in_array( $tab, $allowed, true ) ) {
		return $current;
	}

	$current['schema_version'] = NEGAR_THEME_SETTINGS_VERSION;

	if ( 'general' === $tab ) {
		$current['branding']['site_label'] = isset( $submitted['branding']['site_label'] ) ? sanitize_text_field( $submitted['branding']['site_label'] ) : '';
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
		<?php if ( in_array( $active_tab, array( 'general', 'colors', 'header_footer' ), true ) ) : ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'negar_theme_settings' ); ?>
				<input type="hidden" name="negar_theme_settings_tab" value="<?php echo esc_attr( $active_tab ); ?>">
				<table class="form-table" role="presentation">
					<?php if ( 'general' === $active_tab ) : ?>
						<?php negar_theme_settings_field( 'negar-site-label', __( 'نام نمایشی فروشگاه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[branding][site_label]', negar_theme_brand_label(), 'text', __( 'در بخش‌های نمایشی قالب استفاده می‌شود و نام سایت وردپرس را تغییر نمی‌دهد.', 'negar-shop' ) ); ?>
					<?php elseif ( 'colors' === $active_tab ) : ?>
						<?php negar_theme_settings_field( 'negar-primary-color', __( 'رنگ اصلی', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[colors][primary]', negar_theme_setting( 'colors', 'primary', '#059669', 'negar_shop_primary_color' ), 'color', __( 'برای دکمه‌ها، لینک‌ها و اجزای کلیدی قالب.', 'negar-shop' ) ); ?>
						<?php negar_theme_settings_field( 'negar-secondary-color', __( 'رنگ ثانویه', 'negar-shop' ), NEGAR_THEME_SETTINGS_OPTION . '[colors][secondary]', negar_theme_setting( 'colors', 'secondary', '#e11d48', 'negar_shop_accent_color' ), 'color', __( 'برای پیشنهادها و اجزای مکمل قالب.', 'negar-shop' ) ); ?>
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
