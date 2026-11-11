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

## Planned schema additions

The static tab registry deliberately reserves keys for the following phased
additions: `products`, `banners`, `stories`, `brands`,
`typography`, `sms` and `backup`. Their field schemas must be
documented before they are added; unknown keys must not be accepted by import or
save handlers.
