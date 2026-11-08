# Master Audit V2 — Functional Test Matrix

**Project:** Negar Theming & E-Commerce Platform (Negar Shop)  
**Repository:** `https://github.com/MohamadHoseinSabour/negarThem`  
**Working Branch:** `fix/ui-ux-functional-hardening-v2`  
**Test Suite:** `tests/e2e-test.js` (Playwright 1.65.0)  
**Test Runner:** `npm test`  
**Date Executed:** 2026-11-08  
**Result:** **13 Passed, 0 Failed, 0 Flaky** (100% Pass Rate)

---

## 1. Reproduction Commands

To reproduce the complete test suite locally:

```bash
# 1. Run full PHP syntax linter across all 115 theme files + Playwright E2E suite:
npm test

# 2. Or run only the Playwright E2E suite directly:
node tests/e2e-test.js

# 3. Capture all 36 genuine responsive and modal screenshots with MD5 verification:
node scripts/capture-genuine-screenshots.js
```

---

## 2. Comprehensive Test Scenario Matrix

| # | Test Scenario Name | Covered Scope / Requirement | Concrete Assertions Executed | Status |
|:---|:---|:---|:---|:---|
| **1** | **Homepage HTTP & Title** | Server availability, WordPress rendering, document `<title>` | - HTTP status equals `200`<br>- Page `<title>` contains `"نگار"` | **PASSED** |
| **2** | **Homepage Responsiveness (0px Overflow)** | Multi-breakpoint mobile/tablet/desktop verification | Iterates viewports `[320, 375, 430, 768, 1024, 1440]`:<br>- `scrollWidth <= innerWidth`<br>- Difference equals 0px (no horizontal overflow) | **PASSED** |
| **3** | **Hero Slider Scoped Navigation** | Section 1 Swiper initialization, runtime class cleanliness | - `.landing-nk__carousel` exists<br>- Clean markup: no `swiper-slide-duplicate` baked into server HTML<br>- Navigation buttons scoped to `.landing-nk__carousel .swiper-button-next/prev`<br>- Clicking arrow advances active slide | **PASSED** |
| **4** | **Header Category Search Dropdown** | Search bar UX, dynamic category selection, hidden input | - `.category-menu-btn` or `#search-cat-toggle` exists<br>- Hidden input `input[name="product_cat"]` exists<br>- Clicking toggle opens `.search-category-dropdown`<br>- Selecting category updates hidden input value and button label | **PASSED** |
| **5** | **Customer Phone OTP Modal Flow** | Customer authentication, Iranian phone normalization, OTP step 1 & 2 | - Clicking `.negar-login-trigger` opens `#negar-login-otp-modal`<br>- Modal becomes visible (`display !== 'none'`)<br>- Entering phone `09123456789` triggers `negar_send_otp`<br>- Step 2 OTP inputs become visible with 120s timer<br>- Submitting OTP verifies user session via `negar_verify_otp` | **PASSED** |
| **6** | **Cart Notification Deduplication** | Single add-to-cart toast notification, action-based debounce | - Fires multiple rapid add-to-cart notifications with `actionKey: 'cart_add'`<br>- Asserts only 1 active toast is displayed within 1800ms cooldown window<br>- Second toast is cleanly suppressed | **PASSED** |
| **7** | **Single Product Tabs (No Accordion Collision)** | Tab switching reliability, removal of nested Bootstrap collapse | - Evaluates `/?p=131`<br>- Checks tabs: Specifications (`#pills-profile-tab`), Reviews (`#pills-contact-tab`), Q&A (`#pills-faq-tab`)<br>- Asserts no conflicting `data-bs-toggle="collapse"` in tab headers<br>- Clicking tab switches active pane accurately | **PASSED** |
| **8** | **Honest Product Reviews Stats** | Elimination of fake rating progress bars (`85%`, `90%`, `4.5`) | - Evaluates reviews container in `#pills-contact`<br>- Asserts absence of hardcoded static rating bars<br>- Verifies real review count and honest empty state when reviews == 0 | **PASSED** |
| **9** | **Real Product Q&A Persistence** | Elimination of fake questions ("علیرضا", "مهدی"), real DB persistence | - Asserts absence of fake hardcoded question elements<br>- Submits question via AJAX `negar_submit_product_question`<br>- Verifies question is stored in `wp_comments` (`comment_type = 'product_qa'`)<br>- Verifies AJAX voting endpoint `negar_vote_product_qa` | **PASSED** |
| **10** | **Sticky Purchase Bar vs. Bottom Bar** | Mobile UI collision elimination on single product pages | - Evaluates single product page on 375px viewport<br>- Asserts `.kav-single-product-bottom-nav-price` is rendered<br>- Asserts `.negar-mobile-bottom-bar` is hidden (`display: none`) on `body.single-product` | **PASSED** |
| **11** | **Native Contact Form AJAX Submission** | Contact page functionality, nonce verification, persistence | - Evaluates `/contact/`<br>- Fills contact form (name, email, subject, message)<br>- Submits form via AJAX `negar_submit_contact_form`<br>- Verifies success response, toast notification, and post creation (`negar_inquiry`) | **PASSED** |
| **12** | **Cart & Checkout Visual Continuity** | Checkout styling, card tokens, seeded cart stability | - Seeds cart with product `/?add-to-cart=131`<br>- Visits `/checkout/`<br>- Asserts checkout form is rendered without redirecting to empty cart<br>- Verifies shared design tokens (`border-radius`, typography, button classes) | **PASSED** |
| **13** | **404 Responsive Error Page** | Error handling, theme branding, responsive layout | - Visits non-existent route (`/nonexistentproductqueryxyz12345`)<br>- Asserts 404 template renders gracefully with search form and return home button<br>- Verifies 0px horizontal overflow | **PASSED** |

---

## 3. Static Analysis & Lint Verification

In addition to the 13 automated Playwright browser tests, static syntax verification was performed across all 115 PHP files in the theme:

```text
Linting 115 PHP files...
115/115 PHP files passed syntax check.
```

No syntax errors, deprecated hook calls, or unclosed tags were detected.
