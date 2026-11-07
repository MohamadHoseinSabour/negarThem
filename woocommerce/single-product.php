<?php
/**
 * The Template for displaying all single products - 100% Matching Demo 43
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<main id="primary" class="site-main single-product-main-wrapper bg-slate-50/50">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php wc_get_template_part( 'content', 'single-product' ); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer( 'shop' );
