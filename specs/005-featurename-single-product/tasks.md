# Tasks: 005 Single Product Page

**Feature Branch**: `005-featurename-single-product`  
**Spec Reference**: `specs/005-featurename-single-product/spec.md`  
**Plan Reference**: `specs/005-featurename-single-product/plan.md`  
**Status**: Ready for Execution  

---

## Task List

- [ ] **T070**: Create `inc/single-product.php` for single product helper tags, specs table generator, and trust badges data.
  - *Files*: `inc/single-product.php`
  - *Verification*: Loads without PHP notices; specs table helper formats product attributes correctly.

- [ ] **T071**: Create `woocommerce/single-product/product-gallery.php` (`PROD-01`) with main image, discount badge, zoom action, and thumbnail strip.
  - *Files*: `woocommerce/single-product/product-gallery.php`
  - *Verification*: Renders main image and thumbnail carousel with active states.

- [ ] **T072**: Create `woocommerce/single-product/delivery-perks.php` (`PROD-03`) with 4 assurance perks (authenticity, express delivery, 7-day returns, warranty).
  - *Files*: `woocommerce/single-product/delivery-perks.php`
  - *Verification*: Renders 4 perk cards with custom SVG icons.

- [ ] **T073**: Create `woocommerce/single-product/product-tabs.php` (`PROD-04`) with tabs for Description, Technical Specifications table, and Customer Reviews.
  - *Files*: `woocommerce/single-product/product-tabs.php`
  - *Verification*: Tab headers and panels render with semantic markup.

- [ ] **T074**: Create `woocommerce/single-product/related.php` (`PROD-05`) with related products slider using standard product cards.
  - *Files*: `woocommerce/single-product/related.php`
  - *Verification*: Renders related products grid/slider with `card.php`.

- [ ] **T075**: Create `woocommerce/content-single-product.php` and `woocommerce/single-product.php` assembling gallery, buy box, delivery perks, tabs, and related carousel into a 2-column layout.
  - *Files*: `woocommerce/single-product.php`, `woocommerce/content-single-product.php`
  - *Verification*: Single product URL displays the bespoke layout.

- [ ] **T076**: Create `assets/css/single-product.css` styling the gallery, buy box, quantity selector, specs table, tabs, and trust badges.
  - *Files*: `assets/css/single-product.css`
  - *Verification*: Product page displays styled typography, swatches, and clean responsive columns.

- [ ] **T077**: Create `assets/js/single-product.js` for thumbnail swapping, quantity +/- steppers, and tab navigation.
  - *Files*: `assets/js/single-product.js`
  - *Verification*: Clicking thumbnails updates main image; +/- updates quantity; clicking tab headers switches panels.

- [ ] **T078**: Wire up single product files and assets in `functions.php` and `inc/enqueue.php`.
  - *Files*: `functions.php`, `inc/enqueue.php`
  - *Verification*: Assets enqueue only on `is_product()`.

- [ ] **T079**: Verification & Quality Gate: Capture Playwright screenshots in 4 widths (375, 768, 1024, 1440), verify HTTP 200, test gallery and tabs interactions, and update Coverage Matrix (`PROD-01` to `PROD-05`).
  - *Files*: `specs/coverage-matrix.md`, `PROGRESS.md`, `docs/compare/`
  - *Verification*: 5 matrix rows updated to `verified`.
