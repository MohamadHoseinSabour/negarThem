# Theme-settings execution progress

## Phase 0 — architecture and settings inventory

**Status:** PASS
**Commit:** `9e9014c`

- Added the source-backed inventory of the 35 homepage sections, existing
  Customizer ownership, data-source risks, frontend bindings and OTP path.
- No site settings, customers, products, media or OTP state changed.
- `npm run test:lint` passed (112 PHP files at the time of this documentation-only
  commit).

## Phase 1 — settings framework and admin UI

**Status:** PASS
**Commit:** `7cbec3b`

- Added `inc/theme-settings.php`: a versioned Settings API option, typed getter,
  explicit-empty-value handling, group-preserving sanitization, Settings menu route
  and Persian RTL tab UI.
- The page route is `Settings → تنظیمات قالب نگار` and requires
  `manage_options`; its Settings API form includes a nonce and the sanitizer repeats
  the capability check.
- The framework exposes all required tab destinations. Only the foundation tabs
  (general/branding, colors, header/footer/contact) are writable in Phase 1; later
  tabs deliberately do not accept unimplemented configuration yet.
- Legacy Customizer values remain frontend fallbacks until an administrator stores
  a replacement. No legacy value was bulk-copied or overwritten.
- Connected the Phase 1 settings to the top announcement, contact details, footer
  information/social links, brand label and color CSS variables.

### Verification evidence

- `npm run test:lint` — PASS (113 PHP files).
- `npm test` — PASS (113 PHP files and 37 Playwright checks against
  `http://localhost/wp4/`).
- Browser check — unauthenticated access to the Settings URL is redirected to the
  WordPress sign-in screen.
- WordPress bootstrap check under a transient administrator context — PASS: page
  title, 11 tabs, form nonce, general field and color sanitizer all rendered or
  validated as expected. This check does not persist any setting.

## Phase 1.1 - color and persistence hardening

**Status:** PASS
**Commit:** `c1b2704`

- Restored canonical crimson rendering when no override exists while allowing every
  valid saved v1 or legacy Customizer color, including former Customizer defaults.
- Added reversible persistence checks for access, group isolation, empty values,
  Customizer compatibility and computed frontend CSS tokens.

## Phase 2 - theme text management

**Status:** PASS
**Commit:** recorded with the Phase 2 delivery commit

- Added the strict `texts` registry for documented theme-owned header, home,
  footer, contact, about and not-found copy; WordPress page content and all
  WooCommerce commercial/customer/transactional data remain out of scope.
- Added an authenticated browser form-submission check that reloads the Settings
  screen and representative homepage, footer and contact output, then restores its
  initial settings snapshot and destroys its temporary administrator session.

## Phase 2.1 - text coverage completion

**Status:** PASS
**Commit:** recorded with the Phase 2.1 delivery commit

- Completed reviewed active text coverage for the top-bar/Mega Menu, footer contact
  cards, About information cards and Contact card labels/map empty state.
- Added the ownership matrix for all 35 mounted homepage sections and internal
  templates, so deferred product, media, navigation and managed-data work cannot
  become an accidental text setting.
- Extended the authenticated browser submission test to verify Mega Menu, footer,
  About and Contact rendering, group isolation and snapshot restoration.

## Next phase

## Phase 3 - homepage section layout management

**Status:** PASS
**Commit:** recorded with the Phase 3 delivery commit

- Added a code-owned registry for the 35 existing homepage templates. Default
  output remains their original order and only the non-Elementor homepage uses it.
- The Layout tab saves only known stable IDs and boolean visibility. Invalid or
  duplicate IDs are rejected, missing IDs are safely appended, and no saved value
  can select a PHP template path.
- Added Persian RTL drag/drop controls with visible move-up/move-down alternatives
  for keyboard use. No product query, banner, story, media or Swiper code changed.
- Extended reversible WordPress and authenticated browser tests for 35-row form
  rendering, drag/drop, keyboard movement, visibility, persisted frontend output,
  group isolation and setting restoration.

## Next phase

## Phase 4 - WooCommerce product-section management

**Status:** PASS
**Commit:** recorded with the Phase 4 delivery commit

- Added a whitelist for the existing live-product homepage queries only: sale
  carousel, four product tabs, accessories, product grids and mobile supporting
  cards. Banner, story, review, visitor-history and product-pack components remain
  out of scope.
- The Products tab stores bounded count/sort settings, actual category term IDs and
  published manual product IDs. It cannot save arbitrary post IDs, draft/private
  products, taxonomy strings or product data.
- Existing query paths remain byte-for-byte behaviorally intact until an
  administrator saves this tab. Once configured, cards continue deriving their
  prices, images, ratings, stock state and permalinks from live WooCommerce.
- Authenticated browser checks submit a manual product, reload admin persistence,
  and verify its real permalink at desktop and mobile WAMP widths; all test
  settings are restored.

## Phase 5 — Hero, banner and complete product-pack management

**Status:** PASS (COMPLETE & CLOSED)
**Baseline:** `10917faf6e549e23cf95ff58a802495bc4bfeaed`
**Closure Commits:**
- `2edb5c0` `fix(settings): harden product pack price and purchase semantics`
- `35ef2ea` `test(settings): add try-finally query cleanup and zero-price regression coverage`

