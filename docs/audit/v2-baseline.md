# Master Audit V2 — Baseline Report

**Project:** Negar Theming & E-Commerce Platform (Negar Shop)  
**Repository:** `https://github.com/MohamadHoseinSabour/negarThem`  
**Base Commit (v1 Audit Finish):** `d4221319ef4c4ad71374c2bc442b9095494aacad`  
**Working Branch:** `fix/ui-ux-functional-hardening-v2`  
**Benchmark Reference:** Kaveh Demo 43 (`https://kaveh.moeinwp.com/43/`)  
**Audit Date:** 2026-11-08  
**Environment:** WordPress 7.1.4, WooCommerce 10.5.2, PHP 8.2.29, Node.js v20+, Playwright 1.65.0  

---

## 1. Executive Summary

A thorough, evidence-driven re-audit of the repository following the initial hardening pass (`d422131`) reveals that while previous backend AJAX contract fixes (shop query arguments, cart fragment synchronization, and packaging fixes) resolved major architectural regressions, several critical UI/UX, responsiveness, authenticity, and micro-interaction defects remain active in the theme codebase.

In particular, multiple components violate project integrity rules by relying on copied runtime Swiper DOM states, hardcoded dimensions, placeholder/fabricated feedback mechanisms (`alert()`), mock reviews/Q&A data, and conflicting overlapping fixed navigation bars.

This baseline report catalogs the exact state across all 11 critical focus areas before starting the v2 implementation pass.

---

## 2. Detailed Findings by Area

### 2.1 Hero Slider & Flash Deal Card (Homepage Section 1)
- **Status:** **BROKEN ON MOBILE & ARCHITECTURALLY DEFECTIVE**
- **Affected Files:**
  - `template-parts/home/hero-slider.php`
  - `assets/js/home.js`
- **Evidence:**
  - `hero-slider.php` lines 40–155 contain client-runtime Swiper DOM classes directly baked into PHP server markup: `swiper-initialized`, `swiper-slide-duplicate`, `swiper-slide-active`, `swiper-slide-prev`, `swiper-slide-next`, `swiper-rtl`.
  - Slides contain hardcoded inline styles: `style="width: 747px; transition-duration: 0ms; opacity: 0; transform: translate3d(...)`.
  - Duplicate slides were manually duplicated 4 times inside `.swiper-wrapper`.
  - On mobile screens (< 768px), the inline `width: 747px` forces the viewport outward, causing severe horizontal overflow and content clipping.
  - In `assets/js/home.js`, `initKavehSwipers()` binds navigation using global selector `.swiper-button-prev` / `.swiper-button-next` without scoping to `.landing-nk__carousel`, risking cross-slider collision.
  - An orphaned dead function `initHeroSlider()` looks for `.hero-slider-container` (which does not exist in the DOM).

### 2.2 Homepage Responsiveness Across Breakpoints (320px–1440px)
- **Status:** **PARTIALLY COMPLIANT / MOBILE OVERFLOW ISSUES**
- **Affected Files:**
  - `template-parts/home/*.php`
  - `assets/css/vendor/landingnkkaveh.css`
  - `assets/css/home.css`
- **Evidence:**
  - 320px–430px viewports experience horizontal scrolling triggered by hardcoded widths in the hero slider, unconstrained flex containers in promotional banners, and category bubbles.
  - Flash deals countdown timer (`.negar-countdown`) lacks responsive font scaling on extra small viewports (320px).
  - The left flash-deal box (`.landing-nk__products`) overlaps or breaks flex wrap at 992px–1199px transition.

### 2.3 Header, Mobile Header, Search & Navigation
- **Status:** **DEFECTIVE / INCOMPLETE**
- **Affected Files:**
  - `template-parts/header/main-header.php`
  - `template-parts/header/mobile-header.php`
  - `template-parts/header/mobile-bottom-bar.php`
  - `assets/js/header-footer.js`
- **Evidence:**
  - **Category Dropdown Missing:** In `main-header.php` line 36, `.category-menu-btn` exists as a visual button but contains no attached dropdown menu and no `<input type="hidden" name="product_cat">`. Clicking does nothing; category-scoped search is non-functional.
  - **Mobile Header Notch Overlap:** `mobile-header.php` uses `sticky top-0` without `env(safe-area-inset-top)` support.
  - **Inconsistent Branding:** Desktop header renders the graphic SVG logo from `/kaveh-demo/kaveh-demo/general/logo.svg`, while mobile header falls back to `the_custom_logo()` or text `bloginfo('name')`.
  - **Edge-to-Edge Rigid Mobile Nav:** `mobile-bottom-bar.php` uses `fixed bottom-0 left-0 right-0 w-full` with no margins, clipping against the iPhone home indicator and lacking the floating island aesthetic.
  - **Customer Authentication Flow:** Header user button links directly to `myaccount` or `wp_login_url()` instead of opening an in-page modal.

### 2.4 Customer Authentication (Phone OTP Modal)
- **Status:** **MISSING ENTIRELY**
- **Affected Files:**
  - `inc/auth-otp.php` (does not exist)
  - `template-parts/modals/login-otp-modal.php` (does not exist)
  - `assets/js/modals.js`
- **Evidence:**
  - No phone OTP login modal exists in the theme.
  - Customers clicking "ورود / ثبت‌نام" get redirected to WooCommerce's default `myaccount` page requiring username/email and password.
  - No Persian digit normalization, SMS provider integration, or mock authentication backend is in place.

### 2.5 Duplicate Cart Notifications
- **Status:** **CONFIRMED DEFECT**
- **Affected Files:**
  - `assets/js/vendor/kaveh-ajax-cart.js`
  - `assets/js/modals.js`
