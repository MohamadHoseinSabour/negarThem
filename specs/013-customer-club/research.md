# Technical Research & Architectural Rationale: NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Date:** 2026-11-18
**Phase:** Phase 2.2 — Final Architecture Consistency & Financial Lifecycle Audit
**Author:** Senior WordPress & WooCommerce Architect Agent
**Status:** `DRAFT — PHASE 2.2 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

---

## 1. Baseline Environment & Codebase Evidence

Phase 0 and Phase 2 preflight verified the following runtime facts in the active local environment:

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

## 2. WooCommerce Accounting Architecture & HPOS Integration (Correction C1)

### 2.1 The Accounting Challenge in Installment Purchases
In a typical WooCommerce purchase, `WC_Order::get_total()` equals the cash charged through a payment gateway. When the gateway confirms payment, calling `$order->payment_complete()` automatically:
1. Records `total_paid = order_total`.
2. Transitions order status to `processing` (or `completed`).
3. Decrements inventory stock.
4. Marks the order as eligible for fulfillment.

However, in an **installment purchase**:
- The customer purchases merchandise of total value $M$ (e.g., 50,000,000 IRT).
- The customer pays an upfront **Initial Payment** $I$ (e.g., 10,000,000 IRT) at checkout.
- The remaining **Financed Principal** $P = M - I$ (e.g., 40,000,000 IRT) is extended as a credit facility repayable in monthly installments.

If an integration naively leaves WooCommerce order total at 50,000,000 IRT while charging the gateway 10,000,000 IRT:
- Calling `$order->payment_complete()` marks 50M IRT as paid in cash, producing a **40M IRT cash discrepancy** in WooCommerce accounting and financial reporting.
- Conversely, leaving the order in `pending` or `partially-paid` prevents automatic fulfillment and stock deduction.

### 2.2 Deep Investigation of WooCommerce Core APIs & `class-wc-order-item-fee.php`
We conducted a line-by-line inspection of WooCommerce 10.5.2 core code in `x:\apps\wamp\www\wp4\wp-content\plugins\woocommerce\includes\class-wc-order-item-fee.php`:

```php
// WooCommerce Core: class-wc-order-item-fee.php (lines 5-6)
 * Fee
 *
 * Fee is an amount of money charged for a service or something else. It is not supposed to be negative.

// WooCommerce Core: class-wc-order-item-fee.php (line 170)
 * @param string $amount Fee amount (do not enter negative amounts).
