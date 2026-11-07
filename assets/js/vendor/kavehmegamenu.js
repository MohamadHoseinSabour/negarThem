/**
 * Mega Menu JS
 *
 * @package kavehTheme
 */

(function ($) {
	'use strict';

	var allMains = [];
	var revealed = false;
	var pageLoaded = false;

	var wrapLegacySubMenus = function (main) {
		main.find('.sub-menu').each(function () {
			var $subMenu = $(this);

			if ($subMenu.parent().hasClass('custom-menu-child')) {
				return;
			}

			if ($subMenu.hasClass('sub-mega-menu')) {
				$subMenu.wrap('<div class="custom-menu-child boostify-mega-sub">');
			} else {
				$subMenu.wrap('<div class="custom-menu-child">');
			}
		});
	};

	var setMegaWidth = function (main) {
		var isRtl = $('body').hasClass('rtl') || $('html').attr('dir') === 'rtl';
		var itemMega = main.find('.menu-item-has-mega');

		if (!itemMega.length) {
			return;
		}

		// Three passes on purpose. Resetting a panel's width, then reading its
		// trigger's offset, then writing the new width - all inside one .each() -
		// made the browser re-run layout once per menu item. On a page with ~10k
		// elements that is the most expensive thing this file does, and it repeats
		// on every resize. Reads and writes are now batched, so layout runs once.
		var entries = [];

		// Pass 1: writes. Clear whatever the previous run set.
		itemMega.each(function () {
			var item = $(this);
			var mega = item.find('.custom-menu-child.boostify-mega-sub').first();

			if (!mega.length) {
				return;
			}

			mega.css({ width: '', left: '', right: '', transform: '' });
			entries.push({ item: item, mega: mega });
		});

		if (!entries.length) {
			return;
		}

		// Pass 2: reads. One layout for all of them.
		var width = $(window).width();
		for (var i = 0; i < entries.length; i++) {
			var offset = entries[i].item.offset();
			entries[i].left = offset ? offset.left : 0;
		}

		// Pass 3: writes.
		for (var j = 0; j < entries.length; j++) {
			var item = entries[j].item;
			var mega = entries[j].mega;
			var left = entries[j].left;
			var padding;
			var contentWith;
			var position;

			if (item.hasClass('sub-width-default')) {
				contentWith = 500;
				if (isRtl) {
					mega.css({ left: '', right: 'calc((100% - ' + contentWith + 'px) / 2)', width: contentWith + 'px' });
				} else {
					mega.css({ left: 'calc((100% - ' + contentWith + 'px) / 2)', right: '', width: contentWith + 'px' });
				}
			} else if (item.hasClass('sub-width-container')) {
				if (width > 1170) {
					padding = (width - 1170) / 2;
					contentWith = 1170;
					position = padding - left;
				} else {
					padding = 15;
					position = padding - left;
					contentWith = width - 30;
				}

				mega.css({ left: position + 'px', width: contentWith + 'px' });
			} else if (item.hasClass('sub-width-custom')) {
				contentWith = parseInt(item.attr('data-custom-width'), 10);

				if (!contentWith || contentWith < 200) {
					contentWith = 500;
				}

				padding = (width - contentWith) / 2;
				position = padding - left;

				if (isRtl) {
					mega.css({ left: '', right: position + 'px', width: contentWith + 'px' });
				} else {
					mega.css({ left: position + 'px', right: '', width: contentWith + 'px' });
				}
			} else if (item.hasClass('sub-width-full')) {
				mega.css({
					left: '-' + left + 'px',
					right: 'auto',
					width: width + 'px'
				});
			} else {
				mega.css({ left: '-' + left + 'px', width: width + 'px' });
			}
		}
	};

	var adjustNestedFlyouts = function (main) {
		var isRtl = $('body').hasClass('rtl') || $('html').attr('dir') === 'rtl';

		main.find('.sub-menu .menu-item-has-children, .sub-menu-default .menu-item-has-children')
			.off('mouseenter.kavehFlyout')
			.on('mouseenter.kavehFlyout', function () {
			var $item = $(this);
			var $child = $item.children('.custom-menu-child');

			$child.removeClass('flyout-left');

			if (!$child.length) {
				return;
			}

			var itemOffset = $item.offset().left;
			var windowWidth = $(window).width();
			var spaceRight = windowWidth - itemOffset - $item.outerWidth();
			var spaceLeft = itemOffset;

			if (isRtl) {
				if (spaceLeft < 200) {
					$child.addClass('flyout-left');
				}
			} else if (spaceRight < 200) {
				$child.addClass('flyout-left');
			}
		});
	};

	var initMobileMenu = function ($scope) {
		var toggle = $scope.find('.custom-menu-toggle');

		if (!toggle.length) {
			return;
		}

		toggle.off('click.kavehMobileNav').on('click.kavehMobileNav', function (e) {
			e.preventDefault();
			e.stopPropagation();

			// Style 2 global mobile menu
			var style2 = document.querySelector('.nav-mobile-six-standalone');
			if (style2) {
				if (typeof window.kavehOpenMobileNavStyle2 === 'function') {
					window.kavehOpenMobileNavStyle2();
					return;
				}

				var menu = style2.querySelector('.nav-mobile-six-menu');
				var bottom = style2.querySelector('.nav-mobile-six-bottom');
				if (menu && !menu.classList.contains('opened')) {
					window.clearTimeout(style2._closeTimer);
					style2.classList.remove('is-closing');
					style2.classList.add('opened');
					void style2.offsetHeight;
					requestAnimationFrame(function () {
						menu.classList.add('opened');
						if (bottom) {
							bottom.classList.add('opened');
						}
					});
					document.documentElement.style.overflow = 'hidden';
				}
				return;
			}

			// Style 1 global mobile menu
			var navResponsive = document.querySelector('.nav-responsive');
			if (!navResponsive) {
				return;
			}

			navResponsive.classList.add('opened');
			document.documentElement.style.overflow = 'hidden';

			var navkBottom = document.querySelector('.nav-bottom');
			if (navkBottom) {
				navkBottom.classList.add('d-none');
			}

			if (window.jQuery) {
				if (jQuery('.proccedk').length) {
					jQuery('.proccedk').hide();
				}
				if (jQuery('.select2-container.select2-container--default').length) {
					jQuery('.select2-container.select2-container--default').hide();
				}
			}
		});
	};

	var initMegamenuThreeHover = function ($scope) {
		$scope.find('.custom-menu > li:has(.megamenu-three), .boostify-menu .menu-item-has-children:has(.megamenu-three)').each(function () {
			var $item = $(this);
			var closeTimer = null;

			if ($item.data('kavehMegamenuThreeHover')) {
				return;
			}

			$item.data('kavehMegamenuThreeHover', true);

			var openMenu = function () {
				if (closeTimer) {
					clearTimeout(closeTimer);
					closeTimer = null;
				}

				$item.addClass('megamenu-three-open');
			};

			var scheduleClose = function () {
				if (closeTimer) {
					clearTimeout(closeTimer);
				}

				// Small delay lets the cursor cross the gap from trigger to panel
				// without closing, while sideways leave still closes quickly.
				closeTimer = setTimeout(function () {
					closeTimer = null;
					$item.removeClass('megamenu-three-open');
				}, 260);
			};

			$item.on('mouseenter.kavehMegamenuThree', openMenu);
			$item.on('mouseleave.kavehMegamenuThree', scheduleClose);
		});
	};

	var stripInlineLockStyles = function (el) {
		var style = el.getAttribute('style') || '';
		style = style
			.replace(/display\s*:\s*none\s*(?:!important)?\s*;?/gi, '')
			.replace(/visibility\s*:\s*hidden\s*(?:!important)?\s*;?/gi, '')
			.replace(/opacity\s*:\s*0\s*(?:!important)?\s*;?/gi, '')
			.replace(/^\s*;\s*/, '')
			.trim();

		if (style) {
			el.setAttribute('style', style);
		} else {
			el.removeAttribute('style');
		}
	};

	var markWidgetReady = function (el) {
		el.classList.remove('kaveh-megamenu-loading');
		el.removeAttribute('data-kaveh-megamenu-locked');
		stripInlineLockStyles(el);

		var widgetWrap = el.closest('.elementor-widget-kavehmegamenu, .elementor-widget-ht-hf-mega-menu, .elementor-widget-ht-hf-nav-menu');
		if (widgetWrap) {
			widgetWrap.classList.add('kaveh-megamenu-ready');
		}
	};

	var clearDocumentLoading = function () {
		document.documentElement.classList.remove('kaveh-megamenu-loading');
		if (document.body) {
			document.body.classList.remove('kaveh-megamenu-loading');
		}
	};

	var revealMegaMenu = function () {
		if (revealed) {
			return;
		}
		revealed = true;

		document.documentElement.classList.add('kaveh-megamenu-booting');

		$.each(allMains, function (_, main) {
			if (main && main.parentNode) {
				setMegaWidth($(main));
			}
		});

		document.querySelectorAll(
			'[data-kaveh-megamenu-locked="1"], .custom-navigation-widget.kaveh-megamenu-loading, .boostify-navigation--widget.kaveh-megamenu-loading'
		).forEach(markWidgetReady);

		clearDocumentLoading();

		requestAnimationFrame(function () {
			requestAnimationFrame(function () {
				document.documentElement.classList.remove('kaveh-megamenu-booting');
			});
		});
	};

	var scheduleReveal = function () {
		requestAnimationFrame(function () {
			requestAnimationFrame(revealMegaMenu);
		});
	};

	var resizeBound = false;

	var bindResize = function () {
		if (resizeBound) {
			return;
		}
		resizeBound = true;

		$(window).on('resize.kavehMegaMenu', function () {
			for (var i = 0; i < allMains.length; i++) {
				if (allMains[i] && allMains[i].parentNode) {
					setMegaWidth($(allMains[i]));
				}
			}
		});
	};

	var WidgetMegaMenu = function ($scope) {
		initMegamenuThreeHover($scope);

		var main = $scope.find('.custom-main-navigation');

		if (main.length) {
			wrapLegacySubMenus(main);
			setMegaWidth(main);
			adjustNestedFlyouts(main);
			initMobileMenu($scope);

			if (main.hasClass('primary-menu')) {
				main.removeClass('primary-menu');
			}

			if (allMains.indexOf(main[0]) === -1) {
				allMains.push(main[0]);
			}

			bindResize();
		}

		if (!revealed) {
			scheduleReveal();
		}
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/kavehmegamenu.default', WidgetMegaMenu);
		elementorFrontend.hooks.addAction('frontend/element_ready/ht-hf-mega-menu.default', WidgetMegaMenu);
		elementorFrontend.hooks.addAction('frontend/element_ready/ht-hf-nav-menu.default', WidgetMegaMenu);
	});

	$(function () {
		initMegamenuThreeHover($(document));
		pageLoaded = true;

		$('.elementor-widget-kavehmegamenu, .elementor-widget-ht-hf-mega-menu, .elementor-widget-ht-hf-nav-menu').each(function () {
			WidgetMegaMenu($(this));
		});

		if (!revealed) {
			scheduleReveal();
		}
	});

	$(window).on('load', function () {
		pageLoaded = true;
		if (!revealed) {
			scheduleReveal();
		}
	});

	setTimeout(revealMegaMenu, 800);
})(jQuery);
