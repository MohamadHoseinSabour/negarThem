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
define( 'NEGAR_THEME_MEDIA_OPTION', 'negar_theme_media_options' );
define( 'NEGAR_THEME_MEDIA_VERSION', 1 );
define( 'NEGAR_THEME_PRODUCT_PACK_OPTION', 'negar_theme_product_pack_options' );
define( 'NEGAR_THEME_STORY_OPTION', 'negar_theme_story_options' );
define( 'NEGAR_THEME_STORY_VERSION', 1 );
define( 'NEGAR_THEME_BRAND_OPTION', 'negar_theme_brand_options' );
define( 'NEGAR_THEME_BRAND_VERSION', 1 );

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
			if ( ! is_array( $row ) || ! isset( $row['id'] ) || ! is_scalar( $row['id'] ) ) {
				continue;
			}

			$id = sanitize_key( $row['id'] );
			if ( ! isset( $registry[ $id ] ) || isset( $seen[ $id ] ) ) {
				continue;
			}

			$seen[ $id ] = true;
			$enabled     = isset( $row['enabled'] ) && is_scalar( $row['enabled'] ) ? $row['enabled'] : null;
			$clean[]     = array(
				'id'      => $id,
				'enabled' => null === $enabled || ! in_array( $enabled, array( false, 0, '0', 'false' ), true ),
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
 * Return the homepage product-query registry for Phase 4.
 *
 * Only sections already backed by real product queries are included. Banners,
 * stories, visitor history, review feeds, and demo/product-pack components stay
 * outside this registry until their own approved phases.
 *
 * @return array<string, array<string, mixed>>
 */
function negar_theme_product_section_registry() {
	return array(
		'flash-deals'              => array( 'label' => __( 'پیشنهادهای شگفت‌انگیز', 'negar-shop' ), 'count' => 8, 'sort' => 'date_desc', 'sale_only' => true ),
		'product-tabs-laptops'     => array( 'label' => __( 'تب محصولات: لپ‌تاپ', 'negar-shop' ), 'count' => 5, 'sort' => 'date_desc', 'category_slugs' => array( 'laptop', 'لپ-تاپ', 'لپتاپ' ) ),
		'product-tabs-consoles'    => array( 'label' => __( 'تب محصولات: کنسول', 'negar-shop' ), 'count' => 5, 'sort' => 'date_desc', 'empty_category_sort' => 'random', 'category_slugs' => array( 'console', 'کنسول-بازی', 'کنسول' ) ),
		'product-tabs-mobile'      => array( 'label' => __( 'تب محصولات: موبایل', 'negar-shop' ), 'count' => 5, 'sort' => 'date_desc', 'category_slugs' => array( 'mobile', 'گوشی-موبایل', 'موبایل' ) ),
		'product-tabs-watches'     => array( 'label' => __( 'تب محصولات: ساعت هوشمند', 'negar-shop' ), 'count' => 5, 'sort' => 'date_desc', 'empty_category_sort' => 'comment_count_desc', 'category_slugs' => array( 'smartwatch', 'ساعت-هوشمند' ) ),
		'accessories'              => array( 'label' => __( 'لوازم جانبی', 'negar-shop' ), 'count' => 9, 'sort' => 'date_desc' ),
		'visited-products'         => array( 'label' => __( 'محصولات پربازدید', 'negar-shop' ), 'count' => 5, 'sort' => 'comment_count_desc' ),
		'gaming-laptops'           => array( 'label' => __( 'لپ‌تاپ‌های گیمینگ', 'negar-shop' ), 'count' => 4, 'sort' => 'date_desc', 'category_slugs' => array( 'laptop', 'لپ-تاپ', 'گیمینگ' ) ),
		'multi-category-products'  => array( 'label' => __( 'محصولات منتخب چنددسته‌ای', 'negar-shop' ), 'count' => 5, 'sort' => 'random' ),
		'mobile-spotlight-products' => array( 'label' => __( 'کارت‌های کناری ویترین موبایل', 'negar-shop' ), 'count' => 3, 'sort' => 'date_desc' ),
	);
}

/**
 * Return valid selectable product-section sort modes.
 *
 * @return array<string, string>
 */
function negar_theme_product_section_sort_options() {
	return array(
		'date_desc'          => __( 'جدیدترین', 'negar-shop' ),
		'date_asc'           => __( 'قدیمی‌ترین', 'negar-shop' ),
		'title_asc'          => __( 'نام محصول (الف تا ی)', 'negar-shop' ),
		'title_desc'         => __( 'نام محصول (ی تا الف)', 'negar-shop' ),
		'total_sales_desc'   => __( 'پرفروش‌ترین', 'negar-shop' ),
		'rating_desc'        => __( 'بالاترین امتیاز', 'negar-shop' ),
		'price_asc'          => __( 'ارزان‌ترین', 'negar-shop' ),
		'price_desc'         => __( 'گران‌ترین', 'negar-shop' ),
		'comment_count_desc' => __( 'بیشترین دیدگاه', 'negar-shop' ),
		'random'             => __( 'تصادفی', 'negar-shop' ),
	);
}

/**
 * Resolve current category IDs used by a legacy product query.
 *
 * @param array<int, string> $slugs Category slugs.
 * @return array<int, int>
 */
function negar_theme_product_category_ids_from_slugs( $slugs ) {
	$ids = array();

	foreach ( $slugs as $slug ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$ids[] = (int) $term->term_id;
		}
	}

	return array_values( array_unique( array_filter( $ids ) ) );
}

/**
 * Return defaults for one registered product section.
 *
 * @param string $section_id Product-section ID.
 * @return array<string, mixed>
 */
function negar_theme_product_section_defaults( $section_id ) {
	$registry = negar_theme_product_section_registry();
	$section_id = sanitize_key( $section_id );

	if ( ! isset( $registry[ $section_id ] ) ) {
		return array();
	}

	$entry       = $registry[ $section_id ];
	$include_ids = isset( $entry['category_slugs'] ) ? negar_theme_product_category_ids_from_slugs( $entry['category_slugs'] ) : array();
	$sort        = $entry['sort'];
	if ( empty( $include_ids ) && isset( $entry['empty_category_sort'] ) ) {
		$sort = $entry['empty_category_sort'];
	}

	return array(
		'count'              => (int) $entry['count'],
		'sort'               => $sort,
		'include_categories' => $include_ids,
		'exclude_categories' => array(),
		'product_ids'        => array(),
	);
}

/**
 * Return whether an administrator has saved configuration for a product section.
 *
 * @param string $section_id Product-section ID.
 * @return bool
 */
function negar_theme_product_section_is_configured( $section_id ) {
	$settings = negar_theme_settings_raw();
	$saved    = isset( $settings['products']['sections'][ $section_id ] ) ? $settings['products']['sections'][ $section_id ] : null;

	if ( ! is_array( $saved ) ) {
		return false;
	}

	// Phase 4 configurations did not have an explicit state. Keep each of
	// those saved configurations active rather than changing their behaviour.
	return ! isset( $saved['mode'] ) || 'custom' === $saved['mode'];
}

/**
 * Return saved or documented-default configuration for one product section.
 *
 * @param string $section_id Product-section ID.
 * @return array<string, mixed>
 */
function negar_theme_product_section_config( $section_id ) {
	$defaults = negar_theme_product_section_defaults( $section_id );
	$settings = negar_theme_settings_raw();
	$saved    = isset( $settings['products']['sections'][ $section_id ] ) && is_array( $settings['products']['sections'][ $section_id ] ) ? $settings['products']['sections'][ $section_id ] : array();

	return wp_parse_args( $saved, $defaults );
}

/**
 * Add the WooCommerce catalog-visibility exclusion to a product query.
 *
 * @param array<string, mixed> $args Product query arguments.
 * @return array<string, mixed>
 */
function negar_theme_catalog_visibility_query_args( $args ) {
	$args      = is_array( $args ) ? $args : array();
	$tax_query = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

	if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
		$visibility = wc_get_product_visibility_term_ids();
		$hidden_id  = isset( $visibility['exclude-from-catalog'] ) ? absint( $visibility['exclude-from-catalog'] ) : 0;
		if ( $hidden_id ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_id',
				'terms'    => array( $hidden_id ),
				'operator' => 'NOT IN',
			);
		}
	}

	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	return $args;
}

/**
 * Build a safe product query from a registered saved configuration.
 *
 * This is intentionally used only after a section is configured. Templates keep
 * their legacy query choices until that specific section is explicitly enabled.
 *
 * @param string               $section_id Product-section ID.
 * @param array<string, mixed> $default_args Existing query arguments.
 * @param array<int, int>|null $required_ids Optional real-product ID restriction.
 * @return array<string, mixed>
 */
function negar_theme_product_section_query_args( $section_id, $default_args, $required_ids = null ) {
	$config  = negar_theme_product_section_config( $section_id );
	$sorts   = negar_theme_product_section_sort_options();
	$args    = $default_args;
	$sort    = isset( $config['sort'] ) && isset( $sorts[ $config['sort'] ] ) ? $config['sort'] : 'date_desc';
	$mapping = array(
		'date_desc'          => array( 'orderby' => 'date', 'order' => 'DESC' ),
		'date_asc'           => array( 'orderby' => 'date', 'order' => 'ASC' ),
		'title_asc'          => array( 'orderby' => 'title', 'order' => 'ASC' ),
		'title_desc'         => array( 'orderby' => 'title', 'order' => 'DESC' ),
		'total_sales_desc'   => array( 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num', 'order' => 'DESC' ),
		'rating_desc'        => array( 'meta_key' => '_wc_average_rating', 'orderby' => 'meta_value_num', 'order' => 'DESC' ),
		'price_asc'          => array( 'meta_key' => '_price', 'orderby' => 'meta_value_num', 'order' => 'ASC' ),
		'price_desc'         => array( 'meta_key' => '_price', 'orderby' => 'meta_value_num', 'order' => 'DESC' ),
		'comment_count_desc' => array( 'orderby' => 'comment_count', 'order' => 'DESC' ),
		'random'             => array( 'orderby' => 'rand' ),
	);

	$args['posts_per_page'] = max( 1, min( 12, (int) $config['count'] ) );
	$args['orderby']        = $mapping[ $sort ]['orderby'];
	if ( isset( $mapping[ $sort ]['meta_key'] ) ) {
		$args['meta_key'] = $mapping[ $sort ]['meta_key'];
	} else {
		unset( $args['meta_key'] );
	}
	if ( isset( $mapping[ $sort ]['order'] ) ) {
		$args['order'] = $mapping[ $sort ]['order'];
	} else {
		unset( $args['order'] );
	}

	$manual_ids = isset( $config['product_ids'] ) && is_array( $config['product_ids'] ) ? array_map( 'absint', $config['product_ids'] ) : array();
	$manual_ids = array_values( array_unique( array_filter( $manual_ids ) ) );
	if ( null !== $required_ids ) {
		$required_ids = array_values( array_unique( array_filter( array_map( 'absint', $required_ids ) ) ) );
		$manual_ids   = ! empty( $manual_ids ) ? array_values( array_intersect( $manual_ids, $required_ids ) ) : $required_ids;
	}

	if ( ! empty( $manual_ids ) || null !== $required_ids ) {
		$args['post__in'] = ! empty( $manual_ids ) ? $manual_ids : array( 0 );
	}

	unset( $args['tax_query'] );
	$tax_query = array();
	$include   = isset( $config['include_categories'] ) && is_array( $config['include_categories'] ) ? array_map( 'absint', $config['include_categories'] ) : array();
	$exclude   = isset( $config['exclude_categories'] ) && is_array( $config['exclude_categories'] ) ? array_map( 'absint', $config['exclude_categories'] ) : array();
	if ( ! empty( $include ) ) {
		$tax_query[] = array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => array_values( array_unique( array_filter( $include ) ) ) );
	}
	if ( ! empty( $exclude ) ) {
		$tax_query[] = array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => array_values( array_unique( array_filter( $exclude ) ) ), 'operator' => 'NOT IN' );
	}
	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	return negar_theme_catalog_visibility_query_args( $args );
}

/**
 * Keep only existing product-category term IDs from submitted input.
 *
 * @param mixed $ids Candidate term IDs.
 * @return array<int, int>
 */
function negar_theme_sanitize_product_category_ids( $ids ) {
	$clean = array();
	if ( ! is_array( $ids ) ) {
		return $clean;
	}

	foreach ( $ids as $id ) {
		if ( ! is_scalar( $id ) ) {
			continue;
		}
		$term = get_term( absint( $id ), 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$clean[] = (int) $term->term_id;
		}
	}

	return array_values( array_unique( $clean ) );
}

/**
 * Keep only published catalog-visible WooCommerce product IDs from submitted input.
 *
 * @param mixed $ids Candidate product IDs.
 * @return array<int, int>
 */
function negar_theme_sanitize_product_ids( $ids ) {
	$clean = array();
	if ( ! is_array( $ids ) ) {
		return $clean;
	}

	foreach ( $ids as $id ) {
		if ( ! is_scalar( $id ) ) {
			continue;
		}
		$id = absint( $id );
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $id ) : false;
		if ( $id && 'product' === get_post_type( $id ) && 'publish' === get_post_status( $id ) && $product && $product->is_visible() ) {
			$clean[] = $id;
		}
	}

	return array_slice( array_values( array_unique( $clean ) ), 0, 12 );
}

/**
 * Sanitize one whitelist product-section configuration.
 *
 * @param mixed $submitted Candidate configuration.
 * @param string $section_id Registered section ID.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_product_section_config( $submitted, $section_id ) {
	$defaults = negar_theme_product_section_defaults( $section_id );
	$submitted = is_array( $submitted ) ? $submitted : array();
	$sorts = negar_theme_product_section_sort_options();
	$count = isset( $submitted['count'] ) && is_scalar( $submitted['count'] ) ? absint( $submitted['count'] ) : $defaults['count'];
	$sort  = isset( $submitted['sort'] ) && is_scalar( $submitted['sort'] ) ? sanitize_key( $submitted['sort'] ) : $defaults['sort'];

	return array(
		'mode'               => 'custom',
		'count'              => max( 1, min( 12, $count ) ),
		'sort'               => isset( $sorts[ $sort ] ) ? $sort : $defaults['sort'],
		'include_categories' => negar_theme_sanitize_product_category_ids( isset( $submitted['include_categories'] ) ? $submitted['include_categories'] : array() ),
		'exclude_categories' => negar_theme_sanitize_product_category_ids( isset( $submitted['exclude_categories'] ) ? $submitted['exclude_categories'] : array() ),
		'product_ids'        => negar_theme_sanitize_product_ids( isset( $submitted['product_ids'] ) ? $submitted['product_ids'] : array() ),
	);
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
 * Return the fixed Phase 5 hero and banner records.
 *
 * The record IDs are deliberately code-owned.  Saving a record can never choose a
 * template or create an arbitrary collection.  Text fields exist only where the
 * original template already rendered editorial text.
 *
 * @return array<string, array<string, mixed>>
 */
