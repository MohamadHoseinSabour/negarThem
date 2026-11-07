<?php
/**
 * Blog and Archive Template - 1:1 Match to Demo 43 (latest-articles-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories     = get_categories( array( 'hide_empty' => true ) );
$current_cat_id = is_category() ? get_queried_object_id() : 0;
?>

<section class="latest-articles-nk py-5">
	<div class="container">
		
		<!-- Heading -->
		<div class="latest-articles-nk__heading mb-5">
			<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
				<div>
					<span class="latest-articles-nk__heading__sup-title d-inline-flex align-items-center rounded-pill mb-2">
						<i class="icon-document text-primary" aria-hidden="true"></i>
						<span>وبلاگ و اخبار تکنولوژی</span>
					</span>
					<h1 class="latest-articles-nk__heading__title position-relative m-0 fw-bold">
						<?php
						if ( is_category() ) {
							single_cat_title();
						} elseif ( is_tag() ) {
							single_tag_title();
						} elseif ( is_author() ) {
							the_author();
						} else {
							esc_html_e( 'آخرین مقالات و راهنماهای خرید', 'negar-shop' );
						}
						?>
					</h1>
				</div>

				<!-- Category Tabs Pills -->
				<?php if ( ! empty( $categories ) ) : ?>
					<div class="d-flex align-items-center gap-2 flex-wrap">
						<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="latest-articles-nk__heading__tab text-decoration-none <?php echo ( 0 === $current_cat_id ) ? 'active' : ''; ?>">
							همه مقالات
						</a>
						<?php foreach ( array_slice( $categories, 0, 5 ) as $cat ) : ?>
							<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="latest-articles-nk__heading__tab text-decoration-none <?php echo ( $current_cat_id === $cat->term_id ) ? 'active' : ''; ?>">
								<?php echo esc_html( $cat->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Main 2-Column Grid -->
		<div class="row g-4">
			<!-- Articles Column (8 cols) -->
			<div class="col-12 col-lg-8">
				<?php if ( have_posts() ) : ?>
					<div class="latest-articles-nk__articles row g-4">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/blog/article-card' );
						endwhile;
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination-nk d-flex justify-content-center align-items-center gap-2 mt-5">
						<?php
						echo paginate_links(
							array(
								'prev_text' => '<span class="prev d-flex align-items-center justify-content-center rounded-3"><i class="icon-chevron-right fs-7"></i></span>',
								'next_text' => '<span class="next d-flex align-items-center justify-content-center rounded-3"><i class="icon-chevron-left fs-7"></i></span>',
								'type'      => 'plain',
							)
						);
						?>
					</div>
				<?php else : ?>
					<div class="bg-white rounded-4 border border-light-subtle p-5 text-center shadow-xs">
						<i class="icon-document fs-1 text-muted mb-3 d-block" aria-hidden="true"></i>
						<h3 class="fs-6 fw-bold mb-2">مقاله‌ای در این بخش یافت نشد!</h3>
						<p class="text-muted fs-7 mb-4">می‌توانید سایر دسته‌بندی‌ها یا صفحه اصلی را مشاهده فرمایید.</p>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn rounded-pill px-4 text-white fw-bold fs-7" style="background-color: var(--kaveh-primary-color, #e90544);">
							بازگشت به صفحه اصلی
						</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- Sidebar Column (4 cols) -->
			<div class="col-12 col-lg-4">
				<?php get_template_part( 'template-parts/blog/sidebar' ); ?>
			</div>
		</div>

	</div>
</section>

<?php
get_footer();
