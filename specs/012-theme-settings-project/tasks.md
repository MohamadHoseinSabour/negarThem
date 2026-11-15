# Tasks: 012 Complete Theme Settings Project

**Status:** Phase 11 Final QA, Critical Fixes & Release complete.

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

- [x] **TYPO-8.1** Register `typography` group in `negar_theme_options` with canonical defaults (`font_family => 'vazirmatn'`, `heading_font_family => 'vazirmatn'`, `base_font_size => 16`, `heading_scale => 'normal'`).
- [x] **TYPO-8.2** Implement whitelisted font families (`vazirmatn`, `system`, `tahoma`), base font size clamping (14–18px), and heading scale options (`compact`, `normal`, `large`) with strict sanitization and default fallbacks.
- [x] **TYPO-8.3** Add dedicated Typography tab in WordPress admin (`?page=negar-theme-settings&tab=typography`) with select controls, number input, heading scale selector, and Persian live typography preview box.
- [x] **TYPO-8.4** Add "Restore Defaults" reset actions with confirmation dialogs for both Colors (restoring canonical crimson `#e90544` / `#c40439`) and Typography.
- [x] **TYPO-8.5** Connect core WordPress Site Identity controls for site logo and icon (`custom_logo`), providing `negar_theme_logo_url()` helper with demo SVG fallback, linked from the General tab.
- [x] **TYPO-8.6** Connect typography and color tokens to frontend CSS via `negar_shop_customizer_css()`, generating `--font-primary`, `--font-heading`, `body { font-size: Xpx; }`, and heading scale rules.
- [x] **TYPO-8.7** Optimize font enqueueing in `inc/enqueue.php`: conditionally load Google Fonts Vazirmatn and resource hints only when required; expose resolved family name in `kavehShopData.fontFamily`.
- [x] **TYPO-8.8** Add automated regression tests in `tests/theme-settings-regression.php` and `tests/theme-settings-regression.js` covering persistence, validation fallback, clamping, restore defaults, computed styles, and Site Identity linking.
- [x] **COLOR-8.1a** Expose NegarThem settings in WordPress admin with a prominent top-level dashboard menu (`نگارتم`, Dashicon `dashicons-art`, position 59) while preserving existing `options-general.php?page=negar-theme-settings` routes, deep links, and tab base contexts.
- [x] **COLOR-8.1b** Clarify primary vs secondary/hover color relationship in settings UI, replace fixed decorative tokens (`--color-primary-light`, `--color-accent-light`, `--color-accent-hover`) with dynamic CSS properties and `color-mix()`.
- [x] **COLOR-8.1c** Harmonize theme-accent styling across categories, banners, product card actions, badges, timers, add-to-cart, glows (`--ppnk-card-glow`), and Tailwind rose utilities, preserving semantic alerts/errors.
- [x] **COLOR-8.1d** Add Playwright browser tests verifying default canonical red (#e90544/#c40439) and custom green (#16834a/#116638) palettes, hover states, 1440px desktop and 390px mobile viewports.
- [x] **T211** Design an SMS-provider boundary with separately stored secrets; never export credentials or expose them to the browser (Phase 9).
  - [x] **SMS-9.1** Register dedicated non-autoloaded options `negar_theme_sms_options` and `negar_theme_sms_secrets` with schema isolation.
  - [x] **SMS-9.2** Implement Settings API tab (`tab=sms`) with masked API key input, configuration badge, server constant support (`NEGAR_KAVENEGAR_API_KEY`), and explicit key deletion.
  - [x] **SMS-9.3** Implement practical Kavenegar provider (`negar_kavenegar_send_lookup`) using Verify Lookup REST API with honest Persian error mapping.
  - [x] **SMS-9.4** Connect SMS provider to existing OTP dispatch hook (`negar_dispatch_otp_sms`).
  - [x] **SMS-9.5** Enforce customer-only authentication policy (`negar_is_user_privileged`) rejecting administrative roles and capabilities from public OTP logins.
  - [x] **SMS-9.6** Reject ambiguous phone-to-user matches when multiple accounts share the same `billing_phone`.
  - [x] **SMS-9.7** Add client IP rate limiting (15 attempts/hour) alongside existing 5 requests/phone/hour rate limit.
  - [x] **SMS-9.8** Secure test transport (`NEGAR_TEST_OTP_TRANSPORT`) requiring verified local/development environment and zero OTP code disclosure.
  - [x] **SMS-9.9** Add automated regression and Playwright browser tests covering secrets isolation, blank key preservation, provider HTTP mocks, customer-only policy, ambiguous detection, and admin UI.
- [x] **T212** Design backup/import/export validation, authorization, rollback and
  non-autoloaded storage for large settings (Phase 10).
  - [x] **BACKUP-10.1** Implement versioned configuration export in `inc/theme-backup.php` covering the 5 supported groups (`negar_theme_options`, `negar_theme_media_options`, `negar_theme_product_pack_options`, `negar_theme_story_options`, `negar_theme_brand_options`).
  - [x] **BACKUP-10.2** Strictly exclude secrets, API keys, SMS configuration, customer accounts, orders, passwords, and database tables from export payloads.
  - [x] **BACKUP-10.3** Implement upload and preview workflow in Backup tab (`tab=backup`) with file size validation (max 2 MB), JSON schema verification, disallowed key rejection, and cross-site reference checks without modifying the database.
  - [x] **BACKUP-10.4** Implement atomic apply and restore with complete pre-write snapshots and rollback on write or verification failure.
  - [x] **BACKUP-10.5** Harden customer OTP authentication in `inc/auth-otp.php` to reject username-only phone matches when explicit `billing_phone` binding is absent or mismatched.
  - [x] **BACKUP-10.6** Add automated regression and Playwright browser tests covering backup export structure, secrets exclusion, invalid JSON rejection, preview isolation, apply persistence, rollback restoration, and OTP binding security.
- [x] **T213** Complete end-to-end, accessibility, RTL, migration and packaging
  verification before declaring the full project complete (Phase 11).
  - [x] **RELEASE-11.1** Fix backup import validation (Defect A): add independent `negar_theme_sanitize_full_settings()` sanitizer without tab dependency.
  - [x] **RELEASE-11.2** Correct backup reference schemas (Defect B): packs, stories, and brands with strict cross-site reference checks and warnings.
  - [x] **RELEASE-11.3** Real E2E backup round trip: export -> mutate -> preview (zero writes) -> apply -> verify exact restoration -> rollback test -> snapshot restore.
  - [x] **RELEASE-11.4** Verify top-level admin menu نگارتم and all 12 settings tabs under `manage_options`.
  - [x] **RELEASE-11.5** Storefront smoke tests across homepage, shop, single product, cart/checkout, customer account and OTP.
  - [x] **RELEASE-11.6** Responsive (390px, 768px, 1440px), Persian RTL, and accessibility review.
  - [x] **RELEASE-11.7** Security review: zero OTP disclosure, isolated SMS secrets, nonces.
  - [x] **RELEASE-11.8** Update `style.css` `Requires PHP: 8.0` matching `str_starts_with()` usage.
  - [x] **RELEASE-11.9** Package clean production distribution `dist/negar-shop.zip` excluding git, node_modules, tests, scripts, docs, and specs.
