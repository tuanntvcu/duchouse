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
			'url' => 'https://instagram.com/tahome_design?igshid=YmMyMTA2M2Y=',
			'image' => dimhouse_asset_uri('uploads/config/2024_04/instagram.png'),
		),
		array(
			'label' => 'Facebook',
			'url' => 'https://www.facebook.com/profile.php?id=100065424102650',
			'image' => dimhouse_asset_uri('uploads/config/2024_04/fb.png'),
		),
		array(
			'label' => 'Tiktok',
			'url' => 'https://www.tiktok.com/@tahomedesign?is_from_webapp=1&sender_device=pc',
			'image' => dimhouse_asset_uri('uploads/config/2024_04/tiktok.png'),
		),
		array(
			'label' => 'Youtube',
			'url' => 'https://www.youtube.com/channel/UCXPlJElksDJq8aa8POcxoMA',
			'image' => dimhouse_asset_uri('uploads/config/2024_04/youtube.png'),
		),
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
		$scripts[] = $script;
	}

	if (empty($scripts)) {
		return '';
	}

	return dimhouse_rewrite_clone_asset_urls(implode("\n;\n", $scripts));
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

	return $html;
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
			$class = trim('menu_li ' . ($index === 0 ? 'first' : '') . ($index === $total - 1 ? ' last' : ''));
			$items[] = '<li class="' . esc_attr($class) . '">
        <a href="' . esc_url($url) . '" target="">
            ' . ($image ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '">' : '') . '
            <h3 class="menu_title"><p>' . esc_html($title) . '</p></h3>
        </a>
        <span>T<br>a<br>h<br>o<br>m<br>e</span>
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
		}

		if (!empty($hero['title']) || !empty($hero['text'])) {
			$title = !empty($hero['title']) ? $hero['title'] : 'T A - H O M E';
			$text = !empty($hero['text']) ? $hero['text'] : '<p><strong>Thiáº¿t Káº¿ Kiáº¿n TrÃºc - Thi CÃ´ng XÃ¢y Dá»±ng</strong></p>';
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section section-0"[\s\S]*?<div class="inner">\s*)<h2>[\s\S]*?</h2>\s*<p>[\s\S]*?</p>#',
				function ($matches) use ($title, $text) {
					return $matches[1] . '<h2>' . esc_html($title) . '</h2>' . wp_kses_post($text);
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
	if (!empty($process['banner'])) {
		$banner_url = dimhouse_clone_img_url($process['banner']);
		if ($banner_url) {
			$html = dimhouse_replace_first_match(
				$html,
				'#(<div class="section_home_about"[\s\S]*?<div class="banner_item"[\s\S]*?<img\s+src=")[^"]*(")#',
				function ($matches) use ($banner_url) {
					return $matches[1] . esc_url($banner_url) . $matches[2];
				}
			);
		}
	}

	$menu_grid = dimhouse_home_acf_layout('menu_grid');
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
				'<h3 class="menu_title"><p>' . esc_html($title) . '</p></h3></a><span>T<br>a<br>h<br>o<br>m<br>e</span></li>';
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

	$channel = dimhouse_home_acf_layout('channel');
	if (!empty($channel['title'])) {
		$html = dimhouse_replace_first_match(
			$html,
			'#(<div class="section section-5"[\s\S]*?<h4>)[\s\S]*?(</h4>)#',
			function ($matches) use ($channel) {
				return $matches[1] . esc_html($channel['title']) . $matches[2];
			}
		);
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

	return $html;
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
			'eyebrow' => 'TAHOME',
			'title' => 'T A - H O M E',
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
			'title' => 'Khách hàng khai toán phí xây nhà  - với 1 phút !',
			'intro' => '',
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
					'label' => 'Khai toán phần thô',
					'price_label' => '',
					'banner' => dimhouse_asset_uri('uploads/banner/baner/goi_xd_tho.jpg'),
					'url' => home_url('/khai-toan-phan-tho'),
					'content' => '<p style="margin: 0cm; margin-bottom: .0001pt; text-align: justify; line-height: 16.5pt;"><strong>A. BÁO GIÁ XÂY NHÀ PHẦN THÔ:</strong></p><p>Báo giá thi công phần thô bao gồm trọn gói nhân công + vật liệu xây dựng thô + nhân công phần hoàn thiện và luôn đảm bảo các tiêu chí sau:</p><ul><li>Thi công đúng 100% các hạng mục báo giá trọn gói.</li><li>Đơn giá xây dựng minh bạch, nhanh chóng.</li><li>Luôn đầy đủ nhân công, đảm bảo tiến độ.</li><li>Qui trình quản lý chuẩn.</li><li>Vật liệu xây dựng rõ ràng, minh bạch.</li><li>Báo giá trọn gói, không phát sinh.</li></ul>',
				),
				array(
					'key' => 'tab3',
					'label' => 'Khai toán hoàn thiện',
					'price_label' => '',
					'banner' => dimhouse_asset_uri('uploads/banner/baner/goi_chia_khoa.jpg'),
					'url' => home_url('/chia-khoa-trao-tay'),
					'content' => '<p><strong>NỘI DUNG HỒ SƠ KIẾN TRÚC – NỘI THẤT:</strong></p><p>Nội dung hoàn thiện được tư vấn theo nhu cầu công trình, mức đầu tư và tiêu chuẩn vật tư thực tế của chủ đầu tư.</p>',
				),
			),
		),
		'about' => array(
			'title' => 'TA - HOME Design',
			'text' => '<p>TA-Home không ngừng theo đuổi hướng thiết kế đổi mới và cách tân trong thi công nhằm đem lại không gian sinh hoạt thông thoáng, hiện đại, đáp ứng tốt nhất nhu cầu sử dụng của khách hàng, đảm bảo sự tối ưu về mặt kỹ thuật, ngân sách và thân thiện với môi trường. TA-Home lấy nguyện vọng của khách hàng làm đích đến bên cạnh việc bổ sung, tiếp sức bằng các giải pháp kỹ thuật, mỹ thuật và văn hóa sinh hoạt mở trong thời đại mới.</p><p>Công việc chính của TA-Home là biến ước mơ về không gian sinh hoạt của bạn trở thành hiện thực.</p>',
			'image' => dimhouse_asset_uri('thumbs/banner/baner/[113x113-cr]a1_1646668764.jpg'),
		),
		'channel' => array(
			'title' => 'TAHOME - ChANNEL',
			'items' => array(
				array(
					'title' => 'Quy Trình Thiết Kế',
					'url' => 'https://youtu.be/QDxXVwbHgMA',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]ytube_quitrinh_thietke.00_00_18_05.still001.jpg__cv.webp'),
				),
				array(
					'title' => 'Giới Thiệu Web TA - HOME',
					'url' => 'https://www.youtube.com/watch?v=7OjQo9EBqyk',
					'image' => dimhouse_asset_uri('thumbs/product/2022_09/tumbnail_chanel/[580x400-cr]gioi_thieu_web_tahome1.jpg__cv.webp'),
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
					'text' => 'Tôi cảm thấy rất hài lòng, không gian theo đúng ý mình, kể cả màu sắc nữa, rất hài hòa. Cách phục vụ của các bạn cũng rất tốt. Cám ơn TA - Home đã đem đến cho tôi một không gian sống vô cùng ưng ý.',
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
					'value' => '0964 158 163',
					'url' => 'tel:0964158163',
				),
				array(
					'label' => 'Fanpage Facebook:',
					'value' => 'facebook.com/TAHome Design',
					'url' => 'https://www.facebook.com/profile.php?id=100065424102650',
				),
				array(
					'label' => 'Email tư vấn:',
					'value' => 'info@RdMJAeP6qJeR.vn',
					'url' => 'mailto:info@RdMJAeP6qJeR.vn',
				),
				array(
					'label' => 'Đăng Kí Kinh Doanh:',
					'value' => 'N9-13 KDC Anh Tuấn Riverside, Huỳnh Tấn Phát.',
					'url' => '',
				),
			),
		),
		'footer' => array(
			'footer_logo' => dimhouse_asset_uri('uploads/banner/baner/14_trang_den.png'),
			'footer_title' => 'ĐĂNG KÍ KINH DOANH',
			'footer_text' => '<div><span style="font-size: 15px;">N9-13 KDC Anh Tuấn Riverside, Huỳnh Tấn Phát.</span></div><div><span style="color: #ffba00;"><strong><span style="font-size: 15px;">VĂN PHÒNG</span></strong></span></div><div><span style="font-size: 15px;">Sảnh G, Block A4 ( View Sông ) CC ERA Town, Nguyễn Lương Bằng Quận 7. TP HCM</span></div><div><span style="font-size: 15px; color: #ffba00;">Hotline</span></div><div><span style="font-size: 15px;"><strong>0964.158.163</strong></span></div>',
			'footer_columns' => array(
				array(
					'title' => 'Fanpage',
					'content' => '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fprofile.php%3Fid%3D100065424102650&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="340" height="311" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
				),
				array(
					'title' => 'Thông tin liên hệ',
					'content' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.7037275541857!2d106.7471018153159!3d10.68008916388372!2m3!1f0!2f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31753b730c677b63%3A0xfa33e39535626666!2zS2h1IETDom4gQ8awIEFuaCBUdeG6pW4gR3JlZW4gUml2ZXJzaWRl!5e0!3m2!1sen!2sus!4v1649149804440!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><div class="footer_title social_title">Kết nối với chúng tôi</div><div class="box_social"><div class="row mx-0"><a href="https://instagram.com/tahome_design?igshid=YmMyMTA2M2Y=" target="_blank"><img src="uploads/config/2024_04/instagram.png" alt="Instagram"></a><a href="https://www.facebook.com/profile.php?id=100065424102650" target="_blank"><img src="uploads/config/2024_04/fb.png" alt="Facebook"></a><a href="https://www.tiktok.com/@tahomedesign?is_from_webapp=1&sender_device=pc" target="_blank"><img src="uploads/config/2024_04/tiktok.png" alt="Tiktok"></a><a href="https://www.youtube.com/channel/UCXPlJElksDJq8aa8POcxoMA" target="_blank"><img src="uploads/config/2024_04/youtube.png" alt="Youtube"></a></div></div><div class="email"><i class="fas fa-envelope"></i> Email: info@RdMJAeP6qJeR.vn</div>',
				),
			),
		),
	);
}

