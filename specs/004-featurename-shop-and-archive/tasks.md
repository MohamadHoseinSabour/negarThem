# Tasks: 004 Shop and Archive

**Feature Branch**: `004-featurename-shop-and-archive`  
**Spec Reference**: `specs/004-featurename-shop-and-archive/spec.md`  
**Plan Reference**: `specs/004-featurename-shop-and-archive/plan.md`  
**Status**: Ready for Execution  

---

## Task List

- [ ] **T060**: Create `inc/shop.php` for shop helper functions, custom breadcrumbs formatter, and archive settings.
  - *Files*: `inc/shop.php`
  - *Verification*: Helper functions load cleanly without PHP notices.

- [ ] **T061**: Create `inc/shop-ajax.php` registering `negar_shop_filter_products` Ajax endpoint with sanitization, query assembly, and JSON response.
  - *Files*: `inc/shop-ajax.php`
  - *Verification*: Endpoint responds with HTTP 200 and valid JSON when queried with valid nonce.

- [ ] **T062**: Create `template-parts/shop/toolbar.php` (`SHOP-02`) with results counter, orderby select, active chips container, grid/list view toggle, and mobile filter trigger button.
  - *Files*: `template-parts/shop/toolbar.php`
  - *Verification*: Toolbar renders with correct options and data attributes.

- [ ] **T063**: Create `template-parts/shop/sidebar-filters.php` (`SHOP-03`) with category accordion/tree, price range inputs, stock/sale toggle switches, and reset filters button.
  - *Files*: `template-parts/shop/sidebar-filters.php`
  - *Verification*: Filter widgets render correctly with category counts and current query state.

- [ ] **T064**: Create `template-parts/shop/loop.php` (`SHOP-04`, `SHOP-05`) rendering product loop with standard cards, empty state, and numeric pagination.
  - *Files*: `template-parts/shop/loop.php`
  - *Verification*: Renders product grid with pagination links.

- [ ] **T065**: Create `woocommerce/archive-product.php` (`SHOP-01`) assembling breadcrumbs, shop layout, desktop sidebar, mobile filters drawer, toolbar, and product loop.
  - *Files*: `woocommerce/archive-product.php`
  - *Verification*: `/shop/` loads custom archive template replacing default WooCommerce layout.

- [ ] **T066**: Create `assets/css/shop.css` with 2-column grid layout, sidebar widgets, mobile off-canvas drawer, toolbar, and `.is-list-view` card styling.
  - *Files*: `assets/css/shop.css`
  - *Verification*: Shop layout renders cleanly across mobile, tablet, and desktop viewports.

- [ ] **T067**: Create `assets/js/shop.js` implementing live Ajax filtering, URL state persistence, grid/list toggle, mobile drawer controls, and Ajax pagination.
  - *Files*: `assets/js/shop.js`
  - *Verification*: Filters update products dynamically via Ajax without page reload; view switches smoothly; mobile drawer opens/closes.

- [ ] **T068**: Wire up shop assets and files in `inc/enqueue.php` and `functions.php`.
  - *Files*: `inc/enqueue.php`, `functions.php`
  - *Verification*: `shop.css` and `shop.js` enqueue on shop and product archive pages with localized parameters.

- [ ] **T069**: Verification & Quality Gate: Capture Playwright screenshots in 4 widths (375, 768, 1024, 1440), verify HTTP 200, test Ajax filtering, and update Coverage Matrix (`SHOP-01` to `SHOP-05`).
  - *Files*: `specs/coverage-matrix.md`, `PROGRESS.md`, `docs/compare/`
  - *Verification*: 5 matrix rows updated to `verified`.
