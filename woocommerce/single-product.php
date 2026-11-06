<?php
/**
 * The Template for displaying all single products
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="negar-single-product-page py-6">
	<div class="container mx-auto px-4">
		
		<?php negar_shop_breadcrumbs(); ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		<?php endwhile; ?>

	</div>
</div>

<?php
get_footer( 'shop' );
