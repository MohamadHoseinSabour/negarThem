# Tasks: 007 Content Pages

**Feature Branch**: `007-featurename-content-pages`  
**Spec Reference**: `specs/007-featurename-content-pages/spec.md`  
**Plan Reference**: `specs/007-featurename-content-pages/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T090**: Create `template-parts/blog/article-card.php`, `template-parts/blog/sidebar.php`, and `archive.php` (`CNT-01`).
  - *Files*: `template-parts/blog/article-card.php`, `template-parts/blog/sidebar.php`, `archive.php`
  - *Verification*: Blog archive displays articles grid with metadata and sidebar widgets.

- [x] **T091**: Create `single.php` (`CNT-02`) with readable typography, author bio card, social sharing, and discussion thread.
  - *Files*: `single.php`
  - *Verification*: Single article displays clean layout with author card and comments.

- [x] **T092**: Create `page-templates/page-about.php` (`CNT-03`) with story hero, key metrics, brand values, and call-to-action.
  - *Files*: `page-templates/page-about.php`
  - *Verification*: About Us template renders all story and metrics sections.

- [x] **T093**: Create `page-templates/page-contact.php` (`CNT-04`) with contact cards, interactive contact form, and FAQ accordion.
  - *Files*: `page-templates/page-contact.php`
  - *Verification*: Contact Us template renders contact channels and form.

- [x] **T094**: Create `404.php` (`CNT-05`) with 404 illustration, quick search bar, and recovery links.
  - *Files*: `404.php`
  - *Verification*: Navigating to a 404 URL renders friendly recovery page.

- [x] **T095**: Create `search.php` (`CNT-06`) handling search results and empty search states with recommended categories.
  - *Files*: `search.php`
  - *Verification*: Empty search displays recommendations and category pills.

- [x] **T096**: Create `assets/css/content-pages.css` styling blog posts, prose typography, about cards, contact forms, and 404 page.
  - *Files*: `assets/css/content-pages.css`
  - *Verification*: CSS applies cleanly across all content pages.

- [x] **T097**: Wire up stylesheet in `inc/enqueue.php`.
  - *Files*: `inc/enqueue.php`
  - *Verification*: `content-pages.css` enqueued on relevant content pages.

- [x] **T098**: Verification & Quality Gate: Capture Playwright screenshots in 4 widths, verify HTTP 200/404, and update Coverage Matrix (`CNT-01` to `CNT-06`).
  - *Files*: `specs/coverage-matrix.md`, `PROGRESS.md`, `docs/compare/`
  - *Verification*: 6 matrix rows updated to `verified`.
