<?php
/**
 * Section 33: Latest Blog Articles & Buying Guides (HOME-33)
 * Reference: Demo 43 Section 32 (Top: 16037px, Height: ~707px)
 * Heading: "برترین اخبار - زمانت قیمت و کیفیت"
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$post_thumb = $upload_dir['baseurl'] . '/kaveh-demo/banner-02-150x150.png';

$posts_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$sample_articles = array(
	array(
		'title'    => 'آینده هوش مصنوعی در گجت‌های خانگی؛ چه چیزهایی واقعا کاربردی‌اند؟',
		'category' => 'خانه هوشمند',
		'date'     => '۱۵ آبان ۱۴۰۳',
		'author'   => 'مدیر سایت',
		'excerpt'  => 'بررسی جدیدترین ابزارهای خانه هوشمند سازگار با هوش مصنوعی مولد و اتوماسیون روشنایی و تهویه.',
	),
	array(
		'title'    => 'دوربین گوشی در برابر دوربین کامپکت؛ آیا هنوز به دوربین جدا نیاز داریم؟',
		'category' => 'عکاسی دیجیتال',
		'date'     => '۱۲ آبان ۱۴۰۳',
		'author'   => 'تحریریه نگار',
		'excerpt'  => 'مقایسه سنسورهای ۱ اینچی موبایل‌های جدید با دوربین‌های حرفه‌ای در شرایط نوری گوناگون و سفر.',
	),
	array(
		'title'    => 'بهترین کیبوردهای مکانیکی برای تایپ و گیمینگ؛ مقایسه سوئیچ‌ها',
		'category' => 'تجهیزات گیمینگ',
		'date'     => '۰۸ آبان ۱۴۰۳',
		'author'   => 'کارشناس سخت‌افزار',
		'excerpt'  => 'راهنمای جامع تفاوت سوئیچ‌های قرمز، آبی و قهوه‌ای برای برنامه‌نویسان و بازیکنان حرفه‌ای.',
	),
	array(
		'title'    => 'ذخیره‌سازی ابری در برابر هارد اکسترنال؛ کدام مسیر امن‌تر است؟',
		'category' => 'امنیت اطلاعات',
		'date'     => '۰۲ آبان ۱۴۰۳',
		'author'   => 'مدیر سایت',
		'excerpt'  => 'بررسی هزینه‌های بلندمدت، حفظ حریم خصوصی و سرعت دسترسی به بکاپ‌های ابری و محلی.',
	),
);
?>

<section class="negar-sec-33-blog py-8 bg-slate-50/60 border-t border-slate-100" aria-label="<?php esc_attr_e( 'برترین اخبار و مقالات', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Section Header -->
		<div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200/80">
			<div class="flex items-baseline gap-3">
				<h2 class="text-xl md:text-2xl font-black text-[#212341]">
					<?php esc_html_e( 'برترین اخبار و راهنماهای خرید', 'negar-shop' ); ?>
				</h2>
				<span class="text-xs text-slate-400 font-medium hidden sm:inline">
					<?php esc_html_e( 'نقد و بررسی و بررسی اخبار دنیای تکنولوژی', 'negar-shop' ); ?>
				</span>
			</div>
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'مشاهده همه مقالات', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<!-- 4-Column Articles Grid -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
			<?php
			if ( $posts_query->have_posts() ) :
				$idx = 0;
				while ( $posts_query->have_posts() ) :
					$posts_query->the_post();
					$sample = $sample_articles[ $idx % 4 ];
					?>
					<article class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
						
						<!-- Thumbnail with Category Badge -->
						<div class="relative aspect-[16/10] bg-slate-100 overflow-hidden">
							<a href="<?php the_permalink(); ?>" class="block w-full h-full">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition duration-500' ) ); ?>
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-tr from-slate-800 to-indigo-900 flex items-center justify-center p-4">
										<svg class="text-white/30" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
											<rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line>
										</svg>
									</div>
								<?php endif; ?>
							</a>
							<span class="absolute top-3 right-3 bg-rose-600 text-white text-3xs font-bold px-2.5 py-0.5 rounded-full shadow-xs">
								<?php echo esc_html( $sample['category'] ); ?>
							</span>
						</div>

						<!-- Content -->
						<div class="p-5 flex-1 flex flex-col justify-between">
							<div>
								<h3 class="text-sm font-black text-[#212341] group-hover:text-rose-600 transition leading-snug line-clamp-2 mb-2 min-h-[40px]">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h3>
								<p class="text-2xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
									<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
								</p>
							</div>

							<!-- Footer Meta -->
							<div class="flex items-center justify-between pt-3 border-t border-slate-100 text-3xs text-slate-400 font-medium">
								<span class="flex items-center gap-1">
									<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
									<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
								</span>
								<span class="text-rose-600 font-bold group-hover:underline"><?php esc_html_e( 'مطالعه مطلب', 'negar-shop' ); ?> ←</span>
							</div>
						</div>

					</article>
					<?php
					$idx++;
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

	</div>
</section>
