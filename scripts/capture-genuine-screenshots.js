/**
 * Genuine Screenshot Capture Automation
 *
 * Captures real, non-fabricated screenshots across all required viewports
 * (1440px wide desktop, 1024px desktop, 768px tablet, 375px mobile)
 * and generates docs/compare/manifest.json with real MD5 hashes and metadata.
 */

const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { execSync } = require('child_process');
const { chromium } = require('playwright');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';
const COMPARE_DIR = path.resolve(__dirname, '..', 'docs', 'compare');

function getGitCommitHash() {
  try {
    return execSync('git rev-parse HEAD', { encoding: 'utf8' }).trim();
  } catch (e) {
    return 'unknown';
  }
}

function calculateMD5(filePath) {
  const buffer = fs.readFileSync(filePath);
  return crypto.createHash('md5').update(buffer).digest('hex').toUpperCase();
}

async function captureScreenshots() {
  console.log('--- Starting Genuine Screenshot Capture Automation ---');
  if (!fs.existsSync(COMPARE_DIR)) {
    fs.mkdirSync(COMPARE_DIR, { recursive: true });
  }

  const gitCommit = getGitCommitHash();
  const timestamp = new Date().toISOString();
  const manifest = {
    generated_at: timestamp,
    git_commit: gitCommit,
    base_url: BASE_URL,
    screenshots: []
  };

  const browser = await chromium.launch({
    channel: 'chrome',
    headless: true
  });

  const viewports = {
    '1440': { width: 1440, height: 900 },
    '1024': { width: 1024, height: 768 },
    '768':  { width: 768,  height: 1024 },
    '375':  { width: 375,  height: 812 }
  };

  const pagesToCapture = [
    {
      name: 'home',
      url: BASE_URL,
      viewports: [
        { vp: '1440', file: 'home-wide-1440.png' },
        { vp: '1024', file: 'home-desktop-1024.png' },
        { vp: '768',  file: 'home-tablet-768.png' },
        { vp: '375',  file: 'home-mobile-375.png' }
      ]
    },
    {
      name: 'shop',
      url: `${BASE_URL}/?post_type=product`,
      viewports: [
        { vp: '1440', file: 'shop-wide-1440.png' },
        { vp: '1024', file: 'shop-desktop-1024.png' },
        { vp: '768',  file: 'shop-tablet-768.png' },
        { vp: '375',  file: 'shop-mobile-375.png' }
      ]
    },
    {
      name: 'account',
      url: `${BASE_URL}/my-account/`,
      viewports: [
        { vp: '1440', file: 'account-1440.png' },
        { vp: '1024', file: 'account-1024.png' },
        { vp: '768',  file: 'account-768.png' },
        { vp: '375',  file: 'account-375.png' }
      ]
    },
    {
      name: 'cart',
      url: `${BASE_URL}/cart/`,
      viewports: [
        { vp: '1440', file: 'cart-1440.png' },
        { vp: '1024', file: 'cart-1024.png' },
        { vp: '768',  file: 'cart-768.png' },
        { vp: '375',  file: 'cart-375.png' }
      ]
    },
    {
      name: 'checkout',
      url: `${BASE_URL}/checkout/`,
      viewports: [
        { vp: '1440', file: 'checkout-1440.png' },
        { vp: '1024', file: 'checkout-1024.png' },
        { vp: '768',  file: 'checkout-768.png' },
        { vp: '375',  file: 'checkout-375.png' }
      ]
    },
    {
      name: 'search-empty',
      url: `${BASE_URL}/?s=nonexistentproductqueryxyz12345&post_type=product`,
      viewports: [
        { vp: '1440', file: 'search-empty-1440.png' },
        { vp: '375',  file: 'search-empty-375.png' }
      ]
    },
    {
      name: '404',
      url: `${BASE_URL}/nonexistent-404-test-page/`,
      viewports: [
        { vp: '1440', file: '404-1440.png' },
        { vp: '375',  file: '404-375.png' }
      ]
    }
  ];

  for (const p of pagesToCapture) {
    for (const v of p.viewports) {
      const vpConfig = viewports[v.vp];
      const context = await browser.newContext({
        viewport: vpConfig,
        locale: 'fa-IR'
      });
      const page = await context.newPage();
      try {
        console.log(`Capturing ${p.name} @ ${v.vp}px -> ${v.file}...`);
        await page.goto(p.url, { waitUntil: 'networkidle', timeout: 30000 });
        await page.waitForTimeout(500);
        const dest = path.join(COMPARE_DIR, v.file);
        await page.screenshot({ path: dest, fullPage: false });

        const hash = calculateMD5(dest);
        const stat = fs.statSync(dest);
        manifest.screenshots.push({
          file: v.file,
          route: p.name,
          url: p.url,
          viewport: v.vp,
          dimensions: `${vpConfig.width}x${vpConfig.height}`,
          size_bytes: stat.size,
          md5: hash,
          timestamp: timestamp
        });
      } catch (err) {
        console.error(`Error capturing ${v.file}: ${err.message}`);
      } finally {
        await context.close();
      }
    }
  }

  // Capture Modals
  console.log('Capturing interactive modals...');
  const modalContext = await browser.newContext({
    viewport: viewports['1440'],
    locale: 'fa-IR'
  });
  const mPage = await modalContext.newPage();
  await mPage.goto(`${BASE_URL}/?post_type=product`, { waitUntil: 'networkidle' });

  // 1. QuickView Modal
  try {
    const qvBtn = await mPage.$('.btn-quickview');
    if (qvBtn) {
      await mPage.evaluate(el => el.click(), qvBtn);
      await mPage.waitForTimeout(1500);
      const qvFile = '008-modal-quickview-desktop.png';
      const qvDest = path.join(COMPARE_DIR, qvFile);
      await mPage.screenshot({ path: qvDest });
      manifest.screenshots.push({
        file: qvFile,
        route: 'modal-quickview',
        viewport: '1440',
        size_bytes: fs.statSync(qvDest).size,
        md5: calculateMD5(qvDest),
        timestamp: timestamp
      });
      const qvClose = await mPage.$('.negar-close-quickview');
      if (qvClose) await mPage.evaluate(el => el.click(), qvClose);
      await mPage.waitForTimeout(400);
    }
  } catch (e) {
    console.error('Quickview modal capture error:', e.message);
  }

  // 2. Wishlist Drawer
  try {
    await mPage.evaluate(() => {
      localStorage.setItem('negar_wishlist', JSON.stringify([112]));
    });
    const wlTrigger = await mPage.$('.negar-wishlist-trigger, a[href*="/wishlist"]');
    if (wlTrigger) {
      await mPage.evaluate(el => el.click(), wlTrigger);
      await mPage.waitForTimeout(1500);
      const wlFile = '008-modal-wishlist-drawer.png';
      const wlDest = path.join(COMPARE_DIR, wlFile);
      await mPage.screenshot({ path: wlDest });
      manifest.screenshots.push({
        file: wlFile,
        route: 'modal-wishlist',
        viewport: '1440',
        size_bytes: fs.statSync(wlDest).size,
        md5: calculateMD5(wlDest),
        timestamp: timestamp
      });
      const wlClose = await mPage.$('.negar-close-wishlist');
      if (wlClose) await mPage.evaluate(el => el.click(), wlClose);
      await mPage.waitForTimeout(400);
    }
  } catch (e) {
    console.error('Wishlist drawer capture error:', e.message);
  }

  // 3. Compare Modal
  try {
    await mPage.evaluate(() => {
      localStorage.setItem('negar_compare', JSON.stringify([112, 123]));
    });
    const cmpTrigger = await mPage.$('.negar-compare-trigger');
    if (cmpTrigger) {
      await mPage.evaluate(el => el.click(), cmpTrigger);
      await mPage.waitForTimeout(1500);
      const cmpFile = '008-modal-compare-table.png';
      const cmpDest = path.join(COMPARE_DIR, cmpFile);
      await mPage.screenshot({ path: cmpDest });
      manifest.screenshots.push({
        file: cmpFile,
        route: 'modal-compare',
        viewport: '1440',
        size_bytes: fs.statSync(cmpDest).size,
        md5: calculateMD5(cmpDest),
        timestamp: timestamp
      });
    }
  } catch (e) {
    console.error('Compare modal capture error:', e.message);
  }

  // 4. Toast Notification
  try {
    await mPage.evaluate(() => {
      if (typeof window.negarToast === 'function') {
        window.negarToast('محصول با موفقیت به سبد خرید اضافه شد.', 'success');
      }
    });
    await mPage.waitForTimeout(500);
    const toastFile = '008-modal-toast-alert.png';
    const toastDest = path.join(COMPARE_DIR, toastFile);
    await mPage.screenshot({ path: toastDest });
    manifest.screenshots.push({
      file: toastFile,
      route: 'toast-notification',
      viewport: '1440',
      size_bytes: fs.statSync(toastDest).size,
      md5: calculateMD5(toastDest),
      timestamp: timestamp
    });
  } catch (e) {
    console.error('Toast notification capture error:', e.message);
  }

  await modalContext.close();
  await browser.close();

  // Save manifest
  const manifestPath = path.join(COMPARE_DIR, 'manifest.json');
  fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2), 'utf8');
  console.log(`\nSuccessfully captured ${manifest.screenshots.length} screenshots.`);
  console.log(`Manifest written to ${manifestPath}`);

  // Check uniqueness of MD5 hashes
  const hashes = manifest.screenshots.map(s => s.md5);
  const uniqueHashes = new Set(hashes);
  console.log(`MD5 Uniqueness: ${uniqueHashes.size} unique hashes out of ${hashes.length} screenshots.`);
}

captureScreenshots().catch(err => {
  console.error('Fatal Screenshot Capture Error:', err);
  process.exit(1);
});
