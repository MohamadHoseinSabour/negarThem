<?php
/**
 * The template for displaying product content within loops
 * Exact match to Demo 43 (product-cart-thirty-two-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id   = $product->get_id();
$is_on_sale   = $product->is_on_sale();
$in_stock     = $product->is_in_stock();
$regular_p    = $product->get_regular_price();
$sale_p       = $product->get_sale_price();
$current_p    = $product->get_price();

$discount_pct = 0;
if ( $is_on_sale && $regular_p && $sale_p ) {
	$discount_pct = round( ( ( (float) $regular_p - (float) $sale_p ) / (float) $regular_p ) * 100 );
}

$rating = $product->get_average_rating();
$rating_num = ( ! empty( $rating ) && floatval( $rating ) > 0 ) ? number_format( (float) $rating, 1 ) : '4.0';
$review_count = $product->get_review_count();
$review_text = ( $review_count > 0 ) ? $review_count . ' نظر' : 'بدون نظر';
?>

<div <?php wc_product_class( 'mb-0 col-6 col-md-4 col-lg-4 d-flex', $product ); ?>>
	<div class="product-cart-thirty-two-nk h-100 w-100">
		<div class="product-nk product-five-nk d-flex flex-column h-100 position-relative <?php echo ! $in_stock ? 'is-out-of-stock' : ''; ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
			
			<?php if ( ! $in_stock ) : ?>
				<span class="oos-badge">ناموجود</span>
			<?php endif; ?>

			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="image mxw-fit mx-auto d-block position-relative">
				<?php
				if ( has_post_thumbnail( $product_id ) ) {
					echo get_the_post_thumbnail(
						$product_id,
						'woocommerce_thumbnail',
						array(
							'alt'     => esc_attr( $product->get_name() ),
							'class'   => 'd-block w-auto',
							'loading' => 'lazy',
						)
					);
				} elseif ( function_exists( 'wc_placeholder_img' ) ) {
					echo wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'd-block w-auto' ) );
				}
				?>
			</a>

			<div class="comments-rate d-none d-md-flex align-items-center">
				<div class="comments f-iransans">
					<?php echo esc_html( $review_text ); ?>
				</div>
				<div class="line flex-grow-1"></div>
				<div class="rate d-flex align-items-center justify-content-end gap-1 gap-md-2">
					5/<?php echo esc_html( $rating_num ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" fill="none" viewBox="0 0 18 19" aria-hidden="true">
						<path fill="#FFC700" stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M11.543 4.407 12.6 6.522c.143.293.525.57.848.63l1.912.315c1.223.203 1.508 1.088.63 1.973l-1.492 1.492c-.248.248-.39.736-.308 1.088l.428 1.845c.337 1.455-.443 2.025-1.726 1.26L11.1 14.06c-.322-.195-.862-.195-1.185 0l-1.792 1.065c-1.283.758-2.063.195-1.725-1.26l.427-1.845c.083-.345-.06-.832-.307-1.088L5.025 9.44c-.877-.877-.592-1.763.63-1.973l1.913-.315c.322-.052.705-.337.847-.63l1.058-2.115c.562-1.147 1.492-1.147 2.07 0Z"></path>
						<path stroke="#FFC700" stroke-linecap="round" stroke-linejoin="round" d="M6 4.25H1.5m2.25 10.5H1.5m.75-5.25H1.5" opacity=".4"></path>
					</svg>
				</div>
			</div>

			<h2 class="title f-iran-yekan line-clamp-2">
				<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
			</h2>

			<div class="add-to-cart-price d-flex align-items-center justify-content-between gap-2">
				<?php if ( $product->is_type( 'variable' ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button product_type_variable add-to-cart d-flex align-items-center justify-content-center gap-2 f-iran-yekan fw-semibold" aria-label="<?php esc_attr_e( 'انتخاب گزینه‌ها', 'negar-shop' ); ?>">
						<i class="icon-bag-5 lh-1" aria-hidden="true"></i>
						<span class="d-none d-lg-block"><?php esc_html_e( 'خرید', 'negar-shop' ); ?></span>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart d-flex align-items-center justify-content-center gap-2 f-iran-yekan fw-semibold" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="<?php esc_attr_e( 'افزودن به سبد خرید', 'negar-shop' ); ?>" rel="nofollow">
						<i class="icon-bag-5 lh-1" aria-hidden="true"></i>
						<span class="d-none d-lg-block"><?php esc_html_e( 'خرید', 'negar-shop' ); ?></span>
					</a>
				<?php endif; ?>

				<div class="price">
					<?php if ( $is_on_sale && $regular_p ) : ?>
						<div class="off d-flex align-items-center justify-content-between gap-1">
							<del class="fw-light f-iransans"><?php echo esc_html( number_format_i18n( (float) $regular_p ) ); ?></del>
							<?php if ( $discount_pct > 0 ) : ?>
								<span class="d-flex align-items-center justify-content-center f-iran-yekan gap-1">
									<?php echo esc_html( $discount_pct ); ?>
									<i class="icon-discount-4 lh-1" aria-hidden="true"></i>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="price d-flex align-items-center gap-1">
						<?php echo esc_html( number_format_i18n( (float) $current_p ) ); ?>
						<span class="toman-symbol">
							<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
