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

---

## ۵. فیچر 013: باشگاه مشتریان پیشرفته نگار (NegarThem Advanced Customer Club)

**شاخه توسعه:** `feat/013-customer-club`
**مسیر مستندات:** `specs/013-customer-club/`
**Baseline مصوب:** `eda9740a9943ab54c2eeb8161498b6556e1533d6`
**وضعیت کلی:** `IN PROGRESS (Plan Approved & Tasks Generated — Ready for Phase 3 Implementation upon Authorization)`

- [x] **Phase 0 — Repository Audit & Discovery:** ممیزی کامل کدبیس، محیط WAMP (`WP 7.1.4`, `WC 10.5.2`, `HPOS=yes`, `IRT` با ۰ رقم اعشار)، تنظیمات قالب، سیستم طراحی، مسیرهای WooCommerce/My Account، ابزارها و ریسک‌ها (تأییدشده).
- [x] **Phase 1 — Specification & Business Rules:**
  - تأیید baseline جدید `eda9740` (شامل رفع باگ تگ `<?php` خط ۳۷۱۲ در `inc/theme-settings.php` و تفکیک تصویر نماد زیبال از درگاه فعال) و ایجاد شاخهٔ `feat/013-customer-club`.
  - تدوین مشخصات جامع در `specs/013-customer-club/spec.md` (`Status: Draft`) شامل ۱۴ داستان کاربری (`CLUB-US-01`–`14`)، ۴۰ نیازمندی کارکردی (`CLUB-FR-001`–`040`)، ۲۴ نیازمندی غیرکارکردی/امنیتی/UI (`CLUB-NFR-001`–`024`) و ۱۰ معیار موفقیت (`CLUB-SC-001`–`010`).
  - تدوین رجیستر قوانین تجاری و ۲۳ تصمیم باز (`CLUB-BD-001`–`022` و `CLUB-AD-001`) با مثال‌های عددی در `specs/013-customer-club/business-rules.md`.
  - تدوین ماتریس ردیابی ۱۰۰٪ نیازمندی‌ها به معیارهای پذیرش (`CLUB-AC-001`–`064` با وضعیت `NOT RUN`) در `specs/013-customer-club/acceptance-matrix.md` و چک‌لیست کیفیت مشخصات در `specs/013-customer-club/checklists/requirements.md`.
  - افزودن ۹ ردیف جدید (`CLUB-PAGE-01` تا `CLUB-SEC-01`) با وضعیت `todo` به `specs/coverage-matrix.md`.
- [x] **Phase 2 — Architecture, Technical Design & Implementation Planning:**
  - اعمال اصلاحات چهارگانه مستندات Phase 1 (C1: تفکیک احراز هویت وب‌هوک از نانس مرورگر، C2: اعتبارسنجی سه‌گانه حسابداری اقساط بدون انحراف ریالی، C3: کامپوننت زودهنگام ماشین‌حساب سروری Phase 4، C4: تصحیح شمارش به ۲۲ تصمیم تجاری و ۱ تصمیم معماری) در کامیت `ee67db7` (`T303`).
  - تدوین سند تصمیمات معماری (ADR) در `architecture-decisions.md` جهت تفکیک لایه پوسته از پلاگین پایدار مالی (`CLUB-AD-001`) با وضعیت `PROPOSED — REQUIRES EMPLOYER APPROVAL` در کامیت `145e259` (`T304`).
  - تدوین مستند تحقیق و تحلیل فنی `research.md` (شامل اتصال HPOS ووکامرس، ماشین‌های وضعیت، مرز تبدیل IRT/IRR و رویه بکاپ لوکال WAMP) و مدل داده `data-model.md` با ۵ جدول اختصاصی در کامیت `145e259` (`T304`).
  - تدوین ۳ کانترکت یکپارچه‌سازی در پوشه `contracts/` (`quote-calculator-api.md`, `application-lifecycle-api.md`, `payment-webhook-contract.md`) و راهنمای توسعه‌دهنده `quickstart.md` در کامیت `32ff1b6` (`T305`).
  - تدوین سند برنامه اجرایی `plan.md` منطبق با الگوی Spec Kit و تحلیل دروازه بازبینی گردش کار (`review-plan [gate]`).
