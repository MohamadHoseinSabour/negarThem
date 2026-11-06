# Tasks: 011 Hardening, Packaging & Delivery

**Feature Branch**: `011-featurename-hardening-and-packaging`  
**Spec Reference**: `specs/011-featurename-hardening-and-packaging/spec.md`  
**Plan Reference**: `specs/011-featurename-hardening-and-packaging/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T130**: Create `inc/seo-schema.php` (`HRD-02`) generating JSON-LD Structured Data (`WebSite`, `OnlineStore`, `SearchAction`) and OpenGraph meta tags, and require in `functions.php`.
  - *Files*: `inc/seo-schema.php`, `functions.php`
  - *Verification*: Validate JSON-LD script rendered in `<head>` of homepage.

- [x] **T131**: Generate localization catalog `languages/negar-shop.pot` and Persian translation files `languages/fa_IR.po` & `languages/fa_IR.mo` (`HRD-03`).
  - *Files*: `languages/negar-shop.pot`, `languages/fa_IR.po`, `languages/fa_IR.mo`
  - *Verification*: POT catalog contains theme strings; `fa_IR.mo` exists with non-zero byte size.

- [x] **T132**: Performance & Security audit (`HRD-01`, `HRD-02`):
  - Audit script enqueues, font preconnects, and image dimensions.
  - Audit PHP escaping across templates.
  - *Files*: Codebase templates
  - *Verification*: Zero unescaped variables or raw SQL queries; HTTP 200 responses verified.

- [x] **T133**: Create comprehensive documentation in `README.md` (`HRD-03`):
  - Theme overview, features list, installation instructions, demo importer command, Customizer guide, and hook reference.
  - *Files*: `README.md`
  - *Verification*: Document complete and formatted.

- [x] **T134**: Build production-ready installable distribution archive `dist/negar-shop.zip` (`HRD-03`).
  - *Files*: `dist/negar-shop.zip`
  - *Verification*: Archive contains clean root theme folder `negar-shop` with all templates and assets, excluding `.git`, `node_modules`, `scratch`, `backups`.

- [x] **T135**: Final Quality Gate & Delivery Verification:
  - Verify all 31 rows in `specs/coverage-matrix.md` are marked `verified` (100% coverage).
  - Update `PROGRESS.md`, commit, and re-index repository.
