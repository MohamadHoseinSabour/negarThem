<?php
/**
 * Section 21: Customer Reviews & Testimonials Carousel (HOME-21)
 * Reference: Demo 43 Section 20 (Top: 9678px, Height: ~482px)
 * Heading: "نقد و بررسی‌ها کاربران درباره ما"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$av_1       = $upload_dir['baseurl'] . '/kaveh-demo/avatar-03.png';
$av_2       = $upload_dir['baseurl'] . '/kaveh-demo/avatar-comments-nk-150x150.png';
$av_3       = $upload_dir['baseurl'] . '/kaveh-demo/kaveh-demo/general/avatar-01.png';

$testimonials = array(
	array(
		'name'    => 'امیرحسین رضایی',
		'role'    => 'خریدار گوشی آیفون ۱۷ پرو مکس',
		'avatar'  => $av_1,
		'rating'  => 5,
		'text'    => 'بسته‌بندی فوق‌العاده پلمپ و اصل بود. تحویل در کمتر از ۲ ساعت با پیک اختصاصی انجام شد و گارانتی ۱۸ ماهه بلافاصله فعال شد.',
	),
	array(
		'name'    => 'سارا محمدی',
		'role'    => 'خریدار مک‌بوک پرو M5',
		'avatar'  => $av_2,
		'rating'  => 5,
		'text'    => 'بهترین تجربه خرید آنلاین سخت‌افزار! پشتیبانی فنی قبل از خرید برای انتخاب دقیق رم و حافظه راهنمایی کاملی دادند.',
	),
	array(
		'name'    => 'مهدی کاظمی',
		'role'    => 'خریدار پلی‌استیشن ۵ اسلیم',
		'avatar'  => $av_3,
		'rating'  => 5,
		'text'    => 'قیمت نسبت به کل بازار عالی بود و کد تخفیف شگفت‌انگیز هم کار کرد. کنسول کاملاً ریجن اروپا و تست شده تحویل داده شد.',
	),
);
?>

<section class="negar-sec-21-testimonials py-10 bg-slate-50/70 border-y border-slate-200/60" aria-label="<?php esc_attr_e( 'نظرات خریداران و همراهان', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="text-center max-w-xl mx-auto mb-8">
			<span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-full inline-block mb-2">
				<?php esc_html_e( 'صدای مشتریان نگار شاپ', 'negar-shop' ); ?>
			</span>
			<h2 class="text-2xl md:text-3xl font-black text-[#212341]">
				<?php esc_html_e( 'نقد و بررسی کاربران درباره ما', 'negar-shop' ); ?>
			</h2>
			<p class="text-xs text-slate-500 mt-1">
				<?php esc_html_e( 'با ما مطمئن‌ترین و سریع‌ترین خرید دیجیتال را تجربه کنید', 'negar-shop' ); ?>
			</p>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<?php foreach ( $testimonials as $t ) : ?>
				<div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
					
					<div>
						<!-- Star Rating -->
						<div class="flex items-center gap-1 text-amber-400 mb-4">
							<?php for ( $i = 0; $i < $t['rating']; $i++ ) : ?>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
									<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
								</svg>
							<?php endfor; ?>
						</div>

						<!-- Quote -->
						<p class="text-xs text-slate-600 leading-relaxed italic mb-6">
							«<?php echo esc_html( $t['text'] ); ?>»
						</p>
					</div>

					<!-- Author Info -->
					<div class="flex items-center gap-3 pt-4 border-t border-slate-100">
						<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
							<img src="<?php echo esc_url( $t['avatar'] ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>" class="w-full h-full object-cover">
						</div>
						<div>
							<h4 class="text-xs font-black text-[#212341]"><?php echo esc_html( $t['name'] ); ?></h4>
							<span class="text-3xs text-slate-400 font-medium"><?php echo esc_html( $t['role'] ); ?></span>
						</div>
					</div>

				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