- [x] **Phase 2.1 — Architecture Corrections & Design Consistency:**
  - اعمال اصلاحات ۸‌گانه بازبین فنی (C1 تا C8) در قالب اسناد معماری، مدل داده و قراردادها بدون نوشتن کد اپلیکیشن.
  - اصلاح C1: طراحی معماری دقیق حسابداری ووکامرس (Alternative 1) با اعمال تسهیلات اقساطی به عنوان خط منفی در تسویه‌حساب جهت مطابقت ۱۰۰٪ مبالغ درگاه و گزارش‌های مالی.
  - اصلاح C2: جایگزینی کامل الگوریتم لوهن با فرمول رسمی Modulo 11 کد ملی ایران همراه با بردارهای آزمون راستی‌آزمایی‌شده ریاضی.
  - اصلاح C3: تدوین مدل داده جامع در `data-model.md` شامل جدول اختصاصی مرجوعی `wp_negar_club_refunds`، فیلدهای پولی `BIGINT UNSIGNED`، تفکیک ترازهای متحرک از اسنپ‌شات اولیه، و تطابق کامل دیاگرام ER با اسکیما.
  - اصلاح C4: طراحی معماری وب‌هوک مستقل از ارائه‌دهنده با آداپتورهای سه‌گانه، حذف الزام امضای عمومی برای درگاه‌های استعلامی، ثبت و ردیابی مرحله‌به‌مرحله Idempotency، و شرط الزامی بخش‌پذیری ریال `amount_irr % 10 === 0`.
  - اصلاح C5: یکسان‌سازی وضعیت اولیه به `pending_review` و تصحیح ارجاع مهلت انقضا به `CLUB-BD-018` (پیشنهاد ۱۴ روزه مشروط به تایید کارفرما).
  - اصلاح C6: اصلاح نام‌گذاری گزینه B مازاد سقف، تعیین حداقل خرید متغیر طبق `CLUB-BD-005`، تعیین قسط اول به عنوان پذیرنده باقیمانده غیرقابل‌تقسیم (پیشنهادی طبق `CLUB-BD-022`)، و شفاف‌سازی امنیت اندپوینت عمومی (محدودیت نرخ و اعتبارسنجی ورودی بدون اتکا به نانس مهمان).
  - اصلاح C7: تدوین مشخصات عملیاتی گزینه B در `architecture-decisions.md` (شامل دایرکتوری `packages/negar-customer-club/`، تنظیمات خروج از پکیج پوسته، Junction ویندوز و پکیج زیپ مستقل) و استقرار تنها یک نسخه مرجع از کلاس ماشین‌حساب.
  - اصلاح C8: ریشه‌یابی و مستندسازی کامل علت خطای `test:settings` در آزمون‌های پیشین (حذف `compact-12-one` از `bannerSectionMap` در اسکریپت تست) و رعایت اکید ممنوعیت اجرای مجدد تست علیه دیتابیس لوکال.
  - ممیزی سراسری سازگاری اسناد و حفظ توقف قانونی در فاز ۲ بدون ایجاد `tasks.md` و بدون دور زدن گیت `review-plan`.
