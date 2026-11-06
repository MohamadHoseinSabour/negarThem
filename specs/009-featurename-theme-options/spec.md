# Feature Specification: 009 Theme Options & Customizer

**Feature Code**: `OPT-01`, `OPT-02`  
**Status**: Ready for Execution  

## 1. User Stories & Goals
- **US-OPT-01 (Theme Customizer Panels)**: As an eCommerce store administrator, I want to customize core store settings (Primary brand colors, Announcement bar text, Phone hotline, Social media links, Footer text, and Copyright notice) directly via the native WordPress Theme Customizer (`Appearance > Customize`), with real-time preview and safe sanitization callbacks.
- **US-OPT-02 (Widget Areas & Menus)**: As a site administrator, I want standard widget areas (Shop Sidebar, Blog Sidebar, Footer Columns 1-4) properly registered via `widgets_init`, allowing me to place WordPress widgets, filters, or custom HTML blocks dynamically.

## 2. Requirements & Acceptance Criteria
- **REQ-OPT-01**: Create `inc/customizer.php` registering a master panel or distinct sections in WordPress Customizer:
  - **Colors Section**: Primary Color (`negar_shop_primary_color`), Secondary/Accent Color (`negar_shop_accent_color`). Generates inline CSS for `:root` custom properties (`--negar-primary`, etc.).
  - **Header Section**: Top bar announcement text (`negar_shop_topbar_announcement`), Hotline phone number (`negar_shop_phone_hotline`).
  - **Social Links Section**: Instagram URL (`negar_shop_instagram_url`), Telegram URL (`negar_shop_telegram_url`), WhatsApp URL (`negar_shop_whatsapp_url`), LinkedIn URL (`negar_shop_linkedin_url`).
  - **Footer Section**: Footer about description (`negar_shop_footer_about`), Footer copyright text (`negar_shop_copyright_text`).
- **REQ-OPT-02**: All Customizer settings must implement strict `sanitize_callback` methods (`sanitize_hex_color`, `sanitize_text_field`, `esc_url_raw`, `wp_kses_post`).
- **REQ-OPT-03**: Register widget sidebars in `inc/setup.php` via `widgets_init`:
  - `sidebar-shop`: "سایدبار فروشگاه و آرشیو محصولات"
  - `sidebar-blog`: "سایدبار وبلاگ و مقالات"
  - `footer-1`, `footer-2`, `footer-3`, `footer-4`: ستون‌های ابزارک فوتر
- **REQ-OPT-04**: Verification via WP-CLI:
  - `wp option get ...` or `wp theme mod list` verifying theme mods.
  - `wp sidebar list` verifying registered sidebars.
  - Playwright screenshot of Customizer settings reflected on the frontend.
