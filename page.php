<?php
/**
 * The template for displaying all pages
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php $is_account_page = function_exists( 'is_account_page' ) && is_account_page(); ?>
<main id="primary" class="site-main page-wrapper<?php echo $is_account_page ? ' negar-account-page' : ' negar-generic-page'; ?>">
	<?php if ( $is_account_page ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="container mx-auto px-4">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>"><ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li><li class="separator" aria-hidden="true">/</li><li class="current" aria-current="page"><?php the_title(); ?></li></ol></nav>
				<article class="negar-generic-page__card entry-content">
					<header><h1><?php the_title(); ?></h1></header>
					<?php the_content(); ?>
				</article>
				<?php
			endwhile;
			?>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer();
