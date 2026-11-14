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

**Status:** PASS (COMPLETE, HARDENED & VERIFIED)
**Baseline:** `cf3265ec1af50029e103634e6f724477ea5eaa55`
**Commits:**
- `83b334c` `fix(stories): correct media validation and editorial cta`
- `a3ae3e1` `fix(stories): harden viewer playback and accessibility`
- `d0ef6c2` `test(stories): verify persistence and real media playback`

### Summary of Accomplishments & Hardening:
- **Story Schema & Storage Boundary:** Registered `negar_theme_story_options` (`NEGAR_THEME_STORY_OPTION`, schema version 1) under `negar_theme_story_settings`, keeping it strictly out of `wp_load_alloptions()`.
- **Demo Fallback & First-Save Safety:**
  - Removed all fabricated demonstration product titles, cards, and fake links from fallback data (`has_product = false`, `product_title = ''`, `product_link = ''`).
  - Unconfigured admin settings tab displays an informational banner explaining demo preview mode and starts the editable collection empty (`0` cards). Saving the form never silently copies demo fallbacks or creates broken managed records.
  - Explicit empty collection (`stories => []`) cleanly omits both `.story-shop` and `#modal-story-shop` from the frontend DOM.
- **CTA & Commercial Priority (3 Tiers):**
  1. *Priority 1 (WooCommerce Product):* If an associated product is published and catalog-visible, the card displays its real product title, live price, and direct permalink.
  2. *Priority 2 (Editorial CTA):* If no product is associated but a safe custom CTA URL is configured, the footer displays an editorial CTA with that URL without fabricating a product title.
  3. *Priority 3 (Hidden Footer):* If neither a product nor a safe custom CTA exists, the footer card is completely hidden.
- **Media Validation, Sanitization & Omission:**
  - Attachment IDs for thumbnails, images, and posters require verified image attachments (`wp_attachment_is_image`).
  - Video attachment IDs require verified video mime types (`str_starts_with( get_post_mime_type( $id ), 'video/' )`). Invalid non-video attachments are sanitized to `0`.
  - Durations are clamped between 3 and 30 seconds (default 5).
  - Unusable media omission: Enabled stories lacking usable main media (video story with `video_id = 0`, image story with `image_id = 0`, or any story without a valid thumbnail) are automatically excluded from public storefront rendering (`negar_theme_get_stories( true )`) while remaining preserved and editable in the admin interface (`$only_enabled = false`) alongside warning notices.
- **HTML5 Video Player Lifecycle & Accessibility Hardening:**
  - Native HTML5 `<video playsinline webkit-playsinline preload="none">` ensures videos are not downloaded when the homepage loads.
  - Progress bar bound strictly to `currentTime / duration` when duration is a finite positive number. Stories advance strictly upon the authoritative `ended` event. Video stories never fall through to a fixed 5-second image timer.
  - User-recoverable Play overlay if autoplay policy blocks unmuted audio; user-recoverable Error overlay if media fails.
  - Holding down pointer pauses and freezes progress without triggering navigation upon release; short tap (< 300ms) navigates.
  - Navigation zones and arrow keys preserve correct RTL semantics (Left = Next, Right = Previous).
  - Body scroll lock (`document.body.style.overflow`) is preserved and restored to original value upon closing.
  - Strict Focus Trap handling Tab / Shift+Tab cycling among visible modal controls. Focus restored to activating trigger upon closing.
  - Pausing on page visibility loss (`visibilitychange`).
