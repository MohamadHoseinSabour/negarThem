<?php
/**
 * The template for displaying archive pages (Blog, Category, Author, Date)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="negar-blog-archive py-8">
	<div class="container mx-auto px-4">
		
		<!-- Archive Header -->
		<header class="page-header mb-8 pb-4 border-b border-border">
			<h1 class="page-title text-xl sm:text-2xl font-black text-dark mb-2">
				<?php the_archive_title(); ?>
			</h1>
			<?php the_archive_description( '<div class="archive-description text-xs sm:text-sm text-muted leading-relaxed">', '</div>' ); ?>
		</header>

		<!-- 2-Column Blog Layout -->
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

			<!-- Articles Grid (Right in RTL, 8 cols) -->
			<div class="lg:col-span-8 w-full">
				<?php if ( have_posts() ) : ?>
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/blog/article-card' );
						endwhile;
						?>
					</div>

					<!-- Pagination -->
					<div class="negar-pagination mt-10 flex justify-center">
						<?php
						the_posts_pagination(
							array(
								'mid_size'  => 2,
								'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>',
								'next_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>',
							)
						);
						?>
					</div>

				<?php else : ?>
					<div class="bg-surface border border-border rounded-2xl p-12 text-center">
						<p class="text-base text-dark mb-4"><?php esc_html_e( 'هیچ مقاله‌ای در این بخش یافت نشد.', 'negar-shop' ); ?></p>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary py-2.5 px-6 text-xs font-bold"><?php esc_html_e( 'بازگشت به صفحه اصلی', 'negar-shop' ); ?></a>
					</div>
				<?php endif; ?>
			</div>

			<!-- Blog Sidebar (Left in RTL, 4 cols) -->
			<div class="lg:col-span-4 w-full">
				<?php get_template_part( 'template-parts/blog/sidebar' ); ?>
			</div>

		</div>

	</div>
</div>

<?php
get_footer();
