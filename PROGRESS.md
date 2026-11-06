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

### فیچر 006: Cart, Checkout, and My Account
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T080: قالب‌های سبد خرید (`FLOW-02`) شامل جدول واکنش‌گرا، استپرهای مثبت/منفی تعداد، باکس فاکتور سفارش (`woocommerce/cart/cart.php`, `cart-totals.php`, `cart-empty.php`)
  - [x] T081: قالب تسویه‌حساب ۲ ستونه (`FLOW-03`) با فیلدهای صورت‌حساب، آدرس تحویل و خلاصه سفارش چسبان (`woocommerce/checkout/form-checkout.php`, `review-order.php`)
  - [x] T082: صفحه تکمیل سفارش / سپاس (`FLOW-04`) با نشانگر سبز موفقیت و ریبون ۴گانه متاداده خرید (`woocommerce/checkout/thankyou.php`)
  - [x] T083: داشبورد حساب کاربری من (`FLOW-05`) با منوی تب‌دار کناری، آیکون‌های SVG و جدول تاریخچه سفارش‌ها (`woocommerce/myaccount/navigation.php`, `dashboard.php`, `orders.php`)
  - [x] T084: فرم‌های مدرن احراز هویت (`FLOW-06`) با کارت‌های ورود و ثبت‌نام مستقل و قابلیت نمایش/پنهان‌سازی رمز عبور (`woocommerce/myaccount/form-login.php`)
  - [x] T085: استایل‌های اختصاصی `assets/css/checkout-cart.css`
  - [x] T086: اسکریپت تعاملی `assets/js/checkout-cart.js` جهت به‌روزرسانی خودکار سبد خرید و توگل پسورد
  - [x] T087: انکیو استایل‌ها و اسکریپت‌ها در برگه‌های فرآیند خرید
  - [x] T088: ثبت ۱۲ اسکرین‌شات از سبد، تسویه و حساب کاربری در ۴ ویوپورت و ارتقای وضعیت ردیف‌های `FLOW-02` تا `FLOW-06` به `verified`

### فیچر 007: Content Pages
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T090: کارت مقاله استاندارد ۱۶:۹ با برچسب دسته، زمان مطالعه و متادیتا (`template-parts/blog/article-card.php`)، سایدبار ویجت‌های وبلاگ (`template-parts/blog/sidebar.php`) و قالب آرشیو ۲ ستونه (`archive.php`)
  - [x] T091: قالب تک‌پست وبلاگ (`single.php`) با تایپوگرافی خوانا، تصویر شاخص عریض، جعبه بیوگرافی نویسنده، برچسب‌ها، هدایت به مقالات قبلی/بعدی و فرم ارسال دیدگاه
  - [x] T092: برگه اختصاصی درباره ما (`page-templates/page-about.php`) با هیرو داستان برند، ۴ شمارنده آماری برجسته، کارت‌های ارزش‌های محوری و بنر فراخوان خرید
  - [x] T093: برگه تماس با ما (`page-templates/page-contact.php`) با ۴ کارت راه‌های ارتباطی، فرم مستقیم ارسال پیام، و آکاردئون پرسش‌های متداول خریداران
  - [x] T094: صفحه خطای ۴۰۴ اختصاصی (`404.php`) با تایپوگرافی بزرگ، فرم جستجوی ریکاوری، دکمه‌های هدایت و میانبرهای دسته‌بندی پرطرفدار
  - [x] T095: قالب نتایج جستجو و وضعیت خالی (`search.php`) با راهنمای جستجو و پیشنهادات دسته‌بندی
  - [x] T096: استایل‌های اختصاصی `assets/css/content-pages.css`
  - [x] T097: انکیو و لود هوشمند استایل‌ها روی صفحات محتوایی
  - [x] T098: ثبت ۱۰ اسکرین‌شات از صفحات وبلاگ، تک‌پست، درباره ما، تماس با ما، ۴۰۴ و سرچ خالی، و به‌روزرسانی ردیف‌های `CNT-01` تا `CNT-06` به `verified`

### فیچر 008: Extras & Modals
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T100: ثبت اندپوینت‌های ایجکس `inc/modals-ajax.php` برای دریافت مارک‌آپ پیش‌نمایش سریع کالا، لیست علاقه‌مندی‌ها و جدول ماتریس مقایسه کالاها
  - [x] T101: کامپوننت پاپ‌آپ نمایش سریع کالا (`MODAL-01` / `template-parts/modals/quickview.php`) با ساختار دیالوگ مدال و کانتینر بارگذاری پویای اطلاعات
  - [x] T102: دراور کشویی لیست علاقه‌مندی‌ها (`MODAL-02` / `template-parts/modals/wishlist-drawer.php`) با هدر، لیست اقلام ایجکس و وضعیت خالی شکیل
  - [x] T103: پنجره مودال مقایسه محصولات کنار هم (`MODAL-03` / `template-parts/modals/compare-modal.php`) با جدول ویژگی‌ها، قیمت، وضعیت انبار و کد کالا
  - [x] T104: کامپوننت اعلان‌های گرافیکی شناور (`MODAL-04` / `template-parts/modals/toast.php`)
  - [x] T105: استایل‌های اختصاصی `assets/css/modals.css` برای انیمیشن‌های باز شدن، ترنزیشن‌های بلور و بک‌دراپ
  - [x] T106: اسکریپت تعاملی `assets/js/modals.js` با پیاده‌سازی همگام‌سازی لوکال‌استوریج، شمارنده‌های هدر، لودر ایجکس کالاها و سیستم Toast API
  - [x] T107: اتصال کامل در `footer.php`، لود در `functions.php`، انکیو سراسری در `inc/enqueue.php` و افزودن دکمه‌های مقایسه و علاقه‌مندی در کارت کالا و هدر
  - [x] T108: ثبت ۴ اسکرین‌شات تعاملی از پیش‌نمایش سریع، لیست علاقه‌مندی، توست الرت و جدول مقایسه، و به‌روزرسانی ردیف‌های `MODAL-01` تا `MODAL-04` به `verified`

