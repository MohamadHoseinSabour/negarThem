/**
 * Videos NK — Swiper + popup video player.
 *
 * @package kavehTheme
 */

(function ($) {
	'use strict';

	var elementorHookRegistered = false;
	var $activePopup = null;
	var lastFocus = null;
	var savedScrollX = 0;
	var savedScrollY = 0;

	function readScroll() {
		return {
			x: window.scrollX || window.pageXOffset || document.documentElement.scrollLeft || 0,
			y: window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0
		};
	}

	function restoreScroll() {
		window.scrollTo(savedScrollX, savedScrollY);
		if (document.documentElement) {
			document.documentElement.scrollTop = savedScrollY;
			document.documentElement.scrollLeft = savedScrollX;
		}
		if (document.body) {
			document.body.scrollTop = savedScrollY;
			document.body.scrollLeft = savedScrollX;
		}
	}

	function focusWithoutScroll(el) {
		if (!el || typeof el.focus !== 'function') {
			return;
		}
		try {
			el.focus({ preventScroll: true });
		} catch (err) {
			el.focus();
			restoreScroll();
		}
	}

	function whenSwiperReady(callback) {
		if (typeof window.kavehWhenSwiperReady === 'function') {
			window.kavehWhenSwiperReady(callback);
			return;
		}
		if (typeof window.Swiper === 'function') {
			callback(window.Swiper);
			return;
		}
		var attempts = 0;
		var timer = window.setInterval(function () {
			attempts += 1;
			if (typeof window.Swiper === 'function') {
				window.clearInterval(timer);
				callback(window.Swiper);
			} else if (attempts >= 80) {
				window.clearInterval(timer);
			}
		}, 100);
	}

	function initSwiper(root) {
		if (!root) {
			return;
		}
		if (!window.Swiper) {
			whenSwiperReady(function () {
				initSwiper(root);
			});
			return;
		}

		var el = root.querySelector('.swiper-videos-nk');
		if (!el) {
			return;
		}

		var optionsData = {};
		try {
			optionsData = el.getAttribute('data-swiper-config')
				? JSON.parse(el.getAttribute('data-swiper-config'))
				: {};
		} catch (e) {
			optionsData = {};
		}

		if (el.swiper) {
			el.swiper.destroy(true, true);
		}

		new window.Swiper(el, optionsData);
	}

	function clearPlayer($popup) {
		var $player = $popup.find('[data-videos-nk-player]');
		$player.find('video').each(function () {
			try {
				this.pause();
				this.removeAttribute('src');
				this.load();
			} catch (err) {
				// no-op
			}
		});
		$player.empty();
	}

	function closePopup($popup) {
		if (!$popup || !$popup.length) {
			return;
		}

		$popup.removeClass('is-open').attr({ hidden: true, 'aria-hidden': 'true' });
		clearPlayer($popup);
		$('body').removeClass('videos-nk-popup-open');

		if ($activePopup && $activePopup[0] === $popup[0]) {
			$activePopup = null;
		}

		// Do not restore focus — browsers scroll focused nodes into view.
		lastFocus = null;

		restoreScroll();
		window.requestAnimationFrame(function () {
			restoreScroll();
			window.setTimeout(restoreScroll, 0);
		});
	}

	function mountPlayer($player, type, src, poster) {
		if ('aparat' === type) {
			var $iframe = $('<iframe>', {
				src: src,
				title: 'Aparat video',
				allow: 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
				allowfullscreen: true,
				loading: 'lazy',
				referrerpolicy: 'strict-origin-when-cross-origin',
				tabindex: '-1'
			});
			$player.empty().append($iframe);
			return;
		}

		// Avoid autoplay attribute: some browsers scroll the media into view on insert.
		var $video = $('<video>', {
			controls: true,
			playsinline: true,
			tabindex: '-1'
		});
		if (poster) {
			$video.attr('poster', poster);
		}
		$video.attr('src', src);
		$player.empty().append($video);

		var videoEl = $video.get(0);
		if (videoEl && typeof videoEl.play === 'function') {
			var playPromise = videoEl.play();
			if (playPromise && typeof playPromise.catch === 'function') {
				playPromise.catch(function () {
					/* autoplay may be blocked; controls remain available */
				});
			}
		}
	}

	function openPopup($section, type, src, poster) {
		var $popup = $section.find('.videos-nk-popup').first();
		if (!$popup.length || !src) {
			return;
		}

		// Capture scroll BEFORE any DOM / media changes.
		var scroll = readScroll();
		savedScrollX = scroll.x;
		savedScrollY = scroll.y;
		lastFocus = document.activeElement;

		if ($activePopup && $activePopup[0] !== $popup[0]) {
			closePopup($activePopup);
			// Re-apply the scroll we captured for THIS open after closing the other popup.
			savedScrollX = scroll.x;
			savedScrollY = scroll.y;
			restoreScroll();
		}

		var $player = $popup.find('[data-videos-nk-player]');
		clearPlayer($popup);

		$popup.removeAttr('hidden').attr('aria-hidden', 'false').addClass('is-open');
		$('body').addClass('videos-nk-popup-open');
		$activePopup = $popup;

		// Keep page pinned while mounting media (video/iframe can trigger scroll-into-view).
		restoreScroll();
		mountPlayer($player, type, src, poster);
		restoreScroll();

		window.requestAnimationFrame(function () {
			restoreScroll();
			focusWithoutScroll($popup.find('.videos-nk-popup__close').get(0));
			restoreScroll();
		});
	}

	function bindPopup($section) {
		if ($section.data('videosNkPopupBound')) {
			return;
		}
		$section.data('videosNkPopupBound', true);

		$section.on('click.videosNk', '.videos-nk__play', function (event) {
			event.preventDefault();
			event.stopPropagation();
			var $btn = $(this);
			if ($btn.is(':disabled') || $btn.hasClass('is-disabled')) {
				return;
			}

			openPopup(
				$section,
				$btn.attr('data-video-type') || 'upload',
				$btn.attr('data-video-src') || '',
				$btn.attr('data-video-poster') || ''
			);
		});

		$section.on('click.videosNk', '[data-videos-nk-close]', function (event) {
			event.preventDefault();
			event.stopPropagation();
			closePopup($section.find('.videos-nk-popup').first());
		});
	}

	function initAll(scope) {
		var $scope = scope ? $(scope) : $(document);
		// A `new Swiper` restyles the whole document, so building a carousel the
		// reader has not reached yet is main-thread time spent on nothing. main.js
		// supplies the observer; without it, mount straight away as before.
		// bindPopup stays eager: it attaches click handlers, and a dead click is
		// a bug where a late-built carousel is only a delay.
		var lazy = window.kavehMountWhenVisible;
		$scope.find('.videos-nk').each(function () {
			var $section = $(this);
			if (typeof lazy === 'function') {
				lazy(this, initSwiper);
			} else {
				initSwiper(this);
			}
			bindPopup($section);
		});
	}

	$(function () {
		initAll(document);
	});

	$(document).on('keydown.videosNk', function (event) {
		if ((27 === event.keyCode || 'Escape' === event.key) && $activePopup) {
			event.preventDefault();
			closePopup($activePopup);
		}
	});

	$(window).on('elementor/frontend/init', function () {
		if (elementorHookRegistered || !window.elementorFrontend || !elementorFrontend.hooks) {
			return;
		}

		elementorHookRegistered = true;
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/videosnkkaveh.default',
			function ($scope) {
				whenSwiperReady(function () {
					initAll($scope);
				});
			}
		);
	});
})(jQuery);
