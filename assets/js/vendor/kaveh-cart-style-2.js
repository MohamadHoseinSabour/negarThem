(function ($) {
	'use strict';

	var couponNoticeTimer = null;

	function enableCartUpdateButton() {
		var $update = $('button[name="update_cart"], input[name="update_cart"]');
		if ($update.length) {
			$update.prop('disabled', false).removeAttr('disabled').removeClass('disabled');
		}
	}

	function adjustQuantity($input, type) {
		var min = parseFloat($input.attr('min'));
		var max = parseFloat($input.attr('max'));
		var step = parseFloat($input.attr('step')) || 1;
		var value = parseFloat($input.val());

		if (isNaN(value)) {
			value = !isNaN(min) ? min : 0;
		}

		if (type === 'increment') {
			value += step;
		} else {
			value -= step;
		}

		if (!isNaN(min)) {
			value = Math.max(min, value);
		}

		if (!isNaN(max) && max >= 0) {
			value = Math.min(max, value);
		}

		$input.val(value).attr('value', value).trigger('change');
		enableCartUpdateButton();
	}

	function showCouponNotice(html, isError) {
		var $slot = $('.cart-nk-checkout .cart-nk-coupon-notices');
		if (!$slot.length) {
			return;
		}

		if (couponNoticeTimer) {
			window.clearTimeout(couponNoticeTimer);
			couponNoticeTimer = null;
		}

		$slot.stop(true, true).show().html(html).toggleClass('is-error', !!isError).toggleClass('is-success', !isError);

		couponNoticeTimer = window.setTimeout(function () {
			$slot.fadeOut(250, function () {
				$slot.empty().removeClass('is-error is-success').show();
			});
		}, 5000);
	}

	function applyCheckoutCoupon() {
		if (typeof wc_checkout_params === 'undefined') {
			return;
		}

		var code = $.trim($('#kaveh-nk-coupon-input').val() || '');
		var $btn = $('#kaveh-nk-apply-coupon');

		if (!code) {
			showCouponNotice(
				'<div class="woocommerce-error" role="alert">' +
					(window.kavehCartNkStrings && kavehCartNkStrings.couponEmpty
						? kavehCartNkStrings.couponEmpty
						: 'Please enter a coupon code.') +
					'</div>',
				true
			);
			return;
		}

		if ($btn.data('busy')) {
			return;
		}
		$btn.data('busy', true).addClass('is-busy');

		$.ajax({
			type: 'POST',
			url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
			data: {
				security: wc_checkout_params.apply_coupon_nonce,
				coupon_code: code,
				billing_email: $('form.checkout input[name="billing_email"]').val() || '',
			},
			dataType: 'html',
		})
			.done(function (response) {
				var html = response || '';
				var isError =
					html.indexOf('woocommerce-error') !== -1 ||
					html.indexOf('is-error') !== -1 ||
					html.indexOf('coupon-error-notice') !== -1;

				// Keep only notice markup if WC wraps extra bits.
				var $tmp = $('<div/>').html(html);
				var $notice = $tmp
					.find('.woocommerce-error, .woocommerce-message, .woocommerce-info, .is-error, .is-success, .coupon-error-notice')
					.first();
				if ($notice.length) {
					html = $('<div/>').append($notice.clone()).html();
				}

				showCouponNotice(html, isError);

				if (!isError) {
					$('#kaveh-nk-coupon-input').val('');
					$('#kaveh-checkout-nk-coupon input[name="coupon_code"]').val('');
				}

				$(document.body).trigger('update_checkout', { update_shipping_method: false });
				$(document.body).trigger('applied_coupon_in_checkout', [code]);
			})
			.fail(function () {
				showCouponNotice(
					'<div class="woocommerce-error" role="alert">' +
						(window.kavehCartNkStrings && kavehCartNkStrings.couponError
							? kavehCartNkStrings.couponError
							: 'Error applying coupon. Please try again.') +
						'</div>',
					true
				);
			})
			.always(function () {
				$btn.data('busy', false).removeClass('is-busy');
			});
	}

	function toggleMapPanel(forceOpen) {
		var $panel = $('#kaveh-nk-map-panel');
		var $trigger = $('.cart-nk-map-trigger');
		if (!$panel.length) {
			return;
		}

		var open = typeof forceOpen === 'boolean' ? forceOpen : !!$panel.prop('hidden');
		$panel.prop('hidden', !open);
		$trigger.toggleClass('is-open', open).attr('aria-expanded', open ? 'true' : 'false');

		if (open && typeof window.kavehBootOsmMap === 'function') {
			window.kavehBootOsmMap();
		}
	}

	$(function () {
		if (!$('.cart-nk').length) {
			return;
		}

		$(document).on('click.kavehCartNk', '.cart-nk [data-kaveh-qty]', function (event) {
			event.preventDefault();
			event.stopPropagation();

			var $btn = $(this);
			var target = $btn.attr('data-target');
			var $input = target ? $(target) : $();

			if (!$input.length) {
				return;
			}

			adjustQuantity($input, $btn.attr('data-type'));
		});

		$(document).on('change.kavehCartNk input.kavehCartNk', '.cart-nk .qty', enableCartUpdateButton);

		// Coupon — custom AJAX so error/success land in the visible slot.
		$(document).on('click.kavehCartNk', '#kaveh-nk-apply-coupon', function (event) {
			event.preventDefault();
			applyCheckoutCoupon();
		});

		$(document).on('keypress.kavehCartNk', '#kaveh-nk-coupon-input', function (event) {
			if (event.which === 13) {
				event.preventDefault();
				applyCheckoutCoupon();
			}
		});

		$(document).on('click.kavehCartNk', '#ship-to-different-address-toggle', function (event) {
			event.preventDefault();
			var $cb = $('#ship-to-different-address-checkbox');
			if (!$cb.length) {
				return;
			}
			$cb.prop('checked', !$cb.is(':checked')).trigger('change');
			$(this).toggleClass('collapsed', !$cb.is(':checked'));
		});

		$(document).on('keydown.kavehCartNk', '#ship-to-different-address-toggle', function (event) {
			if (event.which === 13 || event.which === 32) {
				event.preventDefault();
				$(this).trigger('click');
			}
		});

		$(document).on('change.kavehCartNk', '#ship-to-different-address-checkbox', function () {
			$('#ship-to-different-address-toggle').toggleClass('collapsed', !$(this).is(':checked'));
		});

		// Map — instant open/close (no Bootstrap collapse delay).
		$(document).on('click.kavehCartNk', '.cart-nk-map-trigger', function (event) {
			event.preventDefault();
			toggleMapPanel();
		});

		// Keep Style 2 required/optional chrome in sync when field editors toggle WC labels.
		function syncCheckoutFieldRequiredState($field) {
			if (!$field || !$field.length) {
				return;
			}
			var required = $field.hasClass('validate-required') || $field.hasClass('thwcfd-required');
			$field
				.toggleClass('cart-nk-field--required is-required', required)
				.toggleClass('cart-nk-field--optional is-optional', !required);
		}

		// Re-apply half/full width after address-i18n swaps form-row-first/last/wide.
		function syncCheckoutFieldColumnWidth($field) {
			if (!$field || !$field.length) {
				return;
			}
			var col = $field.attr('data-kaveh-col');
			if (col !== 'half' && col !== 'full') {
				return;
			}
			$field
				.removeClass('cart-nk-col--half cart-nk-col--full')
				.addClass(col === 'half' ? 'cart-nk-col--half' : 'cart-nk-col--full');
		}

		function syncAllCheckoutFieldChrome() {
			$('.cart-nk-checkout .cart-nk__form .form-row.cart-nk-field').each(function () {
				var $field = $(this);
				syncCheckoutFieldRequiredState($field);
				syncCheckoutFieldColumnWidth($field);
			});
		}

		syncAllCheckoutFieldChrome();

		$(document.body).on('country_to_state_changing updated_checkout', function () {
			window.setTimeout(syncAllCheckoutFieldChrome, 0);
		});
	});
})(jQuery);
