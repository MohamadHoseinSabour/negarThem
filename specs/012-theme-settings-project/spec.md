# Feature Specification: 012 Complete Theme Settings Project

**Feature code:** `SETTINGS-01`
**Status:** Phase 4 WooCommerce product-section management complete; later phases planned
**Supersedes for new work:** None. `009-featurename-theme-options` remains the
historical Customizer-and-widgets specification and is not this project.

## Goal

Provide one secure, maintainable NegarThem settings experience that can configure
approved presentation content while preserving the Persian RTL storefront,
WooCommerce as the source of commercial truth, and the existing Customizer as a
non-destructive compatibility source.

## Current baseline

Phase 1 provides the `negar_theme_options` v1 option, a Settings > Negar Theme
Settings route protected by `manage_options`, and implemented General, Colors and
Header/Footer/Contact tabs. Phase 1.1 hardens color tokens and persistence. Phase
2 adds a strict, component-owned Texts group for selected header, homepage, footer,
contact, about and not-found copy. Phase 2.1 completes the reviewed header Mega
Menu, active footer, About-card and Contact-card coverage, and records all other
template ownership in the coverage matrix. Phase 3 adds the fixed 35-section
homepage registry, independent visibility/order controls and an RTL accessible
admin interface. This spec plans the complete project; it does not authorize the
remaining phases yet. Phase 4 adds tightly scoped query controls for existing
real-product homepage sections only.

## Functional requirements

1. Settings access and writes require `manage_options`, a WordPress Settings API
   nonce, contextual sanitization and output escaping.
2. Every implemented tab saves only its own group without removing other groups;
   explicit empty values remain distinguishable from absent values.
3. Existing Customizer `theme_mod` values remain frontend fallbacks until an
   explicit v1 replacement is stored. No activation-time bulk migration may
   overwrite saved values.
4. The Colors tab must render saved valid primary and secondary colors through the
   documented CSS tokens. The canonical no-override appearance is crimson:
   `#e90544` primary and `#c40439` secondary.
5. The Texts group may own only documented theme-generated labels, headings,
   descriptions and calls to action. WordPress page content, menus and
   WooCommerce product, review, customer and transactional data are excluded.
6. A coverage matrix must identify every mounted homepage section and internal
   template as handled, deferred with its future owner, or WordPress/WooCommerce/
   system managed before a later phase adds writable controls.
7. The Layout group may save only registered homepage IDs and their visibility;
   template paths remain code-owned. It must preserve the legacy order by default,
   repair invalid/missing/duplicated IDs safely, keep the Elementor branch intact,
   and provide RTL drag-and-drop plus keyboard move controls.
8. Future product-section, banner, story, brand, typography, SMS and backup/import
   controls must have an approved schema and regression tests before becoming
   writable.
9. Product-section controls may select only published WooCommerce products and
   existing `product_cat` terms. They may configure count, supported sorting,
   category include/exclude filters and manual product IDs, while prices, images,
   ratings, stock and permalinks remain properties of the resolved live products.
8. Product prices, stock, sale status, ratings, reviews, orders, customers and OTP
   states must remain live WordPress/WooCommerce or provider data. Settings may
   select presentation/query inputs, never invent transactional or commercial data.

## Non-goals

- Replacing or removing the legacy Customizer in this project phase.
- Implementing Phase 3 layout or later features as part of Phase 2.
- Saving provider secrets in the exportable settings option or exposing them to
  JavaScript.

## Acceptance criteria

- Unauthorized visitors cannot open or persist settings.
- Each enabled tab persists after reload and preserves all other settings groups.
- Empty values, legacy Customizer values and valid saved colors have defined,
  tested behavior.
- Changing a valid color changes the computed frontend tokens after reload; the
  original values are restored after automated checks.
- An authenticated administrator can submit the Texts form, reload it and observe
  its component-owned values on representative frontend templates; test cleanup
  restores the initial option and Customizer snapshots.
- The coverage matrix documents all 35 mounted homepage sections and each internal
  template boundary without exposing managed data or unapproved navigation.
- The authenticated layout form renders 35 registered sections, supports drag and
  keyboard movement, persists order/visibility, and the non-Elementor homepage
  renders only the enabled fixed-registry templates in saved order.
- An authenticated administrator can submit product-query settings, reload them,
  and observe the selected real product and its permalink on desktop and mobile;
  malformed IDs and non-published products are rejected and tests restore the
  initial settings snapshot.
- Documentation keys exactly match implementation and the dedicated spec, plan and
  task list remain separate from specification 009.