function negar_theme_media_registry() {
	$upload_dir = wp_upload_dir();
	$base       = $upload_dir['baseurl'] . '/kaveh-demo';
	$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$banner     = array(
		'enabled'  => true,
		'link'     => $shop_url,
		'alt'      => __( 'محل قرار گیری بنر', 'negar-shop' ),
		'title'    => __( 'محل قرار گیری بنر', 'negar-shop' ),
		'subtitle' => __( 'همین الان با تخفیف خرید کنید', 'negar-shop' ),
		'cta_label'=> __( 'مشاهده بیشتر', 'negar-shop' ),
		'fields'   => array( 'media_id', 'link', 'alt', 'title', 'subtitle', 'cta_label', 'enabled' ),
	);
	$image_only = array(
		'enabled' => true,
		'link'    => $shop_url,
		'fields'  => array( 'media_id', 'link', 'alt', 'enabled' ),
	);

	$registry = array(
		'hero-slide-one' => array_merge( $image_only, array( 'label' => __( 'اسلاید نخست Hero', 'negar-shop' ), 'fallback_url' => $base . '/landing-01-300x261-1.png', 'alt' => __( 'خرید برترین محصولات', 'negar-shop' ) ) ),
		'hero-slide-two' => array_merge( $image_only, array( 'label' => __( 'اسلاید دوم Hero', 'negar-shop' ), 'fallback_url' => $base . '/landing-01-300x261-1.png', 'alt' => __( 'کنسول‌های بازی نسل نهم', 'negar-shop' ) ) ),
		'banners-grid-one' => array_merge( $banner, array( 'label' => __( 'بنر دو ستونه ۱ › راست', 'negar-shop' ), 'fallback_url' => $base . '/banner-01.png' ) ),
		'banners-grid-two' => array_merge( $banner, array( 'label' => __( 'بنر دو ستونه ۱ › چپ', 'negar-shop' ), 'fallback_url' => $base . '/banner-02-1.png' ) ),
		'wide-banner-one' => array_merge( $image_only, array( 'label' => __( 'بنر عریض', 'negar-shop' ), 'fallback_url' => $base . '/banner-03.png', 'alt' => __( 'پیشنهاد ویژه دیجیتال', 'negar-shop' ) ) ),
	);

	foreach ( array( '10', '18', '29', '32' ) as $section ) {
		$registry[ 'banners-' . $section . '-one' ] = array_merge( $banner, array( 'label' => sprintf( __( 'بنر دو ستونه %s › راست', 'negar-shop' ), $section ), 'fallback_url' => $base . '/banner-01.png' ) );
		$registry[ 'banners-' . $section . '-two' ] = array_merge( $banner, array( 'label' => sprintf( __( 'بنر دو ستونه %s › چپ', 'negar-shop' ), $section ), 'fallback_url' => $base . '/banner-02-1.png' ) );
	}
	foreach ( array( '12', '27' ) as $section ) {
		$registry[ 'compact-' . $section . '-one' ] = array_merge( $image_only, array( 'label' => sprintf( __( 'بنر فشرده %s › راست', 'negar-shop' ), $section ), 'fallback_url' => $base . '/banner-04.png', 'alt' => __( 'تخفیف ویژه کالای دیجیتال', 'negar-shop' ) ) );
		$registry[ 'compact-' . $section . '-two' ] = array_merge( $image_only, array( 'label' => sprintf( __( 'بنر فشرده %s › چپ', 'negar-shop' ), $section ), 'fallback_url' => $base . '/banner-05.png', 'alt' => __( 'پیشنهاد خرید لوازم جانبی', 'negar-shop' ) ) );
	}

	return $registry;
}

/**
 * Read a schema-validated media record with its immutable demo fallback.
 *
 * @param string $record_id Stable record ID.
 * @return array<string, mixed>
 */
function negar_theme_media_record( $record_id ) {
	$registry = negar_theme_media_registry();
	if ( ! isset( $registry[ $record_id ] ) ) {
		return array();
	}

	$record          = $registry[ $record_id ];
	$record['media_id'] = 0;
	$stored          = get_option( NEGAR_THEME_MEDIA_OPTION, array() );
	$stored_record   = is_array( $stored ) && isset( $stored['records'][ $record_id ] ) && is_array( $stored['records'][ $record_id ] ) ? $stored['records'][ $record_id ] : array();
	foreach ( $record['fields'] as $field ) {
		if ( array_key_exists( $field, $stored_record ) ) {
			$record[ $field ] = $stored_record[ $field ];
		}
	}

	$attachment_url = ! empty( $record['media_id'] ) ? wp_get_attachment_image_url( absint( $record['media_id'] ), 'full' ) : false;
	$record['image_url'] = $attachment_url ? $attachment_url : $record['fallback_url'];

	return $record;
}

/**
 * Resolve server-side rendering data for a single promotional banner record.
 *
 * Returns null when the banner record is missing or disabled.
 *
 * @param string $record_id Fixed media record ID.
 * @return array<string, mixed>|null
 */
function negar_theme_banner_item_data( $record_id ) {
	$record = negar_theme_media_record( $record_id );
	if ( empty( $record ) || empty( $record['enabled'] ) ) {
		return null;
	}

	$shop_link    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
	$default_url  = $shop_link ? $shop_link : home_url( '/shop/' );
	$raw_link     = isset( $record['link'] ) ? (string) $record['link'] : '';
	$safe_link    = negar_theme_sanitize_media_link( $raw_link );
	$resolved_url = '' !== $safe_link ? $safe_link : $default_url;

	$fallback_image = isset( $record['fallback_url'] ) ? (string) $record['fallback_url'] : '';
	$image_url      = isset( $record['image_url'] ) && '' !== $record['image_url'] ? (string) $record['image_url'] : $fallback_image;
	$alt_text       = array_key_exists( 'alt', $record ) ? (string) $record['alt'] : '';
	$title          = array_key_exists( 'title', $record ) ? (string) $record['title'] : '';
	$subtitle       = array_key_exists( 'subtitle', $record ) ? (string) $record['subtitle'] : '';
	$cta_label      = array_key_exists( 'cta_label', $record ) ? (string) $record['cta_label'] : '';
	$aria_label     = '' !== trim( $cta_label ) ? $cta_label : __( 'مشاهده بیشتر', 'negar-shop' );

	return array(
		'id'         => (string) $record_id,
		'image_url'  => $image_url,
		'link'       => $resolved_url,
		'alt'        => $alt_text,
		'title'      => $title,
		'subtitle'   => $subtitle,
		'cta_label'  => $cta_label,
		'aria_label' => $aria_label,
	);
}

/**
 * Render an editorial two-column banner grid server-side.
 *
 * Omits disabled banners cleanly, collapses to one column when a single banner
 * remains active, and omits the outer section when all banners are disabled.
 *
 * @param string                           $outer_classes Outer Elementor container CSS classes.
 * @param string                           $outer_attrs   Extra attributes for the outer container.
 * @param array<int, array<string, mixed>> $slots         Slot definitions (`record_id`, `widget_id`, optional `col_id`, `col_attrs`, `is_lazy`).
 * @return void
 */
function negar_theme_render_editorial_banner_grid( $outer_classes, $outer_attrs, $slots ) {
	$active_items = array();

	foreach ( $slots as $slot ) {
		$record_id = isset( $slot['record_id'] ) ? (string) $slot['record_id'] : '';
		$item      = negar_theme_banner_item_data( $record_id );
		if ( null === $item ) {
			continue;
		}
		$active_items[] = array_merge( $slot, array( 'data' => $item ) );
	}

	if ( empty( $active_items ) ) {
		return;
	}

	$grid_classes = 1 === count( $active_items )
		? 'grid grid-cols-1 gap-4 md:gap-5 negar-banners-2col--single'
		: 'grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5';

	$outer_attr_suffix = '' !== trim( (string) $outer_attrs ) ? ' ' . trim( (string) $outer_attrs ) : '';
	?>
<div class="<?php echo esc_attr( $outer_classes ); ?>"<?php echo $outer_attr_suffix; ?>>
	<div class="container max-w-[1320px] mx-auto px-3">
		<div class="<?php echo esc_attr( $grid_classes ); ?>">
			<?php foreach ( $active_items as $slot ) : ?>
				<?php
				$item        = $slot['data'];
				$widget_id   = isset( $slot['widget_id'] ) ? (string) $slot['widget_id'] : '';
				$col_id      = isset( $slot['col_id'] ) ? (string) $slot['col_id'] : '';
				$col_classes = '' !== $col_id
					? 'elementor-element elementor-element-' . $col_id . ' e-con-full e-flex e-con e-child'
					: 'elementor-element e-con-full e-flex e-con e-child';
				$col_attrs   = ! empty( $slot['col_attrs'] ) ? ' ' . trim( (string) $slot['col_attrs'] ) : '';
				$loading_attr = ( ! array_key_exists( 'is_lazy', $slot ) || ! empty( $slot['is_lazy'] ) ) ? ' loading="lazy"' : '';
				?>
				<div class="<?php echo esc_attr( $col_classes ); ?>"<?php echo $col_attrs; ?>>
					<div class="elementor-element elementor-element-<?php echo esc_attr( $widget_id ); ?> elementor-widget elementor-widget-bannernkkaveh w-full" data-id="<?php echo esc_attr( $widget_id ); ?>" data-element_type="widget" data-widget_type="bannernkkaveh.default">
						<div class="elementor-widget-container">
							<section class="banners-nk banners-nk-<?php echo esc_attr( $widget_id ); ?>" data-negar-banner-record="<?php echo esc_attr( $item['id'] ); ?>">
								<div class="banners-nk__item position-relative">
									<div class="detail">
										<a href="<?php echo esc_url( $item['link'] ); ?>" class="more d-flex align-items-center justify-content-center rounded-pill" aria-label="<?php echo esc_attr( $item['aria_label'] ); ?>">
											<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
										</a>
										<div class="title f-iran-yekan fw-bold"><?php echo esc_html( $item['title'] ); ?></div>
										<div class="sub-title f-iran-yekan fw-normal"><?php echo esc_html( $item['subtitle'] ); ?></div>
									</div>
									<img src="<?php echo esc_url( $item['image_url'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" width="218" height="245" class="banner-image position-absolute bottom-0"<?php echo $loading_attr; ?>>
								</div>
							</section>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
	<?php
}

/**
 * Render an asymmetric compact two-column image-only banner group server-side.
 *
 * Omits disabled banners cleanly, switches to a single-column container modifier
 * when one banner remains active, and omits the section when both are disabled.
 *
 * @param string                           $container_id Elementor container ID.
 * @param array<int, array<string, mixed>> $slots        Slot definitions (`record_id`, `widget_id`, `width`, `height`).
 * @return void
 */
function negar_theme_render_compact_banner_pair( $container_id, $slots ) {
	$active_items = array();

	foreach ( $slots as $slot ) {
		$record_id = isset( $slot['record_id'] ) ? (string) $slot['record_id'] : '';
		$item      = negar_theme_banner_item_data( $record_id );
		if ( null === $item ) {
			continue;
		}
		$active_items[] = array_merge( $slot, array( 'data' => $item ) );
	}

	if ( empty( $active_items ) ) {
		return;
	}

	$is_single         = 1 === count( $active_items );
	$container_classes = 'elementor-element elementor-element-' . $container_id . ' e-flex e-con-boxed e-con e-parent e-lazyloaded negar-dual-banners-asymmetric my-6' . ( $is_single ? ' negar-dual-banners-asymmetric--single' : '' );
	?>
<div class="<?php echo esc_attr( $container_classes ); ?>" data-id="<?php echo esc_attr( $container_id ); ?>" data-element_type="container" data-e-type="container">
	<div class="e-con-inner container">
		<?php foreach ( $active_items as $slot ) : ?>
			<?php
			$item      = $slot['data'];
			$widget_id = isset( $slot['widget_id'] ) ? (string) $slot['widget_id'] : '';
			$width     = isset( $slot['width'] ) ? absint( $slot['width'] ) : 773;
			$height    = isset( $slot['height'] ) ? absint( $slot['height'] ) : 228;
			?>
			<div class="elementor-element elementor-element-<?php echo esc_attr( $widget_id ); ?> elementor-widget elementor-widget-image" data-id="<?php echo esc_attr( $widget_id ); ?>" data-element_type="widget" data-e-type="widget" data-widget_type="image.default" data-negar-banner-record="<?php echo esc_attr( $item['id'] ); ?>">
				<a href="<?php echo esc_url( $item['link'] ); ?>" class="d-block w-100 h-100 overflow-hidden rounded-3xl group shadow-xs hover:shadow-md transition-all duration-300">
					<img loading="lazy" decoding="async" width="<?php echo esc_attr( (string) $width ); ?>" height="<?php echo esc_attr( (string) $height ); ?>" src="<?php echo esc_url( $item['image_url'] ); ?>" class="attachment-full size-full w-100 h-auto d-block group-hover:scale-102 transition-transform duration-300" alt="<?php echo esc_attr( $item['alt'] ); ?>">
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>
	<?php
}

/**
 * Accept safe site-relative paths and explicit HTTP(S) URLs; reject unsafe schemes.
 *
 * @param mixed $value Raw submitted URL.
 * @return string
 */
function negar_theme_sanitize_media_link( $value ) {
	$value = is_scalar( $value ) ? trim( (string) $value ) : '';
	if ( '' === $value || preg_match( '/[\x00-\x1F\x7F]/', $value ) ) {
		return '';
	}

	if ( 0 === strpos( $value, '/' ) ) {
		if ( 0 === strpos( $value, '//' ) || 0 === strpos( $value, '/\\' ) ) {
			return '';
		}
		$sanitized = esc_url_raw( $value, array( 'http', 'https' ) );
		if ( '' === $sanitized || 0 !== strpos( $sanitized, '/' ) || 0 === strpos( $sanitized, '//' ) ) {
			return '';
		}
		return $sanitized;
	}

	$parsed = wp_parse_url( $value );
	if ( ! is_array( $parsed ) || empty( $parsed['scheme'] ) || empty( $parsed['host'] ) ) {
		return '';
	}
	if ( ! in_array( strtolower( (string) $parsed['scheme'] ), array( 'http', 'https' ), true ) ) {
		return '';
	}

	$sanitized = esc_url_raw( $value, array( 'http', 'https' ) );
	$re_parsed = wp_parse_url( $sanitized );
	if ( ! is_array( $re_parsed ) || empty( $re_parsed['scheme'] ) || ! in_array( strtolower( (string) $re_parsed['scheme'] ), array( 'http', 'https' ), true ) ) {
		return '';
	}

	return $sanitized;
}

