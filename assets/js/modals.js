/**
 * Modals, QuickView, Wishlist, Compare & Unified Toast Notifications
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

(function () {
	'use strict';

	/**
	 * Configuration & State
	 */
	const STORAGE_KEY_WISHLIST = 'negar_wishlist';
	const STORAGE_KEY_COMPARE  = 'negar_compare';

	let lastFocusedElement = null;
	let lastToast = { message: '', timestamp: 0 };

	const getParams = () => window.negarShopParams || {
		ajaxUrl: (window.kavehShopData && window.kavehShopData.ajaxUrl) ? window.kavehShopData.ajaxUrl : '/wp-admin/admin-ajax.php',
		nonce: (window.kavehShopData && window.kavehShopData.nonce) ? window.kavehShopData.nonce : '',
		i18n: {}
	};

	/* ==========================================================================
	   1. Global Unified Toast Notification System (Powered by Notiflix)
	   ========================================================================== */
	if (typeof Notiflix !== 'undefined' && Notiflix.Notify) {
		Notiflix.Notify.init({
			width: '320px',
			position: 'right-top',
			distance: '24px',
			opacity: 1,
			borderRadius: '12px',
			rtl: true,
			fontFamily: 'iranyekan, Vazirmatn, Tahoma, sans-serif',
			useIcon: true,
			fontSize: '13px',
			timeout: 3500,
			cssAnimationDuration: 300,
			cssAnimationStyle: 'fade',
			success: {
				background: '#ffffff',
				textColor: '#1e293b',
				childClassName: 'notiflix-notify-success',
				notiflixIconColor: '#10b981',
				fontAwesomeIconColor: '#10b981',
				backOverlayColor: 'rgba(0,0,0,0.2)',
			},
			failure: {
				background: '#ffffff',
				textColor: '#1e293b',
				childClassName: 'notiflix-notify-failure',
				notiflixIconColor: '#f43f5e',
			},
			warning: {
				background: '#ffffff',
				textColor: '#1e293b',
				childClassName: 'notiflix-notify-warning',
				notiflixIconColor: '#f59e0b',
			},
			info: {
				background: '#ffffff',
				textColor: '#1e293b',
				childClassName: 'notiflix-notify-info',
				notiflixIconColor: '#0284c7',
			}
		});
	}

	let lastActionMap = {};

	/**
	 * Canonical toast notification function with action-based & string-based de-duplication and a11y announcement.
	 *
	 * @param {string} message Toast message.
	 * @param {string} type 'success' | 'error' | 'warning' | 'info'
	 * @param {number} duration Timeout in ms.
	 * @param {string|null} actionKey Action ID to deduplicate across different messages (e.g. 'cart_add')
	 */
	window.negarToast = function (message, type = 'info', duration = 3500, actionKey = null) {
		if (!message) return;

		const now = Date.now();

		// Action-based deduplication: suppress duplicate actions within 1800ms
		if (actionKey) {
			if (lastActionMap[actionKey] && (now - lastActionMap[actionKey]) < 1800) {
				return;
			}
			lastActionMap[actionKey] = now;
		}

		// De-duplication: prevent identical toasts within 1500ms
		if (lastToast.message === message && (now - lastToast.timestamp) < 1500) {
			return;
		}
		lastToast = { message: message, timestamp: now };

		// Screen-reader live region announcement
		let announcer = document.getElementById('negar-live-announcer');
		if (!announcer) {
			announcer = document.createElement('div');
			announcer.id = 'negar-live-announcer';
			announcer.className = 'sr-only';
			announcer.setAttribute('aria-live', 'polite');
			announcer.setAttribute('aria-atomic', 'true');
			document.body.appendChild(announcer);
		}
		announcer.textContent = message;

		// 1. Primary: Notiflix adapter
		if (typeof Notiflix !== 'undefined' && Notiflix.Notify) {
			if (type === 'success') {
				Notiflix.Notify.success(message, { timeout: duration });
				return;
			} else if (type === 'error') {
				Notiflix.Notify.failure(message, { timeout: duration });
				return;
			} else if (type === 'warning') {
				Notiflix.Notify.warning(message, { timeout: duration });
				return;
			} else {
				Notiflix.Notify.info(message, { timeout: duration });
				return;
			}
		}

		// 2. Fallback: Custom accessible DOM toast container
		const container = document.getElementById('negar-toast-container');
		if (!container) return;

		const toast = document.createElement('div');
		toast.className = `negar-toast toast-${type}`;
		toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

		let iconSvg = '';
		if (type === 'success') {
			iconSvg = '<svg class="w-5 h-5 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
		} else if (type === 'error') {
			iconSvg = '<svg class="w-5 h-5 text-rose-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
		} else if (type === 'warning') {
			iconSvg = '<svg class="w-5 h-5 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
		} else {
			iconSvg = '<svg class="w-5 h-5 text-sky-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
		}

		toast.innerHTML = `
			<div class="toast-icon flex items-center justify-center p-1.5 rounded-lg">
				${iconSvg}
			</div>
			<div class="toast-message text-xs sm:text-sm font-medium text-slate-800 flex-1 leading-snug">
				${message}
			</div>
			<button type="button" class="toast-close text-slate-400 hover:text-slate-600 p-1" aria-label="بستن">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
			</button>
		`;

		container.appendChild(toast);

		requestAnimationFrame(() => {
			toast.classList.add('is-show');
		});

		const removeToast = () => {
			toast.classList.remove('is-show');
			setTimeout(() => {
				if (toast.parentNode) {
					toast.parentNode.removeChild(toast);
				}
			}, 300);
		};

		const autoTimer = setTimeout(removeToast, duration);

		toast.querySelector('.toast-close').addEventListener('click', () => {
			clearTimeout(autoTimer);
			removeToast();
		});
	};

	/* ==========================================================================
	   2. Wishlist Storage & UI Management
	   ========================================================================== */
	const getWishlist = () => {
		try {
			return JSON.parse(localStorage.getItem(STORAGE_KEY_WISHLIST)) || [];
		} catch (e) {
			return [];
		}
	};

	const setWishlist = (ids) => {
		try {
			localStorage.setItem(STORAGE_KEY_WISHLIST, JSON.stringify(ids));
		} catch (e) {
			// localStorage full or private browsing
		}
		updateWishlistBadges();
		updateWishlistButtons();
		document.dispatchEvent(new CustomEvent('negar:wishlist_updated', { detail: { ids: ids } }));
	};

	const updateWishlistBadges = () => {
		const list = getWishlist();
		const count = list.length;
		document.querySelectorAll('.negar-wishlist-count').forEach(el => {
			el.textContent = count;
		});
	};

	const updateWishlistButtons = () => {
		const list = getWishlist();
		const selector = '.btn-wishlist-toggle, .wlm-button, [data-action="wishlist"], .add-to-wishlist';
		document.querySelectorAll(selector).forEach(btn => {
			const id = parseInt(btn.getAttribute('data-id') || btn.getAttribute('data-product-id'), 10);
			if (!id) return;

			if (list.includes(id)) {
				btn.classList.add('is-active', 'wlm-button--active', 'text-rose-500', 'bg-rose-50');
				btn.setAttribute('aria-pressed', 'true');
				const svg = btn.querySelector('svg');
				if (svg) svg.setAttribute('fill', 'currentColor');
			} else {
				btn.classList.remove('is-active', 'wlm-button--active', 'text-rose-500', 'bg-rose-50');
				btn.setAttribute('aria-pressed', 'false');
				const svg = btn.querySelector('svg');
				if (svg) svg.setAttribute('fill', 'none');
			}
		});
	};

	const toggleWishlist = (productId) => {
		const id = parseInt(productId, 10);
		if (!id) return;

		let list = getWishlist();
		const exists = list.includes(id);

		if (exists) {
			list = list.filter(item => item !== id);
			setWishlist(list);
			window.negarToast('کالا از لیست علاقه‌مندی‌ها حذف شد.', 'info');
		} else {
			list.push(id);
			setWishlist(list);
			window.negarToast('کالا به لیست علاقه‌مندی‌ها اضافه شد.', 'success');
		}

		// Refresh drawer if currently open
		const drawer = document.getElementById('negar-wishlist-drawer');
		if (drawer && drawer.classList.contains('is-open')) {
			loadWishlistDrawerContent();
		}
	};

	const openWishlistDrawer = () => {
		const drawer = document.getElementById('negar-wishlist-drawer');
		const backdrop = document.getElementById('negar-wishlist-backdrop');
		if (!drawer || !backdrop) return;

		lastFocusedElement = document.activeElement;
		drawer.classList.add('is-open');
		backdrop.classList.add('is-open');
		drawer.setAttribute('aria-hidden', 'false');
		backdrop.setAttribute('aria-hidden', 'false');
		document.body.classList.add('modal-open');

		const closeBtn = drawer.querySelector('.negar-close-wishlist');
		if (closeBtn) closeBtn.focus();

		loadWishlistDrawerContent();
	};

	const closeWishlistDrawer = () => {
		const drawer = document.getElementById('negar-wishlist-drawer');
		const backdrop = document.getElementById('negar-wishlist-backdrop');
		if (drawer) {
			drawer.classList.remove('is-open');
			drawer.setAttribute('aria-hidden', 'true');
		}
		if (backdrop) {
			backdrop.classList.remove('is-open');
			backdrop.setAttribute('aria-hidden', 'true');
		}
		document.body.classList.remove('modal-open');

		if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
			lastFocusedElement.focus();
		}
	};

	const loadWishlistDrawerContent = () => {
		const container = document.getElementById('negar-wishlist-items-container');
		if (!container) return;

		const list = getWishlist();
		if (list.length === 0) {
			container.innerHTML = `
				<div class="negar-empty-wishlist py-12 text-center">
					<div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
						<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
					</div>
					<h4 class="text-sm font-bold text-slate-800 mb-1">لیست علاقه‌مندی‌های شما خالی است!</h4>
					<p class="text-xs text-slate-500 mb-4">کالاهای مورد علاقه خود را با زدن علامت قلب به این لیست اضافه کنید.</p>
				</div>
			`;
			return;
		}

		container.innerHTML = `
			<div class="py-12 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
				<svg class="animate-spin h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
				<span>در حال بارگذاری لیست...</span>
			</div>
		`;

		const params = getParams();
		const formData = new FormData();
		formData.append('action', 'negar_shop_get_wishlist_cards');
		formData.append('nonce', params.nonce);
		list.forEach(id => formData.append('ids[]', id));

		fetch(params.ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(res => {
			if (res.success && res.data && res.data.html) {
				container.innerHTML = res.data.html;
			} else {
				container.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">خطایی رخ داد.</div>';
			}
		})
		.catch(() => {
			container.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">خطایی رخ داد.</div>';
		});
	};

	/* ==========================================================================
	   3. Product Compare Storage & Modal Management
	   ========================================================================== */
	const getCompare = () => {
		try {
			return JSON.parse(localStorage.getItem(STORAGE_KEY_COMPARE)) || [];
		} catch (e) {
			return [];
		}
	};

	const setCompare = (ids) => {
		try {
			localStorage.setItem(STORAGE_KEY_COMPARE, JSON.stringify(ids));
		} catch (e) {
			// ignore
		}
		updateCompareBadges();
		updateCompareButtons();
		document.dispatchEvent(new CustomEvent('negar:compare_updated', { detail: { ids: ids } }));
	};

	const updateCompareBadges = () => {
		const list = getCompare();
		document.querySelectorAll('.negar-compare-count').forEach(el => {
			el.textContent = list.length;
		});
	};

	const updateCompareButtons = () => {
		const list = getCompare();
		const selector = '.btn-compare-toggle, .compare-button, .add-to-compare, [data-action="compare"]';
		document.querySelectorAll(selector).forEach(btn => {
			const id = parseInt(btn.getAttribute('data-id') || btn.getAttribute('data-product-id'), 10);
			if (!id) return;

			if (list.includes(id)) {
				btn.classList.add('is-active', 'compare-button--active', 'text-primary');
				btn.setAttribute('aria-pressed', 'true');
			} else {
				btn.classList.remove('is-active', 'compare-button--active', 'text-primary');
				btn.setAttribute('aria-pressed', 'false');
			}
		});
	};

	const toggleCompare = (productId) => {
		const id = parseInt(productId, 10);
		if (!id) return;

		let list = getCompare();
		if (list.includes(id)) {
			list = list.filter(item => item !== id);
			setCompare(list);
			window.negarToast('کالا از لیست مقایسه حذف شد.', 'info');
		} else {
			if (list.length >= 4) {
				window.negarToast('حداکثر می‌توانید ۴ کالا را همزمان مقایسه کنید.', 'error');
				return;
			}
			list.push(id);
			setCompare(list);
			window.negarToast('کالا به لیست مقایسه اضافه شد.', 'success');
		}

		const modal = document.getElementById('negar-compare-modal');
		if (modal && modal.classList.contains('is-open')) {
			loadCompareTable();
		}
	};

	const openCompareModal = () => {
		const modal = document.getElementById('negar-compare-modal');
		const backdrop = document.getElementById('negar-compare-backdrop');
		if (!modal || !backdrop) return;

		lastFocusedElement = document.activeElement;
		modal.classList.add('is-open');
		backdrop.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		backdrop.setAttribute('aria-hidden', 'false');
		document.body.classList.add('modal-open');

		const closeBtn = modal.querySelector('.negar-close-compare');
		if (closeBtn) closeBtn.focus();

		loadCompareTable();
	};

	const closeCompareModal = () => {
		const modal = document.getElementById('negar-compare-modal');
		const backdrop = document.getElementById('negar-compare-backdrop');
		if (modal) {
			modal.classList.remove('is-open');
			modal.setAttribute('aria-hidden', 'true');
		}
		if (backdrop) {
			backdrop.classList.remove('is-open');
			backdrop.setAttribute('aria-hidden', 'true');
		}
		document.body.classList.remove('modal-open');

		if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
			lastFocusedElement.focus();
		}
	};

	const loadCompareTable = () => {
		const container = document.getElementById('negar-compare-table-container');
		if (!container) return;

		const list = getCompare();
		if (list.length === 0) {
			container.innerHTML = `
				<div class="py-12 text-center">
					<h4 class="text-sm font-bold text-slate-800 mb-2">هیچ کالایی برای مقایسه انتخاب نشده است!</h4>
					<p class="text-xs text-slate-500">برای مقایسه، دکمه مقایسه را روی حداقل ۲ کالا بزنید.</p>
				</div>
			`;
			return;
		}

		container.innerHTML = `
			<div class="py-12 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
				<svg class="animate-spin h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
				<span>در حال بارگذاری جدول مقایسه...</span>
			</div>
		`;

		const params = getParams();
		const formData = new FormData();
		formData.append('action', 'negar_shop_get_compare_data');
		formData.append('nonce', params.nonce);
		list.forEach(id => formData.append('ids[]', id));

		fetch(params.ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(res => {
			if (res.success && res.data && res.data.html) {
				container.innerHTML = res.data.html;
			} else {
				container.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">خطا در بارگذاری جدول مقایسه.</div>';
			}
		})
		.catch(() => {
			container.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">خطا در برقراری ارتباط.</div>';
		});
	};

	/* ==========================================================================
	   4. Quick View Modal Management
	   ========================================================================== */
	const openQuickView = (productId) => {
		const modal = document.getElementById('negar-quickview-modal');
		const backdrop = document.getElementById('negar-quickview-backdrop');
		const body = document.getElementById('negar-quickview-body');
		if (!modal || !backdrop || !body) return;

		lastFocusedElement = document.activeElement;
		modal.classList.add('is-open');
		backdrop.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		backdrop.setAttribute('aria-hidden', 'false');
		document.body.classList.add('modal-open');

		const closeBtn = modal.querySelector('.negar-close-quickview');
		if (closeBtn) closeBtn.focus();

		body.innerHTML = `
			<div class="flex items-center gap-2 text-slate-500 text-xs py-12 justify-center">
				<svg class="animate-spin h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
				<span>در حال دریافت اطلاعات کالا...</span>
			</div>
		`;

		const params = getParams();
		const formData = new FormData();
		formData.append('action', 'negar_shop_quickview');
		formData.append('nonce', params.nonce);
		formData.append('product_id', productId);

		fetch(params.ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(res => {
			if (res.success && res.data && res.data.html) {
				body.innerHTML = res.data.html;

				// Initialize WooCommerce variation form if present in loaded QuickView
				if (window.jQuery && typeof window.jQuery.fn.wc_variation_form !== 'undefined') {
					window.jQuery(body).find('.variations_form').wc_variation_form();
				}
			} else {
				body.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">اطلاعات کالا در دسترس نیست.</div>';
			}
		})
		.catch(() => {
			body.innerHTML = '<div class="py-8 text-center text-xs text-rose-500">خطایی رخ داد.</div>';
		});
	};

	const closeQuickView = () => {
		const modal = document.getElementById('negar-quickview-modal');
		const backdrop = document.getElementById('negar-quickview-backdrop');
		if (modal) {
			modal.classList.remove('is-open');
			modal.setAttribute('aria-hidden', 'true');
		}
		if (backdrop) {
			backdrop.classList.remove('is-open');
			backdrop.setAttribute('aria-hidden', 'true');
		}
		document.body.classList.remove('modal-open');

		if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
			lastFocusedElement.focus();
		}
	};

	/* ==========================================================================
	   5. Global Event Listeners & Initialization
	   ========================================================================== */
	document.addEventListener('DOMContentLoaded', () => {
		updateWishlistBadges();
		updateWishlistButtons();
		updateCompareBadges();
		updateCompareButtons();

		// Delegate clicks across document
		document.addEventListener('click', (e) => {
			// Quick View Open Button
			const quickviewBtn = e.target.closest('.btn-quickview, [data-action="quickview"]');
			if (quickviewBtn) {
				e.preventDefault();
				const id = quickviewBtn.getAttribute('data-id') || quickviewBtn.getAttribute('data-product-id');
				if (id) openQuickView(id);
				return;
			}

			// Quick View Close
			if (e.target.closest('.negar-close-quickview') || e.target.id === 'negar-quickview-backdrop') {
				e.preventDefault();
				closeQuickView();
				return;
			}

			// Wishlist Toggle Button
			const wishlistBtn = e.target.closest('.btn-wishlist-toggle, .wlm-button, [data-action="wishlist"], .add-to-wishlist');
			if (wishlistBtn) {
				e.preventDefault();
				const id = wishlistBtn.getAttribute('data-id') || wishlistBtn.getAttribute('data-product-id');
				if (id) toggleWishlist(id);
				return;
			}

			// Wishlist Drawer Open (Header icon or drawer trigger)
			const wishlistTrigger = e.target.closest('a[href*="/wishlist"], .negar-wishlist-trigger');
			if (wishlistTrigger) {
				e.preventDefault();
				openWishlistDrawer();
				return;
			}

			// Wishlist Drawer Close
			if (e.target.closest('.negar-close-wishlist') || e.target.id === 'negar-wishlist-backdrop') {
				e.preventDefault();
				closeWishlistDrawer();
				return;
			}

			// Wishlist Item Remove
			const removeWishlistBtn = e.target.closest('.negar-remove-wishlist-item');
			if (removeWishlistBtn) {
				e.preventDefault();
				const id = removeWishlistBtn.getAttribute('data-product-id');
				if (id) toggleWishlist(id);
				return;
			}

			// Clear all wishlist
			if (e.target.closest('.negar-clear-all-wishlist')) {
				e.preventDefault();
				setWishlist([]);
				loadWishlistDrawerContent();
				window.negarToast('تمام موارد از علاقه‌مندی‌ها حذف شدند.', 'info');
				return;
			}

			// Compare Toggle Button
			const compareBtn = e.target.closest('.btn-compare-toggle, .compare-button, .add-to-compare, [data-action="compare"]');
			if (compareBtn) {
				e.preventDefault();
				const id = compareBtn.getAttribute('data-id') || compareBtn.getAttribute('data-product-id');
				if (id) toggleCompare(id);
				return;
			}

			// Compare Modal Open
			const compareTrigger = e.target.closest('.negar-compare-trigger');
			if (compareTrigger) {
				e.preventDefault();
				openCompareModal();
				return;
			}

			// Compare Modal Close
			if (e.target.closest('.negar-close-compare') || e.target.id === 'negar-compare-backdrop') {
				e.preventDefault();
				closeCompareModal();
				return;
			}

			// Compare Item Remove
			const removeCompareBtn = e.target.closest('.negar-remove-compare-item');
			if (removeCompareBtn) {
				e.preventDefault();
				const id = removeCompareBtn.getAttribute('data-product-id');
				if (id) toggleCompare(id);
				return;
			}
		});

		// Close modals on Escape key
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				closeQuickView();
				closeWishlistDrawer();
				closeCompareModal();
			}
		});

		// Listen to jQuery / WooCommerce added to cart event for toast feedback
		if (window.jQuery) {
			window.jQuery(document.body).on('added_to_cart', function () {
				window.negarToast('محصول با موفقیت به سبد خرید اضافه شد.', 'success', 3500, 'cart_add');
				// On non-cart/non-checkout pages, hide duplicate notices
				if (!document.body.classList.contains('woocommerce-cart') && !document.body.classList.contains('woocommerce-checkout')) {
					window.jQuery('.woocommerce-message, .woocommerce-notices-wrapper .woocommerce-message').hide();
				}
			});
		}

		// Customer Phone OTP Authentication Modal Controller
		initLoginOtpModal();
	});

	/**
	 * Customer Phone OTP Authentication Modal Controller
	 */
	function initLoginOtpModal() {
		const modal = document.getElementById('negar-login-otp-modal');
		if (!modal) return;

		const params = getParams();
		const ajaxUrl = params.ajaxUrl || (window.kavehShopData && (window.kavehShopData.ajaxurl || window.kavehShopData.ksiteurl)) || '/wp-admin/admin-ajax.php';
		const nonce = params.nonce || (window.kavehShopData && window.kavehShopData.nonce) || '';

		const closeBtn = modal.querySelector('.negar-modal-close');
		const step1Form = document.getElementById('negar-otp-step1-form');
		const step2Form = document.getElementById('negar-otp-step2-form');
		const phoneInput = document.getElementById('negar-otp-phone-input');
		const codeInput = document.getElementById('negar-otp-code-input');
		const targetDisplay = document.getElementById('negar-otp-target-display');
		const backBtn = document.getElementById('negar-otp-back-btn');
		const timerWrap = document.getElementById('negar-otp-timer-wrap');
		const timerEl = document.getElementById('negar-otp-timer');
		const resendBtn = document.getElementById('negar-otp-resend-btn');
		const step1Error = document.getElementById('negar-otp-step1-error');
		const step2Error = document.getElementById('negar-otp-step2-error');
		const step1Submit = document.getElementById('negar-otp-step1-submit');
		const step2Submit = document.getElementById('negar-otp-step2-submit');

		let countdownInterval = null;
		let activePhone = '';

		function toPersianDigits(num) {
			const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
			return String(num).padStart(2, '0').replace(/[0-9]/g, (w) => persian[+w]);
		}

		function normalizePhoneDigits(val) {
			const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
			const arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
			let res = val || '';
			for (let i = 0; i < 10; i++) {
				res = res.replaceAll(persian[i], String(i)).replaceAll(arabic[i], String(i));
			}
			return res.replace(/[^\d+]/g, '');
		}

		function openModal() {
			modal.classList.remove('hidden');
			resetForms();
			setTimeout(() => {
				if (phoneInput) phoneInput.focus();
			}, 100);
			document.body.classList.add('overflow-hidden');
		}

		function closeModal() {
			modal.classList.add('hidden');
			clearInterval(countdownInterval);
			document.body.classList.remove('overflow-hidden');
		}

		function resetForms() {
			if (step1Form) {
				step1Form.reset();
				step1Form.classList.remove('hidden');
			}
			if (step2Form) {
				step2Form.reset();
				step2Form.classList.add('hidden');
			}
			if (step1Error) step1Error.classList.add('hidden');
			if (step2Error) step2Error.classList.add('hidden');
			clearInterval(countdownInterval);
		}

		function startTimer(seconds) {
			clearInterval(countdownInterval);
			let remaining = seconds;
			if (timerWrap) timerWrap.classList.remove('hidden');
			if (resendBtn) {
				resendBtn.classList.add('hidden');
				resendBtn.disabled = true;
			}

			function updateTimerUI() {
				const m = Math.floor(remaining / 60);
				const s = remaining % 60;
				if (timerEl) {
					timerEl.textContent = `${toPersianDigits(m)}:${toPersianDigits(s)}`;
				}
				if (remaining <= 0) {
					clearInterval(countdownInterval);
					if (timerWrap) timerWrap.classList.add('hidden');
					if (resendBtn) {
						resendBtn.classList.remove('hidden');
						resendBtn.disabled = false;
					}
				}
				remaining--;
			}

			updateTimerUI();
			countdownInterval = setInterval(updateTimerUI, 1000);
		}

		// Triggers: any button with class .negar-login-trigger
		document.addEventListener('click', function (e) {
			const trigger = e.target.closest('.negar-login-trigger');
			if (trigger) {
				e.preventDefault();
				openModal();
			}
		});

		if (closeBtn) {
			closeBtn.addEventListener('click', closeModal);
		}

		modal.addEventListener('click', function (e) {
			if (e.target === modal) {
				closeModal();
			}
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
				closeModal();
			}
		});

		if (backBtn) {
			backBtn.addEventListener('click', function () {
				clearInterval(countdownInterval);
				if (step2Form) step2Form.classList.add('hidden');
				if (step1Form) step1Form.classList.remove('hidden');
				if (step1Error) step1Error.classList.add('hidden');
				if (phoneInput) phoneInput.focus();
			});
		}

		if (resendBtn) {
			resendBtn.addEventListener('click', function () {
				if (!activePhone) return;
				requestOtp(activePhone);
			});
		}

		function requestOtp(phone) {
			const spinner = step1Submit ? step1Submit.querySelector('.negar-otp-spinner') : null;
			if (spinner) spinner.classList.remove('hidden');
			if (step1Submit) step1Submit.disabled = true;
			if (step1Error) step1Error.classList.add('hidden');

			const formData = new URLSearchParams();
			formData.append('action', 'negar_auth_send_otp');
			formData.append('nonce', nonce);
			formData.append('phone', phone);

			fetch(ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: formData.toString(),
			})
				.then((res) => res.json())
				.then((data) => {
					if (spinner) spinner.classList.add('hidden');
					if (step1Submit) step1Submit.disabled = false;

					if (data.success) {
						activePhone = phone;
						if (targetDisplay) targetDisplay.textContent = phone;
						if (step1Form) step1Form.classList.add('hidden');
						if (step2Form) step2Form.classList.remove('hidden');
						startTimer(data.data.cooldown || 60);
						if (data.data.mock_code && codeInput) {
							codeInput.value = data.data.mock_code;
						}
						if (codeInput) codeInput.focus();
						window.negarToast(data.data.message || 'کد تایید ارسال شد.', 'info');
					} else {
						if (step1Error) {
							step1Error.textContent = data.data?.message || 'خطا در ارسال کد تایید';
							step1Error.classList.remove('hidden');
						}
					}
				})
				.catch(() => {
					if (spinner) spinner.classList.add('hidden');
					if (step1Submit) step1Submit.disabled = false;
					if (step1Error) {
						step1Error.textContent = 'خطای ارتباط با سرور. لطفاً مجدداً امتحان کنید.';
						step1Error.classList.remove('hidden');
					}
				});
		}

		if (step1Form) {
			step1Form.addEventListener('submit', function (e) {
				e.preventDefault();
				const raw = phoneInput ? phoneInput.value.trim() : '';
				const normalized = normalizePhoneDigits(raw);
				requestOtp(normalized);
			});
		}

		if (step2Form) {
			step2Form.addEventListener('submit', function (e) {
				e.preventDefault();
				const rawCode = codeInput ? codeInput.value.trim() : '';
				const code = normalizePhoneDigits(rawCode);
				const spinner = step2Submit ? step2Submit.querySelector('.negar-otp-spinner') : null;

				if (!code || code.length < 5) {
					if (step2Error) {
						step2Error.textContent = 'لطفاً کد ۵ رقمی را کامل وارد نمایید.';
						step2Error.classList.remove('hidden');
					}
					return;
				}

				if (spinner) spinner.classList.remove('hidden');
				if (step2Submit) step2Submit.disabled = true;
				if (step2Error) step2Error.classList.add('hidden');

				const formData = new URLSearchParams();
				formData.append('action', 'negar_auth_verify_otp');
				formData.append('nonce', nonce);
				formData.append('phone', activePhone);
				formData.append('code', code);

				fetch(ajaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: formData.toString(),
				})
					.then((res) => res.json())
					.then((data) => {
						if (spinner) spinner.classList.add('hidden');
						if (step2Submit) step2Submit.disabled = false;

						if (data.success) {
							window.negarToast(data.data.message || 'ورود با موفقیت انجام شد.', 'success');
							closeModal();
							setTimeout(() => {
								window.location.reload();
							}, 600);
						} else {
							if (step2Error) {
								step2Error.textContent = data.data?.message || 'کد وارد شده معتبر نمی‌باشد.';
								step2Error.classList.remove('hidden');
							}
						}
					})
					.catch(() => {
						if (spinner) spinner.classList.add('hidden');
						if (step2Submit) step2Submit.disabled = false;
						if (step2Error) {
							step2Error.textContent = 'خطای سرور در بررسی کد. لطفاً دوباره تلاش کنید.';
							step2Error.classList.remove('hidden');
						}
					});
			});
		}
	}

})();
