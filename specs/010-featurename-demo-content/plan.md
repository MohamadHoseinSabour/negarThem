# Feature Implementation Plan: 010 Demo Content & Safe Reset

## 1. Architecture & Files
```text
negarThem/
├── backups/
│   └── pre-demo-backup.sql     # Full SQL backup of wp4 database prior to demo setup
├── inc/
│   └── demo-importer.php       # Idempotent demo content generator (Pages, Categories, Products, Menus, Options)
└── specs/
    └── 010-featurename-demo-content/
        ├── spec.md
        ├── plan.md
        └── tasks.md
```

## 2. Technical Decisions
- **Idempotency Strategy**:
  - Checks if a category exists by slug before calling `wp_insert_term()`.
  - Checks if a product exists by slug/title before calling `wp_insert_post()`.
  - Checks if a menu exists by name before calling `wp_create_nav_menu()`.
  - Maps menu locations to `get_theme_mod( 'nav_menu_locations' )`.
- **WooCommerce Integration**:
  - Uses `WC_Product_Simple` class to properly set price, regular price, sale price, stock status, and manage stock flags.
  - Automatically handles currency settings and page assignments.
