# Feature Specification: 010 Demo Content & Safe Reset

**Feature Code**: `DMO-01`, `DMO-02`  
**Status**: Ready for Execution  

## 1. User Stories & Goals
- **US-DMO-01 (Safe Database Backup)**: As a system developer, I must ensure a full SQL dump (`backups/pre-demo-backup.sql`) exists before executing any database modifications or importing demo data, avoiding any accidental data loss.
- **US-DMO-02 (One-Click Idempotent Demo Installer)**: As an agency or merchant installing Negar Shop on a fresh WordPress instance, I want a single automated script (`wp eval-file inc/demo-importer.php`) to set up full demo content—including standard store pages, category hierarchies, realistic Persian products with prices and sales, navigation menus with location mappings, and core store settings—in an idempotent manner without creating duplicate posts or corrupting existing records.

## 2. Requirements & Acceptance Criteria
- **REQ-DMO-01**: Pre-demo database backup exported to `backups/pre-demo-backup.sql` with non-zero byte size. (Verified: 888 KB).
- **REQ-DMO-02**: Create `inc/demo-importer.php` featuring:
  - Idempotency checks: Uses `get_page_by_path()` / `term_exists()` to avoid duplicates.
  - Page generation: 'صفحه اصلی' (front page), 'وبلاگ' (posts page), 'درباره ما', 'تماس با ما', and WC pages.
  - Category creation: Hierarchical categories under `product_cat`.
  - Product dataset: 10+ Persian products with regular price, sale price, stock status, categories, and attributes.
  - Menu generation & assignment: Nav menus created and assigned to theme locations `primary`, `categories`, `mobile`, `footer-1`, `footer-2`.
  - Reading settings: `show_on_front` set to `page`, `page_on_front` and `page_for_posts` assigned.
- **REQ-DMO-03**: Verification via WP-CLI execution and checking exit status code 0.
