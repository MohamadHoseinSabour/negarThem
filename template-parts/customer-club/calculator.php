<?php
/**
 * Customer Club Interactive Quote Calculator Component.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$plans = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'plans', null, array() ) : array();
$allowed_ids = array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' );

$calc_plans = array();
foreach ( $allowed_ids as $pid ) {
	if ( ! empty( $plans[ $pid ] ) && ! empty( $plans[ $pid ]['is_enabled'] ) ) {
		$calc_plans[ $pid ] = array(
			'id'              => $pid,
			'title'           => (string) ( $plans[ $pid ]['title'] ?? $pid ),
			'initial_payment' => (int) ( $plans[ $pid ]['initial_payment'] ?? 0 ),
			'ceiling'         => (int) ( $plans[ $pid ]['ceiling'] ?? 0 ),
			'duration_months' => (int) ( $plans[ $pid ]['duration_months'] ?? 1 ),
			'is_featured'     => ! empty( $plans[ $pid ]['is_featured'] ),
		);
	}
}

if ( empty( $calc_plans ) ) {
	return;
}

// Find default active plan (featured or first)
$default_plan_id = 'negar_plus';
if ( empty( $calc_plans[ $default_plan_id ] ) ) {
	$keys = array_keys( $calc_plans );
	$default_plan_id = $keys[0];
}
$default_plan = $calc_plans[ $default_plan_id ];
$default_amount = (int) $default_plan['ceiling'];
?>

<section id="club-calculator" class="negar-club-calculator" aria-labelledby="club-calc-heading" data-calculator-config="<?php echo esc_attr( wp_json_encode( $calc_plans ) ); ?>" data-rest-url="<?php echo esc_url( rest_url( 'negar-club/v1/quote' ) ); ?>">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'برآورد هوشمند', 'negar-shop' ); ?></span>
		<h2 id="club-calc-heading" class="negar-club-section-title"><?php esc_html_e( 'محاسبه‌گر پیش‌نمایش اقساط', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'مبلغ خرید مورد نظرتان را مشخص کنید تا جزئیات پیش‌پرداخت، تسهیلات و اقساط ماهانه به صورت آنی محاسبه شود.', 'negar-shop' ); ?></p>
	</div>

	<div class="negar-club-calc__container">
		<!-- Left: Controls -->
		<div class="negar-club-calc__controls">
			<!-- Plan Selector Tabs -->
			<div class="negar-club-calc__field">
				<label class="negar-club-calc__label"><?php esc_html_e( 'طرح اقساطی مورد نظر را انتخاب کنید:', 'negar-shop' ); ?></label>
				<div class="negar-club-calc__plan-tabs" role="tablist" aria-label="<?php esc_attr_e( 'انتخاب طرح اقساطی', 'negar-shop' ); ?>">
					<?php foreach ( $calc_plans as $pid => $pinfo ) : ?>
						<button type="button" role="tab" class="negar-club-calc__tab <?php echo $pid === $default_plan_id ? 'is-active' : ''; ?>" data-plan-id="<?php echo esc_attr( $pid ); ?>" aria-selected="<?php echo $pid === $default_plan_id ? 'true' : 'false'; ?>">
							<strong><?php echo esc_html( $pinfo['title'] ); ?></strong>
							<span><?php echo esc_html( number_format_i18n( $pinfo['duration_months'] ) ); ?> <?php esc_html_e( 'ماهه', 'negar-shop' ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Amount Input & Slider -->
			<div class="negar-club-calc__field">
				<div class="negar-club-calc__field-header">
					<label for="negar-calc-amount-input" class="negar-club-calc__label"><?php esc_html_e( 'مبلغ تقریبی فاکتور خرید:', 'negar-shop' ); ?></label>
					<span class="negar-club-calc__hint"><?php esc_html_e( 'به تومان', 'negar-shop' ); ?></span>
				</div>

				<div class="negar-club-calc__input-wrap">
					<input type="text" id="negar-calc-amount-input" class="negar-club-calc__input" value="<?php echo esc_attr( number_format( $default_amount ) ); ?>" inputmode="numeric" autocomplete="off" aria-label="<?php esc_attr_e( 'مبلغ فاکتور خرید به تومان', 'negar-shop' ); ?>">
					<span class="negar-club-calc__currency"><?php esc_html_e( 'تومان', 'negar-shop' ); ?></span>
				</div>

				<div class="negar-club-calc__slider-wrap">
					<input type="range" id="negar-calc-slider" class="negar-club-calc__slider" min="<?php echo esc_attr( $default_plan['initial_payment'] ); ?>" max="<?php echo esc_attr( $default_plan['ceiling'] * 1.5 ); ?>" step="500000" value="<?php echo esc_attr( $default_amount ); ?>" aria-label="<?php esc_attr_e( 'اسلایدر تغییر مبلغ فاکتور خرید', 'negar-shop' ); ?>">
					<div class="negar-club-calc__slider-marks">
						<span id="negar-calc-slider-min"><?php echo esc_html( number_format_i18n( $default_plan['initial_payment'] ) ); ?> <?php esc_html_e( 'تومان', 'negar-shop' ); ?></span>
						<span id="negar-calc-slider-ceiling"><?php esc_html_e( 'سقف طرح:', 'negar-shop' ); ?> <?php echo esc_html( number_format_i18n( $default_plan['ceiling'] ) ); ?></span>
					</div>
				</div>

				<div id="negar-calc-validation-notice" class="negar-club-calc__notice" style="display: none;" role="alert"></div>
			</div>
		</div>

		<!-- Right: Live Result Breakdown -->
		<div class="negar-club-calc__result-card" aria-live="polite">
			<div class="negar-club-calc__result-header">
				<span class="negar-club-calc__result-eyebrow"><?php esc_html_e( 'برآورد وضعیت اقساط', 'negar-shop' ); ?></span>
				<div class="negar-club-calc__highlight">
					<span class="negar-club-calc__highlight-label"><?php esc_html_e( 'مبلغ تقریبی هر قسط ماهانه:', 'negar-shop' ); ?></span>
					<div class="negar-club-calc__highlight-value">
						<strong id="res-monthly-amount">۰</strong>
						<span><?php esc_html_e( 'تومان / ماه', 'negar-shop' ); ?></span>
					</div>
				</div>
			</div>

			<div class="negar-club-calc__breakdown">
				<div class="negar-club-calc__row">
					<span><?php esc_html_e( 'کل مبلغ فاکتور خرید:', 'negar-shop' ); ?></span>
					<strong id="res-purchase-amount">۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
				</div>
				<div class="negar-club-calc__row">
					<span><?php esc_html_e( 'پیش‌پرداخت اولیه مصوب:', 'negar-shop' ); ?></span>
					<strong id="res-initial-payment">۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
				</div>
				<div id="res-excess-row" class="negar-club-calc__row is-excess" style="display: none;">
					<span><?php esc_html_e( 'مازاد نقدی سقف خرید:', 'negar-shop' ); ?></span>
					<strong id="res-excess-amount">۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
				</div>
				<div class="negar-club-calc__row is-total-upfront">
					<span><?php esc_html_e( 'مجموع پرداخت اولیه (نقدی):', 'negar-shop' ); ?></span>
					<strong id="res-total-upfront">۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
				</div>
				<div class="negar-club-calc__row is-financed">
					<span><?php esc_html_e( 'اصل تسهیلات اقساطی:', 'negar-shop' ); ?></span>
					<strong id="res-financed-principal">۰ <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
				</div>
				<div class="negar-club-calc__row">
					<span><?php esc_html_e( 'تعداد اقساط بازپرداخت:', 'negar-shop' ); ?></span>
					<strong id="res-installments-count">۰ <?php esc_html_e( 'قسط ماهانه', 'negar-shop' ); ?></strong>
				</div>
			</div>

			<!-- Schedule Preview Accordion -->
			<div class="negar-club-calc__schedule-wrap">
				<button type="button" class="negar-club-calc__schedule-toggle" aria-expanded="false" aria-controls="negar-calc-schedule-list">
					<span><?php esc_html_e( 'مشاهده جدول تفکیکی اقساط ماهانه', 'negar-shop' ); ?></span>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
				</button>
				<div id="negar-calc-schedule-list" class="negar-club-calc__schedule-list" style="display: none;">
					<!-- Populated dynamically via JS -->
				</div>
			</div>

			<div class="negar-club-calc__disclaimer">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
				<p id="res-disclaimer"><?php esc_html_e( 'این محاسبه صرفاً پیش‌نمایش و برآورد تخمینی است و هیچ‌گونه تعهد مالی یا تأیید اعتبار تلقی نمی‌گردد.', 'negar-shop' ); ?></p>
			</div>
		</div>
	</div>
</section>
