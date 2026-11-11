# Feature Specification: 012 Complete Theme Settings Project

**Feature code:** `SETTINGS-01`
**Status:** Planned; Phase 1.1 hardened
**Supersedes for new work:** None. `009-featurename-theme-options` remains the
historical Customizer-and-widgets specification and is not this project.

## Goal

Provide one secure, maintainable NegarThem settings experience that can configure
approved presentation content while preserving the Persian RTL storefront,
WooCommerce as the source of commercial truth, and the existing Customizer as a
non-destructive compatibility source.

## Current baseline

Phase 1 provides the `negar_theme_options` v1 option, a Settings > Negar Theme
Settings route protected by `manage_options`, and implemented General, Colors and
Header/Footer/Contact tabs. Phase 1.1 hardens color tokens and persistence. This
spec plans the complete project; it does not authorize the remaining phases yet.

## Functional requirements

1. Settings access and writes require `manage_options`, a WordPress Settings API
   nonce, contextual sanitization and output escaping.
2. Every implemented tab saves only its own group without removing other groups;
   explicit empty values remain distinguishable from absent values.
3. Existing Customizer `theme_mod` values remain frontend fallbacks until an
   explicit v1 replacement is stored. No activation-time bulk migration may
   overwrite saved values.
4. The Colors tab must render saved valid primary and secondary colors through the
   documented CSS tokens. The canonical no-override appearance is crimson:
   `#e90544` primary and `#c40439` secondary.
5. Future text, layout, product-section, banner, story, brand, typography, SMS
   and backup/import controls must have an approved schema and regression tests
   before becoming writable.
6. Product prices, stock, sale status, ratings, reviews, orders, customers and OTP
   states must remain live WordPress/WooCommerce or provider data. Settings may
   select presentation/query inputs, never invent transactional or commercial data.

## Non-goals

- Replacing or removing the legacy Customizer in this project phase.
- Implementing any Phase 2 tab or frontend feature as part of Phase 1.1.
- Saving provider secrets in the exportable settings option or exposing them to
  JavaScript.

## Acceptance criteria

- Unauthorized visitors cannot open or persist settings.
- Each enabled tab persists after reload and preserves all other settings groups.
- Empty values, legacy Customizer values and valid saved colors have defined,
  tested behavior.
- Changing a valid color changes the computed frontend tokens after reload; the
  original values are restored after automated checks.
- Documentation keys exactly match implementation and the dedicated spec, plan and
  task list remain separate from specification 009.
