# Integration Contract: Payment Webhook & Settlement Contract

**Feature:** 013-customer-club  
**Component:** External Payment Provider Webhook & Settlement Gateway  
**Target Phase:** Phase 6 (Financial Engine & Installment Payments)  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

---

## 1. Security Architecture & Separation of Concerns

### 1.1 Nonce Boundary & Webhook Authentication
Standard WordPress nonces (`wp_verify_nonce()`) rely on authenticated browser cookies or user session tokens. External bank payment gateways (e.g., Shaparak, Zibal, ZarinPal, Saman, Mellat) dispatch server-to-server HTTP POST callbacks completely outside of user browser sessions.

To satisfy `CLUB-NFR-007` and `CLUB-AC-047`:
1. **Public Webhook Endpoint:** The callback endpoint (`/wp-json/negar-club/v1/payment-callback` or dedicated rewrite endpoint) is accessible without WordPress browser cookies.
2. **Provider Authentication:** The endpoint **strictly enforces** provider-specific authentication before processing any payload:
   - **Method A (Digital Signature / HMAC):** Validating the cryptographic signature in the callback header (e.g., `X-Provider-Signature: HMAC-SHA256(payload, secret_key)`).
   - **Method B (Authenticated Server-to-Server Verification):** Making an immediate outbound HTTPS inquiry from the local server to the provider's verified inquiry/verification endpoint using stored merchant credentials.
3. **No Unauthenticated Bypass:** A request lacking a valid provider signature or failing provider inquiry is rejected with HTTP 401/403 and discarded. **No public webhook is ever processed unauthenticated.**

---

## 2. Inbound Webhook Specification

### Endpoint Specification
- **Route:** `POST /wp-json/negar-club/v1/payment-callback`
- **Rate Limiting:** IP and endpoint throttled.
- **Payload Format:** JSON or `application/x-www-form-urlencoded`.

### 2.1 Inbound Payload Schema
```json
{
  "gateway_provider": "zibal",
  "provider_tx_id": "9876543210",
  "provider_reference": "REF-998877",
  "amount_irr": 33333330,
  "currency": "IRR",
  "status_code": 1,
  "idempotency_key": "PAY-INST-202611-1004-SEQ2",
  "signature": "e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855"
}
```

| Field | Type | Required | Description |
| :--- | :--- | :---: | :--- |
| `gateway_provider` | String | Yes | Provider identifier (`zibal`, `zarinpal`, etc.). |
| `provider_tx_id` | String | Yes | Unique transaction ID assigned by gateway (Shaparak RRN / Authority). |
| `provider_reference` | String | No | Bank card trace number / masked PAN. |
| `amount_irr` | Integer | Yes | Settled amount in Iranian Rial (`IRR`). |
| `currency` | String | Yes | Expected `IRR` (or `IRT` if provider natively operates in Toman). |
| `status_code` | Integer | Yes | Provider status code indicating successful bank debit. |
| `idempotency_key` | String | Yes | Internal unique settlement key generated at payment initiation. |
| `signature` | String | Yes | Provider HMAC-SHA256 signature over payload. |

---

## 3. Server Verification & Reconciliation Workflow

```
[Inbound Webhook Received]
          │
          ▼
[1. Verify Provider Signature / Token] ──(Invalid)──► [HTTP 401: REJECT + LOG]
          │ (Valid)
          ▼
[2. Lookup Pending Payment by Idempotency Key] ──(Not Found)──► [HTTP 404: REJECT]
          │ (Found)
          ▼
[3. Check Existing Settlement Status]
          │
          ├──(Already Verified / Settled)──► [HTTP 200: IDEMPOTENT NO-OP + AUDIT REPLAY]
          │
          ▼ (Status is 'initiated' or 'pending_verification')
[4. Outbound Server-to-Server Inquiry] ──(Gateway Rejects)──► [Update Payment 'failed' + HTTP 400]
          │ (Gateway Confirms Success)
          ▼
[5. Validate Currency & Amount]
    Expected IRT == Amount IRR / 10 ? ──(Mismatch)──► [CRITICAL FRAUD ALERT + HALT]
          │ (Exact Match)
          ▼
[6. Atomic Database Transaction (SELECT FOR UPDATE)]:
    • Insert or Update `wp_negar_club_payments` -> status = 'verified'
    • Update `wp_negar_club_installments` -> status = 'paid', settled_payment_id
    • Recalculate `wp_negar_club_contracts` remaining debt
    • Check if all installments paid -> contract status = 'completed'
    • Emit append-only event into `wp_negar_club_audit_log`
          │
          ▼
[7. Return HTTP 200 OK with Acknowledgment JSON]
```

---

## 4. Webhook Responses & Idempotency Rules

### 4.1 First Settlement Success (`200 OK`)
```json
{
  "success": true,
  "code": "PAYMENT_SETTLED",
  "data": {
    "provider_tx_id": "9876543210",
    "installment_id": 104,
    "contract_id": 12,
    "amount_irt": 3333333,
    "new_installment_status": "paid",
    "contract_remaining_installments": 4,
    "settled_at": "2026-11-18T21:10:00+03:30"
  }
}
```

### 4.2 Idempotent Replay Handling (`200 OK`)
If the gateway retries an already settled webhook with identical `provider_tx_id` and `idempotency_key`:
```json
{
  "success": true,
  "code": "ALREADY_SETTLED",
  "message": "تراکنش قبلاً با موفقیت تسویه گردیده است.",
  "data": {
    "provider_tx_id": "9876543210",
    "original_settled_at": "2026-11-18T21:10:00+03:30"
  }
}
```
*Effect:* Zero database mutation on agreement or installment; emits `audit:idempotent_replay` event to record duplicate callback receipt.

### 4.3 Amount or Currency Mismatch (`422 Unprocessable Entity`)
If the gateway reports 30,000,000 IRR when the installment requires 33,333,330 IRR:
```json
{
  "success": false,
  "code": "AMOUNT_MISMATCH",
  "message": "مبلغ تراکنش با بدهی قسط مطابقت ندارد. تسویه متوقف شد."
}
```
*Effect:* Payment flagged as `disputed_amount`; installment remains `due`; operator notified.

### 4.4 Signature Authentication Failure (`401 Unauthorized`)
```json
{
  "success": false,
  "code": "INVALID_SIGNATURE",
  "message": "امضای امنیتی ارائه‌دهنده درگاه نامعتبر است."
}
```
