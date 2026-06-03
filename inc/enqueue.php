<?php

if (!defined('ABSPATH')) {
	exit;
}

function dimhouse_enqueue_assets() {
	$theme_version = wp_get_theme()->get('Version');
	$style_path = get_stylesheet_directory() . '/style.css';
	$style_version = file_exists($style_path) ? filemtime($style_path) : $theme_version;
	$main_script_path = get_theme_file_path('/assets/js/main.js');
	$main_script_version = file_exists($main_script_path) ? filemtime($main_script_path) : $theme_version;

	wp_enqueue_style('dimhouse-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap', array(), null);
	wp_enqueue_style('dimhouse-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), null);
	wp_enqueue_style('dimhouse-clone', dimhouse_asset_uri('clone.css'), array(), $theme_version);
	wp_enqueue_style('dimhouse-style', get_stylesheet_uri(), array('dimhouse-clone'), $style_version);

	$google_tag_id = trim((string) dimhouse_option('google_tag_id', ''));
	if ($google_tag_id !== '') {
		wp_enqueue_script('dimhouse-gtag', 'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode($google_tag_id), array(), null, array('strategy' => 'async', 'in_footer' => false));
		wp_add_inline_script(
			'dimhouse-gtag',
			'window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag("js", new Date()); gtag("config", ' . wp_json_encode($google_tag_id) . ');'
		);
	}

	$google_ads_client = trim((string) dimhouse_option('google_ads_client', ''));
	if ($google_ads_client !== '') {
		wp_enqueue_script('dimhouse-adsbygoogle', 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . rawurlencode($google_ads_client), array(), null, array('strategy' => 'async', 'in_footer' => false));
		wp_script_add_data('dimhouse-adsbygoogle', 'crossorigin', 'anonymous');
	}

	wp_enqueue_script('jquery');
	wp_enqueue_script('dimhouse-legacy-bundle', dimhouse_asset_uri('resources/minify/minify.jquery.min.js'), array('jquery'), $theme_version, true);
	wp_enqueue_script('dimhouse-ordering', dimhouse_asset_uri('modules/product/assets/default/js/ordering.js'), array('jquery'), $theme_version, true);
	wp_enqueue_script('dimhouse-user', dimhouse_asset_uri('modules/user/assets/default/js/user.js'), array('jquery'), $theme_version, true);
	wp_enqueue_script('dimhouse-timepicker', dimhouse_asset_uri('resources/js/jquery_ui/jquery-ui-timepicker-addon.min.js'), array('jquery'), $theme_version, true);
	wp_enqueue_script('dimhouse-lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@2.0.12/dist/lottie-player.js', array(), '2.0.12', true);
	wp_enqueue_script('dimhouse-main', dimhouse_asset_uri('assets/js/main.js'), array('jquery', 'dimhouse-legacy-bundle', 'dimhouse-ordering', 'dimhouse-user', 'dimhouse-timepicker'), $main_script_version, true);

	$alert_title = dimhouse_option('alert_title', 'Thông báo');
	$send_label = dimhouse_option('send_label', 'Gửi thông tin');
	$form_confirm_message = dimhouse_option('form_confirm_message', 'Bạn bấm gửi là đồng ý gửi thông tin cá nhân đến chúng tôi cho việc tư vấn công trình! Dimhouse sẽ phản hồi sớm nhất.');
	$form_success_message = dimhouse_option('form_success_message', 'Đặt lịch thành công!');
	wp_add_inline_script(
		'dimhouse-legacy-bundle',
		'var ROOT = ' . wp_json_encode(home_url('/')) . ';' .
		'var DIR_IMAGE = ' . wp_json_encode(dimhouse_asset_uri('resources/images/')) . ';' .
		'var deviceType = "phone"; var lang = "vi"; var lang_js = []; var lang_js_mod = []; lang_js_mod.home = [];' .
		'lang_js.aleft_title = ' . wp_json_encode($alert_title) . '; lang_js.send = ' . wp_json_encode($send_label) . '; lang_js_mod.home.confirm = ' . wp_json_encode($form_confirm_message) . '; lang_js_mod.home.book_success = ' . wp_json_encode($form_success_message) . ';',
		'before'
	);

	$clone_footer_scripts = dimhouse_index_footer_inline_scripts();
	if ($clone_footer_scripts) {
		wp_add_inline_script('dimhouse-main', $clone_footer_scripts, 'before');
	}

	$slider_settings = array(
		'productSlides' => max(1, absint(dimhouse_option('product_slider_slides', 4))),
		'articleSlides' => max(1, absint(dimhouse_option('article_slider_slides', 4))),
		'testimonialSlides' => max(1, absint(dimhouse_option('testimonial_slider_slides', 3))),
		'partnerSlides' => max(1, absint(dimhouse_option('partner_slider_slides', 5))),
	);

	wp_localize_script('dimhouse-main', 'dimhouseTheme', array(
		'homeUrl' => home_url('/'),
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'assetBase' => trailingslashit(get_stylesheet_directory_uri()),
		'lang' => 'vi',
		'popupAutoOpen' => (bool) dimhouse_option('popup_auto_open', 1),
		'sliderSettings' => $slider_settings,
	));
}
add_action('wp_enqueue_scripts', 'dimhouse_enqueue_assets');

function dimhouse_enqueue_admin_assets() {
	wp_enqueue_style('dimhouse-admin', dimhouse_asset_uri('assets/css/theme.css'), array(), wp_get_theme()->get('Version'));
}
add_action('admin_enqueue_scripts', 'dimhouse_enqueue_admin_assets');