/**
 * Sanitize fixed media records without touching any other settings group.
 *
 * @param mixed $submitted Raw Settings API value.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_media_settings( $submitted ) {
	$current = get_option( NEGAR_THEME_MEDIA_OPTION, array() );
	$current = is_array( $current ) ? $current : array();
	if ( ! current_user_can( 'manage_options' ) ) {
		add_settings_error( 'negar_theme_media_settings', 'negar_theme_media_forbidden', __( 'اجازهٔ ذخیرهٔ رسانه را ندارید.', 'negar-shop' ), 'error' );
		return $current;
	}

	$submitted = is_array( $submitted ) ? wp_unslash( $submitted ) : array();
	$records   = isset( $submitted['records'] ) && is_array( $submitted['records'] ) ? $submitted['records'] : array();
	$clean     = array( 'schema_version' => NEGAR_THEME_MEDIA_VERSION, 'records' => array() );
	foreach ( negar_theme_media_registry() as $record_id => $definition ) {
		$input = isset( $records[ $record_id ] ) && is_array( $records[ $record_id ] ) ? $records[ $record_id ] : array();
		foreach ( $definition['fields'] as $field ) {
			if ( 'media_id' === $field ) {
				$media_id = isset( $input[ $field ] ) && is_scalar( $input[ $field ] ) ? absint( $input[ $field ] ) : 0;
				$clean['records'][ $record_id ][ $field ] = $media_id && wp_attachment_is_image( $media_id ) ? $media_id : 0;
			} elseif ( 'enabled' === $field ) {
				$clean['records'][ $record_id ][ $field ] = isset( $input[ $field ] ) && is_scalar( $input[ $field ] ) && '1' === (string) $input[ $field ];
			} elseif ( 'link' === $field ) {
				$clean['records'][ $record_id ][ $field ] = negar_theme_sanitize_media_link( isset( $input[ $field ] ) ? $input[ $field ] : '' );
			} else {
				$clean['records'][ $record_id ][ $field ] = isset( $input[ $field ] ) && is_scalar( $input[ $field ] ) ? sanitize_text_field( $input[ $field ] ) : '';
			}
		}
	}

	add_settings_error( 'negar_theme_media_settings', 'negar_theme_media_saved', __( 'تنظیمات رسانه با موفقیت ذخیره شد.', 'negar-shop' ), 'updated' );
	return $clean;
}

/**
 * Return the fixed product-pack/hotspot assignment registry.
 *
 * @return array<string, array<string, mixed>>
 */
function negar_theme_product_pack_registry() {
	return array(
		'gaming-zone'      => array(
			'label' => __( 'پک گیمینگ و نقاط داغ', 'negar-shop' ),
			'slots' => array(
				'shadows' => __( 'نقطهٔ داغ ۱', 'negar-shop' ),
				'mirage'  => __( 'نقطهٔ داغ ۲', 'negar-shop' ),
				'fc'      => __( 'نقطهٔ داغ ۳', 'negar-shop' ),
				'yotei'   => __( 'نقطهٔ داغ ۴', 'negar-shop' ),
			),
		),
		'taste-grid'       => array(
			'label' => __( 'باکس سلیقه شما (چهار دسته)', 'negar-shop' ),
			'slots' => array(
				'box-1-1' => __( 'کالای دیجیتال - محصول ۱', 'negar-shop' ),
				'box-1-2' => __( 'کالای دیجیتال - محصول ۲', 'negar-shop' ),
				'box-1-3' => __( 'کالای دیجیتال - محصول ۳', 'negar-shop' ),
				'box-1-4' => __( 'کالای دیجیتال - محصول ۴', 'negar-shop' ),
				'box-2-1' => __( 'موبایل - محصول ۱', 'negar-shop' ),
				'box-2-2' => __( 'موبایل - محصول ۲', 'negar-shop' ),
				'box-2-3' => __( 'موبایل - محصول ۳', 'negar-shop' ),
				'box-2-4' => __( 'موبایل - محصول ۴', 'negar-shop' ),
				'box-3-1' => __( 'بازی‌های کنسول - محصول ۱', 'negar-shop' ),
				'box-3-2' => __( 'بازی‌های کنسول - محصول ۲', 'negar-shop' ),
				'box-3-3' => __( 'بازی‌های کنسول - محصول ۳', 'negar-shop' ),
				'box-3-4' => __( 'بازی‌های کنسول - محصول ۴', 'negar-shop' ),
				'box-4-1' => __( 'گوشی موبایل - محصول ۱', 'negar-shop' ),
				'box-4-2' => __( 'گوشی موبایل - محصول ۲', 'negar-shop' ),
				'box-4-3' => __( 'گوشی موبایل - محصول ۳', 'negar-shop' ),
				'box-4-4' => __( 'گوشی موبایل - محصول ۴', 'negar-shop' ),
			),
		),
		'feature-bar-1'    => array(
			'label' => __( 'نوار برترین‌ها ۱ (ساعت و گجت)', 'negar-shop' ),
			'slots' => array(
				'slot-1' => __( 'کالای ۱', 'negar-shop' ),
				'slot-2' => __( 'کالای ۲', 'negar-shop' ),
				'slot-3' => __( 'کالای ۳', 'negar-shop' ),
				'slot-4' => __( 'کالای ۴', 'negar-shop' ),
			),
		),
		'feature-bar-2'    => array(
			'label' => __( 'نوار برترین‌ها ۲ (کنسول و بازی)', 'negar-shop' ),
			'slots' => array(
				'slot-1' => __( 'کالای ۱', 'negar-shop' ),
				'slot-2' => __( 'کالای ۲', 'negar-shop' ),
				'slot-3' => __( 'کالای ۳', 'negar-shop' ),
				'slot-4' => __( 'کالای ۴', 'negar-shop' ),
			),
		),
		'smartwatches-2'   => array(
			'label' => __( 'ویترین ساعت‌های پرفروش و گجت پوشیدنی', 'negar-shop' ),
			'slots' => array(
				'promo-1'    => __( 'کارت تبلیغاتی سرخ ۱', 'negar-shop' ),
				'promo-2'    => __( 'کارت تبلیغاتی سرخ ۲', 'negar-shop' ),
				'carousel-1' => __( 'اسلایدر کالا ۱', 'negar-shop' ),
				'carousel-2' => __( 'اسلایدر کالا ۲', 'negar-shop' ),
				'carousel-3' => __( 'اسلایدر کالا ۳', 'negar-shop' ),
				'carousel-4' => __( 'اسلایدر کالا ۴', 'negar-shop' ),
				'carousel-5' => __( 'اسلایدر کالا ۵', 'negar-shop' ),
			),
		),
		'mobile-spotlight' => array(
			'label' => __( 'ویترین کانون موبایل (محصول ویژه)', 'negar-shop' ),
			'slots' => array(
				'focal' => __( 'محصول کانونی ویژه', 'negar-shop' ),
			),
		),
		'feature-bar-3'    => array(
			'label' => __( 'نوار برترین‌ها ۳ (بازی و سرگرمی)', 'negar-shop' ),
			'slots' => array(
				'slot-1' => __( 'کالای ۱', 'negar-shop' ),
				'slot-2' => __( 'کالای ۲', 'negar-shop' ),
				'slot-3' => __( 'کالای ۳', 'negar-shop' ),
				'slot-4' => __( 'کالای ۴', 'negar-shop' ),
			),
		),
	);
}

/**
 * Return an explicitly assigned visible product ID, or zero for legacy fallback.
 *
 * @param string $pack_id Fixed pack ID.
 * @param string $slot_id Fixed slot ID.
 * @return int
 */
function negar_theme_product_pack_product_id( $pack_id, $slot_id ) {
	$stored = get_option( NEGAR_THEME_PRODUCT_PACK_OPTION, array() );
	$id     = is_array( $stored ) && isset( $stored['packs'][ $pack_id ][ $slot_id ] ) ? absint( $stored['packs'][ $pack_id ][ $slot_id ] ) : 0;
	return in_array( $id, negar_theme_sanitize_product_ids( array( $id ) ), true ) ? $id : 0;
}

/**
 * Sanitize fixed product-pack product IDs without accepting arbitrary slots.
 *
 * @param mixed $submitted Raw Settings API value.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_product_pack_settings( $submitted ) {
	$current = get_option( NEGAR_THEME_PRODUCT_PACK_OPTION, array() );
	$current = is_array( $current ) ? $current : array();
	if ( ! current_user_can( 'manage_options' ) ) {
		return $current;
	}
	$submitted = is_array( $submitted ) ? wp_unslash( $submitted ) : array();
	$packs     = isset( $submitted['packs'] ) && is_array( $submitted['packs'] ) ? $submitted['packs'] : array();
	$clean     = array( 'schema_version' => 1, 'packs' => array() );
	foreach ( negar_theme_product_pack_registry() as $pack_id => $pack ) {
		$pack_is_submitted = array_key_exists( $pack_id, $packs ) && is_array( $packs[ $pack_id ] );
		foreach ( $pack['slots'] as $slot_id => $label ) {
			if ( $pack_is_submitted ) {
				$value = isset( $packs[ $pack_id ][ $slot_id ] ) && is_scalar( $packs[ $pack_id ][ $slot_id ] ) ? absint( $packs[ $pack_id ][ $slot_id ] ) : 0;
				$clean['packs'][ $pack_id ][ $slot_id ] = in_array( $value, negar_theme_sanitize_product_ids( array( $value ) ), true ) ? $value : 0;
			} else {
				$clean['packs'][ $pack_id ][ $slot_id ] = isset( $current['packs'][ $pack_id ][ $slot_id ] ) ? absint( $current['packs'][ $pack_id ][ $slot_id ] ) : 0;
			}
		}
	}
	return $clean;
}

/**
 * Find the latest published, catalog-visible WooCommerce product matching any given title needle.
 *
 * Uses a single bounded query with catalog-visibility filtering, avoiding
 * unbounded catalog iteration. Results are memoized per request.
 *
 * @param array<int, string> $needles       Substrings to search in product titles.
 * @param bool               $force_refresh Optional. Reset the request-level memo cache.
 * @return WC_Product|false
 */
function negar_theme_find_product_by_title_needles( $needles, $force_refresh = false ) {
	static $memo = array();

	if ( $force_refresh ) {
		$memo = array();
	}

	$clean_needles = array();
	foreach ( (array) $needles as $needle ) {
		$trimmed = trim( (string) $needle );
		if ( '' !== $trimmed ) {
			$clean_needles[] = $trimmed;
		}
	}
	if ( empty( $clean_needles ) || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$cache_key = md5( wp_json_encode( $clean_needles ) );
	if ( array_key_exists( $cache_key, $memo ) ) {
		return $memo[ $cache_key ];
	}

	global $wpdb;
	$where_filter = static function ( $where ) use ( $clean_needles, $wpdb ) {
		$likes = array();
		foreach ( $clean_needles as $needle ) {
			$likes[] = $wpdb->prepare(
				"{$wpdb->posts}.post_title LIKE %s",
				'%' . $wpdb->esc_like( $needle ) . '%'
			);
		}
		if ( ! empty( $likes ) ) {
			$where .= ' AND (' . implode( ' OR ', $likes ) . ')';
		}
		return $where;
	};

	add_filter( 'posts_where', $where_filter );
	$query_args = negar_theme_catalog_visibility_query_args(
		array(
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'no_found_rows'    => true,
			'suppress_filters' => false,
		)
	);
	try {
		$product_ids = get_posts( $query_args );
	} finally {
		remove_filter( 'posts_where', $where_filter );
	}

	$matched_product = false;
	if ( ! empty( $product_ids ) ) {
		$product = wc_get_product( absint( $product_ids[0] ) );
		if ( $product && $product->is_visible() ) {
			$matched_product = $product;
		}
	}

	$memo[ $cache_key ] = $matched_product;
	return $matched_product;
}

/**
 * Resolve a product pack product with manual selection priority and bounded fallback.
 *
 * 1. Checks manual assignment via `negar_theme_product_pack_product_id( $pack_id, $slot_id )`.
 * 2. If valid and catalog-visible, returns that product immediately (zero fallback search).
 * 3. Otherwise, searches for a published, catalog-visible product using the provided needles.
 *
 * @param string             $pack_id Pack identifier.
 * @param string             $slot_id Slot identifier.
 * @param array<int, string> $needles Fallback search title fragments.
 * @return WC_Product|false
 */
function negar_theme_product_pack_product( $pack_id, $slot_id, $needles = array() ) {
	$selected_id = negar_theme_product_pack_product_id( $pack_id, $slot_id );
	if ( $selected_id && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $selected_id );
		if ( $product && 'publish' === get_post_status( $product->get_id() ) && $product->is_visible() ) {
			return $product;
		}
	}

	return ! empty( $needles ) ? negar_theme_find_product_by_title_needles( $needles ) : false;
}

/**
 * Resolve a Gaming Zone hotspot product with manual selection priority and bounded fallback.
 *
 * @param string             $slot_key Hotspot key ('shadows', 'mirage', 'fc', 'yotei').
 * @param array<int, string> $needles  Fallback search title fragments.
 * @return WC_Product|false
 */
function negar_theme_gaming_zone_product( $slot_key, $needles = array() ) {
	return negar_theme_product_pack_product( 'gaming-zone', $slot_key, $needles );
}

/**
 * Return default demonstration stories for backward compatibility.
 *
 * @return array<int, array<string, mixed>>
 */
function negar_theme_story_defaults() {
	$upload_dir = wp_upload_dir();
	$story_base = $upload_dir['baseurl'] . '/kaveh-demo/stories';

	return array(
		array(
			'id'           => 'story-demo-1',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'پیشنهاد داغ',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-1.jpg',
		),
		array(
			'id'           => 'story-demo-2',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'شروعی متفاوت',
			'badge'        => 'ویژه ما',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-2.jpg',
		),
		array(
			'id'           => 'story-demo-3',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'ویژه تو',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-3.jpg',
		),
		array(
			'id'           => 'story-demo-4',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'شانس با توست',
			'badge'        => 'محصول ویژه',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-4.jpg',
		),
		array(
			'id'           => 'story-demo-5',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'تخفیف رویایی',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-5.jpg',
		),
		array(
			'id'           => 'story-demo-6',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'تکرار نمیشه',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-6.jpg',
		),
		array(
			'id'           => 'story-demo-7',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'باورنکردنی',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-7.jpg',
		),
		array(
			'id'           => 'story-demo-8',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'انتخاب خاص',
			'badge'        => '',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-8.jpg',
		),
		array(
			'id'           => 'story-demo-9',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'یک خرید خاص',
			'badge'        => 'ویژه',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-9.jpg',
		),
		array(
			'id'           => 'story-demo-10',
			'enabled'      => true,
			'type'         => 'image',
			'title'        => 'برای تو',
			'badge'        => 'تخفیف ويژه',
			'thumbnail_id' => 0,
			'image_id'     => 0,
			'video_id'     => 0,
			'poster_id'    => 0,
			'duration'     => 5,
			'product_id'   => 0,
			'cta_label'    => '',
			'cta_url'      => '',
			'fallback_img' => $story_base . '/story-demo-10.jpg',
		),
	);
}

/**
 * Return raw stored story options without defaults.
 *
 * @return mixed
 */
function negar_theme_story_options_raw() {
	return get_option( NEGAR_THEME_STORY_OPTION, null );
}

/**
 * Return resolved stories for storefront rendering.
 *
 * Falls back to demo defaults when the option has never been saved. Returns an
 * empty array when explicitly configured with zero stories.
 *
 * @param bool $only_enabled Whether to include only enabled stories.
 * @return array<int, array<string, mixed>>
 */
