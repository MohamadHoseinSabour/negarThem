<?php
/**
 * The template for displaying product content in the single-product.php template
 * 100% Matching Kaveh Demo 43 (Style 5 Modern Tech)
 *
 * @package Negar_Shop
 * @version 2.5.0
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
$rating_count    = $product->get_review_count();
$average         = $product->get_average_rating();
$product_id      = $product->get_id();
$sku             = $product->get_sku();
$specs           = negar_shop_get_product_specs( $product );
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'insingle_pr kav-single-product-root kav-style5-has-fixed-nav', $product ); ?>>
	<div class="kav-single-product pt-2 pb-8">
		<div class="container">

			<!-- Breadcrumb Row -->
			<nav class="kav-single-product-breadcrumb mb-3 mb-lg-4" aria-label="<?php esc_attr_e( 'بردکرامب', 'negar-shop' ); ?>">
				<div class="breadcrumb-nk d-flex align-items-center mb-4">
					<ul class="d-flex align-items-center flex-nowrap">
						<li class="d-flex align-items-center flex-shrink-0">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="d-block d-flex align-items-center gap-2">
								<i class="iconly-Home lh-1"></i><?php esc_html_e( 'خانه', 'negar-shop' ); ?>
							</a>
						</li>
						<li class="d-flex align-items-center flex-shrink-0">
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="d-block text-nowrap">
								<?php esc_html_e( 'فروشگاه', 'negar-shop' ); ?>
							</a>
						</li>
						<?php
						$terms = wc_get_product_terms( $product_id, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
							$main_term = $terms[0];
							$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
							$ancestors = array_reverse( $ancestors );
							foreach ( $ancestors as $ancestor_id ) :
								$anc = get_term( $ancestor_id, 'product_cat' );
								if ( $anc && ! is_wp_error( $anc ) ) : ?>
									<li class="d-flex align-items-center flex-shrink-0">
										<a href="<?php echo esc_url( get_term_link( $anc ) ); ?>" class="d-block text-nowrap"><?php echo esc_html( $anc->name ); ?></a>
									</li>
								<?php endif;
							endforeach; ?>
							<li class="d-flex align-items-center flex-shrink-0 is-current" aria-current="page">
								<a href="<?php echo esc_url( get_term_link( $main_term ) ); ?>" class="d-block text-nowrap"><?php echo esc_html( $main_term->name ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</nav>

			<!-- Mobile Quick Actions -->
			<div class="kav-single-product-icons-mobile d-flex d-lg-none align-items-center ms-auto bg-white gap-4 mb-3">
				<button type="button" class="share d-flex align-items-center gap-2 letter-spacing-2" data-bs-toggle="modal" data-bs-target="#modal-kav-share">
					<?php esc_html_e( 'اشتراک گذاری', 'negar-shop' ); ?>
					<i class="icon-share-4 lh-1"></i>
				</button>
				<button type="button" class="like wlm-button d-flex align-items-center gap-2 letter-spacing-2 border-0 bg-transparent p-0" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'علاقه مندی', 'negar-shop' ); ?>">
					<i class="icon-heart-7 lh-1" aria-hidden="true"></i>
				</button>
				<span class="divider"></span>
				<button type="button" class="more d-flex align-items-center gap-2 letter-spacing-2 border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#modal-kav-share">
					<i class="icon-more-vert lh-1"></i>
				</button>
			</div>

			<!-- 3-Column Modern Tech Product Row (Style 5) -->
			<div class="row">

				<!-- 1. Column 4: Product Image Gallery -->
				<div class="col-lg-4">
					<?php get_template_part( 'woocommerce/single-product/product-gallery' ); ?>
				</div>

				<!-- 2. Column 5: Product Details, Specs & Mid Banners -->
				<div class="col-lg-5">
					<div class="kav-single-product-info">

						<!-- Rating & Badges Meta Row -->
						<div class="kav-single-product-meta-row">
							<div class="kav-single-product-rating-row d-flex align-items-center flex-wrap gap-2">
								<div class="kav-single-product-rate d-flex align-items-center gap-1 letter-spacing-4">
									<?php echo esc_html( number_format_i18n( $average > 0 ? $average : 5.0, 1 ) ); ?>
									<i class="icon-star-5 lh-1"></i>
								</div>
								<div class="kav-single-product-count-comments letter-spacing-2">
									(<?php echo esc_html( number_format_i18n( $rating_count ) ); ?> <?php esc_html_e( 'نقد و بررسی‌ها', 'negar-shop' ); ?>)
								</div>
							</div>
							<div class="kav-single-product-badges d-flex align-items-center flex-wrap">
								<div class="kav-single-product-badge rounded-pill d-flex align-items-center justify-content-center letter-spacing-2" style="background-color:#ffffff;color:#000000;border:1px solid #e9e9e9;">
									<?php esc_html_e( 'ریجستر شده', 'negar-shop' ); ?>
								</div>
								<div class="kav-single-product-badge rounded-pill d-flex align-items-center justify-content-center letter-spacing-2" style="background-color:#298ef8;color:#ffffff;border:0;">
									<?php esc_html_e( 'نات اکتیو', 'negar-shop' ); ?>
								</div>
								<div class="kav-single-product-badge rounded-pill d-flex align-items-center justify-content-center letter-spacing-2" style="background-color:#ff8640;color:#ffffff;border:0;">
									<?php esc_html_e( 'آکبند', 'negar-shop' ); ?>
								</div>
							</div>
						</div>

						<!-- Product Title -->
						<h1 class="kav-single-product-title letter-spacing-2 fw-bold"><?php the_title(); ?></h1>

						<!-- Category Meta -->
						<div class="kav-single-product-key-category d-flex align-items-center gap-3 letter-spacing-2 pb-4 mb-4">
							<?php if ( $categories_list ) : ?>
								<div class="kav-single-product-category">
									<?php esc_html_e( 'دسته‌بندی:', 'negar-shop' ); ?>
									<?php echo wp_kses_post( $categories_list ); ?>
								</div>
							<?php endif; ?>
						</div>

						<!-- Highlights & Mini Specs Grid -->
						<div class="d-flex justify-content-between align-items-start gap-3 gap-sm-2 mb-3 flex-wrap flex-column flex-sm-row">
							<ul class="kav-single-product-info-options d-flex flex-column gap-2">
								<li class="d-flex align-items-center gap-2 fw-normal letter-spacing-4">
									<?php esc_html_e( 'ارسال از ۲ روز کاری', 'negar-shop' ); ?>
								</li>
								<li class="d-flex align-items-center gap-2 fw-normal letter-spacing-4">
									<?php esc_html_e( 'ارسال سریع در شهر تهران', 'negar-shop' ); ?>
								</li>
								<li class="d-flex align-items-center gap-2 fw-normal letter-spacing-4">
									<?php esc_html_e( 'گارانتی اصالت و سلامت فیزیکی', 'negar-shop' ); ?>
								</li>
							</ul>

							<?php if ( ! empty( $specs ) ) : ?>
								<div class="kav-single-product-specs-wrap">
									<ul class="kav-single-product-info-options-product kav-single-product-specs-grid list-unstyled mb-0">
										<?php
										$s_count = 0;
										foreach ( $specs as $spec_item ) :
											if ( ++$s_count > 3 ) break;
											$s_label = is_array( $spec_item ) && isset( $spec_item['label'] ) ? $spec_item['label'] : '';
											$s_val   = is_array( $spec_item ) && isset( $spec_item['value'] ) ? $spec_item['value'] : (string) $spec_item;
										?>
											<li class="kav-single-product-specs-item letter-spacing-2">
												<span class="kav-single-product-specs-label"><?php echo esc_html( $s_label ); ?></span>
												<span class="kav-single-product-specs-value"><?php echo esc_html( $s_val ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
									<a href="#info-product-tab" class="kav-single-product-specs-more d-flex align-items-center justify-content-center gap-1 mt-2 letter-spacing-2 text-decoration-none">
										<?php esc_html_e( 'مشاهده همه ویژگی ها', 'negar-shop' ); ?>
										<i class="icon-arrow-bottom lh-1"></i>
									</a>
								</div>
							<?php endif; ?>
						</div>

						<!-- Mid Banners (Style 5) -->
						<div class="row kav-style5-banners">
							<div class="col-6">
								<a href="#" class="d-block">
									<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/banner-01.png' ); ?>" alt="" width="240" height="108" class="d-block w-100">
								</a>
							</div>
							<div class="col-6">
								<a href="#" class="d-block">
									<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/banner-02-1-1.png' ); ?>" alt="" width="240" height="108" class="d-block w-100">
								</a>
							</div>
						</div>

					</div><!-- /.kav-single-product-info -->
				</div>

				<!-- 3. Column 3: Sidebar Buy Box -->
				<div class="col-lg-3">
					<div class="kav-single-product-sidebar">
						<div class="kav-single-product-info-payment bg-white p-3 mt-3 d-none d-lg-block">
							
							<!-- Stock Status with Bag Icon -->
							<div class="kav-single-product-info-payment-title letter-spacing-2 d-flex align-items-center mb-3">
								<img loading="lazy" src="<?php echo esc_url( NEGAR_SHOP_URI . '/assets/images/detail-product-7/bag.png' ); ?>" alt="" width="19" height="15" class="me-1">
								<span class="kav-style5-stock-text">
									<?php echo $product->is_in_stock() ? esc_html__( 'محصول موجود است', 'negar-shop' ) : esc_html__( 'ناموجود', 'negar-shop' ); ?>
								</span>
							</div>

							<!-- Add to Cart Form with Stepper -->
							<div class="kav-single-product-style5-cart">
								<div class="kav-single-product-info-payment-price d-flex align-items-center justify-content-between gap-2 kav-style5-sidebar-price">
									<div class="text"><?php esc_html_e( 'قیمت', 'negar-shop' ); ?></div>
									<div class="d-flex align-items-center kav-style5-price-wrap">
										<div class="kav-style5-price-stack fw-bold letter-spacing-4 kav-style5-price-stack--simple d-flex align-items-center gap-1">
											<?php if ( $product->is_on_sale() ) : ?>
												<del class="fw-normal text-muted f-iransans me-1" style="text-decoration: line-through; font-size: 14px; color: #a7a7a7;">
													<?php echo esc_html( number_format_i18n( (float) $product->get_regular_price() ) ); ?>
												</del>
												<div class="price kav-style5-price-output">
													<bdi dir="ltr"><?php echo esc_html( number_format_i18n( (float) $product->get_sale_price() ) ); ?></bdi>
												</div>
											<?php else : ?>
												<div class="price kav-style5-price-output">
													<bdi dir="ltr"><?php echo esc_html( number_format_i18n( (float) $product->get_price() ) ); ?></bdi>
												</div>
											<?php endif; ?>
											<div class="toman kav-style5-toman toman-symbol toman-symbol--modern">
												<svg width="19.5" height="18" viewBox="0 0 13 12" fill="#0a0a0a" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><use href="#kaveh-toman-mark"></use></svg>
											</div>
										</div>
									</div>
								</div>

								<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
									<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
										<div class="d-flex flex-column flex-xl-row gap-2 align-items-stretch kav-style5-qty-row">
											<div class="kav-single-product-info-payment-quantity d-flex align-items-center justify-content-between gap-1">
												<button type="button" class="p-0 border-0 bg-transparent" onclick="const i=document.getElementById('quantity'); if(i) { i.stepUp(); i.dispatchEvent(new Event('change')); }">
													<i class="icon-plus-6 lh-1"></i>
												</button>
												<div class="quantity">
													<label class="screen-reader-text" for="quantity"><?php echo esc_html( $product->get_name() ); ?> عدد</label>
													<input type="number" id="quantity" class="input-text qty text border-0 text-center" name="quantity" value="1" aria-label="<?php esc_attr_e( 'تعداد محصول', 'negar-shop' ); ?>" min="1" step="1" inputmode="numeric" autocomplete="off" />
												</div>
												<button type="button" class="p-0 border-0 bg-transparent" onclick="const i=document.getElementById('quantity'); if(i && i.value > 1) { i.stepDown(); i.dispatchEvent(new Event('change')); }">
													<i class="icon-minus-6 lh-1"></i>
												</button>
											</div>
											<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
												<?php esc_html_e( 'افزودن به سبد خرید', 'negar-shop' ); ?>
											</button>
										</div>
									</form>
								<?php else : ?>
									<div class="alert alert-warning text-center mt-3 py-2 small">
										<?php esc_html_e( 'در حال حاضر این محصول موجود نمی‌باشد.', 'negar-shop' ); ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Better Price Offer Row -->
							<div class="kav-single-product-more-reasonable-price d-flex align-items-center justify-content-between gap-2 mt-2">
								<div class="text d-flex align-items-center f-payda letter-spacing-2 fw-normal">
									<i class="icon-Coins lh-1"></i>
									<?php esc_html_e( 'قیمت بهتری پیدا کردید؟', 'negar-shop' ); ?>
								</div>
								<button type="button" class="f-payda fw-normal letter-spacing-2 p-0 border-0 bg-transparent kav-price-offer-trigger ms-auto" data-bs-toggle="modal" data-bs-target="#modal-register-price-offer">
									<?php esc_html_e( 'ثبت پیشنهاد', 'negar-shop' ); ?>
								</button>
							</div>

						</div><!-- /.kav-single-product-info-payment -->
					</div><!-- /.kav-single-product-sidebar -->
				</div>

			</div><!-- /.row -->

			<!-- 4. Delivery & Trust Options Bar (4 Cards) -->
			<?php get_template_part( 'woocommerce/single-product/delivery-perks' ); ?>

			<!-- 5. Product Tabs (Specifications, Comments, Questions) -->
			<?php get_template_part( 'woocommerce/single-product/product-tabs' ); ?>

			<!-- 6. Related Products Carousel Slider -->
			<?php get_template_part( 'woocommerce/single-product/related' ); ?>

		</div><!-- /.container -->
	</div><!-- /.kav-single-product -->

	<!-- 7. Sticky Bottom Add-to-Cart Bar (Style 5) -->
	<?php get_template_part( 'woocommerce/single-product/sticky-bottom-cart' ); ?>

	<!-- Modal: Product Video -->
	<div id="modal-kav-video" class="modal fade" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content modalkshare">
				<div class="modal-header">
					<p class="modal-title fs-5 mb-0"><?php esc_html_e( 'ویدیو معرفی محصول', 'negar-shop' ); ?></p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>"></button>
				</div>
				<div class="modal-body text-center p-3">
					<div class="ratio ratio-16x9">
						<iframe src="https://www.aparat.com/video/video/embed/videohash/klhi483/vt/frame" title="ویدیو معرفی محصول" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal: Share -->
	<div id="modal-kav-share" class="modal fade" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content modalkshare">
				<div class="modal-header">
					<p class="modal-title fs-5 mb-0"><?php esc_html_e( 'اشتراک گذاری', 'negar-shop' ); ?></p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>"></button>
				</div>
				<div class="modal-body text-center">
					<p class="text-muted small"><?php esc_html_e( 'لینک این محصول را با دوستان خود به اشتراک بگذارید:', 'negar-shop' ); ?></p>
					<div class="input-group my-3" dir="ltr">
						<button class="btn btn-outline-danger" type="button" onclick="navigator.clipboard.writeText('<?php echo esc_url( get_permalink() ); ?>'); alert('لینک کپی شد!');">
							<?php esc_html_e( 'کپی لینک', 'negar-shop' ); ?>
						</button>
						<input type="text" class="form-control text-start font-monospace small" value="<?php echo esc_url( get_permalink() ); ?>" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal: Price Offer -->
	<div id="modal-register-price-offer" class="modal fade" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content modalkshare">
				<div class="modal-header">
					<p class="modal-title fs-5 mb-0"><?php esc_html_e( 'ثبت پیشنهاد قیمت بهتر', 'negar-shop' ); ?></p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'بستن', 'negar-shop' ); ?>"></button>
				</div>
				<div class="modal-body">
					<form onsubmit="event.preventDefault(); alert('پیشنهاد شما با موفقیت ثبت شد.'); bootstrap.Modal.getInstance(document.getElementById('modal-register-price-offer')).hide();">
						<div class="mb-3">
							<label class="form-label small"><?php esc_html_e( 'قیمت پیشنهادی شما (تومان):', 'negar-shop' ); ?></label>
							<input type="number" class="form-control" placeholder="مثال: 5000000" required>
						</div>
						<div class="mb-3">
							<label class="form-label small"><?php esc_html_e( 'لینک فروشگاه دیگر:', 'negar-shop' ); ?></label>
							<input type="url" class="form-control" placeholder="https://..." dir="ltr">
						</div>
						<button type="submit" class="btn btn-danger w-100"><?php esc_html_e( 'ارسال گزارش', 'negar-shop' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
