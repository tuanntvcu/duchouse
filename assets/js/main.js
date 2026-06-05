(function ($) {
	'use strict';

	var themeConfig = window.dimhouseTheme || {};

	function intConfig(key, fallback) {
		var settings = themeConfig.sliderSettings || {};
		var value = parseInt(settings[key], 10);
		return value > 0 ? value : fallback;
	}

	function safeCall(callback) {
		try {
			callback();
		} catch (error) {
			if (window.console && window.console.warn) {
				window.console.warn('[dimhouse]', error);
			}
		}
	}

	function initLegacyAjaxBridge() {
		if (!$.ajaxPrefilter || !window.dimhouseTheme || !window.dimhouseTheme.ajaxUrl) {
			return;
		}

		$.ajaxPrefilter(function (options) {
			if (!options.url || options.url.indexOf('ajax.php') === -1) {
				return;
			}

			options.url = window.dimhouseTheme.ajaxUrl;

			if (typeof options.data === 'string') {
				if (options.data.indexOf('action=') === -1) {
					options.data += (options.data ? '&' : '') + 'action=dimhouse_legacy_ajax';
				}
				return;
			}

			options.data = $.extend({}, options.data, {
				action: 'dimhouse_legacy_ajax'
			});
		});
	}

	function alertTitle() {
		return (window.lang_js && window.lang_js.aleft_title) || 'Thông báo';
	}

	function homeText(key, fallback) {
		if (window.lang_js_mod && window.lang_js_mod.home && window.lang_js_mod.home[key]) {
			return window.lang_js_mod.home[key];
		}

		return fallback;
	}

	function showNotice(icon, message, options) {
		var settings = $.extend({
			icon: icon,
			title: icon === 'error' ? alertTitle() : message,
			text: icon === 'error' ? message : '',
			confirmButtonColor: '#231f20'
		}, options || {});

		if (window.Swal && typeof window.Swal.fire === 'function') {
			return window.Swal.fire(settings);
		}

		window.alert(message);
		return $.Deferred().resolve({ isConfirmed: true }).promise();
	}

	function confirmSubmit() {
		if (window.Swal && typeof window.Swal.fire === 'function') {
			return window.Swal.fire({
				text: homeText('confirm', 'Bạn bấm gửi là đồng ý gửi thông tin cá nhân đến chúng tôi cho việc tư vấn công trình! Dimhouse sẽ phản hồi sớm nhất.'),
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#231f20',
				cancelButtonColor: '#A39283',
				cancelButtonText: 'Hủy',
				confirmButtonText: 'Gửi'
			});
		}

		return $.Deferred().resolve({ isConfirmed: window.confirm(homeText('confirm', 'Bạn có chắc muốn gửi thông tin?')) }).promise();
	}

	function setLoading(visible) {
		if (typeof window.loading === 'function') {
			safeCall(function () {
				window.loading(visible ? 'show' : 'hide');
			});
		}
	}

	function legacyPost(action, formData) {
		return $.ajax({
			type: 'POST',
			url: themeConfig.ajaxUrl || (window.ROOT ? window.ROOT + 'ajax.php' : ''),
			dataType: 'json',
			data: {
				action: 'dimhouse_legacy_ajax',
				m: 'global',
				f: action,
				data: formData,
				lang_cur: window.lang || 'vi'
			}
		});
	}

	function fieldLabel(element) {
		var $field = $(element);
		var placeholder = ($field.attr('placeholder') || '').replace(/\*/g, '').trim();
		var label = $field.closest('.form-group').find('.label_title').first().text().trim();
		return placeholder || label || $field.attr('name') || 'trường bắt buộc';
	}

	function validationMessage(element) {
		return 'Vui lòng nhập ' + fieldLabel(element).toLowerCase() + '.';
	}

	function resetValidator($form) {
		if (!$form.length) {
			return;
		}

		$form.off('submit');
		$form.removeData('validator');
		$form.removeData('unobtrusiveValidation');
		$form.find('label.error').remove();
		$form.find('.error').removeClass('error');
	}

	function initValidationMessages() {
		if (!$.validator) {
			return;
		}

		$.extend($.validator.messages, {
			required: 'Vui lòng nhập thông tin bắt buộc.',
			email: 'Vui lòng nhập email hợp lệ.',
			number: 'Vui lòng nhập số hợp lệ.',
			min: $.validator.format('Giá trị phải lớn hơn hoặc bằng {0}.')
		});
	}

	function bindValidatedSubmit($form, submitHandler) {
		resetValidator($form);

		function runSubmit() {
			submitHandler($form);
			return false;
		}

		if ($.fn.validate) {
			$form.validate({
				ignore: ':hidden:not(.force-validate)',
				errorPlacement: function (error, element) {
					error.insertAfter(element.closest('.select').length ? element.closest('.select') : element);
				},
				invalidHandler: function (event, validator) {
					var firstError = validator.errorList && validator.errorList.length ? validator.errorList[0] : null;
					showNotice('error', firstError ? firstError.message : 'Vui lòng kiểm tra lại các thông tin bắt buộc.');
				},
				messages: {
					full_name: { required: 'Vui lòng nhập họ tên.' },
					phone: { required: 'Vui lòng nhập số điện thoại.' },
					email: { required: 'Vui lòng nhập email.' },
					time: { required: 'Vui lòng chọn thời gian tư vấn.' },
					type: { required: 'Vui lòng chọn loại công trình.' },
					short: { required: 'Vui lòng chọn nội dung tư vấn.' },
					area: { required: 'Vui lòng nhập diện tích.' },
					foundation: { required: 'Vui lòng chọn móng.' },
					foundation_area: { required: 'Vui lòng nhập diện tích móng.' },
					ground_floor: { required: 'Vui lòng nhập diện tích tầng trệt.' },
					num_bedroom: { required: 'Vui lòng nhập số phòng ngủ.' },
					num_wc: { required: 'Vui lòng nhập số phòng vệ sinh.' }
				},
				submitHandler: runSubmit
			});
			return;
		}

		$form.on('submit.dimhouseForms', function (event) {
			event.preventDefault();

			var missing = $form.find('[required]:enabled').filter(function () {
				return !$(this).val();
			}).first();

			if (missing.length) {
				showNotice('error', validationMessage(missing[0]));
				return false;
			}

			return runSubmit();
		});
	}

	function cleanupPopupLocationFields() {
		var $form = $('#form_book');
		if (!$form.length) {
			return;
		}

		$form.find('[name="provinces"], [name="districts"], [name="wards"], [name="address"]').each(function () {
			var $group = $(this).closest('.form-group');
			if ($group.length) {
				$group.remove();
			}
		});

		$form.find('.label_title.col-12').filter(function () {
			return $(this).text().trim().toLowerCase() === 'địa điểm';
		}).remove();
	}

	function cleanupFormValidationNoise() {
		$('#construction, #form_book, #form_book_procedure').find('[required]').filter(function () {
			return !$(this).attr('name');
		}).prop('required', false).removeAttr('required');
	}

	function activateEstimateTab(target) {
		var $construction = $('.construction').first();
		var $trigger = $construction.find('.nav-pills a[href="' + target + '"]').first();

		$construction.find('.nav-pills .nav-link').removeClass('active');
		$construction.find('.nav-pills .nav-item').removeClass('active');
		$trigger.addClass('active').closest('.nav-item').addClass('active');

		$construction.find('.tab-pane').removeClass('active show');
		$construction.find(target).addClass('active show');

		$construction.find('.for_result .banner').removeClass('show');
		$construction.find('.for_result .banner.' + target.replace('#', '')).addClass('show');
	}

	function refreshEstimateContent($scope) {
		$scope.find('ul.list_none').each(function () {
			var $list = $(this);
			var $checked = $list.find('input[type="radio"]:checked').first();

			if (!$checked.length) {
				$checked = $list.find('input[type="radio"]').first().prop('checked', true);
			}

			var target = $checked.data('item');
			var $contentScope = $list.closest('.row').find('.box_content').first();
			if (target && $contentScope.length) {
				$contentScope.find('.estimate-item-content').removeClass('show');
				$contentScope.find('#' + target).addClass('show');
			}
		});

		$scope.find('.box_content').each(function () {
			var $box = $(this);
			if (!$box.find('.estimate-item-content.show').length) {
				$box.find('.estimate-item-content').first().addClass('show');
			}
		});
	}

	function updatePrice(selector, value) {
		if (value) {
			$(selector).html(value);
		}
	}

	function submitConstruction($form) {
		setLoading(true);

		legacyPost('construction', $form.serializeArray()).done(function (data) {
			if (!data || data.ok != 1) {
				showNotice('error', data && data.mess ? data.mess : 'Không gửi được thông tin. Vui lòng thử lại.');
				return;
			}

			if (data.html_design_type && $('#estimate-design_type ul.list_none').length) {
				$('#estimate-design_type ul.list_none').html(data.html_design_type);
			}
			if (data.html_crude && $('#estimate-estimate_crude ul.list_none').length) {
				$('#estimate-estimate_crude ul.list_none').html(data.html_crude);
			}
			if (data.html_completion && $('#estimate-estimate_completion ul.list_none').length) {
				$('#estimate-estimate_completion ul.list_none').html(data.html_completion);
			}

			updatePrice('.construction ul.nav-pills li:first-child .price', data.tab1_price);
			updatePrice('.construction ul.nav-pills li:nth-child(2) .price', data.tab2_price);
			updatePrice('.construction ul.nav-pills li:last-child .price', data.tab3_price);

			$form.hide();
			$('.construction .tab-content').show();
			$('.construction .button_submit button').text('Tính lại').addClass('back');
			$('.construction .for_form').hide();
			$('.construction .for_result').show();

			refreshEstimateContent($('.construction .tab-content'));
			activateEstimateTab('#tab1');
		}).fail(function (xhr, status) {
			var message = status === 'parsererror'
				? 'Phản hồi máy chủ không hợp lệ. Vui lòng thử lại.'
				: 'Không kết nối được máy chủ. Vui lòng thử lại.';
			showNotice('error', message);
		}).always(function () {
			setLoading(false);
		});
	}

	function submitBookingForm($form, isPopup) {
		setLoading(true);

		legacyPost('form_book', $form.serializeArray()).done(function (data) {
			if (!data || data.ok != 1) {
				showNotice('error', data && data.mess ? data.mess : 'Không gửi được thông tin. Vui lòng thử lại.');
				return;
			}

			showNotice('success', data.mess || homeText('book_success', 'Đặt lịch thành công!'), {
				position: 'center',
				showConfirmButton: false,
				timer: 1500
			}).then(function () {
				if ($form[0]) {
					$form[0].reset();
				}
				$form.find('input, select, textarea, button').blur();
				if (isPopup && $('#book').length) {
					$('#book').modal('hide');
				}
			});
		}).fail(function (xhr, status) {
			var message = status === 'parsererror'
				? 'Phản hồi máy chủ không hợp lệ. Vui lòng thử lại.'
				: 'Không kết nối được máy chủ. Vui lòng thử lại.';
			showNotice('error', message);
		}).always(function () {
			setLoading(false);
		});
	}

	function initManagedFormSubmissions() {
		initValidationMessages();
		cleanupPopupLocationFields();
		cleanupFormValidationNoise();

		$(document).off('click', '.button_submit button:not(.back)');
		$(document).on('click.dimhouseForms', '.button_submit button:not(.back)', function (event) {
			event.preventDefault();
			confirmSubmit().then(function (result) {
				if (result.isConfirmed) {
					$('#construction').trigger('submit');
				}
			});
		});

		$(document).off('click', '#form_book .list_button button');
		$(document).on('click.dimhouseForms', '#form_book .list_button button', function (event) {
			event.preventDefault();
			confirmSubmit().then(function (result) {
				if (result.isConfirmed) {
					$('#form_book').trigger('submit');
				}
			});
		});

		$(document).off('click', '.button_submit_procedure button');
		$(document).on('click.dimhouseForms', '.button_submit_procedure button', function (event) {
			event.preventDefault();
			confirmSubmit().then(function (result) {
				if (result.isConfirmed) {
					$('#form_book_procedure').trigger('submit');
				}
			});
		});

		bindValidatedSubmit($('#construction'), submitConstruction);
		bindValidatedSubmit($('#form_book'), function ($form) {
			submitBookingForm($form, true);
		});
		bindValidatedSubmit($('#form_book_procedure'), function ($form) {
			submitBookingForm($form, false);
		});
	}

	function initTabs() {
		$(document).on('click', '[data-toggle="tab"]', function (event) {
			event.preventDefault();

			var target = $(this).attr('href');
			if (!target) {
				return;
			}

			var $trigger = $(this);
			var $container = $trigger.closest('.form, .estimate, .section');

			$trigger.closest('li').addClass('active').siblings().removeClass('active');
			$trigger.addClass('active').parent().siblings().find('.nav-link').removeClass('active');

			$container.find('.tab-pane').removeClass('active show');
			$container.find(target).addClass('active show');
		});
	}

	function initEstimateRadios() {
		$(document).on('change', '.construction input[type="radio"], .estimate input[type="radio"]', function () {
			var target = $(this).data('item');
			var $tab = $(this).closest('.tab-pane');
			var $section = $tab.length ? $tab : $(this).closest('.construction, .estimate');
			var $contentScope = $section.find('.box_content').first();
			if (!target) {
				return;
			}

			if (!$contentScope.length) {
				$contentScope = $section;
			}

			$contentScope.find('.estimate-item-content').removeClass('show');
			$contentScope.find('#' + target).addClass('show');
		});
	}

	function initQuantityControls() {
		$(document).on('click', '.btn_plus, .btn_minus', function () {
			if ($(this).closest('.choose_floor').length) {
				return;
			}

			var $group = $(this).closest('.btn_grps');
			var $input = $group.find('.quantity_text');
			var value = parseInt($input.val(), 10) || 0;
			if ($(this).hasClass('btn_plus')) {
				value += 1;
			} else {
				value = Math.max(0, value - 1);
			}
			$input.val(value).trigger('change');
		});

		$(document).on('input change', '.quantity_text', function () {
			var $input = $(this);
			var value = parseInt($input.val(), 10);
			if (isNaN(value) || value < 0) {
				value = 0;
			}
			$input.val(value);
		});
	}

	function initFloatingContactLinks() {
		var hardcodedFacebookUrl = 'https://www.facebook.com/DimHouse.DimDecor';

		$('#dimhouse-facebook-contact-link-v2').each(function () {
			var link = this;
			link.href = hardcodedFacebookUrl;
			link.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				if (typeof event.stopImmediatePropagation === 'function') {
					event.stopImmediatePropagation();
				}
				window.open(hardcodedFacebookUrl, '_blank', 'noopener,noreferrer');
				return false;
			}, true);
		});

		$('.hotline.sticky .ring > a').each(function () {
			var link = this;
			var $link = $(link);

			if ($link.data('dimhouseFloatingContactBound')) {
				return;
			}

			$link.data('dimhouseFloatingContactBound', true);
			if ($link.closest('.hotline.sticky.facebook').length) {
				link.href = hardcodedFacebookUrl;
				link.addEventListener('click', function (event) {
					event.preventDefault();
					event.stopPropagation();
					if (typeof event.stopImmediatePropagation === 'function') {
						event.stopImmediatePropagation();
					}
					window.open(hardcodedFacebookUrl, '_blank', 'noopener,noreferrer');
					return false;
				}, true);
			}

			link.addEventListener('click', function (event) {
				event.stopPropagation();
				if (typeof event.stopImmediatePropagation === 'function') {
					event.stopImmediatePropagation();
				}
			});
		});
	}

	function initPagePostHeaderMenu() {
		var boundKey = 'dimhousePagePostHeaderMenuBound';
		if ($(document).data(boundKey)) {
			return;
		}

		$(document).data(boundKey, true);

		function setMenuOpen(open, toggle) {
			$('.sideMenu, .overlay_bo').toggleClass('open', open);
			$('.dimhouse-page-post-menu-toggle').toggleClass('change', open).attr('aria-expanded', open ? 'true' : 'false');
			if (toggle) {
				$(toggle).toggleClass('change', open).attr('aria-expanded', open ? 'true' : 'false');
			}
		}

		document.addEventListener('click', function (event) {
			var toggle = event.target.closest ? event.target.closest('.dimhouse-page-post-menu-toggle') : null;
			if (!toggle) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();
			if (typeof event.stopImmediatePropagation === 'function') {
				event.stopImmediatePropagation();
			}

			setMenuOpen(!$('.sideMenu').hasClass('open'), toggle);
			return false;
		}, true);

		$(document).on('click.dimhousePagePostHeader', '.overlay_bo', function () {
			setMenuOpen(false);
		});

		$(document).on('click.dimhousePagePostHeader', '.sideMenu.open .menu_li a, .sideMenu.open .nav-item:not(.dropdown) a', function () {
			setMenuOpen(false);
		});
	}

	function initFullPage() {
		if ($.fn.fullpage && $('#fullpage').length && $(window).width() > 1300) {
			var $fullpage = $('#fullpage');

			if ($('html').hasClass('fp-enabled') && $.fn.fullpage.destroy) {
				$.fn.fullpage.destroy('all');
			}

			if ($('html').hasClass('fp-enabled')) {
				return;
			}

			$fullpage.removeClass('fullpage-wrapper fp-destroyed fp-notransition').removeAttr('style');
			$fullpage.children('.section').removeAttr('data-anchor');
			$('body').removeClass(function (index, className) {
				var matches = className.match(/\bfp-viewing-\S+/g);
				return matches ? matches.join(' ') : '';
			});

			var anchors = [];
			var usedAnchors = {};

			function uniqueAnchor(base) {
				var anchor = base;
				var index = 2;

				while (usedAnchors[anchor]) {
					anchor = base + '-' + index;
					index += 1;
				}

				usedAnchors[anchor] = true;
				return anchor;
			}

			$fullpage.children('.section').each(function (index) {
				var className = this.className || '';
				var match = className.match(/\bsection-(\d+)\b/);
				var base = match ? 'dimhouse-section-' + match[1] : 'dimhouse-section-' + (index + 1);

				if (className.indexOf('villa-designs-section') !== -1) {
					base = 'dimhouse-villa-designs';
				}

				anchors.push(uniqueAnchor(base));
			});

			$fullpage.children('footer.section-7').addClass('fp-auto-height');
			$fullpage.fullpage({
				anchors: anchors,
				slidesNavigation: true,
				controlArrows: true,
				fitToSection: false,
				lockAnchors: true,
				recordHistory: false,
				animateAnchor: false,
				afterRender: function () {
					refreshFullPageScrollables();
				},
				afterReBuild: function () {
					refreshFullPageScrollables();
				},
				afterResize: function () {
					refreshFullPageScrollables();
				}
			});
			bindFullPageScrollables();
		}
	}

	function cleanUndefinedHash() {
		$('a[href*="#undefined"]').each(function () {
			var href = $(this).attr('href');
			if (href) {
				$(this).attr('href', href.replace(/#undefined\b/g, ''));
			}
		});

		if (window.location.hash === '#undefined' && window.history && window.history.replaceState) {
			window.history.replaceState(null, document.title, window.location.pathname + window.location.search);
		}
	}

	function refreshFullPageScrollables() {
		$('#fullpage > .section').each(function () {
			var $section = $(this);
			var $cell = $section.children('.fp-tableCell').first();

			if (!$cell.length) {
				return;
			}

			var cell = $cell.get(0);
			var section = $section.get(0);
			var visibleHeight = Math.max(1, $('#fullpage > .section').first().height() || $(window).height());
			var contentHeight = Math.max(cell.scrollHeight, cell.offsetHeight, section.scrollHeight);
			var isScrollable = contentHeight > visibleHeight + 2;

			$section.toggleClass('dimhouse-section-scrollable', isScrollable);
			$cell.toggleClass('dimhouse-fp-scrollable', isScrollable);
		});
	}

	function bindFullPageScrollables() {
		$(document)
			.off('wheel.dimhouseFullPageScroll')
			.on('wheel.dimhouseFullPageScroll', '#fullpage .dimhouse-fp-scrollable', function (event) {
				var cell = this;
				var original = event.originalEvent || {};
				var delta = original.deltaY || -original.wheelDelta || original.detail || 0;
				var maxScroll = cell.scrollHeight - cell.clientHeight;

				if (maxScroll <= 2) {
					return;
				}

				var atTop = cell.scrollTop <= 0;
				var atBottom = cell.scrollTop + cell.clientHeight >= maxScroll - 2;

				if ((delta < 0 && !atTop) || (delta > 0 && !atBottom)) {
					event.stopPropagation();
				}
			});
	}

	function initSlick() {
		if (!$.fn.slick) {
			return;
		}

		var productSlides = intConfig('productSlides', 4);
		var articleSlides = intConfig('articleSlides', 4);
		var testimonialSlides = intConfig('testimonialSlides', 3);
		var partnerSlides = intConfig('partnerSlides', 5);

		function mount(selector, options) {
			$(selector).each(function () {
				var $slider = $(this);
				if (!$slider.hasClass('slick-initialized')) {
					$slider.slick(options);
				}
			});
		}

		mount('.js-slick', {
			autoplay: true,
			autoplaySpeed: 4000,
			dots: false,
			arrows: false,
			pauseOnHover: true
		});

		mount('.focus_product .row_item, .list_item_product .row_item', {
			arrows: true,
			dots: false,
			infinite: false,
			autoplay: false,
			autoplaySpeed: 3500,
			speed: 500,
			slidesToShow: productSlides,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: Math.min(productSlides, 4) } },
				{ breakpoint: 993, settings: { slidesToShow: Math.min(productSlides, 3) } },
				{ breakpoint: 601, settings: { slidesToShow: 2 } },
				{ breakpoint: 330, settings: { slidesToShow: 1 } }
			]
		});

		mount('.focus_page .row_item', {
			arrows: true,
			dots: false,
			infinite: false,
			autoplay: false,
			autoplaySpeed: 3500,
			speed: 500,
			slidesToShow: articleSlides,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: Math.min(articleSlides, 4) } },
				{ breakpoint: 993, settings: { slidesToShow: Math.min(articleSlides, 3) } },
				{ breakpoint: 601, settings: { slidesToShow: 2 } },
				{ breakpoint: 330, settings: { slidesToShow: 1 } }
			]
		});

		mount('.box_comment .list_item_project', {
			arrows: true,
			dots: false,
			infinite: true,
			autoplay: false,
			autoplaySpeed: 3500,
			speed: 500,
			slidesToShow: testimonialSlides,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: Math.min(testimonialSlides, 2) } },
				{ breakpoint: 769, settings: { slidesToShow: Math.min(testimonialSlides, 2) } },
				{ breakpoint: 601, settings: { slidesToShow: 1 } },
				{ breakpoint: 365, settings: { slidesToShow: 1 } }
			]
		});

		mount('.brand_scroll-content', {
			arrows: false,
			dots: false,
			infinite: true,
			autoplay: true,
			autoplaySpeed: 3500,
			speed: 500,
			slidesToShow: partnerSlides,
			swipeToSlide: true,
			responsive: [
				{ breakpoint: 993, settings: { slidesToShow: Math.min(partnerSlides, 4), slidesToScroll: 3 } },
				{ breakpoint: 501, settings: { slidesToShow: 2, slidesToScroll: 2 } }
			]
		});

		mount('.dg-wrapper', {
			slidesToShow: 4,
			dots: false,
			arrows: true,
			swipeToSlide: true,
			focusOnSelect: true,
			autoplay: false,
			responsive: [
				{ breakpoint: 823, settings: { slidesToShow: 3, slidesToScroll: 2 } },
				{ breakpoint: 576, settings: { slidesToShow: 2, slidesToScroll: 1 } }
			]
		});

		if (window.matchMedia('(max-width: 600px)').matches) {
			mount('.section-service .row', {
				arrows: true,
				dots: false,
				infinite: true,
				autoplay: true,
				autoplaySpeed: 3500,
				speed: 500,
				slidesToShow: 2,
				swipeToSlide: true,
				lazyload: 'ondemand'
			});
		}
	}

	function initLazyImages() {
		var images = document.querySelectorAll('img[data-src]');
		images.forEach(function (img) {
			if (!img.getAttribute('src') || img.getAttribute('src').indexOf('spin.svg') !== -1) {
				img.setAttribute('src', img.getAttribute('data-src'));
			}
		});
	}

	function initClonePlugins() {
		if (window.imsUser && typeof window.imsUser.check_order === 'function') {
			safeCall(function () {
				window.imsUser.check_order('check_order');
			});
		}

		if (window.imsLocation && typeof window.imsLocation.locationChange === 'function') {
			safeCall(function () {
				window.imsLocation.locationChange('province', '.select_location_province');
				window.imsLocation.locationChange('district', '.select_location_district');
				window.imsLocation.locationChange('province', '.select_location_provinces');
				window.imsLocation.locationChange('district', '.select_location_districts');
				window.imsLocation.locationChange('province', '.select_location_province_procedure');
				window.imsLocation.locationChange('district', '.select_location_district_procedure');
			});
		}

		if ($.fn.datetimepicker) {
			safeCall(function () {
				$('#time_procedure, #time').each(function () {
					var $field = $(this);
					if (!$field.data('dimhouse-datetimepicker')) {
						$field.datetimepicker({
							dateFormat: 'dd/mm/yy',
							timeFormat: 'HH:mm',
							controlType: 'select',
							oneLine: true
						});
						$field.data('dimhouse-datetimepicker', true);
					}
				});
			});
		}

		if (window.imsOrdering && typeof window.imsOrdering.add_cart === 'function') {
			safeCall(function () {
				window.imsOrdering.add_cart('form.form_add_cart');
			});
		}

		if (window.imsProduct && typeof window.imsProduct.load_more === 'function') {
			$(document).on('click', '.btn_viewmore button', function () {
				safeCall(function () {
					window.imsProduct.load_more();
				});
			});
		}

		if (window.imsGlobal && typeof window.imsGlobal.emaillist === 'function') {
			safeCall(function () {
				window.imsGlobal.emaillist('form_res_email');
			});
		}

		if ($.fn.fancybox) {
			safeCall(function () {
				$('.fancybox_popup').fancybox({
					padding: 0,
					maxWidth: 800,
					maxHeight: 600,
					fitToView: false,
					width: '70%',
					height: '70%',
					autoSize: false,
					closeClick: false,
					openEffect: 'none',
					closeEffect: 'none',
					closeBtn: false,
					wrapCSS: 'popup_custom'
				});
				if (themeConfig.popupAutoOpen !== false && window.location.search.indexOf('nopopup=1') === -1) {
					setTimeout(function () {
						$('#click_popup').trigger('click');
					}, 300);
				}
				$(document).on('click', '#popup_banner_a button.close', function () {
					$.fancybox.close();
				});
			});
		} else {
			$('#popup_banner').hide();
		}

		if (typeof window.header_cart === 'function') {
			safeCall(function () {
				window.header_cart();
			});
		}
	}

	initLegacyAjaxBridge();

	$(function () {
		cleanUndefinedHash();
		initFullPage();
		initClonePlugins();
		initTabs();
		initEstimateRadios();
		initQuantityControls();
		initManagedFormSubmissions();
		initFloatingContactLinks();
		initPagePostHeaderMenu();
		initSlick();
		initLazyImages();
		if ($.fn.fullpage && $.fn.fullpage.reBuild) {
			$.fn.fullpage.reBuild();
			refreshFullPageScrollables();
		}
		setTimeout(refreshFullPageScrollables, 500);
		setTimeout(refreshFullPageScrollables, 1500);
	});
})(jQuery);
