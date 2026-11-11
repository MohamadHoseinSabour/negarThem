<?php
/**
 * Shop Sidebar Filters Component - Exact Match to Demo 43 (category-nk__filter)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

// Retrieve prices
global $wpdb;
$prices = $wpdb->get_row( "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_p, MAX(CAST(meta_value AS DECIMAL(10,2))) as max_p FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value > 0" );
$lowest_price  = ( $prices && $prices->min_p ) ? (int) $prices->min_p : 10000;
$highest_price = ( $prices && $prices->max_p ) ? (int) $prices->max_p : 100000000;

$min_price = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : '';
$max_price = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : '';
$in_stock  = isset( $_GET['in_stock'] ) && '1' === $_GET['in_stock'];
$on_sale   = isset( $_GET['on_sale'] ) && '1' === $_GET['on_sale'];

// Retrieve categories
$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$categories_by_parent = array();
if ( ! is_wp_error( $categories ) ) {
	foreach ( $categories as $category ) {
		$parent_id = (int) $category->parent;
		if ( ! isset( $categories_by_parent[ $parent_id ] ) ) {
			$categories_by_parent[ $parent_id ] = array();
		}
		$categories_by_parent[ $parent_id ][] = $category;
	}
}

$render_category_filter_items = null;
$render_category_filter_items = static function ( $parent_id = 0, $depth = 0 ) use ( &$render_category_filter_items, $categories_by_parent ) {
	if ( empty( $categories_by_parent[ $parent_id ] ) ) {
		return;
	}

	foreach ( $categories_by_parent[ $parent_id ] as $category ) {
		$has_children = ! empty( $categories_by_parent[ $category->term_id ] );
		$item_classes = array(
			'category-filter-item',
			'category-filter-item--depth-' . $depth,
			0 === $depth ? 'category-filter-item--parent' : 'category-filter-item--child',
		);

		if ( $has_children ) {
			$item_classes[] = 'category-filter-item--has-children';
		}

		echo '<li class="' . esc_attr( implode( ' ', $item_classes ) ) . '" style="--category-depth: ' . esc_attr( min( $depth, 4 ) ) . ';">';
		echo '<input type="checkbox" name="category" id="category-' . esc_attr( $category->term_id ) . '" myid="' . esc_attr( $category->term_id ) . '" hidden="" autocomplete="off">';
		echo '<label for="category-' . esc_attr( $category->term_id ) . '" class="d-flex align-items-center gap-3 f-payda fw-normal">';
		echo '<span class="category-filter-item__control" aria-hidden="true"></span>';
		echo '<span class="category-filter-item__name">' . esc_html( $category->name ) . '</span>';
		if ( 0 === $depth ) {
			echo '<span class="category-filter-item__kind">دسته اصلی</span>';
		}
		echo '</label></li>';
		$render_category_filter_items( (int) $category->term_id, $depth + 1 );
	}
};

// Retrieve brands
$brands = get_terms(
	array(
		'taxonomy'   => 'product_brand',
		'hide_empty' => true,
	)
);
?>

<div id="filter" class="category-nk__filter d-flex flex-column justify-content-end justify-content-lg-start">
	<div class="category-nk__filter__backdrop position-fixed top-0 end-0 w-100 h-100 d-lg-none" data-close="#filter"></div>
	<div class="category-nk__filter__content bg-white d-flex flex-column">

		<!-- Filter Heading -->
		<div class="category-nk__filter__heading d-flex align-items-center gap-2 f-payda fw-semibold">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 18 18" aria-hidden="true">
				<path fill="#000" d="M3.75 12.9c0-.84 0-1.26.163-1.581a1.5 1.5 0 0 1 .656-.655c.32-.164.74-.164 1.581-.164h1.2c.84 0 1.26 0 1.581.164a1.5 1.5 0 0 1 .656.655c.163.32.163.74.163 1.581v1.2c0 .84 0 1.26-.163 1.581a1.5 1.5 0 0 1-.656.656c-.32.163-.74.163-1.581.163h-1.2c-.84 0-1.26 0-1.581-.163a1.5 1.5 0 0 1-.656-.656c-.163-.32-.163-.74-.163-1.581v-1.2Zm4.5-9c0-.84 0-1.26.163-1.581a1.5 1.5 0 0 1 .656-.656c.32-.163.74-.163 1.581-.163h1.2c.84 0 1.26 0 1.581.163a1.5 1.5 0 0 1 .655.656c.164.32.164.74.164 1.581v1.2c0 .84 0 1.26-.164 1.581a1.5 1.5 0 0 1-.655.656c-.32.163-.74.163-1.581.163h-1.2c-.84 0-1.26 0-1.581-.163a1.5 1.5 0 0 1-.656-.656c-.163-.32-.163-.74-.163-1.581V3.9Z" opacity=".12"></path>
				<path stroke="#000" stroke-linecap="round" stroke-width="1.125" d="M1.5 13.5h2.25m10.5-9h2.25m-4.5 9h4.5m-15-9H6m.15 12h1.2c.84 0 1.26 0 1.581-.163a1.5 1.5 0 0 0 .656-.656c.163-.32.163-.74.163-1.581v-1.2c0-.84 0-1.26-.163-1.581a1.5 1.5 0 0 0-.656-.655c-.32-.164-.74-.164-1.581-.164h-1.2c-.84 0-1.26 0-1.581.164a1.5 1.5 0 0 0-.656.655c-.163.32-.163.74-.163 1.581v1.2c0 .84 0 1.26.163 1.581a1.5 1.5 0 0 0 .656.656c.32.163.74.163 1.581.163Zm4.5-9h1.2c.84 0 1.26 0 1.581-.163a1.5 1.5 0 0 0 .655-.656c.164-.32.164-.74.164-1.581V3.9c0-.84 0-1.26-.164-1.581a1.5 1.5 0 0 0-.655-.656c-.32-.163-.74-.163-1.581-.163h-1.2c-.84 0-1.26 0-1.581.163a1.5 1.5 0 0 0-.656.656c-.163.32-.163.74-.163 1.581v1.2c0 .84 0 1.26.163 1.581a1.5 1.5 0 0 0 .656.656c.32.163.74.163 1.581.163Z"></path>
			</svg>
			فیلترها
			<button type="button" class="category-nk__filter__close d-lg-none ms-auto p-0" data-close="#filter">
				<i class="icon-close fs-5 lh-1"></i>
			</button>
		</div>

		<div class="category-nk__filter__items py-4 px-3 d-flex flex-column gap-2">

			<!-- 1. Stock / Sale Switcher -->
			<div class="widget products-only-filter-widget">
				<div class="category-nk__filter__box category-nk__filter__products-only d-flex flex-column gap-4">
					<div class="category-nk__filter__products-only__item">
						<input type="checkbox" name="exists" id="exists-products" class="kaveh-filter-onstock" hidden="" value="1" autocomplete="off" <?php checked( $in_stock ); ?>>
						<label for="exists-products" class="d-flex align-items-center justify-content-between gap-2 f-payda">
							فقط محصولات موجود
							<span class="position-relative flex-fill flex-grow-0 flex-shrink-0" aria-hidden="true"></span>
						</label>
					</div>
					<div class="category-nk__filter__products-only__item">
						<input type="checkbox" name="off" id="onlysale" class="kaveh-filter-onsale" hidden="" value="1" autocomplete="off" <?php checked( $on_sale ); ?>>
						<label for="onlysale" class="d-flex align-items-center justify-content-between gap-2 f-payda">
							فقط تخفیف دار
							<span class="position-relative flex-fill flex-grow-0 flex-shrink-0" aria-hidden="true"></span>
						</label>
					</div>
				</div>
			</div>

			<!-- 2. Price Range Slider -->
			<div class="widget range-price-widget">
				<div class="category-nk__filter__box category-nk__filter__range-price">
					<div class="category-nk__filter__box__heading d-flex align-items-center gap-2 f-payda fw-semibold" data-bs-toggle="collapse" data-bs-target="#range-price-box" aria-expanded="true">
						<i class="icon icon-sliders-horiz lh-1" aria-hidden="true"></i>
						<span>بر اساس قیمت</span>
						<i class="arrow icon-chevron-down-2 lh-1 ms-auto" aria-hidden="true"></i>
					</div>
					<div id="range-price-box" class="collapse show">
						<div class="category-nk__filter__range-price__field d-flex align-items-center gap-3 mt-4 mb-2">
							<span class="f-payda">از</span>
							<div class="position-relative flex-grow-1">
								<input type="text" name="min" value="<?php echo esc_attr( $min_price ); ?>" id="category-price-min" class="form-control f-payda" inputmode="numeric" autocomplete="off">
								<span class="toman-symbol toman-symbol--modern category-nk__filter__range-price__currency lh-1 position-absolute top-50 translate-middle-y" aria-hidden="true">
									<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a"><use href="#kaveh-toman-mark"></use></svg>
								</span>
							</div>
						</div>
						<div class="category-nk__filter__range-price__field d-flex align-items-center gap-3">
							<span class="f-payda">تا</span>
							<div class="position-relative flex-grow-1">
								<input type="text" name="max" value="<?php echo esc_attr( $max_price ); ?>" id="category-price-max" class="form-control f-payda" inputmode="numeric" autocomplete="off">
								<span class="toman-symbol toman-symbol--modern category-nk__filter__range-price__currency lh-1 position-absolute top-50 translate-middle-y" aria-hidden="true">
									<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a"><use href="#kaveh-toman-mark"></use></svg>
								</span>
							</div>
						</div>
						<div id="category-price" class="range-price-nk mt-4 mb-3"></div>
						<div class="range-price-text d-flex align-items-center justify-content-end gap-1 f-payda">
							<div>
								از <span id="range-price-text-min"><?php echo esc_html( number_format_i18n( (float) $lowest_price ) ); ?></span>
								تا <span id="range-price-text-max"><?php echo esc_html( number_format_i18n( (float) $highest_price ) ); ?></span>
							</div>
							<span class="toman-symbol toman-symbol--modern category-nk__filter__range-price__currency lh-1" aria-hidden="true">
								<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a"><use href="#kaveh-toman-mark"></use></svg>
							</span>
						</div>
					</div>
				</div>
			</div>

			<!-- 3. Category Filter -->
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
			<div class="widget category-filter-widget">
				<div class="category-nk__filter__box category-nk__filter__categories">
					<div class="category-nk__filter__box__heading d-flex align-items-center gap-2 f-payda fw-semibold" data-bs-toggle="collapse" data-bs-target="#category-filter-box" aria-expanded="true">
						<i class="icon icon-menu-rec lh-1" aria-hidden="true"></i>
						<span>بر اساس دسته‌بندی</span>
						<i class="arrow icon-chevron-down-2 lh-1 ms-auto" aria-hidden="true"></i>
					</div>
					<div id="category-filter-box" class="collapse show">
						<div class="category-nk__filter__search position-relative mt-4 mb-3">
							<i class="icon-search-4 position-absolute top-50 translate-middle-y lh-1" aria-hidden="true"></i>
							<input type="search" class="form-control category-nk__filter__search-input f-payda shadow-none" placeholder="جستجوی دسته‌بندی‌ها..." autocomplete="off">
						</div>
						<div class="category-nk__filter__categories__title f-payda mb-3">همه محصولات</div>
						<div class="category-nk__filter__scroll">
							<ul class="category-nk__filter__categories__items cathidden d-flex flex-column gap-3">
								<input class="filterhiddeninput" type="hidden" id="filters-category" autocomplete="off" value="">
								<?php $render_category_filter_items(); ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- 4. Brand Filter -->
			<?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
			<div class="widget brand-filter-widget">
				<div class="category-nk__filter__box category-nk__filter__categories category-nk__filter__brands">
					<div class="category-nk__filter__box__heading d-flex align-items-center gap-2 f-payda fw-semibold" data-bs-toggle="collapse" data-bs-target="#brand-filter-box" aria-expanded="true">
						<i class="icon icon-menu-rec lh-1" aria-hidden="true"></i>
						<span>بر اساس برند</span>
						<i class="arrow icon-chevron-down-2 lh-1 ms-auto" aria-hidden="true"></i>
					</div>
					<div id="brand-filter-box" class="collapse show">
						<div class="category-nk__filter__search position-relative mt-4 mb-3">
							<i class="icon-search-4 position-absolute top-50 translate-middle-y lh-1" aria-hidden="true"></i>
							<input type="search" class="form-control category-nk__filter__search-input f-payda shadow-none" placeholder="جستجوی برندها..." autocomplete="off">
						</div>
						<div class="category-nk__filter__categories__title f-payda mb-3">همه برندها</div>
						<div class="category-nk__filter__scroll">
							<ul class="category-nk__filter__categories__items brandhidden d-flex flex-column gap-3">
								<input class="filterhiddeninputbrand" type="hidden" id="filters-brand" autocomplete="off" value="">
								<?php foreach ( $brands as $brand ) : ?>
									<li>
										<input type="checkbox" name="brand" id="brand-<?php echo esc_attr( $brand->term_id ); ?>" myid="<?php echo esc_attr( $brand->term_id ); ?>" hidden="" autocomplete="off">
										<label for="brand-<?php echo esc_attr( $brand->term_id ); ?>" class="d-flex align-items-center gap-3 f-payda fw-normal">
											<span class="position-relative rounded-circle flex-fill flex-grow-0 flex-shrink-0" aria-hidden="true"></span>
											<?php echo esc_html( $brand->name ); ?>
										</label>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</div>
	</div>
</div>
