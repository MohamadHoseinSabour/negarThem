# Architecture Decision Records (ADR): NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Date:** 2026-11-18
**Phase:** Phase 2.3 — Critical Financial Architecture Corrections (Minimal Scope)
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
│  • Presentation Assets (assets/css/customer-club.css, assets/js/customer-club.js)│
│  • Theme Settings Tab («باشگاه مشتریان» in inc/theme-settings.php)              │
│  • Options Storage: negar_theme_club_options (autoload=no)                     │
│  • My Account Endpoint View Hook (woocommerce/myaccount/customer-club.php)      │
│  • Graceful Degradation: Informational fallback when financial module inactive │
└──────────────────────────────────────┬──────────────────────────────────────────┘
                                       │ Public Filters / Actions / Contract API
                                       ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                      PERSISTENT FINANCIAL MODULE / PLUGIN                       │
│                    (wp-content/plugins/negar-customer-club)                     │
│                                                                                 │
│  • Canonical Calculator Service (packages/negar-customer-club/includes/...)     │
│  • Database Schema & Migrations (wp_negar_club_applications, contracts, etc.)   │
│  • Dedicated Refunds Ledger (wp_negar_club_refunds)                             │
│  • Application Intake & Scoped Operator Review Workflow                         │
│  • State Machines (Application, Agreement, Installment, Payment, Refund)        │
│  • WooCommerce HPOS Order Association & Initial Payment Verification            │
│  • Authoritative Financial Engine (Schedule generation, zero-drift math)        │
│  • Provider-Independent Payment Webhooks & Signature Verification               │
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

#### Option B: Dedicated Plugin Source in Monorepo Structure with Build Packaging (Recommended)
- **Description:** The plugin source is maintained within the existing repository under a dedicated top-level directory (`packages/negar-customer-club/`). A build/packaging script (via `npm run build:plugin` or symlink/junction in local WAMP) synchronizes or packages the plugin into `wp-content/plugins/negar-customer-club/`.
- **Workflow:** Single repository for all Customer Club code; atomic commits across theme and plugin; unified CI/linting pipeline; explicit separation of runtime targets.

#### Option C: Git Submodule or Composer Package
- **Description:** The plugin is maintained in a separate repository but embedded into the theme repository or WAMP environment as a Git Submodule or managed via Composer (`composer require mohamadhoseinsabour/negar-customer-club`).
- **Workflow:** Dual repository tracking with submodule pointers or composer lockfile.

---

### Detailed Comparison Across 12 Architectural Criteria

