<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="negar-shop-page py-6">
	<div class="container mx-auto px-4">

		<!-- Breadcrumbs -->
		<?php negar_shop_breadcrumbs(); ?>

		<!-- Archive Header Title -->
		<header class="woocommerce-products-header mb-6">
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
				<h1 class="woocommerce-products-header__title page-title text-xl sm:text-2xl font-black text-dark mb-2">
					<?php woocommerce_page_title(); ?>
				</h1>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_archive_description.
			 */
			do_action( 'woocommerce_archive_description' );
			?>
		</header>

		<!-- Main Shop Layout -->
		<div class="negar-shop-layout grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

			<!-- Desktop Sidebar (Right side in RTL) -->
			<aside class="negar-shop-sidebar hidden lg:block lg:col-span-3 sticky top-24" aria-label="<?php esc_attr_e( 'فیلترهای فروشگاه', 'negar-shop' ); ?>">
				<?php get_template_part( 'template-parts/shop/sidebar-filters' ); ?>
			</aside>

			<!-- Mobile Filter Off-Canvas Drawer -->
			<div class="negar-filters-backdrop fixed inset-0 bg-dark/50 z-50 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300" aria-hidden="true"></div>
			<div class="negar-filters-drawer fixed top-0 bottom-0 right-0 w-[300px] max-w-[85vw] bg-surface z-50 lg:hidden flex flex-col shadow-2xl transition-transform duration-300 translate-x-full overflow-hidden" id="negar-shop-filters-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'فیلتر محصولات', 'negar-shop' ); ?>">
				<!-- Drawer Header -->
				<div class="negar-drawer-header flex items-center justify-between p-4 border-b border-border bg-slate-50">
					<span class="text-sm font-bold text-dark flex items-center gap-2">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
							<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
						</svg>
						<?php esc_html_e( 'فیلتر محصولات', 'negar-shop' ); ?>
					</span>
					<button type="button" class="negar-close-filters-drawer p-1 text-muted hover:text-dark focus:outline-none" aria-label="<?php esc_attr_e( 'بستن منوی فیلترها', 'negar-shop' ); ?>">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
					</button>
				</div>
				<!-- Drawer Body -->
				<div class="negar-drawer-body p-4 flex-1 overflow-y-auto">
					<?php get_template_part( 'template-parts/shop/sidebar-filters' ); ?>
				</div>
			</div>

			<!-- Main Catalog Content -->
			<div class="negar-shop-content lg:col-span-9">
				<!-- Toolbar (Count, Sort, Grid/List switch, Mobile button) -->
				<?php get_template_part( 'template-parts/shop/toolbar' ); ?>

				<!-- Products Loop & Pagination -->
				<?php get_template_part( 'template-parts/shop/loop' ); ?>
			</div>

		</div>

	</div>
</div>

<?php
get_footer( 'shop' );
