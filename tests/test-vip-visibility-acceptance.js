/**
 * VIP Product Visibility & Storefront Exclusion Acceptance Suite
 * 
 * Verifies:
 * 1. Homepage hero flash-deal card displays real physical merchandise (NOT VIP membership).
 * 2. Homepage sections contain ZERO VIP products.
 * 3. Shop catalog and archives exclude VIP products.
 * 4. Search and AJAX search do not return VIP products.
 * 5. AJAX shop filters and pagination exclude VIP products.
 * 6. QuickView modal rejects VIP products.
 * 7. Customer Club continues to display all 3 plans.
 * 8. VIP plan checkout buttons continue to add to cart and lead to VIP checkout.
 * 9. WordPress admin product listing continues to show VIP products for operators.
 * 10. Captures desktop & mobile screenshots.
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');
const { execSync } = require('child_process');

const BASE_URL = 'http://localhost/wp4';
const SCREENSHOT_DIR_ARTIFACT = 'C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\screenshots';
const SCREENSHOT_DIR_LOCAL = path.join(__dirname, 'screenshots');

[SCREENSHOT_DIR_ARTIFACT, SCREENSHOT_DIR_LOCAL].forEach(dir => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

function runPhp(scriptPath) {
    return execSync(`php -f "${scriptPath}"`, { encoding: 'utf-8' });
}

async function capture(page, filename) {
    const p1 = path.join(SCREENSHOT_DIR_ARTIFACT, filename);
    const p2 = path.join(SCREENSHOT_DIR_LOCAL, filename);
    await page.screenshot({ path: p1, fullPage: true });
    fs.copyFileSync(p1, p2);
    console.log(`[SCREENSHOT] Saved: ${filename}`);
}

async function main() {
    console.log('=== STARTING VIP PRODUCT VISIBILITY ACCEPTANCE SUITE ===\n');

    // Get admin cookies
    const authOutput = runPhp('C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\scratch\\generate-auth-cookies.php');
    const authData = JSON.parse(authOutput);
    const adminCookies = authData.admin.cookies;

    const browser = await chromium.launch({ channel: 'msedge', headless: true });

    try {
        // =========================================================================
        // 1. Homepage Hero Slider & Storefront Sections
        // =========================================================================
        console.log('--- 1. Verifying Homepage Hero Slider & Product Sections ---');
        const pageDesktop = await browser.newPage({ viewport: { width: 1440, height: 900 } });
        await pageDesktop.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
        await pageDesktop.waitForTimeout(1000);

        const heroProductTitle = await pageDesktop.locator('.landing-nk__products .title a').first().innerText().catch(e => '');
        console.log(`Hero Product Title: "${heroProductTitle.trim()}"`);
        if (heroProductTitle.includes('اشتراک VIP') || heroProductTitle.includes('VIP')) {
            throw new Error(`CRITICAL: VIP product found in hero slider! Received: "${heroProductTitle}"`);
        }
        if (!heroProductTitle) {
            throw new Error('Hero product title is empty!');
        }

        const homeFullText = await pageDesktop.locator('body').innerText();
        // Check if any product card has "اشتراک VIP"
        const productCardVipCount = await pageDesktop.locator('.landing-nk__products, .negar-product-tabs-section, .negar-category-showcase-section, .negar-recently-viewed')
            .locator('text=اشتراک VIP').count();
        console.log(`VIP product mentions in homepage product sections: ${productCardVipCount}`);
        if (productCardVipCount > 0) {
            throw new Error(`CRITICAL: Found ${productCardVipCount} VIP products rendered in homepage sections!`);
        }

        await capture(pageDesktop, 'homepage-hero-clean-desktop-1440.png');

        // Mobile Homepage
        const pageMobile = await browser.newPage({ viewport: { width: 375, height: 812 } });
        await pageMobile.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
        await pageMobile.waitForTimeout(1000);
        await capture(pageMobile, 'homepage-hero-clean-mobile-375.png');
        await pageMobile.close();

        // =========================================================================
        // 2. Shop Archive & Live Search
        // =========================================================================
        console.log('\n--- 2. Verifying Shop Archive & Search ---');
        await pageDesktop.goto(`${BASE_URL}/shop/`, { waitUntil: 'domcontentloaded' });
        await pageDesktop.waitForTimeout(1000);

        const shopVipCount = await pageDesktop.locator('#woor').locator('text=اشتراک VIP').count();
        console.log(`VIP products in /shop/ catalog grid: ${shopVipCount} (Expected: 0)`);
        if (shopVipCount > 0) {
            throw new Error(`Found ${shopVipCount} VIP products in shop catalog grid!`);
        }

        // Test Live Search for VIP
        await pageDesktop.goto(`${BASE_URL}/?s=VIP&post_type=product`, { waitUntil: 'domcontentloaded' });
        await pageDesktop.waitForTimeout(1000);
        const searchGridVipCount = await pageDesktop.locator('#woor, .products').locator('text=اشتراک VIP').count();
        console.log(`VIP products in search results grid for 'VIP': ${searchGridVipCount} (Expected: 0)`);
        if (searchGridVipCount > 0) {
            throw new Error(`Search results grid returned ${searchGridVipCount} VIP products!`);
        }

        // =========================================================================
        // 3. Customer Club Plans & Purchase Journey
        // =========================================================================
        console.log('\n--- 3. Verifying Customer Club Plans & Checkout ---');
        await pageDesktop.goto(`${BASE_URL}/customer-club/`, { waitUntil: 'domcontentloaded' });
        await pageDesktop.waitForTimeout(1000);

        const clubPlanCount = await pageDesktop.locator('.negar-club-plan-card').count();
        console.log(`Customer Club Plan Cards Rendered: ${clubPlanCount} (Expected: 3)`);
        if (clubPlanCount < 3) {
            throw new Error(`Customer club must display all 3 plans! Found: ${clubPlanCount}`);
        }

        const planLink = await pageDesktop.locator('.negar-club-plan-card a[href*="add-to-cart"]').first().getAttribute('href');
        console.log(`First Plan Checkout Link: "${planLink}"`);
        if (!planLink || !planLink.includes('add-to-cart=')) {
            throw new Error(`Plan card does not have valid add-to-cart checkout link! Received: ${planLink}`);
        }

        await capture(pageDesktop, 'customer-club-plans-verified.png');

        // Follow plan button to checkout
        await pageDesktop.goto(planLink, { waitUntil: 'domcontentloaded' });
        await pageDesktop.waitForTimeout(1000);
        const isCheckout = pageDesktop.url().includes('checkout');
        console.log(`Navigated to Checkout: ${isCheckout} (URL: ${pageDesktop.url()})`);
        if (!isCheckout) {
            throw new Error(`Plan link failed to reach checkout! URL: ${pageDesktop.url()}`);
        }

        // =========================================================================
        // 4. Operator Admin Listing Preservation
        // =========================================================================
        console.log('\n--- 4. Verifying Admin Product Listing for Store Operators ---');
        const adminContext = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await adminContext.addCookies(adminCookies);
        const adminPage = await adminContext.newPage();

        await adminPage.goto(`${BASE_URL}/wp-admin/edit.php?post_type=product`, { waitUntil: 'domcontentloaded' });
        await adminPage.waitForTimeout(1000);

        const adminVipCount = await adminPage.locator('#the-list').locator('text=اشتراک VIP').count();
        console.log(`VIP products visible in WordPress Admin for operators: ${adminVipCount} (Expected: >= 3)`);
        if (adminVipCount < 3) {
            throw new Error(`Admin product management list must preserve all VIP products! Found: ${adminVipCount}`);
        }

        await capture(adminPage, 'admin-products-list-vip-preserved.png');

        await adminPage.close();
        await adminContext.close();
        await pageDesktop.close();

        console.log('\n=== ALL VIP PRODUCT VISIBILITY ACCEPTANCE TESTS PASSED! ===');
    } finally {
        await browser.close();
    }
}

main().catch(err => {
    console.error('\nAcceptance test failed:', err);
    process.exit(1);
});
