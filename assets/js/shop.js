/**
 * Shop & Archive JavaScript Controller
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initViewSwitcher();
		initMobileDrawer();
		initAjaxFilters();
	});

	/* ==========================================================================
	   1. Grid / List View Switcher
	   ========================================================================== */
	function initViewSwitcher() {
		var buttons = document.querySelectorAll('.negar-view-btn');
		var grid = document.getElementById('negar-shop-products-loop');
		if (!buttons.length || !grid) return;

		// Restore saved preference
		var savedView = localStorage.getItem('negar_shop_view') || 'grid';
		setView(savedView);

		buttons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var view = this.getAttribute('data-view');
				setView(view);
				localStorage.setItem('negar_shop_view', view);
			});
		});

		function setView(view) {
			buttons.forEach(function (b) {
				if (b.getAttribute('data-view') === view) {
					b.classList.add('is-active');
				} else {
					b.classList.remove('is-active');
				}
			});

			if (view === 'list') {
				grid.classList.remove('is-grid', 'grid', 'grid-cols-2', 'md:grid-cols-3', 'xl:grid-cols-4');
				grid.classList.add('is-list');
			} else {
				grid.classList.remove('is-list');
				grid.classList.add('is-grid', 'grid', 'grid-cols-2', 'md:grid-cols-3', 'xl:grid-cols-4');
			}
		}
	}

	/* ==========================================================================
	   2. Mobile Filters Off-Canvas Drawer
	   ========================================================================== */
	function initMobileDrawer() {
		var openBtn = document.querySelector('.negar-open-filters-drawer');
		var drawer = document.querySelector('.negar-filters-drawer');
		var backdrop = document.querySelector('.negar-filters-backdrop');
		var closeBtn = document.querySelector('.negar-close-filters-drawer');

		if (!drawer || !backdrop) return;

		function openDrawer() {
			drawer.classList.add('is-open');
			backdrop.classList.add('is-open');
			document.body.classList.add('overflow-hidden');
			if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
		}

		function closeDrawer() {
			drawer.classList.remove('is-open');
			backdrop.classList.remove('is-open');
			document.body.classList.remove('overflow-hidden');
			if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
		}

		if (openBtn) openBtn.addEventListener('click', openDrawer);
		if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
		backdrop.addEventListener('click', closeDrawer);

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
				closeDrawer();
			}
		});
	}

	/* ==========================================================================
	   3. Ajax Live Filters & Pagination
	   ========================================================================== */
	function initAjaxFilters() {
		var loopWrapper = document.querySelector('.negar-products-loop-wrapper');
		var grid = document.getElementById('negar-shop-products-loop');
		var pagination = document.getElementById('negar-shop-pagination');
		var countEl = document.querySelector('.negar-products-count');
		var chipsWrapper = document.querySelector('.negar-active-chips-wrapper');
		var orderbySelect = document.querySelector('.negar-orderby-select');

		if (!loopWrapper || !grid || typeof negarShopVars === 'undefined') return;

		var abortController = null;

		function getFilterData(paged) {
			paged = paged || 1;
			var data = {
				action: 'negar_shop_filter_products',
				nonce: negarShopVars.nonce,
				paged: paged,
				orderby: orderbySelect ? orderbySelect.value : 'menu_order'
			};

			// Category
			var catChecked = document.querySelector('input[name="product_cat"]:checked');
			if (catChecked && catChecked.value) {
				data.product_cat = catChecked.value;
			}

			// In Stock
			var inStock = document.getElementById('negar-filter-in-stock');
			if (inStock && inStock.checked) {
				data.in_stock = '1';
			}

			// On Sale
			var onSale = document.getElementById('negar-filter-on-sale');
			if (onSale && onSale.checked) {
				data.on_sale = '1';
			}

			// Price Range
			var minPrice = document.getElementById('negar-min-price');
			var maxPrice = document.getElementById('negar-max-price');
			if (minPrice && minPrice.value.trim() !== '') {
				data.min_price = minPrice.value.trim();
			}
			if (maxPrice && maxPrice.value.trim() !== '') {
				data.max_price = maxPrice.value.trim();
			}

			return data;
		}

		function fetchProducts(paged, updateUrl) {
			paged = paged || 1;
			if (updateUrl === undefined) updateUrl = true;

			if (abortController) {
				abortController.abort();
			}
			abortController = new AbortController();

			loopWrapper.classList.add('is-loading');

			var filterData = getFilterData(paged);
			var formData = new FormData();
			for (var key in filterData) {
				formData.append(key, filterData[key]);
			}

			fetch(negarShopVars.ajax_url, {
				method: 'POST',
				body: formData,
				signal: abortController.signal
			})
				.then(function (res) {
					return res.json();
				})
				.then(function (response) {
					loopWrapper.classList.remove('is-loading');
					if (response.success && response.data) {
						var d = response.data;
						grid.innerHTML = d.html;
						if (pagination) pagination.innerHTML = d.pagination;
						if (countEl) countEl.innerHTML = d.count_html;
						if (chipsWrapper) chipsWrapper.innerHTML = d.active_chips_html;

						// Preserve current view mode (grid / list)
						var savedView = localStorage.getItem('negar_shop_view') || 'grid';
						if (savedView === 'list') {
							grid.classList.remove('is-grid', 'grid', 'grid-cols-2', 'md:grid-cols-3', 'xl:grid-cols-4');
							grid.classList.add('is-list');
						} else {
							grid.classList.remove('is-list');
							grid.classList.add('is-grid', 'grid', 'grid-cols-2', 'md:grid-cols-3', 'xl:grid-cols-4');
						}

						if (paged > 1) {
							grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
						}

						if (updateUrl) {
							updateBrowserUrl(filterData);
						}
					}
				})
				.catch(function (err) {
					if (err.name !== 'AbortError') {
						loopWrapper.classList.remove('is-loading');
						console.error('Filter request failed:', err);
					}
				});
		}

		function updateBrowserUrl(data) {
			var url = new URL(window.location.href);
			var keys = ['product_cat', 'min_price', 'max_price', 'in_stock', 'on_sale', 'orderby', 'paged'];
			keys.forEach(function (k) {
				if (data[k] && data[k] !== '' && data[k] !== 'menu_order' && !(k === 'paged' && data[k] == 1)) {
					url.searchParams.set(k, data[k]);
				} else {
					url.searchParams.delete(k);
				}
			});
			window.history.pushState({ paged: data.paged }, '', url.toString());
		}

		// Event: Category Change
		document.addEventListener('change', function (e) {
			if (e.target && e.target.name === 'product_cat') {
				fetchProducts(1);
			}
			if (e.target && (e.target.id === 'negar-filter-in-stock' || e.target.id === 'negar-filter-on-sale')) {
				fetchProducts(1);
			}
		});

		// Event: Orderby Select
		if (orderbySelect) {
			orderbySelect.addEventListener('change', function () {
				fetchProducts(1);
			});
		}

		// Event: Apply Price Filter
		var applyPriceBtn = document.getElementById('negar-apply-price-filter');
		if (applyPriceBtn) {
			applyPriceBtn.addEventListener('click', function () {
				fetchProducts(1);
			});
		}

		var minPriceInput = document.getElementById('negar-min-price');
		var maxPriceInput = document.getElementById('negar-max-price');
		[minPriceInput, maxPriceInput].forEach(function (input) {
			if (input) {
				input.addEventListener('keydown', function (e) {
					if (e.key === 'Enter') {
						e.preventDefault();
						fetchProducts(1);
					}
				});
			}
		});

		// Event: Active Chip Removal & Clear All
		document.addEventListener('click', function (e) {
			// Remove individual chip
			var removeBtn = e.target.closest('.negar-remove-chip');
			if (removeBtn) {
				var chip = removeBtn.closest('.negar-filter-chip');
				if (chip) {
					var key = chip.getAttribute('data-filter-key');
					if (key === 'product_cat') {
						var defaultCat = document.querySelector('input[name="product_cat"][value=""]');
						if (defaultCat) defaultCat.checked = true;
					} else if (key === 'in_stock') {
						var inStock = document.getElementById('negar-filter-in-stock');
						if (inStock) inStock.checked = false;
					} else if (key === 'on_sale') {
						var onSale = document.getElementById('negar-filter-on-sale');
						if (onSale) onSale.checked = false;
					} else if (key === 'price_range') {
						if (minPriceInput) minPriceInput.value = '';
						if (maxPriceInput) maxPriceInput.value = '';
					}
					fetchProducts(1);
				}
				return;
			}

			// Clear all filters
			if (e.target.closest('.negar-clear-all-filters') || e.target.closest('.negar-clear-all-chips')) {
				var allCat = document.querySelector('input[name="product_cat"][value=""]');
				if (allCat) allCat.checked = true;
				var inStock = document.getElementById('negar-filter-in-stock');
				if (inStock) inStock.checked = false;
				var onSale = document.getElementById('negar-filter-on-sale');
				if (onSale) onSale.checked = false;
				if (minPriceInput) minPriceInput.value = '';
				if (maxPriceInput) maxPriceInput.value = '';
				fetchProducts(1);
				return;
			}

			// Ajax Pagination clicks
			var pageLink = e.target.closest('.negar-pagination a.page-numbers');
			if (pageLink) {
				e.preventDefault();
				var href = pageLink.getAttribute('href');
				var match = href.match(/paged=(\d+)/) || href.match(/\/page\/(\d+)/);
				var paged = match ? parseInt(match[1], 10) : 1;
				fetchProducts(paged);
			}
		});

		// Handle browser back/forward buttons
		window.addEventListener('popstate', function () {
			var url = new URL(window.location.href);
			var paged = parseInt(url.searchParams.get('paged') || '1', 10);
			fetchProducts(paged, false);
		});
	}

})();