function negar_theme_get_stories( $only_enabled = true ) {
	$option = get_option( NEGAR_THEME_STORY_OPTION, null );
	if ( null === $option ) {
		$raw_stories = negar_theme_story_defaults();
	} else {
		$raw_stories = isset( $option['stories'] ) && is_array( $option['stories'] ) ? $option['stories'] : array();
	}

	$resolved = array();
	foreach ( $raw_stories as $s ) {
		if ( ! is_array( $s ) ) {
			continue;
		}

		$enabled = isset( $s['enabled'] ) && ! in_array( $s['enabled'], array( false, 0, '0', 'false' ), true );
		if ( $only_enabled && ! $enabled ) {
			continue;
		}

		$id        = isset( $s['id'] ) && is_scalar( $s['id'] ) ? sanitize_key( $s['id'] ) : uniqid( 'story_' );
		$type      = isset( $s['type'] ) && 'video' === $s['type'] ? 'video' : 'image';
		$title     = isset( $s['title'] ) && is_scalar( $s['title'] ) ? sanitize_text_field( $s['title'] ) : '';
		$badge     = isset( $s['badge'] ) && is_scalar( $s['badge'] ) ? sanitize_text_field( $s['badge'] ) : '';
		$duration  = isset( $s['duration'] ) && is_scalar( $s['duration'] ) ? max( 3, min( 30, absint( $s['duration'] ) ) ) : 5;
		$cta_label = isset( $s['cta_label'] ) && is_scalar( $s['cta_label'] ) && '' !== trim( (string) $s['cta_label'] )
			? sanitize_text_field( $s['cta_label'] )
			: __( 'مشاهده و خرید', 'negar-shop' );
		$cta_url   = isset( $s['cta_url'] ) && is_scalar( $s['cta_url'] ) ? negar_theme_sanitize_media_link( $s['cta_url'] ) : '';

		// Media IDs.
		$thumb_id  = isset( $s['thumbnail_id'] ) && is_scalar( $s['thumbnail_id'] ) ? absint( $s['thumbnail_id'] ) : 0;
		$image_id  = isset( $s['image_id'] ) && is_scalar( $s['image_id'] ) ? absint( $s['image_id'] ) : 0;
		$video_id  = isset( $s['video_id'] ) && is_scalar( $s['video_id'] ) ? absint( $s['video_id'] ) : 0;
		$poster_id = isset( $s['poster_id'] ) && is_scalar( $s['poster_id'] ) ? absint( $s['poster_id'] ) : 0;

		$fallback_img = isset( $s['fallback_img'] ) && is_string( $s['fallback_img'] ) ? $s['fallback_img'] : '';

		// Resolve thumbnail URL.
		$thumb_url = '';
		if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
			$thumb_url = (string) wp_get_attachment_image_url( $thumb_id, 'thumbnail' );
		}
		if ( '' === $thumb_url && $image_id && wp_attachment_is_image( $image_id ) ) {
			$thumb_url = (string) wp_get_attachment_image_url( $image_id, 'thumbnail' );
		}
		if ( '' === $thumb_url && $poster_id && wp_attachment_is_image( $poster_id ) ) {
			$thumb_url = (string) wp_get_attachment_image_url( $poster_id, 'thumbnail' );
		}
		if ( '' === $thumb_url && '' !== $fallback_img ) {
			$thumb_url = $fallback_img;
		}

		// Resolve image URL for image stories.
		$image_url = '';
		if ( $image_id && wp_attachment_is_image( $image_id ) ) {
			$image_url = (string) wp_get_attachment_image_url( $image_id, 'full' );
		}
		if ( '' === $image_url && '' !== $fallback_img ) {
			$image_url = $fallback_img;
		}

		// Resolve video URL for video stories.
		$video_url = '';
		if ( $video_id ) {
			$mime = (string) get_post_mime_type( $video_id );
			if ( str_starts_with( $mime, 'video/' ) ) {
				$video_url = (string) wp_get_attachment_url( $video_id );
			}
		}

		// Resolve poster URL.
		$poster_url = '';
		if ( $poster_id && wp_attachment_is_image( $poster_id ) ) {
			$poster_url = (string) wp_get_attachment_image_url( $poster_id, 'full' );
		}

		// Resolve Product & CTA.
		$product_id    = isset( $s['product_id'] ) && is_scalar( $s['product_id'] ) ? absint( $s['product_id'] ) : 0;
		$product       = $product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
		$has_product   = false;
		$product_title = '';
		$product_link  = '';
		$product_price = '';

		// Priority 1: Valid published, catalog-visible WooCommerce product.
		if ( $product && $product->is_visible() ) {
			$has_product   = true;
			$product_title = $product->get_name();
			$product_link  = $product->get_permalink();
			$product_price = $product->get_price_html();
		} elseif ( '' !== $cta_url ) {
			// Priority 2: Safe custom CTA URL (editorial story, never invent a product name).
			$has_product   = false;
			$product_title = '';
			$product_link  = $cta_url;
			$product_price = '';
		} else {
			// Priority 3: No product and no custom CTA link.
			$has_product   = false;
			$product_title = '';
			$product_link  = '';
			$product_price = '';
		}

		// STEP 3: Reject or safely omit unusable story media from public storefront collection.
		if ( $only_enabled ) {
			if ( 'video' === $type && '' === $video_url ) {
				continue;
			}
			if ( 'image' === $type && '' === $image_url ) {
				continue;
			}
			if ( '' === $thumb_url ) {
				continue;
			}
		}

		$resolved[] = array(
			'id'            => $id,
			'enabled'       => $enabled,
			'type'          => $type,
			'title'         => $title,
			'badge'         => $badge,
			'duration'      => $duration,
			'cta_label'     => $cta_label,
			'cta_url'       => $cta_url,
			'thumbnail_id'  => $thumb_id,
			'thumbnail_url' => $thumb_url,
			'image_id'      => $image_id,
			'image_url'     => $image_url,
			'video_id'      => $video_id,
			'video_url'     => $video_url,
			'poster_id'     => $poster_id,
			'poster_url'    => $poster_url,
			'has_product'   => $has_product,
			'product_id'    => $has_product && $product ? $product->get_id() : 0,
			'product_title' => $product_title,
			'product_link'  => $product_link,
			'product_price' => $product_price,
		);
	}

	return $resolved;
}

/**
 * Sanitize submitted story collection.
 *
 * @param mixed $submitted Raw Settings API value.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_story_settings( $submitted ) {
	$current = get_option( NEGAR_THEME_STORY_OPTION, array() );
	$current = is_array( $current ) ? $current : array();

	if ( ! current_user_can( 'manage_options' ) ) {
		add_settings_error( 'negar_theme_story_settings', 'negar_theme_story_forbidden', __( 'اجازهٔ ذخیرهٔ استوری‌ها را ندارید.', 'negar-shop' ), 'error' );
		return $current;
	}

	$submitted   = is_array( $submitted ) ? wp_unslash( $submitted ) : array();
	$raw_stories = isset( $submitted['stories'] ) && is_array( $submitted['stories'] ) ? array_values( $submitted['stories'] ) : array();
	$raw_stories = array_slice( $raw_stories, 0, 30 );

	$seen_ids      = array();
	$clean_stories = array();
	foreach ( $raw_stories as $idx => $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$id = isset( $item['id'] ) && is_scalar( $item['id'] ) && '' !== trim( (string) $item['id'] )
			? sanitize_key( $item['id'] )
			: '';
		if ( '' === $id || in_array( $id, $seen_ids, true ) ) {
			$id = uniqid( 'story_' );
		}
		$seen_ids[] = $id;

		$enabled = isset( $item['enabled'] ) && ! in_array( $item['enabled'], array( false, 0, '0', 'false' ), true );
		$type    = isset( $item['type'] ) && 'video' === $item['type'] ? 'video' : 'image';
		$title   = isset( $item['title'] ) && is_scalar( $item['title'] ) ? mb_substr( sanitize_text_field( $item['title'] ), 0, 100 ) : '';
		$badge   = isset( $item['badge'] ) && is_scalar( $item['badge'] ) ? mb_substr( sanitize_text_field( $item['badge'] ), 0, 40 ) : '';

		// Media attachment validation.
		$thumb_id = isset( $item['thumbnail_id'] ) && is_scalar( $item['thumbnail_id'] ) ? absint( $item['thumbnail_id'] ) : 0;
		$thumb_id = ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) ? $thumb_id : 0;

		$image_id = isset( $item['image_id'] ) && is_scalar( $item['image_id'] ) ? absint( $item['image_id'] ) : 0;
		$image_id = ( $image_id && wp_attachment_is_image( $image_id ) ) ? $image_id : 0;

		$video_id = isset( $item['video_id'] ) && is_scalar( $item['video_id'] ) ? absint( $item['video_id'] ) : 0;
		if ( $video_id ) {
			$mime = (string) get_post_mime_type( $video_id );
			if ( ! str_starts_with( $mime, 'video/' ) ) {
				$video_id = 0;
			}
		}

		$poster_id = isset( $item['poster_id'] ) && is_scalar( $item['poster_id'] ) ? absint( $item['poster_id'] ) : 0;
		$poster_id = ( $poster_id && wp_attachment_is_image( $poster_id ) ) ? $poster_id : 0;

		$duration = isset( $item['duration'] ) && is_scalar( $item['duration'] ) ? absint( $item['duration'] ) : 5;
		$duration = max( 3, min( 30, $duration ? $duration : 5 ) );

		// Product validation against published catalog-visible items.
		$prod_id = isset( $item['product_id'] ) && is_scalar( $item['product_id'] ) ? absint( $item['product_id'] ) : 0;
		$prod_id = in_array( $prod_id, negar_theme_sanitize_product_ids( array( $prod_id ) ), true ) ? $prod_id : 0;

		$cta_label = isset( $item['cta_label'] ) && is_scalar( $item['cta_label'] ) ? mb_substr( sanitize_text_field( $item['cta_label'] ), 0, 50 ) : '';
		$cta_url   = isset( $item['cta_url'] ) && is_scalar( $item['cta_url'] ) ? negar_theme_sanitize_media_link( $item['cta_url'] ) : '';

		// Inform administrator of missing or incomplete media on enabled stories.
		if ( $enabled ) {
			if ( 'video' === $type && 0 === $video_id ) {
				add_settings_error(
					'negar_theme_story_settings',
					'negar_story_missing_video_' . $idx,
					sprintf(
						/* translators: %s: story title or index */
						__( 'استوری «%s» از نوع ویدیو است اما فایل ویدیویی معتبری برای آن انتخاب نشده است. این استوری تا زمان انتخاب ویدیو در سایت نمایش داده نخواهد شد.', 'negar-shop' ),
						esc_html( '' !== $title ? $title : '#' . ( $idx + 1 ) )
					),
					'warning'
				);
			} elseif ( 'image' === $type && 0 === $image_id ) {
				add_settings_error(
					'negar_theme_story_settings',
					'negar_story_missing_image_' . $idx,
					sprintf(
						/* translators: %s: story title or index */
						__( 'استوری «%s» فاقد تصویر تمام‌صفحه است و تا زمان انتخاب تصویر در سایت نمایش داده نخواهد شد.', 'negar-shop' ),
						esc_html( '' !== $title ? $title : '#' . ( $idx + 1 ) )
					),
					'warning'
				);
			}
		}

		$clean_stories[] = array(
			'id'           => $id,
			'enabled'      => $enabled,
			'type'         => $type,
			'title'        => $title,
			'badge'        => $badge,
			'thumbnail_id' => $thumb_id,
			'image_id'     => $image_id,
			'video_id'     => $video_id,
			'poster_id'    => $poster_id,
			'duration'     => $duration,
			'product_id'   => $prod_id,
			'cta_label'    => $cta_label,
			'cta_url'      => $cta_url,
		);
	}

	add_settings_error( 'negar_theme_story_settings', 'negar_theme_story_saved', __( 'تنظیمات استوری‌ها با موفقیت ذخیره شد.', 'negar-shop' ), 'updated' );

	return array(
		'schema_version' => NEGAR_THEME_STORY_VERSION,
		'stories'        => $clean_stories,
	);
}

/**
 * Render one story card in the admin manager (or as a template for JS cloning).
 *
 * @param string|int           $index       Card identifier or __INDEX__ placeholder.
 * @param array<string, mixed> $story       Story data.
 * @param bool                 $is_template Whether this is the JS clone template.
 * @return void
 */
