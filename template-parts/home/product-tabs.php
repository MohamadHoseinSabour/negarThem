<?php
/**
 * Product Tabs Component - Selected Home Appliances
 * Reference: Demo 43 Section 4
 * Heading: "محصولات منتخب"
 * Tabs: یخچال و فریزر | ماشین لباسشویی | سرخ‌کن بدون روغن | جاروبرقی
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_categories = array(
	'refrigerators' => array(
		'slug'       => 'refrigerators-freezers',
		'label'      => __( 'یخچال و فریزر', 'negar-shop' ),
		'panel_id'   => 'tab-panel-refrigerators',
		'section_id' => 'product-tabs-refrigerators',
	),
	'washing'       => array(
		'slug'       => 'washing-machines',
		'label'      => __( 'ماشین لباسشویی', 'negar-shop' ),
		'panel_id'   => 'tab-panel-washing',
		'section_id' => 'product-tabs-washers',
	),
	'airfryers'     => array(
		'slug'       => 'air-fryers',
		'label'      => __( 'سرخ‌کن بدون روغن', 'negar-shop' ),
		'panel_id'   => 'tab-panel-airfryers',
		'section_id' => 'product-tabs-airfryers',
	),
	'vacuums'       => array(
		'slug'       => 'vacuum-cleaners',
		'label'      => __( 'جاروبرقی', 'negar-shop' ),
		'panel_id'   => 'tab-panel-vacuums',
		'section_id' => 'product-tabs-vacuums',
	),
);

$tab_queries = array();
foreach ( $tab_categories as $key => $tab ) {
	if ( function_exists( 'negar_theme_product_section_is_configured' ) && negar_theme_product_section_is_configured( $tab['section_id'] ) ) {
		$tab_queries[ $key ] = new WP_Query(
			negar_theme_product_section_query_args(
				$tab['section_id'],
				array(
					'post_type'   => 'product',
					'post_status' => 'publish',
				)
			)
		);
	} else {
		$tab_queries[ $key ] = new WP_Query(
			negar_theme_catalog_visibility_query_args(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 5,
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => array( $tab['slug'] ),
						),
					),
				)
			)
		);
	}
}
?>

<section class="negar-product-tabs-section py-6" aria-label="<?php esc_attr_e( 'محصولات منتخب لوازم خانگی', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Section Header with Title, Tabs, and View All Link -->
		<div class="section-heading-one-nk">
			
			<!-- Right: Title & Subtitle -->
			<div class="title">
				<span class="highlight"><?php esc_html_e( 'محصولات', 'negar-shop' ); ?></span> <?php esc_html_e( 'منتخب', 'negar-shop' ); ?>
			</div>
			<div class="sub-title hidden sm:inline-block">
				<?php esc_html_e( 'برترین لوازم برقی خانگی با ضمانت اصالت کالا', 'negar-shop' ); ?>
			</div>

			<!-- Center: Tabs Navigation Pills -->
			<div class="product-tab-buttons flex items-center gap-1 bg-slate-100 p-1 rounded-2xl mx-1 sm:mx-3">
				<?php
				$is_first_tab = true;
				foreach ( $tab_categories as $key => $tab ) :
					$active_class = $is_first_tab ? 'active' : '';
					$is_first_tab = false;
					?>
					<button type="button" class="tab-trigger <?php echo esc_attr( $active_class ); ?> text-xs font-bold px-3.5 py-1.5 rounded-xl transition-all duration-200" data-target="#<?php echo esc_attr( $tab['panel_id'] ); ?>">
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="line">
				<span></span>
				<span></span>
			</div>

			<!-- Left: View All Button -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-nk-danger">
				<span><?php esc_html_e( 'مشاهده همه', 'negar-shop' ); ?></span>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</a>

		</div>

		<!-- Single-Row Panels Container (Max 5 items per panel) -->
		<div class="product-tab-panels">
			<?php
			$is_first_panel = true;
			foreach ( $tab_categories as $key => $tab ) :
				$query = $tab_queries[ $key ];
				$panel_class = $is_first_panel ? 'active' : 'hidden';
				$is_first_panel = false;
				?>
				<div id="<?php echo esc_attr( $tab['panel_id'] ); ?>" class="product-tab-panel <?php echo esc_attr( $panel_class ); ?>">
					<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
						<?php
						if ( $query->have_posts() ) :
							while ( $query->have_posts() ) :
								$query->the_post();
								if ( class_exists( 'Negar_Club_VIP_Products' ) && Negar_Club_VIP_Products::is_vip_product( get_the_ID() ) ) {
									continue;
								}
								?>
								<div class="product-grid-item">
									<?php get_template_part( 'template-parts/product/card' ); ?>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							?>
							<div class="col-span-full py-12 text-center text-slate-500 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
								<p class="m-0 text-sm font-medium"><?php esc_html_e( 'در حال حاضر محصولی در این دسته‌بندی قرار نگرفته است.', 'negar-shop' ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
