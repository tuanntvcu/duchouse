<?php

if (!defined('ABSPATH')) {
	exit;
}

add_action('wp_ajax_dimhouse_legacy_ajax', 'dimhouse_legacy_ajax');
add_action('wp_ajax_nopriv_dimhouse_legacy_ajax', 'dimhouse_legacy_ajax');

function dimhouse_legacy_ajax() {
	$module = isset($_POST['m']) ? sanitize_key(wp_unslash($_POST['m'])) : '';
	$action = isset($_POST['f']) ? sanitize_key(wp_unslash($_POST['f'])) : '';

	if ($module !== 'global') {
		dimhouse_legacy_json(array(
			'ok' => 0,
			'mess' => dimhouse_estimate_label('form_required_message', 'Request khong hop le.'),
		));
	}

	if ($action === 'construction') {
		dimhouse_legacy_json(dimhouse_ajax_construction());
	}

	if ($action === 'load_pic_review') {
		dimhouse_legacy_json(dimhouse_ajax_load_pic_review());
	}

	if ($action === 'form_book') {
		dimhouse_legacy_json(dimhouse_ajax_form_book());
	}

	dimhouse_legacy_json(array(
		'ok' => 0,
		'mess' => dimhouse_estimate_label('form_required_message', 'Chuc nang chua duoc ho tro.'),
	));
}

function dimhouse_legacy_json($payload) {
	echo wp_json_encode($payload);
	wp_die();
}

function dimhouse_legacy_post_data() {
	$raw = isset($_POST['data']) ? wp_unslash($_POST['data']) : array();
	$values = array();

	if (!is_array($raw)) {
		return $values;
	}

	foreach ($raw as $item) {
		if (!is_array($item) || !isset($item['name'])) {
			continue;
		}

		$name = sanitize_text_field($item['name']);
		$value = isset($item['value']) ? sanitize_text_field($item['value']) : '';

		if (preg_match('/^roof\[(\d+)\]$/', $name, $matches)) {
			if (!isset($values['roof']) || !is_array($values['roof'])) {
				$values['roof'] = array();
			}
			$values['roof'][(int) $matches[1]] = dimhouse_to_float($value);
			continue;
		}

		$values[$name] = $value;
	}

	return $values;
}

function dimhouse_to_float($value) {
	if (is_numeric($value)) {
		return (float) $value;
	}

	$value = preg_replace('/[^0-9.\-]/', '', (string) $value);
	return is_numeric($value) ? (float) $value : 0.0;
}

function dimhouse_value_float($values, $key) {
	return isset($values[$key]) ? dimhouse_to_float($values[$key]) : 0.0;
}

function dimhouse_format_vnd($amount) {
	$amount = max(0, (int) round($amount / 1000) * 1000);
	return number_format($amount, 0, ',', '.') . ' VNĐ';
}

function dimhouse_format_unit($amount) {
	return number_format((int) round($amount), 0, ',', '.');
}

function dimhouse_estimate_setting($field_name, $default) {
	$value = function_exists('dimhouse_option') ? dimhouse_option($field_name, null) : null;
	if ($value === null || $value === false || $value === '') {
		return $default;
	}

	return is_numeric($value) ? (float) $value : $default;
}

function dimhouse_estimate_label($field_name, $default) {
	return function_exists('dimhouse_option') ? dimhouse_option($field_name, $default) : $default;
}

