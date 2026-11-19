# Data Model & Schema Specification: NegarThem Customer Club

**Feature:** 013-customer-club
**Project:** Negar Shop WordPress Theme (`negarThem`)
**Date:** 2026-11-19
**Phase:** Phase 2.3 — Critical Financial Architecture Corrections (Minimal Scope)
**Author:** Senior WordPress & WooCommerce Architect Agent
**Status:** `DRAFT — PHASE 2.3 TECHNICAL DESIGN (AWAITING EXTERNAL REVIEW)`

---

## 1. Domain Entities & Storage Strategy

We evaluated whether to reuse standard WordPress/WooCommerce entities (posts, order meta, user meta) or to introduce dedicated custom tables.

### 1.1 Architectural Evaluation
1. **Customer Applications (`ClubApplication`):** Reusing `wp_posts` with a custom post type (CPT) was evaluated. However, credit applications contain structured identity hashes, operator review workflows, and scoring metrics that are fundamentally non-public and require strict schema enforcement, zero accidental public querying, and fast indexed lookups. Dedicated table `wp_negar_club_applications` is chosen.
2. **Credit Agreements (`CreditAgreement`):** Storing agreements inside WooCommerce order meta (`wp_wc_orders_meta`) was rejected because a customer's credit relationship may span multiple orders, refunds, and extended payment schedules, and must remain durable even if order data is archived. Dedicated table `wp_negar_club_contracts` is chosen.
3. **Installment Schedules (`InstallmentItem`):** An installment schedule creates 3 to 36 rows per agreement. Storing these in meta tables causes severe performance degradation, non-atomic querying, and high risk of race conditions during concurrent payments. Dedicated indexed table `wp_negar_club_installments` is essential.
4. **Payment Transactions (`PaymentTransaction`):** Storing payment callbacks in transient options or post meta violates financial durability and makes idempotency enforcement fragile. Dedicated table `wp_negar_club_payments` guarantees unique provider transaction constraints.
5. **Refunds Ledger (`PaymentRefund`):** A dedicated table `wp_negar_club_refunds` is required to accurately track partial and full refunds, link them to the original payment and WooCommerce order, and reconcile rolling agreement balances.
6. **Operational Audit Trail (`LedgerAuditEvent`):** An append-only audit trail `wp_negar_club_audit_log` is required for regulatory compliance, anti-tampering, and incident investigation.

---

## 2. Relational Schema & Entity Diagram

