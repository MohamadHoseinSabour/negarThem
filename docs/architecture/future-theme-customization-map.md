# NegarThem — Future Theme Customization Architecture Map

## Overview
This architectural reference document maps all frontend components stabilized during the V3 frontend pass to their designated future WordPress customization controls (Customizer / Redux / Kirki / Block Patterns / Settings API).

When developing the upcoming WordPress Admin customization module, refer to this exact specification for option names, data types, default values, sanitization callbacks, and target DOM bindings.

---

## 1. Global & Header Configuration

### 1.1 Brand Identity & Logos
| Component | Setting Key | Control Type | Default Value | Target Selector / PHP Location |
| :--- | :--- | :--- | :--- | :--- |
| Desktop Main Logo | `negar_opt_logo_desktop` | Media / Image Upload | `assets/images/logo.svg` | `template-parts/header/main-header.php` (`.logo-box img`) |
| Mobile Header Logo | `negar_opt_logo_mobile` | Media / Image Upload | `assets/images/logo.svg` | `template-parts/header/mobile-header.php` (`.mobile-logo img`) |
| Logo Width (Desktop) | `negar_opt_logo_width_desktop`| Number / Range (px) | `140` (min: 80, max: 240) | Inline CSS on desktop `<img>` |
| Logo Height (Mobile) | `negar_opt_logo_height_mobile`| Number / Range (px) | `32` (fixed constraint: 32px)| Inline CSS on mobile `<img>` |
| Site Favicon | `site_icon` | Core WP Site Icon | Empty | `<head>` wp_site_icon |

### 1.2 Top Announcement Bar
| Component | Setting Key | Control Type | Default Value | Target Selector / PHP Location |
| :--- | :--- | :--- | :--- | :--- |
| Show/Hide Top Bar | `negar_opt_topbar_enable` | Toggle / Switch | `true` | `template-parts/header/top-bar.php` |
| Announcement Text | `negar_opt_topbar_text` | Text / Rich Text | Persian promo message | `.negar-topbar-banner span` |
| Link URL | `negar_opt_topbar_link` | URL | `#` | `.negar-topbar-banner a` |
| Background Color | `negar_opt_topbar_bg` | Color Picker | `#b80036` (Brand deep red) | CSS var `--color-brand-deep-red` |
| Text Color | `negar_opt_topbar_color` | Color Picker | `#ffffff` | Inline style or CSS var |

### 1.3 Header Action Buttons & Search
| Component | Setting Key | Control Type | Default Value | Target Selector / PHP Location |
| :--- | :--- | :--- | :--- | :--- |
| Search Placeholder | `negar_opt_search_placeholder`| Text | `جستجوی نام کالا یا برند...` | `#negar-live-search-input` placeholder |
| Header Cart Badge | `negar_opt_cart_badge_enable` | Toggle / Switch | `true` | `.negar-cart-count` |
| Auth Modal Style | `negar_opt_auth_modal_mode` | Radio | `otp` (Options: `otp`, `password`, `direct_wc`) | Modal auth trigger and script handler |

---

## 2. Homepage Hero Slider

### 2.1 Slider Dimensions & Responsive Settings
| Setting Key | Type | Default | Description & Constraint |
| :--- | :--- | :--- | :--- |
| `negar_opt_hero_autoplay` | Toggle | `true` | Enable Swiper autoplay |
| `negar_opt_hero_autoplay_speed` | Number (ms) | `5000` | Delay between slide transitions |
| `negar_opt_hero_radius_desktop` | Number (px) | `24` | Border radius on desktop container |
| `negar_opt_hero_radius_mobile` | Number (px) | `20` | Border radius on mobile viewport (< 640px) |
| `negar_opt_hero_primary_bg` | Color Picker | `#e90544` | Canonical brand red gradient start |
| `negar_opt_hero_secondary_bg` | Color Picker | `#c40439` | Gradient end tone |

### 2.2 Slide Item Repeater Schema
Each slide in the hero slider repeater must follow this structured schema:
```json
{
  "slide_id": "string",
  "badge_text": "string (e.g., 'کنسول‌های بازی نسل نهم')",
  "title": "string (e.g., 'تجربه بی‌نظیر گیمینگ')",
  "button_text": "string (e.g., 'مشاهده بیشتر')",
  "button_link": "url",
  "image_url": "url (transparent PNG/WebP)",
  "image_alt": "string",
  "background_art": "url (optional SVG overlay / pattern)"
}
```
- **Fallback Rule**: If image is missing or delayed, the title and CTA button must remain visible with minimum dimensions (no `scale(0)`).
- **Navigation Controls**: Desktop Swiper crescent notches are anchored inside `.landing-nk__carousel` at `right: 8px` and `left: 4px`.

---

## 3. Flash Deals & Product Carousels

### 3.1 Flash Deal Widget (Adjacent Hero Banner)
| Component | Setting Key | Control Type | Default Value | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| Target Product | `negar_opt_flash_deal_product_id` | Post Select (WooCommerce Product) | Latest on-sale item | Renders countdown, discount percentage, pricing |
| Badge Label | `negar_opt_flash_deal_label` | Text | `تخفیفات لحظه‌ای` | Ribbon badge in top-left corner |
| Countdown End Time | `negar_opt_flash_deal_expiry` | DateTime Picker | Midnight today | Drives JS countdown timer |