function dimhouse_estimate_config() {
	return array(
		'design_landscape_rate' => dimhouse_estimate_setting('estimate_design_landscape_rate', 100000),
		'design_interior_rate' => dimhouse_estimate_setting('estimate_design_interior_rate', 150000),
		'design_architecture_rate' => dimhouse_estimate_setting('estimate_design_architecture_rate', 150000),
		'design_architecture_interior_rate' => dimhouse_estimate_setting('estimate_design_architecture_interior_rate', 250000),
		'completion_high_rate' => dimhouse_estimate_setting('estimate_completion_high_rate', 3000000),
		'completion_good_rate' => dimhouse_estimate_setting('estimate_completion_good_rate', 2600000),
		'completion_standard_rate' => dimhouse_estimate_setting('estimate_completion_standard_rate', 2300000),
		'foundation_pile_design_coeff' => dimhouse_estimate_setting('estimate_foundation_pile_design_coeff', 0),
		'foundation_pile_raw_coeff' => dimhouse_estimate_setting('estimate_foundation_pile_raw_coeff', 0.5),
		'foundation_strip_design_coeff' => dimhouse_estimate_setting('estimate_foundation_strip_design_coeff', 0.2),
		'foundation_strip_raw_coeff' => dimhouse_estimate_setting('estimate_foundation_strip_raw_coeff', 0.7),
		'basement_depth_min' => dimhouse_estimate_setting('estimate_basement_depth_min', 1),
		'basement_coeff_shallow' => dimhouse_estimate_setting('estimate_basement_coeff_shallow', 1.5),
		'basement_coeff_medium' => dimhouse_estimate_setting('estimate_basement_coeff_medium', 1.7),
		'basement_coeff_deep' => dimhouse_estimate_setting('estimate_basement_coeff_deep', 2),
		'basement_coeff_extra_deep' => dimhouse_estimate_setting('estimate_basement_coeff_extra_deep', 2.5),
		'mezzanine_floor_coeff' => dimhouse_estimate_setting('estimate_mezzanine_floor_coeff', 1),
		'mezzanine_nofloor_coeff' => dimhouse_estimate_setting('estimate_mezzanine_nofloor_coeff', 0.5),
		'terrace_roof_coeff' => dimhouse_estimate_setting('estimate_terrace_roof_coeff', 1),
		'terrace_noroof_coeff' => dimhouse_estimate_setting('estimate_terrace_noroof_coeff', 0.7),
		'yard_coeff' => dimhouse_estimate_setting('estimate_yard_coeff', 0.5),
		'roof_1_design_coeff' => dimhouse_estimate_setting('estimate_roof_1_design_coeff', 0),
		'roof_1_completion_coeff' => dimhouse_estimate_setting('estimate_roof_1_completion_coeff', 0.5),
		'roof_2_design_coeff' => dimhouse_estimate_setting('estimate_roof_2_design_coeff', -0.2),
		'roof_2_completion_coeff' => dimhouse_estimate_setting('estimate_roof_2_completion_coeff', 0.3),
		'roof_3_design_coeff' => dimhouse_estimate_setting('estimate_roof_3_design_coeff', 0.2),
		'roof_3_completion_coeff' => dimhouse_estimate_setting('estimate_roof_3_completion_coeff', 0.7),
		'roof_4_design_coeff' => dimhouse_estimate_setting('estimate_roof_4_design_coeff', 0.5),
		'roof_4_completion_coeff' => dimhouse_estimate_setting('estimate_roof_4_completion_coeff', 1),
		'crude_tiny_max_area' => dimhouse_estimate_setting('estimate_crude_tiny_max_area', 150),
		'crude_tiny_unit' => dimhouse_estimate_setting('estimate_crude_tiny_unit', 4200000),
		'crude_small_unit' => dimhouse_estimate_setting('estimate_crude_small_unit', 4000000),
		'crude_medium_unit' => dimhouse_estimate_setting('estimate_crude_medium_unit', 3950000),
		'crude_large_unit' => dimhouse_estimate_setting('estimate_crude_large_unit', 3800000),
		'crude_special_min_area' => dimhouse_estimate_setting('estimate_crude_special_min_area', 300),
		'crude_special_max_area' => dimhouse_estimate_setting('estimate_crude_special_max_area', 350),
		'crude_special_unit' => dimhouse_estimate_setting('estimate_crude_special_unit', 39800000),
	);
}

function dimhouse_ajax_construction() {
	$values = dimhouse_legacy_post_data();

	foreach (array('full_name', 'phone', 'type', 'foundation', 'foundation_area', 'ground_floor', 'num_bedroom', 'num_wc') as $required) {
		if (empty($values[$required])) {
			return array(
				'ok' => 0,
				'mess' => dimhouse_estimate_label('form_required_message', 'Vui long nhap day du thong tin bat buoc.'),
			);
		}
	}

	$estimate = dimhouse_calculate_construction($values);
	$config = $estimate['config'];

	return array(
		'ok' => 1,
		'mess' => '',
		'html_design_type' => dimhouse_render_design_options($estimate['design_area'], $config),
		'html_crude' => '',
		'html_completion' => dimhouse_render_completion_options($estimate['completion_area'], $config),
		'tab1_price' => dimhouse_format_vnd($estimate['design']['landscape']),
		'tab2_price' => dimhouse_format_vnd($estimate['crude_total']),
		'tab3_price' => dimhouse_format_vnd($estimate['completion']['high']),
		'summary' => $estimate,
	);
}

