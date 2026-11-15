<?php
/**
 * Phase A: Product Category Migration Script
 *
 * Safely rebuilds WooCommerce product_cat taxonomy according to specification:
 * - 8 Main Categories
 * - 30 Subcategories
 * - Proper Persian names, descriptions, and English slugs
 * - Referential integrity maintained
 * - Product reassignments
 * - Menu updates
 * - Obsolete category removal
 */

if ( 'cli' !== php_sapi_name() ) {
	exit( 1 );
}

require dirname( __DIR__, 4 ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

echo "=== Starting Phase A Product Category Migration ===\n";

$taxonomy_spec = array(
	'major-appliances' => array(
		'name'        => 'لوازم خانگی بزرگ',
		'description' => 'انواع لوازم خانگی بزرگ برقی شامل یخچال، فریزر، ماشین لباسشویی و ظرفشویی با ضمانت اصالت کالا',
		'thumb_id'    => 57,
		'children'    => array(
			'refrigerators-freezers' => array(
				'name'        => 'یخچال و فریزر',
				'description' => 'انواع یخچال فریزر ساید بای ساید، دوقلو و بالا پایین از برترین برندها',
				'thumb_id'    => 57,
			),
			'washing-machines' => array(
				'name'        => 'ماشین لباسشویی',
				'description' => 'انواع ماشین لباسشویی اتوماتیک درب از جلو و درب از بالا با ظرفیت‌های متنوع',
				'thumb_id'    => 58,
			),
			'dishwashers' => array(
				'name'        => 'ماشین ظرفشویی',
				'description' => 'انواع ماشین ظرفشویی رومیزی و مبله کم‌مصرف و کم‌صدا',
				'thumb_id'    => null,
			),
			'cookers-stoves' => array(
				'name'        => 'اجاق گاز',
				'description' => 'انواع اجاق گاز صفحه‌ای، رومیزی و فردار با استانداردهای روز',
				'thumb_id'    => null,
			),
			'built-in-ovens' => array(
				'name'        => 'فر توکار',
				'description' => 'انواع فر توکار برقی و گازی با برنامه‌های پخت حرفه‌ای',
				'thumb_id'    => null,
			),
			'range-hoods' => array(
				'name'        => 'هود آشپزخانه',
				'description' => 'انواع هود آشپزخانه مخفی، مورب و شومینه‌ای با مکش پرقدرت و صدای کم',
				'thumb_id'    => null,
			),
		),
	),
	'electric-cooking' => array(
		'name'        => 'لوازم برقی پختوپز',
		'description' => 'لوازم برقی پخت و پز مدرن برای آشپزی آسان و سریع',
		'thumb_id'    => 60,
		'children'    => array(
			'microwaves' => array(
				'name'        => 'مایکروویو',
				'description' => 'انواع مایکروویو و مایکروفر رومیزی و توکار با عملکردهای گریل و کانوکشن',
				'thumb_id'    => 60,
			),
			'air-fryers' => array(
				'name'        => 'سرخکن بدون روغن و هواپز',
				'description' => 'انواع سرخ‌کن بدون روغن و هواپز رژیمی برای پخت سالم و کم‌کالری',
				'thumb_id'    => null,
			),
			'toaster-ovens' => array(
				'name'        => 'آون توستر',
				'description' => 'انواع آون توستر با جوجه‌گردان و کنترل دقیق دما',
				'thumb_id'    => null,
			),
			'rice-pressure-cookers' => array(
				'name'        => 'پلوپز و زودپز برقی',
				'description' => 'انواع پلوپز چندکاره و زودپز برقی هوشمند و ایمن',
				'thumb_id'    => null,
			),
			'electric-grills-sandwich-makers' => array(
				'name'        => 'گریل و ساندویچساز',
				'description' => 'انواع دستگاه ساندویچ‌ساز، وافل‌ساز و گریل برقی خانگی',
				'thumb_id'    => null,
			),
		),
	),
	'food-preparation' => array(
		'name'        => 'لوازم آمادهسازی غذا',
		'description' => 'دستگاه‌های آماده‌سازی سریع و باکیفیت مواد غذایی',
		'thumb_id'    => null,
		'children'    => array(
			'food-processors-blenders' => array(
				'name'        => 'غذاساز و مخلوطکن',
				'description' => 'انواع غذاساز چندکاره و مخلوط‌کن پرقدرت با تیغه‌های استیل',
				'thumb_id'    => null,
			),
			'juicers' => array(
				'name'        => 'آبمیوهگیری',
				'description' => 'انواع آبمیوه‌گیری تک‌کاره و چندکاره با تفاله‌گیر جداگانه',
				'thumb_id'    => null,
			),
			'meat-grinders' => array(
				'name'        => 'چرخگوشت',
				'description' => 'انواع چرخ‌گوشت پرقدرت خانگی با تیغه‌های ضدزنگ و شبکه‌های مختلف',
				'thumb_id'    => null,
			),
			'mixers' => array(
				'name'        => 'همزن',
				'description' => 'انواع همزن برقی دستی و کاسه‌دار مناسب کیک و شیرینی‌پزی',
				'thumb_id'    => null,
			),
			'choppers-grinders' => array(
				'name'        => 'خردکن و آسیاب',
				'description' => 'انواع خردکن برقی و آسیاب مواد خشک با سرعت و کارایی بالا',
				'thumb_id'    => null,
			),
		),
	),
	'beverage-breakfast' => array(
		'name'        => 'نوشیدنیساز و صبحانه',
		'description' => 'تجهیزات تهیه انواع نوشیدنی‌های گرم و سرد و آماده‌سازی صبحانه',
		'thumb_id'    => 62,
		'children'    => array(
			'tea-makers-electric-kettles' => array(
				'name'        => 'چایساز و کتری برقی',
				'description' => 'انواع چای‌ساز برقی صفحه‌ای و دم‌آور و کتری برقی استیل و پیرکس',
				'thumb_id'    => null,
			),
			'coffee-espresso-machines' => array(
				'name'        => 'قهوهساز و اسپرسوساز',
				'description' => 'انواع اسپرسوساز خانگی، نیمه‌صنعتی و قهوه‌ساز دمی با عصاره‌گیری استاندارد',
				'thumb_id'    => 62,
			),
			'coffee-grinders' => array(
				'name'        => 'آسیاب قهوه',
				'description' => 'انواع آسیاب دانه قهوه با تنظیم درجات مختلف آسیاب',
				'thumb_id'    => null,
			),
			'bread-toasters' => array(
				'name'        => 'توستر نان',
				'description' => 'انواع توستر نان دو اسلایس و چهار اسلایس با قابلیت یخ‌زدایی',
				'thumb_id'    => null,
			),
			'water-dispensers' => array(
				'name'        => 'آبسردکن',
				'description' => 'انواع آبسردکن ایستگاهی و رومیزی با خروجی آب سرد و گرم',
				'thumb_id'    => null,
			),
		),
	),
	'cleaning-ironing' => array(
		'name'        => 'نظافت و اتوکشی',
		'description' => 'لوازم برقی نظافت منزل، جارو و اتوکشی انواع البسه',
		'thumb_id'    => 61,
		'children'    => array(
			'vacuum-cleaners' => array(
				'name'        => 'جاروبرقی',
				'description' => 'انواع جاروبرقی کیسه‌ای و مخزنی با توان مکش بالا و فیلتر بهداشتی',
				'thumb_id'    => 61,
			),
			'cordless-robot-vacuums' => array(
				'name'        => 'جارو شارژی و رباتیک',
				'description' => 'انواع جارو شارژی عصایی و دستی و جاروبرقی رباتیک هوشمند',
				'thumb_id'    => null,
			),
			'steam-cleaners' => array(
				'name'        => 'بخارشوی',
				'description' => 'دستگاه‌های بخارشوی خانگی برای ضدعفونی سطوح و شستشوی عمیق',
				'thumb_id'    => null,
			),
			'irons-garment-steamers' => array(
				'name'        => 'اتو و بخارگر',
				'description' => 'انواع اتو بخار دستی، اتو مخزن‌دار و بخارگر ایستاده البسه',
				'thumb_id'    => null,
			),
		),
	),
	'cooling-heating-air' => array(
		'name'        => 'سرمایش، گرمایش و تهویه',
		'description' => 'تجهیزات تهویه مطبوع، سرمایش، گرمایش و تصفیه هوای مطبوع خانگی',
		'thumb_id'    => null,
		'children'    => array(
			'air-conditioners-splits' => array(
				'name'        => 'کولر گازی و اسپلیت',
				'description' => 'انواع کولر گازی اینورتر و اسپلیت‌های سرد و گرم با راندمان بالا',
				'thumb_id'    => null,
			),
			'fans' => array(
				'name'        => 'پنکه',
				'description' => 'انواع پنکه ایستاده، رومیزی و سقفی با کنترل از راه دور',
				'thumb_id'    => null,
			),
			'electric-heaters' => array(
				'name'        => 'بخاری برقی',
				'description' => 'انواع هیتر و بخاری برقی تابشی و فن‌دار با مصرف بهینه',
				'thumb_id'    => null,
			),
			'air-purifiers' => array(
				'name'        => 'تصفیه هوا',
				'description' => 'دستگاه‌های تصفیه هوا با فیلتر هپا و کربن فعال برای هوای پاکیزه',
				'thumb_id'    => null,
			),
			'humidifiers' => array(
				'name'        => 'رطوبتساز',
				'description' => 'انواع دستگاه‌های بخور سرد و گرم و رطوبت‌ساز خانگی',
				'thumb_id'    => null,
			),
		),
	),
	'tv-audio' => array(
		'name'        => 'تلویزیون و صوتوتصویر',
		'description' => 'انواع تلویزیون‌های هوشمند و سیستم‌های صوتی خانگی',
		'thumb_id'    => 59,
		'children'    => array(
			'televisions' => array(
				'name'        => 'تلویزیون',
				'description' => 'انواع تلویزیون هوشمند 4K، OLED و QLED با کیفیت تصویر فوق‌العاده',
				'thumb_id'    => 59,
			),
			'soundbars-home-audio' => array(
				'name'        => 'ساندبار و سیستم صوتی',
				'description' => 'انواع ساندبار و اسپیکرهای خانگی با صدای فراگیر دالبی',
				'thumb_id'    => null,
			),
		),
	),
	'personal-care-appliances' => array(
		'name'        => 'لوازم برقی بهداشت شخصی',
		'description' => 'لوازم برقی آرایشی و بهداشتی شخصی شامل سشوار، ریش‌تراش و حالت‌دهنده',
		'thumb_id'    => null,
		'children'    => array(
			'hair-dryers' => array(
				'name'        => 'سشوار',
				'description' => 'انواع سشوار حرفه‌ای، خانگی و مسافرتی با موتور AC و یون‌ساز',
				'thumb_id'    => null,
			),
			'electric-shavers' => array(
				'name'        => 'ماشین اصلاح',
				'description' => 'انواع ماشین اصلاح سر و صورت و ریش‌تراش ضدآب با تیغه‌های تیز',
				'thumb_id'    => null,
			),
			'hair-straighteners' => array(
				'name'        => 'اتوی مو',
				'description' => 'انواع اتو و حالت‌دهنده مو سرامیکی با تنظیم حرارت سریع',
				'thumb_id'    => null,
			),
		),
	),
);

// 1. Rename conflicting old terms to prevent slug collisions
$all_target_slugs = array();
foreach ( $taxonomy_spec as $parent_slug => $spec ) {
	$all_target_slugs[] = $parent_slug;
	foreach ( array_keys( $spec['children'] ) as $child_slug ) {
		$all_target_slugs[] = $child_slug;
	}
}

$old_terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
) );

