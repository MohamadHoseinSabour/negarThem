<?php
/**
 * Shop Ajax Filter Handler - Compatible with Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 2.5.0
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

	$paged       = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : ( isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1 );
	$orderby_raw = ! empty( $_POST['sortt'] ) ? sanitize_text_field( wp_unslash( $_POST['sortt'] ) ) : ( ! empty( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'menu_order' );
	$search_term = ! empty( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

	// Price range parameters
	$price_filtered = ( isset( $_POST['price_filtered'] ) && ( '1' === (string) $_POST['price_filtered'] || 'true' === (string) $_POST['price_filtered'] ) );
	$min_price_raw  = isset( $_POST['pricemin'] ) && '' !== $_POST['pricemin'] ? $_POST['pricemin'] : ( isset( $_POST['min_price'] ) && '' !== $_POST['min_price'] ? $_POST['min_price'] : null );
	$max_price_raw  = isset( $_POST['pricemax'] ) && '' !== $_POST['pricemax'] ? $_POST['pricemax'] : ( isset( $_POST['max_price'] ) && '' !== $_POST['max_price'] ? $_POST['max_price'] : null );

	$min_price = null !== $min_price_raw ? floatval( $min_price_raw ) : null;
	$max_price = null !== $max_price_raw ? floatval( $max_price_raw ) : null;

	// Stock & Sale toggles
	$in_stock = ( isset( $_POST['onstock'] ) && ( 'true' === (string) $_POST['onstock'] || '1' === (string) $_POST['onstock'] ) ) || ( isset( $_POST['in_stock'] ) && ( 'true' === (string) $_POST['in_stock'] || '1' === (string) $_POST['in_stock'] ) );
	$on_sale  = ( isset( $_POST['onsalee'] ) && ( 'true' === (string) $_POST['onsalee'] || '1' === (string) $_POST['onsalee'] ) ) || ( isset( $_POST['on_sale'] ) && ( 'true' === (string) $_POST['on_sale'] || '1' === (string) $_POST['on_sale'] ) );
	$per_page = apply_filters( 'loop_shop_per_page', 12 );

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

	// 1. Categories (support catIds, category, categories)
	$raw_cats = null;
	if ( ! empty( $_POST['catIds'] ) ) {
		$raw_cats = $_POST['catIds'];
	} elseif ( ! empty( $_POST['category'] ) ) {
		$raw_cats = $_POST['category'];
	} elseif ( ! empty( $_POST['categories'] ) ) {
		$raw_cats = $_POST['categories'];
	}

	$cats = array();
	if ( ! empty( $raw_cats ) ) {
		if ( is_array( $raw_cats ) ) {
			$cats = array_filter( array_map( 'absint', $raw_cats ) );
		} else {
			$cats = array_filter( array_map( 'absint', explode( ',', (string) $raw_cats ) ) );
		}
	}

	if ( ! empty( $cats ) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'term_id',
			'terms'            => $cats,
			'include_children' => true,
			'operator'         => 'IN',
		);
	}

	// 2. Brands (support brandIds, brand, brands)
	$raw_brands = null;
	if ( ! empty( $_POST['brandIds'] ) ) {
		$raw_brands = $_POST['brandIds'];
	} elseif ( ! empty( $_POST['brand'] ) ) {
		$raw_brands = $_POST['brand'];
	} elseif ( ! empty( $_POST['brands'] ) ) {
		$raw_brands = $_POST['brands'];
	}

	$brands = array();
	if ( ! empty( $raw_brands ) ) {
		if ( is_array( $raw_brands ) ) {
			$brands = array_filter( array_map( 'absint', $raw_brands ) );
		} else {
			$brands = array_filter( array_map( 'absint', explode( ',', (string) $raw_brands ) ) );
		}
	}

	if ( ! empty( $brands ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_brand',
			'field'    => 'term_id',
			'terms'    => $brands,
			'operator' => 'IN',
		);
	}

	// 3. Product Attributes (attrId & taxIds & attrQueryTypes)
	$attr_taxonomies  = isset( $_POST['attrId'] ) ? $_POST['attrId'] : null;
	$attr_terms       = isset( $_POST['taxIds'] ) ? $_POST['taxIds'] : null;
	$attr_query_types = isset( $_POST['attrQueryTypes'] ) ? (array) $_POST['attrQueryTypes'] : array();

	if ( is_string( $attr_taxonomies ) ) {
		$attr_taxonomies = json_decode( wp_unslash( $attr_taxonomies ), true );
	}
	if ( is_string( $attr_terms ) ) {
		$attr_terms = json_decode( wp_unslash( $attr_terms ), true );
	}

	if ( ! empty( $attr_taxonomies ) && is_array( $attr_taxonomies ) && ! empty( $attr_terms ) && is_array( $attr_terms ) ) {
		foreach ( $attr_taxonomies as $idx => $tax_name ) {
			$tax_name = sanitize_text_field( (string) $tax_name );
			if ( empty( $tax_name ) ) {
				continue;
			}
			$terms = isset( $attr_terms[ $idx ] ) ? (array) $attr_terms[ $idx ] : array();
			if ( empty( $terms ) ) {
				continue;
			}

			$clean_terms = array();
			$is_id       = true;
			foreach ( $terms as $t ) {
				$t = trim( (string) $t );
				if ( '' !== $t ) {
					if ( ! is_numeric( $t ) ) {
						$is_id = false;
					}
					$clean_terms[] = is_numeric( $t ) ? absint( $t ) : sanitize_title( $t );
				}
			}

			if ( empty( $clean_terms ) ) {
				continue;
			}

			$q_type = ( isset( $attr_query_types[ $idx ] ) && 'and' === strtolower( (string) $attr_query_types[ $idx ] ) ) ? 'AND' : 'IN';
			$args['tax_query'][] = array(
				'taxonomy' => $tax_name,
				'field'    => $is_id ? 'term_id' : 'slug',
				'terms'    => $clean_terms,
				'operator' => $q_type,
			);
		}
	}

	// 4. Archive Context Scoping (Preserve taxonomy archive boundaries)
	$archive_type     = isset( $_POST['archiveType'] ) ? sanitize_text_field( wp_unslash( $_POST['archiveType'] ) ) : '';
	$archive_taxonomy = isset( $_POST['archiveTaxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['archiveTaxonomy'] ) ) : '';
	$archive_term_id  = isset( $_POST['archiveTermId'] ) ? absint( $_POST['archiveTermId'] ) : ( isset( $_POST['termId'] ) ? absint( $_POST['termId'] ) : ( isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0 ) );

	if ( $archive_term_id > 0 && ! empty( $archive_taxonomy ) && taxonomy_exists( $archive_taxonomy ) ) {
		$already_scoped = false;
		if ( 'product_cat' === $archive_taxonomy && ! empty( $cats ) ) {
			$already_scoped = true;
		} elseif ( 'product_brand' === $archive_taxonomy && ! empty( $brands ) ) {
			$already_scoped = true;
		}

		if ( ! $already_scoped ) {
			$args['tax_query'][] = array(
				'taxonomy'         => $archive_taxonomy,
				'field'            => 'term_id',
				'terms'            => array( $archive_term_id ),
				'include_children' => true,
				'operator'         => 'IN',
			);
		}
	}

	// 5. Stock Status
	if ( $in_stock ) {
		$args['meta_query'][] = array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '=',
		);
	}

	// 6. Price Range
	if ( $price_filtered || null !== $min_price || null !== $max_price ) {
		$price_query = array(
			'key'  => '_price',
			'type' => 'NUMERIC',
		);

		$has_valid_max = ( null !== $max_price && $max_price > 0 && $max_price < 999999999 );
		$has_valid_min = ( null !== $min_price && $min_price > 0 );

		if ( $has_valid_min && $has_valid_max ) {
			$price_query['value']   = array( min( $min_price, $max_price ), max( $min_price, $max_price ) );
			$price_query['compare'] = 'BETWEEN';
		} elseif ( $has_valid_min ) {
			$price_query['value']   = $min_price;
			$price_query['compare'] = '>=';
		} elseif ( $has_valid_max ) {
			$price_query['value']   = $max_price;
			$price_query['compare'] = '<=';
		}

		if ( isset( $price_query['value'] ) ) {
			$args['meta_query'][] = $price_query;
		}
	}

	// 7. On Sale filter
	if ( $on_sale ) {
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$args['post__in']    = ! empty( $product_ids_on_sale ) ? $product_ids_on_sale : array( 0 );
	}

	// 8. Orderby mapping
	switch ( $orderby_raw ) {
		case 'price':
		case 'price-asc':
		case 'low-to-high':
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
			break;

		case 'price-desc':
		case 'high-to-low':
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
		case 'latest':
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
			<nav class="woocommerce-pagination" aria-label="<?php esc_attr_e( 'صفحه‌بندی محصول', 'negar-shop' ); ?>">
				<ul class="page-numbers">
					<?php if ( $paged > 1 ) : ?>
						<li><a class="prev page-numbers" href="#" data-page="<?php echo esc_attr( $paged - 1 ); ?>" aria-label="<?php esc_attr_e( 'قبلی', 'negar-shop' ); ?>"><i class="icon-chevron-right-2 lh-1" aria-hidden="true"></i></a></li>
					<?php endif; ?>

					<?php
					for ( $i = 1; $i <= $query->max_num_pages; $i++ ) {
						if ( $i === $paged ) {
							echo '<li><span aria-current="page" class="page-numbers current">' . esc_html( number_format_i18n( $i ) ) . '</span></li>';
						} else {
							echo '<li><a class="page-numbers" href="#" data-page="' . esc_attr( $i ) . '">' . esc_html( number_format_i18n( $i ) ) . '</a></li>';
						}
					}
					?>

					<?php if ( $paged < $query->max_num_pages ) : ?>
						<li><a class="next page-numbers" href="#" data-page="<?php echo esc_attr( $paged + 1 ); ?>" aria-label="<?php esc_attr_e( 'بعدی', 'negar-shop' ); ?>"><i class="icon-chevron-left-2 lh-1" aria-hidden="true"></i></a></li>
					<?php endif; ?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
	<?php
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html'        => $html,
			'count'       => $query->found_posts,
			'max_pages'   => $query->max_num_pages,
			'page'        => $paged,
			'found_posts' => $query->found_posts,
		)
	);
}
add_action( 'wp_ajax_kaveh_shop_filters', 'negar_shop_kaveh_ajax_shop_filters' );
add_action( 'wp_ajax_nopriv_kaveh_shop_filters', 'negar_shop_kaveh_ajax_shop_filters' );
