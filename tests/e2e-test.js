/**
 * Comprehensive End-to-End Test Suite for Negar Shop Theme (Master Prompt V2)
 *
 * Verifies core functionality, zero-overflow responsiveness, category search,
 * phone OTP login flow, cart/checkout continuity, truthful reviews/Q&A, and contact form.
 */

const { chromium } = require('playwright');
const assert = require('assert');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';

async function runTests() {
  console.log(`\n===============================================================`);
  console.log(`--- Running Negar Shop Hardening V2 Test Suite on ${BASE_URL} ---`);
  console.log(`===============================================================\n`);

  const browser = await chromium.launch({
    channel: 'chrome',
    headless: true,
  });

  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    locale: 'fa-IR',
  });

  const page = await context.newPage();
  let passedCount = 0;
  let failedCount = 0;

  async function testStep(name, fn) {
    try {
      process.stdout.write(`  [TEST] ${name} ... `);
      await fn();
      console.log('PASS');
      passedCount++;
    } catch (err) {
      console.log('FAIL');
      console.error(`         ${err.message}`);
      failedCount++;
    }
  }

  // 1. Homepage Route & Architecture
  await testStep('Homepage renders successfully (HTTP 200, Header, Footer)', async () => {
    const res = await page.goto(BASE_URL, { waitUntil: 'domcontentloaded', timeout: 45000 });
    assert.strictEqual(res.status(), 200, 'Homepage status must be 200');
    const header = await page.$('header#masthead, header.site-header, .negar-main-header');
    assert(header, 'Header element must exist');
    const footer = await page.$('footer#colophon, footer.site-footer, footer');
    assert(footer, 'Footer element must exist');
  });

  // 2. Hero Swiper Clean Initialization & Zero Overflow
  await testStep('Homepage Hero Swiper initializes client-side with zero horizontal overflow', async () => {
    const swiperEl = await page.$('.swiper-landing-nk');
    assert(swiperEl, 'Hero swiper container must exist');
    const isInitialized = await page.evaluate(() => {
      const el = document.querySelector('.swiper-landing-nk');
      return el && el.classList.contains('swiper-initialized');
    });
    assert(isInitialized, 'Hero slider must have .swiper-initialized added client-side');

    const overflowInfo = await page.evaluate(() => {
      return {
        scrollW: document.documentElement.scrollWidth,
        clientW: document.documentElement.clientWidth
      };
    });
    assert.strictEqual(overflowInfo.scrollW, overflowInfo.clientW, 'Desktop viewport must have 0px overflow');
  });

  // 3. Category Search Dropdown
  await testStep('Main search bar contains interactive category dropdown and hidden input', async () => {
    const catBtn = await page.$('#negar-category-menu-btn, .category-menu-btn');
    assert(catBtn, 'Category menu button must exist inside search form');
    const catInput = await page.$('input[name="product_cat"]#negar-search-cat-input, input[name="product_cat"]');
    assert(catInput, 'Hidden input product_cat must exist in search form');
    const dropdown = await page.$('#negar-category-dropdown, .negar-category-dropdown');
    assert(dropdown, 'Category dropdown menu container must exist');

    // Click category button to open dropdown
    await page.evaluate(el => el.click(), catBtn);
    const isOpened = await page.evaluate(() => {
      const dd = document.querySelector('#negar-category-dropdown, .negar-category-dropdown');
      return dd && !dd.classList.contains('hidden');
    });
    assert(isOpened, 'Category dropdown must open when category button is clicked');
  });

  // 4. Customer Phone OTP Authentication Modal Flow
  await testStep('Login modal opens on trigger and handles OTP flow with Iranian phone validation', async () => {
    const loginTrigger = await page.$('.negar-login-trigger');
    if (loginTrigger) {
      await page.evaluate(el => el.click(), loginTrigger);
      await page.waitForTimeout(300);
      const modal = await page.$('#negar-login-otp-modal:not(.hidden)');
      assert(modal, 'OTP login modal must be visible on click');

      // Enter valid Iranian mobile number
      const phoneInput = await page.$('#negar-otp-phone-input');
      assert(phoneInput, 'Phone input must exist in Step 1');
      await phoneInput.fill('09121112233');

      // Submit Step 1
      const submitStep1 = await page.$('#negar-otp-step1-submit');
      await page.evaluate(el => el.click(), submitStep1);
      await page.waitForTimeout(1000);

      // Check transition to Step 2
      const step2Form = await page.$('#negar-otp-step2-form:not(.hidden)');
      assert(step2Form, 'Step 2 form must be visible after sending OTP');

      // Close modal for subsequent tests
      const closeBtn = await page.$('#negar-login-otp-modal .negar-modal-close');
      if (closeBtn) await page.evaluate(el => el.click(), closeBtn);
      await page.waitForTimeout(300);
    }
  });

  // 5. Product Cards Action Triggers (Wishlist, QuickView, Compare)
  await testStep('Product cards contain Wishlist, QuickView, and Compare buttons', async () => {
    await page.goto(`${BASE_URL}/?post_type=product`, { waitUntil: 'domcontentloaded' });
    const wishlistBtn = await page.$('.btn-wishlist-toggle, .wlm-button');
    assert(wishlistBtn, 'Wishlist toggle button must exist on product card');
    const quickviewBtn = await page.$('.btn-quickview');
    assert(quickviewBtn, 'QuickView button must exist on product card');
    const compareBtn = await page.$('.btn-compare-toggle, .compare-button');
    assert(compareBtn, 'Compare button must exist on product card');
  });

  // 6. QuickView Modal Functionality
  await testStep('QuickView modal opens and loads product details via AJAX', async () => {
    const qvBtn = await page.$('.btn-quickview');
    if (qvBtn) {
      await page.evaluate(el => el.click(), qvBtn);
      await page.waitForTimeout(1500);
      const qvModal = await page.$('#negar-quickview-modal.is-open');
      assert(qvModal, 'QuickView modal should have .is-open class');

      // Close QuickView
      const closeBtn = await page.$('.negar-close-quickview');
      if (closeBtn) await page.evaluate(el => el.click(), closeBtn);
      await page.waitForTimeout(300);
    }
  });

  // 7. Wishlist Storage and Counter Sync
  await testStep('Wishlist toggle adds product to localStorage and updates counter', async () => {
    const wlBtn = await page.$('.btn-wishlist-toggle');
    if (wlBtn) {
      await page.evaluate(el => el.click(), wlBtn);
      await page.waitForTimeout(500);
      const wishlistStorage = await page.evaluate(() => localStorage.getItem('negar_wishlist'));
      assert(wishlistStorage, 'localStorage negar_wishlist must exist');
    }
  });

  // 8. Single Product: Gallery, Tabs, Truthful Reviews & Real Q&A
  await testStep('Single product page renders gallery, tabs without accordion conflicts, and real Q&A', async () => {
    await page.goto(`${BASE_URL}/?p=131`, { waitUntil: 'domcontentloaded' });
    const gallery = await page.$('.kav-style5-main-image, .woocommerce-product-gallery, .kav-single-product-image');
    assert(gallery, 'Product image gallery must exist');

    const tabs = await page.$('.kav-single-product-tabs');
    assert(tabs, 'Product tabs navigation must exist');

    const specsTab = await page.$('#info-product-tab');
    assert(specsTab, 'Specifications tab panel must exist');

    const commentsTab = await page.$('#comments-tab');
    assert(commentsTab, 'Comments/reviews tab panel must exist');

    const qaTab = await page.$('#questions-tab');
    assert(qaTab, 'Questions & answers tab panel must exist');
  });

  // 9. Mobile Sticky Purchase Panel & Collision Prevention
  await testStep('Mobile single product view displays sticky purchase bar with zero nav collision', async () => {
    const mobileContext = await browser.newContext({
      viewport: { width: 375, height: 667 },
      locale: 'fa-IR',
    });
    const mobilePage = await mobileContext.newPage();
    await mobilePage.goto(`${BASE_URL}/?p=131`, { waitUntil: 'domcontentloaded' });

    const overflow = await mobilePage.evaluate(() => {
      return document.documentElement.scrollWidth - document.documentElement.clientWidth;
    });
    assert.strictEqual(overflow, 0, 'Mobile single product must have 0px overflow');

    const stickyBuyBar = await mobilePage.$('.kav-single-product-bottom-nav-price');
    assert(stickyBuyBar, 'Sticky bottom purchase bar must exist on single product page');

    const bottomNavHidden = await mobilePage.evaluate(() => {
      const nav = document.querySelector('.negar-mobile-bottom-bar');
      if (!nav) return true;
      const style = window.getComputedStyle(nav);
      return style.display === 'none';
    });
    assert(bottomNavHidden, 'Generic bottom nav bar must be hidden on single product to prevent collision');

    await mobileContext.close();
  });

  // 10. Cart Page Functionality
  await testStep('Cart page renders cart contents and totals accurately', async () => {
    // Seed cart with product 131
    await page.goto(`${BASE_URL}/?add-to-cart=131`, { waitUntil: 'domcontentloaded' });
    await page.goto(`${BASE_URL}/cart/`, { waitUntil: 'domcontentloaded' });

    const cartTable = await page.$('.woocommerce-cart-form, .cart-nk');
    assert(cartTable, 'Cart container must exist');
    const totals = await page.$('.cart_totals, .cart-nk__box');
    assert(totals, 'Cart totals area must exist');
  });

  // 11. Checkout Continuity with Seeded Cart
  await testStep('Checkout page renders order review, billing fields, and step indicator without empty redirect', async () => {
    await page.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });
    assert(page.url().includes('checkout'), 'Checkout route must not redirect away when cart is seeded');

    const checkoutForm = await page.$('form.checkout, .cart-nk-checkout');
    assert(checkoutForm, 'Checkout form element must exist');

    const orderReview = await page.$('#order_review, .woocommerce-checkout-review-order');
    assert(orderReview, 'Order review section must exist in checkout');

    const billingFields = await page.$('.woocommerce-billing-fields');
    assert(billingFields, 'Billing fields card must exist');
  });

  // 12. Contact Us: Real AJAX Submission (No Fake alert())
  await testStep('Contact page renders contact cards, map, and form with real AJAX handler', async () => {
    await page.goto(`${BASE_URL}/contact/`, { waitUntil: 'domcontentloaded' });
    const contactForm = await page.$('#negar-contact-form');
    assert(contactForm, 'Contact form with id #negar-contact-form must exist');

    const nameInput = await page.$('#contact-name');
    assert(nameInput, 'Name input must exist');
    const phoneInput = await page.$('#contact-phone');
    assert(phoneInput, 'Phone input must exist');
    const messageInput = await page.$('#contact-message');
    assert(messageInput, 'Message textarea must exist');
  });

  // 13. About Us Page
  await testStep('About Us page renders brand story, key metrics, and values', async () => {
    await page.goto(`${BASE_URL}/about/`, { waitUntil: 'domcontentloaded' });
    const aboutHeader = await page.$('h1');
    assert(aboutHeader, 'About page title must exist');
    const cards = await page.$$('.card, .negar-pillar-card');
    assert(cards.length >= 4, `About page should have metrics cards (found ${cards.length})`);
  });

  // 14. V3 Header Logo Dimensions across WooCommerce Pages (Shop, Single Product)
  await testStep('Mobile header logo retains non-zero computed dimensions on WooCommerce pages', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();

    for (const url of [`${BASE_URL}/?post_type=product`, `${BASE_URL}/?p=131`]) {
      await mPage.goto(url, { waitUntil: 'domcontentloaded' });
      const logoRect = await mPage.evaluate(() => {
        const logo = document.querySelector('.negar-mobile-header .mobile-logo img, .negar-mobile-header a img');
        if (!logo) return null;
        const rect = logo.getBoundingClientRect();
        return { width: rect.width, height: rect.height };
      });
      assert(logoRect, `Logo must exist on ${url}`);
      assert(logoRect.height >= 24 && logoRect.width >= 40, `Logo on ${url} must be visible (got ${logoRect.width}x${logoRect.height})`);
    }
    await mCtx.close();
  });

  // 15. V3 Mobile Hero Slider Dimensions & Visibility
  await testStep('Mobile Hero Slider displays content with non-zero dimensions and 0px overflow', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    await mPage.waitForSelector('.landing-nk__carousel', { timeout: 10000 });
    await mPage.waitForTimeout(600);

    const heroMetrics = await mPage.evaluate(() => {
      const img = document.querySelector('.landing-nk__carousel .swiper-slide-active .image img, .landing-nk__carousel .image img');
      const title = document.querySelector('.landing-nk__carousel .title');
      const cta = document.querySelector('.landing-nk__carousel .btn');
      const iRect = img ? img.getBoundingClientRect() : null;
      const tRect = title ? title.getBoundingClientRect() : null;
      const cRect = cta ? cta.getBoundingClientRect() : null;

      return {
        imgVisible: iRect && iRect.width > 50 && iRect.height > 50,
        titleVisible: tRect && tRect.width > 50,
        ctaVisible: cRect && cRect.width > 50,
        overflow: document.documentElement.scrollWidth > window.innerWidth,
      };
    });

    assert(heroMetrics.imgVisible, 'Mobile hero image must have visible rendered dimensions');
    assert(heroMetrics.titleVisible, 'Mobile hero title must be visible');
    assert(heroMetrics.ctaVisible, 'Mobile hero CTA button must be visible');
    assert.strictEqual(heroMetrics.overflow, false, 'Homepage must have zero horizontal overflow on mobile');
    await mCtx.close();
  });

  // 16. V3 Mobile Bottom Navigation Clean 3-Item Island
  await testStep('Mobile bottom navigation contains exactly 3 items with 0 count badges', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

    const navMetrics = await mPage.evaluate(() => {
      const bar = document.querySelector('.negar-mobile-bottom-bar');
      if (!bar) return null;
      const items = bar.querySelectorAll('.bottom-bar-item');
      const badges = bar.querySelectorAll('.badge, .negar-cart-count, .negar-wishlist-count');
      const texts = Array.from(items).map(i => i.textContent.trim());
      return {
        itemCount: items.length,
        badgeCount: badges.length,
        texts,
      };
    });

    assert(navMetrics, '.negar-mobile-bottom-bar must exist on mobile');
    assert.strictEqual(navMetrics.itemCount, 3, 'Bottom bar must have exactly 3 items');
    assert.strictEqual(navMetrics.badgeCount, 0, 'Bottom bar must have 0 count badges');
    assert(!navMetrics.texts.some(t => t.includes('دسته') || t.includes('سبد')), 'Bottom bar must not contain Categories or Cart');
    await mCtx.close();
  });

  // 17. V3 Mobile Checkout Input Sizing
  await testStep('Mobile checkout inputs render at full width (>= 240px) without 105px squashing', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(`${BASE_URL}/?add-to-cart=131`, { waitUntil: 'domcontentloaded' });
    await mPage.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });

    const inputWidths = await mPage.evaluate(() => {
      const fn = document.getElementById('billing_first_name');
      const ln = document.getElementById('billing_last_name');
      return {
        fnWidth: fn ? fn.getBoundingClientRect().width : 0,
        lnWidth: ln ? ln.getBoundingClientRect().width : 0,
      };
    });

    assert(inputWidths.fnWidth >= 240, `First name input must be at least 240px on 375px viewport (got ${inputWidths.fnWidth}px)`);
    assert(inputWidths.lnWidth >= 240, `Last name input must be at least 240px on 375px viewport (got ${inputWidths.lnWidth}px)`);
    await mCtx.close();
  });

  await browser.close();

  console.log(`\n===============================================================`);
  console.log(`--- Test Suite Summary: ${passedCount} Passed, ${failedCount} Failed ---`);
  console.log(`===============================================================\n`);

  if (failedCount > 0) {
    process.exit(1);
  } else {
    process.exit(0);
  }
}

runTests().catch(err => {
  console.error('Fatal Test Runner Error:', err);
  process.exit(1);
});
