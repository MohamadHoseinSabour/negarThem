# Integration Contract: Application Lifecycle API

**Feature:** 013-customer-club  
**Component:** Customer Application & Operator Review Workflow  
**Target Phase:** Phase 5 (Applications & Operator Workflow)  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

---

## 1. Overview & Security Boundaries

The **Application Lifecycle API** manages the submission, status tracking, operator review, and qualification of customer installment credit requests.

### Security Guarantees
1. **Authenticated Sessions:** All applicant endpoints require an active WordPress session (`is_user_logged_in()`). Guest requests are rejected with HTTP 401 and redirected to the existing OTP authentication modal.
2. **CSRF & Nonce Protection:** Every browser-initiated mutation requires an action-specific nonce (`wp_verify_nonce($nonce, 'negar_club_apply_action')` or WP REST cookie nonce).
3. **Anti-IDOR:** A customer can only access applications matching their own `get_current_user_id()`. Cross-customer access attempts return HTTP 403 with zero metadata disclosure.
4. **Scoped Operator Capability:** Operator review actions require the dedicated `negar_club_operator` capability (or `manage_options` for administrators). Operators without site administrator privileges cannot modify theme settings or install plugins.
5. **Audit Logging:** Every status change emits an append-only event into `wp_negar_club_audit_log`.

---

## 2. Customer Application Submission

### Endpoint Specification
- **Route:** `POST /wp-json/negar-club/v1/applications`
- **Capability:** Authenticated Customer (`read`)
- **Headers:** `X-WP-Nonce: <rest_nonce>`

### 2.1 Request Schema
```json
{
  "plan_id": "negar_plus",
  "requested_ceiling": 50000000,
  "contact_phone": "09123456789",
  "national_id": "0012345678",
  "applicant_notes": "درخواست خرید لپ‌تاپ اقساطی"
}
```

| Field | Type | Required | Constraints |
| :--- | :--- | :---: | :--- |
| `plan_id` | String | Yes | Must match an active plan (`negar_aghaz`, `negar_plus`, `negar_vizhe`). |
| `requested_ceiling` | Integer | Yes | Non-negative integer Toman; cannot exceed plan ceiling. |
| `contact_phone` | String | Yes | Persian mobile format (`09\d{9}`). |
| `national_id` | String | Yes | 10-digit national code; validated via official Luhn-based checksum algorithm; hashed before persistence (`national_id_hash`). |
| `applicant_notes` | String | No | Sanitized text up to 500 characters. |

---

### 2.2 Success Response (`201 Created`)
```json
{
  "success": true,
  "data": {
    "application_number": "APP-202611-8841",
    "status": "pending_review",
    "status_label": "در حال بررسی مدارک",
    "plan_id": "negar_plus",
    "plan_title": "نگار پلاس",
    "requested_ceiling": 50000000,
    "created_at": "2026-11-18T20:30:00+03:30",
    "message": "درخواست شما با موفقیت ثبت شد و در صف بررسی کارشناسان قرار گرفت."
  }
}
```

---

## 3. Customer Application Status Query (Anti-IDOR)

### Endpoint Specification
- **Route:** `GET /wp-json/negar-club/v1/applications/{application_number}`
- **Capability:** Authenticated Customer (`read`)
- **Ownership Guard:** Server verifies `application.user_id === get_current_user_id()`.

### 3.1 Success Response (`200 OK`)
```json
{
  "success": true,
  "data": {
    "application_number": "APP-202611-8841",
    "status": "approved",
    "status_label": "تأیید شده",
    "plan_id": "negar_plus",
    "approved_ceiling": 50000000,
    "reviewed_at": "2026-11-19T09:15:00+03:30",
    "expires_at": "2026-12-19T09:15:00+03:30",
    "operator_public_note": "اعتبار شما جهت خرید تا سقف ۵۰ میلیون تومان تأیید شد."
  }
}
```

### 3.2 IDOR Negative Test Response (`403 Forbidden`)
When User A requests `APP-202611-8841` belonging to User B:
```json
{
  "success": false,
  "code": "ACCESS_DENIED",
  "message": "شما مجاز به مشاهده این درخواست نمی‌باشید."
}
```

---

## 4. Operator Review & Transition API

### Endpoint Specification
- **Route:** `POST /wp-json/negar-club/v1/operator/review`
- **Capability:** `negar_club_operator` or `manage_options`
- **Headers:** `X-WP-Nonce: <rest_nonce>`

### 4.1 Request Schema
```json
{
  "application_number": "APP-202611-8841",
  "transition": "approve",
  "approved_ceiling": 50000000,
  "operator_notes": "مدارک هویتی و پرینت حساب بانکی تأیید شد.",
  "validity_days": 30
}
```

| Field | Type | Required | Constraints |
| :--- | :--- | :---: | :--- |
| `application_number` | String | Yes | Valid existing application identifier. |
| `transition` | String | Yes | `approve`, `reject`, `cancel`. |
| `approved_ceiling` | Integer | Conditional | Required if `transition == 'approve'`. Integer Toman. |
| `operator_notes` | String | Yes | Mandatory audit reasoning. |
| `validity_days` | Integer | No | Default `30` (governed by `CLUB-BD-010`). |

---

### 4.2 State Machine Validation Guards
- **Approve Transition:**
  - Precondition: Status must be `pending_review`.
  - Effect: Status becomes `approved`; sets `reviewed_by`, `reviewed_at`, `expires_at = NOW() + validity_days`.
  - Emits audit log: `application:approve`.
- **Reject Transition:**
  - Precondition: Status must be `pending_review`.
  - Effect: Status becomes `rejected`; sets `reviewed_by`, `reviewed_at`.
  - Emits audit log: `application:reject`.
- **Cancel Transition:**
  - Precondition: Status must be `pending_review` or `approved` (before contract activation).
  - Effect: Status becomes `cancelled`.

### 4.3 Operator Response Schema (`200 OK`)
```json
{
  "success": true,
  "data": {
    "application_number": "APP-202611-8841",
    "previous_status": "pending_review",
    "new_status": "approved",
    "approved_ceiling": 50000000,
    "reviewed_by": 42,
    "audit_event_id": 1052,
    "message": "وضعیت درخواست با موفقیت به تأیید شده تغییر یافت."
  }
}
```
