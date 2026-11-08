<?php
/**
 * Main Header Component - 100% Matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme_logo_url = get_template_directory_uri() . '/assets/images/logo.svg';
$upload_dir     = wp_upload_dir();
$logo_svg       = $upload_dir['basedir'] . '/kaveh-demo/kaveh-demo/general/logo.svg';
$logo_url       = file_exists( $logo_svg ) ? $upload_dir['baseurl'] . '/kaveh-demo/kaveh-demo/general/logo.svg' : $theme_logo_url;
$has_logo       = true;

$product_cats = function_exists( 'get_terms' ) ? get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'parent'     => 0,
	'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
) ) : array();
?>

<div class="negar-main-header py-3.5 bg-white border-b border-slate-100">
	<div class="container flex items-center justify-between gap-4 lg:gap-8">

		<!-- 1. Right: Logo & Branding -->
		<div class="header-logo shrink-0 flex items-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="135" height="40" class="h-9 w-auto object-contain kavehlogost d-block" style="width: 135px !important; height: auto !important; max-height: 40px !important;">
			</a>
		</div>

		<!-- 2. Center: Search Bar with Nested Red Category Button -->
		<div class="header-search header-search-wrap flex-1 max-w-xl lg:max-w-2xl relative hidden md:block">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="negar-ajax-search-form relative flex items-center bg-slate-100/90 hover:bg-slate-100 focus-within:bg-white rounded-full border border-slate-200/70 focus-within:border-rose-500 focus-within:ring-2 focus-within:ring-rose-500/10 transition-all p-1">
				
				<!-- Nested Category Pill Button & Dropdown (Right side inside input in RTL) -->
				<div class="relative shrink-0 category-select-wrapper">
					<button type="button" class="category-menu-btn flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3.5 py-2 rounded-full transition-all shadow-xs" id="negar-category-menu-btn" aria-haspopup="true" aria-expanded="false" aria-controls="negar-category-dropdown">
						<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
							<path d="M4 4h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 10h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4zM4 16h4v4H4zm6 0h4v4h-4zm6 0h4v4h-4z"/>
						</svg>
						<span class="category-btn-label"><?php esc_html_e( 'دسته‌بندی', 'negar-shop' ); ?></span>
						<svg class="category-chevron transition-transform duration-200" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
					</button>

					<input type="hidden" name="product_cat" id="negar-search-cat-input" value="">

					<!-- Category Dropdown Menu -->
					<div id="negar-category-dropdown" class="negar-category-dropdown hidden absolute top-full right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden text-right" role="menu" aria-labelledby="negar-category-menu-btn">
						<div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 border-b border-slate-100">
							<?php esc_html_e( 'انتخاب دسته‌بندی جستجو', 'negar-shop' ); ?>
						</div>
						<div class="max-h-60 overflow-y-auto py-1" role="none">
							<button type="button" class="category-option w-full text-right px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition flex items-center justify-between" data-cat-slug="" data-cat-name="<?php esc_attr_e( 'دسته‌بندی', 'negar-shop' ); ?>" role="menuitem">
								<span><?php esc_html_e( 'همه دسته‌بندی‌ها', 'negar-shop' ); ?></span>
								<span class="cat-check text-rose-600 font-bold">&#10003;</span>
							</button>
							<?php if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) : ?>
								<?php foreach ( $product_cats as $cat ) : ?>
									<button type="button" class="category-option w-full text-right px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-600 transition flex items-center justify-between" data-cat-slug="<?php echo esc_attr( $cat->slug ); ?>" data-cat-name="<?php echo esc_attr( $cat->name ); ?>" role="menuitem">
										<span><?php echo esc_html( $cat->name ); ?></span>
										<span class="cat-check text-rose-600 font-bold hidden">&#10003;</span>
									</button>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Search Input -->
				<input
					type="search"
					name="s"
					class="negar-search-input flex-1 bg-transparent border-0 px-4 py-1.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 outline-none"
					placeholder="<?php esc_attr_e( 'جستجوی محصول...', 'negar-shop' ); ?>"
					autocomplete="off"
					required
				>
				<input type="hidden" name="post_type" value="product">

				<!-- Search Spinner Indicator -->
				<span class="search-spinner hidden p-1.5 text-rose-500 animate-spin flex items-center justify-center shrink-0">
					<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
				</span>

				<!-- Submit / Search Icon Button (Left side inside input in RTL) -->
				<button type="submit" class="search-submit p-2 text-slate-400 hover:text-rose-600 transition flex items-center justify-center shrink-0" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</button>
			</form>

			<!-- Live AJAX Results Dropdown -->
			<div class="negar-ajax-search-results hidden absolute top-full right-0 left-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50"></div>
		</div>

		<!-- 3. Left: User Account & Cart (Matching Demo Screenshot 1:1) -->
		<div class="header-actions flex items-center gap-3 shrink-0">

			<!-- Wishlist Button -->
			<div class="header-action-item hidden md:block">
				<a href="<?php echo esc_url( home_url( '/wishlist' ) ); ?>" class="negar-wishlist-trigger relative w-9 h-9 rounded-full bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-600 border border-slate-200 flex items-center justify-center transition shadow-2xs" aria-label="<?php esc_attr_e( 'لیست علاقه‌مندی‌ها', 'negar-shop' ); ?>">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
					</svg>
					<span class="negar-wishlist-count absolute -top-1 -right-1 bg-rose-600 text-white rounded-full text-[10px] w-4.5 h-4.5 flex items-center justify-center font-black leading-none">0</span>
				</a>
			</div>

			<!-- User Account Pill Button -->
			<div class="header-action-item">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="flex items-center gap-2 py-1.5 px-3.5 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition border border-slate-200 text-xs font-bold shadow-2xs">
						<span><?php echo esc_html( wp_get_current_user()->display_name ); ?></span>
						<div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 overflow-hidden shrink-0">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
						</div>
					</a>
				<?php else : ?>
					<button type="button" class="negar-login-trigger flex items-center gap-2 py-1.5 px-3.5 rounded-full bg-slate-50 hover:bg-slate-100 text-slate-700 transition border border-slate-200 text-xs font-bold shadow-2xs cursor-pointer" aria-label="<?php esc_attr_e( 'ورود به حساب کاربری', 'negar-shop' ); ?>">
						<span><?php esc_html_e( 'ورود / ثبت‌نام', 'negar-shop' ); ?></span>
						<div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 overflow-hidden shrink-0">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
						</div>
					</button>
				<?php endif; ?>
			</div>

			<!-- Cart Button with Red Circle + Label (Matching Demo) -->
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<div class="header-action-item">
					<button type="button" class="negar-cart-trigger flex items-center gap-2 group cursor-pointer" aria-label="<?php esc_attr_e( 'سبد خرید', 'negar-shop' ); ?>">
						
						<!-- Red Circle with Bag Icon -->
						<div class="relative w-9 h-9 rounded-full bg-rose-600 group-hover:bg-rose-700 text-white flex items-center justify-center shadow-md shadow-rose-600/30 transition-transform group-hover:scale-105 shrink-0">
							<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
								<line x1="3" y1="6" x2="21" y2="6"></line>
								<path d="M16 10a4 4 0 0 1-8 0"></path>
							</svg>
							
							<!-- Badge Count -->
							<span class="negar-cart-count-header negar-cart-count absolute -top-1 -right-1 bg-white text-rose-600 border border-rose-600 rounded-full text-[10px] w-4.5 h-4.5 flex items-center justify-center font-black leading-none">
								<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
							</span>
						</div>

						<span class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition hidden sm:inline-block">
							<?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?>
						</span>

					</button>
				</div>
			<?php endif; ?>

		</div>

	</div>
</div>