function dimhouse_calculate_construction($values) {
	$config = dimhouse_estimate_config();
	$floor_keys = array('first_floor', 'second_floor', 'third_floor', 'fourth_floor', 'fifth_floor');
	$floor_area = 0.0;

	foreach ($floor_keys as $key) {
		$floor_area += dimhouse_value_float($values, $key);
	}

	$ground_area = dimhouse_value_float($values, 'ground_floor');
	$foundation_area = dimhouse_value_float($values, 'foundation_area');
	$basement_area = dimhouse_value_float($values, 'basement_area');
	$basement_depth = dimhouse_value_float($values, 'basement_depth');
	$mezzanine_floor = dimhouse_value_float($values, 'mezzanine_floor');
	$mezzanine_nofloor = dimhouse_value_float($values, 'mezzanine_nofloor');
	$terrace_roof = dimhouse_value_float($values, 'terrace_roof');
	$terrace_noroof = dimhouse_value_float($values, 'terrace_noroof');
	$yard = dimhouse_value_float($values, 'yard');

	$roof = isset($values['roof']) && is_array($values['roof']) ? $values['roof'] : array();

	$foundation_type = isset($values['foundation']) ? (int) $values['foundation'] : 0;

	$basement_coeff = 0.0;
	if ($basement_area > 0) {
		if ($basement_depth < $config['basement_depth_min']) {
			$basement_coeff = 0.0;
		} elseif ($basement_depth >= 2.2) {
			$basement_coeff = $config['basement_coeff_extra_deep'];
		} elseif ($basement_depth >= 1.7) {
			$basement_coeff = $config['basement_coeff_deep'];
		} elseif ($basement_depth >= 1.3) {
			$basement_coeff = $config['basement_coeff_medium'];
		} else {
			$basement_coeff = $config['basement_coeff_shallow'];
		}
	}

	$foundation_design_coeff = $foundation_type === 2 ? $config['foundation_strip_design_coeff'] : $config['foundation_pile_design_coeff'];
	$foundation_raw_coeff = $foundation_type === 2 ? $config['foundation_strip_raw_coeff'] : $config['foundation_pile_raw_coeff'];

	$base_area =
		$ground_area +
		$floor_area +
		($foundation_area * $foundation_design_coeff) +
		($basement_area * $basement_coeff) +
		($mezzanine_floor * $config['mezzanine_floor_coeff']) +
		($mezzanine_nofloor * $config['mezzanine_nofloor_coeff']) +
		($terrace_roof * $config['terrace_roof_coeff']) +
		($terrace_noroof * $config['terrace_noroof_coeff']) +
		($yard * $config['yard_coeff']);

	$roof_design_coefficients = array(
		1 => $config['roof_1_design_coeff'],
		2 => $config['roof_2_design_coeff'],
		3 => $config['roof_3_design_coeff'],
		4 => $config['roof_4_design_coeff'],
	);
	$roof_completion_coefficients = array(
		1 => $config['roof_1_completion_coeff'],
		2 => $config['roof_2_completion_coeff'],
		3 => $config['roof_3_completion_coeff'],
		4 => $config['roof_4_completion_coeff'],
	);

	$roof_design_area = 0.0;
	$roof_completion_area = 0.0;
	foreach ($roof_completion_coefficients as $roof_type => $coeff) {
		$roof_value = isset($roof[$roof_type]) ? dimhouse_to_float($roof[$roof_type]) : 0.0;
		$roof_completion_area += $roof_value * $coeff;
		$roof_design_area += $roof_value * (isset($roof_design_coefficients[$roof_type]) ? $roof_design_coefficients[$roof_type] : 0.0);
	}

	$design_area = max(0, $base_area + $roof_design_area);
	$completion_area = max(0, $base_area + $roof_completion_area);

	$raw_area =
		$ground_area +
		$floor_area +
		($foundation_area * $foundation_raw_coeff) +
		($basement_area * $basement_coeff) +
		($mezzanine_floor * $config['mezzanine_floor_coeff']) +
		($mezzanine_nofloor * $config['mezzanine_nofloor_coeff']) +
		($terrace_roof * $config['terrace_roof_coeff']) +
		($terrace_noroof * $config['terrace_noroof_coeff']) +
		$roof_completion_area +
		($yard * $config['yard_coeff']);

	if ($raw_area >= $config['crude_special_min_area'] && $raw_area < $config['crude_special_max_area']) {
		$crude_unit = $config['crude_special_unit'];
	} elseif ($raw_area < $config['crude_tiny_max_area']) {
		$crude_unit = $config['crude_tiny_unit'];
	} elseif ((float) $raw_area === (float) $config['crude_tiny_max_area']) {
		$crude_unit = $config['crude_small_unit'];
	} elseif ($raw_area < $config['crude_special_min_area']) {
		$crude_unit = $config['crude_medium_unit'];
	} else {
		$crude_unit = $config['crude_large_unit'];
	}

	return array(
		'config' => $config,
		'design_area' => $design_area,
		'construction_area' => $raw_area,
		'completion_area' => $completion_area,
		'crude_unit' => $crude_unit,
		'crude_total' => $raw_area * $crude_unit,
		'design' => array(
			'landscape' => $design_area * $config['design_landscape_rate'],
			'interior' => $design_area * $config['design_interior_rate'],
			'architecture' => $design_area * $config['design_architecture_rate'],
			'architecture_interior' => $design_area * $config['design_architecture_interior_rate'],
		),
		'completion' => array(
			'high' => $completion_area * $config['completion_high_rate'],
			'good' => $completion_area * $config['completion_good_rate'],
			'standard' => $completion_area * $config['completion_standard_rate'],
		),
	);
}

