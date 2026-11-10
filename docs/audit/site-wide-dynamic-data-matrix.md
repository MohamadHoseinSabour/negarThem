# Dynamic-data matrix — V4 work in progress

| Surface | Expected source of truth | Current verified behavior | Status |
| --- | --- | --- | --- |
| Homepage latest reviews | Approved WooCommerce product reviews | Queries the three latest approved `review` comments, renders the reviewer, rating, reviewed product, and review text; a zero-review result shows a truthful empty state. | Resolved |
| Homepage video reviews | Verified publisher or product-video source | No verified local source exists. The component retains its section layout but shows an explicit no-videos state rather than repeated sample presenter/video cards. | Resolved |
| Header product categories | WooCommerce `product_cat` taxonomy | Renders current root product categories; selection writes `product_cat` into the form and Ajax request. | Verified |
| Homepage hero deal | Published WooCommerce product in `games`, then latest published product fallback | Uses real title, link, image, current/sale price, sale discount, and review data. A product with no reviews says so rather than displaying demo values. | Resolved |
| Footer routes and contact surfaces | WordPress routes, WooCommerce URLs, and explicit Customizer values | Uses owned page/cart/account links; hides social or phone contact data unless it is explicitly configured; no longer presents demo retailer, badge, app, or handle content as store data. | Resolved |
| Header live results | WooCommerce product query | Ajax query constrains results by selected category and returns WooCommerce title, URL, thumbnail, category, and price markup. | Verified |
| Search “view all” | Selected form state | Carries `s`, `post_type=product`, and selected `product_cat`. | Resolved |
| Wishlist cards | Visitor’s browser preference plus WooCommerce products | Uses visitor-local IDs and retrieves product cards through the existing nonce-protected Ajax endpoint. The drawer now has an explicit opaque surface. | Verified |
| Hero and promotional sections | To be confirmed per component | Their current data/model ownership has not yet been fully inventoried in this V4 pass. | Pending |
| Testimonials beyond section 21 | To be confirmed per component | No broad claim is made until the remaining homepage sections are reviewed. | Pending |

## Integrity rule

No public component may manufacture customer identities, testimonials, product purchases, prices, availability, orders, delivery states, or verification codes. When a genuine source has no entries, the UI must use a clear empty state or omit the component instead of displaying sample data as fact.
