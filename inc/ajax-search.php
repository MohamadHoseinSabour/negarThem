<?php
/**
 * Live Ajax Search for Negar Shop
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Live Ajax Search Request
 */
function negar_shop_ajax_search_callback() {
	// Verify Nonce
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';
	$category_filter = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';

	if ( mb_strlen( $term, 'UTF-8' ) < 2 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'حداقل ۲ کاراکتر وارد کنید.', 'negar-shop' ) ) );
	}

	$results = array();

	// Check if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			's'              => $term,
		);

		if ( ! empty( $category_filter ) && '0' !== $category_filter ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category_filter,
				),
			);
		}

		$search_query = new WP_Query( $query_args );

		if ( $search_query->have_posts() ) {
			while ( $search_query->have_posts() ) {
				$search_query->the_post();
				global $product;
				if ( ! is_a( $product, 'WC_Product' ) ) {
					$product = wc_get_product( get_the_ID() );
				}

				if ( ! $product ) {
					continue;
				}

				// Thumbnail
				$thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
				if ( ! $thumb && function_exists( 'wc_placeholder_img_src' ) ) {
					$thumb = wc_placeholder_img_src( 'thumbnail' );
				}

				// Category
				$terms = get_the_terms( get_the_ID(), 'product_cat' );
				$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

				$results[] = array(
					'id'       => get_the_ID(),
					'title'    => get_the_title(),
					'url'      => get_permalink(),
					'thumb'    => $thumb,
					'price'    => $product->get_price_html(),
					'category' => $cat_name,
				);
			}
			wp_reset_postdata();
		}

		$view_all_url = add_query_arg(
			array(
				's'         => $term,
				'post_type' => 'product',
			),
			home_url( '/' )
		);
	} else {
		// Fallback for regular posts
		$search_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 6,
				's'              => $term,
			)
		);

		if ( $search_query->have_posts() ) {
			while ( $search_query->have_posts() ) {
				$search_query->the_post();
				$results[] = array(
					'id'       => get_the_ID(),
					'title'    => get_the_title(),
					'url'      => get_permalink(),
					'thumb'    => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
					'price'    => '',
					'category' => '',
				);
			}
			wp_reset_postdata();
		}

		$view_all_url = add_query_arg( array( 's' => $term ), home_url( '/' ) );
	}

	wp_send_json_success(
		array(
			'results'      => $results,
			'total'        => count( $results ),
			'view_all_url' => esc_url( $view_all_url ),
		)
	);
}
add_action( 'wp_ajax_negar_shop_ajax_search', 'negar_shop_ajax_search_callback' );
add_action( 'wp_ajax_nopriv_negar_shop_ajax_search', 'negar_shop_ajax_search_callback' );
