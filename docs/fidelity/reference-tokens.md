# Reference Design Tokens & Style Spec — Kaveh Demo 43

**Source URL:** `https://kaveh.moeinwp.com/43/`  
**Measurement Source:** Browser Computed Styles (`getComputedStyle()`, `getBoundingClientRect()`)

---

## 1. Typography & Hierarchy

- **Primary Font Family:** `iransansxv`, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif
- **Body Text:**
  - Font Size: `14px`
  - Line Height: `1.6` (~22.4px)
  - Color: `#212341` (`rgb(33, 35, 65)`)
  - Weight: `400`
- **Headings:**
  - H1 / Hero Headings: `32px` - `36px`, Weight `800` / `900`
  - H2 / Section Headings: `20px` - `24px`, Weight `700` (`#212341`)
  - H3 / Card Titles: `15px` - `16px`, Weight `600` (`#212341`), Line Height `1.4`
  - H4 / Subtitles / Meta: `12px` - `13px`, Weight `500` (`#6b7280`)
  - Badges & Timers: `11px` - `12px`, Weight `700`, Color `#ffffff`

---

## 2. Color Palette

- **Primary Brand Red / Crimson:** `#e11d48` / `#ef4444` / `#dc2626` (used on Flash Deals, Discount Badges, Primary CTAs)
- **Secondary Dark Navy / Slate:** `#212341` (used for main headings, top bar text, primary icons)
- **Body Background:** `#ffffff`
- **Muted Section Backgrounds:** `#f8fafc` / `#f1f5f9` / `#f9fafb`
- **Card Background:** `#ffffff`
- **Border / Divider Color:** `#e2e8f0` / `#e5e7eb` (`rgba(0, 0, 0, 0.08)`)
- **Text Muted / Subtitle:** `#64748b` / `#94a3b8`
- **Accent Yellow / Star Rating:** `#eab308` / `#f59e0b`
- **Success Green (Stock status):** `#16a34a` / `#22c55e`

---

## 3. Geometry, Spacing & Container

- **Desktop Container Max Width:** `1320px`
- **Container Padding:** `0 12px` (Mobile: `0 16px`)
- **Card Border Radius:** `16px` (Banners: `20px` - `24px`, Bubbles: `9999px` / `50%`)
- **Card Box Shadow (Default):** `0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05)`
- **Card Box Shadow (Hover):** `0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.06)`
- **Hover Transition:** `all 0.3s cubic-bezier(0.4, 0, 0.2, 1)`
- **Card Hover Transform:** `translateY(-4px)`
- **Section Vertical Gap:** `40px` to `60px` margin between consecutive homepage blocks

---

## 4. Layout Breakpoints

- Desktop Wide: `1440px`
- Desktop Standard: `1280px`
- Laptop / Small Desktop: `1024px`
- Tablet: `768px`
- Mobile Large: `390px` / `414px`
- Mobile Small: `375px`
