# Developer Quickstart & Verification Guide: NegarThem Customer Club

**Feature:** 013-customer-club  
**Project:** Negar Shop WordPress Theme (`negarThem`)  
**Target Environment:** Local WAMP (`http://localhost/wp4/`)  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

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

## 2. Standard Test & Validation Commands

All test and linting commands are managed via `package.json` in the theme root:

```powershell
# 1. Validate PHP syntax and code standards
npm run test:lint

# 2. Run Theme Settings & Backup regression tests
npm run test:settings

# 3. Run Playwright E2E visual & functional tests (requires local WAMP running)
npm run test:e2e

# 4. Run unified test suite
npm test

# 5. Build distribution archive (theme zip)
npm run build:dist
```

*Note:* In Phase 2, all tests must pass cleanly against the approved baseline without adding any broken or unverified code.

---

## 3. Safe Database Backup & Migration Preflight

Before any future database-modifying phase (Phase 5/6) is initiated, a full MySQL backup must be captured:

### 3.1 Locate MySQL Dump Binary on Windows WAMP
Because `mysqldump.exe` is not placed on the global `$env:PATH` by WAMP, locate it dynamically in PowerShell:

```powershell
$dumpExe = Get-ChildItem "X:\apps\wamp\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
if (-not $dumpExe) {
    $dumpExe = Get-ChildItem "C:\wamp64\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
}
Write-Host "Discovered MySQL Dump: $dumpExe"
```

### 3.2 Capture Pre-Migration Backup
```powershell
& $dumpExe -u root wp4 > "backups/pre-customer-club-migration.sql"
```

### 3.3 Safe Rollback Rule
In the event of a migration test failure during future phases:
- Drop **only** the custom `wp_negar_club_*` tables.
- **Never** drop core WordPress (`wp_posts`, `wp_users`, `wp_options`) or WooCommerce tables (`wp_wc_orders`).

---

## 4. Test Mocking & Zero-Fake-Payment Policy

In accordance with `AGENTS.md` and project regulations:
1. **No Live Gateway Calls:** Live Iranian payment gateways (Zibal, ZarinPal, Shaparak) are not active in this development environment. Payment tests must execute against the isolated mock adapter specified in `contracts/payment-webhook-contract.md`.
2. **Zero Fake Customer Data:** Never invent real national identification codes, phone numbers, bank account numbers, or fraudulent customer reviews. Test fixtures must use reserved test identifiers (e.g., phone `09000000000`, national ID test hash `TEST_NATIONAL_HASH_001`).
3. **No Fictitious Settlement:** Never record a real financial debt as paid unless backed by the verified test harness assertion.

---

## 5. Phase 2 Scope Boundaries Reminder

During **Phase 2 (Architecture & Planning)**:
- ❌ Do NOT create or edit PHP application files in `inc/`, `woocommerce/`, or `page-templates/`.
- ❌ Do NOT create or run database migration scripts.
- ❌ Do NOT create plugin files in `wp-content/plugins/`.
- ❌ Do NOT modify WordPress database options or WooCommerce order records.
- ✅ DO review, verify, and commit specification and architectural design documents.
