# Reference Pages Inventory — Kaveh Demo 43

**Target Site:** `https://kaveh.moeinwp.com/43/`  
**Audit Date:** 2026-11-06  
**Inspection Tool:** Playwright (Edge Channel / Chromium Headless & Live Navigation)  
**Total URLs Discovered:** 66 internal URLs across 8 distinct categories.

---

## 1. Primary Pages & Store Taxonomy

| Route ID | Category | Reference URL | Observed Status | Local Equivalent Route | Status in Local |
|---|---|---|---|---|---|
| PAGE-01 | Home | `https://kaveh.moeinwp.com/43/` | 200 OK (35 Full Sections, ~18,000px height) | `http://localhost/wp4/` | Exists (Only 9 sections, ~5,473px) — **P0 Deficiency** |
| PAGE-02 | Shop Archive | `https://kaveh.moeinwp.com/43/shop/` | 200 OK (Sidebar filters, sort, grid) | `http://localhost/wp4/shop/` | Exists (Post ID: 9) — Verified |
| PAGE-03 | Product Detail | `https://kaveh.moeinwp.com/43/product/iphone-17-pro-max/` | 200 OK (Gallery, price, tabs, rating, related) | `http://localhost/wp4/product/...` | Exists (32 WC products seeded) — Verified |
| PAGE-04 | Cart | `https://kaveh.moeinwp.com/43/cart/` | 200 OK (Cart table, coupon, totals) | `http://localhost/wp4/cart/` | Exists (Post ID: 10) — Verified |
| PAGE-05 | Checkout | `https://kaveh.moeinwp.com/43/checkout/` | 200 OK (Billing form, payment, order summary) | `http://localhost/wp4/checkout/` | Exists (Post ID: 11) — Verified |
| PAGE-06 | My Account | `https://kaveh.moeinwp.com/43/my-account/` | 200 OK (Login/Register, Dashboard) | `http://localhost/wp4/my-account/` | Exists (Post ID: 12) — Verified |
| PAGE-07 | Blog Archive | `https://kaveh.moeinwp.com/43/blog/` | 200 OK (Article grid, meta, pagination) | `http://localhost/wp4/blog/` | Exists (Post ID: 47) — Verified |
| PAGE-08 | Single Post | `https://kaveh.moeinwp.com/43/2026/10/ai-home-gadgets/` | 200 OK (Post body, tags, comments, author) | `http://localhost/wp4/?p=...` | Exists (`single.php`) — Verified |
| PAGE-09 | About Us | `https://kaveh.moeinwp.com/43/about-us/` | 200 OK (Hero, stats, team, mission) | `http://localhost/wp4/about/` | Exists (Post ID: 43) — Verified |
| PAGE-10 | Contact Us | `https://kaveh.moeinwp.com/43/contact-us/` | 200 OK (Contact form, map, info cards) | `http://localhost/wp4/contact/` | Exists (Post ID: 44) — Verified |
| PAGE-11 | FAQ | `https://kaveh.moeinwp.com/43/faq/` | 200 OK (Accordion items, categories) | `http://localhost/wp4/faq/` | Exists (Post ID: 94) — Verified |
| PAGE-12 | Terms & Conditions | `https://kaveh.moeinwp.com/43/terms/` | 200 OK (Legal text) | `http://localhost/wp4/terms/` | Exists (Post ID: 46) — Verified |
| PAGE-13 | Track Order | `https://kaveh.moeinwp.com/43/track-order/` | 200 OK (WooCommerce order tracking) | `http://localhost/wp4/track-order/` | Exists (Post ID: 45) — Verified |
| PAGE-14 | 404 Error | `https://kaveh.moeinwp.com/43/404-test-page/` | 404 Not Found (Custom 404 graphic + search) | `http://localhost/wp4/404-test-page/` | Exists (`404.php`) — Verified |

---

## 2. Product Categories Discovered

1. `گوشی موبایل` (`/product-category/mobile/`)
2. `لپ تاپ` (`/product-category/laptop/`)
3. `ساعت هوشمند` (`/product-category/smartwatch/`)
4. `کنسول بازی` (`/product-category/console/`)
5. `لوازم جانبی` (`/product-category/accessories/`)
6. `هدفون و هندزفری` (`/product-category/audio/`)
