<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('get_sub_field')) {
	function get_sub_field($selector) {
		return null;
	}
}

if (!function_exists('have_rows')) {
	function have_rows($selector, $post_id = false) {
		return false;
	}
}

if (!function_exists('the_row')) {
	function the_row($format = false) {
		return null;
	}
}

function dimhouse_asset_uri($relative_path) {
	$relative_path = ltrim($relative_path, '/\\');
	$theme_path = get_theme_file_path('/' . $relative_path);

	if (file_exists($theme_path)) {
		return get_theme_file_uri('/' . $relative_path);
	}

	return home_url('/' . $relative_path);
}

function dimhouse_asset_exists($relative_path) {
	$relative_path = ltrim($relative_path, '/\\');
	return file_exists(get_theme_file_path('/' . $relative_path)) || file_exists(ABSPATH . $relative_path);
}

function dimhouse_option($field_name, $default = '') {
	if (function_exists('get_field')) {
		$value = get_field($field_name, 'option');
		if ($value !== null && $value !== false && $value !== '') {
			return $value;
		}
	}

	return $default;
}

function dimhouse_image_url($image, $size = 'full') {
	if (empty($image)) {
		return '';
	}

	if (is_array($image)) {
		if (!empty($image['sizes'][$size])) {
			return $image['sizes'][$size];
		}

		if (!empty($image['url'])) {
			return $image['url'];
		}
	}

	if (is_numeric($image)) {
		$url = wp_get_attachment_image_url((int) $image, $size);
		if ($url) {
			return $url;
		}
	}

	if (is_string($image)) {
		return $image;
	}

	return '';
}

function dimhouse_image_html($image, $size = 'full', $attr = array()) {
	if (empty($image)) {
		return '';
	}

	if (is_numeric($image)) {
		return wp_get_attachment_image((int) $image, $size, false, $attr);
	}

	if (is_array($image) && !empty($image['ID'])) {
		return wp_get_attachment_image((int) $image['ID'], $size, false, $attr);
	}

	$url = dimhouse_image_url($image, $size);
	if (!$url) {
		return '';
	}

	$attributes = array_merge(
		array(
			'src' => $url,
			'alt' => '',
		),
		$attr
	);

	$html = '<img';
	foreach ($attributes as $key => $value) {
		if ($value === '' && $key !== 'alt') {
			continue;
		}
		$html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
	}
	$html .= '>';

	return $html;
}

function dimhouse_link_url($link) {
	if (empty($link)) {
		return '';
	}

	if (is_array($link)) {
		return !empty($link['url']) ? $link['url'] : '';
	}

	return is_string($link) ? $link : '';
}

function dimhouse_link_title($link, $default = '') {
	if (is_array($link) && !empty($link['title'])) {
		return $link['title'];
	}

	return $default;
}

function dimhouse_get_menu_args($location = 'primary') {
	return array(
		'theme_location' => $location,
		'container' => false,
		'fallback_cb' => '__return_false',
		'depth' => 2,
		'walker' => class_exists('Dimhouse_Bootstrap_Walker_Nav_Menu') ? new Dimhouse_Bootstrap_Walker_Nav_Menu() : '',
	);
}

function dimhouse_social_links($context = 'header') {
	$links = array();
	if (!function_exists('have_rows')) {
		return dimhouse_default_social_links();
	}

	if (have_rows($context . '_social_links', 'option')) {
		while (have_rows($context . '_social_links', 'option')) {
			the_row();
			$links[] = array(
				'label' => get_sub_field('label'),
				'url' => get_sub_field('url'),
				'icon' => get_sub_field('icon'),
				'image' => get_sub_field('image'),
			);
		}
	}

	return !empty($links) ? $links : dimhouse_default_social_links();
}

function dimhouse_default_social_links() {
	return array(
		array(
			'label' => 'Instagram',
			'url' => 'https://www.instagram.com/_dim.decor',
			'icon' => 'instagram',
		),
		array(
			'label' => 'Facebook',
			'url' => 'https://www.facebook.com/DimHouse.DimDecor',
			'icon' => 'facebook',
		),
		array(
			'label' => 'Tiktok',
			'url' => 'https://www.tiktok.com/@ducdimhouse',
			'icon' => 'tiktok',
		),
		array(
			'label' => 'Youtube',
			'url' => 'https://www.youtube.com/@DimHouse-l9e',
			'icon' => 'youtube',
		),
	);
}

function dimhouse_icon_key($value) {
	if (is_array($value)) {
		$value = !empty($value['url']) ? $value['url'] : (!empty($value['filename']) ? $value['filename'] : '');
	}

	$value = strtolower((string) $value);
	if (strpos($value, 'instagram') !== false) {
		return 'instagram';
	}
	if (strpos($value, 'facebook') !== false || strpos($value, 'fb') !== false) {
		return 'facebook';
	}
	if (strpos($value, 'tiktok') !== false || strpos($value, 'tik tok') !== false) {
		return 'tiktok';
	}
	if (strpos($value, 'youtube') !== false) {
		return 'youtube';
	}
	if (strpos($value, 'hotline') !== false || strpos($value, 'phone') !== false || strpos($value, 'tel') !== false) {
		return 'phone';
	}
	if (strpos($value, 'mail') !== false || strpos($value, 'email') !== false) {
		return 'email';
	}
	if (strpos($value, 'business') !== false || strpos($value, 'office') !== false || strpos($value, 'company') !== false || strpos($value, 'kinh doanh') !== false || strpos($value, 'address') !== false || strpos($value, 'location') !== false || strpos($value, 'lh-dc') !== false) {
		return 'business';
	}

	return '';
}

function dimhouse_icon_svg($icon, $class = '', $width = 24, $height = 24) {
	$key = dimhouse_icon_key($icon);
	$class = trim('dimhouse-svg-icon ' . $class);
	$attrs = ' class="' . esc_attr($class) . '" width="' . esc_attr((string) $width) . '" height="' . esc_attr((string) $height) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false"';

	switch ($key) {
		case 'instagram':
			$body = '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.3" cy="6.7" r="1.35" fill="currentColor"/>';
			break;
		case 'facebook':
			$attrs = ' class="' . esc_attr($class) . '" width="' . esc_attr((string) $width) . '" height="' . esc_attr((string) $height) . '" viewBox="-337 273 123.5 256" aria-hidden="true" focusable="false"';
			$body = '<path fill="currentColor" d="M-260.9,327.8c0-10.3,9.2-14,19.5-14c10.3,0,21.3,3.2,21.3,3.2l6.6-39.2c0,0-14-4.8-47.4-4.8c-20.5,0-32.4,7.8-41.1,19.3c-8.2,10.9-8.5,28.4-8.5,39.7v25.7H-337V396h26.5v133h49.6V396h39.3l2.9-38.3h-42.2V327.8z"/>';
			break;
		case 'tiktok':
			$body = '<path fill="currentColor" d="M16.6 2.3c.4 2.8 2 4.5 4.6 4.7v4.1c-1.5.1-2.9-.3-4.5-1.2v6.6c0 8.4-9.2 11-13 5-2.4-3.8-.9-10.4 6.8-10.7v4.3c-.5.1-1 .2-1.5.4-1.4.5-2.2 1.5-2 3.1.4 3 6 3.9 5.5-2V2.3h4.1z"/>';
			break;
		case 'youtube':
			$body = '<path fill="currentColor" d="M22.5 7.2a3 3 0 0 0-2.1-2.1C18.5 4.6 12 4.6 12 4.6s-6.5 0-8.4.5a3 3 0 0 0-2.1 2.1C1 9.1 1 12 1 12s0 2.9.5 4.8a3 3 0 0 0 2.1 2.1c1.9.5 8.4.5 8.4.5s6.5 0 8.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-4.8.5-4.8s0-2.9-.5-4.8zM9.8 15.3V8.7L15.6 12l-5.8 3.3z"/>';
			break;
		case 'phone':
			$body = '<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8.2 6.8 10 6.2l1.5 3-1.2 1.2c.8 1.7 2.1 3.1 3.8 3.8l1.2-1.2 3 1.5-.6 1.8c-.2.6-.8 1-1.4 1C9.9 17.3 6.7 14.1 6.7 7.8c0-.6.4-1.2 1-1.4z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>';
			break;
		case 'email':
			$body = '<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M6.5 8.5h11v7h-11z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/><path d="m7 9 5 4 5-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>';
			break;
		case 'business':
			$body = '<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 18V7.5h8V18" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/><path d="M10.2 10h.1M13.7 10h.1M10.2 12.7h.1M13.7 12.7h.1M10 18v-3h4v3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>';
			break;
		default:
			return '';
	}

	return '<svg' . $attrs . '>' . $body . '</svg>';
}

function dimhouse_social_icon_key($link) {
	$candidates = array(
		!empty($link['icon']) ? $link['icon'] : '',
		!empty($link['label']) ? $link['label'] : '',
		!empty($link['image']) ? $link['image'] : '',
	);

	foreach ($candidates as $candidate) {
		$key = dimhouse_icon_key($candidate);
		if ($key) {
			return $key;
		}
	}

	return '';
}

function dimhouse_render_social_icon($link) {
	$key = dimhouse_social_icon_key($link);
	$sizes = array(
		'instagram' => array(51, 51),
		'facebook' => array(26, 50),
		'tiktok' => array(44, 51),
		'youtube' => array(46, 54),
	);

	if ($key && !empty($sizes[$key])) {
		return dimhouse_icon_svg($key, 'dimhouse-social-icon dimhouse-social-icon-' . $key, $sizes[$key][0], $sizes[$key][1]);
	}

	return dimhouse_image_html(!empty($link['image']) ? $link['image'] : '', 'thumbnail', array('alt' => !empty($link['label']) ? $link['label'] : 'Social'));
}

