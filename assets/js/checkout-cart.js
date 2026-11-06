/**
 * Cart, Checkout & My Account JavaScript Controller
 *
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initCartQuantitySteppers();
		initPasswordToggle();
	});

	/* ==========================================================================
	   1. Cart Quantity Steppers & Auto Update
	   ========================================================================== */
	function initCartQuantitySteppers() {
		var qtyBoxes = document.querySelectorAll('.negar-cart-qty-box .quantity');
		var updateCartBtn = document.querySelector('button[name="update_cart"]');
		var debounceTimer = null;

		qtyBoxes.forEach(function (box) {
			var input = box.querySelector('input.qty');
			if (!input || box.querySelector('.negar-cart-qty-btn')) return;

			// Minus Button
			var minusBtn = document.createElement('button');
			minusBtn.type = 'button';
			minusBtn.className = 'negar-cart-qty-btn minus';
			minusBtn.innerHTML = '&minus;';
			minusBtn.setAttribute('aria-label', 'کاهش');

			// Plus Button
			var plusBtn = document.createElement('button');
			plusBtn.type = 'button';
			plusBtn.className = 'negar-cart-qty-btn plus';
			plusBtn.innerHTML = '+';
			plusBtn.setAttribute('aria-label', 'افزایش');

			box.insertBefore(minusBtn, input);
			box.appendChild(plusBtn);

			minusBtn.addEventListener('click', function () {
				var current = parseInt(input.value, 10) || 1;
				var min = parseInt(input.getAttribute('min'), 10) || 0;
				var step = parseInt(input.getAttribute('step'), 10) || 1;
				if (current > min) {
					input.value = current - step;
					triggerCartUpdate();
				}
			});

			plusBtn.addEventListener('click', function () {
				var current = parseInt(input.value, 10) || 1;
				var max = parseInt(input.getAttribute('max'), 10) || 9999;
				var step = parseInt(input.getAttribute('step'), 10) || 1;
				if (current < max) {
					input.value = current + step;
					triggerCartUpdate();
				}
			});
		});

		function triggerCartUpdate() {
			if (!updateCartBtn) return;
			updateCartBtn.removeAttribute('disabled');
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () {
				updateCartBtn.click();
			}, 800);
		}
	}

	/* ==========================================================================
	   2. Password Visibility Toggle
	   ========================================================================== */
	function initPasswordToggle() {
		var passInputs = document.querySelectorAll('.woocommerce-form input[type="password"]');

		passInputs.forEach(function (input) {
			var wrapper = input.parentElement;
			if (!wrapper) return;

			var toggleBtn = document.createElement('button');
			toggleBtn.type = 'button';
			toggleBtn.className = 'absolute left-3 top-1/2 -translate-y-1/2 text-muted hover:text-dark focus:outline-none';
			toggleBtn.setAttribute('aria-label', 'نمایش رمز عبور');
			toggleBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';

			wrapper.appendChild(toggleBtn);

			toggleBtn.addEventListener('click', function () {
				if (input.type === 'password') {
					input.type = 'text';
					toggleBtn.classList.add('text-primary');
				} else {
					input.type = 'password';
					toggleBtn.classList.remove('text-primary');
				}
			});
		});
	}

})();
