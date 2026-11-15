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
- `a372352` `test(settings): verify brand management and carousel`

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

## Phase 8 — Branding, Typography and Colors

**Status:** PASS (COMPLETE & VERIFIED)
**Baseline:** `a372352123d4a0fc7a5ccdc1e0c4b22c67537b98`
**Commits:**
- `dc9ea81` `feat(settings): add practical typography controls`
- `2fa322a` `feat(theme): apply branding typography and color settings`
- [test(settings): verify phase eight settings and sync docs]

### Summary of Accomplishments:
- **Typography Schema & Settings Architecture:**
  - Added `typography` group to `negar_theme_options` with defaults: `font_family => 'vazirmatn'`, `heading_font_family => 'vazirmatn'`, `base_font_size => 16`, `heading_scale => 'normal'`.
  - Whitelisted font stacks: `vazirmatn` (Google Fonts default), `system` (dependency-free stack), and `tahoma` (Persian system standard).
  - Bounded base font size (clamped 14–18px) and validated heading scales (`compact`, `normal`, `large`).
  - Added dedicated Typography tab under Settings → تنظیمات قالب نگار → تایپوگرافی with form controls and Persian live preview box.
  - Added "Restore Defaults" reset actions for both Colors (restoring canonical crimson `#e90544` / `#c40439`) and Typography.
- **Branding & Site Identity Ownership:**
  - Maintained core WordPress Site Identity ownership of site title, icon, and custom logo.
  - Provided `negar_theme_logo_url()` helper across desktop header (`main-header.php`), mobile header (`mobile-header.php`), and JSON-LD SEO schema (`seo-schema.php`), prioritizing Customizer `custom_logo` with clean demo SVG fallback.
  - Added direct admin link in the General tab to WordPress Customizer Site Identity controls.
- **Frontend Design Token Integration & Performance:**
  - Emitted `--font-primary`, `--font-heading`, `body { font-size: Xpx; }`, and heading scale CSS rules via `negar_shop_customizer_css()`.
  - Optimized resource loading in `inc/enqueue.php`: conditionally enqueues Google Fonts Vazirmatn and preconnect resource hints only when `vazirmatn` is selected for body or heading.
  - Updated `kavehShopData.fontFamily` to reflect the resolved body font name.
- **Comprehensive Regression Verification:**
  - PHP regression tests in `tests/theme-settings-regression.php` verifying unauthorized write rejection, persistence, group isolation, whitelist fallback, base size clamping, restore defaults for typography and colors, and custom logo resolution.
  - Playwright browser tests in `tests/theme-settings-regression.js` verifying Typography tab controls, form persistence, frontend computed styles (`--font-primary`, `--font-heading`, `bodyFontSize`), restore defaults dialog, and General tab Site Identity link.
  - Clean database snapshot restoration verified: zero test pollution in WordPress options or theme mods.
  - Test suites: `npm run test:lint` (PASS, 114 PHP files), `npm run test:settings` (PASS), `npm run test:e2e` (37 passed, 0 failed), `npm test` (all green, exit code 0).

## Phase 8.1 — Admin Access & Color Consistency Fix

**Status:** PASS (COMPLETE & VERIFIED)
**Baseline:** `bbec4ed`
**Commits:**
- `b510439` `fix(admin): expose NegarThem settings in dashboard`
- `8661e87` `fix(theme): unify dynamic accent colors across storefront`
- `37273e8` `test(theme): verify admin access and color consistency`

### Summary of Accomplishments:
- **Top-Level Admin Dashboard Access:**
  - Added prominent top-level WordPress dashboard menu `نگارتم` (Dashicon `dashicons-art`, position 59) routing to `admin.php?page=negar-theme-settings`.
  - Preserved existing route `options-general.php?page=negar-theme-settings`, deep links, Settings API behavior, and `manage_options` authorization.
  - Updated admin asset enqueue hook (`negar_theme_enqueue_settings_assets`) to support both `settings_page_negar-theme-settings` and `toplevel_page_negar-theme-settings`.
  - Preserved base page context (`admin.php` vs `options-general.php`) in navigation tabs so users stay within the chosen administrative path.
