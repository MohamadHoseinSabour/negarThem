# Feature Plan: 005 Single Product Page

**Feature Branch**: `005-featurename-single-product`  
**Created**: 2026-11-06  
**Status**: Ready for Tasks Breakdown  
**Spec Reference**: `specs/005-featurename-single-product/spec.md`  

---

## 1. Architecture & Component Structure

```
woocommerce/
├── single-product.php                          # Master single product template wrapper
└── single-product/
    ├── product-gallery.php                     # Bespoke gallery: main image, discount badge, thumbnails
    ├── product-summary.php                     # Title, SKU, brand, ratings, price, add to cart, swatches
    ├── delivery-perks.php                      # Trust badges: authenticity, fast shipping, 7-day return
    ├── product-tabs.php                        # Tabs: description, attributes/specs table, reviews
    └── related.php                             # Related products slider

inc/
└── single-product.php                          # Single product hooks, custom attributes formatter

assets/
├── css/single-product.css                      # Gallery, buy box, quantity selector, tabs, specs table
└── js/single-product.js                        # Thumbnail swapping, quantity counter (+/-), tab switcher
```

---

## 2. Technical Decisions & Standards

1. **Clean-Room Codebase**: Built 100% from scratch with clean semantic HTML and WC-standard classes.
2. **Standard WooCommerce Compatibility**:
   - Preserves WooCommerce standard form handling (`woocommerce_template_single_add_to_cart`), ensuring both simple and variable products work seamlessly with standard extensions.
   - Enriches standard quantity input with custom plus (`+`) and minus (`-`) steppers in JavaScript.
3. **Bespoke Gallery UX**:
   - High performance image switcher without heavy jQuery dependencies.
   - Click or hover on thumbnail immediately swaps main image src/srcset.
   - Native dialog/lightbox zoom overlay for high-resolution inspection.
4. **Persian Technical Specs Table (`PROD-04`)**:
   - Formats WooCommerce product attributes into a structured two-column specification table (`table.negar-specs-table`) with striped rows and right-aligned labels.
5. **Related Products Carousel (`PROD-05`)**:
   - Uses `template-parts/product/card.php` for 100% visual and behavioral consistency.

---

## 3. Step-by-Step Implementation Sequence

1. **Step 1 - Backend & Helper Functions**:
   - Create `inc/single-product.php` for single product helper tags and specs formatter.
   - Wire into `functions.php`.
2. **Step 2 - Component Templates**:
   - Create `woocommerce/single-product/product-gallery.php` (`PROD-01`).
   - Create `woocommerce/single-product/delivery-perks.php` (`PROD-03`).
   - Create `woocommerce/single-product/product-tabs.php` (`PROD-04`).
   - Create `woocommerce/single-product/related.php` (`PROD-05`).
3. **Step 3 - Master Single Product Template**:
   - Create `woocommerce/single-product.php` and `woocommerce/content-single-product.php` assembling gallery, buy box, delivery perks, tabs, and related products.
4. **Step 4 - Stylesheet & Interactivity Script**:
   - Create `assets/css/single-product.css`.
   - Create `assets/js/single-product.js`.
   - Enqueue conditionally in `inc/enqueue.php` for `is_product()`.
5. **Step 5 - Verification & Quality Gate**:
   - Test single product page with Playwright across 4 viewports (375, 768, 1024, 1440).
   - Verify thumbnail switching, quantity adjustments, and tab switching.
   - Update `specs/coverage-matrix.md` (`PROD-01` to `PROD-05`).
