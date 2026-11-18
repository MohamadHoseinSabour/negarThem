# Feature Specification: NegarThem Advanced Customer Club (باشگاه مشتریان نگار)

**Feature Directory**: `specs/013-customer-club`
**Feature Branch**: `feat/013-customer-club`
**Feature Code**: `CLUB-013`
**Created**: 2026-11-18
**Status**: `Draft` *(Remains in Draft until unresolved commercial, financial, and legal decisions in `business-rules.md` receive explicit employer approval)*
**Approved Git Baseline**: `eda9740a9943ab54c2eeb8161498b6556e1533d6` *(superseding Phase 0 baseline `f8fadc4465803f148ce261984ff10db00106893b` after verified baseline reconciliation)*
**Input**: Master Prompt — NegarThem Advanced Customer Club, Phase 0 Audit Report, and Phase 1 Specification & Business Rules Directive.

---

## 1. Product Purpose & Scope

### 1.1 Purpose
Provide an integrated, Persian RTL-first **Negar Customer Club («باشگاه مشتریان نگار»)** experience for the NegarThem WooCommerce storefront (home electrical appliances catalog, `IRT` integer currency, HPOS enabled). The system introduces a controlled three-tier installment-purchasing program that:

1. Enables visitors to discover, compare, and preview installment options transparently without misleading financial claims.
2. Enables administrators to configure presentation content, FAQs, public visibility, and three fixed-identity plan tiers safely from the existing NegarThem settings interface.
3. Enables authenticated customers to apply for plans, track real application statuses, view approved credit agreements, and monitor actual installment schedules and payment records within their WooCommerce account.
4. Enforces strict separation between **public presentation / preview**, **application review**, **checkout / initial payment**, **binding credit agreement**, and **actual installment settlement**, ensuring no incomplete or unapproved financial engine is activated prematurely.

### 1.2 Provisional Three-Tier Commercial Model

> **CRITICAL NOTICE:** All plan amounts, ceilings, durations, and badges below are **provisional defaults** proposed for initial modeling and administrative configuration. They are **NOT** employer-approved financial policies. No plan implies automatic credit approval, zero-interest guarantees, guarantor-free eligibility, or guaranteed purchasing rights.

| Internal Plan ID | Display Name (FA / EN) | Proposed Initial Payment | Proposed Purchase Ceiling | Proposed Remaining Term | Default Featured | Provisional Tagline |
| :--- | :--- | ---: | ---: | ---: | :---: | :--- |
| `negar_aghaz` | نگار آغاز (`Negar Aghaz`) | `3,000,000` IRT | `6,000,000` IRT | `3` months | No | شروعی آسان برای خرید اقساطی لوازم خانگی |
| `negar_plus` | نگار پلاس (`Negar Plus`) | `5,000,000` IRT | `10,000,000` IRT | `6` months | **Yes** («انتخاب محبوب») | تعادل مناسب بین پیش‌پرداخت و اقساط ماهانه |
| `negar_vizhe` | نگار ویژه (`Negar Vizhe`) | `10,000,000` IRT | `20,000,000` IRT | `6` months | No | قدرت انتخاب بیشتر برای خریدهای بزرگ‌تر |

- **Accounting Principle (Confirmed Intent):** The initial payment is intended to count toward the merchandise purchase price and MUST NOT be treated as an undisclosed separate membership fee. Its collection timing and accounting mechanics remain subject to business approval (`CLUB-BD-001`, `CLUB-BD-002`).

---

## 2. User Scenarios & Testing *(mandatory)*

User stories are prioritized (`P1` = foundational MVP/safety, `P2` = core operational journey, `P3` = live payment execution) and designed to be independently testable across five actors: **Guest Visitor**, **Authenticated Customer**, **Administrator**, **Financial/Store Operator**, and **System**.

---

### A. Guest Visitor

#### User Story `CLUB-US-01` — Discover Customer Club & Understand Installment Model (Priority: P1)
As a **Guest Visitor**, I want to visit the public Customer Club page («باشگاه مشتریان نگار») and understand how installment purchasing works so that I can decide whether the club suits my appliance purchase without encountering deceptive promises.

- **Business Value:** Builds buyer trust and drives qualified installment inquiries while preventing legal/reputational exposure from unverified credit claims.
- **Preconditions:** Public Customer Club experience is enabled in theme settings; visitor is not logged in.
- **Independent Test:** Load the public Customer Club page as an unauthenticated visitor across 375px, 768px, 1024px, and 1440px viewports and inspect all informational sections, RTL layout, and disclosures.
- **Main Success Scenario:**
  1. Visitor opens the Customer Club page from storefront navigation or direct URL.
  2. Page displays breadcrumb, hero section with primary CTA («مشاهده طرح‌ها») and secondary CTA («محاسبه اقساط»), benefits section, three plan cards, comparison table, calculator, how-it-works timeline, FAQs, and final CTA.
  3. Explanatory text clearly states that initial payment applies toward product purchase price and that eligibility/approval is subject to store terms and review.
- **Alternative & Failure Scenarios:**
  - *Club Disabled:* If the administrator disables the public club experience, visitor sees the configured disabled-status message and safe navigation links rather than broken sections or blank pages.
  - *All Plans Disabled:* If the page is active but zero plans are enabled, an honest empty state is shown instead of broken cards or fabricated sample tiers.
- **Acceptance Scenarios:**
  1. **Given** the public Customer Club is enabled, **When** a guest visits the page, **Then** the hero, benefits, timeline, FAQs, and disclosures render in Persian RTL with zero horizontal overflow and no unauthorized zero-interest/no-guarantor/instant-approval claims.
  2. **Given** the public Customer Club is disabled by the administrator, **When** a guest visits the page, **Then** the configured unavailable message is displayed clearly and no plan application action is active.

---

#### User Story `CLUB-US-02` — Compare the Three Provisional Plans & Eligibility Conditions (Priority: P1)
As a **Guest Visitor**, I want to inspect and compare the three installment plans side-by-side so that I can identify which tier fits my budget and target purchase ceiling.

- **Business Value:** Reduces pre-sale support questions and improves plan selection accuracy.
- **Preconditions:** At least one plan is enabled; default configuration provides three plans (`negar_aghaz`, `negar_plus`, `negar_vizhe`).
- **Independent Test:** Inspect the plan cards and comparison table as a guest and verify every displayed figure matches stored plan configuration and integer `IRT` formatting.
- **Main Success Scenario:**
  1. Visitor views the three plan cards ordered by configured display order, with the featured tier visually emphasized using theme design tokens.
  2. Each card shows display name, short title, initial payment (`IRT`), purchase ceiling (`IRT`), repayment duration (months), estimated monthly installment preview, editable feature list, badge (when set), supplemental terms, and plan CTA.
  3. Visitor scrolls to the comparison table and compares attributes cleanly on both desktop and mobile (accessible responsive layout without clipping).
- **Alternative & Failure Scenarios:**
  - *One or Two Plans Disabled:* Only enabled plans appear in the cards and comparison table; layout adapts cleanly without orphan columns.
  - *Missing Optional Fields:* Optional badge or supplemental notes are cleanly omitted when intentionally empty.
- **Acceptance Scenarios:**
  1. **Given** all three plans are enabled with default provisional values, **When** a guest views the plans and comparison sections, **Then** all three plans render in configured order with accurate integer `IRT` formatting, clear featured distinction on `negar_plus`, and complete eligibility notes.
  2. **Given** one plan is disabled by the administrator, **When** a guest reloads the page, **Then** only the two enabled plans appear in both cards and comparison table with intact layout geometry.

---

#### User Story `CLUB-US-03` — Preview Installments Using the Interactive Calculator (Priority: P1)
As a **Guest Visitor**, I want to select a plan and enter an intended purchase amount in an interactive installment calculator so that I can see an accurate preview of initial payment, remaining principal, monthly installments, and total payable amount.

- **Business Value:** Clarifies cash-flow expectations before registration and prevents misunderstanding of how initial payment offsets purchase price.
- **Preconditions:** Public Customer Club is enabled and at least one active plan exists.
- **Independent Test:** Exercise the calculator with valid amounts, boundary amounts (minimum, ceiling, below initial payment, above ceiling), non-divisible amounts, and malformed inputs; compare output against server calculation rules.
- **Main Success Scenario:**
  1. Visitor chooses one of the enabled plans and enters a desired purchase amount in Toman (`IRT`).
  2. Calculator displays: selected plan, desired purchase amount, applicable initial payment, remaining principal (`remaining_principal`), installment count, monthly installment breakdown (including final-installment rounding adjustment if applicable), total payable amount, and plan conditions/limits.
  3. Calculator displays a clear notice that results are an estimate/preview and not a binding credit contract.
- **Alternative & Failure Scenarios:**
  - *Amount Exceeds Plan Ceiling:* Calculator shows a clear Persian validation/constraint message explaining the ceiling limit and any approved overflow rule (or disallowance).
  - *Amount Below Minimum or Below Initial Payment:* Calculator rejects or explains why installment financing does not apply (`remaining_principal = 0` or below minimum threshold) without producing negative installments.
  - *Invalid/Non-Numeric Input:* Persian/Arabic numerals are normalized; negative or malformed inputs produce accessible inline error states without `NaN` or `Infinity`.
