/**
 * Shared phone OTP interaction for the header modal and My Account page.
 */
(function () {
	'use strict';

	function initOtpInterfaces() {
		const params = window.negarShopParams || {};
		const ajaxUrl = params.ajaxUrl || '/wp-admin/admin-ajax.php';
		const nonce = params.nonce || '';
		const modal = document.getElementById('negar-login-otp-modal');
		const controllers = [];
		let lastTrigger = null;

		function toPersianDigits(value) {
			const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
			return String(value).padStart(2, '0').replace(/[0-9]/g, (digit) => digits[Number(digit)]);
		}

		function normalizeDigits(value) {
			const persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
			const arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
			let normalized = value || '';
			for (let index = 0; index < 10; index += 1) {
				normalized = normalized.replaceAll(persian[index], String(index)).replaceAll(arabic[index], String(index));
			}
			return normalized.replace(/[^\d+]/g, '');
		}

		function notify(message, type) {
			if (typeof window.negarToast === 'function') {
				window.negarToast(message, type || 'info');
			}
		}

		function createController(root) {
			const phoneForm = root.querySelector('[data-otp-step="phone"]');
			const codeForm = root.querySelector('[data-otp-step="code"]');
			const phoneInput = root.querySelector('[data-otp-phone]');
			const codeInput = root.querySelector('[data-otp-code]');
			const target = root.querySelector('[data-otp-target]');
			const resend = root.querySelector('[data-otp-resend]');
			const timerWrap = root.querySelector('[data-otp-timer-wrap]');
			const timer = root.querySelector('[data-otp-timer]');
			const back = root.querySelector('[data-otp-back]');
			let activePhone = '';
			let interval = null;

			function setMessage(step, message) {
				const region = root.querySelector(`[data-otp-message="${step}"]`);
				if (!region) return;
				region.textContent = message || '';
				region.classList.toggle('hidden', !message);
			}

			function setLoading(step, loading) {
				const button = root.querySelector(`[data-otp-submit="${step}"]`);
				if (!button) return;
				button.disabled = loading;
				button.setAttribute('aria-busy', loading ? 'true' : 'false');
				button.querySelector('.negar-otp__spinner')?.classList.toggle('hidden', !loading);
			}

			function stopTimer() {
				if (interval) window.clearInterval(interval);
				interval = null;
			}

			function startTimer(seconds) {
				stopTimer();
				let remaining = Number(seconds) || 60;
				if (timerWrap) timerWrap.classList.remove('hidden');
				if (resend) {
					resend.classList.add('hidden');
					resend.disabled = true;
				}

				function renderTimer() {
					if (timer) {
						const minutes = Math.floor(Math.max(remaining, 0) / 60);
						const secs = Math.max(remaining, 0) % 60;
						timer.textContent = `${toPersianDigits(minutes)}:${toPersianDigits(secs)}`;
					}
					if (remaining <= 0) {
						stopTimer();
						if (timerWrap) timerWrap.classList.add('hidden');
						if (resend) {
							resend.classList.remove('hidden');
							resend.disabled = false;
						}
						return;
					}
					remaining -= 1;
				}

				renderTimer();
				interval = window.setInterval(renderTimer, 1000);
			}

			function showPhoneStep() {
				stopTimer();
				codeForm?.classList.add('hidden');
				phoneForm?.classList.remove('hidden');
				setMessage('code', '');
				window.setTimeout(() => phoneInput?.focus(), 0);
			}

			async function sendOtp(phone) {
				setMessage('phone', '');
				setLoading('phone', true);
				try {
					const formData = new URLSearchParams({ action: 'negar_auth_send_otp', nonce, phone });
					const response = await fetch(ajaxUrl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
						body: formData.toString(),
					});
					const result = await response.json();
					if (!result.success) {
						setMessage('phone', result.data?.message || 'خطا در ارسال کد تأیید.');
						return;
					}
					activePhone = result.data?.phone || phone;
					if (target) target.textContent = activePhone;
					phoneForm?.classList.add('hidden');
					codeForm?.classList.remove('hidden');
					codeInput.value = '';
					startTimer(result.data?.cooldown || 60);
					window.setTimeout(() => codeInput?.focus(), 0);
					notify(result.data?.message || 'کد تأیید ارسال شد.', 'info');
				} catch (error) {
					setMessage('phone', 'ارتباط با سرور برقرار نشد. لطفاً دوباره تلاش کنید.');
				} finally {
					setLoading('phone', false);
				}
			}

			async function verifyOtp(code) {
				if (!code || code.length !== 5) {
					setMessage('code', 'لطفاً کد پنج‌رقمی را کامل وارد کنید.');
					return;
				}
				setMessage('code', '');
				setLoading('code', true);
				try {
					const formData = new URLSearchParams({
						action: 'negar_auth_verify_otp',
						nonce,
						phone: activePhone,
						code,
						redirect_to: root.dataset.redirectTo || window.location.href,
					});
					const response = await fetch(ajaxUrl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
						body: formData.toString(),
					});
					const result = await response.json();
					if (!result.success) {
						setMessage('code', result.data?.message || 'کد واردشده معتبر نیست.');
						return;
					}
					notify(result.data?.message || 'ورود با موفقیت انجام شد.', 'success');
					window.setTimeout(() => {
						window.location.assign(result.data?.redirect_url || root.dataset.redirectTo || window.location.href);
					}, 300);
				} catch (error) {
					setMessage('code', 'خطا در بررسی کد. لطفاً دوباره تلاش کنید.');
				} finally {
					setLoading('code', false);
				}
			}

			phoneForm?.addEventListener('submit', (event) => {
				event.preventDefault();
				sendOtp(normalizeDigits(phoneInput?.value.trim()));
			});
			codeForm?.addEventListener('submit', (event) => {
				event.preventDefault();
				verifyOtp(normalizeDigits(codeInput?.value.trim()));
			});
			back?.addEventListener('click', showPhoneStep);
			resend?.addEventListener('click', () => activePhone && sendOtp(activePhone));

			return {
				reset() {
					stopTimer();
					phoneForm?.reset();
					codeForm?.reset();
					activePhone = '';
					setMessage('phone', '');
					setMessage('code', '');
					showPhoneStep();
				},
				focus() { phoneInput?.focus(); },
			};
		}

		document.querySelectorAll('[data-negar-otp]').forEach((root) => controllers.push({ root, controller: createController(root) }));

		function modalController() {
			return controllers.find((entry) => modal && modal.contains(entry.root))?.controller;
		}

		function closeModal() {
			if (!modal || modal.classList.contains('hidden')) return;
			modal.classList.add('hidden');
			document.body.classList.remove('overflow-hidden');
			lastTrigger?.focus();
		}

		document.addEventListener('click', (event) => {
			const trigger = event.target.closest('.negar-login-trigger');
			if (trigger && modal) {
				event.preventDefault();
				lastTrigger = trigger;
				modal.classList.remove('hidden');
				document.body.classList.add('overflow-hidden');
				const controller = modalController();
				controller?.reset();
				window.setTimeout(() => controller?.focus(), 0);
				return;
			}
			if (modal && event.target === modal) closeModal();
		});

		modal?.querySelector('.negar-modal-close')?.addEventListener('click', closeModal);
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') closeModal();
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initOtpInterfaces);
	} else {
		initOtpInterfaces();
	}
})();
