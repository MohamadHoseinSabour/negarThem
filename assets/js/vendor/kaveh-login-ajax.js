function kavehNxReady(){return typeof window.Notiflix!=='undefined'&&!!window.Notiflix;}
jQuery(document).on('submit', 'form.smslogin', function (e) {
	e.preventDefault();
	var phoneInput = jQuery(this).find('#phone');
	var persianNumberPattern = /[\u06F0-\u06F9]/g; // matches all Persian digits
	var englishNumberMapping = {
		'۰': '0',
		'۱':  '1',
		'۲': '2',
		'۳': '3',
		'۴': '4',
		'۵': '5',
		'۶': '6',
		'۷': '7',
		'۸': '8',
		'۹': '9'
	};
	var englishPhone = phoneInput.val().replace(persianNumberPattern, function (match) {
		return englishNumberMapping[match];
	});
	// Use optimized Rewrite API URL with fallback
	var ajaxUrl = ksiteurl.smslogin_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		dataType: 'json',
		data: {
			action: 'kaveh_smslogin',
			phone: englishPhone,
		},
		beforeSend: function () {
			Notiflix.Block.dots('.loadlogin');
		},
		success: function (response) {
			if (response.result === 'success') {

				jQuery('form.smslogin').addClass('formfinal');
				jQuery('form.smslogin').removeClass('smslogin');
				jQuery('.auth__page__twelve__logo').hide();
				jQuery('#phone').prop('readonly', true);
				jQuery('.auth__page__btn').text(ksiteurl.submit_text);
				jQuery('#phone').after(
					`
					<br>
					<p class="pinfocode"><i class="isax isax-barcode5 me-2"></i>${ksiteurl.enter_verification_code}</p>
					<div class="minput">
						<input id="boxv1" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv2" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv3" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv4" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
					</div>
					<a class="bazgasht">${ksiteurl.back_and_edit}</a>
					${kbksms == 1 ? `
						<p class="d-block mx-auto text-center" id="timer"> <i class="isax isax-timer-start5"></i> ${ksiteurl.time_remaining}:  <span id="time" class="kh-font-black"> ${kbksmstime} </span> ${ksiteurl.seconds}  </p>
						<a class="mx-auto text-center sendcodea" id="send-again" onclick="sendAgain()" style="display:none;"> ${ksiteurl.resend_sms} </a>
						` : ``}
					
					`
				);
				if(kbksms == 1){
					sendtimer();
				}
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else if (response.result === 'no_register') {

				Notiflix.Notify.failure(ksiteurl.phone_not_found, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else if (response.result === 'error') {

				jQuery('form.smslogin').addClass('formfinal');
				jQuery('form.smslogin').removeClass('smslogin');
				jQuery('.auth__page__twelve__logo').hide();
				jQuery('#phone').prop('readonly', true);
				jQuery('.auth__page__btn').text(ksiteurl.submit_text);
				jQuery('#phone').after(
					`
					<br>
					<p class="pinfocode"><i class="isax isax-barcode5 me-2"></i>${ksiteurl.enter_verification_code}</p>
					<div class="minput">
						<input id="boxv1" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv2" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv3" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv4" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
					</div>
					<div class="auth__page__form__group">
						<input id="first_name" type="text" class="auth__page__form__control auth__ease__in__out" ${kinputslatin == 1 ? `placeholder="${ksiteurl.first_name_english}" pattern="[a-zA-Z]+"` : `placeholder="${ksiteurl.first_name}" pattern="[\\u0600-\\u06FF\\s]+"`} >
					</div>
					<div class="auth__page__form__group">
						<input id="last_name" type="text" class="auth__page__form__control auth__ease__in__out" ${kinputslatin == 1 ? `placeholder="${ksiteurl.last_name_english}" pattern="[a-zA-Z]+"` : `placeholder="${ksiteurl.last_name}" pattern="[\\u0600-\\u06FF\\s]+"`} >
					</div>
					<div class="auth__page__form__group">
					<input id="password" type="password" class="auth__page__form__control auth__ease__in__out" placeholder="${ksiteurl.password}">
					</div>
					<a class="bazgasht">${ksiteurl.back_and_edit}</a>
					${kbksms == 1 ? `
						<p class="d-block mx-auto text-center" id="timer"> <i class="isax isax-timer-start5"></i> ${ksiteurl.time_remaining}:  <span id="time" class="kh-font-black"> ${kbksmstime} </span> ${ksiteurl.seconds}  </p>
						<a class="mx-auto text-center sendcodea" id="send-again" onclick="sendAgain()" style="display:none;"> ${ksiteurl.resend_sms} </a>
						` : ``}
					
					`
				);
				if(kbksms == 1){
					sendtimer();
				}
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else {

				Notiflix.Notify.failure(ksiteurl.contact_support, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			}
		},
		error: function() {
			// Fallback to admin-ajax
			jQuery.ajax({
				type: 'POST',
				url: fallbackUrl,
				data: {
					action: 'kaveh_smslogin',
					phone: englishPhone,
				},
				success: function (response) {
					// Same success handler as above - simplified for fallback
					if (response.result === 'success' || response.result === 'error') {
						location.reload();
					} else {
						Notiflix.Notify.failure(ksiteurl.contact_support, {
							timeout: 5000,
							rtl: true,
							position: 'left-bottom',
							fontFamily: kfont[0],
						});
					}
					Notiflix.Block.remove('.loadlogin');
				}
			});
		}
	});
});


jQuery(document).on('submit', 'form.formfinal', function (e) {
	e.preventDefault();
	var phoneInput = jQuery(this).find('#phone');
	var persianNumberPattern = /[\u06F0-\u06F9]/g; // matches all Persian digits
	var englishNumberMapping = {
		'۰': '0',
		'۱': '1',
		'۲': '2',
		'۳': '3',
		'۴': '4',
		'۵': '5',
		'۶': '6',
		'۷': '7',
		'۸': '8',
		'۹': '9'
	};
	var englishPhone = phoneInput.val().replace(persianNumberPattern, function (match) {
		return englishNumberMapping[match];
	});
	var boxv1 = document.getElementById('boxv1').value;
	var boxv2 = document.getElementById('boxv2').value;
	var boxv3 = document.getElementById('boxv3').value;
	var boxv4 = document.getElementById('boxv4').value;
	var boxesinputpre = boxv1 + boxv2 + boxv3 + boxv4;
	var persianNumberPatternn = /[\u06F0-\u06F9]/g; // matches all Persian digits
	var englishNumberMappingg = {
		'۰': '0',
		'۱': '1',
		'۲': '2',
		'۳': '3',
		'۴': '4',
		'۵': '5',
		'۶': '6',
		'۷': '7',
		'۸': '8',
		'۹': '9'
	};
	var boxesinput = boxesinputpre.replace(persianNumberPatternn, function (match) {
		return englishNumberMappingg[match];
	});
	// Use optimized Rewrite API URL with fallback
	var ajaxUrl = ksiteurl.formfinal_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		dataType: 'json',
		data: {
			action: 'kaveh_formfinal',
			phone: englishPhone,
			code: boxesinput,
			fname: jQuery('#first_name').val(),
			lname: jQuery('#last_name').val(),
			pass: jQuery('#password').val(),
		},
		beforeSend: function () {
			Notiflix.Block.dots('.loadlogin');
		},
		success: function (response) {
			if (typeof response === 'string') {
				try { response = JSON.parse(response); } catch (err) { response = {}; }
			}
			if (response.result === 'success') {
				Notiflix.Notify.success(ksiteurl.login_success_wait, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				Notiflix.Report.success(
					ksiteurl.login_success,
					ksiteurl.processing_request,
					ksiteurl.please_wait,
					{
						rtl: true,
						zindex: 9999999,
						fontFamily: kfont[0],
					},
				);

				setTimeout(function () {
					Notiflix.Block.remove('.loadlogin');
					kavehLoginRedirectAfterSuccess();
				}, 1500);

			} else if (response.result === 'no_register') {

				Notiflix.Notify.failure(ksiteurl.phone_not_found, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else if (response.result === 'error') {
				Notiflix.Notify.failure(ksiteurl.wrong_verification_code, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});

				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else {

				Notiflix.Notify.failure(ksiteurl.contact_support, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			}
		},
		error: function() {
			// Fallback to admin-ajax
			jQuery.ajax({
				type: 'POST',
				url: fallbackUrl,
				dataType: 'json',
				data: {
					action: 'kaveh_formfinal',
					phone: englishPhone,
					code: boxesinput,
					fname: jQuery('#first_name').val(),
					lname: jQuery('#last_name').val(),
					pass: jQuery('#password').val(),
				},
				success: function (response) {
					if (typeof response === 'string') {
						try { response = JSON.parse(response); } catch (err) { response = {}; }
					}
					if (response.result === 'success') {
						kavehLoginRedirectAfterSuccess();
					} else if (response.result === 'error') {
						Notiflix.Notify.failure(ksiteurl.wrong_verification_code, {
							timeout: 2000,
							rtl: true,
							position: 'left-bottom',
							fontFamily: kfont[0],
						});
					} else {
						Notiflix.Notify.failure(ksiteurl.contact_support, {
							timeout: 5000,
							rtl: true,
							position: 'left-bottom',
							fontFamily: kfont[0],
						});
					}
					Notiflix.Block.remove('.loadlogin');
				}
			});
		}
	});
});


function kavehLoginRedirectAfterSuccess() {
	try {
		if (window.location && window.location.search) {
			var queryParams = new URLSearchParams(window.location.search);
			var redirectTo = queryParams.get('redirect_to');
			if (redirectTo) {
				window.location.href = redirectTo;
				return;
			}
		}
	} catch (err) {}
	window.location.reload();
}

function kavehToEnglishDigits(value) {
	return String(value || '')
		.replace(/[\u06F0-\u06F9]/g, function (d) {
			return String(d.charCodeAt(0) - 0x06F0);
		})
		.replace(/[\u0660-\u0669]/g, function (d) {
			return String(d.charCodeAt(0) - 0x0660);
		});
}

// Returns a local 0xxxxxxxxxx number, or '' when the input is not a phone number.
function kavehNormalizeLoginPhone(value) {
	var digits = kavehToEnglishDigits(value).replace(/[\s\-()]/g, '');
	if (/^\+98\d{10}$/.test(digits)) {
		digits = '0' + digits.slice(3);
	} else if (/^0098\d{10}$/.test(digits)) {
		digits = '0' + digits.slice(4);
	} else if (/^98\d{10}$/.test(digits)) {
		digits = '0' + digits.slice(2);
	} else if (/^9\d{9}$/.test(digits)) {
		digits = '0' + digits;
	}
	return /^0\d{10}$/.test(digits) ? digits : '';
}

function kavehPassLoginNotify(message) {
	Notiflix.Notify.failure(message, {
		timeout: 5000,
		rtl: true,
		position: 'left-bottom',
		fontFamily: kfont[0],
	});
}

function kavehPassLoginShowRegister() {
	jQuery('form.passlogin').addClass('passformfinal');
	jQuery('form.passlogin').removeClass('passlogin');
	jQuery('.auth__page__twelve__logo').hide();
	jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').prop('readonly', true);
	jQuery('#password').prop('readonly', true);
	jQuery('.auth__page__btn').text(ksiteurl.submit_text);
	jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').after(
		`
	<br>
	<p class="pinfocode"><i class="isax isax-barcode5 me-2"></i>${ksiteurl.complete_registration}</p>
	<div class="auth__page__form__group">
		<input id="first_name" type="text" class="auth__page__form__control auth__ease__in__out" ${kinputslatin == 1 ? `placeholder="${ksiteurl.first_name_english}" pattern="[a-zA-Z]+"` : `placeholder="${ksiteurl.first_name}" pattern="[\\u0600-\\u06FF\\s]+"`} >
	</div>
	<div class="auth__page__form__group">
		<input id="last_name" type="text" class="auth__page__form__control auth__ease__in__out" ${kinputslatin == 1 ? `placeholder="${ksiteurl.last_name_english}" pattern="[a-zA-Z]+"` : `placeholder="${ksiteurl.last_name}" pattern="[\\u0600-\\u06FF\\s]+"`} >
	</div>
	<a class="bazgasht">${ksiteurl.back_and_edit}</a>
	`
	);
}

function kavehPassLoginHandleResponse(response, loginType) {
	var result = response && response.result;

	if (result === 'success') {
		Notiflix.Notify.success(ksiteurl.login_success_wait, {
			timeout: 2000,
			rtl: true,
			position: 'left-bottom',
			fontFamily: kfont[0],
		});
		Notiflix.Report.success(
			ksiteurl.login_success,
			ksiteurl.processing_request,
			ksiteurl.please_wait,
			{
				rtl: true,
				zindex: 9999999,
				fontFamily: kfont[0],
			},
		);
		setTimeout(function () {
			Notiflix.Block.remove('.loadlogin');
			kavehLoginRedirectAfterSuccess();
		}, 1500);
		return;
	}

	if (result === 'wrong') {
		kavehPassLoginNotify(ksiteurl.wrong_credentials);
	} else if (result === 'no_register') {
		kavehPassLoginNotify(loginType === 'phone' ? ksiteurl.phone_not_found : ksiteurl.email_not_found);
	} else if (result === 'error' && loginType !== 'username') {
		kavehPassLoginShowRegister();
	} else {
		kavehPassLoginNotify(ksiteurl.contact_support);
	}

	setTimeout(function () {
		Notiflix.Block.remove('.loadlogin');
	}, 1500);
}

jQuery(document).on('submit', 'form.passlogin', function (e) {
	e.preventDefault();
	var $form = jQuery(this);
	var rawInput = jQuery.trim($form.find('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').val() || '');
	var pass = $form.find('#password').val() || '';
	var ajaxUrl = ksiteurl.passlogin_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;

	if (rawInput === '') {
		kavehPassLoginNotify(ksiteurl.valid_mobile_email);
		return;
	}
	if (pass === '') {
		kavehPassLoginNotify(ksiteurl.wrong_credentials);
		return;
	}

	// Accepts email, mobile (Persian/Arabic digits, +98/0098/98 prefixes) or a WordPress username.
	var data = {
		action: 'kaveh_passlogin',
		password: pass,
	};
	var loginType;
	var phone = kavehNormalizeLoginPhone(rawInput);
	if (/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(rawInput)) {
		loginType = 'email';
		data.email = rawInput;
	} else if (phone) {
		loginType = 'phone';
		data.phone = phone;
	} else {
		loginType = 'username';
		data.username = rawInput;
	}

	var sendRequest = function (url) {
		return jQuery.ajax({
			type: 'POST',
			url: url,
			data: data,
			dataType: 'json',
		});
	};

	var failHard = function () {
		kavehPassLoginNotify(ksiteurl.contact_support);
		Notiflix.Block.remove('.loadlogin');
	};

	Notiflix.Block.dots('.loadlogin');

	sendRequest(ajaxUrl)
		.done(function (response) {
			kavehPassLoginHandleResponse(response, loginType);
		})
		.fail(function () {
			if (!fallbackUrl || fallbackUrl === ajaxUrl) {
				failHard();
				return;
			}
			sendRequest(fallbackUrl)
				.done(function (response) {
					kavehPassLoginHandleResponse(response, loginType);
				})
				.fail(failHard);
		});
});


jQuery(document).on('submit', 'form.passformfinal', function (e) {
	e.preventDefault();
	var input = jQuery(this).find('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').val();
	var pass = jQuery(this).find('#password').val();
	// Use optimized Rewrite API URL with fallback
	var ajaxUrl = ksiteurl.passformfinal_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(input)) {

		jQuery.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				action: 'kaveh_passformfinal',
				email: input,
				password: pass,
				fname: jQuery('#first_name').val(),
				lname: jQuery('#last_name').val(),
			},
			beforeSend: function () {
				Notiflix.Block.dots('.loadlogin');
			},
			success: function (response) {
				if (response.result === 'success') {

					Notiflix.Notify.success(ksiteurl.login_success_wait, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				Notiflix.Report.success(
					ksiteurl.login_success,
					ksiteurl.processing_request,
					ksiteurl.please_wait,
					{
						rtl: true,
						zindex: 9999999,
						fontFamily: kfont[0],
					},
				);

					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
						if (typeof window !== 'undefined' && window.location && window.location.search) {
							const queryParams = new URLSearchParams(window.location.search);
							const redirectTo = queryParams.get('redirect_to');
							if (redirectTo) {
								window.location.href = redirectTo;
							}
						}
						else {
							location.reload();
						}
					}, 1500);

				} else if (response.result === 'error') {

					Notiflix.Notify.failure(ksiteurl.contact_support, {
						timeout: 5000,
						rtl: true,
						position: 'left-bottom',
						fontFamily: kfont[0],
					});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				} else {

					Notiflix.Notify.failure(ksiteurl.contact_support, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				}
			},
			error: function() {
				// Fallback to admin-ajax
				jQuery.ajax({
					type: 'POST',
					url: fallbackUrl,
					data: {
						action: 'kaveh_passformfinal',
						email: input,
						password: pass,
						fname: jQuery('#first_name').val(),
						lname: jQuery('#last_name').val(),
					},
					success: function (response) {
						if (response.result === 'success') {
							location.reload();
						} else {
							Notiflix.Notify.failure(ksiteurl.contact_support, {
								timeout: 5000,
								rtl: true,
								position: 'left-bottom',
								fontFamily: kfont[0],
							});
						}
						Notiflix.Block.remove('.loadlogin');
					}
				});
			}
		});

	} else if (/^0[0-9]{10}$/.test(input)) {
		var phoneInput = jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot');
		var persianNumberPattern = /[\u06F0-\u06F9]/g; // matches all Persian digits
		var englishNumberMapping = {
			'۰': '0',
			'۱': '1',
			'۲': '2',
			'۳': '3',
			'۴': '4',
			'۵': '5',
			'۶': '6',
			'۷': '7',
			'۸': '8',
			'۹': '9'
		};
		var englishPhone = phoneInput.val().replace(persianNumberPattern, function (match) {
			return englishNumberMapping[match];
		});
		jQuery.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				action: 'kaveh_passformfinal',
				phone: englishPhone,
				password: pass,
				fname: jQuery('#first_name').val(),
				lname: jQuery('#last_name').val(),
			},
			beforeSend: function () {
				Notiflix.Block.dots('.loadlogin');

			},
			success: function (response) {
				if (response.result === 'success') {

					Notiflix.Notify.success(ksiteurl.login_success_wait, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				Notiflix.Report.success(
					ksiteurl.login_success,
					ksiteurl.processing_request,
					ksiteurl.please_wait,
					{
						rtl: true,
						zindex: 9999999,
						fontFamily: kfont[0],
					},
				);

					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
						if (typeof window !== 'undefined' && window.location && window.location.search) {
							const queryParams = new URLSearchParams(window.location.search);
							const redirectTo = queryParams.get('redirect_to');
							if (redirectTo) {
								window.location.href = redirectTo;
							}
						}
						else {
							location.reload();
						}
					}, 1500);

				} else if (response.result === 'error') {

					Notiflix.Notify.failure(ksiteurl.contact_support, {
						timeout: 5000,
						rtl: true,
						position: 'left-bottom',
						fontFamily: kfont[0],
					});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				} else {

					Notiflix.Notify.failure(ksiteurl.contact_support, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				}
			},
			error: function() {
				// Fallback to admin-ajax
				jQuery.ajax({
					type: 'POST',
					url: fallbackUrl,
					data: {
						action: 'kaveh_passformfinal',
						phone: englishPhone,
						password: pass,
						fname: jQuery('#first_name').val(),
						lname: jQuery('#last_name').val(),
					},
					success: function (response) {
						if (response.result === 'success') {
							location.reload();
						} else {
							Notiflix.Notify.failure(ksiteurl.contact_support, {
								timeout: 5000,
								rtl: true,
								position: 'left-bottom',
								fontFamily: kfont[0],
							});
						}
						Notiflix.Block.remove('.loadlogin');
					}
				});
			}
		});

	}


});