- [x] **Phase 2.2 — Final Architecture Consistency & Financial Lifecycle Audit:**
  - ممیزی و بازبینی جامع چرخه عمر مالی، سازگاری معماری حسابداری ووکامرس، مدل داده و وب‌هوک در اسناد فاز ۲ بدون نوشتن کد اپلیکیشن و بدون تغییر دیتابیس.
  - اصلاح C1: ارزیابی عمیق معماری حسابداری ووکامرس بر مبنای سورس‌کد ووکامرس 10.5.2 (`class-wc-order-item-fee.php:6, 170`) و رد استفاده از خط هزینه منفی؛ مقایسه دقیق سه گزینه بر اساس ۱۰ معیار حسابداری و توصیه به گزینه C (درگاه اختصاصی `WC_Gateway_Negar_Installment`) با وضعیت مشروط به تایید کارفرما (`PROPOSED — REQUIRES EMPLOYER APPROVAL`).
  - اصلاح C3/C4: اصلاح اسکیما و قرارداد جدول پرداخت‌ها `wp_negar_club_payments` (اختیاری شدن `contract_id` در مرحله پیش‌پرداخت تسویه، اختیاری شدن `provider_tx_id` هنگام شروع، تعریف رسمی توکن یکتای کلاینت `client_token` به صورت UUIDv4، فیلدهای پولی تفکیک‌شده `amount_internal_irt` و `gateway_amount` و `gateway_currency`، و تفکیک زمینه‌های پرداخت `payment_context`).
  - اصلاح C3: تکمیل مدل تخصیص مرجوعی در `wp_negar_club_refunds` (فیلدهای `principal_refunded_irt` و `charges_refunded_irt`، انطباق با قسط مرجع، حفظ تغییرناپذیری تعهدات اولیه قرارداد و محاسبه ترازهای متحرک طبق `CLUB-BD-014`).
  - اصلاح C4: معماری دومرحله‌ای تراکنش وب‌هوک (Stage A: استعلام و احراز هویت خارجی بدون نگه‌داشتن قفل دیتابیس؛ Stage B: تسویه محلی اتمیک با قفل ردیف `FOR UPDATE` با ترتیب ضدددلاک `contracts` -> `installments` -> `payments`).
  - اصلاح C3: انطباق دقیق عبارات اعتبارسنجی در `CLUB-AC-029` و `CLUB-FR-029` با فیلدهای واقعی اسکیما (`financed_principal`, `total_charges`, `total_customer_obligation`).
  - اصلاح C5: تثبیت کامل `pending_review` به عنوان وضعیت پیش‌فرض و اولیه در تمام اسناد (حذف واژه `submitted` از وضعیت‌ها) و حفظ برچسب پیشنهادی مهلت ۱۴ روزه طبق `CLUB-BD-018`.
  - اصلاح C7: تدوین رویه امن و غیرمخرب رول‌بک و مایگریشن با نگهداری دیتای مشتری و منع حذف جداول در محیط پروداکشن.
  - اصلاح C8: بازطبقه‌بندی وضعیت یافته‌های تستی به `DIAGNOSED — NOT CODE-FIXED / NOT RE-VERIFIED` با استناد به سورس‌کد `tests/theme-settings-regression.js:275-293` بدون دستکاری دیتابیس یا اجرای مجدد تست.
  - رعایت توقف قانونی (Mandatory Stop) در فاز ۲ بدون ایجاد `tasks.md` و بدون تایید غیرمجاز گیت `review-plan`.
