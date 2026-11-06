<?php
/**
 * Section 31: Product Video Reviews Grid (HOME-31)
 * Reference: Demo 43 Section 30 (Top: 14926px, Height: ~737px)
 * Heading: "ویدیو های برسی محصول"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$vid_img    = $upload_dir['baseurl'] . '/kaveh-demo/video-01.png';
$avatar_img = $upload_dir['baseurl'] . '/kaveh-demo/avatar-03.png';

$video_reviews = array(
	array(
		'title'    => 'بررسی تخصصی آیفون ۱۷ پرو مکس؛ آیا ارزش ارتقا دارد؟',
		'author'   => 'امیرحسین شبانی',
		'date'     => '۲۳ مرداد ۱۴۰۲',
		'category' => 'بررسی گوشی موبایل',
		'duration' => '۱۲:۴۵',
	),
	array(
		'title'    => 'جعبه‌گشایی و اولین تجربه کار با مک‌بوک پرو M5',
		'author'   => 'علیرضا حسینی',
		'date'     => '۱۸ مرداد ۱۴۰۲',
		'category' => 'بررسی لپ‌تاپ',
		'duration' => '۰۹:۱۵',
	),
	array(
		'title'    => 'مقایسه ساعت اپل واچ سری ۱۰ با گلکسی واچ ۷ اولترا',
		'author'   => 'پویا مرادی',
		'date'     => '۱۰ مرداد ۱۴۰۲',
		'category' => 'ساعت هوشمند',
		'duration' => '۱۴:۲۰',
	),
);
?>

<section class="negar-sec-31-video-reviews py-8" aria-label="<?php esc_attr_e( 'ویدیوهای بررسی محصول', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Section Header -->
		<div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
			<div class="flex items-baseline gap-3">
				<h2 class="text-xl md:text-2xl font-black text-[#212341]">
					<?php esc_html_e( 'ویدیو های بررسی محصول', 'negar-shop' ); ?>
				</h2>
				<span class="text-xs text-slate-400 font-medium hidden sm:inline">
					<?php esc_html_e( 'تست عملکرد و جعبه‌گشایی توسط متخصصین', 'negar-shop' ); ?>
				</span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'مشاهده بیشتر ویدیوها', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- Video Cards Grid -->
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<?php foreach ( $video_reviews as $vid ) : ?>
				<div class="group bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
					
					<!-- Video Thumbnail with Play Button -->
					<div class="relative aspect-[16/9] bg-slate-900 overflow-hidden">
						<img src="<?php echo esc_url( $vid_img ); ?>" alt="<?php echo esc_attr( $vid['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
						
						<!-- Play Button Overlay -->
						<div class="absolute inset-0 flex items-center justify-center">
							<div class="w-14 h-14 rounded-full bg-rose-600/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:bg-rose-600 transition-all duration-300 cursor-pointer">
								<svg class="mr-0.5" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
									<polygon points="5 3 19 12 5 21 5 3"></polygon>
								</svg>
							</div>
						</div>

						<span class="absolute bottom-2 left-2 bg-black/75 text-white text-3xs font-bold px-2 py-0.5 rounded-md">
							<?php echo esc_html( $vid['duration'] ); ?>
						</span>
					</div>

					<!-- Video Content -->
					<div class="p-5 flex-1 flex flex-col justify-between">
						<div>
							<span class="text-3xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full inline-block mb-2">
								<?php echo esc_html( $vid['category'] ); ?>
							</span>
							<h3 class="text-sm font-black text-[#212341] group-hover:text-rose-600 transition leading-snug line-clamp-2 mb-4">
								<?php echo esc_html( $vid['title'] ); ?>
							</h3>
						</div>

						<!-- Author & Date -->
						<div class="flex items-center justify-between pt-3 border-t border-slate-100 text-3xs text-slate-400 font-medium">
							<div class="flex items-center gap-2">
								<img src="<?php echo esc_url( $avatar_img ); ?>" alt="<?php echo esc_attr( $vid['author'] ); ?>" class="w-6 h-6 rounded-full object-cover">
								<span class="text-slate-700 font-bold"><?php echo esc_html( $vid['author'] ); ?></span>
							</div>
							<span><?php echo esc_html( $vid['date'] ); ?></span>
						</div>
					</div>

				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