```
┌──────────────────────────────────┐
│   wp_negar_club_applications     │
├──────────────────────────────────┤
│ id (BIGINT PK)                   │◄────────────┐
│ application_number (VARCHAR, UQ) │             │
│ user_id (BIGINT FK -> wp_users)  │             │
│ plan_id (VARCHAR)                │             │
│ status (VARCHAR)                 │             │ (default: 'pending_review')
│ requested_ceiling (BIGINT)       │             │
│ approved_ceiling (BIGINT)        │             │
└────────────────┬─────────────────┘             │
                 │ 1:1 (on approval)             │
                 ▼                               │
┌──────────────────────────────────┐             │
│     wp_negar_club_contracts      │             │
├──────────────────────────────────┤             │
│ id (BIGINT PK)                   │             │
│ contract_number (VARCHAR, UQ)    │             │
│ application_id (BIGINT FK) ──────┼─────────────┘
│ user_id (BIGINT FK -> wp_users)  │
│ order_id (BIGINT FK, UQ) ────────┼─► (1:1 with wc_orders, prevents duplicate contracts)
│ plan_id (VARCHAR)                │
│ plan_snapshot (JSON)             │ (Frozen terms at execution)
│ purchase_amount (BIGINT)         │ (Total retail merchandise amount)
│ initial_payment (BIGINT)         │ (Cash collected at checkout)
│ financed_principal (BIGINT)      │ (Original principal: purchase - initial)
│ total_charges (BIGINT)           │ (Original markup/fees, default: 0)
│ total_customer_obligation (BIGINT│ (financed_principal + total_charges)
│ paid_principal (BIGINT)          │ (Cumulative gross settled principal cash)
│ paid_charges (BIGINT)            │ (Cumulative gross settled charges cash)
│ remaining_principal_balance (BIG)│ (financed_principal - net_paid_principal)
│ remaining_charges_balance (BIG)  │ (total_charges - net_paid_charges)
│ total_outstanding_balance (BIGINT│ (Unpaid debt: remaining_p + remaining_c)
│ installments_count (INT)         │
│ status (VARCHAR)                 │ (active, completed, cancelled, defaulted)
└────────────────┬─────────────────┘
                 │ 1:N
                 ▼
┌──────────────────────────────────┐             ┌──────────────────────────────────┐
│   wp_negar_club_installments     │             │      wp_negar_club_payments      │
├──────────────────────────────────┤             ├──────────────────────────────────┤
│ id (BIGINT PK)                   │             │ id (BIGINT PK)                   │
│ contract_id (BIGINT FK)          │             │ client_token (VARCHAR, UQ)       │
│ installment_sequence (INT)       │◄────────────┼─ installment_id (BIGINT FK, Null)│
│ due_date (DATE)                  │             │ contract_id (BIGINT FK, Null)    │
│ principal_amount (BIGINT)        │             │ application_id (BIGINT FK, Null) │
│ charge_amount (BIGINT)           │             │ order_id (BIGINT FK, Null)       │
│ total_amount (BIGINT)            │             │ user_id (BIGINT FK -> wp_users)  │
│ paid_amount (BIGINT)             │             │ amount_internal_irt (BIGINT)     │
│ status (VARCHAR)                 │             │ gateway_amount (BIGINT)          │
│ settled_payment_id (BIGINT FK) ──┼────────────►│ gateway_currency (VARCHAR)       │
└────────────────┬─────────────────┘             │ gateway_provider (VARCHAR)       │
                 │                               │ provider_tx_id (VARCHAR, Null)   │
                 ▼                               │ payment_context (VARCHAR)        │
┌──────────────────────────────────┐             │ idempotency_key (VARCHAR, UQ)    │
│     wp_negar_club_audit_log      │             │ status (VARCHAR)                 │
├──────────────────────────────────┤             └────────────────┬─────────────────┘
│ id (BIGINT PK)                   │                              │
│ entity_type (VARCHAR)            │                              ▼
│ entity_id (BIGINT)               │             ┌──────────────────────────────────┐
│ event_action (VARCHAR)           │             │      wp_negar_club_refunds       │
│ old_state (JSON)                 │             ├──────────────────────────────────┤
│ new_state (JSON)                 │             │ id (BIGINT PK)                   │
│ actor_id (BIGINT)                │             │ refund_number (VARCHAR, UQ)      │
│ actor_ip (VARCHAR)               │             │ contract_id (BIGINT FK)          │
│ created_at (DATETIME)            │             │ payment_id (BIGINT FK, Null)     │
└──────────────────────────────────┘             │ order_id (BIGINT FK)             │
                                                 │ installment_id (BIGINT FK, Null) │
                                                 │ refund_type (VARCHAR)            │ (cash_refund vs debt_cancellation)
                                                 │ amount_irt (BIGINT)              │
                                                 │ principal_refunded_irt (BIGINT)  │
                                                 │ charges_refunded_irt (BIGINT)    │
                                                 │ amount_irr (BIGINT, Null)        │
                                                 │ reason (VARCHAR)                 │
                                                 │ status (VARCHAR)                 │
                                                 │ gateway_refund_ref (VARCHAR)     │
                                                 │ idempotency_key (VARCHAR, UQ)    │
                                                 │ created_at (DATETIME)            │
                                                 │ settled_at (DATETIME)            │
                                                 └──────────────────────────────────┘
```

---

## 3. Detailed Table Specifications

*All table names use the dynamic WordPress prefix via `$wpdb->prefix` (denoted as `wp_` in examples).*

### 3.1 `wp_negar_club_applications`
Stores customer applications for installment club membership and plan access.

