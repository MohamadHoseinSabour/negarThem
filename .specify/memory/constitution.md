# Negar Shop Constitution

**Project:** Negar Shop - Professional Persian WooCommerce Hybrid Theme  
**Author:** Antigravity & User Team  
**License:** GPL v2 or later  
**Text Domain:** `negar-shop`  

---

## Core Principles

### I. Visual Fidelity & Clean-Room Architecture
- The reference demo (`https://kaveh.moeinwp.com/43/#`) serves strictly as a **visual and UX benchmark**, never as a code source.
- All PHP templates, stylesheets, scripts, and structures must be written cleanly from scratch.
- Zero copying of external proprietary CSS/JS/PHP, logos, or commercial assets.
- Default web fonts: Vazirmatn (Open Font License) with modular support for IRANYekan/IRANSans when local webfont files are supplied.

### II. WordPress Security & Integrity (NON-NEGOTIABLE)
- **Output Escaping:** Every dynamic variable in HTML must be escaped using appropriate context functions: `esc_html()`, `esc_attr()`, `esc_url()`, or `wp_kses_post()`.
- **Input Sanitization:** All incoming requests (`$_GET`, `$_POST`, `$_REQUEST`, REST payload) must be sanitized (`sanitize_text_field()`, `absint()`, etc.).
- **Authentication & Nonces:** All Ajax actions and state-modifying requests must verify nonces via `check_ajax_referer()` or `wp_verify_nonce()`, along with capability checks (`current_user_can()`).
- **Database Safety:** Direct SQL queries are strictly forbidden without `$wpdb->prepare()`. Prefer standard WordPress APIs (`WP_Query`, `wc_get_products()`, etc.).

### III. RTL & Accessibility (WCAG 2.1 AA)
- True RTL-first design. Logical CSS properties (`margin-inline-start`, etc.) and RTL stylesheet rules must prevent clipping, overflowing, or mirrored misalignment.
- High contrast ratios (minimum 4.5:1 for normal text), focus indicators for keyboard navigation, ARIA attributes for interactive modals (mini cart, quick view, mobile navigation drawer).
- No raw emojis as UI icons; clean, scalable, accessible SVGs only.

### IV. Performance & Hybrid Architecture
- Hybrid Theme architecture: Standard, robust PHP templates for WooCommerce compatibility and custom Elementor widgets, orchestrated by `theme.json` for typography, color tokens, and layout spacing.
- Ultra-lightweight Vanilla JS / Modern DOM scripting for interactions (search, cart drawer, modals) without adding jQuery bloat where not needed.
- Asset optimization: Preload key fonts, lazy-load images with explicit aspect ratios to eliminate CLS (Cumulative Layout Shift), and conditionally enqueue scripts/styles only when needed.

### V. WooCommerce Compatibility & Standards
- Maintain strict compatibility with WooCommerce core template versioning and standard action/filter hooks.
- Never hardcode WooCommerce URLs or state; use WooCommerce native APIs (`wc_get_cart_url()`, `WC()->cart`, etc.).
- Ensure graceful degradation if WooCommerce is deactivated.

### VI. Spec-Driven Discipline & Continuous Quality Verification
- Every feature undergoes the complete Spec Kit workflow: `specify` → `clarify` → `plan` → `checklist` → `tasks` → `analyze` → `implement` → `converge`.
- Strict execution rule: Only **one task in-progress at a time**.
- Conventional Commits (`feat(scope): description (T###)`) for granular traceability.
- Continuous maintenance of `specs/coverage-matrix.md` with status progression (`todo` → `doing` → `done` → `verified`).
- Definition of Done requires zero PHP notices/warnings in `WP_DEBUG`, clean linter status, and multi-viewport visual verification (375px, 768px, 1024px, 1440px).

---

## Governance & Quality Gates
- This Constitution supersedes arbitrary ad-hoc implementations.
- Database alterations or test content removal requires an automated database backup (`wp db export`) prior to execution.
- No feature is marked `verified` without verifiable proof: automated checks, lint logs, or visual comparison screenshots.

**Version**: 1.0.0 | **Ratified**: 2026-11-06 | **Last Amended**: 2026-11-06
