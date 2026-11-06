<?php
/**
 * Story Circles Component (HOME-01) - 100% Matching Kaveh Demo 43
 * Reference: Demo 43 Section 0 (Top: 257px, Height: ~188px)
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$story_base = $upload_dir['baseurl'] . '/kaveh-demo/stories';

$stories = array(
	array( 'title' => 'پیشنهاد داغ', 'badge' => 'ویژه ما', 'img' => $story_base . '/story-1-pishnahad-dagh.jpg', 'ring' => 'from-rose-500 via-red-500 to-amber-500' ),
	array( 'title' => 'شروعی متفاوت', 'badge' => '', 'img' => $story_base . '/story-2-shoru-motafavet.jpg', 'ring' => 'from-fuchsia-500 via-pink-500 to-rose-500' ),
	array( 'title' => 'ویژه تو', 'badge' => 'محصول ویژه', 'img' => $story_base . '/story-3-vizhe-to.jpg', 'ring' => 'from-amber-500 via-orange-500 to-rose-500' ),
	array( 'title' => 'شانس با توست', 'badge' => '', 'img' => $story_base . '/story-4-shans-ba-tost.jpg', 'ring' => 'from-purple-500 via-indigo-500 to-pink-500' ),
	array( 'title' => 'تخفیف رویایی', 'badge' => 'تخفیف ویژه', 'img' => $story_base . '/story-5-takhfif-royaei.jpg', 'ring' => 'from-rose-600 via-pink-600 to-amber-400' ),
	array( 'title' => 'تکرار نمیشه', 'badge' => '', 'img' => $story_base . '/story-1-pishnahad-dagh.jpg', 'ring' => 'from-rose-500 via-orange-500 to-amber-500' ),
	array( 'title' => 'باورنکردنی', 'badge' => 'فرصت ناب', 'img' => $story_base . '/story-2-shoru-motafavet.jpg', 'ring' => 'from-pink-500 via-rose-500 to-red-500' ),
	array( 'title' => 'انتخاب خاص', 'badge' => '', 'img' => $story_base . '/story-3-vizhe-to.jpg', 'ring' => 'from-indigo-500 via-purple-500 to-pink-500' ),
	array( 'title' => 'یک خرید خاص', 'badge' => '', 'img' => $story_base . '/story-4-shans-ba-tost.jpg', 'ring' => 'from-emerald-500 via-teal-500 to-cyan-500' ),
	array( 'title' => 'برای تو', 'badge' => 'کالای لوکس', 'img' => $story_base . '/story-5-takhfif-royaei.jpg', 'ring' => 'from-rose-500 via-amber-500 to-orange-500' ),
);
?>

<section class="negar-story-circles-section py-4 bg-white border-b border-slate-100/80" aria-label="<?php esc_attr_e( 'استوری‌ها و پیشنهادات سریع', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="stories-track flex items-start gap-4 md:gap-6 overflow-x-auto pb-2 no-scrollbar scroll-smooth">
			<?php foreach ( $stories as $s ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="story-circle-item group flex flex-col items-center shrink-0 w-[76px] sm:w-[84px] text-center cursor-pointer">
					
					<!-- Ring & Avatar -->
					<div class="relative w-[72px] h-[72px] sm:w-[80px] sm:h-[80px] rounded-full p-[2.5px] bg-gradient-to-tr <?php echo esc_attr( $s['ring'] ); ?> flex items-center justify-center shadow-xs group-hover:scale-105 group-hover:shadow-md transition-all duration-300">
						<div class="w-full h-full rounded-full overflow-hidden border-2 border-white bg-slate-100 flex items-center justify-center">
							<img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" class="w-full h-full object-cover">
						</div>

						<?php if ( ! empty( $s['badge'] ) ) : ?>
							<span class="absolute -bottom-1 bg-rose-600 text-white text-[9px] font-black px-1.5 py-0.2 rounded-full shadow-xs border border-white whitespace-nowrap">
								<?php echo esc_html( $s['badge'] ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- Title -->
					<span class="text-xs font-bold text-[#212341] group-hover:text-rose-600 transition-colors mt-2 line-clamp-1 leading-snug">
						<?php echo esc_html( $s['title'] ); ?>
					</span>

				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
