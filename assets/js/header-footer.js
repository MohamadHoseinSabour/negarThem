/**
 * Negar Shop - Header & Footer Interactive Script
 * 
 * @package Negar_Shop
 * @version 1.0.0
 */

(function () {
  'use strict';

  const params = window.negarShopParams || {};

  /**
   * 1. Live Ajax Search Controller with Category Filter
   */
  function initLiveSearch() {
    const searchForms = document.querySelectorAll('.negar-ajax-search-form');

    function closeAllCategoryDropdowns() {
      document.querySelectorAll('.negar-category-dropdown').forEach((dd) => {
        dd.classList.add('hidden');
      });
      document.querySelectorAll('.category-menu-btn').forEach((btn) => {
        btn.setAttribute('aria-expanded', 'false');
      });
    }

    document.addEventListener('click', function (e) {
      if (!e.target.closest('.category-select-wrapper')) {
        closeAllCategoryDropdowns();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeAllCategoryDropdowns();
      }
    });

    searchForms.forEach((form) => {
      const input = form.querySelector('.negar-search-input');
      const resultsContainer = form.parentElement.querySelector('.negar-ajax-search-results');
      const spinner = form.querySelector('.search-spinner');
      const catInput = form.querySelector('[name="product_cat"]');
      const catBtn = form.querySelector('.category-menu-btn');
      const catDropdown = form.querySelector('.negar-category-dropdown');

      if (!input || !resultsContainer) return;

      // Category Dropdown Interaction
      if (catBtn && catDropdown) {
        catBtn.addEventListener('click', function (e) {
          e.stopPropagation();
          const isOpen = !catDropdown.classList.contains('hidden');
          closeAllCategoryDropdowns();
          if (!isOpen) {
            catDropdown.classList.remove('hidden');
            catBtn.setAttribute('aria-expanded', 'true');
          }
        });

        const options = catDropdown.querySelectorAll('.category-option');
        options.forEach((opt) => {
          opt.addEventListener('click', function (e) {
            e.stopPropagation();
            const slug = this.getAttribute('data-cat-slug') || '';
            const name = this.getAttribute('data-cat-name') || '';
            if (catInput) catInput.value = slug;
            const labelEl = catBtn.querySelector('.category-btn-label');
            if (labelEl) labelEl.textContent = name;

            options.forEach((o) => {
              const check = o.querySelector('.cat-check');
              if (check) check.classList.add('hidden');
            });
            const thisCheck = this.querySelector('.cat-check');
            if (thisCheck) thisCheck.classList.remove('hidden');

            catDropdown.classList.add('hidden');
            catBtn.setAttribute('aria-expanded', 'false');

            if (input.value.trim().length >= 2) {
              input.dispatchEvent(new Event('input'));
            }
          });
        });
      }

      let debounceTimer = null;
      let selectedIndex = -1;
      let activeController = null;

      input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (activeController) {
          activeController.abort();
          activeController = null;
        }

        if (query.length < 2) {
          resultsContainer.innerHTML = '';
          resultsContainer.classList.add('hidden');
          if (spinner) spinner.classList.add('hidden');
          return;
        }

        if (spinner) spinner.classList.remove('hidden');

        debounceTimer = setTimeout(() => {
          activeController = new AbortController();
          const signal = activeController.signal;
          const category = catInput ? catInput.value : '';
          const url = `${params.ajaxUrl}?action=negar_shop_ajax_search&nonce=${params.nonce}&term=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`;

          fetch(url, { signal: signal })
            .then((res) => res.json())
            .then((data) => {
              if (spinner) spinner.classList.add('hidden');

              if (data.success && data.data.results && data.data.results.length > 0) {
                renderSearchResults(data.data.results, data.data.view_all_url, resultsContainer);
                resultsContainer.classList.remove('hidden');
                selectedIndex = -1;
              } else {
                renderEmptySearchResults(resultsContainer);
                resultsContainer.classList.remove('hidden');
              }
            })
            .catch((err) => {
              if (err.name === 'AbortError') return;
              if (spinner) spinner.classList.add('hidden');
            });
        }, 280);
      });

      // Keyboard Navigation
      input.addEventListener('keydown', function (e) {
        const items = resultsContainer.querySelectorAll('.search-result-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
          e.preventDefault();
          selectedIndex = (selectedIndex + 1) % items.length;
          highlightItem(items, selectedIndex);
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          selectedIndex = (selectedIndex - 1 + items.length) % items.length;
          highlightItem(items, selectedIndex);
        } else if (e.key === 'Enter' && selectedIndex >= 0) {
          e.preventDefault();
          items[selectedIndex].click();
        } else if (e.key === 'Escape') {
          resultsContainer.classList.add('hidden');
        }
      });
    });

    // Close on Click Outside
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.header-search-wrap')) {
        document.querySelectorAll('.negar-ajax-search-results').forEach((res) => {
          res.classList.add('hidden');
        });
      }
    });
  }

  function renderEmptySearchResults(container) {
    container.innerHTML = '';
    const emptyBox = document.createElement('div');
    emptyBox.className = 'p-6 text-center text-xs text-slate-500';
    const p = document.createElement('p');
    p.className = 'm-0';
    p.textContent = (params.i18n && params.i18n.error) ? params.i18n.error : 'کالایی با این مشخصات یافت نشد.';
    emptyBox.appendChild(p);
    container.appendChild(emptyBox);
  }

  function renderSearchResults(items, viewAllUrl, container) {
    container.innerHTML = '';
    const list = document.createElement('div');
    list.className = 'search-results-list divide-y divide-slate-100 max-h-96 overflow-y-auto';

    items.forEach((item) => {
      const a = document.createElement('a');
      a.className = 'search-result-item flex items-center gap-3 p-3 hover:bg-slate-50 transition';
      a.href = item.url || '#';

      if (item.thumb) {
        const img = document.createElement('img');
        img.className = 'search-result-thumb w-12 h-12 object-cover rounded-lg bg-slate-100 shrink-0';
        img.src = item.thumb;
        img.alt = item.title || '';
        img.loading = 'lazy';
        a.appendChild(img);
      }

      const meta = document.createElement('div');
      meta.className = 'flex-1 min-w-0';

      const title = document.createElement('h5');
      title.className = 'search-result-title text-xs font-bold text-slate-800 line-clamp-1 m-0';
      title.textContent = item.title || '';
      meta.appendChild(title);

      if (item.category) {
        const cat = document.createElement('span');
        cat.className = 'search-result-category text-3xs text-slate-400 block mt-0.5';
        cat.textContent = item.category;
        meta.appendChild(cat);
      }
      a.appendChild(meta);

      if (item.price) {
        const price = document.createElement('div');
        price.className = 'search-result-price text-xs font-bold text-emerald-600 shrink-0';
        price.innerHTML = item.price; // Backend price markup is pre-sanitized by WooCommerce
        a.appendChild(price);
      }

      list.appendChild(a);
    });

    if (viewAllUrl) {
      const footer = document.createElement('div');
      footer.className = 'p-3 bg-slate-50 text-center border-t border-slate-100';
      const viewAllLink = document.createElement('a');
      viewAllLink.className = 'text-xs font-bold text-rose-600 hover:underline';
      viewAllLink.href = viewAllUrl;
      viewAllLink.textContent = 'مشاهده همه نتایج جستجو ←';
      footer.appendChild(viewAllLink);
      list.appendChild(footer);
    }

    container.appendChild(list);
  }

  function highlightItem(items, index) {
    items.forEach((item, idx) => {
      if (idx === index) {
        item.classList.add('is-selected', 'bg-slate-100');
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.classList.remove('is-selected', 'bg-slate-100');
      }
    });
  }

  /**
   * 2. Sticky Floating Island Header Controller
   */
  function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let headerHeight = header.offsetHeight;

    function updateHeight() {
      if (!header.classList.contains('is-sticky')) {
        headerHeight = header.offsetHeight;
      }
    }
    window.addEventListener('resize', updateHeight);

    let isSticky = false;

    function handleScroll() {
      const currentScrollY = window.scrollY;

      if (currentScrollY > 160 && !isSticky) {
        updateHeight();
        header.style.minHeight = headerHeight + 'px';
        header.classList.add('is-sticky');
        isSticky = true;
      } else if (currentScrollY <= 60 && isSticky) {
        header.classList.remove('is-sticky');
        header.style.minHeight = '';
        isSticky = false;
      }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  /**
   * 3. Off-Canvas Drawers (Mini-Cart & Mobile Menu)
   */
  function initDrawers() {
    const backdrop = document.getElementById('negar-drawer-backdrop');
    const cartDrawer = document.getElementById('negar-cart-drawer');
    const mobileDrawer = document.getElementById('negar-mobile-drawer');

    function openDrawer(drawer) {
      if (!drawer) return;
      drawer.classList.add('drawer-active');
      drawer.setAttribute('aria-hidden', 'false');
      if (backdrop) {
        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => backdrop.classList.add('backdrop-active'));
      }
      document.body.classList.add('drawer-open');
    }

    // Expose drawer openers globally
    window.negarOpenCartDrawer = function () {
      openDrawer(cartDrawer);
    };
    window.negarCloseAllDrawers = function () {
      closeAllDrawers();
    };

    function closeAllDrawers() {
      if (cartDrawer) {
        cartDrawer.classList.remove('drawer-active');
        cartDrawer.setAttribute('aria-hidden', 'true');
      }
      if (mobileDrawer) {
        mobileDrawer.classList.remove('drawer-active');
        mobileDrawer.setAttribute('aria-hidden', 'true');
      }
      if (backdrop) {
        backdrop.classList.remove('backdrop-active');
        setTimeout(() => backdrop.classList.add('hidden'), 300);
      }
      document.body.classList.remove('drawer-open');
    }

    // Cart Triggers
    document.querySelectorAll('.negar-cart-trigger').forEach((btn) => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        openDrawer(cartDrawer);
      });
    });

    // Mobile Menu Trigger
    document.querySelectorAll('.mobile-menu-trigger').forEach((btn) => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        openDrawer(mobileDrawer);
      });
    });

    // Close Buttons
    document.querySelectorAll('.drawer-close-btn').forEach((btn) => {
      btn.addEventListener('click', closeAllDrawers);
    });

    // Backdrop Click
    if (backdrop) {
      backdrop.addEventListener('click', closeAllDrawers);
    }

    // Escape Key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeAllDrawers();
      }
    });

    // Mobile Drawer Tab Switcher
    const tabBtns = document.querySelectorAll('.mobile-drawer-tabs .tab-btn');
    tabBtns.forEach((btn) => {
      btn.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        tabBtns.forEach((b) => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.mobile-tab-pane').forEach((pane) => {
          pane.classList.add('hidden');
        });

        const targetPane = document.querySelector(targetId);
        if (targetPane) targetPane.classList.remove('hidden');
      });
    });
  }

  /**
   * 4. Back To Top Button
   */
  function initBackToTop() {
    const btn = document.getElementById('negar-back-to-top');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    });
  }

  /**
   * Initialize on DOM Ready
   */
  function init() {
    initLiveSearch();
    initStickyHeader();
    initDrawers();
    initBackToTop();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
