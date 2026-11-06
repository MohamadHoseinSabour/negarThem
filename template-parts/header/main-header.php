<?php
/**
 * Main Header Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="negar-main-header py-4 bg-white border-b border-slate-100 hidden md:block">
	<div class="container flex items-center justify-between gap-6">

		<!-- Brand Logo (Right side in RTL) -->
		<div class="header-logo shrink-0">
			<?php
			if ( has_custom_logo() ) :
				the_custom_logo();
			else :
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo-text block">
					<span class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-1.5">
						<span class="w-3 h-3 rounded-full bg-emerald-600 inline-block"></span>
						<?php bloginfo( 'name' ); ?>
					</span>
				</a>
				<?php
			endif;
			?>
		</div>

		<!-- Live Ajax Search Bar (Center) -->
		<div class="header-search-wrap flex-1 max-w-2xl relative">
			<form role="search" method="get" class="negar-ajax-search-form flex items-center rounded-xl border-2 border-slate-200 bg-slate-50 focus-within:border-emerald-600 focus-within:bg-white transition shadow-xs" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				
				<!-- Category Dropdown Select (Optional refinement) -->
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="search-category-select hidden lg:block border-l border-slate-200 pl-2 pr-3 shrink-0">
						<?php
						$categories = get_terms(
							array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'parent'     => 0,
								'number'     => 10,
							)
						);
						?>
						<select name="product_cat" class="bg-transparent border-0 text-xs font-medium text-slate-600 cursor-pointer outline-none">
							<option value=""><?php esc_html_e( 'همه دسته‌ها', 'negar-shop' ); ?></option>
							<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
								<?php foreach ( $categories as $cat ) : ?>
									<option value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<input type="hidden" name="post_type" value="product">
				<?php endif; ?>

				<!-- Search Input -->
				<input
					type="search"
					name="s"
					class="negar-search-input w-full bg-transparent border-0 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 outline-none"
					placeholder="<?php esc_attr_e( 'جستجوی نام کالا، برند یا دسته‌بندی...', 'negar-shop' ); ?>"
					autocomplete="off"
					required
				>

				<!-- Clear / Loading Spinner -->
				<div class="search-indicator flex items-center px-3 text-slate-400">
					<div class="search-spinner hidden animate-spin w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full"></div>
				</div>

				<!-- Submit Button -->
				<button type="submit" class="search-submit bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-l-lg transition flex items-center justify-center" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</form>

			<!-- Live AJAX Results Dropdown Container -->
			<div class="negar-ajax-search-results hidden absolute top-full right-0 left-0 mt-2 bg-white rounded-xl shadow-card-hover border border-slate-100 overflow-hidden z-50"></div>
		</div>

		<!-- Action Buttons: Account, Wishlist, Mini-Cart (Left side in RTL) -->
		<div class="header-actions flex items-center gap-3 shrink-0">

			<!-- User Account -->
			<div class="header-action-item">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-action flex items-center gap-2 p-2 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-slate-50 transition" title="<?php esc_attr_e( 'حساب کاربری من', 'negar-shop' ); ?>">
						<div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
								<circle cx="12" cy="7" r="4"></circle>
							</svg>
						</div>
						<div class="account-meta text-right hidden xl:block">
							<span class="block text-2xs text-slate-400 leading-tight"><?php esc_html_e( 'خوش آمدید', 'negar-shop' ); ?></span>
							<span class="block text-xs font-bold text-slate-800 leading-tight"><?php echo esc_html( wp_get_current_user()->display_name ); ?></span>
						</div>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-action flex items-center gap-2 p-2 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-slate-50 transition">
						<div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
								<polyline points="10 17 15 12 10 7"></polyline>
								<line x1="15" y1="12" x2="3" y2="12"></line>
							</svg>
						</div>
						<div class="account-meta text-right hidden xl:block">
							<span class="block text-2xs text-slate-400 leading-tight"><?php esc_html_e( 'ورود به حساب', 'negar-shop' ); ?></span>
							<span class="block text-xs font-bold text-slate-800 leading-tight"><?php esc_html_e( 'ثبت‌نام کاربر', 'negar-shop' ); ?></span>
						</div>
					</a>
				<?php endif; ?>
			</div>

			<!-- Wishlist Button -->
			<div class="header-action-item">
				<a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" class="btn-icon relative" aria-label="<?php esc_attr_e( 'علاقه‌مندی‌ها', 'negar-shop' ); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
					</svg>
					<span class="negar-wishlist-count badge badge-warning absolute -top-1.5 -right-1.5 text-2xs px-1.5 py-0.5">0</span>
				</a>
			</div>

			<!-- Cart Button (Triggers Slide-out Mini-Cart Drawer) -->
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<div class="header-action-item">
					<button type="button" class="negar-cart-trigger flex items-center gap-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 px-4 py-2 rounded-xl transition border border-emerald-200" aria-label="<?php esc_attr_e( 'مشاهده سبد خرید', 'negar-shop' ); ?>">
						<div class="relative">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
								<line x1="3" y1="6" x2="21" y2="6"></line>
								<path d="M16 10a4 4 0 0 1-8 0"></path>
							</svg>
							<span class="negar-cart-count badge badge-discount absolute -top-2.5 -right-2.5 text-2xs px-1.5 py-0.5">
								<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
							</span>
						</div>
						<div class="cart-text text-right hidden lg:block">
							<span class="block text-2xs text-emerald-600 font-medium"><?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?></span>
							<span class="negar-cart-total-price block text-xs font-bold text-emerald-900">
								<?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
							</span>
						</div>
					</button>
				</div>
			<?php endif; ?>

		</div>

	</div>
</div>
