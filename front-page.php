<?php
/**
 * The template for displaying the front page / home page
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main homepage-main">

	<?php
	// Check if this page is edited with Elementor
	$is_elementor = false;
	if ( class_exists( '\Elementor\Plugin' ) && is_page() ) {
		$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
		if ( $document && $document->is_built_with_elementor() ) {
			$is_elementor = true;
		}
	}

	if ( $is_elementor ) :
		// Output Elementor content if the page was designed with Elementor
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	else :
		/**
		 * Default High-Conversion Standalone Homepage Layout
		 */

		// 1. Hero Slider & Side Banners (HOME-01)
		get_template_part( 'template-parts/home/hero-slider' );

		// 2. Quick Category Bubbles (HOME-02)
		get_template_part( 'template-parts/home/category-bubbles' );

		// 3. Flash Deals Slider with Realtime Timer (HOME-03)
		get_template_part( 'template-parts/home/flash-deals' );

		// 4. Promotional Banners Grid (HOME-04)
		get_template_part( 'template-parts/home/banners-grid' );

		// 5. Product Tabs Showcase (HOME-05)
		get_template_part( 'template-parts/home/product-tabs' );

		// 6. Special Category Showcase with Vertical Banner (HOME-07)
		get_template_part( 'template-parts/home/category-showcase' );

		// 7. Popular Brands Carousel (HOME-06)
		get_template_part( 'template-parts/home/brands-slider' );

		// 8. Latest Blog Articles & Buying Guides (HOME-08)
		get_template_part( 'template-parts/home/blog-posts' );

	endif;
	?>

</main>

<?php
get_footer();
