<?php
/**
 * Section 25: Gaming Zone Showcase (HOME-25) - Combo Pack Layout
 * Reference: Demo 43 Section 24 (Top: 11832px, Height: ~640px)
 * Heading: "محصول گیم - بازی Assassin’s Creed Shadows مخصوص PS5"
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$game_banner = $upload_dir['baseurl'] . '/kaveh-demo/product-pack.png';
if ( ! file_exists( $upload_dir['basedir'] . '/kaveh-demo/product-pack.png' ) ) {
	$game_banner = $upload_dir['baseurl'] . '/kaveh-demo/banner-03.png';
}

$bundle_items = array(
	array(
		'title'  => 'بازی Assassin’s Creed Shadows مخصوص PS5',
		'price'  => '۵,۷۵۰,۰۰۰',
		'rating' => '۵ / ۳',
		'img'    => $upload_dir['baseurl'] . '/kaveh-demo/products/samsung-s25-fe.png',
	),
	array(
		'title'  => 'بازی Assassins Creed Mirage مخصوص PS5',
		'price'  => '۴,۳۶۲,۵۰۰',
		'rating' => '۵ / ۴',
		'img'    => $upload_dir['baseurl'] . '/kaveh-demo/products/iphone-16-pro-max.png',
	),
	array(
		'title'  => 'بازی EA Sports FC 26 نسخه استاندارد مخصوص PS5',
		'price'  => '۴,۱۹۹,۰۰۰',
		'rating' => '۵ / ۴.۵',
		'img'    => $upload_dir['baseurl'] . '/kaveh-demo/products/xiaomi-15-ultra.png',
	),
	array(
		'title'  => 'بازی Ghost of Yotei مخصوص PS5',
		'price'  => '۹,۲۸۹,۰۰۰',
		'rating' => '۵ / ۳.۵',
		'img'    => $upload_dir['baseurl'] . '/kaveh-demo/products/nothing-phone-2a.png',
	),
);
?>

<section class="negar-sec-25-gaming py-4" aria-label="<?php esc_attr_e( 'بسته ویژه گیمینگ و کنسول', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<div class="bg-white rounded-3xl p-4 md:p-6 border border-slate-200/80 shadow-xs overflow-hidden">
			
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
				
				<!-- Right Column (In RTL): Big Media with Interactive Hotspot Pins (8 Cols) -->
				<div class="lg:col-span-8 relative rounded-2xl overflow-hidden bg-slate-900 min-h-[380px] lg:min-h-[520px] flex items-center justify-center p-4">
					<img src="<?php echo esc_url( $game_banner ); ?>" alt="<?php esc_attr_e( 'بسته گیمینگ PS5', 'negar-shop' ); ?>" class="w-full h-full object-cover object-center absolute inset-0 opacity-90">
					
					<!-- Hotspot Pin 1 -->
					<div class="absolute bottom-[20%] right-[15%] hidden md:flex items-center gap-2 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-2xl shadow-lg border border-slate-100 z-10">
						<span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-ping"></span>
						<span class="text-3xs font-black text-[#212341]">Assassin's Creed Shadows</span>
						<span class="text-3xs font-black text-rose-600">۵,۷۵۰,۰۰۰ ت</span>
					</div>

					<!-- Hotspot Pin 2 -->
					<div class="absolute top-[25%] left-[20%] hidden md:flex items-center gap-2 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-2xl shadow-lg border border-slate-100 z-10">
						<span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-ping"></span>
						<span class="text-3xs font-black text-[#212341]">Ghost of Yotei</span>
						<span class="text-3xs font-black text-rose-600">۹,۲۸۹,۰۰۰ ت</span>
					</div>

					<!-- Hotspot Pin 3 -->
					<div class="absolute bottom-[28%] left-[30%] hidden md:flex items-center gap-2 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-2xl shadow-lg border border-slate-100 z-10">
						<span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
						<span class="text-3xs font-black text-[#212341]">EA Sports FC 26</span>
						<span class="text-3xs font-black text-rose-600">۴,۱۹۹,۰۰۰ ت</span>
					</div>
				</div>

				<!-- Left Column: Bundle Sidebar with Items & Total (4 Cols) -->
				<div class="lg:col-span-4 bg-slate-50/80 rounded-2xl p-4 md:p-5 flex flex-col justify-between border border-slate-100">
					
					<!-- Header -->
					<div>
						<div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/70">
							<div class="flex items-center gap-2">
								<span class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xs font-black">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
								</span>
								<h3 class="text-sm font-black text-[#212341]"><?php esc_html_e( 'محصول گیم (پک ۴ عددی)', 'negar-shop' ); ?></h3>
							</div>
							<span class="text-3xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">
								<?php esc_html_e( '۹٪ تخفیف پک', 'negar-shop' ); ?>
							</span>
						</div>

						<!-- Items List -->
						<div class="space-y-2.5">
							<?php foreach ( $bundle_items as $item ) : ?>
								<div class="flex items-center justify-between gap-2 p-2 bg-white rounded-xl border border-slate-100 shadow-2xs">
									<div class="flex-1 min-w-0">
										<h4 class="text-2xs font-bold text-slate-800 truncate mb-0.5">
											<?php echo esc_html( $item['title'] ); ?>
										</h4>
										<div class="flex items-center gap-2 text-3xs text-slate-400">
											<span class="text-amber-500 font-bold">★ <?php echo esc_html( $item['rating'] ); ?></span>
											<span>•</span>
											<span class="font-black text-rose-600"><?php echo esc_html( $item['price'] ); ?> تومان</span>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Order Footer -->
					<div class="pt-4 mt-3 border-t border-slate-200">
						<div class="flex items-center justify-between mb-3">
							<span class="text-xs font-bold text-slate-500">۴ کالا در سبد پک</span>
							<div class="text-left">
								<span class="text-3xs text-slate-400 line-through block">۲۵,۸۹۰,۶۰۰ تومان</span>
								<span class="text-sm font-black text-rose-600">۲۳,۶۰۱,۵۰۰ تومان</span>
							</div>
						</div>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="w-full inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs py-2.5 rounded-xl transition shadow-md">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
							<span><?php esc_html_e( 'افزودن همه به سبد خرید', 'negar-shop' ); ?></span>
						</a>
					</div>

				</div>

			</div>

		</div>

	</div>
</section>
