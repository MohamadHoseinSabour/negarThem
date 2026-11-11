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
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$cart_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : home_url( '/' );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$contact_url = home_url( '/contact/' );
$about_url   = home_url( '/about/' );
$blog_page   = (int) get_option( 'page_for_posts' );
$blog_url    = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
$privacy_url = get_privacy_policy_url();
$footer_about = trim( wp_strip_all_tags( (string) negar_theme_setting( 'footer', 'about', '', 'negar_shop_footer_about' ) ) );
$site_description = trim( (string) get_bloginfo( 'description' ) );
$footer_about = $footer_about ? $footer_about : ( $site_description ? $site_description : __( 'برای مشاهده و مقایسه محصولات، از فروشگاه بازدید کنید.', 'negar-shop' ) );
$phone_hotline = trim( (string) negar_theme_setting( 'contact', 'phone', '', 'negar_shop_phone_hotline' ) );
$phone_hotline_href = function_exists( 'negar_shop_phone_href' ) ? negar_shop_phone_href( $phone_hotline ) : preg_replace( '/[^0-9+]/', '', $phone_hotline );
$copyright_text = trim( (string) negar_theme_setting( 'footer', 'copyright', '', 'negar_shop_copyright_text' ) );
$copyright_text = $copyright_text ? $copyright_text : __( 'تمامی حقوق این وب‌سایت محفوظ است.', 'negar-shop' );
$social_links = array_filter(
	array(
		'telegram'  => esc_url_raw( negar_theme_setting( 'social', 'telegram', '', 'negar_shop_telegram_url' ) ),
		'instagram' => esc_url_raw( negar_theme_setting( 'social', 'instagram', '', 'negar_shop_instagram_url' ) ),
		'whatsapp'  => esc_url_raw( negar_theme_setting( 'social', 'whatsapp', '', 'negar_shop_whatsapp_url' ) ),
	),
	static function ( $url ) {
		return ! empty( $url ) && ! in_array( untrailingslashit( $url ), array( 'https://instagram.com', 'https://t.me', 'https://wa.me' ), true );
	}
);
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
						<h3 class="text-sm font-black text-[#212341] m-0"><?php echo esc_html( negar_theme_text( 'footer', 'quick_links_heading' ) ); ?></h3>
					</div>
					<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'صفحه اصلی', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $shop_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'فروشگاه', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $blog_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'وبلاگ', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $about_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></a></li>
					</ul>
				</div>

				<!-- Sub-column 2: Customer Services -->
				<div class="w-1/2">
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-sm font-black text-[#212341] m-0"><?php echo esc_html( negar_theme_text( 'footer', 'customer_services_heading' ) ); ?></h3>
					</div>
					<ul class="space-y-2.5 text-xs text-slate-600 font-medium">
						<li><a href="<?php echo esc_url( $account_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $cart_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'سبد خرید', 'negar-shop' ); ?></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'پشتیبانی', 'negar-shop' ); ?></a></li>
						<?php if ( $privacy_url ) : ?>
							<li><a href="<?php echo esc_url( $privacy_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'حفظ حریم خصوصی', 'negar-shop' ); ?></a></li>
						<?php endif; ?>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" class="hover:text-rose-600 transition-colors"><?php esc_html_e( 'پرسش‌های متداول', 'negar-shop' ); ?></a></li>
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
					<?php echo esc_html( $footer_about ); ?>
				</p>

				<!-- White Pill Button "درباره ما" (No Arrow) -->
				<div class="negar-footer-about-btn-wrap">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="negar-footer-about-btn">
						<?php esc_html_e( 'درباره ما', 'negar-shop' ); ?>
					</a>
				</div>

			</div>

			<!-- Left Column: Verified contact entry points (25% on Desktop) -->
			<div class="w-full lg:w-[25%] flex flex-col justify-between">
				<div>
					<div class="flex items-center gap-2 mb-4">
						<svg width="28" height="7" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
						<h3 class="text-sm font-black text-[#212341] m-0"><?php echo esc_html( negar_theme_text( 'footer', 'contact_heading' ) ); ?></h3>
					</div>
					<div class="rounded-2xl border border-slate-200/80 bg-slate-50 p-4 text-xs text-slate-600 leading-relaxed">
						<?php echo esc_html( negar_theme_text( 'footer', 'contact_description' ) ); ?>
					</div>
				</div>

				<div class="grid grid-cols-1 gap-2">
					<?php if ( $phone_hotline ) : ?>
						<a href="tel:<?php echo esc_attr( $phone_hotline_href ); ?>" class="bg-white p-2.5 rounded-2xl border border-slate-200/70 text-center shadow-2xs hover:border-rose-300 transition">
							<span class="text-3xs text-slate-400 block mb-0.5 font-medium"><?php esc_html_e( 'پشتیبانی تلفنی', 'negar-shop' ); ?></span>
							<span class="text-xs font-black text-rose-600 block dir-ltr"><?php echo esc_html( $phone_hotline ); ?></span>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( $contact_url ); ?>" class="bg-white p-2.5 rounded-2xl border border-slate-200/70 text-center shadow-2xs hover:border-rose-300 transition text-xs font-black text-rose-600">
							<?php echo esc_html( negar_theme_text( 'footer', 'support_cta' ) ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

		</div>

		<!-- 3. Verified contact and social entry points -->
		<div class="py-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 items-center">
			<div class="lg:col-span-4">
				<div class="flex items-center gap-2 mb-2">
					<svg width="20" height="5" viewBox="0 0 34 8" fill="none"><path d="M33.1839 0H0V8.0099H33.1839V0Z" fill="#E90544"></path></svg>
					<h4 class="text-sm font-black text-[#212341] m-0"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></h4>
				</div>
				<p class="text-2xs text-slate-500 leading-relaxed mb-3"><?php esc_html_e( 'برای ارسال پیام یا دریافت راهنمایی، از فرم تماس با فروشگاه استفاده کنید.', 'negar-shop' ); ?></p>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center gap-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition">
					<span><?php esc_html_e( 'صفحه تماس با ما', 'negar-shop' ); ?></span>
				</a>
			</div>

			<div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-3 gap-2 text-center">
				<?php if ( $social_links ) : ?>
					<?php foreach ( $social_links as $network => $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs hover:border-rose-300 transition">
							<span class="text-3xs text-slate-500 font-bold block mb-1"><?php echo esc_html( ucfirst( $network ) ); ?></span>
							<span class="text-xs font-black text-slate-700 block dir-ltr"><?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ); ?></span>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="sm:col-span-3 bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs text-xs text-slate-500"><?php esc_html_e( 'نشانی شبکه‌های اجتماعی هنوز ثبت نشده است.', 'negar-shop' ); ?></div>
				<?php endif; ?>
			</div>

			<div class="lg:col-span-4 grid grid-cols-2 gap-2">
				<a href="<?php echo esc_url( $shop_url ); ?>" class="block bg-white p-3 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs text-center text-xs font-bold text-slate-700"><?php esc_html_e( 'مشاهده محصولات', 'negar-shop' ); ?></a>
				<a href="<?php echo esc_url( $account_url ); ?>" class="block bg-white p-3 rounded-xl border border-slate-200/70 hover:border-slate-300 transition shadow-2xs text-center text-xs font-bold text-slate-700"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></a>
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

		<!-- 5. Copyright and configured social links -->
		<div class="pt-2 pb-1.5 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-3">
			<p class="m-0 text-center md:text-right"><?php echo esc_html( $copyright_text ); ?></p>

			<?php if ( $social_links ) : ?>
				<div class="flex items-center gap-3">
					<span class="text-3xs text-slate-400 font-bold"><?php echo esc_html( negar_theme_text( 'footer', 'social_prompt' ) ); ?></span>
					<div class="flex items-center gap-2">
						<?php foreach ( $social_links as $network => $url ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" class="px-3 py-1.5 rounded-full bg-white border border-slate-200/70 text-slate-600 hover:text-rose-600 transition shadow-2xs" aria-label="<?php echo esc_attr( $network ); ?>"><?php echo esc_html( ucfirst( $network ) ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>

</div>
