# Feature Specification: 001 Foundation

**Feature Branch**: `001-featurename-foundation`  
**Created**: 2026-11-06  
**Status**: Ready for Planning  
**Coverage Matrix IDs**: `FND-01`, `FND-02`, `FND-03`, `FND-04`  

---

## 1. User Scenarios & Testing

### User Story 1 - Theme Activation & Clean Baseline (Priority: P1)
As a WordPress site administrator,  
I want to activate the Negar Shop theme without any fatal errors, warnings, or missing template notices,  
So that the site has a solid, compliant WordPress baseline ready for store setup.

**Why this priority**: Without a cleanly activated theme and valid theme declarations, no subsequent templates, WooCommerce integration, or styles can function.

**Independent Test**:
Activate Negar Shop via WP-CLI (`wp theme activate negarThem --path=...`). Verify HTTP 200 on front page, valid screenshot/metadata in Admin > Themes, and zero PHP warnings/notices in `WP_DEBUG`.

**Acceptance Scenarios**:
1. **Given** a clean WordPress installation with PHP 8.2 and WooCommerce active, **When** the administrator activates `negarThem`, **Then** WordPress recognizes the theme name "Negar Shop", version "1.0.0", text domain "negar-shop", and displays the active status without error.
2. **Given** `WP_DEBUG` is enabled, **When** the homepage is requested, **Then** the server responds with status 200 and logs zero notices or deprecated warnings.

---

### User Story 2 - Hybrid Design Tokens & RTL Baseline (Priority: P1)
As a front-end user visiting on a desktop or mobile device in a Persian locale,  
I want the page typography, color variables, spacing scales, and layout direction to be seamlessly right-to-left (RTL),  
So that all content renders naturally with proper Persian typography (Vazirmatn) and zero alignment or clipping glitches.

**Why this priority**: Establishes the design tokens (`theme.json` + CSS custom properties) and typography across the entire theme so all following sections inherit consistent spacing, fonts, and colors.

**Independent Test**:
Inspect DOM at 375px and 1440px viewports. Confirm `dir="rtl"` on `<html>`, correct font stack applied to `body`, and CSS custom properties for palette, typography, and spacing defined and accessible.

**Acceptance Scenarios**:
1. **Given** a Persian RTL WordPress setting, **When** the front page renders, **Then** `<html>` has attribute `dir="rtl"` and body font-family prioritizes local webfonts with clean fallback to Vazirmatn.
2. **Given** `theme.json` configuration, **When** inspecting root CSS, **Then** all standardized design tokens (`--wp--preset--color--primary`, etc.) are generated and available for template consumption.

---

### User Story 3 - WooCommerce Theme Support & Native Hook Infrastructure (Priority: P2)
As an eCommerce store manager,  
I want the theme to declare explicit `woocommerce` support and hook standard WooCommerce assets,  
So that WooCommerce product data, carts, notices, and checkout pipelines integrate smoothly without template version mismatch warnings.

**Why this priority**: Required before constructing shop archives, single products, and checkout flows.

**Independent Test**:
Inspect WooCommerce status report (`wp wc status` or WP Admin status). Confirm "Theme: Negar Shop" with active WooCommerce support and zero outdated template notices.

**Acceptance Scenarios**:
1. **Given** WooCommerce plugin is active, **When** viewing the WooCommerce System Status, **Then** theme support for `woocommerce`, `wc-product-gallery-zoom`, `wc-product-gallery-lightbox`, and `wc-product-gallery-slider` is confirmed active.
2. **Given** WooCommerce standard cart/notice hooks, **When** triggering a WooCommerce action, **Then** standard hooks `woocommerce_before_main_content` and `woocommerce_after_main_content` wrap shop output correctly.

---

### User Story 4 - Quality Tools & Asset Pipeline (Priority: P2)
As a theme developer and maintainer,  
I want automated linting and code quality verification (WPCS, ESLint, Stylelint) configured in the theme,  
So that all code remains compliant with WordPress Coding Standards and clean-room security requirements.

**Why this priority**: Ensures every commit meets Gate 0 and Gate 11 criteria continuously.

**Independent Test**:
Run quality check commands on theme codebase; confirm 0 errors.

**Acceptance Scenarios**:
1. **Given** all PHP files in the theme, **When** checked against WordPress security and escaping rules, **Then** zero unescaped outputs or raw unsanitized inputs are detected.

---

## 2. Requirements

### Functional Requirements
- **FR-001**: Theme MUST provide standard root files: `style.css` (with headers), `index.php`, `functions.php`, `theme.json`, and `screenshot.png` (`FND-01`).
- **FR-002**: Theme MUST provide a modular `inc/` architecture:
  - `inc/setup.php`: Core theme setup, title-tag, post-thumbnails, HTML5 support.
  - `inc/enqueue.php`: Conditional style and script enqueueing, font loading, versioning.
  - `inc/woocommerce.php`: WooCommerce theme support, wrapper hooks, template overrides preparation.
  - `inc/template-tags.php`: Helper functions and escaping wrappers.
- **FR-003**: Theme MUST define `theme.json` version 3 matching design system tokens (`--color-primary: #059669`, `--color-accent: #EA580C`, layout width 1440px, font scales) (`FND-02`).
- **FR-004**: Theme MUST load Vazirmatn web font cleanly with WOFF2 and `font-display: swap` to ensure high performance and zero FOIT (`FND-03`).
- **FR-005**: Theme MUST register text domain `negar-shop` with load_theme_textdomain on `after_setup_theme`.
- **FR-006**: Theme MUST provide `rtl.css` / RTL-aware CSS resets for Arabic/Persian scripts (`FND-02`).
- **FR-007**: Theme MUST declare WooCommerce supports (`woocommerce`, `wc-product-gallery-zoom`, `wc-product-gallery-lightbox`, `wc-product-gallery-slider`) (`FND-04`).

---

## 3. Non-Functional Requirements
- **Performance**: Zero external render-blocking scripts, font preloading for primary Latin/Persian subsets, CSS payload < 50KB minified for foundation.
- **Security**: 100% adherence to WordPress escaping rules (`esc_html`, `esc_attr`, `esc_url`). No PHP error/warning/notice in `WP_DEBUG`.
- **Accessibility**: Keyboard focus rings, logical tab indices, clean semantic HTML5 markup (`<header>`, `<main>`, `<footer>`, `<nav>`).
