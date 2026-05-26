<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['about'];
$title = get_sub_field('title');
$text = get_sub_field('text');
$image = get_sub_field('image');

$title = $title ? $title : $defaults['title'];
$text = $text ? $text : $defaults['text'];
$image = $image ? $image : $defaults['image'];
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
						<div class="imgae"><img src="<?php echo esc_url(dimhouse_image_url($image)); ?>" alt="Nguyễn Trung"></div>
						<div class="title"><span>Nguyễn Trung</span><div><p>CEO & Founder</p></div></div>
						<a class="item btn" href="<?php echo esc_url(home_url('/ta-home-portfolio')); ?>"><u>Xem hồ sơ năng lực</u></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
