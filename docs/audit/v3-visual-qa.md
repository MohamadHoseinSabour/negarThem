# NegarThem — V3 Visual QA Matrix

## Overview
This visual quality assurance matrix documents every user-reported defect, the root cause, target viewports, and corresponding before/after screenshot artifacts captured during the V3 pass.

---

## 1. Visual Verification Matrix

| # | Area / Defect | Route | Viewports Tested | Before Artifact | After Artifact | Verification Result |
|---|---|---|---|---|---|---|
| 1 | Header logo collapse on WooCommerce pages | `/?post_type=product`, `/?p=131` | 375px, 768px, 1440px | `docs/compare/v3/before/header-shop-mobile-375.png`, `header-product-mobile-375.png` | `docs/compare/v3/after/header-shop-mobile-375.png`, `header-product-mobile-375.png` | **PASSED** (Logo maintains 32px height, 60px+ width, perfectly centered) |
| 2 | Mobile hero slider hourglass collapse & missing content | `/` (Homepage) | 320px, 375px, 414px | `docs/compare/v3/before/hero-mobile-375.png` | `docs/compare/v3/after/hero-mobile-375.png` | **PASSED** (Slide image 133x121px, title and CTA visible, 0px horizontal overflow) |
| 3 | Desktop hero crescent navigation arrows displacement | `/` (Homepage) | 1024px, 1280px, 1440px | `docs/compare/v3/before/hero-desktop-1024.png`, `hero-desktop-1440.png` | `docs/compare/v3/after/hero-desktop-1024.png`, `hero-desktop-1440.png` | **PASSED** (Arrows anchored cleanly inside crescent notches: right 8px, left 4px) |
| 4 | Mobile bottom nav: unwanted count badges & extra items | All routes | 375px, 414px | `docs/compare/v3/before/bottom-nav-mobile-375.png` | `docs/compare/v3/after/bottom-nav-mobile-375.png`, `bottom-nav-full-mobile-375.png` | **PASSED** (Exactly 3 items: Home, Wishlist, Account; 0 count badges; no Cart or Categories) |
| 5 | Checkout Step 2: squashed inputs (105px) & unstyled layout | `/checkout/` | 375px, 768px, 1440px | `docs/compare/v3/before/checkout-mobile-375.png` | `docs/compare/v3/after/checkout-mobile-375.png`, `checkout-desktop-1440.png` | **PASSED** (Inputs expand to full 265px width on 375px; Persian stepper, styled radio gateways) |
| 6 | About Us: generic Bootstrap styling & lack of brand cohesion | `/about/` | 375px, 768px, 1440px | `docs/compare/v3/before/about-mobile-375.png`, `about-desktop-1440.png` | `docs/compare/v3/after/about-mobile-375.png`, `about-tablet-768.png`, `about-desktop-1440.png` | **PASSED** (Kaveh brand badge, 4 feature pillars, narrative box, 4 metric cards) |
| 7 | Contact Us: generic Bootstrap styling & form presentation | `/contact/` | 375px, 768px, 1440px | `docs/compare/v3/before/contact-mobile-375.png`, `contact-desktop-1440.png` | `docs/compare/v3/after/contact-mobile-375.png`, `contact-tablet-768.png`, `contact-desktop-1440.png` | **PASSED** (4 contact cards, embedded map, card-based contact form with active AJAX handler) |

---

## 2. Responsive Viewport Coverage

### 2.1 Mobile Viewports (320px, 375px, 414px)
- **Header**: Compact mobile header with hamburger, centered logo, and cart trigger with badge.
- **Hero Slider**: Self-contained red container with 20px radius, image on top/side, title and CTA button with 0px horizontal overflow.
- **Bottom Navigation**: Floating island with 3 items, zero badges, elevated above screen edge with safe-area padding. Hidden on Single Product.
- **Checkout**: Form inputs expand to 100% width, eliminating narrow two-column float artifacts. Stepper displays clean Persian step numbers.
- **Content Pages**: Single column stack with generous touch targets (min 44px) and soft shadow cards.

### 2.2 Tablet Viewport (768px)
- **Header**: Responsive transitions handle header layout without overlap or layout jumps.
- **Checkout**: Gracefully steps into balanced 2-column or stacked layout depending on order review sidebar position.
- **About / Contact**: 2-column card grids with balanced spacing and typography hierarchy.

### 2.3 Desktop Viewports (1024px, 1280px, 1440px)
- **Header**: Full top bar, main header with mega search and category selector, cart trigger, and navigation menu.
- **Hero Slider**: Crescent Swiper arrows positioned directly inside SVG cutout notches.
- **Checkout**: 2-column billing details paired with sticky order summary card.
- **Content Pages**: 4-column feature pillars and stat cards with hover lift interactions.
