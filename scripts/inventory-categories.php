<?php
/**
 * Inventory existing WooCommerce product categories and dependencies.
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

require dirname( __DIR__, 4 ) . '/wp-load.php';

$inventory = array(
	'timestamp' => date( 'c' ),
	'default_category' => array(),
	'categories' => array(),
	'product_relationships' => array(),
	'theme_settings' => array(),
	'menus' => array(),
	'template_references' => array(),
);

// 1. Default product category
$default_cat_id = (int) get_option( 'default_product_cat', 0 );
$default_cat = get_term( $default_cat_id, 'product_cat' );
$inventory['default_category'] = array(
	'term_id' => $default_cat_id,
	'name' => $default_cat && ! is_wp_error( $default_cat ) ? $default_cat->name : '',
	'slug' => $default_cat && ! is_wp_error( $default_cat ) ? $default_cat->slug : '',
);

// 2. All product_cat terms
$terms = get_terms( array(
	'taxonomy' => 'product_cat',
	'hide_empty' => false,
) );

$term_map = array();
foreach ( $terms as $term ) {
	$term_map[ $term->term_id ] = $term;
}

foreach ( $terms as $term ) {
	$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
	$image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : '';

	// Direct product count
	$direct_posts = get_posts( array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $term->term_id,
				'include_children' => false,
			),
		),
	) );
	$direct_count = count( $direct_posts );

	// Inherited product count
	$inherited_posts = get_posts( array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'fields' => 'ids',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $term->term_id,
				'include_children' => true,
			),
		),
	) );
	$inherited_count = count( $inherited_posts );

	$parent_term = $term->parent && isset( $term_map[ $term->parent ] ) ? $term_map[ $term->parent ] : null;

	$inventory['categories'][] = array(
		'term_id' => $term->term_id,
		'name' => $term->name,
		'slug' => $term->slug,
		'parent_id' => $term->parent,
		'parent_name' => $parent_term ? $parent_term->name : null,
		'parent_slug' => $parent_term ? $parent_term->slug : null,
		'term_taxonomy_id' => $term->term_taxonomy_id,
		'description' => $term->description,
		'direct_count' => $direct_count,
		'inherited_count' => $inherited_count,
		'registered_count' => $term->count,
		'direct_product_ids' => $direct_posts,
		'thumbnail_id' => $thumbnail_id ? (int) $thumbnail_id : null,
		'thumbnail_url' => $image_url ?: null,
		'url' => get_term_link( $term ),
	);
}

// 3. Products and their categories
$products = get_posts( array(
	'post_type' => 'product',
	'posts_per_page' => -1,
	'post_status' => 'any',
) );

foreach ( $products as $prod ) {
	$prod_cats = wp_get_post_terms( $prod->ID, 'product_cat', array( 'fields' => 'all' ) );
	$cat_summaries = array();
	foreach ( $prod_cats as $pc ) {
		$cat_summaries[] = array(
			'term_id' => $pc->term_id,
			'name' => $pc->name,
			'slug' => $pc->slug,
		);
	}
	$inventory['product_relationships'][] = array(
		'product_id' => $prod->ID,
		'title' => $prod->post_title,
		'slug' => $prod->post_name,
		'status' => $prod->post_status,
		'categories' => $cat_summaries,
	);
}

// 4. Theme settings containing category IDs or category references
$all_options = wp_load_alloptions();
$checked_options = array(
	'negar_theme_settings',
	'negar_theme_media',
	'negar_product_pack_settings',
	'negar_story_settings',
	'negar_brand_settings',
	'theme_mods_' . get_option( 'stylesheet' ),
);

foreach ( $checked_options as $opt_key ) {
	$opt_val = get_option( $opt_key, null );
	if ( null !== $opt_val ) {
		$inventory['theme_settings'][ $opt_key ] = $opt_val;
	}
}

// 5. Menus and menu items linked to categories
$nav_menus = wp_get_nav_menus();
foreach ( $nav_menus as $menu ) {
	$items = wp_get_nav_menu_items( $menu->term_id );
	$cat_items = array();
	if ( $items ) {
		foreach ( $items as $item ) {
			if ( 'product_cat' === $item->object || false !== strpos( $item->url, 'product-category' ) ) {
				$cat_items[] = array(
					'item_id' => $item->ID,
					'title' => $item->title,
					'object' => $item->object,
					'object_id' => $item->object_id,
					'url' => $item->url,
				);
			}
		}
	}
	if ( ! empty( $cat_items ) ) {
		$inventory['menus'][] = array(
			'menu_id' => $menu->term_id,
			'menu_name' => $menu->name,
			'menu_slug' => $menu->slug,
			'category_items' => $cat_items,
		);
	}
}

// Save machine-readable JSON report
$json_path = dirname( __DIR__ ) . '/backups/category-inventory-report.json';
file_put_contents( $json_path, json_encode( $inventory, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );

echo "Inventory complete. Total categories: " . count( $inventory['categories'] ) . "\n";
echo "Total products: " . count( $inventory['product_relationships'] ) . "\n";
echo "Saved JSON report to {$json_path}\n";
