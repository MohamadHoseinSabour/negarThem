# Codex execution progress — V4 local audit

Last updated: 2026-11-10 (Asia/Tehran)

## Completed in this repair group

| Area | Result | Evidence |
| --- | --- | --- |
| Local onboarding | Confirmed the active `negarThem` theme, WAMP PHP 8.2.29, WooCommerce, and `http://localhost/wp4/`. | WP-CLI and HTTP checks |
| Baseline | Initial suite passed: 109 PHP files linted and 17 E2E checks passed. | `npm test` |
| Review integrity | Replaced fabricated homepage customer stories with recent approved WooCommerce product reviews; the no-review path is explicit and truthful. | Store API compared to rendered cards at desktop and mobile |
| Video-review integrity | Removed repeated sample presenter, avatar, and video content that was not connected to a verified WordPress/WooCommerce source. The component now communicates that no approved videos are available. | Homepage DOM regression check |
| Hero deal integrity | Replaced demo-only product name, image, price, discount, rating, review count, and link with a live WooCommerce product selected from the games category (with a safe product fallback). | Live Store API compared to the rendered hero |
| Wishlist | Reproduced the transparent drawer in the live browser, added an explicit opaque drawer surface and dimming backdrop, and cache-busted the changed asset. | Live computed style: drawer `rgb(255, 255, 255)`, backdrop `rgba(15, 23, 42, 0.6)` |
| Header category search | Confirmed category selection updates the form field, filters live results, and now retains `product_cat` in the “view all” URL. | Local catalogue browser check |

## Current automated checks

- `npm test` passed: 109 PHP files linted and 21 E2E checks passed.
- `node tests/security-otp-disclosure.test.js` passed: the public OTP response exposed only the safe message, cooldown, and phone fields; no code or mock code was returned.
- `npm run build:dist` passed and generated `dist/negar-shop.zip` (327 entries; no `node_modules`, `.git`, or `scratch` files).

This repair group adds regressions for truthful text/video review states, category-filtered search with URL preservation, and opaque wishlist add/remove/close behavior.

## Pending audit scope

- Complete the remaining site-wide inventory of homepage promotions, marketing claims, external media, and dynamic-data ownership.
- Review hero, carousel, navigation, cart/checkout, and account interactions at the agreed desktop and mobile widths.
- Perform the reference/diff review only with a confirmed reference source and record only verified fidelity findings.
- Run the focused OTP security suite, full final test/build, capture final evidence, package the theme, then commit and push the finished audit groups.

This is a factual progress record, not a claim that the full V4 audit is complete.
