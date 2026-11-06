# Feature Plan: 004 Shop and Archive

**Feature Branch**: `004-featurename-shop-and-archive`  
**Created**: 2026-11-06  
**Status**: Ready for Tasks Breakdown  
**Spec Reference**: `specs/004-featurename-shop-and-archive/spec.md`  

---

## 1. Architecture & Component Structure

```
woocommerce/
└── archive-product.php              # Master WooCommerce archive override (shop, category, tag)

template-parts/shop/
├── toolbar.php                     # Toolbar with count, sort select, grid/list switcher, mobile filter button
├── sidebar-filters.php             # Modern filter widgets (categories, price, stock, sale, active chips)
└── loop.php                        # Product loop rendering standard cards or empty state

inc/
├── shop.php                        # Shop helper functions, query customizations, breadcrumbs helper
└── shop-ajax.php                   # Secure Ajax endpoint for dynamic live filtering & pagination

assets/
├── css/shop.css                    # Responsive 2-column layout, mobile drawer, toolbar, list-view card overrides
└── js/shop.js                      # Ajax filtering, view switcher, mobile drawer, URL history pushState
```

---

## 2. Technical Decisions & Standards

1. **Clean-Room Codebase**: 100% bespoke HTML/CSS/JS written from scratch; uses tokens from `theme.json` (`--negar-primary`, `--negar-surface`, `--negar-border`, `--negar-radius-md`, etc.).
2. **WooCommerce Archive Standard**:
   - `woocommerce/archive-product.php` overrides WooCommerce core templates cleanly.
   - Preserves standard hooks: `woocommerce_before_shop_loop`, `woocommerce_after_shop_loop`, `woocommerce_no_products_found`.
3. **Product Card Integration**:
   - Uses `template-parts/product/card.php` created in Feature 003.
   - For List View (`.is-list-view`), CSS provides horizontal layout: right-aligned thumbnail, left-aligned content with excerpt, rating stars, price, and CTA.
4. **Ajax Security & Robustness**:
   - Uses `check_ajax_referer( 'negar_shop_nonce', 'nonce' )`.
   - Uses `absint()`, `sanitize_text_field()`, and `wp_unslash()`.
   - Browser History: calls `window.history.pushState()` with URL params so users can bookmark or reload filtered catalog pages.
   - Graceful Degradation: If JS is disabled or fails, standard GET query parameters (`?orderby=...&min_price=...`) work natively via standard WooCommerce queries.
5. **Accessibility & RTL**:
   - Full keyboard navigation for filter inputs and mobile drawer.
   - `aria-expanded` and `aria-controls` on the mobile filter trigger and drawer.
   - Focus trap in the mobile drawer when active.

---

## 3. Step-by-Step Implementation Sequence

1. **Step 1 - Backend & Query Engine**:
   - Create `inc/shop.php` and `inc/shop-ajax.php` registering the Ajax endpoint `negar_shop_filter_products`.
   - Register template helpers in `functions.php`.
2. **Step 2 - Filter Widgets & Toolbar Templates**:
   - Create `template-parts/shop/sidebar-filters.php`.
   - Create `template-parts/shop/toolbar.php`.
   - Create `template-parts/shop/loop.php`.
3. **Step 3 - Master Archive Template**:
   - Create `woocommerce/archive-product.php` assembling breadcrumbs, layout grid, desktop sidebar, mobile drawer, toolbar, and product loop.
4. **Step 4 - CSS Stylesheet**:
   - Create `assets/css/shop.css` with mobile-first grid, toolbar flexbox, custom switch toggles, drawer animations, and `.is-list-view` card styling.
   - Enqueue in `inc/enqueue.php` for shop and product archive pages.
5. **Step 5 - Interactive Client Script**:
   - Create `assets/js/shop.js` handling filter changes, Ajax dispatch, loading overlay, view toggling, drawer toggle, and pagination.
   - Enqueue with localized script object (ajax_url, nonce, i18n strings).
6. **Step 6 - Verification & Quality Gate**:
   - Test `/shop/` with Playwright across 4 viewports (375, 768, 1024, 1440).
   - Verify Ajax filtering with price, category, and in-stock parameters.
   - Verify Grid/List view toggle.
   - Verify mobile drawer sliding and closing.
   - Update `specs/coverage-matrix.md` (`SHOP-01` to `SHOP-05`).
