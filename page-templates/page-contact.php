<?php
/**
 * Template Name: Contact Us / تماس با ما
 *
 * Polished contact center design aligned with Kaveh Demo 43 brand identity.
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
				<li class="current" aria-current="page"><?php esc_html_e( 'تماس با ما', 'negar-shop' ); ?></li>
			</ol>
		</nav>

		<!-- Hero Section -->
		<section class="negar-editorial-hero text-center">
			<span class="negar-hero-badge">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
				<span><?php esc_html_e( 'مرکز ارتباط با مشتریان', 'negar-shop' ); ?></span>
			</span>
			<h1 class="negar-hero-title f-iran-yekan">
				<?php esc_html_e( 'همیشه آماده پاسخگویی و همراهی با شما هستیم', 'negar-shop' ); ?>
			</h1>
			<p class="negar-hero-lead f-payda">
				<?php esc_html_e( 'سوالی درباره مشخصات کالا دارید، پیگیر سفارش هستید یا نیاز به مشاوره پیش از خرید دارید؟ تیم کارشناسان ما آماده راهنمایی شما هستند.', 'negar-shop' ); ?>
			</p>
		</section>

		<!-- 4 Contact Cards Grid -->
		<section class="mb-5">
			<div class="row g-3 g-md-4">
				<div class="col-12 col-sm-6 col-lg-3">
					<a href="tel:02112345678" class="negar-contact-card">
						<div class="negar-contact-icon">
							<i class="icon-headphone-2"></i>
						</div>
						<div>
							<span class="negar-contact-card-label f-payda"><?php esc_html_e( 'تماس تلفنی مستقیم', 'negar-shop' ); ?></span>
							<span class="negar-contact-card-value font-monospace dir-ltr">۰۲۱-۱۲۳۴۵۶۷۸</span>
						</div>
					</a>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<a href="mailto:info@negarshop.local" class="negar-contact-card">
						<div class="negar-contact-icon">
							<i class="icon-message"></i>
						</div>
						<div>
							<span class="negar-contact-card-label f-payda"><?php esc_html_e( 'پشتیبانی ایمیلی', 'negar-shop' ); ?></span>
							<span class="negar-contact-card-value font-monospace">info@negarshop.local</span>
						</div>
					</a>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="negar-contact-card">
						<div class="negar-contact-icon">
							<i class="icon-calendar-dates"></i>
						</div>
						<div>
							<span class="negar-contact-card-label f-payda"><?php esc_html_e( 'ساعات کاری پشتیبانی', 'negar-shop' ); ?></span>
							<span class="negar-contact-card-value f-payda"><?php esc_html_e( 'شنبه تا پنجشنبه ۹ الی ۲۱', 'negar-shop' ); ?></span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3">
					<div class="negar-contact-card">
						<div class="negar-contact-icon">
							<i class="icon-pin"></i>
						</div>
						<div>
							<span class="negar-contact-card-label f-payda"><?php esc_html_e( 'دفتر مرکزی', 'negar-shop' ); ?></span>
							<span class="negar-contact-card-value f-payda"><?php esc_html_e( 'تهران، خیابان ولیعصر', 'negar-shop' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Contact Form & Map Section -->
		<section class="mb-4">
			<div class="row g-4">
				
				<!-- Right Column: Inquiry Form (7 cols) -->
				<div class="col-12 col-lg-7">
					<div class="negar-contact-form-box">
						<div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
							<i class="icon-sms fs-4 text-danger"></i>
							<h2 class="fs-5 fw-bold text-dark mb-0 f-iran-yekan"><?php esc_html_e( 'ارسال پیام مستقیم به مدیریت و پشتیبانی', 'negar-shop' ); ?></h2>
						</div>

						<form id="negar-contact-form">
							<div id="negar-contact-alert" class="hidden mb-3 p-3 rounded-3 text-xs font-medium"></div>

							<div class="row g-3 mb-3">
								<div class="col-12 col-sm-6">
									<label for="contact-name" class="form-label f-payda"><?php esc_html_e( 'نام و نام خانوادگی:', 'negar-shop' ); ?> <span class="text-danger">*</span></label>
									<input type="text" id="contact-name" name="name" placeholder="<?php esc_attr_e( 'مثال: علی محمدی', 'negar-shop' ); ?>" required>
								</div>
								<div class="col-12 col-sm-6">
									<label for="contact-phone" class="form-label f-payda"><?php esc_html_e( 'شماره موبایل:', 'negar-shop' ); ?> <span class="text-danger">*</span></label>
									<input type="tel" id="contact-phone" name="phone" class="text-start font-monospace dir-ltr" placeholder="۰۹۱۲..." required>
								</div>
							</div>

							<div class="mb-3">
								<label for="contact-subject" class="form-label f-payda"><?php esc_html_e( 'موضوع پیام یا شماره سفارش:', 'negar-shop' ); ?> <span class="text-danger">*</span></label>
								<input type="text" id="contact-subject" name="subject" placeholder="<?php esc_attr_e( 'موضوع پرسش یا شماره فاکتور خرید', 'negar-shop' ); ?>" required>
							</div>

							<div class="mb-4">
								<label for="contact-message" class="form-label f-payda"><?php esc_html_e( 'متن پیام شما:', 'negar-shop' ); ?> <span class="text-danger">*</span></label>
								<textarea id="contact-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'پیام، دیدگاه یا پرسش خود را اینجا بنویسید...', 'negar-shop' ); ?>" required></textarea>
							</div>

							<button type="submit" id="negar-contact-submit" class="negar-contact-btn f-payda">
								<span><?php esc_html_e( 'ارسال پیام', 'negar-shop' ); ?></span>
								<i class="icon-arrow-left-11 ms-1"></i>
								<span class="contact-spinner hidden animate-spin">
									<svg class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
								</span>
							</button>
						</form>
					</div>
				</div>

				<!-- Left Column: Map & Office Info (5 cols) -->
				<div class="col-12 col-lg-5">
					<div class="negar-map-box">
						<div>
							<div class="d-flex align-items-center gap-2 mb-3">
								<i class="icon-location-2 fs-5 text-danger"></i>
								<h3 class="fs-5 fw-bold text-dark mb-0 f-iran-yekan"><?php esc_html_e( 'موقعیت مکانی فروشگاه', 'negar-shop' ); ?></h3>
							</div>
							<p class="text-secondary small f-payda mb-4 leading-relaxed">
								<?php esc_html_e( 'برای هماهنگی تحویل حضوری سفارش‌ها، مراجعات اداری یا خریدهای سازمانی، می‌توانید در ساعات کاری به دفتر مرکزی ما مراجعه نمایید.', 'negar-shop' ); ?>
							</p>
						</div>

						<div class="negar-map-frame">
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
		</section>

	</div>
</main>

<?php
get_footer();
