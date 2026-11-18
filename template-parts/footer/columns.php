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
$theme_images_dir = get_template_directory() . '/assets/images';
$theme_images_uri = get_template_directory_uri() . '/assets/images';
$enamad_file      = $theme_images_dir . '/trust-enamad.png';
$zibal_file       = $theme_images_dir . '/trust-zibal.png';
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
$enamad_url     = esc_url_raw( (string) negar_theme_setting( 'footer', 'enamad_url', '' ) );
$zibal_url      = esc_url_raw( (string) negar_theme_setting( 'footer', 'zibal_url', '' ) );
$trust_badges   = array(
	'enamad' => array(
		'label' => __( 'نماد اعتماد الکترونیک', 'negar-shop' ),
		'image' => $theme_images_uri . '/trust-enamad.png' . ( file_exists( $enamad_file ) ? '?ver=' . filemtime( $enamad_file ) : '' ),
		'url'   => $enamad_url,
	),
	'zibal'  => array(
		'label' => __( 'نماد درگاه پرداخت زیبال', 'negar-shop' ),
		'image' => $theme_images_uri . '/trust-zibal.png' . ( file_exists( $zibal_file ) ? '?ver=' . filemtime( $zibal_file ) : '' ),
		'url'   => $zibal_url,
	),
);
$social_links = array_filter(
	array(
		'instagram' => esc_url_raw( negar_theme_setting( 'social', 'instagram', '', 'negar_shop_instagram_url' ) ),
		'telegram'  => esc_url_raw( negar_theme_setting( 'social', 'telegram', '', 'negar_shop_telegram_url' ) ),
		'whatsapp'  => esc_url_raw( negar_theme_setting( 'social', 'whatsapp', '', 'negar_shop_whatsapp_url' ) ),
		'linkedin'  => esc_url_raw( negar_theme_setting( 'social', 'linkedin', '', 'negar_shop_linkedin_url' ) ),
	),
	static function ( $url ) {
		return ! empty( $url ) && ! in_array( untrailingslashit( $url ), array( 'https://instagram.com', 'https://t.me', 'https://wa.me', 'https://linkedin.com' ), true );
	}
);
$social_meta = array(
	'instagram' => array(
		'label' => __( 'اینستاگرام', 'negar-shop' ),
		'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
	),
	'telegram'  => array(
		'label' => __( 'تلگرام', 'negar-shop' ),
		'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M20.665 3.717l-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42 10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785l3.019-14.228c.309-1.239-.473-1.8-1.282-1.434z"/></svg>',
	),
	'whatsapp'  => array(
		'label' => __( 'واتس‌اپ', 'negar-shop' ),
		'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12.031 2c-5.516 0-9.969 4.453-9.969 9.969 0 1.766.469 3.484 1.344 5l-1.406 5.141 5.266-1.375c1.469.797 3.109 1.219 4.766 1.219 5.516 0 9.969-4.453 9.969-9.984S17.547 2 12.031 2zm5.828 14.109c-.25.703-1.438 1.344-2 1.422-.516.078-1.172.109-1.891-.125-.438-.141-1-.328-1.719-.641-3.031-1.313-5-4.375-5.156-4.578-.156-.203-1.234-1.641-1.234-3.125s.781-2.219 1.063-2.516c.281-.297.609-.375.813-.375.203 0 .406 0 .578.016.188.016.438-.078.688.531.25.609.859 2.094.938 2.25.078.156.125.344.031.547-.094.203-.141.328-.281.5-.141.172-.297.391-.422.516-.141.141-.297.297-.125.594.172.297.766 1.266 1.641 2.047 1.125 1 2.078 1.313 2.375 1.469.297.156.469.125.641-.078.172-.203.734-.859.938-1.156.203-.297.391-.25.656-.156.266.094 1.703.813 2 .953.297.141.484.219.563.344.078.125.078.734-.172 1.438z"/></svg>',
	),
	'linkedin'  => array(
		'label' => __( 'لینکدین', 'negar-shop' ),
		'icon'  => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>',
	),
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
							<span class="text-3xs text-slate-400 block mb-0.5 font-medium"><?php echo esc_html( negar_theme_text( 'footer', 'phone_support_label' ) ); ?></span>
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
					<h4 class="text-sm font-black text-[#212341] m-0"><?php echo esc_html( negar_theme_text( 'footer', 'contact_card_heading' ) ); ?></h4>
				</div>
				<p class="text-2xs text-slate-500 leading-relaxed mb-3"><?php echo esc_html( negar_theme_text( 'footer', 'contact_card_description' ) ); ?></p>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center gap-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-bold text-xs px-4 py-2 rounded-xl transition">
					<span><?php echo esc_html( negar_theme_text( 'footer', 'contact_card_action' ) ); ?></span>
				</a>
			</div>

			<div class="lg:col-span-4 flex items-center justify-center">
				<?php if ( $social_links ) : ?>
					<div class="negar-footer-social-channels" aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی', 'negar-shop' ); ?>">
						<?php foreach ( $social_links as $network => $url ) : ?>
							<?php
							$meta  = isset( $social_meta[ $network ] ) ? $social_meta[ $network ] : array();
							$label = isset( $meta['label'] ) ? $meta['label'] : ucfirst( $network );
							$icon  = isset( $meta['icon'] ) ? $meta['icon'] : '';
							?>
							<a
								href="<?php echo esc_url( $url ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="negar-footer-social-btn negar-footer-social-btn--<?php echo esc_attr( $network ); ?>"
								aria-label="<?php echo esc_attr( $label ); ?>"
								title="<?php echo esc_attr( $label ); ?>"
								data-tooltip="<?php echo esc_attr( $label ); ?>"
							>
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static trusted SVG icon markup. ?>
								<span class="sr-only"><?php echo esc_html( $label ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="w-full bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs text-xs text-slate-500 text-center"><?php echo esc_html( negar_theme_text( 'footer', 'social_empty_notice' ) ); ?></div>
				<?php endif; ?>
			</div>

			<div class="lg:col-span-4 negar-footer-trust-actions">
				<div class="negar-footer-quick-icons" aria-label="<?php esc_attr_e( 'دسترسی سریع فروشگاه و حساب کاربری', 'negar-shop' ); ?>">
					<a
						href="<?php echo esc_url( $shop_url ); ?>"
						class="negar-footer-quick-icon negar-footer-quick-icon--shop"
						aria-label="<?php esc_attr_e( 'مشاهده محصولات', 'negar-shop' ); ?>"
						title="<?php esc_attr_e( 'مشاهده محصولات', 'negar-shop' ); ?>"
						data-tooltip="<?php esc_attr_e( 'مشاهده محصولات', 'negar-shop' ); ?>"
					>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
							<line x1="3" y1="6" x2="21" y2="6"></line>
							<path d="M16 10a4 4 0 0 1-8 0"></path>
						</svg>
						<span class="sr-only"><?php esc_html_e( 'مشاهده محصولات', 'negar-shop' ); ?></span>
					</a>
					<a
						href="<?php echo esc_url( $account_url ); ?>"
						class="negar-footer-quick-icon negar-footer-quick-icon--account"
						aria-label="<?php esc_attr_e( 'حساب کاربری', 'negar-shop' ); ?>"
						title="<?php esc_attr_e( 'حساب کاربری', 'negar-shop' ); ?>"
						data-tooltip="<?php esc_attr_e( 'حساب کاربری', 'negar-shop' ); ?>"
					>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
						</svg>
						<span class="sr-only"><?php esc_html_e( 'حساب کاربری', 'negar-shop' ); ?></span>
					</a>
				</div>

				<span class="negar-footer-trust-divider" aria-hidden="true"></span>

				<div class="negar-footer-trust-badges" aria-label="<?php esc_attr_e( 'نمادهای اعتماد و درگاه پرداخت', 'negar-shop' ); ?>">
					<?php foreach ( $trust_badges as $badge_key => $badge ) : ?>
						<?php if ( ! empty( $badge['url'] ) ) : ?>
							<a
								href="<?php echo esc_url( $badge['url'] ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="negar-footer-trust-badge negar-footer-trust-badge--<?php echo esc_attr( $badge_key ); ?> is-linked"
								aria-label="<?php echo esc_attr( $badge['label'] ); ?>"
								title="<?php echo esc_attr( $badge['label'] ); ?>"
								data-tooltip="<?php echo esc_attr( $badge['label'] ); ?>"
							>
								<img src="<?php echo esc_url( $badge['image'] ); ?>" alt="<?php echo esc_attr( $badge['label'] ); ?>" loading="lazy" decoding="async">
							</a>
						<?php else : ?>
							<div
								class="negar-footer-trust-badge negar-footer-trust-badge--<?php echo esc_attr( $badge_key ); ?>"
								title="<?php echo esc_attr( $badge['label'] ); ?>"
								data-tooltip="<?php echo esc_attr( $badge['label'] ); ?>"
							>
								<img src="<?php echo esc_url( $badge['image'] ); ?>" alt="<?php echo esc_attr( $badge['label'] ); ?>" loading="lazy" decoding="async">
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
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

		<!-- 5. Copyright and configured social links -->
		<div class="pt-2 pb-1.5 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium gap-3">
			<p class="m-0 text-center md:text-right"><?php echo esc_html( $copyright_text ); ?></p>

			<?php if ( $social_links ) : ?>
				<div class="flex items-center gap-3">
					<span class="text-3xs text-slate-400 font-bold"><?php echo esc_html( negar_theme_text( 'footer', 'social_prompt' ) ); ?></span>
					<div class="negar-footer-social-mini-list">
						<?php foreach ( $social_links as $network => $url ) : ?>
							<?php
							$meta  = isset( $social_meta[ $network ] ) ? $social_meta[ $network ] : array();
							$label = isset( $meta['label'] ) ? $meta['label'] : ucfirst( $network );
							$icon  = isset( $meta['icon'] ) ? $meta['icon'] : '';
							?>
							<a
								href="<?php echo esc_url( $url ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="negar-footer-social-mini negar-footer-social-mini--<?php echo esc_attr( $network ); ?>"
								aria-label="<?php echo esc_attr( $label ); ?>"
								title="<?php echo esc_attr( $label ); ?>"
							>
								<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static trusted SVG icon markup. ?>
								<span class="sr-only"><?php echo esc_html( $label ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>

</div>