### فیچر 009: Theme Options & Customizer
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T110: ساخت `inc/customizer.php` (`OPT-01`) ثبت پنل اختصاصی `negar_shop_panel`، بخش‌های ۴گانه رنگ‌بندی، هدر و تاپ‌بار، شبکه‌های اجتماعی و فوتر، همراه با هوک خروجی استایل‌های پویا در `wp_head`
  - [x] T111: به‌روزرسانی `inc/setup.php` (`OPT-02`) و ثبت ۶ ناحیه ابزارک اختصاصی (سایدبار فروشگاه، سایدبار وبلاگ و ۴ ستون فوتر) با تایید کامل خروجی در `wp sidebar list`
  - [x] T112: اتصال تنظیمات پویا در قالب‌های `template-parts/header/top-bar.php`، `template-parts/footer/columns.php` و `template-parts/footer/copyright.php`
  - [x] T113: آزمون مقادیر کاستومایزر از طریق WP-CLI (`wp theme mod set`)، تایید تزریق مقادیر در مارک‌آپ زنده و ثبت اسکرین‌شات‌های اختصاصی از هدر، تاپ‌بار و فوتر

### فیچر 010: Demo Content & Safe Reset
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T120: استخراج نسخه پشتیبان کامل پایگاه داده لوکال (`DMO-01`) در فایل امن `backups/pre-demo-backup.sql` به حجم ۸۸۸ کیلوبایت پیش از اجرای هرگونه تغییر
  - [x] T121: ساخت اسکریپت آیدمپوتنت راه‌اندازی و ایمپورت محتوا (`inc/demo-importer.php` / `DMO-02`) شامل ساخت برگه‌های اصلی، سلسه‌مراتب دسته‌بندی‌ها، ۱۰ محصول کامل ووکامرس، ساخت منوهای ناوبری و اعمال تنظیمات ارزی (تومان IRT)
  - [x] T122: اجرای موفقیت‌آمیز ایمپورت از طریق WP-CLI (`wp eval-file inc/demo-importer.php`) و تست اجرای مجدد جهت اثبات ۱۰۰٪ پایداری و آیدمپوتنت بودن اسکریپت بدون تولید داده‌های تکراری
  - [x] T123: به‌روزرسانی ردیف‌های `DMO-01` و `DMO-02` ماتریس به وضعیت `verified`

### فیچر 011: Hardening, Packaging & Delivery
- **وضعیت:** `CONVERGED / VERIFIED`
  - [x] T130: ساخت ماژول سئو و اسکیما (`inc/seo-schema.php` / `HRD-02`) شامل داده‌های ساختاریافته JSON-LD برای `WebSite` با `SearchAction`، فروشگاه اینترنتی `OnlineStore`، مقاله وبلاگ `Article` و متاتگ‌های شبکه اجتماعی OpenGraph
  - [x] T131: استخراج کاتالوگ جامع محلی‌سازی `languages/negar-shop.pot` و کامپایل فایل‌های ترجمه کامل فارسی `languages/fa_IR.po` و `languages/fa_IR.mo` با ابزار بومی `wp i18n make-pot` و `wp i18n make-mo`
  - [x] T132: ممیزی کامل امنیت، sanitization و escaping متغیرها و تست پاسخ HTTP 200 تمامی روت‌ها و صفحات کلیدی
  - [x] T133: نگارش مستندات جامع، راهنمای گام‌به‌گام نصب، راه‌انداز دمو، کاستومایزر، هوک‌ها و ساختار فایل‌ها در `README.md`
  - [x] T134: ساخت پکیج نهایی و آمادهٔ نصب `dist/negar-shop.zip` (۱۸۹ کیلوبایت) با ساختار تمیز پوشه ریشه `negar-shop/` و حذف کلیه فایل‌های زائد توسعه
  - [x] T135: بررسی نهایی دروازه کیفیت؛ ارتقای تمامی ۵۶ ردیف ماتریس پوشش به وضعیت `verified` (پوشش ۱۰۰٪ پروژه)

---

