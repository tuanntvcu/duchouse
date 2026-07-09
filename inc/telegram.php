<?php

if (!defined('ABSPATH')) {
	exit;
}

const DIMHOUSE_DEFAULT_TELEGRAM_BOT_TOKEN = '8602579156:AAGhq83QK1Z2u4-0zeXtKL3NuvRcfozf-L0';
const DIMHOUSE_DEFAULT_TELEGRAM_CHAT_ID = '403416136';

add_action('dimhouse_send_telegram_notification_event', 'dimhouse_send_telegram_notification', 10, 3);

function dimhouse_queue_telegram_notification($values, $form_name = 'form_book', $submission_id = 0) {
	if (!is_array($values)) {
		return false;
	}

	$scheduled = wp_schedule_single_event(
		time() + 1,
		'dimhouse_send_telegram_notification_event',
		array($values, sanitize_key($form_name), (int) $submission_id)
	);

	if (is_wp_error($scheduled)) {
		error_log('Dimhouse Telegram notification queue failed: ' . $scheduled->get_error_message());
		return false;
	}

	if (function_exists('spawn_cron')) {
		spawn_cron();
	}

	return true;
}

function dimhouse_send_telegram_notification($values, $form_name = 'form_book', $submission_id = 0) {
	$bot_token = dimhouse_get_telegram_bot_token();
	$chat_id = dimhouse_get_telegram_chat_id();

	if ($bot_token === '' || $chat_id === '') {
		error_log('Dimhouse Telegram notification: config missing.');
		return false;
	}

	$message = dimhouse_build_telegram_message($values, $form_name, $submission_id);
	if ($message === '') {
		return false;
	}

	$response = wp_remote_post(
		esc_url_raw('https://api.telegram.org/bot' . $bot_token . '/sendMessage'),
		array(
			'timeout' => 1,
			'redirection' => 0,
			'blocking' => true,
			'body' => array(
				'chat_id' => $chat_id,
				'text' => $message,
				'parse_mode' => 'HTML',
				'disable_web_page_preview' => true,
			),
		)
	);

	if (is_wp_error($response)) {
		error_log('Dimhouse Telegram notification failed: ' . $response->get_error_message());
		return false;
	}

	return true;
}

function dimhouse_get_telegram_bot_token() {
	$bot_token = defined('DIMHOUSE_TELEGRAM_BOT_TOKEN') ? DIMHOUSE_TELEGRAM_BOT_TOKEN : '';

	if ($bot_token === '' || $bot_token === 'YOUR_BOT_TOKEN_HERE') {
		$bot_token = DIMHOUSE_DEFAULT_TELEGRAM_BOT_TOKEN;
	}

	return sanitize_text_field($bot_token);
}

function dimhouse_get_telegram_chat_id() {
	$chat_id = defined('DIMHOUSE_TELEGRAM_CHAT_ID') ? DIMHOUSE_TELEGRAM_CHAT_ID : '';

	if ($chat_id === '' || $chat_id === 'YOUR_CHAT_ID_HERE') {
		$chat_id = DIMHOUSE_DEFAULT_TELEGRAM_CHAT_ID;
	}

	return sanitize_text_field($chat_id);
}

