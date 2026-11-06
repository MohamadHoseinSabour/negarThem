# Feature Implementation Plan: 009 Theme Options & Customizer

## 1. Architecture & Files
```text
negarThem/
├── inc/
│   ├── customizer.php          # WordPress Theme Customizer panels, sections, controls, and inline CSS injector
│   └── setup.php               # Register widgets_init sidebars (sidebar-shop, sidebar-blog, footer-1 to 4)
├── functions.php               # require_once inc/customizer.php
├── template-parts/
│   ├── header/top-bar.php      # Read get_theme_mod for announcement and phone
│   ├── footer/columns.php      # Read get_theme_mod for footer about and dynamic sidebars
│   └── footer/copyright.php    # Read get_theme_mod for copyright & social URLs
└── specs/
    └── 009-featurename-theme-options/
        ├── spec.md
        ├── plan.md
        └── tasks.md
```

## 2. Technical Decisions
- **Customizer API (`WP_Customize_Manager`)**:
  - Panel: `negar_shop_panel` ("تنظیمات قالب نگار شاپ")
  - Sections:
    1. `negar_shop_section_colors` ("رنگ‌بندی فروشگاه")
    2. `negar_shop_section_header` ("تنظیمات هدر و نوار بالا")
    3. `negar_shop_section_social` ("شبکه‌های اجتماعی")
    4. `negar_shop_section_footer` ("تنظیمات فوتر و کپی‌رایت")
- **Inline Custom CSS Hook**:
  - `negar_shop_customizer_css()` hooked to `wp_head` prints custom CSS overrides for `:root` when colors are set, enabling instant customization without editing files.
- **Sidebars Registration**:
  - `negar_shop_widgets_init()` hooked to `widgets_init` registers `sidebar-shop`, `sidebar-blog`, `footer-1`, `footer-2`, `footer-3`, `footer-4` with standard `<div class="widget %2$s">` wrappers.
