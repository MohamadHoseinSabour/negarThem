<?php
/**
 * Single Product Tabs Component (Overview, Specifications, Reviews)
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;

if ( ! $product instanceof WC_Product ) {
	return;
}

$description = $product->get_description();
$specs       = negar_shop_get_product_specs( $product );
$review_count = $product->get_review_count();
$rating       = $product->get_average_rating();
?>

<div class="negar-product-tabs-section mt-12 bg-surface border border-border rounded-2xl p-6 sm:p-8 shadow-sm" id="negar-product-tabs">
	
	<!-- Tab Navigation Headers -->
	<div class="negar-tabs-nav flex items-center gap-4 border-b border-border pb-4 overflow-x-auto scrollbar-none" role="tablist">
		
		<?php if ( ! empty( $description ) ) : ?>
			<button type="button" class="negar-tab-btn text-sm sm:text-base font-bold text-muted hover:text-dark pb-3 -mb-4.5 border-b-2 border-transparent transition-all is-active" data-tab="tab-description" role="tab" aria-selected="true" aria-controls="tab-description">
				<?php esc_html_e( 'نقد و بررسی تخصصی', 'negar-shop' ); ?>
			</button>
		<?php endif; ?>

		<button type="button" class="negar-tab-btn text-sm sm:text-base font-bold text-muted hover:text-dark pb-3 -mb-4.5 border-b-2 border-transparent transition-all <?php echo empty( $description ) ? 'is-active' : ''; ?>" data-tab="tab-specs" role="tab" aria-selected="<?php echo empty( $description ) ? 'true' : 'false'; ?>" aria-controls="tab-specs">
			<?php esc_html_e( 'مشخصات فنی', 'negar-shop' ); ?>
		</button>

		<button type="button" class="negar-tab-btn text-sm sm:text-base font-bold text-muted hover:text-dark pb-3 -mb-4.5 border-b-2 border-transparent transition-all flex items-center gap-1.5" data-tab="tab-reviews" role="tab" aria-selected="false" aria-controls="tab-reviews">
			<span><?php esc_html_e( 'دیدگاه‌های خریداران', 'negar-shop' ); ?></span>
			<span class="text-xs bg-slate-100 text-muted px-2 py-0.5 rounded-full font-normal">
				<?php echo esc_html( number_format_i18n( $review_count ) ); ?>
			</span>
		</button>

	</div>

	<!-- Tab Content Panels -->
	<div class="negar-tabs-content pt-8">

		<!-- Panel 1: Description -->
		<?php if ( ! empty( $description ) ) : ?>
			<div class="negar-tab-panel prose prose-slate max-w-none text-sm sm:text-base text-dark leading-relaxed" id="tab-description" role="tabpanel">
				<?php echo apply_filters( 'the_content', $description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<!-- Panel 2: Technical Specifications Table -->
		<div class="negar-tab-panel <?php echo ! empty( $description ) ? 'hidden' : ''; ?>" id="tab-specs" role="tabpanel">
			<h4 class="text-base font-bold text-dark mb-4"><?php esc_html_e( 'مشخصات فنی و ویژگی‌های محصول', 'negar-shop' ); ?></h4>
			
			<?php if ( ! empty( $specs ) ) : ?>
				<div class="negar-specs-table-wrapper rounded-xl overflow-hidden border border-border">
					<table class="negar-specs-table w-full text-xs sm:text-sm text-right">
						<tbody>
							<?php foreach ( $specs as $index => $spec ) : ?>
								<tr class="<?php echo ( $index % 2 === 0 ) ? 'bg-slate-50' : 'bg-white'; ?> border-b border-border/60 last:border-b-0">
									<th class="py-3 px-4 font-medium text-muted w-1/3 sm:w-1/4 border-l border-border/40">
										<?php echo esc_html( $spec['label'] ); ?>
									</th>
									<td class="py-3 px-4 text-dark font-medium">
										<?php echo esc_html( $spec['value'] ); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else : ?>
				<p class="text-sm text-muted"><?php esc_html_e( 'مشخصات فنی تکمیلی برای این کالا ثبت نشده است.', 'negar-shop' ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Panel 3: Reviews -->
		<div class="negar-tab-panel hidden" id="tab-reviews" role="tabpanel">
			<div class="negar-reviews-container">
				<!-- Overall Score Header -->
				<div class="flex flex-wrap items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 border border-border mb-8">
					<div class="flex items-center gap-3">
						<span class="text-3xl font-black text-dark"><?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span>
						<div class="flex flex-col">
							<div class="negar-stars flex text-amber-400">
								<?php
								for ( $i = 1; $i <= 5; $i++ ) {
									if ( $i <= round( $rating ) ) {
										echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
									} else {
										echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
									}
								}
								?>
							</div>
							<span class="text-xs text-muted mt-0.5">
								<?php
								printf(
									/* translators: %s: review count */
									esc_html__( 'بر اساس %s دیدگاه ثبت‌شده', 'negar-shop' ),
									number_format_i18n( $review_count )
								);
								?>
							</span>
						</div>
					</div>
				</div>

				<!-- Standard Comments / Reviews Template -->
				<?php
				if ( comments_open() || pings_open() ) {
					comments_template();
				}
				?>
			</div>
		</div>

	</div>

</div>
