# Implementation Plan: NegarThem Advanced Customer Club & Installment System

**Branch**: `feat/013-customer-club` | **Date**: 2026-11-18 | **Spec**: [specs/013-customer-club/spec.md](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/specs/013-customer-club/spec.md)  
**Input**: Feature specification from `/specs/013-customer-club/spec.md`  
**Status**: `DRAFT — PHASE 2 TECHNICAL PLAN (GATED AT REVIEW-PLAN)`  

---

## Summary

The **NegarThem Advanced Customer Club** («باشگاه مشتریان و خرید اقساطی نگار») delivers a complete installment commerce and loyalty ecosystem for the Negar Shop WooCommerce storefront.

The technical approach decouples:
1. **Storefront Presentation Layer (Theme):** High-fidelity Persian RTL customer club landing page, responsive plan comparison cards, interactive preview calculator, Settings API management tab (`negar_theme_club_options`), and My Account customer endpoints, integrated into the existing crimson design system.
2. **Persistent Financial Core (Decoupled Module):** Custom database schema (`wp_negar_club_*`), application underwriting lifecycle, immutable credit agreements, zero-drift integer Toman (`IRT`) installment scheduling, HPOS-compliant WooCommerce order association, and signature-verified payment webhooks.
3. **Early Server-Authoritative Calculator:** A lightweight, stateless calculation service (`Negar_Club_Quote_Calculator`) available in Phase 4 to guarantee exact server/client parity for public previews without requiring live payment or credit activation.

---

## Technical Context

