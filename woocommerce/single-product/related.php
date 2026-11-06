<?php
/**
 * Single Product Related Products Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$related_ids = wc_get_related_products( $product->get_id(), 4 );

if ( empty( $related_ids ) ) {
	return;
}

$args = array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => 4,
	'post__in'             => $related_ids,
	'post__not_in'         => array( $product->get_id() ),
);

$related_products = new WP_Query( $args );

if ( ! $related_products->have_posts() ) {
	return;
}
?>

<section class="negar-related-products mt-16" aria-labelledby="related-products-heading">
	<div class="section-header flex items-center justify-between mb-6 pb-3 border-b border-border">
		<h3 id="related-products-heading" class="text-lg sm:text-xl font-black text-dark flex items-center gap-2">
			<span class="w-2.5 h-6 bg-primary rounded-full inline-block"></span>
			<span><?php esc_html_e( 'کالاهای مشابه و پیشنهادی', 'negar-shop' ); ?></span>
		</h3>
	</div>

	<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
		<?php
		while ( $related_products->have_posts() ) :
			$related_products->the_post();
			get_template_part( 'template-parts/product/card' );
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</section>
