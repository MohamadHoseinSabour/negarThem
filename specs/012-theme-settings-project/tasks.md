# Tasks: 012 Complete Theme Settings Project

**Status:** Phase 4 WooCommerce product-section management complete; later phases remain unimplemented.

## Completed foundation and hardening

- [x] **T200** Establish the versioned `negar_theme_options` schema and Settings
  API foundation, with legacy Customizer fallback.
- [x] **T201** Implement General, Colors and Header/Footer/Contact settings groups
  and their current frontend bindings.
- [x] **T202** Harden color resolution: remove conflicting `!important` overrides,
  align canonical crimson defaults, and preserve valid legacy Customizer colors.
- [x] **T203** Add reversible persistence and browser-token regression coverage for
  authorization, enabled tabs, group isolation, empty values and frontend output.
- [x] **T204** Synchronize the inventory/schema keys and create this dedicated
  complete-project Spec Kit set, separate from historical spec 009.

## Text management

- [x] **T205** Implement the Texts schema for documented theme-owned header,
  homepage, footer, contact, about and not-found copy. Verify access, persistence,
  explicit empties, group isolation and real authenticated form submission; do not
  expose WordPress- or WooCommerce-managed content.

- [x] **T206** Complete the active header Mega Menu, footer, About-card and
  Contact-card text coverage; publish the full homepage/internal coverage matrix
  and extend authenticated form/frontend regression checks with restoration.

## Planned only — do not implement in Phase 4
- [x] **T207** Implement the fixed 35-section Layout schema, safe ID-only
  normalization, RTL drag/drop and keyboard move controls, visibility toggles and
  authenticated frontend regression coverage without changing Elementor or section
  query behavior.
- [x] **T208** Implement Product Sections controls backed solely by published,
  live WooCommerce products and existing categories, with count, supported sort,
  category include/exclude and manual-selection validation plus authenticated
  desktop/mobile form regressions.
- [ ] **T209** Approve and implement validated Banner, Story and Brand media
  collections, using attachment IDs and safe URLs.
- [ ] **T210** Approve Typography controls with safe font-loading and performance
  limits.
- [ ] **T211** Design an SMS-provider boundary with separately stored secrets;
  never export credentials or expose them to the browser.
- [ ] **T212** Design backup/import/export validation, authorization, rollback and
  non-autoloaded storage for large settings.
- [ ] **T213** Complete end-to-end, accessibility, RTL, migration and packaging
  verification before declaring the full project complete.