- **Comprehensive Regression Coverage:**
  - Added standalone `tests/fixtures/sample-video.webm` fixture and dynamic WordPress attachment helpers (`video-fixture`, `cleanup-video-fixture`).
  - `theme-settings-regression.php`: 100% assertions green covering defaults fallback, unconfigured admin safe view, explicit empty omission, 3-tier CTA priority, media validation/omission, ID deduplication, duration clamping, reordering stability, and SSR template rendering.
  - `theme-settings-regression.js`: Full Playwright browser coverage verifying Settings API persistence (add, edit, reorder, delete), real Chromium video metadata loading, real progress advancement, press/hold pause, resume, ended advancement, editorial CTA rendering, scroll-lock restoration, focus trap, and 375px mobile responsiveness.
  - Clean snapshot restoration: Database and media fixtures are restored identically to pre-test baseline in `finally` blocks.
  - `npm test`: Linting (114 PHP files) + Settings Regression + E2E Suite (37 tests) all passed with exit code 0.

## Phase 7 — Complete WooCommerce Brand Management

**Status:** PASS (COMPLETE & VERIFIED)
**Baseline:** `69f04dfe5af8aa1ede3675864c4c25b36d0a4ec6`
**Commits:**
- `8eb3f0f` `feat(settings): add WooCommerce brand management`
- `b85b710` `feat(home): render real WooCommerce brands`
- `92a3c77` `test(settings): verify brand management and carousel`

### Summary of Accomplishments:
- **Brand Schema & Storage Boundary:** Registered `negar_theme_brand_options` (`NEGAR_THEME_BRAND_OPTION`, schema version 1) under `negar_theme_brand_settings`, marked strictly non-autoloaded (`wp_set_option_autoload( ..., 'no' )`), maintaining complete isolation from other settings groups.
- **Genuine WooCommerce Brand Integration:** Integrated with native WooCommerce `product_brand` taxonomy terms. Admin search uses debounced AJAX (`negar_theme_search_brands` with nonce `negar_theme_brand_search`) capped to 20 results with published product counts.
- **Admin Brand Management:**
  - Dedicated Brands tab under Settings → تنظیمات قالب نگار → برندها.
  - Adding, removing, and enabling/disabling managed brands.
  - Keyboard-accessible Move Up / Move Down buttons for reordering.
  - Enforcement of max 20 records and deduplication of term IDs.
  - Optional WordPress Media Library logo override per brand, falling back to taxonomy logo (`wc_get_brand_thumbnail_url()` or `get_brand_thumbnail_url()`), then `wc_placeholder_img_src()`.
- **Frontend Carousel Rendering (`brands-slider.php`):**
  - Displays genuine brand names, logos (native or override), and taxonomy archive links.
  - Neutral section headings (`برندهای منتخب` and `اصالت و تنوع برندها`).
  - Single-brand safety: hides Swiper next/prev navigation buttons when only 1 brand is displayed.
  - Section omission: cleanly omits `.elementor-element-7a921bf6` when 0 brands are enabled or store has no brands.
  - Commercial integrity: completely removed hardcoded ratings (`۳/۳`, `۴/۵`, etc.), star icons (`icon-star-7`), and fake product counts (`+۲۴۰ محصول`).
- **Comprehensive Regression Coverage:**
  - `tests/theme-settings-regression.php`: 20+ Phase 7 assertions verifying unauthorized rejection, sanitization, term validation, deduplication, keyboard ordering, enable/disable, removal, Media Library override, empty omission, layout disabling, and isolation.
  - Safe test fixtures: `negar_theme_test_create_brand_fixture()` and `negar_theme_test_cleanup_brand_fixture()` with unique tokens, fully cleaned up in `finally`.
  - `tests/theme-settings-regression.js`: Browser tests verifying AJAX brand search, adding, keyboard reordering, form persistence, frontend DOM rendering of real brand names and archive links, absence of fake ratings/counts, and 375px mobile viewport responsiveness.
  - Test suites: `npm run test:lint` (114 PHP files passed), `npm run test:settings` (100% passed), `npm run test:e2e` (37 passed, 0 failed), `npm test` (all green, exit code 0).

## Next phase

Phase 8: Branding, typography and colors.
Following approved roadmap: Phase 8 (Typography & Colors), Phase 9 (SMS/OTP), Phase 10 (Backup/Export), Phase 11 (Release QA & Packaging).
