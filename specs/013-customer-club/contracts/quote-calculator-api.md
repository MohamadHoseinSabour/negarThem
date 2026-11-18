# Integration Contract: Quote Calculator API

**Feature:** 013-customer-club  
**Component:** Server-Authoritative Quote Calculator  
**Target Phase:** Phase 4 (Public UI Preview) & Phase 6 (Financial Engine)  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

---

## 1. Overview & Purpose

The **Quote Calculator API** provides a deterministic, server-authoritative calculation service for installment previews and customer quotations.

### Core Architectural Principles
1. **Early Availability (Phase 4):** This component is available during Phase 4 to guarantee exact mathematical parity between the client-side JavaScript calculator and server-side rules without requiring live financial plugin activation.
2. **Zero Side-Effects:** The calculator is strictly stateless and read-only. It **never** creates database records, **never** creates WooCommerce orders, and **never** extends binding credit.
3. **Integer Arithmetic (`IRT`):** All calculations operate on integer Iranian Toman with 0 decimals. Floating-point division is forbidden. Non-divisible remainders are deterministically allocated to the first installment.
4. **Provisional Disclaimers:** Any output calculated under unsettled commercial policies (`CLUB-BD-005`, `CLUB-BD-006`, `CLUB-BD-011`, `CLUB-BD-022`) is explicitly flagged with `is_provisional: true`.

---

## 2. PHP Internal Interface

```php
declare(strict_types=1);

namespace NegarThem\CustomerClub\Calculator;

interface QuoteCalculatorInterface {
    /**
     * Compute an installment quote preview for a specified plan and purchase amount.
     *
     * @param int    $purchaseAmount Purchase amount in integer Toman (IRT).
     * @param string $planId         Plan identifier ('negar_aghaz', 'negar_plus', 'negar_vizhe').
     * @param array  $planOverride   Optional configuration overrides for testing.
     * @return QuoteResult
     * @throws InvalidAmountException If amount is negative or exceeds maximum safe integer.
     * @throws UnknownPlanException   If planId is not registered.
     */
    public function calculate(int $purchaseAmount, string $planId, array $planOverride = []): QuoteResult;
}
```

---

## 3. REST API Contract

### Endpoint Specification
- **Route:** `POST /wp-json/negar-club/v1/quote`
- **Authentication:** Public (Open to Guests and Authenticated Users)
- **Nonce:** Required for browser callers (`X-WP-Nonce: wp_create_nonce('wp_rest')`) to prevent abuse; returns standard JSON.
- **Cacheability:** Cacheable by client or transient key based on input parameters.

### 3.1 Request Schema

```json
{
  "purchase_amount": 25000000,
  "plan_id": "negar_plus"
}
```

| Field | Type | Required | Constraints / Description |
| :--- | :--- | :---: | :--- |
| `purchase_amount` | Integer | Yes | Integer Toman (`IRT`). Minimum `1`, maximum `10,000,000,000`. Persian/Arabic digits normalized before parsing. |
| `plan_id` | String | Yes | Must be one of active plans: `negar_aghaz`, `negar_plus`, `negar_vizhe`. |

---

### 3.2 Success Response Schema (`200 OK`)

```json
{
  "success": true,
  "data": {
    "plan_id": "negar_plus",
    "plan_title": "نگار پلاس",
    "purchase_amount": 25000000,
    "purchase_ceiling": 50000000,
    "initial_payment": 5000000,
    "additional_upfront": 0,
    "total_upfront": 5000000,
    "financed_principal": 20000000,
    "total_disclosed_charges": 0,
    "total_payable": 25000000,
    "installments_count": 6,
    "base_installment_amount": 3333333,
    "remainder_amount": 2,
    "first_installment_amount": 3333335,
    "subsequent_installment_amount": 3333333,
    "schedule_preview": [
      { "sequence": 1, "principal": 3333335, "charge": 0, "total": 3333335 },
      { "sequence": 2, "principal": 3333333, "charge": 0, "total": 3333333 },
      { "sequence": 3, "principal": 3333333, "charge": 0, "total": 3333333 },
      { "sequence": 4, "principal": 3333333, "charge": 0, "total": 3333333 },
      { "sequence": 5, "principal": 3333333, "charge": 0, "total": 3333333 },
      { "sequence": 6, "principal": 3333333, "charge": 0, "total": 3333333 }
    ],
    "is_binding": false,
    "is_provisional": true,
    "disclaimer_text": "این محاسبه صرفاً پیش‌نمایش و برآورد تخمینی است و هیچ‌گونه تعهد مالی یا تأیید اعتبار تلقی نمی‌گردد.",
    "policy_flags": [
      "NO_INTEREST_PROVISIONAL_MODEL",
      "REMAINDER_ALLOCATED_TO_FIRST_INSTALLMENT"
    ]
  }
}
```

---

### 3.3 Boundary & Error Responses

#### A. Amount Exceeds Purchase Ceiling (`CLUB-BD-006`)
When `purchase_amount > purchase_ceiling`:
- In the provisional standard model (Option A per `CLUB-BD-006`), the excess amount is required upfront as additional cash:

```json
{
  "success": true,
  "data": {
    "purchase_amount": 60000000,
    "purchase_ceiling": 50000000,
    "initial_payment": 5000000,
    "additional_upfront": 10000000,
    "total_upfront": 15000000,
    "financed_principal": 45000000,
    "installments_count": 6,
    "validation_notices": [
      "مبلغ انتخابی بیش از سقف خرید طرح است. مازاد سقف (۱۰,۰۰۰,۰۰۰ تومان) به پیش‌پرداخت افزوده می‌شود."
    ],
    "is_provisional": true
  }
}
```

#### B. Amount Below Minimum Initial Payment (`CLUB-BD-005`)
When `purchase_amount <= initial_payment`:
- Returns HTTP 422 with explicit Persian explanation:

```json
{
  "success": false,
  "code": "AMOUNT_BELOW_MINIMUM",
  "message": "مبلغ خرید باید بیشتر از حداقل پیش‌پرداخت طرح (۵,۰۰۰,۰۰۰ تومان) باشد.",
  "data": {
    "min_purchase_required": 5000001
  }
}
```

#### C. Invalid Plan ID
```json
{
  "success": false,
  "code": "INVALID_PLAN",
  "message": "طرح اقساطی انتخاب‌شده معتبر یا فعال نمی‌باشد."
}
```
