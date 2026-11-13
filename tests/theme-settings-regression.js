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
	const imageFixture = JSON.parse(php('image-fixture'));
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

	await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=banners', { waitUntil: 'load' });
	assert.strictEqual(await page.locator('#negar-media-link-hero-slide-one').getAttribute('type'), 'text', 'Media destination input must allow site-relative paths without HTML type=url rejection');
	const heroMediaControl = page.locator('#negar-media-id-hero-slide-one').locator('xpath=ancestor::*[@data-media-control]');
	const heroPreview = heroMediaControl.locator('.negar-media-control__preview');
	const heroFallbackUrl = await heroPreview.getAttribute('data-fallback-url');
	assert(heroFallbackUrl && heroFallbackUrl.length > 0, 'Media preview must expose its default fallback URL');
	await heroMediaControl.locator('.negar-media-control__select').click();
	const mediaModal = page.locator('.media-modal:visible').last();
	await mediaModal.locator('#menu-item-browse').click();
	const firstAttachment = mediaModal.locator('.attachments-browser .attachment').first();
	await firstAttachment.waitFor({ state: 'visible' });
	await firstAttachment.click();
	await mediaModal.locator('button.media-button-select').click();
	const selectedAttachmentId = await page.locator('#negar-media-id-hero-slide-one').inputValue();
	assert(Number(selectedAttachmentId) > 0, 'Selecting an image from Media Library must populate a positive attachment ID');
	const selectedPreviewSrc = await heroPreview.getAttribute('src');
	assert(selectedPreviewSrc && selectedPreviewSrc.length > 0, 'Selecting an image from Media Library must update the preview src');
	await heroMediaControl.locator('.negar-media-control__remove').click();
	assert.strictEqual(await page.locator('#negar-media-id-hero-slide-one').inputValue(), '', 'Clearing media must immediately clear the attachment ID');
	assert.strictEqual(await heroPreview.getAttribute('src'), heroFallbackUrl, 'Clearing media must immediately restore the default preview image');
	await Promise.all([
	  page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	  page.locator('form[action="options.php"] input[type="submit"]').click(),
	]);
	assert.strictEqual(await page.locator('#negar-media-id-hero-slide-one').inputValue(), '0', 'Saving cleared media must persist attachment ID 0');
	assert.strictEqual(await heroPreview.getAttribute('src'), heroFallbackUrl, 'Reopening after clearing media must display the default preview image');
	await heroMediaControl.locator('.negar-media-control__select').click();
	const reselectModal = page.locator('.media-modal:visible').last();
	await reselectModal.locator('#menu-item-browse').click();
	const reselectAttachment = reselectModal.locator('.attachments-browser .attachment').first();
	await reselectAttachment.waitFor({ state: 'visible' });
	await reselectAttachment.click();
	await reselectModal.locator('button.media-button-select').click();
	assert(Number(await page.locator('#negar-media-id-hero-slide-one').inputValue()) > 0, 'Re-selecting an image from Media Library after clearing must populate a positive attachment ID');

	await page.locator('#negar-media-id-hero-slide-one').fill(String(imageFixture.id));
	await page.locator('#negar-media-link-hero-slide-one').fill('/shop/?phase5-relative-test=1');
	await page.locator('#negar-media-alt-hero-slide-one').fill('Phase 5 media test image');
	await page.locator('#negar-media-title-banners-grid-one').fill('Phase 5 banner title');
	await Promise.all([
	  page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	  page.locator('form[action="options.php"] input[type="submit"]').click(),
	]);
	assert.strictEqual(await page.locator('#negar-media-id-hero-slide-one').inputValue(), String(imageFixture.id), 'Authenticated media form submission must preserve a valid attachment ID');
	assert.strictEqual(await page.locator('#negar-media-link-hero-slide-one').inputValue(), '/shop/?phase5-relative-test=1', 'Authenticated media form submission must accept and preserve a site-relative link');

	await page.locator('#negar-media-link-hero-slide-one').fill(BASE_URL + '/shop/?phase5-media-test=1');
	await Promise.all([
	  page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	  page.locator('form[action="options.php"] input[type="submit"]').click(),
	]);
	assert.strictEqual(await page.locator('#negar-media-link-hero-slide-one').inputValue(), BASE_URL + '/shop/?phase5-media-test=1', 'Authenticated media form submission must preserve an HTTP(S) link');

	await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
	await page.waitForTimeout(200);
	const heroSsrResponse = await page.request.get(BASE_URL);
	assert.strictEqual(heroSsrResponse.status(), 200, 'Homepage SSR response must return HTTP 200');
	const heroSsrHtml = await heroSsrResponse.text();
	const heroSectionMatch = heroSsrHtml.match(/<section class="landing-nk[\s\S]*?<\/section>/);
	assert(heroSectionMatch, 'Server-rendered HTML must include the Hero section');
	const heroSectionHtml = heroSectionMatch[0];
	assert(heroSectionHtml.includes(imageFixture.url), 'Server-rendered Hero HTML must directly include the saved hero image URL without JS replacement');
	assert(heroSectionHtml.includes('alt="Phase 5 media test image"'), 'Server-rendered Hero HTML must directly include the saved hero alt attribute');
	assert(heroSectionHtml.includes(BASE_URL + '/shop/?phase5-media-test=1'), 'Server-rendered Hero HTML must directly include the saved hero destination URL');
	assert(!heroSectionHtml.includes('data-negar-media-image='), 'Hero slides must no longer emit client-side image replacement attributes');
	assert.strictEqual(await page.locator('[data-negar-hero-media="one"] img').getAttribute('src'), imageFixture.url, 'Saved hero media must render after reload');
	assert.strictEqual(await page.locator('[data-negar-hero-media="one"] img').getAttribute('alt'), 'Phase 5 media test image', 'Saved hero alt must render after reload');
	assert.strictEqual(await page.locator('[data-negar-hero-media="one"] .detail a.btn').getAttribute('href'), BASE_URL + '/shop/?phase5-media-test=1', 'Saved hero destination must render after reload');
	assert.strictEqual((await page.locator('.banners-nk-60d1115b .title').textContent()).trim(), 'Phase 5 banner title', 'Saved banner editorial copy must render without changing the banner layout');

	const heroErrors = [];
	const onHeroPageError = (err) => heroErrors.push(err.message);
	page.on('pageerror', onHeroPageError);

	const submitHeroSlideVisibility = async (enableOne, enableTwo) => {
	  await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=banners', { waitUntil: 'load' });
	  const cbOne = page.locator('input[type="checkbox"][name="negar_theme_media_options[records][hero-slide-one][enabled]"]');
	  const cbTwo = page.locator('input[type="checkbox"][name="negar_theme_media_options[records][hero-slide-two][enabled]"]');
	  if (enableOne) await cbOne.check(); else await cbOne.uncheck();
	  if (enableTwo) await cbTwo.check(); else await cbTwo.uncheck();
	  await Promise.all([
	    page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	    page.locator('form[action="options.php"] input[type="submit"]').click(),
	  ]);
	};

	const heroMobileCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true, locale: 'fa-IR' });
	const heroMobilePage = await heroMobileCtx.newPage();
	heroMobilePage.on('pageerror', onHeroPageError);

	try {
	  // Scenario A: Both slides enabled (already saved above; verify two-slide Swiper + nav + mobile)
	  await submitHeroSlideVisibility(true, true);
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel .swiper-slide').count(), 2, 'Two enabled hero slides must render two Swiper slides');
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel.swiper-initialized').count(), 1, 'Hero Swiper must initialize with two slides');
	  assert.strictEqual(await page.locator('.landing-nk__carousel .swiper-button-next').count(), 1, 'Two enabled slides must expose hero navigation buttons');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Two-slide hero must have zero desktop overflow');
	  await heroMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await heroMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Two-slide hero must have zero mobile overflow');

	  // Scenario B: Only first slide enabled
	  await submitHeroSlideVisibility(true, false);
	  const ssrOneHtml = await (await page.request.get(BASE_URL)).text();
	  assert(ssrOneHtml.includes('data-negar-hero-media="one"') && !ssrOneHtml.includes('data-negar-hero-media="two"'), 'Only-first-slide SSR HTML must include slide 1 and exclude slide 2');
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel .swiper-slide').count(), 1, 'Only-first-slide mode must render exactly 1 Swiper slide');
	  assert.strictEqual(await page.locator('.landing-nk__carousel .swiper-button-next').count(), 0, 'Single-slide hero must not render navigation buttons to hidden slides');
	  assert.strictEqual(await page.evaluate(() => Boolean(document.querySelector('#negar-home-hero-carousel')?.swiper && !document.querySelector('#negar-home-hero-carousel').swiper.params.loop)), true, 'Single-slide hero must disable Swiper loop');
	  assert.strictEqual(await page.locator('[data-negar-hero-media="one"] img').isVisible(), true, 'Only-first-slide hero image must remain visible on desktop');
	  assert.strictEqual(await page.locator('.swiper-landing-product-nk.swiper-initialized').count(), 1, 'Single-slide hero must not disrupt adjacent product Swiper');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Only-first-slide hero must have zero desktop overflow');
	  await heroMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await heroMobilePage.locator('[data-negar-hero-media="one"] img').isVisible(), true, 'Only-first-slide hero image must remain visible on mobile');
	  assert.strictEqual(await heroMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Only-first-slide hero must have zero mobile overflow');

	  // Scenario C: Only second slide enabled
	  await submitHeroSlideVisibility(false, true);
	  const ssrTwoHtml = await (await page.request.get(BASE_URL)).text();
	  assert(!ssrTwoHtml.includes('data-negar-hero-media="one"') && ssrTwoHtml.includes('data-negar-hero-media="two"'), 'Only-second-slide SSR HTML must exclude slide 1 and include slide 2');
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel .swiper-slide').count(), 1, 'Only-second-slide mode must render exactly 1 Swiper slide');
	  assert.strictEqual(await page.locator('.landing-nk__carousel .swiper-button-next').count(), 0, 'Only-second-slide hero must not render navigation buttons');
	  assert.strictEqual(await page.locator('[data-negar-hero-media="two"] img').isVisible(), true, 'Only-second-slide hero image must remain visible on desktop');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Only-second-slide hero must have zero desktop overflow');
	  await heroMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await heroMobilePage.locator('[data-negar-hero-media="two"] img').isVisible(), true, 'Only-second-slide hero image must remain visible on mobile');
	  assert.strictEqual(await heroMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Only-second-slide hero must have zero mobile overflow');

	  // Scenario D: Both slides disabled
	  await submitHeroSlideVisibility(false, false);
	  const ssrNoneHtml = await (await page.request.get(BASE_URL)).text();
	  assert(ssrNoneHtml.includes('landing-nk--no-slides') && !ssrNoneHtml.includes('landing-nk__carousel') && ssrNoneHtml.includes('landing-nk__products'), 'Zero-slide SSR HTML must omit blank carousel while keeping featured product box');
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('.landing-nk__carousel').count(), 0, 'Zero-slide hero must not render an empty carousel box');
	  assert.strictEqual(await page.locator('.landing-nk__products__box').isVisible(), true, 'Adjacent featured product box must remain visible when hero slides are disabled');
	  assert.strictEqual(await page.locator('.swiper-landing-product-nk.swiper-initialized').count(), 1, 'Other Swipers must still initialize cleanly when hero slides are disabled');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Zero-slide hero must have zero desktop overflow');
	  await heroMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await heroMobilePage.locator('.landing-nk__products__box').isVisible(), true, 'Adjacent featured product box must remain visible on mobile when hero slides are disabled');
	  assert.strictEqual(await heroMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Zero-slide hero must have zero mobile overflow');

	  assert.strictEqual(heroErrors.length, 0, 'Hero visibility combinations must produce zero JavaScript errors: ' + heroErrors.join('; '));
	} finally {
	  await submitHeroSlideVisibility(true, true);
	  page.off('pageerror', onHeroPageError);
	  await heroMobileCtx.close();
	}

	// Phase 5.1-B2: Live banner SSR, empty editorial text, image-only integrity, network suppression, and responsive visibility collapse.
	const openMediaRecord = async (recordId) => {
	  const details = page.locator(`#negar-media-id-${recordId}`).locator('xpath=ancestor::details[contains(@class, "negar-media-record")]');
	  if ((await details.getAttribute('open')) === null) {
	    await details.locator('summary').click();
	  }
	};

	await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=banners', { waitUntil: 'load' });
	await openMediaRecord('wide-banner-one');
	await openMediaRecord('compact-12-one');
	await openMediaRecord('banners-grid-two');
	await page.locator('#negar-media-id-wide-banner-one').fill(String(imageFixture.id));
	await page.locator('#negar-media-link-wide-banner-one').fill('https://example.com/wide-banner-e2e');
	await page.locator('#negar-media-alt-wide-banner-one').fill('Wide Banner E2E Alt');
	await page.locator('#negar-media-id-compact-12-one').fill(String(imageFixture.id));
	await page.locator('#negar-media-link-compact-12-one').fill('/shop/?compact12=1');
	await page.locator('#negar-media-alt-compact-12-one').fill('Compact 12 One Alt');
	await page.locator('#negar-media-title-banners-grid-two').fill('');
	await page.locator('#negar-media-subtitle-banners-grid-two').fill('');
	await Promise.all([
	  page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	  page.locator('form[action="options.php"] input[type="submit"]').click(),
	]);

	const bannerSsrHtml = await (await page.request.get(BASE_URL)).text();
	for (const bannerId of [
	  'banners-grid-one', 'banners-grid-two', 'wide-banner-one',
	  'banners-10-one', 'banners-10-two', 'compact-12-one', 'compact-12-two',
	  'banners-18-one', 'banners-18-two', 'compact-27-one', 'compact-27-two',
	  'banners-29-one', 'banners-29-two', 'banners-32-one', 'banners-32-two',
	]) {
	  assert(bannerSsrHtml.includes(`data-negar-banner-record="${bannerId}"`), `Homepage SSR HTML must include banner record ${bannerId}`);
	}
	assert(!bannerSsrHtml.includes('data-negar-media-image=') && !bannerSsrHtml.includes('data-negar-media-enabled=') && !/data-negar-banner[\s>]/.test(bannerSsrHtml), 'Homepage SSR HTML must not contain legacy client-side banner replacement attributes');
	assert(bannerSsrHtml.includes('https://example.com/wide-banner-e2e') && bannerSsrHtml.includes('alt="Wide Banner E2E Alt"'), 'Wide banner SSR HTML must contain configured link and alt directly');
	assert(bannerSsrHtml.includes('/shop/?compact12=1') && bannerSsrHtml.includes('alt="Compact 12 One Alt"'), 'Compact banner SSR HTML must contain configured link and alt directly');
	assert(bannerSsrHtml.includes('<div class="title f-iran-yekan fw-bold"></div>') && bannerSsrHtml.includes('<div class="sub-title f-iran-yekan fw-normal"></div>'), 'Intentional empty editorial text must render empty without demo fallback text');

	const bannerErrors = [];
	const onBannerPageError = (err) => bannerErrors.push(err.message);
	page.on('pageerror', onBannerPageError);
	const bannerMobileCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true, locale: 'fa-IR' });
	const bannerMobilePage = await bannerMobileCtx.newPage();
	bannerMobilePage.on('pageerror', onBannerPageError);

	const submitBannerVis = async (states) => {
	  await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=banners', { waitUntil: 'load' });
	  for (const [id, enabled] of Object.entries(states)) {
	    await openMediaRecord(id);
	    const cb = page.locator(`input[type="checkbox"][name="negar_theme_media_options[records][${id}][enabled]"]`);
	    if (enabled) await cb.check(); else await cb.uncheck();
	  }
	  await Promise.all([
	    page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	    page.locator('form[action="options.php"] input[type="submit"]').click(),
	  ]);
	};

	const requestedUrls = [];
	const onBannerRequest = (req) => requestedUrls.push(req.url());
	page.on('request', onBannerRequest);
	bannerMobilePage.on('request', onBannerRequest);

	try {
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('[data-negar-banner-record="wide-banner-one"] .title').count(), 0, 'Image-only wide banner must not render title text elements');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="compact-12-one"] .title').count(), 0, 'Image-only compact banner must not render title text elements');
	  assert.strictEqual((await page.locator('[data-negar-banner-record="banners-grid-two"] .title').textContent()), '', 'Cleared banner title must render empty on frontend');

	  // Single active card per pair + disabled wide banner
	  await submitBannerVis({
	    'wide-banner-one': false,
	    'banners-grid-one': true,
	    'banners-grid-two': false,
	    'compact-12-one': false,
	    'compact-12-two': true,
	  });
	  requestedUrls.length = 0;
	  await page.goto(BASE_URL, { waitUntil: 'networkidle' });
	  assert.strictEqual(await page.locator('[data-negar-banner-record="wide-banner-one"]').count(), 0, 'Disabled wide banner must not render in DOM');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="banners-grid-one"]').count(), 1, 'Enabled banner-grid-one must remain visible when sibling is disabled');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="banners-grid-two"]').count(), 0, 'Disabled banner-grid-two must not render in DOM');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="compact-12-one"]').count(), 0, 'Disabled compact-12-one must not render in DOM');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="compact-12-two"]').count(), 1, 'Enabled compact-12-two must remain visible when sibling is disabled');
	  assert.strictEqual(await page.locator('[data-negar-banner-record="banners-10-one"]').count(), 1, 'Disabling one banner group must not affect unrelated banner groups');
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel .swiper-slide').count(), 2, 'Updating banners must not disturb Hero slider visibility');
	  assert.strictEqual(requestedUrls.some((url) => url.includes('banner-03.png')), false, 'Disabled wide banner-03 image must not trigger network requests');
	  const singleGridCols = await page.evaluate(() => {
	    const editorial = document.querySelector('.elementor-element-4e969361 .negar-banners-2col--single');
	    const compact = document.querySelector('.elementor-element-26635d31.negar-dual-banners-asymmetric--single .e-con-inner');
	    return {
	      editorialCols: editorial ? getComputedStyle(editorial).gridTemplateColumns.trim().split(/\s+/).length : 0,
	      compactCols: compact ? getComputedStyle(compact).gridTemplateColumns.trim().split(/\s+/).length : 0,
	    };
	  });
	  assert.strictEqual(singleGridCols.editorialCols, 1, 'Single-active editorial banner group must collapse to 1 column on desktop');
	  assert.strictEqual(singleGridCols.compactCols, 1, 'Single-active asymmetric banner group must collapse to 1 column on desktop');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Single-active banners must have zero desktop overflow');
	  await bannerMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await bannerMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Single-active banners must have zero mobile overflow');

	  // Both banners disabled in pair -> empty container omitted cleanly
	  await submitBannerVis({
	    'wide-banner-one': false,
	    'banners-grid-one': false,
	    'banners-grid-two': false,
	    'compact-12-one': false,
	    'compact-12-two': false,
	  });
	  await page.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await page.locator('.elementor-element-4e969361').count(), 0, 'Fully disabled editorial banner grid must omit its empty wrapper');
	  assert.strictEqual(await page.locator('.elementor-element-26635d31').count(), 0, 'Fully disabled asymmetric banner pair must omit its empty wrapper');
	  assert.strictEqual(await page.locator('#negar-home-hero-carousel .swiper-slide').count(), 2, 'Omitting disabled banner groups must keep Hero intact');
	  assert.strictEqual(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Omitted banner groups must have zero desktop overflow');
	  await bannerMobilePage.goto(BASE_URL, { waitUntil: 'load' });
	  assert.strictEqual(await bannerMobilePage.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true, 'Omitted banner groups must have zero mobile overflow');
	  assert.strictEqual(bannerErrors.length, 0, 'Banner SSR and visibility checks must produce zero JavaScript errors: ' + bannerErrors.join('; '));
	} finally {
	  await submitBannerVis({
	    'wide-banner-one': true,
	    'banners-grid-one': true,
	    'banners-grid-two': true,
	    'compact-12-one': true,
	    'compact-12-two': true,
	  });
	  page.off('request', onBannerRequest);
	  bannerMobilePage.off('request', onBannerRequest);
	  page.off('pageerror', onBannerPageError);
	  bannerMobilePage.off('pageerror', onBannerPageError);
	  await bannerMobileCtx.close();
	}

	await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=banners', { waitUntil: 'domcontentloaded' });
	await page.locator('#negar-pack-search-gaming-zone-fc').fill(productFixture.label);
	await page.waitForTimeout(350);
	const packResult = page.locator('[data-input-name="negar_theme_product_pack_options[packs][gaming-zone][fc]"] button[data-product-id="' + productFixture.id + '"]');
	await packResult.click();
	await page.locator('#negar-pack-search-gaming-zone-mirage').fill(productFixture.label);
	await page.waitForTimeout(350);
	const mirageResult = page.locator('[data-input-name="negar_theme_product_pack_options[packs][gaming-zone][mirage]"] button[data-product-id="' + productFixture.id + '"]');
	await mirageResult.click();
	await Promise.all([
	  page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'banners'),
	  page.locator('form[action="options.php"] input[type="submit"]').click(),
	]);
	assert.strictEqual(await page.locator('[data-input-name="negar_theme_product_pack_options[packs][gaming-zone][fc]"] li[data-product-id="' + productFixture.id + '"]').count(), 1, 'Authenticated product-pack submission must retain the selected product');
	assert.strictEqual(await page.locator('[data-input-name="negar_theme_product_pack_options[packs][gaming-zone][mirage]"] li[data-product-id="' + productFixture.id + '"]').count(), 1, 'Authenticated product-pack submission must retain the mirage hotspot product');
	await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
	assert.strictEqual(await page.locator('.products-pack-nk a[href="' + productFixture.permalink + '"]').count() > 0, true, 'A saved hotspot must render the selected WooCommerce product permalink');
	assert.strictEqual(await page.locator('.products-pack-nk__image__product.two .circle-wrap.start-0').count(), 1, 'The mirage hotspot circle-wrap must use the start-0 alignment class');
	assert.strictEqual(await page.locator('.products-pack-nk__image__product.two .products-pack-nk__image__product__box.start-0.box-align-start').count(), 1, 'The mirage hotspot popup must use start-0 box-align-start classes');
	assert.strictEqual(await page.locator('.products-pack-nk__image__product.three .products-pack-nk__image__product__box.end-0.box-align-end').count(), 1, 'Other hotspots must preserve end-0 box-align-end classes');
	assert.strictEqual(await page.locator('.products-pack-nk__image__product.four.is-empty').count(), 1, 'Unassigned hotspot with no matching product must render the is-empty state');
	assert.strictEqual(await page.locator('.products-pack-nk__image__product.four a').count(), 0, 'Empty hotspot must not render a purchase link');

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
    await page.locator('#negar-products-enabled-accessories').check();
    await page.locator('#negar-products-count-accessories').fill('1');
    await page.locator('#negar-products-sort-accessories').selectOption('title_asc');
    const selectedFixture = productFixture.outside_first_hundred;
    await page.locator('#negar-products-search-accessories').fill(selectedFixture.label);
    await page.locator(`#negar-products-search-results-accessories button[data-product-id="${selectedFixture.id}"]`).click();
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
      page.locator('button[name="negar_theme_product_section_action"][value="accessories"]').click(),
    ]);
    assert.strictEqual(await page.locator(`[data-product-selector][data-section-id="accessories"] .negar-product-selector__selected li[data-product-id="${selectedFixture.id}"]`).count(), 1, 'Authenticated product-section form submission must persist a searched manual product selection');
    assert.strictEqual(await page.locator('#negar-products-enabled-accessories').isChecked(), true, 'Saving a section must persist its explicit custom-configuration state');

    await page.reload({ waitUntil: 'domcontentloaded' });
    assert.strictEqual(await page.locator(`[data-product-selector][data-section-id="accessories"] .negar-product-selector__selected li[data-product-id="${selectedFixture.id}"]`).count(), 1, 'A selected product must remain visible after reopening even though it was not preloaded into the form');
    await page.locator('#negar-products-count-accessories').locator('xpath=ancestor::details').locator('summary').click();
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
      page.locator('button[name="negar_theme_product_section_action"][value="accessories"]').click(),
    ]);
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(await page.locator(`.buy-accessories-nk a[href="${selectedFixture.permalink}"]`).count() > 0, true, 'Saved product selection must render with the real WooCommerce permalink on desktop');

    mobileContext = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, locale: 'fa-IR' });
    const mobilePage = await mobileContext.newPage();
    await mobilePage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    assert.strictEqual(await mobilePage.locator('#negar-home-hero-carousel').isVisible(), true, 'The reordered homepage must retain its existing hero component on mobile');
    const mobileLayoutHtml = await mobilePage.locator('main.homepage-main').innerHTML();
    assert(mobileLayoutHtml.indexOf('<!-- negar-home-section:product-tabs -->') < mobileLayoutHtml.indexOf('<!-- negar-home-section:hero-slider -->'), 'Saved ordering must also render on mobile');
    assert.strictEqual(await mobilePage.locator(`.buy-accessories-nk a[href="${selectedFixture.permalink}"]`).count() > 0, true, 'Saved product selection must render with the real WooCommerce permalink on mobile');

    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=products', { waitUntil: 'domcontentloaded' });
    await page.locator('#negar-products-count-multi-category-products').locator('xpath=ancestor::details').locator('summary').click();
    await page.locator('#negar-products-enabled-multi-category-products').check();
    await page.locator('#negar-products-count-multi-category-products').fill('2');
    await page.locator('#negar-products-sort-multi-category-products').selectOption('price_desc');
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
      page.locator('button[name="negar_theme_product_section_action"][value="multi-category-products"]').click(),
    ]);
    assert.strictEqual(await page.locator(`[data-product-selector][data-section-id="accessories"] .negar-product-selector__selected li[data-product-id="${selectedFixture.id}"]`).count(), 1, 'Saving a second product section must not erase the first section selection');

    if (productFixture.unrated.id) {
      await page.locator('#negar-products-count-accessories').locator('xpath=ancestor::details').locator('summary').click();
      await page.locator(`[data-product-selector][data-section-id="accessories"] .negar-product-selector__remove`).click();
      await page.locator('#negar-products-search-accessories').fill(productFixture.unrated.label);
      await page.locator(`#negar-products-search-results-accessories button[data-product-id="${productFixture.unrated.id}"]`).click();
      await Promise.all([
        page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
        page.locator('button[name="negar_theme_product_section_action"][value="accessories"]').click(),
      ]);
      await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
      const unratedSlide = page.locator(`.buy-accessories-nk .swiper-slide:has(a[href="${productFixture.unrated.permalink}"])`);
      assert.strictEqual(await unratedSlide.locator('.rate').count(), 0, 'A real product without reviews must not display an invented rating');
    }

    await page.goto(BASE_URL + '/wp-admin/options-general.php?page=negar-theme-settings&tab=products', { waitUntil: 'domcontentloaded' });
    await page.locator('#negar-products-count-accessories').locator('xpath=ancestor::details').locator('summary').click();
    await page.locator('#negar-products-enabled-accessories').uncheck();
    await Promise.all([
      page.waitForURL((url) => url.pathname.endsWith('/wp-admin/options-general.php') && url.searchParams.get('tab') === 'products'),
      page.locator('button[name="negar_theme_product_section_action"][value="accessories"]').click(),
    ]);
    assert.strictEqual(await page.locator('#negar-products-enabled-accessories').isChecked(), false, 'Disabling and saving one section must restore its legacy query state');

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
      if (!failure) {
        assert.strictEqual(php('snapshot'), snapshot, 'WordPress options must be restored identically to pre-test snapshot');
      }
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
