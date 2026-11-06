# Tasks: 010 Demo Content & Safe Reset

**Feature Branch**: `010-featurename-demo-content`  
**Spec Reference**: `specs/010-featurename-demo-content/spec.md`  
**Plan Reference**: `specs/010-featurename-demo-content/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T120**: Export pre-demo database backup (`DMO-01`) to `backups/pre-demo-backup.sql`.
  - *Files*: `backups/pre-demo-backup.sql`
  - *Verification*: SQL file generated with non-zero size (888 KB).

- [x] **T121**: Create `inc/demo-importer.php` (`DMO-02`) containing idempotent functions to create pages, categories, products, menus, and theme options.
  - *Files*: `inc/demo-importer.php`
  - *Verification*: `php -l inc/demo-importer.php` passes.

- [x] **T122**: Execute demo importer via WP-CLI:
  - Command: `wp eval-file inc/demo-importer.php`
  - *Verification*: Importer reports success; post count, terms, and menu locations confirmed.

- [x] **T123**: Verification & Quality Gate:
  - Inspect front-page, shop, categories, menus, and Customizer settings.
  - Update `specs/coverage-matrix.md` (`DMO-01`, `DMO-02` -> `verified`).
  - Update `PROGRESS.md`, commit, and re-index repository.
