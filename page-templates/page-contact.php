<?php
/**
 * Template Name: Contact Us / تماس با ما
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
					/ <?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?>
				</li>
			</ul>
		</div>

		<!-- Page Header -->
		<div class="bg-white rounded-4 p-4 p-md-5 shadow-sm text-center mb-4">
			<span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 mb-3 fw-bold f-payda">
				<?php esc_html_e( 'مرکز ارتباط با مشتریان', 'negar-shop' ); ?>
			</span>
			<h1 class="f-iran-yekan fw-black fs-2 fs-md-1 text-dark mb-3">
				<?php esc_html_e( 'همیشه آماده پاسخگویی به شما هستیم', 'negar-shop' ); ?>
			</h1>
			<p class="text-secondary f-payda fs-6 max-w-75 mx-auto mb-0" style="max-width: 650px;">
				<?php esc_html_e( 'سوالی دارید یا نیاز به مشاوره قبل از خرید کالا دارید؟ همکاران ما در واحد پشتیبانی مشتاقانه آماده راهنمایی شما هستند.', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- 4 Contact Cards Grid -->
		<div class="row g-3 g-md-4 mb-4">
			<div class="col-12 col-sm-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white d-flex flex-row align-items-center gap-3">
					<div class="p-3 rounded-3 bg-danger-subtle text-danger flex-shrink-0">
						<i class="icon-headphone-2 fs-4"></i>
					</div>
					<div>
						<span class="text-muted small d-block mb-1"><?php esc_html_e( 'تماس تلفنی', 'negar-shop' ); ?></span>
						<a href="tel:02112345678" class="fw-bold text-dark text-decoration-none font-monospace dir-ltr d-block">۰۲۱-۱۲۳۴۵۶۷۸</a>
					</div>
				</div>
			</div>

			<div class="col-12 col-sm-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white d-flex flex-row align-items-center gap-3">
					<div class="p-3 rounded-3 bg-danger-subtle text-danger flex-shrink-0">
						<i class="icon-message fs-4"></i>
					</div>
					<div>
						<span class="text-muted small d-block mb-1"><?php esc_html_e( 'پشتیبانی ایمیلی', 'negar-shop' ); ?></span>
						<a href="mailto:info@negarshop.local" class="fw-bold text-dark text-decoration-none font-monospace d-block small">info@negarshop.local</a>
					</div>
				</div>
			</div>

			<div class="col-12 col-sm-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white d-flex flex-row align-items-center gap-3">
					<div class="p-3 rounded-3 bg-danger-subtle text-danger flex-shrink-0">
						<i class="icon-calendar-dates fs-4"></i>
					</div>
					<div>
						<span class="text-muted small d-block mb-1"><?php esc_html_e( 'ساعات کاری', 'negar-shop' ); ?></span>
						<span class="fw-bold text-dark small d-block"><?php esc_html_e( 'شنبه تا پنجشنبه ۹ الی ۲۱', 'negar-shop' ); ?></span>
					</div>
				</div>
			</div>

			<div class="col-12 col-sm-6 col-lg-3">
				<div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white d-flex flex-row align-items-center gap-3">
					<div class="p-3 rounded-3 bg-danger-subtle text-danger flex-shrink-0">
						<i class="icon-pin fs-4"></i>
					</div>
					<div>
						<span class="text-muted small d-block mb-1"><?php esc_html_e( 'آدرس دفتر مرکزی', 'negar-shop' ); ?></span>
						<span class="fw-bold text-dark small d-block"><?php esc_html_e( 'تهران، خیابان ولیعصر', 'negar-shop' ); ?></span>
					</div>
				</div>
			</div>
		</div>

		<!-- Contact Form & Info Columns -->
		<div class="row g-4">
			<div class="col-12 col-lg-7">
				<div class="bg-white rounded-4 p-4 p-md-5 shadow-sm h-100">
					<h2 class="f-iran-yekan fw-bold fs-4 text-dark mb-4"><?php esc_html_e( 'ارسال پیام مستقیم به مدیریت', 'negar-shop' ); ?></h2>
					<form id="negar-contact-form">
						<div id="negar-contact-alert" class="hidden mb-3 p-3 rounded-3 text-xs font-medium"></div>
						<div class="row g-3 mb-3">
							<div class="col-12 col-sm-6">
								<label for="contact-name" class="form-label small text-muted"><?php esc_html_e( 'نام و نام خانوادگی:', 'negar-shop' ); ?></label>
								<input type="text" id="contact-name" name="name" class="form-control rounded-3 py-2" placeholder="مثال: علی محمدی" required>
							</div>
							<div class="col-12 col-sm-6">
								<label for="contact-phone" class="form-label small text-muted"><?php esc_html_e( 'شماره موبایل:', 'negar-shop' ); ?></label>
								<input type="tel" id="contact-phone" name="phone" class="form-control rounded-3 py-2 text-start font-monospace" placeholder="۰۹۱۲..." required>
							</div>
						</div>
						<div class="mb-3">
							<label for="contact-subject" class="form-label small text-muted"><?php esc_html_e( 'موضوع پیام:', 'negar-shop' ); ?></label>
							<input type="text" id="contact-subject" name="subject" class="form-control rounded-3 py-2" placeholder="موضوع پیام یا شماره سفارش" required>
						</div>
						<div class="mb-4">
							<label for="contact-message" class="form-label small text-muted"><?php esc_html_e( 'متن پیام شما:', 'negar-shop' ); ?></label>
							<textarea id="contact-message" name="message" class="form-control rounded-3 p-3" rows="5" placeholder="پیام یا پرسش خود را اینجا بنویسید..." required></textarea>
						</div>
						<button type="submit" id="negar-contact-submit" class="btn btn-danger rounded-pill px-5 py-2.5 fw-bold f-payda d-inline-flex align-items-center gap-2">
							<span><?php esc_html_e( 'ارسال پیام', 'negar-shop' ); ?></span>
							<i class="icon-arrow-left-11 ms-1"></i>
							<span class="contact-spinner hidden animate-spin">
								<svg class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
							</span>
						</button>
					</form>
				</div>
			</div>

			<div class="col-12 col-lg-5">
				<div class="bg-white rounded-4 p-4 p-md-5 shadow-sm h-100 d-flex flex-column justify-content-between">
					<div>
						<h3 class="f-iran-yekan fw-bold fs-5 text-dark mb-3"><?php esc_html_e( 'موقعیت مکانی فروشگاه', 'negar-shop' ); ?></h3>
						<p class="text-secondary small mb-4">
							<?php esc_html_e( 'برای خریدهای عمده یا دریافت حضوری سفارش‌ها، با هماهنگی قبلی می‌توانید به دفتر مرکزی ما مراجعه نمایید.', 'negar-shop' ); ?>
						</p>
					</div>

					<div class="rounded-4 overflow-hidden border border-light shadow-2xs position-relative" style="height: 280px; background: #e2e8f0;">
						<iframe
							src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3239.948275685511!2d51.4099!3d35.7006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzXCsDQyJzAyLjIiTiA1McKwMjQnMzUuNiJF!5e0!3m2!1sen!2sir!4v1620000000000!5m2!1sen!2sir"
							width="100%"
							height="100%"
							style="border:0;"
							allowfullscreen=""
							loading="lazy"
							title="<?php esc_attr_e( 'نقشه موقعیت دفتر مرکزی', 'negar-shop' ); ?>">
						</iframe>
					</div>
				</div>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