function dimhouse_render_social_links_html($context = 'header') {
	$social_html = '';
	foreach (dimhouse_social_links($context) as $link) {
		if (empty($link['url'])) {
			continue;
		}

		$label = !empty($link['label']) ? $link['label'] : 'Social';
		$social_html .= '<a href="' . esc_url($link['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($label) . '">' .
			dimhouse_render_social_icon($link) .
			'</a>';
	}

	return $social_html;
}

function dimhouse_contact_icon_svg($icon, $index = 0) {
	$fallback_icons = array('phone', 'facebook', 'email', 'business');
	$key = dimhouse_icon_key($icon);
	if (!$key) {
		$key = !empty($fallback_icons[$index]) ? $fallback_icons[$index] : 'business';
	}

	return dimhouse_icon_svg($key, 'dimhouse-contact-icon dimhouse-contact-icon-' . $key, 80, 80);
}

function dimhouse_replace_clone_contact_icon_images($html) {
	$icons = array(
		'lh-hotline.png' => array('phone', 0),
		'lh-fb.png' => array('facebook', 1),
		'lh-mail.png' => array('email', 2),
		'lh-dc.png' => array('business', 3),
	);

	foreach ($icons as $filename => $icon) {
		$updated = preg_replace(
			'#<img\s+src="[^"]*/uploads/about/2022_04/' . preg_quote($filename, '#') . '"[^>]*>#',
			dimhouse_contact_icon_svg($icon[0], $icon[1]),
			$html
		);
		if (is_string($updated)) {
			$html = $updated;
		}
	}

	return $html;
}

function dimhouse_option_repeater($field_name, $default = array()) {
	$value = dimhouse_option($field_name, array());
	return is_array($value) && !empty($value) ? $value : $default;
}

function dimhouse_default_footer_partners() {
	return array(
		array('image' => dimhouse_asset_uri('uploads/banner/baner/khuyen-mai-juno.png'), 'url' => '#', 'alt' => 'BRAND'),
		array('image' => dimhouse_asset_uri('uploads/banner/baner/logo-ngang.png'), 'url' => '', 'alt' => 'BRAND'),
		array('image' => dimhouse_asset_uri('uploads/banner/baner/logokatinat.jpg'), 'url' => '', 'alt' => 'BRAND'),
		array('image' => dimhouse_asset_uri('uploads/banner/baner/295762826_454329143369678_112143727416609569_n.jpg'), 'url' => '', 'alt' => 'BRAND'),
		array('image' => dimhouse_asset_uri('uploads/banner/baner/455778-kia-devoile-un-nouveau-logo-et-un-nouveau-slogan.jpg'), 'url' => '', 'alt' => 'BRAND'),
	);
}

function dimhouse_default_mobile_menu_items() {
	return array(
		array('label' => 'Kiến Trúc', 'icon' => dimhouse_asset_uri('uploads/layout/2024_04/kientruc.png'), 'url' => home_url('/thiet-ke-kien-truc')),
		array('label' => 'Nội Thất', 'icon' => dimhouse_asset_uri('uploads/layout/2024_04/noithat.png'), 'url' => home_url('/du-an-noi-that-2')),
		array('label' => 'Thi Công', 'icon' => dimhouse_asset_uri('uploads/layout/2024_04/congtrinh.png'), 'url' => home_url('/du-an-thi-cong-1')),
		array('label' => 'Bộ Sưu Tập', 'icon' => dimhouse_asset_uri('uploads/layout/2024_04/bosuutap.png'), 'url' => home_url('/bo-suu-tap')),
	);
}

function dimhouse_default_side_menu_items() {
	return array_map(
		function ($item) {
			return array(
				'label' => !empty($item['label']) ? $item['label'] : '',
				'url' => !empty($item['url']) ? $item['url'] : '',
			);
		},
		dimhouse_default_mobile_menu_items()
	);
}

function dimhouse_side_menu_items() {
	if (function_exists('dimhouse_should_render_page_post_header') && dimhouse_should_render_page_post_header()) {
		return array_map(
			function ($item) {
				return array(
					'label' => !empty($item['label']) ? $item['label'] : '',
					'url' => !empty($item['url']) ? $item['url'] : '',
				);
			},
			dimhouse_page_post_header_menu_items()
		);
	}

	$items = dimhouse_option_repeater('side_menu_items');
	if (!empty($items)) {
		return $items;
	}

	$menu_grid = dimhouse_home_acf_layout('menu_grid');
	if (!empty($menu_grid['cards']) && is_array($menu_grid['cards'])) {
		$items = array();
		foreach ($menu_grid['cards'] as $card) {
			if (empty($card['title']) && empty($card['url'])) {
				continue;
			}
			$items[] = array(
				'label' => !empty($card['title']) ? $card['title'] : '',
				'url' => !empty($card['url']) ? $card['url'] : '',
			);
		}
		if (!empty($items)) {
			return $items;
		}
	}

	return dimhouse_default_side_menu_items();
}

function dimhouse_mobile_menu_items() {
	$items = dimhouse_option_repeater('mobile_menu_items');
	if (!empty($items)) {
		return $items;
	}

	return dimhouse_default_mobile_menu_items();
}

function dimhouse_default_page_post_header_menu_items() {
	return array(
		array('label' => 'Thiết Kế Kiến Trúc', 'url' => home_url('/thiet-ke-kien-truc'), 'new_tab' => 0),
		array('label' => 'Thiết Kế Nội Thất', 'url' => home_url('/du-an-noi-that-2'), 'new_tab' => 0),
		array('label' => 'Công Trình Thi Công', 'url' => home_url('/du-an-thi-cong-1'), 'new_tab' => 0),
		array('label' => 'Bộ Sưu Tập', 'url' => home_url('/bo-suu-tap'), 'new_tab' => 0),
	);
}

function dimhouse_page_post_header_menu_items() {
	$items = dimhouse_option_repeater('page_post_header_menu_items');
	if (!empty($items)) {
		return $items;
	}

	return dimhouse_default_page_post_header_menu_items();
}

function dimhouse_default_page_post_header_logo() {
	return dimhouse_asset_uri('uploads/banner/baner/logo.jpg');
}

function dimhouse_page_post_header_enabled() {
	if (!function_exists('get_field')) {
		return true;
	}

	$value = get_field('page_post_header_enabled', 'option');
	if ($value === null || $value === '') {
		return true;
	}

	return (bool) $value;
}

function dimhouse_should_render_page_post_header() {
	if (!dimhouse_page_post_header_enabled() || is_front_page() || is_home()) {
		return false;
	}

	return is_page() || is_singular('post');
}

function dimhouse_render_page_post_header_html() {
	if (!dimhouse_should_render_page_post_header()) {
		return '';
	}

	$logo = dimhouse_option('page_post_header_logo');
	if (!$logo) {
		$logo = dimhouse_default_page_post_header_logo();
	}

	$logo_url = dimhouse_option('page_post_header_logo_url', home_url('/'));
	$logo_html = dimhouse_image_html($logo, 'full', array('alt' => get_bloginfo('name')));
	if (!$logo_html) {
		$logo_html = '<span class="dimhouse-page-post-header-logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
	}

	$items = dimhouse_page_post_header_menu_items();
	$count = count($items);
	$menu_html = '';
	foreach ($items as $index => $item) {
		$label = !empty($item['label']) ? $item['label'] : '';
		$url = !empty($item['url']) ? $item['url'] : '#';
		if (!$label) {
			continue;
		}

		$item_class = trim('nav-item ' . ($index === 0 ? 'first' : '') . ($index === $count - 1 ? ' last' : ''));
		$target = !empty($item['new_tab']) ? '_blank' : '_self';
		$rel = $target === '_blank' ? ' rel="noopener noreferrer"' : '';
		$current = $url && $url !== '#' && untrailingslashit($url) === untrailingslashit(get_permalink()) ? ' current' : '';

		$menu_html .= '<li class="' . esc_attr($item_class) . '"><a href="' . esc_url($url) . '" target="' . esc_attr($target) . '" class="nav-link dropdown-toggle' . esc_attr($current) . '"' . $rel . '><span class="text">' . esc_html($label) . '</span></a></li>';
	}

	return '<header class="bg-color color-header dimhouse-page-post-header" id="main_header">
		<div class="header-bottom">
			<div class="container">
				<div class="top row m-0 flex-nowrap align-items-center">
					<div class="logo"><a href="' . esc_url($logo_url) . '" target="_self">' . $logo_html . '</a></div>
					<div id="main_menu"><ul class="nav navbar-nav menu-wrapper ">' . $menu_html . '</ul></div>
					<div class="right_header">
						<div class="header-tool">
							<button class="navbar-toggler dimhouse-page-post-menu-toggle" type="button" data-target="#tth-main-menu" aria-controls="tth-main-menu" aria-expanded="false" aria-label="' . esc_attr__('Toggle navigation', 'dimhouse') . '">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>';
}

function dimhouse_default_floating_contact_links() {
	$phone = dimhouse_option('header_phone', '0392959713');
	$phone_href = dimhouse_option('header_phone_link', 'tel:0392959713');
	$phone_digits = preg_replace('/\D+/', '', $phone);

	return array(
		array(
			'label' => 'Phone',
			'url' => $phone_href,
			'image' => dimhouse_asset_uri('resources/images/whatsapp1.png'),
			'class' => '',
		),
		array(
			'label' => 'Zalo',
			'url' => $phone_digits ? 'https://zalo.me/' . $phone_digits : '',
			'image' => dimhouse_asset_uri('resources/images/izalo1.png'),
			'class' => 'zalo',
		),
		array(
			'label' => 'Facebook',
			'url' => 'https://www.facebook.com/DimHouse.DimDecor',
			'image' => dimhouse_asset_uri('resources/images/ifacebook1.png'),
			'class' => 'facebook',
		),
	);
}

function dimhouse_floating_contact_links() {
	return dimhouse_option_repeater('floating_contact_links', dimhouse_default_floating_contact_links());
}

function dimhouse_default_channel_articles() {
	return array(
		array('title' => 'Tiêu Chuẩn Thi Công Xây Dựng Dimhouse 2025', 'subtitle' => 'DIMHOUSE - Thiết Kế & Thi Công Công Trình', 'url' => home_url('/tieu-chuan-thi-cong-xay-dung-Dimhouse'), 'image' => dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/5_tieu_chuanthicong/[580x400-cr]anh_dai_dien___tieuchuanthicong.png__cv.webp')),
		array('title' => 'Hồ Sơ Năng Lực Dimhouse (Portfolio)', 'subtitle' => 'DIMHOUSE - Thiết Kế & Thi Công Công Trình', 'url' => home_url('/Dimhouse-portfolio'), 'image' => dimhouse_asset_uri('thumbs/page/1_ho_so_nang_luc/[580x400-cr]anh_dai_dien___hosonangluc_1.png__cv.webp')),
		array('title' => 'Báo Giá Xây Dựng Trọn Gói', 'subtitle' => 'DIMHOUSE - Thiết Kế & Thi Công Công Trình', 'url' => home_url('/quy-trinh-tu-van-xay-dung-moi-1-1'), 'image' => dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/7_xay_dung_tron_goi/[580x400-cr]anh_dai_dien___xay_dung_tron_goi.png__cv.webp')),
		array('title' => 'Tư Vấn Báo Giá Thi Công', 'subtitle' => 'DIMHOUSE - Thiết Kế & Thi Công Công Trình', 'url' => home_url('/quy-trinh-tu-van-xay-dung-moi-1'), 'image' => dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/4_link_anh/[580x400-cr]anhdaidien___baogiathicong.png__cv.webp')),
	);
}

function dimhouse_kses_content($content) {
	return wp_kses_post($content);
}

function dimhouse_kses_iframe($content) {
	$allowed = wp_kses_allowed_html('post');
	$allowed['iframe'] = array(
		'src' => true,
		'width' => true,
		'height' => true,
		'style' => true,
		'scrolling' => true,
		'frameborder' => true,
		'allowfullscreen' => true,
		'allow' => true,
		'loading' => true,
		'referrerpolicy' => true,
	);

	return wp_kses($content, $allowed);
}

function dimhouse_kses_interactive_html($content) {
	$allowed = wp_kses_allowed_html('post');
	$form_tags = array(
		'form' => array('action' => true, 'method' => true, 'id' => true, 'class' => true, 'style' => true, 'data-*' => true),
		'input' => array('type' => true, 'name' => true, 'value' => true, 'id' => true, 'class' => true, 'placeholder' => true, 'required' => true, 'checked' => true, 'disabled' => true, 'min' => true, 'max' => true, 'step' => true, 'data-*' => true),
		'select' => array('name' => true, 'id' => true, 'class' => true, 'required' => true, 'disabled' => true, 'data-*' => true),
		'option' => array('value' => true, 'selected' => true, 'disabled' => true, 'data-*' => true),
		'textarea' => array('name' => true, 'id' => true, 'class' => true, 'placeholder' => true, 'required' => true, 'disabled' => true, 'rows' => true, 'cols' => true, 'data-*' => true),
		'button' => array('type' => true, 'name' => true, 'value' => true, 'id' => true, 'class' => true, 'style' => true, 'data-*' => true),
		'label' => array('for' => true, 'class' => true, 'style' => true),
		'iframe' => array('src' => true, 'width' => true, 'height' => true, 'style' => true, 'scrolling' => true, 'frameborder' => true, 'allowfullscreen' => true, 'allow' => true, 'loading' => true, 'referrerpolicy' => true),
	);

	foreach ($form_tags as $tag => $attrs) {
		$allowed[$tag] = isset($allowed[$tag]) ? array_merge($allowed[$tag], $attrs) : $attrs;
	}

	return wp_kses($content, $allowed);
}

function dimhouse_render_button($button, $default_class = 'btn btn-primary') {
	$url = dimhouse_link_url($button);
	if (!$url) {
		return '';
	}

	$title = dimhouse_link_title($button, __('Learn More', 'dimhouse'));
	$class = $default_class;
	if (is_array($button) && !empty($button['style']) && $button['style'] === 'secondary') {
		$class = 'btn btn-outline-primary';
	}

	return sprintf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr($class),
		esc_url($url),
		esc_html($title)
	);
}

function dimhouse_render_link($url, $label, $class = '') {
	if (!$url) {
		return '';
	}

	return sprintf(
		'<a%1$s href="%2$s">%3$s</a>',
		$class ? ' class="' . esc_attr($class) . '"' : '',
		esc_url($url),
		esc_html($label)
	);
}

function dimhouse_testimonial_rating_score($rating) {
	if ($rating === null || $rating === false || $rating === '') {
		return 10;
	}

	return min(10, max(1, (int) $rating));
}

function dimhouse_render_footer_html() {
	$defaults = dimhouse_home_defaults();
	$footer_defaults = !empty($defaults['footer']) ? $defaults['footer'] : array();
	$footer_logo = dimhouse_option('footer_logo', !empty($footer_defaults['footer_logo']) ? $footer_defaults['footer_logo'] : '');
	$footer_title = dimhouse_option('footer_title', !empty($footer_defaults['footer_title']) ? $footer_defaults['footer_title'] : '');
	$footer_text = dimhouse_option('footer_text', !empty($footer_defaults['footer_text']) ? $footer_defaults['footer_text'] : '');
	$footer_partners = dimhouse_option_repeater('footer_partners', dimhouse_default_footer_partners());
	$footer_fanpage_iframe = dimhouse_option('footer_fanpage_iframe', '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FDimHouse.DimDecor&amp;tabs=timeline&amp;width=340&amp;height=500&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=false&amp;appId" width="340" height="311" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>');
	$footer_map_iframe = dimhouse_option('footer_map_iframe', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1565.9326619961673!2d105.8156930051418!3d21.01521256380595!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab6349c1445f%3A0x7fdbde275176e812!2zMTY5IFAuIFRow6FpIEjDoCwgxJDhu5FuZyDEkGEsIEjDoCBO4buZaSAxMDAwMDAsIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1780459947279!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>');
	$footer_email = dimhouse_option('footer_email', 'dimhousevietnam@gmail.com');
	$brand_title = dimhouse_option('footer_brand_title', 'Đối tác');
	$contact_title = dimhouse_option('footer_contact_title', 'Thông tin liên hệ');
	$social_title = dimhouse_option('footer_social_title', 'Kết nối với chúng tôi');
	$copyright = dimhouse_option('footer_copyright', 'Copyright © 2026. All rights reserved.');
	$popup_image = dimhouse_option('popup_image', dimhouse_asset_uri('uploads/banner/baner/popup_2025_2.jpg'));
	$popup_url = dimhouse_option('popup_url', '');
	$popup_alt = dimhouse_option('popup_alt', 'POPUP');

	$partner_html = '';
	foreach ($footer_partners as $partner) {
		$image = !empty($partner['image']) ? $partner['image'] : '';
		$image_url = dimhouse_image_url($image, 'thumbnail');
		if (!$image_url) {
			continue;
		}
		$url = !empty($partner['url']) ? $partner['url'] : '#';
		$alt = !empty($partner['alt']) ? $partner['alt'] : 'BRAND';
		$partner_html .= '<div class="item"><a target="_blank" href="' . esc_url($url) . '"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($alt) . '" class="ibanner"></a></div>';
	}

	$social_html = dimhouse_render_social_links_html('header');
	$footer_title_text = trim(wp_strip_all_tags((string) $footer_title));
	$footer_text_plain = preg_replace('/\s+/u', ' ', trim(wp_strip_all_tags((string) $footer_text)));
	$footer_title_html = $footer_title_text && stripos($footer_text_plain, $footer_title_text) === false ? '<div class="footer_title footer_logo-title">' . esc_html($footer_title_text) . '</div>' : '';

	$popup_image_url = dimhouse_image_url($popup_image, 'full');
	$popup_html = '';
	if ($popup_image_url) {
		$popup_html = '<a id="click_popup" class="fancybox_popup" href="#popup_banner_a"></a><div id="popup_banner"><div class="" id="popup_banner_a"><div class="banner_item" style=""><div class="item"><a href="' . esc_url($popup_url) . '" target="_self"><img src="' . esc_url($popup_image_url) . '" alt="' . esc_attr($popup_alt) . '" style=""></a></div></div></div></div>';
	}

	return '<footer class="section section-7 fp-auto-height">
		<div class="bg-footer color-footer">
			<div class="brand_scroll">
				<div class="container">
					<div class="brand_scroll-title">' . esc_html($brand_title) . '</div>
					<div class="brand_scroll-content ">' . $partner_html . '</div>
				</div>
			</div>
			<div class="container main_content">
				<div class="top">
					<div class="footer_logo">
						' . $footer_title_html . '
						<div class="content">' . dimhouse_kses_content($footer_text) . '</div>
						<a href="' . esc_url(home_url('/')) . '" target="_self">' . dimhouse_image_html($footer_logo, 'full', array('alt' => 'Logo footer')) . '</a>
					</div>
					<div class="fanpage">' . dimhouse_kses_iframe($footer_fanpage_iframe) . '</div>
					<div class="contact_map">
						<div class="footer_title">' . esc_html($contact_title) . '</div>
						' . dimhouse_kses_iframe($footer_map_iframe) . '
						<div class="footer_title social_title">' . esc_html($social_title) . '</div>
						<div class="box_social"><div class="row mx-0">' . $social_html . '</div></div>
						<div class="email"><i class="fas fa-envelope"></i> Email: ' . esc_html($footer_email) . '</div>
					</div>
				</div>
			</div>
			<div class="bottom"><div class="copyright">' . dimhouse_kses_content($copyright) . '</div></div>
			<div class="container"></div>
			' . $popup_html . '
		</div>
	</footer>';
}

function dimhouse_render_floating_ui_html() {
	$hardcoded_facebook_url = 'https://www.facebook.com/DimHouse.DimDecor';
	$html = '<div id="ims-scroll_left" class=""></div>
	<div id="ims-scroll_right" class=""></div>
	<div id="ims-loading"><div class="nb-spinner"></div></div>
	<div id="ims-data"></div>
	<div id="BactoTop" class="bg-color text-color" style="display: none;"><i class="far fa-chevron-up"></i></div>';

	foreach (dimhouse_floating_contact_links() as $index => $link) {
		$url = !empty($link['url']) ? $link['url'] : '';
		$image = !empty($link['image']) ? $link['image'] : '';
		$image_url = dimhouse_image_url($image, 'thumbnail');
		if (!$url || !$image_url) {
			continue;
		}

		$label = !empty($link['label']) ? $link['label'] : 'Contact';
		$class = trim('hotline sticky ' . (!empty($link['class']) ? sanitize_html_class($link['class']) : ''));
		$is_facebook_link = strpos(' ' . $class . ' ', ' facebook ') !== false;
		if ($is_facebook_link) {
			$url = $hardcoded_facebook_url;
		}

		$onclick = '';
		if ($is_facebook_link) {
			$onclick = ' onclick="' . esc_attr('event.preventDefault(); event.stopPropagation(); if (event.stopImmediatePropagation) { event.stopImmediatePropagation(); } window.open(' . wp_json_encode($hardcoded_facebook_url) . ', "_blank", "noopener,noreferrer"); return false;') . '"';
		}

		$inner = '<div class="phone"><div class="phone-circle"></div><div class="phone-circle-fill"></div><div class="phone-img-circle"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '"></div></div>';
		if ($is_facebook_link) {
			$new_facebook_inner = '<span class="dimhouse-facebook-phone-v2"><span class="dimhouse-facebook-circle-v2"></span><span class="dimhouse-facebook-circle-fill-v2"></span><span class="dimhouse-facebook-img-circle-v2"><img src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '"></span></span>';
			$html .= '<div id="dimhouse-hidden-facebook-hotline" class="' . esc_attr($class) . '" style="display: none !important;" aria-hidden="true"><div class="ring"><a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"' . $onclick . '>' . $inner . '</a></div></div>';
			$html .= '<div id="dimhouse-facebook-contact-v2" class="dimhouse-floating-facebook-contact-v2"><a id="dimhouse-facebook-contact-link-v2" class="dimhouse-floating-facebook-link-v2" href="' . esc_url($hardcoded_facebook_url) . '" target="_blank" rel="noopener noreferrer"' . $onclick . '>' . $new_facebook_inner . '</a></div>';
			continue;
		}

		$html .= '<div class="' . esc_attr($class) . '"><div class="ring"><a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer"' . $onclick . '>' . $inner . '</a></div></div>';
	}

	$html .= '<div class="overlay_bo"></div><aside class="sideMenu"><div id="ims-side-menu"><ul class="list_none ">';
	$side_items = dimhouse_side_menu_items();
	$side_count = count($side_items);
	foreach ($side_items as $index => $item) {
		$label = !empty($item['label']) ? $item['label'] : '';
		$url = !empty($item['url']) ? $item['url'] : '#';
		if (!$label) {
			continue;
		}
		$class = trim('menu_li ' . ($index === 0 ? 'first' : '') . ($index === $side_count - 1 ? ' last' : ''));
		$html .= '<li class="' . esc_attr($class) . '"><a href="' . esc_url($url) . '" target="" class="menu_link css_bo ">' . esc_html($label) . '</a></li>';
	}
	$html .= '</ul></div></aside>';

	$html .= '<div class="showMobile bottom_phone"><div class="menu_bottom">';
	foreach (dimhouse_mobile_menu_items() as $item) {
		$label = !empty($item['label']) ? $item['label'] : (!empty($item['name']) ? $item['name'] : '');
		$url = !empty($item['url']) ? $item['url'] : '#';
		$icon = !empty($item['icon']) ? $item['icon'] : '';
		$icon_url = dimhouse_image_url($icon, 'thumbnail');
		if (!$label) {
			continue;
		}
		$html .= '<div class="item"><a href="' . esc_url($url) . '" target="_self"><span class="icon">' .
			($icon_url ? '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($label) . '">' : '') .
			'</span><span class="name">' . esc_html($label) . '</span></a></div>';
	}
	$html .= '</div></div>';

	return $html;
}

function dimhouse_rewrite_clone_asset_urls($html) {
	$theme_uri = trailingslashit(get_stylesheet_directory_uri());
	$home_uri = trailingslashit(home_url('/'));

	$asset_dirs = array('uploads', 'thumbs', 'resources', 'modules', 'images');
	foreach ($asset_dirs as $dir) {
		$html = str_replace('https://RdMJAeP6qJeR.vn/' . $dir . '/', $theme_uri . $dir . '/', $html);
		$html = str_replace('http://RdMJAeP6qJeR.vn/' . $dir . '/', $theme_uri . $dir . '/', $html);
	}

	$html = str_replace('https://RdMJAeP6qJeR.vn/', $home_uri, $html);
	$html = str_replace('http://RdMJAeP6qJeR.vn/', $home_uri, $html);

	foreach ($asset_dirs as $dir) {
		foreach (array('src', 'data-src', 'href', 'poster') as $attr) {
			$html = str_replace($attr . '="' . $dir . '/', $attr . '="' . $theme_uri . $dir . '/', $html);
			$html = str_replace($attr . "='" . $dir . '/', $attr . "='" . $theme_uri . $dir . '/', $html);
		}

		$html = str_replace('url(' . $dir . '/', 'url(' . $theme_uri . $dir . '/', $html);
		$html = str_replace('url("' . $dir . '/', 'url("' . $theme_uri . $dir . '/', $html);
		$html = str_replace("url('" . $dir . '/', "url('" . $theme_uri . $dir . '/', $html);
	}

	$missing_image_fallback = $theme_uri . 'thumbs/nophoto/%5B580x400-cr%5Dnophoto.jpg__cv.webp';
	$missing_images = array(
		'uploads/banner/baner/dat_lich_tu_van_2.jpg',
		'uploads/banner/baner/du_toan_1.jpg',
		'uploads/banner/baner/goi_chia_khoa.jpg',
		'uploads/banner/baner/goi_thietke.jpg',
		'uploads/banner/baner/goi_xd_tho.jpg',
	);
	foreach ($missing_images as $missing_image) {
		if (!file_exists(get_theme_file_path('/' . $missing_image))) {
			$html = str_replace($theme_uri . $missing_image, $missing_image_fallback, $html);
		}
	}

	return $html;
}

function dimhouse_home_acf_layout($layout_name) {
	if (!function_exists('get_field')) {
		return array();
	}

	$sections = get_field('home_sections');
	if (!is_array($sections)) {
		return array();
	}

	foreach ($sections as $section) {
		if (is_array($section) && !empty($section['acf_fc_layout']) && $section['acf_fc_layout'] === $layout_name) {
			return $section;
		}
	}

	return array();
}

function dimhouse_replace_first_match($html, $pattern, $callback) {
	$updated = preg_replace_callback($pattern, $callback, $html, 1);
	return is_string($updated) ? $updated : $html;
}

function dimhouse_clone_img_url($image) {
	$url = dimhouse_image_url($image, 'full');
	return $url ? $url : '';
}

function dimhouse_acf_post_value($field_name, $post_id) {
	if (function_exists('get_field')) {
		$value = get_field($field_name, $post_id);
		if ($value !== null && $value !== false && $value !== '') {
			return $value;
		}
	}

	return function_exists('get_post_meta') ? get_post_meta($post_id, $field_name, true) : '';
}

function dimhouse_term_id_from_acf($term) {
	if (is_numeric($term)) {
		return (int) $term;
	}

	if (is_object($term) && !empty($term->term_id)) {
		return (int) $term->term_id;
	}

	if (is_array($term)) {
		if (!empty($term['term_id'])) {
			return (int) $term['term_id'];
		}

		if (!empty($term['term_id'][0])) {
			return (int) $term['term_id'][0];
		}
	}

	return 0;
}

function dimhouse_villa_post_image_url($post_id) {
	$image = dimhouse_acf_post_value('villa_card_image', $post_id);
	$image_url = $image ? dimhouse_image_url($image, 'large') : '';
	if ($image_url) {
		return $image_url;
	}

	if (function_exists('get_the_post_thumbnail_url')) {
		$image_url = get_the_post_thumbnail_url($post_id, 'large');
		if ($image_url) {
			return $image_url;
		}
	}

	return dimhouse_asset_uri('thumbs/nophoto/[580x400-cr]nophoto.jpg__cv.webp');
}

function dimhouse_render_villa_post_card($post_id) {
	if (!function_exists('get_permalink') || !function_exists('get_the_title')) {
		return '';
	}

	$title = get_the_title($post_id);
	$link = get_permalink($post_id);
	$image = dimhouse_villa_post_image_url($post_id);
	$details = array(
		array('label' => 'Chủ đầu tư', 'value' => dimhouse_acf_post_value('villa_project_client', $post_id)),
		array('label' => 'Địa chỉ', 'value' => dimhouse_acf_post_value('villa_project_location', $post_id)),
		array('label' => 'Diện tích', 'value' => dimhouse_acf_post_value('villa_project_area', $post_id)),
		array('label' => 'Số tầng', 'value' => dimhouse_acf_post_value('villa_project_floors', $post_id)),
	);
	$detail_html = '';
	foreach ($details as $detail) {
		if (empty($detail['value'])) {
			continue;
		}

		$detail_html .= '<div class="villa-card-detail"><span>' . esc_html($detail['label']) . ':</span><strong>' . esc_html($detail['value']) . '</strong></div>';
	}

	return '<article class="villa-card">
		<a class="villa-card-media" href="' . esc_url($link) . '"><img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '"></a>
		<div class="villa-card-body">
			<h3><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></h3>
			' . ($detail_html ? '<div class="villa-card-details">' . $detail_html . '</div>' : '') . '
		</div>
	</article>';
}

function dimhouse_render_villa_designs_section($section) {
	if (empty($section) || empty($section['tabs']) || !is_array($section['tabs']) || !class_exists('WP_Query')) {
		return '';
	}

	$title = !empty($section['title']) ? $section['title'] : '1000+ THIẾT KẾ BIỆT THỰ ĐẲNG CẤP';
	$default_count = !empty($section['posts_per_tab']) ? absint($section['posts_per_tab']) : 8;
	$default_count = $default_count > 0 ? $default_count : 8;
	$tabs = array();
	$panels = array();

	foreach ($section['tabs'] as $index => $tab) {
		if (!is_array($tab)) {
			continue;
		}

		$term_id = dimhouse_term_id_from_acf(!empty($tab['category']) ? $tab['category'] : 0);
		$label = !empty($tab['label']) ? $tab['label'] : '';
		if (!$term_id || !$label) {
			continue;
		}

		$count = !empty($tab['posts_per_tab']) ? absint($tab['posts_per_tab']) : $default_count;
		$count = $count > 0 ? $count : $default_count;
		$panel_id = 'villa-designs-tab-' . sanitize_title($label) . '-' . $index;
		$is_active = empty($tabs);
		$query = new WP_Query(array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'category__in' => array($term_id),
			'ignore_sticky_posts' => true,
		));

		$cards = array();
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$cards[] = dimhouse_render_villa_post_card(get_the_ID());
			}
			wp_reset_postdata();
		}

		$tabs[] = '<li class="nav-item"><a class="nav-link' . ($is_active ? ' active' : '') . '" href="#' . esc_attr($panel_id) . '" data-toggle="tab">' . esc_html($label) . '</a></li>';
		$panels[] = '<div class="tab-pane villa-designs-panel' . ($is_active ? ' active show' : '') . '" id="' . esc_attr($panel_id) . '">' .
			(!empty($cards) ? '<div class="villa-designs-grid">' . implode("\n", $cards) . '</div>' : '<div class="villa-designs-empty">Chưa có bài viết trong danh mục này.</div>') .
			'</div>';
	}

	if (empty($tabs)) {
		return '';
	}

	return '<section class="section villa-designs-section">
		<div class="container">
			<h2>' . esc_html($title) . '</h2>
			<ul class="nav nav-pills villa-designs-tabs">' . implode("\n", $tabs) . '</ul>
			<div class="tab-content villa-designs-content">' . implode("\n", $panels) . '</div>
		</div>
	</section>';
}

function dimhouse_index_between($start_marker, $end_marker) {
	$index_path = get_theme_file_path('/index.html');
	if (!file_exists($index_path)) {
		return '';
	}

	$html = file_get_contents($index_path);
	if (!$html) {
		return '';
	}

	$start = strpos($html, $start_marker);
	if ($start === false) {
		return '';
	}

	$end = strpos($html, $end_marker, $start);
	if ($end === false) {
		return '';
	}

	return dimhouse_rewrite_clone_asset_urls(substr($html, $start, $end - $start));
}

function dimhouse_index_section($section_id) {
	$index_path = get_theme_file_path('/index.html');
	if (!file_exists($index_path)) {
		return '';
	}

	$html = file_get_contents($index_path);
	if (!$html) {
		return '';
	}

	$pattern = '/<!--\s*start section\s+([^\s-]+)\s*-->/i';
	if (!preg_match_all($pattern, $html, $matches, PREG_OFFSET_CAPTURE)) {
		return '';
	}

	foreach ($matches[1] as $index => $match) {
		if ((string) $match[0] !== (string) $section_id) {
			continue;
		}

		$start = $matches[0][$index][1];
		$end = isset($matches[0][$index + 1])
			? $matches[0][$index + 1][1]
			: strpos($html, '<script src="resources/minify/minify.jquery.min.js"', $start);

		if ($end === false) {
			$footer_end = strpos($html, '</footer>', $start);
			$end = $footer_end !== false ? $footer_end + strlen('</footer>') : strlen($html);
		}

		return dimhouse_rewrite_clone_asset_urls(substr($html, $start, $end - $start));
	}

	return '';
}

function dimhouse_index_footer_inline_scripts() {
	$index_path = get_theme_file_path('/index.html');
	if (!file_exists($index_path)) {
		return '';
	}

	$html = file_get_contents($index_path);
	if (!$html) {
		return '';
	}

	$start = strpos($html, '<script src="resources/minify/minify.jquery.min.js"');
	if ($start === false) {
		return '';
	}

	$end = stripos($html, '</body>', $start);
	if ($end === false) {
		$end = strlen($html);
	}

	$footer = substr($html, $start, $end - $start);
	if (!preg_match_all('/<script\b(?![^>]*\bsrc=)[^>]*>(.*?)<\/script>/is', $footer, $matches)) {
		return '';
	}

	$scripts = array();
	foreach ($matches[1] as $script) {
		$script = trim($script);
		if ($script === '') {
			continue;
		}

		if (dimhouse_should_skip_clone_inline_script($script)) {
			continue;
		}

		$scripts[] = $script;
	}

	if (empty($scripts)) {
		return '';
	}

	return dimhouse_rewrite_clone_asset_urls(implode("\n;\n", $scripts));
}

function dimhouse_should_skip_clone_inline_script($script) {
	$managed_selectors = array(
		'.focus_product .row_item',
		'.list_item_product .row_item',
		'.focus_page .row_item',
		'.box_comment .list_item_project',
		'.brand_scroll-content',
		'.dg-wrapper',
		'.section-service .row',
	);

	foreach ($managed_selectors as $selector) {
		if (strpos($script, $selector) !== false && strpos($script, '.slick(') !== false) {
			return true;
		}
	}

	if (strpos($script, '#click_popup') !== false && strpos($script, '#popup_banner_a button.close') !== false) {
		return true;
	}

	return false;
}

function dimhouse_sub_field_or_layout($field_name, $layout_name = '') {
	$value = function_exists('get_sub_field') ? get_sub_field($field_name) : null;
	if ($value !== null && $value !== false && $value !== '') {
		return $value;
	}

	if (!$layout_name) {
		return null;
	}

	$layout = dimhouse_home_acf_layout($layout_name);
	if (is_array($layout) && array_key_exists($field_name, $layout)) {
		return $layout[$field_name];
	}

	return null;
}

function dimhouse_render_process_section_from_index() {
	$html = dimhouse_index_between('<div class="section section-1"', '<div class="section section-2"');
	if (!$html) {
		return '';
	}

	$title = dimhouse_sub_field_or_layout('title', 'process');
	if ($title) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="about_title">)[\s\S]*?(</div>)#',
			function ($matches) use ($title) {
				return $matches[1] . esc_html($title) . $matches[2];
			}
		);
	}

	$banner = dimhouse_sub_field_or_layout('banner', 'process');
	$banner_url = $banner ? dimhouse_clone_img_url($banner) : '';
	if ($banner_url) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="banner_item"[\s\S]*?<img\s+class="lazyload"\s+src=")[^"]*("\s+data-src=")[^"]*(")#',
			function ($matches) use ($banner_url) {
				return $matches[1] . esc_url($banner_url) . $matches[2] . esc_url($banner_url) . $matches[3];
			}
		);
	}

	$cta_url = dimhouse_sub_field_or_layout('cta_url', 'process');
	if ($cta_url) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="banner_item"[\s\S]*?<a\s+href=")[^"]*(")#',
			function ($matches) use ($cta_url) {
				return $matches[1] . esc_url($cta_url) . $matches[2];
			}
		);
	}

	$steps = dimhouse_sub_field_or_layout('steps', 'process');
	if (is_array($steps) && !empty($steps)) {
		$items = array();
		foreach ($steps as $step) {
			if (!is_array($step)) {
				continue;
			}

			$step_title = !empty($step['title']) ? $step['title'] : '';
			$step_text = !empty($step['text']) ? $step['text'] : '';
			$step_image = !empty($step['image']) ? dimhouse_clone_img_url($step['image']) : '';
			$items[] = '<div class="item">
						<div class="img"><div class="inner">' . ($step_image ? '<img src="' . esc_url($step_image) . '" alt="' . esc_attr($step_title) . '">' : '') . '</div></div>
						<div class="content">
							<h2 class="title_about">' . esc_html($step_title) . '</h2>
							<div class="short"><p>' . esc_html($step_text) . '</p></div>
						</div>
					</div>';
		}

		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="col-xl-3 pr-0">[\s\S]*?<div class="banner"[\s\S]*?</div>\s*</div>\s*</div>)\s*(?:<div class="item">[\s\S]*?</div>\s*</div>\s*</div>\s*</div>\s*)+(\s*<div class="col-xl-9">)#',
				function ($matches) use ($items) {
					return $matches[1] . "\n" . implode("\n", $items) . $matches[2];
				}
			);
		}
	}

	return dimhouse_remove_procedure_location_fields($html);
}

