<?php
/**
 * SMS Provider and Settings Foundation.
 *
 * Implements Kavenegar Verify Lookup integration and secure administration.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NEGAR_THEME_SMS_OPTION', 'negar_theme_sms_options' );
define( 'NEGAR_THEME_SMS_VERSION', 1 );
define( 'NEGAR_THEME_SMS_SECRETS_OPTION', 'negar_theme_sms_secrets' );

/**
 * Return default SMS configuration.
 *
 * @return array<string, mixed>
 */
function negar_theme_sms_defaults() {
	return array(
		'schema_version' => NEGAR_THEME_SMS_VERSION,
		'enabled'        => false,
		'provider'       => 'kavenegar',
		'template'       => 'verify',
	);
}

/**
 * Return raw stored SMS options merged with defaults.
 *
 * @return array<string, mixed>
 */
function negar_theme_sms_options() {
	$options = get_option( NEGAR_THEME_SMS_OPTION, array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	return wp_parse_args( $options, negar_theme_sms_defaults() );
}

/**
 * Check whether SMS authentication is enabled.
 *
 * @return bool
 */
function negar_theme_sms_is_enabled() {
	$options = negar_theme_sms_options();

	return ! empty( $options['enabled'] );
}

/**
 * Retrieve the active Kavenegar API key.
 *
 * Prioritizes server-side constant NEGAR_KAVENEGAR_API_KEY if defined,
 * then falls back to the separate non-autoloaded secrets option.
 *
 * @return string
 */
function negar_theme_sms_get_api_key() {
	if ( defined( 'NEGAR_KAVENEGAR_API_KEY' ) && ! empty( constant( 'NEGAR_KAVENEGAR_API_KEY' ) ) ) {
		return trim( (string) constant( 'NEGAR_KAVENEGAR_API_KEY' ) );
	}

	$secrets = get_option( NEGAR_THEME_SMS_SECRETS_OPTION, array() );
	if ( is_array( $secrets ) && ! empty( $secrets['kavenegar_api_key'] ) && is_string( $secrets['kavenegar_api_key'] ) ) {
		return trim( $secrets['kavenegar_api_key'] );
	}

	return '';
}

/**
 * Check whether an API key is configured.
 *
 * @return bool
 */
function negar_theme_sms_has_api_key() {
	$key = negar_theme_sms_get_api_key();

	return ! empty( $key );
}

/**
 * Check whether the SMS provider is fully configured and ready.
 *
 * @return bool
 */
function negar_theme_sms_is_configured() {
	$options = negar_theme_sms_options();
	$has_key = negar_theme_sms_has_api_key();

	return ! empty( $options['enabled'] ) && $has_key && ! empty( $options['template'] );
}

/**
 * Ensure SMS options are created as non-autoloaded.
 *
 * @return void
 */
function negar_theme_ensure_sms_options() {
	if ( false === get_option( NEGAR_THEME_SMS_OPTION, false ) ) {
		add_option( NEGAR_THEME_SMS_OPTION, negar_theme_sms_defaults(), '', false );
	}
	if ( false === get_option( NEGAR_THEME_SMS_SECRETS_OPTION, false ) ) {
		add_option( NEGAR_THEME_SMS_SECRETS_OPTION, array(), '', false );
	}
}
add_action( 'admin_init', 'negar_theme_ensure_sms_options', 20 );

/**
 * Register SMS settings with WordPress Settings API.
 *
 * @return void
 */
function negar_theme_register_sms_settings() {
	register_setting(
		'negar_theme_sms_settings',
		NEGAR_THEME_SMS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_sms_settings',
			'default'           => negar_theme_sms_defaults(),
		)
	);
}
add_action( 'admin_init', 'negar_theme_register_sms_settings' );

