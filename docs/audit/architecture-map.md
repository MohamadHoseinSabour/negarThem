# Architecture Map — Negar Shop WordPress Theme

**Theme Name:** Negar Shop (نگار شاپ)  
**Text Domain:** `negar-shop`  
**Architecture Model:** Hybrid Classic/Block-aware WordPress & WooCommerce Theme  
**Target Reference:** Kaveh Shop Demo 43 (`https://kaveh.moeinwp.com/43/`)

---

## 1. PHP Architecture & Bootstrapping

The theme bootstrap starts at `functions.php`, loading modular components from `inc/`:

```
functions.php
├── inc/setup.php                 (Theme supports, nav menus, widget sidebars)
├── inc/enqueue.php               (Scripts, stylesheets, Google/local fonts, localization)
├── inc/template-tags.php         (Escaping helpers, discount badges, price formatters)
├── inc/mega-menu.php             (Custom Walker: Negar_Shop_Mega_Menu_Walker)
├── inc/ajax-search.php           (Live product search REST/AJAX endpoint)
├── inc/elementor/class-elementor-init.php (Elementor category & widget bootstrap)
├── inc/customizer.php            (WP Theme Customizer settings, controls, and colors)
├── inc/seo-schema.php            (OpenGraph meta and JSON-LD structured data)
└── WooCommerce Modules (loaded conditionally when class_exists('WooCommerce')):
    ├── inc/woocommerce.php       (Cart fragments, body classes, wrapper hooks)
    ├── inc/shop.php              (Breadcrumbs, shop filters, layout helpers)
    ├── inc/shop-ajax.php         (AJAX shop filtering & taxonomy queries)
    ├── inc/single-product.php    (Product gallery, tabs, delivery perks)
    └── inc/modals-ajax.php       (QuickView, Wishlist drawer, Compare modal)
```

---

## 2. WordPress Template Hierarchy Mapping

| Route / Context | Template File | Primary Template Parts |
|---|---|---|
| **Front Page** | `front-page.php` | `template-parts/home/*` (hero-slider, categories, flash-deals, etc.) |
| **Blog Index / Archive** | `archive.php` | `template-parts/blog/card.php`, `sidebar-blog.php` |
| **Single Blog Post** | `single.php` | `comments.php`, `template-parts/blog/author-box.php` |
| **Static Pages** | `page.php` | Core content output with container |
| **About Us Page** | `page-templates/page-about.php` | Brand story, 4 counters, core values |
| **Contact Us Page** | `page-templates/page-contact.php` | Contact cards, message form, FAQ accordion |
| **Search Results** | `search.php` | Search header, product loop, empty state fallback |
| **404 Not Found** | `404.php` | Error visual, search recovery form, quick category links |
| **Fallback Index** | `index.php` | Standard WordPress post loop |

---

## 3. WooCommerce Template Overrides

All overrides reside in `woocommerce/` following official WooCommerce standards:

| WooCommerce Core Template | Theme Override | Key Customizations / Demo 43 Matching |
|---|---|---|
| `archive-product.php` | `woocommerce/archive-product.php` | 2-column layout with sticky sidebar and `category-nk` toolbar |
| `content-product.php` | `woocommerce/content-product.php` | `product-cart-thirty-two-nk` card style with discount badge & add-to-cart |
| `content-single-product.php` | `woocommerce/content-single-product.php` | Style 5 (`detail-product-7`): gallery, perks, tabs, sticky mobile cart |
| `cart/cart.php` | `woocommerce/cart/cart.php` | 2-column modern cart table with AJAX quantity steppers & totals box |
| `cart/cart-totals.php` | `woocommerce/cart/cart-totals.php` | Sticky order invoice box with coupon code form |
| `cart/cart-empty.php` | `woocommerce/cart/cart-empty.php` | Empty cart graphic, shopping suggestions |
| `checkout/form-checkout.php` | `woocommerce/checkout/form-checkout.php` | 2-step checkout layout with review order in sidebar |
| `checkout/review-order.php` | `woocommerce/checkout/review-order.php` | Items list with thumbnails, shipping selection, payment methods |
| `checkout/thankyou.php` | `woocommerce/checkout/thankyou.php` | Tracking ribbon (number, date, total, method) and status indicator |
| `myaccount/my-account.php` | `woocommerce/myaccount/my-account.php` | Dashboard grid with sidebar navigation |
| `myaccount/navigation.php` | `woocommerce/myaccount/navigation.php` | SVG icon tab navigation matching Demo 43 |
| `myaccount/dashboard.php` | `woocommerce/myaccount/dashboard.php` | Welcome box, quick order stats, address summary |
| `myaccount/orders.php` | `woocommerce/myaccount/orders.php` | Responsive orders table with payment status badges |
| `myaccount/form-login.php` | `woocommerce/myaccount/form-login.php` | `auth__page__twelve` tabbed login and registration form |

---

## 4. Frontend Asset Dependency Graph

