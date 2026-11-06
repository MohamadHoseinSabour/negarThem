<?php
/**
 * Blog Sidebar Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_categories( array( 'hide_empty' => true ) );

$recent_posts = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
	)
);
?>

<aside class="negar-blog-sidebar flex flex-col gap-6" aria-label="<?php esc_attr_e( 'سایدبار وبلاگ', 'negar-shop' ); ?>">

	<!-- Widget 1: Search Form -->
	<div class="blog-widget bg-surface border border-border rounded-2xl p-5 shadow-xs">
		<h4 class="text-sm font-bold text-dark pb-3 mb-3 border-b border-border flex items-center justify-between">
			<span><?php esc_html_e( 'جستجو در مقالات', 'negar-shop' ); ?></span>
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
		</h4>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative flex items-center">
			<input type="search" name="s" placeholder="<?php esc_attr_e( 'جستجوی مقاله...', 'negar-shop' ); ?>" class="w-full text-xs p-2.5 pl-9 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all">
			<button type="submit" class="absolute left-2.5 text-muted hover:text-primary focus:outline-none" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
		</form>
	</div>

	<!-- Widget 2: Categories -->
	<?php if ( ! empty( $categories ) ) : ?>
		<div class="blog-widget bg-surface border border-border rounded-2xl p-5 shadow-xs">
			<h4 class="text-sm font-bold text-dark pb-3 mb-3 border-b border-border flex items-center justify-between">
				<span><?php esc_html_e( 'دسته‌بندی‌های وبلاگ', 'negar-shop' ); ?></span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
			</h4>
			<ul class="flex flex-col gap-2 text-xs sm:text-sm">
				<?php foreach ( $categories as $cat ) : ?>
					<li>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="flex items-center justify-between py-1 text-dark hover:text-primary transition-colors">
							<span><?php echo esc_html( $cat->name ); ?></span>
							<span class="text-xs text-muted bg-slate-100 px-2 py-0.5 rounded-full font-mono"><?php echo esc_html( $cat->count ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<!-- Widget 3: Recent Articles -->
	<?php if ( $recent_posts->have_posts() ) : ?>
		<div class="blog-widget bg-surface border border-border rounded-2xl p-5 shadow-xs">
			<h4 class="text-sm font-bold text-dark pb-3 mb-3 border-b border-border flex items-center justify-between">
				<span><?php esc_html_e( 'آخرین مقالات', 'negar-shop' ); ?></span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
			</h4>
			<div class="flex flex-col gap-3">
				<?php
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					$r_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : 'https://placehold.co/100x100/f1f5f9/94a3b8?text=Blog';
					?>
					<div class="recent-article-item flex items-center gap-3 group">
						<a href="<?php the_permalink(); ?>" class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
							<img src="<?php echo esc_url( $r_thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
						</a>
						<div class="min-w-0 flex-1">
							<h5 class="text-xs font-bold text-dark group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-1">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h5>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="text-2xs text-muted block"><?php echo esc_html( get_the_date() ); ?></time>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	<?php endif; ?>

</aside>
