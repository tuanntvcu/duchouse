<?php

if (!defined('ABSPATH')) {
	exit;
}

function dimhouse_seo_default_title() {
	return 'Dimhouse - Thiết kế kiến trúc, nội thất & xây dựng trọn gói';
}

function dimhouse_seo_default_description() {
	return 'Dimhouse tư vấn, thiết kế kiến trúc, nội thất và thi công xây dựng trọn gói cho nhà phố, biệt thự, căn hộ. Dự toán minh bạch, quy trình chuyên nghiệp.';
}

function dimhouse_seo_clean_text($text, $max_length = 160) {
	$text = wp_strip_all_tags(strip_shortcodes((string) $text), true);
	$text = preg_replace('/\s+/u', ' ', $text);
	$text = trim((string) $text);

	if ($max_length > 0 && function_exists('mb_strlen') && mb_strlen($text, 'UTF-8') > $max_length) {
		$text = rtrim(mb_substr($text, 0, $max_length - 1, 'UTF-8'));
		$text = preg_replace('/\s+\S*$/u', '', $text);
		$text .= '…';
	}

	return $text;
}

function dimhouse_seo_get_post_value($field_name) {
	if (!is_singular()) {
		return '';
	}

	$post_id = get_queried_object_id();
	if (!$post_id) {
		return '';
	}

	return dimhouse_acf_post_value($field_name, $post_id);
}

function dimhouse_seo_get_option_or_default($field_name, $default = '') {
	$value = dimhouse_option($field_name, '');
	return $value !== '' ? $value : $default;
}

function dimhouse_seo_get_title() {
	$post_title = dimhouse_seo_get_post_value('seo_title');
	if ($post_title) {
		return dimhouse_seo_clean_text($post_title, 70);
	}

	if (is_front_page() || is_home()) {
		return dimhouse_seo_clean_text(dimhouse_seo_get_option_or_default('seo_title', dimhouse_seo_default_title()), 70);
	}

	return wp_get_document_title();
}

function dimhouse_seo_filter_document_title($title) {
	if (is_admin()) {
		return $title;
	}

	$post_title = dimhouse_seo_get_post_value('seo_title');
	if ($post_title) {
		return dimhouse_seo_clean_text($post_title, 70);
	}

	if (is_front_page() || is_home()) {
		return dimhouse_seo_clean_text(dimhouse_seo_get_option_or_default('seo_title', dimhouse_seo_default_title()), 70);
	}

	return $title;
}
add_filter('pre_get_document_title', 'dimhouse_seo_filter_document_title');

function dimhouse_seo_get_description() {
	$post_description = dimhouse_seo_get_post_value('seo_description');
	if ($post_description) {
		return dimhouse_seo_clean_text($post_description);
	}

	if (is_front_page() || is_home()) {
		return dimhouse_seo_clean_text(dimhouse_seo_get_option_or_default('seo_description', dimhouse_seo_default_description()));
	}

	if (is_singular()) {
		$post = get_queried_object();
		if (!empty($post->post_excerpt)) {
			return dimhouse_seo_clean_text($post->post_excerpt);
		}

		if (!empty($post->post_content)) {
			return dimhouse_seo_clean_text($post->post_content);
		}
	}

	if (is_category() || is_tag() || is_tax()) {
		$description = term_description();
		if ($description) {
			return dimhouse_seo_clean_text($description);
		}
	}

	if (is_search()) {
		return dimhouse_seo_clean_text(sprintf('Kết quả tìm kiếm cho "%s" tại Dimhouse.', get_search_query()));
	}

	if (is_404()) {
		return 'Trang bạn đang tìm không tồn tại. Quay lại Dimhouse để xem dịch vụ thiết kế kiến trúc, nội thất và thi công xây dựng trọn gói.';
	}

	return dimhouse_seo_default_description();
}

function dimhouse_seo_get_image() {
	$post_image = dimhouse_seo_get_post_value('og_image');
	if ($post_image) {
		return dimhouse_image_url($post_image, 'full');
	}

	if (is_singular() && has_post_thumbnail()) {
		$image = wp_get_attachment_image_url(get_post_thumbnail_id(), 'full');
		if ($image) {
			return $image;
		}
	}

	$option_image = dimhouse_option('og_image');
	if ($option_image) {
		return dimhouse_image_url($option_image, 'full');
	}

	$logo = dimhouse_option('logo');
	if ($logo) {
		return dimhouse_image_url($logo, 'full');
	}

	return dimhouse_asset_uri('uploads/banner/baner/14_trang_den.png');
}

