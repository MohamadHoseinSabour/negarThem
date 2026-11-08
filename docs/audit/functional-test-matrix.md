# Functional Test Matrix — Negar Shop

**Theme Version:** 2.5.0  
**Environment:** WordPress 7.1.4, WooCommerce 10.5.2, Persian WooCommerce 10.0.2, PHP 8.2.29  
**Execution Target:** Local Test Server (`http://localhost/wp4/`)

---

## 1. Matrix Overview

| Test Suite | Total Scenarios | Initial Status | Post-Repair Status | Critical Bugs Identified |
|---|---|---|---|---|
| **TS-01: Foundation & Header** | 8 | 5 PASS / 3 FAIL | Target: 8 PASS | Live search class missing, BOM in header parts |
| **TS-02: Shop Catalog & Filters** | 12 | 2 PASS / 10 FAIL | Target: 12 PASS | AJAX filter contract mismatch (`catIds`, `sortt`, attributes) |
| **TS-03: Single Product Page** | 10 | 6 PASS / 4 FAIL | Target: 10 PASS | Fabricated ratings (4.5 fallback, max rating), hardcoded claims |
| **TS-04: Cart & Add-to-Cart** | 9 | 1 PASS / 8 FAIL | Target: 9 PASS | Sitewide AJAX cart unlocalized, missing PHP actions |
| **TS-05: Checkout & Order Flow** | 8 | 5 PASS / 3 FAIL | Target: 8 PASS | Thank-you template green ribbon on pending/on-hold orders |
| **TS-06: Customer My Account** | 8 | 6 PASS / 2 FAIL | Target: 8 PASS | UTF-8 BOM in `orders.php`, dead SMS login scripts |
| **TS-07: Modals & Extras** | 9 | 4 PASS / 5 FAIL | Target: 9 PASS | Variable product handling in QuickView, fragmented toasts |
| **TS-08: Content Pages & 404** | 6 | 6 PASS / 0 FAIL | Target: 6 PASS | All content templates render cleanly |
| **Total** | **70 Scenarios** | **35 PASS / 35 FAIL** | Target: **70 PASS** | **11 High/Critical Defect Areas** |

---

## 2. Granular Scenario Breakdown

### TS-01: Foundation, Header & Search
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-01.1 | Clean Page Load & No BOM | HTTP 200, no BOM `\uFEFF` before `<!DOCTYPE html>`, headers sent cleanly | **FAIL** (BOM in `setup.php`) | CLI curl check & `headers_sent()` |
| TC-01.2 | Vazirmatn Font & RTL Layout | `direction: rtl`, `Vazirmatn` font rendered throughout | **PASS** | CSS computed style check |
| TC-01.3 | Sticky Header on Scroll | Header sticks smoothly on scroll past 120px | **PASS** | Scroll event simulation |
| TC-01.4 | Mega Menu Walker | Dropdown menu opens with category links and icons | **PASS** | Mouse hover interaction |
| TC-01.5 | Desktop Live Search Trigger | Typing 2+ chars in header triggers AJAX search | **FAIL** (missing class `.negar-ajax-search-form`) | Input test & network trace |
| TC-01.6 | Live Search Results Rendering | Product thumbnail, title, category, price rendered safely | **FAIL** (unsafe `innerHTML` interpolation) | Security inspection |
| TC-01.7 | Live Search Keyboard Navigation | Arrow Down/Up navigates results; Enter visits product | **FAIL** (not attached) | Keyboard event dispatch |
| TC-01.8 | Mobile Navigation Drawer | Hamburger toggles drawer; Esc or backdrop closes | **PASS** | Click toggle test |

---

