<?php
/**
 * Shop & Archive Helper Functions
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output semantic breadcrumbs for Shop and WooCommerce pages.
 */
function negar_shop_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$delimiter   = '<span class="breadcrumb-separator" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></span>';
	$home_title  = esc_html__( 'صفحه اصلی', 'negar-shop' );
	$shop_title  = esc_html__( 'فروشگاه', 'negar-shop' );
	$shop_url    = wc_get_page_permalink( 'shop' );

	echo '<nav class="negar-breadcrumbs mb-4" aria-label="' . esc_attr__( 'مسیر دسترسی', 'negar-shop' ) . '">';
	echo '<ol class="breadcrumb-list flex items-center gap-2 text-sm text-muted" itemscope itemtype="https://schema.org/BreadcrumbList">';

	// Home
	echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
	echo '<a itemprop="item" href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-primary transition-colors">';
	echo '<span itemprop="name">' . esc_html( $home_title ) . '</span></a>';
	echo '<meta itemprop="position" content="1" />';
	echo '</li>';

	$position = 2;

	if ( is_shop() ) {
		echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<li class="breadcrumb-item active text-dark font-medium" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">';
		echo '<span itemprop="name">' . esc_html( $shop_title ) . '</span>';
		echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
		echo '</li>';
	} elseif ( is_product_category() ) {
		echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<a itemprop="item" href="' . esc_url( $shop_url ) . '" class="hover:text-primary transition-colors">';
		echo '<span itemprop="name">' . esc_html( $shop_title ) . '</span></a>';
		echo '<meta itemprop="position" content="' . esc_attr( $position++ ) . '" />';
		echo '</li>';

		$current_term = get_queried_object();
		if ( $current_term && $current_term->parent ) {
			$ancestors = array_reverse( get_ancestors( $current_term->term_id, 'product_cat' ) );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'product_cat' );
				if ( $ancestor && ! is_wp_error( $ancestor ) ) {
					echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
					echo '<a itemprop="item" href="' . esc_url( get_term_link( $ancestor ) ) . '" class="hover:text-primary transition-colors">';
					echo '<span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a>';
					echo '<meta itemprop="position" content="' . esc_attr( $position++ ) . '" />';
					echo '</li>';
				}
			}
		}

		echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<li class="breadcrumb-item active text-dark font-medium" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">';
		echo '<span itemprop="name">' . esc_html( $current_term ? $current_term->name : '' ) . '</span>';
		echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
		echo '</li>';
	} elseif ( is_product_tag() ) {
		echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		echo '<a itemprop="item" href="' . esc_url( $shop_url ) . '" class="hover:text-primary transition-colors">';
		echo '<span itemprop="name">' . esc_html( $shop_title ) . '</span></a>';
		echo '<meta itemprop="position" content="' . esc_attr( $position++ ) . '" />';
		echo '</li>';

		$current_term = get_queried_object();
		echo $delimiter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<li class="breadcrumb-item active text-dark font-medium" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">';
		echo '<span itemprop="name">' . esc_html( sprintf( __( 'برچسب: %s', 'negar-shop' ), $current_term ? $current_term->name : '' ) ) . '</span>';
		echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
		echo '</li>';
	}

	echo '</ol>';
	echo '</nav>';
}

/**
 * Get min and max price limits across all products.
 *
 * @return array Array with 'min' and 'max'.
 */
function negar_shop_get_price_range() {
	global $wpdb;

	$results = $wpdb->get_row(
		"SELECT MIN(min_price) as min_price, MAX(max_price) as max_price FROM {$wpdb->prefix}wc_product_meta_lookup"
	);

	$min = ( $results && null !== $results->min_price ) ? floor( $results->min_price ) : 0;
	$max = ( $results && null !== $results->max_price ) ? ceil( $results->max_price ) : 100000000;

	return array(
		'min' => (int) $min,
		'max' => (int) $max,
	);
}

/**
 * Get active filter chips array from query parameters.
 *
 * @param array $params Query parameters.
 * @return array List of chips with 'key', 'label', 'value'.
 */
function negar_shop_get_active_filter_chips( $params = array() ) {
	if ( empty( $params ) ) {
		$params = $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	$chips = array();

	// Category
	if ( ! empty( $params['product_cat'] ) ) {
		$term = get_term_by( 'slug', sanitize_text_field( $params['product_cat'] ), 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$chips[] = array(
				'key'   => 'product_cat',
				'label' => sprintf( __( 'دسته: %s', 'negar-shop' ), $term->name ),
				'value' => $term->slug,
			);
		}
	}

	// Min / Max Price
	if ( ! empty( $params['min_price'] ) || ! empty( $params['max_price'] ) ) {
		$min = ! empty( $params['min_price'] ) ? wc_price( absint( $params['min_price'] ) ) : '';
		$max = ! empty( $params['max_price'] ) ? wc_price( absint( $params['max_price'] ) ) : '';

		if ( $min && $max ) {
			$label = sprintf( __( 'قیمت: از %s تا %s', 'negar-shop' ), $min, $max );
		} elseif ( $min ) {
			$label = sprintf( __( 'قیمت: بیش از %s', 'negar-shop' ), $min );
		} else {
			$label = sprintf( __( 'قیمت: تا %s', 'negar-shop' ), $max );
		}

		$chips[] = array(
			'key'   => 'price_range',
			'label' => wp_strip_all_tags( $label ),
			'value' => 'custom',
		);
	}

	// In Stock
	if ( ! empty( $params['in_stock'] ) && '1' === $params['in_stock'] ) {
		$chips[] = array(
			'key'   => 'in_stock',
			'label' => __( 'فقط کالاهای موجود', 'negar-shop' ),
			'value' => '1',
		);
	}

	// On Sale
	if ( ! empty( $params['on_sale'] ) && '1' === $params['on_sale'] ) {
		$chips[] = array(
			'key'   => 'on_sale',
			'label' => __( 'فقط کالاهای حراجی', 'negar-shop' ),
			'value' => '1',
		);
	}

	return $chips;
}
