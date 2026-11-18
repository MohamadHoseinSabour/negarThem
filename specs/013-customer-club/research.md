# Technical Research & Architectural Rationale: NegarThem Customer Club

**Feature:** 013-customer-club  
**Project:** Negar Shop WordPress Theme (`negarThem`)  
**Date:** 2026-11-18  
**Author:** Senior WordPress & WooCommerce Architect Agent  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

---

## 1. Baseline Environment & Codebase Evidence

Phase 0 verified the following runtime facts in the active local environment:

| Component | Verified Local State | Architectural Impact |
| :--- | :--- | :--- |
| **WordPress Core** | 7.1.4 (Development branch / UTF-8) | Standard Settings API, WP REST API v2, native password/session handling available. |
| **PHP Runtime** | 8.2.29 (64-bit, Win32) | Native 64-bit integers (`PHP_INT_MAX` > 9 × 10¹⁸) safely handle large monetary amounts without overflow; strict typing (`declare(strict_types=1);`) and modern attributes supported. |
| **WooCommerce Core** | 10.5.2 | Full WooCommerce CRUD APIs available. |
| **Order Storage** | HPOS Active (`woocommerce_custom_orders_table_enabled = yes`) | **Strict Constraint:** Direct SQL queries to `wp_posts` or `wp_postmeta` for order records are forbidden. All order access must use `wc_get_order()` and official CRUD methods. |
| **Store Currency** | `IRT` (Iranian Toman), `0` Decimals | Currency formatting and calculations must operate strictly on integer Toman values. No floating-point rounding. |
| **Web Server / Stack** | WAMP on Windows (Apache 2.4.x, MySQL/MariaDB) | CLI commands run in PowerShell. MySQL binaries (`mysqldump.exe`) are not present in the system `$env:PATH` and require path discovery. |
| **Active Theme** | `negarThem` (`x:\apps\wamp\www\wp4\wp-content\themes\negarThem`) | Crimson storefront design system (`--color-primary: #de1f38`, `--color-primary-dark: #b51428`, Vazirmatn / IRANSans typography, standard RTL layout). |
| **Theme Settings** | `inc/theme-settings.php` (~3,745 lines) | Multi-tab Settings API implementation storing grouped non-autoloaded options. Commit `eda9740` resolved pre-existing line 3712 opening tag defect. |

---

## 2. WooCommerce HPOS Integration & Order Lifecycle

### 2.1 HPOS Compatibility Declaration
Any persistent plugin developed for this feature must declare full High-Performance Order Storage (HPOS) compatibility during the `before_woocommerce_init` hook:

```php
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            __FILE__,
            true
        );
    }
});
```

### 2.2 Order Association Without Dual-Counting
A critical architectural challenge in installment commerce is ensuring that the initial payment is collected cleanly at checkout without incorrectly treating the remaining financed balance as paid or distorting store revenue.

#### Architecture Model
1. **Cart & Checkout Representation:**
   - When a customer purchases using an approved installment plan, the WooCommerce order total at checkout reflects the total purchase price.
   - The customer pays the approved **Initial Payment** (`applied_initial_payment`) upfront via the active payment gateway.
   - The **Financed Principal** (`remaining_principal`) is recorded as a deferred credit obligation linked to the order via an explicit metadata attribute `_negar_club_agreement_id`.
2. **Order Settlement States:**
   - Once the gateway confirms the initial payment, the WooCommerce order transitions to `processing` (or `completed` for virtual goods), allowing the store to fulfill the order.
   - The linked `CreditAgreement` transitions to `active`, and its installment schedule is instantiated.
   - **Accounting Boundary:** The WooCommerce order records the initial payment transaction. Future installment payments are processed against the `InstallmentItem` records and their dedicated payment log, **not** by re-charging the original WooCommerce order.
3. **Refunds and Order Cancellations (`CLUB-FR-036`):**
   - If an order is cancelled or fully refunded before fulfillment, the linked `CreditAgreement` must transition to `cancelled`, and all scheduled installments must transition to `cancelled`.
   - Any collected initial payment refund is processed through WooCommerce's standard refund API (`wc_create_refund()`).
   - If an installment was already paid prior to an approved return, the refund workflow must credit the customer via the formal policy defined in `CLUB-BD-014`.

---

## 3. Financial Calculation Engine & Precision Boundaries

### 3.1 Integer Arithmetic & Zero-Drift Scheduling
Floating-point arithmetic in financial calculations introduces subtle rounding drift (e.g., `0.1 + 0.2 !== 0.3`). The NegarThem Customer Club enforces **strict integer arithmetic** in `IRT` (0 decimals):

