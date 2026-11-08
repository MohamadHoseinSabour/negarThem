# NegarThem — Audit Baseline & Defect Verification Report (V3)

**Target Branch:** `main`  
**Baseline Git Commit:** `09b57e364f91e7c00e9ee4c8381cc7f51f9b78fa`  
**Backup Tag Created:** `backup/pre-final-frontend-v3` (pushed to `origin/main`)  
**Environment:** Local WordPress / WooCommerce (WAMP Server on Windows, Node.js v24.13.1, Chromium Playwright)  
**Date:** November 2026  

---

## 1. Executive Summary

A comprehensive, deterministic verification pass was performed across the local WooCommerce storefront following the user-provided screenshots and defect reports. The earlier V2 testing suite (which claimed 13/13 passing tests) only validated DOM node presence (`assert(header)`, `assert(swiper)`) rather than computed styles, element dimensions, or visual alignment.

This baseline audit confirms **all reported defects are fully reproducible in the live codebase** with concrete computed CSS and DOM evidence:

1. **Mobile Header Logo Collapse on Shop & Product Pages (P0)**: `.woocommerce img, .woocommerce-page img { height: auto; max-width: 100%; }` in WooCommerce layout CSS overrides `.h-8` (`height: 2rem`), collapsing the SVG logo inside the flex container to `0px × 0px`.
2. **Mobile Hero Slider Content Disappearance & Container Compression (P0)**: Desktop CSS rules for `.landing-nk__carousel` in `assets/css/main.css` (`padding: 81px 140px 81px 80px; max-width: calc(100% - 314px); min-height: 455px;`) lack responsive media queries and override vendor CSS, reducing available slide width on a 375px viewport to exactly `0px` and creating an empty, elongated hourglass container.
3. **Desktop Hero Slider Arrow Displacement (P0)**: Fixed pixel offsets (`right: 48px; left: 21px;`) float the navigation circles inside the banner over product imagery and headlines instead of centering within the decorative crescent notches (`::before` at `right: 0px` and `::after` at `left: 0px`).
4. **Floating Mobile Bottom Navigation Clutter (P1)**: The floating mobile bar currently renders 5 items including "Categories" and "Cart", with red circular counter bubbles (`.negar-cart-count-mobile`, `.negar-wishlist-count`).
5. **Mobile Checkout Step 2 Narrow Input Squashing (P0)**: WooCommerce default layout rules (`.form-row-first, .form-row-last { float: right; width: 47%; }`) combined with nested `.container` wrappers and `p-4` padding compress checkout inputs to a tiny `105px` width on mobile screens.
6. **About Us & Contact Us Generic Template Styling (P1)**: Both pages utilize default Bootstrap 5 classes (`bg-light`, `card`, `bg-danger-subtle`) disconnected from the Kaveh design system (`#e90544`, `f-iran-yekan`, modern soft radius, and elevated cards).
7. **Security Regression: OTP Code Disclosure in AJAX Response (P0)**: `inc/auth-otp.php` leaked `mock_code` containing the raw OTP code whenever SMS dispatch was inactive.

---

## 2. Detailed Baseline Matrix