- **Acceptance Scenarios:**
  1. **Given** `negar_plus` (initial `5,000,000` IRT, ceiling `10,000,000` IRT, `6` months) is selected and the visitor enters `10,000,000` IRT, **When** the calculator computes the preview, **Then** remaining principal is `5,000,000` IRT, the sum of all `6` installments equals exactly `5,000,000` IRT, total payable equals `10,000,000` IRT (in the provisional no-fee model), and a non-binding preview disclaimer is visible.
  2. **Given** the visitor enters an amount above the selected plan ceiling or negative/invalid text, **When** calculation is triggered, **Then** a clear Persian validation message is announced via accessible live region and no misleading installment figure is displayed.

---

#### User Story `CLUB-US-04` — Select Plan & Route to Authentication or Unavailable State (Priority: P2)
As a **Guest Visitor**, I want selecting a plan CTA to guide me smoothly to the storefront's existing authentication flow (or inform me clearly if applications are currently closed) so that I do not hit a dead end or duplicate registration form.

- **Business Value:** Preserves the single unified customer identity model (existing phone OTP / WooCommerce account) while respecting administrative activation switches.
- **Preconditions:** Visitor clicks a plan CTA or Final CTA while logged out.
- **Independent Test:** Click a plan CTA as a guest under both "applications enabled" and "applications disabled" configurations and verify routing/messaging.
- **Main Success Scenario:**
  1. Applications are enabled; guest clicks CTA for a chosen plan (e.g., `negar_plus`).
  2. System prompts authentication via the existing storefront login flow (OTP modal or WooCommerce My Account entry) preserving the selected plan intent for return after authentication.
  3. After verified authentication, user returns to the club application step with their selected plan context intact.
- **Alternative & Failure Scenarios:**
  - *Applications Disabled / Financial Operations Disabled:* Plan CTA displays a disabled or informational state with the administrator's configured unavailability message (e.g., coming soon / contact support) rather than allowing fake submissions.
  - *SMS/OTP Provider Unconfigured:* Existing honest Persian SMS-unavailable error from the storefront OTP module is surfaced without exposing OTP codes or falling back to fake login.
- **Acceptance Scenarios:**
  1. **Given** applications are enabled and the user is a guest, **When** the guest selects an active plan CTA, **Then** the system invokes the existing authentication mechanism (never a parallel registration system) and preserves safe internal return context.
  2. **Given** application submission is disabled in settings, **When** a guest views or clicks plan CTAs, **Then** submission is prevented and the configured Persian disabled-state notice is shown.

---

### B. Authenticated Customer

#### User Story `CLUB-US-05` — Submit a Plan Application When Enabled (Priority: P2)
As an **Authenticated Customer**, I want to submit a formal application for an enabled Customer Club plan after reviewing and explicitly accepting its terms so that the store can evaluate my eligibility.

- **Business Value:** Converts qualified buyers into traceable, auditable applications without granting unverified automatic credit.
- **Preconditions:** User is logged in with a customer account; public club and application intake are enabled; selected plan is active.
- **Independent Test:** Submit an application as a logged-in customer with valid and invalid payloads (missing consent, disabled plan, duplicate open application) and inspect persisted application state.
- **Main Success Scenario:**
  1. Customer selects an enabled plan, reviews the explicit plan summary and legal/terms disclosure, checks explicit terms acceptance, and submits the application.
  2. Server validates authentication, CSRF nonce, plan active status, business eligibility rules, and server-side plan terms.
  3. System records a persisted application with canonical initial status `pending_review`, captures a snapshot of the requested plan parameters and terms version, emits an audit event, and displays a truthful confirmation stating the request is awaiting review (not approved credit).
- **Alternative & Failure Scenarios:**
  - *Terms Not Accepted:* Server rejects submission with a clear Persian validation error.
  - *Plan Disabled Mid-Session:* Server rejects submission with an honest message explaining the selected plan is no longer available.
  - *Privileged Account / Unauthenticated Request:* Unauthorized requests fail with appropriate HTTP status and zero state change.
  - *Duplicate Active Application:* Handled strictly according to business decision `CLUB-BD-020`.
- **Acceptance Scenarios:**
  1. **Given** an authenticated customer accepts disclosed terms for an active plan while intake is enabled, **When** the application is submitted, **Then** a persisted application record is created in `pending_review` status with a server-validated plan snapshot and no automatic credit or WooCommerce order is fabricated.
  2. **Given** a customer submits without accepting terms or selects a disabled plan ID, **When** the server validates the request, **Then** submission is rejected with an honest Persian error and zero records are created.

---

#### User Story `CLUB-US-06` — Track Real Application Status & Outcomes (Priority: P2)
As an **Authenticated Customer**, I want to see the real, up-to-date status of my Customer Club applications—including pending, approved, rejected, expired, or cancelled states—so that I always know what action (if any) is required next.

- **Business Value:** Eliminates status ambiguity and ensures honest communication of review outcomes.
- **Preconditions:** User is logged in; Customer Club account section is available.
- **Independent Test:** Query/view the customer account section across each application lifecycle state (`pending_review`, `approved`, `rejected`, `expired`, `cancelled`) and verify status display and ownership boundaries.
- **Main Success Scenario:**
  1. Customer opens «باشگاه مشتریان من» inside their WooCommerce My Account area.
  2. System displays only applications belonging to the authenticated customer, showing reference code, requested plan name, submission date, current status badge, and allowed next step.
  3. If an application is `rejected` or `expired`, customer sees an approved customer-safe reason (if permitted by disclosure policy) and clear guidance on re-application or support contact.
- **Alternative & Failure Scenarios:**
  - *No Applications:* Displays a helpful empty state linking to the public Customer Club page.
  - *Attempt to Access Another Customer's Application ID:* Server denies access (HTTP 403/404) without disclosing existence of the record.
- **Acceptance Scenarios:**
  1. **Given** an authenticated customer has applications in `pending_review`, `rejected`, or `expired` states, **When** they open «باشگاه مشتریان من», **Then** each real application renders with accurate status, timestamp, and policy-compliant explanation, and never exposes internal operator notes.
  2. **Given** Customer A requests details for an application owned by Customer B, **When** the server processes the request, **Then** access is denied and zero data belonging to Customer B is returned.

---

#### User Story `CLUB-US-07` — View Approved Agreements, Installment Schedule & Payment History (Priority: P2)
As an **Authenticated Customer**, I want to inspect my active and historical credit agreements, remaining balance, paid and unpaid installments, exact due dates, and genuine payment history in my account so that I can manage my financial obligations accurately.

- **Business Value:** Provides transparent post-purchase financial visibility and reduces billing disputes.
- **Preconditions:** Customer is logged in and has at least one real credit agreement (or zero agreements for empty-state verification).
- **Independent Test:** Render the account dashboard with zero agreements, one active agreement with mixed paid/unpaid installments, and one completed/cancelled agreement; verify exact arithmetic reconciliation and ownership isolation.
- **Main Success Scenario:**
  1. Customer navigates to «باشگاه مشتریان من» in `/my-account/`.
  2. Account overview displays membership/application status, active agreements, total remaining obligation, count of paid installments, count of remaining installments, next genuine due date, and linked WooCommerce order reference.
  3. Opening an agreement shows the immutable terms snapshot accepted at creation time, the full installment schedule (installment number, due date, integer `IRT` amount, real payment status), and genuine completed payment receipts/references.
- **Alternative & Failure Scenarios:**
  - *Plan Edited by Admin Later:* Customer's historical agreement continues showing the exact snapshot terms and installment amounts frozen at contract creation.
  - *Customer Has No Agreements:* Honest empty state is shown; no placeholder balances, zero-date due dates, or synthetic installments appear.
- **Acceptance Scenarios:**
  1. **Given** a customer has an active agreement whose source plan was later modified by an admin, **When** the customer views their agreement, **Then** all principal, installment counts, installment amounts, and terms reflect the immutable contract snapshot rather than the newer plan settings.
  2. **Given** a customer has no agreements, **When** they open «باشگاه مشتریان من», **Then** an honest empty state renders with zero invented obligations or fake due dates.

---

#### User Story `CLUB-US-08` — Execute Payments Only When Verified Payment Integration Is Active (Priority: P3)
As an **Authenticated Customer**, I want to pay an initial payment or an outstanding installment only through a genuinely enabled, verified payment mechanism so that my money is never lost, duplicated, or falsely confirmed.

