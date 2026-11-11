<?php
/**
 * Template Name: About Us / درباره ما
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main negar-content-page negar-about-page">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>"><ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'negar-shop' ); ?></a></li><li class="separator" aria-hidden="true">/</li><li class="current" aria-current="page"><?php the_title(); ?></li></ol></nav>

			<section class="negar-showcase-hero negar-showcase-hero--about">
				<div><span class="negar-showcase-hero__eyebrow"><i class="icon-information-solid" aria-hidden="true"></i><?php echo esc_html( negar_theme_text( 'about', 'hero_eyebrow' ) ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( negar_theme_text( 'about', 'hero_description' ) ); ?></p></div>
				<div class="negar-showcase-hero__mark" aria-hidden="true"><i class="icon-shopping-bag-3"></i></div>
			</section>

			<section class="negar-about-intro">
				<div class="negar-about-intro__content">
					<span><?php echo esc_html( negar_theme_text( 'about', 'intro_label' ) ); ?></span>
					<div class="entry-content">
						<?php if ( trim( get_the_content() ) ) : ?>
							<?php the_content(); ?>
						<?php else : ?>
							<p><?php echo esc_html( negar_theme_text( 'about', 'empty_content' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<aside class="negar-about-intro__guide"><i class="icon-document" aria-hidden="true"></i><h2><?php echo esc_html( negar_theme_text( 'about', 'guide_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'about', 'guide_description' ) ); ?></p></aside>
			</section>

			<section class="negar-about-topics" aria-label="<?php esc_attr_e( 'موضوع‌های اصلی فروشگاه', 'negar-shop' ); ?>">
				<article><span><i class="icon-box" aria-hidden="true"></i></span><h2><?php echo esc_html( negar_theme_text( 'about', 'products_card_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'about', 'products_card_description' ) ); ?></p><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php echo esc_html( negar_theme_text( 'about', 'products_card_action' ) ); ?></a></article>
				<article><span><i class="icon-comments" aria-hidden="true"></i></span><h2><?php echo esc_html( negar_theme_text( 'about', 'support_card_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'about', 'support_card_description' ) ); ?></p><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php echo esc_html( negar_theme_text( 'about', 'support_card_action' ) ); ?></a></article>
				<article><span><i class="icon-document" aria-hidden="true"></i></span><h2><?php echo esc_html( negar_theme_text( 'about', 'orders_card_title' ) ); ?></h2><p><?php echo esc_html( negar_theme_text( 'about', 'orders_card_description' ) ); ?></p><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>"><?php echo esc_html( negar_theme_text( 'about', 'orders_card_action' ) ); ?></a></article>
			</section>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>
