<?php
/**
 * Story Circles Component (HOME-01) - 100% Matching Kaveh Demo 43
 * Fully Interactive Instagram-style Story Carousel & Fullscreen Story Modal
 *
 * @package Negar_Shop
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$upload_dir = wp_upload_dir();
$story_base = $upload_dir['baseurl'] . '/kaveh-demo/stories';

$stories = array(
	array(
		'title' => 'پیشنهاد داغ',
		'badge' => '',
		'img'   => $story_base . '/story-demo-1.jpg',
		'prod'  => 'گوشی موبایل اپل مدل iPhone 16 Pro Max',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'شروعی متفاوت',
		'badge' => 'ویژه ما',
		'img'   => $story_base . '/story-demo-2.jpg',
		'prod'  => 'هدفون بلوتوثی سامسونگ مدل Galaxy Buds3 Pro',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'ویژه تو',
		'badge' => '',
		'img'   => $story_base . '/story-demo-3.jpg',
		'prod'  => 'ساعت هوشمند اپل مدل Apple Watch Ultra 2',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'شانس با توست',
		'badge' => 'محصول ویژه',
		'img'   => $story_base . '/story-demo-4.jpg',
		'prod'  => 'کنسول بازی سونی مدل PlayStation 5 Slim',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'تخفیف رویایی',
		'badge' => '',
		'img'   => $story_base . '/story-demo-5.jpg',
		'prod'  => 'لپ تاپ اپل مدل MacBook Pro M3',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'تکرار نمیشه',
		'badge' => '',
		'img'   => $story_base . '/story-demo-6.jpg',
		'prod'  => 'پک هدیه لوازم جانبی دیجیتال پرو',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'باورنکردنی',
		'badge' => '',
		'img'   => $story_base . '/story-demo-7.jpg',
		'prod'  => 'اسپیکر قابل حمل جی بی ال مدل Boombox 3',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'انتخاب خاص',
		'badge' => '',
		'img'   => $story_base . '/story-demo-8.jpg',
		'prod'  => 'دسته بی سیم ایکس باکس مدل اختصاصی',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'یک خرید خاص',
		'badge' => 'ویژه',
		'img'   => $story_base . '/story-demo-9.jpg',
		'prod'  => 'تبلت مایکروسافت مدل Surface Pro 9',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
	array(
		'title' => 'برای تو',
		'badge' => 'تخفیف ويژه',
		'img'   => $story_base . '/story-demo-10.jpg',
		'prod'  => 'شارژر دیواری انکر ۴۵ وات دو پورت سریع',
		'link'  => wc_get_page_permalink( 'shop' ),
	),
);
?>

<section class="story-shop position-relative my-4" aria-label="<?php esc_attr_e( 'استوری‌ها و پیشنهادات سریع', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Swiper Container with left/right fade markers matching Demo 43 -->
		<div class="position-relative">
			<div class="story-shop__r d-none d-sm-block"></div>
			<div class="story-shop__l d-none d-sm-block"></div>

			<div class="stories-track flex items-start gap-4 sm:gap-6 overflow-x-auto pb-2 no-scrollbar scroll-smooth">
				<?php foreach ( $stories as $idx => $s ) : ?>
					<div 
						class="story-shop__item cursor-pointer shrink-0" 
						data-story-index="<?php echo esc_attr( $idx ); ?>"
						data-story-title="<?php echo esc_attr( $s['title'] ); ?>"
						data-story-badge="<?php echo esc_attr( $s['badge'] ); ?>"
						data-story-img="<?php echo esc_url( $s['img'] ); ?>"
						data-story-prod="<?php echo esc_attr( $s['prod'] ); ?>"
						data-story-link="<?php echo esc_url( $s['link'] ); ?>"
					>
						<div class="image position-relative">
							<?php if ( ! empty( $s['badge'] ) ) : ?>
								<p class="upperstorytitle"><?php echo esc_html( $s['badge'] ); ?></p>
							<?php endif; ?>

							<span class="rounded-circle">
								<div class="storyhoverimg">
									<img 
										src="<?php echo esc_url( $s['img'] ); ?>" 
										alt="<?php echo esc_attr( $s['title'] ); ?>" 
										width="70" 
										height="70"
										loading="eager"
									>
									<!-- Hover Play SVG Icon -->
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none">
										<path d="M4.02734 11.9382V5.56315C4.02734 5.12398 4.04151 4.7344 4.09109 4.38732C4.29651 2.5244 5.17484 2.02148 7.56901 2.02148H10.4023C12.7965 2.02148 13.6748 2.5244 13.8803 4.38732C13.9298 4.7344 13.944 5.12398 13.944 5.56315V11.9382C13.944 12.3773 13.9298 12.7669 13.8803 13.114C13.6748 14.9769 12.7965 15.4798 10.4023 15.4798H7.56901C5.17484 15.4798 4.29651 14.9769 4.09109 13.114C4.04151 12.7669 4.02734 12.3773 4.02734 11.9382Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M0.898438 14.0625V3.4375" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M17.0703 13.709V3.08398" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
								</div>
							</span>
						</div>

						<div class="title">
							<?php echo esc_html( $s['title'] ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>

	</div>
</section>

<!-- =========================================================================
     INTERACTIVE STORY MODAL VIEWER (INSTAGRAM-STYLE)
     ========================================================================= -->
<div id="modal-story-shop" class="modal-story-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
	<div class="modal-story-dialog">

		<!-- Segmented Progress Bars -->
		<div class="story-progress-container" id="story-progress-container">
			<?php for ( $i = 0; $i < count( $stories ); $i++ ) : ?>
				<div class="story-progress-bar">
					<div class="story-progress-bar-fill" id="story-progress-fill-<?php echo esc_attr( $i ); ?>"></div>
				</div>
			<?php endfor; ?>
		</div>

		<!-- Story Header -->
		<div class="story-modal-header">
			<div class="flex items-center gap-2.5">
				<img id="story-modal-avatar" src="" alt="" class="w-9 h-9 rounded-full border-2 border-white object-cover">
				<div class="flex flex-col text-right">
					<span id="story-modal-title" class="text-xs font-black text-white leading-tight"></span>
					<span id="story-modal-badge" class="text-3xs text-rose-300 font-bold"></span>
				</div>
			</div>

			<!-- Close Button -->
			<button type="button" id="story-modal-close" class="w-8 h-8 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-rose-600 transition cursor-pointer" aria-label="بستن استوری">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>
		</div>

		<!-- Story Slide Main Body -->
		<div class="story-modal-content" id="story-modal-body">
			<!-- Invisible touch tap areas for Left/Right navigation -->
			<div class="absolute inset-y-0 right-0 w-1/3 z-20 cursor-pointer" id="story-prev-zone" title="استوری قبلی"></div>
			<div class="absolute inset-y-0 left-0 w-1/3 z-20 cursor-pointer" id="story-next-zone" title="استوری بعدی"></div>
		</div>

		<!-- Story Footer: Product Card & Action -->
		<div class="story-modal-footer">
			<div class="bg-black/60 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-center justify-between text-right">
				<div class="text-white text-xs font-bold line-clamp-1 flex-1 pl-2" id="story-modal-prod"></div>
				<a href="#" id="story-modal-cta" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-1.5 px-3 rounded-xl transition whitespace-nowrap shadow-md">
					مشاهده و خرید
				</a>
			</div>
		</div>

	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const storyItems = document.querySelectorAll('.story-shop__item');
	const modal = document.getElementById('modal-story-shop');
	const modalClose = document.getElementById('story-modal-close');
	const avatarEl = document.getElementById('story-modal-avatar');
	const titleEl = document.getElementById('story-modal-title');
	const badgeEl = document.getElementById('story-modal-badge');
	const bodyEl = document.getElementById('story-modal-body');
	const prodEl = document.getElementById('story-modal-prod');
	const ctaEl = document.getElementById('story-modal-cta');
	const prevZone = document.getElementById('story-prev-zone');
	const nextZone = document.getElementById('story-next-zone');

	const storiesData = [];
	storyItems.forEach(function(item) {
		storiesData.push({
			title: item.dataset.storyTitle,
			badge: item.dataset.storyBadge,
			img: item.dataset.storyImg,
			prod: item.dataset.storyProd,
			link: item.dataset.storyLink
		});
	});

	let currentIndex = 0;
	let timer = null;
	let progressInterval = null;
	let progress = 0;
	const duration = 5000; // 5 seconds per story
	let isPaused = false;

	function openStory(index) {
		currentIndex = index;
		modal.classList.add('active');
		modal.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';
		loadStory(currentIndex);
	}

	function closeStory() {
		modal.classList.remove('active');
		modal.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';
		clearTimer();
		resetAllProgress();
	}

	function resetAllProgress() {
		storiesData.forEach(function(_, idx) {
			const bar = document.getElementById('story-progress-fill-' + idx);
			if (bar) bar.style.width = '0%';
		});
	}

	function clearTimer() {
		if (timer) clearInterval(timer);
		if (progressInterval) clearInterval(progressInterval);
	}

	function loadStory(idx) {
		clearTimer();
		progress = 0;
		const s = storiesData[idx];
		if (!s) return;

		// Fill previous bars
		storiesData.forEach(function(_, i) {
			const bar = document.getElementById('story-progress-fill-' + i);
			if (!bar) return;
			if (i < idx) {
				bar.style.width = '100%';
			} else if (i === idx) {
				bar.style.width = '0%';
			} else {
				bar.style.width = '0%';
			}
		});

		avatarEl.src = s.img;
		titleEl.textContent = s.title;
		badgeEl.textContent = s.badge || '';
		prodEl.textContent = s.prod;
		ctaEl.href = s.link;
		bodyEl.style.backgroundImage = 'url(' + s.img + ')';

		// Run progress
		const currentBar = document.getElementById('story-progress-fill-' + idx);
		const step = 50;
		progressInterval = setInterval(function() {
			if (isPaused) return;
			progress += step;
			const pct = Math.min(100, (progress / duration) * 100);
			if (currentBar) currentBar.style.width = pct + '%';
			if (progress >= duration) {
				nextStory();
			}
		}, step);
	}

	function nextStory() {
		if (currentIndex < storiesData.length - 1) {
			currentIndex++;
			loadStory(currentIndex);
		} else {
			closeStory();
		}
	}

	function prevStory() {
		if (currentIndex > 0) {
			currentIndex--;
			loadStory(currentIndex);
		} else {
			loadStory(0);
		}
	}

	storyItems.forEach(function(item, idx) {
		item.addEventListener('click', function(e) {
			e.preventDefault();
			openStory(idx);
		});
	});

	if (modalClose) {
		modalClose.addEventListener('click', closeStory);
	}

	modal.addEventListener('click', function(e) {
		if (e.target === modal) {
			closeStory();
		}
	});

	if (nextZone) nextZone.addEventListener('click', nextStory);
	if (prevZone) prevZone.addEventListener('click', prevStory);

	// Pause on mousedown/touchstart
	modal.addEventListener('mousedown', function() { isPaused = true; });
	modal.addEventListener('mouseup', function() { isPaused = false; });
	modal.addEventListener('touchstart', function() { isPaused = true; });
	modal.addEventListener('touchend', function() { isPaused = false; });

	// Keyboard ESC and arrow keys
	document.addEventListener('keydown', function(e) {
		if (!modal.classList.contains('active')) return;
		if (e.key === 'Escape') closeStory();
		if (e.key === 'ArrowLeft') nextStory();
		if (e.key === 'ArrowRight') prevStory();
	});
});
</script>