- **Business Value:** Prevents financial loss, double charging, and broken checkout/repayment flows.
- **Preconditions:** Financial operations toggle is enabled; an approved, configured payment provider is active (`CLUB-BD-015`); customer owns a payable obligation (checkout initial payment or unpaid installment).
- **Independent Test:** Attempt payment initiation when gateway is disabled (must block cleanly), when gateway succeeds once, when callback replays twice (idempotent), and when gateway fails/cancels.
- **Main Success Scenario:**
  1. Customer initiates payment for an eligible payable obligation.
  2. Server recalculates and verifies the exact payable integer `IRT` amount, generates a unique idempotency/payment-attempt reference, and redirects to the verified payment provider.
  3. Upon verified provider callback, server confirms transaction authenticity with the provider, marks the exact obligation paid once, updates remaining balance, records an audit entry, and shows a truthful payment receipt.
- **Alternative & Failure Scenarios:**
  - *No Verified Payment Provider Enabled:* Payment action is disabled/hidden with an honest Persian notice; no simulated success state is allowed in production.
  - *Failed or Cancelled Gateway Payment:* Obligation remains unpaid; customer sees an honest failure notice and can safely retry without creating duplicate agreements.
  - *Duplicate/Delayed Callback or Double Click:* Server enforces idempotency and concurrency locks so the obligation is settled at most once.
- **Acceptance Scenarios:**
  1. **Given** no verified payment provider is enabled or financial operations are disabled, **When** a customer views an unpaid obligation, **Then** live payment initiation is blocked and an honest unavailability message is displayed.
  2. **Given** a verified payment callback is delivered multiple times for the same transaction reference, **When** the server processes each callback, **Then** the installment or initial payment is credited exactly once and subsequent callbacks are safely ignored and logged as idempotent replays.

---

### C. Administrator

#### User Story `CLUB-US-09` — Manage General Club Settings, Page Copy, Benefits, Timeline & FAQs (Priority: P1)
As a **Store Administrator**, I want a dedicated «باشگاه مشتریان» tab inside the existing NegarThem settings screen to manage all public page content, section toggles, disabled-state notices, and legal disclosures so that I can keep messaging accurate without editing theme code.

- **Business Value:** Centralizes storefront content administration inside the existing `نگارتم` settings workflow without creating a fragmented admin UI.
- **Preconditions:** User has `manage_options` capability and accesses the NegarThem settings page.
- **Independent Test:** Submit valid and invalid changes in the «باشگاه مشتریان» tab, verify persistence across reloads, confirm other theme settings tabs remain untouched, and verify unauthorized users are rejected.
- **Main Success Scenario:**
  1. Administrator opens `نگارتم → باشگاه مشتریان` (`tab=customer_club`).
  2. Administrator edits general settings: public club enable/disable toggle, page title & description, CTA labels & links, benefits items, how-it-works steps, FAQ items, per-section visibility toggles, application intake toggle, disabled-state messages, and legal/informational notices.
  3. Saving validates nonce and capability, sanitizes every field, persists to dedicated non-autoloaded configuration storage, and preserves all other theme settings groups intact.
- **Alternative & Failure Scenarios:**
  - *Unauthorized User:* Non-admin user receives HTTP 403 / WordPress permission denial and zero option changes.
  - *Unsafe URLs or Script Payloads:* `javascript:`/`data:` URLs and raw `<script>` tags are rejected/sanitized safely.
- **Acceptance Scenarios:**
  1. **Given** an authenticated administrator saves general Customer Club settings, **When** the admin page and public page reload, **Then** all updated values render safely escaped and all other NegarThem tabs (`general`, `colors`, `typography`, `texts`, `layout`, `products`, `banners`, `stories`, `brands`, `sms`, `backup`) remain completely unchanged.
  2. **Given** a non-administrator attempts to access or submit Customer Club settings, **When** the request reaches WordPress, **Then** access is denied and stored settings remain unaltered.

---

#### User Story `CLUB-US-10` — Configure Three Fixed-Identity Plans with Backend Validation (Priority: P1)
As a **Store Administrator**, I want to edit all three installment plan records independently with strict backend validation so that display labels, amounts, ceilings, durations, badges, features, ordering, and availability stay mathematically and operationally valid.

- **Business Value:** Allows commercial tuning of tiers without breaking internal plan references or corrupting calculator/backend logic.
- **Preconditions:** Administrator is authenticated with `manage_options`.
- **Independent Test:** Edit each of the three plans individually, submit invalid numbers (negative amounts, ceiling < initial payment, zero installments, non-integer strings), verify rejection/safe validation errors, and confirm editing one plan never erases the other two.
- **Main Success Scenario:**
  1. Administrator opens the three plan configuration panels (`negar_aghaz`, `negar_plus`, `negar_vizhe`), whose internal immutable IDs are independent of editable display names.
  2. Administrator configures for each plan: display name, short title, description, integer initial payment (`IRT`), integer purchase ceiling (`IRT`), optional minimum purchase amount (`IRT`), integer installments count, editable feature list, CTA text, optional badge, active/inactive toggle, display order, featured flag, and supplemental terms.
  3. Backend validates all invariants (e.g., integer `IRT` amounts, `initial_payment >= 0`, `purchase_ceiling >= initial_payment`, `min_purchase <= purchase_ceiling`, `installments_count >= 1` within bounded range) and persists cleanly.
- **Alternative & Failure Scenarios:**
  - *Invalid Financial Constraints:* Submitting `purchase_ceiling < initial_payment`, negative values, or zero/non-integer installments produces explicit Persian admin validation notices and refuses to corrupt stored valid values.
  - *Formatted String Input:* Numeric currency fields are never stored as formatted display strings; backend stores canonical integers in `IRT`.
  - *Backup/Restore Compatibility:* If included in theme configuration backup/export, schema validation, preview, cross-site checks, and atomic restore preserve all three plans cleanly without ever touching customer/contract/financial records.
- **Acceptance Scenarios:**
  1. **Given** an administrator modifies display name and ceiling of `negar_plus`, **When** settings are saved, **Then** its immutable internal ID remains `negar_plus`, `negar_aghaz` and `negar_vizhe` retain all their saved values, and stored monetary values remain strict integers.
  2. **Given** an administrator submits `purchase_ceiling < initial_payment` or `installments_count = 0`, **When** settings are submitted, **Then** backend validation rejects the invalid configuration with a clear Persian error message and does not persist corrupt numbers.

---

#### User Story `CLUB-US-11` — Independently Control Public Club vs. Financial Operations (Priority: P1)
As a **Store Administrator**, I want separate controls for (a) displaying the public Customer Club page, (b) accepting customer applications, and (c) activating live financial/payment operations so that I can safely publish informational pages long before live credit checkout is approved.

- **Business Value:** Enforces phased rollout safety and prevents premature financial liability.
- **Preconditions:** Administrator is authenticated with `manage_options`.
- **Independent Test:** Toggle each switch independently and verify resulting behavior on public page, application endpoints, checkout, and customer dashboard.
- **Main Success Scenario:**
  1. Public club = ON, Application intake = OFF, Financial operations = OFF → Public page and preview calculator work; application submission and live payment remain strictly blocked server-side.
  2. Public club = ON, Application intake = ON, Financial operations = OFF → Customers can submit applications for review; live credit checkout/installment payment remains blocked server-side.
  3. Public club = ON, Application intake = ON, Financial operations = ON *(only permitted when prerequisite financial decisions & verified gateway are complete)* → Full approved lifecycle operates.
- **Acceptance Scenarios:**
  1. **Given** public club is ON and financial operations are OFF, **When** any client invokes a financial/payment endpoint directly, **Then** the server rejects the request with a clear unavailable status even if UI controls are bypassed.

---

### D. Financial / Store Operator

#### User Story `CLUB-US-12` — Review Applications, Manage Agreements & Reconcile Payments with Scoped Permissions (Priority: P2)
As a **Financial or Store Operator**, I want role-scoped access to review customer applications, record approval/rejection decisions, reconcile real payments, handle cancellations/refunds, and inspect audit logs without requiring full WordPress `manage_options` site-administration privileges.

- **Business Value:** Enforces least-privilege operational security and clear accountability between site configuration and financial operations.
- **Preconditions:** Operator account holds a dedicated scoped capability (e.g., future `manage_negar_club_operations` or scoped WooCommerce shop-management capability defined in Phase 2) separate from `manage_options`.
- **Independent Test:** Perform review, approval, rejection, reconciliation, and audit-view actions using (a) an authorized operator account, (b) a regular customer account, and (c) an unauthenticated session; verify strict server-side capability enforcement and immutable audit logging.
- **Main Success Scenario:**
  1. Operator views queued applications (`pending_review`), inspects customer-submitted info and real order/account history, and transitions an application to `approved` or `rejected` with an explicit reason and audit record (actor ID, timestamp, previous state, new state).
  2. For active agreements, operator reconciles verified payment events or initiates approved cancellation/refund adjustments.
  3. Operator cannot silently overwrite historical installment amounts or delete audit history; every financial adjustment creates an explicit auditable ledger entry.
- **Alternative & Failure Scenarios:**
  - *Invalid State Transition:* Attempting to approve an already `cancelled` or `expired` application, or marking an unpaid installment settled without a valid reconciliation reference/reason, is rejected by the state machine.
  - *Customer Attempting Operator Action:* Rejected with HTTP 403 and logged as unauthorized attempt if applicable.
