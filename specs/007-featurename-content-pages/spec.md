# Feature Specification: 007 Content Pages

**Feature Branch**: `007-featurename-content-pages`  
**Created**: 2026-11-06  
**Status**: Ready for Planning  
**Coverage Matrix IDs**: `CNT-01`, `CNT-02`, `CNT-03`, `CNT-04`, `CNT-05`, `CNT-06`  

---

## 1. User Scenarios & Testing

### User Story 1 - Blog Archive with Sidebar & Search (Priority: P1)
As a visitor looking for buying guides or appliance news,  
I want a clean blog archive grid with a sidebar for categories, recent posts, and tags,  
So that I can discover helpful articles and educational content.

**Independent Test**:
- Open `/blog/` or `archive.php` / `home.php`.
- Verify article cards with 16:9 images, category tags, author, date, and reading time.
- Verify blog sidebar widgets and numeric pagination.

**Acceptance Scenarios**:
1. **Given** blog archive (`CNT-01`), **When** page loads, **Then** articles render in a 2-column or 3-column responsive grid with a widget sidebar.

---

### User Story 2 - Single Post Layout with Rich Typography & Comments (Priority: P1)
As a blog reader,  
I want a readable single article layout with prominent featured image, author bio box, social share buttons, previous/next navigation, and comment section,  
So that I can read comfortably and engage with comments.

**Independent Test**:
- Open a single blog post (`single.php`).
- Verify post header, featured image, RTL typography, author bio card, and comment list/form.

**Acceptance Scenarios**:
1. **Given** single blog post (`CNT-02`), **When** user scrolls down, **Then** author box, prev/next posts, and discussion form appear with clean spacing.

---

### User Story 3 - About Us Page (Priority: P2)
As a new customer learning about the store,  
I want an About Us page detailing company history, key metrics, brand values, and trust elements,  
So that I feel confident in the business credibility.

**Independent Test**:
- Open `/about/` (template `page-about.php`).
- Verify hero story section, 4 counter metrics, core values cards, and CTA banner.

**Acceptance Scenarios**:
1. **Given** About Us page (`CNT-03`), **When** rendered, **Then** all brand story components display with rich icons and layout.

---

### User Story 4 - Contact Us Page with Interactive Form (Priority: P2)
As a customer needing help,  
I want a Contact Us page with phone hotline, email, physical address, interactive contact form, and FAQ accordion,  
So that I can quickly reach customer service.

**Independent Test**:
- Open `/contact/` (template `page-contact.php`).
- Verify contact cards (phone, email, hours, location), contact form, and FAQ accordion.

**Acceptance Scenarios**:
1. **Given** Contact Us page (`CNT-04`), **When** user views page, **Then** contact details and form render with accessible fields.

---

### User Story 5 - 404 Error Page & Empty States (Priority: P2)
As a visitor hitting a broken link or an empty search,  
I want a helpful 404 error page and empty state screens with a search bar and quick category shortcuts,  
So that I never get stuck on a dead end.

**Independent Test**:
- Visit a non-existent URL (e.g. `/wp4/nonexistent-page/`).
- Verify friendly 404 illustration, headline, quick search input, and button back to homepage.
- Search for a nonsense term: verify empty state (`CNT-06`) suggesting popular categories.

**Acceptance Scenarios**:
1. **Given** 404 error (`CNT-05`), **When** loaded, **Then** page returns HTTP 404 with helpful recovery links.
2. **Given** empty search results (`CNT-06`), **When** query yields 0 results, **Then** search suggestions and category bubbles are presented.

---

## 2. Requirements & Verification Traceability

| ID | Requirement | Verification Method |
|---|---|---|
| `CNT-01` | Blog archive template with responsive article cards & sidebar | Playwright screenshots at 1440px and 375px; grid verification |
| `CNT-02` | Single post template with readable typography, author box, and comments | Playwright screenshot; typography and comments inspection |
| `CNT-03` | About Us page with brand story, stats counters, and values | Playwright screenshot; verify layout sections |
| `CNT-04` | Contact Us page with contact details cards, form, and FAQ | Playwright screenshot; form and cards DOM check |
| `CNT-05` | 404 error page with friendly illustration and quick search | Test non-existent URL; verify HTTP 404 and recovery UI |
| `CNT-06` | Empty states for search and catalog with suggested categories | Inspect empty search template and category links |
