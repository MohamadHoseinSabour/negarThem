<?php
/**
 * Customer Club Three-Tier Plan Cards.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$plans = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'plans', null, array() ) : array();
$allow_applications = function_exists( 'negar_theme_club_setting' ) ? (bool) negar_theme_club_setting( 'allow_applications', null, false ) : false;

$allowed_ids = array( 'negar_aghaz', 'negar_plus', 'negar_vizhe' );
?>

<section id="club-plans" class="negar-club-plans" aria-labelledby="club-plans-heading">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'پلن‌های تسهیلات', 'negar-shop' ); ?></span>
		<h2 id="club-plans-heading" class="negar-club-section-title"><?php esc_html_e( 'طرح‌های سه‌گانه خرید اقساطی', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'شرایط هر طرح را بررسی کرده و مناسب‌ترین گزینه را بر اساس سقف خرید مورد نیازتان انتخاب فرمایید.', 'negar-shop' ); ?></p>
	</div>

	<div class="negar-club-plans__grid">
		<?php foreach ( $allowed_ids as $p_id ) : ?>
			<?php
			if ( empty( $plans[ $p_id ] ) || empty( $plans[ $p_id ]['is_enabled'] ) ) {
				continue;
			}
			$plan            = $plans[ $p_id ];
			$title           = ! empty( $plan['title'] ) ? (string) $plan['title'] : $p_id;
			$subtitle        = ! empty( $plan['subtitle'] ) ? (string) $plan['subtitle'] : '';
			$initial_payment = isset( $plan['initial_payment'] ) ? (int) $plan['initial_payment'] : 0;
			$ceiling         = isset( $plan['ceiling'] ) ? (int) $plan['ceiling'] : 0;
			$duration        = isset( $plan['duration_months'] ) ? (int) $plan['duration_months'] : 1;
			$badge           = ! empty( $plan['badge'] ) ? (string) $plan['badge'] : '';
			$is_featured     = ! empty( $plan['is_featured'] );
			$features        = isset( $plan['features'] ) && is_array( $plan['features'] ) ? $plan['features'] : array();
			?>
			<article class="negar-club-plan-card <?php echo $is_featured ? 'negar-club-plan-card--featured' : ''; ?>" data-plan-id="<?php echo esc_attr( $p_id ); ?>">
				<?php if ( ! empty( $badge ) ) : ?>
					<div class="negar-club-plan-card__badge" aria-label="<?php echo esc_attr( $badge ); ?>">
						<span><?php echo esc_html( $badge ); ?></span>
					</div>
				<?php endif; ?>

				<div class="negar-club-plan-card__header">
					<h3 class="negar-club-plan-card__title"><?php echo esc_html( $title ); ?></h3>
					<?php if ( ! empty( $subtitle ) ) : ?>
						<p class="negar-club-plan-card__subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>

				<div class="negar-club-plan-card__pricing">
					<div class="negar-club-plan-card__ceiling">
						<span class="negar-club-plan-card__ceiling-label"><?php esc_html_e( 'سقف اعتبار خرید', 'negar-shop' ); ?></span>
						<div class="negar-club-plan-card__ceiling-val">
							<strong><?php echo esc_html( number_format_i18n( $ceiling ) ); ?></strong>
							<span><?php esc_html_e( 'تومان', 'negar-shop' ); ?></span>
						</div>
					</div>
					<div class="negar-club-plan-card__meta">
						<div class="negar-club-plan-card__meta-item">
							<span><?php esc_html_e( 'پیش‌پرداخت اولیه:', 'negar-shop' ); ?></span>
							<strong><?php echo esc_html( number_format_i18n( $initial_payment ) ); ?> <?php esc_html_e( 'تومان', 'negar-shop' ); ?></strong>
						</div>
						<div class="negar-club-plan-card__meta-item">
							<span><?php esc_html_e( 'مدت بازپرداخت:', 'negar-shop' ); ?></span>
							<strong><?php echo esc_html( number_format_i18n( $duration ) ); ?> <?php esc_html_e( 'ماهه', 'negar-shop' ); ?></strong>
						</div>
					</div>
				</div>

				<?php if ( ! empty( $features ) ) : ?>
					<ul class="negar-club-plan-card__features" aria-label="<?php echo esc_attr( sprintf( __( 'ویژگی‌های %s', 'negar-shop' ), $title ) ); ?>">
						<?php foreach ( $features as $feat ) : ?>
							<li>
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
								<span><?php echo esc_html( $feat ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="negar-club-plan-card__footer">
					<button type="button" class="negar-club-btn <?php echo $is_featured ? 'negar-club-btn--primary' : 'negar-club-btn--outline'; ?> js-select-plan" data-plan-id="<?php echo esc_attr( $p_id ); ?>" data-plan-title="<?php echo esc_attr( $title ); ?>">
						<span><?php esc_html_e( 'انتخاب و برآورد در ماشین‌حساب', 'negar-shop' ); ?></span>
					</button>
					<span class="negar-club-plan-card__status">
						<?php if ( ! $allow_applications ) : ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
							<?php esc_html_e( 'پیش‌نمایش شرایط (پذیرش به زودی)', 'negar-shop' ); ?>
						<?php else : ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 10"></polyline></svg>
							<?php esc_html_e( 'امکان برآورد آنلاین', 'negar-shop' ); ?>
						<?php endif; ?>
					</span>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
