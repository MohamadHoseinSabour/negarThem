<?php
/**
 * Product Tabs Component - Matching Kaveh Demo 43 (HOME-05)
 * Reference: Demo 43 Section 4 (Top: 2067px, Height: ~486px)
 * Heading: "پربازدید ترین محصولات بر اساس مخاطبان"
 * Tabs: لپتاپ ها | کنسول ها | موبایل | ساعت هوشمند
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Laptops
if ( negar_theme_product_section_is_configured( 'product-tabs-laptops' ) ) {
	$laptops_query = new WP_Query( negar_theme_product_section_query_args( 'product-tabs-laptops', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
} else {
$laptops_query = new WP_Query( negar_theme_catalog_visibility_query_args( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( 'laptop', 'لپ-تاپ', 'لپتاپ' ),
		),
	),
) ) );
if ( ! $laptops_query->have_posts() ) {
	$laptops_query = new WP_Query( negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 5 ) ) );
}
}

// 2. Consoles
if ( negar_theme_product_section_is_configured( 'product-tabs-consoles' ) ) {
	$consoles_query = new WP_Query( negar_theme_product_section_query_args( 'product-tabs-consoles', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
} else {
$consoles_query = new WP_Query( negar_theme_catalog_visibility_query_args( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( 'console', 'کنسول-بازی', 'کنسول' ),
		),
	),
) ) );
if ( ! $consoles_query->have_posts() ) {
	$consoles_query = new WP_Query( negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 5, 'orderby' => 'rand' ) ) );
}
}

// 3. Mobile
if ( negar_theme_product_section_is_configured( 'product-tabs-mobile' ) ) {
	$mobile_query = new WP_Query( negar_theme_product_section_query_args( 'product-tabs-mobile', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
} else {
$mobile_query = new WP_Query( negar_theme_catalog_visibility_query_args( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( 'mobile', 'گوشی-موبایل', 'موبایل' ),
		),
	),
) ) );
if ( ! $mobile_query->have_posts() ) {
	$mobile_query = new WP_Query( negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 5, 'orderby' => 'date' ) ) );
}
}

// 4. Smartwatches
if ( negar_theme_product_section_is_configured( 'product-tabs-watches' ) ) {
	$watches_query = new WP_Query( negar_theme_product_section_query_args( 'product-tabs-watches', array( 'post_type' => 'product', 'post_status' => 'publish' ) ) );
} else {
$watches_query = new WP_Query( negar_theme_catalog_visibility_query_args( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => array( 'smartwatch', 'ساعت-هوشمند' ),
		),
	),
) ) );
if ( ! $watches_query->have_posts() ) {
	$watches_query = new WP_Query( negar_theme_catalog_visibility_query_args( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 5, 'orderby' => 'comment_count' ) ) );
}
}
?>

<section class="negar-product-tabs-section py-6" aria-label="<?php esc_attr_e( 'پربازدید ترین محصولات بر اساس مخاطبان', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header with Title, Tabs, and View All Link -->
		<div class="section-heading-one-nk">
			
			<!-- Right: Title & Subtitle -->
			<div class="title">
				<span class="highlight">پربازدید</span> ترین محصولات
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'بر اساس مخاطبان', 'negar-shop' ); ?>
			</div>

			<!-- Center: Tabs Navigation Pills (Matching Demo) -->
			<div class="product-tab-buttons flex items-center gap-1 bg-slate-100 p-1 rounded-2xl mx-1 sm:mx-3">
				<button type="button" class="tab-trigger active text-xs font-bold px-3.5 py-1.5 rounded-xl transition-all duration-200" data-target="#tab-panel-laptops">
					<?php esc_html_e( 'لپتاپ ها', 'negar-shop' ); ?>
				</button>
				<button type="button" class="tab-trigger text-xs font-bold px-3.5 py-1.5 rounded-xl transition-all duration-200" data-target="#tab-panel-consoles">
					<?php esc_html_e( 'کنسول ها', 'negar-shop' ); ?>
				</button>
				<button type="button" class="tab-trigger text-xs font-bold px-3.5 py-1.5 rounded-xl transition-all duration-200" data-target="#tab-panel-mobile">
					<?php esc_html_e( 'موبایل', 'negar-shop' ); ?>
				</button>
				<button type="button" class="tab-trigger text-xs font-bold px-3.5 py-1.5 rounded-xl transition-all duration-200" data-target="#tab-panel-watches">
					<?php esc_html_e( 'ساعت هوشمند', 'negar-shop' ); ?>
				</button>
			</div>

			<div class="line">
				<span></span>
				<span></span>
			</div>

			<!-- Left: View All Button -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>

		</div>

		<!-- Single-Row Panels Container (Max 5 items per panel) -->
		<div class="product-tab-panels">

			<!-- Panel 1: Laptops -->
			<div id="tab-panel-laptops" class="product-tab-panel active">
				<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
					<?php
					if ( $laptops_query->have_posts() ) :
						while ( $laptops_query->have_posts() ) :
							$laptops_query->the_post();
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

			<!-- Panel 2: Consoles -->
			<div id="tab-panel-consoles" class="product-tab-panel hidden">
				<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
					<?php
					if ( $consoles_query->have_posts() ) :
						while ( $consoles_query->have_posts() ) :
							$consoles_query->the_post();
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

			<!-- Panel 3: Mobile -->
			<div id="tab-panel-mobile" class="product-tab-panel hidden">
				<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
					<?php
					if ( $mobile_query->have_posts() ) :
						while ( $mobile_query->have_posts() ) :
							$mobile_query->the_post();
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

			<!-- Panel 4: Watches -->
			<div id="tab-panel-watches" class="product-tab-panel hidden">
				<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
					<?php
					if ( $watches_query->have_posts() ) :
						while ( $watches_query->have_posts() ) :
							$watches_query->the_post();
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

		</div>

	</div>
</section>
