(function () {
  'use strict';

  var config = window.NegarThemeProductSearch;
  if (!config) {
    return;
  }

  function productItem(selector, product) {
    var item = document.createElement('li');
    item.setAttribute('data-product-id', product.id);

    var label = document.createElement('span');
    label.textContent = product.label;
    item.appendChild(label);

    var hidden = document.createElement('input');
    hidden.type = 'hidden';

    hidden.name = selector.getAttribute('data-input-name') || ( config.option + '[products][sections][' + selector.getAttribute('data-section-id') + '][product_ids][]' );
    hidden.value = product.id;
    item.appendChild(hidden);

    var remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'button-link-delete negar-product-selector__remove';
    remove.textContent = 'حذف';
    remove.setAttribute('aria-label', 'حذف ' + product.label + ' از انتخاب');
    item.appendChild(remove);

    return item;
  }

  function clearResults(results) {
    results.textContent = '';
  }

  function addResult(results, selector, product) {
    var result = document.createElement('button');
    result.type = 'button';
    result.className = 'negar-product-selector__result';
    result.setAttribute('role', 'option');
    result.setAttribute('data-product-id', product.id);
    result.textContent = product.label;
    result.addEventListener('click', function () {
      var selected = selector.querySelector('.negar-product-selector__selected');
		if (selector.getAttribute('data-single') === '1') {
			selected.textContent = '';
		}
      if (!selected.querySelector('[data-product-id="' + String(product.id) + '"]')) {
        selected.appendChild(productItem(selector, product));
      }
      clearResults(results);
    });
    results.appendChild(result);
  }

  Array.prototype.forEach.call(document.querySelectorAll('[data-product-selector]'), function (selector) {
    var input = selector.querySelector('input[type="search"]');
    var results = selector.querySelector('.negar-product-selector__results');
    var status = selector.querySelector('[aria-live]');
    var timer;
    var request;

    selector.addEventListener('click', function (event) {
      var remove = event.target.closest('.negar-product-selector__remove');
      if (remove) {
        var selectedItem = remove.closest('[data-product-id]');
        if (selectedItem) {
          selectedItem.remove();
        }
      }
    });

    input.addEventListener('input', function () {
      var query = input.value.trim();
      window.clearTimeout(timer);
      if (request) {
        request.abort();
      }
      clearResults(results);
      status.textContent = '';
      if (!query) {
        return;
      }

      timer = window.setTimeout(function () {
        status.textContent = config.messages.loading;
        request = new window.AbortController();
        var body = new URLSearchParams({
          action: 'negar_theme_search_products',
          nonce: config.nonce,
          query: query
        });

        window.fetch(config.url, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
          body: body.toString(),
          signal: request.signal
        }).then(function (response) {
          if (!response.ok) {
            throw new Error('request_failed');
          }
          return response.json();
        }).then(function (payload) {
          clearResults(results);
          var products = payload && payload.success && payload.data ? payload.data.products : [];
          if (!products || !products.length) {
            status.textContent = config.messages.empty;
            return;
          }
          products.forEach(function (product) {
            addResult(results, selector, product);
          });
          status.textContent = products.length + ' نتیجه پیدا شد.';
        }).catch(function (error) {
          if (error.name !== 'AbortError') {
            status.textContent = config.messages.error;
          }
        });
      }, 250);
    });
  });
}());
