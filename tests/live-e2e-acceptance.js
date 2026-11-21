/**
 * Live End-to-End Acceptance Test & Multi-Viewport Screenshot Suite
 * Negar Themed Customer Club VIP Prepayment System
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

async function run() {
    console.log('=== STARTING LIVE END-TO-END ACCEPTANCE ===\n');

    // Step 0: Get fresh auth cookies from PHP
    const authOutput = runPhp('C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\scratch\\generate-auth-cookies.php');
    const authData = JSON.parse(authOutput);

    const customerCookies = authData.customer.cookies.map(c => ({
        name: c.name,
        value: c.value,
        domain: c.domain,
        path: c.path,
        httpOnly: c.httpOnly
    }));

    const adminCookies = authData.admin.cookies.map(c => ({
        name: c.name,
        value: c.value,
        domain: c.domain,
        path: c.path,
        httpOnly: c.httpOnly
    }));

    const browser = await chromium.launch({ channel: 'msedge', headless: true });

    try {
        // ==========================================
        // 1. Customer Club Landing Page
        // ==========================================
        console.log('\n--- 1. Customer Club Public Page ---');
        const landingPageDesktop = await browser.newPage({ viewport: { width: 1440, height: 900 } });
        await landingPageDesktop.goto(`${BASE_URL}/customer-club/`, { waitUntil: 'domcontentloaded' });
        await landingPageDesktop.waitForTimeout(1000);
        await capture(landingPageDesktop, 'customer-club-landing-desktop-1440.png');
        await landingPageDesktop.close();

        const landingPageMobile = await browser.newPage({ viewport: { width: 375, height: 812 } });
        await landingPageMobile.goto(`${BASE_URL}/customer-club/`, { waitUntil: 'domcontentloaded' });
        await landingPageMobile.waitForTimeout(1000);
        await capture(landingPageMobile, 'customer-club-landing-mobile-375.png');
        await landingPageMobile.close();

        // ==========================================
        // 2. Checkout Journey (Negar Plus)
        // ==========================================
        console.log('\n--- 2. Customer Checkout for Negar Plus ---');
        const customerContext = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await customerContext.addCookies(customerCookies);
        const customerPage = await customerContext.newPage();

        // Add Negar Plus (ID 1036) to cart & open checkout
        await customerPage.goto(`${BASE_URL}/checkout/?add-to-cart=1036`, { waitUntil: 'domcontentloaded' });
        await customerPage.waitForTimeout(1500);
        await capture(customerPage, 'checkout-desktop-1440.png');

        // Mobile checkout
        const mobileCustomerContext = await browser.newContext({ viewport: { width: 375, height: 812 } });
        await mobileCustomerContext.addCookies(customerCookies);
        const mobileCustomerPage = await mobileCustomerContext.newPage();
        await mobileCustomerPage.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });
        await mobileCustomerPage.waitForTimeout(1000);
        await capture(mobileCustomerPage, 'checkout-mobile-375.png');
        await mobileCustomerPage.close();

        // ==========================================
        // 3. Create Real Unpaid Order & Verify Review Guard
        // ==========================================
        console.log('\n--- 3. Verifying Order & Review Guard via PHP ---');
        const orderStepScript = 'C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\scratch\\execute-acceptance-steps.php';
        
        // We will call our acceptance step script in stages
        const stage1Output = execSync(`php -f "${orderStepScript}" stage1`, { encoding: 'utf-8' });
        console.log(stage1Output);

        // ==========================================
        // 4. Operator Admin - Member List & Pending Review
        // ==========================================
        console.log('\n--- 4. Operator Admin Views ---');
        const adminContext = await browser.newContext({ viewport: { width: 1440, height: 900 } });
        await adminContext.addCookies(adminCookies);
        const adminPage = await adminContext.newPage();

        await adminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships`, { waitUntil: 'domcontentloaded' });
        await adminPage.waitForTimeout(1000);
        await capture(adminPage, 'admin-members-list-desktop-1440.png');

        // Mobile admin list
        const mobileAdminContext = await browser.newContext({ viewport: { width: 375, height: 812 } });
        await mobileAdminContext.addCookies(adminCookies);
        const mobileAdminPage = await mobileAdminContext.newPage();
        await mobileAdminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships`, { waitUntil: 'domcontentloaded' });
        await mobileAdminPage.waitForTimeout(1000);
        await capture(mobileAdminPage, 'admin-members-list-mobile-375.png');

        // Extract membership ID from stage1
        const memMatch = stage1Output.match(/MEMBERSHIP_ID:(\d+)/);
        const membershipId = memMatch ? memMatch[1] : '1';

        // View Member Detail in Review
        await adminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships&action=view&id=${membershipId}`, { waitUntil: 'domcontentloaded' });
        await adminPage.waitForTimeout(1000);
        await capture(adminPage, 'admin-member-detail-review-desktop-1440.png');

        await mobileAdminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships&action=view&id=${membershipId}`, { waitUntil: 'domcontentloaded' });
        await mobileAdminPage.waitForTimeout(1000);
        await capture(mobileAdminPage, 'admin-member-detail-review-mobile-375.png');

        // ==========================================
        // 5. Operator Confirms Receipt -> Activates
        // ==========================================
        console.log('\n--- 5. Confirming Receipt & Activating Membership ---');
        const stage2Output = execSync(`php -f "${orderStepScript}" stage2 ${membershipId}`, { encoding: 'utf-8' });
        console.log(stage2Output);

        // ==========================================
        // 6. Customer Dashboard - Active Initial State
        // ==========================================
        console.log('\n--- 6. Customer VIP Dashboard (Active Initial) ---');
        await customerPage.goto(`${BASE_URL}/my-account/customer-club/`, { waitUntil: 'domcontentloaded' });
        await customerPage.waitForTimeout(1000);
        await capture(customerPage, 'customer-dashboard-active-desktop-1440.png');

        const activeMobileCust = await mobileCustomerContext.newPage();
        await activeMobileCust.goto(`${BASE_URL}/my-account/customer-club/`, { waitUntil: 'domcontentloaded' });
        await activeMobileCust.waitForTimeout(1000);
        await capture(activeMobileCust, 'customer-dashboard-active-mobile-375.png');
        await activeMobileCust.close();

        // ==========================================
        // 7. Operator Actions: 10M Purchase (5M deposit, 5M debt), 2M Collection, Adjust Limits
        // ==========================================
        console.log('\n--- 7. Operator Financial Actions ---');
        const stage3Output = execSync(`php -f "${orderStepScript}" stage3 ${membershipId}`, { encoding: 'utf-8' });
        console.log(stage3Output);

        // View Updated Member Detail in Admin
        await adminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships&action=view&id=${membershipId}`, { waitUntil: 'domcontentloaded' });
        await adminPage.waitForTimeout(1000);
        await capture(adminPage, 'admin-member-detail-updated-desktop-1440.png');

        await mobileAdminPage.goto(`${BASE_URL}/wp-admin/admin.php?page=negar-club-memberships&action=view&id=${membershipId}`, { waitUntil: 'domcontentloaded' });
        await mobileAdminPage.waitForTimeout(1000);
        await capture(mobileAdminPage, 'admin-member-detail-updated-mobile-375.png');

        // ==========================================
        // 8. Customer Dashboard - Updated State
        // ==========================================
        console.log('\n--- 8. Customer VIP Dashboard (Updated State) ---');
        await customerPage.goto(`${BASE_URL}/my-account/customer-club/`, { waitUntil: 'domcontentloaded' });
        await customerPage.waitForTimeout(1000);
        await capture(customerPage, 'customer-dashboard-updated-desktop-1440.png');

        const updatedMobileCust = await mobileCustomerContext.newPage();
        await updatedMobileCust.goto(`${BASE_URL}/my-account/customer-club/`, { waitUntil: 'domcontentloaded' });
        await updatedMobileCust.waitForTimeout(1000);
        await capture(updatedMobileCust, 'customer-dashboard-updated-mobile-375.png');
        await updatedMobileCust.close();

        console.log('\n=== ALL ACCEPTANCE STEPS & SCREENSHOTS COMPLETED SUCCESSFULLY ===');

    } finally {
        await browser.close();
    }
}

run().catch(err => {
    console.error('Acceptance suite failed:', err);
    process.exit(1);
});
