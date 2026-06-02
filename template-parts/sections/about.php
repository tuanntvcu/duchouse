<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['about'];
$title = get_sub_field('title') ?: $defaults['title'];
$text = get_sub_field('text') ?: $defaults['text'];
$image = get_sub_field('image') ?: $defaults['image'];
$person_name = get_sub_field('person_name') ?: 'Nguyễn Trung';
$person_role = get_sub_field('person_role') ?: 'CEO & Founder';
$cta_label = get_sub_field('cta_label') ?: 'Xem hồ sơ năng lực';
$cta_url = get_sub_field('cta_url') ?: home_url('/Dimhouse-portfolio');
?>
<div class="section section-4">
	<div class="box_personnel">
		<div class="container">
			<h3 class="title"><?php echo esc_html($title); ?></h3>
			<div class="inner">
				<div class="about">
					<div class="content"><?php echo dimhouse_kses_content($text); ?></div>
				</div>
				<div class="list_item">
					<div class="item">
						<div class="imgae"><img src="<?php echo esc_url(dimhouse_image_url($image)); ?>" alt="<?php echo esc_attr($person_name); ?>"></div>
						<div class="title"><span><?php echo esc_html($person_name); ?></span><div><p><?php echo esc_html($person_role); ?></p></div></div>
						<a class="item btn" href="<?php echo esc_url($cta_url); ?>"><u><?php echo esc_html($cta_label); ?></u></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