function dimhouse_seo_get_canonical_url() {
	if (is_404() || is_search()) {
		return '';
	}

	if (is_singular()) {
		$canonical = wp_get_canonical_url();
		return $canonical ? $canonical : get_permalink();
	}

	if (is_front_page()) {
		return home_url('/');
	}

	if (is_home()) {
		$page_for_posts = (int) get_option('page_for_posts');
		return $page_for_posts ? get_permalink($page_for_posts) : home_url('/');
	}

	if (is_category() || is_tag() || is_tax()) {
		$term = get_queried_object();
		if ($term && !is_wp_error($term)) {
			$url = get_term_link($term);
			return !is_wp_error($url) ? $url : '';
		}
	}

	if (is_author()) {
		return get_author_posts_url(get_queried_object_id());
	}

	if (is_post_type_archive()) {
		$post_type = get_query_var('post_type');
		if (is_array($post_type)) {
			$post_type = reset($post_type);
		}
		$url = get_post_type_archive_link($post_type);
		return $url ? $url : '';
	}

	return get_pagenum_link(max(1, (int) get_query_var('paged')));
}

function dimhouse_seo_get_current_url() {
	global $wp;

	$path = !empty($wp->request) ? '/' . trim($wp->request, '/') . '/' : '/';
	$url = home_url($path);
	$query_string = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';

	if ($query_string) {
		$url .= '?' . $query_string;
	}

	return $url;
}

function dimhouse_seo_output_meta_tags() {
	if (is_admin()) {
		return;
	}

	$title = dimhouse_seo_get_title();
	$description = dimhouse_seo_get_description();
	$canonical = dimhouse_seo_get_canonical_url();
	$image = dimhouse_seo_get_image();
	$type = is_singular('post') ? 'article' : 'website';
	$locale = str_replace('-', '_', get_locale());
	$url = $canonical ? $canonical : dimhouse_seo_get_current_url();
	$robots = is_search() || is_404()
		? 'noindex, follow'
		: 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
	?>
	<meta name="robots" content="<?php echo esc_attr($robots); ?>">
	<meta name="description" content="<?php echo esc_attr($description); ?>">
	<?php if ($canonical) : ?><link rel="canonical" href="<?php echo esc_url($canonical); ?>"><?php endif; ?>
	<meta property="og:locale" content="<?php echo esc_attr($locale); ?>">
	<meta property="og:type" content="<?php echo esc_attr($type); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta property="og:title" content="<?php echo esc_attr($title); ?>">
	<meta property="og:description" content="<?php echo esc_attr($description); ?>">
	<meta property="og:url" content="<?php echo esc_url($url); ?>">
	<?php if ($image) : ?><meta property="og:image" content="<?php echo esc_url($image); ?>"><?php endif; ?>
	<meta name="twitter:card" content="<?php echo esc_attr($image ? 'summary_large_image' : 'summary'); ?>">
	<meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
	<?php if ($image) : ?><meta name="twitter:image" content="<?php echo esc_url($image); ?>"><?php endif; ?>
	<?php if (is_singular('post')) : ?>
		<meta property="article:published_time" content="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
		<meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date(DATE_W3C)); ?>">
	<?php endif; ?>
	<?php
}
add_action('wp_head', 'dimhouse_seo_output_meta_tags', 5);

function dimhouse_seo_remove_core_canonical() {
	remove_action('wp_head', 'rel_canonical');
}
add_action('init', 'dimhouse_seo_remove_core_canonical');

function dimhouse_seo_same_as_urls() {
	$urls = array();
	foreach (dimhouse_default_social_links() as $link) {
		if (!empty($link['url'])) {
			$urls[] = esc_url_raw($link['url']);
		}
	}

	foreach (dimhouse_social_links('header') as $link) {
		if (!empty($link['url'])) {
			$urls[] = esc_url_raw($link['url']);
		}
	}

	return array_values(array_unique(array_filter($urls)));
}

function dimhouse_seo_business_data() {
	$phone = dimhouse_option('business_phone', dimhouse_option('header_phone', '0392959713'));
	$email = dimhouse_option('business_email', dimhouse_option('footer_email', 'dimhousevietnam@gmail.com'));

	return array(
		'name' => dimhouse_option('business_name', 'Dimhouse'),
		'alternateName' => 'Dimhouse Design',
		'description' => dimhouse_option('business_description', dimhouse_seo_default_description()),
		'telephone' => $phone,
		'email' => $email,
		'priceRange' => dimhouse_option('business_price_range', '$$'),
		'streetAddress' => dimhouse_option('business_street_address', '10/169 Thái Hà, Đống Đa'),
		'addressLocality' => dimhouse_option('business_locality', 'Hà Nội'),
		'addressCountry' => dimhouse_option('business_country', 'VN'),
		'areaServed' => dimhouse_option('business_area_served', 'Việt Nam'),
	);
}

