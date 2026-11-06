# گزارش پیشرفت پروژه (PROGRESS.md)

**پروژه:** ساخت قالب وردپرس فروشگاهی حرفه‌ای (WooCommerce) با روش Spec-Driven  
**نام قالب:** Negar Shop (`negar-shop`)  
**مسیر قالب:** `x:\apps\wamp\www\wp4\wp-content\themes\negarThem`  
**دموی مرجع:** `https://kaveh.moeinwp.com/43/#`  
**تاریخ به‌روزرسانی:** 2026-11-06  

---

## ۱. وضعیت فازها و فیچرها

### فیچر 001: Foundation
- **وضعیت:** `CONVERGED / VERIFIED`
- تایید اسکلت قالب، theme.json توکن‌ها، راست‌چین RTL، انکیو فونت Vazirmatn و هوک‌های ووکامرس.

### فیچر 002: Header & Footer
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T020: اندپوینت جستجوی زنده ایجکس محصولات همراه با نانس و اعتبارسنجی
  - [x] T021: کلاس واکر منو (Negar_Shop_Mega_Menu_Walker) با قابلیت مگامنو، گرید و بج‌ها
  - [x] T022: کامپوننت تاپ‌بار (HDR-01) با اعلان حراجی و تلفن پشتیبانی
  - [x] T023: هدر اصلی (HDR-02) با لوگو، فرم سرچ ایجکس، دکمه حساب، علاقه‌مندی و سبد
  - [x] T024: نوار ناوبری اصلی (HDR-03) با منوی بازشونده دسته‌بندی و لینک شگفت‌انگیزها
  - [x] T025: دراور کشویی سبد خرید (FLOW-01 / mini-cart drawer)
  - [x] T026: کامپوننت‌های موبایل (HDR-05) شامل هدر فشرده، دراور تب‌دار و نوار ناوبری چسبان پایین (۵ دکمه)
  - [x] T027: کامپوننت‌های فوتر (FTR-01 تا FTR-05) شامل ۵ کارت مزایا، خبرنامه، ۴ ستون لینک، نمادهای اعتماد و دکمه بازگشت به بالا
  - [x] T028: استایل‌های اختصاصی `assets/css/header-footer.css`
  - [x] T029: اسکریپت تعاملی `assets/js/header-footer.js` (سرچ ایجکس، هدر استیکی، دراورها، تب‌ها و اسکرول به بالا)
  - [x] T030: اتصال کامل در `header.php`، `footer.php` و `functions.php`
  - [x] T031: ثبت اسکرین‌شات‌ها در ۴ عرض ۳۷۵، ۷۶۸، ۱۰۲۴، ۱۴۴۰ پیکسل در `docs/compare/` و تایید دروازه کیفیت

---

## ۲. اسکرین‌شات‌های ثبت‌شده
- [home-mobile-375.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-mobile-375.png) (موبایل با نوار پایین و هدر موبایل)
- [home-tablet-768.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-tablet-768.png) (تبلت)
- [home-desktop-1024.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-desktop-1024.png) (لپ‌تاپ)
- [home-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-wide-1440.png) (دسکتاپ عریض کامل)

---

## ۳. فیچر بعدی
- **Feature 003: Home Page** (اسلایدر هیرو، دسترسی سریع دسته‌ها، اسلایدر شگفت‌انگیزها با تایمر معکوس، بنرهای میانی، تب‌های محصولات منتخب، اسلایدر برندها و آخرین مقالات وبلاگ طبق inventory)