- **Acceptance Scenarios:**
  1. **Given** an operator holds scoped financial-operations permission without `manage_options`, **When** the operator approves or rejects a `pending_review` application, **Then** the transition succeeds, an immutable audit record is written, and the operator remains denied access to global WordPress/theme settings.
  2. **Given** an operator attempts an invalid transition (e.g., `rejected` → `active_agreement`) or a customer attempts any operator action, **When** submitted to the server, **Then** the server rejects the request and leaves state unchanged.

---

### E. System

#### User Story `CLUB-US-13` — Enforce Server-Authoritative Calculation, Immutable Snapshots & Ownership Isolation (Priority: P1)
As the **System**, I must treat the server as the sole authority for all financial calculations, freeze accepted commercial terms inside immutable agreement snapshots, and isolate every customer record by authenticated owner so that tampering, configuration drift, and data leaks are impossible.

- **Business Value:** Protects accounting integrity, legal enforceability, and customer privacy.
- **Preconditions:** Any calculation, application, agreement creation, or customer record read/write occurs.
- **Independent Test:** Submit tampered client-side installment totals, edit plan settings after agreement creation, and execute cross-user IDOR probes; confirm all invariants hold.
- **Acceptance Scenarios:**
  1. **Given** a client submits altered price or installment figures alongside a checkout/application request, **When** the server processes the request, **Then** client-supplied amounts are ignored and live WooCommerce prices plus server-stored plan rules govern the output.
  2. **Given** an agreement exists for Plan X and the administrator later changes Plan X's initial payment, ceiling, or term, **When** the agreement and its installments are inspected or settled, **Then** the historical agreement retains its exact original snapshot unchanged.

---

#### User Story `CLUB-US-14` — Guarantee Payment Idempotency, HPOS Compatibility, Auditability & Graceful Degradation (Priority: P1)
As the **System**, I must process payment callbacks idempotently under concurrency, interact with WooCommerce orders strictly through HPOS-compatible APIs, maintain an append-only operational audit trail, and degrade gracefully whenever WooCommerce or the financial module is inactive.

- **Business Value:** Prevents double charges, order-storage corruption under WooCommerce HPOS, and site-wide fatal errors during plugin updates or theme switches.
- **Preconditions:** WooCommerce HPOS is active (`woocommerce_custom_orders_table_enabled = yes`); financial persistence is decoupled from theme presentation.
- **Independent Test:** Simulate concurrent/replayed callbacks, verify order queries under HPOS, deactivate WooCommerce or the financial module in a test environment, and verify zero PHP fatal errors and truthful UI degradation.
- **Acceptance Scenarios:**
  1. **Given** two concurrent or sequential payment callbacks arrive with the same gateway transaction reference, **When** processed by the server, **Then** exactly one payment settlement and ledger debit/credit is recorded and the second is safely treated as an idempotent no-op.
  2. **Given** WooCommerce or the dedicated financial module is temporarily inactive, **When** visitors or administrators load the storefront or Customer Club page, **Then** zero PHP fatal errors occur, informational sections degrade cleanly, and transactional actions display clear unavailability notices.

---

### Edge Cases *(mandatory)*

1. **Disabled Plan Selected via Direct URL/POST:** Customer bookmarks or submits a disabled plan ID (`negar_vizhe` disabled) → Server rejects submission; UI shows honest unavailability notice and lists currently active plans.
2. **Purchase Amount Equals Zero or Product Is Free (`0` IRT):** Live WooCommerce product price is `0` or unpriced (`''`) → Calculator and checkout treat it as ineligible for installment financing without division-by-zero or fake currency output.
3. **Purchase Amount Below Plan Initial Payment:** E.g., product costs `2,000,000` IRT while plan initial payment is `3,000,000` IRT → System MUST NOT compute negative `remaining_principal`; either blocks installment eligibility below minimum threshold or caps applied initial payment to `actual_purchase_amount` (`remaining_principal = 0`, no installment needed) per `CLUB-BD-005`.
4. **Purchase Amount Exceeds Plan Ceiling:** E.g., cart is `12,000,000` IRT on `negar_plus` (`10,000,000` IRT ceiling) → Behavior strictly governed by `CLUB-BD-006` (reject vs. require excess `2,000,000` IRT as additional upfront cash); never silently increase credit beyond ceiling.
5. **Non-Divisible Installment Amounts:** E.g., `remaining_principal = 5,000,000` IRT over `6` months (`833,333.33...` IRT) → Integer rounding rule MUST guarantee `sum(installments) === 5,000,000` IRT exactly, with zero lost or invented Tomans (`CLUB-BD-022`).
6. **WooCommerce Product Price or Stock Changes Between Approval and Checkout:** Product price increases or stock sells out before checkout completion → Live WooCommerce price and stock at checkout govern eligibility; if out of stock or newly outside bounds, checkout halts cleanly without creating a broken agreement (`CLUB-BD-017`).
7. **Plan Configuration Edited After Application or Agreement Creation:**
   - For **existing agreements:** Immutable snapshot is preserved (`CLUB-FR-027`).
   - For **pending applications:** Governed explicitly by `CLUB-BD-019` (must clearly re-confirm or bind requested snapshot; never silently charge a higher amount than accepted).
8. **Failed, Timed-Out, or Cancelled Payment Gateway Callback:** No agreement is activated and no installment is marked paid; pending attempt expires safely and allows clean retry without double-charging.
9. **Duplicate / Replayed Gateway Callback:** Idempotency key prevents duplicate payment registration, duplicate installment settlement, or corrupted balance.
10. **Order Cancellation, Full Refund, or Partial Refund:** Must follow approved accounting rules (`CLUB-BD-014`) to void/adjust unpaid installments and record refund reconciliation without deleting historical audit entries.
11. **Guest vs. Privileged Account Trying OTP:** Existing theme security policy blocks `administrator`/`shop_manager` from public OTP login; club page must respect this policy and never bypass or weaken `inc/auth-otp.php`.
12. **WooCommerce or Financial Plugin Inactive:** Storefront continues rendering HTTP 200 without fatal errors; public club falls back safely with transparent status messaging.
13. **RTL & Extreme Viewport/Long-String Resilience:** Very long Persian plan titles, badges, or multi-million/billion IRT figures on `375px` mobile screens must wrap or stack cleanly with zero horizontal overflow (`scrollWidth <= clientWidth`).

---

## 3. Requirements *(mandatory)*

### 3.1 Functional Requirements Registry

> All functional requirements specify observable behavior. Items marked **`[BLOCKED BY: CLUB-BD-XXX]`** MUST NOT have live financial behavior activated until the referenced decision in [`business-rules.md`](./business-rules.md) is approved.

#### Domain 1 — Public Landing Page
- **`CLUB-FR-001`**: The system MUST provide a dedicated public Customer Club page («باشگاه مشتریان نگار») integrating seamlessly with the storefront header, footer, breadcrumb, and theme visual system, comprising distinct sections for Hero, Benefits, Pricing Plans, Plan Comparison, Installment Calculator, How It Works, FAQ, and Final CTA.
- **`CLUB-FR-002`**: The system MUST support enabling or disabling the public Customer Club page and hiding/showing individual optional sections (e.g., Benefits, Comparison, Calculator, Timeline, FAQ) via administration settings without leaving broken wrappers or empty containers.
- **`CLUB-FR-003`**: When the public Customer Club page is disabled, the system MUST render the administrator-configured Persian disabled-status message and safe navigation alternatives instead of active plan selection or calculator workflows.

#### Domain 2 — Plan Comparison
- **`CLUB-FR-004`**: The system MUST display up to three configured active plans as structured cards containing display name, short title, description, initial payment (`IRT`), purchase ceiling (`IRT`), repayment term (months), estimated monthly installment preview, feature bullets, optional badge, supplemental terms, and a primary CTA, sorted by configured display order with featured visual emphasis when enabled.
- **`CLUB-FR-005`**: The system MUST render a structured side-by-side comparison section comparing all active plans across initial payment, purchase ceiling, minimum purchase threshold (if configured), installment count, estimated monthly installment, and key conditions, gracefully adapting when 1, 2, or 3 plans are active and showing an honest empty state if 0 plans are active.

#### Domain 3 — Installment Calculator
- **`CLUB-FR-006`**: The system MUST provide an interactive installment calculator on the public page allowing selection among active plans and entry of a desired purchase amount in integer Toman (`IRT`), normalizing Persian/Arabic digits and outputting: selected plan, desired purchase amount, applicable initial payment, remaining principal, installment count, installment payment preview (including any rounding remainder note), total payable amount, and active constraints.
- **`CLUB-FR-007`**: The calculator MUST clearly disclose that its output is a **non-binding estimate/preview** and MUST NEVER be treated as an authoritative financial contract or credit approval.
- **`CLUB-FR-008`**: The system MUST provide an early, dependency-light, server-authoritative quotation and calculation component usable by the Phase 4 public calculator preview (without creating contracts, collecting payments, or activating credit) to ensure deterministic client/server parity; its final component location will follow the approved architecture (`CLUB-AD-001`), and any output dependent on unapproved business policies (`CLUB-BD-005`, `CLUB-BD-006`, `CLUB-BD-011`, `CLUB-BD-022`) MUST remain clearly labeled provisional or unavailable.

