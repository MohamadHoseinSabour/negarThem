<?php
/**
 * Shop Archive Toolbar Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$total_products = $wp_query->found_posts;
$orderby        = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'menu_order'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$sort_options = array(
	'menu_order' => __( 'مرتب‌سازی پیش‌فرض', 'negar-shop' ),
	'popularity' => __( 'محبوب‌ترین‌ها', 'negar-shop' ),
	'rating'     => __( 'بالاترین امتیاز', 'negar-shop' ),
	'date'       => __( 'جدیدترین‌ها', 'negar-shop' ),
	'price'      => __( 'ارزان‌ترین', 'negar-shop' ),
	'price-desc' => __( 'گران‌ترین', 'negar-shop' ),
);
?>

<div class="negar-shop-toolbar bg-surface border border-border rounded-lg p-3 sm:p-4 mb-6 shadow-sm">
	<div class="flex flex-wrap items-center justify-between gap-3">
		
		<!-- Right Side: Mobile Filters Toggle + Count -->
		<div class="flex items-center gap-3">
			<button type="button" class="negar-open-filters-drawer lg:hidden btn btn-outline btn-sm inline-flex items-center gap-1.5" aria-expanded="false" aria-controls="negar-shop-filters-drawer">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
				</svg>
				<span><?php esc_html_e( 'فیلترها', 'negar-shop' ); ?></span>
			</button>

			<div class="negar-products-count text-xs sm:text-sm text-muted">
				<?php
				if ( $total_products > 0 ) {
					printf(
						/* translators: %s: number of products */
						esc_html__( 'نمایش %s کالا', 'negar-shop' ),
						'<strong>' . number_format_i18n( $total_products ) . '</strong>'
					);
				} else {
					esc_html_e( '۰ کالا یافت شد', 'negar-shop' );
				}
				?>
			</div>
		</div>

		<!-- Left Side: Sort Options + View Mode Switcher -->
		<div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
			<!-- Orderby Select -->
			<div class="relative flex items-center">
				<label for="negar-shop-orderby" class="sr-only"><?php esc_html_e( 'مرتب‌سازی', 'negar-shop' ); ?></label>
				<div class="relative inline-flex items-center">
					<select id="negar-shop-orderby" name="orderby" class="negar-orderby-select form-select text-xs sm:text-sm pl-8 pr-3 py-1.5 rounded-lg border-border bg-slate-50 text-dark hover:border-primary focus:border-primary focus:ring-1 focus:ring-primary transition-all cursor-pointer">
						<?php foreach ( $sort_options as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $orderby, $key ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<span class="pointer-events-none absolute left-2 text-muted" aria-hidden="true">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
					</span>
				</div>
			</div>

			<!-- View Mode Toggle -->
			<div class="negar-view-switcher inline-flex items-center bg-slate-100 p-0.5 rounded-lg border border-border" role="group" aria-label="<?php esc_attr_e( 'حالت نمایش محصولات', 'negar-shop' ); ?>">
				<button type="button" class="negar-view-btn p-1.5 rounded text-muted hover:text-dark is-active" data-view="grid" aria-label="<?php esc_attr_e( 'نمایش شبکه‌ای', 'negar-shop' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect width="7" height="7" x="3" y="3" rx="1"/>
						<rect width="7" height="7" x="14" y="3" rx="1"/>
						<rect width="7" height="7" x="14" y="14" rx="1"/>
						<rect width="7" height="7" x="3" y="14" rx="1"/>
					</svg>
				</button>
				<button type="button" class="negar-view-btn p-1.5 rounded text-muted hover:text-dark" data-view="list" aria-label="<?php esc_attr_e( 'نمایش ردیفی (لیست)', 'negar-shop' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="8" x2="21" y1="6" y2="6"/>
						<line x1="8" x2="21" y1="12" y2="12"/>
						<line x1="8" x2="21" y1="18" y2="18"/>
						<line x1="3" x2="3.01" y1="6" y2="6"/>
						<line x1="3" x2="3.01" y1="12" y2="12"/>
						<line x1="3" x2="3.01" y1="18" y2="18"/>
					</svg>
				</button>
			</div>
		</div>

	</div>

	<!-- Active Chips Container -->
	<div class="negar-active-chips-wrapper mt-3 pt-3 border-t border-border">
		<?php
		$initial_chips = negar_shop_get_active_filter_chips();
		if ( ! empty( $initial_chips ) ) :
			?>
			<div class="negar-active-filter-chips flex items-center flex-wrap gap-2">
				<span class="text-xs text-muted font-medium"><?php esc_html_e( 'فیلترهای فعال:', 'negar-shop' ); ?></span>
				<?php foreach ( $initial_chips as $chip ) : ?>
					<span class="negar-filter-chip inline-flex items-center gap-1.5 px-3 py-1 bg-surface border border-border rounded-full text-xs text-dark transition-all hover:border-danger hover:text-danger" data-filter-key="<?php echo esc_attr( $chip['key'] ); ?>" data-filter-value="<?php echo esc_attr( $chip['value'] ); ?>">
						<span><?php echo esc_html( $chip['label'] ); ?></span>
						<button type="button" class="negar-remove-chip hover:text-danger focus:outline-none" aria-label="<?php esc_attr_e( 'حذف فیلتر', 'negar-shop' ); ?>">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
						</button>
					</span>
				<?php endforeach; ?>
				<button type="button" class="negar-clear-all-chips text-xs text-danger font-medium hover:underline mr-2">
					<?php esc_html_e( 'حذف همه', 'negar-shop' ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>
</div>
