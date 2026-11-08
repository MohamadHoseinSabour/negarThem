# Reference Fidelity Matrix — Negar Shop vs Kaveh Demo 43

**Reference Site:** [Kaveh Shop Demo 43 (دموی شماره ۴۳ کاوه)](https://kaveh.moeinwp.com/43/)  
**Local Test Site:** `http://localhost/wp4/`  
**Audit Viewports:** `375px` (Mobile), `390px` (Modern Mobile), `768px` (Tablet), `1024px` (Small Desktop), `1280px` (Desktop), `1440px` (Wide Desktop)  
**Tolerances:** Spacing $\pm 4\text{px}$, Typography $\pm 1\text{px}$, Grid Columns 100% match.

---

## 1. Route Comparison & Fidelity Audit

| # | Route / Page | Reference URL | Local Route | Viewports Audited | Visual Layout Status | Functional Fidelity Status |
|---|---|---|---|---|---|---|
| **1** | **Homepage** | `/43/` | `/` | 375, 390, 768, 1024, 1280, 1440 | 35 Sections Present | Banner & slider timers verified |
| **2** | **Shop Catalog** | `/43/shop/` | `/shop/` | 375, 768, 1024, 1440 | `category-nk` Toolbar + Sidebar | **FAIL (contract mismatch)** |
| **3** | **Category Archive** | `/43/product-category/...` | `/product-category/...` | 375, 768, 1024, 1440 | 2-Column with Breadcrumbs | **FAIL (contract mismatch)** |
| **4** | **Brand Archive** | `/43/brand/...` | `/brand/...` | 375, 768, 1024, 1440 | Brand Header + Product Grid | Scoped filter required |
| **5** | **Search Results** | `/43/?s=...&post_type=product` | `/?s=...&post_type=product` | 375, 768, 1024, 1440 | Grid + Search Header | Needs Live Search class fix |
| **6** | **Empty Search** | `/43/?s=nonexistent` | `/?s=nonexistent` | 375, 768, 1024, 1440 | Empty Visual + Suggestions | **VERIFIED** |
| **7** | **Single Product** | `/43/product/...` | `/product/...` | 375, 768, 1024, 1440 | Style 5 (`detail-product-7`) | Fix rating fallbacks |
| **8** | **Cart Page** | `/43/cart/` | `/cart/` | 375, 768, 1024, 1440 | `kaveh-cart-style-2` | Fix AJAX item removal/update |
| **9** | **Checkout Page** | `/43/checkout/` | `/checkout/` | 375, 768, 1024, 1440 | 2-Step Checkout | Needs test order check |
| **10** | **Order Received** | `/43/checkout/order-received/...`| `/checkout/order-received/...`| 375, 768, 1024, 1440 | Ribbon + Order Meta | Distinguish pending vs paid |
| **11** | **Login / Register** | `/43/my-account/` (logged out) | `/my-account/` | 375, 768, 1024, 1440 | `auth__page__twelve` Tabs | Remove dead SMS scripts |
| **12** | **My Account Dashboard** | `/43/my-account/` (logged in) | `/my-account/` | 375, 768, 1024, 1440 | Navigation Tabs + Summary | **VERIFIED** |
| **13** | **My Account Orders** | `/43/my-account/orders/` | `/my-account/orders/` | 375, 768, 1024, 1440 | Orders Table + Badges | Remove BOM from `orders.php` |
| **14** | **My Account Addresses** | `/43/my-account/edit-address/` | `/my-account/edit-address/` | 375, 768, 1024, 1440 | Billing/Shipping Address Forms | **VERIFIED** |
| **15** | **Blog Archive** | `/43/mag/` | `/mag/` or `/blog/` | 375, 768, 1024, 1440 | `latest-articles-nk` 16:9 Grid | **VERIFIED** |
| **16** | **Single Post** | `/43/post-slug/` | `/post-slug/` | 375, 768, 1024, 1440 | `detail-blog-nk` with Author Box| **VERIFIED** |
| **17** | **About Us** | `/43/about-us/` | `/about-us/` | 375, 768, 1024, 1440 | 4 Counters + Brand Story | **VERIFIED** |
| **18** | **Contact Us** | `/43/contact/` | `/contact/` | 375, 768, 1024, 1440 | Contact Cards + Form + FAQ | **VERIFIED** |
| **19** | **404 Not Found** | `/43/non-existent-page/` | `/non-existent-page/` | 375, 768, 1024, 1440 | Custom 404 Illustration | **VERIFIED** |
| **20** | **Drawers & Modals** | QuickView, Wishlist, Compare | Modal Overlays | 375, 768, 1024, 1440 | Off-canvas & Center Modals | Re-wire card triggers |

---

## 2. Screenshot Integrity Audit (docs/compare/)

During independent inspection of the tracked images in `docs/compare/`, severe duplication was uncovered:

### Duplicated Image Clusters:
1. **Cluster 1 (1440px): MD5 `F5070FE8E9D988B80F4702A9A868A306` (8 files identical):**
   - `404-1440.png`
   - `about-1440.png`
   - `contact-1440.png`
   - `home-wide-1440.png`
   - `post-1440.png`
   - *(and 3 other pages)*
2. **Cluster 2 (375px): MD5 `0B32D43199E0E0B572BFC0EF2CB3B8CC` (8 files identical):**
   - `404-375.png`
   - `about-375.png`
   - `contact-375.png`
   - `home-mobile-375.png`
   - `post-375.png`
   - *(and 3 other pages)*
3. **Cluster 3: MD5 `CCDA2FA7C54FA1FBD661984E6032E3BD`:**
   - `account-1024.png` == `cart-1024.png`
4. **Cluster 4: MD5 `051014AC050D1101D6F032CF37D4AB8F`:**
   - `account-1440.png` == `cart-1440.png`

### Remediation Protocol:
All duplicated images are marked **INVALID / UNVERIFIED**. In Phase 5 and Phase 8, a headless Playwright capture pipeline will be executed against each distinct local route with real test fixtures and metadata, replacing all duplicates with genuine independent captures.
