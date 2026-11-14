<?php
/**
 * Story Circles Component (HOME-01)
 * Managed Instagram-style Story Carousel & Fullscreen Story Modal
 *
 * @package Negar_Shop
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stories = function_exists( 'negar_theme_get_stories' ) ? negar_theme_get_stories( true ) : array();
if ( empty( $stories ) ) {
	return;
}
?>

<section class="story-shop position-relative my-6 lg:my-8" aria-label="<?php esc_attr_e( 'استوری‌ها و پیشنهادات سریع', 'negar-shop' ); ?>">
	<div class="container max-w-[1320px] mx-auto px-3">
		
		<!-- Swiper Container with left/right fade markers -->
		<div class="position-relative overflow-hidden w-full">
			<div class="story-shop__r d-none d-sm-block"></div>
			<div class="story-shop__l d-none d-sm-block"></div>

			<div class="stories-track flex items-start gap-4 sm:gap-6 overflow-x-auto pb-2 no-scrollbar scroll-smooth">
				<?php foreach ( $stories as $idx => $s ) : ?>
					<button 
						type="button"
						class="story-shop__item cursor-pointer shrink-0 text-inherit bg-transparent border-0 p-0 text-center" 
						data-story-index="<?php echo esc_attr( (string) $idx ); ?>"
						data-story-id="<?php echo esc_attr( (string) $s['id'] ); ?>"
						data-story-type="<?php echo esc_attr( (string) $s['type'] ); ?>"
						data-story-title="<?php echo esc_attr( (string) $s['title'] ); ?>"
						data-story-badge="<?php echo esc_attr( (string) $s['badge'] ); ?>"
						data-story-thumb="<?php echo esc_url( $s['thumbnail_url'] ); ?>"
						data-story-img="<?php echo esc_url( $s['image_url'] ); ?>"
						data-story-video="<?php echo esc_url( $s['video_url'] ); ?>"
						data-story-poster="<?php echo esc_url( $s['poster_url'] ); ?>"
						data-story-duration="<?php echo esc_attr( (string) $s['duration'] ); ?>"
						data-story-has-product="<?php echo ! empty( $s['has_product'] ) ? '1' : '0'; ?>"
						data-story-prod="<?php echo esc_attr( (string) $s['product_title'] ); ?>"
						data-story-link="<?php echo esc_url( $s['product_link'] ); ?>"
						data-story-cta-label="<?php echo esc_attr( (string) $s['cta_label'] ); ?>"
						aria-haspopup="dialog"
						aria-label="<?php echo esc_attr( sprintf( __( 'مشاهدهٔ استوری %s', 'negar-shop' ), $s['title'] ) ); ?>"
					>
						<div class="image position-relative">
							<?php if ( ! empty( $s['badge'] ) ) : ?>
								<p class="upperstorytitle"><?php echo esc_html( $s['badge'] ); ?></p>
							<?php endif; ?>

							<span class="rounded-circle">
								<div class="storyhoverimg">
									<img 
										src="<?php echo esc_url( $s['thumbnail_url'] ); ?>" 
										alt="<?php echo esc_attr( $s['title'] ); ?>" 
										width="70" 
										height="70"
										loading="eager"
									>
									<!-- Hover Play SVG Icon -->
									<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none" aria-hidden="true">
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
					</button>
				<?php endforeach; ?>
			</div>

		</div>

	</div>
</section>

<!-- =========================================================================
     INTERACTIVE STORY MODAL VIEWER (INSTAGRAM-STYLE)
     ========================================================================= -->
<div id="modal-story-shop" class="modal-story-backdrop" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="story-modal-title">
	<div class="modal-story-dialog" tabindex="-1">

		<!-- Segmented Progress Bars -->
		<div class="story-progress-container" id="story-progress-container" aria-hidden="true">
			<?php for ( $i = 0; $i < count( $stories ); $i++ ) : ?>
				<div class="story-progress-bar">
					<div class="story-progress-bar-fill" id="story-progress-fill-<?php echo esc_attr( (string) $i ); ?>"></div>
				</div>
			<?php endfor; ?>
		</div>

		<!-- Story Header -->
		<div class="story-modal-header">
			<div class="flex items-center gap-2.5">
				<img id="story-modal-avatar" src="" alt="" class="w-9 h-9 rounded-full border-2 border-white object-cover" width="36" height="36">
				<div class="flex flex-col text-right">
					<span id="story-modal-title" class="text-xs font-black text-white leading-tight"></span>
					<span id="story-modal-badge" class="text-3xs text-rose-300 font-bold"></span>
				</div>
			</div>

			<!-- Close Button -->
			<button type="button" id="story-modal-close" class="w-8 h-8 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-rose-600 transition cursor-pointer" aria-label="<?php esc_attr_e( 'بستن استوری', 'negar-shop' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>
		</div>

		<!-- Story Slide Main Body -->
		<div class="story-modal-content" id="story-modal-body">
			<!-- HTML5 Video Element for Video Stories -->
			<video class="story-video" id="story-modal-video" playsinline webkit-playsinline preload="metadata" style="display:none; width:100%; height:100%; object-fit:cover; position:absolute; inset:0; z-index:1;"></video>

			<!-- Invisible touch tap areas for Left/Right navigation -->
			<div class="absolute inset-y-0 right-0 w-1/3 z-20 cursor-pointer" id="story-prev-zone" role="button" aria-label="<?php esc_attr_e( 'استوری قبلی', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'استوری قبلی', 'negar-shop' ); ?>"></div>
			<div class="absolute inset-y-0 left-0 w-1/3 z-20 cursor-pointer" id="story-next-zone" role="button" aria-label="<?php esc_attr_e( 'استوری بعدی', 'negar-shop' ); ?>" title="<?php esc_attr_e( 'استوری بعدی', 'negar-shop' ); ?>"></div>
		</div>

		<!-- Story Footer: Product Card & Action -->
		<div class="story-modal-footer" id="story-modal-footer">
			<div class="bg-black/60 backdrop-blur-md rounded-2xl p-3 border border-white/10 flex items-center justify-between text-right" id="story-modal-footer-card">
				<div class="text-white text-xs font-bold line-clamp-1 flex-1 pl-2" id="story-modal-prod"></div>
				<a href="#" id="story-modal-cta" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-1.5 px-3 rounded-xl transition whitespace-nowrap shadow-md">
					<?php esc_html_e( 'مشاهده و خرید', 'negar-shop' ); ?>
				</a>
			</div>
		</div>

	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const storyItems = document.querySelectorAll('.story-shop__item');
	const modal = document.getElementById('modal-story-shop');
	if (!modal || storyItems.length === 0) return;

	const modalDialog = modal.querySelector('.modal-story-dialog');
	const modalClose = document.getElementById('story-modal-close');
	const avatarEl = document.getElementById('story-modal-avatar');
	const titleEl = document.getElementById('story-modal-title');
	const badgeEl = document.getElementById('story-modal-badge');
	const bodyEl = document.getElementById('story-modal-body');
	const videoEl = document.getElementById('story-modal-video');
	const footerEl = document.getElementById('story-modal-footer');
	const prodEl = document.getElementById('story-modal-prod');
	const ctaEl = document.getElementById('story-modal-cta');
	const prevZone = document.getElementById('story-prev-zone');
	const nextZone = document.getElementById('story-next-zone');

	const storiesData = [];
	storyItems.forEach(function(item) {
		storiesData.push({
			id: item.dataset.storyId,
			type: item.dataset.storyType || 'image',
			title: item.dataset.storyTitle || '',
			badge: item.dataset.storyBadge || '',
			thumb: item.dataset.storyThumb || '',
			img: item.dataset.storyImg || '',
			video: item.dataset.storyVideo || '',
			poster: item.dataset.storyPoster || '',
			duration: parseInt(item.dataset.storyDuration, 10) || 5,
			hasProduct: item.dataset.storyHasProduct === '1',
			prod: item.dataset.storyProd || '',
			link: item.dataset.storyLink || '',
			ctaLabel: item.dataset.storyCtaLabel || 'مشاهده و خرید'
		});
	});

	let currentIndex = 0;
	let progressInterval = null;
	let progressElapsed = 0;
	let isPaused = false;
	let lastActiveElement = null;

	function openStory(index) {
		lastActiveElement = document.activeElement;
		currentIndex = index;
		modal.classList.add('active');
		modal.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';
		if (modalDialog) modalDialog.focus();
		loadStory(currentIndex);
	}

	function closeStory() {
		stopCurrentMedia();
		modal.classList.remove('active');
		modal.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';
		resetAllProgress();
		if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
			lastActiveElement.focus();
		}
	}

	function resetAllProgress() {
		storiesData.forEach(function(_, idx) {
			const bar = document.getElementById('story-progress-fill-' + idx);
			if (bar) bar.style.width = '0%';
		});
	}

	function stopCurrentMedia() {
		if (progressInterval) {
			clearInterval(progressInterval);
			progressInterval = null;
		}
		if (videoEl) {
			videoEl.pause();
			videoEl.onended = null;
			videoEl.ontimeupdate = null;
			videoEl.removeAttribute('src');
			videoEl.load();
			videoEl.style.display = 'none';
		}
	}

	function loadStory(idx) {
		stopCurrentMedia();
		progressElapsed = 0;
		isPaused = false;
		const s = storiesData[idx];
		if (!s) return;

		// Fill previous bars, reset following bars
		storiesData.forEach(function(_, i) {
			const bar = document.getElementById('story-progress-fill-' + i);
			if (!bar) return;
			if (i < idx) {
				bar.style.width = '100%';
			} else {
				bar.style.width = '0%';
			}
		});

		const currentBar = document.getElementById('story-progress-fill-' + idx);

		// Header
		avatarEl.src = s.thumb || s.img || '';
		titleEl.textContent = s.title;
		badgeEl.textContent = s.badge || '';

		// Footer / Product Card
		if (s.hasProduct && s.prod) {
			footerEl.style.display = '';
			prodEl.textContent = s.prod;
			prodEl.style.display = '';
			ctaEl.href = s.link || '#';
			ctaEl.textContent = s.ctaLabel;
			ctaEl.style.display = '';
		} else if (s.link) {
			footerEl.style.display = '';
			prodEl.textContent = '';
			prodEl.style.display = 'none';
			ctaEl.href = s.link;
			ctaEl.textContent = s.ctaLabel;
			ctaEl.style.display = '';
		} else {
			footerEl.style.display = 'none';
		}

		if ('video' === s.type && s.video) {
			bodyEl.style.backgroundImage = s.poster ? 'url(' + s.poster + ')' : (s.thumb ? 'url(' + s.thumb + ')' : 'none');
			videoEl.style.display = 'block';
			videoEl.src = s.video;
			videoEl.currentTime = 0;

			videoEl.ontimeupdate = function() {
				if (videoEl.duration && !isNaN(videoEl.duration) && videoEl.duration > 0) {
					const pct = Math.min(100, (videoEl.currentTime / videoEl.duration) * 100);
					if (currentBar) currentBar.style.width = pct + '%';
				}
			};

			videoEl.onended = function() {
				if (currentBar) currentBar.style.width = '100%';
				nextStory();
			};

			var playPromise = videoEl.play();
			if (playPromise !== undefined) {
				playPromise.catch(function() {
					// Fallback to muted playback if browser autoplay policy blocks unmuted audio
					videoEl.muted = true;
					videoEl.play().catch(function() {});
				});
			}
		} else {
			// Image Story
			videoEl.style.display = 'none';
			bodyEl.style.backgroundImage = 'url(' + (s.img || s.thumb) + ')';

			const storyDurationMs = Math.max(3000, Math.min(30000, s.duration * 1000));
			const step = 50;
			progressInterval = setInterval(function() {
				if (isPaused) return;
				progressElapsed += step;
				const pct = Math.min(100, (progressElapsed / storyDurationMs) * 100);
				if (currentBar) currentBar.style.width = pct + '%';
				if (progressElapsed >= storyDurationMs) {
					clearInterval(progressInterval);
					progressInterval = null;
					nextStory();
				}
			}, step);
		}
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

	// Click to open
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

	// Press / Hold to pause
	function pauseStory() {
		isPaused = true;
		if (videoEl && videoEl.src && !videoEl.paused) {
			videoEl.pause();
		}
	}

	function resumeStory() {
		isPaused = false;
		if (videoEl && videoEl.src && videoEl.paused) {
			videoEl.play().catch(function() {});
		}
	}

	modal.addEventListener('mousedown', pauseStory);
	modal.addEventListener('mouseup', resumeStory);
	modal.addEventListener('touchstart', pauseStory, { passive: true });
	modal.addEventListener('touchend', resumeStory, { passive: true });

	// Keyboard ESC and arrow keys, focus trap
	document.addEventListener('keydown', function(e) {
		if (!modal.classList.contains('active')) return;

		if (e.key === 'Escape') {
			e.preventDefault();
			closeStory();
			return;
		}

		if (e.key === 'ArrowLeft') {
			e.preventDefault();
			nextStory();
			return;
		}

		if (e.key === 'ArrowRight') {
			e.preventDefault();
			prevStory();
			return;
		}

		// Focus trap
		if (e.key === 'Tab') {
			const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
			if (focusable.length === 0) return;
			const first = focusable[0];
			const last = focusable[focusable.length - 1];
			if (e.shiftKey) {
				if (document.activeElement === first) {
					e.preventDefault();
					last.focus();
				}
			} else {
				if (document.activeElement === last) {
					e.preventDefault();
					first.focus();
				}
			}
		}
	});
});
</script>

