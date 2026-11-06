# Feature Plan: 008 Extras and Modals

**Feature Branch**: `008-featurename-extras-and-modals`  
**Created**: 2026-11-06  
**Status**: Ready for Tasks Breakdown  
**Spec Reference**: `specs/008-featurename-extras-and-modals/spec.md`  

---

## 1. Architecture & Component Structure

```
template-parts/modals/
├── quickview.php                   # Quick View modal structure with dynamic Ajax container
├── wishlist-drawer.php             # Wishlist off-canvas drawer with empty state & item list
├── compare-modal.php               # Side-by-side comparison modal table
└── toast.php                       # Floating toast notification container

inc/
└── modals-ajax.php                 # Ajax handlers for quickview rendering, wishlist data, compare data

assets/
├── css/modals.css                  # Modal backdrops, animations, wishlist drawer, compare table, toast
└── js/modals.js                    # Quickview loader, Wishlist manager, Compare manager, Toast API
```

---

## 2. Technical Decisions & Standards

1. **Lightweight & Dependency-Free**:
   - Built with pure Vanilla JS and CSS transitions; zero external bloated popup plugins.
   - Preserves 60fps animations with hardware-accelerated transforms (`translate3d`, `opacity`).
2. **Local Storage Architecture**:
   - Wishlist (`negar_wishlist_ids`): Array of product IDs in localStorage.
   - Compare (`negar_compare_ids`): Array of up to 4 product IDs in localStorage.
   - Header badges (`.negar-wishlist-count`, `.negar-compare-count`) automatically sync.
3. **Ajax Endpoints**:
   - `negar_shop_quickview`: Returns compact HTML for product with ID.
   - `negar_shop_get_wishlist_cards`: Accepts array of IDs and returns rendered item cards.
   - `negar_shop_get_compare_data`: Accepts array of IDs and returns table matrix of titles, prices, images, and attributes.
4. **Accessibility (a11y)**:
   - Proper `aria-modal="true"`, `role="dialog"`, keyboard `Esc` listener, and focus trapping.

---

## 3. Step-by-Step Implementation Sequence

1. **Step 1 - Backend & Ajax Endpoints**:
   - Create `inc/modals-ajax.php` registering `negar_shop_quickview`, `negar_shop_get_wishlist_cards`, `negar_shop_get_compare_data`.
   - Wire into `functions.php`.
2. **Step 2 - Modal Markup Templates**:
   - Create `template-parts/modals/quickview.php`.
   - Create `template-parts/modals/wishlist-drawer.php`.
   - Create `template-parts/modals/compare-modal.php`.
   - Create `template-parts/modals/toast.php`.
   - Include in `footer.php`.
3. **Step 3 - Stylesheet**:
   - Create `assets/css/modals.css` and enqueue globally in `inc/enqueue.php`.
4. **Step 4 - Client-side Manager Script**:
   - Create `assets/js/modals.js` with QuickView handler, Wishlist storage manager, Compare table manager, and global Toast alert function.
   - Enqueue in `inc/enqueue.php`.
5. **Step 5 - Verification & Quality Gate**:
   - Capture Playwright screenshots: QuickView open, Wishlist drawer open, Compare modal open, Toast notification displayed.
   - Update `specs/coverage-matrix.md` (`MODAL-01` to `MODAL-04`).
