<?php
/**
 * Empty Cart Page Template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>

<div class="negar-cart-empty py-16 text-center">
	<div class="container mx-auto px-4 max-w-lg">
		
		<div class="w-24 h-24 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center text-muted">
			<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
				<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
			</svg>
		</div>

		<h2 class="text-xl sm:text-2xl font-black text-dark mb-3">
			<?php esc_html_e( 'سبد خرید شما در حال حاضر خالی است!', 'negar-shop' ); ?>
		</h2>

		<p class="text-sm text-muted leading-relaxed mb-8">
			<?php esc_html_e( 'می‌توانید برای مشاهده محصولات جدید، دسته‌بندی‌ها و تخفیف‌های شگفت‌انگیز به صفحه فروشگاه سر بزنید.', 'negar-shop' ); ?>
		</p>

		<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<a class="button wc-backward btn btn-primary py-3 px-8 text-sm font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
				<span><?php esc_html_e( 'شروع خرید از فروشگاه', 'negar-shop' ); ?></span>
			</a>
		<?php endif; ?>

	</div>
</div>
