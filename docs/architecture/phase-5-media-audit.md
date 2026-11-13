# Phase 5 media, banner and product-pack audit

**Audit date:** 2026-11-11  
**Scope:** Phase 5 only. The audit covers the mounted non-Elementor home-page
templates. It does not change their frontend output.

## Guardrails

- `front-page.php` renders these templates through the fixed Phase 3 registry only
  when Elementor is not building the front page. Phase 5 must not change the
  Elementor branch, the registry, Swiper selectors, or section markup.
- Existing slide text is already owned by the Phase 2 `texts.home` registry.
  Phase 5 owns slide media and destinations only; it must not create duplicate
  eyebrow, title or CTA fields.
- Default artwork is in `wp_upload_dir()['baseurl'] . '/kaveh-demo'` (or, for the
  final feature bar, a theme asset). It remains the fallback when no valid media
  attachment override is saved.
- Product assignments may resolve only published, catalog-visible WooCommerce
  products. Names, images, prices, discounts, review counts, ratings, stock and
  permalinks come from the resolved product and are never stored as settings.

## Mounted hero and banner records

The stable IDs below are code-owned. Saved records may contain a validated image
attachment ID, safe destination URL, alternative text and enabled state. Records
with visible editorial markup also expose the already present title, subtitle and
CTA label. A record with image-only markup deliberately has no invented text
control.

