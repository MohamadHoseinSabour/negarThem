# Tasks: 012 Complete Theme Settings Project

**Status:** Phase 5 banner, hero and product-pack management in progress.

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
- [x] **T208.1** Harden product sections with full live-data sorting, an
  authenticated searchable selector, per-section custom/legacy state,
  catalog-visibility enforcement, truthful unrated cards and reversible
  authenticated persistence regressions.
- [x] **T209.0** Audit every mounted Phase 5 hero, banner, pack and hotspot
  template; record current assets, links, text ownership and stable controls.
- [x] **T209.0a** Stabilize Phase 5.1-A media controls and hotspot keys: accept
  safe site-relative and HTTP(S) media links without browser `type="url"` blocking,
  immediately restore fallback preview images when clearing an attachment, fix
  the Gaming Zone `'mirage'` hotspot key mismatch, synchronize audit docs and
  verify with reversible regression tests.
- [x] **T209.0b** Convert Hero slider (`hero-slide-one`, `hero-slide-two`) to PHP
  server-side rendering, remove Hero-only JS media replacement, safely omit
  disabled slides and single-slide loop/navigation, preserve the adjacent flash-deal
  product card when zero slides are active, and verify desktop/mobile SSR and
  Swiper stability.
- [x] **T209.0c** Convert all 15 audited promotional banner records across 8 home
  templates to PHP server-side rendering via shared helpers, remove legacy
  `applyBannerMediaRecords()` JS replacement and banner data attributes, omit
  disabled banners/images cleanly, collapse single-active banner pairs to one
  column and omit empty containers while preserving intentional empty editorial
  text, image-only templates and snapshot restoration.
- [x] **T209.0d** Optimize `template-parts/home/sec-25-gaming-zone.php` product lookup:
  eliminate unbounded `wc_get_products( ['limit' => -1] )`, resolve manually
  assigned products directly by ID without fallback queries, execute bounded
  `LIMIT 1` searches with catalog-visibility filtering only for unassigned slots,
  preserve the 4 hotspot keys/needles, and render truthful empty states.
- [x] **T209.1** Implement fixed hero/banner media records using image attachment
  IDs, safe URLs, explicit alt/visibility values, non-autoloaded independent
  storage and Media Library controls. Do not include stories or brands.
- [x] **T209.2** Bind each audited hero and promotional banner template without
  changing its layout, Swiper behavior, Phase 2 text fields or demo fallback.
- [ ] **T209.3** Implement fixed product-pack/hotspot product ID selectors and
  resolve all displayed commerce from published, catalog-visible WooCommerce
  products while retaining existing coordinates and interactions.
- [ ] **T209.4** Add authenticated reversible persistence/frontend regression
  coverage for media, pack and hotspot settings, then update architecture docs.
- [ ] **T210** Approve Typography controls with safe font-loading and performance
  limits.
- [ ] **T211** Design an SMS-provider boundary with separately stored secrets;
  never export credentials or expose them to the browser.
- [ ] **T212** Design backup/import/export validation, authorization, rollback and
  non-autoloaded storage for large settings.
- [ ] **T213** Complete end-to-end, accessibility, RTL, migration and packaging
  verification before declaring the full project complete.
