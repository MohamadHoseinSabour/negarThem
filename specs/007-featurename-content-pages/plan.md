# Feature Plan: 007 Content Pages

**Feature Branch**: `007-featurename-content-pages`  
**Created**: 2026-11-06  
**Status**: Ready for Tasks Breakdown  
**Spec Reference**: `specs/007-featurename-content-pages/spec.md`  

---

## 1. Architecture & Component Structure

```
├── archive.php                     # Master blog archive / category / author / date template
├── single.php                      # Single blog post template with author box & comments
├── search.php                      # Search results template with full empty state recovery
├── 404.php                         # 404 error page template
├── page-templates/
│   ├── page-about.php              # Custom About Us page template
│   └── page-contact.php            # Custom Contact Us page template
├── template-parts/blog/
│   ├── article-card.php            # Reusable blog card (16:9 thumb, meta, title, excerpt)
│   └── sidebar.php                 # Blog sidebar (search, categories, recent posts, tags)
└── assets/
    └── css/content-pages.css       # Dedicated styles for blog typography, 404, about & contact
```

---

## 2. Technical Decisions & Standards

1. **Semantic HTML5 & Accessibility**:
   - Uses `<article>`, `<aside>`, `<time>`, `<header>`, `<footer>`.
   - Rich schema.org `Article`, `BreadcrumbList`, and `ContactPage` annotations.
2. **Typography**:
   - Vazirmatn typography optimized for long-form Persian reading with `leading-relaxed` (1.85 line-height), paragraph spacing, and blockquote styling.
3. **Empty States**:
   - `search.php` and `404.php` integrate category pills and instant search input so users always have a next action.
4. **Custom Page Templates**:
   - `page-templates/page-about.php` and `page-templates/page-contact.php` registered in WordPress template dropdown for pages.

---

## 3. Step-by-Step Implementation Sequence

1. **Step 1 - Blog Archive & Components**:
   - Create `template-parts/blog/article-card.php` and `template-parts/blog/sidebar.php`.
   - Create `archive.php` (`CNT-01`).
2. **Step 2 - Single Post**:
   - Create `single.php` (`CNT-02`) with author box, tags, prev/next posts, and comments.
3. **Step 3 - About & Contact Page Templates**:
   - Create `page-templates/page-about.php` (`CNT-03`).
   - Create `page-templates/page-contact.php` (`CNT-04`).
4. **Step 4 - 404 & Empty Search**:
   - Create `404.php` (`CNT-05`).
   - Create `search.php` (`CNT-06`).
5. **Step 5 - Stylesheet**:
   - Create `assets/css/content-pages.css` and enqueue in `inc/enqueue.php`.
6. **Step 6 - Verification & Quality Gate**:
   - Capture Playwright screenshots across viewports for Blog, Single Post, About, Contact, and 404.
   - Update `specs/coverage-matrix.md` (`CNT-01` to `CNT-06`).