/**
 * Sanitize SMS settings and manage credential storage safely.
 *
 * @param mixed $submitted Raw submitted data.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_sms_settings( $submitted ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		add_settings_error( 'negar_theme_sms_settings', 'negar_theme_sms_forbidden', __( 'اجازهٔ ذخیرهٔ تنظیمات پیامک را ندارید.', 'negar-shop' ), 'error' );
		return negar_theme_sms_options();
	}

	$submitted = is_array( $submitted ) ? wp_unslash( $submitted ) : array();

	// 1. Explicit API Key deletion action
	if ( ! empty( $_POST['negar_theme_sms_delete_key'] ) ) {
		delete_option( NEGAR_THEME_SMS_SECRETS_OPTION );
		add_settings_error( 'negar_theme_sms_settings', 'negar_theme_sms_key_deleted', __( 'کلید وب‌سرویس پیامک با موفقیت حذف گردید.', 'negar-shop' ), 'updated' );
	} elseif ( isset( $_POST['negar_theme_sms_api_key'] ) ) {
		// 2. API Key update: only update if a non-empty string is provided.
		// Leaving the field blank preserves the existing key without overwriting it.
		$raw_key = sanitize_text_field( wp_unslash( $_POST['negar_theme_sms_api_key'] ) );
		if ( '' !== $raw_key ) {
			update_option( NEGAR_THEME_SMS_SECRETS_OPTION, array( 'kavenegar_api_key' => $raw_key ), false );
			if ( function_exists( 'wp_set_option_autoload' ) ) {
				wp_set_option_autoload( NEGAR_THEME_SMS_SECRETS_OPTION, 'no' );
			}
		}
	}

	// 3. Sanitize general non-secret SMS options
	$enabled  = ! empty( $submitted['enabled'] );
	$provider = isset( $submitted['provider'] ) && in_array( $submitted['provider'], array( 'kavenegar' ), true ) ? $submitted['provider'] : 'kavenegar';
	$template = isset( $submitted['template'] ) ? sanitize_text_field( $submitted['template'] ) : 'verify';

	$clean = array(
		'schema_version' => NEGAR_THEME_SMS_VERSION,
		'enabled'        => $enabled,
		'provider'       => $provider,
		'template'       => $template,
	);

	if ( function_exists( 'wp_set_option_autoload' ) ) {
		wp_set_option_autoload( NEGAR_THEME_SMS_OPTION, 'no' );
	}

	add_settings_error( 'negar_theme_sms_settings', 'negar_theme_sms_saved', __( 'تنظیمات سامانه پیامکی با موفقیت ذخیره شد.', 'negar-shop' ), 'updated' );

	return $clean;
}

/**
 * Render SMS settings fields for the admin tab.
 *
 * @return void
 */
