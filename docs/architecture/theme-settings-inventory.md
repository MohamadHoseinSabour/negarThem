# NegarThem theme-settings inventory

**Audit date:** 2026-11-11  
**Scope:** Phase 0 of the configurable-theme project. This is an inventory of the
running theme, not a statement that the proposed controls already exist.

## Evidence and constraints

- The repository is a classic WordPress theme (`functions.php`, PHP templates and
  `theme.json`), not a block theme. WooCommerce integration is conditionally
  loaded from `functions.php`.
- `front-page.php` renders its 35 template parts only when the front page is not
  built with Elementor. The future layout registry must preserve that branch.
- The existing Customizer owns the only saved theme controls. It stores individual
  `theme_mod` values; it does **not** have the requested settings page, a versioned
  settings option, repeaters, import/export, or an SMS-provider adapter.
- Homepage templates use a mixture of live WooCommerce queries, hard-coded demo
  arrays, static copied media under the uploads `kaveh-demo` directory, and normal
  WordPress post/comment queries. A future control must not turn a hard-coded
  display value into a second source of truth for price, stock, rating, review or
  purchase information.
- `inc/auth-otp.php` has one dispatch extension point,
  `negar_dispatch_otp_sms`; no theme-owned provider hook was found. It already
  refuses a send when dispatch fails, and its explicit test transport does not
  disclose the OTP in its JSON response.

The code index reports parser gaps for `front-page.php` and several home templates;
their source was therefore read directly for this audit. The inventory should be
updated when a template is materially refactored.

## Current boot and ownership map

| Concern | Current owner | Current frontend binding | Migration implication |
| --- | --- | --- | --- |
| Theme bootstrap | `functions.php` | Loads `inc/setup.php`, enqueue/template helpers, Customizer, contact form, then WooCommerce modules including OTP | Load the future settings service before templates, but keep Customizer loaded for legacy reads. |
| Existing saved controls | `inc/customizer.php` | `get_theme_mod()` calls in header, footer, contact page, SEO and Customizer CSS | Read existing mods as fallbacks; never overwrite a saved mod merely because the new option is absent. |
| Homepage composition | `front-page.php` | 35 ordered `get_template_part()` calls | Replace only the non-Elementor branch with a trusted fixed registry. |
| Product presentation | `template-parts/home/*`, WooCommerce templates | Some live WC objects and some demo arrays | Section settings select IDs/categories/query rules; WC remains the source for all commercial fields. |
| Header and footer | `template-parts/header/*`, `template-parts/footer/*` | Customizer values plus template literals | New settings should fall back to the existing theme mods until an explicit override is saved. |
| Contact page | `page-templates/page-contact.php`, `inc/contact-form.php` | Contact theme mods; submissions in non-autoloaded `negar_contact_submissions` | Reuse the present data values and contact form. Do not make submissions part of theme export. |
| OTP | `inc/auth-otp.php`, `assets/js/otp-auth.js`, login templates | AJAX nonce, hashed transient and `negar_dispatch_otp_sms` filter | Add a narrowly scoped provider adapter to the existing filter; do not replace OTP issuance or verification. |

## Existing Customizer settings

All values below are individual theme mods. Defaults are only used when no theme
mod is saved; an intentionally empty saved value must remain distinguishable from
an unset future setting.

| Legacy key | Default / sanitization | Current bindings | Future ownership |
| --- | --- | --- | --- |
| `negar_shop_primary_color` | `#e90544`; `sanitize_hex_color` | `negar_shop_customizer_css()` | `colors.primary`; preserve legacy fallback. Earlier saved values such as `#059669` remain valid. |
| `negar_shop_accent_color` | `#c40439`; `sanitize_hex_color` | `negar_shop_customizer_css()` | `colors.secondary`; preserve legacy fallback. Earlier saved values such as `#e11d48` remain valid. |
| `negar_shop_topbar_announcement` | Welcome text; plain text | `template-parts/header/top-bar.php` | `header.announcement`. |
| `negar_shop_phone_hotline` | Empty; plain text | top bar, mobile drawer, footer, contact page, SEO schema | `contact.phone`; one canonical setting shared by all bindings. |
| `negar_shop_sticky_header` | `true`; checkbox | No direct template binding was found in this audit | Inventory as legacy-only until its live consumer is confirmed; do not expose a duplicate toggle in Phase 1. |
| `negar_shop_instagram_url`, `negar_shop_telegram_url`, `negar_shop_whatsapp_url`, `negar_shop_linkedin_url` | Empty; `esc_url_raw` | Footer columns/copyright | `social.*`, with the saved `theme_mod` as fallback. |
| `negar_shop_contact_email` | `contact@example.com`; `sanitize_email` | Contact page | `contact.email`. |
| `negar_shop_contact_address` | Configurable placeholder; textarea sanitization | Contact page | `contact.address`. |
| `negar_shop_contact_hours` | Configurable placeholder; plain text | Contact page | `contact.hours`. |
| `negar_shop_contact_map_url` | Empty; `esc_url_raw` | Contact page map area | `contact.map_url`; validation must restrict output to a safe embed use. |
| `negar_shop_footer_about` | Footer description; `wp_kses_post` | Footer columns | `footer.about`; preserve allowed-HTML policy. |
| `negar_shop_copyright_text` | Copyright text; plain text | Footer columns and copyright | `footer.copyright`. |
| Core `custom_logo` / `site_icon` | WordPress core | SEO schema; theme logo currently remains template-owned | Reuse core site icon. Desktop/mobile logo controls must not replace `custom_logo` without an explicit compatibility decision. |

