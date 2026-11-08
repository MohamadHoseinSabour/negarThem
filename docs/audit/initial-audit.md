# Initial Audit — Negar Shop Production Hardening & Fidelity Audit

**Date:** 2026-11-08  
**Auditor:** Negar Shop Production Hardening Specialist  
**Target Repository:** `https://github.com/MohamadHoseinSabour/negarThem`  
**Active Branch:** `fix/production-quality-audit`  
**Baseline Git Commit:** `a0bd3f8312aceb7a456be96938e95a003cb21ff2`  
**Reference Demo:** [Kaveh Shop Demo 43](https://kaveh.moeinwp.com/43/)

---

## 1. Verified Local Test Environment

| Component | Tested Version | Details |
|---|---|---|
| **WordPress Core** | `7.1.4` | Local installation under `X:\apps\wamp\www\wp4` |
| **WooCommerce** | `10.5.2` | Active WooCommerce store plugin |
| **Persian WooCommerce** | `10.0.2` | Active localization and currency plugin |
| **PHP** | `8.2.29 (cli)` | ZTS Visual C++ 2019 x64 with Xdebug v3.4.7 |
| **Web Server** | `Apache 2.4.65 (Win64)` | `mod_fcgid/2.3.10-dev`, Port 80 |
| **Database** | `MySQL 8.x` / `MariaDB 11.x` | Running on port 3306, database `wp4`, user `root` |
| **Node.js** | `v24.13.1` | Local runtime |
| **npm** | `11.8.0` | Package manager |
| **WP-CLI** | `2.12.0` | Located at `X:\apps\wamp\bin\wp-cli\wp-cli.phar` |
| **Local Store URL** | `http://localhost/wp4` | Active local testing route |

---

## 2. Executive Summary of Audit Findings

| Finding ID | Domain / Component | Severity | Title | Status |
|---|---|---|---|---|
| **AUD-01** | PHP Core / Headers | **P0 - Critical** | UTF-8 BOM present in 11 PHP files causing premature header output | Confirmed |
| **AUD-02** | Shop / AJAX Filter | **P0 - Critical** | Shop AJAX contract mismatch between JS caller and PHP handler | Confirmed |
| **AUD-03** | WooCommerce / Cart | **P0 - Critical** | Sitewide AJAX Add-to-Cart script unlocalized and missing server actions | Confirmed |
| **AUD-04** | Header / Live Search | **P1 - High** | Main header search form missing class selector & unsafe HTML rendering | Confirmed |
| **AUD-05** | UI / Notifications | **P2 - Medium** | Fragmented notification systems with conflicting configs and selectors | Confirmed |
| **AUD-06** | UI / Modals | **P1 - High** | QuickView variable product handling and missing card action triggers | Confirmed |
| **AUD-07** | Checkout / Funnel | **P1 - High** | Order received template displays success ribbon for pending/on-hold orders | Confirmed |
| **AUD-08** | Product Data / Catalog | **P2 - Medium** | Hardcoded fabricated ratings (4.0, 4.5, max) bypassing real review data | Confirmed |
| **AUD-09** | QA / Visual Fidelity | **P1 - High** | Duplicate / byte-identical screenshots in `docs/compare/` across pages | Confirmed |
| **AUD-10** | Release / Versioning | **P2 - Medium** | Discrepancies across README (2.5.0), functions.php (1.0.2), style.css (1.0.0) | Confirmed |
| **AUD-11** | Architecture / Legacy | **P3 - Low** | Dead legacy Kaveh login actions and empty Elementor widget registration | Confirmed |

---

## 3. Detailed Audit Findings & Action Plans

### AUD-01: UTF-8 BOM in PHP Files (Header & Session Hazard)
- **Severity:** P0 (Critical)
- **Affected Files:**
  - `inc/setup.php`
  - `template-parts/footer/copyright.php`
  - `template-parts/footer/features.php`
  - `template-parts/footer/newsletter.php`
  - `template-parts/footer/trust-badges.php`
  - `template-parts/header/mini-cart-drawer.php`
  - `template-parts/header/mobile-bottom-bar.php`
  - `template-parts/header/mobile-drawer.php`
  - `template-parts/header/mobile-header.php`
  - `template-parts/home/category-showcase.php`
  - `woocommerce/myaccount/orders.php`
- **Evidence:** Byte sequence `0xEF, 0xBB, 0xBF` detected at byte offset 0 of each file. Observed during WP-CLI runs where `\uFEFF` was output before any stdout.
- **Reproduction:** Run `wp-cli` command or execute `php -r "require 'inc/setup.php';"` and inspect output; headers cannot be sent afterwards.
- **Expected Behavior:** All PHP files must be UTF-8 without BOM.
- **Actual Behavior:** `inc/setup.php` outputs `\uFEFF` during WordPress bootstrap, breaking HTTP redirects, cookies, cart sessions, and REST API/AJAX JSON headers.
- **Proposed Fix:** Strip 3-byte UTF-8 BOM from all affected files while preserving Persian UTF-8 content.
- **Validation Criteria:** File byte scans verify 0 occurrences of BOM; `headers_sent()` remains false after loading theme setup.

---

### AUD-02: Shop AJAX Contract Mismatch
- **Severity:** P0 (Critical)
- **Affected Files:**
  - `assets/js/vendor/kaveh-ajax-shop-method.js`
  - `assets/js/shop.js`
  - `inc/shop-ajax.php`
  - `inc/enqueue.php`
  - `woocommerce/archive-product.php`
- **Evidence:**
  - JS sends `catIds`, `brandIds`, `sortt`, `attrId`, `taxIds`, `attrQueryTypes`, `archiveContext`.
  - PHP handler `negar_shop_kaveh_ajax_shop_filters()` only checks `$_POST['category']`, `$_POST['categories']`, `$_POST['brand']`, `$_POST['brands']`, and `$_POST['orderby']`.
  - Localized `archiveContext` in `inc/enqueue.php` sends `term_id`, but JS expects `termId`.
  - Attribute filtering (`attrId`, `taxIds`) is completely unhandled by PHP.
- **Reproduction:** On the shop page, select a category, brand, or attribute filter; observe request payload vs returned product grid. The query ignores `catIds` and attributes, returning unfiltered results.
- **Expected Behavior:** Filters restrict product queries accurately via WC taxonomy and meta queries.
- **Actual Behavior:** Products do not change according to selected filters.
- **Proposed Fix:** Reconcile client/server contract in `inc/shop-ajax.php` and `kaveh-ajax-shop-method.js` to parse both schemas (`catIds`/`categories`, `brandIds`/`brands`, `sortt`/`orderby`, JSON-encoded `attrId`/`taxIds`, price boundaries, and archive context).
- **Validation Criteria:** Selecting category, brand, attribute, and price filters alters the returned product set to matching items only; pagination retains active filters.

---

### AUD-03: Sitewide AJAX Add-to-Cart Failure
- **Severity:** P0 (Critical)
- **Affected Files:**
  - `assets/js/vendor/kaveh-ajax-cart.js`
  - `inc/enqueue.php`
  - `inc/woocommerce.php`
- **Evidence:**
  - `kaveh-ajax-cart.js` begins with `if (typeof kavehShopData === 'undefined') return;`.
  - `kavehShopData` is localized only inside `is_shop() || is_product_taxonomy()` in `inc/enqueue.php`.
  - Actions `kaveh_ajax_add_to_cart`, `kaveh_ajax_update_cart_kk`, and `kaveh_ajax_update_cart_kk_remove` have zero handlers in the theme's PHP code.
- **Reproduction:** Click "Add to Cart" on the front page or single product page; the script returns immediately or AJAX fails with `0`.
- **Expected Behavior:** Sitewide AJAX add-to-cart works for simple and variable products, updating mini-cart fragments and cart badges.
- **Actual Behavior:** Add-to-cart falls back to standard page reload or fails silently.
- **Proposed Fix:**
  1. Localize necessary cart parameters sitewide on `negarShopParams` or `kavehShopData`.
  2. Implement native WooCommerce AJAX endpoints (`wc_ajax=add_to_cart`) or register robust backend handlers with nonces, quantity limits, and cart fragment recalculation.
- **Validation Criteria:** Adding a product from any page adds item to WooCommerce cart, updates header badge count and mini-cart drawer without page reload.

---

### AUD-04: Live Product Search Form Class & XSS Hazard
- **Severity:** P1 (High)
- **Affected Files:**
  - `template-parts/header/main-header.php`
  - `template-parts/header/mobile-header.php`
  - `assets/js/header-footer.js`
  - `inc/ajax-search.php`
- **Evidence:**
  - `main-header.php` defines `<form role="search" ...>` without class `negar-ajax-search-form`.
  - `header-footer.js` queries `document.querySelectorAll('.negar-ajax-search-form')` and therefore never attaches to the desktop header search form.
  - `header-footer.js` interpolates product title, category, and url directly into `innerHTML` without DOM escaping.
- **Reproduction:** Type 2+ characters in the main header search input; no AJAX request is triggered because listener was never attached.
- **Expected Behavior:** Live search debounces, queries `negar_shop_ajax_search`, displays live dropdown results safely, and handles keyboard navigation.
- **Actual Behavior:** No live results dropdown appears.
- **Proposed Fix:** Add `.negar-ajax-search-form` to header search forms; rewrite `renderSearchResults` to use safe DOM node creation or text escaping.
- **Validation Criteria:** Typing 2+ characters opens live results popup with image, title, category, price; keyboard arrows navigate items; clicking outside closes dropdown.

---

### AUD-05: Fragmented Notification Systems
- **Severity:** P2 (Medium)
- **Affected Files:**
  - `assets/js/main.js`
  - `assets/js/modals.js`
  - `assets/js/vendor/kaveh-ajax-cart.js`
  - `assets/js/vendor/kaveh-ajax-shop-method.js`
- **Evidence:**
  - `modals.js` configures Notiflix with top-right position and IRANYekan/Vazirmatn fonts.
  - `kaveh-ajax-cart.js` defines custom `notifySuccess` and `notifyFailure` with left-bottom position and IRANSans font.
  - Custom DOM toast markup `#negar-toast-container` exists alongside Notiflix.
- **Reproduction:** Trigger a cart action, then trigger a wishlist action; observe conflicting toast notification placements and styles.
- **Expected Behavior:** One unified notification service with consistent typography, RTL styling, position, and duplicate prevention.
- **Proposed Fix:** Establish `window.negarToast` as the canonical notification adapter wrapping Notiflix with unified options matching Demo 43.
- **Validation Criteria:** All UI actions (cart, wishlist, compare, filters) invoke `window.negarToast` with uniform appearance and placement.

---

### AUD-06: QuickView Variable Products & Card Action Triggers
- **Severity:** P1 (High)
- **Affected Files:**
  - `inc/modals-ajax.php`
  - `assets/js/modals.js`
  - `woocommerce/content-product.php`
  - `template-parts/product/card.php`
- **Evidence:**
  - `inc/modals-ajax.php` renders `woocommerce_template_single_add_to_cart()`, but when inserted into the modal via AJAX, `wc_variation_form` is not re-initialized for variable products.
  - `woocommerce/content-product.php` lacks QuickView, Wishlist, and Compare buttons.
  - `template-parts/product/card.php` lacks QuickView and Compare buttons.
- **Reproduction:** Open QuickView for a variable product; variation dropdowns do not trigger price or stock updates. Inspect catalog card; QuickView and Compare cannot be opened.
- **Expected Behavior:** QuickView handles both simple and variable products; product cards expose Wishlist, QuickView, and Compare triggers.
- **Proposed Fix:** Trigger WooCommerce variation form initialization on modal load; expose consistent action buttons on product cards.
- **Validation Criteria:** Variable product attributes can be chosen in QuickView and added to cart; catalog cards allow QuickView and Compare.

---

### AUD-07: Thank You Template Order Status Handling
- **Severity:** P1 (High)
- **Affected Files:**
  - `woocommerce/checkout/thankyou.php`
- **Evidence:**
  - Line 19 checks `if ( $order->has_status( 'failed' ) )`, and everything else enters the green "از خرید شما سپاسگزاریم!" success state.
- **Reproduction:** Create an order with Cheque or BACS (status `on-hold`) or leave payment pending; the thank-you page displays a green checkmark saying the order was paid and is being processed.
- **Expected Behavior:** Orders in `pending`, `on-hold`, or `cancelled` states display appropriate guidance and status notices, distinguishing true payment completion from waiting states.
- **Proposed Fix:** Differentiate `completed`/`processing` (success) from `on-hold`, `pending`, and `cancelled` with distinct messaging and styling.
- **Validation Criteria:** On-hold order displays instructions without claiming payment was received.

---

### AUD-08: Hardcoded Fabricated Ratings
- **Severity:** P2 (Medium)
- **Affected Files:**
  - `template-parts/product/card.php` (lines 33-38: fallback to `'4.5'`)
  - `woocommerce/content-product.php` (line 32: fallback to `'4.0'`)
  - `woocommerce/single-product/product-tabs.php` (line 209: `max( 4.5, (float) $product->get_average_rating() )`)
- **Evidence:** Products with 0 reviews or unrated status are forced to display 4.0 or 4.5 stars.
- **Reproduction:** Create a new product with zero reviews; the card and single-product view display 4.5 or 4.0 rating stars.
- **Expected Behavior:** Products without reviews display an unrated state or review count of 0 without fabricated star values.
- **Proposed Fix:** Use `$product->get_average_rating()` and `$product->get_review_count()`. If unrated, display neutral unrated state or omit fake stars.
- **Validation Criteria:** An unrated product shows "بدون امتیاز" or neutral indicator; rated products show exact average rating.

---

### AUD-09: Screenshot Integrity Failure in `docs/compare/`
- **Severity:** P1 (High)
- **Affected Files:**
  - `docs/compare/404-1440.png`, `about-1440.png`, `contact-1440.png`, `home-wide-1440.png`, `post-1440...` (MD5: `F5070FE8E9D988B80F4702A9A868A306`)
  - `docs/compare/404-375.png`, `about-375.png`, `contact-375.png`, `home-mobile-375.png`, `post-375...` (MD5: `0B32D43199E0E0B572BFC0EF2CB3B8CC`)
  - `docs/compare/account-1024.png` == `cart-1024.png`
  - `docs/compare/account-1440.png` == `cart-1440.png`
- **Evidence:** 8 files share identical MD5 hash at 1440px; 8 files share identical MD5 hash at 375px.
- **Reproduction:** Run file hash check on `docs/compare/*.png`.
- **Expected Behavior:** Every page screenshot must capture the distinct rendered route and state.
- **Actual Behavior:** The same screenshot was saved under multiple page names.
- **Proposed Fix:** Re-capture all page screenshots using automated Playwright script visiting each distinct route with metadata.
- **Validation Criteria:** Every page screenshot has a unique hash and corresponds to the actual rendered page.

---

### AUD-10: Version Number Discrepancies
- **Severity:** P2 (Medium)
- **Affected Files:**
  - `README.md` (`2.5.0`)
  - `functions.php` (`1.0.2`)
  - `style.css` (`1.0.0`)
  - `package.json` (`1.0.0`)
- **Evidence:** 4 different version declarations exist in the codebase.
- **Proposed Fix:** Harmonize on canonical version `2.5.0` (or `2.5.1`) across all project files.
- **Validation Criteria:** Grep for version string confirms matching version across all metadata files.

---

### AUD-11: Dead Legacy Scripts & Empty Elementor Registration
- **Severity:** P3 (Low)
- **Affected Files:**
  - `assets/js/vendor/kaveh-login-ajax.js`
  - `inc/elementor/class-elementor-init.php`
- **Evidence:**
  - `kaveh-login-ajax.js` invokes SMS actions that don't exist in PHP.
  - `register_widgets()` in `Negar_Shop_Elementor_Init` is completely empty.
- **Proposed Fix:** Isolate or remove dead SMS scripts from global enqueue; document Elementor widget status accurately.
- **Validation Criteria:** No console errors or failed AJAX requests on login forms; clean Elementor bootstrap.
