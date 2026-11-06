# Tasks: 001 Foundation

**Feature Branch**: `001-featurename-foundation`  
**Spec Reference**: `specs/001-featurename-foundation/spec.md`  
**Plan Reference**: `specs/001-featurename-foundation/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T001**: Create `style.css` with theme header declarations and base document definitions.
  - *Files*: `style.css`
  - *Verification*: WordPress recognises theme metadata; text domain is `negar-shop`.

- [x] **T002**: Create `theme.json` (V3) containing design tokens for color, typography, spacing, and layout.
  - *Files*: `theme.json`
  - *Verification*: WordPress generates `--wp--preset--color--primary` and related CSS variables.

- [x] **T003**: Create `functions.php` bootstrapping constants and requiring `inc/` modules safely.
  - *Files*: `functions.php`
  - *Verification*: Constants `NEGAR_SHOP_VERSION` and `NEGAR_SHOP_DIR` are defined.

- [x] **T004**: Implement `inc/setup.php` with `after_setup_theme` hooks (title-tag, thumbnails, HTML5, text domain).
  - *Files*: `inc/setup.php`
  - *Verification*: Theme supports confirmed via WP-CLI.

- [x] **T005**: Create `assets/css/main.css` and `rtl.css` with core styling, reset, RTL logic, and design token integration.
  - *Files*: `assets/css/main.css`, `rtl.css`
  - *Verification*: RTL styling and CSS custom properties active in browser.

- [x] **T006**: Create `assets/js/main.js` with vanilla JS core structure (`window.NegarShop`).
  - *Files*: `assets/js/main.js`
  - *Verification*: `NegarShop` namespace exists and logs zero console errors.

- [x] **T007**: Implement `inc/enqueue.php` for font preloading, stylesheet enqueues, and script localization (`negarShopParams`).
  - *Files*: `inc/enqueue.php`
  - *Verification*: CSS/JS loaded in `<head>` and before `</body>` with proper dependencies.

- [x] **T008**: Implement `inc/woocommerce.php` declaring WooCommerce theme support and semantic layout wrappers.
  - *Files*: `inc/woocommerce.php`
  - *Verification*: WooCommerce system report confirms theme support; no deprecated template notices.

- [x] **T009**: Implement `inc/template-tags.php` providing safe formatting and escaping helpers.
  - *Files*: `inc/template-tags.php`
  - *Verification*: Helper functions pass syntax check.

- [x] **T010**: Create semantic HTML5 root templates: `header.php`, `footer.php`, and `index.php`.
  - *Files*: `header.php`, `footer.php`, `index.php`
  - *Verification*: Valid HTML5 structure with `wp_head()` and `wp_footer()` rendering.

- [x] **T011**: Verify Feature 001 Gate: Activate theme on local WordPress site, run WP-CLI health checks, verify zero PHP errors in `WP_DEBUG`, and update `specs/coverage-matrix.md`.
  - *Verification*: Theme active at `http://localhost/wp4/`, HTTP 200, status `done` for FND-01 through FND-04.
