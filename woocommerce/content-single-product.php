<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$categories_list = wc_get_product_category_list( $product->get_id(), '، ' );
$rating_count    = $product->get_rating_count();
$review_count    = $product->get_review_count();
$average         = $product->get_average_rating();
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'negar-product-detail-card', $product ); ?>>

	<!-- 2-Column Product Main Section -->
	<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm">

		<!-- Gallery Column (Right in RTL, 5 cols) -->
		<div class="lg:col-span-5 w-full">
			<?php get_template_part( 'woocommerce/single-product/product-gallery' ); ?>
		</div>

		<!-- Summary & Buy Box Column (Left in RTL, 7 cols) -->
		<div class="lg:col-span-7 w-full flex flex-col">

			<!-- Category and SKU Header -->
			<div class="flex items-center justify-between text-xs text-muted mb-2 pb-2 border-b border-border/50">
				<?php if ( $categories_list ) : ?>
					<span class="product-category hover:text-primary transition-colors">
						<?php echo wp_kses_post( $categories_list ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $product->get_sku() ) : ?>
					<span class="product-sku font-mono">
						<?php echo esc_html__( 'کد کالا:', 'negar-shop' ) . ' ' . esc_html( $product->get_sku() ); ?>
					</span>
				<?php endif; ?>
			</div>

			<!-- Product Title -->
			<h1 class="product_title entry-title text-xl sm:text-2xl font-black text-dark leading-snug mb-3">
				<?php the_title(); ?>
			</h1>

			<!-- Ratings & Review Count -->
			<div class="flex items-center gap-3 text-xs mb-4">
				<?php if ( wc_review_ratings_enabled() && $average > 0 ) : ?>
					<div class="flex items-center gap-1 text-amber-400">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<span class="font-bold text-dark"><?php echo esc_html( number_format_i18n( $average, 1 ) ); ?></span>
					</div>
					<span class="text-muted">|</span>
				<?php endif; ?>

				<span class="text-muted">
					<?php
					printf(
						/* translators: %s: review count */
						esc_html__( '%s دیدگاه خریداران', 'negar-shop' ),
						'<strong>' . number_format_i18n( $review_count ) . '</strong>'
					);
					?>
				</span>
			</div>

			<!-- Short Description -->
			<?php if ( $product->get_short_description() ) : ?>
				<div class="product-short-description text-xs sm:text-sm text-muted leading-relaxed mb-6 p-4 rounded-xl bg-slate-50 border border-border">
					<?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<!-- Buy Box Container -->
			<div class="negar-buy-box p-4 sm:p-6 rounded-xl border border-primary/20 bg-primary/5 mb-6">
				
				<!-- Price Row -->
				<div class="flex items-center justify-between mb-4 pb-4 border-b border-primary/10">
					<span class="text-xs sm:text-sm font-medium text-muted"><?php esc_html_e( 'قیمت محصول:', 'negar-shop' ); ?></span>
					<div class="flex flex-col items-end">
						<?php if ( $product->is_on_sale() && $product->get_regular_price() ) : ?>
							<del class="text-xs text-muted font-normal">
								<?php echo wp_kses_post( wc_price( $product->get_regular_price() ) ); ?>
							</del>
						<?php endif; ?>
						<span class="text-lg sm:text-2xl font-black text-primary">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</span>
					</div>
				</div>

				<!-- Stock Status -->
				<div class="flex items-center gap-2 text-xs mb-4">
					<?php if ( $product->is_in_stock() ) : ?>
						<span class="w-2.5 h-2.5 rounded-full bg-success inline-block"></span>
						<span class="text-success font-medium"><?php esc_html_e( 'موجود در انبار - آماده ارسال فوری', 'negar-shop' ); ?></span>
					<?php else : ?>
						<span class="w-2.5 h-2.5 rounded-full bg-danger inline-block"></span>
						<span class="text-danger font-medium"><?php esc_html_e( 'ناموجود در انبار', 'negar-shop' ); ?></span>
					<?php endif; ?>
				</div>

				<!-- Standard WooCommerce Add to Cart (Handles simple, variable swatches, external, grouped) -->
				<div class="negar-add-to-cart-wrapper">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>

			</div>

			<!-- Delivery & Trust Perks -->
			<?php get_template_part( 'woocommerce/single-product/delivery-perks' ); ?>

		</div>

	</div>

	<!-- Product Detailed Tabs (Description, Specifications, Reviews) -->
	<?php get_template_part( 'woocommerce/single-product/product-tabs' ); ?>

	<!-- Related Products Slider -->
	<?php get_template_part( 'woocommerce/single-product/related' ); ?>

</div>

<?php
/**
 * Hook: woocommerce_after_single_product.
 */
do_action( 'woocommerce_after_single_product' );
