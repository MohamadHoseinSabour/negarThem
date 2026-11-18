# Architecture Decision Records (ADR): NegarThem Customer Club

**Feature:** 013-customer-club  
**Project:** Negar Shop WordPress Theme (`negarThem`)  
**Date:** 2026-11-18  
**Author:** Senior WordPress & WooCommerce Architect Agent  
**Status:** `PROPOSED — REQUIRES EMPLOYER APPROVAL`  

---

## Record 1: Separation of Theme Presentation and Persistent Financial Module (`CLUB-AD-001`)

### Status
`PROPOSED — REQUIRES EMPLOYER APPROVAL`  
*(This architectural decision remains formally open and gated until the employer/external reviewer explicitly selects and approves an option. No plugin code, repository, or database table may be created prior to explicit authorization.)*

---

### Context & Problem Statement

The NegarThem Advanced Customer Club feature introduces two fundamentally distinct categories of functionality:

1. **Storefront Presentation & Experience (Theme Domain):**
   - High-fidelity Persian RTL visual presentation, plan display cards, feature matrices, interactive calculator UI, FAQ accordions, and hero banners.
   - Design system continuity: CSS custom properties (`--color-primary`, `--color-primary-dark`, typography tokens, container widths, RTL margins).
   - Theme settings integration: An administrative tab inside the existing NegarThem Settings API interface (`negar_theme_club_options`) allowing store managers to configure plan titles, marketing badges, descriptive copy, FAQs, and section visibility.
   - Storefront routing & templates (`page-templates/page-customer-club.php`, `woocommerce/myaccount/customer-club.php`).
   - Contextual product badges and storefront links.

2. **Persistent Financial & Regulatory Core (Financial Domain):**
   - Formal customer credit applications, identity verification references, and scoring metadata.
   - State machine governing underwriting, operator reviews, approvals, expirations, and rejections.
   - Legally binding `CreditAgreement` records with frozen, immutable snapshots of plan terms.
   - Deterministic installment schedule generation and ledger balance tracking.
   - Payment initiation, webhook signature verification, replay protection, and double-settlement guards.
   - Immutable, append-only operational audit trails.
   - Scoped operator capabilities (`negar_club_operator`) avoiding blanket `manage_options`.
   - **Crucial Invariant:** Financial obligations, customer debt records, installment schedules, and payment histories **must survive theme switching**. If a store administrator changes the active WordPress theme (e.g., during a redesign, seasonal event, or maintenance), customer credit agreements and unpaid debts must remain intact, accessible, and enforceable.

WordPress core architectural standards dictate that themes handle presentation, while persistent business logic, custom tables, and financial lifecycles belong in plugins. However, development ergonomics in a local WAMP environment, Git version control, and continuous integration require a rigorous evaluation of how this separation is physically packaged and versioned.

---

