<?php
/**
 * Shop Sidebar Filters Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$price_range   = negar_shop_get_price_range();
$current_cat   = is_product_category() ? get_queried_object()->slug : ( isset( $_GET['product_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_min   = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_max   = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$in_stock      = isset( $_GET['in_stock'] ) && '1' === $_GET['in_stock']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$on_sale       = isset( $_GET['on_sale'] ) && '1' === $_GET['on_sale']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
	)
);
?>

<div class="negar-sidebar-filters flex flex-col gap-5">

	<!-- Widget 1: Categories -->
	<div class="negar-filter-widget bg-surface border border-border rounded-lg p-4 shadow-sm">
		<h4 class="negar-filter-widget-title text-sm font-bold text-dark pb-3 mb-3 border-b border-border flex items-center justify-between">
			<span><?php esc_html_e( 'دسته‌بندی محصولات', 'negar-shop' ); ?></span>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
		</h4>
		<ul class="negar-filter-categories flex flex-col gap-2 max-h-60 overflow-y-auto pr-1">
			<li>
				<label class="flex items-center justify-between text-xs sm:text-sm text-dark hover:text-primary cursor-pointer transition-colors py-1">
					<span class="inline-flex items-center gap-2">
						<input type="radio" name="product_cat" value="" <?php checked( empty( $current_cat ) ); ?> class="negar-filter-cat-radio text-primary focus:ring-primary h-4 w-4">
						<span><?php esc_html_e( 'همه دسته‌ها', 'negar-shop' ); ?></span>
					</span>
				</label>
			</li>
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<?php foreach ( $categories as $cat ) : ?>
					<li>
						<label class="flex items-center justify-between text-xs sm:text-sm text-dark hover:text-primary cursor-pointer transition-colors py-1">
							<span class="inline-flex items-center gap-2">
								<input type="radio" name="product_cat" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( $current_cat === $cat->slug ); ?> class="negar-filter-cat-radio text-primary focus:ring-primary h-4 w-4">
								<span><?php echo esc_html( $cat->name ); ?></span>
							</span>
							<span class="text-xs text-muted font-normal bg-slate-100 px-2 py-0.5 rounded-full">
								<?php echo esc_html( number_format_i18n( $cat->count ) ); ?>
							</span>
						</label>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>

	<!-- Widget 2: Availability & Deals Toggles -->
	<div class="negar-filter-widget bg-surface border border-border rounded-lg p-4 shadow-sm flex flex-col gap-3">
		<!-- In Stock Toggle -->
		<label class="flex items-center justify-between cursor-pointer py-1">
			<span class="text-xs sm:text-sm font-medium text-dark"><?php esc_html_e( 'فقط کالاهای موجود', 'negar-shop' ); ?></span>
			<span class="relative inline-flex items-center">
				<input type="checkbox" id="negar-filter-in-stock" name="in_stock" value="1" <?php checked( $in_stock ); ?> class="sr-only peer">
				<span class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary transition-colors"></span>
			</span>
		</label>

		<hr class="border-border">

		<!-- On Sale Toggle -->
		<label class="flex items-center justify-between cursor-pointer py-1">
			<span class="text-xs sm:text-sm font-medium text-dark"><?php esc_html_e( 'فقط کالاهای تخفیف‌دار', 'negar-shop' ); ?></span>
			<span class="relative inline-flex items-center">
				<input type="checkbox" id="negar-filter-on-sale" name="on_sale" value="1" <?php checked( $on_sale ); ?> class="sr-only peer">
				<span class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-danger transition-colors"></span>
			</span>
		</label>
	</div>

	<!-- Widget 3: Price Range -->
	<div class="negar-filter-widget bg-surface border border-border rounded-lg p-4 shadow-sm">
		<h4 class="negar-filter-widget-title text-sm font-bold text-dark pb-3 mb-3 border-b border-border flex items-center justify-between">
			<span><?php esc_html_e( 'محدوده قیمت (تومان)', 'negar-shop' ); ?></span>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
		</h4>
		<div class="flex flex-col gap-3">
			<div class="flex items-center gap-2">
				<div class="flex-1">
					<label for="negar-min-price" class="text-xs text-muted block mb-1"><?php esc_html_e( 'از', 'negar-shop' ); ?></label>
					<input type="number" id="negar-min-price" name="min_price" value="<?php echo esc_attr( $current_min ); ?>" placeholder="<?php echo esc_attr( number_format_i18n( $price_range['min'] ) ); ?>" min="<?php echo esc_attr( $price_range['min'] ); ?>" max="<?php echo esc_attr( $price_range['max'] ); ?>" step="50000" class="w-full text-xs p-2 rounded border border-border bg-slate-50 focus:border-primary focus:bg-white transition-all text-left dir-ltr">
				</div>
				<span class="text-muted text-xs mt-4">-</span>
				<div class="flex-1">
					<label for="negar-max-price" class="text-xs text-muted block mb-1"><?php esc_html_e( 'تا', 'negar-shop' ); ?></label>
					<input type="number" id="negar-max-price" name="max_price" value="<?php echo esc_attr( $current_max ); ?>" placeholder="<?php echo esc_attr( number_format_i18n( $price_range['max'] ) ); ?>" min="<?php echo esc_attr( $price_range['min'] ); ?>" max="<?php echo esc_attr( $price_range['max'] ); ?>" step="50000" class="w-full text-xs p-2 rounded border border-border bg-slate-50 focus:border-primary focus:bg-white transition-all text-left dir-ltr">
				</div>
			</div>
			<button type="button" id="negar-apply-price-filter" class="btn btn-outline btn-sm w-full py-1.5 text-xs inline-flex items-center justify-center gap-1">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
				<span><?php esc_html_e( 'اعمال فیلتر قیمت', 'negar-shop' ); ?></span>
			</button>
		</div>
	</div>

	<!-- Widget 4: Clear Filters Button -->
	<div class="text-center">
		<button type="button" class="negar-clear-all-filters btn btn-outline btn-sm w-full py-2 text-xs text-danger border-danger/30 hover:bg-danger hover:text-white transition-colors inline-flex items-center justify-center gap-1.5">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
			<span><?php esc_html_e( 'حذف همه فیلترها', 'negar-shop' ); ?></span>
		</button>
	</div>

</div>
