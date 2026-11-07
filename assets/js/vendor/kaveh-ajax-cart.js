/**
 * Sitewide cart AJAX: add-to-cart, fragment apply, slide/header qty + remove.
 * Shop filters stay in kaveh-ajax-shop-method.js (archives only).
 */
jQuery(document).ready(function ($) {
  if (typeof kavehShopData === 'undefined') {
    return;
  }

  var ksiteurl = kavehShopData.ksiteurl || kavehShopData.ajaxurl || (typeof ajaxurl !== 'undefined' ? ajaxurl : '');
  var fallbackUrl = kavehShopData.fallback_url || kavehShopData.ajaxurl || ksiteurl;
  var addToCartUrl = kavehShopData.add_to_cart_url || ksiteurl;
  var cartUpdateUrl = kavehShopData.cart_update_url || ksiteurl;
  var cartRemoveUrl = kavehShopData.cart_remove_url || ksiteurl;
  var ajaxnoinsingle = kavehShopData.ajaxnoinsingle;
  var opencartonaddtocart = kavehShopData.opencartonaddtocart;
  var woocommerce_cart_redirect_after_add = kavehShopData.woocommerce_cart_redirect_after_add;
  var cart_url = kavehShopData.cart_url;
  var fontFamily = kavehShopData.fontFamily || 'IRANSans';

  function notifySuccess(msg) {
    if (window.Notiflix && Notiflix.Notify) {
      Notiflix.Notify.success(msg, {
        timeout: 3000,
        rtl: true,
        position: 'left-bottom',
        fontFamily: fontFamily,
      });
    }
  }

  function notifyFailure(msg) {
    if (window.Notiflix && Notiflix.Notify) {
      Notiflix.Notify.failure(msg, {
        timeout: 3000,
        rtl: true,
        position: 'left-bottom',
        fontFamily: fontFamily,
      });
    }
  }

  function blockDots(selector) {
    if (window.Notiflix && Notiflix.Block) {
      Notiflix.Block.dots(selector);
    }
  }

  function blockRemove(selector) {
    if (window.Notiflix && Notiflix.Block) {
      Notiflix.Block.remove(selector);
    }
  }

  var applyCartFragments = function (response, $button) {
    if (!response || !response.fragments || typeof response.fragments !== 'object') {
      return false;
    }

    jQuery.each(response.fragments, function (key, value) {
      jQuery(key).replaceWith(value);
    });

    if (response.cart_hash) {
      try {
        sessionStorage.setItem('wc_cart_hash', response.cart_hash);
      } catch (err) {
        /* ignore */
      }
    }

    jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button || null]);
    jQuery(document.body).trigger('wc_fragments_refreshed');
    return true;
  };

  var handleAddToCartSuccess = function (response, $button) {
    if (response && response.error && response.product_url) {
      window.location = response.product_url;
      return;
    }

    if (!applyCartFragments(response, $button)) {
      notifyFailure(kavehShopData.invalid_response || 'Invalid response from server.');
      return;
    }

    if ($button && $button.length) {
      $button.removeClass('added loading');
    }

    if (opencartonaddtocart == '1') {
      var cartSliding = document.querySelector('body .cart-sliding');
      if (cartSliding) {
        cartSliding.classList.add('opened');
      }
    }

    notifySuccess(kavehShopData.product_added_to_cart);

    if (woocommerce_cart_redirect_after_add === 'yes') {
      window.location.href = cart_url;
    }
  };

  var buildAddToCartPayload = function ($button) {
    var $form = $button.closest('form.cart');
    var productId =
      $form.find('input[name=product_id]').val() ||
      $form.find('input[name="add-to-cart"]').val() ||
      $button.attr('value') ||
      $button.data('product_id') ||
      $button.data('product-id') ||
      '';
    var payload = {
      action: 'kaveh_ajax_add_to_cart',
      product_id: productId,
      product_sku: '',
      variation_id: $form.find('input[name=variation_id]').val() || 0,
    };

    var isGroupedForm = $form.hasClass('grouped_form') || $form.find('input[name^="quantity["]').length > 0;
    if (isGroupedForm) {
      $form.find('input[name^="quantity["]').each(function () {
        var $field = jQuery(this);
        var name = $field.attr('name');
        if (name) {
          payload[name] = $field.val() || 0;
        }
      });
      $form.find('input[name^="kav_grouped_variations"]').each(function () {
        var $field = jQuery(this);
        var name = $field.attr('name');
        if (name && $field.val() !== '') {
          payload[name] = $field.val();
        }
      });
    } else {
      payload.quantity = $form.find('input[name=quantity]').val() || $button.data('quantity') || 1;
    }

    $form.find('[name^="attribute_"]').each(function () {
      var $field = jQuery(this);
      payload[$field.attr('name')] = $field.val();
    });

    return payload;
  };

  // One add-to-cart flow for every card and widget: the same loading overlay, the
  // "open cart on add to cart" option, the success notice and the redirect setting.
  var kavehAddToCart = function ($button) {
    var payload = buildAddToCartPayload($button);

    if (!payload.product_id) {
      notifyFailure(kavehShopData.invalid_response || 'Invalid response from server.');
      return;
    }

    jQuery(document.body).trigger('adding_to_cart', [$button, payload]);

    jQuery.ajax({
      type: 'post',
      url: addToCartUrl,
      dataType: 'json',
      data: payload,
      beforeSend: function () {
        blockDots('body');
      },
      complete: function () {
        blockRemove('body');
      },
      success: function (response) {
        handleAddToCartSuccess(response, $button);
      },
      error: function () {
        jQuery.ajax({
          type: 'post',
          url: fallbackUrl,
          dataType: 'json',
          data: payload,
          success: function (response) {
            handleAddToCartSuccess(response, $button);
          },
          error: function () {
            notifyFailure(kavehShopData.filter_error || 'Add to cart failed.');
          },
        });
      },
    });
  };

  jQuery(document).on('click', '.single_add_to_cart_button', function (e) {
    if (jQuery(this).is(':disabled') || jQuery(this).hasClass('disabled')) {
      return;
    }

    if (ajaxnoinsingle == '1' && jQuery(this).closest('.insingle_pr').length > 0) {
      return;
    }

    e.preventDefault();
    kavehAddToCart(jQuery(this));
  });

  // Some cards and widgets print WooCommerce's own ".add_to_cart_button.ajax_add_to_cart"
  // (cards twenty-two..twenty-eight, amazingoffercarouselkaveh, kstory). WooCommerce's
  // add-to-cart.js would add those silently - no loading overlay, and the cart never opens.
  // Take them over in the capture phase, before WooCommerce's delegated body handler, so the
  // product is added once, through the theme flow above.
  document.addEventListener(
    'click',
    function (e) {
      var button = e.target && e.target.closest ? e.target.closest('.add_to_cart_button.ajax_add_to_cart') : null;
      if (!button || button.classList.contains('single_add_to_cart_button')) {
        return;
      }
      if (!button.getAttribute('data-product_id') || button.classList.contains('disabled')) {
        return;
      }
      if (ajaxnoinsingle == '1' && button.closest('.insingle_pr')) {
        return;
      }

      e.preventDefault();
      e.stopPropagation();
      kavehAddToCart(jQuery(button));
    },
    true
  );

  var getCartItemKey = function ($el) {
    return (
      $el.closest('.nav-header-cart-dropdown-two-products-item').find('.delete').first().data('cart-item-key') || ''
    );
  };

  var requestCartQuantityUpdate = function (cartItemKey, quantity, $item) {
    if (!cartItemKey || isNaN(quantity) || quantity < 0) {
      return;
    }

    var payload = {
      action: 'kaveh_ajax_update_cart_kk',
      cart_item_key: cartItemKey,
      quantity: quantity,
    };

    var $items = jQuery('.nav-header-cart-dropdown-two-products-item');

    jQuery.ajax({
      url: cartUpdateUrl,
      type: 'POST',
      dataType: 'json',
      data: payload,
      beforeSend: function () {
        if ($item && $item.length) {
          $item.css('opacity', '0.4');
        } else {
          $items.css('opacity', '0.4');
        }
      },
      complete: function () {
        $items.css('opacity', '1');
      },
      success: function (response) {
        if (applyCartFragments(response)) {
          return;
        }
        jQuery.ajax({
          url: fallbackUrl,
          type: 'POST',
          dataType: 'json',
          data: payload,
          success: function (fallbackResponse) {
            applyCartFragments(fallbackResponse);
          },
        });
      },
      error: function () {
        jQuery.ajax({
          url: fallbackUrl,
          type: 'POST',
          dataType: 'json',
          data: payload,
          success: function (fallbackResponse) {
            applyCartFragments(fallbackResponse);
          },
        });
      },
    });
  };

  jQuery(document).on(
    'click',
    '.nav-header-cart-dropdown-two .changeinp, .cart-sliding-content .changeinp',
    function () {
      var $btn = jQuery(this);
      var cartItemKey = getCartItemKey($btn);
      var $item = $btn.closest('.nav-header-cart-dropdown-two-products-item');

      setTimeout(function () {
        var $input = $btn.closest('.counter').find('input[type=number]');
        if (!$input.length) {
          $input = $btn.siblings('.quantity').find('input[type=number]');
        }
        var newQuantity = parseInt($input.val(), 10);
        if (!cartItemKey || isNaN(newQuantity)) {
          return;
        }
        requestCartQuantityUpdate(cartItemKey, newQuantity, $item);
      }, 0);
    }
  );

  var qtyInputTimer;
  jQuery(document).on(
    'input',
    '.nav-header-cart-dropdown-two-products-item .quantity input[type=number], .cart-sliding-content .quantity input[type=number]',
    function () {
      var $input = jQuery(this);
      var cartItemKey = getCartItemKey($input);
      var $item = $input.closest('.nav-header-cart-dropdown-two-products-item');
      clearTimeout(qtyInputTimer);
      qtyInputTimer = setTimeout(function () {
        var newQuantity = parseInt($input.val(), 10);
        if (!cartItemKey || isNaN(newQuantity)) {
          return;
        }
        requestCartQuantityUpdate(cartItemKey, newQuantity, $item);
      }, 400);
    }
  );

  jQuery(document).on('click', '.nav-header-cart-dropdown-two .delete, .nav-header-cart-dropdown-two-products .delete', function (e) {
    e.preventDefault();
    var $this = jQuery(this);
    var cartItemKey = $this.data('cart-item-key');

    if (!cartItemKey) {
      cartItemKey = $this.closest('.nav-header-cart-dropdown-two-products-item').find('.delete').data('cart-item-key');
    }

    jQuery.ajax({
      url: cartRemoveUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'kaveh_ajax_update_cart_kk_remove',
        cart_item_key: cartItemKey,
        quantity: 0,
      },
      beforeSend: function () {
        $this.closest('.nav-header-cart-dropdown-two-products-item').css('opacity', '0.4');
        blockDots('.nav-header-cart-dropdown-two');
      },
      complete: function () {
        jQuery('.nav-header-cart-dropdown-two-products-item').css('opacity', '1');
        blockRemove('.nav-header-cart-dropdown-two');
      },
      success: function (response) {
        if (applyCartFragments(response)) {
          notifySuccess(
            kavehShopData.product_removed_from_cart
              ? kavehShopData.product_removed_from_cart
              : 'Product removed from cart'
          );
        }
      },
      error: function () {
        jQuery.ajax({
          url: fallbackUrl,
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'kaveh_ajax_update_cart_kk_remove',
            cart_item_key: cartItemKey,
            quantity: 0,
          },
          success: function (response) {
            if (applyCartFragments(response)) {
              notifySuccess(
                kavehShopData.product_removed_from_cart
                  ? kavehShopData.product_removed_from_cart
                  : 'Product removed from cart'
              );
            }
          },
          error: function () {
            notifyFailure(
              kavehShopData.product_remove_error
                ? kavehShopData.product_remove_error
                : 'Error removing from cart'
            );
          },
        });
      },
    });
  });
});
