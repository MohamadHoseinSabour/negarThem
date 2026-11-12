<?php
/**
 * Reversible WordPress-side regression helper for theme settings.
 *
 * This script changes only theme options/theme mods and is always paired with
 * the Node runner's restore action. It never touches WooCommerce data.
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

require dirname( __DIR__, 4 ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/template.php';

$action = isset( $argv[1] ) ? $argv[1] : '';
$option = NEGAR_THEME_SETTINGS_OPTION;
$media_option = NEGAR_THEME_MEDIA_OPTION;
$pack_option  = NEGAR_THEME_PRODUCT_PACK_OPTION;
$mods   = 'theme_mods_' . get_option( 'stylesheet' );

function negar_theme_test_admin() {
	$admins = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => 'ID',
		)
	);

	if ( empty( $admins ) ) {
		throw new RuntimeException( 'No administrator is available for the settings regression test.' );
	}

	wp_set_current_user( (int) $admins[0] );

	return (int) $admins[0];
}

function negar_theme_test_auth_cookie() {
	$user_id    = negar_theme_test_admin();
	$expiration = time() + ( 5 * MINUTE_IN_SECONDS );
	$token      = WP_Session_Tokens::get_instance( $user_id )->create( $expiration );
	$scheme     = is_ssl() ? 'secure_auth' : 'auth';
	$cookie     = wp_generate_auth_cookie( $user_id, $expiration, $scheme, $token );

	return array(
		'name'    => 'secure_auth' === $scheme ? SECURE_AUTH_COOKIE : AUTH_COOKIE,
		'value'   => $cookie,
		'expires' => $expiration,
		'token'   => $token,
		'user_id' => $user_id,
	);
}

function negar_theme_test_destroy_auth_cookie( $user_id, $token ) {
	if ( $user_id > 0 && '' !== $token ) {
		WP_Session_Tokens::get_instance( $user_id )->destroy( $token );
	}
}

function negar_theme_test_snapshot( $option, $media_option, $pack_option, $mods ) {
	$settings = get_option( $option, null );
	$theme_mods = get_option( $mods, null );

	return array(
		'option_exists' => null !== $settings,
		'option'        => $settings,
		'mods_exists'   => null !== $theme_mods,
		'mods'          => $theme_mods,
		'media_exists'  => null !== get_option( $media_option, null ),
		'media'         => get_option( $media_option, null ),
		'pack_exists'   => null !== get_option( $pack_option, null ),
		'pack'          => get_option( $pack_option, null ),
	);
}

function negar_theme_test_restore( $option, $media_option, $pack_option, $mods, $snapshot ) {
	if ( ! empty( $snapshot['option_exists'] ) ) {
		update_option( $option, $snapshot['option'] );
	} else {
		delete_option( $option );
	}

	if ( ! empty( $snapshot['mods_exists'] ) ) {
		update_option( $mods, $snapshot['mods'] );
	} else {
		delete_option( $mods );
	}
	if ( ! empty( $snapshot['media_exists'] ) ) {
		update_option( $media_option, $snapshot['media'] );
	} else {
		delete_option( $media_option );
	}
	if ( ! empty( $snapshot['pack_exists'] ) ) {
		update_option( $pack_option, $snapshot['pack'] );
	} else {
		delete_option( $pack_option );
	}

	wp_cache_delete( $option, 'options' );
	wp_cache_delete( $mods, 'options' );
}

function negar_theme_test_save_group( $tab, $input, $section_action = '' ) {
	$_POST['negar_theme_settings_tab'] = $tab;
	if ( 'products' === $tab && '' !== $section_action ) {
		$_POST['negar_theme_product_section_action'] = $section_action;
	}
	$settings                          = negar_theme_sanitize_settings( $input );
	update_option( NEGAR_THEME_SETTINGS_OPTION, $settings );
	unset( $_POST['negar_theme_settings_tab'] );
	unset( $_POST['negar_theme_product_section_action'] );

	return $settings;
}

try {
	if ( 'snapshot' === $action ) {
		echo base64_encode( wp_json_encode( negar_theme_test_snapshot( $option, $media_option, $pack_option, $mods ) ) );
		exit( 0 );
	}

	if ( 'restore' === $action ) {
		$snapshot = isset( $argv[2] ) ? json_decode( base64_decode( $argv[2] ), true ) : null;
		if ( ! is_array( $snapshot ) ) {
			throw new InvalidArgumentException( 'Invalid settings snapshot.' );
		}
		negar_theme_test_restore( $option, $media_option, $pack_option, $mods, $snapshot );
		echo wp_json_encode( array( 'restored' => true ) );
		exit( 0 );
	}

	negar_theme_test_admin();

	if ( 'auth-cookie' === $action ) {
		echo wp_json_encode( negar_theme_test_auth_cookie() );
		exit( 0 );
	}

	if ( 'destroy-auth-cookie' === $action ) {
		negar_theme_test_destroy_auth_cookie( isset( $argv[2] ) ? (int) $argv[2] : 0, isset( $argv[3] ) ? $argv[3] : '' );
		echo wp_json_encode( array( 'destroyed' => true ) );
		exit( 0 );
	}

	if ( 'product-fixture' === $action ) {
		$product_ids = get_posts(
			negar_theme_catalog_visibility_query_args(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
			)
		);
		if ( empty( $product_ids ) ) {
			throw new RuntimeException( 'No published WooCommerce product is available for the settings regression test.' );
		}
		$product_id = (int) $product_ids[0];
		$product    = wc_get_product( $product_id );
		$outside_ids = get_posts(
			negar_theme_catalog_visibility_query_args(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'offset'         => 100,
					'fields'         => 'ids',
					'orderby'        => 'title',
					'order'          => 'ASC',
				)
			)
		);
		$outside_id = ! empty( $outside_ids ) ? (int) $outside_ids[0] : $product_id;
		$outside    = wc_get_product( $outside_id );
		$unrated_id = 0;
		$unrated_ids = get_posts(
			negar_theme_catalog_visibility_query_args(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 30,
					'fields'         => 'ids',
				)
			)
		);
		foreach ( $unrated_ids as $candidate_id ) {
			$candidate = wc_get_product( $candidate_id );
			if ( $candidate && 0.0 === (float) $candidate->get_average_rating() ) {
				$unrated_id = (int) $candidate_id;
				break;
			}
		}
		$unrated = $unrated_id ? wc_get_product( $unrated_id ) : false;
		echo wp_json_encode(
			array(
				'id'        => $product_id,
				'permalink' => get_permalink( $product_id ),
				'label'     => $product ? $product->get_name() : get_the_title( $product_id ),
				'outside_first_hundred' => array(
					'id'        => $outside_id,
					'permalink' => get_permalink( $outside_id ),
					'label'     => $outside ? $outside->get_name() : get_the_title( $outside_id ),
					'is_distinct' => $outside_id !== $product_id,
				),
				'unrated' => array(
					'id'        => $unrated_id,
					'permalink' => $unrated_id ? get_permalink( $unrated_id ) : '',
					'label'     => $unrated ? $unrated->get_name() : '',
				),
			)
		);
		exit( 0 );
	}

	if ( 'image-fixture' === $action ) {
		$image_ids = get_posts(
			array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'post_status'    => 'inherit',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( empty( $image_ids ) ) {
			throw new RuntimeException( 'No WordPress image attachment is available for the media regression test.' );
		}
		$image_id = (int) $image_ids[0];
		echo wp_json_encode( array( 'id' => $image_id, 'url' => wp_get_attachment_image_url( $image_id, 'full' ) ) );
		exit( 0 );
	}

	if ( 'set-colors' === $action ) {
		$primary   = isset( $argv[2] ) ? $argv[2] : '';
		$secondary = isset( $argv[3] ) ? $argv[3] : '';
		$settings  = negar_theme_test_save_group(
			'colors',
			array(
				'colors' => array(
					'primary'   => $primary,
					'secondary' => $secondary,
				),
			)
		);
		echo wp_json_encode( $settings['colors'] );
		exit( 0 );
	}

	if ( 'verify' !== $action ) {
		throw new InvalidArgumentException( 'Unknown action.' );
	}

	$results = array();

	wp_set_current_user( 0 );
	$_POST['negar_theme_settings_tab'] = 'colors';
	$denied = negar_theme_sanitize_settings( array( 'colors' => array( 'primary' => '#123456', 'secondary' => '#654321' ) ) );
	unset( $_POST['negar_theme_settings_tab'] );
	$results['admin_only'] = negar_theme_settings_raw() === $denied;

	negar_theme_test_admin();
	$general = negar_theme_test_save_group( 'general', array( 'branding' => array( 'site_label' => 'برند آزمایشی نگار' ) ) );
	$results['general_saved'] = 'برند آزمایشی نگار' === $general['branding']['site_label'];
	$results['reload'] = 'برند آزمایشی نگار' === negar_theme_settings_raw()['branding']['site_label'];

	$header = negar_theme_test_save_group(
		'header_footer',
		array(
			'header'  => array( 'announcement' => 'اطلاعیهٔ آزمایشی' ),
			'contact' => array( 'phone' => '', 'email' => 'settings-test@example.test', 'address' => '', 'hours' => '', 'map_url' => '' ),
			'footer'  => array( 'about' => '', 'copyright' => '' ),
			'social'  => array( 'instagram' => '', 'telegram' => '', 'whatsapp' => '', 'linkedin' => '' ),
		)
	);
	$results['header_footer_saved'] = 'اطلاعیهٔ آزمایشی' === $header['header']['announcement'];
	$results['groups_preserved'] = 'برند آزمایشی نگار' === $header['branding']['site_label'];

	$texts = negar_theme_test_save_group(
		'texts',
		array(
			'texts' => array(
				'header'  => array( 'mega_sale_title' => 'تخفیف آزمایشی مگامنو' ),
				'home'    => array( 'hero_slide_one_title' => 'عنوان آزمایشی صفحهٔ اصلی' ),
				'footer'  => array( 'quick_links_heading' => '', 'contact_card_description' => 'راهنمای آزمایشی فوتر' ),
				'contact' => array( 'phone_card_label' => 'تلفن آزمایشی نگار' ),
				'about'   => array( 'products_card_title' => 'محصولات آزمایشی نگار' ),
			),
		)
	);
	$results['texts_saved']           = 'عنوان آزمایشی صفحهٔ اصلی' === $texts['texts']['home']['hero_slide_one_title'];
	$results['texts_explicit_empty']  = '' === $texts['texts']['footer']['quick_links_heading'];
	$results['texts_preserve_groups'] = 'اطلاعیهٔ آزمایشی' === $texts['header']['announcement'];
	$results['text_getter']           = 'عنوان آزمایشی صفحهٔ اصلی' === negar_theme_text( 'home', 'hero_slide_one_title' );
	$results['texts_coverage_saved']  = 'تخفیف آزمایشی مگامنو' === negar_theme_text( 'header', 'mega_sale_title' )
		&& 'راهنمای آزمایشی فوتر' === negar_theme_text( 'footer', 'contact_card_description' )
		&& 'تلفن آزمایشی نگار' === negar_theme_text( 'contact', 'phone_card_label' )
		&& 'محصولات آزمایشی نگار' === negar_theme_text( 'about', 'products_card_title' );

	$registry       = negar_theme_home_section_registry();
	$default_layout = negar_theme_home_layout_default();
	$default_ids    = wp_list_pluck( $default_layout, 'id' );
	$results['layout_registry_complete'] = 35 === count( $registry ) && 35 === count( $default_layout ) && $default_ids === array_keys( $registry );
	$results['layout_default_order'] = array( 'story-circles', 'hero-slider', 'category-bubbles' ) === array_slice( $default_ids, 0, 3 )
		&& array( 'blog-posts', 'sec-34-feature-bar-3', 'sec-35-trust-marquee' ) === array_slice( $default_ids, -3 );

	$layout = negar_theme_test_save_group(
		'layout',
		array(
			'layout' => array(
				'order'   => array( 'hero-slider', 'story-circles', 'hero-slider', 'not-a-template-path', 'category-bubbles' ),
				'enabled' => array( 'hero-slider' => '1', 'story-circles' => '1' ),
			),
		)
	);
	$normalized_layout = $layout['layout']['sections'];
	$normalized_ids    = wp_list_pluck( $normalized_layout, 'id' );
	$results['layout_saved'] = array( 'hero-slider', 'story-circles', 'category-bubbles' ) === array_slice( $normalized_ids, 0, 3 )
		&& 35 === count( $normalized_layout ) && ! in_array( 'not-a-template-path', $normalized_ids, true );
	$results['layout_visibility_saved'] = false === $normalized_layout[2]['enabled'] && true === $normalized_layout[0]['enabled'];
	$results['layout_reload'] = $normalized_layout === negar_theme_home_layout();
	$results['layout_preserves_groups'] = 'اطلاعیهٔ آزمایشی' === $layout['header']['announcement']
		&& 'عنوان آزمایشی صفحهٔ اصلی' === $layout['texts']['home']['hero_slide_one_title'];
	$results['layout_new_section_safe'] = 'sec-35-trust-marquee' === $normalized_layout[34]['id'] && true === $normalized_layout[34]['enabled'];
	$results['products_default_queries_preserved'] = ! negar_theme_product_section_is_configured( 'accessories' );
	$malformed_layout = negar_theme_test_save_group(
		'layout',
		array(
			'layout' => array(
				'order'   => array( array( 'not-a-scalar' ), 'story-circles', 'hero-slider' ),
				'enabled' => array( 'story-circles' => array( 'not-a-scalar' ), 'hero-slider' => '1' ),
			),
		)
	);
	$results['layout_malformed_input_safe'] = 35 === count( $malformed_layout['layout']['sections'] )
		&& 'story-circles' === $malformed_layout['layout']['sections'][0]['id']
		&& false === $malformed_layout['layout']['sections'][0]['enabled'];
	$normalized_layout = $malformed_layout['layout']['sections'];

	$product_ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( empty( $product_ids ) ) {
		throw new RuntimeException( 'No published WooCommerce product is available for the settings regression test.' );
	}
	$product_id = (int) $product_ids[0];
	$category_ids = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'fields'     => 'ids',
			'number'      => 1,
		)
	);
	$category_id = ! is_wp_error( $category_ids ) && ! empty( $category_ids ) ? (int) $category_ids[0] : 0;
	$products   = negar_theme_test_save_group(
		'products',
		array(
			'products' => array(
				'sections' => array(
					'accessories' => array(
						'enabled'            => '1',
						'count'              => '2',
						'sort'               => 'title_asc',
						'include_categories' => array( $category_id, 'invalid', array( 'invalid' ) ),
						'exclude_categories' => array( '999999999' ),
						'product_ids'        => array( (string) $product_id, '999999999', array( 'invalid' ) ),
					),
				),
			),
		),
		'accessories'
	);
	$product_config = $products['products']['sections']['accessories'];
	$product_args   = negar_theme_product_section_query_args( 'accessories', array( 'post_type' => 'product', 'post_status' => 'publish' ) );
	$results['products_saved'] = 'custom' === $product_config['mode'] && 2 === $product_config['count'] && 'title_asc' === $product_config['sort'] && array( $product_id ) === $product_config['product_ids'];
	$results['products_query_safe'] = 2 === $product_args['posts_per_page'] && 'title' === $product_args['orderby'] && 'ASC' === $product_args['order'] && array( $product_id ) === $product_args['post__in'];
	$results['products_categories_safe'] = $category_id ? array( $category_id ) === $product_config['include_categories'] && isset( $product_args['tax_query'][0]['terms'] ) && array( $category_id ) === $product_args['tax_query'][0]['terms'] : empty( $product_config['include_categories'] );
	$results['products_preserve_groups'] = $normalized_layout === $products['layout']['sections']
		&& 'عنوان آزمایشی صفحهٔ اصلی' === $products['texts']['home']['hero_slide_one_title'];

	$second_section = negar_theme_test_save_group(
		'products',
		array(
			'products' => array(
				'sections' => array(
					'multi-category-products' => array(
						'enabled' => '1',
						'count'   => '3',
						'sort'    => 'price_desc',
					),
				),
			),
		),
		'multi-category-products'
	);
	$results['products_sections_independent'] = isset( $second_section['products']['sections']['accessories'] )
		&& array( $product_id ) === $second_section['products']['sections']['accessories']['product_ids']
		&& 'price_desc' === $second_section['products']['sections']['multi-category-products']['sort'];

	$visibility_ids = function_exists( 'wc_get_product_visibility_term_ids' ) ? wc_get_product_visibility_term_ids() : array();
	$hidden_term_id = isset( $visibility_ids['exclude-from-catalog'] ) ? absint( $visibility_ids['exclude-from-catalog'] ) : 0;
	$hidden_products = $hidden_term_id ? get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'tax_query'      => array( array( 'taxonomy' => 'product_visibility', 'field' => 'term_id', 'terms' => array( $hidden_term_id ) ) ),
		)
	) : array();
	$visibility_args = negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish' ) );
	$results['products_catalog_visibility_safe'] = $hidden_term_id && isset( $visibility_args['tax_query'][0]['terms'] ) && array( $hidden_term_id ) === $visibility_args['tax_query'][0]['terms'];
	$results['products_hidden_manual_rejected'] = empty( $hidden_products ) || ! in_array( (int) $hidden_products[0], negar_theme_sanitize_product_ids( array( (string) $hidden_products[0] ) ), true );

	$empty_categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'number' => 1, 'orderby' => 'count', 'order' => 'ASC' ) );
	$empty_category = ! is_wp_error( $empty_categories ) && ! empty( $empty_categories ) && 0 === (int) $empty_categories[0]->count ? (int) $empty_categories[0]->term_id : 0;
	$results['products_empty_category_safe'] = ! $empty_category || empty( get_posts( negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 1, 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => array( $empty_category ) ) ) ) ) ) );
	$excluded_safe = true;
	if ( $category_id ) {
		$settings = negar_theme_settings_raw();
		$settings['products']['sections']['accessories'] = array( 'mode' => 'custom', 'count' => 12, 'sort' => 'date_desc', 'include_categories' => array(), 'exclude_categories' => array( $category_id ), 'product_ids' => array() );
		update_option( NEGAR_THEME_SETTINGS_OPTION, $settings );
		$excluded_query = new WP_Query( negar_theme_product_section_query_args( 'accessories', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
		foreach ( wp_list_pluck( $excluded_query->posts, 'ID' ) as $id ) {
			$excluded_safe = $excluded_safe && ! has_term( $category_id, 'product_cat', $id );
		}
	}
	$results['products_excluded_category_safe'] = $excluded_safe;

	$sort_modes = array( 'date_desc', 'date_asc', 'title_asc', 'title_desc', 'total_sales_desc', 'rating_desc', 'price_asc', 'price_desc', 'comment_count_desc', 'random' );
	$sorts_verified = true;
	foreach ( $sort_modes as $sort_mode ) {
		$settings = negar_theme_settings_raw();
		$settings['products']['sections']['accessories'] = array( 'mode' => 'custom', 'count' => 12, 'sort' => $sort_mode, 'include_categories' => array(), 'exclude_categories' => array(), 'product_ids' => array() );
		update_option( NEGAR_THEME_SETTINGS_OPTION, $settings );
		$query = new WP_Query( negar_theme_product_section_query_args( 'accessories', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
		$ids   = wp_list_pluck( $query->posts, 'ID' );
		$sorts_verified = $sorts_verified && ! empty( $ids );
		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );
			$sorts_verified = $sorts_verified && $product && $product->is_visible();
		}
		if ( count( $ids ) > 1 && in_array( $sort_mode, array( 'date_desc', 'date_asc', 'total_sales_desc', 'rating_desc', 'price_asc', 'price_desc', 'comment_count_desc' ), true ) ) {
			$values = array();
			foreach ( $ids as $id ) {
				$product = wc_get_product( $id );
				if ( 'date_desc' === $sort_mode || 'date_asc' === $sort_mode ) {
					$values[] = (int) get_post_time( 'U', true, $id );
				} elseif ( 'total_sales_desc' === $sort_mode ) {
					$values[] = (float) $product->get_total_sales();
				} elseif ( 'rating_desc' === $sort_mode ) {
					$values[] = (float) $product->get_average_rating();
				} elseif ( 'price_asc' === $sort_mode || 'price_desc' === $sort_mode ) {
					$values[] = (float) $product->get_price();
				} else {
					$values[] = (int) get_comments_number( $id );
				}
			}
			$ascending = in_array( $sort_mode, array( 'date_asc', 'price_asc' ), true );
			for ( $index = 1; $index < count( $values ); $index++ ) {
				$sorts_verified = $sorts_verified && ( $ascending ? $values[ $index - 1 ] <= $values[ $index ] : $values[ $index - 1 ] >= $values[ $index ] );
			}
		}
	}
	$results['products_all_sort_modes_return_real_products'] = $sorts_verified;

	$settings = negar_theme_settings_raw();
	$settings['products']['sections']['accessories'] = $product_config;
	update_option( NEGAR_THEME_SETTINGS_OPTION, $settings );
	$legacy_accessories = negar_theme_test_save_group(
		'products',
		array( 'products' => array( 'sections' => array( 'accessories' => array( 'enabled' => '0' ) ) ) ),
		'accessories'
	);
	$results['products_restore_legacy'] = ! isset( $legacy_accessories['products']['sections']['accessories'] )
		&& isset( $legacy_accessories['products']['sections']['multi-category-products'] )
		&& ! negar_theme_product_section_is_configured( 'accessories' );
	$accessories_template = file_get_contents( get_template_directory() . '/template-parts/home/sec-09-accessories.php' );
	$results['products_unrated_not_fabricated'] = false === strpos( $accessories_template, ": '5.0'" );

	$image_ids = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => 'image', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	$image_id  = ! empty( $image_ids ) ? (int) $image_ids[0] : 0;
	$header_before_media = negar_theme_settings_raw()['header']['announcement'];
	$media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '1', 'media_id' => (string) $image_id, 'link' => '/shop/', 'alt' => 'media-test' ),
				'banners-grid-one' => array( 'enabled' => '1', 'media_id' => '999999999', 'link' => 'javascript:alert(1)', 'alt' => 'banner-test', 'title' => 'title-test', 'subtitle' => 'subtitle-test', 'cta_label' => 'cta-test' ),
			),
		)
	);
	update_option( $media_option, $media );
	$hero_media = negar_theme_media_record( 'hero-slide-one' );
	$invalid_banner_media = negar_theme_media_record( 'banners-grid-one' );
	$results['media_valid_attachment_saved'] = $image_id ? $image_id === (int) $hero_media['media_id'] : 0 === (int) $hero_media['media_id'];
	$results['media_invalid_attachment_fallback'] = 0 === (int) $invalid_banner_media['media_id'] && false === strpos( $invalid_banner_media['link'], 'javascript:' ) && false !== strpos( $invalid_banner_media['image_url'], '/kaveh-demo/banner-01.png' );
	$results['media_group_isolated'] = $header_before_media === negar_theme_settings_raw()['header']['announcement'];
	$pack = negar_theme_sanitize_product_pack_settings( array( 'packs' => array( 'gaming-zone' => array( 'fc' => (string) $product_id, 'shadows' => '999999999' ) ) ) );
	update_option( $pack_option, $pack );
	$results['pack_visible_product_saved'] = $product_id === negar_theme_product_pack_product_id( 'gaming-zone', 'fc' ) && 0 === negar_theme_product_pack_product_id( 'gaming-zone', 'shadows' );
	$results['pack_group_isolated'] = $image_id ? $image_id === (int) negar_theme_media_record( 'hero-slide-one' )['media_id'] : true;

	$colors = negar_theme_test_save_group( 'colors', array( 'colors' => array( 'primary' => '#059669', 'secondary' => '#e11d48' ) ) );
	$results['previous_defaults_valid'] = '#059669' === $colors['colors']['primary'] && '#e11d48' === $colors['colors']['secondary'];
	$results['colors_preserve_groups']  = 'اطلاعیهٔ آزمایشی' === $colors['header']['announcement'];
	$results['color_getter']            = '#059669' === negar_theme_color( 'primary' ) && '#e11d48' === negar_theme_color( 'secondary' );

	$empty = negar_theme_test_save_group( 'colors', array( 'colors' => array( 'primary' => '', 'secondary' => '' ) ) );
	$results['explicit_empty'] = '' === $empty['colors']['primary'] && '' === $empty['colors']['secondary'];
	$results['empty_fallback'] = '#e90544' === negar_theme_color( 'primary' ) && '#c40439' === negar_theme_color( 'secondary' );

	update_option( $option, array( 'schema_version' => NEGAR_THEME_SETTINGS_VERSION ) );
	set_theme_mod( 'negar_shop_primary_color', '#059669' );
	set_theme_mod( 'negar_shop_accent_color', '#e11d48' );
	$results['legacy_compatible'] = '#059669' === negar_theme_color( 'primary' ) && '#e11d48' === negar_theme_color( 'secondary' );

	ob_start();
	negar_shop_customizer_css();
	$css = ob_get_clean();
	$results['frontend_tokens'] = false !== strpos( $css, '--color-primary: #059669;' ) && false !== strpos( $css, '--color-secondary: #e11d48;' );

	echo wp_json_encode( $results );
	exit( in_array( false, $results, true ) ? 1 : 0 );
} catch ( Throwable $error ) {
	fwrite( STDERR, $error->getMessage() . PHP_EOL );
	exit( 1 );
}
