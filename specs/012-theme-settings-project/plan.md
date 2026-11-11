# Implementation Plan: 012 Complete Theme Settings Project

**Status:** Phase 4.1 WooCommerce product-section hardening complete; later phases planned only.

## Architecture

- Keep `inc/theme-settings.php` as the schema, Settings API, capability boundary
  and field renderer for the versioned `negar_theme_options` option.
- Keep `inc/customizer.php` as the legacy source and render color tokens from
  resolved values only; do not add competing hard-coded CSS overrides.
- Use small versioned settings groups for simple values. Store future large
  repeaters in schema-validated non-autoloaded options and secrets separately.
- Use live WordPress/WooCommerce data for every commercial or transactional value.

## Delivery sequence

1. **Foundation (complete):** schema v1, static tab registry, General, Colors and
   Header/Footer/Contact groups, frontend getters and Customizer fallback.
2. **Hardening (complete):** canonical crimson defaults, reliable CSS token output,
   reversible persistence/browser regression coverage and synchronized docs.
3. **Text management (complete):** implement the documented `texts` registry and
   getter for theme-owned copy only, with authenticated Settings API submission and
   representative frontend regression coverage.
4. **Text coverage completion (complete):** finish the reviewed active header,
   footer, About and Contact copy; publish the homepage/internal ownership matrix
   and keep unmounted partials, navigation, system copy and managed data excluded.
5. **Layout (complete):** implement a fixed 35-ID registry in the non-Elementor
   homepage path. Store only IDs and enabled states, normalize corrupt/missing
   layouts safely, and provide RTL drag/drop plus visible keyboard move controls.
6. **Commerce presentation (complete, hardened):** add independent query controls for the
   existing real-product carousels/grids and product-tab sources: published manual
   IDs, category include/exclude terms, count and a whitelist of sort modes. Phase
   4.1 adds per-section custom/legacy state, catalog visibility filtering and a
   nonce-protected Ajax search selector that retains saved IDs without loading the
   catalog. Keep the exact legacy query path until that section is explicitly enabled.
7. **Media collections (planned):** add banners, stories and brands using validated
   media IDs, safe links and non-autoloaded repeaters where appropriate.
8. **Typography, SMS and backup (planned):** define separate security boundaries,
   secret handling and import/export validation before UI implementation.
9. **Release verification (planned):** expand regression coverage, run PHP lint and
   Playwright checks against WAMP, review RTL/keyboard behavior and package only
   after data-integrity checks pass.

## Verification strategy

- Unit-style WordPress bootstrap checks for capability, sanitization, persistence,
  empty values, group isolation and Customizer compatibility.
- Playwright checks of the local WAMP frontend for computed design tokens after
  save/reload, always restoring the initial option and theme-mod snapshots.
- A second Playwright path uses a temporary administrator session to submit the
  actual Settings API Texts form, then reloads both the admin screen and selected
  frontend components before restoring the snapshot.
- The same authenticated path submits the Layout form, exercises drag/drop and
  keyboard movement, confirms a hidden section and saved order on the frontend,
  then restores the initial option snapshot.
- The authenticated path submits a real manual product selection and verifies its
  live WooCommerce permalink in the existing accessories component at desktop and
  mobile widths. Bootstrap checks reject malformed, unknown and unpublished IDs.
- `npm run test:lint` for PHP syntax and `npm test` for the user-visible suite.
