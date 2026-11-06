<?php
/**
 * Quick Category Bubbles Component (HOME-02) - Styled matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$cat_base   = $upload_dir['baseurl'] . '/kaveh-demo/categories';

$demo_cats = array(
	array( 'name' => 'گوشی موبایل', 'img' => $cat_base . '/cat-mobile.png' ),
	array( 'name' => 'کنسول بازی', 'img' => $cat_base . '/cat-console.png' ),
	array( 'name' => 'ساعت هوشمند', 'img' => $cat_base . '/cat-watch.png' ),
	array( 'name' => 'لوازم جانبی', 'img' => $cat_base . '/cat-accessories.png' ),
	array( 'name' => 'ساعت و مچ‌بند', 'img' => $cat_base . '/cat-icon-watch.png' ),
	array( 'name' => 'لپ‌تاپ و تبلت', 'img' => $cat_base . '/cat-mobile.png' ),
	array( 'name' => 'هدفون و اسپیکر', 'img' => $cat_base . '/cat-accessories.png' ),
	array( 'name' => 'لوازم خانگی', 'img' => $cat_base . '/cat-console.png' ),
);
?>

<section class="negar-category-bubbles-section py-8 bg-white border-b border-slate-100" aria-label="<?php esc_attr_e( 'دسته‌بندی‌های برتر', 'negar-shop' ); ?>">
	<div class="container">
		
		<!-- Section Header -->
		<div class="section-heading flex items-center justify-between mb-6">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-6 rounded-full bg-rose-600 inline-block"></span>
				<h2 class="text-base md:text-lg font-black text-slate-800 m-0">
					<?php esc_html_e( 'دسته‌بندی‌های محبوب فروشگاه', 'negar-shop' ); ?>
				</h2>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- Bubbles Grid -->
		<div class="category-bubbles-grid grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
			<?php foreach ( $demo_cats as $dcat ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="category-bubble-card group flex flex-col items-center text-center p-3 rounded-2xl bg-slate-50/60 hover:bg-white border border-slate-100 hover:border-rose-200 hover:shadow-lg transition duration-300">
					<div class="bubble-icon-wrap w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-white border border-slate-200/60 flex items-center justify-center p-2 mb-2 group-hover:scale-105 group-hover:border-rose-400 transition duration-300 overflow-hidden shadow-xs">
						<img src="<?php echo esc_url( $dcat['img'] ); ?>" alt="<?php echo esc_attr( $dcat['name'] ); ?>" class="w-full h-full object-contain">
					</div>
					<span class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition-colors line-clamp-1">
						<?php echo esc_html( $dcat['name'] ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
