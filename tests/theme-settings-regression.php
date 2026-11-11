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

function negar_theme_test_snapshot( $option, $mods ) {
	$settings = get_option( $option, null );
	$theme_mods = get_option( $mods, null );

	return array(
		'option_exists' => null !== $settings,
		'option'        => $settings,
		'mods_exists'   => null !== $theme_mods,
		'mods'          => $theme_mods,
	);
}

function negar_theme_test_restore( $option, $mods, $snapshot ) {
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

	wp_cache_delete( $option, 'options' );
	wp_cache_delete( $mods, 'options' );
}

function negar_theme_test_save_group( $tab, $input ) {
	$_POST['negar_theme_settings_tab'] = $tab;
	$settings                          = negar_theme_sanitize_settings( $input );
	update_option( NEGAR_THEME_SETTINGS_OPTION, $settings );
	unset( $_POST['negar_theme_settings_tab'] );

	return $settings;
}

try {
	if ( 'snapshot' === $action ) {
		echo base64_encode( wp_json_encode( negar_theme_test_snapshot( $option, $mods ) ) );
		exit( 0 );
	}

	if ( 'restore' === $action ) {
		$snapshot = isset( $argv[2] ) ? json_decode( base64_decode( $argv[2] ), true ) : null;
		if ( ! is_array( $snapshot ) ) {
			throw new InvalidArgumentException( 'Invalid settings snapshot.' );
		}
		negar_theme_test_restore( $option, $mods, $snapshot );
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
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( empty( $product_ids ) ) {
			throw new RuntimeException( 'No published WooCommerce product is available for the settings regression test.' );
		}
		$product_id = (int) $product_ids[0];
		echo wp_json_encode(
			array(
				'id'        => $product_id,
				'permalink' => get_permalink( $product_id ),
			)
		);
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
						'count'              => '2',
						'sort'               => 'title_asc',
						'include_categories' => array( $category_id, 'invalid', array( 'invalid' ) ),
						'exclude_categories' => array( '999999999' ),
						'product_ids'        => array( (string) $product_id, '999999999', array( 'invalid' ) ),
					),
				),
			),
		)
	);
	$product_config = $products['products']['sections']['accessories'];
	$product_args   = negar_theme_product_section_query_args( 'accessories', array( 'post_type' => 'product', 'post_status' => 'publish' ) );
	$results['products_saved'] = 2 === $product_config['count'] && 'title_asc' === $product_config['sort'] && array( $product_id ) === $product_config['product_ids'];
	$results['products_query_safe'] = 2 === $product_args['posts_per_page'] && 'title' === $product_args['orderby'] && 'ASC' === $product_args['order'] && array( $product_id ) === $product_args['post__in'];
	$results['products_categories_safe'] = $category_id ? array( $category_id ) === $product_config['include_categories'] && isset( $product_args['tax_query'][0]['terms'] ) && array( $category_id ) === $product_args['tax_query'][0]['terms'] : empty( $product_config['include_categories'] );
	$results['products_preserve_groups'] = $normalized_layout === $products['layout']['sections']
		&& 'عنوان آزمایشی صفحهٔ اصلی' === $products['texts']['home']['hero_slide_one_title'];

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
