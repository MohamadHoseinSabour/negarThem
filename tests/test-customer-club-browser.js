const { chromium } = require('playwright');
const path = require('path');

async function main() {
    console.log('--- Verifying Customer Club Page in Real Browser ---');
    const browser = await chromium.launch({ channel: 'msedge', headless: true });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

    const errors = [];
    page.on('console', msg => {
        if (msg.type() === 'error') errors.push(msg.text());
    });

    const response = await page.goto('http://localhost/wp4/customer-club/', { waitUntil: 'domcontentloaded', timeout: 15000 });
    const status = response.status();
    console.log(`URL: http://localhost/wp4/customer-club/`);
    console.log(`HTTP Status: ${status}`);

    await page.waitForTimeout(1000);

    // Check DOM sections
    const hasHero = await page.locator('.negar-club-hero').count() > 0;
    const hasBenefits = await page.locator('.negar-club-benefits').count() > 0;
    const hasPlans = await page.locator('.negar-club-plans').count() > 0;
    const hasComparison = await page.locator('.negar-club-comparison').count() > 0;
    const hasCalculator = await page.locator('.negar-club-calculator').count() > 0;
    const hasSteps = await page.locator('.negar-club-steps').count() > 0;
    const hasFaq = await page.locator('.negar-club-faq').count() > 0;

    console.log(`DOM Sections Rendered:`);
    console.log(`  Hero: ${hasHero}`);
    console.log(`  Benefits: ${hasBenefits}`);
    console.log(`  Plans: ${hasPlans}`);
    console.log(`  Comparison: ${hasComparison}`);
    console.log(`  Calculator: ${hasCalculator}`);
    console.log(`  Steps: ${hasSteps}`);
    console.log(`  FAQ: ${hasFaq}`);
    console.log(`Console Errors: ${errors.length}`);

    const screenshotPath = 'C:\\Users\\mmhhs\\.gemini\\antigravity\\brain\\0a335b78-6ba6-4a3d-ab97-d8c45926ad30\\screenshots\\customer-club-verified-200.png';
    await page.screenshot({ path: screenshotPath, fullPage: true });
    console.log(`Screenshot captured: customer-club-verified-200.png`);

    await browser.close();

    if (status !== 200 || !hasHero || !hasPlans) {
        console.error('Verification FAILED!');
        process.exit(1);
    }
    console.log('Browser Verification SUCCESSFUL (HTTP 200)!');
}

main().catch(err => {
    console.error(err);
    process.exit(1);
});
