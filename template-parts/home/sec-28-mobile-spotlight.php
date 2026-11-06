<?php
/**
 * Section 28: Samsung Galaxy S25 FE Spotlight Grid (HOME-28)
 * Reference: Demo 43 Section 27 (Top: 13497px, Height: ~696px)
 * Heading: "پربازدید ترین محصولات - گلکسی اس ۲۵ اف ای"
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$s25_img    = $upload_dir['baseurl'] . '/kaveh-demo/products/samsung-s25-fe.png';

$adjacent_products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
?>

<section class="negar-sec-28-s25-spotlight py-5" aria-label="<?php esc_attr_e( 'ویترین گلکسی S25 FE', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header & Divider -->
		<div class="section-heading-one-nk">
			<div class="title">
				<span class="highlight">پربازدید</span> ترین محصولات
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'بر اساس مخاطبان', 'negar-shop' ); ?>
			</div>
			<div class="line">
				<span></span>
				<span></span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
			
			<!-- Spotlight Featured Card (4 Cols) -->
			<div class="lg:col-span-4 bg-gradient-to-bl from-slate-50 via-white to-blue-50/40 rounded-3xl p-5 border border-slate-200 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
				<div class="flex items-center justify-between mb-2">
					<span class="text-3xs font-bold text-blue-600 bg-blue-100/70 px-2.5 py-0.5 rounded-full">
						<?php esc_html_e( 'پیشنهاد ویژه هواداران سامسونگ', 'negar-shop' ); ?>
					</span>
					<span class="text-amber-500 font-bold text-xs flex items-center gap-1">★ ۴.۹</span>
				</div>
				
				<div class="h-[200px] flex items-center justify-center p-2 my-1">
					<img src="<?php echo esc_url( $s25_img ); ?>" alt="<?php esc_attr_e( 'گوشی سامسونگ Galaxy S25 FE', 'negar-shop' ); ?>" class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-500">
				</div>

				<div>
					<h3 class="text-sm md:text-base font-black text-[#212341] mb-1 line-clamp-2">
						<?php esc_html_e( 'گوشی موبایل سامسونگ مدل Galaxy S25 FE دو سیم‌کارت ظرفیت ۲۵۶ گیگابایت', 'negar-shop' ); ?>
					</h3>
					<p class="text-2xs text-slate-500 line-clamp-2 leading-relaxed mb-3">
						<?php esc_html_e( 'صفحه نمایش Dynamic AMOLED 2X با پردازنده قدرتمند و هوش مصنوعی Galaxy AI.', 'negar-shop' ); ?>
					</p>
					<div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
						<div>
							<span class="text-3xs text-slate-400 block line-through">۴۹,۸۰۰,۰۰۰ تومان</span>
							<span class="text-sm font-black text-rose-600">۴۴,۵۰۰,۰۰۰ تومان</span>
						</div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-full transition shadow-xs">
							<?php esc_html_e( 'خرید آنی', 'negar-shop' ); ?>
						</a>
					</div>
				</div>
			</div>

			<!-- 3 Adjacent Cards in Single Row (8 Cols) -->
			<div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
				<?php
				if ( $adjacent_products->have_posts() ) :
					while ( $adjacent_products->have_posts() ) :
						$adjacent_products->the_post();
						?>
						<div class="product-grid-item">
							<?php get_template_part( 'template-parts/product/card' ); ?>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

		</div>

	</div>
</section>