```

Furthermore, line 94 shows that negative fees trigger discount-like behavior that WooCommerce core explicitly discourages. In addition, calling `$order->payment_complete()` sets `$order->set_date_paid()` and records `total_paid` equal to `get_total()`. Therefore, treating a deferred installment credit as a "negative fee" or calling `payment_complete()` when only the initial payment is received introduces severe architectural and accounting flaws.

| WooCommerce Method / Class | Core Behavior in WC 10.5.2 | Architectural Impact & Hazards |
| :--- | :--- | :--- |
| `WC_Order::get_total()` | Returns net total payable after fees, taxes, shipping, and coupons. Gateways use this to charge customers. | Must reflect the exact cash amount to be charged or transacted. |
| `WC_Order::get_total_paid()` | Tracks exact cash collected. In core, `payment_complete()` sets `total_paid = get_total()`. | **Accounting Hazard:** Calling `payment_complete()` when only initial payment was collected falsely books 100% of merchandise value as received cash. |
| `WC_Order::get_remaining_refund_amount()` | Calculated as `get_total() - get_total_refunded()`. An order cannot refund more than `get_total()`. | If order total was artificially reduced to initial payment, refunding the whole order later (including paid installments) is blocked by core WooCommerce limits. |
| `WC_Order_Item_Fee` | Native line item for extra charges. Docstring line 6 & 170: *"not supposed to be negative (do not enter negative amounts)"*. | **Rejected Approach:** Using negative fees violates WooCommerce core contract and can distort tax calculation bases. |
| `WC_Order::payment_complete($tx_id)` | Core payment finalizer. Sets `date_paid`, transitions status (`processing`), triggers stock reduction. | **Must be bypassed or replaced** by explicit partial payment tracking to prevent false full cash settlement. |
| `wc_reduce_stock_levels($order_id)` | Explicit core function to decrement product inventory. | Can be invoked directly by custom gateway without calling `payment_complete()`. |

---

### 2.3 Exhaustive Evaluation of Three Architectural Alternatives Across 10 Criteria

We evaluated three potential architectures for reconciling WooCommerce orders with installment credit:

- **Alternative A: Full-value WooCommerce Order with Custom Order Status / Partial Payment Tracking**
  The order total represents the full catalog value (50M IRT). Initial payment (10M IRT) is collected via standard gateway. Upon callback, custom metadata records `_negar_club_initial_paid = 10000000`, `_negar_club_financed_principal = 40000000`. The system transitions order status to `processing` (or custom `negar-installment-active`) and manually triggers `wc_reduce_stock_levels()`, but **does NOT call `$order->payment_complete()`** to avoid false full cash recognition.
- **Alternative B: Net Gateway Charge via Negative Fee Line Item**
  Merchandise is in cart at 50M IRT. A negative fee of -40M IRT is injected, reducing `get_total()` to 10M IRT. Gateway charges 10M IRT and calls `payment_complete()`.
- **Alternative C: Dedicated Installment Payment Gateway Integration (`WC_Gateway_Negar_Installment`) (RECOMMENDED)**
  A custom WooCommerce gateway handles checkout. The order total remains the full merchandise value (50M IRT) for official VAT invoicing, product gross, and tax compliance. The gateway intercepts the checkout redirect and passes **only the initial payment amount** (10M IRT) to the online banking gateway. Upon callback verification, the gateway records initial cash settlement, records agreement linkage metadata, updates order status to `processing`, and calls `wc_reduce_stock_levels()`. Remaining principal is managed strictly in `wp_negar_club_contracts`.

#### 10-Criteria Comparative Evaluation Matrix:

| Evaluation Criterion | Alternative A (Full Order + Custom Status) | Alternative B (Negative Fee Line Item) | Alternative C (Dedicated Gateway - Recommended) |
| :--- | :--- | :--- | :--- |
| **1. Product Gross** | **Accurate:** Full merchandise line item value is retained in order lines. | **Accurate:** Product line items retain catalog price, but net total is deflated. | **Accurate:** Retains exact catalog line item totals for all merchandise. |
| **2. Discount Accounting** | **Clean:** Standard WooCommerce coupons and discounts apply normally to items. | **Distorted:** Line item fee can be classified as discount, confusing analytics. | **Clean:** Coupons and discounts calculate natively before installment terms. |
| **3. Taxable Base (VAT)** | **Compliant:** Tax calculated on full legally binding purchase price per Iranian tax law. | **Non-Compliant / Risky:** Negative fee deflates taxable base; risks tax audit penalties. | **Compliant:** Tax calculated on full sale amount, meeting Iranian VAT requirements. |
| **4. Gateway Charge** | **Requires Custom Handling:** Standard gateways charge full total unless intercepted. | **Direct:** Gateway natively charges `get_total()` ($10M IRT). | **Precise:** Gateway explicitly instructs online provider to charge only initial payment. |
| **5. Order Total** | **Full Value ($50M):** Matches binding merchandise sale agreement. | **Deflated ($10M):** Displays only upfront cash; invoice does not show full sale price. | **Full Value ($50M):** Matches legal sale agreement and customer invoice. |
| **6. Total Paid** | **Managed:** Explicitly tracks initial paid ($10M) in metadata; avoids false $50M cash. | **False Core Paid:** `payment_complete()` marks entire order as paid in full cash. | **Controlled:** Metadata tracks settled cash ($10M); avoids false `payment_complete()` call. |
| **7. Deferred Receivable** | **Clear:** Deferred balance ($40M) tracked in club contract ledger. | **Hidden:** WooCommerce has zero record of receivable; exists only in external tables. | **Fully Linked:** Order metadata links directly to `wp_negar_club_contracts.id`. |
| **8. Sales Reporting** | **Requires Filter:** Standard WC reports may count full sale before cash is in hand. | **Distorted:** Reports show only $10M revenue; understates actual sales volume. | **Integrated:** Sales report reflects gross GMV; club ledger tracks cash vs receivable. |
| **9. Inventory Reduction** | **Manual Core Hook:** Requires calling `wc_reduce_stock_levels()` explicitly. | **Automatic:** Core `payment_complete()` automatically reduces stock. | **Controlled:** Gateway executes `wc_reduce_stock_levels()` upon verified callback. |
| **10. Refund/Cancellation** | **Flexible:** Full merchandise value allows refunds of both initial and later payments. | **Capped:** Core `get_remaining_refund_amount()` caps refunds at $10M; breaks future refunds. | **Comprehensive:** Supports partial initial refund via WC and installment refund via club ledger. |

### 2.4 Recommended Architecture & Governance
We recommend **Alternative C (Dedicated Installment Payment Gateway Integration)** as the most robust, compliant, and architecturally sound approach. Alternative A is a viable secondary fallback. Alternative B is **rejected** due to explicit core warnings in `class-wc-order-item-fee.php` and VAT compliance risks.

Because this affects store accounting, revenue recognition, and tax reporting, this architecture is governed by:
$$\textbf{Status: PROPOSED — REQUIRES EMPLOYER APPROVAL (CLUB-BD-001, CLUB-BD-002, CLUB-BD-014)}$$

> [!IMPORTANT]
> **Commercial & Tax Policy Dependency:**
> The final choice depends on the merchant's official tax and accounting policy:
> 1. Does the store issue an official VAT invoice (فاکتور رسمی ارزش افزوده) for the full 100% merchandise amount at the date of purchase, or are periodic VAT invoices issued upon installment settlements?
> 2. How does the merchant's financial system book deferred credit receivables (حساب‌های دریافتنی اقساطی)?
> Implementation will proceed in Phase 6 only after explicit employer approval.

---

### 2.5 Refunds & Cancellations Lifecycle
When an installment order is refunded or cancelled:
1. **Initial Payment Refund:** Handled via WooCommerce standard refund API `wc_create_refund()`, targeting the initial payment cash settled through the gateway.
2. **Financed Balance Cancellation:** The linked `CreditAgreement` transitions to `cancelled`, and all unpaid scheduled installments transition to `cancelled`.
3. **Prior Settled Installments Refund:** If the customer had already paid one or more monthly installments prior to an authorized product return, a dedicated refund record is created in `wp_negar_club_refunds`, and the funds are refunded to the customer per the policy approved under `CLUB-BD-014`.

---

## 3. Iranian National ID Validation (Modulo 11) (Correction C2)

### 3.1 Algorithm Specification
The Iranian National Code (کد ملی) is a 10-digit identification number governed by an official **Modulo 11** checksum algorithm.

#### Exact Calculation Procedure
1. **Digit Normalization:** Convert all Persian (`۰-۹`) and Arabic-Indic (`٠-٩`) numerals to standard ASCII digits (`0-9`).
2. **String Format & Leading Zeros:** The code must be treated strictly as a **10-character string**. Leading zeros must be preserved and must **never** be stripped by converting to integer.
3. **Syntax Guard:** The string must consist of exactly 10 digits (`/^\d{10}$/`).
4. **Repeated Digits Rejection:** Identical repeated digit sequences (`0000000000`, `1111111111`, ..., `9999999999`) pass the mathematical modulo check in certain edge cases but are officially invalid and must be rejected immediately.
5. **Checksum Formula:**
   Let the 10 digits from left to right be:
   $$d_{10}, d_9, d_8, d_7, d_6, d_5, d_4, d_3, d_2, c$$
   where $c$ is the 10th digit (the check digit).
   - Multiply the first 9 digits by descending weights from 10 down to 2:
     $$\text{Sum} = \sum_{i=2}^{10} d_i \times i = (d_{10} \times 10) + (d_9 \times 9) + \dots + (d_2 \times 2)$$
   - Calculate the remainder modulo 11:
     $$\text{Remainder} = \text{Sum} \pmod{11}$$
   - Apply the Iranian national check-digit rule:
     $$\text{Expected Check Digit} = \begin{cases} \text{Remainder} & \text{if } \text{Remainder} < 2 \\ 11 - \text{Remainder} & \text{if } \text{Remainder} \ge 2 \end{cases}$$
   - If $c \equiv \text{Expected Check Digit}$, the national code is mathematically valid.

### 3.2 Mathematically Verified Test Vectors
The following test vectors have been calculated and verified:

| Test Vector | Digits ($d_{10} \dots d_2$) | Sum Calculation | Remainder | Expected Check | Given Check | Result | Rationale |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :--- |
| `0010000003` | `0,0,1,0,0,0,0,0,0` | $1 \times 8 = 8$ | $8 \pmod{11} = 8$ | $11 - 8 = 3$ | `3` | **VALID** | Positive test vector with leading zeros preserved. |
| `0790419904` | `0,7,9,0,4,1,9,9,0` | $63+72+24+5+36+27 = 227$ | $227 \pmod{11} = 7$ | $11 - 7 = 4$ | `4` | **VALID** | Positive test vector. |
| `۰۷۹۰۴۱۹۹۰۴` | Normalized to `0790419904` | Same | $7$ | $4$ | `4` | **VALID** | Persian numerals properly normalized. |
| `1111111111` | `1,1,1,1,1,1,1,1,1` | Repeated digit pattern | — | — | `1` | **INVALID** | Rejected by repeated-digit guard. |
| `0000000000` | `0,0,0,0,0,0,0,0,0` | Repeated digit pattern | — | — | `0` | **INVALID** | Rejected by repeated-digit guard. |
| `0012345678` | `0,0,1,2,3,4,5,6,7` | $8+14+18+20+20+18+14=112$ | $112 \pmod{11} = 2$ | $11 - 2 = 9$ | `8` | **INVALID** | Check digit mismatch ($8 \ne 9$). |
| `1234567890` | `1,2,3,4,5,6,7,8,9` | Various | Various | Non-zero | `0` | **INVALID** | Check digit mismatch. |

### 3.3 Checksum vs. Real Identity Verification
> [!IMPORTANT]
> Modulo 11 validation verifies **only the structural and mathematical integrity** of the national code string. It does **not** prove that the national code belongs to the applicant, that the person is alive, or that the identity is verified.
> Actual identity verification requires integration with governmental/authorized electronic identity verification services (e.g., سامانه شاهکار / ثبت احوال) which is an external commercial policy governed by `CLUB-BD-010` and `CLUB-BD-018`.

---

## 4. Financial Data Model & Accounting Invariants (Correction C3)

### 4.1 Original Terms vs. Rolling Balances
To ensure complete auditability, the financial data model strictly separates **immutable historical terms** from **persisted rolling balances** and **derived runtime queries**:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       CREDIT AGREEMENT FINANCIAL FIELDS                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ IMMUTABLE SNAPSHOT (Frozen at agreement execution):                         │
│  • purchase_amount          : Total merchandise retail value                │
│  • initial_payment          : Upfront cash collected at checkout            │
│  • financed_principal       : Original principal extended (purchase - init) │
│  • total_charges            : Total disclosed markup/fees                   │
│  • total_customer_obligation: Total repayable (financed_principal + charges)│
│  • installments_count       : Number of scheduled monthly installments      │
│                                                                             │
│ PERSISTED ROLLING BALANCES (Updated atomically upon verified settlements):  │
│  • paid_principal           : Sum of settled principal portions             │
│  • paid_charges             : Sum of settled charge portions                │
│  • remaining_principal_balance : financed_principal - paid_principal         │
│  • remaining_charges_balance   : total_charges - paid_charges               │
│  • total_outstanding_balance   : total_customer_obligation - (paid_p + paid_c)
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Exact Reconciliation Invariants
Every settlement, refund, or adjustment must satisfy:
1. **Original Schedule Consistency:**
   $$\sum_{i=1}^{N} \text{installment.principal\_amount}_i \equiv \text{contract.financed\_principal}$$
   $$\sum_{i=1}^{N} \text{installment.charge\_amount}_i \equiv \text{contract.total\_charges}$$
   $$\sum_{i=1}^{N} \text{installment.total\_amount}_i \equiv \text{contract.total\_customer\_obligation}$$
2. **Current Debt Reconciliation:**
   $$\text{contract.total\_outstanding\_balance} \equiv \sum \text{installment.total\_amount} \quad [\text{status} \in (\text{'scheduled'}, \text{'due'}, \text{'overdue'})]$$
3. **Settlement Completion:**
   $$\text{contract.total\_outstanding\_balance} === 0 \iff \text{contract.status} = \text{'completed'}$$

### 4.3 Refund Allocation Model & Invariants
Partial and full refunds are supported via a dedicated table `wp_negar_club_refunds` with deterministic allocation between principal and charges:
- Stores `refund_number`, `contract_id`, `payment_id`, `order_id`, `installment_id`, `amount_irt`, `principal_refunded_irt`, `charges_refunded_irt`, `amount_irr`, `reason`, `status`, and `gateway_refund_ref`.
- **Allocation Rule:** When an installment payment or initial payment is refunded:
  - If linked to a specific installment (`installment_id` is set), the refund is allocated proportionally or sequentially to that installment's `principal_amount` and `charge_amount`.
  - The linked installment status transitions from `paid` to `due` (or `refunded`/`waived` per approved policy `CLUB-BD-014`).
  - **Immutability of Original Terms:** The contract's original terms (`purchase_amount`, `initial_payment`, `financed_principal`, `total_charges`, `total_customer_obligation`) remain **strictly immutable** and are never decremented or overwritten.
  - **Deterministic Rolling Balance Invariants Post-Refund:**
    $$\text{Net Paid Principal} = \text{paid\_principal} - \text{principal\_refunded\_irt}$$
    $$\text{Remaining Principal Balance} = \text{financed\_principal} - \text{Net Paid Principal}$$
    $$\text{Net Paid Charges} = \text{paid\_charges} - \text{charges\_refunded\_irt}$$
    $$\text{Total Outstanding Balance} = \text{total\_customer\_obligation} - (\text{Net Paid Principal} + \text{Net Paid Charges})$$
  - An append-only audit event is written to `wp_negar_club_audit_log`.
  - Governed by `CLUB-BD-014` (`PROPOSED — REQUIRES EMPLOYER APPROVAL`).

### 4.4 Payment Schema Investigation & Dual Settlement Contexts
Our technical investigation identified several essential constraints in `wp_negar_club_payments`:
1. **Initial Payment vs. Agreement Lifecycle:** For initial checkout payments, the binding `CreditAgreement` is activated/created **only after** the initial payment has been verified by the payment provider. Therefore, `contract_id` cannot be `BIGINT UNSIGNED NOT NULL` during payment initiation; it must be **nullable** (`BIGINT UNSIGNED NULL`) and linked via `order_id` and `application_id` at initiation time, or tied to a provisional contract in `pending_prerequisite` state.
2. **Provider Transaction ID Nullability at Initiation:** At the moment a payment attempt is created on the merchant server, the external gateway has not yet returned a transaction reference or tracking token (e.g. Zibal `trackId` or ZarinPal `Authority`). Thus, `provider_tx_id` must be **nullable** (`VARCHAR(64) NULL`) during initiation. The unique constraint `UNIQUE KEY uq_provider_tx (gateway_provider, provider_tx_id)` is fully compliant with MySQL/InnoDB semantics, which permit multiple `NULL` values.
3. **Public Client Token Specification:** To track payments before receiving a provider reference, the server assigns a cryptographically random UUIDv4 `client_token VARCHAR(64) NOT NULL` with a dedicated unique index `UNIQUE KEY uq_client_token (client_token)`. This token is passed in the callback URL or gateway payload for reliable routing.
4. **Normalized Currency Representation:** Rather than forcing all payments to assume Rial (`IRR`), the schema supports dual fields:
   - `amount_internal_irt`: `BIGINT UNSIGNED NOT NULL` (storefront and contract ledger amount in integer Toman).
   - `gateway_amount`: `BIGINT UNSIGNED NOT NULL` (transacted amount in the provider's native currency).
   - `gateway_currency`: `VARCHAR(8) NOT NULL DEFAULT 'IRR'` (`IRR` or `IRT`).
   - If a provider natively accepts and reports in Toman (such as certain modern direct fintech APIs), Rial conversion is bypassed, eliminating unnecessary integer multiplication and division.
5. **Separation of Settlement Contexts:** To eliminate ambiguity between initial checkout payments and monthly installment servicing, each payment record declares its explicit settlement context:
   - `payment_context`: `VARCHAR(24) NOT NULL DEFAULT 'scheduled_installment'` (`ENUM('initial_checkout', 'scheduled_installment', 'early_settlement')`).
   - `initial_checkout`: Settles initial checkout payment, updates WooCommerce order, and activates `wp_negar_club_contracts`.
   - `scheduled_installment`: Settles a specific installment row identified by `installment_id`.
   - `early_settlement`: Settles all remaining outstanding balance for the contract.

### 4.5 Canonical Payment State Machine
Payment statuses are standardized across all documents:
- `initiated`: Payment attempt created; user redirected to gateway.
- `pending_verification`: Callback received from gateway; awaiting server verification.
- `verified`: Transaction verified by provider inquiry/signature and settled atomically.
- `failed`: Transaction failed at gateway or cancelled by user.
- `cancelled`: Payment attempt timed out or cancelled before callback.
- `disputed_amount`: Transaction amount or currency mismatch detected; flagged for operator review.
- `partially_refunded`: A portion of the verified amount was refunded.
- `refunded`: Full transaction amount was refunded.

---

## 5. Payment Webhook Security & Idempotency Architecture (Correction C4)

### 5.1 Provider Adapter Taxonomy
Iranian payment gateways employ three distinct authentication and callback mechanisms:

| Provider Class | Description | Gateway Examples | Verification Strategy |
| :--- | :--- | :--- | :--- |
| **Class 1 (Signature-Based)** | Gateway passes cryptographic signature in HTTP headers or POST payload. | Modern REST / Webhook gateways | Verify HMAC-SHA256 signature using stored merchant secret. No outbound inquiry required if signature is authoritative. |
| **Class 2 (Inquiry-Based)** | Gateway dispatches HTTP GET/POST with tracking parameters; merchant server must make outbound HTTPS call to verify. | Zibal (`/v1/verify`), ZarinPal (`/pg/v4/payment/verify.json`), Mellat (`bpVerifyRequest`) | Extract tracking token; perform outbound HTTPS inquiry with merchant credentials. Never mark verified based solely on inbound parameters. |
| **Class 3 (Dual)** | Gateway provides callback signature AND requires outbound settlement confirmation. | Advanced banking APIs | Validate inbound signature first; then perform outbound confirmation inquiry before committing settlement. |

### 5.2 Idempotency Correlation Sequence
To reliably correlate callbacks without assuming gateways preserve arbitrary custom metadata:
1. **Payment Initiation:**
   - Server creates a pending record in `wp_negar_club_payments` with internal `idempotency_key` and initial status `initiated`.
   - Server assigns a unique, public-safe tracking token `client_token = wp_generate_uuid4()`.
   - When calling gateway `/request`, pass `client_token` in supported fields (e.g., Zibal `orderId`, ZarinPal `order_id`), or append it to the callback URL:
     `https://example.com/wp-json/negar-club/v1/payment-callback?token=<client_token>`
