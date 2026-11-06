# Tasks: 003 Home Page

**Feature Branch**: `003-featurename-home-page`  
**Spec Reference**: `specs/003-featurename-home-page/spec.md`  
**Plan Reference**: `specs/003-featurename-home-page/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T040**: Create standard reusable product card component in `template-parts/product/card.php`.
  - *Files*: `template-parts/product/card.php`
  - *Verification*: Product card renders with thumbnail, discount badge, title, price, and action buttons.

- [x] **T041**: Create `template-parts/home/hero-slider.php` with main slider and 2 adjacent campaign banners (`HOME-01`).
  - *Files*: `template-parts/home/hero-slider.php`
  - *Verification*: Hero section renders with slide items and banners.

- [x] **T042**: Create `template-parts/home/category-bubbles.php` with circular category icon cards and counts (`HOME-02`).
  - *Files*: `template-parts/home/category-bubbles.php`
  - *Verification*: Category bubbles display icon, name, and product count.

- [x] **T043**: Create `template-parts/home/flash-deals.php` with crimson card, countdown timer, discount badges, and stock bar (`HOME-03`).
  - *Files*: `template-parts/home/flash-deals.php`
  - *Verification*: Flash deals slider renders with countdown timer markup.

- [x] **T044**: Create `template-parts/home/banners-grid.php` with middle campaign banners (`HOME-04`).
  - *Files*: `template-parts/home/banners-grid.php`
  - *Verification*: Grid of campaign banners renders with hover effects.

- [x] **T045**: Create `template-parts/home/product-tabs.php` with tabs for Newest, Best Sellers, and Sale items (`HOME-05`).
  - *Files*: `template-parts/home/product-tabs.php`
  - *Verification*: Product tabs render with active tab states.

- [x] **T046**: Create `template-parts/home/brands-slider.php` for partner brands logos (`HOME-06`).
  - *Files*: `template-parts/home/brands-slider.php`
  - *Verification*: Brand logos render with grayscale-to-color hover.

- [x] **T047**: Create `template-parts/home/category-showcase.php` with vertical banner + products carousel (`HOME-07`).
  - *Files*: `template-parts/home/category-showcase.php`
  - *Verification*: Showcase renders with vertical banner and product cards.

- [x] **T048**: Create `template-parts/home/blog-posts.php` with 3-4 blog article cards (`HOME-08`).
  - *Files*: `template-parts/home/blog-posts.php`
  - *Verification*: Blog cards render with thumbnails and metadata.

- [x] **T049**: Create `front-page.php` assembling all 8 sections in sequence.
  - *Files*: `front-page.php`
  - *Verification*: Homepage loads all 8 sections sequentially.

- [x] **T050**: Create `assets/css/home.css` styling sliders, cards, countdown, tabs, and banners.
  - *Files*: `assets/css/home.css`
  - *Verification*: Styles apply without layout breakage.

- [x] **T051**: Create `assets/js/home.js` controlling vanilla JS carousels, realtime countdown, and tab switching.
  - *Files*: `assets/js/home.js`
  - *Verification*: Sliders advance, tabs switch, and countdown timer decrements in real time.

- [x] **T052**: Create `inc/elementor/class-elementor-init.php` for custom Elementor widgets integration.
  - *Files*: `inc/elementor/class-elementor-init.php`
  - *Verification*: Elementor widget category and classes registered if Elementor is loaded.

- [x] **T053**: Verification & Quality Gate: Capture Playwright screenshots in 4 widths (375, 768, 1024, 1440), verify HTTP 200, check `WP_DEBUG` logs, and update Coverage Matrix (`HOME-01` to `HOME-08`).
  - *Verification*: 8 matrix rows updated to `verified`.
