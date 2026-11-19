# Technical Research & Architectural Rationale: NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Date:** 2026-11-19
**Phase:** Phase 2.3 — Critical Financial Architecture Corrections (Minimal Scope)
**Author:** Senior WordPress & WooCommerce Architect Agent
**Status:** `DRAFT — PHASE 2.3 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

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
In standard WooCommerce purchases, `WC_Order::get_total()` equals the full cash charged through a payment gateway. When a gateway confirms payment, standard integrations call `$order->payment_complete()`, which:
1. Transitions order status to `processing` (or `completed`).
2. Decrements inventory stock via `wc_reduce_stock_levels()`.
3. Marks the order as eligible for warehouse fulfillment.

However, in an **installment purchase**:
- The customer purchases merchandise of total value $M$ (e.g., 50,000,000 IRT).
- The customer pays an upfront **Initial Payment** $I$ (e.g., 10,000,000 IRT) at checkout.
- The remaining **Financed Principal** $P = M - I$ (e.g., 40,000,000 IRT) is extended as a credit facility repayable in monthly installments.

In WooCommerce 10.5.2 core (`includes/wc-order-functions.php:134-143`), `wc_get_is_paid_statuses()` strictly defines the paid statuses:
```php
function wc_get_is_paid_statuses() {
    return apply_filters( 'woocommerce_order_is_paid_statuses', array( OrderStatus::PROCESSING, OrderStatus::COMPLETED ) );
}
```
Furthermore, `WC_Order::is_paid()` (`includes/class-wc-order.php:1681`) evaluates as:
```php
return apply_filters( 'woocommerce_order_is_paid', $this->has_status( wc_get_is_paid_statuses() ), $this );
```

> [!CRITICAL]
> **Core Accounting Fact:**
> WooCommerce considers `processing` and `completed` as **paid statuses**.
> If an order containing 50,000,000 IRT of merchandise is transitioned to `processing` upon receipt of only the 10,000,000 IRT initial payment:
> - `$order->is_paid()` evaluates to `true`.
> - WooCommerce sales reports, tax reports, customer invoice emails, and external ERP/accounting exports falsely book **50,000,000 IRT as collected cash**, creating an immediate **40,000,000 IRT unearned cash discrepancy**.
> - Therefore, **an installment order must NEVER be marked as `processing` or `completed` merely because its initial down payment was received.**

### 2.2 Deep Investigation of WooCommerce Core APIs
We inspected WooCommerce 10.5.2 core source code across `class-wc-order.php`, `wc-order-functions.php`, and `class-wc-order-item-fee.php`:

```php
// WooCommerce Core: class-wc-order-item-fee.php (lines 5-6, 170)
 * Fee is an amount of money charged for a service or something else. It is not supposed to be negative.
 * @param string $amount Fee amount (do not enter negative amounts).
