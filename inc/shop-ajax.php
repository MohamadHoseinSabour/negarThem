<?php
/**
 * Shop Ajax Filter Handler - Compatible with Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle live product filtering via kaveh_shop_filters action
 */
function negar_shop_kaveh_ajax_shop_filters() {
	if ( isset( $_POST['nonce'] ) ) {
		check_ajax_referer( 'kaveh_shop_filter_nonce', 'nonce' );
	}

	$paged        = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : ( isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1 );
	$orderby_raw  = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'menu_order';
	$search_term  = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
	$min_price    = isset( $_POST['pricemin'] ) && '' !== $_POST['pricemin'] ? absint( $_POST['pricemin'] ) : null;
	$max_price    = isset( $_POST['pricemax'] ) && '' !== $_POST['pricemax'] ? absint( $_POST['pricemax'] ) : null;
	$in_stock     = ( isset( $_POST['onstock'] ) && ( 'true' === $_POST['onstock'] || '1' === $_POST['onstock'] ) );
	$on_sale      = ( isset( $_POST['onsalee'] ) && ( 'true' === $_POST['onsalee'] || '1' === $_POST['onsalee'] ) );
	$per_page     = apply_filters( 'loop_shop_per_page', 12 );

	// Build query arguments
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'tax_query'      => array( 'relation' => 'AND' ),
		'meta_query'     => array( 'relation' => 'AND' ),
	);

	if ( ! empty( $search_term ) ) {
		$args['s'] = $search_term;
	}

	// Categories
	if ( ! empty( $_POST['category'] ) ) {
		$cats = is_array( $_POST['category'] ) ? array_map( 'absint', $_POST['category'] ) : array( absint( $_POST['category'] ) );
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $cats,
		);
	} elseif ( ! empty( $_POST['categories'] ) ) {
		$cats = is_array( $_POST['categories'] ) ? array_map( 'absint', $_POST['categories'] ) : array( absint( $_POST['categories'] ) );
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $cats,
		);
	}

	// Brands
	if ( ! empty( $_POST['brand'] ) ) {
		$brands = is_array( $_POST['brand'] ) ? array_map( 'absint', $_POST['brand'] ) : array( absint( $_POST['brand'] ) );
		$args['tax_query'][] = array(
			'taxonomy' => 'product_brand',
			'field'    => 'term_id',
			'terms'    => $brands,
		);
	} elseif ( ! empty( $_POST['brands'] ) ) {
		$brands = is_array( $_POST['brands'] ) ? array_map( 'absint', $_POST['brands'] ) : array( absint( $_POST['brands'] ) );
		$args['tax_query'][] = array(
			'taxonomy' => 'product_brand',
			'field'    => 'term_id',
			'terms'    => $brands,
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

		if ( null !== $min_price && null !== $max_price && $max_price < 999999999 ) {
			$price_query['value']   = array( $min_price, $max_price );
			$price_query['compare'] = 'BETWEEN';
		} elseif ( null !== $min_price && $min_price > 0 ) {
			$price_query['value']   = $min_price;
			$price_query['compare'] = '>=';
		} elseif ( null !== $max_price && $max_price < 999999999 ) {
			$price_query['value']   = $max_price;
			$price_query['compare'] = '<=';
		}

		if ( isset( $price_query['value'] ) ) {
			$args['meta_query'][] = $price_query;
		}
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

	ob_start();
	?>
	<div class="category-nk__products row" id="woor">
		<?php if ( $query->have_posts() ) : ?>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
			?>
		<?php else : ?>
			<div class="col-12 py-12 text-center">
				<div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle bg-light mb-3">
					<i class="iconly-Search text-muted fs-3"></i>
				</div>
				<h3 class="f-payda fs-5 fw-bold text-dark mb-2"><?php esc_html_e( 'هیچ محصولی با این مشخصات یافت نشد.', 'negar-shop' ); ?></h3>
				<p class="text-muted fs-6 mb-4"><?php esc_html_e( 'لطفاً فیلترهای انتخابی را تغییر داده یا بازنشانی کنید.', 'negar-shop' ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="pg1">
		<?php if ( $query->max_num_pages > 1 ) : ?>
			<nav class="woocommerce-pagination" aria-label="صفحه‌بندی محصول">
				<ul class="page-numbers">
					<?php
					for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
						if ( $i === $paged ) {
							echo '<li><span aria-current="page" class="page-numbers current">' . esc_html( $i ) . '</span></li>';
						} else {
							echo '<li><a class="page-numbers" href="#" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a></li>';
						}
					}
					?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_kaveh_shop_filters', 'negar_shop_kaveh_ajax_shop_filters' );
add_action( 'wp_ajax_nopriv_kaveh_shop_filters', 'negar_shop_kaveh_ajax_shop_filters' );
