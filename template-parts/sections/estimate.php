<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['estimate'];
$title = get_sub_field('title');
$intro = get_sub_field('intro');
$tabs = get_sub_field('tabs');

$title = $title ? $title : $defaults['title'];
$intro = $intro ? $intro : $defaults['intro'];
$tabs = (is_array($tabs) && !empty($tabs)) ? $tabs : $defaults['tabs'];
?>
<div class="construction section section-3">
	<div class="container">
		<h3 class="title"><?php echo esc_html($title); ?></h3>
				<div class="wrap_top">
					<div class="left">
						<div class="for_form">
							<div class="choose_floor">
								<div class="list_input row">
									<div class="form-group col-6">
										<label>Tầng</label>
										<div class="btn_grps"><span class="btn_minus"></span><input type="number" value="0" min="0" class="floor quantity_text no-spinners"><span class="btn_plus"></span></div>
									</div>
									<div class="form-group col-6">
										<label>Lửng</label>
										<div class="btn_grps"><span class="btn_minus"></span><input type="number" value="0" min="0" class="mezzanine quantity_text no-spinners"><span class="btn_plus"></span></div>
									</div>
								</div>
								<div class="view_house"><img src="<?php echo esc_url(dimhouse_asset_uri('uploads/estimate/tang_lung/khong_lung/0_lau_n.jpg')); ?>" alt="Ảnh 0 tầng 0 lửng"></div>
							</div>
							<div class="banner_item" style=""><a href="<?php echo esc_url(home_url('/khuyen-mai-1')); ?>" target="_blank"><img class="lazyload" src="<?php echo esc_url(dimhouse_asset_uri('resources/images/spin.svg')); ?>" data-src="<?php echo esc_url(dimhouse_asset_uri('uploads/banner/baner/du_toan_1.jpg')); ?>" alt="Dự toán chi phí"></a></div>
						</div>
						<div class="for_result" style="display: none">
							<div class="button_book"><button class="book">Đặt lịch tư vấn</button></div>
							<div class="banner tab1 show"><div class="banner_item" style=""><a href="<?php echo esc_url(home_url('/goi-thiet-ke')); ?>" target="_blank"><img class="lazyload" src="<?php echo esc_url(dimhouse_asset_uri('resources/images/spin.svg')); ?>" data-src="<?php echo esc_url(dimhouse_asset_uri('uploads/banner/baner/goi_thietke.jpg')); ?>" alt="Banner"></a></div></div>
							<div class="banner tab2"><div class="banner_item" style=""><a href="<?php echo esc_url(home_url('/khai-toan-phan-tho')); ?>" target="_blank"><img class="lazyload" src="<?php echo esc_url(dimhouse_asset_uri('resources/images/spin.svg')); ?>" data-src="<?php echo esc_url(dimhouse_asset_uri('uploads/banner/baner/goi_xd_tho.jpg')); ?>" alt="Banner"></a></div></div>
							<div class="banner tab3"><div class="banner_item" style=""><a href="<?php echo esc_url(home_url('/chia-khoa-trao-tay')); ?>" target="_blank"><img class="lazyload" src="<?php echo esc_url(dimhouse_asset_uri('resources/images/spin.svg')); ?>" data-src="<?php echo esc_url(dimhouse_asset_uri('uploads/banner/baner/goi_chia_khoa.jpg')); ?>" alt="Banner"></a></div></div>
						</div>
					</div>
					<div class="form">
						<ul class="nav nav-pills">
							<?php foreach ($tabs as $index => $tab) : ?>
								<li class="nav-item"><a href="#<?php echo esc_attr(!empty($tab['key']) ? $tab['key'] : 'tab' . ($index + 1)); ?>" data-toggle="tab" class="nav-link<?php echo 0 === $index ? ' active' : ''; ?>"><?php echo esc_html(!empty($tab['label']) ? $tab['label'] : ''); ?><div class="price"><?php echo esc_html(!empty($tab['price_label']) ? $tab['price_label'] : ''); ?></div></a></li>
							<?php endforeach; ?>
						</ul>
						<div class="wrap_content">
							<div class="tab-content" style="display: none">
								<?php foreach ($tabs as $index => $tab) : ?>
									<div class="tab-pane<?php echo 0 === $index ? ' active' : ''; ?>" id="<?php echo esc_attr(!empty($tab['key']) ? $tab['key'] : 'tab' . ($index + 1)); ?>">
										<div class="row align-items-start">
											<div class="col-sm-4"><div id="<?php echo esc_attr(0 === $index ? 'design_type-content-9' : ('design_type-content-' . $index)); ?>" class="estimate-item-content<?php echo 0 === $index ? ' show' : ''; ?>"><?php echo dimhouse_kses_content(!empty($tab['content']) ? $tab['content'] : ''); ?></div></div>
											<div class="col-sm-8"><div class="box_content"><div class="estimate-item-content show"><?php echo dimhouse_kses_content(!empty($tab['content']) ? $tab['content'] : ''); ?></div></div></div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
	</div>
</div>