function negar_theme_render_single_story_card( $index, $story = array(), $is_template = false ) {
	$story     = is_array( $story ) ? $story : array();
	$id        = isset( $story['id'] ) && is_scalar( $story['id'] ) && '' !== trim( (string) $story['id'] )
		? (string) $story['id']
		: ( $is_template ? '__INDEX__' : uniqid( 'story_' ) );
	$title     = isset( $story['title'] ) && is_scalar( $story['title'] ) ? (string) $story['title'] : '';
	$badge     = isset( $story['badge'] ) && is_scalar( $story['badge'] ) ? (string) $story['badge'] : '';
	$type      = isset( $story['type'] ) && 'video' === $story['type'] ? 'video' : 'image';
	$enabled   = ! isset( $story['enabled'] ) || ! in_array( $story['enabled'], array( false, 0, '0', 'false' ), true );
	$duration  = isset( $story['duration'] ) && is_scalar( $story['duration'] ) ? max( 3, min( 30, absint( $story['duration'] ) ) ) : 5;
	$cta_label = isset( $story['cta_label'] ) && is_scalar( $story['cta_label'] ) ? (string) $story['cta_label'] : '';
	$cta_url   = isset( $story['cta_url'] ) && is_scalar( $story['cta_url'] ) ? (string) $story['cta_url'] : '';

	$thumb_id   = isset( $story['thumbnail_id'] ) && is_scalar( $story['thumbnail_id'] ) ? absint( $story['thumbnail_id'] ) : 0;
	$image_id   = isset( $story['image_id'] ) && is_scalar( $story['image_id'] ) ? absint( $story['image_id'] ) : 0;
	$video_id   = isset( $story['video_id'] ) && is_scalar( $story['video_id'] ) ? absint( $story['video_id'] ) : 0;
	$poster_id  = isset( $story['poster_id'] ) && is_scalar( $story['poster_id'] ) ? absint( $story['poster_id'] ) : 0;
	$product_id = isset( $story['product_id'] ) && is_scalar( $story['product_id'] ) ? absint( $story['product_id'] ) : 0;

	$fallback_img = isset( $story['fallback_img'] ) && is_string( $story['fallback_img'] ) ? $story['fallback_img'] : '';

	// Previews
	$thumb_preview = '';
	if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
		$thumb_preview = (string) wp_get_attachment_image_url( $thumb_id, 'thumbnail' );
	} elseif ( '' !== $fallback_img ) {
		$thumb_preview = $fallback_img;
	}

	$image_preview = '';
	if ( $image_id && wp_attachment_is_image( $image_id ) ) {
		$image_preview = (string) wp_get_attachment_image_url( $image_id, 'thumbnail' );
	} elseif ( '' !== $fallback_img ) {
		$image_preview = $fallback_img;
	}

	$poster_preview = '';
	if ( $poster_id && wp_attachment_is_image( $poster_id ) ) {
		$poster_preview = (string) wp_get_attachment_image_url( $poster_id, 'thumbnail' );
	}

	$video_filename = '';
	if ( $video_id ) {
		$attached = get_attached_file( $video_id );
		$video_filename = $attached ? basename( $attached ) : sprintf( '#%d', $video_id );
	}

	$product = $product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
	$field_prefix = NEGAR_THEME_STORY_OPTION . '[stories][' . $index . ']';
	?>
	<details class="negar-story-card" data-story-index="<?php echo esc_attr( (string) $index ); ?>">
		<summary class="negar-story-summary">
			<span class="negar-layout-item__handle" aria-hidden="true">☷</span>
			<span class="negar-story-title-preview"><?php echo esc_html( '' !== $title ? $title : __( 'استوری بدون عنوان', 'negar-shop' ) ); ?></span>
			<span class="negar-story-type-badge <?php echo 'video' === $type ? 'negar-badge-video' : 'negar-badge-image'; ?>">
				<?php echo 'video' === $type ? esc_html__( 'ویدیو', 'negar-shop' ) : esc_html__( 'تصویر', 'negar-shop' ); ?>
			</span>
			<label class="negar-story-enabled-label">
				<input type="checkbox" name="<?php echo esc_attr( $field_prefix ); ?>[enabled]" value="1" <?php checked( $enabled ); ?>>
				<span><?php esc_html_e( 'نمایش', 'negar-shop' ); ?></span>
			</label>
			<div class="negar-story-actions">
				<button type="button" class="button-link negar-story-move-up" aria-label="<?php esc_attr_e( 'انتقال به بالا', 'negar-shop' ); ?>"><?php esc_html_e( 'بالا', 'negar-shop' ); ?></button>
				<button type="button" class="button-link negar-story-move-down" aria-label="<?php esc_attr_e( 'انتقال به پایین', 'negar-shop' ); ?>"><?php esc_html_e( 'پایین', 'negar-shop' ); ?></button>
				<button type="button" class="button-link-delete negar-story-delete" aria-label="<?php esc_attr_e( 'حذف استوری', 'negar-shop' ); ?>"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></button>
			</div>
		</summary>
		<div class="negar-story-fields">
			<input type="hidden" class="negar-story-id" name="<?php echo esc_attr( $field_prefix ); ?>[id]" value="<?php echo esc_attr( $id ); ?>">

			<?php if ( ! $is_template && $enabled ) : ?>
				<?php if ( 'video' === $type && ! $video_id ) : ?>
					<p class="negar-story-media-warning description" style="color: #b32d2e; font-weight: 500; margin: 8px 0; padding: 6px 10px; background: #fcf0f1; border-right: 3px solid #d63638;">
						⚠ <?php esc_html_e( 'فایل ویدیویی انتخاب نشده است. این استوری تا زمان انتخاب ویدیو در سایت نمایش داده نمی‌شود.', 'negar-shop' ); ?>
					</p>
				<?php elseif ( 'image' === $type && ! $image_id && empty( $fallback_img ) ) : ?>
					<p class="negar-story-media-warning description" style="color: #b32d2e; font-weight: 500; margin: 8px 0; padding: 6px 10px; background: #fcf0f1; border-right: 3px solid #d63638;">
						⚠ <?php esc_html_e( 'تصویر تمام‌صفحه انتخاب نشده است. این استوری تا زمان انتخاب تصویر در سایت نمایش داده نمی‌شود.', 'negar-shop' ); ?>
					</p>
				<?php endif; ?>
			<?php endif; ?>

			<div class="negar-story-grid">
				<p>
					<label for="negar-story-title-<?php echo esc_attr( (string) $index ); ?>"><strong><?php esc_html_e( 'عنوان استوری (ضروری)', 'negar-shop' ); ?></strong></label><br>
					<input id="negar-story-title-<?php echo esc_attr( (string) $index ); ?>" type="text" class="regular-text negar-story-title-input" name="<?php echo esc_attr( $field_prefix ); ?>[title]" value="<?php echo esc_attr( $title ); ?>" required>
				</p>
				<p>
					<label for="negar-story-badge-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'متن نشان (اختیاری، مثلاً «ویژه» یا «تخفیف»)', 'negar-shop' ); ?></label><br>
					<input id="negar-story-badge-<?php echo esc_attr( (string) $index ); ?>" type="text" class="regular-text" name="<?php echo esc_attr( $field_prefix ); ?>[badge]" value="<?php echo esc_attr( $badge ); ?>">
				</p>
			</div>

			<div class="negar-media-control" data-media-control style="margin: 12px 0;">
				<label><strong><?php esc_html_e( 'تصویر شاخص / دایرهٔ استوری (آیکن روی صفحهٔ اصلی)', 'negar-shop' ); ?></strong></label><br>
				<input type="number" min="0" step="1" class="small-text negar-media-control__id" name="<?php echo esc_attr( $field_prefix ); ?>[thumbnail_id]" value="<?php echo esc_attr( (string) $thumb_id ); ?>">
				<img class="negar-media-control__preview" src="<?php echo esc_url( $thumb_preview ); ?>" data-fallback-url="<?php echo esc_url( $fallback_img ); ?>" width="70" height="70" alt="" style="width:70px;height:70px;border-radius:50%;object-fit:cover;vertical-align:middle;margin:0 8px;">
				<button type="button" class="button button-secondary negar-media-control__select"><?php esc_html_e( 'انتخاب تصویر', 'negar-shop' ); ?></button>
				<button type="button" class="button-link-delete negar-media-control__remove"><?php esc_html_e( 'حذف تصویر', 'negar-shop' ); ?></button>
				<p class="description"><?php esc_html_e( 'در صورت خالی بودن، از تصویر اصلی استوری یا تصویر پیش‌فرض دمو استفاده می‌شود.', 'negar-shop' ); ?></p>
			</div>

			<p>
				<label><strong><?php esc_html_e( 'نوع محتوای استوری', 'negar-shop' ); ?></strong></label><br>
				<select class="negar-story-media-type-select" name="<?php echo esc_attr( $field_prefix ); ?>[type]">
					<option value="image" <?php selected( 'image' === $type ); ?>><?php esc_html_e( 'تصویر (Image)', 'negar-shop' ); ?></option>
					<option value="video" <?php selected( 'video' === $type ); ?>><?php esc_html_e( 'ویدیو (Video)', 'negar-shop' ); ?></option>
				</select>
			</p>

			<div class="negar-story-image-fields" <?php echo 'video' === $type ? 'style="display:none;"' : ''; ?>>
				<div class="negar-media-control" data-media-control style="margin: 12px 0;">
					<label><strong><?php esc_html_e( 'تصویر تمام‌صفحهٔ استوری', 'negar-shop' ); ?></strong></label><br>
					<input type="number" min="0" step="1" class="small-text negar-media-control__id" name="<?php echo esc_attr( $field_prefix ); ?>[image_id]" value="<?php echo esc_attr( (string) $image_id ); ?>">
					<img class="negar-media-control__preview" src="<?php echo esc_url( $image_preview ); ?>" data-fallback-url="<?php echo esc_url( $fallback_img ); ?>" width="80" height="120" alt="" style="width:80px;height:120px;border-radius:8px;object-fit:cover;vertical-align:middle;margin:0 8px;">
					<button type="button" class="button button-secondary negar-media-control__select"><?php esc_html_e( 'انتخاب تصویر', 'negar-shop' ); ?></button>
					<button type="button" class="button-link-delete negar-media-control__remove"><?php esc_html_e( 'حذف تصویر', 'negar-shop' ); ?></button>
				</div>
				<p>
					<label><?php esc_html_e( 'مدت‌زمان نمایش تصویر (ثانیه، بین ۳ تا ۳۰)', 'negar-shop' ); ?></label><br>
					<input type="number" min="3" max="30" class="small-text" name="<?php echo esc_attr( $field_prefix ); ?>[duration]" value="<?php echo esc_attr( (string) $duration ); ?>">
				</p>
			</div>

			<div class="negar-story-video-fields" <?php echo 'video' !== $type ? 'style="display:none;"' : ''; ?>>
				<div class="negar-video-control" data-video-control style="margin: 12px 0; padding: 10px; background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px;">
					<label><strong><?php esc_html_e( 'فایل ویدیوی استوری (از کتابخانهٔ رسانه)', 'negar-shop' ); ?></strong></label><br>
					<input type="hidden" class="negar-video-control__id" name="<?php echo esc_attr( $field_prefix ); ?>[video_id]" value="<?php echo esc_attr( (string) $video_id ); ?>">
					<button type="button" class="button button-secondary negar-video-control__select"><?php esc_html_e( 'انتخاب فایل ویدیو', 'negar-shop' ); ?></button>
					<span class="negar-video-control__filename" style="margin: 0 8px; font-family: monospace; font-weight: bold;"><?php echo esc_html( $video_filename ); ?></span>
					<button type="button" class="button-link-delete negar-video-control__remove" <?php echo $video_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'حذف ویدیو', 'negar-shop' ); ?></button>
					<p class="description"><?php esc_html_e( 'مدت‌زمان پیشرفت استوری ویدیو به‌طور خودکار و دقیقاً برابر با طول فایل ویدیویی خواهد بود.', 'negar-shop' ); ?></p>
				</div>
				<div class="negar-media-control" data-media-control style="margin: 12px 0;">
					<label><?php esc_html_e( 'تصویر پوستر ویدیو (اختیاری)', 'negar-shop' ); ?></label><br>
					<input type="number" min="0" step="1" class="small-text negar-media-control__id" name="<?php echo esc_attr( $field_prefix ); ?>[poster_id]" value="<?php echo esc_attr( (string) $poster_id ); ?>">
					<img class="negar-media-control__preview" src="<?php echo esc_url( $poster_preview ); ?>" data-fallback-url="" width="80" height="120" alt="" style="width:80px;height:120px;border-radius:8px;object-fit:cover;vertical-align:middle;margin:0 8px;">
					<button type="button" class="button button-secondary negar-media-control__select"><?php esc_html_e( 'انتخاب پوستر', 'negar-shop' ); ?></button>
					<button type="button" class="button-link-delete negar-media-control__remove"><?php esc_html_e( 'حذف پوستر', 'negar-shop' ); ?></button>
				</div>
			</div>

			<div class="negar-product-section__manual" data-product-selector data-single="1" data-input-name="<?php echo esc_attr( $field_prefix ); ?>[product_id]" style="margin: 14px 0;">
				<input type="hidden" name="<?php echo esc_attr( $field_prefix ); ?>[product_id]" value="<?php echo esc_attr( (string) ( $product ? $product->get_id() : 0 ) ); ?>">
				<label><strong><?php esc_html_e( 'محصول مرتبط WooCommerce (اختیاری)', 'negar-shop' ); ?></strong></label><br>
				<input class="regular-text" type="search" autocomplete="off" placeholder="<?php esc_attr_e( 'نام یا شناسهٔ محصول را جست‌وجو کنید', 'negar-shop' ); ?>">
				<p class="description negar-product-selector__status" aria-live="polite"></p>
				<div class="negar-product-selector__results" role="listbox"></div>
				<ul class="negar-product-selector__selected" aria-label="<?php esc_attr_e( 'محصول انتخاب‌شده', 'negar-shop' ); ?>">
					<?php if ( $product && $product->is_visible() ) : ?>
						<li data-product-id="<?php echo esc_attr( (string) $product->get_id() ); ?>">
							<span><?php echo esc_html( sprintf( '%1$s (#%2$d)', $product->get_name(), $product->get_id() ) ); ?></span>
							<input type="hidden" name="<?php echo esc_attr( $field_prefix ); ?>[product_id]" value="<?php echo esc_attr( (string) $product->get_id() ); ?>">
							<button type="button" class="button-link-delete negar-product-selector__remove"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></button>
						</li>
					<?php endif; ?>
				</ul>
				<p class="description"><?php esc_html_e( 'در صورت انتخاب محصول، نام و لینک واقعی آن در پایین استوری نمایش داده می‌شود.', 'negar-shop' ); ?></p>
			</div>

			<div class="negar-story-grid">
				<p>
					<label><?php esc_html_e( 'متن دکمهٔ فراخوان (CTA)', 'negar-shop' ); ?></label><br>
					<input type="text" class="regular-text" name="<?php echo esc_attr( $field_prefix ); ?>[cta_label]" value="<?php echo esc_attr( $cta_label ); ?>" placeholder="<?php esc_attr_e( 'مشاهده و خرید', 'negar-shop' ); ?>">
				</p>
				<p>
					<label><?php esc_html_e( 'لینک مقصد دکمه (در صورت نبود محصول، یا برای لینک اختصاصی)', 'negar-shop' ); ?></label><br>
					<input type="text" dir="ltr" inputmode="url" class="regular-text code" name="<?php echo esc_attr( $field_prefix ); ?>[cta_url]" value="<?php echo esc_attr( $cta_url ); ?>" placeholder="/shop/">
				</p>
			</div>
		</div>
	</details>
	<?php
}

/**
 * Render the story collection admin controls.
 *
 * @return void
 */
