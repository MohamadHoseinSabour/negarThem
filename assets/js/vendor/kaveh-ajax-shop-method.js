jQuery(document).ready(function ($) {
  // Ensure kavehShopData exists
  if (typeof kavehShopData === 'undefined') {
    console.error('kavehShopData is not defined');
    return;
  }
  
  // Use optimized Rewrite API URL with fallback
  var ksiteurl = kavehShopData.ksiteurl || kavehShopData.ajaxurl || ajaxurl;
  var fallbackUrl = kavehShopData.fallback_url || kavehShopData.ksiteurl;
  var originalHighestPrice = parseFloat(kavehShopData.highestPrice) || 0;
  var originalLowestPrice = parseFloat(kavehShopData.lowestPrice) || 0;
  
  // Fix swapped values if needed
  var highestPrice = Math.max(originalHighestPrice, originalLowestPrice);
  var lowestPrice = Math.min(originalHighestPrice, originalLowestPrice);
  var isIranianCurrency = kavehShopData.currency_code === 'IRT' || kavehShopData.currency_code === 'IRR';

  if (isIranianCurrency) {
    lowestPrice = Math.floor(lowestPrice);
    highestPrice = Math.ceil(highestPrice);
  }

  // Snapshot of the original WooCommerce loop so clearing filters restores exact initial layout.
  var $initialProductGrid = null;
  var $initialPagination = null;
  var $initialResultCount = null;
  var shouldScrollToShop = false;
  var initialSortId = jQuery('.category-sort ul li input[name="orderby"]:checked').val() || '';

  // Unify initial WC pagination with AJAX markup: wrap bare nav in .pg1 so the
  // same click handler (and visual structure) is used before and after filters.
  function ensureShopPaginationWrapper() {
    var $nav = jQuery('.woocommerce-pagination').first();
    if ($nav.length && !$nav.closest('.pg1').length) {
      $nav.wrap('<div class="pg1"></div>');
    }
  }

  ensureShopPaginationWrapper();

  function getActiveSortValue() {
    var $checked = jQuery('.category-sort ul li input[name="orderby"]:checked');
    return $checked.length ? ($checked.val() || '') : '';
  }

  function setActiveSortValue(orderby) {
    if (!orderby) {
      return;
    }
    var $inputs = jQuery('.category-sort ul li input[name="orderby"]');
    $inputs.prop('checked', false).removeAttr('checked');
    var $target = $inputs.filter('[value="' + orderby + '"]');
    if ($target.length) {
      $target.prop('checked', true).attr('checked', 'checked');
    }
  }

  function syncNkSortDropdown(orderby) {
    var $sort = jQuery('.category-nk__sort');
    if (!$sort.length || !orderby) {
      return;
    }
    var $item = $sort.find('.dropdown-item[data-orderby="' + orderby + '"]');
    if (!$item.length) {
      return;
    }
    $sort.find('.dropdown-item').removeClass('active');
    $item.addClass('active');
    $sort.find('.dropdown-toggle').text(jQuery.trim($item.text()));
  }

  if (jQuery('#woor').length) {
    $initialProductGrid = jQuery('#woor').first().clone(true, true);
  }
  if (jQuery('.pg1').length) {
    $initialPagination = jQuery('.pg1').first().clone(true, true);
  } else if (jQuery('.woocommerce-pagination').length) {
    $initialPagination = jQuery('.woocommerce-pagination').first().clone(true, true);
  }
  if (jQuery('.woocommerce-result-count').length) {
    $initialResultCount = jQuery('.woocommerce-result-count').first().clone(true, true);
  }

  var filterDebounceTimer = null;
  var activeFilterRequest = null;
  var lastFilterPayload = null;
  var FILTER_DEBOUNCE_MS = 320;
  
// Category Price
  const categoryPrice = document.getElementById("category-price");

  function parsePriceInput(value) {
    if (value === undefined || value === null) {
      return NaN;
    }
    var persianMap = {
      '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
      '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9',
      '٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4',
      '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9'
    };
    var normalized = String(value).replace(/[۰-۹٠-٩]/g, function (d) {
      return persianMap[d] || d;
    });
    // Keep digits / decimal point only (drop thousand separators)
    var cleaned = normalized.replace(/[^\d.-]/g, '');
    return cleaned === '' ? NaN : Number(cleaned);
  }

  function formatPriceValue(value) {
    var num = Number(value);
    if (isIranianCurrency) {
      num = Math.round(num);
    }
    return new Intl.NumberFormat("en").format(num);
  }

  function getSelectedPriceRange() {
    if (categoryPrice && categoryPrice.noUiSlider) {
      var values = categoryPrice.noUiSlider.get();
      return {
        min: Number(values[0]),
        max: Number(values[1])
      };
    }

    var minInput = document.getElementById("category-price-min");
    var maxInput = document.getElementById("category-price-max");
    return {
      min: minInput ? parsePriceInput(minInput.value) || 0 : 0,
      max: maxInput ? parsePriceInput(maxInput.value) || 999999999 : 999999999
    };
  }

  function isPriceFilterActive(priceMin, priceMax) {
    // Only treat as a real price filter when the user narrowed the shop slider range.
    // Otherwise every brand/category filter wrongly applies BETWEEN and drops products
    // that have no _price meta or sit on the edge of the catalog range.
    if (!categoryPrice || !categoryPrice.noUiSlider) {
      return false;
    }
    if (isNaN(priceMin) || isNaN(priceMax) || isNaN(lowestPrice) || isNaN(highestPrice)) {
      return false;
    }
    var tolerance = isIranianCurrency ? 1 : 0.01;
    return (Math.abs(priceMin - lowestPrice) > tolerance) || (Math.abs(priceMax - highestPrice) > tolerance);
  }

  if (categoryPrice && typeof noUiSlider !== 'undefined' && lowestPrice !== highestPrice && lowestPrice < highestPrice && !isNaN(lowestPrice) && !isNaN(highestPrice)) {
    try {
      var priceGap = highestPrice - lowestPrice;
      var sliderMargin = Math.floor(Math.min(1000, priceGap / 10));
      if (sliderMargin < 1) {
        sliderMargin = 1;
      }
      // noUiSlider requires margin to be a multiple of step
      if (sliderMargin >= priceGap) {
        sliderMargin = 1;
      }

      var sliderConfig = {
        start: [lowestPrice, highestPrice],
        margin: sliderMargin,
        behaviour: "tap-drag",
        connect: true,
        range: {
          min: lowestPrice,
          max: highestPrice,
        },
      };

      // NK layout in RTL: min on the right, max on the left (matches “از / تا” UX).
      var isNkPriceLayout = categoryPrice.classList.contains("range-price-nk");
      var pageIsRtl =
        document.documentElement.getAttribute("dir") === "rtl" ||
        (document.body && document.body.classList.contains("rtl"));
      if (isNkPriceLayout && pageIsRtl) {
        sliderConfig.direction = "rtl";
      }
      
      // Add step configuration for Iranian currencies to prevent decimals
      if (isIranianCurrency) {
        sliderConfig.step = 1;
      }
      
      noUiSlider.create(categoryPrice, sliderConfig);

      var limitFieldMin = document.getElementById("category-price-min");
      var limitFieldMax = document.getElementById("category-price-max");
      var textFieldMin = document.getElementById("range-price-text-min");
      var textFieldMax = document.getElementById("range-price-text-max");
      var priceFilterTimeout;

      function schedulePriceFilter() {
        if (priceFilterTimeout) {
          clearTimeout(priceFilterTimeout);
        }
        priceFilterTimeout = setTimeout(function() {
          filterProducts();
        }, 300);
      }

      function syncPriceDisplays(values, handle) {
        var formatted = formatPriceValue(values[handle]);
        if (handle === 0) {
          if (limitFieldMin) {
            limitFieldMin.value = formatted;
          }
          if (textFieldMin) {
            textFieldMin.textContent = formatted;
          }
        } else {
          if (limitFieldMax) {
            limitFieldMax.value = formatted;
          }
          if (textFieldMax) {
            textFieldMax.textContent = formatted;
          }
        }
      }

      categoryPrice.noUiSlider.on("update", function (values, handle) {
        syncPriceDisplays(values, handle);
      });

      // Filter when slider handle is released
      categoryPrice.noUiSlider.on("change", function () {
        schedulePriceFilter();
      });

      // Keep slider + filter in sync when user types From / To values
      function syncPriceInputsToSlider() {
        var minVal = parsePriceInput(limitFieldMin.value);
        var maxVal = parsePriceInput(limitFieldMax.value);

        if (isNaN(minVal) || isNaN(maxVal)) {
          return;
        }

        if (minVal < lowestPrice) minVal = lowestPrice;
        if (maxVal > highestPrice) maxVal = highestPrice;
        if (minVal > maxVal) {
          var swap = minVal;
          minVal = maxVal;
          maxVal = swap;
        }

        categoryPrice.noUiSlider.set([minVal, maxVal]);
        schedulePriceFilter();
      }

      function bindPriceInput(field) {
        if (!field) {
          return;
        }
        field.addEventListener("change", syncPriceInputsToSlider);
        field.addEventListener("keydown", function (ev) {
          if (ev.key === "Enter") {
            ev.preventDefault();
            syncPriceInputsToSlider();
          }
        });
      }

      bindPriceInput(limitFieldMin);
      bindPriceInput(limitFieldMax);
    } catch (error) {
      console.error('Error initializing price slider:', error);
    }
  }

  // Open / close nested category & brand filter items (delegated).
  jQuery(document).on('click', '.category-sidebar-box-items li.has-child > span', function (ev) {
    ev.preventDefault();
    ev.stopPropagation();

    var $toggle = jQuery(this);
    var $parent = $toggle.parent('li');
    var $content = $toggle.next('ul');
    if (!$content.length) {
      return;
    }

    $parent.toggleClass('opened');

    if ($parent.hasClass('opened')) {
      $content.css('height', $content.get(0).scrollHeight + 'px');
      window.setTimeout(function () {
        $content.css('height', 'auto');
      }, 300);
    } else {
      $content.css('height', $content.get(0).scrollHeight + 'px');
      window.setTimeout(function () {
        $content.css('height', 0);
      }, 10);
    }
  });
  // Sidebar Category
  const btnOpenSidebarCategory = document.querySelector(".category-btn-filter"),
    sidebarCategory = document.querySelector(".category-sidebar"),
    backdropSidebarCategory = document.querySelector(
      ".category-sidebar-backdrop"
    );

  if (btnOpenSidebarCategory) {
    btnOpenSidebarCategory.addEventListener("click", () => {
      sidebarCategory.classList.add("open");
    });

    backdropSidebarCategory.addEventListener("click", () => {
      sidebarCategory.classList.remove("open");
    });
  }


  // Attribute / category / brand filters (delegated — survives AJAX re-renders).
  // Attribute filters (delegated). Handle the whole <li> so NK labels work
  // even when the checkbox is visually hidden (HTML `hidden` / CSS).
  jQuery(document).on('click', 'ul.attrhidden li', function (e) {
    if (jQuery(e.target).is('.attrhiddeninput') || jQuery(e.target).closest('.attrhiddeninput').length) {
      return;
    }

    var $li = jQuery(this);
    var $input = $li.find('input[type="checkbox"]').first();
    if (!$input.length || !$input.attr('myid')) {
      return;
    }

    e.preventDefault();
    e.stopPropagation();

    $input.toggleClass('activeFilter');
    var isOn = $input.hasClass('activeFilter');
    $input.prop('checked', isOn);
    if (isOn) {
      $input.attr('checked', 'checked');
    } else {
      $input.removeAttr('checked');
    }

    var attrHiddenInput = $li.closest('ul.attrhidden').find('.attrhiddeninput').first();
    editFilterInputs(attrHiddenInput, $input.attr('myid'));
    filterProducts();
  });

  jQuery(document).on('click', 'ul.cathidden li input', function (e) {
    e.preventDefault();
    var $input = jQuery(this);
    $input.toggleClass('activeFilter');
    var isOn = $input.hasClass('activeFilter');
    $input.prop('checked', isOn);
    if (isOn) {
      $input.attr('checked', 'checked');
    } else {
      $input.removeAttr('checked');
    }
    editFilterInputs(jQuery('.filterhiddeninput'), $input.attr('myid'));
    filterProducts();
  });

  jQuery(document).on('click', 'ul.brandhidden li input', function (e) {
    e.preventDefault();
    var $input = jQuery(this);
    $input.toggleClass('activeFilter');
    var isOn = $input.hasClass('activeFilter');
    $input.prop('checked', isOn);
    if (isOn) {
      $input.attr('checked', 'checked');
    } else {
      $input.removeAttr('checked');
    }
    editFilterInputs(jQuery('.filterhiddeninputbrand'), $input.attr('myid'));
    filterProducts();
  });


  function editFilterInputs(inputField, value) {
    if (!inputField || !inputField.length || !value) {
      return;
    }
    
    const currentValue = inputField.val() || '';
    const currentFilters = currentValue.split(',').filter(function(item) { return item.trim() !== ''; });
    const newFilter = value.toString().trim();

    if (currentFilters.includes(newFilter)) {
      const j = currentFilters.indexOf(newFilter);
      currentFilters.splice(j, 1);
      inputField.val(currentFilters.join(','));
    } else {
      if (currentFilters.length === 0) {
        inputField.val(newFilter);
      } else {
        inputField.val(currentFilters.join(',') + ',' + newFilter);
      }
    }
  }


  /**
   * Theme shop filters are AJAX-only (not mirrored in the URL). Browsers restore
   * checkbox UI via bfcache / form restore after product → back, which looks
   * “active” even though WooCommerce would show a clean shop. Clear that state.
   */
  function resetEphemeralAvailabilityFilters() {
    var $toggles = jQuery('#onlysale, #exists-products');
    if (!$toggles.length) {
      return false;
    }
    var wasActive = $toggles.is(':checked');
    $toggles.each(function () {
      this.checked = false;
      this.removeAttribute('checked');
    });
    return wasActive;
  }

  function resetPriceFilterToDefaults() {
    if (categoryPrice && categoryPrice.noUiSlider) {
      try {
        categoryPrice.noUiSlider.set([lowestPrice, highestPrice], false);
      } catch (e) {
        // Ignore slider reset errors.
      }
    }
    var minInput = document.getElementById('category-price-min');
    var maxInput = document.getElementById('category-price-max');
    if (minInput) {
      minInput.value = formatPriceValue(lowestPrice);
    }
    if (maxInput) {
      maxInput.value = formatPriceValue(highestPrice);
    }
  }

  function clearAllEphemeralShopFilters() {
    jQuery(
      'ul.attrhidden li input[type="checkbox"],' +
      'ul.cathidden li input[type="checkbox"],' +
      'ul.brandhidden li input[type="checkbox"]'
    )
      .removeClass('activeFilter')
      .prop('checked', false)
      .removeAttr('checked');
    jQuery('.attrhiddeninput, .filterhiddeninput, .filterhiddeninputbrand').val('');
    resetEphemeralAvailabilityFilters();
    resetPriceFilterToDefaults();
  }

  function isBackForwardNavigation(event) {
    if (event && event.persisted) {
      return true;
    }
    try {
      var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
      if (nav && nav.type === 'back_forward') {
        return true;
      }
    } catch (e) {
      // Ignore Performance API issues.
    }
    if (typeof performance !== 'undefined' && performance.navigation && performance.navigation.type === 2) {
      return true;
    }
    return false;
  }

  function syncShopUiAfterHistoryReturn() {
    clearAllEphemeralShopFilters();
    restoreInitialShop();
    setActiveSortValue(initialSortId);
    syncNkSortDropdown(initialSortId);
    lastFilterPayload = null;
  }

  jQuery(
    'ul.attrhidden input, ul.cathidden input, ul.brandhidden input, #onlysale, #exists-products'
  ).attr('autocomplete', 'off');

  clearAllEphemeralShopFilters();

  jQuery(document).on('change', '#onlysale, #exists-products', function () {
    filterProducts();
  });

  window.addEventListener('pageshow', function (event) {
    if (isBackForwardNavigation(event)) {
      syncShopUiAfterHistoryReturn();
      return;
    }
    // Form field restore can re-check filter inputs after DOMContentLoaded.
    clearAllEphemeralShopFilters();
  });


  jQuery(document).on('click', '.pg1 a.page-numbers', function (a) {
    a.preventDefault();
    var $link = jQuery(this);
    var page = $link.data('page');

    if (!page) {
      var href = $link.attr('href') || '';
      var match = href.match(/[?&]page=(\d+)/) || href.match(/\/page\/(\d+)/) || href.match(/[?&]paged=(\d+)/);
      if (match && match[1]) {
        page = match[1];
      } else {
        page = $link.text();
      }
    }

    page = parseInt(String(page).replace(/[^\d]/g, ''), 10);

    // Prev/next arrows ("←"/"→") have no digits — derive from current page.
    if (!page || page < 1) {
      var $root = $link.closest('.pg1, .woocommerce-pagination');
      var currentPage = parseInt(
        String($root.find('.page-numbers.current').first().text()).replace(/[^\d]/g, ''),
        10
      ) || 1;

      if ($link.hasClass('prev')) {
        page = currentPage - 1;
      } else if ($link.hasClass('next')) {
        page = currentPage + 1;
      }
    }

    if (!page || page < 1) {
      return false;
    }

    var filterData = getFilterData();
    filterData.page = page;
    shouldScrollToShop = true;

    executeFilterRequest(filterData);
    return false;
  });
  
  // Helper function to get current filter data
  function getArchiveContext() {
    if (typeof kavehShopData === 'object' && kavehShopData.archiveContext) {
      return kavehShopData.archiveContext;
    }
    return {
      type: 'shop',
      taxonomy: '',
      termId: 0,
      search: '',
      baseUrl: ''
    };
  }

  function getArchiveContextPayload() {
    var ctx = getArchiveContext();
    return {
      archiveType: ctx.type || 'shop',
      archiveTaxonomy: ctx.taxonomy || '',
      archiveTermId: parseInt(ctx.termId, 10) || 0,
      archiveBaseUrl: ctx.baseUrl || ''
    };
  }

  function applyArchiveScopeToFilterIds(catIds, brandIds, shopSearchTerm) {
    var ctx = getArchiveContext();
    var archivePayload = getArchiveContextPayload();

    if (!catIds.length && ctx.type === 'category' && ctx.termId) {
      catIds = [String(ctx.termId)];
    } else if (!catIds.length && jQuery('.name-category').length && jQuery('.name-category').attr('catid')) {
      var legacyCatId = jQuery('.name-category').attr('catid');
      if (legacyCatId) {
        catIds = [legacyCatId];
      }
    }

    if (!brandIds.length && ctx.type === 'brand' && ctx.termId) {
      brandIds = [String(ctx.termId)];
    } else if (!brandIds.length && jQuery('.name-brands').length && jQuery('.name-brands').attr('catid')) {
      var legacyBrandId = jQuery('.name-brands').attr('catid');
      if (legacyBrandId) {
        brandIds = [legacyBrandId];
      }
    }

    if (!shopSearchTerm) {
      if (ctx.type === 'search' && ctx.search) {
        shopSearchTerm = ctx.search;
      } else {
        shopSearchTerm = getShopSearchTerm();
      }
    }

    return {
      catIds: catIds,
      brandIds: brandIds,
      shopSearchTerm: shopSearchTerm,
      archivePayload: archivePayload
    };
  }

  function getShopSearchTerm() {
    try {
      var params = new URLSearchParams(window.location.search);
      return (params.get('s') || '').trim();
    } catch (e) {
      var match = window.location.search.match(/[?&]s=([^&]+)/);
      return match && match[1] ? decodeURIComponent(match[1].replace(/\+/g, ' ')).trim() : '';
    }
  }

  function getFilterData() {
    var attrHiddenInputs = document.querySelectorAll('.attrhiddeninput');
    var count = attrHiddenInputs ? attrHiddenInputs.length : 0;
    var taxIds = [];
    var attrId = [];
    var catIds = [];
    var brandIds = [];
    var shopSearchTerm = getShopSearchTerm();

    // Get brand IDs — prefer explicit checkbox filters; else current brand archive.
    if (jQuery('.filterhiddeninputbrand').length && jQuery('.filterhiddeninputbrand').val()) {
      brandIds = jQuery('.filterhiddeninputbrand').val().split(',').filter(function(item) { return item.trim() !== ''; });
    }

    // Get category IDs — prefer explicit checkbox filters; else current category archive.
    if (jQuery('.filterhiddeninput').length && jQuery('.filterhiddeninput').val()) {
      catIds = jQuery('.filterhiddeninput').val().split(',').filter(function(item) { return item.trim() !== ''; });
    }

    var scoped = applyArchiveScopeToFilterIds(catIds, brandIds, shopSearchTerm);
    catIds = scoped.catIds;
    brandIds = scoped.brandIds;
    shopSearchTerm = scoped.shopSearchTerm;
    var archivePayload = scoped.archivePayload;

    // Get attribute data (WooCommerce pa_* taxonomies + query type per widget).
    var attrQueryTypes = [];
    for (let i = 0; i < count; i++) {
      var attrInput = jQuery(attrHiddenInputs[i]);
      var taxid = (attrInput.attr('taxid') || '').trim();
      if (!taxid) {
        taxIds.push([]);
        attrId.push('');
        attrQueryTypes.push('or');
        continue;
      }
      var attrVal = attrInput.val();
      taxIds.push(attrVal ? attrVal.split(',').map(function(item) { return item.trim(); }).filter(Boolean) : []);
      attrId.push(taxid);
      attrQueryTypes.push(attrInput.attr('data-query-type') === 'and' ? 'and' : 'or');
    }

    // Get price range from the category price slider / inputs
    var selectedPrice = getSelectedPriceRange();
    var priceMin = !isNaN(selectedPrice.min) ? selectedPrice.min : 0;
    var priceMax = !isNaN(selectedPrice.max) ? selectedPrice.max : 999999999;
    var priceFiltered = isPriceFilterActive(priceMin, priceMax);

    return Object.assign({
      action: 'kaveh_shop_filters',
      search: shopSearchTerm,
      onsalee: jQuery('#onlysale').length ? jQuery('#onlysale').is(':checked') : false,
      onstock: jQuery('#exists-products').length ? jQuery('#exists-products').is(':checked') : false,
      pricemin: priceFiltered ? priceMin : 0,
      pricemax: priceFiltered ? priceMax : 999999999,
      price_filtered: priceFiltered ? '1' : '0',
      count: count,
      attrId: JSON.stringify(attrId),
      taxIds: JSON.stringify(taxIds),
      attrQueryTypes: attrQueryTypes,
      catIds: catIds,
      brandIds: brandIds,
      sortt: getActiveSortValue(),
      nonce: kavehShopData.shop_filter_nonce,
      page: 1
    }, archivePayload);
  }
  
  function scrollShopIntoView() {
    var $anchor = jQuery('#woor').first();
    if (!$anchor.length || !$anchor.offset()) {
      return;
    }
    var top = Math.max(0, $anchor.offset().top - 120);
    jQuery('html, body').stop(true).animate({ scrollTop: top }, 280);
  }

  // Replace product grid correctly (response already includes #woor)
  function applyFilterHtml(html) {
    var $target = jQuery('#woor').first();
    if (!$target.length || !html) {
      return;
    }

    var $wrap = jQuery('<div>').append(html);
    var $newWoor = $wrap.find('#woor').first();
    var $pagination = $wrap.find('.pg1').first();
    if (!$pagination.length) {
      var $nav = $wrap.find('.woocommerce-pagination').first();
      if ($nav.length) {
        $pagination = jQuery('<div class="pg1"></div>').append($nav);
      }
    }
    var $newCount = $wrap.find('.woocommerce-result-count').first();

    jQuery('.pg1, .woocommerce-pagination').remove();

    if ($newCount.length) {
      var $existingCount = jQuery('.woocommerce-result-count').first();
      if ($existingCount.length) {
        $existingCount.replaceWith($newCount);
      } else if ($newWoor.length) {
        $target.before($newCount);
      }
    } else {
      jQuery('.woocommerce-result-count').remove();
    }

    if ($newWoor.length) {
      $target.replaceWith($newWoor);
      if ($pagination.length) {
        jQuery('#woor').first().after($pagination);
      }
    } else {
      $target.html(html);
    }

    ensureShopPaginationWrapper();

    // Countdown badges ([data-time]) only bind on first page load in main.js.
    // Re-init after AJAX replaces the product grid (page 2+, filters, etc.).
    if (typeof window.kavehInitDataTimeTimers === 'function') {
      var woorEl = document.getElementById('woor');
      window.kavehInitDataTimeTimers(woorEl || document);
    }
  }

  function hasUserActiveFilters(data) {
    var hasCat = !!(jQuery('.filterhiddeninput').length && jQuery('.filterhiddeninput').val());
    var hasBrand = !!(jQuery('.filterhiddeninputbrand').length && jQuery('.filterhiddeninputbrand').val());
    var hasAttr = false;
    jQuery('.attrhiddeninput').each(function () {
      if (jQuery(this).val()) {
        hasAttr = true;
        return false;
      }
    });
    var hasSale = jQuery('#onlysale').length ? jQuery('#onlysale').is(':checked') : false;
    var hasStock = jQuery('#exists-products').length ? jQuery('#exists-products').is(':checked') : false;
    var hasPrice = data && String(data.price_filtered) === '1';
    return hasCat || hasBrand || hasAttr || hasSale || hasStock || hasPrice;
  }

  function restoreInitialShop() {
    if (!$initialProductGrid || !$initialProductGrid.length) {
      window.location.reload();
      return;
    }

    var $target = jQuery('#woor').first();
    if (!$target.length) {
      window.location.reload();
      return;
    }

    jQuery('.pg1, .woocommerce-pagination').remove();
    $target.replaceWith($initialProductGrid.clone(true, true));

    if ($initialResultCount && $initialResultCount.length) {
      var $count = jQuery('.woocommerce-result-count').first();
      if ($count.length) {
        $count.replaceWith($initialResultCount.clone(true, true));
      } else {
        jQuery('#woor').first().before($initialResultCount.clone(true, true));
      }
    }

    if ($initialPagination && $initialPagination.length) {
      jQuery('#woor').first().after($initialPagination.clone(true, true));
    }

    ensureShopPaginationWrapper();

    if (typeof window.kavehInitDataTimeTimers === 'function') {
      var woorEl = document.getElementById('woor');
      if (woorEl) {
        jQuery(woorEl).find('[data-time]').removeAttr('data-timer-bound');
      }
      window.kavehInitDataTimeTimers(woorEl || document);
    }

    jQuery('ul.attrhidden li input, ul.cathidden li input, ul.brandhidden li input').removeClass('activeFilter').prop('checked', false).removeAttr('checked');
    jQuery('.attrhiddeninput, .filterhiddeninput, .filterhiddeninputbrand').val('');
    resetEphemeralAvailabilityFilters();
    resetPriceFilterToDefaults();
  }

  // Helper function to execute filter request with fallback
  function setShopGridLoading(isLoading) {
    var $woor = jQuery('#woor').first();
    if (!$woor.length) {
      return;
    }
    $woor.toggleClass('kaveh-shop-filter-loading', !!isLoading);
  }

  function executeFilterRequest(data) {
    var payloadKey = JSON.stringify(data || {});
    if (lastFilterPayload === payloadKey && activeFilterRequest && activeFilterRequest.readyState !== 4) {
      return;
    }
    lastFilterPayload = payloadKey;

    if (activeFilterRequest && activeFilterRequest.readyState !== 4) {
      try {
        activeFilterRequest.abort();
      } catch (abortError) {
        // Ignore abort errors on completed requests.
      }
    }

    activeFilterRequest = jQuery.ajax({
      url: ksiteurl,
      type: 'post',
      data: data,
      beforeSend: function () {
        setShopGridLoading(true);
      },
      success: function (response) {
        try {
          if (response && response.success && response.data && response.data.html) {
            applyFilterHtml(response.data.html);
            if (shouldScrollToShop) {
              shouldScrollToShop = false;
              scrollShopIntoView();
            }
          } else if (typeof response === 'string' && response.trim() !== '') {
            // Fallback for old response format
            applyFilterHtml(response);
            if (shouldScrollToShop) {
              shouldScrollToShop = false;
              scrollShopIntoView();
            }
          } else {
            shouldScrollToShop = false;
            console.error('Invalid response format:', response);
            var errorMsg = 'Invalid response from server.';
            if (kavehShopData && kavehShopData.invalid_response) {
              errorMsg = kavehShopData.invalid_response;
            }
            if (window.Notiflix && Notiflix.Notify) {
              Notiflix.Notify.failure(errorMsg);
            }
          }
        } catch (error) {
          shouldScrollToShop = false;
          console.error('Error processing response:', error);
          if (window.Notiflix && Notiflix.Notify) {
            Notiflix.Notify.failure('Error processing server response.');
          }
        }
      },
      error: function(xhr, status, error) {
        if (status === 'abort') {
          return;
        }
        console.log('Trying fallback URL for filters...');
        // Fallback to admin-ajax
        activeFilterRequest = jQuery.ajax({
          url: fallbackUrl,
          type: 'post',
          data: data,
          beforeSend: function () {
            setShopGridLoading(true);
          },
          success: function (response) {
            try {
              if (response && response.success && response.data && response.data.html) {
                applyFilterHtml(response.data.html);
                if (shouldScrollToShop) {
                  shouldScrollToShop = false;
                  scrollShopIntoView();
                }
              } else if (typeof response === 'string' && response.trim() !== '') {
                applyFilterHtml(response);
                if (shouldScrollToShop) {
                  shouldScrollToShop = false;
                  scrollShopIntoView();
                }
              } else {
                shouldScrollToShop = false;
                var errorMsg = kavehShopData && kavehShopData.invalid_response ? kavehShopData.invalid_response : 'Invalid response from server.';
                if (window.Notiflix && Notiflix.Notify) {
                  Notiflix.Notify.failure(errorMsg);
                }
              }
            } catch (error) {
              shouldScrollToShop = false;
              if (window.Notiflix && Notiflix.Notify) {
                Notiflix.Notify.failure('Error processing server response.');
              }
            }
          },
          error: function(xhr2, status2, error2) {
            if (status2 === 'abort') {
              return;
            }
            shouldScrollToShop = false;
            console.error('Ajax error:', {xhr: xhr2, status: status2, error: error2});

            var errorMessage = 'An error occurred while filtering products.';

            if (xhr2.responseJSON && xhr2.responseJSON.data && xhr2.responseJSON.data.message) {
              errorMessage = xhr2.responseJSON.data.message;
            } else if (kavehShopData && kavehShopData.filter_error) {
              errorMessage = kavehShopData.filter_error;
            } else if (xhr2.status === 0) {
              errorMessage = 'Network error. Please check your connection.';
            } else if (xhr2.status === 404) {
              errorMessage = 'Filter endpoint not found.';
            } else if (xhr2.status === 500) {
              errorMessage = 'Server error. Please try again later.';
            }

            if (window.Notiflix && Notiflix.Notify) {
              Notiflix.Notify.failure(errorMessage);
            }
          },
          complete: function () {
            setShopGridLoading(false);
            activeFilterRequest = null;
          }
        });
      },
      complete: function (xhr, status) {
        if (status !== 'abort') {
          setShopGridLoading(false);
          activeFilterRequest = null;
        }
      },
    });
  }

  function filterProducts(immediate) {
    if (filterDebounceTimer) {
      clearTimeout(filterDebounceTimer);
      filterDebounceTimer = null;
    }

    var runFilter = function () {
      var filterData = getFilterData();
      var currentSort = getActiveSortValue();

      // No sidebar filters + original sort = restore the real WooCommerce initial loop.
      if (!hasUserActiveFilters(filterData) && currentSort === initialSortId) {
        restoreInitialShop();
        return false;
      }

      executeFilterRequest(filterData);
      return false;
    };

    if (immediate) {
      return runFilter();
    }

    filterDebounceTimer = setTimeout(runFilter, FILTER_DEBOUNCE_MS);
    return false;
  }

  jQuery(document).on('change', '.category-sort ul li input[name="orderby"]', function () {
    syncNkSortDropdown(getActiveSortValue());
    filterProducts(true);
  });

  jQuery(document).on('click', '.category-nk__sort .dropdown-item[data-orderby]', function (e) {
    e.preventDefault();
    var orderby = jQuery(this).data('orderby');
    setActiveSortValue(orderby);
    syncNkSortDropdown(orderby);
    filterProducts(true);
  });

  /**
   * Style 2 filter box list search (categories / brands / attributes).
   */
  function nkNormalizeFilterSearch(text) {
    return String(text || '')
      .replace(/\u200c/g, '')
      .trim()
      .toLowerCase()
      .replace(/\s+/g, ' ');
  }

  function nkGetFilterItemLabel($li) {
    var $label = $li.children('label').first();
    if (!$label.length) {
      $label = $li.children('.heading_category').children('label').first();
    }
    return nkNormalizeFilterSearch($label.text());
  }

  function nkExpandFilterParent($li) {
    var $collapse = $li.children('.collapse').first();
    if ($collapse.length) {
      $collapse.addClass('show');
    }
    $li.children('.heading_category').find('[data-bs-toggle="collapse"]').removeClass('collapsed').attr('aria-expanded', 'true');
  }

  function nkFilterTreeList($list, query) {
    var anyVisible = false;
    if (!$list || !$list.length) {
      return false;
    }

    $list.children('li').each(function () {
      var $li = jQuery(this);
      var $childList = $li.children('.collapse').children('ul').first();
      if (!$childList.length) {
        $childList = $li.children('ul').first();
      }

      var selfMatch = !query || nkGetFilterItemLabel($li).indexOf(query) !== -1;
      var childMatch = false;

      if ($childList.length) {
        childMatch = nkFilterTreeList($childList, query);
        if (query && (childMatch || selfMatch)) {
          nkExpandFilterParent($li);
        }
        if (query && selfMatch && !childMatch) {
          $childList.find('li').removeClass('nk-filter-search-hidden');
        }
      }

      var show = !query || selfMatch || childMatch;
      $li.toggleClass('nk-filter-search-hidden', !show);
      if (show) {
        anyVisible = true;
      }
    });

    return anyVisible;
  }

  function nkFilterFlatList($list, query) {
    var anyVisible = false;
    $list.children('li').each(function () {
      var $li = jQuery(this);
      var show = !query || nkGetFilterItemLabel($li).indexOf(query) !== -1;
      $li.toggleClass('nk-filter-search-hidden', !show);
      if (show) {
        anyVisible = true;
      }
    });
    return anyVisible;
  }

  function nkRunFilterBoxSearch($input) {
    var $box = $input.closest('.category-nk__filter__box');
    var $panel = $input.closest('.collapse');
    var query = nkNormalizeFilterSearch($input.val());
    var $list = $panel.find('ul.cathidden, ul.brandhidden, ul.category-nk__filter__colors__items, ul.category-nk__filter__sizes__items').first();
    var $empty = $panel.children('.category-nk__filter__search-empty');
    var anyVisible = true;

    $box.toggleClass('is-nk-filter-searching', !!query);

    if (!$list.length) {
      $empty.prop('hidden', true);
      return;
    }

    if ($list.hasClass('cathidden') || $list.hasClass('brandhidden')) {
      anyVisible = nkFilterTreeList($list, query);
    } else {
      anyVisible = nkFilterFlatList($list, query);
    }

    if (!query) {
      $list.find('li').removeClass('nk-filter-search-hidden');
      anyVisible = true;
    }

    $empty.prop('hidden', anyVisible);

    var $scroll = $list.closest('.category-nk__filter__scroll');
    if ($scroll.length && query) {
      $scroll.stop(true).animate({ scrollTop: 0 }, 120);
    }
  }

  jQuery(document).on('input search', '.category-nk__filter__search-input', function () {
    nkRunFilterBoxSearch(jQuery(this));
  });

  jQuery(document).on('keydown', '.category-nk__filter__search-input', function (e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
      jQuery(this).val('');
      nkRunFilterBoxSearch(jQuery(this));
    }
  });

})
