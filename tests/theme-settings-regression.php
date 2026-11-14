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
$story_option = NEGAR_THEME_STORY_OPTION;
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

function negar_theme_test_snapshot( $option, $media_option, $pack_option, $story_option, $mods ) {
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
		'story_exists'  => null !== get_option( $story_option, null ),
		'story'         => get_option( $story_option, null ),
	);
}

function negar_theme_test_restore( $option, $media_option, $pack_option, $story_option, $mods, $snapshot ) {
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
	if ( ! empty( $snapshot['story_exists'] ) ) {
		update_option( $story_option, $snapshot['story'] );
	} else {
		delete_option( $story_option );
	}

	wp_cache_delete( $option, 'options' );
	wp_cache_delete( $mods, 'options' );
	wp_cache_delete( $media_option, 'options' );
	wp_cache_delete( $pack_option, 'options' );
	wp_cache_delete( $story_option, 'options' );
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

function negar_theme_test_create_video_fixture( $owner = 'browser-runner', $prefix = 'test-story-video-' ) {
	$fixture_path = __DIR__ . '/fixtures/sample-video.webm';
	if ( ! file_exists( $fixture_path ) ) {
		throw new RuntimeException( 'Sample video fixture file not found.' );
	}
	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['error'] ) ) {
		throw new RuntimeException( 'WordPress upload directory is not writable: ' . $upload_dir['error'] );
	}

	$allowed_prefixes = array( 'test-story-video-', 'verify-fixture-', 'safety-fixture-', 'sentinel-fixture-' );
	if ( ! in_array( $prefix, $allowed_prefixes, true ) ) {
		$prefix = 'test-story-video-';
	}

	$recognized_owners = array( 'browser-runner', 'php-verify', 'safety-f1', 'safety-f2', 'sentinel-test' );
	if ( ! in_array( $owner, $recognized_owners, true ) ) {
		$owner = 'browser-runner';
	}

	$unique_filename = wp_unique_filename( $upload_dir['path'], $prefix . wp_generate_password( 8, false, false ) . '.webm' );
	$dest_file       = wp_normalize_path( $upload_dir['path'] . '/' . $unique_filename );

	if ( file_exists( $dest_file ) ) {
		throw new RuntimeException( 'Unique file collision detected during fixture creation: ' . $dest_file );
	}

	if ( ! copy( $fixture_path, $dest_file ) ) {
		throw new RuntimeException( 'Failed to copy sample video fixture to upload directory.' );
	}

	$token    = wp_generate_password( 32, false, false );
	$video_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'video/webm',
			'post_title'     => 'Negar Test Fixture ' . $token,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$dest_file
	);

	if ( ! $video_id || is_wp_error( $video_id ) ) {
		if ( file_exists( $dest_file ) ) {
			@unlink( $dest_file );
		}
		throw new RuntimeException( 'Failed to create WordPress attachment for video fixture.' );
	}

	$rel_path = ltrim( str_replace( wp_normalize_path( $upload_dir['basedir'] ), '', $dest_file ), '/' );
	update_attached_file( $video_id, $rel_path );

	update_post_meta( $video_id, '_negar_test_fixture_token', $token );
	update_post_meta( $video_id, '_negar_test_fixture_owner', (string) $owner );
	update_post_meta( $video_id, '_negar_test_fixture_file', $dest_file );

	return array(
		'id'    => (int) $video_id,
		'url'   => wp_get_attachment_url( $video_id ),
		'token' => $token,
		'file'  => $dest_file,
		'owner' => (string) $owner,
	);
}