function dimhouse_build_telegram_message($values, $form_name = 'form_book', $submission_id = 0) {
	if (!is_array($values)) {
		return '';
	}

	$form_name = sanitize_key($form_name);
	$is_construction = $form_name === 'construction';
	$title = $is_construction
		? 'Dimhouse - Khai toan xay dung moi'
		: 'Dimhouse - Yeu cau tu van moi';

	$lines = array(
		'<b>' . esc_html($title) . '</b>',
		'',
	);

	if ($submission_id) {
		dimhouse_telegram_add_line($lines, 'Submission ID', $submission_id);
	}

	dimhouse_telegram_add_line($lines, 'Ho ten', dimhouse_telegram_value($values, 'full_name'));
	dimhouse_telegram_add_line($lines, 'So dien thoai', dimhouse_telegram_value($values, 'phone'));
	dimhouse_telegram_add_line($lines, 'Email', dimhouse_telegram_value($values, 'email'));
	dimhouse_telegram_add_line($lines, 'Thoi gian tu van', dimhouse_telegram_value($values, 'time'));
	dimhouse_telegram_add_line($lines, 'Loai cong trinh', dimhouse_telegram_project_type_label(dimhouse_telegram_value($values, 'type')));
	dimhouse_telegram_add_line($lines, 'Dia chi', dimhouse_telegram_location($values));

	if ($is_construction) {
		dimhouse_telegram_add_line($lines, 'Loai mong', dimhouse_telegram_foundation_label(dimhouse_telegram_value($values, 'foundation')));
		dimhouse_telegram_add_line($lines, 'Dien tich mong', dimhouse_telegram_value($values, 'foundation_area'));
		dimhouse_telegram_add_line($lines, 'Dien tich ham', dimhouse_telegram_value($values, 'basement_area'));
		dimhouse_telegram_add_line($lines, 'Do sau ham', dimhouse_telegram_value($values, 'basement_depth'));
		dimhouse_telegram_add_line($lines, 'Tang tret', dimhouse_telegram_value($values, 'ground_floor'));
		dimhouse_telegram_add_line($lines, 'Lau 1', dimhouse_telegram_value($values, 'first_floor'));
		dimhouse_telegram_add_line($lines, 'Lau 2', dimhouse_telegram_value($values, 'second_floor'));
		dimhouse_telegram_add_line($lines, 'Lau 3', dimhouse_telegram_value($values, 'third_floor'));
		dimhouse_telegram_add_line($lines, 'Lau 4', dimhouse_telegram_value($values, 'fourth_floor'));
		dimhouse_telegram_add_line($lines, 'Lau 5', dimhouse_telegram_value($values, 'fifth_floor'));
		dimhouse_telegram_add_line($lines, 'Lung co san', dimhouse_telegram_value($values, 'mezzanine_floor'));
		dimhouse_telegram_add_line($lines, 'Lung khong san', dimhouse_telegram_value($values, 'mezzanine_nofloor'));
		dimhouse_telegram_add_line($lines, 'San thuong co mai', dimhouse_telegram_value($values, 'terrace_roof'));
		dimhouse_telegram_add_line($lines, 'San thuong khong mai', dimhouse_telegram_value($values, 'terrace_noroof'));
		dimhouse_telegram_add_line($lines, 'Mai', dimhouse_telegram_value($values, 'roof'));
		dimhouse_telegram_add_line($lines, 'Phong ngu', dimhouse_telegram_value($values, 'num_bedroom'));
		dimhouse_telegram_add_line($lines, 'Nha ve sinh', dimhouse_telegram_value($values, 'num_wc'));
		dimhouse_telegram_add_line($lines, 'San', dimhouse_telegram_value($values, 'yard'));
	}

	dimhouse_telegram_add_line($lines, 'Dien tich', dimhouse_telegram_value($values, 'area'));
	dimhouse_telegram_add_line($lines, 'Ngan sach / Gia tam tinh', dimhouse_telegram_value($values, 'price'));
	dimhouse_telegram_add_line($lines, 'Noi dung tu van', dimhouse_telegram_value($values, 'short'));
	dimhouse_telegram_add_line($lines, 'Ghi chu', dimhouse_telegram_value($values, 'content'));

	if (!empty($values['_estimate']) && is_array($values['_estimate'])) {
		$lines[] = '';
		$lines[] = '<b>Ket qua khai toan</b>';
		dimhouse_telegram_add_line($lines, 'Dien tich thiet ke', dimhouse_telegram_value($values['_estimate'], 'design_area'));
		dimhouse_telegram_add_line($lines, 'Dien tich xay dung', dimhouse_telegram_value($values['_estimate'], 'construction_area'));
		dimhouse_telegram_add_line($lines, 'Dien tich hoan thien', dimhouse_telegram_value($values['_estimate'], 'completion_area'));
		dimhouse_telegram_add_line($lines, 'Don gia phan tho', dimhouse_telegram_value($values['_estimate'], 'crude_unit'));
		dimhouse_telegram_add_line($lines, 'He so tinh/thanh', dimhouse_telegram_value($values['_estimate'], 'province_coeff'));
		dimhouse_telegram_add_line($lines, 'Gia thiet ke', dimhouse_telegram_value($values['_estimate'], 'design_price'));
		dimhouse_telegram_add_line($lines, 'Gia phan tho', dimhouse_telegram_value($values['_estimate'], 'crude_price'));
		dimhouse_telegram_add_line($lines, 'Gia hoan thien', dimhouse_telegram_value($values['_estimate'], 'completion_price'));
	}

	$lines[] = '';
	dimhouse_telegram_add_line($lines, 'Trang gui form', wp_get_referer());
	dimhouse_telegram_add_line($lines, 'IP', dimhouse_telegram_request_ip());
	dimhouse_telegram_add_line($lines, 'Thoi gian gui', current_time('Y-m-d H:i:s'));

	return implode("\n", $lines);
}