#### Domain 4 — Theme Administration
- **`CLUB-FR-009`**: The system MUST add a «باشگاه مشتریان» tab inside the existing NegarThem settings interface (accessible through both existing admin routes under `manage_options`) rather than creating an incompatible standalone settings panel.
- **`CLUB-FR-010`**: Administrators MUST be able to edit general club settings (public enable/disable toggle, application intake toggle, separate financial-operations status/toggle, page title, subtitle/description, CTA labels & links, benefits, how-it-works steps, FAQ items, section visibility toggles, disabled-state messages, and legal/informational notices) without affecting or overwriting other theme settings tabs.
- **`CLUB-FR-011`**: Administrators MUST be able to edit all three primary plan records (`negar_aghaz`, `negar_plus`, `negar_vizhe`) with valid defaults; saving one plan or general club settings MUST preserve all other plan records and all other theme settings groups.

#### Domain 5 — Plan Configuration Validation
- **`CLUB-FR-012`**: Each of the three primary plans MUST retain an immutable internal identifier (`negar_aghaz`, `negar_plus`, `negar_vizhe`) independent of its editable Persian display name so that renaming a plan never breaks historical references or lookups.
- **`CLUB-FR-013`**: The backend MUST validate and sanitize every Customer Club configuration field on save: storing monetary values strictly as non-negative integer `IRT` (never formatted display strings), enforcing `purchase_ceiling >= initial_payment`, enforcing `min_purchase <= purchase_ceiling` when set, clamping `installments_count` to valid positive integers (e.g., `1`–`36`), validating display order and boolean flags, sanitizing URLs to safe site-relative or HTTP(S) schemes, and rejecting invalid inputs with explicit Persian validation notices.
- **`CLUB-FR-014`**: Portable presentation/plan configuration stored in theme settings MUST be kept strictly separate from customer applications, agreements, installments, payments, and secrets; if exported/imported through the existing theme settings backup mechanism, schema validation, disallowed-key enforcement, non-destructive preview, and atomic rollback MUST all cover the club configuration cleanly.

#### Domain 6 — Authentication Integration
- **`CLUB-FR-015`**: The system MUST reuse the existing WordPress/WooCommerce customer account and phone OTP authentication flow (`inc/auth-otp.php` / `/my-account/`) and MUST NOT introduce a parallel user registration or login system.
- **`CLUB-FR-016`**: When a guest selects a plan CTA while application intake is enabled, the system MUST direct the guest to existing authentication with validated same-origin return intent so that upon verified sign-in the customer returns to the intended club workflow; privileged accounts MUST remain subject to existing OTP security policies.

#### Domain 7 — Application Creation & Review
- **`CLUB-FR-017`**: When application intake is enabled, an authenticated customer MUST be able to submit an application for an enabled plan only after explicit consent to disclosed terms; the server MUST persist the application with an immutable snapshot of requested plan terms and set initial status to `pending_review`.
- **`CLUB-FR-018`**: The system MUST enforce an explicit application lifecycle state machine (`pending_review` → `approved` | `rejected` | `cancelled` | `expired`) and reject invalid transitions server-side (`[BLOCKED BY: CLUB-BD-009, CLUB-BD-018, CLUB-BD-020]`).
- **`CLUB-FR-019`**: Authorized Financial/Store Operators MUST be able to review `pending_review` applications and record `approved` or `rejected` decisions with mandatory audit metadata and policy-compliant customer-facing status messages (`[BLOCKED BY: CLUB-BD-009]`).

#### Domain 8 — Credit Approval Boundaries
- **`CLUB-FR-020`**: Submitting an application or paying an initial amount MUST NOT automatically grant credit approval or convert into a binding agreement without passing the approved authorization workflow (`[BLOCKED BY: CLUB-BD-001, CLUB-BD-009, CLUB-BD-010]`).
- **`CLUB-FR-021`**: Every approved application MUST carry an explicit approval scope (eligible plan snapshot, maximum approved credit ceiling, and validity expiration timestamp per `CLUB-BD-018`) beyond which the approval automatically becomes `expired` and cannot be used for new purchases (`[BLOCKED BY: CLUB-BD-003, CLUB-BD-018]`).

#### Domain 9 — WooCommerce Purchasing Flow
- **`CLUB-FR-022`**: Live WooCommerce product pricing, stock status, and catalog visibility MUST be the sole source of truth for any installment-backed purchase (`[BLOCKED BY: CLUB-BD-003, CLUB-BD-004, CLUB-BD-017]`).
- **`CLUB-FR-023`**: When financial operations are enabled and a customer with a valid approval initiates an eligible WooCommerce purchase, checkout MUST validate order amount against approved ceiling, minimum threshold, product/cart eligibility, and live stock before allowing payment (`[BLOCKED BY: CLUB-BD-004, CLUB-BD-005, CLUB-BD-006, CLUB-BD-017, CLUB-BD-021]`).

#### Domain 10 — Initial Payment Accounting
- **`CLUB-FR-024`**: The initial payment MUST be accounted for explicitly as a reduction of the merchandise purchase obligation (`applied_initial_payment`) and MUST NEVER be hidden as an undisclosed membership fee (`[BLOCKED BY: CLUB-BD-001, CLUB-BD-002]`).
- **`CLUB-FR-025`**: If the approved business model permits pre-checkout deposits or results in unused deposited funds, the system MUST explicitly track deposit balance, allocation to purchase, and refund/carry-forward resolution (`[BLOCKED BY: CLUB-BD-001, CLUB-BD-007, CLUB-BD-008]`).

#### Domain 11 — Agreement Creation
- **`CLUB-FR-026`**: A binding `CreditAgreement` MUST be created ONLY after all approved prerequisites are verified (valid credit approval, verified order/checkout commitment, and verified initial payment settlement when required by `CLUB-BD-001`) — never from an unverified client redirect or failed payment (`[BLOCKED BY: CLUB-BD-001, CLUB-BD-015, CLUB-BD-021]`).
- **`CLUB-FR-027`**: Upon creation, each `CreditAgreement` MUST store an immutable snapshot of all accepted commercial terms (internal plan ID, display name at contract time, initial payment applied, purchase ceiling, actual purchase amount, remaining principal, installment count, fee/interest rate if any, rounding rule, legal terms version, and linked WooCommerce order ID); subsequent admin edits to plan settings MUST NOT alter any historical agreement.

#### Domain 12 — Installment Generation
- **`CLUB-FR-028`**: Upon `CreditAgreement` activation, the server MUST generate the complete deterministic schedule of `InstallmentItem` records in integer `IRT` with exact due dates and statuses (`scheduled` / `due` / `paid` / `overdue` / `waived` / `cancelled`) (`[BLOCKED BY: CLUB-BD-011, CLUB-BD-012, CLUB-BD-022]`).
- **`CLUB-FR-029`**: The generated installment schedule MUST satisfy four exact integer reconciliation invariants: (1) `sum(installment.principal_amount) === contract.financed_principal`, (2) `sum(installment.charge_amount) === contract.total_charges`, (3) `sum(installment.total_amount) === contract.total_customer_obligation`, and (4) `installment.total_amount = installment.principal_amount + installment.charge_amount` for each individual installment item; all calculations MUST use integer monetary units (`IRT`, 0 decimals) with zero rounding drift across both divisible and non-divisible amounts (`[BLOCKED BY: CLUB-BD-011, CLUB-BD-022]`).

#### Domain 13 — Installment Tracking
- **`CLUB-FR-030`**: The system MUST accurately track each installment's real status, due date, paid amount, paid timestamp, and verified transaction reference, and derive agreement remaining balance and next due date solely from persisted installment records (never from hardcoded or cached guesses).

#### Domain 14 — Repayment & Reconciliation
- **`CLUB-FR-031`**: When a verified online payment provider is enabled, authenticated customers MUST be able to initiate payment for eligible outstanding installments (and early full settlement if permitted by `CLUB-BD-013`) with server-side amount verification and provider verification before marking any installment `paid` (`[BLOCKED BY: CLUB-BD-013, CLUB-BD-015]`).
- **`CLUB-FR-032`**: Payment confirmation callbacks and operator manual reconciliations MUST be strictly idempotent, reject amount/currency mismatches, prevent duplicate credits under concurrent requests, and link every settled installment to a real payment transaction reference (`[BLOCKED BY: CLUB-BD-015, CLUB-BD-016]`).

