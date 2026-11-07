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

<div class="negar-footer-wrapper bg-white text-slate-700 pt-0 pb-0 relative">
	
	<!-- 1. Top Decorative Wave Vector Image matching Group-427321238.png -->
	<div class="w-full overflow-hidden mb-6 leading-none select-none">
		<img src="<?php echo esc_url( $demo_base . '/footer-wave-top.png' ); ?>" alt="" class="w-full h-auto object-cover max-h-12" loading="lazy">
	</div>

	<div class="container max-w-[1320px] mx-auto px-4">
		
		<!-- 2. Main Footer Section: Links (25%) + About Card (47%) + Trust Badges (25%) matching Demo 43 -->
		<div class="flex flex-col lg:flex-row items-stretch justify-between gap-6 pb-6">
			
			<!-- Links Column: Fast Access & Customer Services (25% on Desktop) -->
			<div class="w-full lg:w-[25%] flex flex-row gap-6 justify-between">
				<!-- Sub-column 1: Fast Access -->
				<div class="w-1/2">
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-sm font-black text-[#212341] m-0"><?php esc_html_e( 'دسترسی سریع', 'negar-shop' ); ?></h3>
					</div>
					<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">دوره‌های آموزشی</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">اتاق خبر دیجی‌کالا</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فروش در دیجی‌کالا</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فرصت‌های شغلی</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">تماس با دیجی‌کالا</a></li>
					</ul>
				</div>

				<!-- Sub-column 2: Customer Services -->
				<div class="w-1/2">
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-sm font-black text-[#212341] m-0"><?php esc_html_e( 'خدمات مشتریان', 'negar-shop' ); ?></h3>
					</div>
					<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">دوره‌های آموزشی</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">اتاق خبر و وبلاگ</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">قوانین و رویه‌های مرجوعی</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">حفظ حریم خصوصی</a></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">پاسخ به سوالات متداول</a></li>
					</ul>
				</div>
			</div>

			<!-- Center Column: About Us Card (47% on Desktop - Exact 1:1 Match to Demo 43 Container a9752bd) -->
			<div class="w-full lg:w-[47%] flex flex-col justify-between negar-footer-about-card" style="background-image: url('<?php echo esc_url( $demo_base . '/Rectangle-259.png' ); ?>');">
				
				<!-- Top SVG Icon inside the Notch Cutout -->
				<div class="negar-footer-about-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="62" height="62" viewBox="0 0 65 65" fill="none">
						<rect width="64.4178" height="64.4178" rx="20" fill="#D4242E"></rect>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M26.0975 12.4701H38.0472C45.7091 12.4701 52.0002 18.5003 52.0002 25.8444V37.2985C52.0002 44.6426 45.7091 50.6728 38.0472 50.6728H26.0975C18.4356 50.6728 12.1445 44.6426 12.1445 37.2985V25.8444C12.1445 18.5003 18.4005 12.4701 26.0975 12.4701Z" fill="url(#paint0_linear_about)"></path>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M37.4137 48.6836H26.6941C19.8055 48.6836 14.1821 43.2934 14.1821 36.6905V26.4492C14.1821 19.8463 19.8055 14.4561 26.6941 14.4561H37.4137C44.3024 14.4561 49.9258 19.8463 49.9258 26.4492V36.7242C49.9258 43.2934 44.3024 48.6836 37.4137 48.6836Z" fill="url(#paint1_linear_about)"></path>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M33.7592 39.1537H36.0788C38.293 39.1537 40.0855 37.4356 40.0855 35.3132C40.0855 33.1908 38.293 31.4727 36.0788 31.4727H25.3592C24.9023 31.4727 24.4806 31.2369 24.2697 30.8327C24.0588 30.4284 24.094 29.9904 24.3751 29.6199L26.8705 26.4868C27.1165 26.1836 27.4329 26.0489 27.8195 26.0489H36.0437C41.3859 26.0489 45.744 30.2263 45.744 35.3469C45.744 40.3665 41.5265 44.5102 36.2897 44.6449C36.2546 44.6449 36.2194 44.6449 36.2194 44.6449H36.0788H33.7592H30.2446H27.9249H27.7843C27.7492 44.6449 27.714 44.6449 27.6789 44.6449C22.9342 44.5439 18.9626 41.1076 18.33 36.7281C18.2246 36.1217 18.5057 35.3806 19.3844 35.3469H22.7584C23.4262 35.3806 23.848 35.7175 24.0237 36.3576V36.3913C24.5157 38.0083 26.0622 39.1874 27.8898 39.1874H30.2094L33.7592 39.1537ZM44.2327 20.4229L41.7374 23.5559C41.4913 23.8591 41.175 23.9939 40.7884 23.9939H25.3944C24.9375 23.9939 24.5157 23.7581 24.3049 23.3538C24.094 22.9495 24.1291 22.5116 24.4103 22.141L26.9057 19.008C27.1517 18.7048 27.468 18.57 27.8546 18.57H43.2486C43.7055 18.57 44.1273 18.8059 44.3382 19.2101C44.549 19.6144 44.5139 20.086 44.2327 20.4229Z" fill="white"></path>
						<defs>
							<linearGradient id="paint0_linear_about" x1="48.104" y1="55.9417" x2="28.0208" y2="22.7134" gradientUnits="userSpaceOnUse">
								<stop stop-color="#680B00"></stop>
								<stop offset="0.1025" stop-color="#7B1000"></stop>
								<stop offset="0.3166" stop-color="#9F1806"></stop>
								<stop offset="0.5201" stop-color="#BD1D0B"></stop>
								<stop offset="0.7076" stop-color="#D2210E"></stop>
								<stop offset="0.8733" stop-color="#E0230E"></stop>
								<stop offset="1" stop-color="#E4240E"></stop>
							</linearGradient>
							<linearGradient id="paint1_linear_about" x1="17.6938" y1="9.74041" x2="35.6932" y2="39.521" gradientUnits="userSpaceOnUse">
								<stop stop-color="#680B00"></stop>
								<stop offset="0.1025" stop-color="#7B1000"></stop>
								<stop offset="0.3166" stop-color="#9F1806"></stop>
								<stop offset="0.5201" stop-color="#BD1D0B"></stop>
								<stop offset="0.7076" stop-color="#D2210E"></stop>
								<stop offset="0.8733" stop-color="#E0230E"></stop>
								<stop offset="1" stop-color="#E4240E"></stop>
							</linearGradient>
						</defs>
					</svg>
				</div>

				<!-- Persian Body Text -->
				<p class="negar-footer-about-text">
					<?php esc_html_e( 'در زمینه فروش کیف پول های سخت افزاری شروع نموده۱۳۹۸فعالیت خود را از سال است . این مجموعه کالا های خود را بدون واسطه و از طریق شرکت های اصلی سازنده کیف پول های سخت افزاری به صورت مستقیم تامین می کند و همچنین ضمانت اصالت و سلامت کالا را به تمام مشتریان ارائه می دهد. هدف اصلی ما در و از طریق شرکت های اصلی سازنده کیف پول های سخت افزاری به صورت مستقیم تامین می کند', 'negar-shop' ); ?>
				</p>

				<!-- White Pill Button "درباره ما" (No Arrow) -->
				<div class="negar-footer-about-btn-wrap">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="negar-footer-about-btn">
						<?php esc_html_e( 'درباره ما', 'negar-shop' ); ?>
					</a>
				</div>

			</div>

			<!-- Left Column: Trust Badges (Enamad & Samandehi) & Dual Phone Boxes (25% on Desktop) -->
			<div class="w-full lg:w-[25%] flex flex-col justify-between">
				<div>
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-sm font-black text-[#212341] m-0"><?php esc_html_e( 'چگونه به ما اعتماد کنید', 'negar-shop' ); ?></h3>
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
		<div class="py-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 items-center">
			
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
		<div class="relative w-full mt-4 mb-1 select-none">
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
		<div class="pt-2 pb-1.5 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-3">
			<p class="m-0 text-center md:text-right">
				<a href="https://mohamadhoseinsabour.github.io/myResume/" target="_blank" rel="noopener noreferrer" class="text-slate-600 hover:text-rose-600 font-bold transition-colors">
					<?php esc_html_e( 'طراحی و اجرا توسط محمدحسین صبور', 'negar-shop' ); ?>
				</a>
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
