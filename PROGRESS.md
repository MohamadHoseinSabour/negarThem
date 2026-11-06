# گزارش پیشرفت پروژه (PROGRESS.md)

**پروژه:** ساخت قالب وردپرس فروشگاهی حرفه‌ای (WooCommerce) با روش Spec-Driven  
**نام قالب:** Negar Shop (`negar-shop`)  
**مسیر قالب:** `x:\apps\wamp\www\wp4\wp-content\themes\negarThem`  
**دموی مرجع:** `https://kaveh.moeinwp.com/43/#`  
**تاریخ به‌روزرسانی:** 2026-11-06  

---

## ۱. وضعیت دروازه ۰ (Gate 0 - Toolchain Verification)

| ابزار | نسخه | وضعیت | دستور / معیار تأیید |
|---|---|---|---|
| **Python** | 3.12.10 | تایید شده | `python --version` |
| **uv** | 0.11.7 | تایید شده | `uv --version` |
| **Node.js** | v24.13.1 | تایید شده | `node --version` |
| **Git** | 2.53.0.windows.1 | تایید شده | `git --version` |
| **Docker** | 29.2.0 | تایید شده | `docker --version` |
| **Spec Kit (specify-cli)** | 1.3.1 | نصب و راه‌اندازی شد | `specify --help` و وجود `.specify/` |
| **codebase-memory-mcp** | 0.12.1 | نصب و فعال (`auto_index=true`) | `codebase-memory-mcp cli list_projects` |
| **WordPress agent-skills** | ۱۲ اسکیل استاندارد | نصب شد در `.agents/skills/` و `.claude/skills/` | وجود `wordpress-router`, `wp-block-themes` و ... |
| **ui-ux-pro-max-skill** | آخرین نسخه (npm) | تولید MASTER.md دیزاین سیستم | `python .claude/skills/ui-ux-pro-max/scripts/search.py` |
| **محیط توسعه WAMP** | Apache + MySQL + MariaDB | سرویس‌ها فعالند | `wampapache64` و `wampmysqld64` |
| **PHP** | 8.2.29 (WAMP) | متصل به PATH | `php -v` |
| **WP-CLI** | 2.12.0 | نصب در WAMP bin و آماده در PATH | `wp core version` (خروجی: 7.1.4) |
| **WooCommerce** | 10.5.2 + ووکامرس فارسی 10.0.2 | فعال روی سایت wp4 | `wp plugin list` |
| **Playwright** | 1.65.0 | نصب و تایید شد | `npx playwright --version` |

---

## ۲. فیچر جاری و وضعیت تسک‌ها

- **فاز جاری:** Feature 001: Foundation
- **وضعیت فیچر 001:** `CONVERGED / VERIFIED`
  - [x] T001: فایل `style.css` با هدر کامل وردپرس و تعاریف پایه
  - [x] T002: فایل `theme.json` نسخه ۳ با توکن‌های پالت رنگی، فونت و فاصله‌گذاری
  - [x] T003: فایل `functions.php` جهت بوت‌استرپ و لود امن ماژول‌ها
  - [x] T004: فایل `inc/setup.php` (عنوان سایت، بندانگشتی‌ها، HTML5، منوها و تکست‌دومین)
  - [x] T005: فایل‌های `assets/css/main.css` و `rtl.css` با استایل‌های پایه راست‌چین و سیستم طراحی
  - [x] T006: فایل `assets/js/main.js` با کنترلر ماژولار وانیلا JS و سیستم اعلان‌ها
  - [x] T007: فایل `inc/enqueue.php` برای لود فونت Vazirmatn، استایل‌ها و لوکالایز پارامترها
  - [x] T008: فایل `inc/woocommerce.php` با تعاریف `add_theme_support` و ساختار رپر کانتینر
  - [x] T009: فایل `inc/template-tags.php` برای هلپرهای امن متادیتا و تخفیف‌ها
  - [x] T010: تمپلیت‌های استاندارد HTML5 شامل `header.php`، `footer.php` و `index.php`
  - [x] T011: تایید نهایی دروازه کیفیت، سلامت قالب و فعال‌سازی در وردپرس محلی (HTTP 200)

- **فیچر بعدی:** Feature 002: Header & Footer (تاپ‌بار، لوگو، مگامنو، سرچ ایجکس، مینیکارت، منوی موبایل و فوتر)

---

## ۳. سوالات باز و نیازمندی‌های ورودی

تمام سوالات مسدودکننده فاز ۰ و ۱ پاسخ داده شدند. به مرحله توسعه فاز ۲ (Feature 002) وارد می‌شویم.