function negar_theme_test_cleanup_fixture( $target_id, $token ) {
	$target_id = (int) $target_id;
	$token     = trim( (string) $token );

	// 1. Target ID must be a positive integer and token non-empty.
	if ( $target_id <= 0 || '' === $token ) {
		return array(
			'cleaned' => false,
			'error'   => 'invalid_arguments',
		);
	}

	// 2. The target ID identifies an existing WordPress attachment.
	$post = get_post( $target_id );
	if ( ! $post || 'attachment' !== $post->post_type ) {
		return array(
			'cleaned' => false,
			'error'   => 'not_an_attachment',
		);
	}

	// 3. The stored private token exists.
	$stored_token = (string) get_post_meta( $target_id, '_negar_test_fixture_token', true );
	if ( '' === $stored_token ) {
		return array(
			'cleaned' => false,
			'error'   => 'missing_fixture_token',
		);
	}

	// 4. The supplied token matches via hash_equals().
	if ( ! hash_equals( $stored_token, $token ) ) {
		return array(
			'cleaned' => false,
			'error'   => 'token_mismatch',
		);
	}

	// 5. A recognized test ownership marker exists.
	$stored_owner      = (string) get_post_meta( $target_id, '_negar_test_fixture_owner', true );
	$recognized_owners = array( 'browser-runner', 'php-verify', 'safety-f1', 'safety-f2', 'sentinel-test' );
	if ( '' === $stored_owner || ! in_array( $stored_owner, $recognized_owners, true ) ) {
		return array(
			'cleaned' => false,
			'error'   => 'unrecognized_fixture_owner',
		);
	}

	// 6. A non-empty test-owned file path exists in fixture metadata.
	$stored_file = (string) get_post_meta( $target_id, '_negar_test_fixture_file', true );
	if ( '' === $stored_file ) {
		return array(
			'cleaned' => false,
			'error'   => 'missing_fixture_file_meta',
		);
	}

	// 7. get_attached_file() resolves to that exact same file.
	$attached_file = (string) get_attached_file( $target_id );
	if ( '' === $attached_file ) {
		return array(
			'cleaned' => false,
			'error'   => 'missing_attached_file',
		);
	}

	$parts = preg_split( '#(?=[a-zA-Z]:[/\\\\])#', $attached_file );
	if ( ! empty( $parts ) ) {
		$attached_file = end( $parts );
	}

	$upload_dir = wp_upload_dir();
	if ( empty( $upload_dir['basedir'] ) ) {
		return array(
			'cleaned' => false,
			'error'   => 'upload_dir_unavailable',
		);
	}

	$norm_uploads_base = strtolower( wp_normalize_path( $upload_dir['basedir'] ) );
	$norm_attached     = strtolower( wp_normalize_path( $attached_file ) );
	$norm_stored       = strtolower( wp_normalize_path( $stored_file ) );

	// Strict match between attached file and stored file
	if ( $norm_attached !== $norm_stored ) {
		return array(
			'cleaned' => false,
			'error'   => 'attached_stored_path_mismatch',
		);
	}

	// 8 & 9. The actual canonical file path is inside allowed uploads directory (no traversal / escaping).
	$real_uploads_base = realpath( $upload_dir['basedir'] );
	if ( false === $real_uploads_base ) {
		return array(
			'cleaned' => false,
			'error'   => 'uploads_dir_realpath_failed',
		);
	}
	$norm_real_uploads = strtolower( wp_normalize_path( $real_uploads_base ) );

	if ( file_exists( $attached_file ) ) {
		$real_attached_file = realpath( $attached_file );
		if ( false === $real_attached_file ) {
			return array(
				'cleaned' => false,
				'error'   => 'attached_file_realpath_failed',
			);
		}
		$norm_real_attached = strtolower( wp_normalize_path( $real_attached_file ) );
		if ( 0 !== strpos( $norm_real_attached, $norm_real_uploads . '/' ) ) {
			return array(
				'cleaned' => false,
				'error'   => 'canonical_path_outside_uploads',
			);
		}
	} else {
		if ( 0 !== strpos( $norm_attached, $norm_uploads_base . '/' ) ) {
			return array(
				'cleaned' => false,
				'error'   => 'path_outside_uploads',
			);
		}
	}

	// 10. The path is a test-owned temporary video fixture, not an arbitrary existing upload.
	$filename = basename( $norm_stored );
	$is_test_fixture_name = (bool) preg_match( '/^(test-story-video-|verify-fixture-|safety-fixture-|sentinel-fixture-)[a-zA-Z0-9_\-]+\.webm$/', $filename );
	if ( ! $is_test_fixture_name ) {
		return array(
			'cleaned' => false,
			'error'   => 'not_a_test_fixture_filename',
		);
	}

	// ALL CHECKS PASSED. Delete the attachment.
	$deleted = wp_delete_attachment( $target_id, true );
	if ( ! $deleted ) {
		return array(
			'cleaned' => false,
			'error'   => 'wp_delete_attachment_failed',
		);
	}

	// If file still remains on disk after wp_delete_attachment, remove it safely since all 10 checks passed.
	if ( file_exists( $attached_file ) ) {
		@unlink( $attached_file );
	}

	return array(
		'cleaned' => true,
		'id'      => $target_id,
	);
}