function dimhouse_render_design_options($area, $config) {
	$options = array(
		array('name' => 'design_type', 'label' => 'Cảnh quan', 'rate' => $config['design_landscape_rate'], 'item' => 'design_type-content-9', 'checked' => true),
		array('name' => 'design_type', 'label' => 'Thiết kế nội thất', 'rate' => $config['design_interior_rate'], 'item' => 'design_type-content-5'),
		array('name' => 'design_type', 'label' => 'Thiết kế kiến trúc', 'rate' => $config['design_architecture_rate'], 'item' => 'design_type-content-3'),
		array('name' => 'design_type', 'label' => 'Kiến trúc - nội thất', 'rate' => $config['design_architecture_interior_rate'], 'item' => 'design_type-content-1'),
	);

	return dimhouse_render_radio_price_list($options, $area);
}

function dimhouse_render_completion_options($area, $config) {
	$options = array(
		array('name' => 'estimate_completion', 'label' => 'Gói cao cấp', 'rate' => $config['completion_high_rate'], 'item' => 'completion-content-5', 'stars' => 4, 'checked' => true),
		array('name' => 'estimate_completion', 'label' => 'Gói khá', 'rate' => $config['completion_good_rate'], 'item' => 'completion-content-3', 'stars' => 3),
		array('name' => 'estimate_completion', 'label' => 'Gói trung bình', 'rate' => $config['completion_standard_rate'], 'item' => 'completion-content-1', 'stars' => 2),
	);

	return dimhouse_render_radio_price_list($options, $area);
}

function dimhouse_render_radio_price_list($options, $area) {
	$html = '';
	$label_fields = array(
		'design_type-content-9' => array('estimate_design_landscape_label', 'Cảnh quan'),
		'design_type-content-5' => array('estimate_design_interior_label', 'Thiết kế nội thất'),
		'design_type-content-3' => array('estimate_design_architecture_label', 'Thiết kế kiến trúc'),
		'design_type-content-1' => array('estimate_design_architecture_interior_label', 'Kiến trúc - nội thất'),
		'completion-content-5' => array('estimate_completion_high_label', 'Gói cao cấp'),
		'completion-content-3' => array('estimate_completion_good_label', 'Gói khá'),
		'completion-content-1' => array('estimate_completion_standard_label', 'Gói trung bình'),
	);

	foreach ($options as $option) {
		if (!empty($option['item']) && isset($label_fields[$option['item']])) {
			$option['label'] = dimhouse_estimate_label($label_fields[$option['item']][0], $label_fields[$option['item']][1]);
		}
		$total = $area * $option['rate'];
		$html .= '<li>';
		$html .= '<label class="radio_css"><input name="' . esc_attr($option['name']) . '" type="radio" value="' . esc_attr(dimhouse_format_vnd($total)) . '"' . (!empty($option['checked']) ? ' checked="checked"' : '') . ' data-item="' . esc_attr($option['item']) . '">' . esc_html($option['label']) . '</label>';

		if (isset($option['stars'])) {
			$html .= '<div class="list_star">';
			for ($i = 0; $i < (int) $option['stars']; $i++) {
				$html .= '<span class="star"><span class="material-icons">star</span></span>';
			}
			$html .= '</div>';
		} else {
			$html .= '<div class="price"><span class="number">' . esc_html(dimhouse_format_unit($option['rate'])) . '</span> <span class="unit">VNĐ/m<sup>2</sup></span></div>';
		}

		$html .= '</li>';
	}

	return $html;
}

