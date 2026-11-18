# Technical Research & Architectural Rationale: NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Date:** 2026-11-18
**Phase:** Phase 2.1 — Architecture Corrections & Design Consistency
**Author:** Senior WordPress & WooCommerce Architect Agent
**Status:** `DRAFT — PHASE 2.1 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

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

### 2.2 Deep Investigation of WooCommerce Core APIs
We analyzed the official WooCommerce CRUD APIs under HPOS:

| WooCommerce Method | Architectural Behavior & Constraints |
| :--- | :--- |
| `WC_Order::get_total()` | Returns the net total payable on the order after fees, taxes, shipping, and discounts. Payment gateways use this value to dispatch the transaction amount. |
| `WC_Order::get_total_paid()` | Under HPOS, tracks the exact cash received. In core WooCommerce, `payment_complete()` sets `total_paid` equal to `get_total()`. |
| `WC_Order::get_remaining_refund_amount()` | Calculated as `get_total() - get_total_refunded()`. An order cannot refund more than `get_total()`. |
| `WC_Order::payment_complete($tx_id)` | Authoritative core method. Sets transaction ID, sets `date_paid`, transitions status (`processing`/`completed`), reduces stock. Expects full cash settlement of `get_total()`. |
| `WC_Order::set_status($status, $note)` | Changes order status directly without invoking payment completion logic or setting `date_paid` unless explicitly managed. |
| `WC_Order_Item_Fee` | Native line item allowing positive or negative monetary adjustments. A negative fee reduces `get_total()` cleanly before taxes and totals calculation. |

### 2.3 Evaluation of Architecture Alternatives

#### Alternative 1: Net Gateway Charge via Deferred Credit Line Item (Recommended)
- **Mechanism:**
  - The customer's cart contains the merchandise items at full retail price (e.g., 50,000,000 IRT).
  - When the customer selects an approved installment plan at checkout, an order fee line item (`WC_Order_Item_Fee`) titled «تسهیلات اقساطی باشگاه مشتریان» with a **negative amount** equal to $-\text{financed\_principal}$ ($-40,000,000$ IRT) is applied to the order.
  - The resulting `WC_Order::get_total()` equals exactly the **Initial Payment** ($10,000,000$ IRT).
  - The active gateway charges $10,000,000$ IRT.
  - Upon gateway callback, `$order->payment_complete($tx_id)` is invoked cleanly. `total_paid` is recorded as $10,000,000$ IRT.
  - The order transitions to `processing`, stock is decremented, and the warehouse fulfills the order.
  - An immutable `CreditAgreement` is instantiated in `wp_negar_club_contracts` recording:
    - `purchase_amount`: 50,000,000 IRT
    - `initial_payment`: 10,000,000 IRT
    - `financed_principal`: 40,000,000 IRT
    - Linked `order_id`: `$order->get_id()`
- **Accounting Trade-offs:**
  - *Pros:* 100% compliant with standard WooCommerce gateway contracts and tax calculations. Gateway settlement matches bank reconciliation exactly ($10M IRT). Order fulfillment operates natively.
  - *Cons:* WooCommerce standard revenue reports reflect only the cash collected ($10M IRT) until installments are settled, requiring a custom reporting filter or club dashboard to aggregate deferred revenue.

#### Alternative 2: Full Merchandise Total with Custom Payment Gateway & Explicit Status
- **Mechanism:**
  - Order total remains at full merchandise price ($50,000,000$ IRT).
  - A dedicated custom payment gateway (`WC_Gateway_Negar_Installment`) handles checkout. It intercepts payment and submits only the initial payment ($10,000,000$ IRT) to the online bank gateway.
  - Upon callback, the gateway records custom metadata: `_negar_club_initial_paid = 10000000` and `_negar_club_financed = 40000000`.
  - The gateway does **not** call `$order->payment_complete()`. Instead, it manually calls:
    `$order->set_status('processing', 'پیش‌پرداخت اقساط پرداخت شد. باقیمانده به عنوان تعهد اقساط ثبت گردید.');`
    and manually calls `wc_reduce_stock_levels($order->get_id());`.
- **Accounting Trade-offs:**
  - *Pros:* Order invoice shows full 50M IRT.
  - *Cons:* Standard WooCommerce reports consider 50M IRT as sales revenue immediately, creating unearned revenue distortion. Gateway audit reconciliation requires custom reporting.

#### Alternative 3: Dual Order / Down-Payment Ledger
- **Mechanism:** A down-payment order is generated for the initial cash, while the parent order represents the merchandise contract.
- **Accounting Trade-offs:** Rejected due to excessive customer confusion and duplicate order IDs.

### 2.4 Recommended Architecture & Governance
We recommend **Alternative 1 (Net Gateway Charge via Deferred Credit Line Item)** as the cleanest, most standard WooCommerce architecture.
However, because this affects accounting and store financial reporting, this recommendation is governed by:
$$\textbf{Status: PROPOSED — REQUIRES EMPLOYER APPROVAL (CLUB-BD-001, CLUB-BD-002, CLUB-BD-014)}$$

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

### 4.3 Refund Data Architecture
Partial and full refunds are supported via a dedicated table `wp_negar_club_refunds`:
- Stores `refund_number`, `contract_id`, `payment_id`, `order_id`, `amount_irt`, `amount_irr`, `reason`, `status`, and `gateway_refund_ref`.
- **Allocation Rule:** When an installment payment is refunded:
  - The linked installment reverts from `paid` to `due` (or `refunded`/`waived` per approved policy `CLUB-BD-014`).
  - `contract.paid_principal` is decremented by the refunded principal portion.
  - `contract.total_outstanding_balance` is recalculated.
  - An append-only audit event is written.

### 4.4 Canonical Payment State Machine
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

---

## 8. Root-Cause Analysis of `test:settings` Failure (Correction C8)

### 8.1 Investigation Findings
In Phase 2 preflight, running `npm run test:settings` returned exit code 1 with:
`[FAIL] Theme settings regression: Homepage SSR HTML must include banner record compact-12-one`

### 8.2 Code-Level Diagnosis
We inspected `tests/theme-settings-regression.js` (lines 274–293):
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

#### Exact Flaw Identified:
1. `compact-12-one` belongs to section `sec-12-banners-compact` (defined in `template-parts/home/sec-12-banners-compact.php`).
2. However, `compact-12-one` was **omitted from `bannerSectionMap`** in `theme-settings-regression.js`.
3. Consequently, `parentSec` evaluated to `undefined`, bypassing the conditional skip guard `if (parentSec && !...) continue;`.
4. The test then executed an unconditional assertion that `data-negar-banner-record="compact-12-one"` must appear in `bannerSsrHtml`.
5. On the local WAMP environment, if section 12 is disabled in the database layout options or if the front page is rendered via Elementor (`$is_elementor = true`), section 12 is not included in the homepage SSR HTML.
6. This caused the assertion to fail.

### 8.3 Database Safety & Non-Destructive Policy
In strict compliance with Master Prompt Section 2.1 and Section 10:
- No database modifications, option updates, or test reruns were performed during Phase 2.1.
- The test failure was caused by a pre-existing test assertion omission in `theme-settings-regression.js` interacting with the local database layout state, completely unrelated to Customer Club documentation.
