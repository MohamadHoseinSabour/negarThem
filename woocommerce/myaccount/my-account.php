<?php
/**
 * My Account Wrapper Template - Exact Match to Demo 43
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="account-page-nk py-4">
	<div class="container">
		<div class="row g-4">
			<!-- Account Sidebar Navigation -->
			<div class="col-12 col-lg-3">
				<?php do_action( 'woocommerce_account_navigation' ); ?>
			</div>

			<!-- Account Main Content -->
			<div class="col-12 col-lg-9">
				<div class="woocommerce-MyAccount-content bg-white p-4 p-md-5 rounded-4 border border-light-subtle shadow-sm">
					<?php do_action( 'woocommerce_account_content' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
