<?php
/**
 * Section 19: Gaming Laptops Spotlight Showcase (HOME-19)
 * Reference: Demo 43 Section 18 (Top: 8859px, Height: ~379px)
 * Heading: "پربازدید ترین محصولات - لپ تاپ ۱۶ اینچی ایسوس"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$laptop_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( 'laptop', 'لپ-تاپ', 'گیمینگ' ),
		),
	),
) );
if ( ! $laptop_products->have_posts() ) {
	$laptop_products = new WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
	) );
}
?>

<section class="negar-sec-19-laptops py-6" aria-label="<?php esc_attr_e( 'لپ‌تاپ‌های گیمینگ پربازدید', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Section Heading & Divider matching Demo 43 -->
		<div class="section-heading-one-nk mb-6 flex items-center justify-between gap-4">
			<div class="flex items-center gap-3">
				<h2 class="title text-base sm:text-lg font-black text-[#212341] m-0">
					<span class="text-rose-600"><?php esc_html_e( 'پربازدید', 'negar-shop' ); ?></span> <?php esc_html_e( 'ترین لپ‌تاپ‌های گیمینگ', 'negar-shop' ); ?>
				</h2>
				<div class="sub-title text-2xs text-slate-400 font-medium hidden sm:inline-block">
					<?php esc_html_e( 'کانفیگ‌های حرفه‌ای پردازنده و کارت گرافیک', 'negar-shop' ); ?>
				</div>
			</div>
			
			<div class="line flex-grow h-px bg-slate-200 hidden md:flex items-center relative mx-2">
				<span class="bar h-0.5 bg-rose-600 w-12 absolute right-0"></span>
				<span class="dot w-1.5 h-1.5 rounded-full bg-rose-600 absolute right-12"></span>
			</div>

			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
			<?php
			if ( $laptop_products->have_posts() ) :
				while ( $laptop_products->have_posts() ) :
					$laptop_products->the_post();
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
