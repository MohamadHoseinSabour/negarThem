# Theme text coverage matrix

**Audit date:** 2026-11-11
**Scope:** Phase 2.1 text coverage completion. This matrix records visible copy
ownership; it does not authorize component reordering, new layouts, media
collections or commerce controls.

## Rules used for the audit

- **Handled** means a documented, static, theme-owned string is read through
  `negar_theme_text()` and appears in the Texts settings tab.
- **Deferred** means the string is coupled to a future component schema, media
  collection, live query, or an unapproved information architecture. It is not a
  writable Phase 2.1 setting.
- **Managed** means WordPress, WooCommerce, a visitor session, a user, a taxonomy,
  or the authentication system owns the data. It is intentionally never copied into
  this option.

## Homepage sections

| # | Section/template | Text ownership outcome | Status / future owner |
| --- | --- | --- | --- |
| 01 | `story-circles.php` | Story titles, CTA and media are collection records. | Deferred — Phase 5 media collections. |
| 02 | `hero-slider.php` | Static slide eyebrow/title/CTA and flash-heading copy are registry fields; featured product facts remain live. | Handled for static copy; product selection Phase 4 and slide media Phase 5. |
| 03 | `category-bubbles.php` | Category names and links are WooCommerce terms. | Managed; visual/order controls Phase 4. |
| 04 | `flash-deals.php` | Heading/query presentation is coupled to a live sale-product query. | Deferred — Phase 4 product sections. |
| 05 | `product-tabs.php` | Tab copy and queries form one product-section component. | Deferred — Phase 4 product sections. |
| 06 | `banners-grid.php` | Banner titles, links and images are collection records. | Deferred — Phase 5 media collections. |
| 07 | `sec-07-smartwatches-1.php` | Product-led presentation includes commercial data. | Deferred — Phase 4 product sections. |
| 08 | `sec-08-banner-wide.php` | Banner copy, link and media are one record. | Deferred — Phase 5 media collections. |
| 09 | `sec-09-accessories.php` | Section copy is paired with a product query. | Deferred — Phase 4 product sections. |
| 10 | `sec-10-banners-2col.php` | Two-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 11 | `sec-11-smartwatches-spec.php` | Product/spec presentation needs verified product ownership. | Deferred — Phase 4 product sections. |
| 12 | `sec-12-banners-compact.php` | Compact-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 13 | `sec-13-taste-grid.php` | Grid is product-led and includes commercial presentation. | Deferred — Phase 4 product sections. |
| 14 | `sec-14-feature-bar-1.php` | Spotlight cards need live product assignments. | Deferred — Phase 4 product sections. |
| 15 | `sec-15-visited-products-2.php` | Query semantics and copy must remain truthful. | Deferred — Phase 4 product sections. |
| 16 | `sec-16-apple-watch-deal.php` | Deal facts are live WooCommerce data. | Managed facts; Phase 4 presentation selection. |
| 17 | `sec-17-flash-deals-2.php` | Flash-deal component couples copy to product data. | Deferred — Phase 4 product sections. |
| 18 | `sec-18-banners-2col.php` | Two-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 19 | `sec-19-gaming-laptop.php` | Product presentation and query are coupled. | Deferred — Phase 4 product sections. |
| 20 | `brands-slider.php` | Brand identity/media needs a verified taxonomy or collection model. | Deferred — Phase 5 media collections. |
| 21 | `sec-21-testimonials.php` | Reviews are real WooCommerce reviews or a truthful empty state. | Managed reviews; Phase 4 presentation only. |
| 22 | `sec-22-flash-deals-audio.php` | Product carousel copy is coupled to live objects. | Deferred — Phase 4 product sections. |
| 23 | `sec-23-feature-bar-2.php` | Spotlight cards need live product assignments. | Deferred — Phase 4 product sections. |
| 24 | `sec-24-smartwatches-2.php` | Static commercial values must be replaced by live product data first. | Deferred — Phase 4 product sections. |
| 25 | `sec-25-gaming-zone.php` | Product/hotspot ownership requires a presentation schema. | Deferred — Phase 4 product sections. |
| 26 | `sec-26-visited-products-3.php` | Query semantics and labels need an approved product schema. | Deferred — Phase 4 product sections. |
| 27 | `sec-27-banners-compact-2.php` | Compact-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 28 | `sec-28-mobile-spotlight.php` | Spotlight is product-led. | Deferred — Phase 4 product sections. |
| 29 | `sec-29-banners-2col.php` | Two-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 30 | `sec-30-recently-viewed.php` | Items come from the current visitor's history. | Managed visitor state; empty-state copy deferred to Phase 4. |
| 31 | `sec-31-video-reviews.php` | No review feed may be invented. | Managed/truthful empty state; verified model required. |
| 32 | `sec-32-banners-2col.php` | Two-banner copy and media are collection records. | Deferred — Phase 5 media collections. |
| 33 | `blog-posts.php` | Article data should move to real WordPress posts before controls. | Deferred — Phase 4 content presentation. |
| 34 | `sec-34-feature-bar-3.php` | Spotlight cards need live product assignments. | Deferred — Phase 4 product sections. |
| 35 | `sec-35-trust-marquee.php` | Trust claims require verification before editing. | Deferred pending verified claims. |

`category-showcase.php` exists under the home folder but is not mounted by
`front-page.php`; it has no writable setting and remains deferred until an approved
homepage composition phase.

## Header, footer and internal templates

| Area/template | Handled in Phase 2.1 | Explicitly excluded or deferred |
| --- | --- | --- |
| `header/top-bar.php` | Existing announcement plus the static seasonal tagline. | Destination URL and decorative assets remain fixed. |
| `header/main-header.php` | Existing search/login/cart copy plus Mega Menu heading, archive hint and sale-card copy. | Product-category names, term links, sale query, user display name and accessibility-only labels are managed. |
| `header/navigation.php`, `mobile-drawer.php`, `mobile-bottom-bar.php` | None. | Registered/fallback navigation labels and routes are approved navigation, not text settings. |
| `header/mini-cart-drawer.php` | None. | Cart counts, cart state and WooCommerce inactive message are system-managed. |
| `footer/columns.php` | Existing headings/contact copy plus phone label, contact-card heading/description/CTA and social-empty notice. | Link destinations, social URLs, privacy route, copyright and footer-about use existing WordPress/legacy settings. |
| `footer/newsletter.php`, `features.php`, `trust-badges.php`, `copyright.php` | None: these partials are not mounted by `footer.php`. | Do not create dead settings; trust/payment claims also need verification. |
| `page-about.php` | Hero, guide, empty state and all three information-card title/description/action fields. | Breadcrumb, page title and `the_content()` remain WordPress-managed; links retain their approved destinations. |
| `page-contact.php` | Hero/form/map copy, contact-card labels and map-empty-state copy. | Contact values, submissions, required form labels, validation/status text and map URL remain their current settings/system owners. |
| `404.php` | Existing recovery heading, description, search placeholder, actions and category prompt. | Search-control accessibility label and live category names remain managed. |
| `page-information.php` | None. | Page title/content are WordPress-managed; generic supporting copy is deferred to an approved information-page scope. |
| `archive.php`, `index.php`, `search.php`, `single.php` | None. | Archive/post titles and query results are WordPress content. |
| `woocommerce/**` | None. | Products, prices, inventory, reviews, cart, checkout, customer, order and transactional messages remain WooCommerce-managed. |
| Global modal/auth components | None. | Authentication, cart, wishlist, compare and toast states are system/visitor components; their copy needs a separate approved component scope. |