function dimhouse_telegram_add_line(&$lines, $label, $value) {
	$value = dimhouse_telegram_normalize_value($value);

	if (dimhouse_telegram_is_blank($value)) {
		return;
	}

	$lines[] = '<b>' . esc_html($label) . ':</b> ' . esc_html($value);
}

function dimhouse_telegram_value($values, $key) {
	return is_array($values) && array_key_exists($key, $values) ? $values[$key] : '';
}

function dimhouse_telegram_normalize_value($value) {
	if (is_array($value)) {
		$parts = array();
		foreach ($value as $key => $item) {
			$item = dimhouse_telegram_normalize_value($item);
			if (dimhouse_telegram_is_blank($item)) {
				continue;
			}
			$parts[] = $key . ': ' . $item;
		}
		return implode(', ', $parts);
	}

	return trim(wp_strip_all_tags((string) $value));
}

function dimhouse_telegram_is_blank($value) {
	return trim((string) $value) === '';
}

function dimhouse_telegram_location($values) {
	$parts = array(
		dimhouse_telegram_value($values, 'address'),
		dimhouse_telegram_value($values, 'ward'),
		dimhouse_telegram_value($values, 'wards'),
		dimhouse_telegram_value($values, 'district'),
		dimhouse_telegram_value($values, 'districts'),
		dimhouse_telegram_value($values, 'province'),
		dimhouse_telegram_value($values, 'provinces'),
	);

	$parts = array_filter(array_map('dimhouse_telegram_normalize_value', $parts), function ($part) {
		return !dimhouse_telegram_is_blank($part);
	});

	return implode(', ', $parts);
}

function dimhouse_telegram_project_type_label($value) {
	$value = dimhouse_telegram_normalize_value($value);
	$labels = array(
		'1' => 'Nha pho lien ke',
		'3' => 'Biet thu, nha pho song lap',
		'5' => 'Nha vuon',
		'7' => 'Van phong, showroom',
		'9' => 'Nha tro, cho thue',
	);

	return isset($labels[$value]) ? $labels[$value] : $value;
}

function dimhouse_telegram_foundation_label($value) {
	$value = dimhouse_telegram_normalize_value($value);
	$labels = array(
		'1' => 'Mong coc',
		'2' => 'Mong bang',
	);

	return isset($labels[$value]) ? $labels[$value] : $value;
}

function dimhouse_telegram_request_ip() {
	$keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');

	foreach ($keys as $key) {
		if (empty($_SERVER[$key])) {
			continue;
		}

		$value = sanitize_text_field(wp_unslash($_SERVER[$key]));
		$ip = trim(explode(',', $value)[0]);

		if (filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		}
	}

	return '';
}