function dimhouse_render_menu_grid_section_from_index() {
	$html = dimhouse_index_between('<div class="section section-2"', '<div class="section section-4"');
	if (!$html) {
		return '';
	}

	$cards = dimhouse_sub_field_or_layout('cards', 'menu_grid');
	if (is_array($cards) && !empty($cards)) {
		$items = array();
		$total = count($cards);
		foreach ($cards as $index => $card) {
			if (!is_array($card)) {
				continue;
			}

			$title = !empty($card['title']) ? $card['title'] : '';
			$url = !empty($card['url']) ? $card['url'] : '#';
			$image = !empty($card['icon']) ? dimhouse_clone_img_url($card['icon']) : '';
			$text = !empty($card['text']) ? $card['text'] : '';
			$class = trim('menu_li ' . ($index === 0 ? 'first' : '') . ($index === $total - 1 ? ' last' : ''));
			$items[] = '<li class="' . esc_attr($class) . '">
        <a href="' . esc_url($url) . '" target="">
            ' . ($image ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" width="476" height="1068">' : '') . '
            <h3 class="menu_title"><p>' . esc_html($title) . '</p></h3>
            ' . ($text ? '<div class="menu_desc">' . esc_html($text) . '</div>' : '') . '
        </a>
        <span>D<br>i<br>m<br>h<br>o<br>u<br>s<br>e</span>
    </li>';
		}

		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div id="slide_menu">[\s\S]*?<ul class="list_none\s*">)[\s\S]*?(</ul>)#',
				function ($matches) use ($items) {
					return $matches[1] . "\n" . implode("\n", $items) . "\n" . $matches[2];
				}
			);
		}
	}

	return $html;
}

