/**
 * Modals, QuickView, Wishlist, Compare & Toast Notifications
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
	'use strict';

	/**
	 * Configuration & State
	 */
	const STORAGE_KEY_WISHLIST = 'negar_wishlist';
	const STORAGE_KEY_COMPARE  = 'negar_compare';

	const getParams = () => window.negarShopParams || {
		ajaxUrl: '/wp-admin/admin-ajax.php',
		nonce: '',
		i18n: {}
	};

	/* ==========================================================================
	   1. Global Toast Notification System (Powered by Notiflix)
	   ========================================================================== */
	if (typeof Notiflix !== 'undefined' && Notiflix.Notify) {
		Notiflix.Notify.init({
			width: '320px',
			position: 'right-top',
			distance: '24px',
			opacity: 1,
			borderRadius: '12px',
			rtl: true,
			fontFamily: 'iranyekan, Vazirmatn, Tahoma',
			useIcon: true,
			fontSize: '13px',
			timeout: 3500,
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
			}
		});
	}

	window.negarToast = function (message, type = 'info', duration = 3500) {
		if (typeof Notiflix !== 'undefined' && Notiflix.Notify) {
			if (type === 'success') {
				Notiflix.Notify.success(message);
				return;
			} else if (type === 'error') {
				Notiflix.Notify.failure(message);
				return;
			} else if (type === 'warning') {
				Notiflix.Notify.warning(message);
				return;
			} else {
				Notiflix.Notify.info(message);
				return;
			}
		}

		const container = document.getElementById('negar-toast-container');
		if (!container) return;

		const toast = document.createElement('div');
		toast.className = `negar-toast toast-${type}`;
		toast.setAttribute('role', 'status');

		let iconSvg = '';
		if (type === 'success') {
			iconSvg = '<svg class="w-5 h-5 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>';
		} else if (type === 'error') {
			iconSvg = '<svg class="w-5 h-5 text-rose-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
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

		// Animate in
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
		document.querySelectorAll('.btn-wishlist-toggle').forEach(btn => {
			const id = parseInt(btn.getAttribute('data-id'), 10);
			if (list.includes(id)) {
				btn.classList.add('is-active', 'text-rose-500', 'bg-rose-50');
				const svg = btn.querySelector('svg');
				if (svg) svg.setAttribute('fill', 'currentColor');
			} else {
				btn.classList.remove('is-active', 'text-rose-500', 'bg-rose-50');
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

		drawer.classList.add('is-open');
		backdrop.classList.add('is-open');
		document.body.classList.add('modal-open');

		loadWishlistDrawerContent();
	};

	const closeWishlistDrawer = () => {
		const drawer = document.getElementById('negar-wishlist-drawer');
		const backdrop = document.getElementById('negar-wishlist-backdrop');
		if (drawer) drawer.classList.remove('is-open');
		if (backdrop) backdrop.classList.remove('is-open');
		document.body.classList.remove('modal-open');
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
			if (res.success && res.data.html) {
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
	};

	const updateCompareBadges = () => {
		const list = getCompare();
		document.querySelectorAll('.negar-compare-count').forEach(el => {
			el.textContent = list.length;
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

		modal.classList.add('is-open');
		backdrop.classList.add('is-open');
		document.body.classList.add('modal-open');

		loadCompareTable();
	};

	const closeCompareModal = () => {
		const modal = document.getElementById('negar-compare-modal');
		const backdrop = document.getElementById('negar-compare-backdrop');
		if (modal) modal.classList.remove('is-open');
		if (backdrop) backdrop.classList.remove('is-open');
		document.body.classList.remove('modal-open');
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
			if (res.success && res.data.html) {
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

		modal.classList.add('is-open');
		backdrop.classList.add('is-open');
		document.body.classList.add('modal-open');

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
			if (res.success && res.data.html) {
				body.innerHTML = res.data.html;
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
		if (modal) modal.classList.remove('is-open');
		if (backdrop) backdrop.classList.remove('is-open');
		document.body.classList.remove('modal-open');
	};

	/* ==========================================================================
	   5. Global Event Listeners & Initialization
	   ========================================================================== */
	document.addEventListener('DOMContentLoaded', () => {
		updateWishlistBadges();
		updateWishlistButtons();
		updateCompareBadges();

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
			const wishlistBtn = e.target.closest('.btn-wishlist-toggle');
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
			const compareBtn = e.target.closest('.btn-compare-toggle');
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
				window.negarToast('محصول با موفقیت به سبد خرید اضافه شد.', 'success');
				window.jQuery('.woocommerce-message, .woocommerce-notices-wrapper .woocommerce-message').hide();
			});
		}
	});

})();
