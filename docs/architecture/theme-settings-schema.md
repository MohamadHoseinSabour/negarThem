# Theme settings schema

## Version 1

The option name is `negar_theme_options`. It is a versioned array intended for
small, exportable theme configuration. A missing key means “use the legacy
Customizer fallback or code default”; a present key with an empty string means
“intentionally empty”.

```json
{
  "schema_version": 1,
  "branding": { "site_label": "" },
  "texts": {
    "header": { "desktop_search_placeholder": "", "mega_sale_title": "" },
    "home": { "hero_slide_one_title": "" },
    "footer": { "quick_links_heading": "", "contact_card_description": "" },
    "contact": { "hero_title": "", "phone_card_label": "" },
    "about": { "hero_eyebrow": "", "products_card_title": "" },
    "not_found": { "title": "" }
  },
  "layout": {
    "sections": [
      { "id": "story-circles", "enabled": true },
      { "id": "hero-slider", "enabled": true }
    ]
  },
  "products": {
    "sections": {
      "accessories": {
        "mode": "custom",
        "count": 9,
        "sort": "date_desc",
        "include_categories": [],
        "exclude_categories": [],
        "product_ids": []
      }
    }
  },
  "colors": { "primary": "#e90544", "secondary": "#c40439" },
  "header": { "announcement": "" },
  "footer": { "about": "", "copyright": "" },
  "contact": { "phone": "", "email": "", "address": "", "hours": "", "map_url": "" },
  "social": { "instagram": "", "telegram": "", "whatsapp": "", "linkedin": "" }
}
```

The Settings API sanitizer merges the submitted group into the stored option, so
one tab cannot remove values stored by another tab. It allows only the groups and
keys listed above. The Texts group is a whitelist registry: its documented header,
home, footer, contact, about and not-found fields use `sanitize_text_field()` or
`sanitize_textarea_field()` and preserve an explicitly submitted empty string.
URLs use `esc_url_raw`, colors use `sanitize_hex_color`, and footer rich text uses
`wp_kses_post`.

`negar_theme_setting( $group, $key, $default, $legacy_theme_mod )` is the only
frontend read path for this schema. It first respects an explicit v1 value, then
reads the matching legacy `theme_mod`, then returns the supplied default. This is a
non-destructive, reversible compatibility migration: saved Customizer settings
continue rendering without a bulk copy or overwrite.

Colors use `negar_theme_color( $key )` on top of that getter. Its canonical
fallbacks are the existing storefront crimson `#e90544` (primary) and `#c40439`
(secondary), while *any* valid saved value remains effective, including the former
Customizer defaults `#059669` and `#e11d48`. An explicit empty color is preserved
in storage and resolves to the canonical crimson fallback at render time.

## Storage boundaries

- The v1 option is suitable for small general, text, display, layout and exportable
  configuration.
- Later large repeater collections (stories, brands and complex banner data) are
  stored in non-autoloaded, schema-validated options rather than expanding the
  autoloaded foundation indefinitely.
- SMS credentials are never stored in this option, never exposed to JavaScript and
  never exported. A future provider adapter uses a separate non-autoloaded secret
  option or server configuration constant.

## Admin route and authorization

The screen is registered through `add_options_page()` and is available at:

`/wp-admin/options-general.php?page=negar-theme-settings`

It requires `manage_options`. The Settings API nonce protects writes and the
sanitizer repeats the capability check. Values are escaped in their output context.

## Implemented text schema

`negar_theme_text( $component, $key )` is the only frontend read path for the
Texts registry. It returns a stored value when its key is present (including an
empty value), otherwise the reviewed template default. The registry intentionally
contains no rich HTML, WordPress `the_content()`, menu labels, taxonomy names or
WooCommerce product/customer/order/review data.

The authoritative component/field list is code, while
[`theme-text-coverage-matrix.md`](theme-text-coverage-matrix.md) records every
mounted homepage and internal-template ownership boundary. It prevents future
phases from turning unmounted partials, approved navigation or managed data into
dead or duplicate settings.

## Implemented layout schema

`layout.sections` is an ordered array of `{ id, enabled }` rows. IDs are matched
only against `negar_theme_home_section_registry()`; the registry owns all 35
template paths in their original `front-page.php` order. The option therefore
never stores or supplies a PHP template path. Unknown and duplicate IDs are
dropped, while missing current IDs are appended as enabled, allowing a future
registered section to render safely before it is explicitly arranged.

`negar_theme_render_home_sections()` renders only enabled entries and only from
that registry. It runs solely in the existing non-Elementor branch, so an
Elementor-built homepage remains untouched. Layout does not alter section queries,
banners, stories, product data or Swiper initialization.

## Implemented product-section schema

Phase 4.1 stores an active configuration with `mode: "custom"`; no entry means
that section uses its exact legacy query. Existing Phase 4 entries without a mode
remain active for compatibility. Saving one section never writes another, and
disabling that section's explicit custom-state control removes only its entry and
restores its legacy behavior.

