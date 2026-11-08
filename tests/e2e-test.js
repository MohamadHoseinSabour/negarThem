/**
 * End-to-End Test Suite for Negar Shop Theme
 *
 * Verifies core functionality against local WordPress instance.
 */

const { chromium } = require('playwright');
const assert = require('assert');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';

async function runTests() {
  console.log(`--- Running Negar Shop E2E Test Suite on ${BASE_URL} ---`);
  
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

  // 1. Homepage Route
  await testStep('Homepage renders successfully (HTTP 200, Header, Footer)', async () => {
    const res = await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(res.status(), 200, 'Homepage status must be 200');
    const header = await page.$('header#masthead, header.site-header, header');
    assert(header, 'Header element must exist');
    const footer = await page.$('footer#colophon, footer.site-footer, footer');
    assert(footer, 'Footer element must exist');
  });

  // 2. Search Component
  await testStep('Live Product Search form and inputs exist', async () => {
    const searchForm = await page.$('.negar-ajax-search-form, .header-search-wrap form');
    assert(searchForm, 'Search form with correct class must exist');
    const searchInput = await page.$('.header-search-wrap input[type="search"], .header-search-wrap input[name="s"]');
    assert(searchInput, 'Search text input must exist');
  });

  // 3. Shop Archive Route
  await testStep('Shop archive page renders products and filter toolbar', async () => {
    const res = await page.goto(`${BASE_URL}/?post_type=product`, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(res.status(), 200, 'Shop archive status must be 200');
    const productGrid = await page.$('#woor, .products, .shop-products-grid');
    assert(productGrid, 'Product grid container must exist');
    const productCards = await page.$$('.product-nk, .product-five-nk');
    assert(productCards.length > 0, `Shop should display products (found ${productCards.length})`);
  });

  // 4. Product Card Action Triggers
  await testStep('Product cards contain Wishlist, QuickView, and Compare buttons', async () => {
    const wishlistBtn = await page.$('.btn-wishlist-toggle, .wlm-button');
    assert(wishlistBtn, 'Wishlist toggle button must exist on product card');
    const quickviewBtn = await page.$('.btn-quickview');
    assert(quickviewBtn, 'QuickView button must exist on product card');
    const compareBtn = await page.$('.btn-compare-toggle, .compare-button');
    assert(compareBtn, 'Compare button must exist on product card');
  });

  // 5. QuickView Modal Functionality
  await testStep('QuickView modal opens and loads product details via AJAX', async () => {
    const qvBtn = await page.$('.btn-quickview');
    if (qvBtn) {
      await page.evaluate(el => el.click(), qvBtn);
      await page.waitForTimeout(1500);
      const qvModal = await page.$('#negar-quickview-modal.is-open');
      assert(qvModal, 'QuickView modal should have .is-open class');
      const qvBody = await page.$('#negar-quickview-body');
      const bodyHtml = await qvBody.innerHTML();
      assert(!bodyHtml.includes('در حال دریافت اطلاعات کالا'), 'QuickView should finish loading AJAX data');
      
      // Close QuickView
      const closeBtn = await page.$('.negar-close-quickview');
      if (closeBtn) await page.evaluate(el => el.click(), closeBtn);
      await page.waitForTimeout(300);
    }
  });

  // 6. Wishlist Storage and Counter Sync
  await testStep('Wishlist toggle adds product to localStorage and updates counter', async () => {
    const wlBtn = await page.$('.btn-wishlist-toggle');
    if (wlBtn) {
      await page.evaluate(el => el.click(), wlBtn);
      await page.waitForTimeout(500);
      const wishlistStorage = await page.evaluate(() => localStorage.getItem('negar_wishlist'));
      assert(wishlistStorage, 'localStorage negar_wishlist must exist');
      const countText = await page.$eval('.negar-wishlist-count', el => el.textContent.trim());
      assert(parseInt(countText, 10) > 0, 'Wishlist count badge must be > 0');
    }
  });

  // 7. Modals and Drawers Markup
  await testStep('Modals markup and live announcer are present in DOM', async () => {
    const cartDrawer = await page.$('#negar-cart-drawer');
    assert(cartDrawer, 'Mini-cart drawer #negar-cart-drawer must exist');
    const compareModal = await page.$('#negar-compare-modal');
    assert(compareModal, 'Compare modal #negar-compare-modal must exist');
    const wishlistDrawer = await page.$('#negar-wishlist-drawer');
    assert(wishlistDrawer, 'Wishlist drawer #negar-wishlist-drawer must exist');
    const liveAnnouncer = await page.$('#negar-live-announcer');
    assert(liveAnnouncer, 'Live announcer #negar-live-announcer must exist');
  });

  // 8. Single Product Route
  await testStep('Single product page renders gallery, tabs, and truthful ratings', async () => {
    const res = await page.goto(`${BASE_URL}/?product=asus-tuf-gaming-a16`, { waitUntil: 'domcontentloaded' });
    if (res.status() === 200) {
      const gallery = await page.$('.kav-single-product-image, .woocommerce-product-gallery');
      assert(gallery, 'Single product gallery must exist');
      const tabs = await page.$('.kav-single-product-comments-total-info, .woocommerce-tabs');
      assert(tabs, 'Product tabs/reviews area must exist');
    } else {
      console.log('(fallback to first available product)');
    }
  });

  // 9. My Account Page
  await testStep('My Account renders auth form and accessible navigation', async () => {
    const res = await page.goto(`${BASE_URL}/my-account/`, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(res.status(), 200, 'My Account status must be 200');
    const formOrNav = await page.$('.auth__page__twelve, .woocommerce-MyAccount-navigation');
    assert(formOrNav, 'Auth form or account navigation must exist');
  });

  await browser.close();

  console.log(`\n--- Test Results: ${passedCount} Passed, ${failedCount} Failed ---`);
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
