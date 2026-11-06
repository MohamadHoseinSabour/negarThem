<?php
/**
 * Footer Copyright, Socials and Back to Top
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="footer-copyright-bar py-5 bg-black text-slate-400 text-xs border-t border-slate-900">
	<div class="container flex flex-col md:flex-row items-center justify-between gap-4">

		<!-- Copyright Text -->
		<div class="copyright-text text-center md:text-right">
			<p class="m-0">
				<?php
				/* translators: 1: Current Year, 2: Site Name */
				printf(
					esc_html__( 'تمامی حقوق مادی و معنوی این وب‌سایت متعلق به %2$s می‌باشد. © %1$s', 'negar-shop' ),
					esc_html( date_i18n( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
		</div>

		<!-- Social Icons & Back-to-Top -->
		<div class="flex items-center gap-6">
			<!-- Social Networks -->
			<div class="footer-socials flex items-center gap-3">
				<!-- Instagram -->
				<a href="#" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-emerald-600 text-slate-400 hover:text-white flex items-center justify-center transition" aria-label="Instagram">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
						<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
						<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
					</svg>
				</a>
				<!-- Telegram -->
				<a href="#" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-emerald-600 text-slate-400 hover:text-white flex items-center justify-center transition" aria-label="Telegram">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<line x1="22" y1="2" x2="11" y2="13"></line>
						<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
					</svg>
				</a>
				<!-- WhatsApp -->
				<a href="#" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-emerald-600 text-slate-400 hover:text-white flex items-center justify-center transition" aria-label="WhatsApp">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
					</svg>
				</a>
			</div>

			<!-- Back to Top Button -->
			<button type="button" id="negar-back-to-top" class="flex items-center gap-1.5 text-slate-400 hover:text-emerald-400 font-medium transition cursor-pointer" aria-label="<?php esc_attr_e( 'بازگشت به بالای صفحه', 'negar-shop' ); ?>">
				<span><?php esc_html_e( 'بازگشت به بالا', 'negar-shop' ); ?></span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<polyline points="18 15 12 9 6 15"></polyline>
				</svg>
			</button>
		</div>

	</div>
</div>
