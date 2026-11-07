<?php
/**
 * Shop Products Loop & Pagination Component - Exact Match to Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;
?>

<!-- Products Grid -->
<div class="category-nk__products row" id="woor">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php wc_get_template_part( 'content', 'product' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="col-12 py-12 text-center">
			<div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle bg-light mb-3">
				<i class="iconly-Search text-muted fs-3"></i>
			</div>
			<h3 class="f-payda fs-5 fw-bold text-dark mb-2"><?php esc_html_e( 'هیچ محصولی با این مشخصات یافت نشد.', 'negar-shop' ); ?></h3>
			<p class="text-muted fs-6 mb-4"><?php esc_html_e( 'لطفاً فیلترهای انتخابی را تغییر داده یا بازنشانی کنید.', 'negar-shop' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<!-- Pagination Wrapper -->
<div class="pg1">
	<?php
	woocommerce_pagination(
		array(
			'prev_text' => '<i class="icon-chevron-right-2 lh-1" aria-hidden="true"></i>',
			'next_text' => '<i class="icon-chevron-left-2 lh-1" aria-hidden="true"></i>',
			'type'      => 'list',
		)
	);
	?>
</div>
