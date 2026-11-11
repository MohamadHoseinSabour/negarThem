<?php
/**
 * The template for displaying the front page / home page.
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
	// Preserve a page built with Elementor exactly as WordPress currently renders it.
	$is_elementor = false;
	if ( class_exists( '\Elementor\Plugin' ) && is_page() ) {
		$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
		if ( $document && $document->is_built_with_elementor() ) {
			$is_elementor = true;
		}
	}

	if ( $is_elementor ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	else :
		// The registry starts with the exact legacy 35-section order.
		negar_theme_render_home_sections();
	endif;
	?>

</main>

<?php
get_footer();
