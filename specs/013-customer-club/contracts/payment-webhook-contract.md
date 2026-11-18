# Integration Contract: Payment Webhook & Settlement Gateway

**Feature:** 013-customer-club
**Component:** Provider-Independent Payment Webhook & Settlement Engine
**Target Phase:** Phase 6 (Financial Engine & Installment Payments)
**Phase:** Phase 2.2 — Final Architecture Consistency & Financial Lifecycle Audit
**Status:** `DRAFT — PHASE 2.2 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

---

## 1. Security Architecture & Provider Adapter Model (Correction C4.1)

### 1.1 Nonce Boundary & Webhook Authentication
Standard WordPress nonces (`wp_verify_nonce()`) rely on authenticated browser cookies or user session tokens. External bank payment gateways (Shaparak, Zibal, ZarinPal, Saman, Mellat) dispatch server-to-server callbacks outside of user browser sessions.

To satisfy `CLUB-NFR-007` and `CLUB-AC-047`:
1. **Public Endpoint:** The callback endpoint (`/wp-json/negar-club/v1/payment-callback`) is accessible without WordPress browser cookies.
2. **Provider-Specific Adapter Architecture:** Because Iranian payment gateways do not follow a uniform callback schema, the system uses a **provider-independent settlement interface** backed by gateway-specific adapters:
   - **Class 1 (Cryptographic Signature):** Validates inbound HMAC-SHA256 signature in headers/payload (e.g., `X-Signature: HMAC-SHA256(payload, secret)`).
   - **Class 2 (Server-to-Server Inquiry):** Inbound callback provides tracking parameters (e.g., Zibal `trackId`, ZarinPal `Authority`); the adapter makes an immediate outbound authenticated HTTPS call to the provider's `/verify` endpoint using stored merchant credentials.
   - **Class 3 (Dual Authentication):** Inbound signature is validated first, followed by an outbound server inquiry before settlement.
3. **Removal of Universal Signature Assumption:** A callback schema does **not** mandate a universal `signature` field if the specific provider adapter relies on authoritative server-to-server inquiry (Class 2).
4. **No Unauthenticated Bypass:** Every callback must pass its provider-specific authentication before any business logic or settlement executes.

---

## 2. Idempotency Correlation & Two-Stage Settlement Sequence (Correction C4.2 & C4.5)

To reliably correlate callbacks without assuming gateways preserve arbitrary merchant metadata, and to avoid database starvation from holding locks across external HTTP requests:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      IDEMPOTENCY CORRELATION SEQUENCE                       │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Payment Initiation:                                                      │
│    • Local record created in wp_negar_club_payments with:                   │
│      - status = 'initiated'                                                 │
│      - client_token = wp_generate_uuid4() (Unique public tracking token)   │
│      - payment_context = 'initial_checkout' OR 'scheduled_installment'     │
│      - idempotency_key = 'PAY-<context>-<id>-<unique_token>'                │
│                                                                             │
│ 2. Gateway Dispatch:                                                        │
│    • Send transaction request to gateway API.                               │
│    • Set callback URL containing the token:                                 │
│      https://site.ir/wp-json/negar-club/v1/payment-callback?token=<token>  │
│    • Gateway returns provider tracking ID (e.g., trackId, Authority).       │
│    • Store provider_tx_id in pending payment row.                           │
│                                                                             │
│ 3. Callback Ingestion:                                                      │
│    • Inbound callback matches payment row by:                               │
│      (a) `token` query parameter (`client_token`), OR                       │
│      (b) `provider_tx_id` (Authority / trackId) in POST body.               │
│                                                                             │
│ 4. Two-Stage Execution Boundary:                                            │
│    • STAGE A (External Verification): Execute provider verification         │
│      WITHOUT holding database transaction or row locks.                     │
│    • STAGE B (Atomic Local Settlement): Acquire DB locks and mutate ledger  │
│      atomically inside an isolated MySQL transaction.                       │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Inbound Webhook Schemas & Examples (Correction C4.4)

### 3.1 Class 1 Schema Example (Signature-Based Gateway)
```json
{
  "gateway_provider": "generic_fintech",
  "provider_tx_id": "TX-99887766",
  "client_token": "a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d",
  "gateway_amount": 33333330,
  "gateway_currency": "IRR",
  "payment_context": "scheduled_installment",
  "status_code": 100,
  "timestamp": 1795000000,
  "signature": "TEST_HMAC_SHA256_e4d909c290d0fb1ca068ffaddf22cbd0c0e5a840e53a"
}
```
*Note:* The signature is an illustrative test placeholder. In production, adapters compute:
`hash_hmac('sha256', $canonical_payload, $provider_merchant_secret)`.

### 3.2 Class 2 Schema Example (Inquiry-Based Gateway — e.g., Zibal / ZarinPal)
```json
{
  "gateway_provider": "zibal",
  "trackId": "123456789",
  "success": 1,
  "status": 2,
  "orderId": "a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d"
}
```
*Verification Action:* Adapter extracts `trackId: 123456789`, makes outbound POST to `https://gateway.zibal.ir/v1/verify` with `merchant: <secret>`, and verifies `result: 100` and `amount: 33333330`.