jQuery(document).on('submit', 'form.forgotform', function (e) {
	e.preventDefault();
	var input = jQuery(this).find('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').val();
	// Use optimized Rewrite API URL with fallback
	var ajaxUrl = ksiteurl.forgotform_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(input)) {

		jQuery.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				action: 'kaveh_forgotform',
				email: input,
			},
			beforeSend: function () {
				Notiflix.Block.dots('.loadlogin');
			},
			success: function (response) {
				if (response.result === 'success') {

					Notiflix.Notify.success(ksiteurl.password_reset_sent, {
						timeout: 2000,
						rtl: true,
						position: 'left-bottom',
						fontFamily: kfont[0],
					});

					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
						if (typeof window !== 'undefined' && window.location && window.location.search) {
							const queryParams = new URLSearchParams(window.location.search);
							const redirectTo = queryParams.get('redirect_to');
							if (redirectTo) {
								window.location.href = redirectTo;
							}
						}
						else {
							location.reload();
						}
					}, 1500);


				} else {

					Notiflix.Notify.failure(ksiteurl.email_not_found, {
						timeout: 5000,
						rtl: true,
						position: 'left-bottom',
						fontFamily: kfont[0],
					});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);
				}
			},
			error: function() {
				// Fallback to admin-ajax
				jQuery.ajax({
					type: 'POST',
					url: fallbackUrl,
					data: {
						action: 'kaveh_forgotform',
						email: input,
					},
					success: function (response) {
						if (response.result === 'success') {
							location.reload();
						} else {
							Notiflix.Notify.failure(ksiteurl.email_not_found, {
								timeout: 5000,
								rtl: true,
								position: 'left-bottom',
								fontFamily: kfont[0],
							});
						}
						Notiflix.Block.remove('.loadlogin');
					}
				});
			}
		});

	} else if (/^0[0-9]{10}$/.test(input)) {
		var phoneInput = jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot');
		var persianNumberPattern = /[\u06F0-\u06F9]/g; // matches all Persian digits
		var englishNumberMapping = {
			'۰': '0',
			'۱': '1',
			'۲': '2',
			'۳': '3',
			'۴': '4',
			'۵': '5',
			'۶': '6',
			'۷': '7',
			'۸': '8',
			'۹': '9'
		};
		var englishPhone = phoneInput.val().replace(persianNumberPattern, function (match) {
			return englishNumberMapping[match];
		});
		jQuery.ajax({
			type: 'POST',
			url: ajaxUrl,
			data: {
				action: 'kaveh_forgotform',
				phone: englishPhone,

			},
			beforeSend: function () {
				Notiflix.Block.dots('.loadlogin');
			},
			success: function (response) {
				if (response.result === 'success') {

					jQuery('form.forgotform').addClass('formforgotfinal');
					jQuery('form.forgotform').removeClass('forgotform');
					jQuery('.auth__page__twelve__logo').hide();
					jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').prop('readonly', true);
					jQuery('.auth__page__btn').text(ksiteurl.submit_text);
					jQuery('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot').after(
						`
					<br>
					<p class="pinfocode"><i class="isax isax-barcode5 me-2"></i>${ksiteurl.enter_verification_code}</p>
					<div class="minput">
						<input id="boxv1" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv2" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv3" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
						<input id="boxv4" class="verifyinput" type="number" maxlength="1" pattern="[0-9]">
					</div>
					<div class="auth__page__form__group">
					<input id="password" type="password" class="auth__page__form__control auth__ease__in__out" placeholder="${ksiteurl.enter_new_password}">
					</div>
					<a class="bazgasht">${ksiteurl.back_and_edit}</a>
					${kbksms == 1 ? `
						<p class="d-block mx-auto text-center" id="timer"> <i class="isax isax-timer-start5"></i> ${ksiteurl.time_remaining}:  <span id="time" class="kh-font-black"> ${kbksmstime} </span> ${ksiteurl.seconds}  </p>
						<a class="mx-auto text-center sendcodea" id="send-again" onclick="sendAgain()" style="display:none;"> ${ksiteurl.resend_sms} </a>
						` : ``}
					
					`
				);
				if(kbksms == 1){
					sendtimer();
				}
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				} else {

					Notiflix.Notify.failure(ksiteurl.phone_not_found, {
						timeout: 5000,
						rtl: true,
						position: 'left-bottom',
						fontFamily: kfont[0],
					});
					setTimeout(() => {
						Notiflix.Block.remove('.loadlogin');
					}, 1500);

				}
			},
			error: function() {
				// Fallback to admin-ajax
				jQuery.ajax({
					type: 'POST',
					url: fallbackUrl,
					data: {
						action: 'kaveh_forgotform',
						phone: englishPhone,
					},
					success: function (response) {
						if (response.result === 'success') {
							location.reload();
						} else {
							Notiflix.Notify.failure(ksiteurl.phone_not_found, {
								timeout: 5000,
								rtl: true,
								position: 'left-bottom',
								fontFamily: kfont[0],
							});
						}
						Notiflix.Block.remove('.loadlogin');
					}
				});
			}
		});

	}

});