### Summary of Accomplishments:
- **Phase 5.1-A (Bug Fixes):** Media link validation accepts safe relative paths (`/shop/`) and HTTP(S) URLs while rejecting unsafe protocols; Media Library removal immediately restores default fallback image; Gaming Zone `'mirage'` hotspot key aligned.
- **Phase 5.1-B1 (Hero SSR):** Converted Hero slider (`hero-slide-one`, `hero-slide-two`) to pure PHP server-side rendering, removed client-side JS media replacement, handled 2-, 1- and 0-slide visibility states cleanly with Swiper stability.
- **Phase 5.1-B2 (Banner SSR):** Converted all 15 audited promotional banner records across 8 templates to PHP server-side rendering; removed `applyBannerMediaRecords()` and client-side data attributes; collapsed single-active pairs; omitted empty containers cleanly.
- **Phase 5.1-C (Gaming Zone Query Optimization):** Replaced unbounded `wc_get_products( ['limit' => -1] )` with direct ID resolution ($O(1)$) and bounded `LIMIT 1` fallback search with catalog-visibility filtering.
- **Phase 5.4 & Hardening (Complete Product Pack Management):** Connected all 7 code-owned product showcase components (40 slots total) to `negar_theme_product_pack_options`; eliminated fabricated commercial demonstration data; enforced 100% truthful empty states (`.is-empty`, standard WooCommerce placeholder, localized neutral empty notices).
- **Phase 5.5 (Final Audit & Closure):**
  - Section accessibility label neutrality: `sec-28-mobile-spotlight` uses `ویترین محصول ویژه` (no hardcoded Galaxy S25 FE).
  - Commercial copy neutrality: Replaced commercial guarantee claims with neutral store descriptions.
  - Price semantics: Proper distinction between unpriced items (`'' === $product->get_price()`), genuine zero-price items (`(float)$price === 0.0` -> `رایگان` without Toman symbol), positive formatted prices, and variable product price ranges. Real sales require `regular_price > sale_price > 0`.
  - Action safety: Supports `simple`, `variable`, `grouped`, and `external` WooCommerce product types safely. Out-of-stock items display disabled `ناموجود` controls; empty slots suppress action buttons completely.
  - Query filter reliability: Wrapped temporary `posts_where` filters in `try ... finally` blocks to ensure filter detachment even upon exceptions.
  - Snapshot restoration: All tests restore option snapshots identically in `finally` blocks.
  - Test Suite: 114 PHP files passed linting; theme settings persistence & rendered-token suite passed; 37 E2E tests passed with 0 failures.

## Phase 6 — Complete Homepage Story Management

**Status:** PASS (COMPLETE & VERIFIED)
**Commits:**
- `ff7f615` `feat(settings): add managed story collection and admin controls`
- `51e3fd0` `feat(home): render managed image and video stories`

### Summary of Accomplishments:
- **Story Schema & Storage Boundary:** Registered `negar_theme_story_options` (`NEGAR_THEME_STORY_OPTION`, schema version 1) under `negar_theme_story_settings`, keeping it strictly out of `wp_load_alloptions()`. Distinguishes unconfigured fallback (`null === get_option(...)` -> 10 reviewed demo stories) from explicit empty state (`'stories' => array()` -> completely omits `.story-shop` and `#modal-story-shop` from the DOM).
- **Admin Story Manager:** Added management screen under **Settings → تنظیمات قالب نگار → استوری‌ها**:
  - Add, edit, remove, enable/disable, and reorder stories with keyboard move controls (Move Up / Down).
  - Separate circle thumbnail image (`thumbnail_id`) from fullscreen story media (`image_id` / `video_id`).
  - Supported Image stories with duration control (3–30s, default 5s) and Video stories with media mime validation (`str_starts_with(mime, 'video/')`).
  - Associated optional real WooCommerce products via searchable selector (`negar-product-selector`), verifying catalog visibility and published status.
  - Configurable custom CTA label and safe destination URL (`negar_theme_sanitize_media_link()`).
- **Server-Side Rendered Frontend Story Component (`template-parts/home/story-circles.php`):**
  - Story circles rendered as accessible `<button type="button" aria-haspopup="dialog">` elements with truthful badges and titles.
  - Native HTML5 `<video playsinline webkit-playsinline preload="metadata">` playback driven by actual media duration (`currentTime / duration`) on `timeupdate`, advancing strictly on authoritative `ended` event.
  - Image stories driven by configured duration timer (3–30s).
  - Press and hold pause/resume interaction on touch and mouse.
  - Escape key closes modal, ArrowLeft/ArrowRight navigates stories.
  - Focus trap inside active modal, and focus restoration to activating story button upon close.
  - Footer product card renders genuine WooCommerce product title and permalink, or custom CTA URL, and cleanly hides when unconfigured.
- **Verification & Regression Testing:**
  - `npm run test:lint` — 114 PHP files passed syntax linting.
  - `npm run test:settings` — Passed all story checks: unauthorized save rejection, mime type validation, clamped duration, unsafe URL rejection, product association, reordering ID stability, disabled story omission, clean omission when empty, demo fallback, and admin/modal Playwright interactions.
  - `npm run test:e2e` & `npm test` — 37 passed, 0 failed across desktop, tablet, and mobile (375px/390px) viewports.

## Next phase

Phase 7: Brands Management.
Following approved roadmap: Phase 7 (Brands), Phase 8 (Typography & Colors), Phase 9 (SMS/OTP), Phase 10 (Backup/Export), Phase 11 (Release QA & Packaging).
