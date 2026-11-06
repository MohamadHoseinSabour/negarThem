<?php
/**
 * Navigation Bar and Mega Menu Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="negar-navigation-bar bg-white border-b border-slate-200 hidden md:block">
	<div class="container flex items-center justify-between">

		<div class="flex items-center gap-6">
			<!-- Category Dropdown Trigger (Mega Menu Button) -->
			<div class="category-menu-wrapper relative">
				<button type="button" class="category-menu-btn flex items-center gap-2.5 bg-emerald-600 text-white px-5 py-3.5 rounded-t-xl font-bold text-sm transition hover:bg-emerald-700">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<line x1="3" y1="12" x2="21" y2="12"></line>
						<line x1="3" y1="6" x2="21" y2="6"></line>
						<line x1="3" y1="18" x2="21" y2="18"></line>
					</svg>
					<span><?php esc_html_e( 'دسته‌بندی کالاها', 'negar-shop' ); ?></span>
					<svg class="chevron-down mr-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<polyline points="6 9 12 15 18 9"></polyline>
					</svg>
				</button>

				<!-- Categories Dropdown Tree -->
				<div class="category-dropdown-panel hidden absolute top-full right-0 w-64 bg-white rounded-b-xl shadow-card-hover border border-slate-100 z-50 py-2">
					<?php
					if ( has_nav_menu( 'categories' ) ) :
						wp_nav_menu(
							array(
								'theme_location' => 'categories',
								'container'      => false,
								'menu_class'     => 'category-nav-list list-none m-0 p-0',
								'walker'         => class_exists( 'Negar_Shop_Mega_Menu_Walker' ) ? new Negar_Shop_Mega_Menu_Walker() : '',
							)
						);
					elseif ( class_exists( 'WooCommerce' ) ) :
						$prod_cats = get_terms(
							array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'parent'     => 0,
								'number'     => 8,
							)
						);
						?>
						<ul class="category-nav-list list-none m-0 p-0 text-sm">
							<?php if ( ! empty( $prod_cats ) && ! is_wp_error( $prod_cats ) ) : ?>
								<?php foreach ( $prod_cats as $cat ) : ?>
									<li class="cat-item">
										<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="flex items-center justify-between px-4 py-2.5 text-slate-700 hover:text-emerald-600 hover:bg-slate-50 transition">
											<span><?php echo esc_html( $cat->name ); ?></span>
											<span class="text-xs text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full"><?php echo esc_html( $cat->count ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							<?php else : ?>
								<li class="px-4 py-2 text-xs text-slate-400"><?php esc_html_e( 'دسته‌ای تعریف نشده است', 'negar-shop' ); ?></li>
							<?php endif; ?>
						</ul>
						<?php
					endif;
					?>
				</div>
			</div>

			<!-- Primary Navigation Menu -->
			<nav class="primary-navigation" aria-label="<?php esc_attr_e( 'ناوبری اصلی فروشگاه', 'negar-shop' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) :
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'primary-menu-list flex items-center gap-6 list-none m-0 p-0 text-sm font-semibold text-slate-700',
							'walker'         => class_exists( 'Negar_Shop_Mega_Menu_Walker' ) ? new Negar_Shop_Mega_Menu_Walker() : '',
						)
					);
				else :
					?>
					<ul class="primary-menu-list flex items-center gap-6 list-none m-0 p-0 text-sm font-semibold text-slate-700">
						<li>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-link hover:text-emerald-600 transition flex items-center gap-1.5">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
									<polyline points="9 22 9 12 15 12 15 22"></polyline>
								</svg>
								<span><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></span>
							</a>
						</li>
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<li>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="nav-link hover:text-emerald-600 transition flex items-center gap-1.5">
									<span><?php esc_html_e( 'فروشگاه', 'negar-shop' ); ?></span>
								</a>
							</li>
						<?php endif; ?>
						<li>
							<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="nav-link hover:text-emerald-600 transition">
								<span><?php esc_html_e( 'وبلاگ و مقالات', 'negar-shop' ); ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="nav-link hover:text-emerald-600 transition">
								<span><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="nav-link hover:text-emerald-600 transition">
								<span><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></span>
							</a>
						</li>
					</ul>
					<?php
				endif;
				?>
			</nav>
		</div>

		<!-- Special Deals Highlight CTA -->
		<div class="nav-deals-cta hidden lg:block">
			<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) . '?on_sale=1' : home_url( '/' ) ); ?>" class="flex items-center gap-2 text-rose-600 hover:text-rose-700 font-bold text-sm bg-rose-50 hover:bg-rose-100 py-1.5 px-3.5 rounded-full transition border border-rose-200">
				<svg class="animate-bounce" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 2c1.1 0 2 .9 2 2 0 .7-.4 1.4-1 1.7V7c2.8 0 5 2.2 5 5 0 2.2-1.4 4.1-3.4 4.7.3.7.4 1.5.4 2.3 0 2.8-2.2 5-5 5s-5-2.2-5-5c0-.8.1-1.6.4-2.3C3.4 16.1 2 14.2 2 12c0-2.8 2.2-5 5-5V5.7c-.6-.3-1-1-1-1.7 0-1.1.9-2 2-2h4z"/>
				</svg>
				<span><?php esc_html_e( 'پیشنهادات شگفت‌انگیز', 'negar-shop' ); ?></span>
				<span class="badge bg-rose-600 text-white text-2xs px-1.5 py-0.5 rounded-full font-bold">٪ تخفیف</span>
			</a>
		</div>

	</div>
</div>
