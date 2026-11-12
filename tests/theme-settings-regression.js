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
	assert.strictEqual(await page.locator('[data-negar-hero-media="one"] img').getAttribute('src'), imageFixture.url, 'Saved hero media must render after reload');
	assert.strictEqual(await page.locator('[data-negar-hero-media="one"] .detail a.btn').getAttribute('href'), BASE_URL + '/shop/?phase5-media-test=1', 'Saved hero destination must render after reload');
	assert.strictEqual((await page.locator('.banners-nk-60d1115b .title').textContent()).trim(), 'Phase 5 banner title', 'Saved banner editorial copy must render without changing the banner layout');

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
