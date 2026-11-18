# Integration Contract: Payment Webhook & Settlement Gateway

**Feature:** 013-customer-club
**Component:** Provider-Independent Payment Webhook & Settlement Engine
**Target Phase:** Phase 6 (Financial Engine & Installment Payments)
**Phase:** Phase 2.1 — Architecture Corrections & Design Consistency
**Status:** `DRAFT — PHASE 2.1 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

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

## 2. Idempotency Correlation Architecture (Correction C4.2)

To reliably correlate callbacks without assuming gateways preserve arbitrary merchant metadata:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      IDEMPOTENCY CORRELATION SEQUENCE                       │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Payment Initiation:                                                      │
│    • Local record created in wp_negar_club_payments with:                   │
│      - status = 'initiated'                                                 │
│      - idempotency_key = 'PAY-INST-<agreement_id>-<seq>-<unique_token>'     │
│      - client_tracking_token = wp_generate_uuid4()                          │
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
│      (a) `token` query parameter, OR                                        │
│      (b) `provider_tx_id` (Authority / trackId) in POST body.               │
│                                                                             │
│ 4. Concurrency Guard:                                                       │
│    • If payment row status is ALREADY 'verified':                           │
│      Return HTTP 200 idempotent acknowledgment immediately; zero mutations. │
│    • If payment row status is 'initiated' or 'pending_verification':        │
│      Lock payment row (SELECT FOR UPDATE) and execute settlement sequence.  │
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
  "amount_irr": 33333330,
  "currency": "IRR",
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
Iranian bank gateways predominantly report in **Rial (`IRR`)**.

### Exact Integer Conversion Rule
$$\text{amount\_irr} \pmod{10} === 0$$
$$\text{amount\_irt} = \text{intdiv}(\text{amount\_irr}, 10)$$

### Validation Guard:
```php
if ( ( $amount_irr % 10 ) !== 0 ) {
    // Corrupted or fractional Rial amount detected — halt immediately
    $payment->update_status( 'disputed_amount', 'مبلغ ریالی دریافتی بر ۱۰ بخش‌پذیر نیست.' );
    return new WP_Error( 'INVALID_CURRENCY_CONVERSION', 'مبلغ نامعتبر است.', array( 'status' => 422 ) );
}
$amount_irt = intdiv( $amount_irr, 10 );
if ( $amount_irt !== $expected_installment_amount_irt ) {
    $payment->update_status( 'disputed_amount', 'مغایرت مبلغ پرداختی با مبلغ قسط.' );
    return new WP_Error( 'AMOUNT_MISMATCH', 'مبلغ پرداختی مطابقت ندارد.', array( 'status' => 422 ) );
}
```

---

## 5. Complete Atomic Settlement Sequence (Correction C4.5)

```
[Inbound Callback Received]
          │
          ▼
[Step 1: Locate Payment Row by Token or provider_tx_id]
          │ (Row not found) ──► HTTP 404
          ▼ (Found)
[Step 2: Begin Database Transaction & Row Lock]
          │ SQL: SELECT * FROM wp_negar_club_payments WHERE id = ? FOR UPDATE
          ▼
[Step 3: Check Idempotent State]
          │ (status == 'verified') ──► COMMIT + HTTP 200 (Already Settled, No-Op)
          ▼ (status == 'initiated' or 'pending_verification')
[Step 4: Execute Provider-Specific Authentication / Outbound Verify API]
          │ (Provider rejects / verify fails) ──►
          │   UPDATE status = 'failed'; COMMIT; HTTP 400
          ▼ (Provider confirms success with provider_reference)
[Step 5: Mathematical Invariant Checks]
          │ • amount_irr % 10 === 0
          │ • intdiv(amount_irr, 10) === installment.total_amount
          │ (Mismatch) ──►
          │   UPDATE status = 'disputed_amount'; COMMIT; HTTP 422 + Alert Operator
          ▼ (All invariants hold)
[Step 6: Atomic Settlement Mutations]
          │ • UPDATE wp_negar_club_payments:
          │     status = 'verified', verified_at = NOW(), provider_reference = ?
          │ • UPDATE wp_negar_club_installments:
          │     status = 'paid', paid_amount = total_amount,
          │     settled_payment_id = payment.id, settled_at = NOW()
          │ • UPDATE wp_negar_club_contracts:
          │     paid_principal = paid_principal + installment.principal_amount,
          │     paid_charges = paid_charges + installment.charge_amount,
          │     remaining_principal_balance = financed_principal - paid_principal,
          │     remaining_charges_balance = total_charges - paid_charges,
          │     total_outstanding_balance = remaining_principal_balance + remaining_charges_balance
          │ • IF total_outstanding_balance == 0:
          │     UPDATE wp_negar_club_contracts SET status = 'completed', completed_at = NOW()
          │ • INSERT INTO wp_negar_club_audit_log (append-only settlement event)
          ▼
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
