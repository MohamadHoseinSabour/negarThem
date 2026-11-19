<?php
/**
 * Customer Club FAQ Accordion Section.
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$faqs = function_exists( 'negar_theme_club_setting' ) ? (array) negar_theme_club_setting( 'faqs', null, array() ) : array();

if ( empty( $faqs ) ) {
	return;
}
?>

<section class="negar-club-faq" aria-labelledby="club-faq-heading">
	<div class="negar-club-section-header">
		<span class="negar-club-section-eyebrow"><?php esc_html_e( 'پرسش‌های متداول', 'negar-shop' ); ?></span>
		<h2 id="club-faq-heading" class="negar-club-section-title"><?php esc_html_e( 'سوالات متداول باشگاه مشتریان و اقساط', 'negar-shop' ); ?></h2>
		<p class="negar-club-section-desc"><?php esc_html_e( 'پاسخ به سوالات رایج درباره طرح‌ها، مدارک و شرایط بازپرداخت اقساط.', 'negar-shop' ); ?></p>
	</div>

	<div class="negar-club-faq__accordion" role="presentation">
		<?php foreach ( $faqs as $index => $item ) : ?>
			<?php
			$q = ! empty( $item['question'] ) ? (string) $item['question'] : '';
			$a = ! empty( $item['answer'] ) ? (string) $item['answer'] : '';
			if ( empty( $q ) ) {
				continue;
			}
			$btn_id   = 'club-faq-btn-' . $index;
			$panel_id = 'club-faq-panel-' . $index;
			?>
			<div class="negar-club-faq__item">
				<h3 class="negar-club-faq__question">
					<button type="button" id="<?php echo esc_attr( $btn_id ); ?>" class="negar-club-faq__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
						<span><?php echo esc_html( $q ); ?></span>
						<svg class="negar-club-faq__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
				</h3>
				<div id="<?php echo esc_attr( $panel_id ); ?>" class="negar-club-faq__panel" role="region" aria-labelledby="<?php echo esc_attr( $btn_id ); ?>" hidden>
					<div class="negar-club-faq__answer">
						<?php echo wp_kses_post( wpautop( $a ) ); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
