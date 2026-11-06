<?php
/**
 * Brands Logos Carousel Component (HOME-06)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$brands = array(
	array( 'name' => 'سامسونگ', 'sub' => 'Samsung' ),
	array( 'name' => 'اپل', 'sub' => 'Apple' ),
	array( 'name' => 'شیائومی', 'sub' => 'Xiaomi' ),
	array( 'name' => 'ایسوس', 'sub' => 'Asus' ),
	array( 'name' => 'سونی', 'sub' => 'Sony' ),
	array( 'name' => 'انکر', 'sub' => 'Anker' ),
	array( 'name' => 'جی‌بی‌ال', 'sub' => 'JBL' ),
	array( 'name' => 'هواوی', 'sub' => 'Huawei' ),
);
?>

<section class="negar-brands-section py-6" aria-label="<?php esc_attr_e( 'برندهای برتر', 'negar-shop' ); ?>">
	<div class="container">
		
		<div class="section-heading flex items-center justify-between mb-5">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
				<h2 class="text-base md:text-lg font-bold text-slate-800 m-0">
					<?php esc_html_e( 'محبوب‌ترین برندهای بازار', 'negar-shop' ); ?>
				</h2>
			</div>
		</div>

		<div class="brands-grid grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
			<?php foreach ( $brands as $brand ) : ?>
				<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="brand-item-card group flex flex-col items-center justify-center p-4 rounded-2xl bg-white border border-slate-100 hover:border-emerald-200 hover:shadow-card-hover transition duration-300">
					<div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center mb-2 text-slate-400 group-hover:text-emerald-600 group-hover:scale-110 transition duration-300">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
						</svg>
					</div>
					<span class="brand-name text-xs font-bold text-slate-800 group-hover:text-emerald-600 transition block">
						<?php echo esc_html( $brand['name'] ); ?>
					</span>
					<span class="brand-sub text-3xs text-slate-400 block dir-ltr">
						<?php echo esc_html( $brand['sub'] ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
