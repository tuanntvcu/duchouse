<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['testimonials'];
$contact_defaults = dimhouse_home_defaults()['contact'];
$title = get_sub_field('title');
$text = get_sub_field('text');
$items = get_sub_field('items');

$title = $title ? $title : $defaults['title'];
$text = $text ? $text : $defaults['text'];
$items = (is_array($items) && !empty($items)) ? $items : $defaults['items'];
?>
<div class="section section-6">
	<div class="box_row">
		<div class="box_comment">
			<div class="container">
				<div class="box_mid-title"><div class="mid_title_l"><h4><?php echo esc_html($title); ?></h4></div></div>
				<div class="box_mid-content">
					<div class="wrap_comment">
						<div class="list_item_project">
							<?php foreach ($items as $item) : ?>
								<div class="col_comment">
									<div class="comment">
										<div class="info_item">
											<?php
											$testimonial_title = !empty($item['role']) ? trim($item['role']) . ' ' . (!empty($item['name']) ? $item['name'] : '') : (!empty($item['name']) ? $item['name'] : '');
											$rating = dimhouse_testimonial_rating_score(!empty($item['rating']) ? $item['rating'] : '');
											?>
											<figure><a title="<?php echo esc_attr($testimonial_title); ?>"><?php echo dimhouse_image_html(!empty($item['avatar']) ? $item['avatar'] : '', 'thumbnail', array('alt' => '')); ?></a></figure>
											<div class="info_title"><div class="title"><?php echo !empty($item['role']) ? esc_html($item['role']) . ' ' : ''; ?><span><?php echo esc_html(!empty($item['name']) ? $item['name'] : ''); ?></span></div><div class="rate">Đánh giá : <span title="<?php echo esc_attr($rating . '/10'); ?>"><?php echo esc_html($rating . '/10'); ?></span></div></div>
										</div>
										<div class="short"><p><?php echo dimhouse_kses_content(!empty($item['text']) ? $item['text'] : ''); ?></p></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="contact">
			<div class="container">
				<div class="title_contact">
					<a href="<?php echo esc_url(home_url('/lien-he-voi-chung-toi')); ?>">
						<img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/about/[550x307-cr]14_trang_den.png__cv.webp')); ?>" alt="LIÊN HỆ VỚI CHÚNG TÔI">
						<h3><?php echo esc_html($contact_defaults['title']); ?></h3>
					</a>
				</div>
				<div class="wrap">
					<div>
						<div><?php echo dimhouse_contact_icon_svg('phone', 0); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="info"><div class="name">Hotline tư vấn:</div><div>0392959713</div></div>
					</div>
					<div>
						<div><?php echo dimhouse_contact_icon_svg('facebook', 1); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="info"><div class="name">Fanpage Facebook:</div><div>facebook.com/DimHouse.DimDecor</div></div>
					</div>
					<div>
						<div><?php echo dimhouse_contact_icon_svg('email', 2); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="info"><div class="name">Email tư vấn:</div><div>dimhousevietnam@gmail.com</div></div>
					</div>
					<div>
						<div><?php echo dimhouse_contact_icon_svg('business', 3); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div class="info"><div class="name">Đăng Kí Kinh Doanh:</div><div>10/169 Thái Hà, Đống Đa, Hà Nội</div><div>Văn Phòng:</div><div><span style="font-size: 15px;">10/169 Thái Hà, Đống Đa, Hà Nội</span></div><div>&nbsp;</div></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
