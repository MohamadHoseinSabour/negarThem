<?php
/**
 * Customer Club Plan Comparison Matrix.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$plans = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'plans', null, array() ) : array();
$allowed_ids = array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' );

$active_plans = array();
foreach ( $allowed_ids as $pid ) {
	if ( ! empty( $plans[ $pid ] ) && ! empty( $plans[ $pid ]['is_enabled'] ) ) {
		$active_plans[ $pid ] = $plans[ $pid ];
	}
}

if ( empty( $active_plans ) ) {
	return;
}
?>

<section class="negar-club-comparison" aria-labelledby="club-comparison-heading">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'مقایسه یک‌نگاه', 'negar-shop' ); ?></span>
		<h2 id="club-comparison-heading" class="negar-club-section-title"><?php esc_html_e( 'جدول مقایسه طرح‌های اقساطی', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'مشخصات کلیدی و تفاوت‌های طرح‌ها را به صورت تطبیقی مقایسه نمایید.', 'negar-shop' ); ?></p>
	</div>

	<div class="negar-club-comparison__table-wrapper" role="region" aria-label="<?php esc_attr_e( 'جدول مقایسه طرح‌های اقساطی', 'negar-shop' ); ?>" tabindex="0">
		<table class="negar-club-comparison__table">
			<thead>
				<tr>
					<th scope="col" class="negar-club-comparison__feature-col"><?php esc_html_e( 'مشخصه طرح', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pid => $pdata ) : ?>
						<th scope="col" class="negar-club-comparison__plan-col <?php echo ! empty( $pdata['is_featured'] ) ? 'is-featured' : ''; ?>">
							<strong><?php echo esc_html( $pdata['title'] ?? $pid ); ?></strong>
							<?php if ( ! empty( $pdata['badge'] ) ) : ?>
								<span class="badge"><?php echo esc_html( $pdata['badge'] ); ?></span>
							<?php endif; ?>
						</th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'سقف اعتبار خرید', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<td><strong><?php echo esc_html( number_format_i18n( (int) ( $pdata['ceiling'] ?? 0 ) ) ); ?> <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'پیش‌پرداخت اولیه (برآورد)', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<td><?php echo esc_html( number_format_i18n( (int) ( $pdata['initial_payment'] ?? 0 ) ) ); ?> <?php esc_html_e( 'تومان', 'negar-shop' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'مدت بازپرداخت', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<td><?php echo esc_html( number_format_i18n( (int) ( $pdata['duration_months'] ?? 1 ) ) ); ?> <?php esc_html_e( 'ماهه', 'negar-shop' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'مبلغ اصل تسهیلات در حداکثر سقف', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<?php
						$ceil = (int) ( $pdata['ceiling'] ?? 0 );
						$init = (int) ( $pdata['initial_payment'] ?? 0 );
						$fin  = max( 0, $ceil - $init );
						?>
						<td><?php echo esc_html( number_format_i18n( $fin ) ); ?> <?php esc_html_e( 'تومان', 'negar-shop' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'برآورد تقریبی هر قسط در سقف طرح', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<?php
						$ceil = (int) ( $pdata['ceiling'] ?? 0 );
						$init = (int) ( $pdata['initial_payment'] ?? 0 );
						$dur  = max( 1, (int) ( $pdata['duration_months'] ?? 1 ) );
						$fin  = max( 0, $ceil - $init );
						$inst = (int) round( $fin / $dur );
						?>
						<td><?php echo esc_html( number_format_i18n( $inst ) ); ?> <?php esc_html_e( 'تومان / ماه', 'negar-shop' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'شرایط و مدارک تضامین', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pdata ) : ?>
						<td><span class="text-muted"><?php esc_html_e( 'مشروط به ارزیابی هویت و ضوابط مصوب فروشگاه', 'negar-shop' ); ?></span></td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'اقدام در ماشین‌حساب', 'negar-shop' ); ?></th>
					<?php foreach ( $active_plans as $pid => $pdata ) : ?>
						<td>
							<button type="button" class="negar-club-btn-sm js-select-plan" data-plan-id="<?php echo esc_attr( $pid ); ?>">
								<?php esc_html_e( 'محاسبه این طرح', 'negar-shop' ); ?>
							</button>
						</td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
</section>
