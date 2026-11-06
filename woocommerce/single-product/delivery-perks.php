<?php
/**
 * Single Product Delivery & Trust Perks Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$perks = negar_shop_get_trust_perks();
if ( empty( $perks ) ) {
	return;
}
?>

<div class="negar-delivery-perks bg-slate-50 border border-border rounded-xl p-4 my-6">
	<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
		<?php foreach ( $perks as $perk ) : ?>
			<div class="negar-perk-item flex items-start gap-3">
				<div class="negar-perk-icon p-2 rounded-lg bg-surface border border-border shadow-xs flex-shrink-0">
					<?php echo $perk['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="negar-perk-content">
					<h5 class="text-xs sm:text-sm font-bold text-dark mb-0.5"><?php echo esc_html( $perk['title'] ); ?></h5>
					<p class="text-xs text-muted leading-relaxed"><?php echo esc_html( $perk['description'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
