/**
 * Negar Customer Club Public Landing Page & Calculator Controller.
 *
 * Handles live installment estimation with client-server parity,
 * Persian/Arabic digit normalization, FAQ accordion keyboard navigation,
 * and session-intent preservation.
 *
 * @package NegarThem
 * @version 1.0.0
 */

(function () {
	'use strict';

	// Digit conversion utilities
	var PERSIAN_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
	var ARABIC_DIGITS  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

	function normalizeDigits(str) {
		if (str === null || str === undefined) return '';
		var s = String(str);
		for (var i = 0; i < 10; i++) {
			s = s.replace(new RegExp(PERSIAN_DIGITS[i], 'g'), String(i));
			s = s.replace(new RegExp(ARABIC_DIGITS[i], 'g'), String(i));
		}
		return s.replace(/[^\d]/g, '');
	}

	function formatPersianNumber(num) {
		var n = parseInt(num, 10);
		if (isNaN(n)) return '۰';
		var parts = String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		var persianFormatted = '';
		for (var i = 0; i < parts.length; i++) {
			var ch = parts[i];
			if (ch >= '0' && ch <= '9') {
				persianFormatted += PERSIAN_DIGITS[parseInt(ch, 10)];
			} else {
				persianFormatted += ch;
			}
		}
		return persianFormatted;
	}

	function formatIntCommas(num) {
		var n = parseInt(num, 10);
		if (isNaN(n)) return '0';
		return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

	// Debounce helper
	function debounce(func, wait) {
		var timeout;
		return function () {
			var context = this;
			var args = arguments;
			clearTimeout(timeout);
			timeout = setTimeout(function () {
				func.apply(context, args);
			}, wait);
		};
	}

	// 1. Calculator Controller
	function initCalculator() {
		var calcSection = document.getElementById('club-calculator');
		if (!calcSection) return;

		var configRaw = calcSection.getAttribute('data-calculator-config');
		var restUrl   = calcSection.getAttribute('data-rest-url');
		var plansConfig = {};
		try {
			plansConfig = JSON.parse(configRaw) || {};
		} catch (e) {
			return;
		}

		var amountInput     = document.getElementById('negar-calc-amount-input');
		var amountSlider    = document.getElementById('negar-calc-slider');
		var sliderMinSpan   = document.getElementById('negar-calc-slider-min');
		var sliderCeilSpan  = document.getElementById('negar-calc-slider-ceiling');
		var noticeBox       = document.getElementById('negar-calc-validation-notice');

		var resMonthlyAmount  = document.getElementById('res-monthly-amount');
		var resPurchaseAmount = document.getElementById('res-purchase-amount');
		var resInitialPayment = document.getElementById('res-initial-payment');
		var resExcessRow      = document.getElementById('res-excess-row');
		var resExcessAmount   = document.getElementById('res-excess-amount');
		var resTotalUpfront   = document.getElementById('res-total-upfront');
		var resFinanced       = document.getElementById('res-financed-principal');
		var resInstallments   = document.getElementById('res-installments-count');
		var resScheduleList   = document.getElementById('negar-calc-schedule-list');
		var scheduleToggle    = document.querySelector('.negar-club-calc__schedule-toggle');

		var planTabs = calcSection.querySelectorAll('.negar-club-calc__tab');
		var currentPlanId = 'negar_plus';

		// Set initial active plan
		for (var i = 0; i < planTabs.length; i++) {
			if (planTabs[i].classList.contains('is-active')) {
				currentPlanId = planTabs[i].getAttribute('data-plan-id');
				break;
			}
		}

		function getActivePlan() {
			return plansConfig[currentPlanId] || null;
		}

		function updateSliderLimits() {
			var plan = getActivePlan();
			if (!plan) return;
			var minVal = plan.initial_payment || 1000000;
			var ceilVal = plan.ceiling || 10000000;
			var maxVal = Math.round(ceilVal * 1.5);

			if (amountSlider) {
				amountSlider.min = minVal;
				amountSlider.max = maxVal;
				amountSlider.step = 500000;
			}
			if (sliderMinSpan) {
				sliderMinSpan.textContent = formatPersianNumber(minVal) + ' تومان';
			}
			if (sliderCeilSpan) {
				sliderCeilSpan.textContent = 'سقف طرح: ' + formatPersianNumber(ceilVal);
			}
		}

		// Client Calculation (Parity with Negar_Club_Quote_Calculator)
		function computeLocalQuote(amount, plan) {
			if (!plan) return null;
			var initialPayment = plan.initial_payment || 0;
			var ceiling = plan.ceiling || 0;
			var duration = Math.max(1, plan.duration_months || 1);

			if (amount < initialPayment || amount <= 0) {
				return {
					valid: false,
					code: 'AMOUNT_BELOW_MINIMUM',
					message: 'مبلغ خرید نمی‌تواند از پیش‌پرداخت طرح (' + formatPersianNumber(initialPayment) + ' تومان) کمتر باشد.'
				};
			}

			var additionalUpfront = 0;
			var totalUpfront = initialPayment;
			var financedPrincipal = 0;
			var notice = '';

			if (amount <= ceiling) {
				additionalUpfront = 0;
				totalUpfront = initialPayment;
				financedPrincipal = amount - initialPayment;
			} else {
				additionalUpfront = amount - ceiling;
				totalUpfront = initialPayment + additionalUpfront;
				financedPrincipal = ceiling - initialPayment;
				notice = 'مبلغ انتخابی بیش از سقف خرید طرح (' + formatPersianNumber(ceiling) + ' تومان) است. مازاد سقف (' + formatPersianNumber(additionalUpfront) + ' تومان) به پیش‌پرداخت افزوده می‌شود.';
			}

			if (financedPrincipal < 0) financedPrincipal = 0;

			var baseInstallment = Math.floor(financedPrincipal / duration);
			var remainder = financedPrincipal % duration;
			var firstInstallment = baseInstallment + remainder;
			var subsequentInstallment = baseInstallment;

			var schedule = [];
			for (var seq = 1; seq <= duration; seq++) {
				var instVal = (seq === 1) ? firstInstallment : subsequentInstallment;
				schedule.push({
					sequence: seq,
					amount: instVal
				});
			}

			return {
				valid: true,
				purchaseAmount: amount,
				initialPayment: initialPayment,
				additionalUpfront: additionalUpfront,
				totalUpfront: totalUpfront,
				financedPrincipal: financedPrincipal,
				installmentsCount: duration,
				firstInstallment: firstInstallment,
				subsequentInstallment: subsequentInstallment,
				baseInstallment: baseInstallment,
				remainder: remainder,
				schedule: schedule,
				notice: notice
			};
		}

		function renderResults(res) {
			if (!res || !res.valid) {
				if (noticeBox) {
					noticeBox.textContent = res ? res.message : 'مبلغ وارد شده معتبر نمی‌باشد.';
					noticeBox.style.display = 'block';
				}
				if (resMonthlyAmount) resMonthlyAmount.textContent = '۰';
				return;
			}

			if (noticeBox) {
				if (res.notice) {
					noticeBox.textContent = res.notice;
					noticeBox.style.display = 'block';
				} else {
					noticeBox.style.display = 'none';
				}
			}

			if (resMonthlyAmount) {
				resMonthlyAmount.textContent = formatPersianNumber(res.firstInstallment);
			}
			if (resPurchaseAmount) {
				resPurchaseAmount.textContent = formatPersianNumber(res.purchaseAmount) + ' تومان';
			}
			if (resInitialPayment) {
				resInitialPayment.textContent = formatPersianNumber(res.initialPayment) + ' تومان';
			}
			if (resExcessRow && resExcessAmount) {
				if (res.additionalUpfront > 0) {
					resExcessAmount.textContent = formatPersianNumber(res.additionalUpfront) + ' تومان';
					resExcessRow.style.display = 'flex';
				} else {
					resExcessRow.style.display = 'none';
				}
			}
			if (resTotalUpfront) {
				resTotalUpfront.textContent = formatPersianNumber(res.totalUpfront) + ' تومان';
			}
			if (resFinanced) {
				resFinanced.textContent = formatPersianNumber(res.financedPrincipal) + ' تومان';
			}
			if (resInstallments) {
				resInstallments.textContent = formatPersianNumber(res.installmentsCount) + ' قسط ماهانه';
			}

			// Render Schedule Preview Table
			if (resScheduleList && res.schedule) {
				var html = '<table class="negar-club-calc__schedule-table">';
				html += '<thead><tr><th>شماره قسط</th><th>مبلغ قسط (تومان)</th><th>توضیحات</th></tr></thead>';
				html += '<tbody>';
				for (var k = 0; k < res.schedule.length; k++) {
					var item = res.schedule[k];
					var note = (k === 0 && res.remainder > 0) ? 'شامل باقیمانده تسهیلات' : 'قسط عادی ماهانه';
					html += '<tr>';
					html += '<td>قسط ' + formatPersianNumber(item.sequence) + '</td>';
					html += '<td><strong>' + formatPersianNumber(item.amount) + '</strong></td>';
					html += '<td><span class="schedule-note">' + note + '</span></td>';
					html += '</tr>';
				}
				html += '</tbody></table>';
				resScheduleList.innerHTML = html;
			}
		}

		// Debounced Server REST Sync
		var syncWithServer = debounce(function (amount, planId) {
			if (!restUrl || !amount || amount <= 0) return;
			var endpoint = restUrl + (restUrl.indexOf('?') >= 0 ? '&' : '?') + 'purchase_amount=' + encodeURIComponent(amount) + '&plan_id=' + encodeURIComponent(planId);

			fetch(endpoint, {
				method: 'GET',
				headers: { 'Accept': 'application/json' }
			})
			.then(function (r) { return r.json(); })
			.then(function (response) {
				if (response && response.success && response.data) {
					var d = response.data;
					if (resMonthlyAmount) resMonthlyAmount.textContent = formatPersianNumber(d.first_installment_amount);
					if (resFinanced) resFinanced.textContent = formatPersianNumber(d.financed_principal) + ' تومان';
					if (resTotalUpfront) resTotalUpfront.textContent = formatPersianNumber(d.total_upfront) + ' تومان';
				}
			})
			.catch(function () {
				// Silently fallback to client calculation
			});
		}, 350);

		function doCalculate() {
			var plan = getActivePlan();
			if (!plan) return;
			var rawVal = amountInput ? amountInput.value : '0';
			var amount = parseInt(normalizeDigits(rawVal), 10);
			if (isNaN(amount) || amount <= 0) {
				amount = plan.ceiling || 6000000;
			}

			var quote = computeLocalQuote(amount, plan);
			renderResults(quote);

			if (quote && quote.valid) {
				syncWithServer(amount, currentPlanId);
			}
		}

		// Event: Plan Tabs Click
		for (var t = 0; t < planTabs.length; t++) {
			planTabs[t].addEventListener('click', function () {
				for (var j = 0; j < planTabs.length; j++) {
					planTabs[j].classList.remove('is-active');
					planTabs[j].setAttribute('aria-selected', 'false');
				}
				this.classList.add('is-active');
				this.setAttribute('aria-selected', 'true');
				currentPlanId = this.getAttribute('data-plan-id');

				var p = getActivePlan();
				if (p) {
					updateSliderLimits();
					var newAmount = p.ceiling || 6000000;
					if (amountInput) amountInput.value = formatIntCommas(newAmount);
					if (amountSlider) amountSlider.value = newAmount;
					doCalculate();
				}
			});
		}

		// Event: Slider Change
		if (amountSlider) {
			amountSlider.addEventListener('input', function () {
				var val = parseInt(this.value, 10);
				if (amountInput) {
					amountInput.value = formatIntCommas(val);
				}
				doCalculate();
			});
		}

		// Event: Input Typing
		if (amountInput) {
			amountInput.addEventListener('input', function () {
				var cleanDigits = normalizeDigits(this.value);
				if (cleanDigits !== '') {
					var val = parseInt(cleanDigits, 10);
					this.value = formatIntCommas(val);
					if (amountSlider) {
						amountSlider.value = val;
					}
				}
				doCalculate();
			});
		}

		// Schedule Toggle Accordion
		if (scheduleToggle && resScheduleList) {
			scheduleToggle.addEventListener('click', function () {
				var isExpanded = this.getAttribute('aria-expanded') === 'true';
				this.setAttribute('aria-expanded', !isExpanded ? 'true' : 'false');
				resScheduleList.style.display = !isExpanded ? 'block' : 'none';
			});
		}

		// Public method to select plan externally (from plan cards or table)
		window.negarClubSelectPlan = function (planId) {
			if (!plansConfig[planId]) return;
			for (var idx = 0; idx < planTabs.length; idx++) {
				if (planTabs[idx].getAttribute('data-plan-id') === planId) {
					planTabs[idx].click();
					break;
				}
			}
			calcSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
		};

		// Initialize display
		updateSliderLimits();
		doCalculate();
	}

	// 2. Plan Card Action Handlers & Session Intent
	function initPlanCardActions() {
		var selectButtons = document.querySelectorAll('.js-select-plan');
		for (var i = 0; i < selectButtons.length; i++) {
			selectButtons[i].addEventListener('click', function () {
				var planId = this.getAttribute('data-plan-id');
				if (planId && typeof window.negarClubSelectPlan === 'function') {
					try {
						sessionStorage.setItem('negar_club_selected_plan', planId);
					} catch (e) {}
					window.negarClubSelectPlan(planId);
				}
			});
		}
	}

	// 3. Accessible FAQ Accordion
	function initFaqAccordion() {
		var faqToggles = document.querySelectorAll('.negar-club-faq__toggle');
		for (var i = 0; i < faqToggles.length; i++) {
			faqToggles[i].addEventListener('click', function () {
				var isExpanded = this.getAttribute('aria-expanded') === 'true';
				var panelId = this.getAttribute('aria-controls');
				var panel = document.getElementById(panelId);

				// Close other open panels for clean accordion
				for (var j = 0; j < faqToggles.length; j++) {
					if (faqToggles[j] !== this) {
						faqToggles[j].setAttribute('aria-expanded', 'false');
						var otherPanel = document.getElementById(faqToggles[j].getAttribute('aria-controls'));
						if (otherPanel) {
							otherPanel.hidden = true;
						}
					}
				}

				this.setAttribute('aria-expanded', !isExpanded ? 'true' : 'false');
				if (panel) {
					panel.hidden = isExpanded;
				}
			});
		}
	}

	// 4. Client-side Modulo 11 National ID Validation
	function validateNationalId(code) {
		var clean = normalizeDigits(code);
		if (clean.length !== 10) return false;
		if (/^(\d)\1{9}$/.test(clean)) return false;
		var sum = 0;
		for (var i = 0; i < 9; i++) {
			sum += parseInt(clean.charAt(i), 10) * (10 - i);
		}
		var remainder = sum % 11;
		var checkDigit = parseInt(clean.charAt(9), 10);
		if (remainder < 2) {
			return checkDigit === remainder;
		}
		return checkDigit === (11 - remainder);
	}

	// 5. Application Form Controller (Fix E)
	function initApplicationForm() {
		var form = document.getElementById('negar-club-apply-form');
		if (!form) return;

		var endpoint    = form.getAttribute('data-rest-endpoint');
		var nonce       = form.getAttribute('data-rest-nonce');
		var planSelect  = document.getElementById('negar-apply-plan');
		var ceilingIn   = document.getElementById('negar-apply-ceiling');
		var ceilingH    = document.getElementById('negar-apply-ceiling-hint');
		var nidIn       = document.getElementById('negar-apply-national-id');
		var phoneIn     = document.getElementById('negar-apply-phone');
		var notesIn     = document.getElementById('negar-apply-notes');
		var submitBtn   = document.getElementById('negar-apply-submit-btn');
		var btnText     = submitBtn ? submitBtn.querySelector('.negar-btn-text') : null;
		var btnSpinner  = submitBtn ? submitBtn.querySelector('.negar-btn-spinner') : null;
		var feedback    = document.getElementById('negar-apply-feedback');
		var successCard = document.getElementById('negar-apply-success-card');
		var successNum  = document.getElementById('negar-apply-success-number');
		var successStat = document.getElementById('negar-apply-success-status');
		var successMsg  = document.getElementById('negar-apply-success-msg');

		var isSubmitting = false;

		function updateCeilingLimits() {
			if (!planSelect || !ceilingIn) return;
			var opt = planSelect.options[planSelect.selectedIndex];
			if (!opt) return;
			var ceil = parseInt(opt.getAttribute('data-ceiling') || '0', 10);
			if (ceil > 0) {
				ceilingIn.max = ceil;
				if (!ceilingIn.value || parseInt(ceilingIn.value, 10) > ceil) {
					ceilingIn.value = ceil;
				}
				if (ceilingH) {
					ceilingH.textContent = 'حداکثر سقف این طرح: ' + formatPersianNumber(ceil) + ' تومان';
				}
			}
		}

		if (planSelect) {
			planSelect.addEventListener('change', updateCeilingLimits);
			updateCeilingLimits();
		}

		// Sync with session selected plan if available
		try {
			var savedPlan = sessionStorage.getItem('negar_club_selected_plan');
			if (savedPlan && planSelect) {
				for (var idx = 0; idx < planSelect.options.length; idx++) {
					if (planSelect.options[idx].value === savedPlan) {
						planSelect.selectedIndex = idx;
						updateCeilingLimits();
						break;
					}
				}
			}
		} catch (e) {}

		function showError(msg) {
			if (!feedback) return;
			feedback.textContent = msg;
			feedback.className = 'negar-club-apply-feedback negar-club-apply-feedback--error';
			feedback.hidden = false;
			feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		}

		function clearError() {
			if (!feedback) return;
			feedback.textContent = '';
			feedback.hidden = true;
		}

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (isSubmitting) return;

			clearError();

			var planId     = planSelect ? planSelect.value : '';
			var ceilingVal = ceilingIn ? parseInt(normalizeDigits(ceilingIn.value), 10) : 0;
			var nidVal     = nidIn ? normalizeDigits(nidIn.value) : '';
			var phoneVal   = phoneIn ? normalizeDigits(phoneIn.value) : '';
			var notesVal   = notesIn ? notesIn.value.trim() : '';

			// Validation
			if (!planId) {
				showError('لطفاً طرح اقساطی مورد نظر را انتخاب فرمایید.');
				return;
			}

			if (isNaN(ceilingVal) || ceilingVal <= 0) {
				showError('مبلغ سقف درخواستی نامعتبر است.');
				if (ceilingIn) ceilingIn.focus();
				return;
			}

			var maxCeil = planSelect && planSelect.options[planSelect.selectedIndex] ? parseInt(planSelect.options[planSelect.selectedIndex].getAttribute('data-ceiling') || '0', 10) : 0;
			if (maxCeil > 0 && ceilingVal > maxCeil) {
				showError('سقف درخواستی نمی‌تواند از سقف مجاز طرح (' + formatPersianNumber(maxCeil) + ' تومان) بیشتر باشد.');
				if (ceilingIn) ceilingIn.focus();
				return;
			}

			if (!validateNationalId(nidVal)) {
				showError('کد ملی وارد شده نامعتبر است (کنترل رقم چک مودولو ۱۱ تأیید نشد).');
				if (nidIn) nidIn.focus();
				return;
			}

			if (!/^09\d{9}$/.test(phoneVal)) {
				showError('شماره همراه وارد شده نامعتبر است. فرمت صحیح: ۰۹xxxxxxxxx');
				if (phoneIn) phoneIn.focus();
				return;
			}

			// Prevent repeated submission (Fix E)
			isSubmitting = true;
			if (submitBtn) submitBtn.disabled = true;
			if (btnSpinner) btnSpinner.hidden = false;
			if (btnText) btnText.textContent = 'در حال ثبت درخواست...';

			var payload = {
				plan_id: planId,
				requested_ceiling: ceilingVal,
				national_id: nidVal,
				contact_phone: phoneVal,
				applicant_notes: notesVal
			};

			fetch(endpoint, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': nonce
				},
				body: JSON.stringify(payload)
			})
			.then(function (res) {
				return res.json().then(function (data) {
					return { status: res.status, data: data };
				});
			})
			.then(function (resObj) {
				isSubmitting = false;
				if (submitBtn) submitBtn.disabled = false;
				if (btnSpinner) btnSpinner.hidden = true;
				if (btnText) btnText.textContent = 'ثبت نهایی درخواست عضویت';

				if (resObj.status === 201 && resObj.data && resObj.data.success) {
					form.hidden = true;
					if (successCard) {
						var d = resObj.data.data;
						if (successNum) successNum.textContent = d.application_number;
						if (successStat) successStat.textContent = d.status_label || 'در انتظار بررسی مدارک';
						if (successMsg) successMsg.textContent = d.message || 'درخواست شما با موفقیت ثبت شد.';
						successCard.hidden = false;
						successCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
					}
				} else {
					var err = (resObj.data && resObj.data.message) ? resObj.data.message : 'خطا در ثبت درخواست. لطفاً دوباره تلاش نمایید.';
					showError(err);
				}
			})
			.catch(function () {
				isSubmitting = false;
				if (submitBtn) submitBtn.disabled = false;
				if (btnSpinner) btnSpinner.hidden = true;
				if (btnText) btnText.textContent = 'ثبت نهایی درخواست عضویت';
				showError('خطا در برقراری ارتباط با سرور. لطفاً اتصال اینترنت خود را بررسی نمایید.');
			});
		});
	}

	// DOM Ready
	document.addEventListener('DOMContentLoaded', function () {
		initCalculator();
		initPlanCardActions();
		initFaqAccordion();
		initApplicationForm();
	});

})();
