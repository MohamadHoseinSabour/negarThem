# Implementation Plan: 003 Home Page

**Feature Branch**: `003-featurename-home-page`  
**Spec Reference**: `specs/003-featurename-home-page/spec.md`  
**Coverage Matrix IDs**: `HOME-01`, `HOME-02`, `HOME-03`, `HOME-04`, `HOME-05`, `HOME-06`, `HOME-07`, `HOME-08`  

---

## 1. Technical Architecture & File Map

```
negarThem/
├── front-page.php                           # Homepage master template
├── template-parts/
│   ├── home/
│   │   ├── hero-slider.php                  # HOME-01: Main slider + side banners
│   │   ├── category-bubbles.php             # HOME-02: Circular category icons
│   │   ├── flash-deals.php                  # HOME-03: Amazing offers with timer & stock bar
│   │   ├── banners-grid.php                 # HOME-04: Promotional middle banners
│   │   ├── product-tabs.php                 # HOME-05: Product tabs carousel
│   │   ├── brands-slider.php                # HOME-06: Brand logos carousel
│   │   ├── category-showcase.php            # HOME-07: Vertical banner + category showcase
│   │   └── blog-posts.php                   # HOME-08: Latest articles carousel
│   └── product/
│       └── card.php                         # Reusable e-commerce product card
├── inc/
│   └── elementor/
│       └── class-elementor-init.php         # Custom Elementor widgets bootstrap
└── assets/
    ├── css/
    │   └── home.css                         # Sliders, cards, timer, tabs styling
    └── js/
        └── home.js                          # Vanilla JS sliders, countdown timer & tabs
```

---

## 2. Technical Details & Component Specifications

### 2.1. Reusable Product Card (`template-parts/product/card.php`)
- Output: `<div class="negar-product-card group relative bg-white rounded-2xl border border-slate-100 p-3 hover:shadow-card-hover transition duration-300">`
- Structure:
  - Header: Badges (`badge-discount` percentage, `badge-new`, `badge-stock`), Wishlist toggle button (`.btn-wishlist-toggle`), Quick View trigger (`.btn-quickview`).
  - Image: Aspect-ratio 1:1, hover zoom effect (`group-hover:scale-105`), placeholder fallback.
  - Body: Primary category tag, product title (`line-clamp-2`), star rating (`wc_get_rating_html()`).
  - Footer: Price display (original strikethrough + sale price in emerald green), Ajax Add to Cart button (`woocommerce_template_loop_add_to_cart()`).

### 2.2. Flash Deals Countdown Timer (`assets/js/home.js`)
- Element: `.negar-countdown[data-endtime="TIMESTAMP"]`
- Ticks every 1000ms calculating remaining days, hours, minutes, seconds.
- Persian numbers localization: Formats digits with `toPersianDigits()` utility for authentic Iranian UX.

### 2.3. Carousel Controller (`assets/js/home.js`)
- Ultra-lightweight Vanilla JS carousel with native touch swipe, keyboard navigation, and responsive CSS scroll-snap fallback.
- Auto-play with pause-on-hover.

### 2.4. Elementor Compatibility (`inc/elementor/`)
- Checks `did_action('elementor/loaded')`.
- Registers custom category `negar-shop-elements`.
- Registers widgets mirroring all home components.

---

## 3. Implementation Steps & Verification

1. **Product Card Component**: Build `template-parts/product/card.php` handling simple and variable products, out of stock states, and ratings.
2. **Homepage Sections (01 to 04)**: Build `hero-slider.php`, `category-bubbles.php`, `flash-deals.php`, and `banners-grid.php`.
3. **Homepage Sections (05 to 08)**: Build `product-tabs.php`, `brands-slider.php`, `category-showcase.php`, and `blog-posts.php`.
4. **Front-Page Assembly**: Assemble in `front-page.php`.
5. **Styles & Scripts**: Create `assets/css/home.css` and `assets/js/home.js`; enqueue in `inc/enqueue.php`.
6. **Elementor Bootstrap**: Create `inc/elementor/class-elementor-init.php` and require in `functions.php`.
7. **Verification & Quality Gate**: Verify on local site (`http://localhost/wp4/`), test countdown timer, capture Playwright screenshots in 4 widths, and update coverage matrix.
