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
    await context.addCookies([{ name: auth.name, value: auth.value, url: BASE_URL, expires: auth.expires }]);
    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=texts', { waitUntil: 'domcontentloaded' });
    assert(!page.url().includes('wp-login.php'), 'A generated administrator session must access the settings form');

    await page.locator('#negar-text-header-desktop_search_placeholder').fill('جستجوی آزمایشی نگار');
    await page.locator('#negar-text-home-hero_slide_one_title').fill('عنوان آزمایشی صفحهٔ اصلی');
    await page.locator('#negar-text-footer-quick_links_heading').fill('پیوندهای آزمایشی نگار');
    await page.locator('#negar-text-contact-hero_title').fill('تماس آزمایشی نگار');
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'texts'),
      page.locator('form[action="options.php"] input[type="submit"]').click(),
    ]);
    assert.strictEqual(await page.locator('#negar-text-home-hero_slide_one_title').inputValue(), 'عنوان آزمایشی صفحهٔ اصلی', 'Authenticated Settings API form submission must persist text values');

    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual((await page.locator('#negar-home-hero-carousel h2').first().textContent()).trim(), 'عنوان آزمایشی صفحهٔ اصلی', 'Saved homepage text must render after reload');
    assert.strictEqual(await page.locator('.negar-main-header .negar-search-input').getAttribute('placeholder'), 'جستجوی آزمایشی نگار', 'Saved header text must render after reload');
    assert.strictEqual((await page.locator('.negar-footer-wrapper h3').first().textContent()).trim(), 'پیوندهای آزمایشی نگار', 'Saved footer text must render after reload');
    await page.goto(BASE_URL + '/contact/', { waitUntil: 'domcontentloaded' });
    assert.strictEqual((await page.locator('.negar-showcase-hero h1').textContent()).trim(), 'تماس آزمایشی نگار', 'Saved internal-page text must render after reload');

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
