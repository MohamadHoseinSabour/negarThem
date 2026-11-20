<?php
/**
 * Customer Club Timeline / Steps Section.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$steps = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'copy', 'timeline_steps', array() ) : array();

if ( empty( $steps ) ) {
	$steps = array(
		array(
			'step'        => 1,
			'title'       => __( 'بررسی و انتخاب طرح', 'negar-shop' ),
			'description' => __( 'آشنایی با شرایط اولیه و انتخاب طرح VIP باشگاه مشتریان متناسب با نیاز خرید.', 'negar-shop' ),
		),
		array(
			'step'        => 2,
			'title'       => __( 'پرداخت آنلاین پیش‌پرداخت', 'negar-shop' ),
			'description' => __( 'واریز آنلاین پیش‌پرداخت طرح انتخابی از طریق درگاه‌های امن ووکامرس و فعال‌سازی سقف خرید.', 'negar-shop' ),
		),
		array(
			'step'        => 3,
			'title'       => __( 'مراجعه به فروشگاه حضوری', 'negar-shop' ),
			'description' => __( 'مراجعه حضوری به فروشگاه نگار، انتخاب کالاهای لوازم خانگی و کسر پیش‌پرداخت از فاکتور.', 'negar-shop' ),
		),
		array(
			'step'        => 4,
			'title'       => __( 'تسویه اقساط در فروشگاه', 'negar-shop' ),
			'description' => __( 'مدیریت و پرداخت اقساط باقیمانده به صورت حضوری و با هماهنگی کارشناسان فروشگاه.', 'negar-shop' ),
		),
	);
}
?>

<section class="negar-club-steps" aria-labelledby="club-steps-heading">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'مراحل استفاده', 'negar-shop' ); ?></span>
		<h2 id="club-steps-heading" class="negar-club-section-title"><?php esc_html_e( 'مسیر دریافت و تسویه تسهیلات', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'چهار گام ساده و شفاف از برآورد اولیه تا تسویه نهایی اقساط.', 'negar-shop' ); ?></p>
	</div>

	<div class="negar-club-steps__timeline">
		<?php foreach ( $steps as $index => $step_data ) : ?>
			<?php
			$num   = ! empty( $step_data['step'] ) ? (int) $step_data['step'] : ( $index + 1 );
			$title = ! empty( $step_data['title'] ) ? (string) $step_data['title'] : '';
			$desc  = ! empty( $step_data['description'] ) ? (string) $step_data['description'] : '';
			?>
			<div class="negar-club-step-item">
				<div class="negar-club-step-item__marker" aria-hidden="true">
					<span><?php echo esc_html( number_format_i18n( $num ) ); ?></span>
				</div>
				<div class="negar-club-step-item__content">
					<h3 class="negar-club-step-item__title"><?php echo esc_html( $title ); ?></h3>
					<p class="negar-club-step-item__desc"><?php echo esc_html( $desc ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="negar-club-steps__notice" role="note">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
		<span><?php esc_html_e( 'توجه: سامانه در مرحله پیش‌نمایش طرح‌ها و ارزیابی زیرساخت است. پیش‌پرداخت واریزی شما بابت خرید کالای فیزیکی در فروشگاه حضوری منظور می‌گردد و تسویه اقساط به صورت حضوری انجام می‌شود.', 'negar-shop' ); ?></span>
	</div>
</section>
