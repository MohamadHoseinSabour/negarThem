<?php
/**
 * Story Circles Component - 100% Matching Kaveh Demo 43
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$story_base = $upload_dir['baseurl'] . '/kaveh-demo/kaveh-demo/stories';

$stories = array(
	array(
		'title'       => 'پیشنهاد داغ',
		'badge'       => 'ویژه ما',
		'badge_color' => 'bg-gradient-to-r from-rose-600 to-red-600',
		'ring_color'  => 'from-rose-500 via-red-500 to-amber-500',
		'image'       => $story_base . '/12-150x150-1.jpg',
	),
	array(
		'title'       => 'شروتی متفاوت',
		'badge'       => '',
		'badge_color' => '',
		'ring_color'  => 'from-fuchsia-500 to-rose-500',
		'image'       => $story_base . '/11c84a6efb8352f41b63a80222c4e5e9d923c2b5_1739995946-150x150-1.jpg',
	),
	array(
		'title'       => 'ویژه تو',
		'badge'       => 'محصول ویژه',
		'badge_color' => 'bg-gradient-to-r from-amber-500 to-orange-500',
		'ring_color'  => 'from-rose-600 to-amber-500',
		'image'       => $story_base . '/0ca0161bda0b47d19aa49cb9225c0f73b37e72ef_1738957158-150x150-1.jpg',
	),
	array(
		'title'       => 'شانس با توست',
		'badge'       => '',
		'badge_color' => '',
		'ring_color'  => 'from-purple-500 to-pink-500',
		'image'       => $story_base . '/eaa2235390c5b4953f8a7263ef5673fefc932a8a_1739293537-150x150-1.jpg',
	),
	array(
		'title'       => 'تخفیف رویایی',
		'badge'       => 'تخفیف ویژه',
		'badge_color' => 'bg-gradient-to-r from-rose-600 to-red-600',
		'ring_color'  => 'from-pink-500 to-rose-600',
		'image'       => $story_base . '/fcf5df508d005982feac77ce3c7feaff599f092b_1738870860-150x150-1.jpg',
	),
	array(
		'title'       => 'تکرار نمیشه',
		'badge'       => '',
		'badge_color' => '',
		'ring_color'  => 'from-rose-500 to-orange-400',
		'image'       => $story_base . '/4-3-150x150-1.jpg',
	),
	array(
		'title'       => 'دایره تکونی',
		'badge'       => '',
		'badge_color' => '',
		'ring_color'  => 'from-violet-500 to-fuchsia-500',
		'image'       => $story_base . '/bdacdf20f670352a4b6c52ea8ade329632dd5f3c_1739723592-150x150-1.jpg',
	),
	array(
		'title'       => 'انتخاب خاص',
		'badge'       => 'ویژه',
		'badge_color' => 'bg-gradient-to-r from-amber-500 to-orange-500',
		'ring_color'  => 'from-red-500 to-pink-500',
		'image'       => $story_base . '/9d92f832e9dfad79b7dde268fe7cc100c8192767_1736272111-150x150-1.jpg',
	),
	array(
		'title'       => 'یک خرید خاص',
		'badge'       => '',
		'badge_color' => '',
		'ring_color'  => 'from-pink-500 to-rose-600',
		'image'       => $story_base . '/22-150x150-1.jpg',
	),
	array(
		'title'       => 'برای تو',
		'badge'       => 'تخفیف ویژه',
		'badge_color' => 'bg-gradient-to-r from-rose-600 to-red-600',
		'ring_color'  => 'from-purple-500 to-rose-500',
		'image'       => $story_base . '/30-150x150-1.jpg',
	),
);
?>

<section class="story-circles-section py-3.5 bg-white border-b border-slate-100 relative">
	<div class="container relative">
		
		<!-- Left / Right Carousel Navigation Arrows -->
		<button type="button" class="story-nav-btn story-prev absolute right-1 top-1/2 -translate-y-1/2 z-10 w-7 h-7 rounded-full bg-white shadow-md border border-slate-200 text-slate-700 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition" aria-label="<?php esc_attr_e( 'قبلی', 'negar-shop' ); ?>">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
		</button>
		<button type="button" class="story-nav-btn story-next absolute left-1 top-1/2 -translate-y-1/2 z-10 w-7 h-7 rounded-full bg-white shadow-md border border-slate-200 text-slate-700 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition" aria-label="<?php esc_attr_e( 'بعدی', 'negar-shop' ); ?>">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
		</button>

		<!-- Horizontal Story Circles Track -->
		<div class="story-track flex items-center justify-between gap-3 sm:gap-4 overflow-x-auto no-scrollbar px-6 sm:px-8 py-1">
			<?php foreach ( $stories as $story ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="story-item flex flex-col items-center shrink-0 group">
					
					<!-- Circle Avatar with Gradient Ring -->
					<div class="relative w-15 h-15 sm:w-16 sm:h-16 rounded-full p-0.5 bg-gradient-to-tr <?php echo esc_attr( $story['ring_color'] ); ?> group-hover:scale-105 transition-transform duration-300">
						<div class="w-full h-full rounded-full p-0.5 bg-white overflow-hidden">
							<img src="<?php echo esc_url( $story['image'] ); ?>" alt="<?php echo esc_attr( $story['title'] ); ?>" class="w-full h-full object-cover rounded-full" loading="lazy">
						</div>

						<!-- Optional Bottom Pill Badge -->
						<?php if ( ! empty( $story['badge'] ) ) : ?>
							<span class="absolute -bottom-1 left-1/2 -translate-x-1/2 <?php echo esc_attr( $story['badge_color'] ); ?> text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-xs whitespace-nowrap leading-none scale-90">
								<?php echo esc_html( $story['badge'] ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- Story Title -->
					<span class="text-3xs sm:text-2xs font-bold text-slate-700 group-hover:text-rose-600 transition-colors mt-2 text-center whitespace-nowrap">
						<?php echo esc_html( $story['title'] ); ?>
					</span>

				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
