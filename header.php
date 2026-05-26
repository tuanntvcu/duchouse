<?php
if (!defined('ABSPATH')) {
	exit;
}

$favicon = dimhouse_option('favicon');
$logo = dimhouse_option('logo');
$seo_title = dimhouse_option('seo_title');
$seo_description = dimhouse_option('seo_description');
$og_image = dimhouse_option('og_image');
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<?php if ($seo_description) : ?><meta name="description" content="<?php echo esc_attr($seo_description); ?>"><?php endif; ?>
	<?php if ($seo_title) : ?><meta property="og:title" content="<?php echo esc_attr($seo_title); ?>"><?php endif; ?>
	<?php if ($seo_description) : ?><meta property="og:description" content="<?php echo esc_attr($seo_description); ?>"><?php endif; ?>
	<?php if ($og_image) : ?><meta property="og:image" content="<?php echo esc_url(dimhouse_image_url($og_image, 'full')); ?>"><?php endif; ?>
	<?php if ($favicon) : ?>
	<link rel="icon" href="<?php echo esc_url(dimhouse_image_url($favicon, 'full')); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url(dimhouse_image_url($favicon, 'full')); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