### TS-02: Shop Catalog & Live Filters
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-02.1 | Archive Initial Load | Products load matching queried category/taxonomy | **PASS** | Initial HTML inspection |
| TC-02.2 | Category Checkbox Filter | Checking a category sends AJAX and updates grid to that category | **FAIL** (PHP ignores `catIds`) | AJAX filter POST check |
| TC-02.3 | Brand Checkbox Filter | Checking a brand filters products by `product_brand` | **FAIL** (PHP ignores `brandIds`) | AJAX filter POST check |
| TC-02.4 | Attribute Filter (Color/Size) | Selecting attribute terms filters by `pa_*` attribute taxonomy | **FAIL** (PHP lacks attribute parser) | AJAX filter query test |
| TC-02.5 | Price Range Slider | Moving NoUiSlider updates price boundaries and filters products | **FAIL** (swapped/unparsed values) | Slider change trigger |
| TC-02.6 | In-Stock Switch | Toggling in-stock filters to `instock` meta | **PASS** | Checkbox toggle test |
| TC-02.7 | On-Sale Switch | Toggling on-sale filters to products on sale | **PASS** | Checkbox toggle test |
| TC-02.8 | Sorting Switch (Price / Popularity / Date) | Selecting orderby updates order without full page reload | **FAIL** (PHP expects `orderby`, JS sends `sortt`) | Sort dropdown click |
| TC-02.9 | Pagination with Active Filters | Page 2 maintains active filters | **FAIL** (contract mismatch) | Pagination click test |
| TC-02.10 | Empty Results State | Clean Persian empty message displayed if no products match | **PASS** | Filter to non-existent price |
| TC-02.11 | Stale Request Cancellation | Rapid filter clicks cancel in-flight request | **PASS** | AbortController / xhr.abort |
| TC-02.12 | Mobile Filter Drawer | Filters open in off-canvas drawer on mobile (<768px) | **PASS** | Mobile drawer toggle |

---

### TS-03: Single Product Page
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-03.1 | Single Product Style 5 Layout | 2-column layout (gallery on right in RTL, details on left) | **PASS** | Visual check |
| TC-03.2 | Gallery Thumbnails Switch | Clicking thumbnail changes main gallery image | **PASS** | Thumbnail click test |
| TC-03.3 | Image Zoom & Lightbox | Hover or click magnifies product image | **PASS** | Magnify plugin check |
| TC-03.4 | True Review Rating Display | Products without reviews show "بدون امتیاز" or unrated | **FAIL** (hardcoded 4.5 / 4.0 fallbacks) | Unrated product inspection |
| TC-03.5 | Variable Product Attribute Selection | Selecting variation updates price, SKU, and stock | **PASS** | Attribute dropdown change |
| TC-03.6 | Quantity Controls | `+` and `-` steppers increment/decrement within limits | **PASS** | Button click simulation |
| TC-03.7 | Delivery Perks Box | Displays store perks without unsupported hardcoded claims | **FAIL** (unsupported claims) | Template audit |
| TC-03.8 | Specifications Two-Color Table | Attributes render in alternating row colors | **PASS** | Attribute table inspection |
| TC-03.9 | Product Reviews Sorting | Sorting reviews alters comment query order | **FAIL** (visual state only) | Review sort button test |
| TC-03.10 | Mobile Sticky Bottom Buy Bar | Bottom bar appears on mobile scroll past main button | **PASS** | Scroll threshold test |

---

### TS-04: Cart & Add-to-Cart
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-04.1 | Simple Product AJAX Add-to-Cart | Adds product to cart, updates fragments and badge | **FAIL** (`kavehShopData` undefined on non-shop) | Click Add to Cart |
| TC-04.2 | Variable Product Add-to-Cart | Adds chosen variation ID and attributes | **FAIL** (AJAX handler missing) | Form submit test |
| TC-04.3 | Mini-Cart Drawer Synchronization | Adding product opens or updates mini-cart drawer | **FAIL** (missing fragments update) | Header cart badge check |
| TC-04.4 | Cart Page Item Quantity Stepper | Changing quantity updates item subtotal and cart total | **FAIL** (`kaveh_ajax_update_cart_kk` unhandled) | Quantity change test |
| TC-04.5 | Cart Page Item Removal | Clicking remove deletes item via AJAX and updates totals | **FAIL** (`kaveh_ajax_update_cart_kk_remove` unhandled) | Remove item click |
| TC-04.6 | Coupon Application (Valid & Invalid) | Valid coupon applies discount; invalid shows Persian error | **PASS** (handled by WC core) | Coupon form test |
| TC-04.7 | Empty Cart View | Empty cart displays icon, notice, and link to shop | **PASS** | Empty cart route check |
| TC-04.8 | Header Cart Counter Synchronization | Header badge matches `WC()->cart->get_cart_contents_count()` | **FAIL** (fragment sync broken) | Counter DOM check |
| TC-04.9 | Graceful Fallback without JS | Forms submit via standard HTTP POST if JS disabled | **PASS** | Standard WC action URLs |

