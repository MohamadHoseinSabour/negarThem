# Homepage layout registry

**Phase:** 3
**Source of truth:** `negar_theme_home_section_registry()` in
`inc/theme-settings.php`

The Layout option stores only the stable IDs below and an `enabled` boolean. The
template path remains this code-owned registry value, so saved settings never load
an arbitrary template. The registry order is the exact legacy non-Elementor
homepage order.

| # | Stable ID | Fixed template part |
| --- | --- | --- |
| 01 | `story-circles` | `template-parts/home/story-circles` |
| 02 | `hero-slider` | `template-parts/home/hero-slider` |
| 03 | `category-bubbles` | `template-parts/home/category-bubbles` |
| 04 | `flash-deals` | `template-parts/home/flash-deals` |
| 05 | `product-tabs` | `template-parts/home/product-tabs` |
| 06 | `banners-grid` | `template-parts/home/banners-grid` |
| 07 | `sec-07-smartwatches-1` | `template-parts/home/sec-07-smartwatches-1` |
| 08 | `sec-08-banner-wide` | `template-parts/home/sec-08-banner-wide` |
| 09 | `sec-09-accessories` | `template-parts/home/sec-09-accessories` |
| 10 | `sec-10-banners-2col` | `template-parts/home/sec-10-banners-2col` |
| 11 | `sec-11-smartwatches-spec` | `template-parts/home/sec-11-smartwatches-spec` |
| 12 | `sec-12-banners-compact` | `template-parts/home/sec-12-banners-compact` |
| 13 | `sec-13-taste-grid` | `template-parts/home/sec-13-taste-grid` |
| 14 | `sec-14-feature-bar-1` | `template-parts/home/sec-14-feature-bar-1` |
| 15 | `sec-15-visited-products-2` | `template-parts/home/sec-15-visited-products-2` |
| 16 | `sec-16-apple-watch-deal` | `template-parts/home/sec-16-apple-watch-deal` |
| 17 | `sec-17-flash-deals-2` | `template-parts/home/sec-17-flash-deals-2` |
| 18 | `sec-18-banners-2col` | `template-parts/home/sec-18-banners-2col` |
| 19 | `sec-19-gaming-laptop` | `template-parts/home/sec-19-gaming-laptop` |
| 20 | `brands-slider` | `template-parts/home/brands-slider` |
| 21 | `sec-21-testimonials` | `template-parts/home/sec-21-testimonials` |
| 22 | `sec-22-flash-deals-audio` | `template-parts/home/sec-22-flash-deals-audio` |
| 23 | `sec-23-feature-bar-2` | `template-parts/home/sec-23-feature-bar-2` |
| 24 | `sec-24-smartwatches-2` | `template-parts/home/sec-24-smartwatches-2` |
| 25 | `sec-25-gaming-zone` | `template-parts/home/sec-25-gaming-zone` |
| 26 | `sec-26-visited-products-3` | `template-parts/home/sec-26-visited-products-3` |
| 27 | `sec-27-banners-compact-2` | `template-parts/home/sec-27-banners-compact-2` |
| 28 | `sec-28-mobile-spotlight` | `template-parts/home/sec-28-mobile-spotlight` |
| 29 | `sec-29-banners-2col` | `template-parts/home/sec-29-banners-2col` |
| 30 | `sec-30-recently-viewed` | `template-parts/home/sec-30-recently-viewed` |
| 31 | `sec-31-video-reviews` | `template-parts/home/sec-31-video-reviews` |
| 32 | `sec-32-banners-2col` | `template-parts/home/sec-32-banners-2col` |
| 33 | `blog-posts` | `template-parts/home/blog-posts` |
| 34 | `sec-34-feature-bar-3` | `template-parts/home/sec-34-feature-bar-3` |
| 35 | `sec-35-trust-marquee` | `template-parts/home/sec-35-trust-marquee` |

`front-page.php` invokes the registry only if the page is not built with Elementor.
No template paths, data queries or section-specific settings are submitted by the
admin form.
