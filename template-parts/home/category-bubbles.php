<?php
/**
 * Category Bubbles Component (HOME-03) - Exact Match to Kaveh Demo 43
 * Reference: Demo 43 Section 2 (Top: 1014px, Height: 360px)
 * Heading: "پرفروش ترین دسته بندی - ضمانت قیمت و کیفیت"
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$demo_base  = $upload_dir['baseurl'] . '/kaveh-demo';

$kaveh_categories = array(
	array(
		'name' => 'ساعت هوشمند',
		'img'  => $demo_base . '/category-01.png',
		'link' => wc_get_page_permalink( 'shop' ),
	),
	array(
		'name' => 'کنسول بازی',
		'img'  => $demo_base . '/category-02.png',
		'link' => wc_get_page_permalink( 'shop' ),
	),
	array(
		'name' => 'گوشی موبایل',
		'img'  => $demo_base . '/category-03.png',
		'link' => wc_get_page_permalink( 'shop' ),
	),
	array(
		'name' => 'ساعت هوشمند',
		'img'  => $demo_base . '/category-01.png',
		'link' => wc_get_page_permalink( 'shop' ),
	),
	array(
		'name' => 'لوازم جانبی موبایل',
		'img'  => $demo_base . '/category-02.png',
		'link' => wc_get_page_permalink( 'shop' ),
	),
);
?>

<section class="negar-categories-nk-section py-8" aria-label="<?php esc_attr_e( 'پرفروش‌ترین دسته‌بندی‌ها', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header & Divider -->
		<div class="section-heading-one-nk">
			<div class="title">
				<span class="highlight">پرفروش</span> ترین دسته بندی
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'ضمانت قیمت و کیفیت', 'negar-shop' ); ?>
			</div>
			<div class="line">
				<span></span>
				<span></span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده بیشتر', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- Category Cards Grid (Exact Demo 43 Layout & Style) -->
		<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-5 pb-4">
			<?php foreach ( $kaveh_categories as $cat ) : ?>
				<div class="categories-nk__item group">
					<a href="<?php echo esc_url( $cat['link'] ); ?>" class="image">
						<img src="<?php echo esc_url( $cat['img'] ); ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>" class="transition-transform duration-300">
					</a>
					<h3 class="title">
						<a href="<?php echo esc_url( $cat['link'] ); ?>" class="text-[#212341] hover:text-rose-600 transition-colors">
							<?php echo esc_html( $cat['name'] ); ?>
						</a>
					</h3>
					<!-- Circular Red Arrow Button inside the Notch -->
					<a href="<?php echo esc_url( $cat['link'] ); ?>" class="btn-nk-danger-2" aria-label="<?php echo esc_attr( $cat['name'] ); ?>">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
