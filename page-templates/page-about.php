<?php
/**
 * Template Name: About Us / درباره ما
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="negar-about-page py-10">
	<div class="container mx-auto px-4 max-w-5xl">

		<!-- Hero Section -->
		<section class="about-hero text-center mb-16">
			<span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3.5 py-1.5 rounded-full mb-4">
				<?php esc_html_e( 'داستان نگار شاپ', 'negar-shop' ); ?>
			</span>
			<h1 class="text-2xl sm:text-4xl font-black text-dark leading-tight mb-6 max-w-2xl mx-auto">
				<?php esc_html_e( 'تعهد ما: تجربه خریدی بی‌دغدغه و مطمئن برای خانه شما', 'negar-shop' ); ?>
			</h1>
			<p class="text-sm sm:text-base text-muted leading-relaxed max-w-3xl mx-auto">
				<?php esc_html_e( 'نگار شاپ با بیش از یک دهه تجربه در حوزه واردات و توزیع جدیدترین لوازم خانگی هوشمند، همواره کوشیده است تا کالای اصل را با بهترین قیمت و ضمانت‌های معتبر به دست مصرف‌کننده ایرانی برساند.', 'negar-shop' ); ?>
			</p>
		</section>

		<!-- 4 Key Metrics Ribbon -->
		<section class="about-metrics grid grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
			<div class="metric-card bg-surface border border-border rounded-2xl p-6 text-center shadow-xs">
				<span class="text-3xl sm:text-4xl font-black text-primary font-mono block mb-1">۱۰+</span>
				<span class="text-xs sm:text-sm font-bold text-dark block"><?php esc_html_e( 'سال سابقه تخصصی', 'negar-shop' ); ?></span>
			</div>
			<div class="metric-card bg-surface border border-border rounded-2xl p-6 text-center shadow-xs">
				<span class="text-3xl sm:text-4xl font-black text-primary font-mono block mb-1">۵۰,۰۰۰+</span>
				<span class="text-xs sm:text-sm font-bold text-dark block"><?php esc_html_e( 'سفارش موفق در کشور', 'negar-shop' ); ?></span>
			</div>
			<div class="metric-card bg-surface border border-border rounded-2xl p-6 text-center shadow-xs">
				<span class="text-3xl sm:text-4xl font-black text-primary font-mono block mb-1">۱۰۰٪</span>
				<span class="text-xs sm:text-sm font-bold text-dark block"><?php esc_html_e( 'ضمانت اصالت و سلامت', 'negar-shop' ); ?></span>
			</div>
			<div class="metric-card bg-surface border border-border rounded-2xl p-6 text-center shadow-xs">
				<span class="text-3xl sm:text-4xl font-black text-primary font-mono block mb-1">۲۴/۷</span>
				<span class="text-xs sm:text-sm font-bold text-dark block"><?php esc_html_e( 'پشتیبانی و مشاوره', 'negar-shop' ); ?></span>
			</div>
		</section>

		<!-- Values Grid -->
		<section class="about-values mb-16">
			<div class="text-center mb-10">
				<h2 class="text-xl sm:text-2xl font-black text-dark mb-2"><?php esc_html_e( 'ارزش‌های کلیدی نگار شاپ', 'negar-shop' ); ?></h2>
				<p class="text-xs sm:text-sm text-muted"><?php esc_html_e( 'چرا هزاران خانواده ایرانی نگار شاپ را برای تجهیز خانه انتخاب کرده‌اند؟', 'negar-shop' ); ?></p>
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
				
				<div class="value-card bg-surface border border-border rounded-2xl p-6 shadow-xs flex items-start gap-4">
					<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
					</div>
					<div>
						<h3 class="text-base font-bold text-dark mb-2"><?php esc_html_e( 'تضمین صددرصدی اصالت کالا', 'negar-shop' ); ?></h3>
						<p class="text-xs text-muted leading-relaxed"><?php esc_html_e( 'کلیه کالاهای موجود در فروشگاه به صورت مستقیم و با برگه‌های معتبر بازرگانی و گارانتی شرکتی عرضه می‌شوند.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="value-card bg-surface border border-border rounded-2xl p-6 shadow-xs flex items-start gap-4">
					<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
					</div>
					<div>
						<h3 class="text-base font-bold text-dark mb-2"><?php esc_html_e( 'شفافیت و بهترین قیمت بازار', 'negar-shop' ); ?></h3>
						<p class="text-xs text-muted leading-relaxed"><?php esc_html_e( 'حذف واسطه‌های غیرضروری به ما این امکان را می‌دهد تا مناسب‌ترین قیمت‌ها را به خریداران عزیز ارائه دهیم.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="value-card bg-surface border border-border rounded-2xl p-6 shadow-xs flex items-start gap-4">
					<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4c0 .6-.4 1-1 1h-2"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>
					</div>
					<div>
						<h3 class="text-base font-bold text-dark mb-2"><?php esc_html_e( 'ارسال سریع با بسته‌بندی ویژه', 'negar-shop' ); ?></h3>
						<p class="text-xs text-muted leading-relaxed"><?php esc_html_e( 'لوازم خانگی حساس با ایمن‌ترین ناوگان پستی و بسته‌بندی مقاوم ضدضربه ارسال می‌شوند.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="value-card bg-surface border border-border rounded-2xl p-6 shadow-xs flex items-start gap-4">
					<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
					</div>
					<div>
						<h3 class="text-base font-bold text-dark mb-2"><?php esc_html_e( 'همراهی و مشاوره رایگان', 'negar-shop' ); ?></h3>
						<p class="text-xs text-muted leading-relaxed"><?php esc_html_e( 'کارشناسان فروش ما قبل از خرید آماده پاسخگویی و راهنمایی شما برای انتخاب بهترین کالا هستند.', 'negar-shop' ); ?></p>
					</div>
				</div>

			</div>
		</section>

		<!-- CTA Banner -->
		<section class="about-cta rounded-2xl bg-gradient-to-r from-primary to-blue-700 text-white p-8 sm:p-12 text-center shadow-lg">
			<h2 class="text-xl sm:text-3xl font-black mb-3"><?php esc_html_e( 'همین حالا خرید از نگار شاپ را تجربه کنید', 'negar-shop' ); ?></h2>
			<p class="text-xs sm:text-sm text-blue-100 max-w-xl mx-auto mb-6 leading-relaxed"><?php esc_html_e( 'تنوع بی‌نظیری از برندهای مطرح جهانی شامل بوش، سامسونگ، ال‌جی، فیلیپس و اسنوا با ارسال رایگان در انتظار شماست.', 'negar-shop' ); ?></p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn bg-white text-primary hover:bg-slate-100 font-bold py-3 px-8 text-sm rounded-xl shadow-md transition-all inline-flex items-center gap-2">
				<span><?php esc_html_e( 'مشاهده کاتالوگ فروشگاه', 'negar-shop' ); ?></span>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
			</a>
		</section>

	</div>
</div>

<?php
get_footer();
