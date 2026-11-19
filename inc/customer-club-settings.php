<?php
/**
 * Customer Club & Installment Settings Management.
 *
 * Handles Settings API registration, schema defaults, server-side sanitization,
 * validation boundaries, admin RTL rendering, and backup integration for
 * the Negar Customer Club feature (013-customer-club).
 *
 * @package NegarThem
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'NEGAR_THEME_CLUB_OPTION' ) ) {
	define( 'NEGAR_THEME_CLUB_OPTION', 'negar_theme_club_options' );
}

if ( ! defined( 'NEGAR_THEME_CLUB_VERSION' ) ) {
	define( 'NEGAR_THEME_CLUB_VERSION', 1 );
}

/**
 * Return default configuration schema for Negar Customer Club.
 *
 * @return array<string, mixed>
 */
function negar_theme_club_settings_defaults() {
	return array(
		'version'             => NEGAR_THEME_CLUB_VERSION,
		'is_enabled'          => true,
		'allow_applications'  => false,
		'plans'               => array(
			'negar_aghaz' => array(
				'id'              => 'negar_aghaz',
				'title'           => 'نگار آغاز',
				'subtitle'        => 'شروعی آسان برای برآورد خرید اقساطی لوازم خانگی',
				'initial_payment' => 3000000,
				'ceiling'         => 6000000,
				'duration_months' => 3,
				'badge'           => 'پیش‌فرض اقتصادی',
				'is_featured'     => false,
				'is_enabled'      => true,
				'features'        => array(
					'پیش‌پرداخت اولیه: ۳,۰۰۰,۰۰۰ تومان (برآورد)',
					'سقف خرید: تا ۶,۰۰۰,۰۰۰ تومان',
					'پیش‌بینی بازپرداخت ۳ ماهه',
					'بررسی اولیه شرایط بر اساس ضوابط فروشگاه',
				),
			),
			'negar_plus'  => array(
				'id'              => 'negar_plus',
				'title'           => 'نگار پلاس',
				'subtitle'        => 'تعادل مناسب بین پیش‌پرداخت و اقساط ماهانه',
				'initial_payment' => 5000000,
				'ceiling'         => 10000000,
				'duration_months' => 6,
				'badge'           => 'انتخاب محبوب',
				'is_featured'     => true,
				'is_enabled'      => true,
				'features'        => array(
					'پیش‌پرداخت اولیه: ۵,۰۰۰,۰۰۰ تومان (برآورد)',
					'سقف خرید: تا ۱۰,۰۰۰,۰۰۰ تومان',
					'پیش‌بینی بازپرداخت ۶ ماهه',
					'مناسب برای خریدهای متوسط لوازم خانگی',
				),
			),
			'negar_vizhe' => array(
				'id'              => 'negar_vizhe',
				'title'           => 'نگار ویژه',
				'subtitle'        => 'حداکثر سقف برآورد برای تجهیز کامل لوازم خانگی',
				'initial_payment' => 10000000,
				'ceiling'         => 20000000,
				'duration_months' => 6,
				'badge'           => 'حداکثر تسهیلات',
				'is_featured'     => false,
				'is_enabled'      => true,
				'features'        => array(
					'پیش‌پرداخت اولیه: ۱۰,۰۰۰,۰۰۰ تومان (برآورد)',
					'سقف خرید: تا ۲۰,۰۰۰,۰۰۰ تومان',
					'پیش‌بینی بازپرداخت ۶ ماهه',
					'مناسب برای خریدهای کامل جهیزیه و لوازم اصلی',
				),
			),
		),
		'sections_visibility' => array(
			'hero'       => true,
			'benefits'   => true,
			'plans'      => true,
			'comparison' => true,
			'calculator' => true,
			'timeline'   => true,
			'faq'        => true,
			'cta'        => true,
		),
		'copy'                => array(
			'hero_title'      => 'باشگاه مشتریان و خرید اقساطی نگار',
			'hero_subtitle'   => 'آشنایی با شرایط و برآورد اولیه‌ی طرح‌های خرید اقساطی لوازم خانگی و پیگیری وضعیت عضویت در باشگاه مشتریان.',
			'hero_cta_text'   => 'مشاهده و انتخاب طرح‌ها',
			'cta_title'       => 'آشنایی با طرح‌ها و برآورد شرایط اقساطی',
			'cta_subtitle'    => 'شرایط و مبالغ طرح‌ها را بررسی فرمایید. فرآیند پذیرش درخواست‌ها پس از اتمام فاز اعتبارسنجی فعال خواهد شد.',
			'cta_button_text' => 'مشاهده طرح‌های اقساطی',
			'disabled_notice' => 'بخش باشگاه مشتریان در حال حاضر در دست پیکربندی و به‌روزرسانی زیرساخت‌های اعتبارسنجی است.',
			'timeline_steps'  => array(
				array(
					'step'        => 1,
					'title'       => 'بررسی و انتخاب طرح',
					'description' => 'آشنایی با شرایط اولیه، سقف خرید و مدت اقساط پیشنهادی.',
				),
				array(
					'step'        => 2,
					'title'       => 'ثبت درخواست و مدارک',
					'description' => 'تکمیل فرم اطلاعات و ارائه مستندات پس از فعال‌سازی ثبت‌نام.',
				),
				array(
					'step'        => 3,
					'title'       => 'بررسی مدارک و اعتبارسنجی',
					'description' => 'ارزیابی صلاحیت و شرایط خریدار توسط کارشناسان فروشگاه.',
				),
				array(
					'step'        => 4,
					'title'       => 'تکمیل سفارش و بازپرداخت',
					'description' => 'نهایی‌سازی سفارش در صورت تأیید و پرداخت اقساط طبق توافقنامه مصوب.',
				),
			),
		),
		'faqs'                => array(
			array(
				'question' => 'شرایط عمومی استفاده از طرح‌های باشگاه مشتریان چیست؟',
				'answer'   => 'در حال حاضر اطلاعات طرح‌ها به عنوان برآورد و پیش‌نمایش شرایط ارائه شده است. ضوابط نهایی، مدارک مورد نیاز و فرآیند پذیرش پس از اتمام فاز اعتبارسنجی رسماً اعلام خواهد شد.',
			),
			array(
				'question' => 'آیا برای خرید اقساطی نیاز به ضامن است؟',
				'answer'   => 'ضوابط مربوط به تضامین، مدارک شغلی و اعتبارسنجی پس از تدوین مقررات تجاری فروشگاه مشخص خواهد شد و هیچ‌گونه تعهد قطعی پیش از ارزیابی مدارک وجود ندارد.',
			),
			array(
				'question' => 'مبلغ پیش‌پرداخت به چه صورت تعیین و واریز می‌شود؟',
				'answer'   => 'مبالغ اعلام‌شده در طرح‌ها جنبه برآورد اولیه دارد و هرگونه واریز یا پیش‌پرداخت تنها پس از تأیید نهایی درخواست و انعقاد توافقنامه امکان‌پذیر خواهد بود.',
			),
			array(
				'question' => 'آیا در حال حاضر امکان ثبت نهایی درخواست وجود دارد؟',
				'answer'   => 'خیر، سامانه در مرحله پیش‌نمایش و ارزیابی زیرساخت بوده و دریافت درخواست‌های جدید به زودی پس از ابلاغ مقررات فعال خواهد شد.',
			),
		),
	);
}

