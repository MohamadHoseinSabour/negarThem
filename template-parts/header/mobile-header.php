<?php
/**
 * Mobile Header Template Part
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="negar-mobile-header md:hidden bg-white border-b border-slate-200 sticky top-0 z-30">
	<!-- Top Bar in Mobile -->
	<div class="px-4 py-3 flex items-center justify-between gap-3">
		<!-- Hamburger Menu Trigger -->
		<button type="button" class="mobile-menu-trigger w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-700" aria-label="<?php esc_attr_e( 'منوی ناوبری موبایل', 'negar-shop' ); ?>">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
				<line x1="4" y1="7" x2="20" y2="7"></line>
				<line x1="4" y1="12" x2="20" y2="12"></line>
				<line x1="4" y1="17" x2="14" y2="17"></line>
			</svg>
		</button>

		<!-- Brand Logo -->
		<div class="mobile-logo flex-1 text-center">
			<?php
			if ( has_custom_logo() ) :
				the_custom_logo();
			else :
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="inline-block text-xl font-black text-slate-900 tracking-tight">
					<?php bloginfo( 'name' ); ?>
				</a>
				<?php
			endif;
			?>
		</div>

		<!-- Cart Trigger Icon -->
		<button type="button" class="negar-cart-trigger w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-700 relative" aria-label="<?php esc_attr_e( 'سبد خرید', 'negar-shop' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
			<span class="negar-cart-count badge badge-discount absolute -top-1.5 -right-1.5 text-2xs px-1.5 py-0.5">
				<?php echo class_exists( 'WooCommerce' ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
			</span>
		</button>
	</div>

	<!-- Mobile Search Row -->
	<div class="px-4 pb-3">
		<form role="search" method="get" class="negar-ajax-search-form flex items-center rounded-xl border border-slate-200 bg-slate-50 focus-within:border-rose-600 focus-within:bg-white transition" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input
				type="search"
				name="s"
				class="negar-search-input w-full bg-transparent border-0 px-3.5 py-2 text-xs text-slate-800 placeholder-slate-400 outline-none"
				placeholder="<?php esc_attr_e( 'جستجوی نام کالا یا برند...', 'negar-shop' ); ?>"
				autocomplete="off"
				required
			>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button type="submit" class="p-2 text-slate-400 hover:text-rose-600" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
			</button>
		</form>
	</div>
</div>
