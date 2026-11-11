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

## Next phase

Phase 3 may define layout controls only after a separate ownership and accessibility
review. It is not implemented by Phase 2.