/**
 * Retrieve raw customer club settings array.
 *
 * @return array<string, mixed>
 */
function negar_theme_club_settings_raw() {
	$saved = get_option( NEGAR_THEME_CLUB_OPTION, false );
	if ( ! is_array( $saved ) ) {
		return negar_theme_club_settings_defaults();
	}
	return wp_parse_args( $saved, negar_theme_club_settings_defaults() );
}

/**
 * Helper to retrieve a nested club setting.
 *
 * @param string      $group Top-level key (plans, sections_visibility, copy, faqs, is_enabled, allow_applications).
 * @param string|null $key Sub-key.
 * @param mixed       $default Default fallback.
 * @return mixed
 */
function negar_theme_club_setting( $group, $key = null, $default = null ) {
	$settings = negar_theme_club_settings_raw();

	// If only 2 arguments passed and 2nd is not a string key (e.g. default array), treat as default
	if ( 2 === func_num_args() && ! is_string( $key ) ) {
		return $settings[ $group ] ?? $key;
	}

	if ( null === $key ) {
		return $settings[ $group ] ?? $default;
	}

	if ( is_scalar( $key ) && isset( $settings[ $group ][ $key ] ) ) {
		return $settings[ $group ][ $key ];
	}

	return $default;
}

/**
 * Ensure customer club option exists with autoload = 'no'.
 *
 * @return void
 */
function negar_theme_ensure_club_option() {
	if ( false === get_option( NEGAR_THEME_CLUB_OPTION, false ) ) {
		add_option( NEGAR_THEME_CLUB_OPTION, negar_theme_club_settings_defaults(), '', false );
	}
}
add_action( 'admin_init', 'negar_theme_ensure_club_option', 20 );

/**
 * Ensure customer club page exists in WordPress and is linked to the template.
 *
 * @return int Page ID.
 */