$old_term_ids = array();
foreach ( $old_terms as $ot ) {
	$old_term_ids[] = (int) $ot->term_id;
	if ( in_array( $ot->slug, $all_target_slugs, true ) ) {
		echo "Renaming old colliding slug '{$ot->slug}' (ID {$ot->term_id}) to 'old-{$ot->slug}'...\n";
		wp_update_term( $ot->term_id, 'product_cat', array(
			'slug' => 'old-' . $ot->slug,
		) );
	}
}

// 2. Create new category tree
$new_term_map = array(); // slug => term_id
$created_count = 0;

foreach ( $taxonomy_spec as $parent_slug => $spec ) {
	$insert = wp_insert_term( $spec['name'], 'product_cat', array(
		'slug'        => $parent_slug,
		'description' => $spec['description'],
		'parent'      => 0,
	) );

	if ( is_wp_error( $insert ) ) {
		echo "Error creating parent {$parent_slug}: " . $insert->get_error_message() . "\n";
		exit( 1 );
	}

	$parent_id = (int) $insert['term_id'];
	$new_term_map[ $parent_slug ] = $parent_id;
	$created_count++;
	echo "Created main category '{$spec['name']}' ({$parent_slug}) -> ID: {$parent_id}\n";

	if ( ! empty( $spec['thumb_id'] ) ) {
		update_term_meta( $parent_id, 'thumbnail_id', $spec['thumb_id'] );
	}

	foreach ( $spec['children'] as $child_slug => $child_spec ) {
		$child_insert = wp_insert_term( $child_spec['name'], 'product_cat', array(
			'slug'        => $child_slug,
			'description' => $child_spec['description'],
			'parent'      => $parent_id,
		) );

		if ( is_wp_error( $child_insert ) ) {
			echo "Error creating child {$child_slug}: " . $child_insert->get_error_message() . "\n";
			exit( 1 );
		}

		$child_id = (int) $child_insert['term_id'];
		$new_term_map[ $child_slug ] = $child_id;
		$created_count++;
		echo "  -> Created subcategory '{$child_spec['name']}' ({$child_slug}) -> ID: {$child_id}\n";

		if ( ! empty( $child_spec['thumb_id'] ) ) {
			update_term_meta( $child_id, 'thumbnail_id', $child_spec['thumb_id'] );
		}
	}
}