### 3.2 Product Grids & Carousels
| Component | Setting Key | Control Type | Default Value | Options |
| :--- | :--- | :--- | :--- | :--- |
| Accessories Section Title | `negar_opt_accessories_title` | Text | `لوازم جانبی برگزیده` | Heading label |
| Items Per View (Desktop) | `negar_opt_carousel_items_desktop`| Number | `4` | 3, 4, 5, 6 |
| Items Per View (Mobile) | `negar_opt_carousel_items_mobile` | Number | `1.2` | 1, 1.2, 1.5, 2 |
| Card Style | `negar_opt_product_card_style` | Select | `kaveh_soft_card` | `kaveh_soft_card`, `bordered`, `minimal` |

---

## 4. Mobile Bottom Navigation (Floating Island)

### 4.1 Strict Constraints (Established in V3)
1. **Item Count**: Exactly 3 items (Home, Wishlist, Profile/Login).
2. **Excluded Elements**: No Categories action, no Cart action.
3. **Badges**: Zero count badges or red dots on mobile bottom items.
4. **Conditional Visibility**: Hidden on Single Product pages (`is_product()`) to prioritize primary sticky purchase actions.

### 4.2 Control Schema
| Setting Key | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `negar_opt_bottom_nav_enable` | Toggle | `true` | Master switch for floating mobile bar |
| `negar_opt_bottom_nav_home_label` | Text | `خانه` | Label for Home tab |
| `negar_opt_bottom_nav_home_icon` | Icon Select | `home-icon` | SVG icon identifier |
| `negar_opt_bottom_nav_wishlist_label` | Text | `علاقه‌مندی‌ها` | Label for Wishlist tab |
| `negar_opt_bottom_nav_account_label` | Text | `حساب کاربری` | Label for Account / Login tab |
| `negar_opt_bottom_nav_account_target` | Select | `modal_or_account` | Open OTP modal if logged out, or go to `/my-account/` |

---

## 5. Checkout Experience (Step 2 — Shipping & Payment)

### 5.1 Stepper Customization
| Setting Key | Type | Default Value | Notes |
| :--- | :--- | :--- | :--- |
| `negar_opt_checkout_step1_label` | Text | `سبد خرید` | Persian step 1 label |
| `negar_opt_checkout_step2_label` | Text | `اطلاعات ارسال و پرداخت` | Active step label |
| `negar_opt_checkout_step3_label` | Text | `تکمیل سفارش` | Final confirmation step |
| `negar_opt_checkout_brand_color` | Color Picker | `#e90544` | Stepper active glow & badge background |

### 5.2 Mobile Layout Constraints
- `.woocommerce-checkout .form-row` must always enforce `width: 100% !important; float: none !important;` on screens under 640px to prevent 105px squashing.
- Select2 state/city dropdowns are bound to `height: 46px` and `border-radius: 12px`.
- Payment method radio cards (BACS, COD, ZarinPal, SnappPay) use soft cards with pink highlight on checked state.

---

## 6. Content Pages (About Us & Contact Us)

### 6.1 About Us Page
- **Hero Title**: `negar_opt_about_hero_title` (Default: `داستان نگار شاپ؛ نوآوری در تجربه خرید`)
- **Hero Subtitle**: `negar_opt_about_hero_subtitle` (Default: `ما از سال ۱۳۹۸ در کنارتان هستیم...`)
- **Pillars Repeater**: 4 value cards (`title`, `description`, `icon_svg`, `badge_color`).
- **Brand Story Narrative**: Rich textarea for the origin history.
- **Stats Counter Repeater**: 4 metric boxes (Active customers, Genuine guarantee, 24/7 support, fast delivery).

### 6.2 Contact Us Page
- **Contact Info Cards**: 4 cards (Phone, Email, Physical Address, Working Hours).
- **AJAX Form Handling**:
  - Nonce action: `negar_contact_nonce`.
  - Storage location: `wp_options` key `negar_contact_submissions` or dedicated CPT `negar_contact_message`.
  - Success alert text: `negar_opt_contact_success_msg` (Default: `پیام شما با موفقیت ثبت شد...`).
- **Google Maps / Neshan Embed URL**: `negar_opt_contact_map_iframe`.

---

## 7. Customization Implementation Guidelines for Next Phase

1. **Option Storage**: Store all settings under a single serialized WordPress option array `negar_theme_options` to ensure zero database bloat and fast autoloading.
2. **Sanitization Functions**:
   - URLs: `esc_url_raw()`
   - Text inputs: `sanitize_text_field()`
   - Textareas / HTML: `wp_kses_post()`
   - Colors: `sanitize_hex_color()`
   - Toggles: `rest_sanitize_boolean()`
3. **Customizer Live Preview (PostMessage)**:
   - Provide `transport => 'postMessage'` bindings for colors, labels, and logo swaps to avoid full page reloads during customization.
4. **CSS Variables Generation**:
   - Output runtime CSS custom properties in `wp_head` via `inc/customizer-dynamic-css.php`.