function negar_theme_render_story_fields() {
	$option          = get_option( NEGAR_THEME_STORY_OPTION, null );
	$is_unconfigured = ( null === $option );
	$stories         = ( ! $is_unconfigured && isset( $option['stories'] ) && is_array( $option['stories'] ) ) ? $option['stories'] : array();
	?>
	<div class="negar-stories-manager-wrapper" style="max-width: 960px; margin: 20px 0;">
		<h2><?php esc_html_e( 'مدیریت استوری‌های صفحهٔ اصلی', 'negar-shop' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'استوری‌ها را اضافه، ویرایش یا مرتب کنید. برای تغییر ترتیب می‌توانید کارت‌ها را با دکمه‌های «بالا» و «پایین» جابه‌جا کنید. هر استوری می‌تواند تصویر (با مدت‌زمان دلخواه ۳ تا ۳۰ ثانیه) یا ویدیو (با مدت‌زمان واقعی فایل ویدیویی) باشد.', 'negar-shop' ); ?>
		</p>

		<?php if ( $is_unconfigured ) : ?>
			<div class="notice notice-info inline" style="margin: 14px 0; padding: 12px; border-right: 4px solid #72aee6; background: #f0f6fc;">
				<p style="margin: 0;">
					<strong><?php esc_html_e( 'حالت پیش‌نمایش دمو:', 'negar-shop' ); ?></strong>
					<?php esc_html_e( 'در حال حاضر استوری‌های پیش‌فرض دمو در صفحهٔ اصلی نمایش داده می‌شوند. برای مدیریت استوری‌های واقعی فروشگاه، دکمهٔ «+ افزودن استوری جدید» را بزنید و استوری‌های خود را ذخیره کنید.', 'negar-shop' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<p style="margin: 16px 0;">
			<button type="button" class="button button-primary" id="negar-add-story-btn">
				<?php esc_html_e( '+ افزودن استوری جدید', 'negar-shop' ); ?>
			</button>
		</p>

		<div id="negar-stories-list" class="negar-stories-list">
			<?php foreach ( $stories as $index => $story ) : ?>
				<?php negar_theme_render_single_story_card( $index, $story ); ?>
			<?php endforeach; ?>
		</div>

		<template id="negar-story-template">
			<?php negar_theme_render_single_story_card( '__INDEX__', array(), true ); ?>
		</template>
	</div>
	<?php
}

/**
 * Return raw stored brand options without defaults.
 *
 * @return mixed
 */
function negar_theme_brand_options_raw() {
	return get_option( NEGAR_THEME_BRAND_OPTION, null );
}

/**
 * Resolve a single configured brand into genuine display data.
 *
 * @param array<string, mixed> $item Configured brand array.
 * @param WP_Term|null         $term Optional pre-fetched term.
 * @return array<string, mixed>|null
 */
function negar_theme_resolve_brand_item( $item, $term = null ) {
	$term_id = isset( $item['term_id'] ) ? absint( $item['term_id'] ) : 0;
	if ( ! $term_id ) {
		return null;
	}

	if ( ! $term instanceof WP_Term ) {
		$term = get_term( $term_id, 'product_brand' );
	}

	if ( ! $term || is_wp_error( $term ) || 'product_brand' !== $term->taxonomy ) {
		return null;
	}

	$link = get_term_link( $term, 'product_brand' );
	if ( is_wp_error( $link ) || empty( $link ) ) {
		return null;
	}

	$logo_url         = '';
	$logo_override_id = isset( $item['logo_override_id'] ) ? absint( $item['logo_override_id'] ) : 0;

	// 1. Valid administrator-selected logo override.
	if ( $logo_override_id > 0 && wp_attachment_is_image( $logo_override_id ) ) {
		$logo_url = wp_get_attachment_image_url( $logo_override_id, 'full' ) ?: '';
	}

	// 2. Real brand thumbnail from WooCommerce / taxonomy metadata.
	if ( empty( $logo_url ) ) {
		if ( function_exists( 'wc_get_brand_thumbnail_url' ) ) {
			$logo_url = wc_get_brand_thumbnail_url( $term->term_id, 'full' );
		}
		if ( empty( $logo_url ) ) {
			$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
				$logo_url = wp_get_attachment_image_url( $thumb_id, 'full' ) ?: '';
			}
		}
	}

	// 3. Neutral placeholder if no logo available.
	if ( empty( $logo_url ) ) {
		$logo_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
	}

	return array(
		'term_id'          => $term->term_id,
		'name'             => $term->name,
		'slug'             => $term->slug,
		'count'            => (int) $term->count,
		'link'             => $link,
		'logo'             => $logo_url,
		'logo_override_id' => $logo_override_id,
		'enabled'          => ! empty( $item['enabled'] ),
	);
}

/**
 * Retrieve resolved brands for storefront carousel rendering.
 *
 * Discovers up to 6 real terms when unconfigured. Returns empty when explicitly configured empty.
 *
 * @param bool $only_enabled Whether to return only enabled brands.
 * @return array<int, array<string, mixed>>
 */
function negar_theme_get_brands( $only_enabled = true ) {
	$option = get_option( NEGAR_THEME_BRAND_OPTION, null );

	if ( null === $option ) {
		if ( ! taxonomy_exists( 'product_brand' ) ) {
			return array();
		}
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_brand',
				'hide_empty' => false,
				'number'     => 6,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}

		$discovered = array();
		foreach ( $terms as $term ) {
			$resolved = negar_theme_resolve_brand_item(
				array(
					'term_id'          => $term->term_id,
					'enabled'          => true,
					'logo_override_id' => 0,
				),
				$term
			);
			if ( $resolved ) {
				$discovered[] = $resolved;
			}
		}
		return $discovered;
	}

	if ( ! is_array( $option ) || ! isset( $option['brands'] ) || ! is_array( $option['brands'] ) ) {
		return array();
	}

	$results = array();
	foreach ( $option['brands'] as $item ) {
		if ( ! is_array( $item ) || empty( $item['term_id'] ) ) {
			continue;
		}

		$enabled = ! empty( $item['enabled'] );
		if ( $only_enabled && ! $enabled ) {
			continue;
		}

		$term = get_term( absint( $item['term_id'] ), 'product_brand' );
		if ( ! $term || is_wp_error( $term ) || 'product_brand' !== $term->taxonomy ) {
			continue;
		}

		$resolved = negar_theme_resolve_brand_item( $item, $term );
		if ( $resolved ) {
			$results[] = $resolved;
		}
	}

	return $results;
}

/**
 * Sanitize Brand settings collection submitted via Settings API.
 *
 * @param mixed $submitted Raw option input supplied by Settings API.
 * @return array<string, mixed>
 */
function negar_theme_sanitize_brand_settings( $submitted ) {
	$current = get_option( NEGAR_THEME_BRAND_OPTION, array() );
	$current = is_array( $current ) ? $current : array();

	if ( ! current_user_can( 'manage_options' ) ) {
		add_settings_error( 'negar_theme_brand_settings', 'negar_theme_brand_forbidden', __( 'اجازهٔ ذخیرهٔ تنظیمات را ندارید.', 'negar-shop' ), 'error' );
		return $current;
	}

	$submitted  = is_array( $submitted ) ? wp_unslash( $submitted ) : array();
	$raw_brands = isset( $submitted['brands'] ) && is_array( $submitted['brands'] ) ? $submitted['brands'] : array();

	$sanitized_brands = array();
	$seen_term_ids    = array();

	foreach ( $raw_brands as $item ) {
		if ( count( $sanitized_brands ) >= 20 ) {
			break;
		}
		if ( ! is_array( $item ) || ! isset( $item['term_id'] ) ) {
			continue;
		}

		$term_id = absint( $item['term_id'] );
		if ( $term_id <= 0 || in_array( $term_id, $seen_term_ids, true ) ) {
			continue;
		}

		// Validate that term exists in product_brand taxonomy.
		$term = get_term( $term_id, 'product_brand' );
		if ( ! $term || is_wp_error( $term ) || 'product_brand' !== $term->taxonomy ) {
			continue;
		}

		$seen_term_ids[] = $term_id;

		$enabled = ! empty( $item['enabled'] );

		$logo_override_id = isset( $item['logo_override_id'] ) ? absint( $item['logo_override_id'] ) : 0;
		if ( $logo_override_id > 0 && ! wp_attachment_is_image( $logo_override_id ) ) {
			$logo_override_id = 0;
		}

		$sanitized_brands[] = array(
			'term_id'          => $term_id,
			'enabled'          => (bool) $enabled,
			'logo_override_id' => $logo_override_id,
		);
	}

	return array(
		'schema_version' => NEGAR_THEME_BRAND_VERSION,
		'brands'         => $sanitized_brands,
	);
}

/**
 * Ensure brand option is not autoloaded when created.
 *
 * @param string $option Option name.
 * @return void
 */
function negar_theme_prevent_brand_option_autoload( $option ) {
	if ( NEGAR_THEME_BRAND_OPTION === $option && function_exists( 'wp_set_option_autoload' ) ) {
		wp_set_option_autoload( NEGAR_THEME_BRAND_OPTION, 'no' );
	}
}
add_action( 'added_option', 'negar_theme_prevent_brand_option_autoload' );

/**
 * Render a single brand management row/card.
 *
 * @param int|string           $index       Numeric index or template token.
 * @param array<string, mixed> $brand       Brand item configuration.
 * @param bool                 $is_template Whether this is the JS template prototype.
 * @return void
 */
function negar_theme_render_single_brand_card( $index, $brand = array(), $is_template = false ) {
	$field_prefix = NEGAR_THEME_BRAND_OPTION . '[brands][' . $index . ']';

	if ( $is_template ) {
		$term_id          = 0;
		$brand_name       = '__NAME__';
		$term_slug        = '__SLUG__';
		$native_logo_url  = '__NATIVE_LOGO_URL__';
		$logo_url         = '__LOGO_URL__';
		$logo_override_id = 0;
		$enabled          = true;
	} else {
		$term_id = isset( $brand['term_id'] ) ? absint( $brand['term_id'] ) : 0;
		if ( ! $term_id ) {
			return;
		}
		$term = get_term( $term_id, 'product_brand' );
		if ( ! $term || is_wp_error( $term ) || 'product_brand' !== $term->taxonomy ) {
			return;
		}
		$brand_name      = $term->name;
		$term_slug       = $term->slug;
		$native_logo_url = '';
		if ( function_exists( 'wc_get_brand_thumbnail_url' ) ) {
			$native_logo_url = wc_get_brand_thumbnail_url( $term_id, 'thumbnail' );
		}
		if ( empty( $native_logo_url ) ) {
			$thumb_id = get_term_meta( $term_id, 'thumbnail_id', true );
			if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
				$native_logo_url = wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) ?: '';
			}
		}
		if ( empty( $native_logo_url ) ) {
			$native_logo_url = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
		}

		$logo_override_id = isset( $brand['logo_override_id'] ) ? absint( $brand['logo_override_id'] ) : 0;
		$logo_url         = '';
		if ( $logo_override_id > 0 && wp_attachment_is_image( $logo_override_id ) ) {
			$logo_url = wp_get_attachment_image_url( $logo_override_id, 'thumbnail' ) ?: '';
		}
		if ( empty( $logo_url ) ) {
			$logo_url         = $native_logo_url;
			$logo_override_id = 0;
		}

		$enabled = ! empty( $brand['enabled'] );
	}
	?>
	<div class="negar-brand-card" data-term-id="<?php echo esc_attr( (string) $term_id ); ?>">
		<input type="hidden" class="negar-brand-term-id" name="<?php echo esc_attr( $field_prefix ); ?>[term_id]" value="<?php echo esc_attr( (string) $term_id ); ?>">
		<input type="hidden" class="negar-brand-enabled-hidden" name="<?php echo esc_attr( $field_prefix ); ?>[enabled]" value="0">
		<input type="hidden" class="negar-brand-logo-override-id" name="<?php echo esc_attr( $field_prefix ); ?>[logo_override_id]" value="<?php echo esc_attr( (string) $logo_override_id ); ?>">

		<div class="negar-brand-card__header">
			<div class="negar-brand-card__identity">
				<img class="negar-brand-card__logo-preview" src="<?php echo esc_url( $logo_url ); ?>" data-fallback-url="<?php echo esc_url( $native_logo_url ); ?>" alt="" width="60" height="36">
				<div class="negar-brand-card__title-wrap">
					<strong class="negar-brand-card__name"><?php echo esc_html( $brand_name ); ?></strong>
					<span class="negar-brand-card__meta description">
						<?php if ( ! $is_template ) : ?>
							(#<?php echo esc_html( (string) $term_id ); ?> &bull; <?php echo esc_html( $term_slug ); ?>)
						<?php endif; ?>
					</span>
				</div>
				<span class="negar-brand-card__override-badge" style="<?php echo ( $logo_override_id > 0 ) ? '' : 'display: none;'; ?>"><?php esc_html_e( 'لوگوی اختصاصی', 'negar-shop' ); ?></span>
			</div>

			<div class="negar-brand-card__actions">
				<label class="negar-brand-enabled-label">
					<input type="checkbox" class="negar-brand-enabled-checkbox" name="<?php echo esc_attr( $field_prefix ); ?>[enabled]" value="1" <?php checked( $enabled ); ?>>
					<?php esc_html_e( 'نمایش در گردونه', 'negar-shop' ); ?>
				</label>
				<button type="button" class="button button-small button-secondary negar-brand-btn-up" aria-label="<?php esc_attr_e( 'انتقال به بالا', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'انتقال به بالا', 'negar-shop' ); ?>">&uarr; <?php esc_html_e( 'بالا', 'negar-shop' ); ?></button>
				<button type="button" class="button button-small button-secondary negar-brand-btn-down" aria-label="<?php esc_attr_e( 'انتقال به پایین', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'انتقال به پایین', 'negar-shop' ); ?>">&darr; <?php esc_html_e( 'پایین', 'negar-shop' ); ?></button>
				<button type="button" class="button-link-delete negar-brand-btn-remove" aria-label="<?php esc_attr_e( 'حذف از گردونه', 'negar-shop' ); ?>"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></button>
			</div>
		</div>

		<div class="negar-brand-card__body">
			<div class="negar-brand-logo-control">
				<span class="description" style="margin-left: 8px;"><?php esc_html_e( 'تصویر لوگو:', 'negar-shop' ); ?></span>
				<button type="button" class="button button-secondary button-small negar-brand-btn-select-logo"><?php esc_html_e( 'انتخاب لوگوی اختصاصی', 'negar-shop' ); ?></button>
				<button type="button" class="button-link-delete button-small negar-brand-btn-remove-logo" style="<?php echo ( $logo_override_id > 0 ) ? '' : 'display: none;'; ?>"><?php esc_html_e( 'بازگشت به لوگوی اصلی برند', 'negar-shop' ); ?></button>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render brand collection admin controls.
 *
 * @return void
 */
function negar_theme_render_brand_fields() {
	$option           = get_option( NEGAR_THEME_BRAND_OPTION, null );
	$is_unconfigured  = ( null === $option );
	$brands           = ( ! $is_unconfigured && isset( $option['brands'] ) && is_array( $option['brands'] ) ) ? $option['brands'] : array();
	$taxonomy_exists  = taxonomy_exists( 'product_brand' );
	$brand_count      = $taxonomy_exists ? (int) wp_count_terms( array( 'taxonomy' => 'product_brand', 'hide_empty' => false ) ) : 0;
	$admin_brands_url = admin_url( 'edit-tags.php?taxonomy=product_brand&post_type=product' );
	?>
	<div class="negar-brands-manager-wrapper" style="max-width: 960px; margin: 20px 0;">
		<h2><?php esc_html_e( 'مدیریت گردونهٔ برندهای صفحهٔ اصلی', 'negar-shop' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'برندهای ووکامرس را برای نمایش در گردونهٔ صفحهٔ اصلی انتخاب و مرتب کنید. برای تغییر ترتیب از دکمه‌های «بالا» و «پایین» استفاده کنید. در صورت تمایل می‌توانید برای هر برند لوگوی اختصاصی تعیین کنید.', 'negar-shop' ); ?>
		</p>

		<div class="notice notice-info inline" style="margin: 14px 0; padding: 12px; border-right: 4px solid #2271b1; background: #f0f6fc;">
			<p style="margin: 0;">
				<strong><?php esc_html_e( 'راهنمای برندهای ووکامرس:', 'negar-shop' ); ?></strong>
				<?php esc_html_e( 'برندها در ووکامرس تعریف می‌شوند و به محصولات اختصاص می‌یابند. این بخش تنها ترتیب و نمایش برندها در گردونهٔ صفحهٔ اصلی را مشخص می‌کند. برای ایجاد، ویرایش نام، یا مدیریت لوگوی اصلی برندها، به بخش ', 'negar-shop' ); ?>
				<a href="<?php echo esc_url( $admin_brands_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'محصولات ← برندها', 'negar-shop' ); ?>
				</a>
				<?php esc_html_e( ' مراجعه کنید.', 'negar-shop' ); ?>
			</p>
		</div>

		<?php if ( ! $taxonomy_exists ) : ?>
			<div class="notice notice-error inline" style="margin: 14px 0; padding: 12px; border-right: 4px solid #d63638; background: #fcf0f1;">
				<p style="margin: 0;">
					<strong><?php esc_html_e( 'خطا:', 'negar-shop' ); ?></strong>
					<?php esc_html_e( 'تاکسونومی برندهای ووکامرس (product_brand) در سیستم ثبت نشده است. لطفاً از فعال بودن افزونهٔ ووکامرس و پشتیبانی از برندها اطمینان حاصل کنید.', 'negar-shop' ); ?>
				</p>
			</div>
		<?php elseif ( 0 === $brand_count ) : ?>
			<div class="notice notice-warning inline" style="margin: 14px 0; padding: 12px; border-right: 4px solid #dba617; background: #fcf9e8;">
				<p style="margin: 0;">
					<strong><?php esc_html_e( 'توجه:', 'negar-shop' ); ?></strong>
					<?php esc_html_e( 'هنوز هیچ برندی در فروشگاه ثبت نشده است. ابتدا از بخش ', 'negar-shop' ); ?>
					<a href="<?php echo esc_url( $admin_brands_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'محصولات ← برندها', 'negar-shop' ); ?></a>
					<?php esc_html_e( ' برندهای مورد نظر خود را ایجاد کنید.', 'negar-shop' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( $is_unconfigured ) : ?>
			<div class="notice notice-info inline" style="margin: 14px 0; padding: 12px; border-right: 4px solid #72aee6; background: #f0f6fc;">
				<p style="margin: 0;">
					<strong><?php esc_html_e( 'حالت اولیه (پیکربندی‌نشده):', 'negar-shop' ); ?></strong>
					<?php esc_html_e( 'تا زمانی که تنظیمی برای برندها ذخیره نشود، تا ۶ برند نخست فروشگاه به‌صورت خودکار در صفحهٔ اصلی نمایش داده می‌شوند. برای تنظیم دلخواه، برندهای مورد نظر را اضافه و این فرم را ذخیره کنید.', 'negar-shop' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<div class="negar-brand-search-box">
			<label for="negar-brand-search-input"><strong><?php esc_html_e( 'جست‌وجو و افزودن برند:', 'negar-shop' ); ?></strong></label>
			<div style="display: flex; gap: 8px; align-items: center; margin-top: 6px;">
				<input type="search" id="negar-brand-search-input" class="regular-text" autocomplete="off" placeholder="<?php esc_attr_e( 'نام برند را جست‌وجو کنید…', 'negar-shop' ); ?>" <?php disabled( ! $taxonomy_exists || 0 === $brand_count ); ?>>
				<span class="spinner" id="negar-brand-search-spinner" style="float: none; margin: 0;"></span>
			</div>
			<p class="description" id="negar-brand-search-status" aria-live="polite" style="margin-top: 4px;"></p>
			<div id="negar-brand-search-results" class="negar-brand-search-results" role="listbox" style="display: none;"></div>
		</div>

		<div id="negar-brands-list" class="negar-brands-list" data-max-records="20">
			<?php foreach ( $brands as $index => $brand ) : ?>
				<?php negar_theme_render_single_brand_card( $index, $brand ); ?>
			<?php endforeach; ?>
		</div>

		<p class="description" id="negar-brands-empty-notice" style="<?php echo empty( $brands ) ? '' : 'display: none;'; ?> margin-top: 10px;">
			<?php esc_html_e( 'هیچ برندی برای گردونه انتخاب نشده است. با خالی ماندن این لیست، بخش برندها در صفحهٔ اصلی مخفی خواهد شد.', 'negar-shop' ); ?>
		</p>

		<template id="negar-brand-card-template">
			<?php negar_theme_render_single_brand_card( '__INDEX__', array(), true ); ?>
		</template>
	</div>
	<?php
}

/**
 * AJAX handler for searching genuine WooCommerce brands.
 *
 * @return void
 */
function negar_theme_ajax_search_brands() {
	check_ajax_referer( 'negar_theme_brand_search', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'اجازهٔ دسترسی ندارید.', 'negar-shop' ) ), 403 );
	}

	if ( ! taxonomy_exists( 'product_brand' ) ) {
		wp_send_json_error( array( 'message' => __( 'تاکسونومی برندها یافت نشد.', 'negar-shop' ) ), 404 );
	}

	$raw_query = isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '';
	$query     = is_scalar( $raw_query ) ? sanitize_text_field( $raw_query ) : '';

	$args = array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => false,
		'number'     => 20,
	);

	if ( '' !== $query ) {
		$args['search'] = $query;
	}

	$terms = get_terms( $args );
	if ( is_wp_error( $terms ) ) {
		wp_send_json_error( array( 'message' => $terms->get_error_message() ) );
	}

	$results     = array();
	$placeholder = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';

	foreach ( $terms as $term ) {
		$logo_url = '';
		if ( function_exists( 'wc_get_brand_thumbnail_url' ) ) {
			$logo_url = wc_get_brand_thumbnail_url( $term->term_id, 'thumbnail' );
		}
		if ( empty( $logo_url ) ) {
			$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
				$logo_url = wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) ?: '';
			}
		}
		if ( empty( $logo_url ) ) {
			$logo_url = $placeholder;
		}

		$results[] = array(
			'term_id'  => $term->term_id,
			'name'     => $term->name,
			'slug'     => $term->slug,
			'count'    => (int) $term->count,
			'logo_url' => $logo_url,
		);
	}

	wp_send_json_success( array( 'brands' => $results ) );
}
add_action( 'wp_ajax_negar_theme_search_brands', 'negar_theme_ajax_search_brands' );

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
	register_setting(
		'negar_theme_media_settings',
		NEGAR_THEME_MEDIA_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_media_settings',
			'default'           => array(),
		)
	);
	register_setting(
		'negar_theme_media_settings',
		NEGAR_THEME_PRODUCT_PACK_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_product_pack_settings',
			'default'           => array(),
		)
	);
	register_setting(
		'negar_theme_story_settings',
		NEGAR_THEME_STORY_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_story_settings',
			'default'           => array(),
		)
	);
	register_setting(
		'negar_theme_brand_settings',
		NEGAR_THEME_BRAND_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'negar_theme_sanitize_brand_settings',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'negar_theme_register_settings' );

