# Implementation Plan: 001 Foundation

**Feature Branch**: `001-featurename-foundation`  
**Spec Reference**: `specs/001-featurename-foundation/spec.md`  
**Coverage Matrix IDs**: `FND-01`, `FND-02`, `FND-03`, `FND-04`  

---

## 1. Technical Architecture & File Map

```
negarThem/
├── style.css                 # Theme declaration header & base styling
├── index.php                 # Fallback template with semantic HTML5
├── header.php                # Universal HTML <head>, body tag, skip link
├── footer.php                # Universal closing tags, wp_footer()
├── functions.php             # Core bootstrap & modular loaders
├── theme.json                # V3 design tokens, palette & typography
├── rtl.css                   # WordPress standard RTL overrides
├── inc/
│   ├── setup.php             # Theme features setup & i18n
│   ├── enqueue.php           # Script & style loading + localization
│   ├── woocommerce.php       # WooCommerce compatibility hooks & wrappers
│   └── template-tags.php     # Helper utilities & safe escaping functions
└── assets/
    ├── css/
    │   └── main.css          # Core CSS variables, typography & reset
    └── js/
        └── main.js           # Vanilla JS core namespace (window.NegarShop)
```

---

## 2. Technical Decisions & Standards

### 2.1. Hybrid Architecture (`theme.json` + Classic PHP Templates)
- WordPress 6.0+ / 7.x supports `theme.json` inside classic themes.
- We declare standard design tokens in `theme.json` (colors, spacing, typography scales).
- WordPress automatically compiles these into standard CSS custom properties (`--wp--preset--color--primary`, etc.) which our custom stylesheets consume.

### 2.2. Font Delivery & Typography
- Primary font: **Vazirmatn** via Google Fonts with `display=swap` and preconnect hints, with local font-face fallback.
- CSS font-family stack:
  ```css
  --font-primary: 'Vazirmatn', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  ```
- If local IRANYekan / IRANSans files are placed into `assets/fonts/`, a dedicated font loader will automatically prioritize them.

### 2.3. WooCommerce Integration
- Explicit declaration of WooCommerce features in `inc/woocommerce.php`:
  - `woocommerce`
  - `wc-product-gallery-zoom`
  - `wc-product-gallery-lightbox`
  - `wc-product-gallery-slider`
- Replacement of default WooCommerce wrappers (`woocommerce_output_content_wrapper` and `woocommerce_output_content_wrapper_end`) with semantic `<main id="primary" class="site-main">` and `<div class="container">`.

### 2.4. Security & Sanitization
- Prefix all theme functions and constants with `negar_shop_` / `NEGAR_SHOP_`.
- Enforce strict escaping on every template tag.
- Localize script object `negarShopParams` with:
  - `ajaxUrl`: `admin_url('admin-ajax.php')`
  - `nonce`: `wp_create_nonce('negar-shop-nonce')`
  - `isRtl`: `is_rtl()`

---

## 3. Implementation Steps & Validation

1. **Root Files & Theme Setup** (`style.css`, `functions.php`, `theme.json`, `inc/setup.php`):
   - Register theme header, text domain, title-tag, post-thumbnails, HTML5 markup support.
   - Validation: `wp theme is-active negarThem` or `wp theme activate negarThem`.
2. **Asset Pipeline** (`inc/enqueue.php`, `assets/css/main.css`, `assets/js/main.js`):
   - Enqueue main styles, Vazirmatn font, and vanilla JS main bundle.
   - Validation: Inspect page head; verify styles loaded and `NegarShop` object exists in browser console.
3. **WooCommerce Bridge** (`inc/woocommerce.php`):
   - Hook into `after_setup_theme` and WooCommerce content hooks.
   - Validation: Verify WooCommerce recognizes theme support without notices.
4. **HTML5 Semantic Skeleton** (`header.php`, `footer.php`, `index.php`):
   - Implement clean semantic markup with `language_attributes()`, `wp_head()`, `body_class()`, `wp_footer()`.
   - Validation: HTML5 validator & zero PHP notices in `WP_DEBUG`.