| Criterion | Option A (Separate Git Repo) | Option B (Monorepo Directory + Packaging) | Option C (Git Submodule / Composer) |
| :--- | :--- | :--- | :--- |
| **1. Data Durability** | **Excellent:** Plugin is installed in `wp-content/plugins/`. Switching or deleting the theme leaves all financial tables, applications, and agreements 100% untouched. | **Excellent:** Deployed to `wp-content/plugins/`. Financial tables and lifecycles remain completely decoupled from active theme lifecycle. | **Excellent:** Installed in `wp-content/plugins/` independently of theme. Data durability is preserved. |
| **2. Git Traceability** | **Moderate:** Two independent commit histories. Atomic cross-layer changes require synchronized commits across two repos. | **Superior:** 100% unified Git history. Specification, theme templates, and financial module evolve in lockstep with atomic conventional commits and Spec Kit traceability. | **Poor/Complex:** Submodule detached HEADs, pointer updates, and merge friction frequently cause version desynchronization. |
| **3. Local WAMP Development** | **Moderate:** Requires cloning second repo into `x:\apps\wamp\www\wp4\wp-content\plugins\`. Developer must manage two active working copies. | **Superior:** Single working directory (`x:\apps\wamp\www\wp4\wp-content\themes\negarThem`). A standard Windows directory junction links `packages/negar-customer-club` directly to `wp-content/plugins/negar-customer-club`. | **Complex:** Submodule clone commands, recursive updates, and Windows symlink permission hurdles introduce friction. |
| **4. CI & Automated Testing** | **Moderate:** Requires multi-repo CI workflows or orchestration to test theme + plugin integration end-to-end. | **Superior:** Existing `npm test`, `npm run test:lint`, and Playwright test suites run in a single environment against both layers without external repo dependencies. | **Complex:** Requires submodule checkout actions or private composer token management in CI pipelines. |
| **5. Release Packaging** | **Good:** Plugin has independent release tags (`v1.0.0.zip`). Theme has separate theme zip (`dist/negar-shop.zip`). | **Excellent:** Build scripts package `dist/negar-shop.zip` (excluding `packages/`) and `dist/negar-customer-club.zip` (with root folder `negar-customer-club`) cleanly in one build pipeline. | **Moderate:** Requires managing tags and release artifacts across multiple repositories. |
| **6. Upgrade Compatibility** | **High:** Theme updates do not overwrite plugin files; plugin updates do not overwrite theme files. Standard WP plugin updater can be used. | **High:** Theme and plugin are deployed to separate directories (`themes/negarThem` vs `plugins/negar-customer-club`). Updating one does not overwrite the other. | **High:** Standard WordPress update channels apply once deployed. |
| **7. Independent Plugin Activation** | **Yes:** Active/inactive status managed in `wp-admin/plugins.php`. Theme gracefully degrades when plugin is inactive. | **Yes:** Plugin resides in `wp-content/plugins/` and is activated/deactivated independently via WordPress admin or WP-CLI. | **Yes:** Managed independently in WordPress plugin directory. |
| **8. Version Compatibility** | **Risk of Desynchronization:** If customer upgrades theme to v2.0 but keeps plugin at v1.0, contract breakage could occur unless strict semantic checks are implemented. | **Low Risk:** Single repository ensures theme and plugin versions are released and tagged in lockstep with verified compatibility matrix. | **Moderate:** Pointers ensure compatibility only if rigorously bumped on every release. |
| **9. Rollback & Recovery** | **Independent:** Plugin or theme can be rolled back individually. | **Unified:** Single git revert or branch checkout restores the entire system to a known good state atomically. | **Complex:** Rolling back requires detached submodule checkouts and synchronizing parent commits. |
| **10. Contributor Complexity** | **Moderate:** Requires managing access, PRs, and branch lifecycles in two distinct GitHub repositories. | **Lowest:** All developers work in a single repository, following existing `AGENTS.md`, `CLAUDE.md`, and Spec Kit workflows. | **Highest:** Developers must master submodule semantics, pointer updates, and recursive cloning. |
| **11. Risk of Unversioned Files** | **Low:** Each repository explicitly tracks its own source tree. | **Low:** Plugin source is fully versioned inside `packages/negar-customer-club/`; only the deployed target in `wp-content/plugins/` is a junction or generated artifact. | **Moderate:** Untracked changes inside submodule working tree are easily lost. |
| **12. Risk of Duplicate WP / Theme Dirs** | **Zero:** Plugin is external. | **Zero:** Strictly adheres to `AGENTS.md` rule: "do not create duplicate themes, WordPress installs, or demo databases". Uses a single local install. | **Zero:** Uses single local install. |

---

### Operational Specification of Recommended Option B (Correction C7.1)

1. **Repository Layout:**
   - Theme source remains at repository root (`x:\apps\wamp\www\wp4\wp-content\themes\negarThem`).
   - Plugin source resides in `packages/negar-customer-club/`.
2. **Distribution Build Architecture:**
   - In `scripts/build-dist.js`, the `EXCLUDE_NAMES` set is extended to include `'packages'` and `'plugins'`. As a result, `dist/negar-shop.zip` never contains plugin code.
   - A dedicated packaging script `scripts/build-plugin.js` stages `packages/negar-customer-club/` and compresses it to `dist/negar-customer-club.zip` with the internal root directory `negar-customer-club/`.
   - `package.json` provides unified scripts:
     - `"build:theme": "node scripts/build-dist.js"`
     - `"build:plugin": "node scripts/build-plugin.js"`
     - `"build:dist": "npm run build:theme && npm run build:plugin"`
3. **Local WAMP Development Integration:**
   - Development uses a standard Windows NTFS Directory Junction created via PowerShell:
     ```powershell
     New-Item -ItemType Junction -Path "X:\apps\wamp\www\wp4\wp-content\plugins\negar-customer-club" -Target "X:\apps\wamp\www\wp4\wp-content\themes\negarThem\packages\negar-customer-club"
     ```
   - Editing files in `packages/negar-customer-club/` is immediately reflected in WordPress without copying or build steps.
4. **Independent Plugin Versioning & Compatibility Check:**
   - The plugin declares its own semantic version in its main file header:
     `Version: 1.0.0`
   - The theme verifies minimum plugin version compatibility via a hook check:
     ```php
     if ( defined( 'NEGAR_CLUB_PLUGIN_VERSION' ) && version_compare( NEGAR_CLUB_PLUGIN_VERSION, '1.0.0', '<' ) ) {
         // Display administrative compatibility notice
     }
     ```
5. **Theme Graceful Degradation:**
   - When `negar-customer-club` plugin is inactive, the public Customer Club page still renders informational copy, plan cards, benefits, and FAQ from `negar_theme_club_options`.
   - Application buttons and active payment controls display an honest disabled notice: «سیستم ثبت درخواست و تسهیلات در حال به‌روزرسانی می‌باشد».
   - Zero fatal errors or PHP warnings are triggered.
6. **Data Durability on Theme Switch:**
   - Because all custom tables (`wp_negar_club_*`) and financial logic reside in `wp-content/plugins/negar-customer-club/`, switching or deleting the active theme has **zero effect** on customer debt records, installment schedules, or payment logs.

---

## Record 2: Single Canonical Quote Calculator Ownership (Correction C7.2)

### Status
`PROPOSED — TECHNICAL ARCHITECTURE`

### Problem
The implementation plan requires an early server-authoritative quote calculator in Phase 4 for public preview testing, but persistent financial tables are scheduled for Phase 6. Having separate calculator classes in both theme and plugin would create code duplication and divergent business rules.

### Decision
1. **Single Canonical Location:**
   The calculator logic has **one canonical implementation** owned by the Customer Club core:
   `packages/negar-customer-club/includes/class-quote-calculator.php`
2. **Phase 4 Lightweight Plugin Foundation:**
   - In **Phase 4**, a lightweight, dependency-free foundation of the plugin is introduced. It registers only the stateless `Negar_Club_Quote_Calculator` class and the public REST endpoint `/wp-json/negar-club/v1/quote`.
   - It requires zero database tables, zero WooCommerce orders, and zero payment credentials.
3. **Phase 6 Seamless Expansion:**
   In Phase 6, the financial engine uses this existing canonical calculator to generate authoritative `wp_negar_club_contracts` and `wp_negar_club_installments` rows.
4. **Theme Role:**
   The theme consumes this service via REST or direct class invocation (`Negar_Club_Quote_Calculator::calculate()`), maintaining pure presentation without duplicating mathematical formulas.

---

## Record 3: Webhook Verification vs. Browser Nonce Separation

### Status
`PROPOSED — TECHNICAL ARCHITECTURE`

### Decision
1. **Browser/User Requests:**
   - Must verify active user session (`is_user_logged_in()`).
   - Must verify scoped user capability (`manage_options`, `negar_club_operator`, or customer ownership).
   - Must verify action-specific WordPress nonce (`wp_verify_nonce()` or `check_ajax_referer()`).
   - Must enforce server-side object ownership (`record.user_id === get_current_user_id()`).
2. **External Payment Provider Webhooks:**
   - Must use dedicated endpoint (`wp-json/negar-club/v1/payment-callback`).
   - Must execute provider-specific adapter authentication (HMAC signature for Class 1, outbound server inquiry for Class 2, or both for Class 3).
   - Must verify transaction amount, currency (`amount_irr % 10 === 0`), and merchant account.
   - Must enforce database-level idempotency via unique index on provider transaction ID and idempotency key.
   - Must **never** rely on browser nonces or user cookies.
   - Must **never** allow unauthenticated execution.

---

## Record 4: WooCommerce Accounting & Custom Order Status (`CLUB-AD-002`)

### Status
`PROPOSED — REQUIRES EMPLOYER APPROVAL`

### Context & Problem Statement
When an order is placed using installment payment (`WC_Gateway_Negar_Installment`), only the initial down payment is collected immediately at checkout. The remaining balance represents customer debt to be paid over future installments.
In WooCommerce 10.5.2 core:
- `wc_get_is_paid_statuses()` returns `array('processing', 'completed')`.
- Calling `$order->payment_complete()` or transitioning the order to `processing` or `completed` causes `$order->is_paid()` to return `true`.
- If an installment order with only down payment collected is marked `processing`, WooCommerce native sales reports, tax accounting, and invoices record 100% of merchandise value as received cash, distorting financial books.
- Furthermore, native `WC_Order::get_total_paid()` does not exist in WooCommerce 10.5.2 core.
- Alternative B (inserting a negative fee line item to balance order total to down payment) is rejected due to core warnings in `class-wc-order-item-fee.php`.

### Decision
1. **Adopt Alternative C (Dedicated Gateway + Custom Status `wc-partially-paid`):**
   - Register custom WooCommerce order status `wc-partially-paid` («پرداخت جزئی (اقساطی)»).
   - `wc-partially-paid` is NOT added to `wc_get_is_paid_statuses()`, guaranteeing `$order->is_paid() === false`.
   - Native WooCommerce reports and tax summaries reflect that full merchandise cash has not been received.
2. **Warehouse Fulfillment & Stock Reduction:**
   - `wc-partially-paid` is registered as a valid fulfillment-ready status for store dispatchers.
   - Upon initial down payment verification, inventory stock is reduced exactly once by calling `wc_reduce_stock_levels($order_id)`. Native WooCommerce `_order_stock_reduced` metadata prevents duplicate stock reduction.
3. **Transition to Fully Paid:**
   - When all installments are fully settled and the credit agreement status transitions to `settled`, the WooCommerce order is transitioned to `processing` (or `completed` if already fulfilled), and `$order->payment_complete()` is invoked.
4. **Disallow Non-Existent Core Methods:**
   - All references to `WC_Order::get_total_paid()` are strictly forbidden. Initial cash received is tracked in order metadata `_negar_club_down_payment_settled` and in the immutable `wp_negar_club_payments` table.
