<?php
/**
 * Footer Content - Exact Match to Kaveh Demo 43 (#ag-theme-builder-footer)
 *
 * @package Negar_Shop
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$demo_base  = $upload_dir['baseurl'] . '/kaveh-demo';
?>

<div class="negar-footer-wrapper bg-[#f8fafc] text-slate-700 pt-6 pb-2 border-t border-slate-200/80">
	
	<!-- 1. Top Decorative Wave Vector Image -->
	<div class="w-full overflow-hidden mb-8 leading-none opacity-80">
		<img src="<?php echo esc_url( $demo_base . '/footer-wave-top.png' ); ?>" alt="" class="w-full h-auto object-cover max-h-12" loading="lazy">
	</div>

	<div class="container max-w-[1320px] mx-auto px-4">
		
		<!-- 2. Main 4-Column Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-slate-200/70">
			
			<!-- Column 1: Fast Access Links (3 Cols) -->
			<div class="lg:col-span-3">
				<div class="flex items-center gap-2 mb-5">
					<svg width="24" height="6" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'دسترسی سریع', 'negar-shop' ); ?></h3>
				</div>
				<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">دوره‌های آموزشی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">اتاق خبر و وبلاگ</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فروشنده شوید</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">فرصت‌های شغلی</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">تماس با پشتیبانی</a></li>
				</ul>
			</div>

			<!-- Column 2: Customer Services (3 Cols) -->
			<div class="lg:col-span-3">
				<div class="flex items-center gap-2 mb-5">
					<svg width="24" height="6" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'خدمات مشتریان', 'negar-shop' ); ?></h3>
				</div>
				<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">پاسخ به پرسش‌های متداول</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">رویه‌های بازگرداندن کالا</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">شرایط و قوانین استفاده</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">حریم خصوصی کاربران</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors">گزارش خطا یا باگ فنی</a></li>
				</ul>
			</div>

			<!-- Column 3: About Shop Box (3 Cols) -->
			<div class="lg:col-span-3 bg-white p-5 rounded-3xl border border-slate-200/70 shadow-xs flex flex-col justify-between">
				<div>
					<div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center mb-3 shadow-md shadow-rose-600/30">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
					</div>
					<p class="text-2xs text-slate-500 leading-relaxed line-clamp-4 mb-4">
						<?php esc_html_e( 'فعالیت خود را از سال ۱۳۹۸ در زمینه فروش کالاهای دیجیتال و گجت‌های هوشمند آغاز نموده است. این مجموعه کالاها را بدون واسطه و با ضمانت اصالت به مشتریان گرامی عرضه می‌دارد.', 'negar-shop' ); ?>
					</p>
				</div>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="inline-flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs py-2 px-4 rounded-xl transition shadow-xs">
					<span><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></span>
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</a>
			</div>

			<!-- Column 4: Trust Badges (Enamad & Samandehi) (3 Cols) -->
			<div class="lg:col-span-3">
				<div class="flex items-center gap-2 mb-5">
					<svg width="24" height="6" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h3 class="text-base font-black text-[#212341] m-0"><?php esc_html_e( 'چگونه به ما اعتماد کنید', 'negar-shop' ); ?></h3>
				</div>
				<div class="grid grid-cols-2 gap-3 items-center">
					<div class="bg-white p-3 rounded-2xl border border-slate-200/70 flex items-center justify-center aspect-square shadow-2xs hover:shadow-md transition">
						<img src="<?php echo esc_url( $demo_base . '/enamad.png' ); ?>" alt="نماد اعتماد الکترونیکی" class="max-h-20 object-contain">
					</div>
					<div class="bg-white p-3 rounded-2xl border border-slate-200/70 flex items-center justify-center aspect-square shadow-2xs hover:shadow-md transition">
						<img src="<?php echo esc_url( $demo_base . '/samandehi.png' ); ?>" alt="ساماندهی پایگاه‌های اینترنتی" class="max-h-20 object-contain">
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
				<a href="mailto:info@Sitename.com" class="inline-flex items-center gap-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-bold text-xs px-3.5 py-1.5 rounded-xl transition">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
					<span>info@Sitename.com</span>
				</a>
			</div>

			<!-- Support Channels: Telegram, Instagram, WhatsApp, Phone (4 Cols) -->
			<div class="lg:col-span-4 grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
				
				<!-- Phone -->
				<div class="bg-white p-2.5 rounded-2xl border border-slate-200/60 shadow-2xs">
					<span class="text-3xs text-slate-400 block mb-0.5">پشتیبانی سریع</span>
					<span class="text-xs font-black text-rose-600 block dir-ltr">۰۲۱-۱۲۳۴۵۶۷۸</span>
				</div>

				<!-- Telegram -->
				<div class="bg-white p-2.5 rounded-2xl border border-slate-200/60 shadow-2xs">
					<span class="text-3xs text-slate-400 block mb-0.5">تلگرام</span>
					<span class="text-xs font-bold text-sky-600 block dir-ltr">moeinwp@</span>
				</div>

				<!-- Instagram -->
				<div class="bg-white p-2.5 rounded-2xl border border-slate-200/60 shadow-2xs">
					<span class="text-3xs text-slate-400 block mb-0.5">اینستاگرام</span>
					<span class="text-xs font-bold text-pink-600 block dir-ltr">moeinwp@</span>
				</div>

				<!-- WhatsApp -->
				<div class="bg-white p-2.5 rounded-2xl border border-slate-200/60 shadow-2xs">
					<span class="text-3xs text-slate-400 block mb-0.5">واتساپ</span>
					<span class="text-xs font-bold text-emerald-600 block dir-ltr">moeinwp@</span>
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

		<!-- 4. Bottom Decorative Wave Image -->
		<div class="w-full overflow-hidden my-4 leading-none opacity-80">
			<img src="<?php echo esc_url( $demo_base . '/footer-wave-bottom.png' ); ?>" alt="" class="w-full h-auto object-cover max-h-10" loading="lazy">
		</div>

		<!-- 5. Copyright Bar -->
		<div class="py-4 text-center text-xs text-slate-500 font-medium">
			<p class="m-0">
				<?php
				printf(
					/* translators: 1: Year, 2: Site name */
					esc_html__( 'تمامی حقوق مادی و معنوی این وب‌سایت متعلق به %2$s می‌باشد. © %1$s', 'negar-shop' ),
					esc_html( date_i18n( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
		</div>

	</div>

</div>
