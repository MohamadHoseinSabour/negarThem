# Feature Specification: 008 Extras and Modals

**Feature Branch**: `008-featurename-extras-and-modals`  
**Created**: 2026-11-06  
**Status**: Ready for Planning  
**Coverage Matrix IDs**: `MODAL-01`, `MODAL-02`, `MODAL-03`, `MODAL-04`  

---

## 1. User Scenarios & Testing

### User Story 1 - Quick View Modal (Priority: P1)
As a buyer browsing product lists,  
I want to click a "نمایش سریع" button on any product card to see photos, price, variations, and add to cart without navigating away,  
So that I can evaluate items rapidly and keep browsing smoothly.

**Why this priority**: Quick View speeds up catalog exploration and increases conversion velocity.

**Independent Test**:
- Click quick view icon on any product card.
- Verify modal opens with backdrop blur, loading state, compact gallery, title, price, and instant add-to-cart button.
- Verify closing via close button, backdrop click, or Esc key.

**Acceptance Scenarios**:
1. **Given** any product card (`MODAL-01`), **When** quick view button is clicked, **Then** modal displays product details via Ajax with zero full page reloads.

---

### User Story 2 - Wishlist Drawer & Local Storage (Priority: P1)
As a shopper saving items for later,  
I want to click the heart icon on any card to add/remove it from my Wishlist, and open a drawer to view saved items,  
So that I can purchase them later or compare my favorites.

**Why this priority**: Essential eCommerce feature expected by Persian shoppers (Digikala benchmark).

**Independent Test**:
- Click heart icon on product card: verify icon toggles to filled state and counter badge in header increases.
- Click header wishlist icon: verify off-canvas drawer slides in showing saved items with image, price, "افزودن به سبد" button, and delete button.
- Verify persistence across page refreshes via `localStorage`.

**Acceptance Scenarios**:
1. **Given** product card (`MODAL-02`), **When** heart icon is clicked, **Then** product ID is saved and floating toast confirms addition.

---

### User Story 3 - Product Comparison Table (Priority: P2)
As a shopper deciding between 2-4 appliances,  
I want to select products for comparison and view a side-by-side modal table of prices, specs, and features,  
So that I can clearly determine which product best suits my needs.

**Why this priority**: Critical for home appliances where specs (dimensions, wattage, capacity) differ subtly.

**Independent Test**:
- Click compare icon or add to compare list.
- Open compare modal (`MODAL-03`): verify side-by-side table comparing image, title, price, and attributes.
- Click remove item or clear comparison.

**Acceptance Scenarios**:
1. **Given** 2+ compared products, **When** compare modal opens, **Then** structured columns display side-by-side specs with clear removal controls.

---

### User Story 4 - Toast Notification Alert (Priority: P1)
As a customer performing actions (adding to cart, wishlist, copying links),  
I want immediate non-intrusive visual feedback in the corner of the screen,  
So that I am confident my action was executed successfully.

**Why this priority**: Polish and responsive feedback elevate perceived website quality and reduce repeated duplicate clicks.

**Independent Test**:
- Add product to cart or wishlist: verify toast appears at bottom-left/right in RTL with green icon, message, and auto-dismiss after 3.5 seconds.

**Acceptance Scenarios**:
1. **Given** user action (`MODAL-04`), **When** triggered, **Then** toast animates smoothly into view and auto-dismisses.

---

## 2. Requirements & Verification Traceability

| ID | Requirement | Verification Method |
|---|---|---|
| `MODAL-01` | Quick View modal with Ajax loading, compact gallery, and buy box | Click trigger on card, verify modal DOM, test Esc close |
| `MODAL-02` | Wishlist drawer with local storage persistence and move-to-cart | Test heart toggle, open drawer, verify item count |
| `MODAL-03` | Product comparison table comparing 2-4 items side by side | Add 2 items, open compare table, verify aligned rows |
| `MODAL-04` | Toast notification alert for cart and wishlist actions | Trigger action, verify toast animation and auto-dismiss |
