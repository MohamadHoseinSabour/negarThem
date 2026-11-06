# Implementation Plan: 002 Header & Footer

**Feature Branch**: `002-featurename-header-and-footer`  
**Spec Reference**: `specs/002-featurename-header-and-footer/spec.md`  
**Coverage Matrix IDs**: `HDR-01`, `HDR-02`, `HDR-03`, `HDR-04`, `HDR-05`, `FTR-01`, `FTR-02`, `FTR-03`, `FTR-04`, `FTR-05`  

---

## 1. Technical Architecture & Component Structure

```
negarThem/
├── header.php                               # Orchestrates top-bar, main-header, navigation, mobile components
├── footer.php                               # Orchestrates features, newsletter, footer columns, copyright, drawers
├── template-parts/
│   ├── header/
│   │   ├── top-bar.php                      # HDR-01: Announcement and contact hotline
│   │   ├── main-header.php                  # HDR-02: Brand logo, live search, account, wishlist, cart
│   │   ├── navigation.php                   # HDR-03: Primary menu & Mega Menu dropdown
│   │   ├── mobile-header.php                # HDR-05: Smartphone compact header
│   │   ├── mobile-drawer.php                # HDR-05: Off-canvas navigation drawer (tabs: pages & categories)
│   │   └── mobile-bottom-bar.php            # HDR-05: App-like sticky bottom bar (5 targets)
│   └── footer/
│       ├── features.php                     # FTR-01: 4 Trust and service guarantee badges
│       ├── newsletter.php                   # FTR-02: Newsletter email/phone capture
│       ├── columns.php                      # FTR-03: 4 responsive footer columns & menus
│       ├── trust-badges.php                 # FTR-04: Enamad, Samandehi, and Payment cards
│       └── copyright.php                    # FTR-05: Copyright, social icons & back-to-top button
├── inc/
│   ├── ajax-search.php                      # Live product search Ajax endpoint
│   └── mega-menu.php                        # Custom Nav Walker for Mega Menu & badging
└── assets/
    ├── css/
    │   └── header-footer.css                # Styles for header, mega menu, drawers, and footer
    └── js/
        └── header-footer.js                 # JS controller: Ajax search, sticky, drawers, back-to-top
```

---

## 2. Technical Details & APIs

### 2.1. Live AJAX Search (`inc/ajax-search.php`)
- Hook: `wp_ajax_negar_shop_ajax_search` and `wp_ajax_nopriv_negar_shop_ajax_search`.
- Security: `check_ajax_referer('negar_shop_nonce', 'nonce')`.
- Sanitization: `sanitize_text_field(wp_unslash($_GET['term']))`.
- Query: `WP_Query` with `post_type => 'product'`, `post_status => 'publish'`, `posts_per_page => 6`, `s => $term`.
- Output: JSON array containing:
  - `id`: product ID
  - `title`: product title (`esc_html`)
  - `url`: permalink (`esc_url`)
  - `thumb`: product thumbnail image tag or placeholder
  - `price`: formatted price HTML (`wc_price`)
  - `category`: primary category name
  - `view_all_url`: link to full search results page

### 2.2. Mega Menu System (`inc/mega-menu.php`)
- Class `Negar_Shop_Mega_Menu_Walker extends Walker_Nav_Menu`:
  - Detects menu items marked with custom classes (e.g. `mega-menu`, `mega-col-4`, `menu-item-badge`).
  - Supports badge highlights (e.g. "ویژه", "حراج", "جدید").
  - Produces multi-column dropdown markup cleanly formatted for CSS Grid.

### 2.3. Off-Canvas Slide-out Drawer Architecture
- Universal drawer mechanism in vanilla JS:
  - Mini Cart Drawer: `#negar-cart-drawer`
  - Mobile Menu Drawer: `#negar-mobile-drawer`
  - Shared Backdrop: `#negar-drawer-backdrop`
- Body class `.drawer-open` prevents background scrolling (`overflow: hidden`).
- Traps keyboard focus inside drawer while open and closes smoothly on Escape key or backdrop click.

### 2.4. Sticky Header Behavior
- IntersectionObserver or passive scroll listener toggles `.is-sticky` class on `#masthead` when scroll offset > 250px.
- CSS transitions ensure height and opacity transform smoothly (250ms).

---

## 3. Implementation Steps & Verification

1. **AJAX Search Backend & Walker**: Implement `inc/ajax-search.php` and `inc/mega-menu.php`; require in `functions.php`.
2. **Header Sub-templates**: Build `top-bar.php`, `main-header.php`, `navigation.php`, and `mini-cart-drawer.php`.
3. **Mobile Header & App Bar**: Build `mobile-header.php`, `mobile-drawer.php`, and `mobile-bottom-bar.php`.
4. **Footer Sub-templates**: Build `features.php`, `newsletter.php`, `columns.php`, `trust-badges.php`, and `copyright.php`.
5. **Styling & Assets**: Create `assets/css/header-footer.css` and enqueue in `inc/enqueue.php`.
6. **Interactivity JS**: Create `assets/js/header-footer.js` for live search, drawers, sticky header, and back-to-top.
7. **Assembly & Verification**: Integrate into `header.php` and `footer.php`, test at 375px and 1440px viewports, verify HTTP 200 and zero errors in console.
