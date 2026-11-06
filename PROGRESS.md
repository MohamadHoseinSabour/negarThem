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

### فیچر 003: Home Page
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T040: کامپوننت کارت محصول استاندارد و چندمنظوره (`template-parts/product/card.php`) با بج تخفیف، دکمه خرید، علاقه‌مندی و پیش‌نمایش سریع
  - [x] T041: هیرو سکشن اصلی شامل اسلایدر بزرگ و ۲ بنر تبلیغاتی کناری (`HOME-01`)
  - [x] T042: حباب‌های دسته‌بندی سریع محصولات با آیکون و شمارنده محصولات (`HOME-02`)
  - [x] T043: بخش شگفت‌انگیزها با باکس قرمز زرشکی، تایمر معکوس زنده و اسلایدر کالاهای تخفیف‌دار (`HOME-03`)
  - [x] T044: گرید بنرهای چندگانه تبلیغاتی میانی با ترنزیشن‌های نرم (`HOME-04`)
  - [x] T045: تب‌های محصولات منتخب (جدیدترین‌ها، پرفروش‌ترین‌ها، حراجی) با سوییچ آنی (`HOME-05`)
  - [x] T046: اسلایدر لوگوهای برندهای برتر همکار با فیلتر خاکستری و هاور رنگی (`HOME-06`)
  - [x] T047: شوکیس دسته‌بندی با بنر عمودی و کاروسل محصولات اختصاصی (`HOME-07`)
  - [x] T048: ویترین مقالات وبلاگ و اخبار با تصاویر ۱۶:۹ و متادیتا (`HOME-08`)
  - [x] T049: تمپلیت یکپارچه `front-page.php` با چینش منطقی و پشتیبانی از برگه المنتوری
  - [x] T050: استایل‌های اختصاصی `assets/css/home.css` برای تمامی بخش‌های صفحه اصلی
  - [x] T051: اسکریپت تعاملی `assets/js/home.js` (کنترلر کاروسل‌های وانیلا، تایمر معکوس، تب‌ها)
  - [x] T052: راه‌انداز ویجت‌های المنتور (`inc/elementor/class-elementor-init.php`)
  - [x] T053: ثبت اسکرین‌شات‌ها در ۴ عرض و به‌روزرسانی ماتریس پوشش (وضعیت `verified`)

### فیچر 004: Shop & Archive
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T060: توابع کمکی فروشگاه، بریدکرامب معنایی با schema.org و استخراج محدوده قیمت و چیپ‌ها (`inc/shop.php`)
  - [x] T061: اندپوینت ایجکس فیلتر محصولات با اعتبارسنجی نانس، کوئری‌های تاکسونومی، متادیتا و متدهای مرتب‌سازی (`inc/shop-ajax.php`)
  - [x] T062: نوار ابزار فروشگاه (`SHOP-02`) شامل دکمه فیلتر موبایل، شمارنده محصولات، سلکتور مرتب‌سازی، سوییچر گرید/لیست و چیپ‌های فعال
  - [x] T063: سایدبار فیلترها (`SHOP-03`) با لیست درختی دسته‌ها و شمارنده، کلیدهای مدرن سوئیچی موجودی و حراجی، فیلد قیمت و دکمه حذف فیلترها
  - [x] T064: لوپ محصولات (`SHOP-04`, `SHOP-05`) با پشتیبانی از گرید و لیست، لودر چرخشی ترنزیشن، وضعیت خالی و صفحه‌بندی عددی
  - [x] T065: قالب یکپارچه `woocommerce/archive-product.php` (`SHOP-01`) با سایدبار دسکتاپ و دراور کشویی ریسپانسیو موبایل
  - [x] T066: استایل‌های اختصاصی `assets/css/shop.css` شامل چیدمان سایدبار، دراور متحرک موبایل، حالت نمایش افقی لیست و استایل صفحه‌بندی
  - [x] T067: اسکریپت کلاینت `assets/js/shop.js` با پشتیبانی از فیلتر زنده ایجکس، ذخیره سلیقه نمایش در LocalStorage، مدیریت تاریخچه مرورگر با PushState و کنترل دراور موبایل
  - [x] T068: انکیو و لود هوشمند اسکریپت‌ها و استایل‌ها در آرشیوهای فروشگاه با پارامترهای بومی‌سازی‌شده
  - [x] T069: ثبت ۶ اسکرین‌شات در ویوپورت‌های مختلف و به‌روزرسانی ردیف‌های `SHOP-01` تا `SHOP-05` به `verified`