function dimhouse_apply_clone_acf_overrides($html) {
	$top_social_html = dimhouse_render_social_links_html('header');
	if ($top_social_html) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="top_social">\s*<div class="box_social">\s*<div class="row mx-0">)[\s\S]*?(</div>\s*</div>\s*</div>)#',
			function ($matches) use ($top_social_html) {
				return $matches[1] . "\n" . $top_social_html . "\n" . $matches[2];
			}
		);
	}
	$html = dimhouse_replace_clone_contact_icon_images($html);

	$hero = dimhouse_home_acf_layout('hero');
	if (!empty($hero)) {
		if (!empty($hero['video'])) {
			$video_url = dimhouse_clone_img_url($hero['video']);
			if ($video_url) {
				$html = dimhouse_replace_first_match(
					$html,
					'#(<div class="section section-0"[\s\S]*?<video\s+src=")[^"]*(")#',
					function ($matches) use ($video_url) {
						return $matches[1] . esc_url($video_url) . $matches[2];
					}
				);
			}
		} elseif (!empty($hero['image'])) {
			$image_url = dimhouse_clone_img_url($hero['image']);
			if ($image_url) {
				$image_alt = !empty($hero['title']) ? $hero['title'] : get_bloginfo('name');
				$html = dimhouse_replace_first_match(
					$html,
					'#(<div class="section section-0"[\s\S]*?<div class="item">)\s*<video[\s\S]*?</video>#',
					function ($matches) use ($image_url, $image_alt) {
						return $matches[1] . '<img class="hero-image" src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '">';
					}
				);
			}
		}

		if (!empty($hero['eyebrow']) || !empty($hero['title']) || !empty($hero['text']) || !empty($hero['buttons'])) {
			$title = !empty($hero['title']) ? $hero['title'] : 'D I M H O U S E';
			$text = !empty($hero['text']) ? $hero['text'] : '<p><strong>Thiáº¿t Káº¿ Kiáº¿n TrÃºc - Thi CÃ´ng XÃ¢y Dá»±ng</strong></p>';
			$inner = '';
			if (!empty($hero['eyebrow'])) {
				$inner .= '<div class="eyebrow">' . esc_html($hero['eyebrow']) . '</div>';
			}
			$inner .= '<h2>' . esc_html($title) . '</h2>' . wp_kses_post($text);
			if (!empty($hero['buttons']) && is_array($hero['buttons'])) {
				$buttons = array();
				foreach ($hero['buttons'] as $button) {
					if (!is_array($button)) {
						continue;
					}

					$button_html = dimhouse_render_button(array(
						'url' => !empty($button['url']) ? $button['url'] : '',
						'title' => !empty($button['label']) ? $button['label'] : '',
						'style' => !empty($button['style']) ? $button['style'] : '',
					));
					if ($button_html) {
						$buttons[] = $button_html;
					}
				}
				if (!empty($buttons)) {
					$inner .= '<div class="buttons">' . implode('', $buttons) . '</div>';
				}
			}
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section section-0"[\s\S]*?<div class="inner">\s*)<h2>[\s\S]*?</h2>\s*<p>[\s\S]*?</p>#',
				function ($matches) use ($inner) {
					return $matches[1] . $inner;
				}
			);
		}
	}

	if (!empty($hero) && !empty($hero['banners']) && is_array($hero['banners'])) {
		$banners = array();
		foreach ($hero['banners'] as $banner) {
			if (!is_array($banner)) {
				continue;
			}

			$image = !empty($banner['image']) ? dimhouse_clone_img_url($banner['image']) : '';
			if (!$image) {
				continue;
			}

			$url = !empty($banner['url']) ? $banner['url'] : '#';
			$alt = !empty($banner['alt']) ? $banner['alt'] : 'Banner';
			$target = !empty($banner['url']) ? ' target="_blank" rel="noopener noreferrer"' : '';
			$banners[] = '<div class="banner_item"><a href="' . esc_url($url) . '"' . $target . '><img src="' . esc_url($image) . '" alt="' . esc_attr($alt) . '"></a></div>';
		}

		if (!empty($banners)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(</div>\s*<lottie-player\s+src="[^"]*resources/images/mouse\.json")#',
				function ($matches) use ($banners) {
					return '</div><div class="hero-banners container">' . implode("\n", $banners) . '</div>' . $matches[1];
				}
			);
		}
	}

	$estimate = dimhouse_home_acf_layout('estimate');
	if (!empty($estimate['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="construction section section-3">[\s\S]*?<h3 class="title">)[\s\S]*?(</h3>)#',
			function ($matches) use ($estimate) {
				return $matches[1] . esc_html($estimate['title']) . $matches[2];
			}
		);
	}

	if (!empty($estimate['intro'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="construction section section-3">[\s\S]*?<h3 class="title">[\s\S]*?</h3>)#',
			function ($matches) use ($estimate) {
				return $matches[1] . '<div class="estimate-intro">' . wp_kses_post($estimate['intro']) . '</div>';
			}
		);
	}
	if (!empty($estimate['floor_label'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="choose_floor">[\s\S]*?<div class="form-group col-6">\s*<label>)[\s\S]*?(</label>)#',
			function ($matches) use ($estimate) {
				return $matches[1] . esc_html($estimate['floor_label']) . $matches[2];
			}
		);
	}
	if (!empty($estimate['mezzanine_label'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="choose_floor">[\s\S]*?<div class="form-group col-6">[\s\S]*?</div>\s*<div class="form-group col-6">\s*<label>)[\s\S]*?(</label>)#',
			function ($matches) use ($estimate) {
				return $matches[1] . esc_html($estimate['mezzanine_label']) . $matches[2];
			}
		);
	}
	if (!empty($estimate['preview_image'])) {
		$preview_url = dimhouse_clone_img_url($estimate['preview_image']);
		if ($preview_url) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="view_house">\s*<img\s+src=")[^"]*(")#',
				function ($matches) use ($preview_url) {
					return $matches[1] . esc_url($preview_url) . $matches[2];
				}
			);
		}
	}
	if (!empty($estimate['form_banner_image'])) {
		$banner_image = dimhouse_clone_img_url($estimate['form_banner_image']);
		if ($banner_image) {
			$banner_url = !empty($estimate['form_banner_url']) ? $estimate['form_banner_url'] : '#';
			$banner_alt = !empty($estimate['form_banner_alt']) ? $estimate['form_banner_alt'] : 'Banner';
			$banner_html = '<div class="banner_item" style=""><a href="' . esc_url($banner_url) . '" target="_blank"><img class="lazyload" src="' . esc_url(dimhouse_asset_uri('resources/images/spin.svg')) . '" data-src="' . esc_url($banner_image) . '" alt="' . esc_attr($banner_alt) . '"></a></div>';
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="view_house">[\s\S]*?</div>\s*)<div class="banner_item"[\s\S]*?</div>\s*(</div>\s*<div class="for_result")#',
				function ($matches) use ($banner_html) {
					return $matches[1] . $banner_html . $matches[2];
				}
			);
		}
	}
	if (!empty($estimate['book_button_label'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="button_book">\s*<button class="book">)[\s\S]*?(</button>)#',
			function ($matches) use ($estimate) {
				return $matches[1] . esc_html($estimate['book_button_label']) . $matches[2];
			}
		);
	}
	if (!empty($estimate['tabs']) && is_array($estimate['tabs'])) {
		$nav_items = array();
		$tab_panes = array();
		$banners = array();
		foreach ($estimate['tabs'] as $index => $tab) {
			if (!is_array($tab)) {
				continue;
			}

			$key = !empty($tab['key']) ? sanitize_title($tab['key']) : 'tab' . ($index + 1);
			$label = !empty($tab['label']) ? $tab['label'] : '';
			$price = !empty($tab['price_label']) ? $tab['price_label'] : '';
			$content = !empty($tab['content']) ? $tab['content'] : '';
			$active_class = $index === 0 ? ' active' : '';
			$show_class = $index === 0 ? ' show' : '';
			$nav_items[] = '<li class="nav-item"><a href="#' . esc_attr($key) . '" data-toggle="tab" class="nav-link' . esc_attr($active_class) . '">' . esc_html($label) . '<div class="price">' . esc_html($price) . '</div></a></li>';
			$tab_panes[] = '<div class="tab-pane' . esc_attr($active_class) . '" id="' . esc_attr($key) . '"><div class="row"><div class="col-sm-12"><div class="box_content"><div class="estimate-item-content' . esc_attr($show_class) . '">' . wp_kses_post($content) . '</div></div></div></div></div>';

			$banner_image = !empty($tab['banner']) ? dimhouse_clone_img_url($tab['banner']) : '';
			if ($banner_image) {
				$banner_url = !empty($tab['url']) ? $tab['url'] : '#';
				$banners[] = '<div class="banner ' . esc_attr($key) . ($index === 0 ? ' show' : '') . '"><div class="banner_item" style=""><a href="' . esc_url($banner_url) . '" target="_blank"><img class="lazyload" src="' . esc_url(dimhouse_asset_uri('resources/images/spin.svg')) . '" data-src="' . esc_url($banner_image) . '" alt="' . esc_attr($label ? $label : 'Banner') . '"></a></div></div>';
			}
		}

		if (!empty($nav_items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="construction section section-3">[\s\S]*?<ul class="nav nav-pills">)[\s\S]*?(</ul>)#',
				function ($matches) use ($nav_items) {
					return $matches[1] . "\n" . implode("\n", $nav_items) . "\n" . $matches[2];
				}
			);
		}
		if (!empty($tab_panes)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="construction section section-3">[\s\S]*?<div class="tab-content" style="display: none">)[\s\S]*?(</div>\s*<form action="" method="post" id="construction">)#',
				function ($matches) use ($tab_panes) {
					return $matches[1] . "\n" . implode("\n", $tab_panes) . "\n" . $matches[2];
				}
			);
		}
		if (!empty($banners)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="for_result" style="display: none">[\s\S]*?<div class="button_book">[\s\S]*?</div>)\s*(?:<div class="banner[\s\S]*?</div>\s*</div>\s*)+(</div>\s*</div>\s*<div class="form">)#',
				function ($matches) use ($banners) {
					return $matches[1] . "\n" . implode("\n", $banners) . "\n" . $matches[2];
				}
			);
		}
	}
	if (!empty($estimate['construction_form_html'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#<form action="" method="post" id="construction">[\s\S]*?</form>#',
			function () use ($estimate) {
				return dimhouse_kses_interactive_html($estimate['construction_form_html']);
			}
		);
	}
	if (!empty($estimate['booking_form_html'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#<form action="" method="post" id="form_book"[\s\S]*?</form>#',
			function () use ($estimate) {
				return dimhouse_kses_interactive_html($estimate['booking_form_html']);
			}
		);
	}

	$process = dimhouse_home_acf_layout('process');
	if (!empty($process['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section_home_about">[\s\S]*?<div class="about_title">)[\s\S]*?(</div>)#',
			function ($matches) use ($process) {
				return $matches[1] . esc_html($process['title']) . $matches[2];
			}
		);
	}
	if (!empty($process['text'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section_home_about">[\s\S]*?<div class="about_title">[\s\S]*?</div>)#',
			function ($matches) use ($process) {
				return $matches[1] . '<div class="about_text">' . wp_kses_post($process['text']) . '</div>';
			}
		);
	}
	if (!empty($process['banner'])) {
		$banner_url = dimhouse_clone_img_url($process['banner']);
		if ($banner_url) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section_home_about"[\s\S]*?<div class="banner_item"[\s\S]*?<img\s+class="lazyload"\s+src=")[^"]*("\s+data-src=")[^"]*(")#',
				function ($matches) use ($banner_url) {
					return $matches[1] . esc_url($banner_url) . $matches[2] . esc_url($banner_url) . $matches[3];
				}
			);
		}
	}
	if (!empty($process['cta_url'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section_home_about"[\s\S]*?<div class="banner_item"[\s\S]*?<a\s+href=")[^"]*(")#',
			function ($matches) use ($process) {
				return $matches[1] . esc_url($process['cta_url']) . $matches[2];
			}
		);
	}
	if (!empty($process['cta_label'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section_home_about">[\s\S]*?<button type="submit">)[\s\S]*?(</button>)#',
			function ($matches) use ($process) {
				return $matches[1] . esc_html($process['cta_label']) . $matches[2];
			}
		);
	}
	if (!empty($process['steps']) && is_array($process['steps'])) {
		$items = array();
		foreach ($process['steps'] as $step) {
			if (!is_array($step)) {
				continue;
			}

			$step_title = !empty($step['title']) ? $step['title'] : '';
			$step_text = !empty($step['text']) ? $step['text'] : '';
			$step_image = !empty($step['image']) ? dimhouse_clone_img_url($step['image']) : '';
			if (!$step_title && !$step_text && !$step_image) {
				continue;
			}

			$items[] = '<div class="item">
				<div class="img"><div class="inner">' . ($step_image ? '<img src="' . esc_url($step_image) . '" alt="' . esc_attr($step_title) . '">' : '') . '</div></div>
				<div class="content">
					' . ($step_title ? '<h2 class="title_about">' . esc_html($step_title) . '</h2>' : '') . '
					' . ($step_text ? '<div class="short"><p>' . esc_html($step_text) . '</p></div>' : '') . '
				</div>
			</div>';
		}

		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="col-xl-3 pr-0">[\s\S]*?<div class="banner"[\s\S]*?</div>\s*</div>\s*</div>)\s*(?:<div class="item">[\s\S]*?</div>\s*</div>\s*</div>\s*</div>\s*)+(\s*<div class="col-xl-9">)#',
				function ($matches) use ($items) {
					return $matches[1] . "\n" . implode("\n", $items) . $matches[2];
				}
			);
		}
	}
	if (!empty($process['booking_form_html'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#<form action="" method="post" id="form_book_procedure"[\s\S]*?</form>#',
			function () use ($process) {
				return dimhouse_kses_interactive_html($process['booking_form_html']);
			}
		);
	}
	$html = dimhouse_remove_procedure_location_fields($html);

	$menu_grid = dimhouse_home_acf_layout('menu_grid');
	if (!empty($menu_grid['title']) || !empty($menu_grid['text'])) {
		$menu_intro = '<div class="menu_grid_intro">';
		if (!empty($menu_grid['title'])) {
			$menu_intro .= '<h3>' . esc_html($menu_grid['title']) . '</h3>';
		}
		if (!empty($menu_grid['text'])) {
			$menu_intro .= '<div class="content">' . wp_kses_post($menu_grid['text']) . '</div>';
		}
		$menu_intro .= '</div>';

		$html = dimhouse_replace_first_match(
			$html,
			'#(<div id="slide_menu">\s*<div class="container">)#',
			function ($matches) use ($menu_intro) {
				return $matches[1] . $menu_intro;
			}
		);
	}
	if (!empty($menu_grid['cards']) && is_array($menu_grid['cards'])) {
		$items = array();
		$count = count($menu_grid['cards']);
		foreach ($menu_grid['cards'] as $index => $card) {
			$title = !empty($card['title']) ? $card['title'] : '';
			$url = !empty($card['url']) ? $card['url'] : '#';
			$image = !empty($card['icon']) ? dimhouse_clone_img_url($card['icon']) : '';
			if (!$title && !$image) {
				continue;
			}
			$class = trim('menu_li ' . ($index === 0 ? 'first' : '') . ($index === $count - 1 ? ' last' : ''));
			$items[] = '<li class="' . esc_attr($class) . '"><a href="' . esc_url($url) . '" target="">' .
				($image ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '">' : '') .
				'<h3 class="menu_title"><p>' . esc_html($title) . '</p></h3>' .
				(!empty($card['text']) ? '<div class="menu_desc">' . esc_html($card['text']) . '</div>' : '') .
				'</a><span>D<br>i<br>m<br>h<br>o<br>u<br>s<br>e</span></li>';
		}
		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div id="slide_menu">[\s\S]*?<ul class="list_none\s*">)[\s\S]*?(</ul>)#',
				function ($matches) use ($items) {
					return $matches[1] . implode("\n", $items) . $matches[2];
				}
			);
		}
	}

	$villa_designs = dimhouse_home_acf_layout('villa_designs');
	$villa_designs_html = dimhouse_render_villa_designs_section($villa_designs);
	if ($villa_designs_html) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(\s*<!-- start section 4 -->)#',
			function ($matches) use ($villa_designs_html) {
				return "\n" . $villa_designs_html . "\n" . $matches[1];
			}
		);
	}

	$about = dimhouse_home_acf_layout('about');
	if (!empty($about['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<h3 class="title">)[\s\S]*?(</h3>)#',
			function ($matches) use ($about) {
				return $matches[1] . esc_html($about['title']) . $matches[2];
			}
		);
	}
	if (!empty($about['text'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<div class="about">\s*<div class="content">)[\s\S]*?(</div>\s*</div>\s*<div class="list_item">)#',
			function ($matches) use ($about) {
				return $matches[1] . wp_kses_post($about['text']) . $matches[2];
			}
		);
	}
	if (!empty($about['image'])) {
		$image_url = dimhouse_clone_img_url($about['image']);
		if ($image_url) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="box_personnel">[\s\S]*?<div class="imgae">\s*<img\s+src=")[^"]*(")#',
				function ($matches) use ($image_url) {
					return $matches[1] . esc_url($image_url) . $matches[2];
				}
			);
		}
	}
	if (!empty($about['person_name'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<div class="list_item">[\s\S]*?<div class="title">\s*<span>)[\s\S]*?(</span>)#',
			function ($matches) use ($about) {
				return $matches[1] . esc_html($about['person_name']) . $matches[2];
			}
		);
	}
	if (!empty($about['person_role'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<div class="list_item">[\s\S]*?<div class="title">[\s\S]*?<div>\s*<p>)[\s\S]*?(</p>)#',
			function ($matches) use ($about) {
				return $matches[1] . esc_html($about['person_role']) . $matches[2];
			}
		);
	}
	if (!empty($about['cta_url'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<a class="item btn" href=")[^"]*(")#',
			function ($matches) use ($about) {
				return $matches[1] . esc_url($about['cta_url']) . $matches[2];
			}
		);
	}
	if (!empty($about['cta_label'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_personnel">[\s\S]*?<a class="item btn"[\s\S]*?<u>)[\s\S]*?(</u>)#',
			function ($matches) use ($about) {
				return $matches[1] . esc_html($about['cta_label']) . $matches[2];
			}
		);
	}

	$channel = dimhouse_home_acf_layout('channel');
	if (!empty($channel['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section section-5"[\s\S]*?<div class="focus_product">[\s\S]*?<div class="focus_title">)[\s\S]*?(</div>)#',
			function ($matches) use ($channel) {
				return $matches[1] . esc_html($channel['title']) . $matches[2];
			}
		);
	}
	if (!empty($channel['items']) && is_array($channel['items'])) {
		$items = array();
		foreach ($channel['items'] as $item) {
			if (!is_array($item)) {
				continue;
			}

			$title = !empty($item['title']) ? $item['title'] : '';
			$url = !empty($item['url']) ? $item['url'] : '#';
			$image = !empty($item['image']) ? dimhouse_clone_img_url($item['image']) : '';
			if (!$title && !$image) {
				continue;
			}

			$items[] = '<div class="col_item ">
				<div class="item">
					<a href="' . esc_url($url) . '" title="' . esc_attr($title) . '" data-fancybox="">
						' . ($image ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" width="580" height="400">' : '') . '
					</a>
					<span><i class="fa fa-play"></i></span>
				</div>
			</div>';
		}

		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section section-5"[\s\S]*?<div class="focus_product">[\s\S]*?<div class="row_item">)[\s\S]*?(</div>\s*</div>\s*<div class="page">)#',
				function ($matches) use ($items) {
					return $matches[1] . "\n" . implode("\n", $items) . "\n" . $matches[2];
				}
			);
		}
	}
	if (!empty($channel['articles_title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section section-5"[\s\S]*?<div class="focus_page">[\s\S]*?<div class="focus_title">)[\s\S]*?(</div>)#',
			function ($matches) use ($channel) {
				return $matches[1] . esc_html($channel['articles_title']) . $matches[2];
			}
		);
	}
	if (!empty($channel['articles']) && is_array($channel['articles'])) {
		$article_items = array();
		foreach ($channel['articles'] as $article) {
			if (!is_array($article)) {
				continue;
			}
			$title = !empty($article['title']) ? $article['title'] : '';
			$url = !empty($article['url']) ? $article['url'] : '#';
			$image = !empty($article['image']) ? dimhouse_clone_img_url($article['image']) : '';
			$subtitle = !empty($article['subtitle']) ? $article['subtitle'] : '';
			if (!$title && !$image) {
				continue;
			}
			$article_items[] = '<div class="item"><a href="' . esc_url($url) . '">' .
				($image ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" width="580" height="400">' : '') .
				'</a><h3><a href="' . esc_url($url) . '">' . esc_html($title) . '</a></h3>' .
				($subtitle ? '<span>' . esc_html($subtitle) . '</span>' : '') .
				'</div>';
		}

		if (!empty($article_items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section section-5"[\s\S]*?<div class="focus_page">[\s\S]*?<div class="row_item">)[\s\S]*?(</div>\s*</div>\s*</div>\s*</div>\s*</div>\s*</div>\s*<!-- start section 6 -->)#',
				function ($matches) use ($article_items) {
					return $matches[1] . "\n" . implode("\n", $article_items) . "\n" . $matches[2];
				}
			);
		}
	}

	$testimonials = dimhouse_home_acf_layout('testimonials');
	if (!empty($testimonials['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_comment">[\s\S]*?<h4>)[\s\S]*?(</h4>)#',
			function ($matches) use ($testimonials) {
				return $matches[1] . esc_html($testimonials['title']) . $matches[2];
			}
		);
	}
	if (!empty($testimonials['text'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="box_comment">[\s\S]*?<div class="box_mid-title">[\s\S]*?</div>\s*</div>)#',
			function ($matches) use ($testimonials) {
				return $matches[1] . '<div class="box_mid-text">' . wp_kses_post($testimonials['text']) . '</div>';
			}
		);
	}
	if (!empty($testimonials['items']) && is_array($testimonials['items'])) {
		$items = array();
		foreach ($testimonials['items'] as $item) {
			if (!is_array($item)) {
				continue;
			}

			$name = !empty($item['name']) ? $item['name'] : '';
			$role = !empty($item['role']) ? $item['role'] : '';
			$text = !empty($item['text']) ? $item['text'] : '';
			$avatar = !empty($item['avatar']) ? dimhouse_clone_img_url($item['avatar']) : '';
			$rating = dimhouse_testimonial_rating_score(!empty($item['rating']) ? $item['rating'] : '');
			$testimonial_title = trim($role . ' ' . $name);
			if (!$testimonial_title && !$text && !$avatar) {
				continue;
			}

			$items[] = '<div class="col_comment">
				<div class="comment">
					<div class="info_item">
						<figure><a title="' . esc_attr($testimonial_title) . '">' . ($avatar ? '<img src="' . esc_url($avatar) . '" alt="">' : '') . '</a></figure>
						<div class="info_title">
							<div class="title">' . ($role ? esc_html($role) . ' ' : '') . '<span>' . esc_html($name) . '</span></div>
							<div class="rate">Đánh giá : <span title="' . esc_attr($rating . '/10') . '">' . esc_html($rating . '/10') . '</span></div>
						</div>
					</div>
					<div class="short"><p>' . esc_html($text) . '</p></div>
				</div>
			</div>';
		}

		if (!empty($items)) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="box_comment">[\s\S]*?<div class="list_item_project">)[\s\S]*?(</div>\s*</div>\s*</div>\s*</div>\s*<div class="contact">)#',
				function ($matches) use ($items) {
					return $matches[1] . "\n" . implode("\n", $items) . "\n" . $matches[2];
				}
			);
		}
	}

	$faq = dimhouse_home_acf_layout('faq');
	if (!empty($faq) && (!empty($faq['title']) || !empty($faq['items']))) {
		$faq_items = array();
		if (!empty($faq['items']) && is_array($faq['items'])) {
			foreach ($faq['items'] as $item) {
				if (!is_array($item)) {
					continue;
				}

				$question = !empty($item['question']) ? $item['question'] : '';
				$answer = !empty($item['answer']) ? $item['answer'] : '';
				if (!$question && !$answer) {
					continue;
				}

				$faq_items[] = '<details class="faq-item"><summary>' . esc_html($question) . '</summary><div class="faq-answer">' . wp_kses_post($answer) . '</div></details>';
			}
		}

		if (!empty($faq['title']) || !empty($faq_items)) {
			$faq_html = '<section class="section section-faq"><div class="container">';
			if (!empty($faq['title'])) {
				$faq_html .= '<h2>' . esc_html($faq['title']) . '</h2>';
			}
			if (!empty($faq_items)) {
				$faq_html .= '<div class="faq-list">' . implode("\n", $faq_items) . '</div>';
			}
			$faq_html .= '</div></section>';

			$html = dimhouse_replace_first_match(
				$html,
				'#(\s*<!-- start section 7 -->)#',
				function ($matches) use ($faq_html) {
					return "\n" . $faq_html . "\n" . $matches[1];
				}
			);
		}
	}

	$contact = dimhouse_home_acf_layout('contact');
	if (!empty($contact)) {
		if (!empty($contact['title']) || !empty($contact['title_url']) || !empty($contact['title_logo'])) {
			$title = !empty($contact['title']) ? $contact['title'] : 'LIEN HE VOI CHUNG TOI';
			$title_url = !empty($contact['title_url']) ? $contact['title_url'] : home_url('/lien-he-voi-chung-toi');
			$title_logo = !empty($contact['title_logo']) ? dimhouse_clone_img_url($contact['title_logo']) : dimhouse_asset_uri('thumbs/about/[550x307-cr]14_trang_den.png__cv.webp');
			$title_html = '<div class="title_contact"><a href="' . esc_url($title_url) . '">';
			if ($title_logo) {
				$title_html .= '<img src="' . esc_url($title_logo) . '" alt="' . esc_attr($title) . '">';
			}
			$title_html .= '<h3>' . esc_html($title) . '</h3></a></div>';

			$html = dimhouse_replace_first_match(
				$html,
				'#<div class="contact">([\s\S]*?)<div class="title_contact">[\s\S]*?</div>\s*(<div class="wrap">)#',
				function ($matches) use ($title_html) {
					return '<div class="contact">' . $matches[1] . $title_html . $matches[2];
				}
			);
		}

		if (!empty($contact['title'])) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="contact">[\s\S]*?<div class="title_contact">[\s\S]*?<h3>)[\s\S]*?(</h3>)#',
				function ($matches) use ($contact) {
					return $matches[1] . esc_html($contact['title']) . $matches[2];
				}
			);
		}

		if (!empty($contact['text']) || !empty($contact['shortcode'])) {
			$contact_extra = '';
			if (!empty($contact['text'])) {
				$contact_extra .= '<div class="contact-text">' . wp_kses_post($contact['text']) . '</div>';
			}
			if (!empty($contact['shortcode'])) {
				$contact_extra .= '<div class="contact-form">' . do_shortcode($contact['shortcode']) . '</div>';
			}

			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="contact">[\s\S]*?<div class="title_contact">[\s\S]*?</div>\s*)(<div class="wrap">)#',
				function ($matches) use ($contact_extra) {
					return $matches[1] . $contact_extra . $matches[2];
				}
			);
		}

		if (!empty($contact['items']) && is_array($contact['items'])) {
			$items = array();
			foreach ($contact['items'] as $index => $item) {
				if (!is_array($item)) {
					continue;
				}

				$label = !empty($item['label']) ? $item['label'] : '';
				$value = !empty($item['value']) ? $item['value'] : '';
				$url = !empty($item['url']) ? $item['url'] : '';
				if (!$label && !$value) {
					continue;
				}

				$icon = !empty($item['icon']) ? $item['icon'] : $label;
				$value_html = $url
					? '<a href="' . esc_url($url) . '">' . esc_html($value) . '</a>'
					: esc_html($value);

				$items[] = '<div>
					<div>' . dimhouse_contact_icon_svg($icon, $index) . '</div>
					<div class="info">
						' . ($label ? '<div class="name">' . esc_html($label) . '</div>' : '') . '
						' . ($value !== '' ? '<div>' . $value_html . '</div>' : '') . '
					</div>
				</div>';
			}

			if (!empty($items)) {
				$html = dimhouse_replace_first_match(
					$html,
					'#(<div class="contact">[\s\S]*?<div class="wrap">\s*<div>)[\s\S]*?(</div>\s*</div>\s*</div>\s*</div>\s*</div>\s*</div>\s*(?:<section class="section section-faq">|<!-- start section 7 -->))#',
					function ($matches) use ($items) {
						return $matches[1] . "\n" . implode("\n", $items) . "\n" . $matches[2];
					}
				);
			}
		}
	}

	$html = dimhouse_replace_first_match(
		$html,
		'#<footer class="section section-7"[\s\S]*?</footer>#',
		function () {
			return dimhouse_render_footer_html();
		}
	);

	$popup_image = dimhouse_option('popup_image');
	$popup_url = dimhouse_option('popup_url');
	if ($popup_image) {
		$popup_image_url = dimhouse_clone_img_url($popup_image);
		if ($popup_image_url) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div id="popup_banner"[\s\S]*?<img\s+src=")[^"]*(")#',
				function ($matches) use ($popup_image_url) {
					return $matches[1] . esc_url($popup_image_url) . $matches[2];
				}
			);
		}
	}
	if ($popup_url) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div id="popup_banner"[\s\S]*?<div class="item"><a\s+href=")[^"]*(")#',
			function ($matches) use ($popup_url) {
				return $matches[1] . esc_url($popup_url) . $matches[2];
			}
		);
	}

	$html = dimhouse_replace_first_match(
		$html,
		'#<div id="ims-scroll_left"[\s\S]*$#',
		function () {
			return dimhouse_render_floating_ui_html();
		}
	);

	$html = dimhouse_remove_construction_location_fields($html);
	$html = dimhouse_remove_popup_booking_location_fields($html);
	return dimhouse_remove_procedure_location_fields($html);
}

