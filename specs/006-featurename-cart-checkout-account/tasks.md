# Tasks: 006 Cart, Checkout, and My Account

**Feature Branch**: `006-featurename-cart-checkout-account`  
**Spec Reference**: `specs/006-featurename-cart-checkout-account/spec.md`  
**Plan Reference**: `specs/006-featurename-cart-checkout-account/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T080**: Create Cart templates `woocommerce/cart/cart.php`, `woocommerce/cart/cart-empty.php`, and `woocommerce/cart/cart-totals.php` (`FLOW-02`).
  - *Files*: `woocommerce/cart/cart.php`, `woocommerce/cart/cart-empty.php`, `woocommerce/cart/cart-totals.php`
  - *Verification*: `/cart/` renders clean 2-column layout with items table, quantity controls, and totals summary.

- [x] **T081**: Create Checkout templates `woocommerce/checkout/form-checkout.php` and `woocommerce/checkout/review-order.php` (`FLOW-03`).
  - *Files*: `woocommerce/checkout/form-checkout.php`, `woocommerce/checkout/review-order.php`
  - *Verification*: `/checkout/` renders 2-column layout with billing fields and sticky order review.

- [x] **T082**: Create Thank You template `woocommerce/checkout/thankyou.php` (`FLOW-04`).
  - *Files*: `woocommerce/checkout/thankyou.php`
  - *Verification*: Renders order confirmation card with reference details and checkmark badge.

- [x] **T083**: Create My Account templates `woocommerce/myaccount/navigation.php`, `woocommerce/myaccount/dashboard.php`, and `woocommerce/myaccount/orders.php` (`FLOW-05`).
  - *Files*: `woocommerce/myaccount/navigation.php`, `woocommerce/myaccount/dashboard.php`, `woocommerce/myaccount/orders.php`
  - *Verification*: `/my-account/` renders tabbed dashboard with icons and orders table.

- [x] **T084**: Create Auth form template `woocommerce/myaccount/form-login.php` (`FLOW-06`).
  - *Files*: `woocommerce/myaccount/form-login.php`
  - *Verification*: Logged-out `/my-account/` renders login & registration cards with clean inputs.

- [x] **T085**: Create `assets/css/checkout-cart.css` styling cart tables, sticky summaries, checkout inputs, payment gateways, and account navigation.
  - *Files*: `assets/css/checkout-cart.css`
  - *Verification*: Styles apply without layout breakage across mobile and desktop.

- [x] **T086**: Create `assets/js/checkout-cart.js` for cart auto-update, quantity steppers, and login/register tab switching.
  - *Files*: `assets/js/checkout-cart.js`
  - *Verification*: Interactive features work smoothly without console errors.

- [x] **T087**: Wire up assets in `inc/enqueue.php` for cart, checkout, and account pages.
  - *Files*: `inc/enqueue.php`
  - *Verification*: `checkout-cart.css` and `checkout-cart.js` load on relevant WooCommerce flow pages.

- [x] **T088**: Verification & Quality Gate: Capture Playwright screenshots in 4 widths (375, 768, 1024, 1440), verify HTTP 200 on `/cart/`, `/checkout/`, `/my-account/`, and update Coverage Matrix (`FLOW-02` to `FLOW-06`).
  - *Files*: `specs/coverage-matrix.md`, `PROGRESS.md`, `docs/compare/`
  - *Verification*: 5 matrix rows updated to `verified`.