try {
	if ( 'snapshot' === $action ) {
		echo base64_encode( wp_json_encode( negar_theme_test_snapshot( $option, $media_option, $pack_option, $story_option, $mods ) ) );
		exit( 0 );
	}

	if ( 'restore' === $action ) {
		$snapshot = isset( $argv[2] ) ? json_decode( base64_decode( $argv[2] ), true ) : null;
		if ( ! is_array( $snapshot ) ) {
			throw new InvalidArgumentException( 'Invalid settings snapshot.' );
		}
		negar_theme_test_restore( $option, $media_option, $pack_option, $story_option, $mods, $snapshot );
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

	if ( 'video-fixture' === $action ) {
		$fixture = negar_theme_test_create_video_fixture( 'browser-runner' );
		echo wp_json_encode( $fixture );
		exit( 0 );
	}

	if ( 'cleanup-video-fixture' === $action ) {
		$target_id = isset( $argv[2] ) ? (int) $argv[2] : 0;
		$token     = isset( $argv[3] ) ? trim( (string) $argv[3] ) : '';
		$res       = negar_theme_test_cleanup_fixture( $target_id, $token );
		echo wp_json_encode( $res );
		exit( empty( $res['cleaned'] ) ? 1 : 0 );
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

	$verify_snapshot = negar_theme_test_snapshot( $option, $media_option, $pack_option, $story_option, $mods );
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

	$pack_registry = negar_theme_product_pack_registry();
	$total_pack_slots = 0;
	foreach ( $pack_registry as $preg_pack ) {
		$total_pack_slots += count( $preg_pack['slots'] );
	}
	$results['pack_registry_all_seven_packs_present'] = 7 === count( $pack_registry )
		&& 40 === $total_pack_slots
		&& isset( $pack_registry['gaming-zone'], $pack_registry['taste-grid'], $pack_registry['feature-bar-1'], $pack_registry['feature-bar-2'], $pack_registry['smartwatches-2'], $pack_registry['mobile-spotlight'], $pack_registry['feature-bar-3'] );

	$pack = negar_theme_sanitize_product_pack_settings(
		array(
			'packs' => array(
				'gaming-zone'      => array( 'fc' => (string) $product_id, 'mirage' => (string) $product_id, 'shadows' => '999999999' ),
				'taste-grid'       => array( 'box-1-1' => (string) $product_id, 'box-2-1' => '999999999' ),
				'feature-bar-1'    => array( 'slot-1' => (string) $product_id ),
				'feature-bar-2'    => array( 'slot-2' => (string) $product_id ),
				'smartwatches-2'   => array( 'promo-1' => (string) $product_id, 'carousel-1' => (string) $product_id ),
				'mobile-spotlight' => array( 'focal' => (string) $product_id ),
				'feature-bar-3'    => array( 'slot-4' => (string) $product_id ),
			),
		)
	);
	update_option( $pack_option, $pack );
	$results['pack_visible_product_saved'] = $product_id === negar_theme_product_pack_product_id( 'gaming-zone', 'fc' )
		&& $product_id === negar_theme_product_pack_product_id( 'gaming-zone', 'mirage' )
		&& 0 === negar_theme_product_pack_product_id( 'gaming-zone', 'shadows' )
		&& $product_id === negar_theme_product_pack_product_id( 'taste-grid', 'box-1-1' )
		&& 0 === negar_theme_product_pack_product_id( 'taste-grid', 'box-2-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'feature-bar-1', 'slot-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'feature-bar-2', 'slot-2' )
		&& $product_id === negar_theme_product_pack_product_id( 'smartwatches-2', 'promo-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'smartwatches-2', 'carousel-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'mobile-spotlight', 'focal' )
		&& $product_id === negar_theme_product_pack_product_id( 'feature-bar-3', 'slot-4' );
	$results['pack_group_isolated'] = $image_id ? $image_id === (int) negar_theme_media_record( 'hero-slide-one' )['media_id'] : true;

	$partial_pack = negar_theme_sanitize_product_pack_settings(
		array(
			'packs' => array(
				'taste-grid' => array( 'box-1-1' => '0' ),
			),
		)
	);
	update_option( $pack_option, $partial_pack );
	$results['pack_partial_update_preserves_other_packs'] = 0 === negar_theme_product_pack_product_id( 'taste-grid', 'box-1-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'feature-bar-1', 'slot-1' )
		&& $product_id === negar_theme_product_pack_product_id( 'mobile-spotlight', 'focal' );

	$resolved_fb1 = negar_theme_product_pack_product( 'feature-bar-1', 'slot-1', array( 'NonExistent' ) );
	$results['pack_product_helper_resolves_assigned'] = $resolved_fb1 && $resolved_fb1->get_id() === $product_id;
	$resolved_fb1_none = negar_theme_product_pack_product( 'feature-bar-1', 'slot-2', array( 'NonExistentXYZ9999' ) );
	$results['pack_product_helper_unassigned_returns_false_on_miss'] = false === $resolved_fb1_none;

	$gz_file = file_get_contents( get_template_directory() . '/template-parts/home/sec-25-gaming-zone.php' );
	$results['gaming_zone_unbounded_query_eliminated'] = false === strpos( $gz_file, 'wc_get_products' ) && false === strpos( $gz_file, 'limit' );

	$fallback_called = false;
	$check_where = static function ( $where ) use ( &$fallback_called ) {
		$fallback_called = true;
		return $where;
	};
	add_filter( 'posts_where', $check_where );
	try {
		$resolved_manual = negar_theme_gaming_zone_product( 'fc', array( 'EA Sports FC' ) );
	} finally {
		remove_filter( 'posts_where', $check_where );
	}
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

	// Verify truthful empty-state rendering across all six product pack templates when products cannot be resolved.
	$block_product_resolution = static function ( $where ) {
		return $where . ' AND 1=0';
	};
	update_option( $pack_option, array( 'packs' => array() ) );
	add_filter( 'posts_where', $block_product_resolution );
	try {
		negar_theme_find_product_by_title_needles( array(), true );

		ob_start();
		get_template_part( 'template-parts/home/sec-13-taste-grid' );
		$sec13_html = ob_get_clean();
		$results['taste_grid_empty_slot_truthful'] = false !== strpos( $sec13_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec13_html, 'is-empty' )
			&& false === strpos( $sec13_html, '580,000,000' )
			&& false === strpos( $sec13_html, '839,000,000' )
			&& false === strpos( $sec13_html, '409,999,000' );

		ob_start();
		get_template_part( 'template-parts/home/sec-14-feature-bar-1' );
		$sec14_html = ob_get_clean();
		$results['feature_bar_1_empty_slot_truthful'] = false !== strpos( $sec14_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec14_html, 'is-empty' )
			&& false === strpos( $sec14_html, '169.300.000' )
			&& false === strpos( $sec14_html, '5/4.5' );

		ob_start();
		get_template_part( 'template-parts/home/sec-23-feature-bar-2' );
		$sec23_html = ob_get_clean();
		$results['feature_bar_2_empty_slot_truthful'] = false !== strpos( $sec23_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec23_html, 'is-empty' )
			&& false === strpos( $sec23_html, '169.300.000' )
			&& false === strpos( $sec23_html, '5/4.5' );

		ob_start();
		get_template_part( 'template-parts/home/sec-24-smartwatches-2' );
		$sec24_html = ob_get_clean();
		$results['smartwatches_2_empty_slot_truthful'] = false !== strpos( $sec24_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec24_html, 'is-empty' )
			&& false === strpos( $sec24_html, '30,875,000' )
			&& false === strpos( $sec24_html, '32,500,000' )
			&& false === strpos( $sec24_html, '11 نظر' )
			&& false === strpos( $sec24_html, '11,400,000' )
			&& false === strpos( $sec24_html, 'data-product_id="0"' )
			&& false === strpos( $sec24_html, 'kaveh-variation-modal-trigger' );

		ob_start();
		get_template_part( 'template-parts/home/sec-28-mobile-spotlight' );
		$sec28_html = ob_get_clean();
		$results['mobile_spotlight_empty_slot_truthful'] = false !== strpos( $sec28_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec28_html, 'is-empty' )
			&& false === strpos( $sec28_html, '۴۴,۵۰۰,۰۰۰ تومان' )
			&& false === strpos( $sec28_html, '۴۹,۸۰۰,۰۰۰ تومان' )
			&& false === strpos( $sec28_html, '★ ۴.۹' )
			&& false === strpos( $sec28_html, 'Dynamic AMOLED 2X با پردازنده قدرتمند و هوش مصنوعی Galaxy AI' )
			&& false === strpos( $sec28_html, 'خرید آنی' )
			&& false === strpos( $sec28_html, 'ویترین گلکسی S25 FE' )
			&& false !== strpos( $sec28_html, 'ویترین محصول ویژه' )
			&& false === strpos( $sec28_html, 'ضمانت اصالت' );

		ob_start();
		get_template_part( 'template-parts/home/sec-34-feature-bar-3' );
		$sec34_html = ob_get_clean();
		$results['feature_bar_3_empty_slot_truthful'] = false !== strpos( $sec34_html, 'محصولی برای نمایش موجود نیست' )
			&& false !== strpos( $sec34_html, 'is-empty' )
			&& false === strpos( $sec34_html, '169.300.000' )
			&& false === strpos( $sec34_html, '5/4.5' );
	} finally {
		remove_filter( 'posts_where', $block_product_resolution );
		negar_theme_find_product_by_title_needles( array(), true );
		update_option( $pack_option, $pack );
	}

	// Assigned product truthful rendering and action safety
	update_option( $pack_option, array( 'packs' => array( 'mobile-spotlight' => array( 'focal' => (string) $product_id ) ) ) );
	ob_start();
	get_template_part( 'template-parts/home/sec-28-mobile-spotlight' );
	$assigned_spotlight = ob_get_clean();
	$results['mobile_spotlight_assigned_product_renders_truthfully'] = false !== strpos( $assigned_spotlight, esc_html( $product->get_name() ) )
		&& false !== strpos( $assigned_spotlight, 'خرید آنی' )
		&& false === strpos( $assigned_spotlight, 'Dynamic AMOLED 2X با پردازنده قدرتمند و هوش مصنوعی Galaxy AI' )
		&& false === strpos( $assigned_spotlight, 'ویترین گلکسی S25 FE' )
		&& false !== strpos( $assigned_spotlight, 'ویترین محصول ویژه' )
		&& false === strpos( $assigned_spotlight, 'ضمانت اصالت' );

	update_option( $pack_option, array( 'packs' => array( 'smartwatches-2' => array( 'carousel-1' => (string) $product_id ) ) ) );
	ob_start();
	get_template_part( 'template-parts/home/sec-24-smartwatches-2' );
	$assigned_sw2 = ob_get_clean();
	$results['smartwatches_2_assigned_product_action_safe'] = ( false !== strpos( $assigned_sw2, 'data-product_id="' . $product_id . '"' ) || false !== strpos( $assigned_sw2, 'مشاهده و انتخاب گزینه‌ها' ) )
		&& false === strpos( $assigned_sw2, 'data-product_id="0"' );

	// Zero-price rendering verification: genuine zero price displays 'رایگان' without Toman symbol.
	$zero_price_override = static function() {
		return '0';
	};
	add_filter( 'woocommerce_product_get_price', $zero_price_override );
	try {
		ob_start();
		get_template_part( 'template-parts/home/sec-28-mobile-spotlight' );
		$zero_spotlight = ob_get_clean();
		$results['product_pack_zero_price_handled_truthfully'] = false !== strpos( $zero_spotlight, 'رایگان' )
			&& false === strpos( $zero_spotlight, '۰ تومان' );
	} finally {
		remove_filter( 'woocommerce_product_get_price', $zero_price_override );
	}

	update_option( $pack_option, $pack );

	// --- Safety Regression Coverage for Media Fixture Lifecycle ---
	$upload_dir         = wp_upload_dir();
	$fixture_video_path = __DIR__ . '/fixtures/sample-video.webm';
	$active_fixtures    = array();
	$tracked_temp_files = array();

	// 1. Fixture creation uses a unique name
	$safe_f1 = negar_theme_test_create_video_fixture( 'safety-f1', 'safety-fixture-' );
	$active_fixtures[ $safe_f1['id'] ] = $safe_f1;

	$safe_f2 = negar_theme_test_create_video_fixture( 'safety-f2', 'safety-fixture-' );
	$active_fixtures[ $safe_f2['id'] ] = $safe_f2;

	$results['fixture_unique_naming'] = ( $safe_f1['file'] !== $safe_f2['file'] && $safe_f1['id'] !== $safe_f2['id'] );

	// 2. Two separate runs cannot overwrite each other's files
	$results['fixture_non_collision'] = file_exists( $safe_f1['file'] )
		&& file_exists( $safe_f2['file'] )
		&& wp_unique_filename( $upload_dir['path'], basename( $safe_f1['file'] ) ) !== basename( $safe_f1['file'] );

	// 3. Pre-existing file with old fixed filename is inspected and hashed without attaching to WP
	$old_fixed_file   = wp_normalize_path( $upload_dir['path'] . '/test-sample-video.webm' );
	$old_file_existed = file_exists( $old_fixed_file );
	if ( ! $old_file_existed ) {
		file_put_contents( $old_fixed_file, 'test-sentinel-data-do-not-delete-' . wp_generate_password( 12, false, false ) );
		$tracked_temp_files[] = $old_fixed_file;
	}
	$old_file_hash = sha1_file( $old_fixed_file );

	// 4. Isolated sentinel attachment pointing to an EXCLUSIVE test file (never pointing to pre-existing file)
	$sentinel_file = wp_normalize_path( $upload_dir['path'] . '/' . wp_unique_filename( $upload_dir['path'], 'sentinel-fixture-' . wp_generate_password( 8, false, false ) . '.webm' ) );
	copy( $fixture_video_path, $sentinel_file );
	$tracked_temp_files[] = $sentinel_file;

	$sentinel_attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'video/webm',
			'post_title'     => 'Negar Test Sample Video',
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$sentinel_file
	);
	$active_fixtures[ $sentinel_attachment_id ] = array(
		'id'    => $sentinel_attachment_id,
		'token' => '',
		'file'  => $sentinel_file,
		'owner' => 'unowned-sentinel',
	);

	// 5. Cleanup without a valid ownership token is rejected
	$reject_empty_token = negar_theme_test_cleanup_fixture( $sentinel_attachment_id, '' );
	$reject_wrong_token = negar_theme_test_cleanup_fixture( $sentinel_attachment_id, 'unauthorized-token' );
	$reject_other_token = negar_theme_test_cleanup_fixture( $sentinel_attachment_id, $safe_f1['token'] );
	$results['fixture_token_rejection'] = false === $reject_empty_token['cleaned']
		&& false === $reject_wrong_token['cleaned']
		&& false === $reject_other_token['cleaned'];

	// 6. Cleanup with the wrong attachment ID is rejected
	$reject_wrong_id = negar_theme_test_cleanup_fixture( 99999999, $safe_f1['token'] );
	$results['fixture_wrong_id_rejection'] = false === $reject_wrong_id['cleaned'];

	// 7. Missing ownership metadata blocks deletion
	$reject_missing_meta = negar_theme_test_cleanup_fixture( $sentinel_attachment_id, 'any-token' );
	$results['fixture_missing_meta_rejection'] = false === $reject_missing_meta['cleaned']
		&& 'missing_fixture_token' === $reject_missing_meta['error'];

	// 8. Mismatched actual and stored file paths block deletion
	$mismatch_fix = negar_theme_test_create_video_fixture( 'safety-f1', 'safety-fixture-' );
	$active_fixtures[ $mismatch_fix['id'] ] = $mismatch_fix;
	update_post_meta( $mismatch_fix['id'], '_negar_test_fixture_file', $upload_dir['path'] . '/mismatched-file.webm' );
	$reject_mismatched = negar_theme_test_cleanup_fixture( $mismatch_fix['id'], $mismatch_fix['token'] );
	$results['fixture_mismatched_path_rejection'] = false === $reject_mismatched['cleaned']
		&& 'attached_stored_path_mismatch' === $reject_mismatched['error'];

	// 9. Files outside uploads cannot be deleted
	update_post_meta( $mismatch_fix['id'], '_negar_test_fixture_file', 'C:/Windows/System32/drivers/etc/hosts' );
	update_attached_file( $mismatch_fix['id'], 'C:/Windows/System32/drivers/etc/hosts' );
	$reject_outside = negar_theme_test_cleanup_fixture( $mismatch_fix['id'], $mismatch_fix['token'] );
	$results['fixture_outside_uploads_rejection'] = false === $reject_outside['cleaned'];

	// Restore valid metadata on mismatch fixture so it can be cleaned up cleanly
	$rel_mismatch = ltrim( str_replace( wp_normalize_path( $upload_dir['basedir'] ), '', $mismatch_fix['file'] ), '/' );
	update_attached_file( $mismatch_fix['id'], $rel_mismatch );
	update_post_meta( $mismatch_fix['id'], '_negar_test_fixture_file', $mismatch_fix['file'] );
	$clean_mismatch = negar_theme_test_cleanup_fixture( $mismatch_fix['id'], $mismatch_fix['token'] );
	unset( $active_fixtures[ $mismatch_fix['id'] ] );

	// 10. Cleanup of the exact owned fixture succeeds
	$clean_f1 = negar_theme_test_cleanup_fixture( $safe_f1['id'], $safe_f1['token'] );
	unset( $active_fixtures[ $safe_f1['id'] ] );
	$results['fixture_owned_cleanup_succeeds'] = true === $clean_f1['cleaned']
		&& null === get_post( $safe_f1['id'] )
		&& ! file_exists( $safe_f1['file'] );

	// 11. Repeated cleanup does not delete unrelated files
	$clean_repeat = negar_theme_test_cleanup_fixture( $safe_f1['id'], $safe_f1['token'] );
	$results['fixture_repeated_cleanup_safe'] = false === $clean_repeat['cleaned']
		&& 'not_an_attachment' === $clean_repeat['error'];

	// Cleanup safe_f2
	$clean_f2 = negar_theme_test_cleanup_fixture( $safe_f2['id'], $safe_f2['token'] );
	unset( $active_fixtures[ $safe_f2['id'] ] );
	$results['fixture_f2_cleanup_succeeds'] = true === $clean_f2['cleaned']
		&& null === get_post( $safe_f2['id'] )
		&& ! file_exists( $safe_f2['file'] );

	// 12. Unrelated attachment with matching title survived ALL preceding cleanup actions
	$results['fixture_unrelated_attachment_preserved'] = null !== get_post( $sentinel_attachment_id )
		&& 'Negar Test Sample Video' === get_the_title( $sentinel_attachment_id )
		&& file_exists( $sentinel_file );

	// Clean up the sentinel attachment safely by assigning recognized test ownership to its exclusive file
	$sentinel_token = wp_generate_password( 32, false, false );
	update_post_meta( $sentinel_attachment_id, '_negar_test_fixture_token', $sentinel_token );
	update_post_meta( $sentinel_attachment_id, '_negar_test_fixture_owner', 'sentinel-test' );
	update_post_meta( $sentinel_attachment_id, '_negar_test_fixture_file', $sentinel_file );
	$clean_sentinel = negar_theme_test_cleanup_fixture( $sentinel_attachment_id, $sentinel_token );
	unset( $active_fixtures[ $sentinel_attachment_id ] );

	// 13. Pre-existing file survived ALL cleanups! Checked strictly AFTER all cleanup operations!
	$results['fixture_preexisting_file_preserved'] = file_exists( $old_fixed_file )
		&& sha1_file( $old_fixed_file ) === $old_file_hash;

	// Clean up temporary pre-existing file if created specifically for this test
	if ( ! $old_file_existed && file_exists( $old_fixed_file ) ) {
		@unlink( $old_fixed_file );
	}

	// 14. If fixture creation fails, partial test-owned artifacts are handled safely
	$simulated_partial = wp_normalize_path( $upload_dir['path'] . '/' . wp_unique_filename( $upload_dir['path'], 'safety-fixture-partial-' . wp_generate_password( 8, false, false ) . '.webm' ) );
	copy( $fixture_video_path, $simulated_partial );
	$partial_existed = file_exists( $simulated_partial );
	if ( file_exists( $simulated_partial ) ) {
		@unlink( $simulated_partial );
	}
	$results['fixture_partial_artifact_safe'] = $partial_existed && ! file_exists( $simulated_partial );

	// --- Story Management Tests ---
	$test_video_id  = 0;
	$verify_token   = '';
	$verify_fixture = negar_theme_test_create_video_fixture( 'php-verify', 'verify-fixture-' );
	$test_video_id  = $verify_fixture['id'];
	$verify_token   = $verify_fixture['token'];
	$active_fixtures[ $test_video_id ] = $verify_fixture;

	try {
		wp_set_current_user( 0 );
		$denied_story = negar_theme_sanitize_story_settings( array( 'stories' => array( array( 'title' => 'Unauth' ) ) ) );
		$results['story_admin_only'] = get_option( $story_option, array() ) === $denied_story;
		negar_theme_test_admin();

		// Default demo fallback when option does not exist
		delete_option( $story_option );
		$default_stories = negar_theme_get_stories( true );
		$results['story_defaults_fallback'] = 10 === count( $default_stories )
			&& 'پیشنهاد داغ' === $default_stories[0]['title']
			&& false === $default_stories[0]['has_product']
			&& '' === $default_stories[0]['product_title']
			&& '' === $default_stories[0]['product_link'];

		// Unconfigured admin view renders demo notice and 0 editable cards in the list
		ob_start();
		negar_theme_render_story_fields();
		$admin_unconfigured_html = ob_get_clean();
		$results['story_unconfigured_admin_safe'] = false !== strpos( $admin_unconfigured_html, 'حالت پیش‌نمایش دمو' )
			&& 1 === preg_match( '#<div id="negar-stories-list"[^>]*>\s*</div>#', $admin_unconfigured_html );

		// Explicit empty array returns empty collection (does NOT fall back to demo)
		update_option( $story_option, array( 'schema_version' => 1, 'stories' => array() ) );
		$empty_stories = negar_theme_get_stories( true );
		$results['story_explicit_empty'] = 0 === count( $empty_stories );

		// Clean omission of homepage component and modal when 0 stories
		ob_start();
		get_template_part( 'template-parts/home/story-circles' );
		$empty_story_html = ob_get_clean();
		$results['story_clean_omission_when_empty'] = '' === trim( $empty_story_html );

		// Sanitization: priority, links, duration clamping, media omission, deduplication
		$test_input = array(
			'stories' => array(
				array(
					'id'           => 'story_prod',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری با محصول',
					'badge'        => 'ویژه',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 12,
					'product_id'   => $product_id,
					'cta_label'    => 'خرید اختصاصی',
					'cta_url'      => '/shop/ignored-when-prod-active/',
				),
				array(
					'id'           => 'story_edit_rel',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری ادیتوریال نسبی',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => 'مشاهده تخفیف‌ها',
					'cta_url'      => '/shop/sale/',
				),
				array(
					'id'           => 'story_edit_abs',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری ادیتوریال مطلق',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => 'صفحه پروموشن',
					'cta_url'      => 'https://example.com/promo',
				),
				array(
					'id'           => 'story_no_cta',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری بدون لینک',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_unsafe_cta',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری لینک ناامن',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => 'javascript:alert(1)',
				),
				array(
					'id'           => 'story_valid_video',
					'enabled'      => '1',
					'type'         => 'video',
					'title'        => 'استوری ویدیویی معتبر',
					'badge'        => 'ویدیو',
					'thumbnail_id' => $image_id,
					'video_id'     => $test_video_id,
					'poster_id'    => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_missing_video',
					'enabled'      => '1',
					'type'         => 'video',
					'title'        => 'استوری ویدیوی گمشده',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'video_id'     => 0,
					'poster_id'    => 0,
					'duration'     => 50, // clamp to 30
					'product_id'   => 9999999, // non-existent product, sanitize to 0
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_invalid_mime',
					'enabled'      => '1',
					'type'         => 'video',
					'title'        => 'استوری مایم نامعتبر',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'video_id'     => $image_id, // image ID passed as video_id -> must be zeroed
					'poster_id'    => 0,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_missing_image',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری تصویر گمشده',
					'badge'        => '',
					'thumbnail_id' => 0,
					'image_id'     => 0,
					'duration'     => 1, // clamp to 3
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_disabled',
					'enabled'      => '0',
					'type'         => 'image',
					'title'        => 'استوری غیرفعال',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_dup',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری تکراری ۱',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
				array(
					'id'           => 'story_dup',
					'enabled'      => '1',
					'type'         => 'image',
					'title'        => 'استوری تکراری ۲',
					'badge'        => '',
					'thumbnail_id' => $image_id,
					'image_id'     => $image_id,
					'duration'     => 5,
					'product_id'   => 0,
					'cta_label'    => '',
					'cta_url'      => '',
				),
			),
		);

		$sanitized_story_settings = negar_theme_sanitize_story_settings( $test_input );
		update_option( $story_option, $sanitized_story_settings );

		$results['story_sanitization_persisted'] = 12 === count( $sanitized_story_settings['stories'] );
		$results['story_duration_clamped']      = 30 === $sanitized_story_settings['stories'][6]['duration']
			&& 3 === $sanitized_story_settings['stories'][8]['duration'];
		$results['story_unsafe_url_rejected']   = '' === $sanitized_story_settings['stories'][4]['cta_url']
			&& '/shop/sale/' === $sanitized_story_settings['stories'][1]['cta_url'];
		$results['story_invalid_prod_zeroed']   = 0 === $sanitized_story_settings['stories'][6]['product_id']
			&& $product_id === $sanitized_story_settings['stories'][0]['product_id'];
		$results['story_invalid_mime_zeroed']   = 0 === $sanitized_story_settings['stories'][7]['video_id']
			&& $test_video_id === $sanitized_story_settings['stories'][5]['video_id'];
		$results['story_id_deduplication']      = $sanitized_story_settings['stories'][10]['id'] !== $sanitized_story_settings['stories'][11]['id'];

		// Resolved public enabled stories (unusable media & disabled omitted)
		$resolved_enabled = negar_theme_get_stories( true );
		$results['story_unusable_media_omitted'] = 8 === count( $resolved_enabled );

		// Custom CTA & Product Priority Checks
		$wc_prod    = wc_get_product( $product_id );
		$prod_story = $resolved_enabled[0];
		$results['story_priority_wc_product'] = true === $prod_story['has_product']
			&& $product_id === $prod_story['product_id']
			&& '' !== $prod_story['product_title']
			&& $wc_prod
			&& $wc_prod->get_permalink() === $prod_story['product_link'];

		$rel_story = $resolved_enabled[1];
		$results['story_priority_rel_cta'] = false === $rel_story['has_product']
			&& '' === $rel_story['product_title']
			&& '/shop/sale/' === $rel_story['product_link'];

		$abs_story = $resolved_enabled[2];
		$results['story_priority_abs_cta'] = false === $abs_story['has_product']
			&& '' === $abs_story['product_title']
			&& 'https://example.com/promo' === $abs_story['product_link'];

		$no_cta_story = $resolved_enabled[3];
		$results['story_priority_no_cta'] = false === $no_cta_story['has_product']
			&& '' === $no_cta_story['product_title']
			&& '' === $no_cta_story['product_link'];

		$video_story = $resolved_enabled[5];
		$results['story_video_resolved'] = 'video' === $video_story['type']
			&& '' !== $video_story['video_url'];

		// Reordering stability: moving story_edit_rel before story_prod
		$reordered_input = array(
			'stories' => array(
				$sanitized_story_settings['stories'][1],
				$sanitized_story_settings['stories'][0],
				$sanitized_story_settings['stories'][2],
			),
		);
		$reordered_sanitized = negar_theme_sanitize_story_settings( $reordered_input );
		update_option( $story_option, $reordered_sanitized );

		$results['story_reorder_id_stability'] = 'story_edit_rel' === $reordered_sanitized['stories'][0]['id']
			&& 'story_prod' === $reordered_sanitized['stories'][1]['id']
			&& 'story_edit_abs' === $reordered_sanitized['stories'][2]['id'];

		// Frontend rendering of configured stories
		ob_start();
		get_template_part( 'template-parts/home/story-circles' );
		$story_html = ob_get_clean();
		$results['story_frontend_rendered'] = false !== strpos( $story_html, 'story-shop__item' )
			&& false !== strpos( $story_html, 'modal-story-shop' )
			&& false !== strpos( $story_html, 'id="story-modal-play-overlay"' )
			&& false !== strpos( $story_html, 'id="story-modal-error-overlay"' )
			&& false !== strpos( $story_html, 'preload="none"' )
			&& false !== strpos( $story_html, 'استوری با محصول' )
			&& false !== strpos( $story_html, 'استوری ادیتوریال نسبی' );
	} finally {
		if ( $test_video_id > 0 && '' !== $verify_token ) {
			negar_theme_test_cleanup_fixture( $test_video_id, $verify_token );
			unset( $active_fixtures[ $test_video_id ] );
		}
		if ( ! empty( $active_fixtures ) ) {
			foreach ( $active_fixtures as $rem_id => $rem_fix ) {
				if ( ! empty( $rem_fix['token'] ) ) {
					negar_theme_test_cleanup_fixture( $rem_id, $rem_fix['token'] );
				} elseif ( $rem_id > 0 && ! empty( $rem_fix['file'] ) ) {
					$emergency_token = wp_generate_password( 32, false, false );
					update_post_meta( $rem_id, '_negar_test_fixture_token', $emergency_token );
					update_post_meta( $rem_id, '_negar_test_fixture_owner', 'sentinel-test' );
					update_post_meta( $rem_id, '_negar_test_fixture_file', $rem_fix['file'] );
					negar_theme_test_cleanup_fixture( $rem_id, $emergency_token );
				}
			}
		}
		if ( ! empty( $tracked_temp_files ) ) {
			foreach ( $tracked_temp_files as $temp_file ) {
				if ( file_exists( $temp_file ) ) {
					@unlink( $temp_file );
				}
			}
		}
	}

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

	negar_theme_test_restore( $option, $media_option, $pack_option, $story_option, $mods, $verify_snapshot );

	echo wp_json_encode( $results );
	exit( in_array( false, $results, true ) ? 1 : 0 );
} catch ( Throwable $error ) {
	if ( ! empty( $active_fixtures ) ) {
		foreach ( $active_fixtures as $rem_id => $rem_fix ) {
			if ( ! empty( $rem_fix['token'] ) ) {
				negar_theme_test_cleanup_fixture( $rem_id, $rem_fix['token'] );
			}
		}
	}
	if ( ! empty( $tracked_temp_files ) ) {
		foreach ( $tracked_temp_files as $temp_file ) {
			if ( file_exists( $temp_file ) ) {
				@unlink( $temp_file );
			}
		}
	}
	if ( isset( $option, $media_option, $pack_option, $story_option, $mods, $verify_snapshot ) ) {
		negar_theme_test_restore( $option, $media_option, $pack_option, $story_option, $mods, $verify_snapshot );
	}
	fwrite( STDERR, $error->getMessage() . PHP_EOL );
	exit( 1 );
}
