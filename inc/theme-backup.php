<?php
/**
 * NegarThem Theme Settings Backup, Export, Preview & Restore Module
 *
 * Implements Phase 10 safe configuration backup and restore:
 * - Versioned JSON export of supported theme configuration groups.
 * - Strict exclusion of secrets, SMS credentials, API keys, user data and database tables.
 * - JSON schema validation, disallowed key rejection and cross-site reference checks.
 * - Non-destructive preview before apply.
 * - Atomic rollback on write or verification failure.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NEGAR_THEME_BACKUP_FORMAT', 'negar_theme_settings_backup' );
define( 'NEGAR_THEME_BACKUP_VERSION', '1.0' );
define( 'NEGAR_THEME_BACKUP_THEME', 'negarThem' );

/**
 * Return the supported settings groups eligible for export and import.
 *
 * @return array<string, array{label: string, sanitize: callable|string, autoload: string}>
 */
function negar_theme_backup_supported_groups() {
	return array(
		NEGAR_THEME_SETTINGS_OPTION     => array(
			'label'    => __( 'تنظیمات عمومی، هویت برند، چیدمان، متن‌ها، رنگ‌ها، تایپوگرافی و اطلاعات تماس', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_full_settings',
			'autoload' => 'yes',
		),
		NEGAR_THEME_MEDIA_OPTION        => array(
			'label'    => __( 'پیکربندی بنرهای تبلیغاتی و تصاویر بخش‌ها', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_media_settings',
			'autoload' => 'no',
		),
		NEGAR_THEME_PRODUCT_PACK_OPTION => array(
			'label'    => __( 'انتخاب پکیج‌های محصولات صفحه اصلی', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_product_pack_settings',
			'autoload' => 'no',
		),
		NEGAR_THEME_STORY_OPTION        => array(
			'label'    => __( 'استوری‌های تصویری و ویدیویی و پیوندهای اکشن', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_story_settings',
			'autoload' => 'no',
		),
		NEGAR_THEME_BRAND_OPTION        => array(
			'label'    => __( 'برندهای منتخب صفحه اصلی و لوگوهای جایگزین', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_brand_settings',
			'autoload' => 'no',
		),
		NEGAR_THEME_CLUB_OPTION         => array(
			'label'    => __( 'تنظیمات باشگاه مشتریان و طرح‌های اقساطی', 'negar-shop' ),
			'sanitize' => 'negar_theme_sanitize_club_settings',
			'autoload' => 'no',
		),
	);
}

/**
 * Check whether a settings key or field name is disallowed or contains secrets.
 *
 * @param string $key Option or field key.
 * @return bool
 */
function negar_theme_backup_is_disallowed_key( $key ) {
	$blocked = array(
		'negar_theme_sms_secrets',
		'negar_theme_sms_options',
		'sms',
		'secret',
		'secrets',
		'api_key',
		'token',
		'password',
		'kavenegar_api_key',
		'users',
		'customers',
		'orders',
	);

	foreach ( $blocked as $bad ) {
		if ( false !== stripos( (string) $key, $bad ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Generate the complete versioned backup array for export.
 *
 * @return array<string, mixed>
 */
function negar_theme_backup_generate_data() {
	$supported = negar_theme_backup_supported_groups();
	$groups    = array();

	foreach ( $supported as $opt_name => $meta ) {
		$raw = get_option( $opt_name, null );
		if ( null !== $raw ) {
			// Run through registered sanitizer to ensure canonical structure
			if ( is_callable( $meta['sanitize'] ) ) {
				$clean = call_user_func( $meta['sanitize'], $raw );
			} else {
				$clean = $raw;
			}
			$groups[ $opt_name ] = $clean;
		}
	}

	return array(
		'format'          => NEGAR_THEME_BACKUP_FORMAT,
		'version'         => NEGAR_THEME_BACKUP_VERSION,
		'theme'           => NEGAR_THEME_BACKUP_THEME,
		'created_at'      => gmdate( 'c' ),
		'source_site_url' => home_url(),
		'groups'          => $groups,
	);
}

/**
 * Validate and sanitize raw imported backup data.
 *
 * @param mixed $data Raw decoded JSON array.
 * @return array{valid: bool, errors: array<string>, warnings: array<string>, clean_groups: array<string, mixed>, meta: array<string, mixed>}
 */
function negar_theme_backup_validate_data( $data ) {
	$errors       = array();
	$warnings     = array();
	$clean_groups = array();
	$meta         = array();

	if ( ! is_array( $data ) ) {
		return array(
			'valid'        => false,
			'errors'       => array( __( 'داده‌های فایل پشتیبان ساختار معتبری ندارند.', 'negar-shop' ) ),
			'warnings'     => array(),
			'clean_groups' => array(),
			'meta'         => array(),
		);
	}

	// 1. Format identifier validation
	if ( empty( $data['format'] ) || NEGAR_THEME_BACKUP_FORMAT !== $data['format'] ) {
		$errors[] = __( 'شناسهٔ ساختار فایل پشتیبان نامعتبر است یا این فایل مربوط به نگارتم نمی‌باشد.', 'negar-shop' );
	}

	// 2. Version validation
	if ( empty( $data['version'] ) || version_compare( (string) $data['version'], NEGAR_THEME_BACKUP_VERSION, '>' ) ) {
		$errors[] = sprintf(
			/* translators: %s: supported version */
			__( 'نسخهٔ فایل پشتیبان پشتیبانی نمی‌شود. بالاترین نسخه مجاز: %s', 'negar-shop' ),
			NEGAR_THEME_BACKUP_VERSION
		);
	}

	// 3. Theme identifier
	if ( ! empty( $data['theme'] ) && ! in_array( $data['theme'], array( 'negarThem', 'negar-shop' ), true ) ) {
		$warnings[] = sprintf(
			/* translators: %s: theme name in file */
			__( 'نام پوسته در فایل پشتیبان (%s) با نگارتم همخوانی کامل ندارد.', 'negar-shop' ),
			esc_html( $data['theme'] )
		);
	}

	// 4. Groups structure validation
	if ( ! isset( $data['groups'] ) || ! is_array( $data['groups'] ) ) {
		$errors[] = __( 'بخش اطلاعات تنظیمات (groups) در فایل پشتیبان یافت نشد.', 'negar-shop' );
		return array(
			'valid'        => false,
			'errors'       => $errors,
			'warnings'     => $warnings,
			'clean_groups' => array(),
			'meta'         => $meta,
		);
	}

	$supported = negar_theme_backup_supported_groups();

	// 5. Check for unknown groups or secret-related fields
	foreach ( array_keys( $data['groups'] ) as $group_key ) {
		if ( ! array_key_exists( $group_key, $supported ) ) {
			$errors[] = sprintf(
				/* translators: %s: unknown group key */
				__( 'فایل حاوی گروه تنظیمات ناشناخته یا غیرمجاز است: %s', 'negar-shop' ),
				esc_html( $group_key )
			);
		}

		if ( negar_theme_backup_is_disallowed_key( $group_key ) ) {
			$errors[] = sprintf(
				/* translators: %s: disallowed group key */
				__( 'فایل شامل کلید محرمانه یا نامعتبر است: %s', 'negar-shop' ),
				esc_html( $group_key )
			);
		}
	}

	// Deep scan for disallowed keys within each group
	$deep_scan = function ( $items, $prefix = '' ) use ( &$deep_scan, &$errors ) {
		if ( ! is_array( $items ) ) {
			return;
		}
		foreach ( $items as $k => $v ) {
			if ( is_string( $k ) && negar_theme_backup_is_disallowed_key( $k ) ) {
				$errors[] = sprintf(
					/* translators: %s: forbidden field */
					__( 'فیلد غیرمجاز یا محرمانه در فایل پشتیبان شناسایی شد: %s', 'negar-shop' ),
					esc_html( $prefix . $k )
				);
			}
			if ( is_array( $v ) ) {
				$deep_scan( $v, $prefix . $k . '.' );
			}
		}
	};
	$deep_scan( $data['groups'] );

	// 6. Cross-site URL check
	$source_url = ! empty( $data['source_site_url'] ) ? (string) $data['source_site_url'] : '';
	$current_url = home_url();
	if ( $source_url && untrailingslashit( $source_url ) !== untrailingslashit( $current_url ) ) {
		$warnings[] = sprintf(
			/* translators: 1: source site URL, 2: current site URL */
			__( 'نشانی سایت مبدأ (%1$s) با نشانی این سایت (%2$s) متفاوت است. شناسه‌های تصاویر، محصولات و برندها برای این سایت اعتبارسنجی می‌شوند.', 'negar-shop' ),
			esc_html( $source_url ),
			esc_html( $current_url )
		);
	}

	$meta = array(
		'created_at'      => ! empty( $data['created_at'] ) ? (string) $data['created_at'] : '',
		'source_site_url' => $source_url,
		'version'         => ! empty( $data['version'] ) ? (string) $data['version'] : '',
		'groups_count'    => count( $data['groups'] ),
	);

	if ( ! empty( $errors ) ) {
		return array(
			'valid'        => false,
			'errors'       => $errors,
			'warnings'     => $warnings,
			'clean_groups' => array(),
			'meta'         => $meta,
		);
	}

	// 7. Sanitize each supported group and validate destination object references
	foreach ( $data['groups'] as $group_key => $group_data ) {
		if ( ! array_key_exists( $group_key, $supported ) || ! is_array( $group_data ) ) {
			continue;
		}

		$sanitizer = $supported[ $group_key ]['sanitize'];
		$clean     = is_callable( $sanitizer ) ? call_user_func( $sanitizer, $group_data ) : $group_data;

		// Cross-reference sanity checks: Ensure IDs reference genuine local objects
		if ( NEGAR_THEME_MEDIA_OPTION === $group_key ) {
			if ( ! empty( $clean['records'] ) && is_array( $clean['records'] ) ) {
				foreach ( $clean['records'] as $r_key => &$record ) {
					if ( ! empty( $record['media_id'] ) ) {
						$img_post = get_post( (int) $record['media_id'] );
						if ( ! $img_post || 'attachment' !== $img_post->post_type ) {
							$warnings[] = sprintf(
								/* translators: %s: record key */
								__( 'تصویر بخش %s در کتابخانه پرونده‌های چندرسانه‌ای این سایت وجود ندارد و خالی شد.', 'negar-shop' ),
								esc_html( $r_key )
							);
							$record['media_id'] = 0;
						}
					}
				}
				unset( $record );
			}
		} elseif ( NEGAR_THEME_PRODUCT_PACK_OPTION === $group_key ) {
			if ( ! empty( $clean['packs'] ) && is_array( $clean['packs'] ) ) {
				foreach ( $clean['packs'] as $pack_id => &$slots ) {
					if ( ! is_array( $slots ) ) {
						continue;
					}
					foreach ( $slots as $slot_id => &$product_id ) {
						$product_id = absint( $product_id );
						$submitted_id = isset( $group_data['packs'][ $pack_id ][ $slot_id ] ) ? absint( $group_data['packs'][ $pack_id ][ $slot_id ] ) : 0;
						if ( $product_id > 0 ) {
							$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
							if ( ! $product || 'publish' !== get_post_status( $product_id ) || ! $product->is_visible() ) {
								$product_id = 0;
							}
						}
						if ( $submitted_id > 0 && 0 === $product_id ) {
							$warnings[] = sprintf(
								/* translators: 1: pack id, 2: slot id */
								__( 'محصول اسلات %2$s در پکیج %1$s در این سایت یافت نشد و صفر شد.', 'negar-shop' ),
								esc_html( $pack_id ),
								esc_html( $slot_id )
							);
						}
					}
					unset( $product_id );
				}
				unset( $slots );
			}
		} elseif ( NEGAR_THEME_STORY_OPTION === $group_key ) {
			if ( ! empty( $clean['stories'] ) && is_array( $clean['stories'] ) ) {
				foreach ( $clean['stories'] as $s_idx => &$story_item ) {
					$submitted_item = isset( $group_data['stories'][ $s_idx ] ) && is_array( $group_data['stories'][ $s_idx ] ) ? $group_data['stories'][ $s_idx ] : array();
					$story_label    = ! empty( $story_item['title'] ) ? $story_item['title'] : sprintf( '#%d', $s_idx + 1 );

					// Thumbnail image check
					$submitted_thumb = isset( $submitted_item['thumbnail_id'] ) ? absint( $submitted_item['thumbnail_id'] ) : 0;
					if ( ! empty( $story_item['thumbnail_id'] ) && ! wp_attachment_is_image( (int) $story_item['thumbnail_id'] ) ) {
						$story_item['thumbnail_id'] = 0;
					}
					if ( $submitted_thumb > 0 && 0 === $story_item['thumbnail_id'] ) {
						$warnings[] = sprintf(
							/* translators: %s: story label */
							__( 'تصویر شاخص بندانگشتی استوری «%s» در این سایت یافت نشد و پاک شد.', 'negar-shop' ),
							esc_html( $story_label )
						);
					}

					// Media (video or image) check
					if ( isset( $story_item['type'] ) && 'video' === $story_item['type'] ) {
						$submitted_video = isset( $submitted_item['video_id'] ) ? absint( $submitted_item['video_id'] ) : 0;
						if ( ! empty( $story_item['video_id'] ) ) {
							$mime = (string) get_post_mime_type( (int) $story_item['video_id'] );
							if ( ! str_starts_with( $mime, 'video/' ) ) {
								$story_item['video_id'] = 0;
							}
						}
						if ( $submitted_video > 0 && 0 === $story_item['video_id'] ) {
							$warnings[] = sprintf(
								/* translators: %s: story label */
								__( 'فایل ویدیوی استوری «%s» در این سایت یافت نشد و پاک شد.', 'negar-shop' ),
								esc_html( $story_label )
							);
						}
					} else {
						$submitted_image = isset( $submitted_item['image_id'] ) ? absint( $submitted_item['image_id'] ) : 0;
						if ( ! empty( $story_item['image_id'] ) && ! wp_attachment_is_image( (int) $story_item['image_id'] ) ) {
							$story_item['image_id'] = 0;
						}
						if ( $submitted_image > 0 && 0 === $story_item['image_id'] ) {
							$warnings[] = sprintf(
								/* translators: %s: story label */
								__( 'تصویر تمام‌صفحه استوری «%s» در این سایت یافت نشد و پاک شد.', 'negar-shop' ),
								esc_html( $story_label )
							);
						}
					}

					// Poster image check
					if ( ! empty( $story_item['poster_id'] ) && ! wp_attachment_is_image( (int) $story_item['poster_id'] ) ) {
						$story_item['poster_id'] = 0;
					}

					// Linked product check
					$submitted_product = isset( $submitted_item['product_id'] ) ? absint( $submitted_item['product_id'] ) : 0;
					if ( ! empty( $story_item['product_id'] ) ) {
						$product = function_exists( 'wc_get_product' ) ? wc_get_product( (int) $story_item['product_id'] ) : null;
						if ( ! $product || 'publish' !== get_post_status( (int) $story_item['product_id'] ) ) {
							$story_item['product_id'] = 0;
						}
					}
					if ( $submitted_product > 0 && 0 === $story_item['product_id'] ) {
						$warnings[] = sprintf(
							/* translators: %s: story label */
							__( 'محصول متصل به استوری «%s» در این سایت یافت نشد و شناسه آن پاک شد.', 'negar-shop' ),
							esc_html( $story_label )
						);
					}
				}
				unset( $story_item );
			}
		} elseif ( NEGAR_THEME_BRAND_OPTION === $group_key ) {
			if ( ! empty( $clean['brands'] ) && is_array( $clean['brands'] ) ) {
				$valid_brands = array();
				foreach ( $clean['brands'] as $b_item ) {
					if ( ! is_array( $b_item ) || empty( $b_item['term_id'] ) ) {
						continue;
					}
					$tid  = (int) $b_item['term_id'];
					$term = get_term( $tid, 'product_brand' );
					if ( ! $term || is_wp_error( $term ) || 'product_brand' !== $term->taxonomy ) {
						continue;
					}
					if ( ! empty( $b_item['logo_override_id'] ) && ! wp_attachment_is_image( (int) $b_item['logo_override_id'] ) ) {
						$warnings[] = sprintf(
							/* translators: %d: term ID */
							__( 'لوگوی جایگزین برای برند با شناسه %d در کتابخانه پرونده‌های چندرسانه‌ای یافت نشد و پاک شد.', 'negar-shop' ),
							$tid
						);
						$b_item['logo_override_id'] = 0;
					}
					$valid_brands[] = $b_item;
				}
				$clean['brands'] = $valid_brands;
			}
			$submitted_brands_count = ( isset( $group_data['brands'] ) && is_array( $group_data['brands'] ) ) ? count( $group_data['brands'] ) : 0;
			$clean_brands_count     = ( isset( $clean['brands'] ) && is_array( $clean['brands'] ) ) ? count( $clean['brands'] ) : 0;
			if ( $submitted_brands_count > 0 && $clean_brands_count < $submitted_brands_count ) {
				$warnings[] = __( 'برخی از برندهای موجود در فایل پشتیبان در دسته‌بندی برندهای ووکامرس این سایت یافت نشدند و نادیده گرفته شدند.', 'negar-shop' );
			}
		}

		$clean_groups[ $group_key ] = $clean;
	}

	return array(
		'valid'        => true,
		'errors'       => array(),
		'warnings'     => $warnings,
		'clean_groups' => $clean_groups,
		'meta'         => $meta,
	);
}

/**
 * Apply sanitized backup groups with atomic rollback on failure.
 *
 * @param array<string, mixed> $clean_groups Sanitized groups to write.
 * @return true|WP_Error
 */
function negar_theme_backup_apply_data( $clean_groups ) {
	if ( ! is_array( $clean_groups ) || empty( $clean_groups ) ) {
		return new WP_Error( 'empty_payload', __( 'داده‌ای برای اعمال وجود ندارد.', 'negar-shop' ) );
	}

	$supported = negar_theme_backup_supported_groups();

	// 1. Snapshot all supported options before making any changes
	$snapshot = array();
	foreach ( $supported as $opt_name => $meta ) {
		$raw_val = get_option( $opt_name, '__NOT_SET__' );
		$snapshot[ $opt_name ] = array(
			'exists' => ( '__NOT_SET__' !== $raw_val ),
			'value'  => ( '__NOT_SET__' !== $raw_val ) ? $raw_val : null,
		);
	}

	// 2. Apply writes and verify
	$failed_group = '';
	foreach ( $clean_groups as $opt_name => $clean_val ) {
		if ( ! array_key_exists( $opt_name, $supported ) ) {
			continue;
		}

		$meta     = $supported[ $opt_name ];
		$autoload = ( 'yes' === $meta['autoload'] );

		update_option( $opt_name, $clean_val, $autoload ? 'yes' : 'no' );

		// Strict verification check
		$stored_val = get_option( $opt_name, null );
		if ( $stored_val !== $clean_val ) {
			$failed_group = $opt_name;
			break;
		}
	}

	// 3. Rollback on write failure
	if ( ! empty( $failed_group ) ) {
		negar_theme_backup_rollback( $snapshot );
		return new WP_Error(
			'write_verification_failed',
			sprintf(
				/* translators: %s: failed option name */
				__( 'خطا در تایید ذخیره‌سازی گروه %s. تمام تنظیمات به وضعیت اولیه بازگردانده شدند.', 'negar-shop' ),
				$failed_group
			)
		);
	}

	return true;
}

/**
 * Restore option state from a snapshot.
 *
 * @param array<string, array{exists: bool, value: mixed}> $snapshot Pre-write snapshot.
 * @return void
 */
function negar_theme_backup_rollback( $snapshot ) {
	if ( ! is_array( $snapshot ) ) {
		return;
	}

	foreach ( $snapshot as $opt_name => $state ) {
		if ( ! empty( $state['exists'] ) ) {
			update_option( $opt_name, $state['value'] );
		} else {
			delete_option( $opt_name );
		}
	}
}

/**
 * Handle on-demand export download.
 */
function negar_theme_backup_handle_export() {
	if ( ! isset( $_GET['negar_action'] ) || 'export_backup' !== $_GET['negar_action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'اجازهٔ دسترسی به این بخش را ندارید.', 'negar-shop' ) );
	}

	check_admin_referer( 'negar_theme_export_backup', 'negar_export_nonce' );

	$data     = negar_theme_backup_generate_data();
	$filename = 'negar-theme-settings-' . gmdate( 'Y-m-d-His' ) . '.json';

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'X-Content-Type-Options: nosniff' );

	echo wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	exit;
}
add_action( 'admin_init', 'negar_theme_backup_handle_export' );

/**
 * Handle backup import workflow (Preview, Apply, Cancel).
 */
function negar_theme_backup_handle_import() {
	if ( ! isset( $_POST['negar_backup_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'اجازهٔ دسترسی به این بخش را ندارید.', 'negar-shop' ) );
	}

	$action = sanitize_key( $_POST['negar_backup_action'] );

	if ( 'preview' === $action ) {
		check_admin_referer( 'negar_theme_import_preview', 'negar_import_nonce' );

		if ( empty( $_FILES['negar_backup_file'] ) || ! is_uploaded_file( $_FILES['negar_backup_file']['tmp_name'] ) ) {
			add_settings_error( 'negar_theme_backup', 'no_file', __( 'هیچ فایلی برای بارگذاری انتخاب نشده است.', 'negar-shop' ), 'error' );
			return;
		}

		$file = $_FILES['negar_backup_file'];

		// Enforce 2 MB maximum upload boundary
		if ( $file['size'] > 2 * 1024 * 1024 ) {
			add_settings_error( 'negar_theme_backup', 'file_too_large', __( 'حجم فایل انتخاب‌شده بیش از حد مجاز (۲ مگابایت) است.', 'negar-shop' ), 'error' );
			return;
		}

		$raw_json = file_get_contents( $file['tmp_name'] );
		$decoded  = json_decode( $raw_json, true );

		if ( null === $decoded || JSON_ERROR_NONE !== json_last_error() ) {
			add_settings_error( 'negar_theme_backup', 'invalid_json', __( 'فایل انتخاب‌شده ساختار معتبر JSON ندارد.', 'negar-shop' ), 'error' );
			return;
		}

		$validation = negar_theme_backup_validate_data( $decoded );

		if ( ! $validation['valid'] ) {
			foreach ( $validation['errors'] as $err ) {
				add_settings_error( 'negar_theme_backup', 'validation_error', $err, 'error' );
			}
			return;
		}

		// Save preview state in user transient for 15 minutes (non-destructive)
		set_transient( 'negar_theme_backup_preview_' . get_current_user_id(), $validation, 15 * MINUTE_IN_SECONDS );
		add_settings_error( 'negar_theme_backup', 'preview_ready', __( 'پیش‌نمایش فایل پشتیبان با موفقیت آماده شد. لطفاً جزئیات زیر را بررسی کرده و برای جایگزینی تنظیمات تایید فرمایید.', 'negar-shop' ), 'updated' );

	} elseif ( 'apply' === $action ) {
		check_admin_referer( 'negar_theme_import_apply', 'negar_apply_nonce' );

		if ( empty( $_POST['negar_confirm_apply'] ) ) {
			add_settings_error( 'negar_theme_backup', 'no_confirm', __( 'برای اعمال تغییرات، تایید جایگزینی الزامی است.', 'negar-shop' ), 'error' );
			return;
		}

		$preview = get_transient( 'negar_theme_backup_preview_' . get_current_user_id() );
		if ( ! is_array( $preview ) || empty( $preview['clean_groups'] ) ) {
			add_settings_error( 'negar_theme_backup', 'preview_expired', __( 'جلسهٔ پیش‌نمایش منقضی شده است. لطفاً فایل پشتیبان را مجدداً بارگذاری کنید.', 'negar-shop' ), 'error' );
			return;
		}

		$result = negar_theme_backup_apply_data( $preview['clean_groups'] );

		if ( is_wp_error( $result ) ) {
			add_settings_error( 'negar_theme_backup', 'apply_failed', $result->get_error_message(), 'error' );
			return;
		}

		delete_transient( 'negar_theme_backup_preview_' . get_current_user_id() );
		add_settings_error( 'negar_theme_backup', 'import_success', __( 'تنظیمات قالب با موفقیت درون‌ریزی و در فروشگاه اعمال شدند.', 'negar-shop' ), 'updated' );

	} elseif ( 'cancel' === $action ) {
		check_admin_referer( 'negar_theme_import_apply', 'negar_apply_nonce' );
		delete_transient( 'negar_theme_backup_preview_' . get_current_user_id() );
		add_settings_error( 'negar_theme_backup', 'preview_cancelled', __( 'عملیات درون‌ریزی لغو شد.', 'negar-shop' ), 'updated' );
	}
}
add_action( 'admin_init', 'negar_theme_backup_handle_import' );

/**
 * Render the complete Backup & Restore admin tab.
 *
 * Provides:
 * 1. Export section (download on-demand).
 * 2. Import section with preview and confirmation.
 * 3. Scope & safety guidance.
 */
function negar_theme_render_backup_tab() {
	$preview   = get_transient( 'negar_theme_backup_preview_' . get_current_user_id() );
	$supported = negar_theme_backup_supported_groups();
	$base_page = ( isset( $GLOBALS['pagenow'] ) && 'admin.php' === $GLOBALS['pagenow'] ) ? 'admin.php' : 'options-general.php';
	?>
	<div class="negar-backup-manager" dir="rtl" style="margin-top: 16px;">
		<style>
			.negar-backup-card { background: #fff; border: 1px solid #c3c4c7; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
			.negar-backup-card h2 { margin-top: 0; margin-bottom: 12px; font-size: 1.15rem; color: #1e293b; display: flex; align-items: center; gap: 8px; }
			.negar-backup-preview-box { background: #f8fafc; border: 1px solid #cbd5e1; border-right: 4px solid #2271b1; padding: 16px 20px; border-radius: 6px; margin: 18px 0; }
			.negar-backup-group-list { list-style: disc; margin: 10px 24px 14px; color: #334155; }
			.negar-backup-group-list li { margin-bottom: 6px; }
			.negar-backup-warning-box { background: #fffbeb; border: 1px solid #fef3c7; border-right: 4px solid #f59e0b; padding: 12px 16px; border-radius: 6px; margin: 12px 0; color: #92400e; }
		</style>

		<!-- 1. EXPORT SECTION -->
		<div class="negar-backup-card">
			<h2>
				<span class="dashicons dashicons-download" style="color: #2271b1;"></span>
				<?php esc_html_e( 'برون‌بری تنظیمات پوسته (Export)', 'negar-shop' ); ?>
			</h2>
			<p class="description" style="margin-bottom: 16px;">
				<?php esc_html_e( 'تنظیمات ظاهری، چیدمان صفحه اصلی، متن‌ها، تایپوگرافی، پالت رنگ، بنرها، استوری‌ها و برندها در قالب یک فایل استاندارد JSON بارگیری می‌شود. این فایل شامل کدهای محرمانه، رمزهای عبور یا اطلاعات کاربران نیست و برای پشتیبان‌گیری سریع و امن طراحی شده است.', 'negar-shop' ); ?>
			</p>
			<?php
			$export_url = wp_nonce_url(
				add_query_arg(
					array(
						'page'         => 'negar-theme-settings',
						'tab'          => 'backup',
						'negar_action' => 'export_backup',
					),
					admin_url( $base_page )
				),
				'negar_theme_export_backup',
				'negar_export_nonce'
			);
			?>
			<a href="<?php echo esc_url( $export_url ); ?>" class="button button-primary button-hero" style="display: inline-flex; align-items: center; gap: 6px;">
				<span class="dashicons dashicons-download"></span>
				<?php esc_html_e( 'دریافت فایل پشتیبان تنظیمات (JSON)', 'negar-shop' ); ?>
			</a>
		</div>

		<!-- 2. IMPORT & PREVIEW SECTION -->
		<div class="negar-backup-card">
			<h2>
				<span class="dashicons dashicons-upload" style="color: #059669;"></span>
				<?php esc_html_e( 'درون‌ریزی و بازیابی تنظیمات (Import & Restore)', 'negar-shop' ); ?>
			</h2>

			<?php if ( ! empty( $preview ) && is_array( $preview ) ) : ?>
				<!-- PREVIEW PANEL -->
				<div class="negar-backup-preview-box">
					<h3 style="margin-top: 0; color: #0f172a;">
						<?php esc_html_e( 'پیش‌نمایش تغییرات فایل بارگذاری‌شده', 'negar-shop' ); ?>
					</h3>
					<p style="margin: 6px 0; font-size: 13px; color: #475569;">
						<strong><?php esc_html_e( 'تاریخ ایجاد فایل:', 'negar-shop' ); ?></strong>
						<?php echo esc_html( ! empty( $preview['meta']['created_at'] ) ? $preview['meta']['created_at'] : __( 'نامشخص', 'negar-shop' ) ); ?>
						&nbsp;|&nbsp;
						<strong><?php esc_html_e( 'سایت مبدأ:', 'negar-shop' ); ?></strong>
						<code><?php echo esc_html( ! empty( $preview['meta']['source_site_url'] ) ? $preview['meta']['source_site_url'] : __( 'نامشخص', 'negar-shop' ) ); ?></code>
					</p>

					<?php if ( ! empty( $preview['warnings'] ) ) : ?>
						<div class="negar-backup-warning-box">
							<strong><?php esc_html_e( 'نکات قابل توجه:', 'negar-shop' ); ?></strong>
							<ul style="margin: 6px 18px 0; list-style: disc;">
								<?php foreach ( $preview['warnings'] as $warning ) : ?>
									<li><?php echo esc_html( $warning ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<p style="margin: 12px 0 6px;"><strong><?php esc_html_e( 'بخش‌های تنظیمات موجود در این فایل برای اعمال:', 'negar-shop' ); ?></strong></p>
					<ul class="negar-backup-group-list">
						<?php foreach ( $preview['clean_groups'] as $group_key => $group_data ) : ?>
							<li>
								<strong><?php echo esc_html( isset( $supported[ $group_key ] ) ? $supported[ $group_key ]['label'] : $group_key ); ?></strong>
								<span style="color: #64748b; font-size: 12px;">(<code><?php echo esc_html( $group_key ); ?></code>)</span>
							</li>
						<?php endforeach; ?>
					</ul>

					<form method="post" action="<?php echo esc_url( add_query_arg( array( 'page' => 'negar-theme-settings', 'tab' => 'backup' ), admin_url( $base_page ) ) ); ?>">
						<?php wp_nonce_field( 'negar_theme_import_apply', 'negar_apply_nonce' ); ?>
						<div style="margin: 16px 0 12px;">
							<label style="font-weight: 600; color: #b91c1c; display: flex; align-items: center; gap: 8px;">
								<input type="checkbox" name="negar_confirm_apply" value="1" required>
								<?php esc_html_e( 'تایید می‌کنم که تنظیمات فعلی پوسته با اطلاعات فایل بالا جایگزین شوند.', 'negar-shop' ); ?>
							</label>
						</div>
						<div style="display: flex; gap: 10px; align-items: center;">
							<button type="submit" name="negar_backup_action" value="apply" class="button button-primary">
								<?php esc_html_e( 'اعمال و درون‌ریزی نهایی تنظیمات', 'negar-shop' ); ?>
							</button>
							<button type="submit" name="negar_backup_action" value="cancel" class="button button-secondary">
								<?php esc_html_e( 'انصراف و لغو', 'negar-shop' ); ?>
							</button>
						</div>
					</form>
				</div>
			<?php else : ?>
				<!-- UPLOAD FORM -->
				<p class="description" style="margin-bottom: 16px;">
					<?php esc_html_e( 'یک فایل خروجی معتبر JSON انتخاب نمایید. پیش از هرگونه تغییر در پایگاه داده، فایل اعتبارسنجی شده و پیش‌نمایش تغییرات به شما نمایش داده می‌شود.', 'negar-shop' ); ?>
				</p>
				<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( add_query_arg( array( 'page' => 'negar-theme-settings', 'tab' => 'backup' ), admin_url( $base_page ) ) ); ?>">
					<?php wp_nonce_field( 'negar_theme_import_preview', 'negar_import_nonce' ); ?>
					<input type="hidden" name="negar_backup_action" value="preview">
					<p>
						<label for="negar_backup_file" class="screen-reader-text"><?php esc_html_e( 'انتخاب فایل JSON پشتیبان', 'negar-shop' ); ?></label>
						<input type="file" id="negar_backup_file" name="negar_backup_file" accept=".json,application/json" required>
					</p>
					<p>
						<button type="submit" class="button button-secondary">
							<?php esc_html_e( 'بررسی و پیش‌نمایش فایل پشتیبان', 'negar-shop' ); ?>
						</button>
					</p>
				</form>
			<?php endif; ?>
		</div>

		<!-- 3. SCOPE & INFORMATION -->
		<div class="negar-backup-card">
			<h2>
				<span class="dashicons dashicons-info" style="color: #64748b;"></span>
				<?php esc_html_e( 'دامنه، ایمنی و راهنمای انتقال تنظیمات', 'negar-shop' ); ?>
			</h2>
			<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
				<div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 14px 18px;">
					<h3 style="margin-top: 0; color: #166534; font-size: 14px;"><?php esc_html_e( 'موارد شامل پشتیبان‌گیری:', 'negar-shop' ); ?></h3>
					<ul style="margin: 0; padding-right: 18px; list-style: disc; color: #14532d; font-size: 13px;">
						<li><?php esc_html_e( 'تنظیمات چیدمان و فعال/غیرفعال بخش‌های صفحه اصلی', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'پالت رنگ‌های اصلی و ثانویه و مقیاس تایپوگرافی', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'متون پیش‌فرض بخش‌های مختلف و اطلاعات تماس', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'پیکربندی بنرهای تبلیغاتی و لینک‌های آن‌ها', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'استوری‌های فعال و پیوندهای اکشن', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'فهرست برندهای برگزیده صفحه اصلی', 'negar-shop' ); ?></li>
					</ul>
				</div>
				<div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 14px 18px;">
					<h3 style="margin-top: 0; color: #991b1b; font-size: 14px;"><?php esc_html_e( 'موارد مستثنی‌شده (جهت امنیت):', 'negar-shop' ); ?></h3>
					<ul style="margin: 0; padding-right: 18px; list-style: disc; color: #7f1d1d; font-size: 13px;">
						<li><?php esc_html_e( 'کلیدهای محرمانه و تنظیمات سامانه پیامکی (negar_theme_sms_secrets)', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'حساب‌های کاربری مشتریان و مدیران', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'سفارش‌ها، تراکنش‌ها و تنظیمات درگاه‌های پرداخت', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'رمزهای عبور و کدهای OTP', 'negar-shop' ); ?></li>
						<li><?php esc_html_e( 'فایل‌های چندرسانه‌ای فیزیکی سرور', 'negar-shop' ); ?></li>
					</ul>
				</div>
			</div>
			<p style="margin-top: 14px; color: #64748b; font-size: 12px; line-height: 1.6;">
				<?php esc_html_e( 'نکته درباره انتقال بین سایت‌ها: در صورت درون‌ریزی فایل پشتیبان در یک دامنه دیگر، شناسه‌های تصاویر، محصولات و برندها با دیتابیس سایت جدید مطابقت داده می‌شوند و شناسه‌های ناموجود به صورت خودکار پاک می‌گردند تا اختلالی در عملکرد پوسته ایجاد نشود.', 'negar-shop' ); ?>
			</p>
		</div>
	</div>
	<?php
}
