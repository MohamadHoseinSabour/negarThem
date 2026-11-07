<?php
/**
 * Single Product Tabs Component (Specifications, Description, Reviews) - 100% Matching Demo 43
 *
 * @package Negar_Shop
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $post;

if ( ! $product instanceof WC_Product ) {
	return;
}

$description  = $product->get_description();
$specs        = negar_shop_get_product_specs( $product );
$review_count = $product->get_review_count();
$rating       = $product->get_average_rating();
?>

<!-- Tab Navigation Headers -->
<ul class="kav-single-product-tabs d-flex gap-1 p-2 bg-white rounded-2xl border border-slate-100 shadow-2xs mb-5 list-unstyled">
	<li class="active d-flex align-items-center gap-2 letter-spacing-2 px-4 py-2.5 rounded-xl cursor-pointer text-xs sm:text-sm font-bold transition-all text-rose-600 bg-rose-50" data-target="#info-product-tab">
		<i class="icon-stars-2 lh-1"></i>
		<span><?php esc_html_e( 'مشخصات فنی', 'negar-shop' ); ?></span>
	</li>
	<?php if ( ! empty( $description ) ) : ?>
		<li class="d-flex align-items-center gap-2 letter-spacing-2 px-4 py-2.5 rounded-xl cursor-pointer text-xs sm:text-sm font-bold transition-all text-slate-600 hover:text-rose-600" data-target="#desc-product-tab">
			<i class="icon-document-text lh-1"></i>
			<span><?php esc_html_e( 'توضیحات و نقد تخصصی', 'negar-shop' ); ?></span>
		</li>
	<?php endif; ?>
	<li class="d-flex align-items-center gap-2 letter-spacing-2 px-4 py-2.5 rounded-xl cursor-pointer text-xs sm:text-sm font-bold transition-all text-slate-600 hover:text-rose-600" data-target="#comments-tab">
		<i class="icon-message lh-1"></i>
		<span><?php esc_html_e( 'دیدگاه‌های کاربران', 'negar-shop' ); ?></span>
		<span class="badge rounded-pill bg-slate-100 text-slate-600 text-[11px] px-2 py-0.5">
			<?php echo esc_html( number_format_i18n( $review_count ) ); ?>
		</span>
	</li>
</ul>

<!-- 1. Specifications Tab Panel -->
<div id="info-product-tab" class="kav-single-product-info-product bg-white rounded-2xl border border-slate-100 p-6 shadow-2xs mb-8">
	<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-4 pb-3 border-b border-slate-100">
		<i class="kav-single-product-heading-triangle icon-triangle-left text-rose-600 lh-1"></i>
		<h3 class="kav-single-product-heading-title fw-bold letter-spacing-4 text-base sm:text-lg text-slate-900 m-0">
			<?php esc_html_e( 'مشخصات فنی محصول', 'negar-shop' ); ?>
		</h3>
	</div>

	<div id="info-product">
		<ul class="kav-single-product-info-product-items d-flex flex-column list-unstyled m-0 p-0">
			<?php if ( ! empty( $specs ) ) : ?>
				<?php foreach ( $specs as $spec_item ) :
					$spec_label = is_array( $spec_item ) && isset( $spec_item['label'] ) ? $spec_item['label'] : '';
					$spec_value = is_array( $spec_item ) && isset( $spec_item['value'] ) ? $spec_item['value'] : (string) $spec_item;
				?>
					<li class="d-flex align-items-stretch gap-2 gap-md-4 py-3 border-b border-slate-50">
						<div class="label letter-spacing-2 text-xs sm:text-sm text-slate-400 font-medium w-48 shrink-0">
							<?php echo esc_html( $spec_label ); ?>
						</div>
						<div class="value letter-spacing-2 text-xs sm:text-sm text-slate-800 font-bold flex-1">
							<?php echo wp_kses_post( $spec_value ); ?>
						</div>
					</li>
				<?php endforeach; ?>
			<?php else : ?>
				<li class="py-4 text-xs sm:text-sm text-slate-500">
					<?php esc_html_e( 'مشخصات فنی تکمیلی برای این محصول درج نشده است.', 'negar-shop' ); ?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</div>

<!-- 2. Description Tab Panel -->
<?php if ( ! empty( $description ) ) : ?>
	<div id="desc-product-tab" class="kav-single-product-description bg-white rounded-2xl border border-slate-100 p-6 shadow-2xs mb-8 d-none">
		<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-4 pb-3 border-b border-slate-100">
			<i class="icon-document-text text-rose-600 lh-1"></i>
			<h3 class="kav-single-product-heading-title fw-bold text-base sm:text-lg text-slate-900 m-0">
				<?php esc_html_e( 'معرفی و نقد و بررسی محصول', 'negar-shop' ); ?>
			</h3>
		</div>
		<div class="prose max-w-none text-xs sm:text-sm text-slate-700 leading-loose">
			<?php echo wp_kses_post( wpautop( $description ) ); ?>
		</div>
	</div>
<?php endif; ?>

<!-- 3. Reviews Tab Panel -->
<div id="comments-tab" class="kav-single-product-comments bg-white rounded-2xl border border-slate-100 p-6 shadow-2xs mb-8 d-none">
	<div class="kav-single-product-heading d-flex align-items-center gap-2 mb-4 pb-3 border-b border-slate-100">
		<i class="icon-message text-rose-600 lh-1"></i>
		<h3 class="kav-single-product-heading-title fw-bold text-base sm:text-lg text-slate-900 m-0">
			<?php esc_html_e( 'نظرات و بازخورد خریداران', 'negar-shop' ); ?>
		</h3>
	</div>

	<?php
	if ( comments_open() || pings_open() ) {
		comments_template();
	} else {
		?>
		<p class="text-xs sm:text-sm text-slate-500 py-3">
			<?php esc_html_e( 'ثبت دیدگاه برای این کالا بسته شده است.', 'negar-shop' ); ?>
		</p>
		<?php
	}
	?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const tabs = document.querySelectorAll('.kav-single-product-tabs li');
	const panels = [
		document.getElementById('info-product-tab'),
		document.getElementById('desc-product-tab'),
		document.getElementById('comments-tab')
	].filter(Boolean);

	tabs.forEach(tab => {
		tab.addEventListener('click', function() {
			tabs.forEach(t => {
				t.classList.remove('active', 'text-rose-600', 'bg-rose-50');
				t.classList.add('text-slate-600');
			});
			this.classList.add('active', 'text-rose-600', 'bg-rose-50');
			this.classList.remove('text-slate-600');

			const targetSelector = this.getAttribute('data-target');
			panels.forEach(p => p.classList.add('d-none'));
			const activePanel = document.querySelector(targetSelector);
			if (activePanel) {
				activePanel.classList.remove('d-none');
			}
		});
	});
});
</script>