---

## 4. Currency Conversion Boundary (Correction C4.3)

Storefront prices and customer debts are denominated in **integer Toman (`IRT`)**.
When external bank gateways report in **Rial (`IRR`)**, strict integer modulo verification is mandatory.
If the gateway operates natively in **Toman (`IRT`)**, currency conversion is bypassed.

### Exact Integer Conversion Rule (When `gateway_currency == 'IRR'`)
$$\text{gateway\_amount} \pmod{10} === 0$$
$$\text{amount\_internal\_irt} = \text{intdiv}(\text{gateway\_amount}, 10)$$

### Validation Guard:
```php
if ( 'IRR' === $payment->gateway_currency ) {
    if ( ( $gateway_amount % 10 ) !== 0 ) {
        // Corrupted or fractional Rial amount detected — halt immediately
        $payment->update_status( 'disputed_amount', 'مبلغ ریالی دریافتی بر ۱۰ بخش‌پذیر نیست.' );
        return new WP_Error( 'INVALID_CURRENCY_CONVERSION', 'مبلغ نامعتبر است.', array( 'status' => 422 ) );
    }
    $amount_internal_irt = intdiv( $gateway_amount, 10 );
} else {
    $amount_internal_irt = $gateway_amount;
}

if ( $amount_internal_irt !== (int) $payment->amount_internal_irt ) {
    $payment->update_status( 'disputed_amount', 'مغایرت مبلغ پرداختی با مبلغ ثبت‌شده.' );
    return new WP_Error( 'AMOUNT_MISMATCH', 'مبلغ پرداختی مطابقت ندارد.', array( 'status' => 422 ) );
}
```

---

## 5. Complete Two-Stage Settlement Sequence (Correction C4.5)

