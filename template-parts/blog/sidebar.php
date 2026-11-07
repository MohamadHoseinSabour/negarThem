<?php
/**
 * Blog Sidebar Component - Exact Match to Demo 43 (latest-articles-nk__sidebar)
 *
 * @package Negar_Shop
 * @version 2.2.0
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

<aside class="latest-articles-nk__sidebar d-flex flex-column gap-4" aria-label="<?php esc_attr_e( 'سایدبار وبلاگ', 'negar-shop' ); ?>">

	<!-- Widget 1: Search Form -->
	<div class="latest-articles-nk__sidebar__box bg-white rounded-4 border border-light-subtle shadow-xs p-4">
		<h4 class="fs-6 fw-bold text-dark pb-3 mb-3 border-bottom border-light-subtle d-flex align-items-center justify-content-between">
			<span><?php esc_html_e( 'جستجو در مقالات', 'negar-shop' ); ?></span>
			<i class="icon-search fs-6 text-muted" aria-hidden="true"></i>
		</h4>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="position-relative d-flex align-items-center">
			<input type="search" name="s" placeholder="<?php esc_attr_e( 'جستجوی مقاله...', 'negar-shop' ); ?>" class="form-control rounded-pill pe-3 ps-5 fs-7 bg-light border-0 py-2">
			<button type="submit" class="btn position-absolute start-0 text-muted p-0 ps-3 border-0 bg-transparent" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
				<i class="icon-search fs-6" aria-hidden="true"></i>
			</button>
		</form>
	</div>

	<!-- Widget 2: Categories -->
	<?php if ( ! empty( $categories ) ) : ?>
		<div class="latest-articles-nk__sidebar__box bg-white rounded-4 border border-light-subtle shadow-xs p-4">
			<h4 class="fs-6 fw-bold text-dark pb-3 mb-3 border-bottom border-light-subtle d-flex align-items-center justify-content-between">
				<span><?php esc_html_e( 'دسته‌بندی‌های وبلاگ', 'negar-shop' ); ?></span>
				<i class="icon-category fs-6 text-muted" aria-hidden="true"></i>
			</h4>
			<ul class="list-unstyled p-0 m-0 d-flex flex-column gap-2 fs-7">
				<?php foreach ( $categories as $cat ) : ?>
					<li>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="d-flex align-items-center justify-content-between py-1 text-dark text-decoration-none hover-primary">
							<span><?php echo esc_html( $cat->name ); ?></span>
							<span class="badge rounded-pill bg-light text-muted px-2 py-1 font-monospace"><?php echo esc_html( $cat->count ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<!-- Widget 3: Recent Articles -->
	<?php if ( $recent_posts->have_posts() ) : ?>
		<div class="latest-articles-nk__sidebar__box bg-white rounded-4 border border-light-subtle shadow-xs p-4">
			<h4 class="fs-6 fw-bold text-dark pb-3 mb-3 border-bottom border-light-subtle d-flex align-items-center justify-content-between">
				<span><?php esc_html_e( 'جدیدترین مقالات', 'negar-shop' ); ?></span>
				<i class="icon-document fs-6 text-muted" aria-hidden="true"></i>
			</h4>
			<div class="d-flex flex-column gap-3">
				<?php
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					$r_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : NEGAR_SHOP_URI . '/assets/images/new-demo/1.jpeg';
					?>
					<div class="d-flex align-items-center gap-3">
						<a href="<?php the_permalink(); ?>" class="flex-shrink-0 rounded-3 overflow-hidden" style="width: 60px; height: 60px;">
							<img src="<?php echo esc_url( $r_thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
						</a>
						<div class="min-w-0">
							<a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none hover-primary fs-7 fw-bold line-clamp-2 mb-1 d-block">
								<?php the_title(); ?>
							</a>
							<span class="text-muted fs-8">
								<?php echo esc_html( get_the_date() ); ?>
							</span>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	<?php endif; ?>

</aside>
