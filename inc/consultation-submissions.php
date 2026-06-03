<?php

if (!defined('ABSPATH')) {
	exit;
}

function dimhouse_consultation_table_name() {
	global $wpdb;

	return $wpdb->prefix . 'dimhouse_consultation_submissions';
}

function dimhouse_create_consultation_table() {
	global $wpdb;

	$table_name = dimhouse_consultation_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta(
		"CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			form_name varchar(64) NOT NULL DEFAULT '',
			full_name varchar(190) NOT NULL DEFAULT '',
			phone varchar(60) NOT NULL DEFAULT '',
			email varchar(190) NOT NULL DEFAULT '',
			consultation_time varchar(190) NOT NULL DEFAULT '',
			project_type varchar(190) NOT NULL DEFAULT '',
			area varchar(60) NOT NULL DEFAULT '',
			budget varchar(190) NOT NULL DEFAULT '',
			consultation_topic varchar(190) NOT NULL DEFAULT '',
			content text NULL,
			page_url text NULL,
			ip_address varchar(64) NOT NULL DEFAULT '',
			user_agent text NULL,
			raw_data longtext NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY created_at (created_at),
			KEY phone (phone),
			KEY email (email)
		) {$charset_collate};"
	);
}
add_action('after_switch_theme', 'dimhouse_create_consultation_table');
add_action('admin_init', 'dimhouse_create_consultation_table');

function dimhouse_consultation_client_ip() {
	$keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');

	foreach ($keys as $key) {
		if (empty($_SERVER[$key])) {
			continue;
		}

		$value = sanitize_text_field(wp_unslash($_SERVER[$key]));
		$parts = array_map('trim', explode(',', $value));
		$ip = reset($parts);

		if (filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		}
	}

	return '';
}

function dimhouse_consultation_value($values, $key) {
	if (!isset($values[$key])) {
		return '';
	}

	if (is_array($values[$key])) {
		return sanitize_text_field(implode(', ', $values[$key]));
	}

	return sanitize_text_field($values[$key]);
}

function dimhouse_save_consultation_submission($values, $form_name = 'form_book') {
	global $wpdb;

	dimhouse_create_consultation_table();

	$table_name = dimhouse_consultation_table_name();
	$referer = wp_get_referer();
	$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_textarea_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

	$inserted = $wpdb->insert(
		$table_name,
		array(
			'form_name' => sanitize_key($form_name),
			'full_name' => dimhouse_consultation_value($values, 'full_name'),
			'phone' => dimhouse_consultation_value($values, 'phone'),
			'email' => sanitize_email(dimhouse_consultation_value($values, 'email')),
			'consultation_time' => dimhouse_consultation_value($values, 'time'),
			'project_type' => dimhouse_consultation_value($values, 'type'),
			'area' => dimhouse_consultation_value($values, 'area'),
			'budget' => dimhouse_consultation_value($values, 'price'),
			'consultation_topic' => dimhouse_consultation_value($values, 'short'),
			'content' => sanitize_textarea_field(dimhouse_consultation_value($values, 'content')),
			'page_url' => $referer ? esc_url_raw($referer) : '',
			'ip_address' => dimhouse_consultation_client_ip(),
			'user_agent' => $user_agent,
			'raw_data' => wp_json_encode($values, JSON_UNESCAPED_UNICODE),
			'created_at' => current_time('mysql'),
		),
		array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
	);

	return false !== $inserted ? (int) $wpdb->insert_id : 0;
}

function dimhouse_consultation_project_type_label($value) {
	$labels = array(
		'1' => 'Nhà phố liền kề',
		'3' => 'Biệt thự, nhà phố song lập',
		'5' => 'Nhà vườn',
		'7' => 'Văn phòng, showroom',
		'9' => 'Nhà trọ, cho thuê',
	);

	$key = (string) $value;
	return isset($labels[$key]) ? $labels[$key] : $value;
}

function dimhouse_register_consultation_admin_page() {
	add_menu_page(
		'Đăng ký tư vấn',
		'Đăng ký tư vấn',
		'manage_options',
		'dimhouse-consultations',
		'dimhouse_render_consultation_admin_page',
		'dashicons-feedback',
		58
	);
}
add_action('admin_menu', 'dimhouse_register_consultation_admin_page');

function dimhouse_render_consultation_admin_page() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html__('You do not have permission to access this page.', 'dimhouse'));
	}

	global $wpdb;

	dimhouse_create_consultation_table();

	$table_name = dimhouse_consultation_table_name();
	$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
	$per_page = 20;
	$offset = ($paged - 1) * $per_page;
	$total = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
	$items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$table_name} ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d",
			$per_page,
			$offset
		)
	);
	$total_pages = max(1, (int) ceil($total / $per_page));
	$base_url = menu_page_url('dimhouse-consultations', false);
	?>
	<div class="wrap">
		<h1>Đăng ký tư vấn</h1>
		<p>Danh sách dữ liệu khách gửi từ form tư vấn trên website.</p>

		<table class="widefat striped">
			<thead>
				<tr>
					<th>Thời gian</th>
					<th>Họ tên</th>
					<th>Số điện thoại</th>
					<th>Email</th>
					<th>Lịch tư vấn</th>
					<th>Loại công trình</th>
					<th>Diện tích</th>
					<th>Ngân sách</th>
					<th>Nội dung tư vấn</th>
					<th>Ghi chú</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($items)) : ?>
					<tr>
						<td colspan="10">Chưa có đăng ký nào.</td>
					</tr>
				<?php else : ?>
					<?php foreach ($items as $item) : ?>
						<tr>
							<td><?php echo esc_html(mysql2date('d/m/Y H:i', $item->created_at)); ?></td>
							<td><?php echo esc_html($item->full_name); ?></td>
							<td><?php echo esc_html($item->phone); ?></td>
							<td><?php echo esc_html($item->email); ?></td>
							<td><?php echo esc_html($item->consultation_time); ?></td>
							<td><?php echo esc_html(dimhouse_consultation_project_type_label($item->project_type)); ?></td>
							<td><?php echo esc_html($item->area); ?></td>
							<td><?php echo esc_html($item->budget); ?></td>
							<td><?php echo esc_html($item->consultation_topic); ?></td>
							<td><?php echo esc_html(wp_trim_words($item->content, 18, '...')); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<?php if ($total_pages > 1) : ?>
			<div class="tablenav bottom">
				<div class="tablenav-pages">
					<span class="displaying-num"><?php echo esc_html(sprintf('%d mục', $total)); ?></span>
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'base' => add_query_arg('paged', '%#%', $base_url),
								'format' => '',
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'total' => $total_pages,
								'current' => $paged,
							)
						)
					);
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
