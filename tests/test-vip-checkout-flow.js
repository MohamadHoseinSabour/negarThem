/**
 * VIP Digital Product Simplified Checkout - Playwright E2E Acceptance Test
 * 
 * Verifies:
 * 1. Complete profile customer: VIP checkout displays compact summary card, zero shipping/address fields, step 2 & heading VIP labels.
 * 2. Partial profile customer (missing name): Displays only name fields, no shipping/address fields.
 * 3. Physical merchandise checkout: Full standard checkout with address, shipping, and step 2 delivery labels.
 * 4. Multi-viewport screenshot generation (1440px desktop & 375px mobile).
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');
const { execSync } = require('child_process');

const BASE_URL = 'http://localhost/wp4';
const SCREENSHOT_DIR_ARTIFACT = 'C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\screenshots';
const SCREENSHOT_DIR_LOCAL = path.join(__dirname, 'screenshots');
const SETUP_SCRIPT = 'C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\scratch\\setup-vip-checkout-test-users.php';

[SCREENSHOT_DIR_ARTIFACT, SCREENSHOT_DIR_LOCAL].forEach(dir => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

function runPhp(scriptPath, args = '') {
    return execSync(`php -f "${scriptPath}" ${args}`, { encoding: 'utf-8' });
}

async function capture(page, filename) {
    const p1 = path.join(SCREENSHOT_DIR_ARTIFACT, filename);
    const p2 = path.join(SCREENSHOT_DIR_LOCAL, filename);
    await page.screenshot({ path: p1, fullPage: true });
    fs.copyFileSync(p1, p2);
    console.log(`[SCREENSHOT] Saved: ${filename}`);
}

async function main() {
    console.log('=== STARTING VIP DIGITAL PRODUCT CHECKOUT E2E TEST ===\n');

    // 1. Setup test users and retrieve auth cookies
    console.log('--- Setting up test users ---');
    const authOutput = runPhp(SETUP_SCRIPT);
    const users = JSON.parse(authOutput);

    const browser = await chromium.launch({ channel: 'msedge', headless: true });

    try {
        // =========================================================================
        // CASE 1: Complete Profile VIP Checkout (Desktop & Mobile)
        // =========================================================================
        console.log('\n--- CASE 1: Complete Profile Customer (test_vip_complete) ---');
        runPhp(SETUP_SCRIPT, `clear_cart ${users.complete.user_id}`);

        const contextComplete = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await contextComplete.addCookies(users.complete.cookies);
        const pageComplete = await contextComplete.newPage();

        await pageComplete.goto(`${BASE_URL}/checkout/?add-to-cart=1036`, { waitUntil: 'domcontentloaded' });
        await pageComplete.waitForTimeout(1000);

        // Verification assertions
        const step2Text = await pageComplete.locator('.cart-nk__steps__item.active').innerText();
        console.log(`Step 2 Text: "${step2Text.trim()}" (Expected: "اطلاعات خریدار و پرداخت")`);
        if (!step2Text.includes('اطلاعات خریدار و پرداخت')) {
            throw new Error(`Step 2 text mismatch! Received: ${step2Text}`);
        }

        const headingText = await pageComplete.locator('.cart-nk__box__heading').first().innerText();
        console.log(`Card Heading: "${headingText.trim()}" (Expected: "اطلاعات خریدار اشتراک VIP")`);
        if (!headingText.includes('اطلاعات خریدار اشتراک VIP')) {
            throw new Error(`Card heading mismatch! Received: ${headingText}`);
        }

        const summaryCardCount = await pageComplete.locator('.vip-checkout-summary-card').count();
        console.log(`Summary Card Present: ${summaryCardCount > 0}`);
        if (summaryCardCount === 0) {
            throw new Error('Expected .vip-checkout-summary-card to be rendered for complete customer profile!');
        }

        const customerNameText = await pageComplete.locator('.vip-checkout-summary-card .fw-bold').first().innerText();
        console.log(`Summary Customer Name: "${customerNameText.trim()}" (Expected: "محمدرضا بختیاری")`);

        // Verify unnecessary fields are NOT present
        const addressFieldCount = await pageComplete.locator('#billing_address_1_field').count();
        const cityFieldCount = await pageComplete.locator('#billing_city_field').count();
        const orderCommentsCount = await pageComplete.locator('#order_comments_field').count();

        console.log(`Address Field Present: ${addressFieldCount > 0} (Expected: false)`);
        console.log(`City Field Present: ${cityFieldCount > 0} (Expected: false)`);
        console.log(`Order Comments Present: ${orderCommentsCount > 0} (Expected: false)`);

        if (addressFieldCount > 0 || cityFieldCount > 0 || orderCommentsCount > 0) {
            throw new Error('Unnecessary fields (address/city/order comments) should NOT exist in VIP checkout!');
        }

        // Capture Desktop Screenshot
        await capture(pageComplete, 'vip-checkout-complete-desktop-1440.png');

        // Capture Mobile Screenshot
        const mobileCompleteContext = await browser.newContext({ viewport: { width: 375, height: 812 } });
        await mobileCompleteContext.addCookies(users.complete.cookies);
        const pageCompleteMobile = await mobileCompleteContext.newPage();
        await pageCompleteMobile.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });
        await pageCompleteMobile.waitForTimeout(1000);
        await capture(pageCompleteMobile, 'vip-checkout-complete-mobile-375.png');
        await pageCompleteMobile.close();
        await mobileCompleteContext.close();

        await pageComplete.close();
        await contextComplete.close();

        // =========================================================================
        // CASE 2: Partial Profile (Missing Name) VIP Checkout (Desktop)
        // =========================================================================
        console.log('\n--- CASE 2: Partial Profile Customer with Missing Name (test_vip_noname) ---');
        runPhp(SETUP_SCRIPT, `clear_cart ${users.noname.user_id}`);

        const contextNoName = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await contextNoName.addCookies(users.noname.cookies);
        const pageNoName = await contextNoName.newPage();

        await pageNoName.goto(`${BASE_URL}/checkout/?add-to-cart=1036`, { waitUntil: 'domcontentloaded' });
        await pageNoName.waitForTimeout(1000);

        const firstNameFieldCount = await pageNoName.locator('#billing_first_name_field').count();
        const lastNameFieldCount = await pageNoName.locator('#billing_last_name_field').count();
        const noNameAddressFieldCount = await pageNoName.locator('#billing_address_1_field').count();
        const noNameSummaryCount = await pageNoName.locator('.vip-checkout-summary-card').count();

        console.log(`First Name Field Present: ${firstNameFieldCount > 0} (Expected: true)`);
        console.log(`Last Name Field Present: ${lastNameFieldCount > 0} (Expected: true)`);
        console.log(`Address Field Present: ${noNameAddressFieldCount > 0} (Expected: false)`);
        console.log(`Summary Card Present: ${noNameSummaryCount > 0} (Expected: false)`);

        if (firstNameFieldCount === 0 || lastNameFieldCount === 0) {
            throw new Error('Missing name fields should be displayed for customer with incomplete profile!');
        }
        if (noNameAddressFieldCount > 0) {
            throw new Error('Address field should NEVER be displayed for VIP checkout even with partial profile!');
        }

        await capture(pageNoName, 'vip-checkout-partial-desktop-1440.png');
        await pageNoName.close();
        await contextNoName.close();

        // =========================================================================
        // CASE 3: Normal Physical Merchandise Checkout (Must remain unchanged)
        // =========================================================================
        console.log('\n--- CASE 3: Normal Physical Merchandise Checkout (Product 734) ---');
        runPhp(SETUP_SCRIPT, `clear_cart ${users.physical.user_id}`);

        const contextPhysical = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await contextPhysical.addCookies(users.physical.cookies);
        const pagePhysical = await contextPhysical.newPage();

        await pagePhysical.goto(`${BASE_URL}/checkout/?add-to-cart=734`, { waitUntil: 'domcontentloaded' });
        await pagePhysical.waitForTimeout(1000);

        const physStep2Text = await pagePhysical.locator('.cart-nk__steps__item.active').innerText();
        console.log(`Physical Step 2 Text: "${physStep2Text.trim()}" (Expected: "اطلاعات ارسال و پرداخت")`);
        if (!physStep2Text.includes('اطلاعات ارسال و پرداخت')) {
            throw new Error(`Physical step 2 text mismatch! Received: ${physStep2Text}`);
        }

        const physHeadingText = await pagePhysical.locator('.cart-nk__box__heading').first().innerText();
        console.log(`Physical Card Heading: "${physHeadingText.trim()}" (Expected: "اطلاعات گیرنده و آدرس تحویل")`);
        if (!physHeadingText.includes('اطلاعات گیرنده و آدرس تحویل')) {
            throw new Error(`Physical card heading mismatch! Received: ${physHeadingText}`);
        }

        const physAddressFieldCount = await pagePhysical.locator('#billing_address_1_field').count();
        const physCityFieldCount = await pagePhysical.locator('#billing_city_field').count();
        const physCommentsCount = await pagePhysical.locator('#order_comments_field').count();

        console.log(`Physical Address Field Present: ${physAddressFieldCount > 0} (Expected: true)`);
        console.log(`Physical City Field Present: ${physCityFieldCount > 0} (Expected: true)`);
        console.log(`Physical Order Comments Present: ${physCommentsCount > 0} (Expected: true)`);

        if (physAddressFieldCount === 0 || physCityFieldCount === 0) {
            throw new Error('Physical checkout MUST retain address and city fields!');
        }

        await capture(pagePhysical, 'normal-checkout-physical-desktop-1440.png');
        await pagePhysical.close();
        await contextPhysical.close();

        console.log('\n=== ALL PLAYWRIGHT E2E ACCEPTANCE TESTS PASSED SUCCESSFULLY! ===');
    } finally {
        await browser.close();
    }
}

main().catch(err => {
    console.error('\nE2E Test Failed with error:', err);
    process.exit(1);
});
