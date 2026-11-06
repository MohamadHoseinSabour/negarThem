# Tasks: 008 Extras and Modals

**Feature Branch**: `008-featurename-extras-and-modals`  
**Spec Reference**: `specs/008-featurename-extras-and-modals/spec.md`  
**Plan Reference**: `specs/008-featurename-extras-and-modals/plan.md`  
**Status**: Ready for Execution  

---

## Task List

- [ ] **T100**: Create `inc/modals-ajax.php` registering Ajax endpoints for quick view markup, wishlist items, and comparison matrix.
  - *Files*: `inc/modals-ajax.php`
  - *Verification*: Endpoints return valid JSON and HTML on product query.

- [ ] **T101**: Create `template-parts/modals/quickview.php` (`MODAL-01`) for quick view dialog container.
  - *Files*: `template-parts/modals/quickview.php`
  - *Verification*: Modal structure renders with close button and dynamic target body.

- [ ] **T102**: Create `template-parts/modals/wishlist-drawer.php` (`MODAL-02`) for wishlist sliding drawer with item list and empty state.
  - *Files*: `template-parts/modals/wishlist-drawer.php`
  - *Verification*: Drawer renders with close button and empty state/items wrapper.

- [ ] **T103**: Create `template-parts/modals/compare-modal.php` (`MODAL-03`) for product comparison side-by-side modal.
  - *Files*: `template-parts/modals/compare-modal.php`
  - *Verification*: Compare modal renders with table container and empty state.

- [ ] **T104**: Create `template-parts/modals/toast.php` (`MODAL-04`) for floating corner feedback notifications.
  - *Files*: `template-parts/modals/toast.php`
  - *Verification*: Toast container renders in DOM.

- [ ] **T105**: Create `assets/css/modals.css` styling modals, backdrops, wishlist drawer, comparison tables, and toast animations.
  - *Files*: `assets/css/modals.css`
  - *Verification*: Styles apply without layout jumps; smooth fade and slide animations.

- [ ] **T106**: Create `assets/js/modals.js` implementing QuickView Ajax loader, Wishlist LocalStorage sync, Compare table builder, and Toast notification triggers.
  - *Files*: `assets/js/modals.js`
  - *Verification*: QuickView opens on card click; Wishlist persists and counts; Compare displays items; Toasts fire.

- [ ] **T107**: Wire up modal templates in `footer.php`, require in `functions.php`, and enqueue in `inc/enqueue.php`.
  - *Files*: `footer.php`, `functions.php`, `inc/enqueue.php`
  - *Verification*: Modals load globally with localized script parameters.

- [ ] **T108**: Verification & Quality Gate: Capture Playwright screenshots of QuickView modal open, Wishlist drawer open, and Toast alert, and update Coverage Matrix (`MODAL-01` to `MODAL-04`).
  - *Files*: `specs/coverage-matrix.md`, `PROGRESS.md`, `docs/compare/`
  - *Verification*: 4 matrix rows updated to `verified`.