- **Dynamic Accent Color & Design Token Harmonization:**
  - Clarified primary vs secondary/hover relationship in admin settings UI (`رنگ اصلی سازمانی (Primary)` and `رنگ ثانویه / هاور (Secondary / Hover)`).
  - Eliminated hardcoded decorative red tokens (`--color-primary-light`, `--color-accent-light`, `--color-accent-hover`) by deriving them dynamically via CSS custom properties and `color-mix()`.
  - Harmonized theme-accent elements across storefront: category buttons (`.btn-nk-danger`, `.categories-nk__item .btn-nk-danger-2`), promotional banners (`.banners-nk__item .detail .more`), product card action buttons, add-to-cart, buy-now, card glow (`--ppnk-card-glow`), timers, discount badges (`.price .off span`), daily deal wrapper gradient and contrast button, single product page buttons and price, navigation accents (`.category-menu-btn`), header cart badge, and Tailwind rose utilities (`.bg-rose-600`, `.hover:bg-rose-700`, `.text-rose-600`, etc.).
  - Preserved semantic alerts/errors/stock warnings (`.woocommerce-error`, `.out-of-stock`, `.badge-danger-stock`) untouched.
  - Ensured canonical crimson palette (`#e90544` / `#c40439`) remains visually identical to default.
- **Comprehensive Browser Verification with Real Computed Styles:**
  - Verified default palette (#e90544/#c40439) and custom green palette (#16834a/#116638) on 1440px desktop and 390px mobile viewports.
  - Verified computed styles for category buttons, banner CTAs, header category button, card glows, and hover transition states.
  - Integrated into `tests/theme-settings-regression.js`.
  - Test suites: `npm run test:lint` (PASS, 115 PHP files), `npm run test:settings` (PASS), `npm run test:e2e` (37 passed, 0 failed), `npm test` (all green, exit code 0).

## Phase 9 — SMS Provider & Secure OTP Integration

**Status:** PASS (COMPLETE & VERIFIED)
**Baseline:** `4aa48acef0fb8e74e889f527a92023293d8387ff`
**Commits:**
- `f5b310c` `feat(settings): add secure Kavenegar SMS configuration`
- `1d1f0ab` `feat(auth): connect SMS provider and harden OTP login`
- `bbec4ed` `test(auth): verify provider integration and OTP security`

### Summary of Accomplishments:
- **SMS Configuration & Secrets Isolation Architecture:**
  - Registered dedicated non-autoloaded options `negar_theme_sms_options` (general options: `enabled`, `provider`, `template`) and `negar_theme_sms_secrets` (isolated API key storage).
  - Credentials are never stored in `negar_theme_options`, never exposed in JavaScript/DOM, and never included in public AJAX or export paths.
  - Added dedicated SMS tab in WordPress Admin (`Settings → تنظیمات قالب نگار → سامانه پیامکی` / `tab=sms`) with masked password input, configuration status badge, and server constant fallback (`NEGAR_KAVENEGAR_API_KEY`).
  - Implemented blank-key preservation (submitting empty key preserves stored secret) and explicit deletion action (`negar_theme_sms_delete_key`) with confirmation dialog.
- **Provider Implementation & Dispatch Hook:**
  - Implemented practical Kavenegar provider (`negar_kavenegar_send_lookup`) using Verify Lookup REST API with honest Persian error mapping (insufficient credit, invalid receptor, pending template, inactive line, unauthorized key).
  - Connected provider to the existing `negar_dispatch_otp_sms` filter hook with transient error tracking.
- **OTP Security & Hardened Customer-Only Policy:**
  - Implemented strict customer-only authentication policy (`negar_is_user_privileged`): rejected administrator, editor, author, contributor, and shop manager accounts from public OTP login with an honest Persian security error.
  - Implemented duplicate phone detection: rejected phone numbers associated with multiple customer accounts to prevent ambiguous logins.
  - Implemented client IP rate limiting (max 15 requests per IP per hour) alongside the existing per-phone hourly limit (5 requests per phone per hour).
  - Restricted test transport (`negar_is_test_transport_allowed`) strictly to verified local/development environments with explicit flag; disallowed on staging/production.
  - Zero OTP code or credential disclosure in AJAX responses or logs.
- **Comprehensive Regression Verification:**
  - PHP regression tests in `tests/theme-settings-regression.php` verifying unauthorized save rejection, settings persistence, secrets isolation, blank key preservation, key deletion, provider HTTP mock scenarios (success, credit error, template error, network timeout), filter failure handling, customer-only policy, ambiguous phone detection, and IP rate limiting.
  - Playwright browser tests in `tests/theme-settings-regression.js` verifying SMS tab fields, configuration badge, settings persistence, blank key preservation, and key deletion.
  - Security suite `tests/security-otp-disclosure.test.js` verifying zero code disclosure and valid OTP responses.
  - Snapshot and restore logic updated to cleanly isolate and restore `negar_theme_sms_options` and `negar_theme_sms_secrets`.
  - Test suites: `npm run test:lint` (PASS, 115 PHP files), `npm run test:settings` (PASS), `node tests/security-otp-disclosure.test.js` (PASS), `npm run test:e2e` (37 passed, 0 failed), `npm test` (all green, exit code 0).

## Phase 10 — Final Carryovers + Settings Backup / Import / Export

**Status:** PASS (COMPLETE & VERIFIED)
**Baseline:** `bbec4edca0755511a211e08f8f26160dce827d4e`
**Commits:**
- `9bda986` `fix(auth): reject unbound phone username associations`
- `e25fdd4` `feat(settings): add safe configuration backup and restore`
- `9cc8ba7` `test(settings): verify backup restore and final carryovers`

### Summary of Accomplishments:
- **Carryover 1: Top-Level Admin Dashboard Access:**
  - Added visible top-level WordPress dashboard menu `نگارتم` (Dashicon `dashicons-art`, position 59) routing to `admin.php?page=negar-theme-settings`.
  - Preserved existing route `options-general.php?page=negar-theme-settings`, deep links, Settings API behavior, and `manage_options` capability check.
  - Preserved base page context (`admin.php` vs `options-general.php`) in navigation tabs.
- **Carryover 2: Dynamic Color Consistency Across Storefront:**
  - Replaced hardcoded red tokens with dynamic CSS properties and `color-mix()`.
  - Harmonized category buttons, promotional banners, product cards, add-to-cart, buy-now, card glows (`--ppnk-card-glow`), discount badges, timers, daily deal gradient, single-product page actions, header category button, and Tailwind rose utilities.
  - Verified default canonical crimson (`#e90544`, `#c40439`) and custom green (`#16834a`, `#116638`) on desktop (1440px) and mobile (390px).
- **Carryover 3: Customer OTP Account-Association Security:**
  - In `inc/auth-otp.php`, eliminated vulnerable fallback accepting username matches when `billing_phone` is empty or mismatched.
  - Strictly requires explicit, trustworthy phone-to-account binding (`billing_phone === $phone`).
  - Safely rejects ambiguous or unbound existing accounts with an honest Persian error directing to password login or support.
- **Phase 10 Settings Backup, Export, Preview & Restore:**
  - Implemented versioned JSON configuration export (`negar_theme_settings_backup` v1.0) in `inc/theme-backup.php` covering the 5 supported configuration groups: `negar_theme_options`, `negar_theme_media_options`, `negar_theme_product_pack_options`, `negar_theme_story_options`, and `negar_theme_brand_options`.
  - Strictly excluded all secrets (`negar_theme_sms_secrets`, API keys), SMS settings, passwords, OTP transients, customer records, orders, and database tables.
  - Generated export downloads on-the-fly with CSRF nonce verification; no JSON files written to public uploads directories.
  - Implemented upload and preview workflow in Backup tab (`tab=backup`) with file size boundary (max 2 MB), JSON validation, format/version check, disallowed key rejection, unknown group rejection, and cross-site reference checks.
  - Implemented atomic apply and restore: takes full pre-write snapshots of all 5 options, verifies all writes, and automatically rolls back all settings if any write or verification check fails.
  - Preserved non-autoloaded settings behavior for large repeater options.
- **Comprehensive Regression Verification:**
  - PHP regression tests in `tests/theme-settings-regression.php` (section 11 and 12) verifying unbound phone account rejection, mismatched phone rejection, duplicate phone rejection, valid customer acceptance, export payload structure, secrets exclusion, invalid JSON rejection, preview isolation, apply persistence, empty collection preservation, cross-site reference clearing, and rollback restoration.
  - Playwright browser tests in `tests/theme-settings-regression.js` verifying Backup tab UI (Export, Import, Information cards), authenticated export download, payload structure, secrets absence, and unauthenticated request rejection.
  - Test suites: `npm run test:lint` (PASS, 116 PHP files), `npm run test:settings` (PASS), `node tests/security-otp-disclosure.test.js` (PASS), `npm run test:e2e` (37 passed, 0 failed), `npm test` (all green, exit code 0).

## Phase 11 — Final QA, Critical Fixes & Release

**Status:** PASS (COMPLETE, VERIFIED & RELEASE READY)
**Baseline:** `e27c85f0fbeba3f2f82b0ca9e860b3e4d5a0e1f8`

### Summary of Accomplishments:
- **Step 1: Backup Import Defect Fixes & Real Round-Trip:**
  - Implemented `negar_theme_sanitize_full_settings()` in `inc/theme-settings.php` to sanitize all theme settings groups independently without requiring `$_POST['negar_theme_settings_tab']`.
  - Updated `inc/theme-backup.php` supported groups to use `negar_theme_sanitize_full_settings` for `NEGAR_THEME_SETTINGS_OPTION`.
  - Updated `negar_theme_sanitize_settings()` fallback so that when no tab is passed (import/restore operations), it invokes full settings sanitization instead of discarding imported values.
  - Corrected backup reference schemas in `inc/theme-backup.php`:
    - `NEGAR_THEME_PRODUCT_PACK_OPTION`: checks `$clean['packs'][$pack_id][$slot_id]` and resets non-existent or uncatalogued products to 0 with descriptive warnings.
    - `NEGAR_THEME_STORY_OPTION`: checks `$clean['stories']` with `thumbnail_id`, `image_id`, `video_id`, `poster_id`, `product_id`, validates video mime type and published catalog products.
    - `NEGAR_THEME_BRAND_OPTION`: checks `$clean['brands']` with `term_id`, `enabled`, and `logo_override_id` against WooCommerce `product_brand` taxonomy.
  - Real end-to-end round trip test implemented and verified: snapshot -> set distinct values -> export JSON -> mutate database -> preview import (verified 0 writes) -> apply import -> confirm exact original values restored -> rollback test -> restore initial snapshot.
- **Step 2: Admin Dashboard & Settings Verification:**
  - Verified prominent top-level dashboard menu `نگارتم` (Dashicon `dashicons-art`, position 59) routing to `admin.php?page=negar-theme-settings`.
  - Verified all 12 tabs: General, Colors, Typography, Texts, Layout, Products, Media, Packs, Stories, Brands, SMS, and Backup.
  - Verified strict `manage_options` capability check on all administrative actions and Settings API forms.
- **Step 3: Color and Design Verification:**
  - Verified canonical crimson `#e90544` / `#c40439` and custom green `#16834a` / `#116638` palettes.
  - Dynamic `color-mix()`, `--ppnk-card-glow`, buy-now, add-to-cart, badges, category buttons.
- **Step 4: Storefront Smoke Tests:**
  - Verified homepage (35 sections), shop and category archives, single product, cart and checkout, customer account and OTP login.
- **Step 5: Accessibility and RTL Review:**
  - Tested viewports 390px, 768px, and 1440px with zero horizontal overflow, correct Persian RTL typography and layout.
- **Step 6: Security Audit:**
  - Zero OTP code disclosure, non-autoloaded SMS secrets in `negar_theme_sms_secrets`, CSRF nonces on all actions.
- **Step 7: Compatibility:**
  - Updated `style.css` to `Requires PHP: 8.0` due to `str_starts_with()` usage.
- **Step 8: Automated Test Verification:**
  - `npm run test:lint`: PASS (116 PHP files).
  - `tests/theme-settings-regression.php`: PASS (all 136 assertions).
  - `tests/theme-settings-regression.js`: PASS.
  - `node tests/security-otp-disclosure.test.js`: PASS.
  - `npm run test:e2e`: PASS (37 tests passed, 0 failed).
  - `npm test`: PASS (full CI green).
- **Step 9: Production Distribution:**
  - Packaged installable production archive `dist/negar-shop.zip` excluding `.git`, `node_modules`, `tests`, `scripts`, `docs`, and `specs`.

## Project Completion

All phases (Phase 0 through Phase 11) of the NegarThem Theme Settings Project are complete, verified, and released.
