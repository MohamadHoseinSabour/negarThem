<?php
/**
 * My Account Orders History Component
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="negar-account-orders">

	<div class="section-title mb-6 pb-3 border-b border-border">
		<h3 class="text-base font-bold text-dark flex items-center gap-2">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			<span><?php esc_html_e( 'تاریخچه سفارش‌های من', 'negar-shop' ); ?></span>
		</h3>
	</div>

	<?php if ( $has_orders ) : ?>

		<div class="overflow-x-auto rounded-xl border border-border">
			<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table w-full text-right text-xs sm:text-sm">
				<thead>
					<tr class="bg-slate-50 border-b border-border text-muted font-bold">
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?> py-3 px-4">
								<span class="nobr"><?php echo esc_html( $column_name ); ?></span>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody class="divide-y divide-border/60">
					<?php
					foreach ( $customer_orders->orders as $customer_order ) {
						$order      = wc_get_order( $customer_order );
						$item_count = $order->get_item_count();
						?>
						<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order hover:bg-slate-50/50 transition-colors">
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?> py-3 px-4" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( 'order-number' === $column_id ) : ?>
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="font-bold text-primary font-mono hover:underline">
											<?php echo esc_html( _x( '#', 'hash before order number', 'negar-shop' ) . $order->get_order_number() ); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
											<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
										</time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<?php
										$status_slug = $order->get_status();
										$status_name = wc_get_order_status_name( $status_slug );
										$status_classes = 'bg-slate-100 text-slate-700';
										if ( 'completed' === $status_slug ) {
											$status_classes = 'bg-rose-50 text-rose-700 border border-rose-200';
										} elseif ( 'processing' === $status_slug ) {
											$status_classes = 'bg-blue-50 text-blue-700 border border-blue-200';
										} elseif ( 'failed' === $status_slug || 'cancelled' === $status_slug ) {
											$status_classes = 'bg-rose-50 text-rose-700 border border-rose-200';
										}
										?>
										<span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold <?php echo esc_attr( $status_classes ); ?>">
											<?php echo esc_html( $status_name ); ?>
										</span>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<span class="font-bold text-dark">
											<?php
											/* translators: 1: formatted order total 2: total order items */
											printf( _n( '%1$s برای %2$s قلم', '%1$s برای %2$s قلم کالا', $item_count, 'negar-shop' ), $order->get_formatted_order_total(), $item_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</span>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) {
												echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button btn btn-outline btn-sm py-1.5 px-3 text-xs font-bold inline-block mr-1 ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
											}
										}
										?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination flex items-center justify-between mt-6">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button btn btn-outline btn-sm" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'قبلی', 'negar-shop' ); ?></a>
				<?php endif; ?>

				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button btn btn-outline btn-sm" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'بعدی', 'negar-shop' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	<?php else : ?>

		<div class="bg-surface border border-border rounded-xl p-8 text-center">
			<div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center text-muted">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/></svg>
			</div>
			<p class="text-sm font-medium text-dark mb-4"><?php esc_html_e( 'شما تاکنون هیچ سفارشی در فروشگاه ثبت نکرده‌اید.', 'negar-shop' ); ?></p>
			<a class="button btn btn-primary py-2 px-6 text-xs font-bold inline-block" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php esc_html_e( 'مشاهده کالاهای فروشگاه', 'negar-shop' ); ?>
			</a>
		</div>

	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