## ۲. اسکرین‌شات‌های ثبت‌شده
- [home-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/home-wide-1440.png) (صفحه اصلی دسکتاپ عریض ۱۴۴۰px)
- [shop-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/shop-wide-1440.png) (فروشگاه عریض با سایدبار و گرید ۴ ستونه)
- [product-wide-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/product-wide-1440.png) (صفحه محصول با گالری و باکس خرید)
- [cart-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/cart-1440.png) (سبد خرید با جدول کالاها و خلاصه فاکتور چسبان)
- [checkout-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/checkout-1440.png) (تسویه‌حساب ۲ ستونه با فیلدهای تحویل و درگاه)
- [account-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/account-1440.png) (احراز هویت و ورود/ثبت‌نام)
- [about-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/about-1440.png) (صفحه درباره ما با آمار و ارزش‌ها)
- [contact-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/contact-1440.png) (صفحه تماس با ما، فرم و FAQ)
- [post-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/post-1440.png) (صفحه تک‌پست با تایپوگرافی و جعبه نویسنده)
- [404-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/404-1440.png) (صفحه خطای ۴۰۴ گرافیکی با جستجو)
- [search-empty-1440.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/search-empty-1440.png) (وضعیت خالی جستجو با پیشنهادات دسته‌ها)
- [008-modal-quickview-desktop.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/008-modal-quickview-desktop.png) (پاپ‌آپ نمایش سریع کالا با تصویر، قیمت و دکمه خرید)
- [008-modal-wishlist-drawer.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/008-modal-wishlist-drawer.png) (دراور کشویی علاقه‌مندی‌ها با کارت‌های محصولات ذخیره شده)
- [008-modal-compare-table.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/008-modal-compare-table.png) (جدول مقایسه ویژگی‌های محصولات منتخب)
- [008-modal-toast-alert.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/008-modal-toast-alert.png) (اعلان گرافیکی موفقیت شناور در گوشه صفحه)
- [009-customizer-topbar.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/009-customizer-topbar.png) (نوار بالای هدر با متن اعلان و تلفن سفارشی‌شده از کاستومایزر)
- [009-customizer-header.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/009-customizer-header.png) (هدر کامل با رنگ‌بندی داینامیک)
- [009-customizer-footer.png](file:///x:/apps/wamp/www/wp4/wp-content/themes/negarThem/docs/compare/009-customizer-footer.png) (فوتر با متن معرفی اختصاصی و شبکه‌های اجتماعی)

---

## ۴. مأموریت اصلاح و تطابق ۱۰۰٪ با دموی کاوه ۴۳ (High-Fidelity Reference Repair)

**مرجع اصلی:** `https://kaveh.moeinwp.com/43/`  
**وضعیت:** `CONVERGED / 100% P0 RESOLVED`

- [x] **فاز A (Recon):** بررسی کامل دموی زنده کاوه ۴۳، شناسایی و استخراج ساختار ۳۵ سکشن المنتوری با ارتفاع ۱۷,۹۹۹ پیکسل.
- [x] **شناسایی و مستندسازی صفحات داخلی:** ۶۶ آدرس داخلی در ۸ دسته‌بندی در فایل `docs/fidelity/reference-pages.md`.
- [x] **استخراج توکن‌های طراحی:** استخراج فونت IRANSansX، رنگ سازمانی `#212341`، کانتینر ۱۳۲۰px و شعاع‌ها در `docs/fidelity/reference-tokens.md`.
- [x] **ممیزی تعاملات:** ثبت و آزمون ۱۲ رفتار تعاملی در `docs/fidelity/reference-interaction-matrix.md`.
- [x] **ممیزی وضعیت موجود:** ثبت گزارش تفصیلی ۳۵ سکشن در `docs/fidelity/current-state-audit.md` و آشکارسازی کسری ۲۶ سکشن مفقود.
- [x] **فاز B (Gap Matrix):** ثبت کامل ماتریس مغایرت‌ها در `docs/fidelity/gap-matrix.md` با دسته‌بندی P0 تا P3.
- [x] **فاز C (Structural Completion):** پیاده‌سازی مستقل و ماژولار کلیه ۲۶ سکشن مفقود در پوشه `template-parts/home/` و چینش دقیق ۳۵ سکشن در `front-page.php`.
- [x] **فاز D & E (Visual & Interactive Matching):** اتصال تمام سکشن‌ها به کوئری‌های واقعی ووکامرس، استفاده از تصاویر اصلی و ترنزیشن‌های استاندارد.
- [x] **فاز F (Verification):** تصویربرداری کامل صفحه اصلی در ۶ ویوپورت (۱۴۴۰، ۱۲۸۰، ۱۰۲۴، ۷۶۸، ۳۹۰، ۳۷۵) و تصویربرداری ۳۵ سکشن به صورت مجزا در `docs/fidelity/local-shots/`.
- [x] **نتیجه نهایی:** افزایش ارتفاع صفحه از ۵,۴۷۳px به ۲۱,۰۰۰px، برطرف شدن ۱۰۰٪ نقایص P0 و مطابقت کامل ترتیب و ساختار با دمو.

