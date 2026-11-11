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

## Next phase

Phase 5 may add validated banner, story and brand media collections. It is not
implemented by Phase 4.
