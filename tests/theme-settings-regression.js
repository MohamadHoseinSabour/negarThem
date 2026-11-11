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

  try {
    const checks = JSON.parse(php('verify'));
    for (const [name, passed] of Object.entries(checks)) {
      assert.strictEqual(passed, true, `WordPress settings check failed: ${name}`);
    }

    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    const context = await browser.newContext({ viewport: { width: 1440, height: 900 }, locale: 'fa-IR' });
    const page = await context.newPage();

    const adminResponse = await page.goto(`${BASE_URL}/wp-admin/options-general.php?page=negar-theme-settings`, { waitUntil: 'domcontentloaded' });
    assert(adminResponse, 'Admin route must return a response');
    assert(page.url().includes('wp-login.php'), 'Unauthenticated visitors must be redirected to WordPress sign-in');

    php('set-colors', '#123456', '#654321');
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.deepStrictEqual(await readColors(page), { primary: '#123456', secondary: '#654321' }, 'Saved color values must apply after a frontend reload');

    php('set-colors', '#059669', '#e11d48');
    await page.reload({ waitUntil: 'domcontentloaded' });
    assert.deepStrictEqual(await readColors(page), { primary: '#059669', secondary: '#e11d48' }, 'Former Customizer default values must remain valid saved colors');

    await context.close();
    await browser.close();
    console.log('[PASS] Theme settings persistence and rendered-token regression checks passed.');
  } catch (error) {
    failure = error;
  } finally {
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
