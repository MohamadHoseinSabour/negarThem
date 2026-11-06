<?php
/**
 * The template for displaying all single posts
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
$thumb_url   = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
?>

<div class="negar-single-post py-8">
	<div class="container mx-auto px-4">

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

			<!-- Main Article Column (Right in RTL, 8 cols) -->
			<main class="lg:col-span-8 w-full bg-surface border border-border rounded-2xl p-6 sm:p-10 shadow-sm">
				
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
						<!-- Header & Meta -->
						<header class="entry-header mb-6 pb-6 border-b border-border">
							
							<?php if ( $primary_cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full mb-3">
									<?php echo esc_html( $primary_cat->name ); ?>
								</a>
							<?php endif; ?>

							<h1 class="entry-title text-xl sm:text-3xl font-black text-dark leading-snug mb-4">
								<?php the_title(); ?>
							</h1>

							<div class="entry-meta flex flex-wrap items-center gap-4 text-xs text-muted">
								<span class="flex items-center gap-1.5">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</span>
								<span>•</span>
								<span class="flex items-center gap-1.5">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
									<span><?php echo esc_html( get_the_author() ); ?></span>
								</span>
								<span>•</span>
								<span class="flex items-center gap-1.5">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
									<span><?php comments_number( __( 'بدون دیدگاه', 'negar-shop' ), __( '۱ دیدگاه', 'negar-shop' ), __( '% دیدگاه', 'negar-shop' ) ); ?></span>
								</span>
							</div>

						</header>

						<!-- Featured Image -->
						<?php if ( $thumb_url ) : ?>
							<div class="featured-image mb-8 rounded-xl overflow-hidden max-h-[440px]">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover">
							</div>
						<?php endif; ?>

						<!-- Content Body -->
						<div class="entry-content prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-dark mb-10">
							<?php
							the_content();

							wp_link_pages(
								array(
									'before' => '<div class="page-links mt-6 pt-4 border-t border-border flex items-center gap-2 text-xs font-bold">' . esc_html__( 'صفحات:', 'negar-shop' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>

						<!-- Tags -->
						<?php
						$tags = get_the_tags();
						if ( $tags ) :
							?>
							<div class="entry-tags flex flex-wrap items-center gap-2 mb-8 pt-6 border-t border-border">
								<span class="text-xs text-muted font-bold"><?php esc_html_e( 'برچسب‌ها:', 'negar-shop' ); ?></span>
								<?php foreach ( $tags as $tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="text-xs px-3 py-1 rounded-full bg-slate-100 hover:bg-primary hover:text-white transition-colors text-dark font-medium">
										#<?php echo esc_html( $tag->name ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<!-- Author Box -->
						<div class="author-box flex items-start gap-4 p-5 rounded-2xl bg-slate-50 border border-border mb-10">
							<div class="w-14 h-14 rounded-full overflow-hidden bg-white border border-border flex-shrink-0">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 56, '', '', array( 'class' => 'w-full h-full object-cover' ) ); ?>
							</div>
							<div class="min-w-0 flex-1">
								<h4 class="text-sm font-bold text-dark mb-1"><?php echo esc_html( get_the_author() ); ?></h4>
								<p class="text-xs text-muted leading-relaxed"><?php echo esc_html( get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) : __( 'نویسنده و کارشناس تخصصی تولید محتوا در نگار شاپ.', 'negar-shop' ) ); ?></p>
							</div>
						</div>

						<!-- Adjacent Posts Navigation -->
						<div class="adjacent-posts grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10 pt-6 border-t border-border">
							<?php
							$prev_post = get_previous_post();
							$next_post = get_next_post();
							?>
							<?php if ( $prev_post ) : ?>
								<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="p-4 rounded-xl border border-border hover:border-primary transition-all flex flex-col group">
									<span class="text-2xs text-muted mb-1"><?php esc_html_e( '← مقاله قبلی', 'negar-shop' ); ?></span>
									<span class="text-xs font-bold text-dark group-hover:text-primary transition-colors line-clamp-1"><?php echo esc_html( $prev_post->post_title ); ?></span>
								</a>
							<?php else : ?>
								<div></div>
							<?php endif; ?>

							<?php if ( $next_post ) : ?>
								<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="p-4 rounded-xl border border-border hover:border-primary transition-all flex flex-col items-end text-left group">
									<span class="text-2xs text-muted mb-1"><?php esc_html_e( 'مقاله بعدی →', 'negar-shop' ); ?></span>
									<span class="text-xs font-bold text-dark group-hover:text-primary transition-colors line-clamp-1"><?php echo esc_html( $next_post->post_title ); ?></span>
								</a>
							<?php endif; ?>
						</div>

						<!-- Comments -->
						<?php
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>

					</article>

				<?php endwhile; ?>

			</main>

			<!-- Blog Sidebar (Left in RTL, 4 cols) -->
			<div class="lg:col-span-4 w-full">
				<?php get_template_part( 'template-parts/blog/sidebar' ); ?>
			</div>

		</div>

	</div>
</div>

<?php
get_footer();
