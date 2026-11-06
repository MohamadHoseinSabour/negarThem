/**
 * Single Product JavaScript Controller
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initGallery();
		initQuantitySteppers();
		initProductTabs();
	});

	/* ==========================================================================
	   1. Product Gallery & Lightbox
	   ========================================================================== */
	function initGallery() {
		var mainImg = document.getElementById('negar-main-gallery-image');
		var thumbBtns = document.querySelectorAll('.negar-thumb-btn');
		var zoomBtn = document.querySelector('.negar-zoom-btn');
		var lightbox = document.getElementById('negar-gallery-lightbox');
		var lightboxImg = document.getElementById('negar-lightbox-img');
		var closeLightbox = document.querySelector('.negar-close-lightbox');

		if (!mainImg) return;

		// Thumbnail switching
		thumbBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var largeSrc = this.getAttribute('data-large-src');
				var fullSrc = this.getAttribute('data-full-src');

				if (largeSrc) {
					mainImg.style.opacity = '0.3';
					setTimeout(function () {
						mainImg.src = largeSrc;
						mainImg.setAttribute('data-zoom-src', fullSrc);
						mainImg.style.opacity = '1';
					}, 150);
				}

				if (lightboxImg && fullSrc) {
					lightboxImg.src = fullSrc;
				}

				thumbBtns.forEach(function (b) {
					b.classList.remove('border-primary', 'shadow-sm', 'scale-105');
					b.classList.add('border-border', 'opacity-70');
				});
				this.classList.add('border-primary', 'shadow-sm', 'scale-105');
				this.classList.remove('border-border', 'opacity-70');
			});
		});

		// Lightbox Zoom
		if (zoomBtn && lightbox) {
			zoomBtn.addEventListener('click', function () {
				var fullSrc = mainImg.getAttribute('data-zoom-src') || mainImg.src;
				if (lightboxImg) lightboxImg.src = fullSrc;
				lightbox.classList.add('is-open');
				document.body.classList.add('overflow-hidden');
			});

			function hideLightbox() {
				lightbox.classList.remove('is-open');
				document.body.classList.remove('overflow-hidden');
			}

			if (closeLightbox) closeLightbox.addEventListener('click', hideLightbox);
			lightbox.addEventListener('click', function (e) {
				if (e.target === lightbox) hideLightbox();
			});

			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' && lightbox.classList.contains('is-open')) {
					hideLightbox();
				}
			});
		}
	}

	/* ==========================================================================
	   2. Quantity Controls (+ / -)
	   ========================================================================== */
	function initQuantitySteppers() {
		var qtyWrappers = document.querySelectorAll('.negar-add-to-cart-wrapper .quantity');

		qtyWrappers.forEach(function (wrapper) {
			var input = wrapper.querySelector('input.qty');
			if (!input || wrapper.querySelector('.negar-qty-btn')) return;

			// Create Minus Button
			var minusBtn = document.createElement('button');
			minusBtn.type = 'button';
			minusBtn.className = 'negar-qty-btn minus';
			minusBtn.innerHTML = '&minus;';
			minusBtn.setAttribute('aria-label', 'کاهش تعداد');

			// Create Plus Button
			var plusBtn = document.createElement('button');
			plusBtn.type = 'button';
			plusBtn.className = 'negar-qty-btn plus';
			plusBtn.innerHTML = '+';
			plusBtn.setAttribute('aria-label', 'افزایش تعداد');

			wrapper.insertBefore(minusBtn, input);
			wrapper.appendChild(plusBtn);

			minusBtn.addEventListener('click', function () {
				var current = parseInt(input.value, 10) || 1;
				var min = parseInt(input.getAttribute('min'), 10) || 1;
				var step = parseInt(input.getAttribute('step'), 10) || 1;
				if (current > min) {
					input.value = current - step;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}
			});

			plusBtn.addEventListener('click', function () {
				var current = parseInt(input.value, 10) || 1;
				var max = parseInt(input.getAttribute('max'), 10) || 9999;
				var step = parseInt(input.getAttribute('step'), 10) || 1;
				if (current < max) {
					input.value = current + step;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}
			});
		});
	}

	/* ==========================================================================
	   3. Product Tabs Navigation
	   ========================================================================== */
	function initProductTabs() {
		var tabButtons = document.querySelectorAll('.negar-tab-btn');
		var tabPanels = document.querySelectorAll('.negar-tab-panel');

		if (!tabButtons.length || !tabPanels.length) return;

		tabButtons.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var targetId = this.getAttribute('data-tab');

				tabButtons.forEach(function (b) {
					b.classList.remove('is-active');
					b.setAttribute('aria-selected', 'false');
				});

				tabPanels.forEach(function (panel) {
					panel.classList.add('hidden');
				});

				this.classList.add('is-active');
				this.setAttribute('aria-selected', 'true');

				var activePanel = document.getElementById(targetId);
				if (activePanel) {
					activePanel.classList.remove('hidden');
				}
			});
		});
	}

})();
