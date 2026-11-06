<?php
/**
 * Template Name: Contact Us / تماس با ما
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="negar-contact-page py-10">
	<div class="container mx-auto px-4 max-w-5xl">

		<!-- Page Title -->
		<div class="text-center mb-12">
			<span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3.5 py-1.5 rounded-full mb-3">
				<?php esc_html_e( 'مرکز ارتباط با مشتریان', 'negar-shop' ); ?>
			</span>
			<h1 class="text-2xl sm:text-4xl font-black text-dark mb-3">
				<?php esc_html_e( 'همیشه آماده پاسخگویی به شما هستیم', 'negar-shop' ); ?>
			</h1>
			<p class="text-xs sm:text-sm text-muted max-w-xl mx-auto">
				<?php esc_html_e( 'سوالی دارید یا به مشاوره خرید نیاز دارید؟ از طریق راه‌های ارتباطی زیر یا فرم پیام با ما در تماس باشید.', 'negar-shop' ); ?>
			</p>
		</div>

		<!-- 4 Contact Cards Grid -->
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
			
			<div class="contact-card bg-surface border border-border rounded-2xl p-5 shadow-xs flex items-center gap-3">
				<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
				</div>
				<div>
					<span class="text-2xs text-muted block mb-0.5"><?php esc_html_e( 'تماس مستقیم تلفنی', 'negar-shop' ); ?></span>
					<a href="tel:02112345678" class="text-sm font-bold text-dark hover:text-primary font-mono dir-ltr block">۰۲۱-۱۲۳۴۵۶۷۸</a>
				</div>
			</div>

			<div class="contact-card bg-surface border border-border rounded-2xl p-5 shadow-xs flex items-center gap-3">
				<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
				</div>
				<div>
					<span class="text-2xs text-muted block mb-0.5"><?php esc_html_e( 'پشتیبانی ایمیلی', 'negar-shop' ); ?></span>
					<a href="mailto:info@negarshop.local" class="text-xs font-bold text-dark hover:text-primary font-mono block">info@negarshop.local</a>
				</div>
			</div>

			<div class="contact-card bg-surface border border-border rounded-2xl p-5 shadow-xs flex items-center gap-3">
				<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				</div>
				<div>
					<span class="text-2xs text-muted block mb-0.5"><?php esc_html_e( 'ساعات پاسخگویی', 'negar-shop' ); ?></span>
					<span class="text-xs font-bold text-dark block"><?php esc_html_e( 'شنبه تا پنجشنبه ۹ الی ۲۱', 'negar-shop' ); ?></span>
				</div>
			</div>

			<div class="contact-card bg-surface border border-border rounded-2xl p-5 shadow-xs flex items-center gap-3">
				<div class="p-3 rounded-xl bg-primary/10 text-primary flex-shrink-0">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				<div>
					<span class="text-2xs text-muted block mb-0.5"><?php esc_html_e( 'دفتر مرکزی', 'negar-shop' ); ?></span>
					<span class="text-xs font-bold text-dark block"><?php esc_html_e( 'تهران، ولیعصر، برج نگار', 'negar-shop' ); ?></span>
				</div>
			</div>

		</div>

		<!-- 2-Column Form & FAQ -->
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
			
			<!-- Contact Form Column (Right in RTL, 7 cols) -->
			<div class="lg:col-span-7 bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">
				<h3 class="text-base font-bold text-dark pb-3 mb-6 border-b border-border flex items-center gap-2">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
					<span><?php esc_html_e( 'ارسال پیام مستقیم برای مدیریت و پشتیبانی', 'negar-shop' ); ?></span>
				</h3>

				<form action="#" method="post" class="flex flex-col gap-4 text-xs sm:text-sm">
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<div>
							<label for="contact_name" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'نام و نام خانوادگی', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
							<input type="text" id="contact_name" name="contact_name" required placeholder="<?php esc_attr_e( 'مثال: علی محمدی', 'negar-shop' ); ?>" class="w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all">
						</div>
						<div>
							<label for="contact_phone" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'شماره موبایل', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
							<input type="tel" id="contact_phone" name="contact_phone" required placeholder="09123456789" class="w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all text-left dir-ltr">
						</div>
					</div>

					<div>
						<label for="contact_subject" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'موضوع پیام', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
						<input type="text" id="contact_subject" name="contact_subject" required placeholder="<?php esc_attr_e( 'مثال: پیگیری سفارش یا مشاوره خرید کالا', 'negar-shop' ); ?>" class="w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all">
					</div>

					<div>
						<label for="contact_message" class="font-bold text-dark block mb-1.5"><?php esc_html_e( 'متن پیام شما', 'negar-shop' ); ?>&nbsp;<span class="text-danger">*</span></label>
						<textarea id="contact_message" name="contact_message" rows="5" required placeholder="<?php esc_attr_e( 'توضیحات خود را اینجا بنویسید...', 'negar-shop' ); ?>" class="w-full p-2.5 rounded-xl border border-border bg-slate-50 focus:bg-white focus:border-primary transition-all"></textarea>
					</div>

					<button type="submit" class="btn btn-primary py-3 px-8 text-sm font-bold shadow-md hover:shadow-lg transition-all self-start mt-2">
						<?php esc_html_e( 'ارسال پیام', 'negar-shop' ); ?>
					</button>
				</form>
			</div>

			<!-- FAQ Accordion Column (Left in RTL, 5 cols) -->
			<div class="lg:col-span-5 bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">
				<h3 class="text-base font-bold text-dark pb-3 mb-6 border-b border-border flex items-center gap-2">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><circle cx="12" cy="12" r="10"/><path d="9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
					<span><?php esc_html_e( 'پرسش‌های متداول خریداران', 'negar-shop' ); ?></span>
				</h3>

				<div class="flex flex-col gap-3 text-xs sm:text-sm">
					
					<details class="border border-border rounded-xl p-3 bg-slate-50 group open:bg-white transition-all">
						<summary class="font-bold text-dark cursor-pointer list-none flex items-center justify-between">
							<span><?php esc_html_e( 'چگونه سفارش خود را پیگیری کنم؟', 'negar-shop' ); ?></span>
							<span class="text-muted group-open:rotate-180 transition-transform">▼</span>
						</summary>
						<p class="text-xs text-muted leading-relaxed mt-2 pt-2 border-t border-border/50">
							<?php esc_html_e( 'از طریق بخش «سفارش‌های من» در حساب کاربری یا صفحه «پیگیری سفارش» با وارد کردن کد پیگیری می‌توانید وضعیت ارسال را به صورت لحظه‌ای مشاهده کنید.', 'negar-shop' ); ?>
						</p>
					</details>

					<details class="border border-border rounded-xl p-3 bg-slate-50 group open:bg-white transition-all">
						<summary class="font-bold text-dark cursor-pointer list-none flex items-center justify-between">
							<span><?php esc_html_e( 'هزینه ارسال چقدر است؟', 'negar-shop' ); ?></span>
							<span class="text-muted group-open:rotate-180 transition-transform">▼</span>
						</summary>
						<p class="text-xs text-muted leading-relaxed mt-2 pt-2 border-t border-border/50">
							<?php esc_html_e( 'ارسال کلیه خریدهای بالای ۱ میلیون تومان به سراسر کشور کاملاً رایگان است. برای مبالغ کمتر بر اساس تعرفه مصوب پست محاسبه می‌شود.', 'negar-shop' ); ?>
						</p>
					</details>

					<details class="border border-border rounded-xl p-3 bg-slate-50 group open:bg-white transition-all">
						<summary class="font-bold text-dark cursor-pointer list-none flex items-center justify-between">
							<span><?php esc_html_e( 'شرایط گارانتی و مرجوعی چیست؟', 'negar-shop' ); ?></span>
							<span class="text-muted group-open:rotate-180 transition-transform">▼</span>
						</summary>
						<p class="text-xs text-muted leading-relaxed mt-2 pt-2 border-t border-border/50">
							<?php esc_html_e( 'تمامی کالاها تا ۷ روز پس از تحویل در صورت مغایرت یا نقص فنی بدون کسر هزینه تعویض یا مرجوع می‌شوند.', 'negar-shop' ); ?>
						</p>
					</details>

				</div>
			</div>

		</div>

	</div>
</div>

<?php
get_footer();
