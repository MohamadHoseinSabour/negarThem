<?php
/**
 * Section 24: Best-Selling Smartwatches Carousel #3 (HOME-24)
 * Reference: Demo 43 Section 23 (Top: 11226px, Height: ~531px)
 * Heading: "پرفروش ترین ساعت هوشمند"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$watch_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'orderby'        => 'date',
	'order'          => 'ASC',
) );
?>

<section class="negar-sec-24-smartwatches py-6" aria-label="<?php esc_attr_e( 'ساعت هوشمند پرفروش', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header & Divider -->
		<div class="section-heading-one-nk">
			<div class="title">
				<span class="highlight">پرفروش</span> ترین ساعت هوشمند
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'ضمانت قیمت و کیفیت', 'negar-shop' ); ?>
			</div>
			<div class="line">
				<span></span>
				<span></span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
			<?php
			if ( $watch_products->have_posts() ) :
				while ( $watch_products->have_posts() ) :
					$watch_products->the_post();
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
