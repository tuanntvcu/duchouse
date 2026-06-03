<?php

if (!defined('ABSPATH')) {
	exit;
}

function dimhouse_register_acf_fields() {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_theme_options',
		'title' => 'Dimhouse Theme Options',
		'fields' => array(
			array('key' => 'field_dimhouse_favicon', 'label' => 'Favicon', 'name' => 'favicon', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
			array('key' => 'field_dimhouse_logo', 'label' => 'Logo', 'name' => 'logo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
			array('key' => 'field_dimhouse_footer_logo', 'label' => 'Footer Logo', 'name' => 'footer_logo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
			array('key' => 'field_dimhouse_header_info', 'label' => 'Header Info', 'name' => 'header_info', 'type' => 'textarea', 'new_lines' => 'br'),
			array('key' => 'field_dimhouse_header_phone', 'label' => 'Header Phone', 'name' => 'header_phone', 'type' => 'text'),
			array('key' => 'field_dimhouse_header_phone_link', 'label' => 'Header Phone Link', 'name' => 'header_phone_link', 'type' => 'url'),
			array('key' => 'field_dimhouse_header_cta_label', 'label' => 'Header CTA Label', 'name' => 'header_cta_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_header_cta_url', 'label' => 'Header CTA URL', 'name' => 'header_cta_url', 'type' => 'url'),
			array('key' => 'field_dimhouse_google_tag_id', 'label' => 'Google Tag ID', 'name' => 'google_tag_id', 'type' => 'text', 'default_value' => ''),
			array('key' => 'field_dimhouse_google_ads_client', 'label' => 'Google Ads Client', 'name' => 'google_ads_client', 'type' => 'text', 'default_value' => ''),
			array('key' => 'field_dimhouse_popup_auto_open', 'label' => 'Auto Open Popup', 'name' => 'popup_auto_open', 'type' => 'true_false', 'default_value' => 1, 'ui' => 1),
			array('key' => 'field_dimhouse_product_slider_slides', 'label' => 'Product/Video Slider Slides', 'name' => 'product_slider_slides', 'type' => 'number', 'default_value' => 4, 'min' => 1, 'max' => 8),
			array('key' => 'field_dimhouse_article_slider_slides', 'label' => 'Article Slider Slides', 'name' => 'article_slider_slides', 'type' => 'number', 'default_value' => 4, 'min' => 1, 'max' => 8),
			array('key' => 'field_dimhouse_testimonial_slider_slides', 'label' => 'Testimonial Slider Slides', 'name' => 'testimonial_slider_slides', 'type' => 'number', 'default_value' => 3, 'min' => 1, 'max' => 6),
			array('key' => 'field_dimhouse_partner_slider_slides', 'label' => 'Partner Slider Slides', 'name' => 'partner_slider_slides', 'type' => 'number', 'default_value' => 5, 'min' => 1, 'max' => 8),
			array(
				'key' => 'field_dimhouse_header_social_links',
				'label' => 'Header Social Links',
				'name' => 'header_social_links',
				'type' => 'repeater',
				'layout' => 'row',
				'min' => 0,
				'button_label' => 'Add Social Link',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_header_social_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
					array('key' => 'field_dimhouse_header_social_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
					array('key' => 'field_dimhouse_header_social_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
				),
			),
			array(
				'key' => 'field_dimhouse_floating_contact_links',
				'label' => 'Floating Contact Links',
				'name' => 'floating_contact_links',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Add Floating Link',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_floating_contact_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
					array('key' => 'field_dimhouse_floating_contact_url', 'label' => 'URL', 'name' => 'url', 'type' => 'text'),
					array('key' => 'field_dimhouse_floating_contact_image', 'label' => 'Icon Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
					array('key' => 'field_dimhouse_floating_contact_class', 'label' => 'CSS Class', 'name' => 'class', 'type' => 'text'),
				),
			),
			array(
				'key' => 'field_dimhouse_side_menu_items',
				'label' => 'Side Menu Items',
				'name' => 'side_menu_items',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Add Side Menu Item',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_side_menu_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
					array('key' => 'field_dimhouse_side_menu_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
				),
			),
			array(
				'key' => 'field_dimhouse_mobile_menu_items',
				'label' => 'Mobile Bottom Menu Items',
				'name' => 'mobile_menu_items',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Add Mobile Menu Item',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_mobile_menu_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
					array('key' => 'field_dimhouse_mobile_menu_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
					array('key' => 'field_dimhouse_mobile_menu_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
				),
			),
			array('key' => 'field_dimhouse_footer_title', 'label' => 'Footer Title', 'name' => 'footer_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_footer_brand_title', 'label' => 'Footer Partners Title', 'name' => 'footer_brand_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_footer_contact_title', 'label' => 'Footer Contact Title', 'name' => 'footer_contact_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_footer_social_title', 'label' => 'Footer Social Title', 'name' => 'footer_social_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_footer_text', 'label' => 'Footer Text', 'name' => 'footer_text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
			array('key' => 'field_dimhouse_footer_fanpage_iframe', 'label' => 'Footer Fanpage Iframe', 'name' => 'footer_fanpage_iframe', 'type' => 'textarea', 'new_lines' => ''),
			array('key' => 'field_dimhouse_footer_map_iframe', 'label' => 'Footer Map Iframe', 'name' => 'footer_map_iframe', 'type' => 'textarea', 'new_lines' => ''),
			array('key' => 'field_dimhouse_footer_email', 'label' => 'Footer Email', 'name' => 'footer_email', 'type' => 'email'),
			array('key' => 'field_dimhouse_footer_copyright', 'label' => 'Footer Copyright', 'name' => 'footer_copyright', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
			array('key' => 'field_dimhouse_popup_image', 'label' => 'Popup Image', 'name' => 'popup_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
			array('key' => 'field_dimhouse_popup_url', 'label' => 'Popup URL', 'name' => 'popup_url', 'type' => 'url'),
			array('key' => 'field_dimhouse_popup_alt', 'label' => 'Popup Alt Text', 'name' => 'popup_alt', 'type' => 'text'),
			array(
				'key' => 'field_dimhouse_footer_partners',
				'label' => 'Footer Partners',
				'name' => 'footer_partners',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Add Partner',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_footer_partner_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
					array('key' => 'field_dimhouse_footer_partner_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
					array('key' => 'field_dimhouse_footer_partner_alt', 'label' => 'Alt', 'name' => 'alt', 'type' => 'text'),
				),
			),
			array(
				'key' => 'field_dimhouse_footer_columns',
				'label' => 'Footer Columns',
				'name' => 'footer_columns',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Add Footer Column',
				'sub_fields' => array(
					array('key' => 'field_dimhouse_footer_col_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
					array('key' => 'field_dimhouse_footer_col_content', 'label' => 'Content', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
				),
			),
			array('key' => 'field_dimhouse_seo_title', 'label' => 'SEO Title', 'name' => 'seo_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_seo_description', 'label' => 'SEO Description', 'name' => 'seo_description', 'type' => 'textarea'),
			array('key' => 'field_dimhouse_og_image', 'label' => 'OG Image', 'name' => 'og_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
			array('key' => 'field_dimhouse_business_name', 'label' => 'Business Name', 'name' => 'business_name', 'type' => 'text', 'default_value' => 'Dimhouse'),
			array('key' => 'field_dimhouse_business_description', 'label' => 'Business Schema Description', 'name' => 'business_description', 'type' => 'textarea'),
			array('key' => 'field_dimhouse_business_phone', 'label' => 'Business Phone', 'name' => 'business_phone', 'type' => 'text'),
			array('key' => 'field_dimhouse_business_email', 'label' => 'Business Email', 'name' => 'business_email', 'type' => 'email'),
			array('key' => 'field_dimhouse_business_price_range', 'label' => 'Business Price Range', 'name' => 'business_price_range', 'type' => 'text', 'default_value' => '$$'),
			array('key' => 'field_dimhouse_business_street_address', 'label' => 'Business Street Address', 'name' => 'business_street_address', 'type' => 'text', 'default_value' => '10/169 Thái Hà, Đống Đa'),
			array('key' => 'field_dimhouse_business_locality', 'label' => 'Business City / Locality', 'name' => 'business_locality', 'type' => 'text', 'default_value' => 'Hà Nội'),
			array('key' => 'field_dimhouse_business_country', 'label' => 'Business Country Code', 'name' => 'business_country', 'type' => 'text', 'default_value' => 'VN'),
			array('key' => 'field_dimhouse_business_area_served', 'label' => 'Business Area Served', 'name' => 'business_area_served', 'type' => 'text', 'default_value' => 'Việt Nam'),
			array('key' => 'field_dimhouse_alert_title', 'label' => 'Alert Title', 'name' => 'alert_title', 'type' => 'text'),
			array('key' => 'field_dimhouse_send_label', 'label' => 'Send Label', 'name' => 'send_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_form_confirm_message', 'label' => 'Form Confirm Message', 'name' => 'form_confirm_message', 'type' => 'textarea'),
			array('key' => 'field_dimhouse_form_success_message', 'label' => 'Form Success Message', 'name' => 'form_success_message', 'type' => 'text'),
			array('key' => 'field_dimhouse_form_required_message', 'label' => 'Form Required Message', 'name' => 'form_required_message', 'type' => 'text'),
			array('key' => 'field_dimhouse_invalid_floor_message', 'label' => 'Invalid Floor Message', 'name' => 'invalid_floor_message', 'type' => 'text'),
			array('key' => 'field_dimhouse_invalid_mezzanine_message', 'label' => 'Invalid Mezzanine Message', 'name' => 'invalid_mezzanine_message', 'type' => 'text'),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'dimhouse-theme-options',
				),
			),
		),
	));

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_page_post_seo',
		'title' => 'Dimhouse SEO',
		'fields' => array(
			array('key' => 'field_dimhouse_post_seo_title', 'label' => 'SEO Title', 'name' => 'seo_title', 'type' => 'text', 'instructions' => 'Nen ngan gon, ro y dinh tim kiem va khac biet voi cac trang khac.'),
			array('key' => 'field_dimhouse_post_seo_description', 'label' => 'SEO Description', 'name' => 'seo_description', 'type' => 'textarea', 'instructions' => 'Khoang 140-160 ky tu, tom tat noi dung va loi ich chinh cua trang.'),
			array('key' => 'field_dimhouse_post_og_image', 'label' => 'OG Image', 'name' => 'og_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Anh chia se cho Facebook, Zalo va Twitter/X. Neu bo trong, theme se dung anh dai dien hoac anh mac dinh.'),
		),
		'location' => array(
			array(
				array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
			),
			array(
				array('param' => 'post_type', 'operator' => '==', 'value' => 'post'),
			),
		),
		'menu_order' => 5,
	));

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_home_sections',
		'title' => 'Home Sections',
		'fields' => array(
			array(
				'key' => 'field_dimhouse_home_sections',
				'label' => 'Sections',
				'name' => 'home_sections',
				'type' => 'flexible_content',
				'button_label' => 'Add Section',
				'layouts' => array(
					'layout_hero' => array(
						'key' => 'layout_dimhouse_hero',
						'name' => 'hero',
						'label' => 'Hero Banner',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_hero_eyebrow', 'label' => 'Eyebrow', 'name' => 'eyebrow', 'type' => 'text'),
							array('key' => 'field_dimhouse_hero_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_hero_text', 'label' => 'Text', 'name' => 'text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array('key' => 'field_dimhouse_hero_video', 'label' => 'Background Video', 'name' => 'video', 'type' => 'file', 'return_format' => 'array', 'mime_types' => 'mp4,webm'),
							array('key' => 'field_dimhouse_hero_image', 'label' => 'Preview Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'large'),
							array(
								'key' => 'field_dimhouse_hero_buttons',
								'label' => 'Buttons',
								'name' => 'buttons',
								'type' => 'repeater',
								'button_label' => 'Add Button',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_hero_button_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
									array('key' => 'field_dimhouse_hero_button_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
									array('key' => 'field_dimhouse_hero_button_style', 'label' => 'Style', 'name' => 'style', 'type' => 'select', 'choices' => array('primary' => 'Primary', 'secondary' => 'Secondary')),
								),
							),
							array(
								'key' => 'field_dimhouse_hero_banners',
								'label' => 'Banners',
								'name' => 'banners',
								'type' => 'repeater',
								'button_label' => 'Add Banner',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_hero_banner_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
									array('key' => 'field_dimhouse_hero_banner_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
									array('key' => 'field_dimhouse_hero_banner_alt', 'label' => 'Alt', 'name' => 'alt', 'type' => 'text'),
								),
							),
						),
					),
					'layout_process' => array(
						'key' => 'layout_dimhouse_process',
						'name' => 'process',
						'label' => 'Process Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_process_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_process_text', 'label' => 'Text', 'name' => 'text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array('key' => 'field_dimhouse_process_banner', 'label' => 'Banner', 'name' => 'banner', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
							array('key' => 'field_dimhouse_process_cta_label', 'label' => 'CTA Label', 'name' => 'cta_label', 'type' => 'text'),
							array('key' => 'field_dimhouse_process_cta_url', 'label' => 'CTA URL', 'name' => 'cta_url', 'type' => 'url'),
							array('key' => 'field_dimhouse_process_booking_form_html', 'label' => 'Booking Form HTML', 'name' => 'booking_form_html', 'type' => 'textarea', 'new_lines' => ''),
							array(
								'key' => 'field_dimhouse_process_steps',
								'label' => 'Steps',
								'name' => 'steps',
								'type' => 'repeater',
								'button_label' => 'Add Step',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_process_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
									array('key' => 'field_dimhouse_process_step_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea'),
									array('key' => 'field_dimhouse_process_step_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
								),
							),
						),
					),
					'layout_menu_grid' => array(
						'key' => 'layout_dimhouse_menu_grid',
						'name' => 'menu_grid',
						'label' => 'Menu Grid',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_menu_grid_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_menu_grid_text', 'label' => 'Text', 'name' => 'text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array(
								'key' => 'field_dimhouse_menu_cards',
								'label' => 'Cards',
								'name' => 'cards',
								'type' => 'repeater',
								'button_label' => 'Add Card',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_menu_card_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
									array('key' => 'field_dimhouse_menu_card_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
									array('key' => 'field_dimhouse_menu_card_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea'),
									array('key' => 'field_dimhouse_menu_card_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
								),
							),
						),
					),
					'layout_villa_designs' => array(
						'key' => 'layout_dimhouse_villa_designs',
						'name' => 'villa_designs',
						'label' => 'Villa Designs Section',
						'sub_fields' => array(
							array(
								'key' => 'field_dimhouse_villa_designs_title',
								'label' => 'Title',
								'name' => 'title',
								'type' => 'text',
								'default_value' => '1000+ THIẾT KẾ BIỆT THỰ ĐẲNG CẤP',
							),
							array(
								'key' => 'field_dimhouse_villa_designs_posts_per_tab',
								'label' => 'Default Posts Per Tab',
								'name' => 'posts_per_tab',
								'type' => 'number',
								'default_value' => 8,
								'min' => 1,
								'max' => 24,
							),
							array(
								'key' => 'field_dimhouse_villa_designs_tabs',
								'label' => 'Tabs',
								'name' => 'tabs',
								'type' => 'repeater',
								'layout' => 'row',
								'button_label' => 'Add Tab',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_villa_designs_tab_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
									array(
										'key' => 'field_dimhouse_villa_designs_tab_category',
										'label' => 'Post Category',
										'name' => 'category',
										'type' => 'taxonomy',
										'taxonomy' => 'category',
										'field_type' => 'select',
										'allow_null' => 0,
										'add_term' => 1,
										'save_terms' => 0,
										'load_terms' => 0,
										'return_format' => 'id',
									),
									array(
										'key' => 'field_dimhouse_villa_designs_tab_posts_per_tab',
										'label' => 'Posts Per Tab',
										'name' => 'posts_per_tab',
										'type' => 'number',
										'min' => 1,
										'max' => 24,
									),
								),
							),
						),
					),
					'layout_estimate' => array(
						'key' => 'layout_dimhouse_estimate',
						'name' => 'estimate',
						'label' => 'Estimate Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_estimate_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_estimate_intro', 'label' => 'Intro', 'name' => 'intro', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array('key' => 'field_dimhouse_estimate_floor_label', 'label' => 'Floor Control Label', 'name' => 'floor_label', 'type' => 'text'),
							array('key' => 'field_dimhouse_estimate_mezzanine_label', 'label' => 'Mezzanine Control Label', 'name' => 'mezzanine_label', 'type' => 'text'),
							array('key' => 'field_dimhouse_estimate_preview_image', 'label' => 'Default Preview Image', 'name' => 'preview_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
							array('key' => 'field_dimhouse_estimate_form_banner_image', 'label' => 'Form Banner Image', 'name' => 'form_banner_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
							array('key' => 'field_dimhouse_estimate_form_banner_url', 'label' => 'Form Banner URL', 'name' => 'form_banner_url', 'type' => 'url'),
							array('key' => 'field_dimhouse_estimate_form_banner_alt', 'label' => 'Form Banner Alt', 'name' => 'form_banner_alt', 'type' => 'text'),
							array('key' => 'field_dimhouse_estimate_book_button_label', 'label' => 'Book Button Label', 'name' => 'book_button_label', 'type' => 'text'),
							array('key' => 'field_dimhouse_estimate_construction_form_html', 'label' => 'Construction Form HTML', 'name' => 'construction_form_html', 'type' => 'textarea', 'new_lines' => ''),
							array('key' => 'field_dimhouse_estimate_booking_form_html', 'label' => 'Booking Form HTML', 'name' => 'booking_form_html', 'type' => 'textarea', 'new_lines' => ''),
							array(
								'key' => 'field_dimhouse_estimate_tabs',
								'label' => 'Estimate Tabs',
								'name' => 'tabs',
								'type' => 'repeater',
								'button_label' => 'Add Tab',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_estimate_tab_key', 'label' => 'Key', 'name' => 'key', 'type' => 'text'),
									array('key' => 'field_dimhouse_estimate_tab_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
									array('key' => 'field_dimhouse_estimate_tab_price', 'label' => 'Price Label', 'name' => 'price_label', 'type' => 'text'),
									array('key' => 'field_dimhouse_estimate_tab_banner', 'label' => 'Banner', 'name' => 'banner', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
									array('key' => 'field_dimhouse_estimate_tab_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
									array('key' => 'field_dimhouse_estimate_tab_content', 'label' => 'Content', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full'),
								),
							),
						),
					),
					'layout_about' => array(
						'key' => 'layout_dimhouse_about',
						'name' => 'about',
						'label' => 'About Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_about_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_about_text', 'label' => 'Text', 'name' => 'text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array('key' => 'field_dimhouse_about_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'large'),
							array('key' => 'field_dimhouse_about_person_name', 'label' => 'Person Name', 'name' => 'person_name', 'type' => 'text'),
							array('key' => 'field_dimhouse_about_person_role', 'label' => 'Person Role', 'name' => 'person_role', 'type' => 'text'),
							array('key' => 'field_dimhouse_about_cta_label', 'label' => 'CTA Label', 'name' => 'cta_label', 'type' => 'text'),
							array('key' => 'field_dimhouse_about_cta_url', 'label' => 'CTA URL', 'name' => 'cta_url', 'type' => 'url'),
						),
					),
					'layout_channel' => array(
						'key' => 'layout_dimhouse_channel',
						'name' => 'channel',
						'label' => 'Channel Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_channel_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_channel_articles_title', 'label' => 'Articles Title', 'name' => 'articles_title', 'type' => 'text'),
							array(
								'key' => 'field_dimhouse_channel_items',
								'label' => 'Videos',
								'name' => 'items',
								'type' => 'repeater',
								'layout' => 'row',
								'button_label' => 'Add Video',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_channel_item_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
									array('key' => 'field_dimhouse_channel_item_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
									array('key' => 'field_dimhouse_channel_item_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
								),
							),
							array(
								'key' => 'field_dimhouse_channel_articles',
								'label' => 'Articles',
								'name' => 'articles',
								'type' => 'repeater',
								'layout' => 'row',
								'button_label' => 'Add Article',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_channel_article_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
									array('key' => 'field_dimhouse_channel_article_subtitle', 'label' => 'Subtitle', 'name' => 'subtitle', 'type' => 'text'),
									array('key' => 'field_dimhouse_channel_article_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
									array('key' => 'field_dimhouse_channel_article_image', 'label' => 'Image', 'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
								),
							),
						),
					),
					'layout_testimonials' => array(
						'key' => 'layout_dimhouse_testimonials',
						'name' => 'testimonials',
						'label' => 'Testimonials Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_testimonials_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_testimonials_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea'),
							array(
								'key' => 'field_dimhouse_testimonials_items',
								'label' => 'Items',
								'name' => 'items',
								'type' => 'repeater',
								'button_label' => 'Add Testimonial',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_testimonial_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'),
									array('key' => 'field_dimhouse_testimonial_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text'),
									array('key' => 'field_dimhouse_testimonial_avatar', 'label' => 'Avatar', 'name' => 'avatar', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
									array('key' => 'field_dimhouse_testimonial_rating', 'label' => 'Rating', 'name' => 'rating', 'type' => 'number', 'min' => 1, 'max' => 10, 'default_value' => 10),
									array('key' => 'field_dimhouse_testimonial_text', 'label' => 'Text', 'name' => 'text', 'type' => 'textarea'),
								),
							),
						),
					),
					'layout_faq' => array(
						'key' => 'layout_dimhouse_faq',
						'name' => 'faq',
						'label' => 'FAQ Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_faq_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array(
								'key' => 'field_dimhouse_faq_items',
								'label' => 'FAQs',
								'name' => 'items',
								'type' => 'repeater',
								'button_label' => 'Add FAQ',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_faq_question', 'label' => 'Question', 'name' => 'question', 'type' => 'text'),
									array('key' => 'field_dimhouse_faq_answer', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
								),
							),
						),
					),
					'layout_contact' => array(
						'key' => 'layout_dimhouse_contact',
						'name' => 'contact',
						'label' => 'Contact Section',
						'sub_fields' => array(
							array('key' => 'field_dimhouse_contact_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
							array('key' => 'field_dimhouse_contact_title_url', 'label' => 'Title URL', 'name' => 'title_url', 'type' => 'url'),
							array('key' => 'field_dimhouse_contact_title_logo', 'label' => 'Title Logo', 'name' => 'title_logo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
							array('key' => 'field_dimhouse_contact_text', 'label' => 'Text', 'name' => 'text', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic'),
							array('key' => 'field_dimhouse_contact_shortcode', 'label' => 'Form Shortcode', 'name' => 'shortcode', 'type' => 'text'),
							array(
								'key' => 'field_dimhouse_contact_items',
								'label' => 'Contact Items',
								'name' => 'items',
								'type' => 'repeater',
								'button_label' => 'Add Item',
								'sub_fields' => array(
									array('key' => 'field_dimhouse_contact_item_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'),
									array('key' => 'field_dimhouse_contact_item_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
									array('key' => 'field_dimhouse_contact_item_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text'),
									array('key' => 'field_dimhouse_contact_item_url', 'label' => 'URL', 'name' => 'url', 'type' => 'url'),
								),
							),
						),
					),
				),
			),
		),
		'location' => array(
			array(
				array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page'),
			),
		),
	));

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_villa_post_details',
		'title' => 'Villa Post Details',
		'fields' => array(
			array('key' => 'field_dimhouse_villa_card_image', 'label' => 'Card Image Override', 'name' => 'villa_card_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
			array('key' => 'field_dimhouse_villa_project_client', 'label' => 'Client / Chủ đầu tư', 'name' => 'villa_project_client', 'type' => 'text'),
			array('key' => 'field_dimhouse_villa_project_location', 'label' => 'Location / Địa chỉ', 'name' => 'villa_project_location', 'type' => 'text'),
			array('key' => 'field_dimhouse_villa_project_area', 'label' => 'Area / Diện tích', 'name' => 'villa_project_area', 'type' => 'text'),
			array('key' => 'field_dimhouse_villa_project_floors', 'label' => 'Floors / Số tầng', 'name' => 'villa_project_floors', 'type' => 'text'),
		),
		'location' => array(
			array(
				array('param' => 'post_type', 'operator' => '==', 'value' => 'post'),
			),
		),
	));

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_construction_estimate_settings',
		'title' => 'Cấu hình khai toán chi phí',
		'fields' => array(
			array('key' => 'field_dimhouse_estimate_design_tab', 'label' => 'Đơn giá thiết kế', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_design_landscape_rate', 'label' => 'Cảnh quan (VNĐ/m2)', 'name' => 'estimate_design_landscape_rate', 'type' => 'number', 'default_value' => 100000, 'min' => 0, 'step' => 1000),
			array('key' => 'field_dimhouse_estimate_design_interior_rate', 'label' => 'Thiết kế nội thất (VNĐ/m2)', 'name' => 'estimate_design_interior_rate', 'type' => 'number', 'default_value' => 150000, 'min' => 0, 'step' => 1000),
			array('key' => 'field_dimhouse_estimate_design_architecture_rate', 'label' => 'Thiết kế kiến trúc (VNĐ/m2)', 'name' => 'estimate_design_architecture_rate', 'type' => 'number', 'default_value' => 150000, 'min' => 0, 'step' => 1000),
			array('key' => 'field_dimhouse_estimate_design_architecture_interior_rate', 'label' => 'Kiến trúc - nội thất (VNĐ/m2)', 'name' => 'estimate_design_architecture_interior_rate', 'type' => 'number', 'default_value' => 250000, 'min' => 0, 'step' => 1000),

			array('key' => 'field_dimhouse_estimate_completion_tab', 'label' => 'Đơn giá hoàn thiện', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_completion_high_rate', 'label' => 'Gói cao cấp (VNĐ/m2)', 'name' => 'estimate_completion_high_rate', 'type' => 'number', 'default_value' => 3000000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_completion_good_rate', 'label' => 'Gói khá (VNĐ/m2)', 'name' => 'estimate_completion_good_rate', 'type' => 'number', 'default_value' => 2600000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_completion_standard_rate', 'label' => 'Gói trung bình (VNĐ/m2)', 'name' => 'estimate_completion_standard_rate', 'type' => 'number', 'default_value' => 2300000, 'min' => 0, 'step' => 10000),

			array('key' => 'field_dimhouse_estimate_crude_tab', 'label' => 'Đơn giá phần thô', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_crude_tiny_max_area', 'label' => 'Ngưỡng diện tích nhỏ', 'name' => 'estimate_crude_tiny_max_area', 'type' => 'number', 'default_value' => 150, 'min' => 0, 'step' => 1),
			array('key' => 'field_dimhouse_estimate_crude_tiny_unit', 'label' => 'Đơn giá khi nhỏ hơn ngưỡng (VNĐ/m2)', 'name' => 'estimate_crude_tiny_unit', 'type' => 'number', 'default_value' => 4200000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_crude_small_unit', 'label' => 'Đơn giá tại đúng ngưỡng (VNĐ/m2)', 'name' => 'estimate_crude_small_unit', 'type' => 'number', 'default_value' => 4000000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_crude_medium_unit', 'label' => 'Đơn giá từ ngưỡng đến trước khoảng đặc biệt (VNĐ/m2)', 'name' => 'estimate_crude_medium_unit', 'type' => 'number', 'default_value' => 3950000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_crude_large_unit', 'label' => 'Đơn giá từ sau khoảng đặc biệt (VNĐ/m2)', 'name' => 'estimate_crude_large_unit', 'type' => 'number', 'default_value' => 3800000, 'min' => 0, 'step' => 10000),
			array('key' => 'field_dimhouse_estimate_crude_special_min_area', 'label' => 'Bắt đầu khoảng đặc biệt', 'name' => 'estimate_crude_special_min_area', 'type' => 'number', 'default_value' => 300, 'min' => 0, 'step' => 1),
			array('key' => 'field_dimhouse_estimate_crude_special_max_area', 'label' => 'Kết thúc khoảng đặc biệt', 'name' => 'estimate_crude_special_max_area', 'type' => 'number', 'default_value' => 350, 'min' => 0, 'step' => 1),
			array('key' => 'field_dimhouse_estimate_crude_special_unit', 'label' => 'Đơn giá khoảng đặc biệt (VNĐ/m2)', 'name' => 'estimate_crude_special_unit', 'type' => 'number', 'default_value' => 39800000, 'min' => 0, 'step' => 10000),

			array('key' => 'field_dimhouse_estimate_foundation_tab', 'label' => 'Hệ số móng và hầm', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_foundation_pile_design_coeff', 'label' => 'Móng cọc - hệ số thiết kế/hoàn thiện', 'name' => 'estimate_foundation_pile_design_coeff', 'type' => 'number', 'default_value' => 0, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_foundation_pile_raw_coeff', 'label' => 'Móng cọc - hệ số phần thô', 'name' => 'estimate_foundation_pile_raw_coeff', 'type' => 'number', 'default_value' => 0.5, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_foundation_strip_design_coeff', 'label' => 'Móng băng - hệ số thiết kế/hoàn thiện', 'name' => 'estimate_foundation_strip_design_coeff', 'type' => 'number', 'default_value' => 0.2, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_foundation_strip_raw_coeff', 'label' => 'Móng băng - hệ số phần thô', 'name' => 'estimate_foundation_strip_raw_coeff', 'type' => 'number', 'default_value' => 0.7, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_basement_depth_min', 'label' => 'Chiều sâu hầm tối thiểu để tính', 'name' => 'estimate_basement_depth_min', 'type' => 'number', 'default_value' => 1, 'min' => 0, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_basement_coeff_shallow', 'label' => 'Hầm từ 1.0m đến dưới 1.3m', 'name' => 'estimate_basement_coeff_shallow', 'type' => 'number', 'default_value' => 1.5, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_basement_coeff_medium', 'label' => 'Hầm từ 1.3m đến dưới 1.7m', 'name' => 'estimate_basement_coeff_medium', 'type' => 'number', 'default_value' => 1.7, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_basement_coeff_deep', 'label' => 'Hầm từ 1.7m đến dưới 2.2m', 'name' => 'estimate_basement_coeff_deep', 'type' => 'number', 'default_value' => 2, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_basement_coeff_extra_deep', 'label' => 'Hầm từ 2.2m trở lên', 'name' => 'estimate_basement_coeff_extra_deep', 'type' => 'number', 'default_value' => 2.5, 'step' => 0.1),

			array('key' => 'field_dimhouse_estimate_area_tab', 'label' => 'Hệ số diện tích khác', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_mezzanine_floor_coeff', 'label' => 'Tầng lửng có sàn', 'name' => 'estimate_mezzanine_floor_coeff', 'type' => 'number', 'default_value' => 1, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_mezzanine_nofloor_coeff', 'label' => 'Tầng lửng không sàn', 'name' => 'estimate_mezzanine_nofloor_coeff', 'type' => 'number', 'default_value' => 0.5, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_terrace_roof_coeff', 'label' => 'Sân thượng có mái', 'name' => 'estimate_terrace_roof_coeff', 'type' => 'number', 'default_value' => 1, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_terrace_noroof_coeff', 'label' => 'Sân thượng không mái', 'name' => 'estimate_terrace_noroof_coeff', 'type' => 'number', 'default_value' => 0.7, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_yard_coeff', 'label' => 'Phần sân', 'name' => 'estimate_yard_coeff', 'type' => 'number', 'default_value' => 0.5, 'step' => 0.1),

			array('key' => 'field_dimhouse_estimate_roof_tab', 'label' => 'Hệ số mái', 'name' => '', 'type' => 'tab', 'placement' => 'top'),
			array('key' => 'field_dimhouse_estimate_roof_1_design_coeff', 'label' => 'Mái BTCT - hệ số thiết kế', 'name' => 'estimate_roof_1_design_coeff', 'type' => 'number', 'default_value' => 0, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_1_completion_coeff', 'label' => 'Mái BTCT - hệ số hoàn thiện/phần thô', 'name' => 'estimate_roof_1_completion_coeff', 'type' => 'number', 'default_value' => 0.5, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_2_design_coeff', 'label' => 'Mái tôn - hệ số thiết kế', 'name' => 'estimate_roof_2_design_coeff', 'type' => 'number', 'default_value' => -0.2, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_2_completion_coeff', 'label' => 'Mái tôn - hệ số hoàn thiện/phần thô', 'name' => 'estimate_roof_2_completion_coeff', 'type' => 'number', 'default_value' => 0.3, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_3_design_coeff', 'label' => 'Mái ngói kèo sắt - hệ số thiết kế', 'name' => 'estimate_roof_3_design_coeff', 'type' => 'number', 'default_value' => 0.2, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_3_completion_coeff', 'label' => 'Mái ngói kèo sắt - hệ số hoàn thiện/phần thô', 'name' => 'estimate_roof_3_completion_coeff', 'type' => 'number', 'default_value' => 0.7, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_4_design_coeff', 'label' => 'Mái ngói BTCT - hệ số thiết kế', 'name' => 'estimate_roof_4_design_coeff', 'type' => 'number', 'default_value' => 0.5, 'step' => 0.1),
			array('key' => 'field_dimhouse_estimate_roof_4_completion_coeff', 'label' => 'Mái ngói BTCT - hệ số hoàn thiện/phần thô', 'name' => 'estimate_roof_4_completion_coeff', 'type' => 'number', 'default_value' => 1, 'step' => 0.1),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'dimhouse-theme-options',
				),
			),
		),
		'menu_order' => 20,
	));

	acf_add_local_field_group(array(
		'key' => 'group_dimhouse_estimate_content_labels',
		'title' => 'Dimhouse Estimate Content Labels',
		'fields' => array(
			array('key' => 'field_dimhouse_estimate_design_landscape_label', 'label' => 'Landscape Label', 'name' => 'estimate_design_landscape_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_design_interior_label', 'label' => 'Interior Design Label', 'name' => 'estimate_design_interior_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_design_architecture_label', 'label' => 'Architecture Design Label', 'name' => 'estimate_design_architecture_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_design_architecture_interior_label', 'label' => 'Architecture Interior Label', 'name' => 'estimate_design_architecture_interior_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_completion_high_label', 'label' => 'High Completion Label', 'name' => 'estimate_completion_high_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_completion_good_label', 'label' => 'Good Completion Label', 'name' => 'estimate_completion_good_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_completion_standard_label', 'label' => 'Standard Completion Label', 'name' => 'estimate_completion_standard_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_unit_label', 'label' => 'Unit Label', 'name' => 'estimate_unit_label', 'type' => 'text'),
			array('key' => 'field_dimhouse_estimate_rating_label', 'label' => 'Rating Label', 'name' => 'estimate_rating_label', 'type' => 'text'),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'dimhouse-theme-options',
				),
			),
		),
		'menu_order' => 21,
	));

	if (function_exists('acf_add_options_page')) {
		acf_add_options_page(array(
			'page_title' => 'Dimhouse Theme Options',
			'menu_title' => 'Dimhouse Options',
			'menu_slug' => 'dimhouse-theme-options',
			'capability' => 'manage_options',
			'redirect' => false,
		));
	}
}
add_action('acf/init', 'dimhouse_register_acf_fields');