| # | Route | Viewport | Defect Description | Computed CSS / DOM Evidence | Root Cause |
|---|---|---|---|---|---|
| **D-01** | `/?post_type=product` & `/?p=131` | `375×812` (Mobile) | Logo completely missing between hamburger and cart | `.mobile-logo img` computed width: `0px`, height: `0px` | `.woocommerce-page img` sets `height: auto` which overrides `.h-8` with higher specificity, collapsing SVG `inline-flex` |
| **D-02** | `/` (Home) | `375×812` (Mobile) | Empty red hourglass slider, no product image, title, or CTA | `.landing-nk__carousel` width: `220px`, padding: `81px 140px 81px 80px`, slide width: `0px` | `assets/css/main.css:758` declares desktop rules without `@media (min-width: 992px)`, overriding mobile queries |
| **D-03** | `/` (Home) | `1440×900` (Desktop) | Arrows displaced inward over image & headline | `swiper-button-prev` right: `48px`, `swiper-button-next` left: `21px` | Fixed hardcoded offsets do not align with decorative crescent cutouts at `0px` edges |
| **D-04** | `/` (Sitewide) | `375×812` (Mobile) | Bottom island has 5 items with red counter bubbles | 5 items (`.bottom-bar-item`), `.negar-cart-count-mobile`, `.negar-wishlist-count` | `template-parts/header/mobile-bottom-bar.php` has Categories & Cart actions plus notification badges |
| **D-05** | `/checkout/` | `375×812` (Mobile) | Step 2 form fields shrunk to narrow 105px boxes | `#billing_first_name` width: `105px`, row float: `right`, width: `47%` | WooCommerce `.form-row-first` / `last` 47% width in 1-col grid with nested `.container` wrappers |
| **D-06** | `/about/` & `/contact/` | `375px`, `768px`, `1440px` | Unchanged generic Bootstrap styling | Default `.card`, `.bg-light`, blue `--negar-primary: #2563eb` highlights | Content pages lack Kaveh brand styling and custom editorial hierarchy |
| **D-07** | `/wp-admin/admin-ajax.php` | All | OTP code returned in unauthenticated JSON response | `'mock_code' => $code` in response payload | `inc/auth-otp.php:108` and `assets/js/modals.js:869` exposed and auto-filled mock OTP code |

---

## 3. BEFORE Screenshots Captured

All genuine before screenshots have been captured and saved to `docs/compare/v3/before/`:

| Screenshot File | Target Component | Viewport | Verified MD5 Hash |
|---|---|---|---|
| `header-shop-mobile-375.png` | Shop Mobile Header (Missing Logo) | `375×812` | `5DA526AE5D1BB214376085829EFCF4D3` |
| `header-product-mobile-375.png` | Product Mobile Header (Missing Logo) | `375×812` | `4E9A738819490C9DFC04C1C255E8D14A` |
| `hero-mobile-375.png` | Mobile Hero Slider (Hourglass & Empty) | `375×812` | `BBED690A68A5543F3FD4B4F79AF4BEA7` |
| `hero-desktop-1440.png` | Desktop Hero Slider (Displaced Arrows) | `1440×900` | `3879CD86FD7FD441507120241C812B1C` |
| `hero-desktop-1024.png` | Desktop Hero Slider (Displaced Arrows) | `1024×768` | `61F0F095DF4A22C219F5532F47104432` |
| `bottom-nav-mobile-375.png` | Mobile Bottom Bar (5 items + Badges) | `375×812` | `86D040B1BBE198CE7D1CAED9A1BF3CAC` |
| `checkout-mobile-375.png` | Mobile Checkout Step 2 (Squeezed Inputs) | `375×812` | `A21519AC31BF74B0B1278E94EE405C7A` |
| `about-mobile-375.png` | About Us Mobile Page | `375×812` | `E48FF8CE2BF5D893B3EBD46B38140A45` |
| `about-desktop-1440.png` | About Us Desktop Page | `1440×900` | `94FC3CF949880F17A2703D4039FF0DF0` |
| `contact-mobile-375.png` | Contact Us Mobile Page | `375×812` | `3732866361DEC00E80E673D848BCAD60` |
| `contact-desktop-1440.png` | Contact Us Desktop Page | `1440×900` | `D7F1E613EBC6E544FD807ECCA00D8F6D` |

---

## 4. Phase 1 Security Fix Summary

- **Removed `mock_code` disclosure:** Eliminated `'mock_code' => $code` from `inc/auth-otp.php` and deleted client auto-fill in `assets/js/modals.js`.
- **Honest failure reporting:** If SMS delivery is not successful and no isolated test transport is active, the endpoint responds with an honest Persian error (`سامانه پیامکی در دسترس نمی‌باشد...`).
- **Cryptographic storage:** Stored verification codes are hashed using `wp_hash()` in transient storage, and verified using `hash_equals()` to prevent timing side-channels.
- **Account hijacking prevention:** Verified that user login lookup strictly checks matching `billing_phone` metadata to prevent unverified account takeovers.
- **Automated Regression Suite:** Created `tests/security-otp-disclosure.test.js` validating zero code disclosure, invalid phone handling, and wrong code rejection.
