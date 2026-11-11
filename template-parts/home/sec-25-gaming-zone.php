<?php
/**
 * Section 25: Gaming Combo Pack Showcase.
 *
 * Hotspots keep the reference layout, but every product surface is populated
 * from published WooCommerce products. Missing catalogue items stay inert and
 * never show a fabricated price or shop destination.
 *
 * @package Negar_Shop
 */

defined( 'ABSPATH' ) || exit;

$upload_dir = wp_upload_dir();
$kaveh_base = $upload_dir['baseurl'] . '/kaveh-demo';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$pack_banner = $kaveh_base . '/product-pack.png';
$kala_pack_icon = $kaveh_base . '/kala-pack.png';

$catalogue = function_exists( 'wc_get_products' ) ? wc_get_products(
	array(
		'status'  => 'publish',
		'limit'   => -1,
		'return'  => 'objects',
		'orderby' => 'date',
		'order'   => 'DESC',
	)
) : array();

$find_product = static function ( $needles ) use ( $catalogue ) {
	foreach ( $catalogue as $product ) {
		$name = $product->get_name();
		foreach ( $needles as $needle ) {
			if ( false !== stripos( $name, $needle ) ) {
				return $product;
			}
		}
	}

	return false;
};

$hotspots = array(
	array(
		'key'   => 'shadows',
		'class' => 'one line-layout--vertical ppnk-hs-61f16945-0',
		'style' => '--hs-top:auto;--hs-right:auto;--hs-bottom:13%;--hs-left:10%;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:72px;--hs-h:141px;--line-w:58px;--line-h:133px;--line-origin:100% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:-25deg;--box-x:-10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover',
		'needles' => array( 'Assassin', 'Shadows' ),
	),
	array(
		'key'   => 'mirage',
		'class' => 'two line-layout--vertical-mirror ppnk-hs-61f16945-1',
		'style' => '--hs-top:auto;--hs-right:13%;--hs-bottom:4%;--hs-left:auto;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:72px;--hs-h:141px;--line-w:58px;--line-h:133px;--line-origin:0% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:25deg;--box-x:10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover',
		'needles' => array( 'Assassin', 'Mirage' ),
	),
	array(
		'key'   => 'fc',
		'class' => 'three line-layout--horizontal ppnk-hs-61f16945-2',
		'style' => '--hs-top:20%;--hs-right:27%;--hs-bottom:auto;--hs-left:auto;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:141px;--hs-h:72px;--line-w:133px;--line-h:58px;--line-origin:100% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:25deg;--box-x:10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover',
		'needles' => array( 'EA Sports FC' ),
	),
	array(
		'key'   => 'yotei',
		'class' => 'four line-layout--horizontal-mirror ppnk-hs-61f16945-3',
		'style' => '--hs-top:12%;--hs-right:auto;--hs-bottom:auto;--hs-left:25%;--hs-anchor:translate(0);--hs-offset:translate(0px, 0px);--hs-rotate:0deg;--hs-w:141px;--hs-h:72px;--line-w:133px;--line-h:58px;--line-origin:0% 100%;--line-x:0px;--line-y:0px;--line-rotate:0deg;--line-diagonal-angle:-25deg;--box-x:-10%;--box-rotate:0deg;--line-end-w:76px;--line-end-h:76px;--line-end-rotate:0deg;--popup-img-rotate:0deg;--line-end-x:0px;--line-end-y:0px;--line-end-radius:0px 0px 0px 0px;--line-end-fit:cover',
		'needles' => array( 'Ghost of Yotei', 'Yotei' ),
	),
);

$matched_products = array();
foreach ( $hotspots as &$hotspot ) {
	$hotspot['product'] = $find_product( $hotspot['needles'] );
	if ( $hotspot['product'] ) {
		$matched_products[ $hotspot['product']->get_id() ] = $hotspot['product'];
	}
}
unset( $hotspot );

$gaming_category_url = get_term_link( 'gaming', 'product_cat' );
$gaming_category_url = is_wp_error( $gaming_category_url ) ? $shop_url : $gaming_category_url;
?>