| Stable ID | Template | Current media fallback | Current destination | Existing text ownership | Controls |
| --- | --- | --- | --- | --- | --- |
| `hero-slide-one` | `template-parts/home/hero-slider.php` | `kaveh-demo/landing-01-300x261-1.png` | WooCommerce shop URL | `texts.home.hero_slide_one_*` | image, link, alt, visibility |
| `hero-slide-two` | `template-parts/home/hero-slider.php` | `kaveh-demo/landing-01-300x261-1.png` | WooCommerce shop URL | `texts.home.hero_slide_two_*` | image, link, alt, visibility |
| `banners-grid-one` | `banners-grid.php` | `banner-01.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |
| `banners-grid-two` | `banners-grid.php` | `banner-02-1.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |
| `wide-banner-one` | `sec-08-banner-wide.php` | `banner-03.png` | shop URL | image-only | image, link, alt, visibility |
| `banners-10-one`, `banners-10-two` | `sec-10-banners-2col.php` | `banner-01.png`, `banner-02-1.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |
| `compact-12-one`, `compact-12-two` | `sec-12-banners-compact.php` | `banner-04.png`, `banner-05.png` | shop URL | image-only | image, link, alt, visibility |
| `banners-18-one`, `banners-18-two` | `sec-18-banners-2col.php` | `banner-01.png`, `banner-02-1.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |
| `compact-27-one`, `compact-27-two` | `sec-27-banners-compact-2.php` | `banner-04.png`, `banner-05.png` | shop URL | image-only | image, link, alt, visibility |
| `banners-29-one`, `banners-29-two` | `sec-29-banners-2col.php` | `banner-01.png`, `banner-02-1.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |
| `banners-32-one`, `banners-32-two` | `sec-32-banners-2col.php` | `banner-01.png`, `banner-02-1.png` | shop URL | inline title/subtitle/CTA | image, link, alt, title, subtitle, CTA, visibility |

## Mounted product packs and hotspot showcase

| Stable component | Pack ID | Template | Slot IDs | Implemented Status |
| --- | --- | --- | --- | --- |
| `gaming-zone` hotspots | `gaming-zone` | `sec-25-gaming-zone.php` | `shadows`, `mirage`, `fc`, `yotei` (4 slots) | Connected to `negar_theme_product_pack_product()`; bounded lookup; coordinates, background and popups intact. |
| `taste-grid` | `taste-grid` | `sec-13-taste-grid.php` | `box-1-1` .. `box-1-4`, `box-2-1` .. `box-2-4`, `box-3-1` .. `box-3-4`, `box-4-1` .. `box-4-4` (16 slots) | Connected to `negar_theme_product_pack_product()`; live WC prices, discounts, ratings, permalinks; real swatches when `pa_color` exists. |
| `feature-bar-one` | `feature-bar-1` | `sec-14-feature-bar-1.php` | `slot-1` .. `slot-4` (4 slots) | Connected to `negar_theme_product_pack_product()`; live WC data; local Swiper preserved. |
| `feature-bar-two` | `feature-bar-2` | `sec-23-feature-bar-2.php` | `slot-1` .. `slot-4` (4 slots) | Connected to `negar_theme_product_pack_product()`; live WC data; runs independently of Bar 1. |
| `smartwatches-two` | `smartwatches-2` | `sec-24-smartwatches-2.php` | `promo-1`, `promo-2`, `carousel-1` .. `carousel-5` (7 slots) | Connected to `negar_theme_product_pack_product()`; live WC prices, review counts, ratings, and cart button data. |
| `mobile-spotlight` | `mobile-spotlight` | `sec-28-mobile-spotlight.php` | `focal` (1 slot) | Connected to `negar_theme_product_pack_product()`; focal card live commerce; 3 adjacent Phase 4 cards untouched. |
| `feature-bar-three` | `feature-bar-3` | `sec-34-feature-bar-3.php` | `slot-1` .. `slot-4` (4 slots) | Connected to `negar_theme_product_pack_product()`; live WC data; local Swiper preserved. |

Total product pack positions: **40 slots across 7 components**.

`sec-11-smartwatches-spec.php` and `sec-16-apple-watch-deal.php` remain outside
this pack assignment boundary: they are product-query/section-specific work, not
an existing ID-addressable pack or hotspot collection. Stories and brands remain
explicitly deferred to Phases 6 and 7 respectively.

## Phase 5 settings model

Phase 5 uses separate non-autoloaded, schema-validated options instead of adding
repeaters to the small `negar_theme_options` v1 option:

- `negar_theme_media_options` for fixed hero/banner records;
- `negar_theme_product_pack_options` for fixed pack slots and hotspot IDs.

Both schemas whitelist code-owned stable record IDs. Missing, malformed, deleted
or non-image attachment IDs fall back to the current demo asset. Unknown records
are discarded. Saving either group cannot rewrite text, layout, product-query or
legacy Customizer groups.

## Server-side rendering & visibility parity

All 17 audited `negar_theme_media_options` records (2 Hero slides plus 15
promotional banners across 8 templates) render image URLs, `alt` attributes,
safe destinations and supported editorial copy directly from PHP on the
non-Elementor front page:

- **Single wide banner (`wide-banner-one`):** omits the section wrapper and
  `<img>` element when disabled.
- **Two-column editorial & compact asymmetric pairs (`banners-grid`, `sec-10`,
  `sec-12`, `sec-18`, `sec-27`, `sec-29`, `sec-32`):** omit disabled cards/images
  cleanly, collapse single-active pairs to one responsive column without leaving
  an empty track, and omit the outer group container when both slots are
  disabled. Intentional empty editorial strings (`title`, `subtitle`) are
  preserved without falling back to stale demo copy, while image-only banners
  (`wide-banner-one`, `compact-12-*`, `compact-27-*`) render no text elements.

## Gaming Zone product lookup optimization (Phase 5.1-C)

- **Previous query behavior:** `sec-25-gaming-zone.php` previously invoked
  `wc_get_products( array( 'limit' => -1, 'status' => 'publish', 'visibility' => 'catalog', 'return' => 'objects' ) )`,
  loading the entire WooCommerce catalog into memory on every homepage load,
  then iterating in PHP to match title fragments.
- **New bounded lookup strategy:**
  1. *Direct ID resolution:* `negar_theme_gaming_zone_product()` first checks
     `negar_theme_product_pack_product_id( 'gaming-zone', $slot_key )`. If a valid,
     published and catalog-visible product is assigned, it resolves directly via
     `wc_get_product( $id )` with zero fallback database queries.
  2. *Bounded fallback search:* Only unassigned or invalid/hidden slots execute
     `negar_theme_find_product_by_title_needles()`, which issues a single targeted
     query (`posts_per_page => 1`, `fields => 'ids'`, `no_found_rows => true`,
     `orderby => 'date'`, `order => 'DESC'`) with a `post_title LIKE` filter and
     the standard `negar_theme_catalog_visibility_query_args()` exclusion.
  3. *Legacy intent preservation:* The 4 stable hotspot IDs (`shadows`, `mirage`,
     `fc`, `yotei`) and their needle sets (`['Assassin', 'Shadows']`,
     `['Assassin', 'Mirage']`, `['EA Sports FC']`, `['Ghost of Yotei', 'Yotei']`)
     are preserved. The newest published, visible product matching any needle
     is selected.
  4. *Safe empty state:* Unmatched slots return `false`, rendering `.is-empty` on
     the hotspot and suppressing popup boxes, purchase links and prices.
  5. *Request memoization:* Repeated lookups for the same needle sets within a
     single request are memoized in memory.
- **Known limitations:** While bounded to `LIMIT 1`, fallback title searching
  uses SQL `LIKE '%needle%'`. For stores with extensive catalogs, explicitly
  assigning products via Theme Settings › Banners & Packs completely bypasses
  fallback queries and executes in O(1) post lookup time.
