<?php
/**
 * Navigation Sub-bar - Styled matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="negar-navigation-bar bg-white border-b border-slate-100 hidden md:block py-2.5">
	<div class="container flex items-center justify-between">

		<!-- Right Side: Main Nav Links & Flash Sale Link (Matching Demo Screenshot) -->
		<div class="flex items-center gap-6">
			
			<nav class="main-nav-links" aria-label="<?php esc_attr_e( 'منوی ناوبری اصلی', 'negar-shop' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) :
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'flex items-center gap-6 list-none m-0 p-0 text-xs sm:text-sm font-bold text-slate-700',
							'fallback_cb'    => false,
						)
					);
				else :
					?>
					<ul class="flex items-center gap-5 lg:gap-6 list-none m-0 p-0 text-xs sm:text-sm font-bold text-slate-700">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition"><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></a></li>
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hover:text-rose-600 transition"><?php esc_html_e( 'فروشگاه', 'negar-shop' ); ?></a></li>
						<?php endif; ?>
						<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="hover:text-rose-600 transition"><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="hover:text-rose-600 transition"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="hover:text-rose-600 transition"><?php esc_html_e( 'وبلاگ', 'negar-shop' ); ?></a></li>
					</ul>
					<?php
				endif;
				?>
			</nav>

			<div class="h-4 w-px bg-slate-200"></div>

			<!-- Flash Sale Flame Link (Matching Demo) -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>?on_sale=1" class="flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700 transition">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" class="text-rose-600">
					<path d="M12 2c1.5 3 4 5.5 4 8.5 0 3.5-2.5 5.5-4 5.5s-4-2-4-5.5C8 7.5 10.5 5 12 2zm0 18c-4.4 0-8-3.6-8-8 0-2.8 1.5-5.5 3.5-7.5.5 2 1.5 3.8 3 5 .3-1.5 1-3 2.5-4 1.5 2 3 3.5 3 6.5 0 4.4-3.6 8-8 8z"/>
				</svg>
				<span><?php esc_html_e( 'فروش ویژه', 'negar-shop' ); ?></span>
			</a>

		</div>

		<!-- Left Side: Special Promo Card Callout (Matching Demo Screenshot) -->
		<div class="flex items-center gap-3">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="flex items-center gap-2.5 group">
				<div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-lg shadow-xs">
					📦
				</div>
				<div class="flex flex-col text-right">
					<span class="text-xs font-black text-rose-600 leading-tight group-hover:underline"><?php esc_html_e( 'تخفیفات ویژه', 'negar-shop' ); ?></span>
					<span class="text-3xs text-slate-400 font-medium leading-tight"><?php esc_html_e( 'برای فصل تابستان', 'negar-shop' ); ?></span>
				</div>
			</a>
		</div>

	</div>
</div>
