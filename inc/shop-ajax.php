<?php
/**
 * Shop Ajax Filter Handler
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle live product filtering and pagination via Ajax.
 */
function negar_shop_ajax_filter_products() {
	check_ajax_referer( 'negar_shop_nonce', 'nonce' );

	$paged        = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
	$orderby_raw  = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'menu_order';
	$category     = isset( $_POST['product_cat'] ) ? sanitize_text_field( wp_unslash( $_POST['product_cat'] ) ) : '';
	$min_price    = isset( $_POST['min_price'] ) && '' !== $_POST['min_price'] ? absint( $_POST['min_price'] ) : null;
	$max_price    = isset( $_POST['max_price'] ) && '' !== $_POST['max_price'] ? absint( $_POST['max_price'] ) : null;
	$in_stock     = isset( $_POST['in_stock'] ) && '1' === $_POST['in_stock'];
	$on_sale      = isset( $_POST['on_sale'] ) && '1' === $_POST['on_sale'];
	$per_page     = apply_filters( 'loop_shop_per_page', 12 );

	// Build query arguments
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'tax_query'      => array(),
		'meta_query'     => array(),
	);

	// Category Tax Query
	if ( ! empty( $category ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	// Stock Status
	if ( $in_stock ) {
		$args['meta_query'][] = array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '=',
		);
	}

	// Price Range
	if ( null !== $min_price || null !== $max_price ) {
		$price_query = array(
			'key'  => '_price',
			'type' => 'NUMERIC',
		);

		if ( null !== $min_price && null !== $max_price ) {
			$price_query['value']   = array( $min_price, $max_price );
			$price_query['compare'] = 'BETWEEN';
		} elseif ( null !== $min_price ) {
			$price_query['value']   = $min_price;
			$price_query['compare'] = '>=';
		} elseif ( null !== $max_price ) {
			$price_query['value']   = $max_price;
			$price_query['compare'] = '<=';
		}

		$args['meta_query'][] = $price_query;
	}

	// On Sale filter
	if ( $on_sale ) {
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$args['post__in']    = ! empty( $product_ids_on_sale ) ? $product_ids_on_sale : array( 0 );
	}

	// Orderby mapping
	switch ( $orderby_raw ) {
		case 'price':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
			break;

		case 'price-desc':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;

		case 'popularity':
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;

		case 'rating':
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
			break;

		case 'date':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;

		default:
			$args['orderby'] = 'menu_order title';
			$args['order']   = 'ASC';
			break;
	}

	$query = new WP_Query( $args );

	// Render Product Cards
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/product/card' );
		}
	} else {
		?>
		<div class="negar-no-products col-span-full py-12 text-center">
			<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-muted mb-4">
				<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"/>
					<path d="m21 21-4.3-4.3"/>
				</svg>
			</div>
			<h3 class="text-lg font-bold text-dark mb-2"><?php esc_html_e( 'هیچ کالایی با این مشخصات یافت نشد', 'negar-shop' ); ?></h3>
			<p class="text-sm text-muted mb-4"><?php esc_html_e( 'لطفاً فیلترهای انتخابی را تغییر داده یا بازنشانی کنید.', 'negar-shop' ); ?></p>
			<button type="button" class="btn btn-outline btn-sm negar-clear-all-filters">
				<?php esc_html_e( 'حذف همه فیلترها', 'negar-shop' ); ?>
			</button>
		</div>
		<?php
	}
	$html = ob_get_clean();

	// Render Pagination
	ob_start();
	if ( $query->max_num_pages > 1 ) {
		$prev_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>';
		$next_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>';

		$paginate_links = paginate_links(
			array(
				'base'      => '%_%',
				'format'    => '?paged=%#%',
				'current'   => $paged,
				'total'     => $query->max_num_pages,
				'type'      => 'list',
				'prev_text' => $prev_svg . ' <span class="sr-only">' . esc_html__( 'قبلی', 'negar-shop' ) . '</span>',
				'next_text' => '<span class="sr-only">' . esc_html__( 'بعدی', 'negar-shop' ) . '</span> ' . $next_svg,
			)
		);
		echo $paginate_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	$pagination_html = ob_get_clean();

	// Render Active Chips
	$filter_params = array();
	if ( ! empty( $category ) ) {
		$filter_params['product_cat'] = $category;
	}
	if ( null !== $min_price ) {
		$filter_params['min_price'] = $min_price;
	}
	if ( null !== $max_price ) {
		$filter_params['max_price'] = $max_price;
	}
	if ( $in_stock ) {
		$filter_params['in_stock'] = '1';
	}
	if ( $on_sale ) {
		$filter_params['on_sale'] = '1';
	}

	$chips = negar_shop_get_active_filter_chips( $filter_params );
	ob_start();
	if ( ! empty( $chips ) ) {
		?>
		<div class="negar-active-filter-chips flex items-center flex-wrap gap-2 mb-4">
			<span class="text-xs text-muted font-medium"><?php esc_html_e( 'فیلترهای فعال:', 'negar-shop' ); ?></span>
			<?php foreach ( $chips as $chip ) : ?>
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
		<?php
	}
	$active_chips_html = ob_get_clean();

	// Counter HTML
	$total = $query->found_posts;
	if ( $total > 0 ) {
		$count_html = sprintf(
			/* translators: %s: number of products */
			esc_html__( 'نمایش %s کالا', 'negar-shop' ),
			'<strong>' . number_format_i18n( $total ) . '</strong>'
		);
	} else {
		$count_html = esc_html__( '۰ کالا یافت شد', 'negar-shop' );
	}

	wp_reset_postdata();

	wp_send_json_success(
		array(
			'html'              => $html,
			'pagination'        => $pagination_html,
			'count_html'        => $count_html,
			'active_chips_html' => $active_chips_html,
			'found_posts'       => $total,
			'max_num_pages'     => $query->max_num_pages,
		)
	);
}
add_action( 'wp_ajax_negar_shop_filter_products', 'negar_shop_ajax_filter_products' );
add_action( 'wp_ajax_nopriv_negar_shop_filter_products', 'negar_shop_ajax_filter_products' );
