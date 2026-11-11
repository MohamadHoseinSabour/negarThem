<?php
/**
 * Section 26: Multi-Category Products Grid #3 (HOME-26)
 * Reference: Demo 43 Section 25 (Top: 12547px, Height: ~554px)
 * Heading: "بر اساس مخاطبان پربازدید محصولات"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$visited3_products = new WP_Query(
	negar_theme_product_section_is_configured( 'multi-category-products' )
		? negar_theme_product_section_query_args( 'multi-category-products', array( 'post_type' => 'product', 'post_status' => 'publish' ) )
		: array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'orderby'        => 'rand',
		)
);
?>

<section class="negar-sec-26-visited-3 py-6" aria-label="<?php esc_attr_e( 'پربازدیدترین محصولات منتخب', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header & Divider -->
		<div class="section-heading-one-nk">
			<div class="title">
				<span class="highlight">پربازدید</span> ترین محصولات
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'کنسول‌ها، لپ‌تاپ‌ها، موبایل و لوازم جانبی', 'negar-shop' ); ?>
			</div>
			<div class="line">
				<span></span>
				<span></span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
			<?php
			if ( $visited3_products->have_posts() ) :
				while ( $visited3_products->have_posts() ) :
					$visited3_products->the_post();
					?>
					<div class="product-grid-item">
						<?php get_template_part( 'template-parts/product/card' ); ?>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

	</div>
</section>
