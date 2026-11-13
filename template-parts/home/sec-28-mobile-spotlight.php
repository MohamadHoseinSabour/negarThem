<?php
/**
 * Section 28: Samsung Galaxy S25 FE Spotlight Grid (HOME-28)
 * Reference: Demo 43 Section 27 (Top: 13497px, Height: ~696px)
 * Heading: "پربازدید ترین محصولات - گلکسی اس ۲۵ اف ای"
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$s25_img    = $upload_dir['baseurl'] . '/kaveh-demo/products/samsung-s25-fe.png';

$focal_product = function_exists( 'negar_theme_product_pack_product' )
	? negar_theme_product_pack_product( 'mobile-spotlight', 'focal', array( 'Galaxy S25 FE', 'S25 FE', 'Galaxy S25' ) )
	: false;

if ( $focal_product ) {
	$focal_title      = $focal_product->get_name();
	$focal_short_desc = wp_strip_all_tags( $focal_product->get_short_description() );
	$focal_desc       = ! empty( $focal_short_desc ) ? $focal_short_desc : __( 'محصول منتخب و معرفی‌شده در صفحهٔ اصلی فروشگاه.', 'negar-shop' );
	$focal_url        = get_permalink( $focal_product->get_id() );
	$focal_img_id     = $focal_product->get_image_id();
	$focal_image      = $focal_img_id ? wp_get_attachment_image_url( $focal_img_id, 'large' ) : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_single' ) : '' );
	$focal_rating_val = (float) $focal_product->get_average_rating();
	$focal_rating     = $focal_rating_val > 0 ? '★ ' . number_format_i18n( $focal_rating_val, 1 ) : '';

	if ( $focal_product->is_type( 'variable' ) ) {
		$focal_min_price = $focal_product->get_variation_price( 'min', true );
		$focal_max_price = $focal_product->get_variation_price( 'max', true );
		if ( '' !== $focal_min_price && false !== $focal_min_price ) {
			if ( (float) $focal_min_price === 0.0 && (float) $focal_max_price === 0.0 ) {
				$focal_price_txt = __( 'رایگان', 'negar-shop' );
			} elseif ( $focal_min_price === $focal_max_price ) {
				$focal_price_txt = number_format_i18n( (float) $focal_min_price ) . ' ' . __( 'تومان', 'negar-shop' );
			} else {
				$focal_price_txt = sprintf( __( 'از %s تومان', 'negar-shop' ), number_format_i18n( (float) $focal_min_price ) );
			}
		} else {
			$focal_price_txt = '';
		}
		$focal_del_txt = '';
	} else {
		$focal_price_raw = $focal_product->get_price();
		$focal_price_txt = '';
		if ( '' !== $focal_price_raw ) {
			$focal_val       = (float) $focal_price_raw;
			$focal_price_txt = $focal_val > 0 ? number_format_i18n( $focal_val ) . ' ' . __( 'تومان', 'negar-shop' ) : __( 'رایگان', 'negar-shop' );
		}

		$focal_is_sale      = $focal_product->is_on_sale();
		$focal_reg_price    = (float) $focal_product->get_regular_price();
		$focal_sale_val     = (float) $focal_product->get_sale_price();
		$focal_is_real_sale = $focal_is_sale && $focal_reg_price > 0 && '' !== $focal_product->get_sale_price() && $focal_reg_price > $focal_sale_val;
		$focal_del_txt      = $focal_is_real_sale ? number_format_i18n( $focal_reg_price ) . ' ' . __( 'تومان', 'negar-shop' ) : '';
	}
} else {
	$focal_title     = __( 'محصولی برای نمایش موجود نیست', 'negar-shop' );
	$focal_desc      = __( 'در حال حاضر محصولی در این جایگاه انتخاب نشده است.', 'negar-shop' );
	$focal_url       = '';
	$focal_image     = function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src( 'woocommerce_single' ) : '';
	$focal_rating    = '';
	$focal_price_txt = '';
	$focal_del_txt   = '';
}

$adjacent_products = new WP_Query(
	negar_theme_catalog_visibility_query_args( negar_theme_product_section_is_configured( 'mobile-spotlight-products' )
		? negar_theme_product_section_query_args( 'mobile-spotlight-products', array( 'post_type' => 'product', 'post_status' => 'publish' ) )
		: array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) )
);
?>

<section class="negar-sec-28-s25-spotlight py-5" aria-label="<?php esc_attr_e( 'ویترین محصول ویژه', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Signature Section Header & Divider -->
		<div class="section-heading-one-nk">
			<div class="title">
				<span class="highlight">پربازدید</span> ترین محصولات
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'بر اساس مخاطبان', 'negar-shop' ); ?>
			</div>
			<div class="line">
				<span></span>
				<span></span>
			</div>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>
		</div>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
			
			<!-- Spotlight Featured Card (4 Cols) -->
			<div class="lg:col-span-4 bg-gradient-to-bl from-slate-50 via-white to-blue-50/40 rounded-3xl p-5 border border-slate-200 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between<?php echo ! $focal_product ? ' is-empty' : ''; ?>">
				<div class="flex items-center justify-between mb-2">
					<span class="text-3xs font-bold <?php echo $focal_product ? 'text-blue-600 bg-blue-100/70' : 'text-slate-500 bg-slate-100'; ?> px-2.5 py-0.5 rounded-full">
						<?php echo $focal_product ? esc_html__( 'پیشنهاد ویژه', 'negar-shop' ) : esc_html__( 'ویترین فروشگاه', 'negar-shop' ); ?>
					</span>
					<?php if ( ! empty( $focal_rating ) ) : ?>
						<span class="text-amber-500 font-bold text-xs flex items-center gap-1"><?php echo esc_html( $focal_rating ); ?></span>
					<?php endif; ?>
				</div>
				
				<div class="h-[200px] flex items-center justify-center p-2 my-1">
					<?php if ( $focal_product && $focal_url ) : ?>
						<a href="<?php echo esc_url( $focal_url ); ?>" class="d-flex align-items-center justify-content-center h-full w-full">
							<img src="<?php echo esc_url( $focal_image ); ?>" alt="<?php echo esc_attr( $focal_title ); ?>" class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-500">
						</a>
					<?php else : ?>
						<div class="d-flex align-items-center justify-content-center h-full w-full opacity-50">
							<img src="<?php echo esc_url( $focal_image ); ?>" alt="<?php echo esc_attr( $focal_title ); ?>" class="max-h-full max-w-full object-contain">
						</div>
					<?php endif; ?>
				</div>

				<div>
					<h3 class="text-sm md:text-base font-black <?php echo $focal_product ? 'text-[#212341]' : 'text-slate-400'; ?> mb-1 line-clamp-2">
						<?php if ( $focal_product && $focal_url ) : ?>
							<a href="<?php echo esc_url( $focal_url ); ?>" class="text-reset text-decoration-none"><?php echo esc_html( $focal_title ); ?></a>
						<?php else : ?>
							<span><?php echo esc_html( $focal_title ); ?></span>
						<?php endif; ?>
					</h3>
					<p class="text-2xs text-slate-500 line-clamp-2 leading-relaxed mb-3">
						<?php echo esc_html( $focal_desc ); ?>
					</p>
					<div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
						<?php if ( $focal_product ) : ?>
							<div>
								<?php if ( ! empty( $focal_del_txt ) ) : ?>
									<span class="text-3xs text-slate-400 block line-through"><?php echo esc_html( $focal_del_txt ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $focal_price_txt ) ) : ?>
									<span class="text-sm font-black text-rose-600"><?php echo esc_html( $focal_price_txt ); ?></span>
								<?php endif; ?>
							</div>
							<?php
							$focal_in_stock    = $focal_product->is_in_stock();
							$focal_purchasable = $focal_product->is_purchasable();
							if ( ! $focal_in_stock || ! $focal_purchasable ) :
							?>
								<span class="bg-slate-200 text-slate-500 font-bold text-xs px-4 py-2 rounded-full cursor-not-allowed">
									<?php esc_html_e( 'ناموجود', 'negar-shop' ); ?>
								</span>
							<?php elseif ( $focal_product->is_type( 'variable' ) || $focal_product->is_type( 'grouped' ) ) : ?>
								<a href="<?php echo esc_url( $focal_url ); ?>" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-full transition shadow-xs">
									<?php esc_html_e( 'مشاهده و انتخاب گزینه‌ها', 'negar-shop' ); ?>
								</a>
							<?php elseif ( $focal_product->is_type( 'external' ) ) : ?>
								<a href="<?php echo esc_url( $focal_url ); ?>" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-full transition shadow-xs">
									<?php esc_html_e( 'مشاهده محصول', 'negar-shop' ); ?>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $focal_url ); ?>" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-4 py-2 rounded-full transition shadow-xs">
									<?php esc_html_e( 'خرید آنی', 'negar-shop' ); ?>
								</a>
							<?php endif; ?>
						<?php else : ?>
							<div class="w-full text-center py-1">
								<span class="text-xs text-slate-400"><?php esc_html_e( 'ناموجود', 'negar-shop' ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- 3 Adjacent Cards in Single Row (8 Cols) -->
			<div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
				<?php
				if ( $adjacent_products->have_posts() ) :
					while ( $adjacent_products->have_posts() ) :
						$adjacent_products->the_post();
						?>
						<div class="product-grid-item">
							<?php get_template_part( 'template-parts/product/card' ); ?>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

		</div>

	</div>
</section>
