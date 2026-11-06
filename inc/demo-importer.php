<?php
/**
 * Idempotent Demo Content Importer and Initializer
 *
 * Can be executed via WP-CLI:
 * wp eval-file inc/demo-importer.php
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Demo Import Handler
 */
function negar_shop_import_demo_content() {
	echo "\n=======================================================\n";
	echo "  شروع راه‌اندازی و ایمپورت محتوای آزمایشی نگار شاپ (Negar Shop)  \n";
	echo "=======================================================\n\n";

	// 1. Core Pages
	echo "--- مرحله ۱: بررسی و ساخت برگه‌های اصلی وب‌سایت ---\n";
	$pages_data = array(
		'home'    => array(
			'title'    => 'صفحه اصلی',
			'content'  => '',
			'template' => 'front-page.php',
		),
		'blog'    => array(
			'title'   => 'وبلاگ و مجله اینترنتی',
			'content' => '',
		),
		'about'   => array(
			'title'    => 'درباره نگار شاپ',
			'content'  => '',
			'template' => 'page-templates/page-about.php',
		),
		'contact' => array(
			'title'    => 'تماس با ما',
			'content'  => '',
			'template' => 'page-templates/page-contact.php',
		),
		'faq'     => array(
			'title'   => 'پرسش‌های متداول (FAQ)',
			'content' => '<p>راهنمای خرید، پیگیری سفارش‌ها و قوانین نگار شاپ.</p>',
		),
		'terms'   => array(
			'title'   => 'قوانین و مقررات خرید',
			'content' => '<p>کلیه قوانین و شرایط استفاده از خدمات نگار شاپ مطابق با قانون تجارت الکترونیک است.</p>',
		),
	);

	$created_pages = array();
	foreach ( $pages_data as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_id = $existing->ID;
			echo "✓ برگه '{$data['title']}' قبلاً ایجاد شده بود (ID: {$page_id}).\n";
		} else {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $data['title'],
					'post_name'    => $slug,
					'post_content' => $data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
				)
			);
			if ( ! empty( $data['template'] ) ) {
				update_post_meta( $page_id, '_wp_page_template', $data['template'] );
			}
			echo "+ برگه '{$data['title']}' با موفقیت ساخته شد (ID: {$page_id}).\n";
		}
		$created_pages[ $slug ] = $page_id;
	}

	// Update Reading Options (Static Home & Posts page)
	if ( ! empty( $created_pages['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $created_pages['home'] );
		echo "✓ برگه خانگی ایستا روی 'صفحه اصلی' تنظیم شد.\n";
	}
	if ( ! empty( $created_pages['blog'] ) ) {
		update_option( 'page_for_posts', $created_pages['blog'] );
		echo "✓ برگه مقالات وبلاگ تنظیم شد.\n";
	}

	// 2. Product Categories
	echo "\n--- مرحله ۲: بررسی و ساخت ساختار دسته‌بندی‌های محصولات ---\n";
	$categories_data = array(
		'digital-goods'   => array(
			'name'     => 'کالای دیجیتال',
			'children' => array(
				'smartphones' => 'گوشی موبایل',
				'laptops'     => 'لپ‌تاپ و اولترابوک',
				'wearables'   => 'هندزفری و ساعت هوشمند',
			),
		),
		'home-appliances' => array(
			'name'     => 'لوازم خانگی برقی',
			'children' => array(
				'refrigerators'   => 'یخچال و فریزر',
				'washing-machines' => 'ماشین لباسشویی',
				'microwaves'      => 'مایکروویو و توستر',
				'vacuum-cleaners' => 'جاروبرقی و نظافت',
			),
		),
		'audio-video'     => array(
			'name'     => 'صوتی و تصویری',
			'children' => array(
				'smart-tvs' => 'تلویزیون هوشمند',
				'speakers'  => 'ساندبار و اسپیکر',
			),
		),
		'beauty-health'   => array(
			'name'     => 'زیبایی و سلامت',
			'children' => array(
				'hair-dryer' => 'سشوار و حالت‌دهنده مو',
				'shaver'     => 'اصلاح سر و صورت',
			),
		),
	);

	$cat_term_ids = array();
	foreach ( $categories_data as $parent_slug => $parent_data ) {
		$parent_term = term_exists( $parent_slug, 'product_cat' );
		if ( ! $parent_term ) {
			$inserted = wp_insert_term( $parent_data['name'], 'product_cat', array( 'slug' => $parent_slug ) );
			$parent_id = is_array( $inserted ) ? $inserted['term_id'] : 0;
			echo "+ دسته اصلی '{$parent_data['name']}' ساخته شد.\n";
		} else {
			$parent_id = is_array( $parent_term ) ? $parent_term['term_id'] : (int) $parent_term;
			echo "✓ دسته اصلی '{$parent_data['name']}' وجود دارد.\n";
		}
		$cat_term_ids[ $parent_slug ] = $parent_id;

		if ( ! empty( $parent_data['children'] ) ) {
			foreach ( $parent_data['children'] as $child_slug => $child_name ) {
				$child_term = term_exists( $child_slug, 'product_cat' );
				if ( ! $child_term ) {
					$c_ins = wp_insert_term(
						$child_name,
						'product_cat',
						array(
							'slug'   => $child_slug,
							'parent' => $parent_id,
						)
					);
					$child_id = is_array( $c_ins ) ? $c_ins['term_id'] : 0;
					echo "  + زیردسته '{$child_name}' ساخته شد.\n";
				} else {
					$child_id = is_array( $child_term ) ? $child_term['term_id'] : (int) $child_term;
				}
				$cat_term_ids[ $child_slug ] = $child_id;
			}
		}
	}

	// 3. Demo Products
	echo "\n--- مرحله ۳: بررسی و ساخت محصولات نمونه ووکامرس ---\n";
	$products_data = array(
		array(
			'name'          => 'یخچال و فریزر دوقلو سامسونگ مدل RR39 و RZ32 بدون برفک نقره‌ای',
			'slug'          => 'samsung-refrigerator-rr39-rz32',
			'price'         => 85000000,
			'sale_price'    => 78900000,
			'cat'           => 'refrigerators',
			'sku'           => 'SAM-REF-3932',
			'short_desc'    => 'مجهز به کمپرسور اینورتر دیجیتال، رده مصرف انرژی +A، سیستم خنک‌کننده همه‌جانبه All-Around Cooling و فیلتر بوزدای فعال.',
			'featured'      => true,
		),
		array(
			'name'          => 'مایکروویو ۳۲ لیتری بوش مدل HMT84G451 استیل ضد زنگ',
			'slug'          => 'bosch-microwave-hmt84g451',
			'price'         => 18500000,
			'sale_price'    => 16900000,
			'cat'           => 'microwaves',
			'sku'           => 'BOS-MW-8445',
			'short_desc'    => 'ظرفیت ۳۲ لیتر، توان پخت ۱۰۰۰ وات، مجهز به گریل ۱۲۰۰ وات و ۸ برنامه پخت اتوماتیک آسان.',
			'featured'      => false,
		),
		array(
			'name'          => 'اسپرسوساز مباشی مدل ECM2016 با فشار بخار ۲۰ بار ایتالیایی',
			'slug'          => 'mebashi-espresso-ecm2016',
			'price'         => 5400000,
			'sale_price'    => 4850000,
			'cat'           => 'home-appliances',
			'sku'           => 'MEB-ESP-2016',
			'short_desc'    => 'فشار ۲۰ بار پمپ بخار ایتالیایی اولکا، توان ۱۳۵۰ وات، نازل بخار چرخشی جهت تهیه فوم شیر غلیظ و کاپوچینو.',
			'featured'      => true,
		),
		array(
			'name'          => 'تلویزیون ۵۵ اینچ سامسونگ کریستال 4K مدل 55CU7000 مدل ۲۰۲۳',
			'slug'          => 'samsung-tv-55cu7000',
			'price'         => 34500000,
			'sale_price'    => 31900000,
			'cat'           => 'smart-tvs',
			'sku'           => 'SAM-TV-55CU7',
			'short_desc'    => 'رزولوشن 4K Ultra HD، پردازنده Crystal Processor 4K، پشتیبانی از +HDR10 و سیستم عامل تایزن هوشمند.',
			'featured'      => true,
		),
		array(
			'name'          => 'جاروبرقی فیلیپس ۲۰۰۰ وات مدل FC9170 ساخت لهستان',
			'slug'          => 'philips-vacuum-fc9170',
			'price'         => 14200000,
			'sale_price'    => 12800000,
			'cat'           => 'vacuum-cleaners',
			'sku'           => 'PHI-VAC-9170',
			'short_desc'    => 'توان مکش ۵۰۰ وات واقعی، مجهز به فیلتر هپا ۱۳ ضد آلرژی، کیسه با ظرفیت ۴ لیتر و کابل بلند با شعاع کاری ۱۲ متر.',
			'featured'      => true,
		),
		array(
			'name'          => 'تلویزیون هوشمند ۶۵ اینچ ال‌جی OLED مدل C3 با پنل 4K 120Hz',
			'slug'          => 'lg-oled-tv-65c3',
			'price'         => 98000000,
			'sale_price'    => 92500000,
			'cat'           => 'smart-tvs',
			'sku'           => 'LG-OLED-65C3',
			'short_desc'    => 'پنل OLED evo با روشنایی فوق‌العاده، پردازنده α9 AI 4K Gen6، نرخ رفرش ۱۲۰ هرتز مخصوص گیمینگ، Dolby Vision و Atmos.',
			'featured'      => true,
		),
		array(
			'name'          => 'ماشین لباسشویی ۹ کیلویی بوش مدل WAV28K90ME سری ۸ آلمان',
			'slug'          => 'bosch-washing-machine-wav28k90',
			'price'         => 59000000,
			'sale_price'    => 54500000,
			'cat'           => 'washing-machines',
			'sku'           => 'BOS-WASH-9K',
			'short_desc'    => 'موتور بی‌صدا EcoSilence Drive، سرعت چرخش ۱۴۰۰ دور در دقیقه، فناوری لکه‌بری هوشمند و سیستم ضد نشتی AquaStop.',
			'featured'      => true,
		),
		array(
			'name'          => 'گوشی موبایل سامسونگ گلکسی S24 Ultra حافظه ۵۱۲ گیگابایت',
			'slug'          => 'samsung-galaxy-s24-ultra',
			'price'         => 72000000,
			'sale_price'    => 68500000,
			'cat'           => 'smartphones',
			'sku'           => 'SAM-S24U-512',
			'short_desc'    => 'فریم تیتانیومی مقاوم، پردازنده اسنپ‌دراگون ۸ نسل ۳، دوربین ۲۰۰ مگاپیکسلی با زوم ۱۰۰ برابری و هوش مصنوعی Galaxy AI.',
			'featured'      => true,
		),
		array(
			'name'          => 'لپ‌تاپ ۱۵.۶ اینچی ایسوس مدل ZenBook Pro Duo با دو نمایشگر',
			'slug'          => 'asus-zenbook-pro-duo-ux582',
			'price'         => 125000000,
			'sale_price'    => 119000000,
			'cat'           => 'laptops',
			'sku'           => 'ASU-ZEN-UX582',
			'short_desc'    => 'پردازنده Core i9 نسل ۱۳، رم ۳۲ گیگابایت DDR5، گرافیک RTX 4080، صفحه نمایش لمسی OLED 4K و قلم هوشمند ایسوس.',
			'featured'      => true,
		),
		array(
			'name'          => 'هدفون بی‌سیم سونی مدل WH-1000XM5 با نویزکنسلینگ فوق‌پیشرفته',
			'slug'          => 'sony-headphone-wh1000xm5',
			'price'         => 19500000,
			'sale_price'    => 17900000,
			'cat'           => 'wearables',
			'sku'           => 'SNY-WH-1000M5',
			'short_desc'    => 'بهترین فناوری حذف نویز در کلاس جهانی، دو پردازنده اختصاصی V1 و QN1، شارژدهی تا ۳۰ ساعت و کیفیت صدای High-Resolution.',
			'featured'      => true,
		),
	);

	foreach ( $products_data as $pdata ) {
		$existing_post = get_page_by_path( $pdata['slug'], OBJECT, 'product' );
		if ( $existing_post ) {
			echo "✓ محصول '{$pdata['name']}' قبلاً موجود بود (ID: {$existing_post->ID}).\n";
			continue;
		}

		if ( class_exists( 'WC_Product_Simple' ) ) {
			$product = new WC_Product_Simple();
			$product->set_name( $pdata['name'] );
			$product->set_slug( $pdata['slug'] );
			$product->set_status( 'publish' );
			$product->set_short_description( $pdata['short_desc'] );
			$product->set_regular_price( (string) $pdata['price'] );
			if ( ! empty( $pdata['sale_price'] ) ) {
				$product->set_sale_price( (string) $pdata['sale_price'] );
				$product->set_price( (string) $pdata['sale_price'] );
			} else {
				$product->set_price( (string) $pdata['price'] );
			}
			$product->set_sku( $pdata['sku'] );
			$product->set_manage_stock( false );
			$product->set_stock_status( 'instock' );
			$product->set_featured( $pdata['featured'] );

			// Category Assignment
			if ( ! empty( $pdata['cat'] ) && ! empty( $cat_term_ids[ $pdata['cat'] ] ) ) {
				$product->set_category_ids( array( $cat_term_ids[ $pdata['cat'] ] ) );
			}

			$prod_id = $product->save();
			echo "+ محصول ووکامرس '{$pdata['name']}' با موفقیت درج شد (ID: {$prod_id}).\n";
		}
	}

	// 4. Navigation Menus
	echo "\n--- مرحله ۴: بررسی و ساخت منوهای ناوبری و اختصاص موقعیت‌ها ---\n";
	$primary_menu_name = 'منوی ناوبری اصلی';
	$primary_menu = wp_get_nav_menu_object( $primary_menu_name );
	if ( ! $primary_menu ) {
		$primary_menu_id = wp_create_nav_menu( $primary_menu_name );
		echo "+ منوی اصلی ساخته شد (ID: {$primary_menu_id}).\n";

		// Add Items
		wp_update_nav_menu_item(
			$primary_menu_id,
			0,
			array(
				'menu-item-title'  => 'صفحه اصلی',
				'menu-item-url'    => home_url( '/' ),
				'menu-item-status' => 'publish',
			)
		);
		if ( function_exists( 'wc_get_page_permalink' ) ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'  => 'فروشگاه و محصولات',
					'menu-item-url'    => wc_get_page_permalink( 'shop' ),
					'menu-item-status' => 'publish',
				)
			);
		}
		if ( ! empty( $created_pages['about'] ) ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'  => 'درباره ما',
					'menu-item-url'    => get_permalink( $created_pages['about'] ),
					'menu-item-status' => 'publish',
				)
			);
		}
		if ( ! empty( $created_pages['contact'] ) ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'  => 'تماس با ما',
					'menu-item-url'    => get_permalink( $created_pages['contact'] ),
					'menu-item-status' => 'publish',
				)
			);
		}
		if ( ! empty( $created_pages['blog'] ) ) {
			wp_update_nav_menu_item(
				$primary_menu_id,
				0,
				array(
					'menu-item-title'  => 'وبلاگ و مقالات',
					'menu-item-url'    => get_permalink( $created_pages['blog'] ),
					'menu-item-status' => 'publish',
				)
			);
		}
	} else {
		$primary_menu_id = $primary_menu->term_id;
		echo "✓ منوی اصلی از قبل موجود است (ID: {$primary_menu_id}).\n";
	}

	// Assign Menu Locations
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']  = $primary_menu_id;
	$locations['mobile']   = $primary_menu_id;
	$locations['footer-1'] = $primary_menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
	echo "✓ موقعیت‌های منو (primary, mobile, footer-1) به منوی اصلی متصل شدند.\n";

	// 5. WooCommerce Store Settings
	echo "\n--- مرحله ۵: اعمال تنظیمات ارزی و قیمت‌گذاری ووکامرس ---\n";
	update_option( 'woocommerce_currency', 'IRT' );
	update_option( 'woocommerce_currency_pos', 'right_space' );
	update_option( 'woocommerce_price_num_decimals', 0 );
	echo "✓ واحد پول ووکامرس روی 'تومان' (IRT) و بدون اعشار تنظیم شد.\n";

	echo "\n=======================================================\n";
	echo "  راه‌اندازی کامل دمو با موفقیت انجام پذیرفت! (Idempotent Success)  \n";
	echo "=======================================================\n\n";
}

// Automatically trigger if run via CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	negar_shop_import_demo_content();
}