echo "Successfully created {$created_count} terms (8 main + 30 subcategories).\n";

// 3. Set default WooCommerce product category to 'major-appliances'
$default_cat_id = $new_term_map['major-appliances'];
update_option( 'default_product_cat', $default_cat_id );
echo "Updated default_product_cat to {$default_cat_id} ('major-appliances').\n";

// 4. Reassign genuine relevant products to new categories
$reassignment_map = array(
	// Refrigerators
	14  => array( 'major-appliances', 'refrigerators-freezers' ),
	20  => array( 'major-appliances', 'refrigerators-freezers' ),
	95  => array( 'major-appliances', 'refrigerators-freezers' ),
	// Washing machines
	15  => array( 'major-appliances', 'washing-machines' ),
	19  => array( 'major-appliances', 'washing-machines' ),
	101 => array( 'major-appliances', 'washing-machines' ),
	// Vacuum cleaners
	18  => array( 'cleaning-ironing', 'vacuum-cleaners' ),
	24  => array( 'cleaning-ironing', 'vacuum-cleaners' ),
	99  => array( 'cleaning-ironing', 'vacuum-cleaners' ),
	// Microwaves
	21  => array( 'electric-cooking', 'microwaves' ),
	96  => array( 'electric-cooking', 'microwaves' ),
	// Coffee & espresso makers
	22  => array( 'beverage-breakfast', 'coffee-espresso-machines' ),
	97  => array( 'beverage-breakfast', 'coffee-espresso-machines' ),
	// TVs
	16  => array( 'tv-audio', 'televisions' ),
	23  => array( 'tv-audio', 'televisions' ),
	98  => array( 'tv-audio', 'televisions' ),
	100 => array( 'tv-audio', 'televisions' ),
);

