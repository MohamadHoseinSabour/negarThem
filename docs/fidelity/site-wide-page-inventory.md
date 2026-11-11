# Site-wide page inventory and verification status

Last verified: 2026-11-11 (Asia/Tehran)  
Environment: `http://localhost/wp4/`, active theme `negarThem`, WooCommerce live catalog

This inventory records the public routes that were checked during the phased repair. “Verified” means the route returned the expected HTTP response and its primary rendering/interaction is covered by the current browser suite. “Partial” means the route is reachable but one or more archive variants or deeper states still need a dedicated scenario. No product, order, customer, price, or policy data is fabricated by this audit.

## Public routes

| Surface | Route observed | Template/owner | Status | Evidence and remaining scope |
| --- | --- | --- | --- | --- |
| Homepage | `/` | `front-page.php` | Verified | HTTP 200; hero, live product sections, category links, reviews/video empty state, overflow, and mobile header are covered by `npm run test:e2e`. |
| Shop catalogue | `/shop/` | `woocommerce/archive-product.php` | Verified | HTTP 200; title is “فروشگاه”, all-catalogue pagination and sale destination are covered by E2E. |
| Product category archives | `/product-category/{slug}/` | `woocommerce/archive-product.php` | Partial | Mega-menu links are checked against the live category archive URLs; add a direct archive smoke test when more category fixtures are available. |
| Product search | `/?s={term}` | `search.php` / WooCommerce loop | Partial | HTTP 200 observed for `?s=iphone`; live search interaction is covered, while an explicit empty-result scenario remains useful. |
| Product detail | `/product/{slug}/` | `woocommerce/single-product.php` | Verified | HTTP 200 for the live FC24 product; gallery, tabs, Q&A, sticky mobile purchase bar and product actions pass E2E. |
| Cart | `/cart/` | `woocommerce/cart/cart.php` | Verified | HTTP 200; cart contents/totals and empty-state rendering are covered. |
| Checkout | `/checkout/` | `woocommerce/checkout/form-checkout.php` | Verified | Empty-cart request intentionally resolves to the cart state; checkout billing/review/step layout is covered after a seeded cart. |
| My account | `/my-account/` | `woocommerce/myaccount/my-account.php` | Verified | HTTP 200; guest OTP UI, no password/register form, navigation and responsive layout pass E2E. |
| Blog archive | `/blog/` | `archive.php` / page content | Verified | HTTP 200; semantic heading and responsive public-route smoke check pass. |
| About | `/about/` | `page-about.php` | Verified | HTTP 200; white surface, editable story area, cards and icon rendering pass E2E. |
| Contact | `/contact/` | `page-contact.php` | Verified | HTTP 200; configurable contact cards, labelled AJAX form, validation surface, white background and icons pass E2E. |
| FAQ | `/faq/` | shared information layout via `page.php` | Verified | HTTP 200; existing content is retained and the information-page layout is covered. |
| Terms | `/terms/` | shared information layout via `page.php` | Verified | HTTP 200; existing content is retained and the information-page layout is covered. |
| Track order | `/track-order/` | shared information layout via `page.php` | Verified | HTTP 200; existing title/content is rendered without inventing tracking data. |
| 404 | `/does-not-exist/` | `404.php` | Partial | Server returned HTTP 404; add a visual/keyboard assertion for the final empty-state links in a future browser pass. |

## Shared interaction surfaces

| Surface | Evidence | Status |
| --- | --- | --- |
| Desktop category mega-menu | Category tree, keyboard opening, `aria-expanded`, archive links and sale card | Verified by E2E |
| Mobile category drawer | Full category tree and zero horizontal overflow at 375px | Verified by responsive E2E coverage |
| Product cards | Wishlist, quick view, compare controls retain the live product ID | Verified by E2E |
| Sale carousel | Every image/title/action link resolves to the matching on-sale WooCommerce product; “مشاهده همه” uses `/shop/?on_sale=1` | Verified by E2E |
| Toast/add-to-cart feedback | Shared toast container is present; add/remove and cart continuity are covered in the core-flow pass | Verified/see Phase 6 |
| OTP authentication | Header and account forms use the shared phone/code flow and safe service errors | Verified by E2E and OTP security test |

## Verification commands

```text
npm run test:lint
npm run test:e2e
```

The inventory is intentionally evidence-based. Product/category/order states must continue to come from WordPress and WooCommerce at runtime; this document is not a claim that every possible plugin endpoint or authenticated order state has been exhaustively tested.