function dimhouse_seo_schema_organization() {
	$business = dimhouse_seo_business_data();
	$home_url = home_url('/');
	$logo = dimhouse_option('logo');
	$logo_url = $logo ? dimhouse_image_url($logo, 'full') : dimhouse_asset_uri('uploads/banner/baner/14_trang_den.png');

	$schema = array(
		'@type' => array('LocalBusiness', 'HomeAndConstructionBusiness'),
		'@id' => $home_url . '#organization',
		'name' => $business['name'],
		'alternateName' => $business['alternateName'],
		'url' => $home_url,
		'description' => dimhouse_seo_clean_text($business['description'], 300),
		'logo' => $logo_url,
		'image' => dimhouse_seo_get_image(),
		'telephone' => $business['telephone'],
		'email' => $business['email'],
		'priceRange' => $business['priceRange'],
		'address' => array(
			'@type' => 'PostalAddress',
			'streetAddress' => $business['streetAddress'],
			'addressLocality' => $business['addressLocality'],
			'addressCountry' => $business['addressCountry'],
		),
		'areaServed' => array(
			'@type' => 'Country',
			'name' => $business['areaServed'],
		),
		'sameAs' => dimhouse_seo_same_as_urls(),
	);

	return array_filter($schema);
}

function dimhouse_seo_schema_breadcrumbs($canonical) {
	if (is_front_page() || !$canonical) {
		return array();
	}

	$items = array(
		array(
			'@type' => 'ListItem',
			'position' => 1,
			'name' => 'Trang chủ',
			'item' => home_url('/'),
		),
	);

	if (is_singular()) {
		$post_id = get_queried_object_id();
		$position = 2;

		if (is_page()) {
			$ancestors = array_reverse(get_post_ancestors($post_id));
			foreach ($ancestors as $ancestor_id) {
				$items[] = array(
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => get_the_title($ancestor_id),
					'item' => get_permalink($ancestor_id),
				);
			}
		} elseif (is_single()) {
			$categories = get_the_category($post_id);
			if (!empty($categories)) {
				$category = $categories[0];
				$items[] = array(
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $category->name,
					'item' => get_category_link($category),
				);
			}
		}

		$items[] = array(
			'@type' => 'ListItem',
			'position' => $position,
			'name' => get_the_title($post_id),
			'item' => $canonical,
		);
	} else {
		$items[] = array(
			'@type' => 'ListItem',
			'position' => 2,
			'name' => dimhouse_seo_get_title(),
			'item' => $canonical,
		);
	}

	return array(
		'@type' => 'BreadcrumbList',
		'@id' => $canonical . '#breadcrumb',
		'itemListElement' => $items,
	);
}

function dimhouse_seo_schema_article($canonical) {
	if (!is_singular('post') || !$canonical) {
		return array();
	}

	$image = dimhouse_seo_get_image();
	$schema = array(
		'@type' => 'Article',
		'@id' => $canonical . '#article',
		'headline' => dimhouse_seo_get_title(),
		'description' => dimhouse_seo_get_description(),
		'image' => $image ? array($image) : array(),
		'datePublished' => get_the_date(DATE_W3C),
		'dateModified' => get_the_modified_date(DATE_W3C),
		'author' => array(
			'@type' => 'Person',
			'name' => get_the_author_meta('display_name'),
		),
		'publisher' => array(
			'@id' => home_url('/') . '#organization',
		),
		'mainEntityOfPage' => array(
			'@id' => $canonical . '#webpage',
		),
	);

	return array_filter($schema);
}

function dimhouse_seo_schema_graph() {
	if (is_admin()) {
		return;
	}

	if (is_search() || is_404()) {
		return;
	}

	$canonical = dimhouse_seo_get_canonical_url();
	$title = dimhouse_seo_get_title();
	$description = dimhouse_seo_get_description();
	$image = dimhouse_seo_get_image();
	$home_url = home_url('/');
	$webpage_id = ($canonical ? $canonical : $home_url) . '#webpage';

	$graph = array(
		dimhouse_seo_schema_organization(),
		array(
			'@type' => 'WebSite',
			'@id' => $home_url . '#website',
			'url' => $home_url,
			'name' => get_bloginfo('name') ?: 'Dimhouse',
			'description' => dimhouse_seo_default_description(),
			'inLanguage' => get_bloginfo('language'),
			'publisher' => array('@id' => $home_url . '#organization'),
			'potentialAction' => array(
				'@type' => 'SearchAction',
				'target' => home_url('/?s={search_term_string}'),
				'query-input' => 'required name=search_term_string',
			),
		),
		array_filter(array(
			'@type' => 'WebPage',
			'@id' => $webpage_id,
			'url' => $canonical ? $canonical : $home_url,
			'name' => $title,
			'description' => $description,
			'inLanguage' => get_bloginfo('language'),
			'isPartOf' => array('@id' => $home_url . '#website'),
			'about' => array('@id' => $home_url . '#organization'),
			'primaryImageOfPage' => $image ? array('@type' => 'ImageObject', 'url' => $image) : null,
		)),
	);

	$breadcrumbs = dimhouse_seo_schema_breadcrumbs($canonical);
	if (!empty($breadcrumbs)) {
		$graph[] = $breadcrumbs;
	}

	$article = dimhouse_seo_schema_article($canonical);
	if (!empty($article)) {
		$graph[] = $article;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph' => array_values(array_filter($graph)),
	);
	?>
	<script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
	<?php
}
add_action('wp_head', 'dimhouse_seo_schema_graph', 30);
