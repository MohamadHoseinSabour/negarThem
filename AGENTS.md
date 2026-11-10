# NegarThem working guide

- Work in the active local theme directory; do not create duplicate themes, WordPress installs, or demo databases.
- Preserve the Persian RTL storefront, WooCommerce compatibility, public routes, and accessible keyboard interactions.
- Use live WordPress and WooCommerce data for products, reviews, prices, inventory, and transactional states. Never invent customers, reviews, purchases, stock, orders, OTPs, or success states.
- Keep external media origins and assets in place unless a deliberate, verified replacement is requested.
- Validate PHP changes with `npm run test:lint` and user-visible changes with `npm test` against the local WAMP site.
- For structural codebase exploration, use the installed `codebase-memory` skill before direct fallback reads.
- Avoid destructive database, WordPress core, configuration, or Git history operations. Do not reset or discard unrelated work.