```sql
CREATE TABLE `wp_negar_club_applications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_number` VARCHAR(32) NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `plan_id` VARCHAR(32) NOT NULL,
  `status` VARCHAR(24) NOT NULL DEFAULT 'pending_review',
  `requested_ceiling` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `approved_ceiling` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `national_id_hash` VARCHAR(64) NULL,
  `income_bracket` VARCHAR(32) NULL,
  `contact_phone` VARCHAR(20) NOT NULL,
  `applicant_notes` TEXT NULL,
  `operator_notes` TEXT NULL,
  `reviewed_by` BIGINT UNSIGNED NULL,
  `reviewed_at` DATETIME NULL,
  `expires_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_application_number` (`application_number`),
  KEY `ix_user_status` (`user_id`, `status`),
  KEY `ix_status_created` (`status`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Canonical Statuses:** `pending_review` (sole canonical initial state), `approved`, `rejected`, `cancelled`, `expired`.
- **Monetary Units:** `requested_ceiling` and `approved_ceiling` are stored strictly as integer Toman (`BIGINT UNSIGNED`).
- **Privacy:** `national_id_hash` is a one-way cryptographic SHA-256 hash with site salt, preventing plaintext national code exposure while permitting duplicate detection.

---

### 3.2 `wp_negar_club_contracts`
Stores binding credit agreements created upon initial payment verification for an approved order. Exactly one contract is permitted per WooCommerce order.

```sql
CREATE TABLE `wp_negar_club_contracts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_number` VARCHAR(32) NOT NULL,
  `application_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `plan_id` VARCHAR(32) NOT NULL,
  `plan_title_snapshot` VARCHAR(128) NOT NULL,
  `plan_snapshot` JSON NOT NULL,
  `purchase_amount` BIGINT UNSIGNED NOT NULL,
  `initial_payment` BIGINT UNSIGNED NOT NULL,
  `financed_principal` BIGINT UNSIGNED NOT NULL,
  `total_charges` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `total_customer_obligation` BIGINT UNSIGNED NOT NULL,
  `paid_principal` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `paid_charges` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `remaining_principal_balance` BIGINT UNSIGNED NOT NULL,
  `remaining_charges_balance` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `total_outstanding_balance` BIGINT UNSIGNED NOT NULL,
  `installments_count` INT UNSIGNED NOT NULL,
  `status` VARCHAR(24) NOT NULL DEFAULT 'pending_prerequisite',
  `activated_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contract_number` (`contract_number`),
  UNIQUE KEY `uq_contract_order` (`order_id`),
  KEY `ix_user_contracts` (`user_id`, `status`),
  KEY `ix_application_id` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Immutability Invariant:** `purchase_amount`, `initial_payment`, `financed_principal`, `total_charges`, `total_customer_obligation`, `installments_count`, and `plan_snapshot` are frozen at agreement execution and never modified.
- **Single Contract Per Order Guard (Correction C2):** `UNIQUE KEY uq_contract_order (order_id)` enforces at the database engine level that no race condition or duplicate callback can ever create a second contract for the same WooCommerce order.
- **Initial Balancing:**
  - `financed_principal = purchase_amount - initial_payment`
  - `total_customer_obligation = financed_principal + total_charges`
  - `remaining_principal_balance = financed_principal`
  - `remaining_charges_balance = total_charges`
  - `total_outstanding_balance = total_customer_obligation`
- **Gross vs. Net Accounting Convention (Correction C4):**
  - `paid_principal` and `paid_charges` represent **Gross Cumulative Cash Settled** (total money received from customer, never decremented).
  - Cash refunds tracked in `wp_negar_club_refunds` reduce net cash received:
    $$\text{Net Cash Paid} = (\text{paid\_principal} - \text{principal\_refunded\_irt}) + (\text{paid\_charges} - \text{charges\_refunded\_irt})$$
  - Product returns / cancellations reduce debt directly by cancelling future unpaid installment rows:
    $$\text{Remaining Debt Balance} = \text{total\_customer\_obligation} - \text{Net Cash Paid} - \text{cancelled\_obligation}$$
- **Canonical Statuses:** `pending_prerequisite`, `active`, `completed`, `cancelled`, `defaulted`.

---

### 3.3 `wp_negar_club_installments`
Stores individual scheduled installment items linked to an active agreement.

```sql
CREATE TABLE `wp_negar_club_installments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` BIGINT UNSIGNED NOT NULL,
  `installment_sequence` INT UNSIGNED NOT NULL,
  `due_date` DATE NOT NULL,
  `principal_amount` BIGINT UNSIGNED NOT NULL,
  `charge_amount` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `total_amount` BIGINT UNSIGNED NOT NULL,
  `paid_amount` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `status` VARCHAR(24) NOT NULL DEFAULT 'scheduled',
  `settled_payment_id` BIGINT UNSIGNED NULL,
  `settled_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contract_sequence` (`contract_id`, `installment_sequence`),
  KEY `ix_due_status` (`due_date`, `status`),
  KEY `ix_contract_status` (`contract_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Per-Row Accounting Invariant:**
  `total_amount = principal_amount + charge_amount`
- **Schedule Reconciliation Invariant:**
  $$\sum \text{principal\_amount} \equiv \text{contract.financed\_principal}$$
  $$\sum \text{charge\_amount} \equiv \text{contract.total\_charges}$$
  $$\sum \text{total\_amount} \equiv \text{contract.total\_customer\_obligation}$$
- **Canonical Statuses:** `scheduled`, `due`, `paid`, `overdue`, `waived`, `cancelled`.

---

### 3.4 `wp_negar_club_payments`
Stores verified online transaction attempts and settlements across both initial checkout and installment contexts.

```sql
CREATE TABLE `wp_negar_club_payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_token` VARCHAR(64) NOT NULL,
  `contract_id` BIGINT UNSIGNED NULL,
  `application_id` BIGINT UNSIGNED NULL,
  `order_id` BIGINT UNSIGNED NULL,
  `installment_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `amount_internal_irt` BIGINT UNSIGNED NOT NULL,
  `gateway_amount` BIGINT UNSIGNED NOT NULL,
  `gateway_currency` VARCHAR(8) NOT NULL DEFAULT 'IRR',
  `gateway_provider` VARCHAR(32) NOT NULL,
  `provider_tx_id` VARCHAR(64) NULL,
  `provider_reference` VARCHAR(128) NULL,
  `idempotency_key` VARCHAR(64) NOT NULL,
  `payment_context` VARCHAR(24) NOT NULL DEFAULT 'scheduled_installment',
  `status` VARCHAR(24) NOT NULL DEFAULT 'initiated',
  `verified_at` DATETIME NULL,
  `raw_callback_payload` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_client_token` (`client_token`),
  UNIQUE KEY `uq_provider_tx` (`gateway_provider`, `provider_tx_id`),
  UNIQUE KEY `uq_idempotency` (`idempotency_key`),
  KEY `ix_contract_payments` (`contract_id`, `status`),
  KEY `ix_order_payments` (`order_id`, `status`),
  KEY `ix_installment_payments` (`installment_id`, `status`),
  KEY `ix_context_status` (`payment_context`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Nullable Keys at Initiation:**
  - `contract_id`: `NULL` during initial checkout payment initiation, because binding contracts are only activated/created upon verified payment. In that context, `order_id` and `application_id` provide initial association.
  - `provider_tx_id`: `NULL` at the moment a local payment attempt is created, before the gateway returns a tracking authority (e.g. Zibal `trackId`). In MySQL/InnoDB, `UNIQUE KEY (gateway_provider, provider_tx_id)` permits multiple `NULL` values safely.
- **Client Tracking Token:**
  - `client_token`: Cryptographically random UUIDv4 (`wp_generate_uuid4()`), passed in gateway redirect/callback parameters for reliable lookup.
- **Currency Representation:**
  - `amount_internal_irt`: Stored strictly as integer Iranian Toman (`IRT`), matching contract schedules.
  - `gateway_amount` & `gateway_currency`: Stores the amount and currency transacted with the external provider (`IRR` or `IRT`). Rial conversion is bypassed when the gateway operates natively in Toman.
- **Payment Contexts:**
  - `initial_checkout`: First down-payment at checkout; triggers order fulfillment and contract activation.
  - `scheduled_installment`: Monthly repayment settling a specific `installment_id`.
  - `early_settlement`: Settle remaining total debt under `CLUB-BD-013`.
- **Reconciled Payment Statuses:**
  - `initiated`: Payment attempt created, user redirected to gateway.
  - `pending_verification`: Inbound callback received or outbound inquiry in progress, awaiting server verification.
  - `verified`: Provider inquiry/signature verified, funds settled atomically.
  - `duplicate_settlement_detected`: Gateway confirmed payment, but target installment was already settled by a concurrent attempt; logged for operator review without double-crediting.
  - `verified_pending_contract`: Gateway confirmed payment, but local contract creation encountered a temporary failure; queued for safe automated retry.
  - `failed`: Transaction definitively failed at gateway or cancelled by user.
  - `cancelled`: Payment attempt timed out or cancelled prior to settlement.
  - `disputed_amount`: Transaction amount or currency mismatch detected.
  - `partially_refunded`: Partial refund processed against this payment.
  - `refunded`: Full refund processed against this payment.

---

### 3.5 `wp_negar_club_refunds` (Dedicated Refund Architecture)
Stores partial and full refund transactions linked to orders, contracts, installments, and payments with deterministic principal vs charges allocation and explicit operation type.

```sql
CREATE TABLE `wp_negar_club_refunds` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `refund_number` VARCHAR(32) NOT NULL,
  `contract_id` BIGINT UNSIGNED NOT NULL,
  `payment_id` BIGINT UNSIGNED NULL,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `installment_id` BIGINT UNSIGNED NULL,
  `refund_type` VARCHAR(24) NOT NULL DEFAULT 'cash_refund',
  `amount_irt` BIGINT UNSIGNED NOT NULL,
  `principal_refunded_irt` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `charges_refunded_irt` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `amount_irr` BIGINT UNSIGNED NULL,
  `reason` VARCHAR(255) NOT NULL,
  `status` VARCHAR(24) NOT NULL DEFAULT 'initiated',
  `gateway_refund_ref` VARCHAR(128) NULL,
  `idempotency_key` VARCHAR(64) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `settled_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_refund_number` (`refund_number`),
  UNIQUE KEY `uq_refund_idempotency` (`idempotency_key`),
  KEY `ix_contract_refunds` (`contract_id`, `status`),
  KEY `ix_payment_refunds` (`payment_id`),
  KEY `ix_order_refunds` (`order_id`),
  KEY `ix_installment_refunds` (`installment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Two Distinct Operations (`refund_type` — Correction C4):**
  - `cash_refund`: Cash sent back to customer for funds previously collected. Reduces net cash collected.
  - `debt_cancellation`: Contractual debt extinguished because merchandise was returned before payment. Unpaid future installments are marked `cancelled`. No cash is disbursed.
- **Double-Deduction Prevention Rule:**
  - A cash refund for an already paid installment never cancels future product obligations twice.
  - A product return debt cancellation never issues a cash payout for unpaid future installments.
- **Allocation Invariant:**
  `amount_irt = principal_refunded_irt + charges_refunded_irt`
- **Immutability of Original Terms:**
  Original contract terms (`purchase_amount`, `initial_payment`, `financed_principal`, `total_charges`, `total_customer_obligation`) are **strictly immutable** and are never decremented on refund.
- **Deterministic Post-Refund Balances:**
  $$\text{Net Paid Principal} = \text{contract.paid\_principal} - \text{principal\_refunded\_irt}$$
  $$\text{Net Paid Charges} = \text{contract.paid\_charges} - \text{charges\_refunded\_irt}$$
  $$\text{Remaining Principal Balance} = \text{contract.financed\_principal} - \text{Net Paid Principal} - \text{cancelled\_principal}$$
  $$\text{Total Outstanding Balance} = \text{contract.total\_customer\_obligation} - (\text{Net Paid Principal} + \text{Net Paid Charges}) - \text{cancelled\_debt}$$
- **Statuses:** `initiated`, `completed`, `failed`.
- **Commercial Limitation:** Allocation of single-item returns from multi-item orders requires an explicit merchant policy under `CLUB-BD-014` (`PROPOSED — REQUIRES EMPLOYER APPROVAL`).

---

### 3.6 `wp_negar_club_audit_log`
Append-only operational event log for financial security and compliance.

```sql
CREATE TABLE `wp_negar_club_audit_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` VARCHAR(32) NOT NULL,
  `entity_id` BIGINT UNSIGNED NOT NULL,
  `event_action` VARCHAR(48) NOT NULL,
  `old_state` JSON NULL,
  `new_state` JSON NULL,
  `actor_id` BIGINT UNSIGNED NOT NULL,
  `actor_role` VARCHAR(32) NOT NULL,
  `actor_ip` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NULL,
  `context_note` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ix_entity` (`entity_type`, `entity_id`),
  KEY `ix_actor` (`actor_id`, `created_at`),
  KEY `ix_action_time` (`event_action`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Audit vs. Ledger Boundary:** `wp_negar_club_audit_log` is strictly an operational tracking log. Authoritative balances are derived exclusively from `wp_negar_club_contracts`, `wp_negar_club_installments`, `wp_negar_club_payments`, and `wp_negar_club_refunds`.
- **Tamper Resistance:** Zero `UPDATE` or `DELETE` statements are ever executed against this table.

---

## 4. Reconciliation Integrity Verification

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       RECONCILIATION INTEGRITY CHECK                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Principal Schedule Check:                                                │
│    contract.financed_principal == SUM(installments.principal_amount)        │
│                                                                             │
│ 2. Charges Schedule Check:                                                  │
│    contract.total_charges      == SUM(installments.charge_amount)           │
│                                                                             │
│ 3. Outstanding Balance Check:                                               │
│    contract.total_outstanding_balance == SUM(installments.total_amount)     │
│       WHERE status IN ('scheduled', 'due', 'overdue')                       │
│                                                                             │
│ 4. Paid Balance Check:                                                      │
│    (contract.paid_principal + contract.paid_charges) ==                     │
│       SUM(installments.paid_amount) WHERE status = 'paid'                   │
│                                                                             │
│ 5. Settlement Completeness:                                                 │
│    IF contract.total_outstanding_balance == 0 THEN                          │
│       contract.status MUST BE 'completed'                                   │
└─────────────────────────────────────────────────────────────────────────────┘
```

The system executes this integrity check automatically upon any installment settlement or refund before committing the database transaction.
