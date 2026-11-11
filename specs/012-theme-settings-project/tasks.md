# Tasks: 012 Complete Theme Settings Project

**Status:** Phase 1.1 complete; no Phase 2 implementation authorized.

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

## Planned only — do not implement in Phase 1.1

- [ ] **T205** Approve and implement Texts and Layout schema groups with RTL and
  keyboard regression coverage.
- [ ] **T206** Approve and implement Product Sections controls backed solely by
  live WooCommerce objects and truthful inventory/pricing/review states.
- [ ] **T207** Approve and implement validated Banner, Story and Brand media
  collections, using attachment IDs and safe URLs.
- [ ] **T208** Approve Typography controls with safe font-loading and performance
  limits.
- [ ] **T209** Design an SMS-provider boundary with separately stored secrets;
  never export credentials or expose them to the browser.
- [ ] **T210** Design backup/import/export validation, authorization, rollback and
  non-autoloaded storage for large settings.
- [ ] **T211** Complete end-to-end, accessibility, RTL, migration and packaging
  verification before declaring the full project complete.
