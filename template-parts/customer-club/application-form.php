<?php
/**
 * Customer Club Public Application Form Component.
 *
 * Provides a minimal, accessible, visually consistent form connected
 * to the secure REST API when application intake is explicitly enabled.
 *
 * CRITICAL SAFETY CONDITION:
 * If allow_applications is false, no input fields or national ID collectors
 * are rendered to the public.
 *
 * @package Negar_Shop
 * @version 1.1.0
 */

defined( 'ABSPATH' ) || exit;

$allow_applications = function_exists( 'negar_theme_club_setting' ) ? (bool) negar_theme_club_setting( 'allow_applications', null, false ) : false;
$plans              = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'plans', null, array() ) : array();

$current_user_id = get_current_user_id();
$user_phone      = '';
if ( $current_user_id > 0 ) {
	$user_phone = get_user_meta( $current_user_id, 'billing_phone', true );
	if ( empty( $user_phone ) ) {
		$user_phone = get_user_meta( $current_user_id, 'phone', true );
	}
}
?>

<section id="club-application-section" class="negar-club-application-section" aria-labelledby="club-application-heading" dir="rtl">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'درخواست عضویت و تسهیلات', 'negar-shop' ); ?></span>
		<h2 id="club-application-heading" class="negar-club-section-title"><?php esc_html_e( 'ثبت درخواست خرید اقساطی', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'پس از انتخاب طرح و بررسی شرایط، درخواست خود را برای ارزیابی اولیه کارشناسان ارسال فرمایید.', 'negar-shop' ); ?></p>
	</div>

	<?php if ( ! $allow_applications ) : ?>
		<!-- Public Intake Closed State (Guaranteed Safety Guard) -->
		<div class="negar-club-app-card negar-club-app-card--closed" role="status">
			<div class="negar-club-app-card__icon" aria-hidden="true">
				<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
			</div>
			<div class="negar-club-app-card__content">
				<h3 class="negar-club-app-card__title"><?php esc_html_e( 'پذیرش عمومی درخواست‌ها در حال حاضر غیرفعال است', 'negar-shop' ); ?></h3>
				<p class="negar-club-app-card__text"><?php esc_html_e( 'اطلاعات طرح‌ها و مبالغ در این برگه صرفاً جنبه پیش‌نمایش و برآورد دارد. با فعال‌سازی رسمی ثبت‌نام توسط فروشگاه، امکان ارسال درخواست و اعتبارسنجی در این بخش در دسترس قرار خواهد گرفت.', 'negar-shop' ); ?></p>
			</div>
		</div>
	<?php elseif ( ! is_user_logged_in() ) : ?>
		<!-- Guest Authentication Required State -->
		<div class="negar-club-app-card negar-club-app-card--auth">
			<div class="negar-club-app-card__icon" aria-hidden="true">
				<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</div>
			<div class="negar-club-app-card__content">
				<h3 class="negar-club-app-card__title"><?php esc_html_e( 'جهت ثبت درخواست ابتدا وارد حساب کاربری خود شوید', 'negar-shop' ); ?></h3>
				<p class="negar-club-app-card__text"><?php esc_html_e( 'برای ثبت امن مشخصات و امکان پیگیری وضعیت پرونده، احراز هویت اولیه از طریق شماره همراه الزامی است.', 'negar-shop' ); ?></p>
				<div class="negar-club-app-card__actions">
					<button type="button" class="negar-club-btn negar-club-btn--primary negar-login-trigger">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
						<span><?php esc_html_e( 'ورود / ثبت‌نام سریع با شماره همراه', 'negar-shop' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	<?php else : ?>
		<!-- Active Authenticated Application Form -->
		<div class="negar-club-form-wrap">
			<form id="negar-club-apply-form" class="negar-club-apply-form" novalidate data-rest-endpoint="<?php echo esc_url( rest_url( 'negar-club/v1/applications' ) ); ?>" data-rest-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>">
				
				<!-- Error and Success Alerts -->
				<div id="negar-apply-feedback" class="negar-club-apply-feedback" role="alert" hidden></div>

				<!-- Step Indicator / Status Header -->
				<div class="negar-club-form-grid">
					
					<!-- Field: Plan Selection -->
					<div class="negar-club-form-group">
						<label for="negar-apply-plan" class="negar-club-form-label">
							<?php esc_html_e( 'طرح اقساطی مورد نظر', 'negar-shop' ); ?>
							<span class="required" aria-hidden="true">*</span>
						</label>
						<select id="negar-apply-plan" name="plan_id" class="negar-club-form-select" required>
							<?php foreach ( $plans as $p_id => $p_data ) : ?>
								<?php if ( ! empty( $p_data['is_enabled'] ) ) : ?>
									<option value="<?php echo esc_attr( $p_id ); ?>" data-ceiling="<?php echo esc_attr( $p_data['ceiling'] ?? 0 ); ?>" data-initial="<?php echo esc_attr( $p_data['initial_payment'] ?? 0 ); ?>">
										<?php echo esc_html( $p_data['title'] ?? $p_id ); ?> (سقف: <?php echo number_format( (int) ( $p_data['ceiling'] ?? 0 ) ); ?> تومان)
									</option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</div>

					<!-- Field: Requested Ceiling -->
					<div class="negar-club-form-group">
						<label for="negar-apply-ceiling" class="negar-club-form-label">
							<?php esc_html_e( 'سقف اعتبار درخواستی (تومان)', 'negar-shop' ); ?>
							<span class="required" aria-hidden="true">*</span>
						</label>
						<input type="number" id="negar-apply-ceiling" name="requested_ceiling" class="negar-club-form-input" min="1000000" step="500000" placeholder="مثال: ۵۰۰۰۰۰۰" required />
						<span id="negar-apply-ceiling-hint" class="negar-club-form-hint"></span>
					</div>

					<!-- Field: National ID (Modulo 11 validated) -->
					<div class="negar-club-form-group">
						<label for="negar-apply-national-id" class="negar-club-form-label">
							<?php esc_html_e( 'کد ملی متقاضی', 'negar-shop' ); ?>
							<span class="required" aria-hidden="true">*</span>
						</label>
						<input type="text" id="negar-apply-national-id" name="national_id" class="negar-club-form-input" maxlength="10" inputmode="numeric" placeholder="۱۰ رقم کد ملی بدون خط تیره" dir="ltr" required />
						<span class="negar-club-form-hint"><?php esc_html_e( 'جهت اعتبارسنجی اولیه؛ کد ملی به صورت رمزنگاری‌شده نگهداری می‌شود.', 'negar-shop' ); ?></span>
					</div>

					<!-- Field: Contact Phone -->
					<div class="negar-club-form-group">
						<label for="negar-apply-phone" class="negar-club-form-label">
							<?php esc_html_e( 'شماره همراه در دسترس', 'negar-shop' ); ?>
							<span class="required" aria-hidden="true">*</span>
						</label>
						<input type="tel" id="negar-apply-phone" name="contact_phone" class="negar-club-form-input" value="<?php echo esc_attr( $user_phone ); ?>" maxlength="11" inputmode="numeric" placeholder="۰۹xxxxxxxxx" dir="ltr" required />
					</div>

					<!-- Field: Applicant Notes -->
					<div class="negar-club-form-group negar-club-form-group--full">
						<label for="negar-apply-notes" class="negar-club-form-label">
							<?php esc_html_e( 'توضیحات تکمیلی (اختیاری)', 'negar-shop' ); ?>
						</label>
						<textarea id="negar-apply-notes" name="applicant_notes" class="negar-club-form-textarea" rows="2" maxlength="500" placeholder="<?php esc_attr_e( 'در صورت وجود توضیحات در مورد نوع کالای مدنظر یا شرایط شغلی...', 'negar-shop' ); ?>"></textarea>
					</div>

				</div>

				<div class="negar-club-form-footer">
					<button type="submit" id="negar-apply-submit-btn" class="negar-club-btn negar-club-btn--primary">
						<span class="negar-btn-text"><?php esc_html_e( 'ثبت نهایی درخواست عضویت', 'negar-shop' ); ?></span>
						<span class="negar-btn-spinner" hidden aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="animate-spin"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path></svg>
						</span>
					</button>
					<p class="negar-club-form-disclaimer">
						<?php esc_html_e( 'ثبت این فرم به منزله درخواست اولیه ارزیابی است و ایجادکننده تعهد مالی قطعی پیش از تأیید کارشناس نمی‌باشد.', 'negar-shop' ); ?>
					</p>
				</div>
			</form>

			<!-- Success Card (Rendered via JS on HTTP 201) -->
			<div id="negar-apply-success-card" class="negar-club-app-card negar-club-app-card--success" hidden>
				<div class="negar-club-app-card__icon">
					<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
				</div>
				<div class="negar-club-app-card__content">
					<h3 class="negar-club-app-card__title"><?php esc_html_e( 'درخواست شما با موفقیت ثبت شد', 'negar-shop' ); ?></h3>
					<p class="negar-club-app-card__text" id="negar-apply-success-msg"></p>
					<div class="negar-club-app-details">
						<div class="negar-club-app-detail-item">
							<span><?php esc_html_e( 'شماره پیگیری درخواست:', 'negar-shop' ); ?></span>
							<strong id="negar-apply-success-number"></strong>
						</div>
						<div class="negar-club-app-detail-item">
							<span><?php esc_html_e( 'وضعیت فعلی:', 'negar-shop' ); ?></span>
							<span class="negar-badge" id="negar-apply-success-status"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
