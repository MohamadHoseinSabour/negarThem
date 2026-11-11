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
				<div><span class="negar-showcase-hero__eyebrow"><i class="icon-information-solid" aria-hidden="true"></i><?php esc_html_e( 'دربارهٔ فروشگاه', 'negar-shop' ); ?></span><h1><?php the_title(); ?></h1><p><?php esc_html_e( 'داستان، ارزش‌ها و اطلاعات فروشگاه را از مدیریت این برگه تکمیل کنید تا مشتریان با شیوهٔ فعالیت شما آشنا شوند.', 'negar-shop' ); ?></p></div>
				<div class="negar-showcase-hero__mark" aria-hidden="true"><i class="icon-shopping-bag-3"></i></div>
			</section>

			<section class="negar-about-intro">
				<div class="negar-about-intro__content">
					<span><?php esc_html_e( 'معرفی فروشگاه', 'negar-shop' ); ?></span>
					<div class="entry-content">
						<?php if ( trim( get_the_content() ) ) : ?>
							<?php the_content(); ?>
						<?php else : ?>
							<p><?php esc_html_e( 'متن معرفی، داستان شکل‌گیری و اطلاعات واقعی فروشگاه را از ویرایشگر این برگه وارد کنید. این بخش عمداً هیچ ادعای نمونه‌ای دربارهٔ خدمات یا مجوزها ندارد.', 'negar-shop' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<aside class="negar-about-intro__guide"><i class="icon-document" aria-hidden="true"></i><h2><?php esc_html_e( 'صفحه‌ای قابل شخصی‌سازی', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'برای حفظ شفافیت، اطلاعات فروشگاه، مجوزها و تعهدات فقط پس از ثبت جزئیات واقعی نمایش داده شوند.', 'negar-shop' ); ?></p></aside>
			</section>

			<section class="negar-about-topics" aria-label="<?php esc_attr_e( 'موضوع‌های اصلی فروشگاه', 'negar-shop' ); ?>">
				<article><span><i class="icon-box" aria-hidden="true"></i></span><h2><?php esc_html_e( 'محصولات', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'برای بررسی کالاها و دسته‌بندی‌های فروشگاه به بخش محصولات بروید.', 'negar-shop' ); ?></p><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'مشاهدهٔ محصولات', 'negar-shop' ); ?></a></article>
				<article><span><i class="icon-comments" aria-hidden="true"></i></span><h2><?php esc_html_e( 'پشتیبانی', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'راه‌های ارتباطی و فرم پیام در صفحهٔ تماس در دسترس است.', 'negar-shop' ); ?></p><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'ارتباط با فروشگاه', 'negar-shop' ); ?></a></article>
				<article><span><i class="icon-document" aria-hidden="true"></i></span><h2><?php esc_html_e( 'سفارش‌ها', 'negar-shop' ); ?></h2><p><?php esc_html_e( 'وضعیت سفارش‌های ثبت‌شده از حساب کاربری قابل مشاهده است.', 'negar-shop' ); ?></p><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></a></article>
			</section>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>