jQuery(document).on('submit', 'form.formforgotfinal', function (e) {
	e.preventDefault();
	var phoneInput = jQuery(this).find('#email, #email-login, #email-register, #email-page-login, #email-page-register, #email-forgot');
	var persianNumberPattern = /[\u06F0-\u06F9]/g; // matches all Persian digits
	var englishNumberMapping = {
		'۰': '0',
		'۱': '1',
		'۲': '2',
		'۳': '3',
		'۴': '4',
		'۵': '5',
		'۶': '6',
		'۷': '7',
		'۸': '8',
		'۹': '9'
	};
	var englishPhone = phoneInput.val().replace(persianNumberPattern, function (match) {
		return englishNumberMapping[match];
	});
	var boxv1 = document.getElementById('boxv1').value;
	var boxv2 = document.getElementById('boxv2').value;
	var boxv3 = document.getElementById('boxv3').value;
	var boxv4 = document.getElementById('boxv4').value;
	var boxesinputpre = boxv1 + boxv2 + boxv3 + boxv4;
	var persianNumberPatternn = /[\u06F0-\u06F9]/g; // matches all Persian digits
	var englishNumberMappingg = {
		'۰': '0',
		'۱': '1',
		'۲': '2',
		'۳': '3',
		'۴': '4',
		'۵': '5',
		'۶': '6',
		'۷': '7',
		'۸': '8',
		'۹': '9'
	};
	var boxesinput = boxesinputpre.replace(persianNumberPatternn, function (match) {
		return englishNumberMappingg[match];
	});
	// Use optimized Rewrite API URL with fallback
	var ajaxUrl = ksiteurl.formforgotfinal_url || ksiteurl.ajax_url;
	var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
	jQuery.ajax({
		type: 'POST',
		url: ajaxUrl,
		data: {
			action: 'kaveh_formforgotfinal',
			phone: englishPhone,
			code: boxesinput,
			pass: jQuery('#password').val(),
		},
		beforeSend: function () {
			Notiflix.Block.dots('.loadlogin');
		},
		success: function (response) {
			if (response.result === 'success') {
				Notiflix.Notify.success(ksiteurl.password_changed_success, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				Notiflix.Report.success(
					ksiteurl.login_success,
					ksiteurl.processing_request,
					ksiteurl.please_wait,
					{
						rtl: true,
						zindex: 9999999,
						fontFamily: kfont[0],
					},
				);

				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
					if (typeof window !== 'undefined' && window.location && window.location.search) {
						const queryParams = new URLSearchParams(window.location.search);
						const redirectTo = queryParams.get('redirect_to');
						if (redirectTo) {
							window.location.href = redirectTo;
						}
					}
					else {
						location.reload();
					}
				}, 1500);

			} else if (response.result === 'error') {
				Notiflix.Notify.failure(ksiteurl.wrong_verification_code, {
					timeout: 2000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});

				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			} else {

				Notiflix.Notify.failure(ksiteurl.contact_support, {
					timeout: 5000,
					rtl: true,
					position: 'left-bottom',
					fontFamily: kfont[0],
				});
				setTimeout(() => {
					Notiflix.Block.remove('.loadlogin');
				}, 1500);

			}
		},
		error: function() {
			// Fallback to admin-ajax
			jQuery.ajax({
				type: 'POST',
				url: fallbackUrl,
				data: {
					action: 'kaveh_formforgotfinal',
					phone: englishPhone,
					code: boxesinput,
					pass: jQuery('#password').val(),
				},
				success: function (response) {
					if (response.result === 'success') {
						location.reload();
					} else {
						Notiflix.Notify.failure(ksiteurl.contact_support, {
							timeout: 5000,
							rtl: true,
							position: 'left-bottom',
							fontFamily: kfont[0],
						});
					}
					Notiflix.Block.remove('.loadlogin');
				}
			});
		}
	});
});


