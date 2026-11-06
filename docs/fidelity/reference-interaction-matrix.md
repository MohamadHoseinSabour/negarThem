# Reference Interaction Matrix — Kaveh Demo 43

**Target Site:** `https://kaveh.moeinwp.com/43/`  
**Audit Date:** 2026-11-06  
**Audited By:** Playwright Automation & Visual Inspection  

---

## Interactive Elements & Behavior Table

| Item ID | Component / Element | Trigger | State / Condition | Expected Behavior in Demo 43 | Local Status & Target Action | Evidence / Screenshot |
|---|---|---|---|---|---|---|
| INT-01 | Main Header | Scroll down > 300px | Sticky / Fixed | Header switches to compact sticky bar with shadow | Local header has sticky script; verify seamless z-index & backdrop-blur | `docs/fidelity/local-shots/local-header.png` |
| INT-02 | Search Bar | Focus & input text | Live Autocomplete | Shows ajax product search results dropdown with thumbnail, title & price | Local search has modal/ajax endpoint; verify live typing trigger | `template-parts/header/main-header.php` |
| INT-03 | Mini-Cart in Header | Hover / Click | Cart Preview / Drawer | Opens slide-out drawer or dropdown showing cart items and total | Local has modal cart drawer; verify open on click/hover | `assets/js/modals.js` |
| INT-04 | Product Card Action Buttons | Hover on Card | QuickView, Wishlist, Compare | Action icons overlay appears or animates with subtle scale/fade | Local card has hover icons; verify smooth transition | `template-parts/product/card.php` |
| INT-05 | QuickView Modal | Click Eye icon on card | Modal open | Ajax-loads product gallery, price, short desc, add-to-cart | Implemented via `inc/modals-ajax.php`; verified active | `docs/compare/008-modal-quickview-desktop.png` |
| INT-06 | Wishlist Toggle | Click Heart icon on card | Drawer open + cookie update | Saves product to wishlist drawer without reload | Implemented via localStorage / cookie; verified active | `docs/compare/008-modal-wishlist-drawer.png` |
| INT-07 | Product Compare | Click Compare icon | Modal open | Displays 2-4 products side by side with spec comparisons | Implemented via `template-parts/modals/compare-modal.php` | `docs/compare/008-modal-compare-table.png` |
| INT-08 | Flash Deals Countdown | Real-time interval | Ticking timer | Days, hours, minutes, seconds countdown updates every 1s | Local countdown script running; verify styling alignment | `template-parts/home/flash-deals.php` |
| INT-09 | Product Swiper / Sliders | Click Prev/Next arrows / Drag | Carousel Slide | Smooth sliding with touch swipe support | Local Swiper.js running; verify arrow alignment & autoplay | `template-parts/home/hero-slider.php` |
| INT-10 | Product Category Tabs | Click Tab Button | Tab Switch / Filter | Filters product cards by active tab smoothly | Local product tabs implemented; verify multi-section tabs | `template-parts/home/product-tabs.php` |
| INT-11 | Mobile Navigation Drawer | Click Hamburger icon | Offcanvas open | Slides out mobile navigation with accordion categories | Implemented in `header.php`; verified | `docs/compare/home-mobile-375.png` |
| INT-12 | Mobile Bottom Nav | Viewport <= 768px | Fixed Bottom Bar | Quick icons: Home, Shop, Cart, Account, Search | Implemented in `footer.php`; verified | `docs/compare/home-mobile-375.png` |