2. **Gateway Response Storage:**
   - Gateway returns authority/track ID (e.g., Zibal `trackId`, ZarinPal `Authority`).
   - Server updates pending payment record: `provider_tx_id = <trackId>`.
3. **Inbound Callback Processing:**
   - Locate pending payment using either `token` query parameter or inbound `provider_tx_id`.
   - If payment status is already `verified`: return HTTP 200 idempotent acknowledgment immediately with zero database mutation.
   - If payment status is `initiated`: proceed to provider verification.

### 5.3 Exact Integer Currency Conversion
- Gateway amount received in Rial (`IRR`).
- Internal contract amount in Toman (`IRT`).
- **Mathematical Requirement:**
  The integer remainder condition `amount_irr % 10 === 0` must strictly evaluate to true before any division:
  $$\text{amount\_irr} \pmod{10} === 0$$
  $$\text{amount\_irt} = \text{intdiv}(\text{amount\_irr}, 10)$$
- If $\text{amount\_irr} \pmod{10} \ne 0$, the payload is corrupted or fraudulent; the transaction is rejected immediately with status `disputed_amount`.

### 5.4 Two-Stage Webhook Transaction Architecture (Correction C4)
A critical architectural defect in naive webhook implementations is holding a database row lock during an outbound HTTPS API call to the payment gateway. Network latency (often 500ms–3000ms for banking switches) or transient gateway timeouts while holding an open transaction starves the database connection pool and induces deadlocks under concurrent callbacks.

