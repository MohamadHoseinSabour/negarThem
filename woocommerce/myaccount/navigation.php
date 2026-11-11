<?php
/**
 * My Account Navigation Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );

$current_user = wp_get_current_user();
$account_phone = get_user_meta( $current_user->ID, 'billing_phone', true );

$endpoint_icons = array(
	'dashboard'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
	'orders'          => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
	'downloads'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
	'edit-address'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
	'edit-account'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
	'customer-logout' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
);
?>

<nav class="woocommerce-MyAccount-navigation bg-surface border border-border rounded-2xl p-4 sm:p-6 shadow-sm mb-6" aria-label="<?php esc_attr_e( 'ناوبری حساب کاربری', 'negar-shop' ); ?>">
	
	<!-- User Profile Header -->
	<div class="user-profile-header flex items-center gap-3 pb-4 mb-4 border-b border-border">
		<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100 border border-border flex items-center justify-center flex-shrink-0">
			<?php echo get_avatar( $current_user->ID, 48, '', '', array( 'class' => 'w-full h-full object-cover' ) ); ?>
		</div>
		<div class="flex flex-col min-w-0">
			<span class="text-sm font-bold text-dark truncate"><?php echo esc_html( $current_user->display_name ); ?></span>
			<span class="text-xs text-muted font-mono truncate" dir="ltr"><?php echo esc_html( $account_phone ? $account_phone : __( 'حساب تأییدشده', 'negar-shop' ) ); ?></span>
		</div>
	</div>

	<!-- Menu Links -->
	<ul class="flex flex-col gap-1 text-xs sm:text-sm">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php
			$is_active = function_exists( 'wc_is_current_account_menu_item' )
				? wc_is_current_account_menu_item( $endpoint )
				: ( 'dashboard' === $endpoint ? ! is_wc_endpoint_url() : is_wc_endpoint_url( $endpoint ) );
			$icon      = isset( $endpoint_icons[ $endpoint ] ) ? $endpoint_icons[ $endpoint ] : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
			$is_logout = ( 'customer-logout' === $endpoint );
			?>
			<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--<?php echo esc_attr( $endpoint ); ?> <?php echo $is_active ? 'is-active' : ''; ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo $is_active ? 'bg-primary/10 text-primary font-bold' : ( $is_logout ? 'text-danger hover:bg-danger/10' : 'text-dark hover:bg-slate-50 hover:text-primary' ); ?>">
					<span class="nav-icon <?php echo $is_active ? 'text-primary' : ( $is_logout ? 'text-danger' : 'text-muted' ); ?>">
						<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
