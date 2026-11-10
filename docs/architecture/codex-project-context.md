# NegarThem local project context

## Verified runtime

- WordPress root: `X:\apps\wamp\www\wp4`
- Active theme: `negarThem` (`Negar Shop`)
- Local site: `http://localhost/wp4/`
- PHP: WAMP PHP 8.2.29; WooCommerce is active.
- Theme test commands: `npm run test:lint`, `npm test`, `npm run build:dist`.

## Theme structure

The theme is a classic PHP WordPress/WooCommerce theme with an RTL Persian storefront. `functions.php` establishes theme constants and loads feature modules from `inc/`. `inc/enqueue.php` owns front-end assets and localizes the JavaScript data used by Ajax features.

- `front-page.php` composes the non-Elementor homepage from ordered files in `template-parts/home/`.
- `template-parts/header/main-header.php` renders the desktop header and product search form.
- `template-parts/modals/` contains the wishlist, compare, quick-view, cart, and authentication dialogs.
- `inc/ajax-search.php`, `inc/modals-ajax.php`, and `inc/auth-otp.php` provide the relevant public Ajax endpoints.
- `assets/js/header-footer.js` manages category search and sticky header behavior; `assets/js/modals.js` owns local wishlist/compare interactions and dialog focus handling.
- `assets/css/modals.css` carries explicit modal/drawer state styles.

## Data and ownership rules

WooCommerce is the source of truth for products, categories, prices, stock, and approved product reviews. The homepage review component uses the three most-recent approved WooCommerce reviews and must show a transparent empty state if none exist. The wishlist is a client-side visitor preference stored in the browser; it retrieves display cards from WordPress through the existing authenticated-nonce Ajax endpoint.

## Change and verification conventions

Keep theme changes in this active directory, use `apply_patch` for source changes, retain the direct `main` workflow requested for this project, and test the live local site after UI changes. The codebase-memory graph is useful for broad call-path discovery; this project’s index may be incomplete for template and asset paths, so direct source reads remain necessary when its coverage check reports a gap.