jQuery(document).on('click', '.forgotk', function (e) {
	e.preventDefault();
	jQuery('.passlogin').html(
		`
	<div class="auth__page__form__group position-relative">
		<i class="iconly iconly-Message iabs"></i>
		<input type="text" id="email-forgot" class="auth__page__form__control auth__ease__in__out" placeholder="${ksiteurl.valid_mobile_email}">
	</div>
	<a class="bazgasht">${ksiteurl.back_and_edit}</a>
	<br>
	<button type="submit" class="auth__page__btn auth__page__btn__success auth__w__fit auth__block"> ${ksiteurl.password_recovery} </button>
	`
	)
	jQuery('form.passlogin').addClass('forgotform');
	jQuery('form.passlogin').removeClass('passlogin');
});


jQuery(document).on('click', '.bazgasht', function (e) {
	e.preventDefault();
	location.reload();
});


function sendtimer(){
    const timerElement = document.getElementById('time');
    const sendAgainButton = document.getElementById('send-again');

    timeLeft = kbksmstime;
    timerElement.textContent = timeLeft;
    sendAgainButton.style.display = 'none';
    countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            sendAgainButton.style.display = 'block';
            timerElement.textContent = 0;
        } else {
            timerElement.textContent = timeLeft;
        }
        timeLeft -= 1;
    }, 1000);

}

