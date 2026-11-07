<?php
/**
 * Blog Article Card Component - Exact Match to Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories  = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
$thumb_url   = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : NEGAR_SHOP_URI . '/assets/images/new-demo/1.jpeg';
$author_id   = get_the_author_meta( 'ID' );
?>

<div class="col-12 col-md-6 mb-4">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'article-card-nk bg-white rounded-4 overflow-hidden border border-light-subtle shadow-xs h-100 d-flex flex-column transition-all' ); ?>>
		
		<!-- 16:9 Thumbnail -->
		<div class="article-thumb position-relative overflow-hidden" style="aspect-ratio: 16/9; background-color: #f1f5f9;">
			<a href="<?php the_permalink(); ?>" class="d-block w-100 h-100">
				<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover transition-transform duration-300" loading="lazy">
			</a>
			<?php if ( $primary_cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="position-absolute top-0 end-0 m-3 px-3 py-1 rounded-pill bg-white text-dark fs-7 fw-bold shadow-sm text-decoration-none" style="z-index: 2;">
					<?php echo esc_html( $primary_cat->name ); ?>
				</a>
			<?php endif; ?>
		</div>

		<!-- Body -->
		<div class="article-body p-4 d-flex flex-column justify-content-between flex-grow-1">
			<div>
				<!-- Meta: Author & Date -->
				<div class="article-meta d-flex align-items-center gap-2 mb-2 text-muted fs-7">
					<div class="d-flex align-items-center gap-1">
						<i class="icon-calendar fs-7" aria-hidden="true"></i>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</div>
					<span>•</span>
					<div class="d-flex align-items-center gap-1">
						<i class="icon-user fs-7" aria-hidden="true"></i>
						<span><?php echo esc_html( get_the_author() ); ?></span>
					</div>
				</div>

				<!-- Title -->
				<h3 class="article-title fs-6 fw-bold mb-2">
					<a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none hover-primary line-clamp-2">
						<?php the_title(); ?>
					</a>
				</h3>

				<!-- Excerpt -->
				<p class="article-excerpt text-muted fs-7 leading-relaxed mb-3 line-clamp-2">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
				</p>
			</div>

			<!-- Footer Action -->
			<div class="article-footer pt-3 border-top border-light-subtle d-flex align-items-center justify-content-between">
				<a href="<?php the_permalink(); ?>" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-bold fs-7" style="color: var(--kaveh-primary-color, #e90544);">
					<span>مشاهده مقاله</span>
					<i class="icon-arrow-left fs-7" aria-hidden="true"></i>
				</a>
				<span class="text-muted fs-7">
					<?php comments_number( '۰ دیدگاه', '۱ دیدگاه', '% دیدگاه' ); ?>
				</span>
			</div>
		</div>

	</article>
</div>
