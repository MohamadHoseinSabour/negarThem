<?php
/**
 * Category URL Redirects for Negar The Home Appliances Migration.
 *
 * Implements 301 permanent redirects for obsolete category URLs that have
 * legitimate equivalents in the new home appliances taxonomy.
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle 301 redirects for legacy product categories.
 */
function negar_theme_handle_legacy_category_redirects() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( empty( $request_uri ) || false === strpos( $request_uri, '/product-category/' ) ) {
		return;
	}

	$legacy_mappings = array(
		'refrigerators'      => 'refrigerators-freezers',
		'washing-machine'    => 'washing-machines',
		'tvs'                => 'televisions',
		'smart-tvs'          => 'televisions',
		'audio-video'        => 'tv-audio',
		'speakers'           => 'soundbars-home-audio',
		'kitchen-appliances' => 'beverage-breakfast',
		'home-appliances'    => 'major-appliances',
		'beauty-health'      => 'personal-care-appliances',
		'shaver'             => 'electric-shavers',
		'hair-dryer'         => 'hair-dryers',
	);

	foreach ( $legacy_mappings as $old_slug => $new_slug ) {
		if ( preg_match( '#/product-category/' . preg_quote( $old_slug, '#' ) . '(/.*)?$#i', $request_uri ) ) {
			$term = get_term_by( 'slug', $new_slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				$new_url = get_term_link( $term );
				if ( ! is_wp_error( $new_url ) ) {
					wp_safe_redirect( $new_url, 301 );
					exit;
				}
			}
		}
	}
}
add_action( 'template_redirect', 'negar_theme_handle_legacy_category_redirects', 1 );
