<?php
/**
 * Quick Category Bubbles Component (HOME-02)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = array();
if ( class_exists( 'WooCommerce' ) ) {
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
			'number'     => 8,
		)
	);
}
?>

<section class="negar-category-bubbles-section py-6" aria-label="<?php esc_attr_e( 'دسته‌بندی‌های برتر', 'negar-shop' ); ?>">
	<div class="container">
		
		<!-- Section Header -->
		<div class="section-heading flex items-center justify-between mb-5">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-6 rounded-full bg-emerald-600 inline-block"></span>
				<h2 class="text-base md:text-lg font-bold text-slate-800 m-0">
					<?php esc_html_e( 'دسته‌بندی‌های محبوب فروشگاه', 'negar-shop' ); ?>
				</h2>
			</div>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
					<span><?php esc_html_e( 'همه دسته‌ها', 'negar-shop' ); ?></span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
						<polyline points="15 18 9 12 15 6"></polyline>
					</svg>
				</a>
			<?php endif; ?>
		</div>

		<!-- Bubbles Grid -->
		<div class="category-bubbles-grid grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 md:gap-4">
			<?php
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
				foreach ( $categories as $cat ) :
					$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image_url    = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : '';
					?>
					<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="category-bubble-card group flex flex-col items-center text-center p-3 rounded-2xl bg-white border border-slate-100 hover:border-emerald-200 hover:shadow-card-hover transition duration-300">
						<div class="bubble-icon-wrap w-16 h-16 md:w-20 md:h-20 rounded-full bg-slate-50 border-2 border-emerald-100 flex items-center justify-center p-2 mb-2 group-hover:scale-105 group-hover:border-emerald-500 transition duration-300 overflow-hidden">
							<?php if ( $image_url ) : ?>
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" class="w-full h-full object-contain">
							<?php else : ?>
								<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.8">
									<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
									<line x1="8" y1="21" x2="16" y2="21"></line>
									<line x1="12" y1="17" x2="12" y2="21"></line>
								</svg>
							<?php endif; ?>
						</div>
						<h3 class="category-name text-xs font-bold text-slate-800 group-hover:text-emerald-600 transition line-clamp-1 m-0">
							<?php echo esc_html( $cat->name ); ?>
						</h3>
						<span class="category-count text-3xs text-slate-400 mt-1">
							<?php echo esc_html( sprintf( _n( '%s کالا', '%s کالا', $cat->count, 'negar-shop' ), number_format_i18n( $cat->count ) ) ); ?>
						</span>
					</a>
					<?php
				endforeach;
			else :
				// Fallback Sample Categories
				$fallback_categories = array(
					array( 'title' => 'موبایل و تبلت', 'count' => '۱۲۸', 'icon' => 'smartphone' ),
					array( 'title' => 'لپ‌تاپ و کامپیوتر', 'count' => '۸۴', 'icon' => 'laptop' ),
					array( 'title' => 'هدفون و هندزفری', 'count' => '۲۱۵', 'icon' => 'headphones' ),
					array( 'title' => 'ساعت هوشمند', 'count' => '۹۶', 'icon' => 'watch' ),
					array( 'title' => 'لوازم جانبی', 'count' => '۳۴۰', 'icon' => 'hard-drive' ),
					array( 'title' => 'لوازم خانگی', 'count' => '۱۱۲', 'icon' => 'home' ),
					array( 'title' => 'مد و پوشاک', 'count' => '۱۹۰', 'icon' => 'shopping-bag' ),
					array( 'title' => 'زیبایی و سلامت', 'count' => '۱۴۵', 'icon' => 'heart' ),
				);
				foreach ( $fallback_categories as $fcat ) :
					?>
					<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>" class="category-bubble-card group flex flex-col items-center text-center p-3 rounded-2xl bg-white border border-slate-100 hover:border-emerald-200 hover:shadow-card-hover transition duration-300">
						<div class="bubble-icon-wrap w-16 h-16 md:w-20 md:h-20 rounded-full bg-slate-50 border-2 border-emerald-100 flex items-center justify-center p-2 mb-2 group-hover:scale-105 group-hover:border-emerald-500 transition duration-300">
							<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.8">
								<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
								<circle cx="8.5" cy="8.5" r="1.5"></circle>
								<polyline points="21 15 16 10 5 21"></polyline>
							</svg>
						</div>
						<h3 class="category-name text-xs font-bold text-slate-800 group-hover:text-emerald-600 transition line-clamp-1 m-0">
							<?php echo esc_html( $fcat['title'] ); ?>
						</h3>
						<span class="category-count text-3xs text-slate-400 mt-1">
							<?php echo esc_html( $fcat['count'] . ' کالا' ); ?>
						</span>
					</a>
					<?php
				endforeach;
			endif;
			?>
		</div>

	</div>
</section>
