/**
 * Cart page AJAX quantity updates (Style 1 + Style 2).
 *
 * Uses WooCommerce core `wc-cart` handlers — no custom cart endpoints.
 * Scoped to the cart page form only; does not touch mini-cart / slide-cart logic.
 */
(function ($) {
	'use strict';

	if (typeof wc_cart_params === 'undefined') {
		return;
	}

	var DEBOUNCE_MS = 450;
	var updateTimer = null;
	var lastQtySnapshot = '';
	var QTY_INPUT = '.woocommerce-cart-form .cart_item .qty, .woocommerce-cart-form .cart_item input[name*="[qty]"]';

	function getCartForm() {
		return $('form.woocommerce-cart-form').first();
	}

	function getQtyInputs($form) {
		return ($form || getCartForm()).find('.cart_item .qty, .cart_item input[name*="[qty]"]');
	}

	function getQtySnapshot($form) {
		var data = {};
		getQtyInputs($form).each(function () {
			var name = this.name;
			if (name) {
				data[name] = $(this).val();
			}
		});
		return JSON.stringify(data);
	}

	function rememberQtySnapshot($form) {
		lastQtySnapshot = getQtySnapshot($form || getCartForm());
	}

	function scheduleQuantityUpdate() {
		window.clearTimeout(updateTimer);
		updateTimer = window.setTimeout(runQuantityUpdate, DEBOUNCE_MS);
	}

	function runQuantityUpdate() {
		var $form = getCartForm();

		if (!$form.length) {
			return;
		}

		// Wait until the previous WC cart AJAX request finishes.
		if ($form.hasClass('processing')) {
			scheduleQuantityUpdate();
			return;
		}

		var snapshot = getQtySnapshot($form);
		if (snapshot === lastQtySnapshot) {
			return;
		}

		// Route through WooCommerce cart.js → quantity_update().
		$form.find(':input[type="submit"]').removeAttr('clicked');
		$form
			.find(':input[name="update_cart"]')
			.prop('disabled', false)
			.removeAttr('disabled')
			.attr('clicked', 'true');

		$form.trigger('submit');
	}

	$(function () {
		var $form = getCartForm();
		if (!$form.length) {
			return;
		}

		rememberQtySnapshot($form);

		// Qty typing + Style 1/2 +/- (after the value changes).
		$(document).on('change.kavehCartPageAjax input.kavehCartPageAjax', QTY_INPUT, scheduleQuantityUpdate);

		// Style 1 +/- safety net.
		$(document).on('click.kavehCartPageAjax', 'button.kplustwo, button.kminustwo', function () {
			var $qty = $(this).closest('.quantity, .product-quantity').find('.qty, input[type="number"]').first();
			window.setTimeout(function () {
				if ($qty.length) {
					$qty.trigger('change');
				}
			}, 0);
		});

		// Style 2 +/- — ensure AJAX runs even if another handler misses the change.
		$(document).on('click.kavehCartPageAjax', '.cart-nk [data-kaveh-qty]', function () {
			window.setTimeout(scheduleQuantityUpdate, 0);
		});

		$(document.body).on('updated_wc_div.kavehCartPageAjax updated_cart_totals.kavehCartPageAjax', function () {
			rememberQtySnapshot();

			// Keep header / mini-cart counts in sync after classic cart AJAX.
			if (document.body.classList.contains('woocommerce-cart')) {
				$(document.body).trigger('wc_fragment_refresh');
			}
		});
	});
})(jQuery);