The NegarThem payment engine enforces a strict **Two-Stage Settlement Architecture**:

```
[Inbound Gateway Callback Received]
                  │
                  ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ STAGE A: EXTERNAL VERIFICATION (Zero Database Locks Held)                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Locate payment row by `client_token` or `provider_tx_id` (Read-only).    │
│ 2. If status is already 'verified': return HTTP 200 immediately (Idempotent)│
│ 3. Validate callback payload syntax and provider signature (Class 1).       │
│ 4. If Class 2 / Class 3: dispatch outbound HTTPS verification request to    │
│    provider verify endpoint WITHOUT holding any database transaction/lock. │
│ 5. Parse and normalize provider response (provider_ref, verified_amount).  │
│ 6. If provider reports payment failure: update status='failed'; return 400. │
└─────────────────────────────────────┬───────────────────────────────────────┘
                                      │ Provider Confirms Success
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ STAGE B: ATOMIC LOCAL SETTLEMENT (Strict DB Transaction & Row Locks)        │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Start MySQL Transaction ($wpdb->query('START TRANSACTION')).             │
│ 2. Acquire row locks in consistent deadlock-prevention hierarchy:           │
│    a. SELECT * FROM wp_negar_club_contracts WHERE id = ? FOR UPDATE         │
│    b. SELECT * FROM wp_negar_club_installments WHERE id = ? FOR UPDATE      │
│    c. SELECT * FROM wp_negar_club_payments WHERE id = ? FOR UPDATE          │
│ 3. Concurrency Re-Check: If payment is ALREADY 'verified', COMMIT & 200 OK. │
│ 4. Revalidate mathematical invariants:                                      │
│    • amount_irr % 10 === 0                                                  │
│    • intdiv(amount_irr, 10) === payment.amount_internal_irt                 │
│ 5. Execute atomic state mutations:                                          │
│    • UPDATE wp_negar_club_payments: status='verified', verified_at=NOW()    │
│    • IF payment_context == 'scheduled_installment':                         │
│        - UPDATE wp_negar_club_installments: status='paid', settled_at=NOW() │
│        - UPDATE wp_negar_club_contracts: update rolling balances            │
│        - IF total_outstanding_balance == 0: contract.status = 'completed'   │
│    • IF payment_context == 'initial_checkout':                              │
│        - UPDATE wp_negar_club_contracts: status='active', activated_at=NOW()│
│        - Set order status to 'processing' and call wc_reduce_stock_levels()  │
│ 6. INSERT append-only audit event into wp_negar_club_audit_log.             │
│ 7. COMMIT transaction; return HTTP 200 to gateway.                          │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 6. Quote Calculator Consistency & Boundaries (Correction C6)

### 6.1 Consistent Proposed Rounding Policy (`CLUB-BD-022`)
To eliminate all discrepancies between specification and contracts:
- **Convention:** The non-divisible integer remainder $R = P \pmod N$ is allocated to the **first installment**:
  $$\text{base} = \text{intdiv}(P, N)$$
  $$\text{first\_installment} = \text{base} + (P \pmod N)$$
  $$\text{subsequent\_installments} (2 \dots N) = \text{base}$$
- **Invariant:**
  $$\text{first\_installment} + \sum_{i=2}^{N} \text{subsequent\_installments} \equiv P$$
- **Status:** Marked `PROPOSED per CLUB-BD-022, REQUIRES EMPLOYER APPROVAL`.

### 6.2 Over-Ceiling Purchase Handling (Option B)
When customer enters desired purchase $A > \text{purchase\_ceiling}$:
- **Option B (Upfront Cash Addition):**
  $$\text{financed\_principal} = \text{purchase\_ceiling} - \text{standard\_initial\_payment}$$
  $$\text{additional\_upfront} = A - \text{purchase\_ceiling}$$
  $$\text{total\_upfront} = \text{standard\_initial\_payment} + \text{additional\_upfront}$$
  $$\text{total\_customer\_cost} = \text{total\_upfront} + \text{financed\_principal} = A$$
- Credit exposure is strictly capped at $\text{purchase\_ceiling} - \text{initial\_payment}$.

### 6.3 Public Endpoint Security
The quote calculator `/wp-json/negar-club/v1/quote` is a stateless, read-only public service.
- WordPress guest REST nonces do not provide strong anti-abuse protection for unauthenticated users.
- Real security controls:
  1. Strict input sanitization and schema bounds ($1 \le A \le 10,000,000,000$).
  2. IP-based rate limiting (e.g., max 60 requests/minute per IP).
  3. Response caching for identical parameter tuples.
  4. Zero database writes, zero order creation, zero credit extension.

---

## 7. Monorepo Packaging & Calculator Ownership (Correction C7)

### 7.1 Operational Specification of Option B
1. **Source Location:**
   `packages/negar-customer-club/` inside the theme repository.
2. **Local WAMP Development:**
   NTFS Directory Junction created via PowerShell:
   ```powershell
   New-Item -ItemType Junction -Path "X:\apps\wamp\www\wp4\wp-content\plugins\negar-customer-club" -Target "packages\negar-customer-club"
   ```
3. **Build & Exclusion Rules (`build-dist.js`):**
   - Theme build script `scripts/build-dist.js` adds `packages/` and `plugins/` to `EXCLUDE_NAMES`.
   - Theme ZIP `dist/negar-shop.zip` contains zero plugin code.
   - Dedicated plugin build script `scripts/build-plugin.js` packages `packages/negar-customer-club/` into `dist/negar-customer-club.zip` with root folder `negar-customer-club`.
4. **Data Durability:**
   Deleting or switching the active theme leaves `wp-content/plugins/negar-customer-club/` and all database tables completely intact.

### 7.2 Single Canonical Calculator Ownership
- The authoritative calculator logic resides exclusively in:
  `packages/negar-customer-club/includes/class-quote-calculator.php`
- In **Phase 4**, a lightweight, dependency-free foundation of this plugin exposes the calculator service and REST API.
- If the plugin is not yet activated during public theme preview, the theme provides a safe fallback presentation with pre-calculated static estimates and an informative notice.
- Zero duplicate calculation logic exists between theme and plugin.

### 7.3 Packaging, Migration & Rollback Safety Strategy
Rather than recommending destructive "drop custom tables" operations for schema recovery, the architecture specifies a strict, data-preserving rollback strategy:
1. **Pre-Migration Snapshot:** Before executing any DDL schema creation in Phase 5 or Phase 6, an authoritative database backup must be captured via `mysqldump` and verified into `backups/pre-customer-club-migration.sql`.
2. **Schema Version Tracking:** Database migration state is tracked explicitly in WordPress options via `negar_club_schema_version` (e.g., `100` for 1.0.0). Migrations run idempotently (`IF NOT EXISTS`, column existence checks).
3. **Migration-Specific Rollback Scripts:** Down-migrations are scripted to reverse DDL safely without dropping populated data tables.
4. **Absolute Prohibition Against Dropping Populated Financial Tables:** Under no circumstances may production rollback procedures drop `wp_negar_club_applications`, `wp_negar_club_contracts`, `wp_negar_club_installments`, `wp_negar_club_payments`, `wp_negar_club_refunds`, or `wp_negar_club_audit_log` once live or test customer financial records have been populated. Uninstallation in production requires explicit confirmation and archiving.

---

## 8. Root-Cause Analysis & Classification of `test:settings` Failure (Correction C8)

### 8.1 Status & Reclassification
$$\textbf{Status: DIAGNOSED — NOT CODE-FIXED / NOT RE-VERIFIED}$$

In strict compliance with the project's documentation-only mandate and database safety rules:
- **No application code, test code, or theme files were modified.**
- **No database options or tables were updated or touched.**
- **`npm run test:settings` was NOT re-run against the local WAMP database**, avoiding unintended option mutation or test state leakage.

### 8.2 Investigation Findings & Code Citations
In Phase 2 preflight, running `npm run test:settings` returned exit code 1 with:
`[FAIL] Theme settings regression: Homepage SSR HTML must include banner record compact-12-one`

We performed an in-depth code inspection of `tests/theme-settings-regression.js` (lines 274–293):
```javascript
const bannerSsrHtml = await (await page.request.get(BASE_URL)).text();
const bannerSectionMap = {
  'wide-banner-one': 'sec-08-banner-wide',
  'compact-27-one': 'sec-27-banners-compact-2',
  'compact-27-two': 'sec-27-banners-compact-2',
  'banners-29-one': 'sec-29-banners-2col',
  'banners-29-two': 'sec-29-banners-2col',
};
for (const bannerId of [
  'banners-grid-one', 'banners-grid-two', 'wide-banner-one',
  'banners-10-one', 'banners-10-two', 'compact-12-one', 'compact-12-two',
  ...
]) {
  const parentSec = bannerSectionMap[bannerId];
  if (parentSec && !bannerSsrHtml.includes(`<!-- negar-home-section:${parentSec} -->`)) {
    continue;
  }
  assert(bannerSsrHtml.includes(`data-negar-banner-record="${bannerId}"`), `Homepage SSR HTML must include banner record ${bannerId}`);
}
```

#### Exact Diagnosis:
1. `compact-12-one` belongs to section `sec-12-banners-compact` (defined in `template-parts/home/sec-12-banners-compact.php`).
2. However, `compact-12-one` was **omitted from `bannerSectionMap`** in `tests/theme-settings-regression.js`.
3. Consequently, `parentSec` evaluated to `undefined`, bypassing the conditional skip guard `if (parentSec && !...) continue;`.
4. The test then executed an unconditional assertion at line 292 that `data-negar-banner-record="compact-12-one"` must appear in `bannerSsrHtml`.
5. On the local WAMP environment, when section 12 is disabled in theme layout options or when the homepage is rendered via Elementor (`$is_elementor = true`), section 12 comments and HTML are legitimately absent from the homepage SSR HTML.
6. This caused the assertion to fail.

### 8.3 Required Fix Plan (Deferred to Authorized Code Phase)
The proven fix is to add `'compact-12-one': 'sec-12-banners-compact'` and `'compact-12-two': 'sec-12-banners-compact'` to `bannerSectionMap` in `tests/theme-settings-regression.js`. Because Phase 2 is strictly documentation-only with zero database writes, this fix will be implemented and re-verified during Phase 3 when code modifications are officially authorized.
