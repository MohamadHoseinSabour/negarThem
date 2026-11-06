<?php
/**
 * Section 35: Shipping & Delivery Trust Marquee (HOME-35)
 * Reference: Demo 43 Section 34 (Top: 17167px, Height: ~80px)
 * Heading: "تیباکس - حمل و نقل به روش تیباکس"
 *
 * @package Negar_Shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$marquee_items = array(
	'ارسال سریع با تیپاکس به سراسر کشور',
	'ضمانت اصالت ۷ روزه کالا',
	'بسته‌بندی ایمن و ضدضربه',
	'پشتیبانی ۲۴ ساعته در ۷ روز هفته',
	'امکان پرداخت آنلاین امن شتاب',
	'تحویل اکسپرس کمتر از ۲ ساعت در تهران',
);
?>

<section class="negar-sec-35-marquee py-3 bg-slate-900 text-white overflow-hidden border-t border-slate-800" aria-label="<?php esc_attr_e( 'روش‌های ارسال و تضمین خرید', 'negar-shop' ); ?>">
	<div class="relative w-full overflow-hidden flex whitespace-nowrap">
		
		<div class="marquee-track flex items-center gap-12 text-xs font-bold text-slate-300 animate-marquee select-none">
			<?php for ( $loop = 0; $loop < 3; $loop++ ) : ?>
				<?php foreach ( $marquee_items as $item ) : ?>
					<div class="flex items-center gap-3 shrink-0">
						<span class="w-2 h-2 rounded-full bg-rose-500"></span>
						<span class="text-white font-black tracking-wide"><?php echo esc_html( $item ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>

	</div>
</section>

<style>
@keyframes marquee-rtl {
	0% { transform: translateX(0); }
	100% { transform: translateX(50%); }
}
.animate-marquee {
	display: flex;
	width: max-content;
	animation: marquee-rtl 25s linear infinite;
}
.animate-marquee:hover {
	animation-play-state: paused;
}
</style>