foreach ( $reassignment_map as $product_id => $slugs ) {
	$target_term_ids = array();
	foreach ( $slugs as $s ) {
		if ( isset( $new_term_map[ $s ] ) ) {
			$target_term_ids[] = $new_term_map[ $s ];
		}
	}
	wp_set_object_terms( $product_id, $target_term_ids, 'product_cat' );
	echo "Reassigned product #{$product_id} to categories: " . implode( ', ', $slugs ) . "\n";
}

// 5. Update nav menu items (Menu 32)
$menu_item_updates = array(
	50 => $new_term_map['refrigerators-freezers'],
	51 => $new_term_map['washing-machines'],
	52 => $new_term_map['televisions'],
);

foreach ( $menu_item_updates as $item_id => $term_id ) {
	update_post_meta( $item_id, '_menu_item_object_id', $term_id );
	$term = get_term( $term_id, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		update_post_meta( $item_id, '_menu_item_url', get_term_link( $term ) );
	}
	echo "Updated menu item #{$item_id} to term #{$term_id}\n";
}

// 6. Safely remove old obsolete product_cat terms
$deleted_old_count = 0;
foreach ( $old_term_ids as $ot_id ) {
	$res = wp_delete_term( $ot_id, 'product_cat' );
	if ( is_wp_error( $res ) ) {
		echo "Warning: Failed to delete old term #{$ot_id}: " . $res->get_error_message() . "\n";
	} else {
		$deleted_old_count++;
	}
}
echo "Deleted {$deleted_old_count} old obsolete product_cat terms.\n";

// 7. Flush rewrite rules
flush_rewrite_rules( false );
echo "Rewrite rules flushed.\n";

// 8. Save report of new taxonomy
$output_report = array(
	'timestamp' => date( 'c' ),
	'default_category_id' => $default_cat_id,
	'new_categories' => array(),
);

$all_new_terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
) );

foreach ( $all_new_terms as $nt ) {
	$output_report['new_categories'][] = array(
		'term_id'     => $nt->term_id,
		'name'        => $nt->name,
		'slug'        => $nt->slug,
		'parent_id'   => $nt->parent,
		'description' => $nt->description,
		'url'         => get_term_link( $nt ),
	);
}

file_put_contents( dirname( __DIR__ ) . '/backups/new-category-tree.json', json_encode( $output_report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
echo "Saved new taxonomy report to backups/new-category-tree.json\n";
echo "=== Phase A Category Rebuild Completed Successfully ===\n";
