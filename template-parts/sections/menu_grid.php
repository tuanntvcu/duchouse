<?php
if (!defined('ABSPATH')) {
	exit;
}

$section_html = dimhouse_render_menu_grid_section_from_index();

if ($section_html) {
	echo $section_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
