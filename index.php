<?php
// Render file HTML tĩnh đã clone nhưng vẫn giữ cấu trúc WordPress
$static_file = get_stylesheet_directory() . '/index.html';

if (!file_exists($static_file)) {
    echo 'Không tìm thấy file index.html trong theme.';
    return;
}

$html = file_get_contents($static_file);
$base_url = esc_url(get_stylesheet_directory_uri() . '/');

if (strpos($html, '<head>') !== false) {
    $html = str_replace(
        '<head>',
        '<head>' . PHP_EOL . '    <base href="' . $base_url . '">',
        $html
    );
}

$body_classes = esc_attr(implode(' ', get_body_class()));
$html = preg_replace(
    '/<body\b([^>]*)>/',
    '<body$1 class="' . $body_classes . '">',
    $html,
    1
);

ob_start();
include get_stylesheet_directory() . '/header.php';
$header_part = ob_get_clean();
$html = str_replace('<!-- HEADER_PART -->', $header_part, $html);

ob_start();
include get_stylesheet_directory() . '/footer.php';
$footer_part = ob_get_clean();
$html = str_replace('<!-- FOOTER_PART -->', $footer_part, $html);

ob_start();
wp_head();
$wp_head = ob_get_clean();
$html = str_replace('</head>', $wp_head . "\n</head>", $html);

ob_start();
wp_footer();
$wp_footer = ob_get_clean();
$html = str_replace('</body>', $wp_footer . "\n</body>", $html);

echo $html;