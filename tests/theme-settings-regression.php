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
	wp_cache_delete( $media_option, 'options' );
	wp_cache_delete( $pack_option, 'options' );
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

	$verify_snapshot = negar_theme_test_snapshot( $option, $media_option, $pack_option, $mods );
	$results         = array();

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

	$results['media_link_relative_valid'] = '/shop/' === negar_theme_sanitize_media_link( '/shop/' )
		&& '/shop/?a=1#top' === negar_theme_sanitize_media_link( '  /shop/?a=1#top  ' );
	$results['media_link_absolute_valid'] = 'https://example.com/offer' === negar_theme_sanitize_media_link( 'https://example.com/offer' )
		&& 'http://localhost/wp4/shop/' === negar_theme_sanitize_media_link( 'http://localhost/wp4/shop/' );
	$results['media_link_unsafe_rejected'] = '' === negar_theme_sanitize_media_link( 'javascript:alert(1)' )
		&& '' === negar_theme_sanitize_media_link( 'JAVASCRIPT:alert(1)' )
		&& '' === negar_theme_sanitize_media_link( 'data:text/html,<script>alert(1)</script>' )
		&& '' === negar_theme_sanitize_media_link( 'vbscript:msgbox(1)' )
		&& '' === negar_theme_sanitize_media_link( '//evil.example/path' )
		&& '' === negar_theme_sanitize_media_link( '/\\evil.example' )
		&& '' === negar_theme_sanitize_media_link( 'not-a-url' )
		&& '' === negar_theme_sanitize_media_link( "java\nscript:alert(1)" );

	$render_hero = static function() {
		ob_start();
		get_template_part( 'template-parts/home/hero-slider' );
		return ob_get_clean();
	};

	delete_option( $media_option );
	$hero_default_html = $render_hero();
	$results['hero_ssr_default_renders_two_slides'] = false !== strpos( $hero_default_html, 'data-negar-hero-media="one"' )
		&& false !== strpos( $hero_default_html, 'data-negar-hero-media="two"' )
		&& false !== strpos( $hero_default_html, '/kaveh-demo/landing-01-300x261-1.png' )
		&& false !== strpos( $hero_default_html, 'data-landing-nk-carousel-loop="1"' )
		&& false === strpos( $hero_default_html, 'data-negar-media-image=' );

	$image_ids = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => 'image', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	$image_id  = ! empty( $image_ids ) ? (int) $image_ids[0] : 0;
	$expected_image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
	$header_before_media = negar_theme_settings_raw()['header']['announcement'];
	$media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '1', 'media_id' => (string) $image_id, 'link' => '/shop/?hero-ssr=1', 'alt' => 'Hero SSR Alt' ),
				'hero-slide-two' => array( 'enabled' => '1', 'media_id' => '999999999', 'link' => 'https://example.com/hero-two', 'alt' => 'Hero Two Fallback Alt' ),
				'banners-grid-one' => array( 'enabled' => '1', 'media_id' => '999999999', 'link' => 'javascript:alert(1)', 'alt' => 'banner-test', 'title' => 'title-test', 'subtitle' => 'subtitle-test', 'cta_label' => 'cta-test' ),
			),
		)
	);
	update_option( $media_option, $media );
	$hero_media = negar_theme_media_record( 'hero-slide-one' );
	$invalid_banner_media = negar_theme_media_record( 'banners-grid-one' );
	$results['media_valid_attachment_saved'] = $image_id ? $image_id === (int) $hero_media['media_id'] : 0 === (int) $hero_media['media_id'];
	$results['media_invalid_attachment_fallback'] = 0 === (int) $invalid_banner_media['media_id'] && '' === $invalid_banner_media['link'] && false !== strpos( $invalid_banner_media['image_url'], '/kaveh-demo/banner-01.png' );
	$hero_override_html = $render_hero();
	$results['hero_ssr_renders_override_without_js'] = ( ! $expected_image_url || false !== strpos( $hero_override_html, esc_url( $expected_image_url ) ) )
		&& false !== strpos( $hero_override_html, 'alt="Hero SSR Alt"' )
		&& false !== strpos( $hero_override_html, 'href="/shop/?hero-ssr=1"' )
		&& false !== strpos( $hero_override_html, 'alt="Hero Two Fallback Alt"' )
		&& false !== strpos( $hero_override_html, '/kaveh-demo/landing-01-300x261-1.png' );

	$only_one_media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '1', 'media_id' => (string) $image_id, 'link' => '/shop/', 'alt' => 'Only One' ),
				'hero-slide-two' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Hidden Two' ),
			),
		)
	);
	update_option( $media_option, $only_one_media );
	$hero_only_one_html = $render_hero();
	$results['hero_ssr_only_slide_one'] = false !== strpos( $hero_only_one_html, 'data-negar-hero-media="one"' )
		&& false === strpos( $hero_only_one_html, 'data-negar-hero-media="two"' )
		&& false !== strpos( $hero_only_one_html, 'data-landing-nk-carousel-loop="0"' )
		&& false === strpos( $hero_only_one_html, 'swiper-button-next' );

	$only_two_media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Hidden One' ),
				'hero-slide-two' => array( 'enabled' => '1', 'media_id' => (string) $image_id, 'link' => '/shop/?slide=2', 'alt' => 'Only Two' ),
			),
		)
	);
	update_option( $media_option, $only_two_media );
	$hero_only_two_html = $render_hero();
	$results['hero_ssr_only_slide_two'] = false === strpos( $hero_only_two_html, 'data-negar-hero-media="one"' )
		&& false !== strpos( $hero_only_two_html, 'data-negar-hero-media="two"' )
		&& false !== strpos( $hero_only_two_html, 'href="/shop/?slide=2"' )
		&& false !== strpos( $hero_only_two_html, 'data-landing-nk-carousel-loop="0"' )
		&& false === strpos( $hero_only_two_html, 'swiper-button-next' );

	$no_slides_media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
				'hero-slide-two' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
			),
		)
	);
	update_option( $media_option, $no_slides_media );
	$hero_no_slides_html = $render_hero();
	$results['hero_ssr_both_disabled_preserves_deal'] = false !== strpos( $hero_no_slides_html, 'landing-nk--no-slides' )
		&& false === strpos( $hero_no_slides_html, 'landing-nk__carousel' )
		&& false === strpos( $hero_no_slides_html, 'data-negar-hero-media=' )
		&& false !== strpos( $hero_no_slides_html, 'landing-nk__products' );

	$cleared_media = negar_theme_sanitize_media_settings(
		array(
			'records' => array(
				'hero-slide-one' => array( 'enabled' => '1', 'media_id' => '', 'link' => '/shop/', 'alt' => 'media-test' ),
			),
		)
	);
	update_option( $media_option, $cleared_media );
	$cleared_hero_media = negar_theme_media_record( 'hero-slide-one' );
	$results['media_cleared_restores_fallback'] = 0 === (int) $cleared_hero_media['media_id']
		&& $cleared_hero_media['fallback_url'] === $cleared_hero_media['image_url']
		&& '/shop/' === $cleared_hero_media['link'];

	$banner_templates = array(
		'template-parts/home/banners-grid',
		'template-parts/home/sec-08-banner-wide',
		'template-parts/home/sec-10-banners-2col',
		'template-parts/home/sec-12-banners-compact',
		'template-parts/home/sec-18-banners-2col',
		'template-parts/home/sec-27-banners-compact-2',
		'template-parts/home/sec-29-banners-2col',
		'template-parts/home/sec-32-banners-2col',
	);
	$render_banners = static function( $templates = null ) use ( $banner_templates ) {
		$targets = null === $templates ? $banner_templates : (array) $templates;
		ob_start();
		foreach ( $targets as $template_slug ) {
			get_template_part( $template_slug );
		}
		return ob_get_clean();
	};

	delete_option( $media_option );
	$default_banners_html = $render_banners();
	$expected_banner_ids  = array(
		'banners-grid-one',
		'banners-grid-two',
		'wide-banner-one',
		'banners-10-one',
		'banners-10-two',
		'compact-12-one',
		'compact-12-two',
		'banners-18-one',
		'banners-18-two',
		'compact-27-one',
		'compact-27-two',
		'banners-29-one',
		'banners-29-two',
		'banners-32-one',
		'banners-32-two',
	);
	$all_15_rendered = true;
	foreach ( $expected_banner_ids as $banner_id ) {
		if ( false === strpos( $default_banners_html, 'data-negar-banner-record="' . $banner_id . '"' ) ) {
			$all_15_rendered = false;
		}
	}
	$home_js_contents = file_get_contents( get_template_directory() . '/assets/js/home.js' );
	$results['banners_ssr_default_renders_all_15_without_js_attrs'] = $all_15_rendered
		&& false !== strpos( $default_banners_html, '/kaveh-demo/banner-01.png' )
		&& false !== strpos( $default_banners_html, '/kaveh-demo/banner-02-1.png' )
		&& false !== strpos( $default_banners_html, '/kaveh-demo/banner-03.png' )
		&& false !== strpos( $default_banners_html, '/kaveh-demo/banner-04.png' )
		&& false !== strpos( $default_banners_html, '/kaveh-demo/banner-05.png' )
		&& false === strpos( $default_banners_html, 'data-negar-media-image=' )
		&& false === strpos( $default_banners_html, 'data-negar-media-enabled=' )
		&& false === strpos( $home_js_contents, 'applyBannerMediaRecords' );

	$build_media_payload = static function( $overrides ) {
		$records = array();
		foreach ( negar_theme_media_registry() as $id => $def ) {
			$row = array( 'enabled' => ! empty( $def['enabled'] ) ? '1' : '0' );
			foreach ( $def['fields'] as $field ) {
				if ( 'enabled' === $field || 'media_id' === $field ) {
					continue;
				}
				if ( isset( $def[ $field ] ) ) {
					$row[ $field ] = (string) $def[ $field ];
				}
			}
			$records[ $id ] = isset( $overrides[ $id ] ) ? array_merge( $row, $overrides[ $id ] ) : $row;
		}
		return array( 'records' => $records );
	};

	$banner_override_input = $build_media_payload(
		array(
			'banners-grid-one' => array(
				'enabled'   => '1',
				'media_id'  => (string) $image_id,
				'link'      => '/shop/?banner-ssr=1',
				'alt'       => 'Banner SSR One Alt',
				'title'     => 'Banner SSR Title One',
				'subtitle'  => 'Banner SSR Subtitle One',
				'cta_label' => 'Banner SSR CTA One',
			),
			'banners-grid-two' => array(
				'enabled'   => '1',
				'media_id'  => '999999999',
				'link'      => 'javascript:alert(1)',
				'alt'       => '',
				'title'     => '',
				'subtitle'  => '',
				'cta_label' => '',
			),
			'wide-banner-one'  => array(
				'enabled'  => '1',
				'media_id' => (string) $image_id,
				'link'     => 'https://example.com/wide-ssr',
				'alt'      => 'Wide SSR Alt',
			),
			'compact-12-one'   => array(
				'enabled'  => '1',
				'media_id' => '999999999',
				'link'     => '/shop/?compact-ssr=12',
				'alt'      => 'Compact 12 Alt',
			),
			'compact-27-two'   => array(
				'enabled'  => '1',
				'media_id' => (string) $image_id,
				'link'     => 'https://example.com/compact-27',
				'alt'      => 'Compact 27 Alt',
			),
		)
	);
	update_option( $media_option, negar_theme_sanitize_media_settings( $banner_override_input ) );
	$banners_grid_html   = $render_banners( 'template-parts/home/banners-grid' );
	$wide_banner_html    = $render_banners( 'template-parts/home/sec-08-banner-wide' );
	$compact_12_html     = $render_banners( 'template-parts/home/sec-12-banners-compact' );
	$compact_27_html     = $render_banners( 'template-parts/home/sec-27-banners-compact-2' );
	$banners_10_html     = $render_banners( 'template-parts/home/sec-10-banners-2col' );
	$shop_permalink      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$results['banners_ssr_overrides_and_fallbacks'] = ( ! $expected_image_url || false !== strpos( $banners_grid_html, esc_url( $expected_image_url ) ) )
		&& false !== strpos( $banners_grid_html, 'href="/shop/?banner-ssr=1"' )
		&& false !== strpos( $banners_grid_html, 'alt="Banner SSR One Alt"' )
		&& false !== strpos( $banners_grid_html, 'Banner SSR Title One' )
		&& false !== strpos( $banners_grid_html, 'Banner SSR Subtitle One' )
		&& false !== strpos( $banners_grid_html, 'aria-label="Banner SSR CTA One"' )
		&& false !== strpos( $banners_grid_html, '/kaveh-demo/banner-02-1.png' )
		&& false !== strpos( $banners_grid_html, 'href="' . esc_url( $shop_permalink ) . '"' )
		&& false === strpos( $banners_grid_html, 'javascript:alert' )
		&& false !== strpos( $banners_grid_html, '<div class="title f-iran-yekan fw-bold"></div>' )
		&& false !== strpos( $banners_grid_html, '<div class="sub-title f-iran-yekan fw-normal"></div>' )
		&& false === strpos( $banners_grid_html, 'محل قرار گیری بنر' )
		&& false === strpos( $banners_grid_html, 'همین الان با تخفیف خرید کنید' )
		&& false !== strpos( $wide_banner_html, 'href="https://example.com/wide-ssr"' )
		&& false !== strpos( $wide_banner_html, 'alt="Wide SSR Alt"' )
		&& false === strpos( $wide_banner_html, 'class="title' )
		&& false !== strpos( $compact_12_html, 'href="/shop/?compact-ssr=12"' )
		&& false !== strpos( $compact_12_html, '/kaveh-demo/banner-04.png' )
		&& false !== strpos( $compact_12_html, 'alt="Compact 12 Alt"' )
		&& false === strpos( $compact_12_html, 'class="title' )
		&& false !== strpos( $compact_27_html, 'href="https://example.com/compact-27"' )
		&& false !== strpos( $compact_27_html, 'alt="Compact 27 Alt"' )
		&& false === strpos( $compact_27_html, 'class="title' )
		&& false !== strpos( $banners_10_html, 'محل قرار گیری بنر' );

	// Test visibility states: single-column collapse + empty container omission.
	update_option(
		$media_option,
		negar_theme_sanitize_media_settings(
			array(
				'records' => array(
					'wide-banner-one'  => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Disabled Wide' ),
					'banners-grid-one' => array( 'enabled' => '1', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Only Grid One' ),
					'banners-grid-two' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Disabled Grid Two' ),
					'compact-12-one'   => array( 'enabled' => '1', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Only Compact One' ),
					'compact-12-two'   => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Disabled Compact Two' ),
				),
			)
		)
	);
	$wide_disabled_html     = $render_banners( 'template-parts/home/sec-08-banner-wide' );
	$grid_only_one_html     = $render_banners( 'template-parts/home/banners-grid' );
	$compact_only_one_html  = $render_banners( 'template-parts/home/sec-12-banners-compact' );
	$visibility_one_ok = '' === trim( $wide_disabled_html )
		&& false !== strpos( $grid_only_one_html, 'data-negar-banner-record="banners-grid-one"' )
		&& false === strpos( $grid_only_one_html, 'data-negar-banner-record="banners-grid-two"' )
		&& false === strpos( $grid_only_one_html, 'banner-02-1.png' )
		&& false !== strpos( $grid_only_one_html, 'negar-banners-2col--single' )
		&& false === strpos( $grid_only_one_html, 'md:grid-cols-2' )
		&& false !== strpos( $compact_only_one_html, 'data-negar-banner-record="compact-12-one"' )
		&& false === strpos( $compact_only_one_html, 'data-negar-banner-record="compact-12-two"' )
		&& false === strpos( $compact_only_one_html, 'banner-05.png' )
		&& false !== strpos( $compact_only_one_html, 'negar-dual-banners-asymmetric--single' );

	update_option(
		$media_option,
		negar_theme_sanitize_media_settings(
			array(
				'records' => array(
					'banners-grid-one' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Disabled Grid One' ),
					'banners-grid-two' => array( 'enabled' => '1', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Only Grid Two' ),
					'compact-12-one'   => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Disabled Compact One' ),
					'compact-12-two'   => array( 'enabled' => '1', 'media_id' => '0', 'link' => '/shop/', 'alt' => 'Only Compact Two' ),
				),
			)
		)
	);
	$grid_only_two_html    = $render_banners( 'template-parts/home/banners-grid' );
	$compact_only_two_html = $render_banners( 'template-parts/home/sec-12-banners-compact' );
	$visibility_two_ok = false === strpos( $grid_only_two_html, 'data-negar-banner-record="banners-grid-one"' )
		&& false === strpos( $grid_only_two_html, 'banner-01.png' )
		&& false !== strpos( $grid_only_two_html, 'data-negar-banner-record="banners-grid-two"' )
		&& false !== strpos( $grid_only_two_html, 'negar-banners-2col--single' )
		&& false === strpos( $compact_only_two_html, 'data-negar-banner-record="compact-12-one"' )
		&& false === strpos( $compact_only_two_html, 'banner-04.png' )
		&& false !== strpos( $compact_only_two_html, 'data-negar-banner-record="compact-12-two"' )
		&& false !== strpos( $compact_only_two_html, 'negar-dual-banners-asymmetric--single' );

	update_option(
		$media_option,
		negar_theme_sanitize_media_settings(
			array(
				'records' => array(
					'banners-grid-one' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
					'banners-grid-two' => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
					'compact-12-one'   => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
					'compact-12-two'   => array( 'enabled' => '0', 'media_id' => '0', 'link' => '/shop/', 'alt' => '' ),
				),
			)
		)
	);
	$grid_none_html    = $render_banners( 'template-parts/home/banners-grid' );
	$compact_none_html = $render_banners( 'template-parts/home/sec-12-banners-compact' );
	$results['banners_ssr_visibility_states'] = $visibility_one_ok
		&& $visibility_two_ok
		&& '' === trim( $grid_none_html )
		&& '' === trim( $compact_none_html );

	update_option( $media_option, $media );
	$results['media_group_isolated'] = $header_before_media === negar_theme_settings_raw()['header']['announcement'];
	$pack = negar_theme_sanitize_product_pack_settings( array( 'packs' => array( 'gaming-zone' => array( 'fc' => (string) $product_id, 'mirage' => (string) $product_id, 'shadows' => '999999999' ) ) ) );
	update_option( $pack_option, $pack );
	$results['pack_visible_product_saved'] = $product_id === negar_theme_product_pack_product_id( 'gaming-zone', 'fc' )
		&& $product_id === negar_theme_product_pack_product_id( 'gaming-zone', 'mirage' )
		&& 0 === negar_theme_product_pack_product_id( 'gaming-zone', 'shadows' );
	$results['pack_group_isolated'] = $image_id ? $image_id === (int) negar_theme_media_record( 'hero-slide-one' )['media_id'] : true;

	$gz_file = file_get_contents( get_template_directory() . '/template-parts/home/sec-25-gaming-zone.php' );
	$results['gaming_zone_unbounded_query_eliminated'] = false === strpos( $gz_file, 'wc_get_products' ) && false === strpos( $gz_file, 'limit' );

	$fallback_called = false;
	$check_where = static function ( $where ) use ( &$fallback_called ) {
		$fallback_called = true;
		return $where;
	};
	add_filter( 'posts_where', $check_where );
	$resolved_manual = negar_theme_gaming_zone_product( 'fc', array( 'EA Sports FC' ) );
	remove_filter( 'posts_where', $check_where );
	$results['gaming_zone_manual_resolved_without_fallback'] = $resolved_manual && $resolved_manual->get_id() === $product_id && ! $fallback_called;

	negar_theme_find_product_by_title_needles( array(), true );
	$fallback_search = negar_theme_gaming_zone_product( 'shadows', array( 'EA Sports FC' ) );
	$results['gaming_zone_unassigned_slot_uses_fallback'] = $fallback_search && false !== stripos( $fallback_search->get_name(), 'EA Sports FC' );

	$fallback_none = negar_theme_gaming_zone_product( 'shadows', array( 'NonExistentNeedleXYZ987' ) );
	$results['gaming_zone_missing_needle_returns_false'] = false === $fallback_none;

	ob_start();
	get_template_part( 'template-parts/home/sec-25-gaming-zone' );
	$gz_html = ob_get_clean();
	$results['gaming_zone_rendered_hotspots'] = false !== strpos( $gz_html, 'ppnk-hs-61f16945-0' )
		&& false !== strpos( $gz_html, 'ppnk-hs-61f16945-1' )
		&& false !== strpos( $gz_html, 'ppnk-hs-61f16945-2' )
		&& false !== strpos( $gz_html, 'ppnk-hs-61f16945-3' )
		&& false !== strpos( $gz_html, 'is-empty' );

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

	negar_theme_test_restore( $option, $media_option, $pack_option, $mods, $verify_snapshot );

	echo wp_json_encode( $results );
	exit( in_array( false, $results, true ) ? 1 : 0 );
} catch ( Throwable $error ) {
	fwrite( STDERR, $error->getMessage() . PHP_EOL );
	exit( 1 );
}