---

### TS-05: Checkout & Order Flow
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-05.1 | 2-Step Checkout Display | Customer details on right (RTL), sticky order review on left | **PASS** | Checkout route check |
| TC-05.2 | Required Fields Validation | Submitting empty form highlights required fields | **PASS** | WC checkout validation |
| TC-05.3 | Shipping Method Selection | Selecting shipping updates order review total | **PASS** | Radio toggle test |
| TC-05.4 | Payment Gateway Integration | Test payment completes with sandbox/offline gateway | **PASS** | Cheque / COD test |
| TC-05.5 | Successful Payment Thank-You Page | Displays green ribbon, tracking number, payment date | **PASS** | Completed order test |
| TC-05.6 | On-Hold / Pending Order Thank-You Page | Displays on-hold status notice instead of false success | **FAIL** (always shows green success ribbon) | On-hold order test |
| TC-05.7 | Failed Payment State | Displays red failed alert and retry payment link | **PASS** | Failed order test |
| TC-05.8 | Guest vs Logged-In Checkout | Supports both guest checkout and account creation | **PASS** | Session test |

---

### TS-06: Customer My Account & Auth
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-06.1 | Logged-Out Tabbed Login / Register | Displays `auth__page__twelve` with functional tabs | **PASS** | Tab click toggle |
| TC-06.2 | Standard WooCommerce Login | Submitting credentials logs in user and redirects | **PASS** | WC login test |
| TC-06.3 | Registration Form | Creates new customer account if enabled in settings | **PASS** | WC register test |
| TC-06.4 | Password Visibility Toggle | Eye icon toggles password between text and password | **PASS** | Password toggle click |
| TC-06.5 | Orders Endpoint Display | Orders table renders with status badges without BOM | **FAIL** (BOM in `orders.php`) | Orders endpoint test |
| TC-06.6 | Account Addresses & Details | Customer can view and update addresses and details | **PASS** | Form submit test |
| TC-06.7 | Customer Logout | Logout link safely logs out customer with nonce | **PASS** | Logout action test |
| TC-06.8 | Dead SMS Scripts Removal | No broken SMS OTP AJAX calls on login pages | **FAIL** (`kaveh-login-ajax.js` enqueued) | Network inspect |

---

### TS-07: Modals & Extras
| ID | Scenario | Expected Behavior | Initial Result | Validation Method |
|---|---|---|---|---|
| TC-07.1 | QuickView Modal Open & Close | Clicking QuickView loads product data in modal | **PASS** | Modal toggle test |
| TC-07.2 | QuickView Variable Product Support | Variation selects work inside modal | **FAIL** (`wc_variation_form` not initialized) | Variation test in modal |
| TC-07.3 | Wishlist Add / Remove Toggle | Heart icon toggles product ID in localStorage | **PASS** | Wishlist button test |
| TC-07.4 | Wishlist Drawer Display | Wishlist drawer opens and lists stored items via AJAX | **PASS** | Drawer open test |
| TC-07.5 | Compare Add / Remove Toggle | Compare button adds item to compare table | **PASS** | Compare button test |
| TC-07.6 | Compare Specifications Modal | Displays full attribute specifications table | **PASS** | Compare modal test |
| TC-07.7 | Product Card Action Buttons | Shop cards expose QuickView, Wishlist, Compare | **FAIL** (missing triggers on catalog cards) | DOM inspection |
| TC-07.8 | Unified Toast Notifications | Consistent styling, RTL, top-right placement | **FAIL** (conflicting Notiflix configs) | Toast trigger test |
| TC-07.9 | Focus Trap & Escape Key on Modals | Pressing Escape closes any open modal/drawer | **PASS** | Keyboard Escape test |