1. **Financed Principal Formula:**
   $$\text{financed\_principal} = \text{purchase\_amount} - \text{initial\_payment}$$
2. **Base Installment Calculation (Divisible & Non-Divisible):**
   Given financed principal $P$ and installment count $N$:
   $$\text{base\_installment} = \lfloor P / N \rfloor$$
   $$\text{remainder} = P - (\text{base\_installment} \times N)$$
3. **Deterministic Remainder Allocation:**
   - To prevent cumulative drift, the non-divisible remainder ($R$) is distributed across the installments deterministically.
   - Standard convention: The remainder $R$ is added to the **first installment** (or distributed $1$ Toman to the first $R$ installments):
     - Installment 1: $\text{base\_installment} + R$
     - Installments 2 through $N$: $\text{base\_installment}$
   - Invariant:
     $$\sum_{i=1}^{N} \text{installment\_principal}_i \equiv \text{financed\_principal}$$
4. **Disclosed Charges / Fee Policy (`CLUB-BD-011`, `CLUB-BD-022`):**
   - In the provisional baseline model, fees are `0` IRT (0% interest / Murabaha no-fee).
   - If an approved fee/markup $C$ is introduced by future employer decision:
     $$\text{base\_charge} = \lfloor C / N \rfloor, \quad \text{charge\_remainder} = C - (\text{base\_charge} \times N)$$
     $$\text{installment\_total}_i = \text{installment\_principal}_i + \text{installment\_charge}_i$$
     $$\sum_{i=1}^{N} \text{installment\_total}_i \equiv \text{financed\_principal} + C$$

### 3.2 Currency Conversion Boundary (`IRT` vs. `IRR`)
Storefront prices and customer agreements are denominated in **Toman (`IRT`)**. However, Iranian bank gateways (Shaparak, Zibal, ZarinPal, Saman, Mellat) predominantly require transaction amounts in **Rial (`IRR`)**:
- Conversion rule:
  $$\text{amount\_irr} = \text{amount\_irt} \times 10$$
- **Verification Rule:** The conversion must happen at the exact gateway dispatch boundary. The internal database, contracts, agreement snapshots, and audit logs must store the native `IRT` integer value. Gateway callbacks must convert back (`callback_irr / 10`) and strictly verify equality against the expected `IRT` amount before crediting.

### 3.3 Server-Authoritative Quote Calculator Component
To allow Phase 4 public UI testing without activating the financial core:
- A standalone calculation utility `Negar_Club_Quote_Calculator` will provide deterministic calculation methods:
  - `calculate_quote(int $amount, string $plan_id, array $plan_config): array`
- It outputs:
  - `purchase_amount`: validated integer IRT
  - `applicable_initial_payment`: integer IRT
  - `remaining_principal`: integer IRT
  - `installments_count`: integer (e.g. 3, 6, 12)
  - `monthly_installment_estimate`: base integer IRT
  - `first_installment_amount`: base + remainder integer IRT
  - `total_payable`: integer IRT
  - `is_provisional`: true (marked non-binding)
  - `validation_notices`: array of Persian strings (e.g. ceiling exceeded, below minimum)

---

## 4. State Machines & Transition Guards

### 4.1 Application Lifecycle (`ClubApplication`)
```
[Guest / Customer Submits] ──► ( submitted )
                                   │
                                   ▼
                            ( pending_review )
                                   │
                  ┌────────────────┴────────────────┐
                  ▼                                 ▼
             ( approved )                      ( rejected )
                  │
      ┌───────────┴───────────┐
      ▼                       ▼
 ( active_credit )       ( expired )
```

- **Guards:**
  - `submitted` → `pending_review`: Triggered automatically on submission after nonce, ownership, and required document checks pass.
  - `pending_review` → `approved`: Requires `negar_club_operator` or `manage_options` capability. Validates credit score and identity. Emits audit log.
  - `pending_review` → `rejected`: Requires operator capability. Stores rejection reason.
  - `approved` → `expired`: Automatic transition if customer does not execute an agreement within the validity window (default 30 days per `CLUB-BD-010`).

### 4.2 Credit Agreement Lifecycle (`CreditAgreement`)
```
[Order Placed + Approved App] ──► ( pending_prerequisite )
                                           │ (Gateway verifies initial payment)
                                           ▼
                                       ( active )
                                           │
                        ┌──────────────────┴──────────────────┐
                        ▼                                     ▼
                  ( completed )                         ( cancelled )
            (All installments paid)                 (Order returned/voided)
```

