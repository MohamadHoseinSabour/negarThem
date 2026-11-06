<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$popular_cats = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'number'     => 5,
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);
?>

<div class="negar-404-page py-16 text-center">
	<div class="container mx-auto px-4 max-w-xl">

		<!-- Large 404 Number -->
		<div class="text-7xl sm:text-9xl font-black text-primary/20 tracking-widest font-mono mb-2 select-none">
			404
		</div>

		<h1 class="text-xl sm:text-3xl font-black text-dark mb-3">
			<?php esc_html_e( 'متأسفانه صفحه مورد نظر یافت نشد!', 'negar-shop' ); ?>
		</h1>

		<p class="text-xs sm:text-sm text-muted leading-relaxed mb-8">
			<?php esc_html_e( 'صفحه‌ای که به دنبال آن بودید ممکن است حذف شده باشد، نام آن تغییر یافته باشد یا موقتاً در دسترس نباشد.', 'negar-shop' ); ?>
		</p>

		<!-- Search Recovery Form -->
		<div class="max-w-md mx-auto mb-8">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative flex items-center">
				<input type="search" name="s" placeholder="<?php esc_attr_e( 'جستجوی محصول یا مقاله مورد نظر...', 'negar-shop' ); ?>" class="w-full p-3.5 pl-12 rounded-2xl border border-border bg-slate-50 focus:bg-white focus:border-primary shadow-xs transition-all text-xs sm:text-sm">
				<button type="submit" class="absolute left-3 p-1.5 rounded-xl bg-primary text-white hover:bg-primary-dark transition-colors focus:outline-none" aria-label="<?php esc_attr_e( 'جستجو', 'negar-shop' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
				</button>
			</form>
		</div>

		<!-- Action Buttons -->
		<div class="flex flex-wrap items-center justify-center gap-3 mb-10">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary py-2.5 px-6 text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all inline-flex items-center gap-2">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
				<span><?php esc_html_e( 'بازگشت به صفحه اصلی', 'negar-shop' ); ?></span>
			</a>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline py-2.5 px-6 text-xs sm:text-sm font-bold transition-all">
					<?php esc_html_e( 'مشاهده محصولات فروشگاه', 'negar-shop' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<!-- Popular Categories Shortcuts -->
		<?php if ( ! empty( $popular_cats ) && ! is_wp_error( $popular_cats ) ) : ?>
			<div class="popular-cats-shortcuts pt-8 border-t border-border">
				<span class="text-xs text-muted block mb-3"><?php esc_html_e( 'یا از دسته‌بندی‌های پرطرفدار زیر دیدن فرمایید:', 'negar-shop' ); ?></span>
				<div class="flex flex-wrap items-center justify-center gap-2">
					<?php foreach ( $popular_cats as $cat ) : ?>
						<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="text-xs px-3 py-1.5 rounded-full bg-slate-100 hover:bg-primary hover:text-white transition-colors text-dark font-medium">
							<?php echo esc_html( $cat->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