function negar_theme_ensure_club_page() {
	$page = get_page_by_path( 'customer-club' );
	if ( ! $page ) {
		$page_id = wp_insert_post(
			array(
				'post_title'     => __( 'باشگاه مشتریان و خرید اقساطی', 'negar-shop' ),
				'post_name'      => 'customer-club',
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-templates/page-customer-club.php' );
			return (int) $page_id;
		}
		return 0;
	}

	if ( 'page-templates/page-customer-club.php' !== get_post_meta( $page->ID, '_wp_page_template', true ) ) {
		update_post_meta( $page->ID, '_wp_page_template', 'page-templates/page-customer-club.php' );
	}

	return (int) $page->ID;
}

/**
 * Filter template_include to route /customer-club/ to page-templates/page-customer-club.php.
 *
 * @param string $template Matched template file path.
 * @return string
 */
function negar_theme_club_template_include( $template ) {
	if ( is_page( 'customer-club' ) ) {
		$club_template = locate_template( array( 'page-templates/page-customer-club.php' ) );
		if ( $club_template ) {
			return $club_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'negar_theme_club_template_include' );

/**
 * Server-side sanitization and validation callback for customer club settings.
 *
 * @param mixed $input Submitted raw data.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_club_settings( $input ) {
	$defaults = negar_theme_club_settings_defaults();
	$current  = get_option( NEGAR_THEME_CLUB_OPTION, $defaults );
	if ( ! is_array( $current ) ) {
		$current = $defaults;
	}

	if ( ! is_array( $input ) ) {
		return $current;
	}

	$clean = array(
		'version'             => NEGAR_THEME_CLUB_VERSION,
		'is_enabled'          => ! empty( $input['is_enabled'] ),
		'allow_applications'  => ! empty( $input['allow_applications'] ),
		'plans'               => array(),
		'sections_visibility' => array(),
		'copy'                => array(),
		'faqs'                => array(),
	);

	// 1. Sanitize 3 Primary Fixed Plans
	$allowed_plans = array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' );
	$submitted_plans = isset( $input['plans'] ) && is_array( $input['plans'] ) ? $input['plans'] : array();

	foreach ( $allowed_plans as $plan_id ) {
		$curr_plan = $current['plans'][ $plan_id ] ?? $defaults['plans'][ $plan_id ];
		$raw_plan  = $submitted_plans[ $plan_id ] ?? array();

		$title       = isset( $raw_plan['title'] ) ? sanitize_text_field( $raw_plan['title'] ) : $curr_plan['title'];
		$subtitle    = isset( $raw_plan['subtitle'] ) ? sanitize_text_field( $raw_plan['subtitle'] ) : $curr_plan['subtitle'];
		$badge       = isset( $raw_plan['badge'] ) ? sanitize_text_field( $raw_plan['badge'] ) : (string) ( $curr_plan['badge'] ?? '' );
		$is_featured = ! empty( $raw_plan['is_featured'] );
		$is_enabled  = ! empty( $raw_plan['is_enabled'] );

		// Validate initial_payment: must be strictly positive numeric integer
		if ( isset( $raw_plan['initial_payment'] ) ) {
			$raw_init = trim( (string) $raw_plan['initial_payment'] );
			if ( ! is_numeric( $raw_init ) || (int) $raw_init <= 0 || false !== strpos( $raw_init, '-' ) ) {
				if ( function_exists( 'add_settings_error' ) ) {
					add_settings_error(
						'negar_theme_club_options',
						'invalid_initial_payment_' . $plan_id,
						sprintf(
							/* translators: %s: plan title */
							__( 'در طرح «%s»، مبلغ پیش‌پرداخت باید یک عدد مثبت معتبر باشد. مقدار قبلی حفظ شد.', 'negar-shop' ),
							esc_html( $title )
						),
						'error'
					);
				}
				$initial_payment = (int) $curr_plan['initial_payment'];
			} else {
				$initial_payment = (int) $raw_init;
			}
		} else {
			$initial_payment = (int) $curr_plan['initial_payment'];
		}

		// Validate ceiling: must be strictly positive numeric integer
		if ( isset( $raw_plan['ceiling'] ) ) {
			$raw_ceil = trim( (string) $raw_plan['ceiling'] );
			if ( ! is_numeric( $raw_ceil ) || (int) $raw_ceil <= 0 || false !== strpos( $raw_ceil, '-' ) ) {
				if ( function_exists( 'add_settings_error' ) ) {
					add_settings_error(
						'negar_theme_club_options',
						'invalid_ceiling_' . $plan_id,
						sprintf(
							/* translators: %s: plan title */
							__( 'در طرح «%s»، سقف خرید باید یک عدد مثبت معتبر باشد. مقدار قبلی حفظ شد.', 'negar-shop' ),
							esc_html( $title )
						),
						'error'
					);
				}
				$ceiling = (int) $curr_plan['ceiling'];
			} else {
				$ceiling = (int) $raw_ceil;
			}
		} else {
			$ceiling = (int) $curr_plan['ceiling'];
		}

		// Validate duration_months: must be at least 1 month
		if ( isset( $raw_plan['duration_months'] ) ) {
			$raw_dur = trim( (string) $raw_plan['duration_months'] );
			if ( ! is_numeric( $raw_dur ) || (int) $raw_dur < 1 || false !== strpos( $raw_dur, '-' ) ) {
				if ( function_exists( 'add_settings_error' ) ) {
					add_settings_error(
						'negar_theme_club_options',
						'invalid_duration_' . $plan_id,
						sprintf(
							/* translators: %s: plan title */
							__( 'در طرح «%s»، مدت اقساط باید حداقل ۱ ماه باشد. مقدار قبلی حفظ شد.', 'negar-shop' ),
							esc_html( $title )
						),
						'error'
					);
				}
				$duration_months = (int) $curr_plan['duration_months'];
			} else {
				$duration_months = (int) $raw_dur;
			}
		} else {
			$duration_months = (int) $curr_plan['duration_months'];
		}

		// Validation Boundary: initial_payment <= ceiling
		if ( $initial_payment > $ceiling && $ceiling > 0 ) {
			if ( function_exists( 'add_settings_error' ) ) {
				add_settings_error(
					'negar_theme_club_options',
					'invalid_plan_amounts_' . $plan_id,
					sprintf(
						/* translators: 1: plan title, 2: initial payment, 3: ceiling */
						__( 'در طرح «%1$s»، مبلغ پیش‌پرداخت (%2$s تومان) نمی‌تواند از سقف خرید (%3$s تومان) بیشتر باشد. مقادیر معتبر قبلی حفظ شدند.', 'negar-shop' ),
						esc_html( $title ),
						number_format_i18n( $initial_payment ),
						number_format_i18n( $ceiling )
					),
					'error'
				);
			}
			$initial_payment = (int) $curr_plan['initial_payment'];
			$ceiling         = (int) $curr_plan['ceiling'];
		}

		// Parse features
		$features = array();
		if ( isset( $raw_plan['features'] ) ) {
			if ( is_array( $raw_plan['features'] ) ) {
				foreach ( $raw_plan['features'] as $f_item ) {
					$line = sanitize_text_field( (string) $f_item );
					if ( '' !== trim( $line ) ) {
						$features[] = $line;
					}
				}
			} elseif ( is_string( $raw_plan['features'] ) ) {
				$lines = explode( "\n", str_replace( "\r", '', $raw_plan['features'] ) );
				foreach ( $lines as $line ) {
					$clean_line = sanitize_text_field( trim( $line ) );
					if ( '' !== $clean_line ) {
						$features[] = $clean_line;
					}
				}
			}
		} else {
			$features = $curr_plan['features'];
		}

		$clean['plans'][ $plan_id ] = array(
			'id'              => $plan_id,
			'title'           => $title,
			'subtitle'        => $subtitle,
			'initial_payment' => $initial_payment,
			'ceiling'         => $ceiling,
			'duration_months' => $duration_months,
			'badge'           => $badge,
			'is_featured'     => $is_featured,
			'is_enabled'      => $is_enabled,
			'features'        => $features,
		);
	}

	// 2. Sanitize Section Visibility
	$vis_sections = array( 'hero', 'benefits', 'plans', 'comparison', 'calculator', 'timeline', 'faq', 'cta' );
	$submitted_vis = isset( $input['sections_visibility'] ) && is_array( $input['sections_visibility'] ) ? $input['sections_visibility'] : array();

	foreach ( $vis_sections as $sec_key ) {
		// When settings are submitted from our form, presence indicates true
		$clean['sections_visibility'][ $sec_key ] = ! empty( $submitted_vis[ $sec_key ] );
	}

	// 3. Sanitize Copy & Notices
	$submitted_copy = isset( $input['copy'] ) && is_array( $input['copy'] ) ? $input['copy'] : array();
	$curr_copy      = $current['copy'] ?? $defaults['copy'];

	$clean['copy']['hero_title']      = isset( $submitted_copy['hero_title'] ) ? sanitize_text_field( $submitted_copy['hero_title'] ) : $curr_copy['hero_title'];
	$clean['copy']['hero_subtitle']   = isset( $submitted_copy['hero_subtitle'] ) ? sanitize_text_field( $submitted_copy['hero_subtitle'] ) : $curr_copy['hero_subtitle'];
	$clean['copy']['hero_cta_text']   = isset( $submitted_copy['hero_cta_text'] ) ? sanitize_text_field( $submitted_copy['hero_cta_text'] ) : $curr_copy['hero_cta_text'];
	$clean['copy']['cta_title']       = isset( $submitted_copy['cta_title'] ) ? sanitize_text_field( $submitted_copy['cta_title'] ) : $curr_copy['cta_title'];
	$clean['copy']['cta_subtitle']    = isset( $submitted_copy['cta_subtitle'] ) ? sanitize_text_field( $submitted_copy['cta_subtitle'] ) : $curr_copy['cta_subtitle'];
	$clean['copy']['cta_button_text'] = isset( $submitted_copy['cta_button_text'] ) ? sanitize_text_field( $submitted_copy['cta_button_text'] ) : $curr_copy['cta_button_text'];
	$clean['copy']['disabled_notice'] = isset( $submitted_copy['disabled_notice'] ) ? sanitize_text_field( $submitted_copy['disabled_notice'] ) : $curr_copy['disabled_notice'];

	// Timeline Steps (1 to 4)
	$clean['copy']['timeline_steps'] = array();
	if ( isset( $submitted_copy['timeline_steps'] ) && is_array( $submitted_copy['timeline_steps'] ) ) {
		foreach ( $submitted_copy['timeline_steps'] as $idx => $step_data ) {
			if ( is_array( $step_data ) ) {
				$clean['copy']['timeline_steps'][] = array(
					'step'        => absint( $step_data['step'] ?? ( $idx + 1 ) ),
					'title'       => sanitize_text_field( $step_data['title'] ?? '' ),
					'description' => sanitize_text_field( $step_data['description'] ?? '' ),
				);
			}
		}
	} else {
		$clean['copy']['timeline_steps'] = $curr_copy['timeline_steps'];
	}

	// 4. Sanitize FAQs (Repeater)
	if ( isset( $input['faqs'] ) && is_array( $input['faqs'] ) ) {
		foreach ( $input['faqs'] as $faq_item ) {
			if ( is_array( $faq_item ) ) {
				$q = isset( $faq_item['question'] ) ? sanitize_text_field( trim( $faq_item['question'] ) ) : '';
				$a = isset( $faq_item['answer'] ) ? wp_kses_post( trim( $faq_item['answer'] ) ) : '';
				if ( '' !== $q ) {
					$clean['faqs'][] = array(
						'question' => $q,
						'answer'   => $a,
					);
				}
			}
		}
	} else {
		$clean['faqs'] = $current['faqs'] ?? $defaults['faqs'];
	}

	return $clean;
}