- **Guards:**
  - `pending_prerequisite` → `active`: Gateway webhook confirms settlement of initial payment order. Instantiates immutable snapshot of plan terms and schedule of installments.
  - `active` → `completed`: Triggered automatically when all linked installments reach status `paid`.
  - `active` → `cancelled`: Triggered on approved order return or mutual cancellation.

### 4.3 Installment Item Lifecycle (`InstallmentItem`)
```
( scheduled ) ──► ( due ) ──► ( paid )
      │              │
      ▼              ▼
 ( cancelled )   ( overdue ) ──► ( paid )
```

- **Guards:**
  - `scheduled` → `due`: Automatic cron/time-based transition when current date reaches due date minus advance notice window (e.g., 5 days).
  - `due` → `paid`: Requires verified payment transaction matching exact installment amount.
  - `due` → `overdue`: Automatic transition when due date passes without verified settlement.
  - `paid`: Terminal state (immutable). Cannot transition back unless an official refund is processed under `CLUB-BD-014`.

### 4.4 Payment Transaction Lifecycle (`PaymentTransaction`)
```
( initiated ) ──► ( pending_verification ) ──► ( verified )
      │                                             │
      ▼                                             ▼
  ( failed )                                    ( refunded )
```

- **Guards:**
  - `initiated` → `pending_verification`: Generated when customer is redirected to payment gateway.
  - `pending_verification` → `verified`: Triggered on server-to-server callback verification. Must pass idempotency check, merchant check, amount check, and currency check.
  - `pending_verification` → `failed`: Triggered on user cancellation or gateway rejection.

---

## 5. Security & Isolation Architecture

### 5.1 Role & Capability Matrix
| Role | Settings API (`manage_options`) | Review Applications (`negar_club_operator`) | Submit Application | View Own Dashboard | Initiate Installment Payment |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Guest** | ❌ | ❌ | ❌ (Redirects to OTP login) | ❌ | ❌ |
| **Customer** | ❌ | ❌ | ✅ | ✅ (Own records only) | ✅ (Own installments only) |
| **Store Operator** | ❌ | ✅ | ❌ | ❌ | ❌ |
| **Administrator** | ✅ | ✅ | ✅ | ✅ | ✅ |

### 5.2 Anti-IDOR (Insecure Direct Object Reference) Protection
Every API endpoint (REST or AJAX) accepting an `application_id`, `agreement_id`, or `installment_id` must enforce strict ownership:
```php
$record = Negar_Club_Storage::get_application($application_id);
if (!$record) {
    wp_send_json_error(['message' => 'یافت نشد.'], 404);
}
if ($record->user_id !== get_current_user_id() && !current_user_can('negar_club_operator')) {
    wp_send_json_error(['message' => 'دسترسی غیرمجاز.'], 403);
}
```

### 5.3 Audit Log vs. Accounting Ledger Boundary
`wp_negar_club_audit_log` is an **append-only operational event log** recording actors, timestamps, IP addresses, old states, and new states.
- It is **not** an accounting journal.
- The authoritative financial balance is derived strictly from the state of persisted `CreditAgreement` and `InstallmentItem` records.
- Audit log rows cannot be mutated or deleted.

---

## 6. Operational Architecture on Local WAMP

### 6.1 Database Backup Strategy
Phase 0 confirmed that WAMP on Windows does not expose `mysqldump` in the global PATH. Before any future phase runs database migrations:
1. **Binary Discovery:**
   The CLI script or developer must resolve the path to MySQL/MariaDB:
   ```powershell
   $mysqlBin = Get-ChildItem "X:\apps\wamp\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
   if (-not $mysqlBin) {
       $mysqlBin = Get-ChildItem "C:\wamp64\bin\mysql" -Recurse -Filter "mysqldump.exe" | Select-Object -First 1 -ExpandProperty FullName
   }
   ```
2. **Pre-Migration Dump:**
   ```powershell
   & $mysqlBin -u root wp4 > "backups/pre-customer-club-migration.sql"
   ```
3. **Rollback Verification:**
   In the event of a migration failure, the table rollback script drops only custom `wp_negar_club_*` tables, leaving all core WordPress and WooCommerce tables intact.

### 6.2 Theme Settings Tab Integration (`inc/theme-settings.php`)
- Option Name: `negar_theme_club_options`
- Autoload: `no`
- Tab Key: `customer_club`
- Registration: Added to the `$tabs` array in `inc/theme-settings.php`
- Sanitization: Hooked into `sanitize_callback` to validate integers, sanitize strings (`sanitize_text_field`), validate URLs (`esc_url_raw`), and reject negative amounts.
- Backup Compatibility: Covered by `inc/theme-backup.php` export/import handlers without exposing customer PII.
