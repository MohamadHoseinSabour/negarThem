# NegarThem — V3 Remaining Issues & Future Roadmap

## Status Summary
At the conclusion of the V3 frontend stabilization pass:
- **Reported Defects Fixed**: 8 of 8 (100%).
- **Visual Evidence**: Verified via before/after screenshot comparisons across mobile (375px), tablet (768px), and desktop (1024px, 1440px).
- **Automated Tests**: 17 of 17 tests passed (0 failures).
- **Security**: OTP code disclosure completely eliminated from HTTP responses.

---

## 1. Verified Defect Resolution

1. **Header Layout on WooCommerce Pages**:
   - Resolved. Mobile logo maintains strict 32px height and auto-width across Shop Archive and Single Product pages.
2. **Mobile Hero Slider Collapse**:
   - Resolved. Slider renders with 180px+ height, active slide image (133x121px), visible title and CTA, and zero horizontal overflow.
3. **Desktop Crescent Navigation Arrows**:
   - Resolved. Arrows positioned inside the SVG crescent notches (`right: 8px`, `left: 4px`).
4. **Mobile Bottom Navigation Badges**:
   - Resolved. Clean 3-item island with zero count badges.
5. **Mobile Bottom Navigation Actions**:
   - Resolved. Categories and Cart removed from bottom bar; Cart retained cleanly in top header.
6. **Checkout Step 2 Styling & Mobile Input Squashing**:
   - Resolved. Full-width inputs (265px on 375px viewport), styled stepper with Persian numbers, clean Select2 dropdowns, and card-based payment method radios.
7. **About Us & Contact Us Consistency**:
   - Resolved. Generic Bootstrap classes replaced with Kaveh brand design system (badges, editorial heroes, feature cards, and functional AJAX form).
8. **Security Hardening**:
   - Resolved. No OTP credentials leaked in client AJAX payloads.

---

## 2. Recommendations for Next Project (WordPress Admin Customizer Pass)

As established in the Master Prompt, the upcoming project will focus on creating administrative controls in WordPress Admin. The frontend architecture stabilized in V3 was intentionally engineered to accept these controls with zero friction:

1. **Adopt the Centralized Architecture Map**:
   - Use `docs/architecture/future-theme-customization-map.md` as the direct source of truth for all setting keys and control types.
2. **Implement WordPress Customizer / Kirki Settings**:
   - Bind controls to the mapped keys (`negar_opt_logo_desktop`, `negar_opt_hero_autoplay`, etc.).
3. **Live Preview (PostMessage)**:
   - Implement `postMessage` transports for typography, colors, and top announcement text to enable instantaneous visual updates without iframe reloads.
4. **SMS Gateway Plugin Integration**:
   - Connect the `negar_dispatch_otp_sms` filter hook in `inc/auth-otp.php` to a production Iranian SMS provider (e.g., Kavenegar, FarazSMS, IPPanel, Melipayamak).
