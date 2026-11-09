<?php
/**
 * Template Name: About Us / درباره ما
 *
 * Polished editorial design aligned with Kaveh Demo 43 brand identity.
 *
 * @package Negar_Shop
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main negar-content-page">
	<div class="container">

		<!-- Breadcrumb -->
		<nav class="negar-page-breadcrumb" aria-label="<?php esc_attr_e( 'مسیر راهنما', 'negar-shop' ); ?>">
			<ol>
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
							<polyline points="9 22 9 12 15 12 15 22"></polyline>
						</svg>
						<span><?php esc_html_e( 'خانه', 'negar-shop' ); ?></span>
					</a>
				</li>
				<li class="separator">/</li>
				<li class="current" aria-current="page"><?php esc_html_e( 'درباره ما', 'negar-shop' ); ?></li>
			</ol>
		</nav>

		<!-- Hero Section -->
		<section class="negar-editorial-hero text-center">
			<span class="negar-hero-badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
				</svg>
				<span><?php esc_html_e( 'داستان و ارزش‌های ما', 'negar-shop' ); ?></span>
			</span>
			<h1 class="negar-hero-title f-iran-yekan">
				<?php esc_html_e( 'تجربه خریدی مطمئن، سریع و اصیل', 'negar-shop' ); ?>
			</h1>
			<p class="negar-hero-lead f-payda">
				<?php esc_html_e( 'ما در نگارشاپ با هدف گردآوری جدیدترین تجهیزات دیجیتال، لوازم جانبی معتبر و بازی‌های روز جهان با تضمین قطعی اصالت و مناسب‌ترین قیمت فعالیت می‌کنیم. هدف ما ارائه تجربه‌ای شفاف، مشاوره صادقانه و تحویل سریع در سراسر کشور است.', 'negar-shop' ); ?>
			</p>
		</section>

		<!-- 4 Value Pillars -->
		<section class="mb-5">
			<div class="row g-3 g-md-4">
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card border-0 negar-pillar-card">
						<div class="negar-pillar-icon">
							<i class="icon-security-3"></i>
						</div>
						<h3 class="negar-pillar-title f-iran-yekan"><?php esc_html_e( 'تضمین اصالت کالا', 'negar-shop' ); ?></h3>
						<p class="negar-pillar-desc f-payda"><?php esc_html_e( 'تمامی کالاها با گارانتی اصلی شرکتی، سریال معتبر و پلمب کارخانه به دست شما می‌رسد.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card border-0 negar-pillar-card">
						<div class="negar-pillar-icon">
							<i class="icon-energy"></i>
						</div>
						<h3 class="negar-pillar-title f-iran-yekan"><?php esc_html_e( 'ارسال سریع اکسپرس', 'negar-shop' ); ?></h3>
						<p class="negar-pillar-desc f-payda"><?php esc_html_e( 'بسته‌بندی تخصصی ایمن و ضدضربه با ارسال فوری و رهگیری آنلاین مرسوله پستی.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card border-0 negar-pillar-card">
						<div class="negar-pillar-icon">
							<i class="icon-headphone-2"></i>
						</div>
						<h3 class="negar-pillar-title f-iran-yekan"><?php esc_html_e( 'پشتیبانی تخصصی', 'negar-shop' ); ?></h3>
						<p class="negar-pillar-desc f-payda"><?php esc_html_e( 'مشاوره خرید، راهنمای راه‌اندازی و همراهی گام‌به‌گام کارشناسان در ساعات کاری.', 'negar-shop' ); ?></p>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card border-0 negar-pillar-card">
						<div class="negar-pillar-icon">
							<i class="icon-backward"></i>
						</div>
						<h3 class="negar-pillar-title f-iran-yekan"><?php esc_html_e( '۷ روز ضمانت بازگشت', 'negar-shop' ); ?></h3>
						<p class="negar-pillar-desc f-payda"><?php esc_html_e( 'آسودگی خاطر خریداران با امکان ارجاع در صورت مغایرت مشخصات یا نقص فنی اولیه.', 'negar-shop' ); ?></p>
					</div>
				</div>
			</div>
		</section>

		<!-- Brand Story Narrative Box -->
		<section class="negar-story-box">
			<div class="row align-items-center g-4">
				<div class="col-12 col-lg-7">
					<h2 class="negar-story-heading f-iran-yekan">
						<i class="icon-lamp-charge"></i>
						<span><?php esc_html_e( 'مسیر ما در ارائه خدمات تخصصی دیجیتال', 'negar-shop' ); ?></span>
					</h2>
					<p class="negar-story-text f-payda">
						<?php esc_html_e( 'در دنیای پرشتاب فناوری، دسترسی به تجهیزات اورجینال و خدمات پس از فروش مطمئن حق هر خریداری است. نگارشاپ با اتکا به دانش فنی و شبکه تأمین رسمی، تلاش می‌کند تا فاصله‌ی میان معرفی جدیدترین محصولات جهانی تا رسیدن آن‌ها به دست مشتریان در سراسر ایران را به حداقل برساند.', 'negar-shop' ); ?>
					</p>
					<p class="negar-story-text f-payda mb-0">
						<?php esc_html_e( 'ما معتقدیم اعتماد بزرگ‌ترین سرمایه یک فروشگاه است؛ به همین دلیل کنترل کیفیت دقیق، پاسخگویی مسئولانه و قیمت‌گذاری منصفانه را هسته اصلی تعهد خود به خریداران قرار داده‌ایم.', 'negar-shop' ); ?>
					</p>
				</div>
				<div class="col-12 col-lg-5">
					<div class="p-4 rounded-4 bg-slate-50 border border-slate-200">
						<div class="d-flex align-items-center gap-3 mb-3">
							<div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
								<i class="icon-tick-circle fs-5"></i>
							</div>
							<div>
								<h4 class="fs-6 fw-bold text-dark mb-0 f-iran-yekan"><?php esc_html_e( 'مجوزها و اصالت رسمی', 'negar-shop' ); ?></h4>
								<span class="text-muted small"><?php esc_html_e( 'دارای نماد اعتماد الکترونیکی و ساماندهی', 'negar-shop' ); ?></span>
							</div>
						</div>
						<div class="d-flex align-items-center gap-3">
							<div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
								<i class="icon-box-time fs-5"></i>
							</div>
							<div>
								<h4 class="fs-6 fw-bold text-dark mb-0 f-iran-yekan"><?php esc_html_e( 'بسته‌بندی مطمئن', 'negar-shop' ); ?></h4>
								<span class="text-muted small"><?php esc_html_e( 'ارسال پستی استاندارد با بیمه کامل مرسوله', 'negar-shop' ); ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

	</div>
</main>

<?php
get_footer();
