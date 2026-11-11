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

| Stable component | Template | Current association | Phase 5 control boundary |
| --- | --- | --- | --- |
| `gaming-zone` hotspots | `sec-25-gaming-zone.php` | Four visual hotspots (`fc`, `two`, `yotei`, `gta`) find a product by demo-name fragments. `product-pack.png` is the background and the coordinates/classes are authored CSS. | Four stable product-ID selectors; keep coordinates, background, popup design and mobile interaction intact. |
| `taste-grid` | `sec-13-taste-grid.php` | Four cards contain demo product arrays and attempt name matching. | Stable per-card product IDs; render every commercial field from WooCommerce. |
| `feature-bar-one` | `sec-14-feature-bar-1.php` | Four demo cards attempt name matching. | Stable product-ID list; preserve local Swiper and visual art. |
| `feature-bar-two` | `sec-23-feature-bar-2.php` | Four demo cards attempt name matching. | Stable product-ID list; preserve local Swiper and visual art. |
| `smartwatches-two` | `sec-24-smartwatches-2.php` | Demo primary/discount arrays attempt name matching and retain demo commerce values. | Stable product-ID lists; resolve commerce only from WooCommerce. |
| `mobile-spotlight` | `sec-28-mobile-spotlight.php` | Focus panel is a static Galaxy S25 FE promotion; only adjacent cards were Phase 4 queries. | One focal-product selector; associated product image/link/commerce becomes live, while its approved existing visual copy/layout stays intact. |
| `feature-bar-three` | `sec-34-feature-bar-3.php` | Four demo cards attempt name matching. | Stable product-ID list; preserve local Swiper and visual art. |

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