/**
 * Create the media option only on the admin path and keep it out of autoload.
 *
 * @return void
 */
function negar_theme_ensure_media_option() {
	if ( false === get_option( NEGAR_THEME_MEDIA_OPTION, false ) ) {
		add_option( NEGAR_THEME_MEDIA_OPTION, array(), '', false );
	}
	if ( false === get_option( NEGAR_THEME_PRODUCT_PACK_OPTION, false ) ) {
		add_option( NEGAR_THEME_PRODUCT_PACK_OPTION, array(), '', false );
	}
}
add_action( 'admin_init', 'negar_theme_ensure_media_option', 20 );

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
	$raw_tab   = isset( $_POST['negar_theme_settings_tab'] ) ? wp_unslash( $_POST['negar_theme_settings_tab'] ) : '';
	$tab       = is_scalar( $raw_tab ) ? sanitize_key( $raw_tab ) : '';
	$allowed   = array( 'general', 'texts', 'layout', 'products', 'colors', 'header_footer' );

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
			if ( ! is_scalar( $id ) ) {
				continue;
			}
			$id = sanitize_key( $id );
			if ( ! isset( $registry[ $id ] ) ) {
				continue;
			}

			$enabled_value = isset( $enabled[ $id ] ) && is_scalar( $enabled[ $id ] ) ? $enabled[ $id ] : '';
			$rows[] = array(
				'id'      => $id,
				'enabled' => '1' === (string) $enabled_value,
			);
		}

		$current['layout']['sections'] = negar_theme_normalize_home_layout( $rows );
	}

	if ( 'products' === $tab ) {
		$registry      = negar_theme_product_section_registry();
		$raw_section   = isset( $_POST['negar_theme_product_section_action'] ) ? wp_unslash( $_POST['negar_theme_product_section_action'] ) : '';
		$section_id    = is_scalar( $raw_section ) ? sanitize_key( $raw_section ) : '';
		$submitted_sections = isset( $submitted['products']['sections'] ) && is_array( $submitted['products']['sections'] ) ? $submitted['products']['sections'] : array();

		if ( isset( $registry[ $section_id ] ) ) {
			$section_input = isset( $submitted_sections[ $section_id ] ) && is_array( $submitted_sections[ $section_id ] ) ? $submitted_sections[ $section_id ] : array();
			$enabled       = isset( $section_input['enabled'] ) && is_scalar( $section_input['enabled'] ) && '1' === (string) $section_input['enabled'];

			if ( $enabled ) {
				$current['products']['sections'][ $section_id ] = negar_theme_sanitize_product_section_config( $section_input, $section_id );
			} else {
				// Removing only this section restores its exact legacy template query.
				unset( $current['products']['sections'][ $section_id ] );
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
	$product_script_path = NEGAR_SHOP_DIR . '/assets/js/theme-settings-products.js';
	$media_script_path   = NEGAR_SHOP_DIR . '/assets/js/theme-settings-media.js';

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
	wp_enqueue_media();
	wp_enqueue_script(
		'negar-theme-settings-media',
		NEGAR_SHOP_URI . '/assets/js/theme-settings-media.js',
		array( 'jquery' ),
		file_exists( $media_script_path ) ? (string) filemtime( $media_script_path ) : NEGAR_SHOP_VERSION,
		true
	);
	wp_enqueue_script(
		'negar-theme-settings-products',
		NEGAR_SHOP_URI . '/assets/js/theme-settings-products.js',
		array(),
		file_exists( $product_script_path ) ? (string) filemtime( $product_script_path ) : NEGAR_SHOP_VERSION,
		true
	);
	wp_localize_script(
		'negar-theme-settings-products',
		'NegarThemeProductSearch',
		array(
			'url'      => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'negar_theme_product_search' ),
			'option'   => NEGAR_THEME_SETTINGS_OPTION,
			'messages' => array(
				'loading' => __( 'در حال جست‌وجو…', 'negar-shop' ),
				'empty'   => __( 'محصولی پیدا نشد. نام یا شناسهٔ دیگری را جست‌وجو کنید.', 'negar-shop' ),
				'error'   => __( 'جست‌وجوی محصولات انجام نشد. دوباره تلاش کنید.', 'negar-shop' ),
			),
		)
	);

	$story_script_path = NEGAR_SHOP_DIR . '/assets/js/theme-settings-stories.js';
	wp_enqueue_script(
		'negar-theme-settings-stories',
		NEGAR_SHOP_URI . '/assets/js/theme-settings-stories.js',
		array( 'jquery', 'negar-theme-settings-products', 'negar-theme-settings-media' ),
		file_exists( $story_script_path ) ? (string) filemtime( $story_script_path ) : NEGAR_SHOP_VERSION,
		true
	);
	wp_localize_script(
		'negar-theme-settings-stories',
		'NegarThemeStoriesConfig',
		array(
			'messages' => array(
				'selectVideo' => __( 'انتخاب ویدیو برای استوری', 'negar-shop' ),
				'useVideo'    => __( 'استفاده از این ویدیو', 'negar-shop' ),
			),
		)
	);

	$brand_script_path = NEGAR_SHOP_DIR . '/assets/js/theme-settings-brands.js';
	wp_enqueue_script(
		'negar-theme-settings-brands',
		NEGAR_SHOP_URI . '/assets/js/theme-settings-brands.js',
		array( 'jquery', 'negar-theme-settings-media' ),
		file_exists( $brand_script_path ) ? (string) filemtime( $brand_script_path ) : NEGAR_SHOP_VERSION,
		true
	);
	wp_localize_script(
		'negar-theme-settings-brands',
		'NegarThemeBrandsConfig',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'negar_theme_brand_search' ),
			'messages' => array(
				'loading'      => __( 'در حال جست‌وجو…', 'negar-shop' ),
				'empty'        => __( 'برندی پیدا نشد.', 'negar-shop' ),
				'error'        => __( 'خطا در جست‌وجوی برندها.', 'negar-shop' ),
				'alreadyAdded' => __( 'این برند قبلاً به لیست اضافه شده است.', 'negar-shop' ),
				'maxReached'   => __( 'حداکثر ۲۰ برند می‌توانید اضافه کنید.', 'negar-shop' ),
				'selectLogo'   => __( 'انتخاب تصویر لوگوی اختصاصی', 'negar-shop' ),
				'useLogo'      => __( 'استفاده از این تصویر', 'negar-shop' ),
			),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'negar_theme_enqueue_settings_assets' );

/**
 * Return a small authorized product-search result set for the settings page.
 *
 * @return void
 */
function negar_theme_ajax_search_products() {
	check_ajax_referer( 'negar_theme_product_search', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'اجازهٔ جست‌وجوی محصولات را ندارید.', 'negar-shop' ) ), 403 );
	}

	$raw_query = isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '';
	$query     = is_scalar( $raw_query ) ? sanitize_text_field( $raw_query ) : '';
	if ( '' === $query ) {
		wp_send_json_success( array( 'products' => array() ) );
	}

	$search_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 30,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	);
	if ( ctype_digit( $query ) ) {
		$search_args['post__in'] = array( absint( $query ) );
	} else {
		$search_args['orderby'] = 'relevance';
		$search_args['s']       = $query;
	}
	$candidates = get_posts( negar_theme_catalog_visibility_query_args( $search_args ) );
	$products = array();
	foreach ( $candidates as $product_id ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
		if ( ! $product || ! $product->is_visible() ) {
			continue;
		}

		$products[] = array(
			'id'    => $product->get_id(),
			'label' => sprintf( '%1$s (#%2$d)', $product->get_name(), $product->get_id() ),
		);
		if ( 20 === count( $products ) ) {
			break;
		}
	}

	wp_send_json_success( array( 'products' => $products ) );
}
add_action( 'wp_ajax_negar_theme_search_products', 'negar_theme_ajax_search_products' );

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
 * Return the already selected real products for a lightweight admin selector.
 *
 * @param array<int, int> $product_ids Saved product IDs.
 * @return array<int, array{id:int,label:string}>
 */
function negar_theme_selected_product_selector_items( $product_ids ) {
	$items = array();
	foreach ( array_values( array_unique( array_filter( array_map( 'absint', (array) $product_ids ) ) ) ) as $product_id ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : false;
		if ( ! $product ) {
			continue;
		}

		$items[] = array(
			'id'    => $product->get_id(),
			'label' => sprintf( '%1$s (#%2$d)', $product->get_name(), $product->get_id() ),
		);
	}

	return $items;
}

/**
 * Render the Phase 4 product-query controls.
 *
 * @return void
 */