#### Domain 15 — Customer Dashboard
- **`CLUB-FR-033`**: The system MUST integrate a «باشگاه مشتریان من» section into the existing WooCommerce My Account navigation (`/my-account/`) displaying the authenticated customer's real membership/application status, submitted applications, active and closed agreements, remaining balance, paid installment count, remaining installment count, next genuine due date, and real payment history.
- **`CLUB-FR-034`**: Every customer dashboard view MUST display truthful Loading, Empty, Error, Disabled, and Success states and MUST suppress live payment controls whenever financial operations or payment gateway integration are inactive.

#### Domain 16 — Notifications
- **`CLUB-FR-035`**: The system MUST provide clear in-UI status feedback for application submission, validation errors, approval/rejection updates, and payment outcomes, and define clean extension hooks for future SMS/email lifecycle notifications (application status change, installment reminder, payment receipt) without leaking secrets, OTPs, or sensitive financial details (`[BLOCKED BY: CLUB-BD-015]`).

#### Domain 17 — Cancellation & Refunds
- **`CLUB-FR-036`**: The system MUST handle WooCommerce order cancellation, failure, full refund, and partial refund events in synchronization with linked Customer Club agreements and installments without corrupting balances or deleting audit trails (`[BLOCKED BY: CLUB-BD-014, CLUB-BD-016]`).
- **`CLUB-FR-037`**: Cancelled or rejected applications and voided/refunded agreements MUST transition to truthful terminal states visible to both customer and operator, with clear accounting of any refundable initial payment or paid installments (`[BLOCKED BY: CLUB-BD-008, CLUB-BD-014]`).

#### Domain 18 — Data Ownership & Persistence
- **`CLUB-FR-038`**: All customer applications, credit agreements, installment schedules, payment transactions, and audit events MUST be stored in durable, structured records independent of active WordPress theme options (`wp_options`) so that switching or updating the theme never deletes or corrupts business-critical financial obligations.

#### Domain 19 — Operational Audit Trail
- **`CLUB-FR-039`**: The system MUST record an append-only audit trail (`LedgerAuditEvent`) for every material lifecycle and financial event (application submitted, approved, rejected, expired, cancelled; agreement created; payment initiated, verified, failed, replayed; manual reconciliation; refund/cancellation adjustment) capturing actor ID/role, UTC timestamp, entity reference, prior state, new state, and non-sensitive summary metadata.

#### Domain 20 — Graceful Degradation
- **`CLUB-FR-040`**: If WooCommerce, the dedicated financial module, SMS provider, or online payment gateway is inactive or unavailable, the system MUST degrade safely with zero PHP fatal errors, keeping public informational pages accessible where appropriate and disabling dependent transactional actions with honest Persian notices.

---

### 3.2 Installment Calculation Behavioral Requirements

1. **Strict Separation of Financial Terms:** The system MUST keep the following nine quantities distinct in data models, server calculations, admin labels, and customer displays:
   - `plan_purchase_ceiling` (سقف ارزش خرید طرح)
   - `plan_initial_payment` (مبلغ اولیه / پیش‌پرداخت طرح)
   - `actual_purchase_amount` (مبلغ واقعی خرید از قیمت زندهٔ ووکامرس)
   - `applied_initial_payment` (پیش‌پرداخت اعمال‌شده روی این خرید)
   - `remaining_principal` (اصل بدهی باقی‌مانده قابل تقسیط)
   - `interest_amount` (سود — در مدل پیشنهادی فعلی `0` تا تأیید سیاست)
   - `service_fee_amount` (کارمزد / هزینهٔ خدمات — در مدل پیشنهادی فعلی `0` تا تأیید سیاست)
   - `total_payable_amount` (جمع کل تعهد پرداختی: `applied_initial_payment + remaining_principal + interest_amount + service_fee_amount`)
   - `installment_amounts[]` (آرایهٔ مبلغ دقیق تک‌تک اقساط به عدد صحیح تومان)
2. **Currency Representation:** All monetary inputs, stored values, and intermediate/final obligations MUST use integer Toman (`IRT`), matching the verified store configuration (`woocommerce_currency = IRT`, `woocommerce_price_num_decimals = 0`). Floating-point arithmetic MUST NOT cause rounding drift.
3. **Provisional No-Fee Baseline Formulas (`PROPOSED — NEEDS APPROVAL`):**
   - `remaining_principal = actual_purchase_amount - applied_initial_payment`
   - `sum(installment_amounts[1..N]) = remaining_principal`
   - `total_payable_amount = applied_initial_payment + sum(installment_amounts[1..N])`
4. **Proposed Integer Rounding Invariant (`PROPOSED — NEEDS APPROVAL`, see `CLUB-BD-022`):**
   - When `remaining_principal` is not evenly divisible by `installments_count` (`N`), integer division MUST ensure no Toman is created or destroyed:
     - **Proposed Convention (`CLUB-BD-022`):** Compute base installment `base = intdiv(remaining_principal, N)`, compute integer remainder `rem = remaining_principal % N`, and allocate remainder to the first installment (`installment[1] = base + rem`, `installment[2..N] = base`) so `sum(installment_amounts) === remaining_principal` is guaranteed with zero drift across all N installments.
   - Until approved, this rule is explicitly labeled **Proposed** and documented alongside alternatives in `business-rules.md`.
5. **Boundary Conditions:**
   - `actual_purchase_amount <= 0`: Not eligible for installment calculation; display clear boundary message.
   - `actual_purchase_amount < plan_min_purchase` (if configured): Reject eligibility and show minimum threshold.
   - `actual_purchase_amount <= plan_initial_payment`: Never produce negative `remaining_principal`; follow `CLUB-BD-005` (either ineligible for installment or `remaining_principal = 0` cash purchase).
   - `actual_purchase_amount > plan_purchase_ceiling`: Never silently extend credit above ceiling; follow `CLUB-BD-006` (reject or require excess cash upfront).
   - **Multi-product carts / changed or cancelled orders / modified plan prices / future disclosed fees:** Governed by `CLUB-FR-022`, `CLUB-FR-027`, `CLUB-FR-029`, `CLUB-FR-036` and decisions `CLUB-BD-004`, `CLUB-BD-011`, `CLUB-BD-014`, `CLUB-BD-017`.

---

### 3.3 UI/UX Requirements (NegarThem Visual Continuity)

- **`CLUB-NFR-019` (Design System Continuity):** The public Customer Club page and My Account club views MUST be a natural visual continuation of NegarThem—consuming existing dynamic CSS custom properties (`--color-primary`, `--color-primary-hover`, `--color-secondary`, `--color-primary-light`, `--font-primary`, `--font-heading`, `--radius-*`, `--shadow-*`, `--container-max`) emitted by `inc/customizer.php` and `assets/css/main.css` (canonical crimson `#e90544` / `#c40439` default, automatically adapting whenever the administrator updates Colors or Typography). It MUST NOT introduce an isolated landing-page design language or hardcode legacy emerald (`#059669`) or fixed hex brand colors.
- **`CLUB-NFR-020` (RTL-First & Responsive Breakpoints):** All layouts, grids, plan cards, comparison tables, calculator controls, timelines, FAQ accordions, and account views MUST be true Persian RTL-first and mobile-first, verified with zero horizontal overflow (`scrollWidth <= clientWidth`) at `375px`, `768px`, `1024px`, and `1440px`. Final UI pixel measurements and multi-viewport screenshot evidence MUST be captured during **Phase 4** (no UI code or mockups in Phase 1).
- **`CLUB-NFR-021` (WCAG 2.1 AA Accessibility):** All interactive elements MUST support complete keyboard navigation (Tab/Shift+Tab, Enter/Space, Escape for modals/accordions), visible `:focus-visible` indicators, proper ARIA attributes (`aria-expanded`, `aria-controls`, `aria-live="polite"` for calculator updates/errors, semantic table headers), minimum `4.5:1` text contrast (`3:1` for large text/UI components), and `@media (prefers-reduced-motion: reduce)` compliance.
- **`CLUB-NFR-022` (Iconography & Financial Formatting):** All UI icons MUST be crisp, accessible inline SVGs matching NegarThem's icon language; emojis MUST NOT be used as UI icons. All monetary figures MUST display properly formatted Persian/localized integer amounts with explicit `تومان` / Toman mark and clear directional handling (`dir="ltr"` or isolated numeric spans where appropriate).
- **`CLUB-NFR-023` (Complete Interaction States):** Every interactive region (plans list, calculator, application form, account dashboard, installment list) MUST define explicit, user-friendly **Loading**, **Empty**, **Error**, **Disabled**, and **Success** states in natural Persian.
- **`CLUB-NFR-024` (Truthful Financial Copy & Trust-Imagery Honesty):** Page copy, badges, and CTAs MUST NOT promise unapproved zero-interest financing, guarantor-free approval, or instant credit approval. Displaying static storefront footer trust imagery (such as `trust-enamad.png` or `trust-zibal.png` added in baseline `eda9740`) MUST NEVER be treated or marketed as proof of an enabled payment gateway, active installment provider, or verified legal certification.

---

### 3.4 Technical, Security & Reliability Requirements

