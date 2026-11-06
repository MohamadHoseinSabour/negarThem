<?php
/**
 * Latest Blog Posts Component (HOME-08)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$latest_posts = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'ignore_sticky_posts' => 1,
	)
);
?>

<section class="negar-home-blog-section py-6" aria-label="<?php esc_attr_e( 'آخرین مقالات و آموزش‌ها', 'negar-shop' ); ?>">
	<div class="container">
		
		<!-- Section Header -->
		<div class="section-heading flex items-center justify-between mb-5">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
				<h2 class="text-base md:text-lg font-bold text-slate-800 m-0">
					<?php esc_html_e( 'آخرین مقالات و راهنماهای خرید', 'negar-shop' ); ?>
				</h2>
			</div>
			<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
				<span><?php esc_html_e( 'مشاهده همه مقالات', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- Posts Grid -->
		<div class="blog-posts-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
			<?php
			if ( $latest_posts->have_posts() ) :
				while ( $latest_posts->have_posts() ) :
					$latest_posts->the_post();
					?>
					<article class="blog-card group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-card-hover transition duration-300 flex flex-col justify-between">
						<div>
							<!-- Thumbnail -->
							<a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden bg-slate-100 relative">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition duration-300' ) ); ?>
								<?php else : ?>
									<div class="w-full h-full flex items-center justify-center text-slate-400">
										<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
									</div>
								<?php endif; ?>
							</a>

							<!-- Body -->
							<div class="p-4">
								<div class="meta text-3xs text-slate-400 mb-2 flex items-center gap-2">
									<span><?php echo esc_html( get_the_date() ); ?></span>
									<span>•</span>
									<span><?php echo esc_html( get_the_author() ); ?></span>
								</div>

								<h3 class="post-title text-xs font-bold text-slate-800 line-clamp-2 leading-snug mb-2 m-0">
									<a href="<?php the_permalink(); ?>" class="hover:text-emerald-600 transition">
										<?php the_title(); ?>
									</a>
								</h3>

								<p class="post-excerpt text-3xs text-slate-500 line-clamp-2 leading-relaxed m-0">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
								</p>
							</div>
						</div>

						<!-- Footer -->
						<div class="px-4 py-3 border-t border-slate-50 flex items-center justify-between text-xs">
							<a href="<?php the_permalink(); ?>" class="text-emerald-600 font-bold text-2xs hover:underline flex items-center gap-1">
								<span><?php esc_html_e( 'مطالعه مقاله', 'negar-shop' ); ?></span>
								<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			else :
				// Sample Fallback Articles
				$fallback_blogs = array(
					array( 'title' => 'راهنمای خرید بهترین گوشی‌های هوشمند میان‌رده در سال ۱۴۰۵', 'date' => '۱۵ آبان ۱۴۰۵' ),
					array( 'title' => 'مقایسه جامع ساعت‌های هوشمند ورزشی و کاربردهای سلامتی', 'date' => '۱۲ آبان ۱۴۰۵' ),
					array( 'title' => 'چگونه عمر باتری هندزفری‌های بلوتوثی را دو برابر کنیم؟', 'date' => '۰۸ آبان ۱۴۰۵' ),
					array( 'title' => 'راهنمای انتخاب لپ‌تاپ مناسب برای برنامه‌نویسی و طراحی گرافیک', 'date' => '۰۲ آبان ۱۴۰۵' ),
				);
				foreach ( $fallback_blogs as $fb ) :
					?>
					<article class="blog-card group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-card-hover transition duration-300 flex flex-col justify-between">
						<div>
							<div class="aspect-video bg-slate-100 flex items-center justify-center text-slate-400">
								<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
							</div>
							<div class="p-4">
								<span class="text-3xs text-slate-400 mb-1 block"><?php echo esc_html( $fb['date'] ); ?></span>
								<h3 class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug mb-2 m-0"><?php echo esc_html( $fb['title'] ); ?></h3>
								<p class="text-3xs text-slate-500 line-clamp-2 m-0">بررسی مهم‌ترین فاکتورها، مشخصات فنی و نکات کلیدی قبل از خرید برای انتخاب هوشمندانه‌تر.</p>
							</div>
						</div>
						<div class="px-4 py-3 border-t border-slate-50 text-emerald-600 font-bold text-2xs">
							<span>مطالعه ادامه مقاله ←</span>
						</div>
					</article>
					<?php
				endforeach;
			endif;
			?>
		</div>

	</div>
</section>
