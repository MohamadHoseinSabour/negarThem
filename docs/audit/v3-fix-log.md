# NegarThem — V3 Frontend Pass Fix Log

## Overview
This log details all architectural, visual, responsive, and functional fixes implemented during the V3 final frontend stabilization pass. All work was performed directly on `main` under non-destructive baseline backup tag `backup/pre-final-frontend-v3`.

---

## Commit & Change History

### 1. Phase 1: Security Hardening & Defect Baseline
- **Commit**: `23c1274` (`fix(security): eliminate OTP mock_code disclosure and capture Phase 1 baseline defects`)
- **Key Changes**:
  - `inc/auth-otp.php`: Removed `mock_code` property from `wp_send_json_success()`. OTP verification codes are now hashed into transients and never leaked to browser network responses or client console logs.
  - `assets/js/modals.js`: Removed `res.data.mock_code` auto-fill logic.
  - `docs/audit/v3-baseline.md`: Documented full initial status and reproduction of all user-reported visual defects.
  - Created initial before screenshot suite in `docs/compare/v3/before/`.

### 2. Phase 2: Mobile Header SVG Logo Restoration
- **Commit**: `9a6da72` (`fix(header): restore mobile logo contract and prevent SVG collapse on WooCommerce pages`)
- **Key Changes**:
  - `assets/css/header-footer.css`: Enforced `.negar-mobile-header .mobile-logo img` and `.h-8` with explicit dimensions (`height: 32px !important; max-height: 32px !important; width: auto !important; min-width: 60px;`).
  - `template-parts/header/mobile-header.php`: Bound explicit width (`120`) and height (`32`) attributes and inline fallback styles to prevent zero-width SVG collapsing when WooCommerce layouts reset image dimensions.
  - Verified on Shop Archive (`/?post_type=product`) and Single Product (`/?p=131`).

### 3. Phase 3: Mobile Hero Slider & Desktop Crescent Arrows
- **Commit**: `17a5912` (`fix(hero): stabilize mobile layout dimensions, ensure slide content visibility, and align desktop crescent arrows`)
- **Key Changes**:
  - `template-parts/home/hero-slider.php`: Relocated desktop crescent Swiper navigation buttons (`.swiper-button-prev`, `.swiper-button-next`) to direct children of `.landing-nk__carousel`, allowing precise alignment with SVG crescent cutouts.
  - `assets/css/home.css`:
    - Desktop (>= 1024px): Positioned next arrow at `left: 4px` and prev arrow at `right: 8px` centered on crescent notches.
    - Mobile (320px–430px): Overhauled `.landing-nk__carousel` container and slide layout. Eliminated elongated hourglass collapse, set flex row layout with `min-height: 180px`, rendered slide image at 133px x 121px, and prevented text or button clipping. Guaranteed 0px horizontal overflow across all mobile viewports.

### 4. Phase 4: Floating Mobile Bottom Navigation Simplification
- **Commit**: `6f5a269` (`fix(mobile-nav): simplify bottom island to 3 items and remove count badges`)
- **Key Changes**:
  - `template-parts/header/mobile-bottom-bar.php`: Redesigned floating mobile bar into an elegant 3-item island: Home (خانه), Wishlist (علاقه‌مندی‌ها), and Account/Login (حساب کاربری / ورود).
  - Completely removed Categories and Cart from the floating bottom island.
  - Completely removed red count badges (`.negar-cart-count-mobile`, `.negar-wishlist-count`) from the bottom island.
  - Retained cart count badge exclusively in the top mobile header (`template-parts/header/mobile-header.php`).
  - Maintained conditional hiding on Single Product views (`is_product()`) to ensure no visual overlap with the sticky purchase bar.

### 5. Phase 5: Checkout Step 2 Redesign & Mobile Input Widths
- **Commit**: `bddc28f` (`fix(checkout): redesign step 2 layout, resolve mobile input squashing, and harmonize styling with brand tokens`)
- **Key Changes**:
  - `assets/css/checkout-cart.css`:
    - Enforced `.woocommerce-checkout .form-row { width: 100% !important; float: none !important; }` on viewports < 640px. Eliminated the 105px squashing caused by WooCommerce RTL floats (`.form-row-first`, `.form-row-last`). Inputs now expand to full card width (265px on 375px viewport).
    - Redesigned 3-step checkout indicator with Persian numerals (۲, ۳) and brand colors (`#e90544`).
    - Styled coupon toggle and WooCommerce notices into clean cards with pink accents.
    - Restyled Select2 state/city dropdowns to 46px height with 12px border radius.
    - Restyled payment gateway cards (BACS, COD) with custom radio badges and soft active borders.
    - Transformed Place Order button into a full-width high-converting CTA with brand red gradient.

### 6. Phase 6: About Us & Contact Us Brand Harmonization
- **Commit**: `428171a` (`fix(pages): redesign About Us and Contact Us to match Kaveh brand identity and retain real AJAX form`)
- **Key Changes**:
  - `page-templates/page-about.php`: Built editorial hero with Kaveh badge, 4 feature pillars (`negar-pillar-card`), brand narrative card, and 4 achievement metric boxes.
  - `page-templates/page-contact.php`: Built 4 contact info cards (phone, email, address, working hours), responsive map container, and stylized card-based contact form.
  - `assets/css/content-pages.css`: Added responsive styles, soft shadow cards, hover lift animations, and mobile optimizations for both pages.
  - Retained real AJAX contact form handler (`negar_ajax_contact_submit`) with nonce verification and storage in `negar_contact_submissions`.

### 7. Phase 7: Automated E2E Regression Suite & Documentation
- **Commit**: `[Current Phase]`
- **Key Changes**:
  - `tests/e2e-test.js`: Expanded test suite from 13 to 17 automated tests, verifying real bounding boxes and computed properties for mobile logo, mobile hero slider, 3-item bottom bar, and mobile checkout input widths.
  - `inc/auth-otp.php`: Improved test transport constant check to support truthy values (`1` or `true`).
  - `docs/architecture/future-theme-customization-map.md`: Produced architecture map for future admin theme customization controls.
  - Generated production distribution package `dist/negar-shop.zip`.
