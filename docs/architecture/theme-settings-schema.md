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
  "colors": { "primary": "#059669", "secondary": "#e11d48" },
  "header": { "announcement": "" },
  "footer": { "about": "", "copyright": "" },
  "contact": { "phone": "", "email": "", "address": "", "hours": "", "map_url": "" },
  "social": { "instagram": "", "telegram": "", "whatsapp": "", "linkedin": "" }
}
```

The Settings API sanitizer merges the submitted group into the stored option, so
one tab cannot remove values stored by another tab. It allows only the groups and
keys listed above. Text is sanitized with the context-specific WordPress function;
URLs use `esc_url_raw`, colors use `sanitize_hex_color`, and footer rich text uses
`wp_kses_post`.

`negar_theme_setting( $group, $key, $default, $legacy_theme_mod )` is the only
frontend read path for this schema. It first respects an explicit v1 value, then
reads the matching legacy `theme_mod`, then returns the supplied default. This is a
non-destructive, reversible compatibility migration: saved Customizer settings
continue rendering without a bulk copy or overwrite.

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

## Planned schema additions

The static tab registry deliberately reserves keys for the following phased
additions: `texts`, `home_layout`, `product_sections`, `banners`, `stories`,
`brands`, `typography`, `sms_public` and `backup`. Their field schemas must be
documented before they are added; unknown keys must not be accepted by import or
save handlers.
