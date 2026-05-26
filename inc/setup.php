<?php

if (!defined('ABSPATH')) {
	exit;
}

class Dimhouse_Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
	public function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'nav-item';
		if (in_array('menu-item-has-children', $classes, true)) {
			$classes[] = 'dropdown';
		}
		$class_names = implode(' ', array_map('sanitize_html_class', array_filter($classes)));
		$output .= '<li class="' . esc_attr($class_names) . '">';

		$atts = array();
		$atts['class'] = 'nav-link';
		if (in_array('menu-item-has-children', $classes, true)) {
			$atts['class'] .= ' dropdown-toggle';
			$atts['data-toggle'] = 'dropdown';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		$atts['href'] = !empty($item->url) ? $item->url : '#';

		$attributes = '';
		foreach ($atts as $attr => $value) {
			if ($value === '') {
				continue;
			}
			$attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
		}

		$title = apply_filters('the_title', $item->title, $item->ID);
		$output .= '<a' . $attributes . '>' . esc_html($title) . '</a>';
	}

	public function end_el(&$output, $item, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}

function dimhouse_theme_setup() {
	load_theme_textdomain('dimhouse', get_theme_file_path('/languages'));

	add_theme_support('automatic-feed-links');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
	add_theme_support('custom-logo', array('height' => 120, 'width' => 320, 'flex-height' => true, 'flex-width' => true));
	add_theme_support('responsive-embeds');
	add_theme_support('align-wide');
	add_theme_support('editor-styles');
	add_editor_style('assets/css/theme.css');

	register_nav_menus(array(
		'primary' => __('Primary Menu', 'dimhouse'),
		'footer' => __('Footer Menu', 'dimhouse'),
	));
}
add_action('after_setup_theme', 'dimhouse_theme_setup');

function dimhouse_widgets_init() {
	register_sidebar(array(
		'name' => __('Sidebar', 'dimhouse'),
		'id' => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
}
add_action('widgets_init', 'dimhouse_widgets_init');

function dimhouse_hide_frontend_admin_bar($show) {
	if (!is_admin()) {
		return false;
	}

	return $show;
}
add_filter('show_admin_bar', 'dimhouse_hide_frontend_admin_bar');