- **`CLUB-NFR-001` (Platform Compatibility):** Must remain fully compatible with WordPress `6.0+` (verified runtime `7.1.4`), PHP `8.0+` (verified runtime `8.2.29`), and WooCommerce `8.0+` (verified runtime `10.5.2`) without modifying WordPress Core or WooCommerce core files.
- **`CLUB-NFR-002` (WooCommerce HPOS Compatibility):** All order reads, queries, and metadata operations MUST use official HPOS-compatible WooCommerce CRUD APIs (`wc_get_order()`, `wc_get_orders()`, `WC_Order::get_meta()`, `WC_Order::update_meta_data()`, `WC_Order::save()`). Direct queries against `wp_posts` or `wp_postmeta` for WooCommerce orders are strictly forbidden.
- **`CLUB-NFR-003` (Theme-Independent Financial Persistence & Pending Source-Control Decision):** Business-critical financial data (applications, agreements, installments, payment transactions, audit logs) MUST NOT depend on active theme options and is **recommended** to reside in a lightweight dedicated WordPress plugin separate from theme UI. Because a plugin under `wp-content/plugins/` is not automatically versioned inside the theme Git repository, **Phase 2 MUST formally evaluate and obtain approval on decision `CLUB-AD-001`** (separate plugin repository vs. controlled monorepo source/build/deploy layout vs. alternative maintainable strategy) before any plugin is created.
- **`CLUB-NFR-004` (Safe Data Migrations & Rollback):** Any future custom database tables or schema upgrades MUST use versioned, idempotent migrations (`schema_version`), never drop or corrupt existing WordPress/WooCommerce tables, and support clean deployment rollback.
- **`CLUB-NFR-005` (Authentication & Role-Based Authorization):** Every administrative settings action requires `manage_options`; every financial/operational review action requires a dedicated scoped capability (distinct from full site administration); every customer action requires an authenticated session (`is_user_logged_in()`).
- **`CLUB-NFR-006` (Object-Level Ownership Validation / Anti-IDOR):** Every read or write on an application, agreement, installment, or receipt MUST enforce server-side ownership (`record.user_id === get_current_user_id()`) unless the caller holds an explicit authorized operator capability.
- **`CLUB-NFR-007` (Request Authentication & Verification Separation):** State-changing operations MUST enforce strictly separated authentication boundaries:
  - **Browser/User-Originated Requests:** Every state-changing browser request (Settings API submissions, AJAX actions, REST mutations, operator status transitions, customer payment initiations) MUST verify authentication, authorization, an action-specific WordPress nonce (`check_ajax_referer()`, `wp_verify_nonce()`, or WP REST cookie nonce), server-side input validation, and object-level ownership checks (`record.user_id === get_current_user_id()`).
  - **External Payment Provider Callbacks/Webhooks:** External provider callbacks/webhooks MUST verify provider-specific authentication, digital signatures, or authenticated server-to-server transaction verification (as supported by the approved provider), along with verified transaction amount, currency (`IRT`/`IRR`), merchant account, and transaction identity, plus strict replay protection and idempotency; they MUST NEVER depend on an authenticated WordPress browser session or user nonce, and MUST NEVER allow unauthenticated public access or bypass verification merely because a WordPress nonce cannot be provided.
