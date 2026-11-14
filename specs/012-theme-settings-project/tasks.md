# Tasks: 012 Complete Theme Settings Project

**Status:** Phase 6 Story Management in progress.

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
- [x] **T209.3** Implement fixed product-pack/hotspot product ID selectors and
  resolve all displayed commerce from published, catalog-visible WooCommerce
  products while retaining existing coordinates and interactions.
- [x] **T209.4** Add authenticated reversible persistence/frontend regression
  coverage for media, pack and hotspot settings, then update architecture docs.

## Phase 6: Story Management

- [x] **STORY-6.1** Define `negar_theme_story_options` schema, validation rules, media mime checking, and non-autoloaded option registration under `negar_theme_story_settings`.
- [x] **STORY-6.2** Implement admin Story Management UI under Settings → تنظیمات قالب نگار → استوری‌ها (CRUD, reordering with keyboard move buttons, separate thumbnail and image/video media selectors, product association, duration).
- [x] **STORY-6.3** Implement frontend story rendering in `template-parts/home/story-circles.php` with real WordPress media, truthful WooCommerce product binding, clean omission when empty, and safe fallback.
- [x] **STORY-6.4** Implement image and native HTML5 video story modal playback with actual video duration progress, pause/resume, and accessible keyboard/touch navigation.
- [x] **STORY-6.5** Add comprehensive reversible regression tests for story persistence, validation, video/image playback, and frontend tokens.
- [x] **STORY-6.6** Implement 3-tier CTA priority: real WooCommerce product > safe custom CTA URL without fake product names > hide footer completely.
- [x] **STORY-6.7** Protect demo fallback and first-save: remove fabricated product names from demo stories; display explanatory notice in unconfigured admin state starting with empty list so first save cannot pollute managed records.
- [x] **STORY-6.8** Reject and omit unusable media: omit video stories without valid videos, image stories without images, and stories without thumbnails from public frontend collection while preserving in admin settings with warning notices.
- [x] **STORY-6.9** Harden video player and modal accessibility: use `preload="none"`, bind progress to finite positive duration, advance strictly on `ended`, provide user-recoverable play/error overlays, lock/restore body scroll, enforce focus trap, and restore focus to activating button.
- [x] **STORY-6.10** Upgrade regression coverage with real WebM test fixture, Playwright real playback/pause/ended tests, and database snapshot/cleanup restoration.

## Phase 7: Brand Management

- [x] **BRAND-7.1** Define `negar_theme_brand_options` schema (version 1), validation rules, non-autoloaded option registration under `negar_theme_brand_settings`, and integration with genuine WooCommerce `product_brand` taxonomy.
- [x] **BRAND-7.2** Implement admin Brand Management UI under Settings → تنظیمات قالب نگار → برندها (debounced AJAX search for genuine WooCommerce brands, selection of up to 20 terms, keyboard accessible Move Up / Move Down buttons, enable/disable toggle, removal, and optional WordPress Media Library logo override with native taxonomy fallback).
- [x] **BRAND-7.3** Implement frontend brand rendering in `template-parts/home/brands-slider.php` with genuine WooCommerce brands, real brand names, native or overridden logos, and archive links.
- [x] **BRAND-7.4** Cleanly omit fake demonstration commercial data: remove hardcoded ratings (`۳/۳`, `۴/۵`, etc.), star icons (`icon-star-7`), and fake product counts (`+۲۴۰ محصول`) from the carousel.
- [x] **BRAND-7.5** Implement layout safety: suppress Swiper navigation arrows when only 1 brand is rendered, cleanly omit the section container when 0 brands are enabled or store has no brands, and support homepage layout toggle disabling.
- [x] **BRAND-7.6** Add comprehensive reversible regression coverage in `tests/theme-settings-regression.php` and `tests/theme-settings-regression.js` verifying unauthorized rejection, sanitization, keyboard ordering, Media Library override, empty omission, and full fixture cleanup.

## Future Phases Roadmap

- **Phase 8:** Branding, typography and colors
- **Phase 9:** SMS and OTP provider integration
- **Phase 10:** Settings backup/import/export
- **Phase 11:** Final QA and release preparation

- [ ] **T210** Approve Typography controls with safe font-loading and performance
  limits (Phase 8).
- [ ] **T211** Design an SMS-provider boundary with separately stored secrets;
  never export credentials or expose them to the browser (Phase 9).
- [ ] **T212** Design backup/import/export validation, authorization, rollback and
  non-autoloaded storage for large settings (Phase 10).
- [ ] **T213** Complete end-to-end, accessibility, RTL, migration and packaging
  verification before declaring the full project complete (Phase 11).
