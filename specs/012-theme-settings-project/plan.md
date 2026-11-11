# Implementation Plan: 012 Complete Theme Settings Project

**Status:** Phase 1.1 complete; later phases planned only.

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
3. **Content and layout (planned):** define and implement the Texts and Layout
   schemas only after per-template ownership and accessibility review.
4. **Commerce presentation (planned):** add product-section and promotion controls
   that select/query real WooCommerce objects without duplicating commercial data.
5. **Media collections (planned):** add banners, stories and brands using validated
   media IDs, safe links and non-autoloaded repeaters where appropriate.
6. **Typography, SMS and backup (planned):** define separate security boundaries,
   secret handling and import/export validation before UI implementation.
7. **Release verification (planned):** expand regression coverage, run PHP lint and
   Playwright checks against WAMP, review RTL/keyboard behavior and package only
   after data-integrity checks pass.

## Verification strategy

- Unit-style WordPress bootstrap checks for capability, sanitization, persistence,
  empty values, group isolation and Customizer compatibility.
- Playwright checks of the local WAMP frontend for computed design tokens after
  save/reload, always restoring the initial option and theme-mod snapshots.
- `npm run test:lint` for PHP syntax and `npm test` for the user-visible suite.