### CSS Architecture
```
style.css (Theme metadata & base reset)
├── assets/css/vendor/bootstrap-grid.rtl.min.css (Bootstrap 5 grid system)
├── assets/css/vendor/icomoon.css                (Custom icon font)
├── assets/css/vendor/swiper-bundle.min.css      (Slider styles)
├── assets/css/vendor/nouislider.min.css         (Price slider styles)
├── assets/css/vendor/notiflix.css               (Notifications & loading)
├── assets/css/main.css                          (Vazirmatn font, design tokens, utility classes)
├── assets/css/header-footer.css                 (Top bar, sticky header, mega menu, footer)
├── assets/css/home.css                          (Home sections & sliders)
├── assets/css/shop.css                          (Shop catalog, filters sidebar, mobile drawer)
├── assets/css/single-product.css                (Single product style 5, gallery, tabs)
├── assets/css/checkout-cart.css                 (Cart & checkout funnel, auth forms)
├── assets/css/content-pages.css                 (Blog, single post, about, contact)
└── assets/css/modals.css                        (QuickView, wishlist drawer, compare modal)
```

### JavaScript Architecture
```
jQuery 3.x (WordPress bundled)
├── assets/js/vendor/swiper-bundle.min.js        (Carousels & sliders)
├── assets/js/vendor/notiflix.js                 (Toast notifications & blocking)
├── assets/js/main.js                            (Global initializers & common helpers)
├── assets/js/vendor/kavehmegamenu.js            (Multi-level navigation)
├── assets/js/header-footer.js                   (Live search, sticky header, back to top)
├── assets/js/modals.js                          (QuickView, Wishlist, Compare modals)
├── Conditional Scripts:
│   ├── assets/js/home.js                        (Home sliders & flash deals countdown)
│   ├── assets/js/vendor/nouislider.min.js       (Price slider on shop/archives)
│   ├── assets/js/vendor/kaveh-ajax-shop-method.js (Shop filter AJAX engine)
│   ├── assets/js/shop.js                        (Shop mobile filter drawer, sort dropdown)
│   ├── assets/js/vendor/jquery.magnify.js       (Product gallery zoom)
│   ├── assets/js/single-product.js              (Gallery thumbnails, tabs, sticky mobile bar)
│   └── assets/js/checkout-cart.js               (Cart AJAX, quantity steppers, password toggle)
```

---

## 5. Global JavaScript State & Namespaces

| Namespace / Variable | Source Script | Scope | Contents / Purpose |
|---|---|---|---|
| `window.negarShopParams` | `inc/enqueue.php` | Sitewide | `ajaxUrl`, `nonce`, `i18n`, `cartUrl`, `themeUri` |
| `window.kavehShopData` | `inc/enqueue.php` | Sitewide / Shop | `ajaxurl`, `highestPrice`, `lowestPrice`, `currency_code`, `archiveContext` |
| `window.negarToast` | `assets/js/modals.js` | Sitewide | Canonical toast adapter: `(message, type, duration)` |
| `localStorage.negar_wishlist` | `assets/js/modals.js` | Browser Client | Array of product IDs stored in customer wishlist |
| `localStorage.negar_compare` | `assets/js/modals.js` | Browser Client | Array of product IDs selected for product comparison |

---

## 6. AJAX Endpoints & Actions Map

| AJAX Action Name | Caller Script | PHP Handler Function | Security / Nonce | Description |
|---|---|---|---|---|
| `negar_shop_ajax_search` | `assets/js/header-footer.js` | `negar_shop_ajax_search_callback()` | `negar_shop_nonce` | Live product search with category scoping |
| `kaveh_shop_filters` | `assets/js/vendor/kaveh-ajax-shop-method.js` | `negar_shop_kaveh_ajax_shop_filters()` | `kaveh_shop_filter_nonce` | Shop archive multi-attribute & price filter |
| `negar_shop_quickview` | `assets/js/modals.js` | `negar_shop_ajax_quickview()` | `negar_shop_nonce` | Fetches product HTML for QuickView modal |
| `negar_shop_get_wishlist_cards` | `assets/js/modals.js` | `negar_shop_ajax_get_wishlist_cards()` | `negar_shop_nonce` | Renders wishlist drawer product items |
| `negar_shop_get_compare_data` | `assets/js/modals.js` | `negar_shop_ajax_get_compare_data()` | `negar_shop_nonce` | Renders specifications table for comparison |
| `wc_ajax=add_to_cart` / `negar_add_to_cart` | `assets/js/vendor/kaveh-ajax-cart.js` | Native WC / `inc/woocommerce.php` | WC nonce | Adds simple/variable product to cart with fragments |
| `wc_ajax=get_refreshed_fragments` | Native WooCommerce | `WC_AJAX::get_refreshed_fragments()` | WC session | Synchronizes mini-cart & header count |

---

## 7. Third-Party Libraries & Licensing Audit

| Asset / Library | Path | Bundled Version | License | Verification Notes |
|---|---|---|---|---|
| **Vazirmatn Font** | `assets/fonts/vazirmatn/` | v33.003 | OFL-1.1 | Open-source Persian font by Saber Rastikerdar |
| **Swiper** | `assets/js/vendor/swiper-bundle.min.js` | v11.0.5 | MIT | Standard responsive carousel |
| **Notiflix** | `assets/js/vendor/notiflix.js` | v3.2.7 | MIT | Toast notification and block library |
| **NoUiSlider** | `assets/js/vendor/nouislider.min.js` | v15.7.1 | MIT | Lightweight range slider |
| **jQuery Magnify** | `assets/js/vendor/jquery.magnify.js` | v2.3.3 | MIT | Image magnification plugin |
| **Bootstrap Grid** | `assets/css/vendor/bootstrap-grid.rtl.min.css` | v5.3.2 | MIT | RTL-adapted CSS grid system |
