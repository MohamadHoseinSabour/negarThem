/**
 * Theme-settings persistence and rendered-token regression test.
 */

const assert = require('assert');
const { execFileSync } = require('child_process');
const path = require('path');
const { chromium } = require('playwright');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';
const helper = path.join(__dirname, 'theme-settings-regression.php');

function php(action, ...args) {
  return execFileSync('php', [helper, action, ...args], { encoding: 'utf8' }).trim();
}

async function readColors(page) {
  return page.evaluate(() => {
    const styles = getComputedStyle(document.documentElement);
    return {
      primary: styles.getPropertyValue('--color-primary').trim().toLowerCase(),
      secondary: styles.getPropertyValue('--color-secondary').trim().toLowerCase(),
    };
  });
}

async function run() {
  const snapshot = php('snapshot');
  let failure;
  let browser;
  let context;
  let mobileContext;
  let auth;

  try {
    const checks = JSON.parse(php('verify'));
    for (const [name, passed] of Object.entries(checks)) {
      assert.strictEqual(passed, true, `WordPress settings check failed: ${name}`);
    }

    browser = await chromium.launch({ channel: 'chrome', headless: true });
    context = await browser.newContext({ viewport: { width: 1440, height: 900 }, locale: 'fa-IR' });
    const page = await context.newPage();

    const adminResponse = await page.goto(`${BASE_URL}/wp-admin/options-general.php?page=negar-theme-settings`, { waitUntil: 'domcontentloaded' });
    assert(adminResponse, 'Admin route must return a response');
    assert(page.url().includes('wp-login.php'), 'Unauthenticated visitors must be redirected to WordPress sign-in');

    auth = JSON.parse(php('auth-cookie'));
    const productFixture = JSON.parse(php('product-fixture'));
    await context.addCookies([{ name: auth.name, value: auth.value, url: BASE_URL, expires: auth.expires }]);
    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=texts', { waitUntil: 'domcontentloaded' });
    assert(!page.url().includes('wp-login.php'), 'A generated administrator session must access the settings form');

    await page.locator('#negar-text-header-desktop_search_placeholder').fill('جستجوی آزمایشی نگار');
    await page.locator('#negar-text-home-hero_slide_one_title').fill('عنوان آزمایشی صفحهٔ اصلی');
    await page.locator('#negar-text-footer-quick_links_heading').fill('پیوندهای آزمایشی نگار');
    await page.locator('#negar-text-footer-contact_card_description').fill('راهنمای تکمیلی فوتر نگار');
    await page.locator('#negar-text-contact-hero_title').fill('تماس آزمایشی نگار');
    await page.locator('#negar-text-contact-phone_card_label').fill('تلفن آزمایشی نگار');
    await page.locator('#negar-text-about-products_card_title').fill('محصولات آزمایشی نگار');
    await page.locator('#negar-text-header-mega_sale_title').fill('تخفیف آزمایشی مگامنو');
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'texts'),
      page.locator('form[action="options.php"] input[type="submit"]').click(),
    ]);
    assert.strictEqual(await page.locator('#negar-text-home-hero_slide_one_title').inputValue(), 'عنوان آزمایشی صفحهٔ اصلی', 'Authenticated Settings API form submission must persist text values');

    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual((await page.locator('#negar-home-hero-carousel h2').first().textContent()).trim(), 'عنوان آزمایشی صفحهٔ اصلی', 'Saved homepage text must render after reload');
    assert.strictEqual(await page.locator('.negar-main-header .negar-search-input').getAttribute('placeholder'), 'جستجوی آزمایشی نگار', 'Saved header text must render after reload');
    assert.strictEqual((await page.locator('.negar-footer-wrapper h3').first().textContent()).trim(), 'پیوندهای آزمایشی نگار', 'Saved footer text must render after reload');
    assert.strictEqual((await page.getByText('راهنمای تکمیلی فوتر نگار', { exact: true }).textContent()).trim(), 'راهنمای تکمیلی فوتر نگار', 'Saved footer editorial copy must render after reload');
    await page.locator('#negar-category-menu-btn').click();
    assert.strictEqual((await page.locator('.negar-category-mega__sale-title').textContent()).trim(), 'تخفیف آزمایشی مگامنو', 'Saved mega-menu copy must render after interaction');
    await page.goto(BASE_URL + '/about/', { waitUntil: 'domcontentloaded' });
    assert.strictEqual((await page.locator('.negar-about-topics article').first().locator('h2').textContent()).trim(), 'محصولات آزمایشی نگار', 'Saved About-card copy must render after reload');
    await page.goto(BASE_URL + '/contact/', { waitUntil: 'domcontentloaded' });
    assert.strictEqual((await page.locator('.negar-showcase-hero h1').textContent()).trim(), 'تماس آزمایشی نگار', 'Saved internal-page text must render after reload');
    assert.strictEqual((await page.locator('.negar-contact-grid .negar-contact-card b').first().textContent()).trim(), 'تلفن آزمایشی نگار', 'Saved contact-card label must render after reload');

    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=layout', { waitUntil: 'domcontentloaded' });
    assert.strictEqual(await page.locator('#negar-layout-sections .negar-layout-item').count(), 35, 'The authenticated layout form must list all 35 fixed homepage sections');
    await page.locator('#negar-layout-enabled-story-circles').uncheck();
    await page.locator('#negar-layout-enabled-category-bubbles').check();
    await page.locator('.negar-layout-item[data-section-id="category-bubbles"] [data-direction="up"]').click();
    await page.locator('.negar-layout-item[data-section-id="category-bubbles"] [data-direction="up"]').click();
    assert.strictEqual(await page.locator('#negar-layout-sections .negar-layout-item').nth(0).getAttribute('data-section-id'), 'category-bubbles', 'Keyboard-accessible move controls must update the submitted order');
    await page.locator('.negar-layout-item[data-section-id="product-tabs"]').dragTo(page.locator('.negar-layout-item[data-section-id="category-bubbles"]'), { targetPosition: { x: 20, y: 5 } });
    assert.strictEqual(await page.locator('#negar-layout-sections .negar-layout-item').nth(0).getAttribute('data-section-id'), 'product-tabs', 'Drag-and-drop must update the submitted order');
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'layout'),
      page.locator('form[action="options.php"] input[type="submit"]').click(),
    ]);
    assert.strictEqual(await page.locator('#negar-layout-enabled-story-circles').isChecked(), false, 'A saved visibility checkbox must survive the Settings API redirect');
    assert.strictEqual(await page.locator('#negar-layout-sections .negar-layout-item').nth(0).getAttribute('data-section-id'), 'product-tabs', 'A saved section order must survive the Settings API redirect');

    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    const layoutHtml = await page.locator('main.homepage-main').innerHTML();
    assert(layoutHtml.indexOf('<!-- negar-home-section:product-tabs -->') < layoutHtml.indexOf('<!-- negar-home-section:hero-slider -->'), 'Saved ordering must render on the frontend after reload');
    assert.strictEqual(layoutHtml.includes('<!-- negar-home-section:story-circles -->'), false, 'A disabled homepage section must not render on the frontend');

    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=products', { waitUntil: 'domcontentloaded' });
    await page.locator('#negar-products-count-accessories').locator('xpath=ancestor::details').locator('summary').click();
    await page.locator('#negar-products-count-accessories').fill('1');
    await page.locator('#negar-products-sort-accessories').selectOption('title_asc');
    await page.locator('#negar-products-manual-accessories').selectOption(String(productFixture.id));
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
      page.locator('form[action="options.php"] input[type="submit"]').click(),
    ]);
    assert.deepStrictEqual(await page.locator('#negar-products-manual-accessories').inputValue(), String(productFixture.id), 'Authenticated product-section form submission must persist a published manual product selection');
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(await page.locator(`.buy-accessories-nk a[href="${productFixture.permalink}"]`).count() > 0, true, 'Saved product selection must render with the real WooCommerce permalink on desktop');

    mobileContext = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, locale: 'fa-IR' });
    const mobilePage = await mobileContext.newPage();
    await mobilePage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(await mobilePage.locator('#negar-home-hero-carousel').isVisible(), true, 'The reordered homepage must retain its existing hero component on mobile');
    const mobileLayoutHtml = await mobilePage.locator('main.homepage-main').innerHTML();
    assert(mobileLayoutHtml.indexOf('<!-- negar-home-section:product-tabs -->') < mobileLayoutHtml.indexOf('<!-- negar-home-section:hero-slider -->'), 'Saved ordering must also render on mobile');
    assert.strictEqual(await mobilePage.locator(`.buy-accessories-nk a[href="${productFixture.permalink}"]`).count() > 0, true, 'Saved product selection must render with the real WooCommerce permalink on mobile');

    php('set-colors', '#123456', '#654321');
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.deepStrictEqual(await readColors(page), { primary: '#123456', secondary: '#654321' }, 'Saved color values must apply after a frontend reload');

    php('set-colors', '#059669', '#e11d48');
    await page.reload({ waitUntil: 'domcontentloaded' });
    assert.deepStrictEqual(await readColors(page), { primary: '#059669', secondary: '#e11d48' }, 'Former Customizer default values must remain valid saved colors');

    console.log('[PASS] Theme settings persistence and rendered-token regression checks passed.');
  } catch (error) {
    failure = error;
  } finally {
    try {
      if (context) {
        await context.close();
      }
    } catch (closeError) {
      failure = failure || closeError;
    }
    try {
      if (mobileContext) {
        await mobileContext.close();
      }
    } catch (closeError) {
      failure = failure || closeError;
    }
    try {
      if (browser) {
        await browser.close();
      }
    } catch (closeError) {
      failure = failure || closeError;
    }
    if (auth) {
      try {
        php('destroy-auth-cookie', String(auth.user_id), auth.token);
      } catch (destroyError) {
        failure = failure || destroyError;
      }
    }
    try {
      php('restore', snapshot);
    } catch (restoreError) {
      failure = failure || restoreError;
    }
  }

  if (failure) {
    throw failure;
  }
}

run().catch((error) => {
  console.error(`[FAIL] Theme settings regression: ${error.message}`);
  process.exit(1);
});