- [x] **Phase 2.3 — Critical Financial Architecture Corrections (Minimal Scope):**
  - اعمال اصلاحات مالی و حسابداری ضروری (C1 تا C5) در معماری سیستم باشگاه مشتریان نگار بدون تغییر کد اپلیکیشن و بدون دستکاری دیتابیس (حالت مستندات محض).
  - اصلاح C1 (حسابداری و وضعیت پرداخت ووکامرس): بررسی دقیق سورس‌کد ووکامرس 10.5.2 (`wc-order-functions.php:134-143` و `class-wc-order.php:1681`)؛ اثبات اینکه وضعیت‌های `processing` و `completed` در هسته ووکامرس وضعیت پرداخت کامل (`is_paid = true`) محسوب می‌شوند و تغییر وضعیت سفارش به `processing` با دریافت پیش‌پرداخت، گزارش‌های مالی و فاکتورهای رسمی را مخدوش می‌کند؛ حذف ارجاع نادرست به متد ناموجود `WC_Order::get_total_paid()`؛ تصویب وضعیت سفارشی `wc-partially-paid` («پرداخت جزئی (اقساطی)») که در آن `$order->is_paid() === false` باقی مانده و امکان انبارداری و ارسال فراهم می‌شود؛ تضمین کسر موجودی انبار دقیقا یک‌بار با `wc_reduce_stock_levels()`.
  - اصلاح C2 (چرخه عمر و ترتیب ساخت قرارداد اولیه): رفع تناقض مقدار `contract_id = NULL` در مرحله شروع پرداخت با طراحی ترتیبی Stage B؛ اتمیک بودن ایجاد قرارداد و جدول اقساط در وب‌هوک اولیه، اتصال به سفارش با کلید یکتای پایگاه داده `UNIQUE KEY uq_contract_order` جهت جلوگیری از ایجاد قرارداد تکراری؛ و ثبت وضعیت بازیابی خودکار `verified_pending_contract` در صورت بروز خطای محلی پس از تایید موفقیت‌آمیز درگاه.
  - اصلاح C3 (همزمانی و جلوگیری از تسویه تکراری اقساط): رفع Race Condition پرداخت‌های همزمان روی یک قسط با قفل ردیف `FOR UPDATE`، به‌روزرسانی شرطی اتمیک (`WHERE status != 'paid'`) و بررسی `settled_payment_id`؛ نشانه‌گذاری پرداخت دوم به عنوان `duplicate_settlement_detected` جهت بررسی اپراتور/استرداد بدون تغییر وضعیت قسط و بدون دوبار کسر کردن از ترازهای قرارداد.
  - اصلاح C4 (تخصیص استرداد و ترازهای مالی): تفکیک قاطع عودت نقدی (عملیات A: بازگشت وجه پرداختی) از ابطال دین/مرجوعی کالا (عملیات B: لغو اقساط سررسیدنیافته)؛ حفظ تغییرناپذیری فیلدهای تجمعی ناخالص `paid_principal` و `paid_charges`؛ و افزودن فیلد `refund_type` به جدول `wp_negar_club_refunds`.
  - اصلاح C5 (مدیریت خطاهای گذرای وب‌هوک و تایم‌اوت): تفکیک تایم‌اوت شبکه یا خطای ۵xx ارائه‌دهنده در Stage A از رد قطعی تراکنش؛ باقی ماندن پرداخت در وضعیت `pending_verification` و ارسال پاسخ HTTP 503 جهت تلاش مجدد وب‌هوک ارائه‌دهنده؛ و تغییرناپذیری وضعیت‌های نهایی (`verified`, `failed`) در برابر فراخوانی‌های با تأخیر.
  - توقف الزامی (Mandatory Stop) در گیت `review-plan` بدون تولید `tasks.md` و بدون آغاز فاز ۳.
- [x] **T307 — Review-Plan Approval & Task Generation (Phase 2 Convergence):**
  - تایید رسمی گیت `review-plan` توسط بازبین خارجی با شناسه پایه `40d48aef54180681ee2add2fb12fb33a13fe6fd9` و ثبت عبارت رسمی: «Review-plan approved by external reviewer for implementation task generation; financial activation remains separately gated.»
  - تدوین سند وظایف اجرایی جامع در `specs/013-customer-club/tasks.md` شامل ۶۴ تسک اجرایی ساختاریافته (`T310` تا `T380`) منطبق ۱:۱ با ۶۴ معیار پذیرش (`CLUB-AC-001`–`064`) و ۱۴ داستان کاربری در ۷ فاز عملیاتی.
  - اعمال محافظ‌های پنج‌گانه مالی و حسابداری (Financial Guards A-E) در تسک‌های فاز ۶ همراه با مسدود ماندن صریح فعال‌سازی مالی تا تصویب قوانین تجاری `CLUB-BD-001` تا `CLUB-BD-022`.
  - اجرای موفق ابزارهای پیش‌نیاز Spec Kit (`check-prerequisites.ps1 -Json -RequireTasks -IncludeTasks`) و تایید کامل انسجام اسناد با `speckit.analyze`.
  - نگاشت شناسه‌های تسک‌ها به ردیف‌های ۹‌گانه باشگاه مشتریان در `specs/coverage-matrix.md`.
  - آماده‌سازی کامل فاز ۳ (تنظیمات پوسته) جهت پیاده‌سازی بلادرنگ به محض دریافت پرامپت تایید کارفرما.
- [ ] **Phases 3–9:** آغاز نشده (موتور مالی تا تأیید تصمیم‌های تجاری `CLUB-BD-001`–`022` و مجوز کارفرما کاملاً غیرفعال می‌ماند).

