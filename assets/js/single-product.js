/**
 * Negar Shop - Single Product JavaScript Controller
 * 100% Matching Kaveh Demo 43 (Style 5 Modern Tech)
 *
 * @package Negar_Shop
 * @version 2.5.0
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initKavehGallery();
		initKavehTabs();
		initKavehCollapse();
		initSpecsShowMore();
		initKavehVotes();
		initQuestionSubmission();
	});

	/* ==========================================================================
	   1. Kaveh Product Gallery (Style 5)
	   ========================================================================== */
	function initKavehGallery() {
		var mainImg = document.querySelector('.kav-style5-main-image');
		var thumbs = document.querySelectorAll('.kav-style5-thumb');

		if (!mainImg || !thumbs.length) return;

		thumbs.forEach(function (thumb) {
			thumb.addEventListener('click', function () {
				var fullSrc = this.getAttribute('data-full-src');
				var thumbImg = this.querySelector('img');

				if (fullSrc) {
					mainImg.style.opacity = '0.3';
					mainImg.style.transition = 'opacity 0.2s ease';
					setTimeout(function () {
						mainImg.src = fullSrc;
						mainImg.setAttribute('data-large_image', fullSrc);
						mainImg.style.opacity = '1';
					}, 150);
				} else if (thumbImg && thumbImg.src) {
					mainImg.src = thumbImg.src;
				}

				thumbs.forEach(function (t) {
					t.style.borderColor = '#f2f2f2';
				});
				this.style.borderColor = '#e90544';
			});
		});
	}

	/* ==========================================================================
	   2. Kaveh Product Tabs (True Tab Switching: Info, Comments, Questions)
	   ========================================================================== */
	function initKavehTabs() {
		var tabItems = document.querySelectorAll('.kav-single-product-tabs li');
		var tabPanes = document.querySelectorAll('.kav-single-product-tab-content');

		if (!tabItems.length) return;

		function activateTab(targetSelector, shouldScroll) {
			tabItems.forEach(function (t) {
				if (t.getAttribute('data-content') === targetSelector) {
					t.classList.add('active');
				} else {
					t.classList.remove('active');
				}
			});

			tabPanes.forEach(function (pane) {
				if ('#' + pane.id === targetSelector) {
					pane.classList.add('is-active');
					pane.style.display = 'block';
				} else {
					pane.classList.remove('is-active');
					pane.style.display = 'none';
				}
			});

			if (shouldScroll) {
				var tabsBar = document.querySelector('.kav-single-product-tabs');
				if (tabsBar) {
					var offset = 100;
					var elPos = tabsBar.getBoundingClientRect().top + window.pageYOffset - offset;
					window.scrollTo({
						top: elPos,
						behavior: 'smooth'
					});
				}
			}
		}

		tabItems.forEach(function (tab) {
			tab.addEventListener('click', function () {
				var targetSelector = this.getAttribute('data-content');
				if (targetSelector) {
					activateTab(targetSelector, false);
				}
			});
		});

		// External triggers (like "مشاهده همه ویژگی ها" link)
		var specsMoreLinks = document.querySelectorAll('a[href="#info-product-tab"], .kav-single-product-specs-more');
		specsMoreLinks.forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				activateTab('#info-product-tab', true);
			});
		});

		// Sort buttons in comments and questions tabs
		var sortBtns = document.querySelectorAll('.kav-single-product-comments-sort');
		sortBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var parentGroup = this.closest('.kav-reviews2-sort');
				if (parentGroup) {
					parentGroup.querySelectorAll('.kav-single-product-comments-sort').forEach(function (b) {
						b.classList.remove('active');
					});
				}
				this.classList.add('active');
			});
		});
	}

	/* ==========================================================================
	   3. Kaveh Heading Collapse (Bootstrap Compatible)
	   ========================================================================== */
	function initKavehCollapse() {
		var headings = document.querySelectorAll('.kav-single-product-heading');

		headings.forEach(function (heading) {
			heading.addEventListener('click', function () {
				var targetId = this.getAttribute('data-bs-target');
				if (!targetId) return;

				var target = document.querySelector(targetId);
				var chevron = this.querySelector('.kav-single-product-heading-chevron');

				if (target) {
					if (target.classList.contains('show')) {
						target.classList.remove('show');
						if (chevron) chevron.style.transform = 'rotate(180deg)';
					} else {
						target.classList.add('show');
						if (chevron) chevron.style.transform = 'rotate(0deg)';
					}
				}
			});
		});
	}

	/* ==========================================================================
	   4. Specifications Show More / Less Toggle
	   ========================================================================== */
	function initSpecsShowMore() {
		var btn = document.querySelector('.kav-single-product-btn-show-more');
		var content = document.querySelector('#kav-single-product-info-product-content');

		if (!btn || !content) return;

		btn.addEventListener('click', function () {
			var isExpanded = btn.classList.contains('is-expanded');
			var spans = btn.querySelectorAll('span');
			if (isExpanded) {
				content.style.maxHeight = '464px';
				btn.classList.remove('is-expanded');
				if (spans.length >= 2) {
					spans[0].classList.remove('d-none');
					spans[1].classList.add('d-none');
				}
			} else {
				content.style.maxHeight = 'none';
				btn.classList.add('is-expanded');
				if (spans.length >= 2) {
					spans[0].classList.add('d-none');
					spans[1].classList.remove('d-none');
				}
			}
		});
	}

	/* ==========================================================================
	   5. Real AJAX Thumbs Up / Down Voting
	   ========================================================================== */
	function initKavehVotes() {
		var voteBtns = document.querySelectorAll('[data-kav-vote]');
		var ajaxUrl = (typeof kavehShopData !== 'undefined' && (kavehShopData.ajaxurl || kavehShopData.ksiteurl)) ||
		              (typeof negarShopParams !== 'undefined' && negarShopParams.ajaxUrl) ||
		              '/wp-admin/admin-ajax.php';
		var nonce = (typeof kavehShopData !== 'undefined' && kavehShopData.nonce) ||
		            (typeof negarShopParams !== 'undefined' && negarShopParams.nonce) ||
		            '';

		voteBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				if (this.classList.contains('is-voted')) return;
				var id = this.getAttribute('data-id');
				var voteType = this.getAttribute('data-vote-type') || 'like';
				var countEl = this.querySelector('.kav-vote-count');
				this.classList.add('is-voted');
				this.disabled = true;

				var formData = new URLSearchParams();
				formData.append('action', 'negar_vote_comment');
				formData.append('nonce', nonce);
				formData.append('id', id);
				formData.append('vote_type', voteType);

				fetch(ajaxUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
					body: formData.toString()
				})
				.then(function (res) { return res.json(); })
				.then(function (data) {
					if (data.success && countEl) {
						countEl.textContent = data.data.new_count;
					}
				})
				.catch(function () {});
			});
		});
	}

	/* ==========================================================================
	   6. Real AJAX Product Question Submission
	   ========================================================================== */
	function initQuestionSubmission() {
		var form = document.getElementById('kav-question-form');
		if (!form) return;

		form.addEventListener('submit', function (e) {
			e.preventDefault();

			var productId = form.getAttribute('data-product-id');
			var textArea = document.getElementById('kav-qa-text');
			var authorInput = document.getElementById('kav-qa-author');
			var msgBox = document.getElementById('kav-qa-msg');
			var submitBtn = document.getElementById('kav-qa-submit');

			var question = textArea ? textArea.value.trim() : '';
			var author = authorInput ? authorInput.value.trim() : '';

			if (!question || question.length < 5) {
				if (msgBox) {
					msgBox.textContent = 'متن پرسش باید حداقل ۵ کاراکتر باشد.';
					msgBox.className = 'mb-3 text-xs p-2.5 rounded-3 font-medium bg-danger-subtle text-danger';
					msgBox.classList.remove('hidden');
				}
				return;
			}

			if (submitBtn) submitBtn.disabled = true;

			var ajaxUrl = (typeof kavehShopData !== 'undefined' && (kavehShopData.ajaxurl || kavehShopData.ksiteurl)) ||
			              (typeof negarShopParams !== 'undefined' && negarShopParams.ajaxUrl) ||
			              '/wp-admin/admin-ajax.php';
			var nonce = (typeof kavehShopData !== 'undefined' && kavehShopData.nonce) ||
			            (typeof negarShopParams !== 'undefined' && negarShopParams.nonce) ||
			            '';

			var formData = new URLSearchParams();
			formData.append('action', 'negar_submit_product_question');
			formData.append('nonce', nonce);
			formData.append('product_id', productId);
			formData.append('question', question);
			formData.append('author', author);

			fetch(ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: formData.toString()
			})
			.then(function (res) { return res.json(); })
			.then(function (data) {
				if (submitBtn) submitBtn.disabled = false;

				if (data.success) {
					// Hide empty state if present
					var emptyNotice = document.getElementById('kav-no-questions-notice');
					if (emptyNotice) emptyNotice.remove();

					// Prepend new question to questions list
					var list = document.getElementById('kav-questions2-list');
					if (list) {
						var li = document.createElement('li');
						li.className = 'kav-questions2-item bg-white p-4 rounded-4 shadow-sm border border-slate-100';
						li.innerHTML = '<div class="content">' +
							'<div class="d-flex align-items-center justify-content-between gap-2 mb-2">' +
								'<div class="author d-flex align-items-center gap-2">' +
									'<span class="name fw-bold text-slate-800 small">' + escapeHtml(data.data.author) + '</span>' +
									'<span class="badge bg-slate-100 text-slate-600 text-3xs">کاربر</span>' +
								'</div>' +
								'<span class="text-muted text-xs">' + escapeHtml(data.data.date) + '</span>' +
							'</div>' +
							'<p class="text-slate-800 my-2 fw-medium">' + escapeHtml(data.data.question) + '</p>' +
						'</div>';
						list.prepend(li);
					}

					form.reset();

					// Close Bootstrap modal
					var modalEl = document.getElementById('modal-kav-add-question');
					if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
						var modal = bootstrap.Modal.getInstance(modalEl);
						if (modal) modal.hide();
					}

					if (typeof window.negarToast === 'function') {
						window.negarToast(data.data.message || 'پرسش شما ثبت شد.', 'success');
					}
				} else {
					if (msgBox) {
						msgBox.textContent = data.data ? data.data.message : 'خطا در ثبت پرسش';
						msgBox.className = 'mb-3 text-xs p-2.5 rounded-3 font-medium bg-danger-subtle text-danger';
						msgBox.classList.remove('hidden');
					}
				}
			})
			.catch(function () {
				if (submitBtn) submitBtn.disabled = false;
				if (msgBox) {
					msgBox.textContent = 'خطای اتصال به سرور.';
					msgBox.className = 'mb-3 text-xs p-2.5 rounded-3 font-medium bg-danger-subtle text-danger';
					msgBox.classList.remove('hidden');
				}
			});
		});
	}

	function escapeHtml(str) {
		var div = document.createElement('div');
		div.textContent = str;
		return div.innerHTML;
	}

})();
