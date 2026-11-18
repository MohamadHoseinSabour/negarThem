# Developer Quickstart & Verification Guide: NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Target Environment:** Local WAMP (`http://localhost/wp4/`)
**Phase:** Phase 2.2 — Final Architecture Consistency & Financial Lifecycle Audit
**Status:** `DRAFT — PHASE 2.2 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

---

## 1. Local Environment Preconditions

Before executing any tests or inspecting Customer Club artifacts, ensure your local environment satisfies the verified baseline:

1. **WordPress Core:** WordPress 6.7+ / 7.1.4 active at `x:\apps\wamp\www\wp4\`.
2. **WooCommerce:** Version 10.5.2+ installed and active.
3. **HPOS Enabled:** WooCommerce → Settings → Advanced → Features → **High-Performance order storage** must be active.
4. **Currency Settings:** WooCommerce currency set to Iranian Toman (`IRT`) with **0** decimals (`Number of decimals: 0`).
5. **Theme Directory:** `x:\apps\wamp\www\wp4\wp-content\themes\negarThem`.
6. **Git Branch:** Active development on `feat/013-customer-club`.

---

## 2. Monorepo Local Development Setup (Option B)

Under the recommended monorepo architecture (`CLUB-AD-001` Option B), the persistent plugin source lives in `packages/negar-customer-club/`.

### 2.1 Directory Junction Setup (PowerShell Administrator)
To link the plugin source directly into the local WAMP WordPress plugins directory without duplicating files:

```powershell
# Create NTFS Directory Junction (Run once when plugin scaffolding is authorized)
New-Item -ItemType Junction `
  -Path "X:\apps\wamp\www\wp4\wp-content\plugins\negar-customer-club" `
  -Target "X:\apps\wamp\www\wp4\wp-content\themes\negarThem\packages\negar-customer-club"
```

*Effect:* Any edits in `packages/negar-customer-club/` are reflected immediately in WordPress admin and runtime.

---

## 3. Standard Test & Validation Commands

All test and linting commands are managed via `package.json` in the theme root:

```powershell
# 1. Validate PHP syntax and code standards (Safe, read-only)
npm run test:lint

# 2. Run Playwright E2E visual & functional tests (requires local WAMP running)
npm run test:e2e

# 3. Build distribution archives
npm run build:dist
```

### 3.1 Critical Note on `npm run test:settings` (Correction C8 Reclassification)
> [!WARNING]
> **Database Safety & Pre-existing Test Finding Classification:**
> During Phase 2 preflight, `npm run test:settings` reported an assertion failure:
> `[FAIL] Theme settings regression: Homepage SSR HTML must include banner record compact-12-one`
>
> **Finding Classification:**
> $$\textbf{Status: DIAGNOSED — NOT CODE-FIXED / NOT RE-VERIFIED}$$
>
> **Root Cause Diagnosis:**
> Code inspection of `tests/theme-settings-regression.js` (lines 274–293) confirmed that `compact-12-one` belongs to section `sec-12-banners-compact`, but was **omitted from `bannerSectionMap`** in the test runner. Consequently, the test runner failed to conditionally skip this banner when section 12 is disabled or when the homepage is rendered via Elementor, triggering an assertion failure at line 292.
>
> In accordance with project instructions and strict database safety rules:
> - **DO NOT re-run `npm run test:settings` against the local live WAMP database** during documentation phases.
> - The test suite creates and restores live database option snapshots (`tests/theme-settings-regression.php`).
> - PHP code quality is fully protected by `npm run test:lint`, which passed 100% on all 122 PHP files.
> - The code fix (adding `'compact-12-one': 'sec-12-banners-compact'` to `bannerSectionMap`) is documented and deferred to an authorized code phase.

---

## 4. Safe Database Backup & Migration Preflight

Before any future database-modifying phase (Phase 5/6) is initiated, a full MySQL backup must be captured:

### 4.1 Locate MySQL Dump Binary on Windows WAMP
Because `mysqldump.exe` is not placed on the global `$env:PATH` by WAMP, locate it dynamically in PowerShell:

```powershell
$dumpExe = Get-ChildItem "X:\apps\wamp\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
if (-not $dumpExe) {
    $dumpExe = Get-ChildItem "C:\wamp64\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
}
Write-Host "Discovered MySQL Dump: $dumpExe"
```

### 4.2 Capture Pre-Migration Backup
```powershell
& $dumpExe -u root wp4 > "backups/pre-customer-club-migration.sql"
```

### 4.3 Safe Data-Preserving Migration & Rollback Strategy (Correction C7)
In the event of a migration failure or schema reversion:
- **Pre-Migration Snapshot:** Always restore from `backups/pre-customer-club-migration.sql` if a migration fails mid-flight.
- **Migration Version Tracking:** Schema version is tracked via `negar_club_schema_version` option; migrations must be strictly idempotent.
- **Data Preservation Rule:** Down-migrations must reverse DDL without dropping populated customer data.
- **Prohibition Against Dropping Populated Tables:** In production or populated staging environments, dropping `wp_negar_club_*` tables is **strictly prohibited** to protect legal credit agreements and audit trails.

---

## 5. Test Mocking & Zero-Fake-Payment Policy

In accordance with `AGENTS.md` and project regulations:
1. **No Live Gateway Calls:** Live Iranian payment gateways (Zibal, ZarinPal, Shaparak) are not active in this development environment. Payment tests must execute against the isolated mock adapter specified in `contracts/payment-webhook-contract.md`.
2. **Zero Fake Customer Data:** Never invent real national identification codes, phone numbers, bank account numbers, or fraudulent customer reviews. Test fixtures must use reserved test identifiers (e.g., phone `09000000000`, national ID test hash `TEST_NATIONAL_HASH_001`).
3. **No Fictitious Settlement:** Never record a real financial debt as paid unless backed by the verified test harness assertion.

---

## 6. Phase 2.2 Scope Boundaries Reminder

During **Phase 2.2 (Final Architecture Consistency & Financial Lifecycle Audit)**:
- ❌ Do NOT create or edit PHP application files in `inc/`, `woocommerce/`, or `page-templates/`.
- ❌ Do NOT create or run database migration scripts.
- ❌ Do NOT create plugin files in `wp-content/plugins/`.
- ❌ Do NOT modify WordPress database options or WooCommerce order records.
- ❌ Do NOT run `npm run test:settings` against the local database.
- ❌ Do NOT create `tasks.md` or execute `speckit.tasks`.
- ❌ Do NOT approve or bypass the `review-plan` gate.
- ✅ DO review, verify, and commit architectural corrections in Markdown.
