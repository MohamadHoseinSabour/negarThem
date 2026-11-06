# Feature Specification: 004 Shop and Archive

**Feature Branch**: `004-featurename-shop-and-archive`  
**Created**: 2026-11-06  
**Status**: Ready for Planning  
**Coverage Matrix IDs**: `SHOP-01`, `SHOP-02`, `SHOP-03`, `SHOP-04`, `SHOP-05`  

---

## 1. User Scenarios & Testing

### User Story 1 - Responsive Shop Layout & Mobile Filters Drawer (Priority: P1)
As a shopper browsing the catalog on either desktop or mobile,  
I want a clean, organized layout with easy access to filters (sidebar on desktop, sliding drawer on mobile),  
So that I can effortlessly narrow down products without screen clutter.

**Why this priority**: Fast access to catalog filtering is the primary conversion driver for an eCommerce archive.

**Independent Test**:
- Open `/shop/` on desktop (1440px): verify 2-column layout with sidebar on right (RTL) and main product grid.
- Open `/shop/` on mobile (375px): verify sidebar is hidden by default and accessible via a floating/toolbar "فیلترها" button that slides in an off-canvas drawer with backdrop overlay.

**Acceptance Scenarios**:
1. **Given** desktop screen (`SHOP-01`), **When** page loads, **Then** sidebar displays on the right side of the main grid with structured widget cards.
2. **Given** mobile screen, **When** user taps "فیلترها" button, **Then** drawer slides smoothly from the right, locking body scroll until closed via close button, backdrop tap, or Esc key.

---

### User Story 2 - Archive Toolbar, Sorting, and View Switcher (Priority: P1)
As a buyer looking through products,  
I want a top toolbar showing total results, ordering options, and a Grid/List view switcher,  
So that I can view products in my preferred density and sort order.

**Why this priority**: Users expect immediate feedback on total results and the ability to sort by price or popularity.

**Independent Test**:
- Verify results counter e.g. "نمایش ۱۰ محصول".
- Select "ارزان‌ترین" or "جدیدترین": verify products sort accordingly.
- Toggle between Grid (۳-۴ ستونه) and List (ردیفی با توضیحات): verify grid layout switches seamlessly without breaking card components.

**Acceptance Scenarios**:
1. **Given** toolbar (`SHOP-02`), **When** user changes sort order, **Then** product loop re-orders immediately via Ajax.
2. **Given** view toggle buttons, **When** List icon is selected, **Then** container gains `.is-list-view` class displaying horizontal card layout with expanded details.

---

### User Story 3 - Ajax Live Filters (Priority: P1)
As a buyer searching for specific items,  
I want to filter by category, price range, stock availability, and on-sale status with instant Ajax updates,  
So that I can find exact matching products without waiting for full page reloads.

**Why this priority**: Modern shoppers expect seamless instantaneous filtering matching the benchmark reference store.

**Independent Test**:
- Toggle "فقط کالاهای موجود" or "فقط کالاهای تخفیف‌دار": verify loop refreshes via Ajax with loader overlay.
- Adjust price range min/max and click apply: verify products filter to matching price bracket.
- Click a category in sidebar: filter products to that category with active count chips displayed above the grid.

**Acceptance Scenarios**:
1. **Given** filter selections (`SHOP-03`), **When** an option is selected/deselected, **Then** an Ajax request is dispatched, showing a loading indicator, updating the DOM with new cards and active filter badges.
2. **Given** active filter badges, **When** user clicks the (×) on a badge or "حذف همه", **Then** the filter resets and loop refreshes.

---

### User Story 4 - Standardized Product Grid & List Cards (Priority: P1)
As a store customer,  
I want to see crisp product cards with title, price, discount badge, stock status, ratings, and quick actions,  
So that I can compare products at a glance and add to cart directly.

**Why this priority**: Consistency between homepage cards and archive cards ensures a professional, cohesive user experience.

**Independent Test**:
- Inspect cards in grid and list views (`SHOP-04`). Verify image aspect ratio, discount percentage badge, price formatting in Toman/Rial, and action buttons (quick view, add to cart, wishlist).

**Acceptance Scenarios**:
1. **Given** product catalog loop, **When** card renders, **Then** markup matches `template-parts/product/card.php` standards with accessible links and schema data.

---

### User Story 5 - Numeric Pagination & Ajax Navigation (Priority: P2)
As a shopper browsing large categories,  
I want clear numeric pagination with previous/next controls that loads pages smoothly,  
So that I can navigate through hundreds of products predictably.

**Why this priority**: Reliable pagination ensures deep catalog exploration and SEO crawler accessibility.

**Independent Test**:
- Verify numeric page numbers (`۱`, `۲`, `۳`, ...) with active state and arrows.
- Click page 2: verify Ajax loading transitions to page 2 and smooth-scrolls to top of product grid.

**Acceptance Scenarios**:
1. **Given** pagination bar (`SHOP-05`), **When** a page number is clicked, **Then** Ajax retrieves the requested page results without page refresh, or falls back seamlessly to standard URL navigation if JavaScript is disabled.

---

## 2. Requirements & Verification Traceability

| ID | Requirement | Verification Method |
|---|---|---|
| `SHOP-01` | Responsive Shop layout with desktop sidebar & mobile off-canvas drawer | Playwright screenshots at 1440px and 375px; drawer toggle DOM check |
| `SHOP-02` | Archive toolbar with result count, sort dropdown, and Grid/List switcher | Interactive test changing view and sort dropdown; verify classes |
| `SHOP-03` | Ajax live filters (category, price range, stock, sale, active chips, clear all) | Ajax request verification with custom nonce, DOM mutation check |
| `SHOP-04` | Reusable card integration in archive loop with Grid and List view styling | Inspect DOM elements, badge percentages, and responsive grid |
| `SHOP-05` | Numeric pagination with Ajax support and accessible fallback | Click page link, verify URL history state and product grid replacement |
