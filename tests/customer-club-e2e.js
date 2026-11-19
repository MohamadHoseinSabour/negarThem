/**
 * Playwright E2E & Multi-Viewport Verification for Customer Club Page.
 *
 * Verifies responsive layout (375px, 768px, 1024px, 1440px),
 * zero horizontal overflow, console errors, calculator interactivity,
 * digit normalization, and FAQ accordion behavior.
 *
 * @package NegarThem
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const TARGET_URL = 'http://localhost/wp4/customer-club/';
const VIEWPORTS = [
    { name: 'mobile-375', width: 375, height: 812 },
    { name: 'tablet-768', width: 768, height: 1024 },
    { name: 'laptop-1024', width: 1024, height: 768 },
    { name: 'desktop-1440', width: 1440, height: 900 }
];

async function runE2E() {
    console.log('--- Running Customer Club Playwright E2E Suite ---');
    console.log(`Target URL: ${TARGET_URL}\n`);

    const browser = await chromium.launch({ channel: 'msedge', headless: true });
    let passed = 0;
    let failed = 0;

    const screenshotsDir = path.join(__dirname, 'screenshots');
    if (!fs.existsSync(screenshotsDir)) {
        fs.mkdirSync(screenshotsDir, { recursive: true });
    }

    try {
        // Test 1: Viewport checks and overflow inspection
        for (const vp of VIEWPORTS) {
            const page = await browser.newPage({
                viewport: { width: vp.width, height: vp.height },
                userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            });

            const consoleErrors = [];
            page.on('console', msg => {
                if (msg.type() === 'error') {
                    consoleErrors.push(msg.text());
                }
            });

            const response = await page.goto(TARGET_URL, { waitUntil: 'domcontentloaded', timeout: 15000 });
            const status = response ? response.status() : 0;

            if (status !== 200) {
                console.log(`[FAIL] ${vp.name}: Expected HTTP 200, got ${status}`);
                failed++;
                await page.close();
                continue;
            }

            // Check horizontal overflow
            const overflow = await page.evaluate(() => {
                const docWidth = document.documentElement.clientWidth;
                const scrollWidth = document.documentElement.scrollWidth;
                return {
                    docWidth,
                    scrollWidth,
                    hasOverflow: scrollWidth > docWidth
                };
            });

            if (overflow.hasOverflow) {
                console.log(`[FAIL] ${vp.name}: Horizontal overflow detected! (docWidth: ${overflow.docWidth}, scrollWidth: ${overflow.scrollWidth})`);
                failed++;
            } else {
                console.log(`[PASS] ${vp.name}: HTTP 200 & Zero Horizontal Overflow (width: ${overflow.docWidth}px)`);
                passed++;
            }

            // Capture screenshot
            const shotPath = path.join(screenshotsDir, `customer-club-${vp.name}.png`);
            await page.screenshot({ path: shotPath, fullPage: false });

            await page.close();
        }

        // Test 2: Interactive Calculator & Persian Digits on Desktop
        const page = await browser.newPage({
            viewport: { width: 1440, height: 900 }
        });

        await page.goto(TARGET_URL, { waitUntil: 'domcontentloaded', timeout: 15000 });

        console.log('\n--- Testing Interactive Calculator Behaviors ---');

        // Check initial default calculation
        const initialMonthly = await page.textContent('#res-monthly-amount');
        console.log(`Initial Monthly Amount: ${initialMonthly.trim()} تومان`);
        if (initialMonthly && initialMonthly.trim() !== '۰') {
            console.log('[PASS] T326: Calculator initialized with valid default estimate');
            passed++;
        } else {
            console.log('[FAIL] T326: Calculator failed to initialize default estimate');
            failed++;
        }

        // Type Persian digits in amount input
        const amountInput = await page.$('#negar-calc-amount-input');
        if (amountInput) {
            await amountInput.fill('۱۵۰۰۰۰۰۰'); // 15,000,000 in Persian
            await page.waitForTimeout(500);

            const updatedMonthly = await page.textContent('#res-monthly-amount');
            const excessVisible = await page.isVisible('#res-excess-row');

            console.log(`Updated Monthly after 15M IRT: ${updatedMonthly.trim()} تومان (Excess visible: ${excessVisible})`);
            if (excessVisible) {
                console.log('[PASS] T326: Persian digits normalized and excess ceiling row rendered (Option B)');
                passed++;
            } else {
                console.log('[FAIL] T326: Excess ceiling row expected for 15M on negar_plus');
                failed++;
            }
        }

        // Test 3: Plan Tabs Switching
        const aghazTab = await page.$('button[data-plan-id="negar_aghaz"]');
        if (aghazTab) {
            await aghazTab.click();
            await page.waitForTimeout(400);

            const activeTab = await page.$('.negar-club-calc__tab.is-active');
            const planId = await activeTab.getAttribute('data-plan-id');
            if (planId === 'negar_aghaz') {
                console.log('[PASS] T326: Plan tab successfully switched to negar_aghaz');
                passed++;
            } else {
                console.log(`[FAIL] T326: Expected active tab negar_aghaz, got ${planId}`);
                failed++;
            }
        }

        // Test 4: FAQ Accordion Toggle
        console.log('\n--- Testing Accessible FAQ Accordion ---');
        const firstFaqToggle = await page.$('.negar-club-faq__toggle');
        if (firstFaqToggle) {
            const initialExpanded = await firstFaqToggle.getAttribute('aria-expanded');
            await firstFaqToggle.click();
            await page.waitForTimeout(300);

            const toggledExpanded = await firstFaqToggle.getAttribute('aria-expanded');
            const panelId = await firstFaqToggle.getAttribute('aria-controls');
            const panelVisible = await page.isVisible(`#${panelId}`);

            if (initialExpanded === 'false' && toggledExpanded === 'true' && panelVisible) {
                console.log('[PASS] T327: FAQ accordion expands with proper ARIA and visible answer');
                passed++;
            } else {
                console.log(`[FAIL] T327: Accordion failed to toggle (expanded: ${toggledExpanded}, visible: ${panelVisible})`);
                failed++;
            }
        }

        // Test 5: Plan Card Select Button
        console.log('\n--- Testing Plan Card Select Button & Intent ---');
        const selectBtn = await page.$('.js-select-plan[data-plan-id="negar_vizhe"]');
        if (selectBtn) {
            await selectBtn.click();
            await page.waitForTimeout(600);

            const storedIntent = await page.evaluate(() => sessionStorage.getItem('negar_club_selected_plan'));
            if (storedIntent === 'negar_vizhe') {
                console.log('[PASS] T328: Session intent successfully stored ("negar_vizhe")');
                passed++;
            } else {
                console.log(`[FAIL] T328: Expected session intent "negar_vizhe", got ${storedIntent}`);
                failed++;
            }
        }

        await page.close();
    } catch (err) {
        console.error('Test execution error:', err);
        failed++;
    } finally {
        await browser.close();
    }

    console.log(`\n--- Playwright Verification Summary ---`);
    console.log(`Total: ${passed + failed}`);
    console.log(`Passed: ${passed}`);
    console.log(`Failed: ${failed}`);

    if (failed > 0) {
        process.exit(1);
    }
    process.exit(0);
}

runE2E();