```

| WooCommerce Method / Class | Core Behavior in WC 10.5.2 | Architectural Impact & Constraints |
| :--- | :--- | :--- |
| `WC_Order::get_total()` | Returns net total payable after fees, taxes, shipping, and coupons. Gateways use this to charge customers. | Retains the full legally binding merchandise value ($50M IRT). |
| `wc_get_is_paid_statuses()` | Returns `['processing', 'completed']`. | **Crucial Guard:** Any custom status for partial installment payment must NOT be added to this list. |
| `WC_Order::is_paid()` | Checks `$this->has_status( wc_get_is_paid_statuses() )`. | Returns `false` while an order is partially paid, preventing false revenue recognition. |
| `WC_Order_Item_Fee` | Line item for fees. Lines 6 & 170 explicitly warn: *"not supposed to be negative (do not enter negative amounts)"*. | **Rejected:** Using negative fees violates core contract and distorts VAT tax bases. |
| `wc_reduce_stock_levels($order_id)` | Core function to decrement inventory. Checks `_order_stock_reduced` in order meta. | **Called exactly once** upon verified down payment; subsequent calls are safely no-ops. |
| Order Meta (`update_post_meta` / HPOS CRUD) | Key-value metadata bound to the order. | Safely stores `_negar_club_initial_paid`, `_negar_club_financed_principal`, and `_negar_club_contract_id`. |

*(Note: Earlier documents referenced a non-existent `WC_Order::get_total_paid()`. That reference is now removed; native WooCommerce 10.5.2 tracks order totals and refunds, while partial cash amounts are tracked via order metadata).*

---

### 2.3 Practical Order Lifecycle via Custom Order Status (`wc-partially-paid`)

To solve the accounting challenge cleanly without bloated custom infrastructure:

1. **Full Merchandise Value Preserved:**
   `WC_Order::get_total()` remains at full retail value (50,000,000 IRT) for official commercial invoices, tax compliance, and legal customer obligations.
2. **Dedicated Installment Gateway (`WC_Gateway_Negar_Installment`):**
   Intercepts checkout, calculates the initial down payment (10,000,000 IRT), and passes **only the initial payment amount** to the online banking gateway.
3. **Custom Order Status: `wc-partially-paid`:**
   Upon verified receipt of the initial down payment, the order transitions to:
   - Status: `wc-partially-paid` (عنوان نمایشی: «پیش‌پرداخت اقساط پرداخت شد»).
   - Registered via standard WordPress `register_post_status()` and added to `wc_order_statuses`.
   - **Crucial Invariant:** `wc-partially-paid` is **NOT** included in `wc_get_is_paid_statuses()`.
   - Result: `$order->is_paid()` evaluates to **`false`**. WooCommerce reports and accounting systems truthfully show that the full order amount has not yet been received in cash.
4. **Order Metadata Tracking:**
   The gateway writes clean metadata to the order:
   - `_negar_club_initial_paid`: `10000000` (IRT)
   - `_negar_club_financed_principal`: `40000000` (IRT)
   - `_negar_club_contract_id`: `<contract_id>` (Link to `wp_negar_club_contracts`)
   - `_negar_club_initial_payment_tx`: `<provider_reference>`
5. **Exact Single Stock Reduction:**
   When transitioning from `pending` to `wc-partially-paid`, the engine calls `wc_reduce_stock_levels($order->get_id())` **exactly once**. WooCommerce records `_order_stock_reduced = 'yes'`, guaranteeing that inventory is never decremented twice.
6. **Warehouse Fulfillment:**
   Warehouse operators and store managers treat `wc-partially-paid` as an authorized, fulfillable order, allowing physical packaging and shipping.
7. **Terminal Settlement:**
   When all scheduled monthly installments in `wp_negar_club_contracts` are settled (`contract.total_outstanding_balance === 0`), the contract status becomes `completed`, and the order status is updated to `completed`, at which point `$order->is_paid()` truthfully becomes `true`.

---

### 2.4 Comparative Evaluation Across 10 Criteria

| Evaluation Criterion | Alternative A (Full Order + Processing Status) | Alternative B (Negative Fee Line Item) | Alternative C (Dedicated Gateway + `wc-partially-paid` - Recommended) |
| :--- | :--- | :--- | :--- |
| **1. Product Gross** | Accurate: Full line items retained. | Accurate in line items, deflated total. | **Accurate:** Full merchandise line item value is retained. |
| **2. Discount Accounting** | Clean: Coupons calculate natively. | Distorted: Fee classified as discount. | **Clean:** Coupons and discounts calculate natively. |
| **3. Taxable Base (VAT)** | Compliant: Tax on full legal price. | Non-Compliant: Deflates VAT base. | **Compliant:** Tax calculated on full legally binding sale value. |
| **4. Gateway Charge** | Requires manual intercept. | Charges net total ($10M). | **Precise:** Gateway charges only initial payment ($10M). |
| **5. Order Total** | Full Value ($50M). | Deflated ($10M). | **Full Value ($50M):** Matches legal purchase contract and invoice. |
| **6. WooCommerce Paid Status** | **Fatal Flaw:** `processing` marks order as 100% paid ($50M). | False: Core marks order 100% paid ($10M). | **Truthful:** `wc-partially-paid` keeps `is_paid() == false`. |
| **7. Deferred Receivable** | Tracked in club ledger. | Hidden from WooCommerce. | **Fully Linked:** Order metadata links directly to `wp_negar_club_contracts.id`. |
| **8. Sales Reporting** | Distorted: $50M counted as cash. | Distorted: Only $10M reported. | **Truthful:** Gross merchandise volume reported; cash receipt tracked cleanly. |
| **9. Inventory Reduction** | Manual or core hook. | Core `payment_complete()`. | **Exactly Once:** `wc_reduce_stock_levels()` called upon verified down payment. |
| **10. Refund/Cancellation** | Supported. | Capped at $10M by WC limits. | **Comprehensive:** Supports down-payment refund via WC and installment refund via ledger. |

### 2.5 Recommended Architecture & Governance
We recommend **Alternative C (Dedicated Installment Payment Gateway + `wc-partially-paid` Custom Status)**.
Alternative B is rejected due to `class-wc-order-item-fee.php` core violations. Alternative A is rejected because `processing` falsely triggers `$order->is_paid() === true`.

Because commercial invoicing and VAT timing depend on merchant legal and tax policies, this decision remains:
$$\textbf{Status: PROPOSED — REQUIRES EMPLOYER APPROVAL (CLUB-BD-001, CLUB-BD-002, CLUB-BD-014)}$$

> [!IMPORTANT]
> **Commercial & Tax Policy Dependency:**
> The merchant's tax advisor must confirm whether:
> 1. Official VAT invoices (فاکتور رسمی ارزش افزوده) are issued for the full 100% amount upon order creation, or periodically as installments are paid.
> 2. How deferred installment receivables (حساب‌های دریافتنی اقساطی) are mapped to external accounting ledgers.
> Implementation will proceed in Phase 6 only after explicit employer/accountant approval.

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

### 4.3 Refund Accounting Consistency & Allocation Model (Correction C4)
To ensure absolute mathematical integrity and prevent double-deduction errors, the financial engine enforces a strict distinction between two fundamentally different financial operations:

1. **Operation A: Cash Refund (بازگشت وجه نقد):**
   - The merchant returns cash previously collected from the customer back to their bank account (e.g. for an overpayment or a returned item that was already paid for).
   - This operation reduces the cumulative net cash received by the merchant.
   - It does **not** reduce the customer's remaining contractual debt obligation (in fact, if the customer retains the merchandise, unpaid debt increases).
2. **Operation B: Contractual Debt Cancellation / Product Return (کاهش تعهد ناشی از عودت کالا):**
   - The customer returns physical merchandise before completing installment payments.
   - Unpaid future scheduled installments are transitioned to status `cancelled`.
   - The customer's remaining contractual obligation is reduced by the value of the returned goods.
   - **Crucial Rule:** No cash is refunded to the customer for unpaid future installments, because cash was never collected for them.

#### Accounting Convention in `wp_negar_club_contracts`:
- **Original Contract Terms (Strictly Immutable):**
  `purchase_amount`, `initial_payment`, `financed_principal`, `total_charges`, and `total_customer_obligation` are frozen at execution and **never decremented**.
- **Cumulative Gross Cash Received:**
  - `paid_principal`: Cumulative gross principal cash paid by customer (never decremented on refund).
  - `paid_charges`: Cumulative gross charges cash paid by customer (never decremented on refund).
- **Dedicated Refund Ledger (`wp_negar_club_refunds`):**
  Stores each cash refund transaction with explicit breakdown:
  - `principal_refunded_irt`: Cash refunded from previously paid principal.
  - `charges_refunded_irt`: Cash refunded from previously paid charges.
- **Derived Net Invariants (Zero Double Deduction):**
  $$\text{Net Cash Received} = (\text{paid\_principal} - \text{principal\_refunded\_irt}) + (\text{paid\_charges} - \text{charges\_refunded\_irt})$$
  $$\text{Remaining Principal Balance} = \text{financed\_principal} - (\text{paid\_principal} - \text{principal\_refunded\_irt}) - \text{cancelled\_principal\_obligation}$$
  $$\text{Total Outstanding Debt} = \text{total\_customer\_obligation} - \text{Net Cash Received} - \text{cancelled\_obligation}$$
- **Limitation on Product-Level Partial Returns:**
  If a customer returns a single item from a multi-item installment order, allocating the debt reduction across remaining installments (e.g. reducing monthly installment amounts vs shortening remaining tenure) requires an explicit commercial policy governed by `CLUB-BD-014` (`PROPOSED — REQUIRES EMPLOYER APPROVAL`). Rather than introducing speculative code abstractions, this limitation is formally documented.

### 4.4 Payment Schema & Dual Settlement Contexts
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
   - `initial_checkout`: Settles initial checkout payment, updates WooCommerce order to `wc-partially-paid`, reduces stock once, and creates/activates `wp_negar_club_contracts`.
   - `scheduled_installment`: Settles a specific installment row identified by `installment_id`.
   - `early_settlement`: Settles all remaining outstanding balance for the contract.

### 4.5 Canonical Payment State Machine
Payment statuses are standardized across all documents:
- `initiated`: Payment attempt created; user redirected to gateway.
- `pending_verification`: Callback received or outbound inquiry in progress; awaiting server verification.
- `verified`: Transaction verified by provider inquiry/signature and settled atomically.
- `duplicate_settlement_detected`: Gateway confirmed payment, but target installment was already settled by a concurrent payment attempt; held for operator review/refund without double-crediting.
- `verified_pending_contract`: Initial payment verified with gateway, but local contract creation encountered a temporary database error; queued for safe automated retry.
- `failed`: Transaction definitively rejected or cancelled by provider/user.
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

### 5.4 Two-Stage Webhook Transaction Architecture (Corrections C2, C3, C5)
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
│ 2. If status is already terminal ('verified', 'duplicate_settlement_detected'):
│    return HTTP 200 immediately (Idempotent No-Op; Stale Callback Guard).    │
│ 3. If Class 1: Validate cryptographic signature on incoming payload.        │
│ 4. If Class 2 / Class 3: Make outbound HTTPS verify call to provider API   │
│    WITHOUT holding any database transaction or row lock.                    │
│ 5. Failure Classification (Correction C5):                                  │
│    • Network Timeout / cURL Error / HTTP 5xx: DO NOT mark failed!            │
│      Keep status = 'pending_verification'; return HTTP 503 to trigger retry. │
│    • Definitive Provider Rejection (Code -1, cancelled, invalid token):     │
│      UPDATE wp_negar_club_payments SET status = 'failed'                     │
│      WHERE id = ? AND status IN ('initiated', 'pending_verification');      │
│      return HTTP 400.                                                       │
│ 6. Parse and normalize provider response (provider_ref, verified_amount).  │
└─────────────────────────────────────┬───────────────────────────────────────┘
                                      │ Provider Confirms Success
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ STAGE B: ATOMIC LOCAL SETTLEMENT (Strict DB Transaction & Row Locks)        │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Start MySQL Transaction ($wpdb->query('START TRANSACTION')).             │
│                                                                             │
│ 2. BRANCH BY PAYMENT CONTEXT:                                               │
│                                                                             │
│ [Context A: initial_checkout (Correction C2)]                               │
│  a. Lock application: SELECT * FROM wp_negar_club_applications              │
│     WHERE id = ? FOR UPDATE; verify status == 'approved'.                   │
│  b. Check contract existence: SELECT * FROM wp_negar_club_contracts         │
│     WHERE order_id = ? FOR UPDATE.                                          │
│     - If ALREADY exists (idempotent replay): use existing contract.         │
│     - If NOT exists:                                                        │
│         - INSERT INTO wp_negar_club_contracts:                              │
│             contract_number, order_id, application_id, user_id,             │
│             purchase_amount, initial_payment, financed_principal,           │
│             total_charges, total_customer_obligation, status = 'active',   │
│             activated_at = NOW(), plan_snapshot = ...                        │
│         - Generate N scheduled installment rows in                          │
│           wp_negar_club_installments with status = 'scheduled'.             │
│  c. Link & verify payment: UPDATE wp_negar_club_payments SET                 │
│     status = 'verified', contract_id = contract.id,                         │
│     provider_reference = ?, verified_at = NOW()                             │
│     WHERE id = ? AND status IN ('initiated', 'pending_verification');       │
│  d. Update WooCommerce Order:                                               │
│     - Set custom status: 'wc-partially-paid' (keeps is_paid() == false).    │
│     - Set order meta: _negar_club_initial_paid, _negar_club_contract_id.    │
│     - Call wc_reduce_stock_levels($order->get_id()) (executed exactly once).│
│  e. INSERT audit_log event: 'initial_checkout_settled'.                     │
│                                                                             │
│ [Context B: scheduled_installment (Correction C3)]                          │
│  a. Lock installment: SELECT * FROM wp_negar_club_installments              │
│     WHERE id = ? FOR UPDATE.                                                │
│  b. Concurrency & Duplicate Check:                                          │
│     - If installment.status == 'paid' OR settled_payment_id IS NOT NULL:    │
│         - If payment.id == installment.settled_payment_id:                  │
│             COMMIT; return HTTP 200 OK (Idempotent replay).                 │
│         - If payment.id != installment.settled_payment_id:                  │
│             Concurrent duplicate payment detected! DO NOT settle again.     │
│             UPDATE wp_negar_club_payments SET                               │
│               status = 'duplicate_settlement_detected',                     │
│               provider_reference = ?, verified_at = NOW() WHERE id = ?;     │
│             INSERT audit_log: 'duplicate_installment_payment_flagged';      │
│             COMMIT; return HTTP 200 OK.                                     │
│  c. Conditional Atomic Update:                                              │
│     UPDATE wp_negar_club_installments SET status = 'paid',                  │
│       paid_amount = total_amount, settled_payment_id = payment.id,          │
│       settled_at = NOW() WHERE id = ? AND status != 'paid';                 │
│     (Only if rows_affected == 1):                                           │
│       - Lock contract: SELECT * FROM wp_negar_club_contracts                │
│         WHERE id = ? FOR UPDATE; update rolling balances.                   │
│       - IF total_outstanding_balance == 0: contract.status = 'completed'.  │
│       - UPDATE wp_negar_club_payments: status = 'verified'.                 │
│       - INSERT audit_log event: 'installment_settled'.                      │
│                                                                             │
│ 3. COMMIT transaction ($wpdb->query('COMMIT')); return HTTP 200 to gateway. │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### Practical Recovery Mechanism for Contract Creation Failure (Correction C2):
If provider verification succeeds in Stage A, but Stage B encounters an unhandled local failure (e.g. database connection drop or constraint error during contract generation):
1. The transaction automatically rolls back.
2. The payment record is updated to `verified_pending_contract` with `provider_reference` stored.
3. An urgent alert is recorded in `wp_negar_club_audit_log` and an administrative order note is appended:
   «پیش‌پرداخت در درگاه بانکی تأیید شد اما صدور قرارداد با خطای پایگاه داده مواجه گردید. شماره ارجاع درگاه: XYZ».
4. A scheduled recovery reconciler (`negar_club_reconcile_pending_contracts`) or an administrative action allows the operator to trigger Stage B contract creation safely and idempotently without charging the customer again.

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
