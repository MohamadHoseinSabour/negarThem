<?php
/**
 * Idempotent Demo Content Importer and Initializer
 *
 * Can be executed via WP-CLI:
 * & "X:\apps\wamp\bin\php\php8.2.29\php.exe" "X:\apps\wamp\bin\wp-cli\wp-cli.phar" --path="X:\apps\wamp\www\wp4" eval-file inc/demo-importer.php
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper to get or create attachment for demo media
 *
 * @param int    $post_id Post ID.
 * @param string $relative_path Path relative to wp-content/uploads/kaveh-demo/.
 * @param string $title Attachment title.
 * @return int Attachment ID.
 */
function negar_shop_set_product_demo_thumbnail( $post_id, $relative_path, $title ) {
	$upload_dir = wp_upload_dir();
	$full_path  = $upload_dir['basedir'] . '/kaveh-demo/' . ltrim( $relative_path, '/' );

	if ( ! file_exists( $full_path ) ) {
		echo "  ! فایل تصویر یافت نشد: {$full_path}\n";
		return 0;
	}

	$filename = basename( $full_path );
	global $wpdb;

	// Check if already in media library
	$attachment_id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid LIKE %s",
			'%' . $wpdb->esc_like( $filename )
		)
	);

	if ( ! $attachment_id ) {
		$file_url = $upload_dir['baseurl'] . '/kaveh-demo/' . ltrim( $relative_path, '/' );
		$filetype = wp_check_filetype( $filename, null );

		$attachment = array(
			'guid'           => $file_url,
			'post_mime_type' => ! empty( $filetype['type'] ) ? $filetype['type'] : 'image/png',
			'post_title'     => ! empty( $title ) ? $title : preg_replace( '/\.[^.]+$/', '', $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment, $full_path, $post_id );
		if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attach_data = wp_generate_attachment_metadata( $attachment_id, $full_path );
			wp_update_attachment_metadata( $attachment_id, $attach_data );
		}
	}

	if ( $attachment_id ) {
		set_post_thumbnail( $post_id, $attachment_id );
		update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
		echo "  ✓ تصویر بندانگشتی تنظیم شد (Attachment ID: {$attachment_id}).\n";
	}

	return $attachment_id;
}

/**
 * Main Demo Import Handler
 */
