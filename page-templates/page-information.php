<?php
/**
 * Template Name: Information Page / صفحه اطلاعاتی
 *
 * Shared presentation for FAQ, terms, and order tracking content.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main negar-content-page negar-information-page">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>"><ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li><li class="separator" aria-hidden="true">/</li><li class="current" aria-current="page"><?php the_title(); ?></li></ol></nav>
			<section class="negar-information-page__hero">
				<span><i class="icon-document-text" aria-hidden="true"></i><?php esc_html_e( 'راهنما و اطلاعات', 'negar-shop' ); ?></span>
				<h1><?php the_title(); ?></h1>
			</section>
			<article class="negar-information-page__content entry-content">
				<?php the_content(); ?>
			</article>
			<div class="negar-information-page__footer">
				<p><?php esc_html_e( 'اگر پاسخ پرسش خود را پیدا نکردید، از طریق فرم تماس برای فروشگاه پیام بگذارید.', 'negar-shop' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'صفحهٔ تماس', 'negar-shop' ); ?></a>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>
