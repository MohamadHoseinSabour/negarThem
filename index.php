<?php
/**
 * The main template file
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main py-10">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php body_class( 'card bg-white p-6 rounded-xl border border-slate-200 shadow-sm' ); ?>>
						<header class="entry-header mb-4">
							<?php negar_shop_post_thumbnail( 'medium' ); ?>
							<div class="entry-meta text-xs text-slate-500 mb-2">
								<?php negar_shop_posted_on(); ?>
							</div>
							<?php the_title( '<h2 class="entry-title text-xl font-bold m-0"><a href="' . esc_url( get_permalink() ) . '" class="text-slate-900 hover:text-emerald-600">', '</a></h2>' ); ?>
						</header>

						<div class="entry-summary text-sm text-slate-600 line-clamp-3 mb-4">
							<?php the_excerpt(); ?>
						</div>

						<footer class="entry-footer pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
							<?php negar_shop_posted_by(); ?>
							<a href="<?php the_permalink(); ?>" class="text-emerald-600 font-semibold hover:underline">
								<?php esc_html_e( 'ادامه مطلب ←', 'negar-shop' ); ?>
							</a>
						</footer>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<div class="pagination-wrapper mt-8">
				<?php the_posts_pagination( array(
					'prev_text' => esc_html__( '← قبلی', 'negar-shop' ),
					'next_text' => esc_html__( 'بعدی →', 'negar-shop' ),
				) ); ?>
			</div>

		<?php else : ?>

			<div class="no-results not-found bg-white p-12 rounded-xl text-center border border-slate-200">
				<h2 class="text-2xl font-bold text-slate-800 mb-3"><?php esc_html_e( 'مطلبی یافت نشد!', 'negar-shop' ); ?></h2>
				<p class="text-slate-500 max-w-md mx-auto mb-6"><?php esc_html_e( 'به نظر می‌رسد هیچ محتوایی در این بخش وجود ندارد. لطفاً جستجو کنید یا به صفحه اصلی بازگردید.', 'negar-shop' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
					<?php esc_html_e( 'بازگشت به صفحه اصلی', 'negar-shop' ); ?>
				</a>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
