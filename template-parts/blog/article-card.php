<?php
/**
 * Blog Article Card Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
$thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : 'https://placehold.co/600x400/f1f5f9/94a3b8?text=Negar+Shop';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'negar-article-card bg-surface border border-border rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group' ); ?>>
	
	<!-- 16:9 Image -->
	<a href="<?php the_permalink(); ?>" class="article-thumb block relative aspect-video overflow-hidden bg-slate-100">
		<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
		<?php if ( $primary_cat ) : ?>
			<span class="absolute top-3 right-3 text-2xs font-bold py-1 px-2.5 rounded-full bg-surface/90 backdrop-blur-xs text-primary shadow-xs">
				<?php echo esc_html( $primary_cat->name ); ?>
			</span>
		<?php endif; ?>
	</a>

	<!-- Body -->
	<div class="article-body p-5 flex-1 flex flex-col justify-between">
		
		<div>
			<!-- Meta (Date & Author) -->
			<div class="article-meta flex items-center gap-3 text-xs text-muted mb-2">
				<span class="flex items-center gap-1">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</span>
				<span>•</span>
				<span><?php echo esc_html( get_the_author() ); ?></span>
			</div>

			<!-- Title -->
			<h3 class="article-title text-base font-bold text-dark group-hover:text-primary transition-colors leading-snug mb-2 line-clamp-2">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<!-- Excerpt -->
			<p class="article-excerpt text-xs text-muted leading-relaxed line-clamp-2 mb-4">
				<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
			</p>
		</div>

		<!-- Footer Link -->
		<div class="article-footer pt-3 border-t border-border flex items-center justify-between text-xs">
			<a href="<?php the_permalink(); ?>" class="text-primary font-bold inline-flex items-center gap-1 group-hover:gap-1.5 transition-all">
				<span><?php esc_html_e( 'ادامه مطلب', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
			</a>
			<span class="text-2xs text-muted font-medium"><?php esc_html_e( 'زمان مطالعه: ۵ دقیقه', 'negar-shop' ); ?></span>
		</div>

	</div>

</article>
