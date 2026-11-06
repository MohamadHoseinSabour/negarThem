<?php
/**
 * Shop Products Loop & Pagination Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;
?>

<div class="negar-products-loop-wrapper relative min-h-[300px]">
	<!-- Loading Overlay -->
	<div class="negar-loop-loader absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center z-10 opacity-0 pointer-events-none transition-opacity duration-200">
		<div class="inline-flex items-center gap-2 px-4 py-2 bg-surface rounded-full shadow-md border border-border">
			<svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
				<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
				<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
			</svg>
			<span class="text-xs font-medium text-dark"><?php esc_html_e( 'در حال بارگذاری کالاها...', 'negar-shop' ); ?></span>
		</div>
	</div>

	<!-- Product Cards Grid / List -->
	<div class="negar-products-grid is-grid grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="negar-shop-products-loop">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/product/card' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="negar-no-products col-span-full py-12 text-center bg-surface rounded-lg border border-border">
				<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-muted mb-4">
					<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"/>
						<path d="m21 21-4.3-4.3"/>
					</svg>
				</div>
				<h3 class="text-lg font-bold text-dark mb-2"><?php esc_html_e( 'هیچ کالایی با این مشخصات یافت نشد', 'negar-shop' ); ?></h3>
				<p class="text-sm text-muted mb-4"><?php esc_html_e( 'لطفاً فیلترهای انتخابی را تغییر داده یا بازنشانی کنید.', 'negar-shop' ); ?></p>
				<button type="button" class="btn btn-outline btn-sm negar-clear-all-filters">
					<?php esc_html_e( 'حذف همه فیلترها', 'negar-shop' ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<!-- Numeric Pagination -->
	<div class="negar-pagination mt-8 flex justify-center" id="negar-shop-pagination">
		<?php
		if ( $wp_query->max_num_pages > 1 ) {
			$prev_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>';
			$next_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>';

			$paginate_links = paginate_links(
				array(
					'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
					'format'    => '',
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'type'      => 'list',
					'prev_text' => $prev_svg . ' <span class="sr-only">' . esc_html__( 'قبلی', 'negar-shop' ) . '</span>',
					'next_text' => '<span class="sr-only">' . esc_html__( 'بعدی', 'negar-shop' ) . '</span> ' . $next_svg,
				)
			);
			echo $paginate_links; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
</div>