/**
 * Render Customer Club settings pane inside theme settings page.
 *
 * @return void
 */
function negar_theme_render_customer_club_fields() {
	$options = negar_theme_club_settings_raw();
	$opt_key = NEGAR_THEME_CLUB_OPTION;
	?>
	<div class="negar-club-admin-wrap" dir="rtl">
		<style>
			.negar-club-admin-wrap { margin-top: 15px; }
			.negar-club-card { background: #fff; border: 1px solid #c3c4c7; border-radius: 8px; padding: 20px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
			.negar-club-card__header { border-bottom: 1px solid #f0f0f1; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; }
			.negar-club-card__title { margin: 0; font-size: 16px; font-weight: 700; color: #1d2327; display: flex; align-items: center; gap: 8px; }
			.negar-club-card__desc { color: #646970; font-size: 13px; margin: 4px 0 0; }
			.negar-club-badge-tag { background: #e7f5ea; color: #185e2b; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 12px; }
			.negar-club-plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); gap: 20px; }
			.negar-plan-box { background: #fdfdfd; border: 1px solid #dcdcde; border-radius: 6px; padding: 16px; border-top: 4px solid #de1f38; }
			.negar-plan-box.is-featured { border-top-color: #2271b1; background: #fafcff; }
			.negar-plan-box h4 { margin: 0 0 12px; font-size: 15px; color: #1d2327; display: flex; justify-content: space-between; align-items: center; }
			.negar-field-row { margin-bottom: 14px; }
			.negar-field-row label { display: block; font-weight: 600; font-size: 12px; margin-bottom: 4px; color: #2c3338; }
			.negar-field-row input[type="text"], .negar-field-row input[type="number"], .negar-field-row textarea { width: 100%; border-radius: 4px; }
			.negar-toggles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
			.negar-toggle-item { display: flex; align-items: center; gap: 8px; background: #f6f7f7; padding: 10px 14px; border-radius: 4px; border: 1px solid #e2e4e7; }
			.negar-toggle-item input[type="checkbox"] { margin: 0; }
			.negar-faq-row { background: #f9f9fb; border: 1px solid #dcdcde; border-radius: 6px; padding: 14px; margin-bottom: 12px; position: relative; }
			.negar-faq-row .remove-faq-btn { color: #b32d2e; border: 1px solid #dcdcde; background: #fff; cursor: pointer; padding: 4px 10px; border-radius: 4px; font-size: 12px; }
			.negar-faq-row .remove-faq-btn:hover { background: #b32d2e; color: #fff; border-color: #b32d2e; }
			.negar-faq-actions { margin-top: 14px; }
			.negar-currency-suffix { color: #646970; font-size: 11px; margin-right: 4px; }
			.negar-help-text { font-size: 11px; color: #646970; margin-top: 3px; }
		</style>

		<!-- Card 1: Master Toggles -->
		<div class="negar-club-card">
			<div class="negar-club-card__header">
				<div>
					<h3 class="negar-club-card__title">
						<span class="dashicons dashicons-admin-generic" style="color: #de1f38;"></span>
						<?php esc_html_e( 'وضعیت عمومی و کنترل دسترسی باشگاه مشتریان', 'negar-shop' ); ?>
					</h3>
					<p class="negar-club-card__desc"><?php esc_html_e( 'فعال‌سازی سراسری صفحه باشگاه و تعیین وضعیت پذیرش درخواست‌های تسهیلات خرید اقساطی.', 'negar-shop' ); ?></p>
				</div>
				<span class="negar-club-badge-tag"><?php esc_html_e( 'هسته تنظیمی', 'negar-shop' ); ?></span>
			</div>

			<div class="negar-toggles-grid">
				<label class="negar-toggle-item">
					<input type="checkbox" name="<?php echo esc_attr( $opt_key ); ?>[is_enabled]" value="1" <?php checked( $options['is_enabled'] ); ?>>
					<span><strong><?php esc_html_e( 'فعال‌سازی صفحه عمومی باشگاه مشتریان', 'negar-shop' ); ?></strong></span>
				</label>

				<label class="negar-toggle-item">
					<input type="checkbox" name="<?php echo esc_attr( $opt_key ); ?>[allow_applications]" value="1" <?php checked( $options['allow_applications'] ); ?>>
					<span><strong><?php esc_html_e( 'پذیرش فعال درخواست‌های اقساطی', 'negar-shop' ); ?></strong></span>
				</label>
			</div>

			<div class="negar-field-row" style="margin-top: 16px;">
				<label for="negar-club-disabled-notice"><?php esc_html_e( 'پیام اطلاع‌رسانی در زمان عدم پذیرش درخواست:', 'negar-shop' ); ?></label>
				<input id="negar-club-disabled-notice" type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][disabled_notice]" value="<?php echo esc_attr( $options['copy']['disabled_notice'] ?? '' ); ?>" class="large-text">
				<p class="negar-help-text"><?php esc_html_e( 'این متن در صورت غیرفعال بودن گزینه «پذیرش فعال درخواست‌ها» به جای دکمه ثبت درخواست نمایش داده می‌شود.', 'negar-shop' ); ?></p>
			</div>
		</div>

		<!-- Card 2: Three Fixed Plans -->
		<div class="negar-club-card">
			<div class="negar-club-card__header">
				<div>
					<h3 class="negar-club-card__title">
						<span class="dashicons dashicons-money-alt" style="color: #2271b1;"></span>
						<?php esc_html_e( 'پیکربندی طرح‌های سه‌گانه خرید اقساطی', 'negar-shop' ); ?>
					</h3>
					<p class="negar-club-card__desc"><?php esc_html_e( 'تعیین مبالغ پیش‌پرداخت، سقف تسهیلات، مدت زمان بازپرداخت و ویژگی‌های هر یک از طرح‌ها به تومان.', 'negar-shop' ); ?></p>
				</div>
			</div>

			<div class="negar-club-plans-grid">
				<?php
				$plans = $options['plans'] ?? array();
				foreach ( array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' ) as $p_key ) :
					$plan = $plans[ $p_key ] ?? array();
					$p_prefix = $opt_key . '[plans][' . $p_key . ']';
					$is_feat  = ! empty( $plan['is_featured'] );
					$is_en    = ! empty( $plan['is_enabled'] );
				?>
					<div class="negar-plan-box <?php echo $is_feat ? 'is-featured' : ''; ?>">
						<h4>
							<span><?php echo esc_html( $plan['title'] ?? $p_key ); ?></span>
							<label style="font-size: 12px; font-weight: normal; cursor: pointer;">
								<input type="checkbox" name="<?php echo esc_attr( $p_prefix ); ?>[is_enabled]" value="1" <?php checked( $is_en ); ?>>
								<?php esc_html_e( 'فعال', 'negar-shop' ); ?>
							</label>
						</h4>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'عنوان نمایشی طرح:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $p_prefix ); ?>[title]" value="<?php echo esc_attr( $plan['title'] ?? '' ); ?>">
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'توضیح کوتاه / شعار طرح:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $p_prefix ); ?>[subtitle]" value="<?php echo esc_attr( $plan['subtitle'] ?? '' ); ?>">
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'مبلغ پیش‌پرداخت (تومان):', 'negar-shop' ); ?></label>
							<input type="number" step="50000" min="0" name="<?php echo esc_attr( $p_prefix ); ?>[initial_payment]" value="<?php echo esc_attr( (string) ( $plan['initial_payment'] ?? 0 ) ); ?>">
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'سقف خرید تسهیلات (تومان):', 'negar-shop' ); ?></label>
							<input type="number" step="50000" min="0" name="<?php echo esc_attr( $p_prefix ); ?>[ceiling]" value="<?php echo esc_attr( (string) ( $plan['ceiling'] ?? 0 ) ); ?>">
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'مدت بازپرداخت (ماه):', 'negar-shop' ); ?></label>
							<input type="number" min="1" max="60" name="<?php echo esc_attr( $p_prefix ); ?>[duration_months]" value="<?php echo esc_attr( (string) ( $plan['duration_months'] ?? 3 ) ); ?>">
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'برچسب تمایز / نشان بازاریابی:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $p_prefix ); ?>[badge]" value="<?php echo esc_attr( $plan['badge'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'مثال: انتخاب محبوب', 'negar-shop' ); ?>">
						</div>

						<div class="negar-field-row">
							<label style="cursor: pointer;">
								<input type="checkbox" name="<?php echo esc_attr( $p_prefix ); ?>[is_featured]" value="1" <?php checked( $is_feat ); ?>>
								<span><?php esc_html_e( 'علامت‌گذاری به عنوان طرح برجسته / منتخب', 'negar-shop' ); ?></span>
							</label>
						</div>

						<div class="negar-field-row">
							<label><?php esc_html_e( 'ویژگی‌های طرح (هر سطر یک ویژگی):', 'negar-shop' ); ?></label>
							<?php
							$feat_text = is_array( $plan['features'] ?? null ) ? implode( "\n", $plan['features'] ) : '';
							?>
							<textarea name="<?php echo esc_attr( $p_prefix ); ?>[features]" rows="4"><?php echo esc_textarea( $feat_text ); ?></textarea>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Card 3: Page Content & Section Visibility -->
		<div class="negar-club-card">
			<div class="negar-club-card__header">
				<div>
					<h3 class="negar-club-card__title">
						<span class="dashicons dashicons-layout" style="color: #46b450;"></span>
						<?php esc_html_e( 'محتوای صفحه عمومی و کنترل نمایش بخش‌ها', 'negar-shop' ); ?>
					</h3>
					<p class="negar-club-card__desc"><?php esc_html_e( 'مدیریت متن‌های هیرو، دکمه‌های فراخوان و تعیین فعال بودن سکشن‌های مختلف صفحه.', 'negar-shop' ); ?></p>
				</div>
			</div>

			<h4 style="margin: 0 0 10px; font-size: 14px;"><?php esc_html_e( 'نمایش سکشن‌های صفحه عمومی:', 'negar-shop' ); ?></h4>
			<div class="negar-toggles-grid" style="margin-bottom: 20px;">
				<?php
				$sec_labels = array(
					'hero'       => __( 'بخش بنر هیرو', 'negar-shop' ),
					'benefits'   => __( 'کارت‌های مزایای خرید اقساطی', 'negar-shop' ),
					'plans'      => __( 'کارت‌های سه‌گانه طرح‌ها', 'negar-shop' ),
					'comparison' => __( 'جدول مقایسه طرح‌ها', 'negar-shop' ),
					'calculator' => __( 'ماشین‌حساب پیش‌نمایش اقساط', 'negar-shop' ),
					'timeline'   => __( 'مراحل دریافت تسهیلات', 'negar-shop' ),
					'faq'        => __( 'پرسش‌های متداول', 'negar-shop' ),
					'cta'        => __( 'باکس فراخوان نهایی خرید', 'negar-shop' ),
				);
				$vis = $options['sections_visibility'] ?? array();
				foreach ( $sec_labels as $s_id => $s_label ) :
					$checked = ! empty( $vis[ $s_id ] );
				?>
					<label class="negar-toggle-item">
						<input type="checkbox" name="<?php echo esc_attr( $opt_key ); ?>[sections_visibility][<?php echo esc_attr( $s_id ); ?>]" value="1" <?php checked( $checked ); ?>>
						<span><?php echo esc_html( $s_label ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>

			<h4 style="margin: 20px 0 10px; font-size: 14px; border-top: 1px solid #f0f0f1; padding-top: 15px;"><?php esc_html_e( 'متن‌های هیرو و فراخوان‌ها:', 'negar-shop' ); ?></h4>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
				<div class="negar-field-row">
					<label><?php esc_html_e( 'عنوان هیرو:', 'negar-shop' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][hero_title]" value="<?php echo esc_attr( $options['copy']['hero_title'] ?? '' ); ?>">
				</div>

				<div class="negar-field-row">
					<label><?php esc_html_e( 'متن دکمه فراخوان هیرو:', 'negar-shop' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][hero_cta_text]" value="<?php echo esc_attr( $options['copy']['hero_cta_text'] ?? '' ); ?>">
				</div>
			</div>

			<div class="negar-field-row">
				<label><?php esc_html_e( 'توضیحات هیرو:', 'negar-shop' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][hero_subtitle]" value="<?php echo esc_attr( $options['copy']['hero_subtitle'] ?? '' ); ?>">
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-top: 14px;">
				<div class="negar-field-row">
					<label><?php esc_html_e( 'عنوان باکس فراخوان پایانی:', 'negar-shop' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][cta_title]" value="<?php echo esc_attr( $options['copy']['cta_title'] ?? '' ); ?>">
				</div>

				<div class="negar-field-row">
					<label><?php esc_html_e( 'متن دکمه فراخوان پایانی:', 'negar-shop' ); ?></label>
					<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][cta_button_text]" value="<?php echo esc_attr( $options['copy']['cta_button_text'] ?? '' ); ?>">
				</div>
			</div>

			<div class="negar-field-row">
				<label><?php esc_html_e( 'توضیحات باکس فراخوان پایانی:', 'negar-shop' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $opt_key ); ?>[copy][cta_subtitle]" value="<?php echo esc_attr( $options['copy']['cta_subtitle'] ?? '' ); ?>">
			</div>

			<h4 style="margin: 20px 0 10px; font-size: 14px; border-top: 1px solid #f0f0f1; padding-top: 15px;"><?php esc_html_e( 'مراحل گام‌به‌گام تسهیلات (۴ مرحله):', 'negar-shop' ); ?></h4>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 14px;">
				<?php
				$steps = $options['copy']['timeline_steps'] ?? array();
				for ( $i = 0; $i < 4; $i++ ) :
					$step_num = $i + 1;
					$st = $steps[ $i ] ?? array( 'step' => $step_num, 'title' => '', 'description' => '' );
					$st_prefix = $opt_key . '[copy][timeline_steps][' . $i . ']';
				?>
					<div style="background: #fdfdfd; border: 1px solid #e2e4e7; border-radius: 4px; padding: 10px;">
						<input type="hidden" name="<?php echo esc_attr( $st_prefix ); ?>[step]" value="<?php echo esc_attr( (string) $step_num ); ?>">
						<strong><?php printf( esc_html__( 'گام %d', 'negar-shop' ), (int) $step_num ); ?></strong>
						<div class="negar-field-row" style="margin-top: 8px;">
							<label><?php esc_html_e( 'عنوان گام:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $st_prefix ); ?>[title]" value="<?php echo esc_attr( $st['title'] ?? '' ); ?>">
						</div>
						<div class="negar-field-row">
							<label><?php esc_html_e( 'شرح مختصر:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $st_prefix ); ?>[description]" value="<?php echo esc_attr( $st['description'] ?? '' ); ?>">
						</div>
					</div>
				<?php endfor; ?>
			</div>
		</div>

		<!-- Card 4: Dynamic FAQ Repeater -->
		<div class="negar-club-card">
			<div class="negar-club-card__header">
				<div>
					<h3 class="negar-club-card__title">
						<span class="dashicons dashicons-editor-help" style="color: #f56e28;"></span>
						<?php esc_html_e( 'مدیریت پرسش‌ها و پاسخ‌های متداول (FAQ)', 'negar-shop' ); ?>
					</h3>
					<p class="negar-club-card__desc"><?php esc_html_e( 'افزودن، ویرایش و حذف سؤالات متداول خریداران درباره شرایط تسهیلات و اقساط.', 'negar-shop' ); ?></p>
				</div>
			</div>

			<div id="negar-faq-list">
				<?php
				$faqs = $options['faqs'] ?? array();
				if ( empty( $faqs ) ) {
					$faqs = array( array( 'question' => '', 'answer' => '' ) );
				}
				foreach ( $faqs as $f_idx => $faq ) :
					$f_prefix = $opt_key . '[faqs][' . $f_idx . ']';
				?>
					<div class="negar-faq-row">
						<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
							<span style="font-weight: 700; font-size: 13px; color: #50575e;">
								<?php printf( esc_html__( 'پرسش #%d', 'negar-shop' ), (int) ( $f_idx + 1 ) ); ?>
							</span>
							<button type="button" class="remove-faq-btn" onclick="negarRemoveFaqRow(this);">
								<span class="dashicons dashicons-trash" style="font-size: 15px; vertical-align: middle;"></span>
								<?php esc_html_e( 'حذف', 'negar-shop' ); ?>
							</button>
						</div>
						<div class="negar-field-row">
							<label><?php esc_html_e( 'متن پرسش:', 'negar-shop' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $f_prefix ); ?>[question]" value="<?php echo esc_attr( $faq['question'] ?? '' ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'پرسش خود را وارد کنید...', 'negar-shop' ); ?>">
						</div>
						<div class="negar-field-row">
							<label><?php esc_html_e( 'پاسخ کامل:', 'negar-shop' ); ?></label>
							<textarea name="<?php echo esc_attr( $f_prefix ); ?>[answer]" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'پاسخ به این پرسش را بنویسید...', 'negar-shop' ); ?>"><?php echo esc_textarea( $faq['answer'] ?? '' ); ?></textarea>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="negar-faq-actions">
				<button type="button" id="negar-add-faq-btn" class="button button-secondary" onclick="negarAddFaqRow();">
					<span class="dashicons dashicons-plus-alt2" style="vertical-align: middle;"></span>
					<?php esc_html_e( 'افزودن پرسش جدید', 'negar-shop' ); ?>
				</button>
			</div>
		</div>

		<script>
		function negarAddFaqRow() {
			var list = document.getElementById('negar-faq-list');
			if (!list) return;
			var idx = list.querySelectorAll('.negar-faq-row').length;
			var div = document.createElement('div');
			div.className = 'negar-faq-row';
			div.innerHTML = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">' +
				'<span style="font-weight:700;font-size:13px;color:#50575e;">پرسش #' + (idx + 1) + '</span>' +
				'<button type="button" class="remove-faq-btn" onclick="negarRemoveFaqRow(this);"><span class="dashicons dashicons-trash" style="font-size:15px;vertical-align:middle;"></span> حذف</button>' +
				'</div>' +
				'<div class="negar-field-row"><label>متن پرسش:</label><input type="text" name="<?php echo esc_js( $opt_key ); ?>[faqs][' + idx + '][question]" value="" class="large-text" placeholder="پرسش خود را وارد کنید..."></div>' +
				'<div class="negar-field-row"><label>پاسخ کامل:</label><textarea name="<?php echo esc_js( $opt_key ); ?>[faqs][' + idx + '][answer]" rows="3" class="large-text" placeholder="پاسخ به این پرسش را بنویسید..."></textarea></div>';
			list.appendChild(div);
		}

		function negarRemoveFaqRow(btn) {
			var row = btn.closest('.negar-faq-row');
			if (!row) return;
			var list = document.getElementById('negar-faq-list');
			if (list && list.querySelectorAll('.negar-faq-row').length > 1) {
				row.remove();
			} else {
				row.querySelectorAll('input, textarea').forEach(function(el) { el.value = ''; });
			}
		}
		</script>
	</div>
	<?php
}
