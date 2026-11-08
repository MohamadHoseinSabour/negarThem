/**
 * Capture Genuine Before Screenshots for Master Prompt V3
 */

const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { chromium } = require('playwright');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';
const OUT_DIR = path.resolve(__dirname, '..', 'docs', 'compare', 'v3', 'before');

function calculateMD5(filePath) {
  const buffer = fs.readFileSync(filePath);
  return crypto.createHash('md5').update(buffer).digest('hex').toUpperCase();
}

async function run() {
  if (!fs.existsSync(OUT_DIR)) {
    fs.mkdirSync(OUT_DIR, { recursive: true });
  }

  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const manifest = [];

  const tasks = [
    {
      name: 'header-shop-mobile-375',
      url: `${BASE_URL}/?post_type=product`,
      viewport: { width: 375, height: 812 },
      clip: { x: 0, y: 0, width: 375, height: 260 },
      file: 'header-shop-mobile-375.png'
    },
    {
      name: 'header-product-mobile-375',
      url: `${BASE_URL}/?p=131`,
      viewport: { width: 375, height: 812 },
      clip: { x: 0, y: 0, width: 375, height: 260 },
      file: 'header-product-mobile-375.png'
    },
    {
      name: 'hero-mobile-375',
      url: BASE_URL,
      viewport: { width: 375, height: 812 },
      clipSelector: '.landing-nk',
      file: 'hero-mobile-375.png'
    },
    {
      name: 'hero-desktop-1440',
      url: BASE_URL,
      viewport: { width: 1440, height: 900 },
      clipSelector: '.landing-nk',
      file: 'hero-desktop-1440.png'
    },
    {
      name: 'hero-desktop-1024',
      url: BASE_URL,
      viewport: { width: 1024, height: 768 },
      clipSelector: '.landing-nk',
      file: 'hero-desktop-1024.png'
    },
    {
      name: 'bottom-nav-mobile-375',
      url: BASE_URL,
      viewport: { width: 375, height: 812 },
      clipSelector: '.negar-mobile-bottom-bar',
      file: 'bottom-nav-mobile-375.png'
    },
    {
      name: 'checkout-mobile-375',
      seed: `${BASE_URL}/?add-to-cart=131`,
      url: `${BASE_URL}/checkout/`,
      viewport: { width: 375, height: 812 },
      file: 'checkout-mobile-375.png'
    },
    {
      name: 'about-mobile-375',
      url: `${BASE_URL}/about/`,
      viewport: { width: 375, height: 812 },
      file: 'about-mobile-375.png'
    },
    {
      name: 'about-desktop-1440',
      url: `${BASE_URL}/about/`,
      viewport: { width: 1440, height: 900 },
      file: 'about-desktop-1440.png'
    },
    {
      name: 'contact-mobile-375',
      url: `${BASE_URL}/contact/`,
      viewport: { width: 375, height: 812 },
      file: 'contact-mobile-375.png'
    },
    {
      name: 'contact-desktop-1440',
      url: `${BASE_URL}/contact/`,
      viewport: { width: 1440, height: 900 },
      file: 'contact-desktop-1440.png'
    }
  ];

  for (const t of tasks) {
    console.log(`Capturing BEFORE: ${t.name}...`);
    const context = await browser.newContext({
      viewport: t.viewport,
      locale: 'fa-IR'
    });
    const page = await context.newPage();
    if (t.seed) {
      await page.goto(t.seed, { waitUntil: 'domcontentloaded' });
    }
    await page.goto(t.url, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);

    const dest = path.join(OUT_DIR, t.file);
    if (t.clip) {
      await page.screenshot({ path: dest, clip: t.clip });
    } else if (t.clipSelector) {
      const el = await page.$(t.clipSelector);
      if (el) {
        await el.screenshot({ path: dest });
      } else {
        await page.screenshot({ path: dest });
      }
    } else {
      await page.screenshot({ path: dest, fullPage: false });
    }

    const md5 = calculateMD5(dest);
    manifest.push({
      file: t.file,
      name: t.name,
      viewport: `${t.viewport.width}x${t.viewport.height}`,
      md5
    });
    await context.close();
  }

  await browser.close();
  fs.writeFileSync(path.join(OUT_DIR, 'manifest.json'), JSON.stringify(manifest, null, 2));
  console.log('Finished capturing BEFORE screenshots!');
}

run().catch(err => {
  console.error(err);
  process.exit(1);
});
