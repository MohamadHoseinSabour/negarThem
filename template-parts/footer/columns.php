<?php
/**
 * Footer Columns & Channels - 100% Matching Kaveh Demo 43 (#ag-theme-builder-footer)
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$demo_base  = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<div class="negar-footer-wrapper bg-[#f8fafc] text-slate-700 pt-0 pb-2 border-t border-slate-200/80 relative">
	
	<!-- 1. Top Decorative Wave Vector Image matching Group-427321238.png -->
	<div class="w-full overflow-hidden mb-6 leading-none select-none">
		<img src="<?php echo esc_url( $demo_base . '/footer-wave-top.png' ); ?>" alt="" class="w-full h-auto object-cover max-h-12" loading="lazy">
	</div>

	<div class="container max-w-[1320px] mx-auto px-4">
		
		<!-- 2. Main 4-Column Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-slate-200/70">
			
			<!-- Column 1: Fast Access Links (3 Cols) -->
			<div class="lg:col-span-3">
				<div class="flex items-center gap-2 mb-5">
					<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'دسترسی سریع', 'negar-shop' ); ?></h3>
				</div>
				<ul class="space-y-3 text-xs text-slate-600 font-medium">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">دوره‌های آموزشی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">اتاق خبر دیجی‌کالا</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فروش در دیجی‌کالا</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فرصت‌های شغلی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">تماس با دیجی‌کالا</a></li>
				</ul>
			</div>

			<!-- Column 2: Customer Services (3 Cols) -->
			<div class="lg:col-span-3">
				<div class="flex items-center gap-2 mb-5">
					<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'خدمات مشتریان', 'negar-shop' ); ?></h3>
				</div>
				<ul class="space-y-3 text-xs text-slate-600 font-medium">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">دوره‌های آموزشی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">اتاق خبر و وبلاگ</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">قوانین و رویه‌های مرجوعی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">حفظ حریم خصوصی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">پاسخ به سوالات متداول</a></li>
				</ul>
			</div>

			<!-- Column 3: About Shop Box matching Demo 43 (3 Cols) -->
			<div class="lg:col-span-3 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
				<div>
					<div class="w-14 h-14 rounded-2xl bg-[#e90544] text-white flex items-center justify-center mb-4 shadow-md shadow-rose-700/25">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
					</div>
					<p class="text-2xs text-slate-500 leading-relaxed line-clamp-4 mb-4">
						<?php esc_html_e( 'فعالیت خود را از سال ۱۳۹۸ در زمینه فروش کالاهای دیجیتال و تجهیزات هوشمند آغاز نموده است. این مجموعه کالاها را بدون واسطه و از طریق شرکت‌های اصلی سازنده به صورت مستقیم تامین می‌کند و ضمانت اصالت را به تمام مشتریان ارائه می‌دهد.', 'negar-shop' ); ?>
					</p>
				</div>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="inline-flex items-center justify-center gap-1.5 bg-[#e90544] hover:bg-rose-700 text-white font-bold text-xs py-2 px-5 rounded-full transition shadow-xs w-fit">
					<span><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></span>
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</div>

			<!-- Column 4: Trust Badges (Enamad & Samandehi) & Dual Phone Boxes (3 Cols) -->
			<div class="lg:col-span-3 flex flex-col justify-between">
				<div>
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'چگونه به ما اعتماد کنید', 'negar-shop' ); ?></h3>
					</div>
					<div class="grid grid-cols-2 gap-3 items-center mb-4">
						<div class="bg-white p-3 rounded-2xl border border-slate-200/80 flex items-center justify-center aspect-square shadow-2xs hover:shadow-md transition">
							<img src="<?php echo esc_url( $demo_base . '/enamad.png' ); ?>" alt="نماد اعتماد الکترونیکی" class="max-h-20 object-contain">
						</div>
						<div class="bg-white p-3 rounded-2xl border border-slate-200/80 flex items-center justify-center aspect-square shadow-2xs hover:shadow-md transition">
							<img src="<?php echo esc_url( $demo_base . '/samandehi.png' ); ?>" alt="ساماندهی پایگاه‌های اینترنتی" class="max-h-20 object-contain">
						</div>
					</div>
				</div>

				<!-- Dual Fast Phone Boxes matching Demo 43 (3f5f71ae & 200af606) -->
				<div class="grid grid-cols-2 gap-2">
					<div class="bg-white p-2.5 rounded-2xl border border-slate-200/70 text-center shadow-2xs">
						<span class="text-3xs text-slate-400 block mb-0.5 font-medium">پشتیبانی سریع</span>
						<span class="text-xs font-black text-rose-600 block dir-ltr">021-12345678</span>
					</div>
					<div class="bg-white p-2.5 rounded-2xl border border-slate-200/70 text-center shadow-2xs">
						<span class="text-3xs text-slate-400 block mb-0.5 font-medium">پشتیبانی سریع</span>
						<span class="text-xs font-black text-rose-600 block dir-ltr">021-87654321</span>
					</div>
				</div>

			</div>

		</div>

		<!-- 3. Contact Channels & App Download Bar (Matching Demo 43 Container 7bafa34a) -->
		<div class="py-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 items-center border-b border-slate-200/70">
			
			<!-- Contact Info & Address (4 Cols) -->
			<div class="lg:col-span-4">
				<div class="flex items-center gap-2 mb-2">
					<svg width="20" height="5" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h4 class="text-sm font-black text-[#212341] m-0"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></h4>
				</div>
				<p class="text-2xs text-slate-500 leading-relaxed mb-3">
					<?php esc_html_e( 'اصفهان، خیابان امام خمینی، جنب بیمه تامین اجتماعی، ساختمان طبقه پنجم، واحد ۲', 'negar-shop' ); ?>
				</p>
				<a href="mailto:info@Sitename.com" class="inline-flex items-center gap-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
					<span>info@Sitename.com</span>
				</a>
			</div>

			<!-- Support Channels: Telegram, Instagram, WhatsApp (4 Cols) -->
			<div class="lg:col-span-4 grid grid-cols-3 gap-2 text-center">
				
				<!-- Telegram -->
				<div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
					<div class="flex items-center justify-center gap-1 mb-1">
						<svg width="14" height="4" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<span class="text-3xs text-slate-500 font-bold">تلگرام</span>
					</div>
					<span class="text-xs font-black text-sky-600 block dir-ltr">moeinwp@</span>
				</div>

				<!-- Instagram -->
				<div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
					<div class="flex items-center justify-center gap-1 mb-1">
						<svg width="14" height="4" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<span class="text-3xs text-slate-500 font-bold">اینستاگرام</span>
					</div>
					<span class="text-xs font-black text-pink-600 block dir-ltr">moeinwp@</span>
				</div>

				<!-- WhatsApp -->
				<div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
					<div class="flex items-center justify-center gap-1 mb-1">
						<svg width="14" height="4" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<span class="text-3xs text-slate-500 font-bold">واتساپ</span>
					</div>
					<span class="text-xs font-black text-emerald-600 block dir-ltr">moeinwp@</span>
				</div>

			</div>

			<!-- App Download Badges (4 Cols) -->
			<div class="lg:col-span-4">
				<div class="grid grid-cols-2 gap-2">
					<a href="#" class="block bg-white p-1.5 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs">
						<img src="<?php echo esc_url( $demo_base . '/app-bazaar.png' ); ?>" alt="دانلود از کافه بازار" class="w-full h-8 object-contain">
					</a>
					<a href="#" class="block bg-white p-1.5 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs">
						<img src="<?php echo esc_url( $demo_base . '/app-sibche.png' ); ?>" alt="دانلود از سیبچه" class="w-full h-8 object-contain">
					</a>
					<a href="#" class="block bg-white p-1.5 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs">
						<img src="<?php echo esc_url( $demo_base . '/app-googleplay.png' ); ?>" alt="دانلود از گوگل‌پلی" class="w-full h-8 object-contain">
					</a>
					<a href="#" class="block bg-white p-1.5 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs">
						<img src="<?php echo esc_url( $demo_base . '/app-direct.png' ); ?>" alt="دانلود مستقیم" class="w-full h-8 object-contain">
					</a>
				</div>
			</div>

		</div>

		<!-- 4. Bottom Decorative Wave Image matching Vector-3180.png with Floating Blur Button -->
		<div class="relative w-full my-6 select-none">
			<img src="<?php echo esc_url( $demo_base . '/footer-wave-bottom.png' ); ?>" alt="" class="w-full h-auto object-cover max-h-12" loading="lazy">
			
			<!-- Floating Circular Back-to-Top Button matching Elementor 3fce803c -->
			<button 
				type="button" 
				class="footer-back-to-top" 
				onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
				aria-label="بازگشت به بالای صفحه"
			>
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
			</button>
		</div>

		<!-- 5. Copyright Bar & Social Icons matching Demo 43 (Container 388eea2c) -->
		<div class="py-4 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-4">
			<p class="m-0 text-center md:text-right">
				<?php esc_html_e( 'کلیه حقوق این سایت متعلق به شرکت نام فروشگاه می‌باشد.', 'negar-shop' ); ?>
			</p>

			<!-- Social Icons in One Row -->
			<div class="flex items-center gap-3">
				<span class="text-3xs text-slate-400 font-bold"><?php esc_html_e( 'در شبکه‌های اجتماعی همراه ما باشید', 'negar-shop' ); ?></span>
				<div class="flex items-center gap-2">
					<a href="#" class="w-8 h-8 rounded-full bg-white border border-slate-200/70 text-slate-600 hover:text-emerald-500 flex items-center justify-center transition shadow-2xs" aria-label="واتساپ">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
					</a>
					<a href="#" class="w-8 h-8 rounded-full bg-white border border-slate-200/70 text-slate-600 hover:text-pink-500 flex items-center justify-center transition shadow-2xs" aria-label="اینستاگرام">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
					</a>
					<a href="#" class="w-8 h-8 rounded-full bg-white border border-slate-200/70 text-slate-600 hover:text-sky-500 flex items-center justify-center transition shadow-2xs" aria-label="تلگرام">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.941z"/></svg>
					</a>
				</div>
			</div>
		</div>

	</div>

</div>
