/**
 * Shop & Product Archive JavaScript Controller
 * Exact match to Demo 43 (category-nk)
 *
 * @package Negar_Shop
 * @version 2.2.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initCategoryFilterMobileDrawer();
		initCategorySearch();
		initSortDropdown();
	});

	/* ==========================================================================
	   1. Mobile Filter Off-Canvas Drawer (data-open="#filter", data-close="#filter")
	   ========================================================================== */
	function initCategoryFilterMobileDrawer() {
		var filter = document.getElementById('filter');
		if (!filter) return;

		var openBtns = document.querySelectorAll('[data-open="#filter"]');
		var closeBtns = document.querySelectorAll('[data-close="#filter"]');

		function openFilter() {
			filter.classList.add('show', 'is-open');
			document.body.classList.add('overflow-hidden');
		}

		function closeFilter() {
			filter.classList.remove('show', 'is-open');
			document.body.classList.remove('overflow-hidden');
		}

		openBtns.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				openFilter();
			});
		});

		closeBtns.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				closeFilter();
			});
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && filter.classList.contains('show')) {
				closeFilter();
			}
		});
	}

	/* ==========================================================================
	   2. Category & Brand Live Search Filter Inside Sidebar Box
	   ========================================================================== */
	function initCategorySearch() {
		var searchInputs = document.querySelectorAll('.category-nk__filter__search-input');
		searchInputs.forEach(function (input) {
			input.addEventListener('input', function () {
				var query = this.value.trim().toLowerCase();
				var container = this.closest('.collapse') || this.closest('.category-nk__filter__box');
				if (!container) return;

				var items = container.querySelectorAll('.category-nk__filter__categories__items li');
				var hasVisible = false;

				items.forEach(function (item) {
					var label = item.querySelector('label');
					var text = label ? label.textContent.trim().toLowerCase() : '';
					if (!query || text.indexOf(query) !== -1) {
						item.style.display = '';
						hasVisible = true;
					} else {
						item.style.display = 'none';
					}
				});

				var emptyMsg = container.querySelector('.category-nk__filter__search-empty');
				if (emptyMsg) {
					emptyMsg.hidden = hasVisible;
				}
			});
		});
	}

	/* ==========================================================================
	   3. Sync Sort Dropdown With Orderby Radio Inputs
	   ========================================================================== */
	function initSortDropdown() {
		var dropdownItems = document.querySelectorAll('.category-nk__sort .dropdown-item');
		var dropdownToggle = document.querySelector('.category-nk__sort .dropdown-toggle');

		dropdownItems.forEach(function (item) {
			item.addEventListener('click', function (e) {
				e.preventDefault();
				var orderby = this.getAttribute('data-orderby');
				if (!orderby) return;

				dropdownItems.forEach(function (el) { el.classList.remove('active'); });
				this.classList.add('active');

				if (dropdownToggle) {
					dropdownToggle.textContent = this.textContent.trim();
				}

				var radio = document.querySelector('.category-sort ul li input[value="' + orderby + '"]');
				if (radio) {
					radio.checked = true;
					jQuery(radio).trigger('change');
				}
			});
		});
	}
})();
