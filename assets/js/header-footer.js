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
   * 1. Live Ajax Search Controller
   */
  function initLiveSearch() {
    const searchForms = document.querySelectorAll('.negar-ajax-search-form');

    searchForms.forEach((form) => {
      const input = form.querySelector('.negar-search-input');
      const resultsContainer = form.parentElement.querySelector('.negar-ajax-search-results');
      const spinner = form.querySelector('.search-spinner');
      const catSelect = form.querySelector('select[name="product_cat"]');

      if (!input || !resultsContainer) return;

      let debounceTimer = null;
      let selectedIndex = -1;

      input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
          resultsContainer.innerHTML = '';
          resultsContainer.classList.add('hidden');
          if (spinner) spinner.classList.add('hidden');
          return;
        }

        if (spinner) spinner.classList.remove('hidden');

        debounceTimer = setTimeout(() => {
          const category = catSelect ? catSelect.value : '';
          const url = `${params.ajaxUrl}?action=negar_shop_ajax_search&nonce=${params.nonce}&term=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`;

          fetch(url)
            .then((res) => res.json())
            .then((data) => {
              if (spinner) spinner.classList.add('hidden');

              if (data.success && data.data.results && data.data.results.length > 0) {
                renderSearchResults(data.data.results, data.data.view_all_url, resultsContainer);
                resultsContainer.classList.remove('hidden');
                selectedIndex = -1;
              } else {
                resultsContainer.innerHTML = `
                  <div class="p-6 text-center text-xs text-slate-500">
                    <p class="m-0">${params.i18n ? params.i18n.error || 'کالایی با این مشخصات یافت نشد.' : 'کالایی یافت نشد.'}</p>
                  </div>
                `;
                resultsContainer.classList.remove('hidden');
              }
            })
            .catch(() => {
              if (spinner) spinner.classList.add('hidden');
            });
        }, 300);
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

  function renderSearchResults(items, viewAllUrl, container) {
    let html = '<div class="search-results-list divide-y divide-slate-100">';

    items.forEach((item) => {
      html += `
        <a href="${item.url}" class="search-result-item flex items-center gap-3 p-3 hover:bg-slate-50 transition">
          <img src="${item.thumb || ''}" alt="${item.title}" class="search-result-thumb w-12 h-12 object-cover rounded-lg bg-slate-100">
          <div class="flex-1 min-w-0">
            <h5 class="search-result-title text-xs font-bold text-slate-800 line-clamp-1 m-0">${item.title}</h5>
            ${item.category ? `<span class="search-result-category text-3xs text-slate-400 block">${item.category}</span>` : ''}
          </div>
          ${item.price ? `<div class="search-result-price text-xs font-bold text-emerald-600 shrink-0">${item.price}</div>` : ''}
        </a>
      `;
    });

    html += `
      <div class="p-3 bg-slate-50 text-center border-t border-slate-100">
        <a href="${viewAllUrl}" class="text-xs font-bold text-emerald-600 hover:underline">
          مشاهده همه نتایج جستجو ←
        </a>
      </div>
    </div>`;

    container.innerHTML = html;
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
   * 2. Sticky Header Controller
   */
  function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let lastScrollY = window.scrollY;

    window.addEventListener(
      'scroll',
      function () {
        const currentScrollY = window.scrollY;

        if (currentScrollY > 250) {
          header.classList.add('is-sticky');
        } else if (currentScrollY <= 150) {
          header.classList.remove('is-sticky');
        }

        lastScrollY = currentScrollY;
      },
      { passive: true }
    );
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
