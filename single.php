<?php
/**
 * Single Post Template - Exact Match to Demo 43 (detail-blog / detail-blog-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories  = get_the_category();
$primary_cat = ! empty( $categories ) ? $categories[0] : null;
$thumb_url   = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
$post_id     = get_the_ID();
?>

<div class="detail-blog detail-blog-nk py-4">
	<div class="container">

		<!-- Breadcrumb -->
		<div class="detail-blog-nk__breadcrumb mb-4">
			<nav class="breadcrumb-nk d-flex align-items-center gap-2 fs-7 text-muted" aria-label="مسیر راهنما">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-muted text-decoration-none hover-primary">خانه</a>
				<i class="icon-chevron-left fs-8"></i>
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="text-muted text-decoration-none hover-primary">وبلاگ</a>
				<?php if ( $primary_cat ) : ?>
					<i class="icon-chevron-left fs-8"></i>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="text-muted text-decoration-none hover-primary">
						<?php echo esc_html( $primary_cat->name ); ?>
					</a>
				<?php endif; ?>
				<i class="icon-chevron-left fs-8"></i>
				<span class="text-dark fw-bold truncate"><?php the_title(); ?></span>
			</nav>
		</div>

		<div class="row g-4 items-start position-relative">
			<!-- Main Column (8 cols) -->
			<div class="col-12 col-lg-8">
				<?php while ( have_posts() ) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-4 border border-light-subtle shadow-sm p-4 p-md-5 mb-4' ); ?>>
						
						<!-- Post Header Info -->
						<div class="detail-blog-nk__info p-0 border-0 mb-4">
							<?php if ( $primary_cat ) : ?>
								<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="d-inline-block px-3 py-1 rounded-pill fs-7 fw-bold mb-3 text-decoration-none" style="background-color: rgba(233, 5, 68, 0.1); color: var(--kaveh-primary-color, #e90544);">
									<?php echo esc_html( $primary_cat->name ); ?>
								</a>
							<?php endif; ?>

							<h1 class="detail-blog-nk__title fs-4 fs-md-3 fw-bold text-dark lh-base mb-3">
								<?php the_title(); ?>
							</h1>

							<!-- Author Meta Row -->
							<div class="detail-blog-nk__author d-flex align-items-center gap-3 pb-3 border-bottom border-light-subtle fs-7 text-muted">
								<div class="d-flex align-items-center gap-2">
									<div class="rounded-circle overflow-hidden" style="width: 36px; height: 36px;">
										<?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', '', array( 'class' => 'w-100 h-100 object-fit-cover' ) ); ?>
									</div>
									<span class="name fw-bold text-dark"><?php echo esc_html( get_the_author() ); ?></span>
								</div>
								<span>•</span>
								<div class="date d-flex align-items-center gap-1">
									<i class="icon-calendar fs-7"></i>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date() ); ?>
									</time>
								</div>
								<span>•</span>
								<div class="d-flex align-items-center gap-1">
									<i class="icon-comment fs-7"></i>
									<span><?php comments_number( 'بدون دیدگاه', '۱ دیدگاه', '% دیدگاه' ); ?></span>
								</div>
							</div>
						</div>

						<!-- Featured Image -->
						<?php if ( $thumb_url ) : ?>
							<div class="detail-blog-nk__image overflow-hidden mb-4 rounded-4" style="max-height: 480px;">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 h-100 object-fit-cover">
							</div>
						<?php endif; ?>

						<!-- Excerpt Lead Box -->
						<?php if ( has_excerpt() ) : ?>
							<div class="detail-blog-nk__excerpt mb-4">
								<div class="detail-blog-nk__excerpt__label">
									<i class="icon-info-circle fs-6"></i>
									<span>خلاصه مطلب</span>
								</div>
								<p class="detail-blog-nk__excerpt__text m-0">
									<?php echo esc_html( get_the_excerpt() ); ?>
								</p>
							</div>
						<?php endif; ?>

						<!-- Post Content -->
						<div class="detail-blog-content detail-blog-nk__content lh-lg text-dark fs-6">
							<?php the_content(); ?>
						</div>

						<!-- Tags & Sharing -->
						<?php
						$post_tags = get_the_tags();
						if ( $post_tags ) :
							?>
							<div class="post-tags mt-5 pt-3 border-top border-light-subtle d-flex align-items-center gap-2 flex-wrap">
								<span class="fw-bold fs-7 text-muted">برچسب‌ها:</span>
								<?php foreach ( $post_tags as $tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="badge rounded-pill bg-light text-dark text-decoration-none px-3 py-2 fs-7 hover-primary">
										#<?php echo esc_html( $tag->name ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

					</article>

					<!-- Comments Section -->
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

				<?php endwhile; ?>
			</div>

			<!-- Sidebar Column (4 cols) -->
			<div class="col-12 col-lg-4">
				<?php get_template_part( 'template-parts/blog/sidebar' ); ?>
			</div>
		</div>

	</div>
</div>

<?php
get_footer();
