<?php
/**
 * The template for displaying product content in the single-product.php template
 * 100% Matching Kaveh Demo 43 (Style 5 Modern Tech)
 *
 * @package Negar_Shop
 * @version 2.0.0
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
$product_id      = $product->get_id();
$sku             = $product->get_sku();
$specs           = negar_shop_get_product_specs( $product );
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'insingle_pr kav-single-product-root kav-style5-has-fixed-nav', $product ); ?>>

	<div class="kav-single-product pt-2 pb-8">
		<div class="container">

			<!-- Breadcrumb Row -->
			<nav class="kav-single-product-breadcrumb mb-4" aria-label="<?php esc_attr_e( 'بردکرامب', 'negar-shop' ); ?>">
				<div class="breadcrumb-nk d-flex align-items-center">
					<?php negar_shop_breadcrumbs(); ?>
				</div>
			</nav>

			<!-- 3-Column Modern Tech Product Row (Style 5) -->
			<div class="row g-4 align-items-start">

				<!-- 1. Right Column: Product Image Gallery (4 Cols) -->
				<div class="col-12 col-lg-4">
					<div class="bg-white rounded-3xl border border-slate-100 p-4 shadow-2xs">
						<?php get_template_part( 'woocommerce/single-product/product-gallery' ); ?>
					</div>
				</div>

				<!-- 2. Center Column: Product Specs & Details (5 Cols) -->
				<div class="col-12 col-lg-5">
					<div class="kav-single-product-info bg-white rounded-3xl border border-slate-100 p-6 shadow-2xs">

						<!-- Rating & Badges Meta Row -->
						<div class="kav-single-product-meta-row d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
							
							<div class="kav-single-product-rating-row d-flex align-items-center flex-wrap gap-2">
								<div class="kav-single-product-rate d-flex align-items-center gap-1 text-amber-500 font-bold text-xs bg-amber-50 px-2.5 py-1 rounded-full">
									<span><?php echo esc_html( number_format_i18n( $average > 0 ? $average : 5.0, 1 ) ); ?></span>
									<i class="icon-star-5 lh-1 text-xs"></i>
								</div>
								<div class="kav-single-product-count-comments text-xs text-slate-400">
									(<?php echo esc_html( number_format_i18n( $review_count ) ); ?> <?php esc_html_e( 'دیدگاه خریداران', 'negar-shop' ); ?>)
								</div>
							</div>

							<!-- Badges -->
							<div class="kav-single-product-badges d-flex align-items-center gap-1.5 flex-wrap">
								<span class="kav-single-product-badge rounded-pill text-[10px] font-bold px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200">
									<?php esc_html_e( 'رجیستر شده', 'negar-shop' ); ?>
								</span>
								<span class="kav-single-product-badge rounded-pill text-[10px] font-bold px-2 py-0.5 bg-blue-500 text-white">
									<?php esc_html_e( 'اصل شرکتی', 'negar-shop' ); ?>
								</span>
								<span class="kav-single-product-badge rounded-pill text-[10px] font-bold px-2 py-0.5 bg-amber-500 text-white">
									<?php esc_html_e( 'آکبند', 'negar-shop' ); ?>
								</span>
							</div>

						</div>

						<!-- Product Title -->
						<h1 class="kav-single-product-title letter-spacing-2 fw-bold text-base sm:text-xl text-slate-900 leading-snug mb-3">
							<?php the_title(); ?>
						</h1>

						<!-- Category & SKU Meta -->
						<div class="kav-single-product-key-category d-flex align-items-center justify-content-between text-xs text-slate-400 pb-3 mb-4 border-b border-slate-100">
							<?php if ( $categories_list ) : ?>
								<div class="kav-single-product-category flex items-center gap-1">
									<span><?php esc_html_e( 'دسته‌بندی:', 'negar-shop' ); ?></span>
									<span class="text-rose-600 font-bold"><?php echo wp_kses_post( $categories_list ); ?></span>
								</div>
							<?php endif; ?>

							<?php if ( $sku ) : ?>
								<div class="kav-single-product-sku font-mono">
									<span><?php esc_html_e( 'شناسه:', 'negar-shop' ); ?></span>
									<span><?php echo esc_html( $sku ); ?></span>
								</div>
							<?php endif; ?>
						</div>

						<!-- Short Description / Key Features List -->
						<div class="d-flex justify-content-between align-items-start gap-3 mb-4 flex-wrap flex-column">
							<ul class="kav-single-product-info-options d-flex flex-column gap-2 list-unstyled p-0 m-0 text-xs text-slate-600">
								<li class="d-flex align-items-center gap-2">
									<i class="icon-truck-fast text-rose-600"></i>
									<span><?php esc_html_e( 'ارسال سریع اکسپرس در کمتر از ۲ ساعت در تهران', 'negar-shop' ); ?></span>
								</li>
								<li class="d-flex align-items-center gap-2">
									<i class="icon-box-time text-rose-600"></i>
									<span><?php esc_html_e( 'تحویل تیپاکس و پست پیشتاز سراسر کشور ۲ الی ۳ روز کاری', 'negar-shop' ); ?></span>
								</li>
								<li class="d-flex align-items-center gap-2">
									<i class="icon-shield-security text-rose-600"></i>
									<span><?php esc_html_e( 'گارانتی ۱۸ ماهه شرکتی و اصالت فیزیکی کالا', 'negar-shop' ); ?></span>
								</li>
							</ul>

							<!-- Key Specs Summary Grid -->
							<?php if ( ! empty( $specs ) ) : ?>
								<div class="kav-single-product-specs-wrap w-100 mt-3 pt-3 border-t border-slate-100">
									<div class="text-xs font-bold text-slate-800 mb-2"><?php esc_html_e( 'ویژگی‌های کلیدی:', 'negar-shop' ); ?></div>
									<ul class="kav-single-product-info-options-product kav-single-product-specs-grid list-unstyled m-0 p-0 grid grid-cols-2 gap-2 text-xs">
										<?php
										$count = 0;
										foreach ( $specs as $spec_item ) :
											if ( ++$count > 4 ) break;
											$s_label = is_array( $spec_item ) && isset( $spec_item['label'] ) ? $spec_item['label'] : '';
											$s_val   = is_array( $spec_item ) && isset( $spec_item['value'] ) ? $spec_item['value'] : (string) $spec_item;
										?>
											<li class="kav-single-product-specs-item p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col">
												<span class="kav-single-product-specs-label text-slate-400 text-[10px]"><?php echo esc_html( $s_label ); ?></span>
												<span class="kav-single-product-specs-value text-slate-800 font-bold truncate mt-0.5"><?php echo esc_html( $s_val ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</div>

				<!-- 3. Left Column: Modern Buy Box Sidebar (3 Cols) -->
				<div class="col-12 col-lg-3">
					<div class="kav-single-product-sidebar">
						<div class="kav-single-product-info-payment bg-white p-5 rounded-3xl border border-slate-100 shadow-2xs">

							<!-- Stock Status -->
							<div class="kav-single-product-info-payment-title letter-spacing-2 d-flex align-items-center mb-3 pb-3 border-b border-slate-100">
								<?php if ( $product->is_in_stock() ) : ?>
									<span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block me-2 animate-pulse"></span>
									<span class="kav-style5-stock-text text-emerald-600 font-bold text-xs"><?php esc_html_e( 'موجود در انبار و آماده ارسال', 'negar-shop' ); ?></span>
								<?php else : ?>
									<span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block me-2"></span>
									<span class="kav-style5-stock-text text-rose-600 font-bold text-xs"><?php esc_html_e( 'در حال حاضر ناموجود', 'negar-shop' ); ?></span>
								<?php endif; ?>
							</div>

							<!-- Seller Info Row -->
							<div class="seller-info d-flex align-items-center justify-between text-xs text-slate-600 mb-4 pb-3 border-b border-slate-50">
								<div class="d-flex align-items-center gap-1.5">
									<i class="icon-shop text-slate-400"></i>
									<span><?php esc_html_e( 'فروشنده:', 'negar-shop' ); ?></span>
									<span class="font-bold text-slate-800"><?php esc_html_e( 'کاوه دیجیتال', 'negar-shop' ); ?></span>
								</div>
								<span class="badge bg-emerald-50 text-emerald-600 text-[10px] px-2 py-0.5 rounded-full font-bold">
									<?php esc_html_e( '۱۰۰٪ رضایت', 'negar-shop' ); ?>
								</span>
							</div>

							<!-- Price Output Stack -->
							<div class="kav-single-product-info-payment-price mb-4">
								<div class="text-xs text-slate-400 mb-1"><?php esc_html_e( 'قیمت نهایی برای شما:', 'negar-shop' ); ?></div>
								<div class="d-flex align-items-baseline justify-content-between">
									<div class="kav-style5-price-stack fw-bold letter-spacing-4 d-flex align-items-baseline gap-1 text-rose-600 font-black text-xl sm:text-2xl">
										<?php echo wp_kses_post( $product->get_price_html() ); ?>
									</div>
								</div>
							</div>

							<!-- Standard WooCommerce Add To Cart Form (Supports Simple & Variable) -->
							<div class="kav-single-product-style5-cart">
								<?php woocommerce_template_single_add_to_cart(); ?>
							</div>

							<!-- Better Price Found Modal Trigger -->
							<div class="kav-single-product-more-reasonable-price d-flex align-items-center justify-content-between gap-2 mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
								<div class="text d-flex align-items-center gap-1">
									<i class="icon-coins text-amber-500"></i>
									<span><?php esc_html_e( 'قیمت مناسب‌تری سراغ دارید؟', 'negar-shop' ); ?></span>
								</div>
								<button type="button" class="btn p-0 border-0 bg-transparent text-rose-600 font-bold hover:underline text-[11px]" onclick="alert('پیشنهاد قیمت شما دریافت شد.')">
									<?php esc_html_e( 'اطلاع دهید', 'negar-shop' ); ?>
								</button>
							</div>

						</div>
					</div>
				</div>

			</div><!-- /.row -->

			<!-- 4. Delivery & Trust Options Bar (4 Cards) -->
			<?php get_template_part( 'woocommerce/single-product/delivery-perks' ); ?>

			<!-- 5. Product Tabs (Specifications, Description, Reviews) -->
			<?php get_template_part( 'woocommerce/single-product/product-tabs' ); ?>

			<!-- 6. Related Products Carousel Slider -->
			<?php get_template_part( 'woocommerce/single-product/related' ); ?>

		</div><!-- /.container -->
	</div><!-- /.kav-single-product -->

	<!-- 7. Sticky Bottom Add-to-Cart Bar (Style 5) -->
	<?php get_template_part( 'woocommerce/single-product/sticky-bottom-cart' ); ?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