### Safe migration policy

Phase 1 should create a single versioned `negar_theme_options` option with small
groups and an option version. A typed getter should resolve values in this order:

1. an explicitly stored new option value (including an explicit empty value),
2. the mapped legacy theme mod when no new key exists,
3. a code default.

Migration must be idempotent and should record only a migration version, rather
than copying every legacy mod at activation. This avoids premature divergence,
preserves rollback to the Customizer, and prevents a newly introduced default from
overwriting a real saved value. Large story/brand/repeater data and SMS credentials
need separate non-autoloaded options; credentials are never put in the exportable
option.

## Homepage section inventory

Stable IDs below are proposed permanent registry IDs. The “current source” column
describes the observed implementation, not an approval to retain hard-coded
commercial data. “Controls” identifies the next phase that owns the work.

| # / stable ID | Template | Current content and source | Required controls / dependencies |
| --- | --- | --- | --- |
| 01 `stories` | `story-circles.php` | Static story array, images from `uploads/kaveh-demo/stories`, static titles/product labels and shop links | Story repeater: ID, enabled/order, independent thumbnail/main media/video/poster, copy and CTA. Existing viewer JS must move from fixed-duration image logic to media-aware progress. |
| 02 `hero` | `hero-slider.php` | Two static hero slides/media; adjacent deal resolves one published `games` product then any product, with real product price/rating/image | Hero slide repeater and banner media fields; deal product/source setting. Preserve Swiper structure and source WC fields from the selected product. |
| 03 `category_bubbles` | `category-bubbles.php` | Static visual category entries/media | Configurable category cards sourced from actual `product_cat` terms, with media and ordering; retain safe empty state. |
| 04 `flash_deals_primary` | `flash-deals.php` | Live `wc_get_products()` sale-product query (published, limit 8, newest) | Product-section query controls: count, include/exclude categories, valid sort, manually selected IDs. |
| 05 `product_tabs` | `product-tabs.php` | Four tab-specific `WP_Query` product sources with fixed tab behavior and limits | Each tab must receive independent title, categories, count, source and sort controls. |
| 06 `promo_grid_primary` | `banners-grid.php` | Static two-column promotion images/links | Banner repeater entries with attachment IDs, alt, link, title/subtitle/badge/visibility. |
| 07 `smartwatches_primary` | `sec-07-smartwatches-1.php` | Template-defined smartwatch/demo presentation | Replace hard-coded product/commercial fields with real WC product objects; provide section query controls. |
| 08 `promo_wide_primary` | `sec-08-banner-wide.php` | Static wide promotion/media | One banner record. |
| 09 `accessories` | `sec-09-accessories.php` | Published product `WP_Query`, fixed count 9 and newest ordering, plus static presentation content | Standard product section with count/category/source/sort; keep existing carousel dependency. |
| 10 `promo_grid_secondary` | `sec-10-banners-2col.php` | Static two-column banner markup/media | Two banner records. |
| 11 `smartwatch_specs` | `sec-11-smartwatches-spec.php` | Template-defined product/spec presentation | Pack/product assignment controls; product data must be taken from WC, while explicitly authored non-commercial specs remain scoped copy. |
| 12 `promo_compact_primary` | `sec-12-banners-compact.php` | Static compact promotion cards | Two banner records. |
| 13 `taste_grid` | `sec-13-taste-grid.php` | Template-defined demo product grid | Real WC query/selected products and title/subtitle controls; remove static commercial values. |
| 14 `feature_bar_primary` | `sec-14-feature-bar-1.php` | Static product spotlight cards, local Swiper initializer | Product/pack assignments, copy, media and CTA; real product details only. |
| 15 `most_visited_products` | `sec-15-visited-products-2.php` | `WP_Query` ordered by comment count, then an alternate product query | Do not call this “most viewed” without a verified view-count source. Expose only supported query modes. |
| 16 `featured_deal` | `sec-16-apple-watch-deal.php` | Live WC product lookup, currently scans published products then filters | Selected product/source controls; change to bounded WC-supported query and retain true price/stock/link. |
| 17 `flash_deals_secondary` | `sec-17-flash-deals-2.php` | Template-defined promo/product carousel | Real WC products with flash-deal query controls and truthful timer semantics. |
| 18 `promo_grid_tertiary` | `sec-18-banners-2col.php` | Static two-column banner markup/media | Two banner records. |
| 19 `gaming_laptops` | `sec-19-gaming-laptop.php` | Product `WP_Query`, fixed four items | Standard product-section controls and product pack/hotspot controls where present. |
| 20 `brands` | `brands-slider.php` | Hard-coded brand cards/logo assets | Discover real installed brand taxonomy first. Otherwise a managed collection with real product/category association and derived count only. |
| 21 `reviews` | `sec-21-testimonials.php` | `get_comments()` for approved WooCommerce product reviews | No fabricated testimonials. Keep real review query; allow only presentation text/visibility controls if needed. |
| 22 `flash_deals_audio` | `sec-22-flash-deals-audio.php` | Template-defined audio/product carousel | Replace hard-coded commercial fields with live WC product data; add standard product controls. |
| 23 `feature_bar_secondary` | `sec-23-feature-bar-2.php` | Static product spotlight cards | Product/pack assignments, media/copy/CTA; real product fields only. |
| 24 `smartwatches_secondary` | `sec-24-smartwatches-2.php` | Demo arrays matched to products by search keyword, but retains static image, price, rating, comments and discount | Replace all commercial values with selected WC products; preserve layout and add query/selection controls. |
| 25 `gaming_zone` | `sec-25-gaming-zone.php` | Live/selected product-oriented showcase with static visual treatment | Pack and hotspot assignment, authored media/copy/CTA; WC objects remain commercial source. |
| 26 `multi_category_products` | `sec-26-visited-products-3.php` | `WP_Query`, fixed five products and random ordering | Standard product-section controls; do not present it as viewed history without a genuine source. |
| 27 `promo_compact_secondary` | `sec-27-banners-compact-2.php` | Static compact banners | Two banner records. |
| 28 `mobile_spotlight` | `sec-28-mobile-spotlight.php` | Product `WP_Query`, fixed three newest adjacent products plus static focus content | Selected/queried main product and supporting products, copy/media/CTA; preserve mobile grid. |
| 29 `promo_grid_quaternary` | `sec-29-banners-2col.php` | Static two-column banners | Two banner records. |
| 30 `recently_viewed` | `sec-30-recently-viewed.php` | Reads visitor’s recorded product IDs then `wc_get_product()` | Preserve per-visitor history; no admin category/sort replacement. Empty state may have theme-owned copy. |
| 31 `video_reviews` | `sec-31-video-reviews.php` | Truthful static empty/no-approved-video state | Do not create a fabricated review feed. Any future video source needs a verified content model before controls are added. |
| 32 `promo_grid_quinary` | `sec-32-banners-2col.php` | Static two-column banners | Two banner records. |
| 33 `blog_posts` | `blog-posts.php` | Static-looking article card content/media in template | Prefer WordPress post query and real posts before exposing selection/count controls. |
| 34 `feature_bar_tertiary` | `sec-34-feature-bar-3.php` | Static product spotlight cards and local Swiper initializer | Product/pack assignments, media/copy/CTA; replace any demo commercial content. |
| 35 `trust_marquee` | `sec-35-trust-marquee.php` | Static service/trust cards | Theme-owned wording/icon controls only after claims are verified. Never reintroduce unsupported delivery, support or guarantee claims. |

