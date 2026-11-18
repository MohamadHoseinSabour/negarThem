# Data Model & Schema Specification: NegarThem Customer Club

**Feature:** 013-customer-club  
**Project:** Negar Shop WordPress Theme (`negarThem`)  
**Date:** 2026-11-18  
**Author:** Senior WordPress & WooCommerce Architect Agent  
**Status:** `DRAFT — PHASE 2 TECHNICAL DESIGN`  

---

## 1. Domain Entities & Storage Strategy

We evaluated whether to reuse standard WordPress/WooCommerce entities (posts, order meta, user meta) or to introduce dedicated custom tables.

### 1.1 Architectural Evaluation
1. **Customer Applications (`ClubApplication`):** Reusing `wp_posts` with a custom post type (CPT) was evaluated. However, credit applications contain structured identity hashes, operator review workflows, and scoring metrics that are fundamentally non-public and require strict schema enforcement and indexing. Dedicated table `wp_negar_club_applications` is chosen.
2. **Credit Agreements (`CreditAgreement`):** Storing agreements inside WooCommerce order meta (`wp_wc_orders_meta`) was rejected because a customer's credit relationship may span multiple orders, refunds, and extended payment schedules, and must remain durable even if order data is archived. Dedicated table `wp_negar_club_contracts` is chosen.
3. **Installment Schedules (`InstallmentItem`):** An installment schedule creates 3 to 36 rows per agreement. Storing these in meta tables causes severe performance degradation, non-atomic querying, and high risk of race conditions during concurrent payments. Dedicated indexed table `wp_negar_club_installments` is essential.
4. **Payment Transactions (`PaymentTransaction`):** Storing payment callbacks in transient options or post meta violates financial durability and makes idempotency enforcement fragile. Dedicated table `wp_negar_club_payments` guarantees unique provider transaction constraints.
5. **Operational Audit Trail (`LedgerAuditEvent`):** An append-only audit trail `wp_negar_club_audit_log` is required for regulatory compliance, anti-tampering, and incident investigation.

---

## 2. Relational Schema & Entity Diagram

