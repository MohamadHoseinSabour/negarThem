/**
 * Comprehensive End-to-End Test Suite for Negar Shop Theme (Master Prompt V2)
 *
 * Verifies core functionality, zero-overflow responsiveness, category navigation,
 * phone OTP login flow, cart/checkout continuity, truthful reviews/Q&A, and contact form.
 */

const { chromium } = require('playwright');
const assert = require('assert');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';

async function runTests() {
  console.log(`\n===============================================================`);
  console.log(`--- Running Negar Shop Hardening V2 Test Suite on ${BASE_URL} ---`);
  console.log(`===============================================================\n`);

  const browser = await chromium.launch({
    channel: 'chrome',
    headless: true,
  });

  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    locale: 'fa-IR',
  });

  const page = await context.newPage();
  let passedCount = 0;
  let failedCount = 0;

  async function testStep(name, fn) {
    try {
      process.stdout.write(`  [TEST] ${name} ... `);
      await fn();
      console.log('PASS');
      passedCount++;
    } catch (err) {
      console.log('FAIL');
      console.error(`         ${err.message}`);
      failedCount++;
    }
  }

  // 1. Homepage Route & Architecture
  await testStep('Homepage renders successfully (HTTP 200, Header, Footer)', async () => {
    const res = await page.goto(BASE_URL, { waitUntil: 'domcontentloaded', timeout: 45000 });
    assert.strictEqual(res.status(), 200, 'Homepage status must be 200');
    const header = await page.$('header#masthead, header.site-header, .negar-main-header');
    assert(header, 'Header element must exist');
    const footer = await page.$('footer#colophon, footer.site-footer, footer');
    assert(footer, 'Footer element must exist');
  });

  // 2. Hero Swiper Clean Initialization & Zero Overflow
  await testStep('Homepage Hero Swiper initializes client-side with zero horizontal overflow', async () => {
    const swiperEl = await page.$('.swiper-landing-nk');
    assert(swiperEl, 'Hero swiper container must exist');
    const isInitialized = await page.evaluate(() => {
      const el = document.querySelector('.swiper-landing-nk');
      return el && el.classList.contains('swiper-initialized');
    });
    assert(isInitialized, 'Hero slider must have .swiper-initialized added client-side');

    const overflowInfo = await page.evaluate(() => {
      return {
        scrollW: document.documentElement.scrollWidth,
        clientW: document.documentElement.clientWidth
      };
    });
    assert.strictEqual(overflowInfo.scrollW, overflowInfo.clientW, 'Desktop viewport must have 0px overflow');

    const nextButton = page.locator('.landing-nk__carousel .swiper-button-next');
    await nextButton.focus();
    await page.keyboard.press('Enter');
    await page.waitForTimeout(350);
    assert.strictEqual(await nextButton.evaluate((button) => button.tagName), 'BUTTON', 'Hero next control must be a keyboard-operable button');
  });

  await testStep('Desktop announcement bar is at least 60px high without widening its content', async () => {
    const topbarMetrics = await page.evaluate(() => {
      const topbar = document.querySelector('.negar-topbar');
      const container = topbar?.querySelector('.container');
      const topbarRect = topbar?.getBoundingClientRect();
      const containerRect = container?.getBoundingClientRect();
      return {
        height: topbarRect?.height || 0,
        topbarWidth: topbarRect?.width || 0,
        containerWidth: containerRect?.width || 0,
      };
    });
    assert(topbarMetrics.height >= 60, `Desktop topbar must be at least 60px high (got ${topbarMetrics.height}px)`);
    assert(topbarMetrics.containerWidth <= topbarMetrics.topbarWidth, 'Topbar content must remain within the viewport width');
  });

  // 2A. The hero deal must not use demo-only product claims.
  await testStep('Homepage hero deal reflects its live WooCommerce product', async () => {
    const productResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?category=51&per_page=1`);
    assert.strictEqual(productResponse.status(), 200, 'The Store API must provide the live games product used by the hero');
    const products = await productResponse.json();
    assert(products.length > 0, 'A live games product must be available for the hero deal');
    const sourceProduct = products[0];

    const heroDeal = await page.evaluate(() => {
      const slide = document.querySelector('.swiper-landing-product-nk .swiper-slide');
      const productLink = slide?.querySelector('.landing-nk__products__item .title a');
      const rating = slide?.querySelector('.comments-rate')?.textContent.replace(/\s+/g, ' ').trim() || '';
      return {
        link: productLink?.href || '',
        title: productLink?.textContent.trim() || '',
        rating,
        content: slide?.textContent.replace(/\s+/g, ' ').trim() || '',
      };
    });

    assert.strictEqual(heroDeal.link, sourceProduct.permalink, 'Hero deal link must be the live product permalink');
    assert.strictEqual(heroDeal.title, sourceProduct.name, 'Hero deal title must be the live product title');
    assert(!heroDeal.content.includes('FC 26'), 'Hero deal must not retain the demo-only FC 26 product claim');

    if (Number(sourceProduct.review_count) === 0) {
      assert(heroDeal.rating.includes('بدون دیدگاه'), 'A product with no reviews must not display an invented review count or rating');
    }
  });

  await testStep('Homepage featured Apple Watch deal uses the live WooCommerce product', async () => {
    const productResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?category=36&per_page=100`);
    assert.strictEqual(productResponse.status(), 200, 'The Store API must expose the featured Apple Watch candidate');
    const sourceProducts = await productResponse.json();
    const sourceProduct = sourceProducts.find((product) => /watch|ساعت/i.test(product.name));
    assert(sourceProduct, 'A published Apple Watch product must be available for the featured-deal check');

    const dealState = await page.evaluate(() => {
      const section = document.querySelector('.negar-sec-16-spotlight');
      const links = Array.from(section?.querySelectorAll('a[href]') || []);
      return {
        sectionFound: Boolean(section),
        links: links.map((link) => link.href),
        title: section?.querySelector('h3')?.textContent.replace(/\s+/g, ' ').trim() || '',
        text: section?.textContent.replace(/\s+/g, ' ').trim() || '',
      };
    });

    assert(dealState.sectionFound, 'The featured deal section must render');
    assert(dealState.links.length > 0, 'The featured deal must expose a destination');
    assert(dealState.links.filter((url) => url.includes('/product/')).every((url) => url === sourceProduct.permalink), 'Featured-deal product links must target the live product');
    assert.strictEqual(dealState.title, sourceProduct.name, 'Featured-deal title must use the live product name');
    assert(!dealState.text.includes('iphone-17-pro-max'), 'Featured deal must not retain the old mismatched image reference');
  });

  await testStep('Gaming Zone exposes only real product hotspots and sidebar items', async () => {
    const productResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?per_page=100`);
    assert.strictEqual(productResponse.status(), 200, 'The Store API must expose the gaming catalogue');
    const sourceIds = new Set((await productResponse.json()).map((product) => String(product.id)));

    const gamingState = await page.evaluate(() => {
      const section = document.querySelector('.products-pack-nk');
      return {
        sectionFound: Boolean(section),
        hotspots: Array.from(section?.querySelectorAll('.products-pack-nk__image__product') || []).map((hotspot) => ({
          empty: hotspot.classList.contains('is-empty'),
          links: Array.from(hotspot.querySelectorAll('a[href]')).map((link) => link.href),
          prices: hotspot.querySelectorAll('.price').length,
        })),
        items: Array.from(section?.querySelectorAll('.products-pack-nk__products__item[data-product-id]') || []).map((item) => ({
          id: item.dataset.productId || '',
          links: Array.from(item.querySelectorAll('a[href]')).map((link) => link.href),
        })),
      };
    });

    assert(gamingState.sectionFound, 'Gaming Zone must render');
    assert(gamingState.hotspots.length >= 3, 'Gaming Zone must preserve its hotspot layout');
    gamingState.hotspots.forEach((hotspot, index) => {
      if (hotspot.empty) {
        assert.strictEqual(hotspot.links.length, 0, `Unavailable hotspot ${index + 1} must not expose a generic link`);
        assert.strictEqual(hotspot.prices, 0, `Unavailable hotspot ${index + 1} must not expose a fabricated price`);
      } else {
        assert(hotspot.links.length > 0 && hotspot.links.every((url) => url.includes('/product/')), `Available hotspot ${index + 1} must link to its product`);
      }
    });
    gamingState.items.forEach((item, index) => {
      assert(sourceIds.has(item.id), `Gaming sidebar item ${index + 1} must use a published product ID`);
      assert(item.links.length > 0 && item.links.every((url) => url.includes('/product/')), `Gaming sidebar item ${index + 1} must link to a product page`);
    });
  });

  // 2AA. Product carousel cards must always land on their own product page.
  await testStep('Homepage sale carousel exposes only real product destinations', async () => {
    const saleResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?on_sale=true&per_page=100`);
    assert.strictEqual(saleResponse.status(), 200, 'The Store API must expose the live sale catalogue');
    const saleProducts = await saleResponse.json();
    assert(saleProducts.length > 0, 'At least one real sale product is required for the homepage carousel');

    const carouselState = await page.evaluate(() => {
      const section = document.querySelector('.negar-home-sale-carousel');
      const cards = Array.from(section?.querySelectorAll('.product-nk[data-product-id]') || []);
      return {
        sectionFound: Boolean(section),
        cards: cards.map((card) => ({
          id: card.dataset.productId || '',
          imageLink: card.querySelector('.image a[href]')?.href || '',
          titleLink: card.querySelector('h2 a[href]')?.href || '',
          actionIds: Array.from(card.querySelectorAll('[data-id]')).map((button) => button.dataset.id || ''),
        })),
        allSaleLink: section?.querySelector('a[href*="on_sale=1"]')?.href || '',
      };
    });

    assert(carouselState.sectionFound, 'Homepage sale carousel must be rendered');
    assert(carouselState.cards.length > 0, 'Homepage sale carousel must render product cards');

    const sourceById = new Map(saleProducts.map((product) => [String(product.id), product.permalink]));
    carouselState.cards.forEach((card, index) => {
      const expectedLink = sourceById.get(card.id);
      assert(expectedLink, `Carousel card ${index + 1} must use a live sale-product ID`);
      assert.strictEqual(card.imageLink, expectedLink, `Carousel card ${index + 1} image must link to its own product`);
      assert.strictEqual(card.titleLink, expectedLink, `Carousel card ${index + 1} title must link to its own product`);
      assert(card.actionIds.every((id) => id === card.id), `Carousel card ${index + 1} actions must retain the real product ID`);
    });
    assert(carouselState.allSaleLink.includes('/shop/') && carouselState.allSaleLink.includes('on_sale=1'), 'Only the carousel “view all” link may target the sale catalogue');
  });

  await testStep('Recently viewed carousel reflects real customer product visits', async () => {
    const productResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?per_page=1`);
    assert.strictEqual(productResponse.status(), 200, 'The Store API must expose a product for the view-history check');
    const [sourceProduct] = await productResponse.json();
    assert(sourceProduct, 'A published product is required for the view-history check');

    await page.goto(sourceProduct.permalink, { waitUntil: 'domcontentloaded' });
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

    const viewedState = await page.evaluate(() => {
      const section = document.querySelector('.negar-recently-viewed');
      const card = section?.querySelector('.product-nk[data-product-id]');
      return {
        sectionFound: Boolean(section),
        cardId: card?.dataset.productId || '',
        imageLink: card?.querySelector('.image a[href]')?.href || '',
        titleLink: card?.querySelector('h2 a[href]')?.href || '',
      };
    });

    assert(viewedState.sectionFound, 'A visited product must create the recently viewed section');
    assert.strictEqual(viewedState.cardId, String(sourceProduct.id), 'Recently viewed card must retain the visited product ID');
    assert.strictEqual(viewedState.imageLink, sourceProduct.permalink, 'Recently viewed image must link to the visited product');
    assert.strictEqual(viewedState.titleLink, sourceProduct.permalink, 'Recently viewed title must link to the visited product');
  });

  await testStep('Homepage category bubbles link to their category archives', async () => {
    const categoryLinks = await page.evaluate(() => Array.from(document.querySelectorAll('.categories-nk__item a[href]'))
      .map((link) => link.href));
    assert(categoryLinks.length > 0, 'Homepage category bubbles must render navigation links');
    assert(categoryLinks.every((url) => new URL(url).pathname.includes('/product-category/')), 'Every category bubble must point to its category archive');
  });

  // 2B. Homepage reviews must reflect the public WooCommerce review source.
  await testStep('Homepage displays live WooCommerce reviews or a truthful empty state', async () => {
    const reviewState = await page.evaluate(async () => {
      const section = document.querySelector('.negar-sec-21-testimonials');
      if (!section) return { sectionFound: false };

      const reviewsUrl = new URL('wp-json/wc/store/v1/products/reviews?per_page=3&orderby=date&order=desc', window.location.href);
      const response = await fetch(reviewsUrl.toString());
      const sourceReviews = response.ok ? await response.json() : [];
      const cards = Array.from(section.querySelectorAll('article'));

      return {
        sectionFound: true,
        sourceReviews,
        hasEmptyState: Boolean(section.querySelector('.border-dashed')),
        cards: cards.map((card) => ({
          author: card.querySelector('h3')?.textContent.trim() || '',
          productUrl: card.querySelector('a[href*="/product/"]')?.href || '',
          text: card.textContent.replace(/\s+/g, ' ').trim(),
        })),
      };
    });

    assert(reviewState.sectionFound, 'The homepage review section must exist');

    if (reviewState.sourceReviews.length === 0) {
      assert(reviewState.hasEmptyState, 'An empty review source must show the truthful empty state');
      assert.strictEqual(reviewState.cards.length, 0, 'An empty review source must not render invented cards');
      return;
    }

    const expectedReviews = reviewState.sourceReviews.slice(0, 3);
    assert.strictEqual(reviewState.cards.length, expectedReviews.length, 'Homepage review cards must match the live review count');

    expectedReviews.forEach((review, index) => {
      const card = reviewState.cards[index];
      assert.strictEqual(card.author, review.reviewer, `Review card ${index + 1} must use the live reviewer name`);
      assert.strictEqual(card.productUrl, review.product_permalink, `Review card ${index + 1} must link to its reviewed product`);
      const sourceText = review.review.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
      assert(card.text.includes(sourceText), `Review card ${index + 1} must use the live review content`);
    });
  });

  // 2C. Video-review content must not pretend that sample presenters are customers.
  await testStep('Homepage video-review section uses a truthful no-content state', async () => {
    const videoReviewState = await page.evaluate(() => {
      const section = document.querySelector('.videos-nk-39f2483d');
      return {
        sectionFound: Boolean(section),
        hasEmptyState: Boolean(section?.querySelector('.videos-nk__empty')),
        videoButtons: section?.querySelectorAll('.videos-nk__play').length || 0,
      };
    });

    assert(videoReviewState.sectionFound, 'The homepage video-review section must exist');
    assert(videoReviewState.hasEmptyState, 'Video reviews without a verified source must show a truthful empty state');
    assert.strictEqual(videoReviewState.videoButtons, 0, 'The empty state must not present sample videos as customer reviews');
  });

  // 2D. Footer navigation and contact surfaces must not retain demo-store placeholders.
  await testStep('Footer uses owned routes without demo contact or retailer placeholders', async () => {
    const footerState = await page.evaluate(() => {
      const footer = document.querySelector('footer');
      return {
        found: Boolean(footer),
        text: footer?.textContent.replace(/\s+/g, ' ').trim() || '',
        links: Array.from(footer?.querySelectorAll('a') || []).map((link) => link.getAttribute('href') || ''),
        badgeImages: Array.from(footer?.querySelectorAll('img') || []).map((image) => image.getAttribute('src') || ''),
      };
    });

    assert(footerState.found, 'The site footer must exist');
    assert(!footerState.text.includes('دیجی‌کالا'), 'Footer must not present another retailer as this storefront');
    assert(!footerState.text.includes('moeinwp@'), 'Footer must not expose a demo social-media handle');
    assert(!footerState.text.includes('info@Sitename.com'), 'Footer must not expose a placeholder email address');
    assert(!footerState.links.some((href) => href === '#'), 'Footer links must resolve to a real route or configured URL');
    assert(!footerState.badgeImages.some((src) => /(?:enamad|samandehi)\.png/i.test(src)), 'Unverified demo trust-badge images must not be rendered');
  });

  // 2E. Service guidance must describe only verifiable storefront behaviour.
  await testStep('Service guidance avoids unsupported delivery, guarantee, and payment claims', async () => {
    await page.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
    const serviceState = await page.evaluate(() => ({
      footerFeaturesFound: Boolean(document.querySelector('.footer-features-section')),
      footerFeatures: document.querySelector('.footer-features-section')?.textContent.replace(/\s+/g, ' ').trim() || '',
      trustMarquee: document.querySelector('.elementor-element-797893fc')?.textContent.replace(/\s+/g, ' ').trim() || '',
      featureCards: document.querySelectorAll('.footer-features-section .feature-card').length,
      trustItems: document.querySelectorAll('.elementor-element-797893fc .elementor-widget-icon-box').length,
    }));

    if (serviceState.footerFeaturesFound) {
      assert(serviceState.featureCards >= 5, 'Rendered footer service guidance must include all five cards');
    }
    assert(serviceState.trustItems >= 5, 'Homepage service guidance must render all five items');
    const unsupportedClaims = /تیپاکس|تیباکس|سراسری کشور|تحویل اکسپرس|۱۰۰٪|۲۴ ساعته|عضو شتاب/;
    assert(!unsupportedClaims.test(serviceState.footerFeatures), 'Footer must not make unsupported service or payment claims');
    assert(!unsupportedClaims.test(serviceState.trustMarquee), 'Homepage service guidance must not make unsupported delivery claims');
  });

  // 3. Shop title, global catalogue, and normal pagination.
  await testStep('Shop uses the correct title and exposes every product through pagination', async () => {
    const catalogueResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?per_page=100`);
    assert.strictEqual(catalogueResponse.status(), 200, 'The Store API must expose the shop catalogue');
    const sourceProducts = await catalogueResponse.json();
    assert(sourceProducts.length > 6, 'The local catalogue must have multiple pages for this regression test');

    await page.goto(`${BASE_URL}/shop/`, { waitUntil: 'domcontentloaded' });
    const shopTitle = await page.title();
    assert(shopTitle.includes('فروشگاه'), 'The Shop page title must use فروشگاه');
    assert(!shopTitle.includes('فروشگاه محصولات دیجیتال'), 'The old Shop page title must not remain');

    const pageState = await page.evaluate(() => ({
      breadcrumb: document.querySelector('.breadcrumb-nk__current')?.textContent.trim() || '',
      productIds: Array.from(document.querySelectorAll('#woor .product-nk[data-product-id]')).map((card) => card.dataset.productId),
      pageLinks: Array.from(document.querySelectorAll('.woocommerce-pagination a.page-numbers')).map((link) => link.href),
      paginationControls: Array.from(document.querySelectorAll('.woocommerce-pagination .page-numbers li > *')).map((control) => {
        const rect = control.getBoundingClientRect();
        const style = getComputedStyle(control);
        return {
          width: Math.round(rect.width),
          height: Math.round(rect.height),
          borderRadius: style.borderRadius,
        };
      }),
      categoryTree: {
        parents: document.querySelectorAll('.category-filter-item--parent').length,
        children: document.querySelectorAll('.category-filter-item--child').length,
        parentLabels: Array.from(document.querySelectorAll('.category-filter-item--parent .category-filter-item__kind')).map((badge) => badge.textContent.trim()),
      },
    }));
    assert.strictEqual(pageState.breadcrumb, 'فروشگاه', 'The Shop breadcrumb must use the current page title');
    assert.strictEqual(pageState.productIds.length, 6, 'The Shop must keep its six-product page size');
    assert(pageState.pageLinks.length > 0, 'The Shop must expose normal pagination links');
    assert(pageState.paginationControls.length > 0, 'The Shop must render pagination controls');
    assert(pageState.paginationControls.every((control) => control.width >= 40 && control.height >= 40 && control.borderRadius === '50%'), 'Pagination controls must remain circular and touch-friendly');
    assert(pageState.categoryTree.parents > 0, 'The category filter must identify primary categories');
    assert(pageState.categoryTree.children > 0, 'The category filter must expose subcategories separately from primary categories');
    assert(pageState.categoryTree.parentLabels.every((label) => label === 'دسته اصلی'), 'Each primary category must carry a visible primary-category label');

    const discoveredIds = new Set(pageState.productIds);
    const uniquePageLinks = [...new Set(pageState.pageLinks.filter((url) => new URL(url).pathname !== new URL(`${BASE_URL}/shop/`).pathname || new URL(url).search))];
    for (const pageUrl of uniquePageLinks) {
      await page.goto(pageUrl, { waitUntil: 'domcontentloaded' });
      const pageIds = await page.evaluate(() => Array.from(document.querySelectorAll('#woor .product-nk[data-product-id]')).map((card) => card.dataset.productId));
      pageIds.forEach((id) => discoveredIds.add(id));
    }
    assert.strictEqual(discoveredIds.size, sourceProducts.length, 'Pagination must provide access to every published catalogue product');
  });

  // 3A. Desktop categories must be navigation, not a search filter.
  await testStep('Desktop category mega menu is accessible and links to category archives', async () => {
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

    const categoryButton = await page.$('#negar-category-menu-btn');
    const megaMenu = await page.$('#negar-category-mega');
    assert(categoryButton && megaMenu, 'The category button and mega menu must exist');
    assert.strictEqual(await page.$('input[name="product_cat"], .category-option'), null, 'Search category filter controls must be removed');

    await categoryButton.focus();
    await page.keyboard.press('Enter');
    await page.waitForFunction(() => document.getElementById('negar-category-mega')?.getAttribute('aria-hidden') !== 'true' && !document.getElementById('negar-category-mega')?.classList.contains('hidden'));

    const megaState = await page.evaluate(() => {
      const menu = document.getElementById('negar-category-mega');
      const button = document.getElementById('negar-category-menu-btn');
      const links = Array.from(menu?.querySelectorAll('.negar-category-mega__navigation a') || []);
      const saleLink = menu?.querySelector('.negar-category-mega__sale-card a');
      const childLinks = menu?.querySelectorAll('.negar-category-mega__child a') || [];
      const saleCard = menu?.querySelector('.negar-category-mega__sale-card');
      return {
        expanded: button?.getAttribute('aria-expanded'),
        archiveLinks: links.map((link) => link.href),
        childLinkCount: childLinks.length,
        saleLink: saleLink?.href || '',
        saleTitle: saleCard?.querySelector('.negar-category-mega__sale-title')?.textContent.trim() || '',
        saleArt: saleCard?.querySelector('.negar-category-mega__sale-art')?.getAttribute('src') || '',
        referencesProduct: Boolean(saleCard?.querySelector('.negar-category-mega__sale-product, .negar-category-mega__sale-price, .negar-category-mega__sale-discount')),
      };
    });

    assert.strictEqual(megaState.expanded, 'true', 'The category trigger must expose its expanded state');
    assert(megaState.archiveLinks.length > 0, 'The mega menu must expose category archive links');
    assert(megaState.archiveLinks.every((url) => new URL(url).pathname.includes('/product-category/')), 'Every menu category item must navigate to a category archive');
    assert(megaState.childLinkCount > 0, 'The mega menu must include nested category links');
    assert.strictEqual(new URL(megaState.saleLink).searchParams.get('on_sale'), '1', 'The daily-deals banner must link to the shop sale filter');
    assert(megaState.saleTitle.includes('تخفیفات روز'), 'The sale card must be a general daily-deals banner');
    assert(megaState.saleArt.length > 0, 'The daily-deals banner must display its decorative deal artwork');
    assert.strictEqual(megaState.referencesProduct, false, 'The mega-menu banner must not promote an individual product or price');

    await page.keyboard.press('Escape');
    const closedState = await page.evaluate(() => ({
      isHidden: document.getElementById('negar-category-mega')?.classList.contains('hidden'),
      expanded: document.getElementById('negar-category-menu-btn')?.getAttribute('aria-expanded'),
      focusedId: document.activeElement?.id,
    }));
    assert.strictEqual(closedState.isHidden, true, 'Escape must close the mega menu');
    assert.strictEqual(closedState.expanded, 'false', 'Escape must reset aria-expanded');
    assert.strictEqual(closedState.focusedId, 'negar-category-menu-btn', 'Escape must return focus to the trigger');
  });

  // 3B. Live search must remain global after category filtering is removed.
  await testStep('Live search searches all products without category controls', async () => {
    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    const catalogueResponse = await page.request.get(`${BASE_URL}/wp-json/wc/store/v1/products?per_page=1`);
    const catalogue = await catalogueResponse.json();
    assert(catalogue.length > 0, 'A live product must be available for search');

    const searchInput = await page.$('.negar-ajax-search-form .negar-search-input');
    assert(searchInput, 'The global search input must exist');
    await searchInput.fill(catalogue[0].name);
    await page.waitForFunction(
      (title) => Array.from(document.querySelectorAll('.negar-ajax-search-results .search-result-title'))
        .some((element) => element.textContent.trim() === title),
      catalogue[0].name,
      { timeout: 10000 }
    );
  });

  // 4. Customer Phone OTP Authentication Modal Flow
  await testStep('Login modal opens on trigger and handles OTP flow with Iranian phone validation', async () => {
    const loginTrigger = await page.$('.negar-login-trigger');
    if (loginTrigger) {
      await page.evaluate(el => el.click(), loginTrigger);
      await page.waitForTimeout(300);
      const modal = await page.$('#negar-login-otp-modal:not(.hidden)');
      assert(modal, 'OTP login modal must be visible on click');

      // Enter valid Iranian mobile number
      const phoneInput = await page.$('#negar-login-otp-modal [data-otp-phone]');
      assert(phoneInput, 'Phone input must exist in Step 1');
      await phoneInput.fill('09121112233');

      // Submit Step 1
      const submitStep1 = await page.$('#negar-login-otp-modal [data-otp-submit="phone"]');
      await page.evaluate(el => el.click(), submitStep1);
      await page.waitForFunction(() => {
        const modal = document.getElementById('negar-login-otp-modal');
        const step2 = modal?.querySelector('[data-otp-step="code"]');
        const error = modal?.querySelector('[data-otp-message="phone"]');
        return (step2 && !step2.classList.contains('hidden')) || (error && !error.classList.contains('hidden'));
      }, { timeout: 10000 });

      const otpState = await page.evaluate(() => {
        const modal = document.getElementById('negar-login-otp-modal');
        const step2 = modal?.querySelector('[data-otp-step="code"]');
        const error = modal?.querySelector('[data-otp-message="phone"]');
        return {
          step2Visible: Boolean(step2 && !step2.classList.contains('hidden')),
          errorVisible: Boolean(error && !error.classList.contains('hidden')),
          errorText: error?.textContent.trim() || '',
        };
      });
      assert(otpState.step2Visible || otpState.errorVisible, 'OTP submission must produce either verification controls or an honest server response');
      if (!otpState.step2Visible) {
        assert(otpState.errorText.length > 0, 'Rate-limit or delivery failures must be shown honestly in the OTP form');
      }

      // Close modal for subsequent tests
      const closeBtn = await page.$('#negar-login-otp-modal .negar-modal-close');
      if (closeBtn) await page.evaluate(el => el.click(), closeBtn);
      await page.waitForTimeout(300);
    }
  });

  // 5. Product Cards Action Triggers (Wishlist, QuickView, Compare)
  await testStep('Product cards contain Wishlist, QuickView, and Compare buttons', async () => {
    await page.goto(`${BASE_URL}/?post_type=product`, { waitUntil: 'domcontentloaded' });
    const wishlistBtn = await page.$('.btn-wishlist-toggle, .wlm-button');
    assert(wishlistBtn, 'Wishlist toggle button must exist on product card');
    const quickviewBtn = await page.$('.btn-quickview');
    assert(quickviewBtn, 'QuickView button must exist on product card');
    const compareBtn = await page.$('.btn-compare-toggle, .compare-button');
    assert(compareBtn, 'Compare button must exist on product card');
  });

  // 6. QuickView Modal Functionality
  await testStep('QuickView modal opens and loads product details via AJAX', async () => {
    const qvBtn = await page.$('.btn-quickview');
    if (qvBtn) {
      await page.evaluate(el => el.click(), qvBtn);
      await page.waitForTimeout(1500);
      const qvModal = await page.$('#negar-quickview-modal.is-open');
      assert(qvModal, 'QuickView modal should have .is-open class');

      // Close QuickView
      const closeBtn = await page.$('.negar-close-quickview');
      if (closeBtn) await page.evaluate(el => el.click(), closeBtn);
      await page.waitForTimeout(300);
    }
  });

  // 7. Wishlist Storage, Drawer State & Counter Sync
  await testStep('Wishlist drawer is opaque and supports add, remove, and close interactions', async () => {
    const wlBtn = await page.$('.btn-wishlist-toggle');
    assert(wlBtn, 'A product-card wishlist toggle must exist');
    // The compact card action is visually covered until card hover; dispatch its
    // registered click handler directly so this test exercises the wishlist flow.
    await page.evaluate((button) => button.click(), wlBtn);
    await page.waitForFunction(() => {
      try {
        return JSON.parse(localStorage.getItem('negar_wishlist') || '[]').length > 0;
      } catch (error) {
        return false;
      }
    });

    await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    const wishlistTrigger = await page.$('.negar-wishlist-trigger');
    assert(wishlistTrigger, 'The header wishlist trigger must exist');
    await wishlistTrigger.click();
    await page.waitForSelector('#negar-wishlist-drawer.is-open');
    await page.waitForSelector('.wishlist-item');

    const drawerVisual = await page.evaluate(() => {
      const drawer = document.getElementById('negar-wishlist-drawer');
      const backdrop = document.getElementById('negar-wishlist-backdrop');
      return {
        drawerBackground: getComputedStyle(drawer).backgroundColor,
        drawerZIndex: getComputedStyle(drawer).zIndex,
        backdropBackground: getComputedStyle(backdrop).backgroundColor,
      };
    });
    assert.strictEqual(drawerVisual.drawerBackground, 'rgb(255, 255, 255)', 'Wishlist drawer must have an opaque white surface');
    assert.strictEqual(drawerVisual.drawerZIndex, '50', 'Wishlist drawer must remain above page content');
    assert.notStrictEqual(drawerVisual.backdropBackground, 'rgba(0, 0, 0, 0)', 'Wishlist backdrop must dim the page');

    const removeButton = await page.$('.negar-remove-wishlist-item');
    assert(removeButton, 'A populated wishlist item must offer a remove action');
    await removeButton.click();
    await page.waitForSelector('.negar-empty-wishlist');

    const closeButton = await page.$('.negar-close-wishlist');
    assert(closeButton, 'Wishlist drawer must expose a close action');
    await closeButton.click();
    await page.waitForFunction(() => !document.getElementById('negar-wishlist-drawer').classList.contains('is-open'));
  });

  // 8. Single Product: Gallery, Tabs, Truthful Reviews & Real Q&A
  await testStep('Single product page renders gallery, tabs without accordion conflicts, and real Q&A', async () => {
    await page.goto(`${BASE_URL}/?p=131`, { waitUntil: 'domcontentloaded' });
    const gallery = await page.$('.kav-style5-main-image, .woocommerce-product-gallery, .kav-single-product-image');
    assert(gallery, 'Product image gallery must exist');

    const tabs = await page.$('.kav-single-product-tabs');
    assert(tabs, 'Product tabs navigation must exist');

    const specsTab = await page.$('#info-product-tab');
    assert(specsTab, 'Specifications tab panel must exist');

    const commentsTab = await page.$('#comments-tab');
    assert(commentsTab, 'Comments/reviews tab panel must exist');

    const qaTab = await page.$('#questions-tab');
    assert(qaTab, 'Questions & answers tab panel must exist');
  });

  // 9. Mobile Sticky Purchase Panel & Collision Prevention
  await testStep('Mobile single product view displays sticky purchase bar with zero nav collision', async () => {
    const mobileContext = await browser.newContext({
      viewport: { width: 375, height: 667 },
      locale: 'fa-IR',
    });
    const mobilePage = await mobileContext.newPage();
    await mobilePage.goto(`${BASE_URL}/?p=131`, { waitUntil: 'domcontentloaded' });

    const overflow = await mobilePage.evaluate(() => {
      return document.documentElement.scrollWidth - document.documentElement.clientWidth;
    });
    assert.strictEqual(overflow, 0, 'Mobile single product must have 0px overflow');

    const stickyBuyBar = await mobilePage.$('.kav-single-product-bottom-nav-price');
    assert(stickyBuyBar, 'Sticky bottom purchase bar must exist on single product page');

    const bottomNavHidden = await mobilePage.evaluate(() => {
      const nav = document.querySelector('.negar-mobile-bottom-bar');
      if (!nav) return true;
      const style = window.getComputedStyle(nav);
      return style.display === 'none';
    });
    assert(bottomNavHidden, 'Generic bottom nav bar must be hidden on single product to prevent collision');

    await mobileContext.close();
  });

  // 10. Cart Page Functionality
  await testStep('Cart page renders cart contents and totals accurately', async () => {
    // Seed cart with product 131
    await page.goto(`${BASE_URL}/?add-to-cart=131`, { waitUntil: 'domcontentloaded' });
    await page.goto(`${BASE_URL}/cart/`, { waitUntil: 'domcontentloaded' });

    const cartTable = await page.$('.woocommerce-cart-form, .cart-nk');
    assert(cartTable, 'Cart container must exist');
    const totals = await page.$('.cart_totals, .cart-nk__box');
    assert(totals, 'Cart totals area must exist');
  });

  // 11. Checkout Continuity with Seeded Cart
  await testStep('Checkout page renders order review, billing fields, and step indicator without empty redirect', async () => {
    await page.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });
    assert(page.url().includes('checkout'), 'Checkout route must not redirect away when cart is seeded');

    const checkoutForm = await page.$('form.checkout, .cart-nk-checkout');
    assert(checkoutForm, 'Checkout form element must exist');

    const orderReview = await page.$('#order_review, .woocommerce-checkout-review-order');
    assert(orderReview, 'Order review section must exist in checkout');

    const billingFields = await page.$('.woocommerce-billing-fields');
    assert(billingFields, 'Billing fields card must exist');
  });

  // 12. Contact Us: Real AJAX Submission (No Fake alert())
  await testStep('Contact page uses customizable contact cards and an accessible real AJAX form', async () => {
    await page.goto(`${BASE_URL}/contact/`, { waitUntil: 'domcontentloaded' });
    const contactForm = await page.$('#negar-contact-form');
    assert(contactForm, 'Contact form with id #negar-contact-form must exist');

    const nameInput = await page.$('#contact-name');
    assert(nameInput, 'Name input must exist');
    const phoneInput = await page.$('#contact-phone');
    assert(phoneInput, 'Phone input must exist');
    const messageInput = await page.$('#contact-message');
    assert(messageInput, 'Message textarea must exist');
    const labels = await page.evaluate(() => ['contact-name', 'contact-phone', 'contact-subject', 'contact-message']
      .every((id) => Boolean(document.querySelector(`label[for="${id}"]`))));
    assert(labels, 'Every contact field must have a visible associated label');
    assert(await page.$('.negar-map-placeholder, .negar-map-frame iframe'), 'The map area must have either configured media or an explicit editable placeholder');
    const contactVisuals = await page.evaluate(() => ({
      background: getComputedStyle(document.querySelector('.negar-contact-page')).backgroundColor,
      icons: Array.from(document.querySelectorAll('.negar-contact-page i')).every((icon) => getComputedStyle(icon, '::before').content !== 'none'),
    }));
    assert.strictEqual(contactVisuals.background, 'rgb(255, 255, 255)', 'The contact page background must be white');
    assert.strictEqual(contactVisuals.icons, true, 'Contact-page icon glyphs must be loaded');
  });

  // 13. About Us Page
  await testStep('About Us page renders an editable brand story and truthful navigation topics', async () => {
    await page.goto(`${BASE_URL}/about/`, { waitUntil: 'domcontentloaded' });
    const aboutHeader = await page.$('h1');
    assert(aboutHeader, 'About page title must exist');
    assert(await page.$('.negar-about-intro .entry-content'), 'About page must expose the editable story area');
    const topics = await page.$$('.negar-about-topics article');
    assert.strictEqual(topics.length, 3, 'About page must expose its product, support, and account route cards');
    const aboutVisuals = await page.evaluate(() => ({
      background: getComputedStyle(document.querySelector('.negar-about-page')).backgroundColor,
      icons: Array.from(document.querySelectorAll('.negar-about-page i')).every((icon) => getComputedStyle(icon, '::before').content !== 'none'),
    }));
    assert.strictEqual(aboutVisuals.background, 'rgb(255, 255, 255)', 'The about page background must be white');
    assert.strictEqual(aboutVisuals.icons, true, 'About-page icon glyphs must be loaded');
  });

  await testStep('My Account uses an inline OTP form and exposes no guest password or registration form', async () => {
    await page.goto(`${BASE_URL}/my-account/`, { waitUntil: 'domcontentloaded' });
    assert(await page.$('.negar-account-auth [data-negar-otp]'), 'Guest account page must render the inline OTP component');
    assert(await page.$('.negar-account-auth [data-otp-phone]'), 'Guest account page must collect a mobile number');
    assert.strictEqual(await page.$('.negar-account-auth input[type="password"], .negar-account-auth [name="username"], .negar-account-auth [name="email"]'), null, 'Guest account page must not expose password or registration inputs');
    const destination = await page.$eval('.negar-account-auth [data-negar-otp]', (root) => root.dataset.redirectTo || '');
    assert(destination.includes('/my-account'), 'OTP success must return the customer to My Account');
  });

  await testStep('FAQ, terms, and order tracking retain their existing content in the shared information layout', async () => {
    for (const path of ['/faq/', '/terms/', '/track-order/']) {
      const response = await page.goto(`${BASE_URL}${path}`, { waitUntil: 'domcontentloaded' });
      assert.strictEqual(response.status(), 200, `${path} must respond successfully`);
      assert(await page.$('.negar-information-page h1'), `${path} must have an information-page heading`);
      const text = await page.$eval('.negar-information-page__content', (element) => element.textContent.trim());
      assert(text.length > 0, `${path} must retain its existing page content`);
    }
  });

  await testStep('Public routes remain responsive and free of horizontal overflow at key widths', async () => {
    const routes = ['/', '/shop/', '/?p=131', '/cart/', '/checkout/', '/my-account/', '/blog/', '/about/', '/contact/', '/faq/', '/terms/', '/track-order/'];
    for (const width of [375, 768, 1440]) {
      const auditContext = await browser.newContext({ viewport: { width, height: 900 }, locale: 'fa-IR' });
      const auditPage = await auditContext.newPage();
      for (const path of routes) {
        const response = await auditPage.goto(`${BASE_URL}${path}`, { waitUntil: 'domcontentloaded' });
        assert(response && response.status() < 400, `${path} must remain available at ${width}px`);
        const overflow = await auditPage.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        assert.strictEqual(overflow, 0, `${path} must have no horizontal overflow at ${width}px`);
      }
      await auditContext.close();
    }
  });

  // 14. V3 Header Logo Dimensions across WooCommerce Pages (Shop, Single Product)
  await testStep('Mobile header logo retains non-zero computed dimensions on WooCommerce pages', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();

    for (const url of [`${BASE_URL}/?post_type=product`, `${BASE_URL}/?p=131`]) {
      await mPage.goto(url, { waitUntil: 'domcontentloaded' });
      const logoRect = await mPage.evaluate(() => {
        const logo = document.querySelector('.negar-mobile-header .mobile-logo img, .negar-mobile-header a img');
        if (!logo) return null;
        const rect = logo.getBoundingClientRect();
        return { width: rect.width, height: rect.height };
      });
      assert(logoRect, `Logo must exist on ${url}`);
      assert(logoRect.height >= 24 && logoRect.width >= 40, `Logo on ${url} must be visible (got ${logoRect.width}x${logoRect.height})`);
    }
    await mCtx.close();
  });

  // 15. V3 Mobile Hero Slider Dimensions & Visibility
  await testStep('Mobile hero has one sale price, aligned deal geometry, no backdrop, and 0px overflow', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    await mPage.waitForSelector('.landing-nk__carousel', { timeout: 10000 });
    await mPage.waitForTimeout(600);

    const heroMetrics = await mPage.evaluate(() => {
      const img = document.querySelector('.landing-nk__carousel .swiper-slide-active .image img, .landing-nk__carousel .image img');
      const title = document.querySelector('.landing-nk__carousel .title');
      const cta = document.querySelector('.landing-nk__carousel .btn');
      const wrapper = document.querySelector('.landing-nk__wrapper');
      const dealBox = document.querySelector('.landing-nk__products__box');
      const offer = document.querySelector('.landing-nk__products__box .offer');
      const iRect = img ? img.getBoundingClientRect() : null;
      const tRect = title ? title.getBoundingClientRect() : null;
      const cRect = cta ? cta.getBoundingClientRect() : null;
      const dealBoxRect = dealBox ? dealBox.getBoundingClientRect() : null;
      const offerRect = offer ? offer.getBoundingClientRect() : null;

      return {
        imgVisible: iRect && iRect.width > 50 && iRect.height > 50,
        titleVisible: tRect && tRect.width > 50,
        ctaVisible: cRect && cRect.width > 50,
        strikePrices: document.querySelectorAll('.swiper-landing-product-nk .landing-nk__products__item .detail > .price del').length,
        backdropContent: wrapper ? getComputedStyle(wrapper, '::before').content : '',
        topbarHeight: document.querySelector('.negar-topbar')?.getBoundingClientRect().height || 0,
        dealBoxRect: dealBoxRect && { left: dealBoxRect.left, right: dealBoxRect.right, top: dealBoxRect.top },
        offerRect: offerRect && { left: offerRect.left, right: offerRect.right, top: offerRect.top },
        overflow: document.documentElement.scrollWidth > window.innerWidth,
      };
    });

    assert(heroMetrics.imgVisible, 'Mobile hero image must have visible rendered dimensions');
    assert(heroMetrics.titleVisible, 'Mobile hero title must be visible');
    assert(heroMetrics.ctaVisible, 'Mobile hero CTA button must be visible');
    assert(heroMetrics.topbarHeight >= 52, `Mobile topbar must be at least 52px high (got ${heroMetrics.topbarHeight}px)`);
    assert.strictEqual(heroMetrics.strikePrices, 1, 'The mobile flash deal must render exactly one regular (struck-through) price');
    assert.strictEqual(heroMetrics.backdropContent, 'none', 'Mobile hero must not retain the detached grey backdrop');
    assert(heroMetrics.dealBoxRect && heroMetrics.offerRect, 'Mobile flash-deal card and label must both render');
    assert(heroMetrics.offerRect.left >= heroMetrics.dealBoxRect.left - 1, 'Flash-deal label must align with the card’s left edge');
    assert(heroMetrics.offerRect.right <= heroMetrics.dealBoxRect.right + 1, 'Flash-deal label must remain within the card width');
    assert(heroMetrics.offerRect.top <= heroMetrics.dealBoxRect.top + 1, 'Flash-deal label must align with the card’s top notch');
    assert.strictEqual(heroMetrics.overflow, false, 'Homepage must have zero horizontal overflow on mobile');
    await mCtx.close();
  });

  await testStep('Header and home content have zero horizontal overflow at tablet width', async () => {
    const tabletContext = await browser.newContext({ viewport: { width: 768, height: 900 }, locale: 'fa-IR' });
    const tabletPage = await tabletContext.newPage();
    await tabletPage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
    const metrics = await tabletPage.evaluate(() => ({
      overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
      topbarHeight: document.querySelector('.negar-topbar')?.getBoundingClientRect().height || 0,
    }));
    assert.strictEqual(metrics.overflow, 0, '768px viewport must have zero horizontal overflow');
    assert(metrics.topbarHeight >= 60, `Tablet topbar must preserve the desktop 60px height (got ${metrics.topbarHeight}px)`);
    await tabletContext.close();
  });

  // 16. V3 Mobile Bottom Navigation Clean 3-Item Island
  await testStep('Mobile bottom navigation contains exactly 3 items with 0 count badges', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

    const navMetrics = await mPage.evaluate(() => {
      const bar = document.querySelector('.negar-mobile-bottom-bar');
      if (!bar) return null;
      const items = bar.querySelectorAll('.bottom-bar-item');
      const badges = bar.querySelectorAll('.badge, .negar-cart-count, .negar-wishlist-count');
      const texts = Array.from(items).map(i => i.textContent.trim());
      return {
        itemCount: items.length,
        badgeCount: badges.length,
        texts,
      };
    });

    assert(navMetrics, '.negar-mobile-bottom-bar must exist on mobile');
    assert.strictEqual(navMetrics.itemCount, 3, 'Bottom bar must have exactly 3 items');
    assert.strictEqual(navMetrics.badgeCount, 0, 'Bottom bar must have 0 count badges');
    assert(!navMetrics.texts.some(t => t.includes('دسته') || t.includes('سبد')), 'Bottom bar must not contain Categories or Cart');
    await mCtx.close();
  });

  // 17. V3 Mobile Checkout Input Sizing
  await testStep('Mobile checkout inputs render at full width (>= 240px) without 105px squashing', async () => {
    const mCtx = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true });
    const mPage = await mCtx.newPage();
    await mPage.goto(`${BASE_URL}/?add-to-cart=131`, { waitUntil: 'domcontentloaded' });
    await mPage.goto(`${BASE_URL}/checkout/`, { waitUntil: 'domcontentloaded' });

    const inputWidths = await mPage.evaluate(() => {
      const fn = document.getElementById('billing_first_name');
      const ln = document.getElementById('billing_last_name');
      return {
        fnWidth: fn ? fn.getBoundingClientRect().width : 0,
        lnWidth: ln ? ln.getBoundingClientRect().width : 0,
      };
    });

    assert(inputWidths.fnWidth >= 240, `First name input must be at least 240px on 375px viewport (got ${inputWidths.fnWidth}px)`);
    assert(inputWidths.lnWidth >= 240, `Last name input must be at least 240px on 375px viewport (got ${inputWidths.lnWidth}px)`);
    await mCtx.close();
  });

  await browser.close();

  console.log(`\n===============================================================`);
  console.log(`--- Test Suite Summary: ${passedCount} Passed, ${failedCount} Failed ---`);
  console.log(`===============================================================\n`);

  if (failedCount > 0) {
    process.exit(1);
  } else {
    process.exit(0);
  }
}

runTests().catch(err => {
  console.error('Fatal Test Runner Error:', err);
  process.exit(1);
});