### Shared homepage dependencies

- Swiper is enqueued by `inc/enqueue.php`; several templates also initialise an
  instance inline. Registry reordering must not let duplicate selectors initialise
  unrelated sections.
- Home assets commonly resolve from `wp_upload_dir()['baseurl'] . '/kaveh-demo'`.
  Media controls must store attachment IDs and use WordPress media functions, while
  keeping present external/upload origins as the default fallback.
- Existing templates use Elementor-like structural classes and section-specific CSS.
  Registry changes must preserve each template path, wrapper and asset loading.
- For every product selection, only published, visible WooCommerce products may
  render. Empty explicit selections must remain empty rather than falling back to
  unrelated products.

## Text and non-home inventory

| Area | Source | Current state | Future settings scope |
| --- | --- | --- | --- |
| Header | `top-bar.php`, `main-header.php`, mobile header/drawer/bottom bar | Announcement and hotline have legacy mods; navigation/category data uses live WP/WC terms | Header labels, announcement and theme-owned search/help text. Do not replace menus or product categories with fake settings. |
| Footer | `columns.php`, `copyright.php`, `features.php`, `newsletter.php`, `trust-badges.php` | About/copyright/social/hotline use legacy mods; remaining copy is template-owned | Group editable theme copy by footer component; preserve registered sidebars and live routes. |
| About | `page-templates/page-about.php` | Page title/content is WordPress-managed; supporting guide/cards are template copy | Only supporting theme copy and CTA labels, not `the_content()`. |
| Contact | `page-templates/page-contact.php` | Contact data uses legacy mods; contact-form handler persists actual submissions | Contact hero/support text and present contact fields; do not export submissions. |
| Account/checkout/shop | `woocommerce/`, `template-parts/auth/`, modals | WooCommerce owns product/order/customer state; theme contributes labels and UI | Only theme-generated labels/empty/help text. Do not configure product/order/customer data. |