```
┌──────────────────────────────┐
│  wp_negar_club_applications  │
├──────────────────────────────┤
│ id (PK)                      │◄───────────┐
│ user_id (FK -> wp_users)     │            │
│ plan_id (VARCHAR)            │            │
│ status (VARCHAR)             │            │
│ requested_ceiling (BIGINT)   │            │
└──────────────┬───────────────┘            │
               │ 1:1 (on approval)          │
               ▼                            │
┌──────────────────────────────┐            │
│   wp_negar_club_contracts    │            │
├──────────────────────────────┤            │
│ id (PK)                      │            │
│ contract_number (VARCHAR, UQ)│            │
│ application_id (FK) ─────────┼────────────┘
│ user_id (FK -> wp_users)     │
│ order_id (FK -> wc_orders)   │
│ plan_id (VARCHAR)            │
│ plan_snapshot (JSON/TEXT)    │
│ purchase_amount (BIGINT)     │
│ initial_payment (BIGINT)     │
│ remaining_principal (BIGINT) │
│ total_disclosed_charges (BIG)│
│ installments_count (INT)     │
│ status (VARCHAR)             │
└──────────────┬───────────────┘
               │ 1:N
               ▼
┌──────────────────────────────┐            ┌──────────────────────────────┐
│  wp_negar_club_installments  │            │    wp_negar_club_payments    │
├──────────────────────────────┤            ├──────────────────────────────┤
│ id (PK)                      │            │ id (PK)                      │
│ contract_id (FK)             │            │ contract_id (FK)             │
│ installment_sequence (INT)   │◄───────────┼─ installment_id (FK, Nullable│
│ due_date (DATE)              │            │ user_id (FK -> wp_users)     │
│ principal_amount (BIGINT)    │            │ amount_irt (BIGINT)          │
│ charge_amount (BIGINT)       │            │ amount_irr (BIGINT)          │
│ total_amount (BIGINT)        │            │ gateway_provider (VARCHAR)   │
│ paid_amount (BIGINT)         │            │ provider_tx_id (VARCHAR, UQ) │
│ status (VARCHAR)             │            │ idempotency_key (VARCHAR, UQ)│
│ settled_payment_id (FK) ─────┼───────────►│ status (VARCHAR)             │
└──────────────────────────────┘            └──────────────────────────────┘
               ▲
               │
┌──────────────┴───────────────┐
│   wp_negar_club_audit_log    │
├──────────────────────────────┤
│ id (BIGINT PK)               │
│ entity_type (VARCHAR)        │ (e.g., 'application', 'contract', 'installment')
│ entity_id (BIGINT)           │
│ event_action (VARCHAR)       │ (e.g., 'status_transition', 'settlement', 'rejection')
│ old_state (JSON/TEXT)        │
│ new_state (JSON/TEXT)        │
│ actor_id (BIGINT)            │
│ actor_ip (VARCHAR)           │
│ created_at (DATETIME)        │
└──────────────────────────────┘
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
  `status` VARCHAR(24) NOT NULL DEFAULT 'submitted',
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

- **Monetary Units:** `requested_ceiling` and `approved_ceiling` are stored strictly as integer Toman (`BIGINT UNSIGNED`).
- **Statuses:** `submitted`, `pending_review`, `approved`, `rejected`, `cancelled`, `expired`.
- **Privacy:** `national_id_hash` is a one-way cryptographic SHA-256 hash with site salt, preventing plaintext national code exposure while permitting duplicate detection.

---

### 3.2 `wp_negar_club_contracts`
Stores binding credit agreements created upon initial payment verification for an approved order.

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
  `remaining_principal` BIGINT UNSIGNED NOT NULL,
  `total_disclosed_charges` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `installments_count` INT UNSIGNED NOT NULL,
  `status` VARCHAR(24) NOT NULL DEFAULT 'pending_prerequisite',
  `activated_at` DATETIME NULL,
  `completed_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_contract_number` (`contract_number`),
  KEY `ix_user_contracts` (`user_id`, `status`),
  KEY `ix_order_id` (`order_id`),
  KEY `ix_application_id` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Immutability Invariant:** `plan_snapshot` stores the exact JSON representation of plan terms (ceiling, initial payment percentage/amount, charges, duration) at the moment of agreement execution. Subsequent changes to theme settings **never** alter this snapshot.
- **Arithmetic Invariant:**
  `purchase_amount = initial_payment + remaining_principal`
- **Statuses:** `pending_prerequisite`, `active`, `completed`, `cancelled`, `defaulted`.

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
  $$\sum \text{principal\_amount} \equiv \text{contract.remaining\_principal}$$
  $$\sum \text{total\_amount} \equiv \text{contract.remaining\_principal} + \text{contract.total\_disclosed\_charges}$$
- **Statuses:** `scheduled`, `due`, `paid`, `overdue`, `waived`, `cancelled`.

---

### 3.4 `wp_negar_club_payments`
Stores verified online transaction attempts and settlements.

```sql
CREATE TABLE `wp_negar_club_payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` BIGINT UNSIGNED NOT NULL,
  `installment_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `amount_irt` BIGINT UNSIGNED NOT NULL,
  `amount_irr` BIGINT UNSIGNED NOT NULL,
  `gateway_provider` VARCHAR(32) NOT NULL,
  `provider_tx_id` VARCHAR(64) NOT NULL,
  `provider_reference` VARCHAR(128) NULL,
  `idempotency_key` VARCHAR(64) NOT NULL,
  `status` VARCHAR(24) NOT NULL DEFAULT 'initiated',
  `verified_at` DATETIME NULL,
  `raw_callback_payload` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_provider_tx` (`gateway_provider`, `provider_tx_id`),
  UNIQUE KEY `uq_idempotency` (`idempotency_key`),
  KEY `ix_contract_payments` (`contract_id`, `status`),
  KEY `ix_installment_payments` (`installment_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

- **Idempotency Invariant:** `uq_provider_tx` and `uq_idempotency` guarantee that a duplicate callback or replayed webhook is rejected at the database level with zero secondary credit.
- **Statuses:** `initiated`, `pending_verification`, `verified`, `failed`, `cancelled`, `refunded`.

---

### 3.5 `wp_negar_club_audit_log`
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

- **Tamper Resistance:** This table contains no `updated_at` column and application code must never issue `UPDATE` or `DELETE` queries against it.
- **Redaction Rule:** Passwords, payment card numbers, gateway API keys, and OTP codes must never be included in `old_state` or `new_state`.

---

## 4. Accounting Boundary & Invariant Verification

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       RECONCILIATION INTEGRITY CHECK                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ 1. Remaining Debt Balance Check:                                            │
│    agreement.remaining_debt == SUM(installments.total_amount)               │
│                                WHERE status IN ('scheduled','due','overdue')│
│                                                                             │
│ 2. Paid Balance Check:                                                      │
│    agreement.paid_debt      == SUM(installments.paid_amount)                │
│                                WHERE status = 'paid'                        │
│                                                                             │
│ 3. Settlement Completeness:                                                 │
│    IF COUNT(installments WHERE status != 'paid') == 0                       │
│    THEN agreement.status MUST BE 'completed'                                │
└─────────────────────────────────────────────────────────────────────────────┘
```

The system executes this integrity check automatically upon any installment settlement before committing the transaction. Any discrepancy triggers an immediate administrative alert and halts automatic settlement until reviewed by an operator.