### فیچر 005: Single Product
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T070: توابع کمکی تک‌محصول، استخراج جدول مشخصات فنی و داده‌های ۴ مزیت خرید (`inc/single-product.php`)
  - [x] T071: گالری محصول (`PROD-01`) با تصویر شاخص، بج تخفیف، دکمه زوم و نوار افقی بندانگشتی‌ها
  - [x] T072: جعبه مزایای خرید و اطمینان تحویل (`PROD-03`) شامل اصالت کالا، بازگشت ۷ روزه، ارسال اکسپرس و گارانتی
  - [x] T073: تب‌های توضیحات تکمیلی (`PROD-04`) شامل نقد و بررسی، جدول مشخصات فنی دورنگ و دیدگاه‌های خریداران با امتیاز ستاره‌ای
  - [x] T074: کاروسل محصولات مشابه و مکمل (`PROD-05`) با بهره‌گیری از کارت استاندارد محصول
  - [x] T075: مونتاژ قالب کامل ۲ ستونه `woocommerce/single-product.php` و `woocommerce/content-single-product.php`
  - [x] T076: استایل‌های اختصاصی `assets/css/single-product.css` برای گالری، استپرهای تعداد، دکمه خرید، سواچ‌ها و جدول مشخصات
  - [x] T077: اسکریپت تعاملی `assets/js/single-product.js` جهت سوئیچ تصویر با کلیک تامبنیل، بازشدن لایت‌باکس، دکمه‌های مثبت/منفی تعداد و تب‌ها
  - [x] T078: انکیو استایل‌ها و اسکریپت‌های اختصاصی فقط در صفحات تکی محصول
  - [x] T079: ثبت ۵ اسکرین‌شات در ۴ عرض، تایید رفتار استپر تعداد و سوییچ تب مشخصات، و به‌روزرسانی ردیف‌های `PROD-01` تا `PROD-05` به `verified`

---

## ۲. اسکرین‌شات‌های ثبت‌شده
- [home-mobile-375.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-mobile-375.png) (صفحه اصلی موبایل ۳۷۵px)
- [home-tablet-768.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-tablet-768.png) (صفحه اصلی تبلت ۷۶۸px)
- [home-desktop-1024.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-desktop-1024.png) (صفحه اصلی دسکتاپ ۱۰۲۴px)
- [home-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-wide-1440.png) (صفحه اصلی دسکتاپ عریض ۱۴۴۰px)
- [shop-mobile-375.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-mobile-375.png) (فروشگاه موبایل با نوار ابزار و گرید ۲ ستونه)
- [shop-mobile-drawer-open-375.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-mobile-drawer-open-375.png) (فروشگاه موبایل با دراور کشویی فیلترها بازشده)
- [shop-tablet-768.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-tablet-768.png) (فروشگاه تبلت ۳ ستونه)
- [shop-desktop-1024.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-desktop-1024.png) (فروشگاه دسکتاپ با سایدبار راست)
- [shop-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-wide-1440.png) (فروشگاه عریض با سایدبار و گرید ۴ ستونه)
- [shop-wide-list-view-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-wide-list-view-1440.png) (حالت نمایش ردیفی کالاها در دسکتاپ عریض)
- [product-mobile-375.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-mobile-375.png) (صفحه محصول موبایل ۳۷۵px)
- [product-tablet-768.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-tablet-768.png) (صفحه محصول تبلت ۷۶۸px)
- [product-desktop-1024.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-desktop-1024.png) (صفحه محصول دسکتاپ ۱۰۲۴px)
- [product-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-wide-1440.png) (صفحه محصول عریض با گالری و باکس خرید)
- [product-wide-specs-tab-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-wide-specs-tab-1440.png) (تب مشخصات فنی راه‌راه در دسکتاپ)

---

## ۳. فیچر بعدی
- **Feature 006: Cart / Checkout / My Account (`FLOW-02` تا `FLOW-06`)**: صفحه سبد خرید مدرن با تنظیم ایجکس تعداد، فیلد کوپن، صفحه تسویه حساب بهینه‌شده، صفحه سپاس سفارش، و داشبورد حساب کاربری با فرم ورود/ثبت‌نام تمیز.
