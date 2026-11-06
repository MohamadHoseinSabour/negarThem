# Tasks: 009 Theme Options & Customizer

**Feature Branch**: `009-featurename-theme-options`  
**Spec Reference**: `specs/009-featurename-theme-options/spec.md`  
**Plan Reference**: `specs/009-featurename-theme-options/plan.md`  
**Status**: Completed  

---

## Task List

- [x] **T110**: Create `inc/customizer.php` (`OPT-01`) registering `negar_shop_customize_register` with panel `negar_shop_panel`, sections for Colors, Header, Social, and Footer, and inline CSS injector hooked to `wp_head`.
  - *Files*: `inc/customizer.php`, `functions.php`
  - *Verification*: `php -l inc/customizer.php` passes; settings appear in Customizer registry.

- [x] **T111**: Update `inc/setup.php` (`OPT-02`) registering `widgets_init` action with `sidebar-shop`, `sidebar-blog`, and `footer-1` to `footer-4`.
  - *Files*: `inc/setup.php`
  - *Verification*: `wp sidebar list` shows 6 registered sidebars.

- [x] **T112**: Connect theme mods to template parts:
  - Update `template-parts/header/top-bar.php` to read announcement text & hotline phone.
  - Update `template-parts/footer/columns.php` to read footer about text.
  - Update `template-parts/footer/copyright.php` to read copyright text and social profile links.
  - *Files*: `template-parts/header/top-bar.php`, `template-parts/footer/columns.php`, `template-parts/footer/copyright.php`
  - *Verification*: Setting theme mods alters rendered HTML output.

- [x] **T113**: Verification & Quality Gate:
  - Test via WP-CLI: `wp theme mod set` and inspect frontend markup.
  - Run Playwright test capturing screenshots of customized header and footer.
  - Update `specs/coverage-matrix.md` (`OPT-01`, `OPT-02` -> `verified`).
  - Update `PROGRESS.md`, commit, and re-index repository.
