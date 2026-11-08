<?php
/**
 * Template Name: About Us / درباره ما
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main py-5 bg-light">
	<div class="container">

		<!-- Breadcrumb -->
		<div class="breadcrumb-nk d-flex align-items-center mb-4">
			<ul class="d-flex align-items-center flex-nowrap list-unstyled m-0 p-0">
				<li class="d-flex align-items-center flex-shrink-0">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-block d-flex align-items-center gap-2 text-decoration-none text-muted">
						<i class="iconly-Home lh-1"></i><?php esc_html_e( 'خانه', 'negar-shop' ); ?>
					</a>
				</li>
				<li class="d-flex align-items-center flex-shrink-0 ms-2 is-current fw-bold text-dark" aria-current="page">
					/ <?php esc_html_e( 'درباره ما', 'negar-shop' ); ?>
				</li>
			</ul>
		</div>

		<!-- Hero Section -->
		<div class="bg-white rounded-4 p-4 p-md-5 shadow-sm text-center mb-4">
			<span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 mb-3 fw-bold f-payda">
				<?php esc_html_e( 'داستان کاوه دیجیتال', 'negar-shop' ); ?>
			</span>
			<h1 class="f-iran-yekan fw-black fs-2 fs-md-1 text-dark mb-4 max-w-75 mx-auto">
				<?php esc_html_e( 'تجربه خریدی مطمئن، سریع و اصیل با کاوه', 'negar-shop' ); ?>
			</h1>
			<p class="text-secondary f-payda fs-6 leading-relaxed max-w-75 mx-auto mb-0" style="max-width: 780px;">
				<?php esc_html_e( 'فروشگاه کاوه با هدف عرضه جدیدترین تجهیزات بازی، کالای دیجیتال و لوازم الکترونیک روز جهان با تضمین اصالت و بهترین قیمت راه‌اندازی شده است. تمامی محصولات پیش از ارسال با دقت کارشناسی بررسی شده و در بسته‌بندی ایمن و ضدضربه برای سراسر ایران ارسال می‌گردند.', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- 4 Key Metrics -->
		<div class="row g-3 g-md-4 mb-4">
			<div class="col-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm text-center p-4 h-100 bg-white">
					<div class="fs-1 fw-bold text-danger font-monospace mb-1">۱۰+</div>
					<div class="fw-bold text-dark f-payda small"><?php esc_html_e( 'سال سابقه تخصصی', 'negar-shop' ); ?></div>
					<div class="text-muted small mt-1"><?php esc_html_e( 'واردات و عرضه مستقیم', 'negar-shop' ); ?></div>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm text-center p-4 h-100 bg-white">
					<div class="fs-1 fw-bold text-danger font-monospace mb-1">۵۰,۰۰۰+</div>
					<div class="fw-bold text-dark f-payda small"><?php esc_html_e( 'سفارش موفق در کشور', 'negar-shop' ); ?></div>
					<div class="text-muted small mt-1"><?php esc_html_e( 'رضایت بالای خریداران', 'negar-shop' ); ?></div>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm text-center p-4 h-100 bg-white">
					<div class="fs-1 fw-bold text-danger font-monospace mb-1">۱۰۰٪</div>
					<div class="fw-bold text-dark f-payda small"><?php esc_html_e( 'ضمانت اصالت و سلامت', 'negar-shop' ); ?></div>
					<div class="text-muted small mt-1"><?php esc_html_e( 'کالای پلمب شرکتی', 'negar-shop' ); ?></div>
				</div>
			</div>
			<div class="col-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm text-center p-4 h-100 bg-white">
					<div class="fs-1 fw-bold text-danger font-monospace mb-1">۲۴/۷</div>
					<div class="fw-bold text-dark f-payda small"><?php esc_html_e( 'پشتیبانی ۲۴ ساعته', 'negar-shop' ); ?></div>
					<div class="text-muted small mt-1"><?php esc_html_e( 'مشاوره پیش و پس از خرید', 'negar-shop' ); ?></div>
				</div>
			</div>
		</div>

		<!-- Core Values -->
		<div class="bg-white rounded-4 p-4 p-md-5 shadow-sm mb-4">
			<div class="text-center mb-4">
				<h2 class="f-iran-yekan fw-bold fs-3 text-dark mb-2"><?php esc_html_e( 'ارزش‌های کلیدی ما در کاوه', 'negar-shop' ); ?></h2>
				<p class="text-muted small f-payda"><?php esc_html_e( 'چرا هزاران مشتری، کاوه را برای خرید تجهیزات دیجیتال انتخاب کرده‌اند؟', 'negar-shop' ); ?></p>
			</div>

			<div class="row g-4">
				<div class="col-12 col-md-6">
					<div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light">
						<div class="p-3 rounded-circle bg-danger text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
							<i class="icon-security-3 fs-5"></i>
						</div>
						<div>
							<h3 class="fw-bold fs-6 text-dark mb-1"><?php esc_html_e( 'تضمین اصالت کالا', 'negar-shop' ); ?></h3>
							<p class="text-secondary small mb-0"><?php esc_html_e( 'تمامی کالاها مستقیماً با ضمانت اصلی و پلمب کارخانه تهیه و به دست شما می‌رسد.', 'negar-shop' ); ?></p>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light">
						<div class="p-3 rounded-circle bg-danger text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
							<i class="icon-headphone-2 fs-5"></i>
						</div>
						<div>
							<h3 class="fw-bold fs-6 text-dark mb-1"><?php esc_html_e( 'پشتیبانی تخصصی', 'negar-shop' ); ?></h3>
							<p class="text-secondary small mb-0"><?php esc_html_e( 'تیم فنی مجرب ما در هر مرحله از انتخاب و راه‌اندازی در کنار شماست.', 'negar-shop' ); ?></p>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light">
						<div class="p-3 rounded-circle bg-danger text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
							<i class="icon-energy fs-5"></i>
						</div>
						<div>
							<h3 class="fw-bold fs-6 text-dark mb-1"><?php esc_html_e( 'ارسال فوق سریع اکسپرس', 'negar-shop' ); ?></h3>
							<p class="text-secondary small mb-0"><?php esc_html_e( 'تحویل کمتر از ۲ ساعت در تهران و ارسال سریع تیپاکس به تمامی نقاط کشور.', 'negar-shop' ); ?></p>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-6">
					<div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light">
						<div class="p-3 rounded-circle bg-danger text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
							<i class="icon-backward fs-5"></i>
						</div>
						<div>
							<h3 class="fw-bold fs-6 text-dark mb-1"><?php esc_html_e( '۷ روز ضمانت بازگشت وجه', 'negar-shop' ); ?></h3>
							<p class="text-secondary small mb-0"><?php esc_html_e( 'امکان انصراف از خرید و بازگشت کامل وجه با خیال راحت و بدون دغدغه.', 'negar-shop' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
