<?php
/**
 * The Template for displaying product archives, including the main shop page
 * Exact match to Demo 43 (category-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<section class="category-nk mt-4">
			<div class="container">

				<!-- Breadcrumbs -->
				<div class="breadcrumb-nk d-flex align-items-center mb-4">
					<ul class="d-flex align-items-center flex-nowrap">
						<li class="d-flex align-items-center flex-shrink-0">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-block d-flex align-items-center gap-2">
								<i class="iconly-Home lh-1"></i>خانه
							</a>
						</li>
						<li class="d-flex align-items-center flex-shrink-0 is-current" aria-current="page">
							<span class="breadcrumb-nk__current"><?php woocommerce_page_title(); ?></span>
						</li>
					</ul>
				</div>

				<div class="row">
					<!-- Sidebar Filters Column: col-6 col-md-4 col-xl-3 category-nk__filter-col -->
					<div class="col-6 col-md-4 col-xl-3 category-nk__filter-col">
						<?php get_template_part( 'template-parts/shop/sidebar-filters' ); ?>
					</div>

					<!-- Products Column: col-lg-8 col-xl-9 -->
					<div class="col-lg-8 col-xl-9">
						<?php get_template_part( 'template-parts/shop/toolbar' ); ?>
						<?php get_template_part( 'template-parts/shop/loop' ); ?>
					</div>
				</div>

				<!-- Archive Description -->
				<?php if ( is_product_taxonomy() ) : ?>
					<div class="kaveh-archive-description mt-5">
						<?php do_action( 'woocommerce_archive_description' ); ?>
					</div>
				<?php endif; ?>

			</div>
		</section>
	</main>
</div>

<?php
get_footer( 'shop' );