```
[Inbound Callback Received at /wp-json/negar-club/v1/payment-callback]
                                  │
                                  ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ STAGE A: EXTERNAL VERIFICATION (Zero Database Locks Held)                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ Step A1: Read-only lookup of payment attempt by `token` or `provider_tx_id`. │
│          (Not found ──► HTTP 404 Not Found)                                 │
│                                                                             │
│ Step A2: Check Current State:                                               │
│          If payment.status == 'verified' ──► HTTP 200 OK (Idempotent No-Op) │
│                                                                             │
│ Step A3: Provider-Specific Verification:                                    │
│          • Class 1: Verify HMAC signature on incoming payload.              │
│          • Class 2 / 3: Make outbound HTTPS verify call to provider API     │
│            WITHOUT holding any open MySQL transaction or row lock.          │
│          (Provider verify fails ──► UPDATE status='failed'; HTTP 400 Bad Req)
│          (Provider verify succeeds with verified_ref and verified_amount)   │
└─────────────────────────────────────┬───────────────────────────────────────┘
                                      │ Provider Confirmed
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│ STAGE B: ATOMIC LOCAL SETTLEMENT (Strict DB Transaction & Row Locks)        │
├─────────────────────────────────────────────────────────────────────────────┤
│ Step B1: Begin MySQL Transaction ($wpdb->query('START TRANSACTION')).       │
│                                                                             │
│ Step B2: Acquire row locks in consistent deadlock-prevention hierarchy:     │
│          1. SELECT * FROM wp_negar_club_contracts WHERE id = ? FOR UPDATE   │
│             (if contract_id is not null)                                    │
│          2. SELECT * FROM wp_negar_club_installments WHERE id = ? FOR UPDATE│
│             (if installment_id is not null)                                 │
│          3. SELECT * FROM wp_negar_club_payments WHERE id = ? FOR UPDATE    │
│                                                                             │
│ Step B3: Concurrency Guard:                                                 │
│          If payment.status == 'verified':                                   │
│            $wpdb->query('COMMIT'); return HTTP 200 OK (Already settled)     │
│                                                                             │
│ Step B4: Mathematical Invariant Checks:                                     │
│          • If gateway_currency == 'IRR': verified_amount % 10 === 0         │
│          • verified_amount_irt === payment.amount_internal_irt              │
│          (Mismatch ──► UPDATE status='disputed_amount'; COMMIT; HTTP 422)   │
│                                                                             │
│ Step B5: Atomic Ledger Mutations:                                           │
│          • UPDATE wp_negar_club_payments:                                   │
│              status = 'verified',                                           │
│              provider_reference = $verified_ref,                            │
│              verified_at = NOW();                                           │
│                                                                             │
│          • Context: scheduled_installment:                                  │
│              - UPDATE wp_negar_club_installments:                           │
│                  status = 'paid', paid_amount = total_amount,               │
│                  settled_payment_id = payment.id, settled_at = NOW();       │
│              - UPDATE wp_negar_club_contracts:                              │
│                  paid_principal = paid_principal + installment.principal,   │
│                  paid_charges = paid_charges + installment.charge_amount,   │
│                  remaining_principal_balance = financed_p - paid_principal, │
│                  remaining_charges_balance = total_c - paid_charges,        │
│                  total_outstanding_balance = remaining_p + remaining_c;     │
│              - IF total_outstanding_balance == 0:                           │
│                  UPDATE wp_negar_club_contracts SET status = 'completed';   │
│                                                                             │
│          • Context: initial_checkout:                                       │
│              - UPDATE wp_negar_club_contracts:                              │
│                  status = 'active', activated_at = NOW();                   │
│              - Link to WooCommerce Order: set order status to 'processing', │
│                record payment metadata, and call wc_reduce_stock_levels();  │
│                                                                             │
│ Step B6: Write Append-Only Audit Event:                                     │
│          INSERT INTO wp_negar_club_audit_log (event_action='payment_settled')│
│                                                                             │
│ Step B7: Commit Transaction ($wpdb->query('COMMIT')) and return HTTP 200.   │
└─────────────────────────────────────────────────────────────────────────────┘
```
[Step 7: Commit Transaction & Return HTTP 200 OK]
```

---

## 6. Edge Cases & Commercial Policies

1. **Partial Payments / Underpayments:**
   By default, the system rejects underpayments (`amount_irt < installment.total_amount`) as `disputed_amount`. Supporting partial installment settlement is governed by `CLUB-BD-013` and remains `BLOCKED BY EMPLOYER APPROVAL`.
2. **Overpayments:**
   Overpayments are flagged as `disputed_amount` for operator reconciliation; excess funds are never silently absorbed.
3. **Duplicate Callbacks (Replay):**
   Database unique key `uq_provider_tx` on `(gateway_provider, provider_tx_id)` guarantees that replayed callbacks hit the idempotency check and return HTTP 200 with zero state mutation.