function negar_theme_render_product_fields() {
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		)
	);
	$sorts      = negar_theme_product_section_sort_options();
	?>
	<fieldset class="negar-product-manager" aria-describedby="negar-product-manager-help">
		<legend class="screen-reader-text"><?php esc_html_e( 'مدیریت بخش‌های محصول صفحهٔ اصلی', 'negar-shop' ); ?></legend>
		<p id="negar-product-manager-help" class="description"><?php esc_html_e( 'تنظیمات هر بخش مستقل است و تا فعال‌سازی آن، همان query قدیمی قالب حفظ می‌شود. فقط محصولات منتشرشده و قابل‌نمایش WooCommerce قابل انتخاب‌اند؛ انتخاب دستی و دسته‌ها، دادهٔ محصول را نمی‌سازند یا تغییر نمی‌دهند.', 'negar-shop' ); ?></p>
		<?php foreach ( negar_theme_product_section_registry() as $section_id => $section ) : ?>
			<?php
			$config       = negar_theme_product_section_config( $section_id );
			$is_configured = negar_theme_product_section_is_configured( $section_id );
			$selected_items = negar_theme_selected_product_selector_items( $config['product_ids'] );
			?>
			<details class="negar-product-section" <?php echo 'flash-deals' === $section_id ? 'open' : ''; ?>>
				<summary><?php echo esc_html( $section['label'] ); ?></summary>
				<div class="negar-product-section__fields">
					<p class="negar-product-section__state">
						<input type="hidden" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][enabled]" value="0">
						<label for="negar-products-enabled-<?php echo esc_attr( $section_id ); ?>">
							<input id="negar-products-enabled-<?php echo esc_attr( $section_id ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][enabled]" type="checkbox" value="1" <?php checked( $is_configured ); ?>>
							<?php esc_html_e( 'استفاده از تنظیمات اختصاصی این بخش', 'negar-shop' ); ?>
						</label>
						<span class="description"><?php esc_html_e( 'خاموش‌کردن و ذخیرهٔ همین بخش، رفتار query قدیمی آن را برمی‌گرداند.', 'negar-shop' ); ?></span>
					</p>
					<p>
						<label for="negar-products-count-<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'تعداد محصولات', 'negar-shop' ); ?></label>
						<input id="negar-products-count-<?php echo esc_attr( $section_id ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][count]" type="number" min="1" max="12" value="<?php echo esc_attr( $config['count'] ); ?>">
					</p>
					<p>
						<label for="negar-products-sort-<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'مرتب‌سازی', 'negar-shop' ); ?></label>
						<select id="negar-products-sort-<?php echo esc_attr( $section_id ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][sort]">
							<?php foreach ( $sorts as $sort_id => $sort_label ) : ?>
								<option value="<?php echo esc_attr( $sort_id ); ?>" <?php selected( $config['sort'], $sort_id ); ?>><?php echo esc_html( $sort_label ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p>
						<label for="negar-products-include-<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'دسته‌های شامل', 'negar-shop' ); ?></label>
						<select id="negar-products-include-<?php echo esc_attr( $section_id ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][include_categories][]" multiple size="5">
							<?php foreach ( $categories as $category ) : ?>
								<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( in_array( (int) $category->term_id, $config['include_categories'], true ) ); ?>><?php echo esc_html( $category->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p>
						<label for="negar-products-exclude-<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'دسته‌های مستثنا', 'negar-shop' ); ?></label>
						<select id="negar-products-exclude-<?php echo esc_attr( $section_id ); ?>" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][exclude_categories][]" multiple size="5">
							<?php foreach ( $categories as $category ) : ?>
								<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( in_array( (int) $category->term_id, $config['exclude_categories'], true ) ); ?>><?php echo esc_html( $category->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<div class="negar-product-section__manual" data-product-selector data-section-id="<?php echo esc_attr( $section_id ); ?>">
						<label for="negar-products-search-<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'محصولات انتخابی', 'negar-shop' ); ?></label>
						<input id="negar-products-search-<?php echo esc_attr( $section_id ); ?>" class="regular-text" type="search" autocomplete="off" aria-describedby="negar-products-search-help-<?php echo esc_attr( $section_id ); ?> negar-products-search-status-<?php echo esc_attr( $section_id ); ?>" aria-controls="negar-products-search-results-<?php echo esc_attr( $section_id ); ?>" placeholder="<?php esc_attr_e( 'نام یا شناسهٔ محصول را جست‌وجو کنید', 'negar-shop' ); ?>">
						<p id="negar-products-search-help-<?php echo esc_attr( $section_id ); ?>" class="description"><?php esc_html_e( 'نتایج جست‌وجو درخواستی و محدود هستند؛ محصولات انتخاب‌شده حتی اگر در نتایج فعلی نباشند حفظ می‌شوند.', 'negar-shop' ); ?></p>
						<p id="negar-products-search-status-<?php echo esc_attr( $section_id ); ?>" class="description negar-product-selector__status" aria-live="polite"></p>
						<div id="negar-products-search-results-<?php echo esc_attr( $section_id ); ?>" class="negar-product-selector__results" role="listbox" aria-label="<?php esc_attr_e( 'نتایج جست‌وجوی محصول', 'negar-shop' ); ?>"></div>
						<ul class="negar-product-selector__selected" aria-label="<?php esc_attr_e( 'محصولات انتخاب‌شده', 'negar-shop' ); ?>">
							<?php foreach ( $selected_items as $item ) : ?>
								<li data-product-id="<?php echo esc_attr( $item['id'] ); ?>">
									<span><?php echo esc_html( $item['label'] ); ?></span>
									<input type="hidden" name="<?php echo esc_attr( NEGAR_THEME_SETTINGS_OPTION ); ?>[products][sections][<?php echo esc_attr( $section_id ); ?>][product_ids][]" value="<?php echo esc_attr( $item['id'] ); ?>">
									<button type="button" class="button-link-delete negar-product-selector__remove" aria-label="<?php echo esc_attr( sprintf( __( 'حذف %s از انتخاب', 'negar-shop' ), $item['label'] ) ); ?>"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></button>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<p class="negar-product-section__save"><button class="button button-primary" type="submit" name="negar_theme_product_section_action" value="<?php echo esc_attr( $section_id ); ?>"><?php esc_html_e( 'ذخیرهٔ همین بخش', 'negar-shop' ); ?></button></p>
				</div>
			</details>
		<?php endforeach; ?>
	</fieldset>
	<?php
}

/**
 * Render the fixed Phase 5 media records.
 *
 * @return void
 */
function negar_theme_render_media_fields() {
	?>
	<p class="description"><?php esc_html_e( 'هر رکورد مستقل است. تا زمانی که یک تصویر معتبر از کتابخانهٔ رسانه انتخاب نشود، تصویر فعلی دمو روی سایت می‌ماند. متن اسلایدهای Hero در زبانهٔ متن‌ها مدیریت می‌شود.', 'negar-shop' ); ?></p>
	<div class="negar-media-manager">
		<?php foreach ( negar_theme_media_registry() as $record_id => $definition ) : ?>
			<?php $record = negar_theme_media_record( $record_id ); ?>
			<details class="negar-media-record" <?php echo in_array( $record_id, array( 'hero-slide-one', 'hero-slide-two', 'banners-grid-one' ), true ) ? 'open' : ''; ?>>
				<summary><?php echo esc_html( $definition['label'] ); ?></summary>
				<div class="negar-media-record__fields">
					<input type="hidden" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][enabled]" value="0">
					<p><label><input type="checkbox" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][enabled]" value="1" <?php checked( ! empty( $record['enabled'] ) ); ?>> <?php esc_html_e( 'نمایش این رکورد', 'negar-shop' ); ?></label></p>
					<p class="negar-media-control" data-media-control>
						<label for="negar-media-id-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'تصویر', 'negar-shop' ); ?></label><br>
						<input id="negar-media-id-<?php echo esc_attr( $record_id ); ?>" class="small-text negar-media-control__id" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][media_id]" type="number" min="0" step="1" value="<?php echo esc_attr( absint( $record['media_id'] ) ); ?>" aria-label="<?php esc_attr_e( 'شناسهٔ تصویر', 'negar-shop' ); ?>">
						<img class="negar-media-control__preview" src="<?php echo esc_url( $record['image_url'] ); ?>" data-fallback-url="<?php echo esc_url( $record['fallback_url'] ); ?>" alt="" width="120" height="80">
						<button type="button" class="button negar-media-control__select"><?php esc_html_e( 'انتخاب تصویر', 'negar-shop' ); ?></button>
						<button type="button" class="button-link-delete negar-media-control__remove"><?php esc_html_e( 'بازگشت به تصویر پیش‌فرض', 'negar-shop' ); ?></button>
					</p>
					<p><label for="negar-media-link-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'پیوند مقصد', 'negar-shop' ); ?></label><br><input id="negar-media-link-<?php echo esc_attr( $record_id ); ?>" class="regular-text code" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][link]" type="text" dir="ltr" inputmode="url" value="<?php echo esc_attr( $record['link'] ); ?>"></p>
					<p><label for="negar-media-alt-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'متن جایگزین تصویر', 'negar-shop' ); ?></label><br><input id="negar-media-alt-<?php echo esc_attr( $record_id ); ?>" class="regular-text" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][alt]" type="text" value="<?php echo esc_attr( $record['alt'] ); ?>"></p>
					<?php if ( in_array( 'title', $definition['fields'], true ) ) : ?>
						<p><label for="negar-media-title-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'عنوان', 'negar-shop' ); ?></label><br><input id="negar-media-title-<?php echo esc_attr( $record_id ); ?>" class="regular-text" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][title]" type="text" value="<?php echo esc_attr( $record['title'] ); ?>"></p>
						<p><label for="negar-media-subtitle-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'زیرعنوان', 'negar-shop' ); ?></label><br><input id="negar-media-subtitle-<?php echo esc_attr( $record_id ); ?>" class="regular-text" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][subtitle]" type="text" value="<?php echo esc_attr( $record['subtitle'] ); ?>"></p>
						<p><label for="negar-media-cta-<?php echo esc_attr( $record_id ); ?>"><?php esc_html_e( 'برچسب CTA', 'negar-shop' ); ?></label><br><input id="negar-media-cta-<?php echo esc_attr( $record_id ); ?>" class="regular-text" name="<?php echo esc_attr( NEGAR_THEME_MEDIA_OPTION ); ?>[records][<?php echo esc_attr( $record_id ); ?>][cta_label]" type="text" value="<?php echo esc_attr( $record['cta_label'] ); ?>"></p>
					<?php endif; ?>
				</div>
			</details>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render fixed product-pack and hotspot product selectors.
 *
 * @return void
 */
function negar_theme_render_product_pack_fields() {
	?>
	<hr>
	<h2><?php esc_html_e( 'پک‌های محصول و نقاط داغ', 'negar-shop' ); ?></h2>
	<p class="description"><?php esc_html_e( 'فقط شناسهٔ محصول منتشرشده و قابل‌نمایش WooCommerce پذیرفته می‌شود. خالی‌کردن یک شناسه، همان انتخاب پیش‌فرض قالب را برمی‌گرداند و مختصات نقاط داغ را تغییر نمی‌دهد.', 'negar-shop' ); ?></p>
	<?php foreach ( negar_theme_product_pack_registry() as $pack_id => $pack ) : ?>
		<fieldset class="negar-product-section">
			<legend><?php echo esc_html( $pack['label'] ); ?></legend>
			<div class="negar-product-section__fields">
				<?php foreach ( $pack['slots'] as $slot_id => $label ) : ?>
					<?php
					$value   = negar_theme_product_pack_product_id( $pack_id, $slot_id );
					$product = $value && function_exists( 'wc_get_product' ) ? wc_get_product( $value ) : false;
					$field_name = NEGAR_THEME_PRODUCT_PACK_OPTION . '[packs][' . $pack_id . '][' . $slot_id . ']';
					?>
					<div class="negar-product-section__manual" data-product-selector data-single="1" data-input-name="<?php echo esc_attr( $field_name ); ?>">
						<input type="hidden" name="<?php echo esc_attr( $field_name ); ?>" value="0">
						<label for="negar-pack-search-<?php echo esc_attr( $pack_id . '-' . $slot_id ); ?>"><?php echo esc_html( $label ); ?></label>
						<input id="negar-pack-search-<?php echo esc_attr( $pack_id . '-' . $slot_id ); ?>" class="regular-text" type="search" autocomplete="off" placeholder="<?php esc_attr_e( 'نام یا شناسهٔ محصول را جست‌وجو کنید', 'negar-shop' ); ?>">
						<p class="description negar-product-selector__status" aria-live="polite"></p>
						<div class="negar-product-selector__results" role="listbox"></div>
						<ul class="negar-product-selector__selected" aria-label="<?php echo esc_attr( $label ); ?>">
							<?php if ( $product ) : ?><li data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"><span><?php echo esc_html( sprintf( '%1$s (#%2$d)', $product->get_name(), $product->get_id() ) ); ?></span><input type="hidden" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $product->get_id() ); ?>"><button type="button" class="button-link-delete negar-product-selector__remove"><?php esc_html_e( 'حذف', 'negar-shop' ); ?></button></li><?php endif; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</fieldset>
	<?php endforeach; ?>
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
		<?php settings_errors( 'negar_theme_settings' ); settings_errors( 'negar_theme_media_settings' ); settings_errors( 'negar_theme_story_settings' ); settings_errors( 'negar_theme_brand_settings' ); ?>
		<?php if ( in_array( $active_tab, array( 'general', 'texts', 'layout', 'products', 'colors', 'header_footer', 'banners', 'stories', 'brands' ), true ) ) : ?>
			<form action="options.php" method="post">
				<?php
				if ( 'banners' === $active_tab ) {
					settings_fields( 'negar_theme_media_settings' );
				} elseif ( 'stories' === $active_tab ) {
					settings_fields( 'negar_theme_story_settings' );
				} elseif ( 'brands' === $active_tab ) {
					settings_fields( 'negar_theme_brand_settings' );
				} else {
					settings_fields( 'negar_theme_settings' );
				}
				?>
				<input type="hidden" name="negar_theme_settings_tab" value="<?php echo esc_attr( $active_tab ); ?>">
				<?php if ( 'layout' === $active_tab ) : ?>
					<?php negar_theme_render_layout_fields(); ?>
				<?php elseif ( 'products' === $active_tab ) : ?>
					<?php negar_theme_render_product_fields(); ?>
				<?php elseif ( 'banners' === $active_tab ) : ?>
					<?php negar_theme_render_media_fields(); negar_theme_render_product_pack_fields(); ?>
				<?php elseif ( 'stories' === $active_tab ) : ?>
					<?php negar_theme_render_story_fields(); ?>
				<?php elseif ( 'brands' === $active_tab ) : ?>
					<?php negar_theme_render_brand_fields(); ?>
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
				<?php if ( 'products' !== $active_tab ) : ?>
					<?php submit_button( __( 'ذخیرهٔ تنظیمات', 'negar-shop' ) ); ?>
				<?php endif; ?>
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