function sendAgain() {

    const timerElement = document.getElementById('time');
    const sendAgainButton = document.getElementById('send-again');

    timeLeft = kbksmstime;
    timerElement.textContent = timeLeft;
    sendAgainButton.style.display = 'none';
    countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            sendAgainButton.style.display = 'block';
            timerElement.textContent = 0;
        } else {
            timerElement.textContent = timeLeft;
        }
        timeLeft -= 1;
    }, 1000);
    send_code_again()
}

function send_code_again(){
    var phoneInput = jQuery('#phone');
    // Use optimized Rewrite API URL with fallback
    var ajaxUrl = ksiteurl.backupsms_url || ksiteurl.ajax_url;
    var fallbackUrl = ksiteurl.fallback_url || ksiteurl.ajax_url;
    jQuery.ajax({
        type: 'POST',
        url: ajaxUrl,
        data: {
			action: 'kaveh_backupsms',
            phone: phoneInput.val(),
        },
        beforeSend: function () {
			Notiflix.Block.dots('.loadlogin');
        },
        success: function (response) {
            if (response.result === 'success') {
                setTimeout(() => {
                    Notiflix.Block.remove('.loadlogin');
                }, 500);
            }
        },
        error: function() {
            // Fallback to admin-ajax
            jQuery.ajax({
                type: 'POST',
                url: fallbackUrl,
                data: {
                    action: 'kaveh_backupsms',
                    phone: phoneInput.val(),
                },
                success: function (response) {
                    Notiflix.Block.remove('.loadlogin');
                }
            });
        }
    })
}