The supported sort tokens are newest, oldest, name ascending/descending,
best-selling (`total_sales`), highest-rated (`_wc_average_rating`), price
ascending/descending (`_price`), most reviewed and random. Every managed query
also excludes WooCommerce's `exclude-from-catalog` visibility term; manual IDs
must resolve to published, catalog-visible `WC_Product` objects.

The admin selector does not preload the catalog. It fetches at most 20 matching
products through a nonce-protected `manage_options` Ajax request, while rendering
the previously saved IDs separately so they remain visible after reopening.

`products.sections` is a whitelist keyed only by existing homepage product-query
components. Each entry stores a bounded count (1–12), one supported sort token,
existing `product_cat` include/exclude term IDs, and up to 12 published,
catalog-visible product IDs. The sanitizer verifies every ID against WordPress
before storage, so it cannot create products or surface drafts/private/hidden
products. Manual IDs and category filters
only choose the query candidates; each template still resolves price, image,
rating, stock and permalink from the live `WC_Product` object.

No product configuration is read until the Products tab has been explicitly saved.
This preserves every legacy query—including its fallbacks and sale-only behavior—by
default. Product settings are deliberately excluded from stories, banners, review
feeds, visitor-specific recently viewed products and demo/pack components.

## Implemented product-pack schema (Phase 5)

Fixed product packs and hotspot showcases are stored under the non-autoloaded option
`negar_theme_product_pack_options` (`NEGAR_THEME_PRODUCT_PACK_OPTION`). The schema
whitelists seven code-owned components and their fixed slot identifiers:

```json
{
  "schema_version": 1,
  "packs": {
    "gaming-zone": {
      "shadows": 0,
      "mirage": 0,
      "fc": 0,
      "yotei": 0
    },
    "taste-grid": {
      "box-1-1": 0, "box-1-2": 0, "box-1-3": 0, "box-1-4": 0,
      "box-2-1": 0, "box-2-2": 0, "box-2-3": 0, "box-2-4": 0,
      "box-3-1": 0, "box-3-2": 0, "box-3-3": 0, "box-3-4": 0,
      "box-4-1": 0, "box-4-2": 0, "box-4-3": 0, "box-4-4": 0
    },
    "feature-bar-1": {
      "slot-1": 0, "slot-2": 0, "slot-3": 0, "slot-4": 0
    },
    "feature-bar-2": {
      "slot-1": 0, "slot-2": 0, "slot-3": 0, "slot-4": 0
    },
    "smartwatches-2": {
      "promo-1": 0, "promo-2": 0,
      "carousel-1": 0, "carousel-2": 0, "carousel-3": 0, "carousel-4": 0, "carousel-5": 0
    },
    "mobile-spotlight": {
      "focal": 0
    },
    "feature-bar-3": {
      "slot-1": 0, "slot-2": 0, "slot-3": 0, "slot-4": 0
    }
  }
}
```

- **Validation & isolation:** Only registered pack and slot IDs are accepted. Product
  IDs are validated via `negar_theme_sanitize_product_ids()`, requiring published and
  catalog-visible WooCommerce products. Unassigned or cleared slots store `0`. Saving
  one pack preserves other packs.
- **Resolution priority:** `negar_theme_product_pack_product( $pack_id, $slot_id, $needles )`
  resolves explicitly assigned products with $O(1)$ efficiency. If unassigned or invalid,
  it falls back to bounded needle lookups via `negar_theme_find_product_by_title_needles()`.
  The `posts_where` filter in `negar_theme_find_product_by_title_needles()` is safely enclosed
  in a `try ... finally` block, ensuring filter detachment even upon unexpected failures.
- **Authentic commerce & truthful empty states:** When matched, each template renders real
  titles, images, prices, discounts, ratings, review counts, and attribute swatches directly
  from the `WC_Product` object.
  - *Missing products:* When a product cannot be resolved, templates render a truthful,
    non-commercial empty state (`.is-empty`, standard WooCommerce placeholder image via
    `wc_placeholder_img_src()`, localized neutral labels `محصولی برای نمایش موجود نیست` and `ناموجود`).
    No fabricated prices, strike-through amounts, fake discounts, star ratings, review counts,
    or invalid add-to-cart controls are ever rendered.
  - *Image fidelity:* Real products lacking a featured image display the standard WooCommerce
    placeholder rather than unrelated demonstration photos.
  - *Description neutrality:* In `sec-28-mobile-spotlight`, real products lacking a short
    description render neutral store copy rather than hardcoded Samsung Galaxy S25 FE specifications.
  - *Color swatch validation:* Color attributes (`pa_color`) strictly validate term hex metadata
    (`preg_match('/^#([a-f0-9]{3}){1,2}$/i', $hex)`); invalid or missing values never fall back to
    an invented `#808080` swatch.
  - *Action safety:* In `sec-24-smartwatches-2`, variable products link directly to the product permalink
    for variation selection; purchasable simple products emit verified product IDs; empty slots suppress
    all add-to-cart buttons.

## Planned schema additions

The static tab registry deliberately reserves keys for the following phased
additions: `stories`, `brands`, `sms` and `backup`. Their field schemas must be
documented before they are added; unknown keys must not be accepted by import or
save handlers.