function dimhouse_ajax_load_pic_review() {
	$floor = isset($_POST['floor']) ? (int) $_POST['floor'] : 0;
	$mezzanine = isset($_POST['mezzanine']) ? (int) $_POST['mezzanine'] : 0;

	if ($floor < 0 || $floor > 5) {
		return array(
			'ok' => 0,
			'mess' => dimhouse_estimate_label('invalid_floor_message', 'So lau khong hop le.'),
		);
	}

	if ($mezzanine < 0 || $mezzanine > 1) {
		return array(
			'ok' => 0,
			'mess' => dimhouse_estimate_label('invalid_mezzanine_message', 'So lung khong hop le.'),
			'mezzanine' => 0,
		);
	}

	if ($mezzanine === 1 && $floor === 0) {
		return array(
			'ok' => 0,
			'html' => '',
			'mess' => dimhouse_estimate_label('invalid_mezzanine_zero_floor_message', 'So lung khong vuot qua 0.'),
			'mezzanine' => 0,
		);
	}

	$preview_paths = array(
		0 => array(
			0 => 'uploads/estimate/tang_lung/khong_lung/0_lau_n.jpg',
			1 => 'uploads/estimate/tang_lung/khong_lung/1_lau_n.jpg',
			2 => 'uploads/estimate/tang_lung/khong_lung/2_lau_n.jpg',
			3 => 'uploads/estimate/tang_lung/khong_lung/3_lau_n.jpg',
			4 => 'uploads/estimate/tang_lung/khong_lung/4-lau_n.jpg',
			5 => 'uploads/estimate/tang_lung/khong_lung/5-lau_n.jpg',
		),
		1 => array(
			1 => 'uploads/estimate/tang_lung/co_lung/1lau.jpg',
			2 => 'uploads/estimate/tang_lung/co_lung/2lau.jpg',
			3 => 'uploads/estimate/tang_lung/co_lung/3lau.jpg',
			4 => 'uploads/estimate/tang_lung/co_lung/4lau.jpg',
			5 => 'uploads/estimate/tang_lung/co_lung/5lau.jpg',
		),
	);

	$path = isset($preview_paths[$mezzanine][$floor])
		? $preview_paths[$mezzanine][$floor]
		: 'uploads/estimate/tang_lung/khong_lung/0_lau_n.jpg';

	if (!file_exists(get_theme_file_path('/' . $path))) {
		return array(
			'ok' => 0,
			'html' => '',
			'mess' => dimhouse_estimate_label('missing_preview_image_message', 'Khong tim thay anh xem truoc.'),
			'mezzanine' => 0,
		);
	}

	$alt = sprintf('Anh %d lau%s', $floor, $mezzanine === 1 ? ' co lung' : ' khong lung');

	return array(
		'ok' => 1,
		'html' => '<img src="' . esc_url(dimhouse_asset_uri($path)) . '" alt="' . esc_attr($alt) . '">',
		'mess' => '',
		'mezzanine' => -1,
	);
}

function dimhouse_ajax_form_book() {
	$values = dimhouse_legacy_post_data();

	if (empty($values['full_name']) || empty($values['phone'])) {
		return array(
			'ok' => 0,
			'mess' => dimhouse_estimate_label('form_required_message', 'Vui long nhap ho ten va so dien thoai.'),
		);
	}

	return array(
		'ok' => 1,
		'mess' => dimhouse_estimate_label('form_success_message', 'Dat lich thanh cong!'),
	);
}