function dimhouse_remove_construction_location_fields($html) {
	$html = dimhouse_replace_first_match(
		$html,
		'#(<form action="" method="post" id="construction">[\s\S]*?<input type="text" name="phone"[\s\S]*?</div>)\s*<p class="label_title col-12">[\s\S]*?</p>\s*<div class="form-group col-12 col-md-6"><select name="province"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="district"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="ward"[\s\S]*?</select></div>#',
		function ($matches) {
			return $matches[1];
		}
	);

	$without_address = preg_replace(
		'#\s*<div class="form-group col-12 col-md-6">\s*<input\s+type="text"\s+name="address"\s+placeholder="Số nhà, tên đường"\s*/?>\s*</div>#',
		'',
		$html
	);

	return $without_address !== null ? $without_address : $html;
}

function dimhouse_remove_procedure_location_fields($html) {
	return dimhouse_replace_first_match(
		$html,
		'#(<form action="" method="post" id="form_book_procedure">[\s\S]*?<input type="text" name="email"[\s\S]*?</div>)\s*<p class="label_title col-12">[\s\S]*?</p>\s*<div class="form-group col-12 col-md-6"><select name="provinces"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="districts"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="wards"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6">\s*<input type="text" name="address"[\s\S]*?</div>#',
		function ($matches) {
			return $matches[1];
		}
	);
}

