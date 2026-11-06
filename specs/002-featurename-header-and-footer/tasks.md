# Tasks: 002 Header & Footer

**Feature Branch**: `002-featurename-header-and-footer`  
**Spec Reference**: `specs/002-featurename-header-and-footer/spec.md`  
**Plan Reference**: `specs/002-featurename-header-and-footer/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T020**: Create `inc/ajax-search.php` implementing real-time AJAX product search endpoint with security checks and nonce validation (`HDR-02`).
  - *Files*: `inc/ajax-search.php`
  - *Verification*: AJAX action `negar_shop_ajax_search` returns JSON product matches.

- [x] **T021**: Create `inc/mega-menu.php` with custom Nav Walker for mega-menu multi-column grids and badges (`HDR-03`).
  - *Files*: `inc/mega-menu.php`
  - *Verification*: Custom walker renders multi-column submenus cleanly.

- [x] **T022**: Create `template-parts/header/top-bar.php` with announcement text and support hotline (`HDR-01`).
  - *Files*: `template-parts/header/top-bar.php`
  - *Verification*: Top bar renders in desktop viewport.

- [x] **T023**: Create `template-parts/header/main-header.php` with logo, search form, account trigger, wishlist badge, and cart trigger (`HDR-02`).
  - *Files*: `template-parts/header/main-header.php`
  - *Verification*: Layout matches RTL specs with crisp SVG icons.

- [x] **T024**: Create `template-parts/header/navigation.php` with category dropdown and primary navigation links (`HDR-03`).
  - *Files*: `template-parts/header/navigation.php`
  - *Verification*: Menus render with hover state dropdowns.

- [x] **T025**: Create `template-parts/header/mini-cart-drawer.php` implementing off-canvas slide-out mini-cart (`FLOW-01` trigger).
  - *Files*: `template-parts/header/mini-cart-drawer.php`
  - *Verification*: Drawer markup renders with cart items, subtotal, and checkout CTAs.

- [x] **T026**: Create mobile components: `mobile-header.php`, `mobile-drawer.php`, and `mobile-bottom-bar.php` (`HDR-05`).
  - *Files*: `template-parts/header/mobile-header.php`, `mobile-drawer.php`, `mobile-bottom-bar.php`
  - *Verification*: Mobile navigation and sticky bottom bar render at 375px viewport.

- [x] **T027**: Create footer components: `features.php` (`FTR-01`), `newsletter.php` (`FTR-02`), `columns.php` (`FTR-03`), `trust-badges.php` (`FTR-04`), and `copyright.php` (`FTR-05`).
  - *Files*: `template-parts/footer/features.php`, `newsletter.php`, `columns.php`, `trust-badges.php`, `copyright.php`
  - *Verification*: Footer displays value badges, newsletter form, 4 columns, and trust badges.

- [x] **T028**: Create `assets/css/header-footer.css` containing dedicated styles for top-bar, header, mega-menu, drawers, bottom-bar, and footer.
  - *Files*: `assets/css/header-footer.css`
  - *Verification*: CSS compiles without syntax errors and applies responsive layout.

- [x] **T029**: Create `assets/js/header-footer.js` with live search debounce, sticky header, drawer toggles, and back-to-top scrolling (`HDR-04`).
  - *Files*: `assets/js/header-footer.js`
  - *Verification*: Search suggestions render dynamically, drawers open/close smoothly, and header sticks on scroll.

- [x] **T030**: Integrate components into `header.php`, `footer.php`, `functions.php`, and enqueue assets in `inc/enqueue.php`.
  - *Files*: `header.php`, `footer.php`, `functions.php`, `inc/enqueue.php`
  - *Verification*: Full page loads with complete header and footer.

- [x] **T031**: Verification & Quality Gate: Test live search, drawer transitions, mobile view at 375px, desktop at 1440px, check `WP_DEBUG` logs, and update Coverage Matrix (`HDR-01` to `HDR-05` and `FTR-01` to `FTR-05`).
  - *Verification*: 10 matrix rows updated to `verified`.