function negar_shop_import_demo_content() {
	echo "\n=======================================================\n";
	echo "  شروع راه‌اندازی و جایگزینی کامل محتوای دمو (Kaveh Demo 43)  \n";
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
			'title'    => 'درباره ما',
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
			echo "✓ برگه '{$data['title']}' موجود است (ID: {$page_id}).\n";
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

	// 2. Product Categories (Digital Goods & Kaveh Demo 43 Taxonomy)
	echo "\n--- مرحله ۲: ساخت ساختار دسته‌بندی‌های دمو کاوه ۴۳ ---\n";
	$categories_data = array(
		'digital-goods' => array(
			'name'     => 'کالای دیجیتال',
			'children' => array(
				'smartphones' => 'گوشی موبایل',
				'laptops'     => 'لپ‌تاپ و کامپیوتر',
				'gaming'      => 'کنسول و بازی',
				'wearables'   => 'ساعت هوشمند و هندزفری',
				'game-acc'    => 'لوازم جانبی گیمینگ',
			),
		),
		'consoles'      => array(
			'name'     => 'کنسول بازی',
			'children' => array(
				'playstation' => 'پلی‌استیشن',
				'xbox'        => 'ایکس‌باکس',
				'games'       => 'بازی‌های ویدئویی',
			),
		),
		'accessories'   => array(
			'name'     => 'لوازم جانبی',
			'children' => array(
				'phone-acc' => 'لوازم جانبی موبایل',
			),
		),
	);

	$cat_term_ids = array();
	foreach ( $categories_data as $parent_slug => $parent_data ) {
		$parent_term = term_exists( $parent_slug, 'product_cat' );
		if ( ! $parent_term ) {
			$inserted  = wp_insert_term( $parent_data['name'], 'product_cat', array( 'slug' => $parent_slug ) );
			$parent_id = is_array( $inserted ) ? $inserted['term_id'] : 0;
			echo "+ دسته اصلی '{$parent_data['name']}' ساخته شد.\n";
		} else {
			$parent_id = is_array( $parent_term ) ? $parent_term['term_id'] : (int) $parent_term;
		}
		$cat_term_ids[ $parent_slug ] = $parent_id;

		if ( ! empty( $parent_data['children'] ) ) {
			foreach ( $parent_data['children'] as $child_slug => $child_name ) {
				$child_term = term_exists( $child_slug, 'product_cat' );
				if ( ! $child_term ) {
					$c_ins    = wp_insert_term(
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

	// 3. Draft/Unpublish old placeholder products
	echo "\n--- مرحله ۳: غیرفعال‌سازی محصولات آزمایشی قدیمی ---\n";
	$old_slugs = array(
		'samsung-refrigerator-rr39-rz32',
		'bosch-microwave-hmt84g451',
		'mebashi-espresso-ecm2016',
		'samsung-tv-55cu7000',
		'philips-vacuum-fc9170',
		'lg-oled-tv-65c3',
		'bosch-washing-machine-wav28k90',
		'samsung-galaxy-s24-ultra',
		'asus-zenbook-pro-duo-ux582',
		'sony-headphone-wh1000xm5',
	);
	foreach ( $old_slugs as $oslug ) {
		$old_p = get_page_by_path( $oslug, OBJECT, 'product' );
		if ( $old_p ) {
			wp_update_post(
				array(
					'ID'          => $old_p->ID,
					'post_status' => 'draft',
				)
			);
			echo "  - محصول قدیمی '{$old_p->post_title}' به پیش‌نویس منتقل شد.\n";
		}
	}

	// 4. Create and populate authentic Kaveh Demo 43 products
	echo "\n--- مرحله ۴: درج و پیکربندی محصولات دمو کاوه ۴۳ ---\n";
	$products_data = array(
		array(
			'name'       => 'بازی EA Sports FC 24 نسخه استاندارد مخصوص PS5',
			'slug'       => 'ea-sports-fc-24-ps5',
			'price'      => 6488600,
			'sale_price' => 4199000,
			'cat'        => 'games',
			'sku'        => 'GAME-EA-FC24-PS5',
			'image'      => 'hero/hero-product-featured.png',
			'short_desc' => 'جدیدترین نسخه بازی محبوب فوتبال الکترونیک آرتز با موتور گرافیکی فراست‌بایت و فناوری HyperMotionV ویژه کنسول پلی‌استیشن ۵.',
			'featured'   => true,
		),
		array(
			'name'       => 'دسته بازی بی‌سیم مایکروسافت مدل Xbox Series X|S سفید',
			'slug'       => 'xbox-series-xs-wireless-controller',
			'price'      => 4850000,
			'sale_price' => 3950000,
			'cat'        => 'game-acc',
			'sku'        => 'ACC-XBX-WHT-CTRL',
			'image'      => 'categories/cat-console.png',
			'short_desc' => 'طراحی ارگونومیک بهبودیافته با دی‌پد هیبریدی، دستگیره‌های بافت‌دار و اتصال بی‌سیم پرسرعت به کنسول و کامپیوتر.',
			'featured'   => true,
		),
		array(
			'name'       => 'کنسول بازی سونی مدل PlayStation 5 Slim دیجیتال',
			'slug'       => 'sony-playstation-5-slim',
			'price'      => 38900000,
			'sale_price' => 34900000,
			'cat'        => 'playstation',
			'sku'        => 'CNS-SONY-PS5-SLIM',
			'image'      => 'categories/cat-console.png',
			'short_desc' => 'کنسول بازی نسل نهم سونی با حافظه ۱ ترابایت SSD فوق پرسرعت، صدای سه‌بعدی Tempest و طراحی فوق‌العاده باریک و سبک.',
			'featured'   => true,
		),
		array(
			'name'       => 'لپ‌تاپ گیمینگ ایسوس مدل ROG Strix G16 G614JIR',
			'slug'       => 'asus-rog-strix-g16',
			'price'      => 118000000,
			'sale_price' => 109500000,
			'cat'        => 'laptops',
			'sku'        => 'LAP-ASUS-G614JIR',
			'image'      => 'products/asus-rog.png',
			'short_desc' => 'پردازنده نسل ۱۴ اینتل Core i9-14900HX، کارت گرافیک RTX 4080 شانزده گیگابایت، رم ۳۲ گیگابایت و نمایشگر ۲۴۰ هرتز ROG Nebula.',
			'featured'   => true,
		),
		array(
			'name'       => 'گوشی موبایل اپل مدل iPhone 16 Pro Max رجیستر شده',
			'slug'       => 'iphone-16-pro-max-512',
			'price'      => 105000000,
			'sale_price' => 98900000,
			'cat'        => 'smartphones',
			'sku'        => 'PHN-APL-16PM-512',
			'image'      => 'products/iphone-17-pro-max.png',
			'short_desc' => 'صفحه نمایش ۶.۹ اینچی Super Retina XDR، تراشه A18 Pro، دکمه Camera Control جدید و بدنه تیتانیوم گرید ۵.',
			'featured'   => true,
		),
		array(
			'name'       => 'گوشی موبایل اپل مدل iPhone 15 دو سیم‌کارت ظرفیت ۱۲۸ گیگابایت',
			'slug'       => 'iphone-15-ch-dual-sim',
			'price'      => 52000000,
			'sale_price' => 47800000,
			'cat'        => 'smartphones',
			'sku'        => 'PHN-APL-IP15-128',
			'image'      => 'products/iphone-15.png',
			'short_desc' => 'مجهز به داینامیک آیلند، دوربین اصلی ۴۸ مگاپیکسل، پورت جهانی USB-C و باتری بهینه‌شده برای تمام روز.',
			'featured'   => true,
		),
		array(
			'name'       => 'لپ‌تاپ لنوو مدل Legion 5 Pro نسل ۱۴ اینتل',
			'slug'       => 'lenovo-legion-5-pro',
			'price'      => 94000000,
			'sale_price' => 87500000,
			'cat'        => 'laptops',
			'sku'        => 'LAP-LEN-LEGION-5P',
			'image'      => 'products/lenovo-legion.png',
			'short_desc' => 'قدرت‌گرفته از هوش مصنوعی لنوو AI Engine+، سیستم خنک‌کننده Legion ColdFront 5.0 و کیبورد اختصاصی TrueStrike.',
			'featured'   => true,
		),
		array(
			'name'       => 'لپ‌تاپ اپل مدل MacBook Pro M3 با صفحه نمایش Liquid Retina',
			'slug'       => 'macbook-pro-16-m3-pro',
			'price'      => 132000000,
			'sale_price' => 124000000,
			'cat'        => 'laptops',
			'sku'        => 'LAP-APL-MBP16-M3',
			'image'      => 'products/macbook-pro-m5.png',
			'short_desc' => 'پردازنده بی‌نظیر M3 Pro با معماری ۳ نانومتری، ۲۲ ساعت شارژدهی باتری شگفت‌انگیز و رنگ جدید مشکی فضایی Space Black.',
			'featured'   => true,
		),
		array(
			'name'       => 'لپ‌تاپ ام‌اس‌آی مدل Vector GP68 HX گیمینگ',
			'slug'       => 'msi-vector-gp68-hx',
			'price'      => 112000000,
			'sale_price' => 104000000,
			'cat'        => 'laptops',
			'sku'        => 'LAP-MSI-VEC-GP68',
			'image'      => 'products/msi-vector.png',
			'short_desc' => 'بهینه‌سازی شده برای مهندسین و گیمرهای حرفه‌ای با پردازنده Core i9 سری HX و حداکثر توان گرافیکی ۱۷۵ وات.',
			'featured'   => true,
		),
		array(
			'name'       => 'گوشی موبایل سامسونگ مدل Galaxy S25 FE 5G',
			'slug'       => 'samsung-galaxy-s25-fe',
			'price'      => 38500000,
			'sale_price' => 34900000,
			'cat'        => 'smartphones',
			'sku'        => 'PHN-SAM-S25FE-256',
			'image'      => 'products/samsung-s25-fe.png',
			'short_desc' => 'طراحی مدرن با حاشیه‌های باریک، امکانات پیشرفته Galaxy AI، دوربین تله‌فوتو با زوم اپتیکال ۳ برابری و نمایشگر ۱۲۰ هرتز Dynamic AMOLED.',
			'featured'   => true,
		),
		array(
			'name'       => 'ساعت هوشمند اپل مدل Apple Watch Ultra 2 تیتانیومی',
			'slug'       => 'apple-watch-ultra-2',
			'price'      => 46000000,
			'sale_price' => 42500000,
			'cat'        => 'wearables',
			'sku'        => 'WCH-APL-ULTRA-2',
			'image'      => 'categories/cat-watch.png',
			'short_desc' => 'روشن‌ترین نمایشگر تاریخ اپل با ۳۰۰۰ نیت روشنایی، جی‌پی‌اس دوفرکانسه فوق دقیق و مقاومت در برابر آب تا عمق ۱۰۰ متر.',
			'featured'   => true,
		),
		array(
			'name'       => 'هندزفری بی‌سیم اپل مدل AirPods Pro 2 با پورت Type-C',
			'slug'       => 'apple-airpods-pro-2',
			'price'      => 14500000,
			'sale_price' => 12900000,
			'cat'        => 'wearables',
			'sku'        => 'ACC-APL-AIRPODS-P2',
			'image'      => 'categories/cat-accessories.png',
			'short_desc' => 'حذف نویز فعال تا ۲ برابر قوی‌تر، شفافیت تطبیقی Adaptive Audio، صدای فضایی شخصی‌سازی‌شده و درگاه مدرن USB-C.',
			'featured'   => true,
		),
	);

	foreach ( $products_data as $pdata ) {
		$existing_post = get_page_by_path( $pdata['slug'], OBJECT, 'product' );
		$product       = null;

		if ( $existing_post ) {
			$product = wc_get_product( $existing_post->ID );
			echo "✓ محصول موجود '{$pdata['name']}' به‌روزرسانی می‌شود (ID: {$existing_post->ID})...\n";
		} else {
			$product = new WC_Product_Simple();
		}

		if ( $product ) {
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

			// Set Thumbnail Image from kaveh-demo directory
			if ( ! empty( $pdata['image'] ) ) {
				negar_shop_set_product_demo_thumbnail( $prod_id, $pdata['image'], $pdata['name'] );
			}

			echo "+ محصول دمو '{$pdata['name']}' با موفقیت ذخیره و مصور شد (ID: {$prod_id}).\n";
		}
	}

	// 5. Navigation Menus
	echo "\n--- مرحله ۵: پیکربندی فهرست‌های ناوبری دمو کاوه ---\n";
	$primary_menu_name = 'منوی اصلی کاوه';
	$primary_menu      = wp_get_nav_menu_object( $primary_menu_name );
	if ( ! $primary_menu ) {
		$primary_menu_id = wp_create_nav_menu( $primary_menu_name );
		echo "+ فهرست اصلی ساخته شد (ID: {$primary_menu_id}).\n";

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
					'menu-item-title'  => 'فروشگاه',
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
					'menu-item-title'  => 'وبلاگ',
					'menu-item-url'    => get_permalink( $created_pages['blog'] ),
					'menu-item-status' => 'publish',
				)
			);
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $primary_menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		echo "✓ فهرست اصلی به موقعیت 'primary' اختصاص یافت.\n";
	}

	// 6. Set Theme Options & Customizer Defaults
	set_theme_mod( 'negar_shop_primary_color', '#e11242' );
	set_theme_mod( 'negar_shop_phone_hotline', '۰۲۱-۸۸۸۸۹۹۹۹' );
	set_theme_mod( 'negar_shop_topbar_announcement', 'هر چیزی که برای استایل کردن لازمه' );

	// Flush rewrite rules
	flush_rewrite_rules();

	echo "\n=======================================================\n";
	echo "  عملیات جایگزینی و مصورسازی محصولات دمو کاوه ۴۳ با موفقیت پایان یافت!  \n";
	echo "=======================================================\n\n";
}

// Auto-run when executed via WP-CLI
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	negar_shop_import_demo_content();
}