function dimhouse_remove_popup_booking_location_fields($html) {
	$html = dimhouse_replace_first_match(
		$html,
		'#(<form action="" method="post" id="form_book"[\s\S]*?<input type="text" name="email"[\s\S]*?</div>)\s*<p class="label_title col-12">[\s\S]*?</p>\s*<div class="form-group col-12 col-md-6"><select name="provinces"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="districts"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="wards"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6">\s*<input type="text" name="address"[\s\S]*?</div>#',
		function ($matches) {
			return $matches[1];
		}
	);

	$without_location_controls = preg_replace(
		'#\s*<p class="label_title col-12">[^<]*(?:Địa điểm|Äá»‹a Ä‘iá»ƒm)[^<]*</p>\s*<div class="form-group col-12 col-md-6"><select name="provinces"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="districts"[\s\S]*?</select></div>\s*<div class="form-group col-12 col-md-6"><select name="wards"[\s\S]*?</select></div>(?:\s*<div class="form-group col-12 col-md-6">\s*<input type="text" name="address"[\s\S]*?</div>)?#',
		'',
		$html
	);

	return $without_location_controls !== null ? $without_location_controls : $html;
}

function dimhouse_index_fallback_fullpage() {
	$index_path = get_theme_file_path('/index.html');
	if (!file_exists($index_path)) {
		return '';
	}

	$html = file_get_contents($index_path);
	if (!$html) {
		return '';
	}

	$start = strpos($html, '<div class="section section-0"');
	$footer_start = strpos($html, '<footer class="section section-7"');
	if ($start === false || $footer_start === false) {
		return '';
	}

	$footer_end = strpos($html, '</footer>', $footer_start);
	if ($footer_end === false) {
		return '';
	}

	$fragment = substr($html, $start, ($footer_end + strlen('</footer>')) - $start);

	$fragment = dimhouse_rewrite_clone_asset_urls($fragment);

	return dimhouse_apply_clone_acf_overrides($fragment);
}

