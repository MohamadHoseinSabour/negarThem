<?php
/**
 * My account addresses in the Negar Shop account interface.
 *
 * @package Negar_Shop
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'آدرس صورتحساب', 'negar-shop' ),
			'shipping' => __( 'آدرس ارسال', 'negar-shop' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'آدرس صورتحساب', 'negar-shop' ),
		),
		$customer_id
	);
}
?>

<p class="negar-account-addresses__intro">
	<?php esc_html_e( 'این نشانی‌ها به‌صورت پیش‌فرض برای ارسال سفارش و صورتحساب استفاده می‌شوند.', 'negar-shop' ); ?>
</p>

<div class="woocommerce-Addresses addresses">
	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php $address = wc_get_account_formatted_address( $name ); ?>
		<section class="woocommerce-Address woocommerce-Address--<?php echo esc_attr( $name ); ?>">
			<header class="woocommerce-Address-title title">
				<h2><?php echo esc_html( $address_title ); ?></h2>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit">
					<?php echo esc_html( $address ? __( 'ویرایش نشانی', 'negar-shop' ) : __( 'افزودن نشانی', 'negar-shop' ) ); ?>
				</a>
			</header>
			<address>
				<?php
				if ( $address ) {
					echo wp_kses_post( $address );
				} else {
					esc_html_e( 'هنوز نشانی‌ای برای این بخش ثبت نشده است.', 'negar-shop' );
				}

				do_action( 'woocommerce_my_account_after_my_address', $name );
				?>
			</address>
		</section>
	<?php endforeach; ?>
</div>
