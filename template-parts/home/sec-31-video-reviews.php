<?php
/**
 * Section 31: Product video reviews (HOME-31).
 *
 * Video reviews may only be shown when they come from a verified publisher or
 * product data source. Do not place sample presenters, customer identities, or
 * copied video URLs in this public storefront component.
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_link = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<div class="elementor-element elementor-element-1a06770f e-con-full e-flex e-con e-parent e-lazyloaded" data-id="1a06770f" data-element_type="container">
	<div class="elementor-element elementor-element-39f2483d elementor-widget elementor-widget-videosnkkaveh" data-id="39f2483d" data-element_type="widget" data-widget_type="videosnkkaveh.default">
		<div class="elementor-widget-container">
			<section class="videos-nk videos-nk-39f2483d" aria-labelledby="negar-video-reviews-title">
				<div class="container">
					<div class="videos-nk__wrapper position-relative">
						<div class="section-heading-one-nk d-flex align-items-center gap-2">
							<div class="title f-iran-yekan" id="negar-video-reviews-title"><?php esc_html_e( 'ویدیوهای بررسی محصول', 'negar-shop' ); ?></div>
							<div class="sub-title f-iran-yekan fw-normal d-none d-md-block"><?php esc_html_e( 'محتوای تأییدشده‌ی فروشگاه', 'negar-shop' ); ?></div>
							<div class="line flex-grow-1 d-flex align-items-center gap-1" aria-hidden="true"><span class="flex-grow-1"></span><span></span></div>
							<a href="<?php echo esc_url( $shop_link ); ?>" class="btn d-flex align-items-center justify-content-center fw-normal rounded-pill position-relative">
								<?php esc_html_e( 'مشاهده محصولات', 'negar-shop' ); ?>
								<i class="icon icon-chevron-left-2 lh-1" aria-hidden="true"></i>
							</a>
						</div>

						<div class="videos-nk__empty rounded-4 border border-slate-200 bg-white p-5 p-md-6 text-center" role="status">
							<div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-slate-100 text-slate-500" style="width: 3.5rem; height: 3.5rem;">
								<i class="icon-play-4 lh-1" aria-hidden="true"></i>
							</div>
							<p class="mb-1 fw-bold text-slate-800"><?php esc_html_e( 'هنوز ویدیوی بررسی تأییدشده‌ای برای نمایش نداریم.', 'negar-shop' ); ?></p>
							<p class="mb-0 small text-slate-500"><?php esc_html_e( 'پس از انتشار محتوای واقعی و تأییدشده، این بخش به‌روزرسانی می‌شود.', 'negar-shop' ); ?></p>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
