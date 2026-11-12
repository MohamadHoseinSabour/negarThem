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
		'gaming-zone' => array(
			'label' => __( 'پک گیمینگ و نقاط داغ', 'negar-shop' ),
			'slots' => array(
				'shadows' => __( 'نقطهٔ داغ ۱', 'negar-shop' ),
				'mirage'  => __( 'نقطهٔ داغ ۲', 'negar-shop' ),
				'fc'      => __( 'نقطهٔ داغ ۳', 'negar-shop' ),
				'yotei'   => __( 'نقطهٔ داغ ۴', 'negar-shop' ),
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
		foreach ( $pack['slots'] as $slot_id => $label ) {
			$value = isset( $packs[ $pack_id ][ $slot_id ] ) && is_scalar( $packs[ $pack_id ][ $slot_id ] ) ? absint( $packs[ $pack_id ][ $slot_id ] ) : 0;
			$clean['packs'][ $pack_id ][ $slot_id ] = in_array( $value, negar_theme_sanitize_product_ids( array( $value ) ), true ) ? $value : 0;
		}
	}
	return $clean;
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
		<?php settings_errors( 'negar_theme_settings' ); settings_errors( 'negar_theme_media_settings' ); ?>
		<?php if ( in_array( $active_tab, array( 'general', 'texts', 'layout', 'products', 'colors', 'header_footer', 'banners' ), true ) ) : ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'banners' === $active_tab ? 'negar_theme_media_settings' : 'negar_theme_settings' ); ?>
				<input type="hidden" name="negar_theme_settings_tab" value="<?php echo esc_attr( $active_tab ); ?>">
				<?php if ( 'layout' === $active_tab ) : ?>
					<?php negar_theme_render_layout_fields(); ?>
				<?php elseif ( 'products' === $active_tab ) : ?>
					<?php negar_theme_render_product_fields(); ?>
				<?php elseif ( 'banners' === $active_tab ) : ?>
					<?php negar_theme_render_media_fields(); negar_theme_render_product_pack_fields(); ?>
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