- **Evidence:**
  - In `kaveh-ajax-cart.js` line 80: `jQuery(document.body).trigger('added_to_cart', ...)` is fired on successful AJAX add-to-cart.
  - Line 111 of `kaveh-ajax-cart.js` simultaneously executes `notifySuccess(kavehShopData.product_added_to_cart)` -> calling `window.negarToast()`.
  - Meanwhile, `assets/js/modals.js` line 672 listens to `added_to_cart` and calls `window.negarToast('محصول به سبد خرید اضافه شد', 'success')`.
  - Every add-to-cart event displays two identical stacked toast notifications.

### 2.6 Single Product Tabs, Reviews & Questions/Answers
- **Status:** **CRITICAL DEFECT — FABRICATED DATA & CONFLICTING JS ACCORDION**
- **Affected Files:**
  - `woocommerce/single-product/product-tabs.php`
- **Evidence:**
  - **Conflicting Accordion:** Every tab item contains `<div data-bs-toggle="collapse" data-bs-target="#info-product">` and `<div class="collapse show">`, conflicting with the parent tab switching mechanism.
  - **Fabricated Progress Bars:** Lines 230–250 render hardcoded static rating bars (`85%`, `90%`, `4,5`, `4,7`) regardless of real product reviews.
  - **Fabricated Q&A Data:** Lines 330–364 explicitly inject hardcoded mock questions ("علیرضا", "مهدی") when no questions exist, directly violating project rules.
  - No backend persistence exists for customer questions and answers.

### 2.7 Sticky Purchase Panel & Mobile Bar Collision
- **Status:** **HIGH COLLISION DEFECT**
- **Affected Files:**
  - `woocommerce/single-product/sticky-bottom-cart.php`
  - `template-parts/header/mobile-bottom-bar.php`
  - `woocommerce/content-single-product.php`
- **Evidence:**
  - `sticky-bottom-cart.php` has `position-fixed bottom-0 end-0 w-100 z-index: 1000`.
  - `mobile-bottom-bar.php` has `fixed bottom-0 left-0 right-0 z-40`.
  - On mobile single-product views, both bars sit simultaneously on `bottom: 0`, completely obscuring each other and preventing users from tapping either the bottom navigation or the add-to-cart CTA.
  - In `content-single-product.php` lines 316 & 335, quick modals use inline `onclick="alert('...')"` and `onsubmit="alert('...')"` placeholders.

### 2.8 Cart -> Checkout Visual & Functional Continuity
- **Status:** **PARTIAL**
- **Affected Files:**
  - `woocommerce/cart/cart.php`
  - `woocommerce/checkout/form-checkout.php`
  - `tests/e2e-test.js`
- **Evidence:**
  - Automated tests testing `/checkout/` fail or produce false positives because tests run with an empty cart, triggering WooCommerce's automatic redirect to `/cart/`.
  - Border radius, step indicators, and form field card styling differ slightly between cart and checkout pages.

### 2.9 Content Pages: About Us & Contact Us
- **Status:** **DEFECTIVE / PLACEHOLDER JS ALERTS**
- **Affected Files:**
  - `page-templates/page-contact.php`
  - `page-templates/page-about.php`
- **Evidence:**
  - In `page-contact.php` line 102, the contact form uses `onsubmit="event.preventDefault(); alert('پیام شما با موفقیت ثبت شد...'); this.reset();"`.
  - No AJAX endpoint, nonce check, field validation, or submission logging/emailing exists.
  - `page-about.php` contains hardcoded stats numbers without dynamic fallback options.

### 2.10 Micro-Interactions & Accessibility
- **Status:** **NEEDS STANDARDIZATION**
- **Affected Files:**
  - `assets/js/header-footer.js`
  - `assets/js/modals.js`
- **Evidence:**
  - Dropdown menus lack ESC key listener and outside-click trap.
  - Focus rings on search inputs and buttons lack uniform design token colors.
  - ARIA attributes missing on category filter dropdown and modal dialogs.

---

## 3. Prioritized Implementation Plan

| Phase | Description | Target Files |
|-------|-------------|--------------|
| **Step 1** | Baseline Audit Documentation | `docs/audit/v2-baseline.md` |
| **Step 2** | Rebuild Homepage Hero Slider | `template-parts/home/hero-slider.php`, `assets/js/home.js`, `assets/css/vendor/landingnkkaveh.css` |
| **Step 3** | Homepage Responsiveness across Breakpoints | `template-parts/home/*.php`, `assets/css/home.css` |
| **Step 4** | Header, Search Category Dropdown & Floating Mobile Navigation | `template-parts/header/*.php`, `assets/js/header-footer.js`, `assets/css/header-footer.css` |
| **Step 5** | Customer Phone OTP Authentication System | `inc/auth-otp.php`, `template-parts/modals/login-otp-modal.php`, `assets/js/modals.js` |
| **Step 6** | Eliminate Duplicate Cart Notifications | `assets/js/vendor/kaveh-ajax-cart.js`, `assets/js/modals.js` |
| **Step 7** | Rebuild Single-Product Tabs, Reviews & Real Q&A | `woocommerce/single-product/product-tabs.php`, `inc/product-qa.php`, `assets/js/single-product.js` |
| **Step 8** | Sticky Purchase Panel & Mobile Collision Prevention | `woocommerce/single-product/sticky-bottom-cart.php`, `woocommerce/content-single-product.php` |
| **Step 9** | Cart -> Checkout Continuity & Seeded E2E Tests | `woocommerce/checkout/form-checkout.php`, `tests/e2e-test.js` |
| **Step 10** | Content Pages & Real Contact Form Handler | `page-templates/page-contact.php`, `inc/contact-form.php`, `assets/js/contact.js` |
| **Step 11** | Full Verification, Matrices, Zip Build & Git Push | `docs/audit/v2-*`, `dist/negar-shop.zip` |