### Phase 2 implemented text registry

The `texts` group now controls reviewed, plain-text theme copy only. All fields are
listed by `negar_theme_text_registry()` in `inc/theme-settings.php`; the table is a
component ownership index, not a second schema.

| Component | Editable copy | Verified templates | Deliberate exclusions |
| --- | --- | --- | --- |
| Header | Desktop/mobile search placeholders, guest-login/cart labels, top-bar tagline and approved Mega Menu editorial copy | `top-bar.php`, `main-header.php`, `mobile-header.php` | Menus, categories, archive links, product names and user name remain their existing owners. |
| Home | Static hero eyebrow/title/CTA and flash-deal heading copy | `template-parts/home/hero-slider.php` | The featured product, price, rating, stock and link remain live WooCommerce values. |
| Footer | Column headings, phone label, contact descriptions/CTA, social prompt and social-empty copy | `template-parts/footer/columns.php` | Routes, social URLs, copyright and footer-about preserve their existing owners. |
| Contact | Hero/form/map copy, form submit, card labels and map empty-state copy | `page-templates/page-contact.php` | Contact submissions, contact values, required labels and validation/system states are not text settings. |
| About | Supporting hero/guide/empty-state copy and the three information cards | `page-templates/page-about.php` | `the_title()` and `the_content()` remain WordPress-managed; card destinations remain fixed. |
| Not found | Empty-state heading, description, search/action/category prompts | `404.php` | Product category names remain live taxonomy terms. |

The complete mounted homepage and internal-template ownership record is maintained
in [the text coverage matrix](theme-text-coverage-matrix.md). It names every
deferred phase and excludes inactive footer partials rather than creating unused
settings.

## OTP and SMS transport audit

`negar_ajax_send_otp()` normalizes Iranian mobile numbers, checks an AJAX nonce,
applies a 60-second resend cooldown and five requests/hour limit, creates a random
five-digit code, stores only `wp_hash($code)` in a 120-second transient, then calls:

```php
apply_filters( 'negar_dispatch_otp_sms', false, $phone, $code );
```

If no provider accepts the dispatch, it deletes the OTP/cooldown state and returns a
safe failure. The only fallback is an explicitly enabled test transport. The public
success payload contains a message, cooldown and normalized phone; it does not
contain an OTP or mock OTP. Verification limits failed attempts to five and creates
or logs in a WooCommerce customer only after a correct hash comparison.

Phase 9 must retain that call path. Its provider adapter needs a fixed, documented
HTTPS endpoint per supported provider; a separate non-autoloaded secret option;
strict `manage_options` and nonce protection; a redacted diagnostics record; and a
deliberate, rate-limited administrator test send. No SMS provider was identified in
the theme code, so live delivery remains blocked until an owner selects a provider
and supplies valid credentials.

## Phase-0 outcome and next implementation boundary

This audit identifies a safe first implementation boundary:

1. Add the versioned settings service, legacy-read getter and Settings API page.
2. Add the Persian RTL admin tabs with capability, nonce, validation and accessible
   keyboard behavior. The layout tab must include a non-drag keyboard alternative.
3. Bind one small set of legacy-compatible general/header/footer/color controls and
   verify its frontend output before adding repeater or product-query controls.

No frontend behavior, option value, customer data, WooCommerce record, OTP setting
or media record was changed during Phase 0.