- **`CLUB-NFR-008` (Sanitization, Escaping & Prepared SQL):** All input MUST be strictly typed and sanitized on entry; all HTML output MUST use contextual escaping (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`); any necessary custom SQL MUST use `$wpdb->prepare()` exclusively.
- **`CLUB-NFR-009` (Server-Side Calculation Authority):** All financial calculations affecting applications, checkout, agreements, or installment payments MUST execute authoritatively on the server; client JavaScript outputs are display previews only.
- **`CLUB-NFR-010` (Idempotent Payment Processing):** Every payment initiation and gateway callback MUST carry an idempotency / unique provider transaction reference enforced by database uniqueness constraints so replayed callbacks never double-credit or create duplicate obligations.
- **`CLUB-NFR-011` (Race-Condition & Concurrency Protection):** Critical transitions (application approval → agreement creation, installment payment settlement, balance deduction) MUST execute atomically with row/state verification to prevent concurrent double-submission or double-charging.
- **`CLUB-NFR-012` (Immutable Historical Agreement Terms):** Once a `CreditAgreement` is created, its snapshot of plan terms, prices, installment counts, and fee policy MUST be immutable; subsequent plan configuration edits apply solely to future applications/agreements.
- **`CLUB-NFR-013` (Secure Audit Logging):** Operational audit logs MUST be append-only and MUST NEVER log API keys, payment gateway secrets, passwords, OTP codes, session tokens, or raw payment card numbers.
- **`CLUB-NFR-014` (Personal Data Protection):** Collect only the minimum personal data strictly needed for approved credit workflows; restrict customer PII access to the owning customer and authorized operators; never expose PII in public pages, cached responses, JavaScript globals, or theme configuration backups.
- **`CLUB-NFR-015` (Secure Error Handling):** All failure paths MUST return honest, non-leaking Persian messages without exposing PHP warnings, SQL errors, stack traces, or internal system paths, and MUST NEVER fabricate fake orders, payments, or success states.
- **`CLUB-NFR-016` (Performance & Conditional Loading):** Customer Club stylesheets and scripts MUST load conditionally only on the Customer Club page, My Account club endpoint, and authorized admin settings tab—zero asset overhead on unrelated storefront pages; public/plan configuration MUST be stored non-autoloaded.
- **`CLUB-NFR-017` (Maintainable Extensibility):** Follow NegarThem's clean, dependency-light architecture (Vanilla JS, native WordPress/WooCommerce APIs, no heavy frameworks or speculative abstractions).
- **`CLUB-NFR-018` (Graceful Dependency Failure):** Deactivating WooCommerce or the financial plugin MUST NOT cause HTTP 500 or fatal errors anywhere on frontend or admin screens.

---

### 3.5 Key Domain Entities *(implementation-agnostic)*

- **`ClubConfiguration`**: Non-autoloaded portable storefront configuration (public enable flag, application intake flag, page texts, benefits, timeline steps, FAQ entries, section visibility toggles, disabled notices, legal disclosure texts, and the three `ClubPlan` definitions). Excludes all customer/transactional/secret data.
- **`ClubPlan`**: One of the three primary plan records identified by immutable `plan_id` (`negar_aghaz`, `negar_plus`, `negar_vizhe`), carrying display metadata, integer `initial_payment_irt`, integer `purchase_ceiling_irt`, optional integer `min_purchase_irt`, integer `installments_count`, features list, CTA label, badge, active toggle, display order, featured flag, and supplemental terms.
- **`ClubApplication`**: A customer's persisted request to enroll/qualify for a specific `ClubPlan`, holding `application_id`, `user_id`, `plan_id`, immutable `requested_plan_snapshot`, explicit `terms_accepted_at`, `terms_version`, lifecycle `status`, `approved_ceiling_irt` (when approved), `expires_at`, customer-safe status reason, and timestamps.
- **`CreditAgreement`**: A binding installment obligation created only after verified approval and qualifying purchase/payment conditions, holding `contract_id`, `contract_number`, `user_id`, `application_id`, `order_id`, immutable `plan_snapshot`, `purchase_amount`, `initial_payment`, `financed_principal`, `total_charges`, `total_customer_obligation`, rolling balances (`paid_principal`, `paid_charges`, `remaining_principal_balance`, `remaining_charges_balance`, `total_outstanding_balance`), `installments_count`, lifecycle `status` (`pending_prerequisite`, `active`, `completed`, `cancelled`, `defaulted`), and timestamps.
- **`InstallmentItem`**: An individual scheduled repayment belonging to one `CreditAgreement`, holding `installment_id`, `agreement_id`, `sequence_number` (`1..N`), `due_date`, `principal_amount_irt`, `charge_amount_irt`, `total_amount_irt`, `paid_amount_irt`, `status`, `paid_at`, and linked verified `transaction_id`.
- **`PaymentTransaction`**: A real, verifiable monetary event (initial payment, installment settlement, early payoff, or refund), holding `transaction_id`, `user_id`, `agreement_id`/`installment_id`/`wc_order_id`, `gateway_id`, unique `idempotency_key`, `provider_reference`, integer `amount_irt`, `currency` (`IRT`), `type`, `status`, and verification timestamp.
- **`LedgerAuditEvent`**: Append-only audit entry recording actor (`user_id` or `system`), actor role/capability context, `entity_type`, `entity_id`, `event_type`, `previous_status`, `new_status`, non-sensitive metadata summary, and UTC timestamp.

---

## 4. Success Criteria *(mandatory)*

### Measurable Outcomes

- **`CLUB-SC-001`**: 100% of guest visitors on desktop (`1440px`, `1024px`), tablet (`768px`), and mobile (`375px`) can view and compare all active plans and calculate an installment preview with `0px` horizontal overflow and zero console/PHP errors.
- **`CLUB-SC-002`**: 100% of installment calculations satisfy exact integer accounting (`sum(installment_amounts) === remaining_principal` in the no-fee model) across divisible, non-divisible, minimum, and ceiling boundary test cases with `0` IRT rounding drift.
- **`CLUB-SC-003`**: 100% of theme administration updates in the «باشگاه مشتریان» tab persist accurately across reloads, reject invalid inputs (`purchase_ceiling < initial_payment`, negative values, zero installments, unsafe URLs), and leave all 12 existing NegarThem settings tabs completely unaffected.
- **`CLUB-SC-004`**: 100% of dynamic color and typography changes made in NegarThem settings propagate automatically to the Customer Club page without hardcoded brand color mismatches.
- **`CLUB-SC-005`**: 100% of interactive Customer Club controls meet WCAG 2.1 AA automated and manual keyboard-navigation checks (focus visibility, ARIA live regions, contrast >= 4.5:1, reduced-motion respect, zero UI emojis).
- **`CLUB-SC-006`**: 100% of unauthorized access, CSRF-missing, and cross-customer IDOR negative test cases are rejected server-side with zero data disclosure and zero state mutation.
- **`CLUB-SC-007`**: 100% of historical agreements retain their exact frozen snapshot terms even after administrators modify or disable the originating plan configuration.
- **`CLUB-SC-008`**: 100% of duplicate/replayed payment callback simulations settle the target obligation at most once (strict idempotency) with complete append-only audit trail entries.
- **`CLUB-SC-009`**: 100% of existing NegarThem automated regression tests (`npm run test:lint`, `npm run test:settings`, `npm run test:e2e`, `tests/security-otp-disclosure.test.js`) remain green with zero regressions.
- **`CLUB-SC-010`**: Zero live financial commitments, real payments, or unapproved credit promises are active while unresolved business decisions remain in `NEEDS APPROVAL` status.

---

## 5. Assumptions, Dependencies & Baseline Context

### 5.1 Baseline Reconciliation (`f8fadc4` → `eda9740`)
- Phase 0 audited baseline `f8fadc4465803f148ce261984ff10db00106893b`. Before Phase 1 edits began, `main` advanced by one verified commit to `eda9740a9943ab54c2eeb8161498b6556e1533d6` (`footer`), which was approved as the new baseline after verification.
- **Confirmed Baseline Changes in `eda9740`:**
  - Added footer quick-action icons and trust badges (`assets/images/trust-enamad.png`, `assets/images/trust-zibal.png`) in `template-parts/footer/columns.php` and `assets/css/header-footer.css`, plus `footer.enamad_url` / `footer.zibal_url` settings in `inc/theme-settings.php` and corresponding updates in `inc/enqueue.php` and `tests/e2e-test.js`.
  - **Pre-existing Defect Confirmed Resolved:** The missing PHP opening tag previously reported by Phase 0 at lines 3704–3713 of `inc/theme-settings.php` is **verified fixed** at line 3712 (`<?php`) in commit `eda9740`. It MUST NOT be modified again in Phase 1.
- **Critical Distinction Regarding Footer Trust Imagery:** The presence of `assets/images/trust-zibal.png` and `assets/images/trust-enamad.png` in the footer is purely static storefront presentation. Live WooCommerce gateway verification confirmed `wc_zibal` is currently **disabled** (`bacs` and `cod` are the only enabled gateways). Footer imagery MUST NOT be inferred as an active online payment gateway, financial readiness, or verified regulatory certification.

### 5.2 Preserved Pre-Existing Limitations (Recorded, Not Expanded into Phase 1 Scope)
1. **No Active Online Payment Gateway:** Only offline `bacs` and `cod` gateways are active locally (`wc_zibal` is disabled); real online payment integration remains blocked until `CLUB-BD-015` is approved and a verified gateway is configured.
2. **Incomplete Local SMS Configuration:** `negar_theme_sms_options` has `enabled=true`, but no Kavenegar API key is configured locally (`SMS_CONFIGURED=no`). Local OTP testing must remain restricted to the existing local-only test transport guard (`negar_is_test_transport_allowed()`).
3. **Unavailable `codebase-memory-mcp` Session Connection:** The binary is installed on the host, but no MCP server is attached in the current agent runtime and no `.codebase-memory/` index exists.
4. **Legacy Design-Token & Doc Inconsistencies:** `theme.json` and `design-system/negarshop/MASTER.md` retain historical emerald `#059669` tokens while the live storefront uses canonical crimson `#e90544`/`#c40439`; `assets/css/content-pages.css` has 30 hardcoded crimson/pink values. New Customer Club UI MUST use dynamic CSS variables only, without refactoring unrelated pages during Phase 1.
5. **Existing Ajax Security Audit Findings Outside Scope:** Existing `kaveh_ajax_add_to_cart` / update / remove handlers in `inc/woocommerce.php` lack nonce checks; new Customer Club endpoints MUST strictly enforce nonces and capability checks without expanding Phase 1 into legacy refactoring.
6. **CLI MySQL Availability:** `mysql` is not in system `PATH` for `wp db export/query`; database backups prior to later DB-touching phases must resolve WAMP's MySQL path or use a safe PHP export utility.
7. **Currency & Rounding Dependencies:** Storefront currency is integer `IRT` (`0` decimals); non-divisible installments require formal approval of `CLUB-BD-022`.

### 5.3 Dependencies
- WordPress `6.0+` (live `7.1.4`), PHP `8.0+` (live `8.2.29`), WooCommerce `8.0+` (live `10.5.2`, HPOS enabled), Persian WooCommerce (`10.0.2`).
- Existing NegarThem settings framework (`inc/theme-settings.php`), backup module (`inc/theme-backup.php`), dynamic token output (`inc/customizer.php`), and OTP authentication (`inc/auth-otp.php`).
- Formal Phase 2 resolution of `CLUB-AD-001` (dedicated financial plugin repository, versioning, and deployment model).

---

## 6. Explicit Out-of-Scope Items (Phase 1 & Initial Release Boundaries)

- Creating or modifying PHP, CSS, JavaScript, templates, plugin source code, WordPress pages, WordPress options, or database tables during Phase 1.
- Generating `plan.md` or `tasks.md` prematurely during Phase 1 (reserved for Phase 2).
- Activating real online payments, automated credit scoring, or external banking/credit-bureau APIs without explicit employer approval and verified credentials.
- Replacing or creating a parallel authentication/registration system alongside `inc/auth-otp.php` and WooCommerce My Account.
- Refactoring unrelated theme templates, legacy Customizer fields, `theme.json` historical tokens, or unrelated WooCommerce Ajax handlers.
- Inventing synthetic customers, orders, credit approvals, installment payments, or fake gateway success states.

---

## 7. Requirements Blocked by Unresolved Business & Architecture Decisions

| Requirement ID(s) | Blocking Decision ID(s) | Blocks Phase | Summary of Dependency |
| :--- | :--- | :--- | :--- |
| `CLUB-FR-008`, `CLUB-FR-024`, `CLUB-FR-025`, `CLUB-FR-026` | `CLUB-BD-001`, `CLUB-BD-002`, `CLUB-BD-007`, `CLUB-BD-008` | Phase 6 (Financial Engine) | Collection timing & accounting treatment of initial payment and unused deposit handling |
| `CLUB-FR-021`, `CLUB-FR-022`, `CLUB-FR-023` | `CLUB-BD-003`, `CLUB-BD-004`, `CLUB-BD-005`, `CLUB-BD-006`, `CLUB-BD-017` | Phase 6 (Financial Engine) | Single vs. multi-purchase credit, multi-product carts, below-initial/above-ceiling rules, live price/stock lock |
| `CLUB-FR-018`, `CLUB-FR-019`, `CLUB-FR-020`, `CLUB-FR-021` | `CLUB-BD-009`, `CLUB-BD-010`, `CLUB-BD-018`, `CLUB-BD-019`, `CLUB-BD-020` | Phase 5 / Phase 6 | Credit approver role, collateral/guarantees, approval expiry, mid-review plan edits, concurrent agreements |
| `CLUB-FR-008`, `CLUB-FR-028`, `CLUB-FR-029`, `CLUB-FR-031` | `CLUB-BD-011`, `CLUB-BD-012`, `CLUB-BD-013`, `CLUB-BD-022` | Phase 4 (exact disclosure) & Phase 6 | Interest/service fees, overdue penalties, early settlement, integer rounding policy |
| `CLUB-FR-026`, `CLUB-FR-031`, `CLUB-FR-032`, `CLUB-FR-035`, `CLUB-FR-036`, `CLUB-FR-037` | `CLUB-BD-014`, `CLUB-BD-015`, `CLUB-BD-016`, `CLUB-BD-021` | Phase 6 (Financial Engine) | Cancellation/refund policy, approved online payment gateway, callback replay/delay policy, delivery/ownership timing |
| `CLUB-FR-038`, `CLUB-NFR-003` | `CLUB-AD-001` | Phase 2 (Architecture) & Phase 5+ | Source-control, versioning, and deployment strategy for the recommended dedicated financial plugin |

> **Full decision options, recommendations, and risk analyses are maintained in [`specs/013-customer-club/business-rules.md`](./business-rules.md).**
