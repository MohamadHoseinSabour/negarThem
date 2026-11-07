<?php
/**
 * Shop Toolbar Component - Exact Match to Demo 43 (category-nk__sort)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

$current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';

$orderby_options = array(
	'menu_order' => 'پیش‌فرض',
	'popularity' => 'پرفروش',
	'date'       => 'جدیدترین',
	'price'      => 'ارزان ترین',
	'price-desc' => 'گران ترین',
);

$current_label = isset( $orderby_options[ $current_orderby ] ) ? $orderby_options[ $current_orderby ] : 'پیش‌فرض';
?>

<div class="category-nk__sort d-flex align-items-center justify-content-lg-between gap-2 mb-4">
	<div class="category-nk__sort__title">
		مرتب‌سازی بر اساس
	</div>

	<div class="category-sort visually-hidden" aria-hidden="true">
		<ul class="d-flex align-items-center">
			<?php foreach ( $orderby_options as $val => $lbl ) : ?>
				<li>
					<input type="radio" name="orderby" id="orderby-<?php echo esc_attr( $val ); ?>" value="<?php echo esc_attr( $val ); ?>" <?php checked( $current_orderby, $val ); ?>>
					<label for="orderby-<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $lbl ); ?></label>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="dropdown">
		<button class="btn dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="kaveh-nk-orderby-1" data-bs-toggle="dropdown" aria-expanded="false">
			<?php echo esc_html( $current_label ); ?>
		</button>
		<ul class="dropdown-menu border-0 bg-white p-3 shadow-sm" aria-labelledby="kaveh-nk-orderby-1">
			<?php foreach ( $orderby_options as $val => $lbl ) : ?>
				<li>
					<a href="#" class="dropdown-item d-flex align-items-center justify-content-between <?php echo ( $val === $current_orderby ) ? 'active' : ''; ?>" data-orderby="<?php echo esc_attr( $val ); ?>">
						<?php echo esc_html( $lbl ); ?>
						<span class="rounded-circle position-relative" aria-hidden="true"></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<button type="button" class="d-flex d-lg-none align-items-center justify-content-center gap-2 ms-auto btn btn-outline-secondary rounded-pill px-3 py-1" data-open="#filter" style="font-size: 13px;">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 18 18" aria-hidden="true">
			<path stroke="currentColor" stroke-linecap="round" stroke-width="1.3" d="M1.5 13.5h2.25m10.5-9h2.25m-4.5 9h4.5m-15-9H6m.15 12h1.2c.84 0 1.26 0 1.581-.163a1.5 1.5 0 0 0 .656-.656c.163-.32.163-.74.163-1.581v-1.2c0-.84 0-1.26-.163-1.581a1.5 1.5 0 0 0-.656-.655c-.32-.164-.74-.164-1.581-.164h-1.2c-.84 0-1.26 0-1.581.164a1.5 1.5 0 0 0-.656.655c-.163.32-.163.74-.163 1.581v1.2c0 .84 0 1.26.163 1.581a1.5 1.5 0 0 0 .656.656c.32.163.74.163 1.581.163Zm4.5-9h1.2c.84 0 1.26 0 1.581-.163a1.5 1.5 0 0 0 .655-.656c.164-.32.164-.74.164-1.581V3.9c0-.84 0-1.26-.164-1.581a1.5 1.5 0 0 0-.655-.656c-.32-.163-.74-.163-1.581-.163h-1.2c-.84 0-1.26 0-1.581.163a1.5 1.5 0 0 0-.656.656c-.163.32-.163.74-.163 1.581v1.2c0 .84 0 1.26.163 1.581a1.5 1.5 0 0 0 .656.656c.32.163.74.163 1.581.163Z"></path>
		</svg>
		فیلترها
	</button>
</div>

<p class="woocommerce-result-count" role="status" aria-relevant="all">
	<?php woocommerce_result_count(); ?>
</p>