**Language/Version**: PHP 8.2.29 (Strict typing, 64-bit integer arithmetic) | WordPress Core 7.1.4 / 6.7+ | WooCommerce 10.5.2  
**Primary Dependencies**: WooCommerce Core, WordPress Settings API, WordPress REST API v2, Vanilla JavaScript (ES6+), Vazirmatn / IRANSans typography tokens  
**Storage**:
- Theme Settings: WordPress options table (`negar_theme_club_options`, `autoload = 'no'`).
- Financial Core: MySQL 8.0+ / MariaDB 10.4+ custom InnoDB tables (`wp_negar_club_applications`, `wp_negar_club_contracts`, `wp_negar_club_installments`, `wp_negar_club_payments`, `wp_negar_club_audit_log`).
- WooCommerce Orders: HPOS Custom Orders Table (`wp_wc_orders`, `wp_wc_orders_meta`). Direct SQL to `wp_posts`/`wp_postmeta` is strictly prohibited.  
**Testing**: PHPUnit / custom PHP CLI test suites, Theme Settings regression harness (`npm run test:settings`), Playwright E2E suites across 4 viewports (`375px`, `768px`, `1024px`, `1440px`), PHP linting (`npm run test:lint`).  
**Target Platform**: Windows local WAMP development environment (`x:\apps\wamp\www\wp4\`) / Standard Linux production stack.  
**Project Type**: WordPress Theme Extension + Decoupled Financial Module.  
**Performance Goals**:
- Public Customer Club page TTFB < 200ms on local WAMP.
- Zero extra database queries on unrelated storefront pages (conditional asset loading & non-autoloaded options).
- Deterministic calculation execution < 5ms.  
**Constraints**:
- Strict integer Iranian Toman (`IRT`) monetary values with 0 decimals (`PHP_INT_MAX` safe).
- Gateway boundary: explicit 10x conversion to Iranian Rial (`IRR`) at gateway dispatch; reverse verification on callback.
- RTL Persian storefront continuity, fully responsive down to 375px mobile viewport without horizontal overflow.
- Anti-IDOR object ownership verification on all customer data endpoints.
- Independent payment webhook authentication (HMAC signature or server-to-server check; zero reliance on browser nonces or user sessions).
- Strict adherence to `AGENTS.md`: no duplicate theme folders, no duplicate WordPress installs, no fake customer data or mock payments in production.  
**Scale/Scope**: 3 primary configurable plans (`negar_aghaz`, `negar_plus`, `negar_vizhe`), scalable to tens of thousands of applications, agreements, and installments.

---

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design and Phase 2 architecture.*

| Rule / Principle | Status | Compliance Verification |
| :--- | :---: | :--- |
| **No Duplicate Installs / Themes** | **PASS** | Works directly in `x:\apps\wamp\www\wp4\wp-content\themes\negarThem`. No parallel theme clones or demo databases. |
| **Persian RTL Storefront Continuity** | **PASS** | Uses existing `--color-primary: #de1f38`, Vazirmatn font tokens, native Persian typography, and responsive RTL grid. |
| **Live WooCommerce Data** | **PASS** | Integrates with active WooCommerce HPOS orders and products; zero fictitious orders, reviews, or fake customers. |
| **External Media Origins** | **PASS** | Preserves existing media paths and footer trust assets (`trust-zibal.png`, `trust-enamad.png`). |
| **Automated Verification Harness** | **PASS** | Validates against `npm run test:lint`, `npm run test:settings`, and `npm test`. |
| **Non-Destructive Operations** | **PASS** | Safe backup procedure designed in `quickstart.md`; schema rollback affects only custom `wp_negar_club_*` tables. |
| **Commercial Decision Integrity** | **PASS** | All 22 commercial decisions (`CLUB-BD-001`–`022`) and architecture decision (`CLUB-AD-001`) remain formally gated and marked `PROPOSED — REQUIRES EMPLOYER APPROVAL`. |

---

## Project Structure

### Documentation (`specs/013-customer-club/`)

```text
specs/013-customer-club/
├── spec.md                          # Phase 1 Specification (User stories, FRs, NFRs)
├── business-rules.md                # Phase 1 Business rules & decision register (CLUB-BD-001..022, CLUB-AD-001)
├── acceptance-matrix.md             # Phase 1 Acceptance criteria traceability matrix (CLUB-AC-001..064)
├── architecture-decisions.md        # Phase 2 ADR (CLUB-AD-001 Theme vs Plugin separation)
├── research.md                      # Phase 2 Technical research, HPOS, state machines, math
├── data-model.md                    # Phase 2 Relational database schemas & invariants
├── quickstart.md                    # Phase 2 Local WAMP developer setup & testing guide
├── plan.md                          # Phase 2 Implementation plan (This document)
├── contracts/
│   ├── quote-calculator-api.md      # Phase 2 Quote Calculator contract (Phase 4 preview parity)
│   ├── application-lifecycle-api.md # Phase 2 Application intake & operator review contract
│   └── payment-webhook-contract.md  # Phase 2 Webhook security & settlement contract
└── checklists/
    └── requirements.md              # Phase 1 Specification completeness & consistency review
```

### Source Code Architecture (Decoupled Monorepo Structure - Option B)

```text
# 1. Storefront / Theme Layer (x:\apps\wamp\www\wp4\wp-content\themes\negarThem)
page-templates/
└── page-customer-club.php           # Public customer club template (Phase 4)

template-parts/
└── customer-club/                   # Reusable template components (Phase 4)
    ├── hero.php
    ├── benefits.php
    ├── plans.php
    ├── comparison.php
    ├── calculator.php
    ├── steps.php
    ├── faq.php
    └── cta.php

woocommerce/
└── myaccount/
    └── customer-club.php            # My Account «باشگاه مشتریان من» endpoint view (Phase 7)

assets/
├── css/
│   └── customer-club.css            # Scoped public club stylesheet (Phase 4)
└── js/
    └── customer-club.js             # Interactive calculator & UI interactions (Phase 4)

inc/
├── theme-settings.php               # Tab «باشگاه مشتریان» registration & sanitization (Phase 3)
└── club-quote-calculator.php        # Early server-authoritative quote calculator (Phase 4)

# 2. Persistent Financial Module (Under Proposal CLUB-AD-001 - Target: wp-content/plugins/)
packages/negar-customer-club/        # (Proposed location under Option B - Zero files created in Phase 2)
├── negar-customer-club.php          # Plugin bootstrap & HPOS compatibility
├── includes/
│   ├── class-installer.php          # Database schema migrations (wp_negar_club_*)
│   ├── class-application-manager.php# Application state machine & anti-IDOR
│   ├── class-agreement-engine.php   # Credit agreement snapshots & schedule generation
│   ├── class-payment-gateway.php    # Webhook signature verification & settlement
│   └── class-audit-logger.php       # Append-only operational audit trail
└── tests/
    └── ...
```

---

## Technical Architecture & Separation of Concerns

### 1. ADR Recommendation (`CLUB-AD-001`)
As documented in `architecture-decisions.md`:
- **Theme Responsibility:** Presentation, responsive layout, dynamic CSS tokens, Settings API tab configuration, and public interactive preview.
- **Plugin Responsibility:** Persistent database tables, state machines, WooCommerce HPOS order linkage, payment webhook signature verification, and append-only audit trail.
- **Recommended Option:** **Option B** (Monorepo source structure under `packages/negar-customer-club/` deployed to `wp-content/plugins/negar-customer-club/`), providing unified Git history, atomic commits, seamless WAMP symlink/junction development, and absolute runtime data durability.
- **Governance:** Formally held at `PROPOSED — REQUIRES EMPLOYER APPROVAL`. Zero plugin code is created in Phase 2.

### 2. Theme Settings Integration (`inc/theme-settings.php`)
- **Tab Key:** `customer_club`
- **Option Name:** `negar_theme_club_options`
- **Autoload:** `no`
- **Configuration Structure:**
  - `is_enabled`: boolean (Public page master toggle)
  - `allow_applications`: boolean (Application intake toggle)
  - `plans`: array of 3 plans (`negar_aghaz`, `negar_plus`, `negar_vizhe`) with title, subtitle, initial payment, ceiling, duration, features, badge, featured flag.
  - `sections_visibility`: toggles for benefits, comparison, calculator, timeline, faq.
  - `copy`: hero headings, cta text, disabled notice, faq items.
- **Sanitization:** Strict validation enforcing positive integers for monetary values, `initial_payment <= purchase_ceiling`, text escaping, and URL validation.
- **Backup Support:** Registered in `inc/theme-backup.php` for seamless export/import without leaking customer PII.

### 3. Public UI Architecture (`page-templates/page-customer-club.php`)
- **Layout & Tokens:** Fully responsive RTL layout inheriting `--color-primary`, `--container-width: 1320px`, and standard button components.
- **Calculator State:** Uses `assets/js/customer-club.js` to normalize Persian digits, calculate provisional estimates, display non-binding disclaimers, and handle boundary conditions.
- **Authentication Intent:** Clicking "درخواست طرح" for guests saves selected plan intent into session storage and triggers the theme's native OTP modal; upon successful authentication, the user is redirected directly to the application submission flow.

### 4. Financial Calculation & Webhook Security
- **Server Parity:** Phase 4 leverages the lightweight `Negar_Club_Quote_Calculator` implementing strict integer division and remainder distribution.
- **Webhook Nonce Separation (`CLUB-NFR-007` & `CLUB-AC-047`):**
  - Browser/user requests enforce session cookies, user nonces, and object ownership.
  - Payment webhooks enforce provider digital signatures (HMAC-SHA256) or direct outbound server verification with gateway inquiry APIs, along with currency verification (`1 IRT = 10 IRR`) and database idempotency guards.

---

## Phased Implementation Roadmap & Dependency Review

| Phase | Title | Scope Summary | Preconditions / Dependencies | Gated By |
| :---: | :--- | :--- | :--- | :--- |
| **Phase 3** | **Theme Settings** | Implement «باشگاه مشتریان» tab in `inc/theme-settings.php`, register `negar_theme_club_options`, validation callbacks, and backup integration. | Phase 2 approved. | None |
| **Phase 4** | **Public Customer Club UI** | Page template, template parts, responsive CSS, interactive calculator, and early server-authoritative quote calculator. | Phase 3 settings active. | `CLUB-BD-005`, `006`, `011`, `022` (provisional fallback used if open) |
| **Phase 5** | **Customer Applications** | Application submission endpoint, validation, anti-IDOR checks, and scoped operator review interface (`negar_club_operator`). | Phase 4 UI completed; Plugin packaging approved (`CLUB-AD-001`). | `CLUB-AD-001`, `CLUB-BD-009`, `010` |
| **Phase 6** | **Financial Engine & Payments** | Database tables, HPOS order linkage, immutable agreement snapshots, installment generation, webhook verification, and settlement. | Phase 5 applications; Commercial policies approved. | **BLOCKED BY ALL COMMERCIAL DECISIONS (`CLUB-BD-001`–`022`)** |
| **Phase 7** | **My Account Customer Club** | «باشگاه مشتریان من» endpoint in WooCommerce My Account (`/my-account/customer-club/`), application tracking, installment due dates, real payment history. | Phase 5/6 entities active. | None |
| **Phase 8** | **Storefront Integration & Hardening** | Single product installment banners, cart/checkout prompts, security audit (CSRF, IDOR, SQL injection), and responsive polish. | Phases 3–7 completed. | None |
| **Phase 9** | **Release Packaging & Convergence** | End-to-end regression testing, translation catalog updates, zip build packaging, documentation finalization. | All acceptance criteria verified. | Final Employer Approval |

---

## Spec Kit Workflow Review Gate Analysis

### Canonical Workflow Definition (`.specify/workflows/speckit/workflow.yml`)
The project's installed Spec Kit workflow explicitly specifies:

```yaml
  - id: plan
    command: speckit.plan
    integration: "{{ inputs.integration }}"
    input:
      args: "{{ inputs.spec }}"

  - id: review-plan
    type: gate
    message: "Review the plan before generating tasks."
    options: [approve, reject]
    on_reject: abort

  - id: tasks
    command: speckit.tasks
    integration: "{{ inputs.integration }}"
    input:
      args: "{{ inputs.spec }}"
```

### Review Gate Status & Decision
1. **Mandatory Human Gate:** The step `review-plan` is an explicit, mandatory review gate designed to ensure that the employer/system architect reviews and approves:
   - The architectural separation of Theme vs. Financial Plugin (`CLUB-AD-001`).
   - The data model and relational schemas (`data-model.md`).
   - The integration contracts (`contracts/`).
   - The testing and rollback procedures (`quickstart.md`).
2. **Phase 2 Boundary:** In strict compliance with Prompt Section 3 ("If the actual workflow requires explicit external approval before `tasks`, finish and commit the plan/research/design artifacts, report the gate, and STOP. The external reviewer will authorize the remaining Phase 2 tasks separately"), the generation of `tasks.md` is **held at this gate**.
3. **Execution Ready:** The detailed phase boundaries and task prerequisites defined above provide complete transparency, enabling immediate task generation upon external approval of the `review-plan` gate.
