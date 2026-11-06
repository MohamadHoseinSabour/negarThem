# Feature Implementation Plan: 011 Hardening, Packaging & Delivery

## 1. Architecture & Files
```text
negarThem/
├── inc/
│   └── seo-schema.php          # JSON-LD structured data (WebSite, Store, Breadcrumbs, OpenGraph)
├── languages/
│   ├── negar-shop.pot          # POT template catalog for all gettext strings
│   ├── fa_IR.po                # Persian translation catalog
│   └── fa_IR.mo                # Compiled binary gettext catalog
├── README.md                   # Complete documentation (installation, hooks, customizer, demo import)
├── dist/
│   └── negar-shop.zip          # Production-ready installable theme archive
└── specs/
    └── 011-featurename-hardening-and-packaging/
        ├── spec.md
        ├── plan.md
        └── tasks.md
```

## 2. Technical Decisions
- **Structured Data & SEO**:
  - `inc/seo-schema.php` generates clean JSON-LD for `@type`: `WebSite` with SearchAction target, and `@type`: `OnlineStore` with name, url, logo, and contactPoint.
- **Localization**:
  - Scan codebase using WP-CLI `wp i18n make-pot . languages/negar-shop.pot` (or generate standard POT).
  - Compile `fa_IR.mo` from `fa_IR.po` using `wp i18n make-mo` or PHP gettext script.
- **Packaging**:
  - PowerShell script creating `dist/negar-shop.zip` containing all essential theme files and templates under the `negar-shop/` folder.
