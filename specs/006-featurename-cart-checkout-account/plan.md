# Feature Plan: 006 Cart, Checkout, and My Account

**Feature Branch**: `006-featurename-cart-checkout-account`  
**Created**: 2026-11-06  
**Status**: Ready for Tasks Breakdown  
**Spec Reference**: `specs/006-featurename-cart-checkout-account/spec.md`  

---

## 1. Architecture & Component Structure

```
woocommerce/
├── cart/
│   ├── cart.php                          # Master cart page: responsive items table + sticky totals
│   ├── cart-empty.php                    # Graphic empty state with return-to-shop CTA
│   └── cart-totals.php                   # Order summary calculations box
├── checkout/
│   ├── form-checkout.php                 # 2-column checkout wrapper (billing/shipping + review)
│   ├── review-order.php                  # Order review table with payment gateways
│   └── thankyou.php                      # Order confirmation page with tracking details
└── myaccount/
    ├── navigation.php                    # Sidebar tabbed account navigation with SVG icons
    ├── dashboard.php                     # Account welcome banner + quick action cards
    ├── orders.php                        # Orders history table with status badges
    └── form-login.php                    # Unified login & register auth cards

assets/
├── css/checkout-cart.css                 # Dedicated styles for cart, checkout, thank-you, and account
└── js/checkout-cart.js                   # Cart auto-update, auth tabs toggle, password visibility toggle
```

---

## 2. Technical Decisions & Standards

1. **Standard WooCommerce Compatibility**:
   - Preserves all standard WooCommerce actions: `woocommerce_before_cart`, `woocommerce_after_cart`, `woocommerce_checkout_before_customer_details`, `woocommerce_checkout_order_review`, `woocommerce_account_navigation`, `woocommerce_account_content`.
   - Compatible with payment gateways (Zarinpal, Mellat, Cash on Delivery, etc.) and shipping plugins.
2. **Modern RTL Design System**:
   - Uses `--negar-primary`, `--negar-surface`, `--negar-border`, `--negar-radius-lg`.
   - Clean tables with rounded borders, responsive cards on mobile screens (< 768px).
3. **Sticky Summaries**:
   - Cart totals and Checkout order review use `sticky top-24` on desktop for uninterrupted summary visibility during long form completion.
4. **Auth Forms**:
   - Tabbed or 2-column layout in `myaccount/form-login.php` with toggle switch for mobile and distinct cards for desktop.

---

## 3. Step-by-Step Implementation Sequence

1. **Step 1 - Cart Templates**:
   - Create `woocommerce/cart/cart.php`, `woocommerce/cart/cart-empty.php`, `woocommerce/cart/cart-totals.php` (`FLOW-02`).
2. **Step 2 - Checkout & Thank You Templates**:
   - Create `woocommerce/checkout/form-checkout.php`, `woocommerce/checkout/review-order.php` (`FLOW-03`).
   - Create `woocommerce/checkout/thankyou.php` (`FLOW-04`).
3. **Step 3 - My Account & Auth Templates**:
   - Create `woocommerce/myaccount/navigation.php`, `woocommerce/myaccount/dashboard.php`, `woocommerce/myaccount/orders.php` (`FLOW-05`).
   - Create `woocommerce/myaccount/form-login.php` (`FLOW-06`).
4. **Step 4 - Stylesheet & Scripts**:
   - Create `assets/css/checkout-cart.css`.
   - Create `assets/js/checkout-cart.js`.
   - Enqueue in `inc/enqueue.php` for `is_cart() || is_checkout() || is_account_page()`.
5. **Step 5 - Verification & Quality Gate**:
   - Capture Playwright screenshots across viewports for Cart, Checkout, and My Account.
   - Update `specs/coverage-matrix.md` (`FLOW-02` to `FLOW-06`).
