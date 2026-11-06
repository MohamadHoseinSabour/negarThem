# Feature Specification: 003 Home Page

**Feature Branch**: `003-featurename-home-page`  
**Created**: 2026-11-06  
**Status**: Ready for Planning  
**Coverage Matrix IDs**: `HOME-01`, `HOME-02`, `HOME-03`, `HOME-04`, `HOME-05`, `HOME-06`, `HOME-07`, `HOME-08`  

---

## 1. User Scenarios & Testing

### User Story 1 - Hero Section & Category Bubbles (Priority: P1)
As a visitor landing on the store's homepage,  
I want to see prominent campaign promotions with engaging slides, call-to-action buttons, alongside quick circular category icons,  
So that I immediately understand ongoing promotions and can jump directly into my desired product department.

**Why this priority**: The hero section and category bubbles drive over 50% of first-click navigational choices.

**Independent Test**:
View homepage on 1440px and 375px. Verify wide hero slider auto-plays and advances on arrow/dot click; verify 2 adjacent promo banners link to sale pages; verify circular category bubbles display names and counts with smooth hover scaling.

**Acceptance Scenarios**:
1. **Given** homepage load (`HOME-01`), **When** user views hero section, **Then** wide banner slider rotates smoothly with slide transitions, titles, subtitles, and CTA buttons, alongside 2 stacked/grid campaign banners.
2. **Given** category grid section (`HOME-02`), **When** user hovers over an icon card, **Then** card elevates with subtle zoom effect and links directly to that product category archive.

---

### User Story 2 - Flash Deals Slider with Realtime Countdown & Stock Bar (Priority: P1)
As an impulse buyer looking for limited-time offers,  
I want to see a dedicated "پیشنهادات شگفت‌انگیز" (Flash Deals) section with a ticking countdown timer and sales progress bar,  
So that I feel urgency to purchase discounted items before the offer expires or inventory depletes.

**Why this priority**: Urgency drivers (flash deals) represent the highest converting module on modern Iranian eCommerce stores (e.g. Digikala / Kaveh demo).

**Independent Test**:
Verify crimson/rose background container; verify countdown timer ticks down seconds in real-time; verify product cards show discount percent badge (e.g. `۲۵٪ تخفیف`), strike-through original price, current sale price, and stock progress indicator.

**Acceptance Scenarios**:
1. **Given** active sale products (`HOME-03`), **When** rendered in the flash deal slider, **Then** timer displays hours, minutes, and seconds decreasing each second.
2. **Given** a product card in the slider, **When** hovered, **Then** quick-action buttons (Quick View, Add to Cart, Wishlist) appear with smooth transition.

---

### User Story 3 - Promotional Grid Banners & Interactive Product Tabs (Priority: P2)
As a shopper browsing multiple categories,  
I want to see promotional banners and easily switch between "جدیدترین‌ها" (Newest), "پرفروش‌ترین‌ها" (Best Sellers), and "محبوب‌ترین‌ها" (Top Rated),  
So that I can discover top products without leaving the homepage.

**Why this priority**: Tabs eliminate pagination friction while maintaining an organized, high-density homepage layout.

**Independent Test**:
Inspect middle banner grid (`HOME-04`); click different tab headers (`HOME-05`); verify tab panel switches instantly without full page refresh.

**Acceptance Scenarios**:
1. **Given** middle promo section (`HOME-04`), **When** displayed, **Then** 2 or 4 visual banner cards appear in responsive grid with shine/zoom hover effect.
2. **Given** product tabs (`HOME-05`), **When** user clicks a tab button, **Then** the corresponding carousel loads seamlessly.

---

### User Story 4 - Brand Logos, Category Showcase & Blog Articles (Priority: P2)
As a customer researching products and looking for reputable brands and buying guides,  
I want to see a brand slider, a showcase section for a hero category, and recent blog posts,  
So that I gain confidence in the store's curation and access helpful buying advice.

**Why this priority**: Enhances social proof, brand credibility, and content-driven SEO.

**Independent Test**:
Scroll to lower sections; verify brand logo carousel (`HOME-06`); verify special category showcase banner + product grid (`HOME-07`); verify 3-4 blog article cards with thumbnails and dates (`HOME-08`).

**Acceptance Scenarios**:
1. **Given** brand section (`HOME-06`), **When** rendered, **Then** partner logos display in grayscale and transition to full color on hover.
2. **Given** blog section (`HOME-08`), **When** rendered, **Then** articles show 16:9 images, publishing date, excerpt, and "ادامه مطلب" link.

---

### User Story 5 - Elementor Widgets Compatibility (Priority: P3)
As an administrator who wishes to customize the homepage layout using Elementor,  
I want each homepage section to be available as a custom Elementor widget,  
So that I can reorder, duplicate, or customize content inside the Elementor editor without touching PHP.

**Why this priority**: Fulfills the user's architectural choice (hybrid + Elementor widgets).

**Independent Test**:
Check Elementor widget registry (if Elementor is active); verify widgets render identical semantic markup and styling.

---

## 2. Functional Requirements

- **FR-001**: System MUST provide `front-page.php` composing all 8 homepage sections seamlessly (`HOME-01` to `HOME-08`).
- **FR-002**: System MUST implement lightweight, swipeable carousels using vanilla JS / modern CSS scroll snap without bloated external dependencies.
- **FR-003**: System MUST provide realtime JavaScript countdown timer (`negar-countdown`) counting down to a configurable end timestamp (`HOME-03`).
- **FR-004**: System MUST query WooCommerce products with caching and efficient `WP_Query` arguments for On Sale, Featured, and Best-Selling items.
- **FR-005**: System MUST provide standard product card component ([template-parts/product/card.php](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/template-parts/product/card.php)) reused across all sliders and archives.
- **FR-006**: System MUST register custom Elementor widgets in `inc/elementor/` when Elementor is active (`HOME-01` to `HOME-08`).