function dimhouse_index_fallback_body() {
	$index_path = get_theme_file_path('/index.html');
	if (!file_exists($index_path)) {
		return '';
	}

	$html = file_get_contents($index_path);
	if (!$html) {
		return '';
	}

	$start = strpos($html, '<div id="ims-wrapper"');
	$end = strpos($html, '<script src="resources/minify/minify.jquery.min.js"', $start);
	if ($start === false || $end === false) {
		return '';
	}

	$fragment = substr($html, $start, $end - $start);
	$fragment = dimhouse_rewrite_clone_asset_urls($fragment);

	return dimhouse_apply_clone_acf_overrides($fragment);
}

function dimhouse_home_defaults() {
	return array(
		'hero' => array(
			'eyebrow' => 'DIMHOUSE',
			'title' => 'D I M H O U S E',
			'text' => '<p><strong>Thiết Kế Kiến Trúc - Thi Công Xây Dựng</strong></p>',
			'video' => dimhouse_asset_uri('uploads/banner/video_baner/video_trang_chu_2023.mp4'),
			'image' => '',
			'buttons' => array(),
			'banners' => array(),
		),
		'process' => array(
			'title' => 'Giai đoạn làm việc - Gửi yêu cầu tư vấn',
			'text' => '',
			'cta_label' => 'Đặt lịch',
			'cta_url' => home_url('/lien-he-voi-chung-toi'),
			'steps' => array(
				array(
					'title' => 'Giai Đoạn 1',
					'text' => 'Tiếp nhận thông tin, gửi HSNL theo từng nhu cầu dự án: thiết kế, thi công...',
					'image' => dimhouse_asset_uri('thumbs/2022_03/[35x35]buoc-1_1646811717.png'),
				),
				array(
					'title' => 'Giai Đoạn 2',
					'text' => 'Tư vấn qui trình, hợp đồng. Thống nhất các bên.',
					'image' => dimhouse_asset_uri('thumbs/2022_03/[35x35]buoc-2_1646811808.png'),
				),
				array(
					'title' => 'Giai Đoạn 3',
					'text' => 'Ký kết, triển khai hợp đồng theo hạng mục đã ký.',
					'image' => dimhouse_asset_uri('thumbs/2022_03/[35x35]buoc-3_1646811774.png'),
				),
				array(
					'title' => 'Giai Đoạn 4',
					'text' => 'Bàn giao thiết kế hoặc thi công và nghiệm thu theo hạng mục đã ký.',
					'image' => dimhouse_asset_uri('thumbs/2022_03/[35x35]buoc-4_1646811700.png'),
				),
			),
		),
		'menu_grid' => array(
			'title' => '',
			'text' => '',
			'cards' => array(
				array(
					'icon' => dimhouse_asset_uri('thumbs/layout/layout_2022/[476x1068-cr]muc_kt_4.jpg__cv.webp'),
					'title' => 'Kiến Trúc',
					'text' => '',
					'url' => home_url('/thiet-ke-kien-truc'),
				),
				array(
					'icon' => dimhouse_asset_uri('thumbs/layout/layout_2022/[476x1068-cr]muc_nt_3_.jpg__cv.webp'),
					'title' => 'Nội Thất',
					'text' => '',
					'url' => home_url('/du-an-noi-that-2'),
				),
				array(
					'icon' => dimhouse_asset_uri('thumbs/layout/layout_2022/[476x1068-cr]muc_tc_4_.jpg__cv.webp'),
					'title' => 'Thi Công',
					'text' => '',
					'url' => home_url('/du-an-thi-cong-1'),
				),
				array(
					'icon' => dimhouse_asset_uri('thumbs/layout/layout_2022/[476x1068-cr]muc_bst_4.jpg__cv.webp'),
					'title' => 'Bộ sưu tập',
					'text' => '',
					'url' => home_url('/bo-suu-tap'),
				),
			),
		),
		'estimate' => array(
			'title' => 'Khách hàng khái toán phí xây nhà  - với 1 phút !',
			'intro' => '',
			'floor_label' => 'Tầng',
			'mezzanine_label' => 'Có lửng hay không ?',
			'preview_image' => dimhouse_asset_uri('uploads/estimate/tang_lung/khong_lung/0_lau_n.jpg'),
			'form_banner_image' => dimhouse_asset_uri('uploads/banner/baner/du_toan_1.jpg'),
			'form_banner_url' => home_url('/khuyen-mai-1'),
			'form_banner_alt' => 'Dự toán chi phí',
			'book_button_label' => 'Đặt lịch tư vấn',
			'construction_form_html' => '',
			'booking_form_html' => '',
			'tabs' => array(
				array(
					'key' => 'tab1',
					'label' => 'Chi phí thiết kế',
					'price_label' => '',
					'banner' => dimhouse_asset_uri('uploads/banner/baner/goi_thietke.jpg'),
					'url' => home_url('/goi-thiet-ke'),
					'content' => '<p>NỘI DUNG HỒ SƠ KIẾN TRÚC – NỘI THẤT:</p><p>Lên kế hoạch làm hồ sơ phương án kiến trúc (có thể 2 hoặc 3 phương án), hồ sơ xin phép xây dựng (bản vẽ theo quy định của Nhà nước), hồ sơ bản vẽ thiết kế thi công và dự toán công trình gồm:</p><ul><li>Cung cấp bản vẽ kiến trúc (có đầy đủ thành phần chi tiết cấu tạo, vật liệu…</li><li>Cung cấp bản vẽ kết cấu công trình</li><li>Cung cấp bản vẽ thiết kế điện</li><li>Cung cấp bản vẽ thiết kế cấp, thoát nước</li><li>Cung cấp bản vẽ thiết kế hệ thống điều hòa không khí</li></ul>',
				),
				array(
					'key' => 'tab2',
					'label' => 'Khái toán phần thô',
					'price_label' => '',
					'banner' => dimhouse_asset_uri('uploads/banner/baner/goi_xd_tho.jpg'),
					'url' => home_url('/khai-toan-phan-tho'),
					'content' => '<p style="margin: 0cm; margin-bottom: .0001pt; text-align: justify; line-height: 16.5pt;"><strong>A. BÁO GIÁ XÂY NHÀ PHẦN THÔ:</strong></p><p>Báo giá thi công phần thô bao gồm trọn gói nhân công + vật liệu xây dựng thô + nhân công phần hoàn thiện và luôn đảm bảo các tiêu chí sau:</p><ul><li>Thi công đúng 100% các hạng mục báo giá trọn gói.</li><li>Đơn giá xây dựng minh bạch, nhanh chóng.</li><li>Luôn đầy đủ nhân công, đảm bảo tiến độ.</li><li>Qui trình quản lý chuẩn.</li><li>Vật liệu xây dựng rõ ràng, minh bạch.</li><li>Báo giá trọn gói, không phát sinh.</li></ul>',
				),
				array(
					'key' => 'tab3',
					'label' => 'Khái toán hoàn thiện',
					'price_label' => '',
					'banner' => dimhouse_asset_uri('uploads/banner/baner/goi_chia_khoa.jpg'),
					'url' => home_url('/chia-khoa-trao-tay'),
					'content' => '<p><strong>NỘI DUNG HỒ SƠ KIẾN TRÚC – NỘI THẤT:</strong></p><p>Nội dung hoàn thiện được tư vấn theo nhu cầu công trình, mức đầu tư và tiêu chuẩn vật tư thực tế của chủ đầu tư.</p>',
				),
			),
		),
		'about' => array(
			'title' => 'Dimhouse Design',
			'text' => '<p>Dimhouse không ngừng theo đuổi hướng thiết kế đổi mới và cách tân trong thi công nhằm đem lại không gian sinh hoạt thông thoáng, hiện đại, đáp ứng tốt nhất nhu cầu sử dụng của khách hàng, đảm bảo sự tối ưu về mặt kỹ thuật, ngân sách và thân thiện với môi trường. Dimhouse lấy nguyện vọng của khách hàng làm đích đến bên cạnh việc bổ sung, tiếp sức bằng các giải pháp kỹ thuật, mỹ thuật và văn hóa sinh hoạt mở trong thời đại mới.</p><p>Công việc chính của Dimhouse là biến ước mơ về không gian sinh hoạt của bạn trở thành hiện thực.</p>',
			'image' => dimhouse_asset_uri('thumbs/banner/baner/[113x113-cr]a1_1646668764.jpg'),
			'person_name' => 'Nguyễn Minh Đức',
			'person_role' => 'CEO & Founder',
			'cta_label' => 'Xem hồ sơ năng lực',
			'cta_url' => home_url('/Dimhouse-portfolio'),
		),
		'channel' => array(
			'title' => 'DIMHOUSE - ChANNEL',
			'articles_title' => 'Tư vấn chia sẻ',
			'articles' => dimhouse_default_channel_articles(),
			'items' => array(
				array(
					'title' => 'Quy Trình Thiết Kế',
					'url' => 'https://youtu.be/QDxXVwbHgMA',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]ytube_quitrinh_thietke.00_00_18_05.still001.jpg__cv.webp'),
				),
				array(
					'title' => 'Giới Thiệu Web Dimhouse',
					'url' => 'https://www.youtube.com/watch?v=7OjQo9EBqyk',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]gioi_thieu_web_dimhouse1.jpg__cv.webp'),
				),
				array(
					'title' => 'Tính Chi Phí Xây Dựng Tự Động',
					'url' => 'https://www.youtube.com/watch?v=iuV3CNOkXEA',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]tumnail_dutoan_tudong_4.jpg__cv.webp'),
				),
				array(
					'title' => 'Hố Sơ Năng Lực',
					'url' => 'https://www.youtube.com/watch?v=X_uQaGlSOms',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]hsnl3.jpg__cv.webp'),
				),
				array(
					'title' => 'Dư Trù Chi Phí Xây Nhà',
					'url' => 'https://www.youtube.com/shorts/C0Pc_hjXTLg',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]fb_du_toan.jpg__cv.webp'),
				),
			),
		),
		'testimonials' => array(
			'title' => 'Cảm nhận khách hàng',
			'text' => '',
			'items' => array(
				array(
					'name' => 'MỸ HƯƠNG',
					'role' => '( Chị )',
					'avatar' => dimhouse_asset_uri('thumbs/advisory/feedback/[90x90-cr]z3705231038744_50c027375536f468beaf304cd16eeef4.jpg__cv.webp'),
					'text' => 'Tôi cảm thấy rất hài lòng, không gian theo đúng ý mình, kể cả màu sắc nữa, rất hài hòa. Cách phục vụ của các bạn cũng rất tốt. Cám ơn Dimhouse đã đem đến cho tôi một không gian sống vô cùng ưng ý.',
				),
			),
		),
		'faq' => array(
			'title' => 'Câu hỏi thường gặp',
			'items' => array(
				array(
					'question' => 'Tôi chưa nhập content riêng thì trang sẽ hiển thị gì?',
					'answer' => '<p>Homepage sẽ tự dùng fallback theo bản tĩnh để vẫn giữ đúng bố cục và nội dung mặc định.</p>',
				),
				array(
					'question' => 'Có bị mất layout khi thiếu ảnh không?',
					'answer' => '<p>Không, các section chính sẽ tự lấy ảnh mặc định trong theme để không bị vỡ khối.</p>',
				),
				array(
					'question' => 'Sau này có thể thay bằng content riêng không?',
					'answer' => '<p>Có. Chỉ cần nhập nội dung trong ACF, dữ liệu riêng sẽ được ưu tiên hiển thị.</p>',
				),
			),
		),
		'contact' => array(
			'title' => 'LIÊN HỆ VỚI CHÚNG TÔI',
			'text' => '<p>Thông tin liên hệ mặc định được giữ sẵn để người dùng vẫn có thể tra cứu khi bạn chưa nhập dữ liệu riêng.</p>',
			'shortcode' => '',
			'items' => array(
				array(
					'label' => 'Hotline tư vấn:',
					'value' => '0392959713',
					'url' => 'tel:0392959713',
				),
				array(
					'label' => 'Fanpage Facebook:',
					'value' => 'facebook.com/DimHouse.DimDecor',
					'url' => 'https://www.facebook.com/DimHouse.DimDecor',
				),
				array(
					'label' => 'Email tư vấn:',
					'value' => 'dimhousevietnam@gmail.com',
					'url' => 'mailto:dimhousevietnam@gmail.com',
				),
				array(
					'label' => 'Đăng Kí Kinh Doanh:',
					'value' => '10/169 Thái Hà, Đống Đa, Hà Nội',
					'url' => '',
				),
			),
		),
		'footer' => array(
			'footer_logo' => dimhouse_asset_uri('uploads/banner/baner/14_trang_den.png'),
			'footer_title' => 'ĐĂNG KÍ KINH DOANH',
			'footer_text' => '<div><span style="font-size: 15px;">10/169 Thái Hà, Đống Đa, Hà Nội</span></div><div><span style="color: #dda77b;"><strong><span style="font-size: 15px;">VĂN PHÒNG</span></strong></span></div><div><span style="font-size: 15px;">10/169 Thái Hà, Đống Đa, Hà Nội</span></div><div><span style="font-size: 15px; color: #dda77b;">Hotline</span></div><div><span style="font-size: 15px;"><strong>0392959713</strong></span></div>',
			'footer_columns' => array(
				array(
					'title' => 'Fanpage',
					'content' => '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FDimHouse.DimDecor&amp;tabs=timeline&amp;width=340&amp;height=500&amp;small_header=false&amp;adapt_container_width=true&amp;hide_cover=false&amp;show_facepile=false&amp;appId" width="340" height="311" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
				),
				array(
					'title' => 'Thông tin liên hệ',
					'content' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.7037275541857!2d106.7471018153159!3d10.68008916388372!2m3!1f0!2f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31753b730c677b63%3A0xfa33e39535626666!2zS2h1IETDom4gQ8awIEFuaCBUdeG6pW4gR3JlZW4gUml2ZXJzaWRl!5e0!3m2!1sen!2sus!4v1649149804440!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><div class="footer_title social_title">Kết nối với chúng tôi</div><div class="box_social"><div class="row mx-0"><a href="https://www.instagram.com/_dim.decor" target="_blank"><img src="uploads/config/2024_04/instagram.png" alt="Instagram"></a><a href="https://www.facebook.com/DimHouse.DimDecor" target="_blank"><img src="uploads/config/2024_04/fb.png" alt="Facebook"></a><a href="https://www.tiktok.com/@ducdimhouse" target="_blank"><img src="uploads/config/2024_04/tiktok.png" alt="Tiktok"></a><a href="https://www.youtube.com/@DimHouse-l9e" target="_blank"><img src="uploads/config/2024_04/youtube.png" alt="Youtube"></a></div></div><div class="email"><i class="fas fa-envelope"></i> Email: dimhousevietnam@gmail.com</div>',
				),
			),
		),
	);
}
