# Master Audit V2 — Remaining Issues & Production Readiness Guide

**Project:** Negar Theming & E-Commerce Platform (Negar Shop)  
**Repository:** `https://github.com/MohamadHoseinSabour/negarThem`  
**Working Branch:** `fix/ui-ux-functional-hardening-v2`  
**Audit Date:** 2026-11-08  
**Status:** **Theme Core Architecture 100% Self-Contained & Production-Hardened**

---

## 1. Executive Summary

All local theme architecture, WooCommerce template overrides, frontend JavaScript controllers, responsive layouts (320px–1440px), authentication flows, and micro-interactions have been audited, repaired, and verified with automated test suites.

There are no blocking syntax errors, broken template hierarchies, or layout overflows remaining within the theme codebase.

This document transparently lists external infrastructure dependencies, third-party provider configurations, and deployment prerequisites necessary for live production launch.

---

## 2. External Third-Party Integrations & Configurations

### 2.1 SMS Gateway for Live Phone OTP Authentication
- **Current Architecture:**
  - Implemented in `inc/auth-otp.php`.
  - Normalizes Iranian mobile phone numbers (`09xxxxxxxxx`).
  - Generates secure 5-digit OTP codes with 120s TTL and IP/phone rate-limiting.
  - In local development (`WP_DEBUG` or `localhost`), the system runs in safe development simulation mode: the test code is returned in the AJAX JSON response (`test_code: "12345"`).
- **Production Requirement:**
  - Connect the OTP handler to an active Iranian SMS gateway (e.g., **Kavenegar**, **IPPanel / FarazSMS**, **Melipayamak**, or **Ghasedak**).
  - A clean WordPress filter hook is provided in `inc/auth-otp.php`:
    ```php
    // In child theme or site-specific plugin:
    add_filter('negar_send_sms_otp', function($sent, $mobile, $otp) {
        // Call your SMS provider API (e.g., Kavenegar Pattern/Lookup API)
        // $response = my_kavenegar_lookup($mobile, $otp);
        // return $response->status === 200;
        return true;
    }, 10, 3);
    ```

### 2.2 Payment Gateway Configuration
- **Current Architecture:**
  - Fully compatible with standard WooCommerce payment gateway extensions.
  - Checkout templates (`woocommerce/checkout/form-checkout.php`) seamlessly render available gateways.
- **Production Requirement:**
  - Install and activate the production payment gateway plugin for Iranian banking systems (e.g., **Zarinpal**, **NextPay**, **Vandar**, or direct bank gateways like **Mellat / Behpardakht**, **Saman / SEP**).
  - Configure Merchant ID and terminal credentials in `WooCommerce -> Settings -> Payments`.

### 2.3 Outbound Email / SMTP Configuration
- **Current Architecture:**
  - Native AJAX contact form (`inc/contact-form.php`) stores all inquiries in the WordPress database as custom post type `negar_inquiry` with complete meta fields (sender name, email, subject, message, IP, timestamp).
- **Production Requirement:**
  - If instant email notification to the site administrator is desired upon contact form submission, install and configure a reliable SMTP plugin (e.g., **WP Mail SMTP** or **FluentSMTP**) to ensure delivery without PHP `mail()` spam flagging.

### 2.4 Map Provider on Contact Page
- **Current Architecture:**
  - `page-templates/page-contact.php` renders an accessible, responsive OpenStreetMap embed with fallback styling.
- **Production Recommendation:**
  - For localized Iranian street-level maps, site administrators can replace the embed URL with a **Neshan** (`neshan.org`) or **Balad** widget iframe if desired.

---

## 3. Maintenance & Safe Deployment Checklist

1. **Theme Installation:**
   - Upload `dist/negar-shop.zip` via `WordPress Admin -> Appearance -> Themes -> Add New -> Upload Theme`.
2. **WooCommerce Pages Setup:**
   - Verify standard WooCommerce pages (Shop, Cart, Checkout, My Account) are assigned in `WooCommerce -> Settings -> Advanced`.
3. **Permalinks:**
   - Save permalinks under `Settings -> Permalinks` (`/%postname%/`) to ensure clean REST API and AJAX routing.
4. **Cache & Minification Compatibility:**
   - The theme's assets are cleanly enqueued with file versioning.
   - If using **LiteSpeed Cache** or **WP Rocket**, ensure `assets/js/vendor/kaveh-ajax-cart.js` and `assets/js/modals.js` are excluded from deferral that might delay initial DOMContentLoaded binding.
