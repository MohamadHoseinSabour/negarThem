<?php
/**
 * The template for displaying the front page / home page
 * Full 35-Section High-Fidelity Implementation matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 2.0.0
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
		 * 35 Consecutive Homepage Sections - Exact Order & Structure of Demo 43
		 */

		// 01. Story Circles (Circular stories with gradient rings)
		get_template_part( 'template-parts/home/story-circles' );

		// 02. Hero Section (Xbox Crimson Banner + Flash Deal Card)
		get_template_part( 'template-parts/home/hero-slider' );

		// 03. Quick Category Bubbles (Rounded category icons)
		get_template_part( 'template-parts/home/category-bubbles' );

		// 04. Flash Deals Slider #1 with Realtime Countdown Timer
		get_template_part( 'template-parts/home/flash-deals' );

		// 05. Product Tabs Showcase (Laptops, Consoles, Mobile, Smartwatch)
		get_template_part( 'template-parts/home/product-tabs' );

		// 06. 2-Column Promo Banners Grid #1
		get_template_part( 'template-parts/home/banners-grid' );

		// 07. Best-Selling Smartwatches Carousel #1
		get_template_part( 'template-parts/home/sec-07-smartwatches-1' );

		// 08. Full-Width Hero Promo Banner #1
		get_template_part( 'template-parts/home/sec-08-banner-wide' );

		// 09. Accessories Grid (Games & Hardware)
		get_template_part( 'template-parts/home/sec-09-accessories' );

		// 10. 2-Column Promo Banners Grid #2
		get_template_part( 'template-parts/home/sec-10-banners-2col' );

		// 11. Smartwatch & Audio Showcase with Technical Specs
		get_template_part( 'template-parts/home/sec-11-smartwatches-spec' );

		// 12. Dual Compact Promo Banners #3
		get_template_part( 'template-parts/home/sec-12-banners-compact' );

		// 13. Multi-Column Category Taste Grid (3-Column showcase)
		get_template_part( 'template-parts/home/sec-13-taste-grid' );

		// 14. Horizontal Feature Bar #1 (PS5 Slim & Watch Spotlight)
		get_template_part( 'template-parts/home/sec-14-feature-bar-1' );

		// 15. Most Visited Products Carousel #2
		get_template_part( 'template-parts/home/sec-15-visited-products-2' );

		// 16. Single Featured Deal Spotlight Bar (Apple Watch SE)
		get_template_part( 'template-parts/home/sec-16-apple-watch-deal' );

		// 17. Flash Deals Countdown Carousel #2
		get_template_part( 'template-parts/home/sec-17-flash-deals-2' );

		// 18. 2-Column Promo Banners Grid #4
		get_template_part( 'template-parts/home/sec-18-banners-2col' );

		// 19. Gaming Laptops Spotlight Showcase
		get_template_part( 'template-parts/home/sec-19-gaming-laptop' );

		// 20. Popular Brands Carousel (Samsung, Apple, Sony, Asus)
		get_template_part( 'template-parts/home/brands-slider' );

		// 21. Latest approved WooCommerce product reviews
		get_template_part( 'template-parts/home/sec-21-testimonials' );

		// 22. Audio & Headphones Flash Deals Carousel
		get_template_part( 'template-parts/home/sec-22-flash-deals-audio' );

		// 23. Horizontal Feature Bar #2
		get_template_part( 'template-parts/home/sec-23-feature-bar-2' );

		// 24. Best-Selling Smartwatches Carousel #3
		get_template_part( 'template-parts/home/sec-24-smartwatches-2' );

		// 25. Gaming Zone Showcase (Assassin’s Creed Shadows)
		get_template_part( 'template-parts/home/sec-25-gaming-zone' );

		// 26. Multi-Category Products Grid #3
		get_template_part( 'template-parts/home/sec-26-visited-products-3' );

		// 27. Dual Compact Promo Banners #5
		get_template_part( 'template-parts/home/sec-27-banners-compact-2' );

		// 28. Samsung Galaxy S25 FE Spotlight Grid
		get_template_part( 'template-parts/home/sec-28-mobile-spotlight' );

		// 29. 2-Column Promo Banners Grid #6
		get_template_part( 'template-parts/home/sec-29-banners-2col' );

		// 30. Recently Viewed Products Carousel
		get_template_part( 'template-parts/home/sec-30-recently-viewed' );

		// 31. Product Video Reviews Grid
		get_template_part( 'template-parts/home/sec-31-video-reviews' );

		// 32. 2-Column Promo Banners Grid #7
		get_template_part( 'template-parts/home/sec-32-banners-2col' );

		// 33. Latest Blog Articles & Buying Guides
		get_template_part( 'template-parts/home/blog-posts' );

		// 34. Horizontal Feature Bar #3 (PS5 Games Collection)
		get_template_part( 'template-parts/home/sec-34-feature-bar-3' );

		// 35. Shipping & Delivery Trust Marquee (Tipax Delivery Ticker)
		get_template_part( 'template-parts/home/sec-35-trust-marquee' );

	endif;
	?>

</main>

<?php
get_footer();
