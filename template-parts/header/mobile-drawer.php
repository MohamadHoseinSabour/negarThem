<?php
/**
 * Mobile Navigation Drawer Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mobile_hotline      = trim( (string) negar_theme_setting( 'contact', 'phone', '', 'negar_shop_phone_hotline' ) );
$mobile_hotline_href = function_exists( 'negar_shop_phone_href' ) ? negar_shop_phone_href( $mobile_hotline ) : preg_replace( '/[^0-9+]/', '', $mobile_hotline );
?>
<div id="negar-mobile-drawer" class="negar-drawer offcanvas-drawer fixed top-0 bottom-0 right-0 w-4/5 max-w-sm bg-white shadow-2xl z-50 flex flex-col transition duration-300 transform translate-x-full" aria-hidden="true">
	<!-- Drawer Header -->
	<div class="drawer-header p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
		<span class="font-bold text-slate-800 text-sm flex items-center gap-2">
			<span class="w-2.5 h-2.5 rounded-full bg-rose-600 inline-block"></span>
			<?php echo esc_html( negar_theme_brand_label() ); ?>
		</span>
		<button type="button" class="drawer-close-btn w-8 h-8 rounded-full bg-white hover:bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-500" aria-label="<?php esc_attr_e( 'بستن منو', 'negar-shop' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
				<line x1="18" y1="6" x2="6" y2="18"></line>
				<line x1="6" y1="6" x2="18" y2="18"></line>
			</svg>
		</button>
	</div>

	<!-- Drawer Tab Switches -->
	<div class="mobile-drawer-tabs flex border-b border-slate-200 bg-slate-100/70 p-1">
		<button type="button" class="tab-btn active flex-1 py-2 text-xs font-bold text-center rounded-lg transition" data-target="#mobile-tab-menu">
			<?php esc_html_e( 'منوی سایت', 'negar-shop' ); ?>
		</button>
		<button type="button" class="tab-btn flex-1 py-2 text-xs font-bold text-center rounded-lg transition" data-target="#mobile-tab-categories">
			<?php esc_html_e( 'دسته‌بندی‌ها', 'negar-shop' ); ?>
		</button>
	</div>

	<!-- Drawer Body -->
	<div class="drawer-body flex-1 overflow-y-auto p-4">
		<!-- Tab 1: Menu Links -->
		<div id="mobile-tab-menu" class="mobile-tab-pane active">
			<?php
			if ( has_nav_menu( 'mobile' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'mobile',
						'container'      => false,
						'menu_class'     => 'mobile-nav-list list-none m-0 p-0 space-y-1',
					)
				);
			elseif ( has_nav_menu( 'primary' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mobile-nav-list list-none m-0 p-0 space-y-1',
					)
				);
			else :
				?>
				<ul class="mobile-nav-list list-none m-0 p-0 space-y-1 text-sm font-medium">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block py-2.5 px-3 rounded-lg text-slate-700 hover:bg-slate-100"><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></a></li>
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="block py-2.5 px-3 rounded-lg text-slate-700 hover:bg-slate-100"><?php esc_html_e( 'فروشگاه', 'negar-shop' ); ?></a></li>
					<?php endif; ?>
					<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="block py-2.5 px-3 rounded-lg text-slate-700 hover:bg-slate-100"><?php esc_html_e( 'وبلاگ', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="block py-2.5 px-3 rounded-lg text-slate-700 hover:bg-slate-100"><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="block py-2.5 px-3 rounded-lg text-slate-700 hover:bg-slate-100"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></a></li>
				</ul>
				<?php
			endif;
			?>
		</div>

		<!-- Tab 2: Product Categories Tree -->
		<div id="mobile-tab-categories" class="mobile-tab-pane hidden">
			<?php
			if ( class_exists( 'WooCommerce' ) ) :
				$mobile_cats = get_terms(
					array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
						'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
						'orderby'    => 'name',
						'order'      => 'ASC',
					)
				);
				if ( ! empty( $mobile_cats ) && ! is_wp_error( $mobile_cats ) ) :
					$mobile_categories_by_parent = array();
					foreach ( $mobile_cats as $mobile_cat ) {
						$parent_id = (int) $mobile_cat->parent;
						if ( ! isset( $mobile_categories_by_parent[ $parent_id ] ) ) {
							$mobile_categories_by_parent[ $parent_id ] = array();
						}
						$mobile_categories_by_parent[ $parent_id ][] = $mobile_cat;
					}

					$render_mobile_categories = null;
					$render_mobile_categories = static function ( $parent_id = 0, $depth = 0 ) use ( &$render_mobile_categories, $mobile_categories_by_parent ) {
						if ( empty( $mobile_categories_by_parent[ $parent_id ] ) ) {
							return;
						}

						$list_class = 0 === $depth ? 'mobile-cat-list list-none m-0 p-0 space-y-1 text-sm' : 'mobile-cat-list__children list-none m-0 space-y-1 text-sm';
						echo '<ul class="' . esc_attr( $list_class ) . '">';

						foreach ( $mobile_categories_by_parent[ $parent_id ] as $mobile_cat ) {
							$term_link = get_term_link( $mobile_cat );
							if ( is_wp_error( $term_link ) ) {
								continue;
							}

							echo '<li class="mobile-cat-list__item mobile-cat-list__item--depth-' . esc_attr( $depth ) . '">';
							echo '<a href="' . esc_url( $term_link ) . '" class="flex items-center justify-between py-2.5 px-3 rounded-lg text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition">';
							echo '<span>' . esc_html( $mobile_cat->name ) . '</span>';
							echo '<span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">' . esc_html( $mobile_cat->count ) . '</span>';
							echo '</a>';
							$render_mobile_categories( (int) $mobile_cat->term_id, $depth + 1 );
							echo '</li>';
						}

						echo '</ul>';
					};
					?>
					<?php $render_mobile_categories(); ?>
					<?php
				endif;
			endif;
			?>
		</div>
	</div>

	<!-- Drawer Footer: User & Support -->
	<div class="drawer-footer p-4 border-t border-slate-100 bg-slate-50">
		<a href="<?php echo esc_url( $mobile_hotline_href ? 'tel:' . $mobile_hotline_href : home_url( '/contact/' ) ); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg bg-rose-600 text-white font-bold text-xs<?php echo $mobile_hotline ? ' dir-ltr' : ''; ?>">
			<span><?php echo esc_html( $mobile_hotline ? $mobile_hotline : __( 'ارتباط با فروشگاه', 'negar-shop' ) ); ?></span>
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
			</svg>
		</a>
	</div>
</div>
