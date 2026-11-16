<?php
/**
 * Fix major-appliances Persian name and category ordering.
 */

require_once dirname(__DIR__) . '/../../../wp-load.php';

// 1. Update major-appliances name to "لوازم خانگی سنگین"
$term_major = get_term_by( 'slug', 'major-appliances', 'product_cat' );
if ( $term_major ) {
	$updated = wp_update_term(
		$term_major->term_id,
		'product_cat',
		array(
			'name'        => 'لوازم خانگی سنگین',
			'description' => 'انواع لوازم خانگی سنگین برقی شامل یخچال و فریزر، ماشین لباسشویی، ماشین ظرفشویی، اجاق گاز، فر توکار و هود آشپزخانه با ضمانت اصالت کالا',
		)
	);
	echo "Updated major-appliances (ID: {$term_major->term_id}) name to 'لوازم خانگی سنگین'.\n";
} else {
	echo "ERROR: major-appliances not found!\n";
}

// 2. Ensure an 'uncategorized' term exists and set default_product_cat to it
$uncat = get_term_by( 'slug', 'uncategorized', 'product_cat' );
if ( ! $uncat ) {
	$created = wp_insert_term(
		'دسته‌بندی نشده',
		'product_cat',
		array(
			'slug'        => 'uncategorized',
			'description' => 'دسته‌بندی پیش‌فرض برای کالاهای بدون دسته‌بندی',
		)
	);
	if ( ! is_wp_error( $created ) ) {
		$uncat_id = (int) $created['term_id'];
		update_option( 'default_product_cat', $uncat_id );
		echo "Created 'uncategorized' term (ID: {$uncat_id}) and updated default_product_cat.\n";
	} else {
		echo "ERROR creating uncategorized: " . $created->get_error_message() . "\n";
	}
} else {
	update_option( 'default_product_cat', (int) $uncat->term_id );
	echo "Existing 'uncategorized' term ID: {$uncat->term_id} set as default_product_cat.\n";
}

// 3. Define priority order for all categories
$priority_order = array(
	// 1. Major appliances
	'major-appliances'                 => 10,
	'refrigerators-freezers'           => 11,
	'washing-machines'                 => 12,
	'dishwashers'                      => 13,
	'cookers-stoves'                   => 14,
	'built-in-ovens'                   => 15,
	'range-hoods'                      => 16,

	// 2. Electric cooking
	'electric-cooking'                 => 20,
	'microwaves'                       => 21,
	'air-fryers'                       => 22,
	'toaster-ovens'                    => 23,
	'rice-pressure-cookers'            => 24,
	'electric-grills-sandwich-makers'  => 25,

	// 3. Food preparation
	'food-preparation'                 => 30,
	'food-processors-blenders'         => 31,
	'juicers'                          => 32,
	'meat-grinders'                    => 33,
	'mixers'                           => 34,
	'choppers-grinders'                => 35,

	// 4. Beverage & Breakfast
	'beverage-breakfast'               => 40,
	'tea-makers-electric-kettles'      => 41,
	'coffee-espresso-machines'         => 42,
	'coffee-grinders'                  => 43,
	'bread-toasters'                   => 44,
	'water-dispensers'                 => 45,

	// 5. Cleaning & Ironing
	'cleaning-ironing'                 => 50,
	'vacuum-cleaners'                  => 51,
	'cordless-robot-vacuums'           => 52,
	'steam-cleaners'                   => 53,
	'irons-garment-steamers'           => 54,

	// 6. Cooling, Heating & Air
	'cooling-heating-air'              => 60,
	'air-conditioners-splits'          => 61,
	'fans'                             => 62,
	'electric-heaters'                 => 63,
	'air-purifiers'                    => 64,
	'humidifiers'                      => 65,

	// 7. TV & Audio
	'tv-audio'                         => 70,
	'televisions'                      => 71,
	'soundbars-home-audio'             => 72,

	// 8. Personal care
	'personal-care-appliances'         => 80,
	'hair-dryers'                      => 81,
	'electric-shavers'                 => 82,
	'hair-straighteners'               => 83,
);

// 4. Update termmeta 'order' for each category
foreach ( $priority_order as $slug => $order ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term ) {
		update_term_meta( $term->term_id, 'order', $order );
		// Also update term_order in wp_terms if column exists
		echo "Set order={$order} for '{$term->name}' ({$slug}, ID {$term->term_id})\n";
	} else {
		echo "WARNING: Term not found for slug '{$slug}'\n";
	}
}

// 5. Verify all terms with parent = 0
echo "\n=== Top-level Categories sorted by order ===\n";
$top_cats = get_terms( array(
	'taxonomy'   => 'product_cat',
	'parent'     => 0,
	'hide_empty' => false,
	'meta_key'   => 'order',
	'orderby'    => 'meta_value_num',
	'order'      => 'ASC',
) );
foreach ( $top_cats as $cat ) {
	$ord = get_term_meta( $cat->term_id, 'order', true );
	echo "[Order: {$ord}] {$cat->name} (Slug: {$cat->slug}, ID: {$cat->term_id})\n";
}
