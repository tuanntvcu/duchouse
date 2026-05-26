<?php
if (!defined('ABSPATH')) {
	exit;
}

$section_html = dimhouse_render_process_section_from_index();

if ($section_html) {
	echo $section_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