function negar_theme_render_sms_fields() {
	$options  = negar_theme_sms_options();
	$has_key  = negar_theme_sms_has_api_key();
	$is_const = defined( 'NEGAR_KAVENEGAR_API_KEY' ) && ! empty( constant( 'NEGAR_KAVENEGAR_API_KEY' ) );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'وضعیت سامانه پیامک', 'negar-shop' ); ?></th>
			<td>
				<div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
					<?php if ( negar_theme_sms_is_configured() ) : ?>
						<span class="badge" style="background: #10b981; color: #fff; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: bold;">
							<?php esc_html_e( 'آمادهٔ ارسال پیامک (فعال و پیکربندی‌شده)', 'negar-shop' ); ?>
						</span>
					<?php elseif ( ! empty( $options['enabled'] ) ) : ?>
						<span class="badge" style="background: #f59e0b; color: #fff; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: bold;">
							<?php esc_html_e( 'فعال اما ناقص (کلید یا الگو تنظیم نشده است)', 'negar-shop' ); ?>
						</span>
					<?php else : ?>
						<span class="badge" style="background: #64748b; color: #fff; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: bold;">
							<?php esc_html_e( 'غیرفعال', 'negar-shop' ); ?>
						</span>
					<?php endif; ?>
				</div>
				<label for="negar-sms-enabled">
					<input id="negar-sms-enabled" name="<?php echo esc_attr( NEGAR_THEME_SMS_OPTION ); ?>[enabled]" type="checkbox" value="1" <?php checked( ! empty( $options['enabled'] ) ); ?>>
					<?php esc_html_e( 'فعال‌سازی ارسال پیامک کد تایید (OTP) برای ورود و عضویت کاربران', 'negar-shop' ); ?>
				</label>
				<p class="description"><?php esc_html_e( 'در صورت غیرفعال بودن، پیامک اعتبارسنجی ارسال نمی‌شود و ورود شماره‌ای غیرفعال خواهد بود.', 'negar-shop' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="negar-sms-provider"><?php esc_html_e( 'سرویس‌دهنده پیامک', 'negar-shop' ); ?></label></th>
			<td>
				<select id="negar-sms-provider" name="<?php echo esc_attr( NEGAR_THEME_SMS_OPTION ); ?>[provider]" class="regular-text">
					<option value="kavenegar" <?php selected( $options['provider'], 'kavenegar' ); ?>><?php esc_html_e( 'کاوه نگار (Kavenegar - متد Verify Lookup)', 'negar-shop' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'ارسال از طریق متد اعتبارسنجی Verify Lookup با بالاترین سرعت و عبور از بلک‌لیست تبلیغاتی مخابرات.', 'negar-shop' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="negar-sms-api-key"><?php esc_html_e( 'کلید وب‌سرویس (API Key)', 'negar-shop' ); ?></label></th>
			<td>
				<div style="display: flex; gap: 8px; align-items: center; max-width: 500px;">
					<input id="negar-sms-api-key" name="negar_theme_sms_api_key" type="password" class="regular-text code" dir="ltr" autocomplete="new-password" placeholder="<?php echo $has_key ? '••••••••••••••••••••••••••••••••' : ''; ?>">
					<?php if ( $has_key ) : ?>
						<span style="color: #059669; font-weight: 600; font-size: 13px;">✔ <?php esc_html_e( 'تنظیم شده', 'negar-shop' ); ?></span>
					<?php else : ?>
						<span style="color: #dc2626; font-size: 13px;">✖ <?php esc_html_e( 'تنظیم نشده', 'negar-shop' ); ?></span>
					<?php endif; ?>
				</div>
				<p class="description">
					<?php esc_html_e( 'کلید اختصاصی وب‌سرویس دریافتی از پنل کاوه‌نگار. خالی گذاشتن این فیلد، کلید قبلی ذخیره‌شده را حفظ می‌کند.', 'negar-shop' ); ?>
					<?php if ( $is_const ) : ?>
						<br><strong style="color: #2563eb;"><?php esc_html_e( 'نکته: کلید از طریق ثابت سروری NEGAR_KAVENEGAR_API_KEY مقداردهی شده است.', 'negar-shop' ); ?></strong>
					<?php endif; ?>
				</p>
				<?php if ( $has_key && ! $is_const ) : ?>
					<p style="margin-top: 8px;">
						<button type="submit" name="negar_theme_sms_delete_key" value="1" class="button button-link-delete" onclick="return confirm('<?php echo esc_js( __( 'آیا از حذف کلید API ذخیره‌شده اطمینان دارید؟', 'negar-shop' ) ); ?>');">
							<?php esc_html_e( 'حذف کلید ذخیره‌شده فعلی', 'negar-shop' ); ?>
						</button>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="negar-sms-template"><?php esc_html_e( 'نام الگوی تایید (Template)', 'negar-shop' ); ?></label></th>
			<td>
				<input id="negar-sms-template" name="<?php echo esc_attr( NEGAR_THEME_SMS_OPTION ); ?>[template]" type="text" class="regular-text code" dir="ltr" value="<?php echo esc_attr( $options['template'] ); ?>">
				<p class="description"><?php esc_html_e( 'شناسه الگوی ثبت و تایید شده در بخش سرویس اعتبارسنجی پنل کاوه‌نگار (مثلاً verify). متغیر %token در الگو جایگزین کد ۵ رقمی می‌شود.', 'negar-shop' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Send OTP lookup via Kavenegar Verify Lookup REST API.
 *
 * Endpoint: https://api.kavenegar.com/v1/{API_KEY}/verify/lookup.json
 * Method: POST
 *
 * @param string      $receptor 11-digit mobile number (e.g. 09123456789).
 * @param string      $token    5-digit verification code.
 * @param string      $template Verification template identifier.
 * @param string|null $api_key  Optional API key override.
 * @return array{success: bool, message: string, code: int}
 */
function negar_kavenegar_send_lookup( $receptor, $token, $template, $api_key = null ) {
	if ( null === $api_key ) {
		$api_key = negar_theme_sms_get_api_key();
	}

	if ( empty( $api_key ) ) {
		return array(
			'success' => false,
			'message' => __( 'کلید وب‌سرویس پیامک (API Key) تنظیم نشده است.', 'negar-shop' ),
			'code'    => 401,
		);
	}

	if ( empty( $template ) ) {
		return array(
			'success' => false,
			'message' => __( 'نام الگوی پیامک (Template) مشخص نشده است.', 'negar-shop' ),
			'code'    => 400,
		);
	}

	// Build endpoint URL: https://api.kavenegar.com/v1/{API_KEY}/verify/lookup.json
	$url = sprintf( 'https://api.kavenegar.com/v1/%s/verify/lookup.json', rawurlencode( $api_key ) );

	$body_params = array(
		'receptor' => $receptor,
		'token'    => $token,
		'template' => $template,
		'type'     => 'sms',
	);

	$args = array(
		'method'      => 'POST',
		'timeout'     => 10,
		'redirection' => 2,
		'httpversion' => '1.1',
		'blocking'    => true,
		'headers'     => array(
			'Accept'       => 'application/json',
			'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
		),
		'body'        => $body_params,
		'sslverify'   => true,
	);

	$response = wp_remote_post( $url, $args );

	if ( is_wp_error( $response ) ) {
		return array(
			'success' => false,
			'message' => __( 'خطا در برقراری ارتباط با سرور پیامک (تایم‌اوت یا اختلال شبکه).', 'negar-shop' ),
			'code'    => 504,
		);
	}

	$http_code = wp_remote_retrieve_response_code( $response );
	$raw_body  = wp_remote_retrieve_body( $response );
	$data      = json_decode( $raw_body, true );

	if ( ! is_array( $data ) || ! isset( $data['return'] ) || ! is_array( $data['return'] ) ) {
		return array(
			'success' => false,
			'message' => __( 'پاسخ نامعتبر از سامانه پیامک دریافت شد.', 'negar-shop' ),
			'code'    => 502,
		);
	}

	$status_code = isset( $data['return']['status'] ) ? (int) $data['return']['status'] : 0;

	// Kavenegar status 200 means accepted by provider
	if ( 200 === $http_code && 200 === $status_code ) {
		return array(
			'success' => true,
			'message' => __( 'پیامک اعتبارسنجی با موفقیت ارسال شد.', 'negar-shop' ),
			'code'    => 200,
		);
	}

	// Provider error mappings
	$persian_error = __( 'ارسال پیامک توسط سامانه تایید نشد.', 'negar-shop' );
	if ( 418 === $status_code ) {
		$persian_error = __( 'اعتبار حساب پیامکی کافی نمی‌باشد.', 'negar-shop' );
	} elseif ( 411 === $status_code ) {
		$persian_error = __( 'شماره گیرنده در سامانه پیامک نامعتبر است.', 'negar-shop' );
	} elseif ( 424 === $status_code ) {
		$persian_error = __( 'الگوی پیامک (Template) در سامانه یافت نشد یا هنوز تایید نشده است.', 'negar-shop' );
	} elseif ( 412 === $status_code ) {
		$persian_error = __( 'خط ارسال پیامک غیرفعال است.', 'negar-shop' );
	} elseif ( 401 === $status_code || 403 === $status_code ) {
		$persian_error = __( 'کلید وب‌سرویس پیامک (API Key) نامعتبر است.', 'negar-shop' );
	}

	return array(
		'success' => false,
		'message' => $persian_error,
		'code'    => $status_code ? $status_code : $http_code,
	);
}

/**
 * Dispatch OTP SMS via configured provider (Kavenegar).
 *
 * Connected to the negar_dispatch_otp_sms filter hook.
 *
 * @param bool   $dispatched Current dispatch status.
 * @param string $phone      Normalized 11-digit mobile number.
 * @param string $code       5-digit verification code.
 * @return bool
 */
function negar_sms_dispatch_otp( $dispatched, $phone, $code ) {
	if ( $dispatched ) {
		return true;
	}

	if ( ! negar_theme_sms_is_enabled() ) {
		return false;
	}

	$options  = negar_theme_sms_options();
	$api_key  = negar_theme_sms_get_api_key();
	$template = ! empty( $options['template'] ) ? $options['template'] : 'verify';

	if ( empty( $api_key ) || empty( $template ) ) {
		return false;
	}

	$result = negar_kavenegar_send_lookup( $phone, $code, $template, $api_key );

	if ( ! $result['success'] ) {
		set_transient( 'negar_otp_last_error_' . md5( $phone ), $result['message'], 60 );
		return false;
	}

	delete_transient( 'negar_otp_last_error_' . md5( $phone ) );
	return true;
}
add_filter( 'negar_dispatch_otp_sms', 'negar_sms_dispatch_otp', 10, 3 );