<section class="products-pack-nk" aria-label="<?php esc_attr_e( 'محصولات گیمینگ', 'negar-shop' ); ?>">
	<div class="container">
		<div class="products-pack-nk__wrapper">
			<div class="row flex-column-reverse flex-lg-row gap-4 gap-lg-0">
				<div class="col-lg-8 products-pack-nk__media">
					<div class="products-pack-nk__image position-relative">
						<div class="products-pack-nk__image__bg position-absolute top-0 start-0 w-100 h-100" aria-hidden="true"></div>
						<img loading="lazy" decoding="async" src="<?php echo esc_url( $pack_banner ); ?>" alt="<?php esc_attr_e( 'پک گیمینگ', 'negar-shop' ); ?>" width="881" height="585" class="d-block w-100 position-relative">

						<?php foreach ( $hotspots as $hotspot ) :
							$product = $hotspot['product'];
							$image_id = $product ? $product->get_image_id() : 0;
							$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
							$price_html = $product ? $product->get_price_html() : '';
							$rating = $product ? (float) $product->get_average_rating() : 0;
							?>
							<div class="products-pack-nk__image__product position-absolute <?php echo esc_attr( $hotspot['class'] . ( $product ? '' : ' is-empty' ) ); ?>" style="<?php echo esc_attr( $hotspot['style'] ); ?>">
								<div class="line position-absolute top-0 start-0"></div>
								<div class="circle-wrap position-absolute bottom-0 <?php echo 'two' === $hotspot['key'] ? 'start-0' : 'end-0'; ?>">
									<div class="circle rounded-circle"></div>
									<?php if ( $product ) : ?>
										<div class="products-pack-nk__image__product__box <?php echo in_array( $hotspot['key'], array( 'fc', 'yotei' ), true ) ? 'bottom' : 'top'; ?> bg-white d-flex align-items-center position-absolute <?php echo in_array( $hotspot['key'], array( 'fc', 'yotei' ), true ) ? 'top-100' : 'bottom-100'; ?> <?php echo 'two' === $hotspot['key'] ? 'start-0 box-align-start' : 'end-0 box-align-end'; ?>">
											<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="stretched-link position-absolute top-0 end-0 w-100 h-100" aria-label="<?php echo esc_attr( $product->get_name() ); ?>"></a>
											<div class="image rounded-circle position-relative d-flex align-items-center justify-content-center flex-fill flex-grow-0 flex-shrink-0">
												<div class="img rounded-circle d-flex align-items-center justify-content-center">
													<?php if ( $image_url ) : ?><img loading="lazy" decoding="async" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="41" height="45" class="w-auto"><?php endif; ?>
												</div>
											</div>
											<div class="detail w-100 flex-fill flex-grow-0 flex-shrink-0">
												<h2 class="title f-iran-yekan line-clamp-2 mb-0"><?php echo esc_html( $product->get_name() ); ?></h2>
												<?php if ( $price_html || $rating > 0 ) : ?>
													<div class="price d-flex flex-column align-items-stretch gap-1">
														<?php if ( $price_html ) : ?><div class="price price--html d-flex align-items-center gap-2"><?php echo wp_kses_post( $price_html ); ?></div><?php endif; ?>
														<?php if ( $rating > 0 ) : ?><span class="text-2xs text-amber-500" aria-label="<?php esc_attr_e( 'امتیاز محصول', 'negar-shop' ); ?>">★ <?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span><?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="products-pack-nk__products position-relative h-100">
						<div class="bg position-absolute d-none d-lg-block"></div>
						<div class="products-pack-nk__products__heading d-flex align-items-center justify-content-between gap-2">
							<img loading="lazy" decoding="async" src="<?php echo esc_url( $kala_pack_icon ); ?>" alt="<?php esc_attr_e( 'محصول گیم', 'negar-shop' ); ?>" width="56" height="38">
							<div class="products-pack-nk__products__heading__category f-iran-yekan"><?php esc_html_e( 'محصول گیم', 'negar-shop' ); ?></div>
						</div>

						<div class="products-pack-nk__products__items d-flex flex-column scrollbar" data-scrollbar-after-effect="true">
							<?php if ( empty( $matched_products ) ) : ?>
								<p class="text-sm text-slate-500 text-center p-4"><?php esc_html_e( 'در حال حاضر محصول گیمینگی برای نمایش وجود ندارد.', 'negar-shop' ); ?></p>
							<?php else : ?>
								<?php foreach ( $matched_products as $product ) :
									$image_id = $product->get_image_id();
									$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
									$rating = (float) $product->get_average_rating();
									?>
									<div class="products-pack-nk__products__item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
										<div class="info d-flex align-items-center">
											<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="image position-relative flex-fill flex-grow-0 flex-shrink-0">
												<?php if ( $image_url ) : ?><img loading="lazy" decoding="async" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" width="63" height="70" class="d-block w-auto"><?php endif; ?>
											</a>
											<h2 class="title f-iran-yekan fw-semibold line-clamp-2 mb-0"><a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h2>
										</div>
										<?php if ( $rating > 0 ) : ?><div class="bottom d-flex align-items-center"><div class="line flex-grow-1"></div><div class="rate d-flex align-items-center gap-2 f-iransans"><span class="rate__text">5/<?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span><span aria-hidden="true">★</span></div></div><?php endif; ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<div class="products-pack-nk__products__order d-flex align-items-center gap-3">
							<div class="counter d-flex flex-column align-items-center f-iran-yekan fw-normal"><span><?php echo esc_html( count( $matched_products ) ); ?></span><?php esc_html_e( 'کالا', 'negar-shop' ); ?></div>
							<a href="<?php echo esc_url( $gaming_category_url ); ?>" class="btn btn-nk-danger ms-auto"><?php esc_html_e( 'مشاهده محصولات', 'negar-shop' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
