<?php
/**
 * The template for displaying search results
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;
$search_query   = get_search_query();
$total_results  = $wp_query->found_posts;
$popular_cats   = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'number'     => 6,
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);
?>

<div class="negar-search-results-page py-8">
	<div class="container mx-auto px-4">

		<!-- Search Header -->
		<header class="search-header mb-8 pb-4 border-b border-border flex flex-wrap items-center justify-between gap-4">
			<div>
				<h1 class="page-title text-xl sm:text-2xl font-black text-dark mb-1">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'نتایج جستجو برای: «%s»', 'negar-shop' ),
						'<span class="text-primary">' . esc_html( $search_query ) . '</span>'
					);
					?>
				</h1>
				<span class="text-xs text-muted">
					<?php
					printf(
						/* translators: %s: total results count */
						esc_html__( 'تعداد %s مورد یافت شد.', 'negar-shop' ),
						'<strong>' . number_format_i18n( $total_results ) . '</strong>'
					);
					?>
				</span>
			</div>

			<!-- Search Re-query Input -->
			<div class="w-full sm:w-72">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative flex items-center">
					<input type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php esc_attr_e( 'جستجوی مجدد...', 'negar-shop' ); ?>" class="w-full text-xs p-2.5 pl-9 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all">
					<button type="submit" class="absolute left-2.5 text-muted hover:text-primary focus:outline-none" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
					</button>
				</form>
			</div>
		</header>

		<?php if ( have_posts() ) : ?>

			<!-- Results Loop -->
			<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( 'product' === get_post_type() ) {
						get_template_part( 'template-parts/product/card' );
					} else {
						get_template_part( 'template-parts/blog/article-card' );
					}
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

			<!-- Empty State Recovery (CNT-06) -->
			<div class="negar-search-empty bg-surface border border-border rounded-2xl p-8 sm:p-14 text-center max-w-2xl mx-auto shadow-xs my-8">
				
				<div class="w-20 h-20 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center text-muted">
					<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"/>
						<path d="m21 21-4.3-4.3"/>
						<line x1="8" y1="11" x2="14" y2="11"/>
					</svg>
				</div>

				<h2 class="text-lg sm:text-2xl font-black text-dark mb-2">
					<?php esc_html_e( 'هیچ موردی منطبق با جستجوی شما یافت نشد!', 'negar-shop' ); ?>
				</h2>

				<p class="text-xs sm:text-sm text-muted leading-relaxed mb-8 max-w-md mx-auto">
					<?php esc_html_e( 'لطفاً املای کلمات را بررسی کنید، از عبارات عمومی‌تر استفاده کنید یا از دسته‌بندی‌های زیر دیدن فرمایید.', 'negar-shop' ); ?>
				</p>

				<?php if ( ! empty( $popular_cats ) && ! is_wp_error( $popular_cats ) ) : ?>
					<div class="pt-6 border-t border-border">
						<h4 class="text-xs font-bold text-dark mb-4"><?php esc_html_e( 'دسته‌بندی‌های پیشنهادی برای شما:', 'negar-shop' ); ?></h4>
						<div class="flex flex-wrap items-center justify-center gap-2">
							<?php foreach ( $popular_cats as $cat ) : ?>
								<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="text-xs px-3.5 py-2 rounded-full bg-slate-100 hover:bg-primary hover:text-white transition-all text-dark font-medium shadow-xs hover:shadow">
									<?php echo esc_html( $cat->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

			</div>

		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
