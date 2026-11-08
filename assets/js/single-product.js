/**
 * Negar Shop - Single Product JavaScript Controller
 * 100% Matching Kaveh Demo 43 (Style 5 Modern Tech)
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initKavehGallery();
		initKavehTabs();
		initKavehCollapse();
		initSpecsShowMore();
		initKavehVotes();
	});

	/* ==========================================================================
	   1. Kaveh Product Gallery (Style 5)
	   ========================================================================== */
	function initKavehGallery() {
		var mainImg = document.querySelector('.kav-style5-main-image');
		var thumbs = document.querySelectorAll('.kav-style5-thumb');

		if (!mainImg || !thumbs.length) return;

		thumbs.forEach(function (thumb) {
			thumb.addEventListener('click', function () {
				var fullSrc = this.getAttribute('data-full-src');
				var thumbImg = this.querySelector('img');

				if (fullSrc) {
					mainImg.style.opacity = '0.3';
					mainImg.style.transition = 'opacity 0.2s ease';
					setTimeout(function () {
						mainImg.src = fullSrc;
						mainImg.setAttribute('data-large_image', fullSrc);
						mainImg.style.opacity = '1';
					}, 150);
				} else if (thumbImg && thumbImg.src) {
					mainImg.src = thumbImg.src;
				}

				thumbs.forEach(function (t) {
					t.style.borderColor = '#f2f2f2';
				});
				this.style.borderColor = '#e90544';
			});
		});
	}

	/* ==========================================================================
	   2. Kaveh Product Tabs (True Tab Switching: Info, Comments, Questions)
	   ========================================================================== */
	function initKavehTabs() {
		var tabItems = document.querySelectorAll('.kav-single-product-tabs li');
		var tabPanes = document.querySelectorAll('.kav-single-product-tab-content');

		if (!tabItems.length) return;

		function activateTab(targetSelector, shouldScroll) {
			tabItems.forEach(function (t) {
				if (t.getAttribute('data-content') === targetSelector) {
					t.classList.add('active');
				} else {
					t.classList.remove('active');
				}
			});

			tabPanes.forEach(function (pane) {
				if ('#' + pane.id === targetSelector) {
					pane.classList.add('is-active');
					pane.style.display = 'block';
				} else {
					pane.classList.remove('is-active');
					pane.style.display = 'none';
				}
			});

			if (shouldScroll) {
				var tabsBar = document.querySelector('.kav-single-product-tabs');
				if (tabsBar) {
					var offset = 100;
					var elPos = tabsBar.getBoundingClientRect().top + window.pageYOffset - offset;
					window.scrollTo({
						top: elPos,
						behavior: 'smooth'
					});
				}
			}
		}

		tabItems.forEach(function (tab) {
			tab.addEventListener('click', function () {
				var targetSelector = this.getAttribute('data-content');
				if (targetSelector) {
					activateTab(targetSelector, false);
				}
			});
		});

		// External triggers (like "مشاهده همه ویژگی ها" link)
		var specsMoreLinks = document.querySelectorAll('a[href="#info-product-tab"], .kav-single-product-specs-more');
		specsMoreLinks.forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				activateTab('#info-product-tab', true);
			});
		});

		// Sort buttons in comments and questions tabs
		var sortBtns = document.querySelectorAll('.kav-single-product-comments-sort');
		sortBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var parentGroup = this.closest('.kav-reviews2-sort');
				if (parentGroup) {
					parentGroup.querySelectorAll('.kav-single-product-comments-sort').forEach(function (b) {
						b.classList.remove('active');
					});
				}
				this.classList.add('active');
			});
		});
	}

	/* ==========================================================================
	   3. Kaveh Heading Collapse (Bootstrap Compatible)
	   ========================================================================== */
	function initKavehCollapse() {
		var headings = document.querySelectorAll('.kav-single-product-heading');

		headings.forEach(function (heading) {
			heading.addEventListener('click', function () {
				var targetId = this.getAttribute('data-bs-target');
				if (!targetId) return;

				var target = document.querySelector(targetId);
				var chevron = this.querySelector('.kav-single-product-heading-chevron');

				if (target) {
					if (target.classList.contains('show')) {
						target.classList.remove('show');
						if (chevron) chevron.style.transform = 'rotate(180deg)';
					} else {
						target.classList.add('show');
						if (chevron) chevron.style.transform = 'rotate(0deg)';
					}
				}
			});
		});
	}

	/* ==========================================================================
	   4. Specifications Show More / Less Toggle
	   ========================================================================== */
	function initSpecsShowMore() {
		var btn = document.querySelector('.kav-single-product-btn-show-more');
		var content = document.querySelector('#kav-single-product-info-product-content');

		if (!btn || !content) return;

		btn.addEventListener('click', function () {
			var isExpanded = btn.classList.contains('is-expanded');
			if (isExpanded) {
				content.style.maxHeight = '464px';
				btn.classList.remove('is-expanded');
			} else {
				content.style.maxHeight = 'none';
				btn.classList.add('is-expanded');
			}
		});
	}

	/* ==========================================================================
	   5. Thumbs Up / Down Voting
	   ========================================================================== */
	function initKavehVotes() {
		var voteBtns = document.querySelectorAll('[data-kav-vote]');
		voteBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var countEl = this.querySelector('.kav-vote-count');
				var current = countEl ? parseInt(countEl.textContent, 10) || 0 : 0;
				if (this.classList.contains('is-active')) {
					this.classList.remove('is-active');
					if (countEl && current > 0) countEl.textContent = current - 1;
				} else {
					this.classList.add('is-active');
					if (countEl) countEl.textContent = current + 1;
				}
			});
		});
	}

})();
