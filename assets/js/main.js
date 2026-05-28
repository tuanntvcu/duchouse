(function ($) {
	'use strict';

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
			var $section = $(this).closest('.construction, .estimate');
			if (!target) {
				return;
			}

			$section.find('.estimate-item-content').removeClass('show');
			$section.find('#' + target).addClass('show');
		});
	}

	function initQuantityControls() {
		$(document).on('click', '.btn_plus, .btn_minus', function () {
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

	function initFullPage() {
		if ($.fn.fullpage && $('#fullpage').length && !$('html').hasClass('fp-enabled') && $(window).width() > 1300) {
			$('#fullpage').fullpage({
				slidesNavigation: true,
				controlArrows: true
			});
		}
	}

	function initSlick() {
		if (!$.fn.slick) {
			return;
		}

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
			slidesToShow: 4,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: 4 } },
				{ breakpoint: 993, settings: { slidesToShow: 3 } },
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
			slidesToShow: 4,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: 4 } },
				{ breakpoint: 993, settings: { slidesToShow: 3 } },
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
			slidesToShow: 3,
			swipeToSlide: true,
			lazyload: 'ondemand',
			responsive: [
				{ breakpoint: 1101, settings: { slidesToShow: 2 } },
				{ breakpoint: 769, settings: { slidesToShow: 2 } },
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
			slidesToShow: 5,
			swipeToSlide: true,
			responsive: [
				{ breakpoint: 993, settings: { slidesToShow: 4, slidesToScroll: 3 } },
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
				if (window.location.search.indexOf('nopopup=1') === -1) {
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
		initClonePlugins();
		initTabs();
		initEstimateRadios();
		initQuantityControls();
		initFullPage();
		initSlick();
		initLazyImages();
	});
})(jQuery);