### Architectural Boundaries & Component Ownership

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           STOREFRONT / THEME LAYER                             │
│                     (x:\...\wp-content\themes\negarThem)                         │
│                                                                                 │
│  • Public Page Template (page-templates/page-customer-club.php)                 │
│  • Template Parts (template-parts/customer-club/*.php)                          │
│  • Presentation Assets (assets/css/customer-club.css, assets/js/calculator.js) │
│  • Theme Settings Tab («باشگاه مشتریان» in inc/theme-settings.php)              │
│  • Options Storage: negar_theme_club_options (autoload=no)                     │
│  • My Account Endpoint View Hook (woocommerce/myaccount/customer-club.php)      │
│  • Early Server Quote Interface (Provisional preview calculations)             │
└──────────────────────────────────────┬──────────────────────────────────────────┘
                                       │ Public Filters / Actions / Contract API
                                       ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                      PERSISTENT FINANCIAL MODULE / PLUGIN                       │
│                    (wp-content/plugins/negar-customer-club)                     │
│                                                                                 │
│  • Database Schema & Migrations (wp_negar_club_applications, contracts, etc.)   │
│  • Application Intake & Scoped Operator Review Workflow                         │
│  • State Machines (Application, Agreement, Installment, Payment)                │
│  • WooCommerce HPOS Order Association & Initial Payment Verification            │
│  • Authoritative Financial Engine (Schedule generation, zero-drift math)        │
│  • Payment Webhook Ingestion & Signature Verification (Independent of Nonces)   │
│  • Append-Only Operational Audit Trail (wp_negar_club_audit_log)                │
│  • Scoped Capabilities & Access Control (negar_club_operator)                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

### Evaluation of Packaging & Version Control Options

We evaluate three concrete options for managing this separation across 12 criteria:

#### Option A: Dedicated Plugin in a Separate GitHub Repository
- **Description:** A completely separate Git repository (`github.com/MohamadHoseinSabour/negar-customer-club`) is created for the plugin. The active theme repository (`negarThem`) contains only the theme presentation templates, styles, scripts, and theme settings tab.
- **Workflow:** Deployed to `wp-content/plugins/negar-customer-club` via standard plugin release or clone. Theme references the plugin via standard WordPress hooks (`has_action`, `apply_filters`, or class existence checks).

#### Option B: Dedicated Plugin Source in Monorepo Structure with Build Packaging
- **Description:** The plugin source is maintained within the existing repository under a dedicated top-level directory (e.g., `packages/negar-customer-club/` or `plugins/negar-customer-club/`). A build/packaging script (via `npm run build:plugin` or symlink/junction in local WAMP) synchronizes or packages the plugin into `wp-content/plugins/negar-customer-club/`.
- **Workflow:** Single repository for all Customer Club code; atomic commits across theme and plugin; unified CI/linting pipeline; explicit separation of runtime targets.

#### Option C: Git Submodule or Composer Package
- **Description:** The plugin is maintained in a separate repository but embedded into the theme repository or WAMP environment as a Git Submodule or managed via Composer (`composer require mohamadhoseinsabour/negar-customer-club`).
- **Workflow:** Dual repository tracking with submodule pointers or composer lockfile.

---

### Detailed Comparison Across 12 Architectural Criteria

| Criterion | Option A (Separate Git Repo) | Option B (Monorepo Directory + Packaging) | Option C (Git Submodule / Composer) |
| :--- | :--- | :--- | :--- |
| **1. Data Durability** | **Excellent:** Plugin is installed in `wp-content/plugins/`. Switching or deleting the theme leaves all financial tables, applications, and agreements 100% untouched. | **Excellent:** Deployed to `wp-content/plugins/`. Financial tables and lifecycles remain completely decoupled from active theme lifecycle. | **Excellent:** Installed in `wp-content/plugins/` independently of theme. Data durability is preserved. |
| **2. Git Traceability** | **Moderate:** Two independent commit histories. Atomic cross-layer changes (e.g., theme contract updated simultaneously with plugin hook) require synchronized commits across two repos. | **Superior:** 100% unified Git history. Specification, theme templates, and financial module evolve in lockstep with atomic conventional commits and Spec Kit traceability. | **Poor/Complex:** Submodule detached HEADs, pointer updates, and merge friction frequently cause version desynchronization. |
| **3. Local WAMP Development** | **Moderate:** Requires cloning second repo into `x:\apps\wamp\www\wp4\wp-content\plugins\`. Developer must manage two active working copies. | **Superior:** Single working directory (`x:\apps\wamp\www\wp4\wp-content\themes\negarThem`). A standard Windows directory junction or build script links `plugins/negar-customer-club` directly to `wp-content/plugins/negar-customer-club`. | **Complex:** Submodule clone commands, recursive updates, and Windows symlink permission hurdles introduce friction. |
| **4. CI & Automated Testing** | **Moderate:** Requires multi-repo CI workflows or orchestration to test theme + plugin integration end-to-end. | **Superior:** Existing `npm test`, `npm run test:lint`, and Playwright test suites run in a single environment against both layers without external repo dependencies. | **Complex:** Requires submodule checkout actions or private composer token management in CI pipelines. |
| **5. Release Packaging** | **Good:** Plugin has independent release tags (`v1.0.0.zip`). Theme has separate theme zip (`dist/negar-shop.zip`). | **Excellent:** Existing `npm run build:dist` can package `dist/negar-shop.zip` and `dist/negar-customer-club.zip` cleanly in one build step. | **Moderate:** Requires managing tags and release artifacts across multiple repositories. |
| **6. Upgrade Compatibility** | **High:** Theme updates do not overwrite plugin files; plugin updates do not overwrite theme files. Standard WP plugin updater can be used. | **High:** Theme and plugin are deployed to separate directories (`themes/negarThem` vs `plugins/negar-customer-club`). Updating one does not overwrite the other. | **High:** Standard WordPress update channels apply once deployed. |
| **7. Independent Plugin Activation** | **Yes:** Active/inactive status managed in `wp-admin/plugins.php`. Theme gracefully degrades when plugin is inactive. | **Yes:** Plugin resides in `wp-content/plugins/` and is activated/deactivated independently via WordPress admin or WP-CLI. | **Yes:** Managed independently in WordPress plugin directory. |
| **8. Version Compatibility** | **Risk of Desynchronization:** If customer upgrades theme to v2.0 but keeps plugin at v1.0, contract breakage could occur unless strict semantic checks are implemented. | **Low Risk:** Single repository ensures theme and plugin versions are released and tagged in lockstep with verified compatibility matrix. | **Moderate:** Pointers ensure compatibility only if rigorously bumped on every release. |
| **9. Rollback & Recovery** | **Independent:** Plugin or theme can be rolled back individually. | **Unified:** Single git revert or branch checkout restores the entire system to a known good state atomically. | **Complex:** Rolling back requires detached submodule checkouts and synchronizing parent commits. |
| **10. Contributor Complexity** | **Moderate:** Requires managing access, PRs, and branch lifecycles in two distinct GitHub repositories. | **Lowest:** All developers work in a single repository, following existing `AGENTS.md`, `CLAUDE.md`, and Spec Kit workflows. | **Highest:** Developers must master submodule semantics, pointer updates, and recursive cloning. |
| **11. Risk of Unversioned Files** | **Low:** Each repository explicitly tracks its own source tree. | **Low:** Plugin source is fully versioned inside `packages/negar-customer-club/` (or `plugins/negar-customer-club/`); only the deployed target in `wp-content/plugins/` is a junction or generated artifact. | **Moderate:** Untracked changes inside submodule working tree are easily lost. |
| **12. Risk of Duplicate WP / Theme Dirs** | **Zero:** Plugin is external. | **Zero:** Strictly adheres to `AGENTS.md` rule: "do not create duplicate themes, WordPress installs, or demo databases". Uses a single local install. | **Zero:** Uses single local install. |

---

### Architectural Assessment & Recommendation

#### Comparison Summary
1. **Option C (Submodules/Composer)** is rejected due to high operational complexity on Windows WAMP, detached HEAD risks, and severe developer friction.
2. **Option A (Separate Repository)** is the traditional WordPress ecosystem approach. It offers pristine physical isolation between repositories, but introduces significant coordination overhead: dual branches, duplicate Spec Kit configurations, non-atomic commits, and complicated local WAMP synchronization.
3. **Option B (Monorepo with Dedicated Directory & Build Packaging)** provides the optimal balance of architectural purity and development velocity:
   - **Runtime Separation:** The code runs as a genuine WordPress plugin in `wp-content/plugins/negar-customer-club/`, ensuring absolute data durability and surviving theme deactivation.
   - **Repository Cohesion:** Source code, specifications (`specs/013-customer-club/`), tests, and build scripts live in a single repository with atomic Git commits.
   - **Local WAMP Compatibility:** On Windows WAMP, a PowerShell setup command (`New-Item -ItemType Junction`) allows live editing in `packages/negar-customer-club/` with instant reflection in WordPress plugins without manual copying.

#### Recommendation
The architectural recommendation is **Option B (Monorepo Source under `packages/negar-customer-club/` or `plugins/negar-customer-club/` with packaging to `wp-content/plugins/negar-customer-club/`)**, with **Option A** as a fully viable fallback if the employer strictly mandates separate GitHub repositories for client deliverables.

#### Formal Governance Status
```
Decision ID: CLUB-AD-001
Status: PROPOSED — REQUIRES EMPLOYER APPROVAL
Selected Option: PENDING FORMAL EMPLOYER SELECTION (Recommendation: Option B)
Phase 2 Action: NO plugin repository or plugin code created. All planning, contracts, and data models remain purely documented.
```

---

## Record 2: Server-Authoritative Quote Calculator Architecture

### Status
`PROPOSED — TECHNICAL ARCHITECTURE`

### Context
In Phase 4, the public Customer Club page renders an interactive installment preview calculator. However, the authoritative financial engine is scheduled for Phase 6. Without an early calculation component, the client-side calculator cannot be validated for server/client parity.

### Decision
1. Design an early, dependency-light PHP class `Negar_Club_Quote_Calculator` implementing the formal mathematical specification.
2. In Phase 4, this component operates in **provisional preview mode**:
   - It performs integer arithmetic in `IRT`.
   - It accepts purchase amount, plan ceilings, initial payments, and installment counts.
   - It outputs deterministic schedules, principal shares, and charges.
   - It explicitly returns `is_binding = false` and `status = 'provisional_quote'`.
   - It creates **zero** database records, **zero** WooCommerce orders, and **zero** credit agreements.
3. In Phase 6, the persistent financial module wraps or incorporates this calculator to generate authoritative `CreditAgreement` and `InstallmentItem` records.
4. If unapproved business policies (`CLUB-BD-005`, `CLUB-BD-006`, `CLUB-BD-011`, `CLUB-BD-022`) are still open during Phase 4, calculations under those edge conditions return explicit provisional warnings or clamp to safe defaults.

---

## Record 3: Webhook Verification vs. Browser Nonce Separation

### Status
`PROPOSED — TECHNICAL ARCHITECTURE`

### Context
`CLUB-NFR-007` and `CLUB-AC-047` require robust security across both user-initiated browser requests and external payment callbacks. A common vulnerability in naive WordPress implementations is attempting to use browser nonces on public webhooks or, conversely, disabling all authentication for webhooks.

### Decision
1. **Browser/User Requests:**
   - Must verify active user session (`is_user_logged_in()`).
   - Must verify scoped user capability (`manage_options`, `negar_club_operator`, or customer ownership).
   - Must verify action-specific WordPress nonce (`wp_verify_nonce()` or `check_ajax_referer()`).
   - Must enforce server-side object ownership (`record.user_id === get_current_user_id()`).
2. **External Payment Provider Webhooks:**
   - Must use dedicated endpoint (e.g., `wp-json/negar-club/v1/payment-callback` or template endpoint).
   - Must verify provider digital signature (HMAC-SHA256 or provider certificate) or perform direct authenticated server-to-server inquiry with the payment gateway.
   - Must verify transaction amount, currency (`IRT`/`IRR`), merchant account, and provider reference.
   - Must enforce database-level idempotency via unique index on provider transaction ID.
   - Must **never** rely on browser nonces or user cookies.
   - Must **never** allow unauthenticated execution.
