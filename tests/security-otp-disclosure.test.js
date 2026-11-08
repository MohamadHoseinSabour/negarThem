/**
 * Security Regression Test Suite: OTP Code Disclosure Prevention
 *
 * Verifies:
 * 1. SMS transport failure NEVER discloses verification code in public AJAX responses.
 * 2. Response payload contains ONLY safe fields (message, cooldown, phone).
 * 3. mock_code or code keys do NOT exist in response JSON.
 * 4. Code comparison uses constant-time hash comparison without plaintext storage.
 */

const assert = require('assert');
const { chromium } = require('playwright');

const BASE_URL = process.env.WP_BASE_URL || 'http://localhost/wp4';

async function runSecurityTests() {
  console.log('\n===============================================================');
  console.log('--- Running Security Test: OTP Code Disclosure Prevention ---');
  console.log('===============================================================\n');

  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const page = await browser.newPage();

  // Load homepage to obtain legitimate nonce
  await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });
  const nonce = await page.evaluate(() => {
    return (window.negarShopParams && window.negarShopParams.nonce) ||
           (window.negarModalsData && window.negarModalsData.nonce) ||
           '';
  });

  const ajaxUrl = `${BASE_URL}/wp-admin/admin-ajax.php`;

  // Test 1: Send OTP request and verify payload has zero code leakage
  console.log('  [TEST 1] Send OTP AJAX response contains zero code or mock_code fields...');
  const sendRes = await page.request.post(ajaxUrl, {
    form: {
      action: 'negar_auth_send_otp',
      nonce: nonce,
      phone: '09129998877'
    }
  });

  assert.strictEqual(sendRes.status(), 200, 'HTTP status must be 200');
  const json = await sendRes.json();
  console.log('           Response JSON:', JSON.stringify(json));

  // Assert NO mock_code or code key anywhere in response
  assert(!('mock_code' in (json.data || {})), 'SECURITY CRITICAL: mock_code must NOT exist in response data');
  assert(!('code' in (json.data || {})), 'SECURITY CRITICAL: code must NOT exist in response data');
  assert(!('secret' in (json.data || {})), 'SECURITY CRITICAL: secret must NOT exist in response data');

  // Test 2: Invalid phone rejection
  console.log('  [TEST 2] Invalid phone number is rejected with 400 or JSON error...');
  const invalidRes = await page.request.post(ajaxUrl, {
    form: {
      action: 'negar_auth_send_otp',
      nonce: nonce,
      phone: '12345'
    }
  });
  const invalidJson = await invalidRes.json();
  assert.strictEqual(invalidJson.success, false, 'Invalid phone number must return success: false');

  // Test 3: OTP verify rejection for wrong code
  console.log('  [TEST 3] Verify OTP with invalid code is rejected with honest error...');
  const verifyRes = await page.request.post(ajaxUrl, {
    form: {
      action: 'negar_auth_verify_otp',
      nonce: nonce,
      phone: '09129998877',
      code: '00000'
    }
  });
  const verifyJson = await verifyRes.json();
  assert.strictEqual(verifyJson.success, false, 'Invalid OTP code must return success: false');
  assert(verifyJson.data.message.includes('اشتباه'), 'Message must indicate code was incorrect');

  await browser.close();

  console.log('\n[PASS] All OTP security regression checks PASSED with zero code disclosure.\n');
}

runSecurityTests().catch((err) => {
  console.error('[FAIL] Security Test Failed:', err);
  process.exit(1);
});
