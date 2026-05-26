<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['channel'];
$title = get_sub_field('title');
$items = get_sub_field('items');

$title = $title ? $title : $defaults['title'];
$items = (is_array($items) && !empty($items)) ? $items : $defaults['items'];
?>
<div class="section section-5" style="background: #FFF">
	<div class="box_row">
		<div class="focus_product">
			<div class="container">
				<div class="focus_title"><?php echo esc_html($title); ?></div>
				<div class="list_item list_item_product " data-group="0">
					<div class="row_item">
						<?php foreach ($items as $item) : ?>
							<div class="col_item ">
								<div class="item">
									<a href="<?php echo esc_url(!empty($item['url']) ? $item['url'] : '#'); ?>" title="<?php echo esc_attr(!empty($item['title']) ? $item['title'] : ''); ?>" data-fancybox="">
										<?php echo dimhouse_image_html(!empty($item['image']) ? $item['image'] : '', 'full', array('alt' => !empty($item['title']) ? $item['title'] : $title)); ?>
									</a>
									<span><i class="fa fa-play"></i></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="page"></div>
				</div>
			</div>
		</div>

		<div class="focus_page">
			<div class="container">
				<div class="focus_title">Tư vấn chia sẻ</div>
				<div class="list_item_page">
					<div class="row_item">
						<div class="item"><a href="<?php echo esc_url(home_url('/tieu-chuan-thi-cong-xay-dung-ta-home')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/5_tieu_chuanthicong/[580x400-cr]anh_dai_dien___tieuchuanthicong.png__cv.webp')); ?>" alt="Tiêu Chuẩn Thi Công Xây Dựng TA HOME 2025"></a><h3><a href="<?php echo esc_url(home_url('/tieu-chuan-thi-cong-xay-dung-ta-home')); ?>">Tiêu Chuẩn Thi Công Xây Dựng TA HOME 2025</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/ta-home-portfolio')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_ho_so_nang_luc/[580x400-cr]anh_dai_dien___hosonangluc_1.png__cv.webp')); ?>" alt="Hồ Sơ Năng Lực TA - HOME  ( Portfolio )"></a><h3><a href="<?php echo esc_url(home_url('/ta-home-portfolio')); ?>">Hồ Sơ Năng Lực TA - HOME  ( Portfolio )</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/quy-trinh-tu-van-xay-dung-moi-1-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/7_xay_dung_tron_goi/[580x400-cr]anh_dai_dien___xay_dung_tron_goi.png__cv.webp')); ?>" alt="Báo Giá Xây Dựng Trọn Gói"></a><h3><a href="<?php echo esc_url(home_url('/quy-trinh-tu-van-xay-dung-moi-1-1')); ?>">Báo Giá Xây Dựng Trọn Gói</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/quy-trinh-tu-van-xay-dung-moi-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/4_link_anh/[580x400-cr]anhdaidien___baogiathicong.png__cv.webp')); ?>" alt="Tư Vấn Báo Giá Thi Công"></a><h3><a href="<?php echo esc_url(home_url('/quy-trinh-tu-van-xay-dung-moi-1')); ?>">Tư Vấn Báo Giá Thi Công</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/quy-trinh-thiet-ke-cong-trinh')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_tu_van_chia_se/4_link_anh/[580x400-cr]anhdaidien___tuvanthietke_1.png__cv.webp')); ?>" alt="Tư Vấn Thiết Kế Kiến Trúc, Nội Thất."></a><h3><a href="<?php echo esc_url(home_url('/quy-trinh-thiet-ke-cong-trinh')); ?>">Tư Vấn Thiết Kế Kiến Trúc, Nội Thất.</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/cai-tao-sua-chua-nha-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/1_ho_so_nang_luc/[580x400-cr]page_9.jpg__cv.webp')); ?>" alt="Qui Trình Tư Vấn - Cả Tạo Và Sửa Chữa Công Trình."></a><h3><a href="<?php echo esc_url(home_url('/cai-tao-sua-chua-nha-1')); ?>">Qui Trình Tư Vấn - Cả Tạo Và Sửa Chữa Công Trình.</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/quy-trinh-lam-viec-4')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]01.quytrinh-lamviec.jpg__cv.webp')); ?>" alt="TA - HOME Qui Trình Làm Việc"></a><h3><a href="<?php echo esc_url(home_url('/quy-trinh-lam-viec-4')); ?>">TA - HOME Qui Trình Làm Việc</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/thiet-ke-shop-nail-spa-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/thiet_ke_shop/[580x400-cr]bia_shop.jpg__cv.webp')); ?>" alt="THIẾT KẾ SHOP - NAIL - SPA"></a><h3><a href="<?php echo esc_url(home_url('/thiet-ke-shop-nail-spa-1')); ?>">THIẾT KẾ SHOP - NAIL - SPA</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/khuyen-mai-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/2_anh_nhan_su/[580x400-cr]dji_0297.jpg__cv.webp')); ?>" alt="KHUYẾN MÃI"></a><h3><a href="<?php echo esc_url(home_url('/khuyen-mai-1')); ?>">KHUYẾN MÃI</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/goi-thiet-ke')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/nophoto/[580x400-cr]nophoto.jpg__cv.webp')); ?>" alt="GÓI THIẾT KẾ"></a><h3><a href="<?php echo esc_url(home_url('/goi-thiet-ke')); ?>">GÓI THIẾT KẾ</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/khai-toan-phan-tho')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/nophoto/[580x400-cr]nophoto.jpg__cv.webp')); ?>" alt="KHAI TOÁN PHẦN THÔ"></a><h3><a href="<?php echo esc_url(home_url('/khai-toan-phan-tho')); ?>">KHAI TOÁN PHẦN THÔ</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/chia-khoa-trao-tay')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/nophoto/[580x400-cr]nophoto.jpg__cv.webp')); ?>" alt="CHÌA KHÓA TRAO TAY"></a><h3><a href="<?php echo esc_url(home_url('/chia-khoa-trao-tay')); ?>">CHÌA KHÓA TRAO TAY</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/phong-cach-thiet-ke-tropical-style-phong-cach-nhiet-doi')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]tropical.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_ TROPICAL STYLE - PHONG CÁCH NHIỆT ĐỚI"></a><h3><a href="<?php echo esc_url(home_url('/phong-cach-thiet-ke-tropical-style-phong-cach-nhiet-doi')); ?>">PHONG CÁCH THIẾT KẾ_ TROPICAL STYLE - PHONG CÁCH NHIỆT ĐỚI</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/phong-cach-thiet-ke-color-block-phong-cach-phoi-mau')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]color-block.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_ COLOR BLOCK STYLE - PHONG CÁCH PHỐI MÀU"></a><h3><a href="<?php echo esc_url(home_url('/phong-cach-thiet-ke-color-block-phong-cach-phoi-mau')); ?>">PHONG CÁCH THIẾT KẾ_ COLOR BLOCK STYLE - PHONG CÁCH PHỐI MÀU</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/industrial-style-phong-cach-cong-nghiep')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]industrial.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_INDUSTRIAL STYLE - PHONG CÁCH CÔNG NGHIỆP"></a><h3><a href="<?php echo esc_url(home_url('/industrial-style-phong-cach-cong-nghiep')); ?>">PHONG CÁCH THIẾT KẾ_INDUSTRIAL STYLE - PHONG CÁCH CÔNG NGHIỆP</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/minimalism-style-phong-cach-toi-gian')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]minimalism.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_MINIMALISM STYLE - PHONG CÁCH TỐI GIẢN"></a><h3><a href="<?php echo esc_url(home_url('/minimalism-style-phong-cach-toi-gian')); ?>">PHONG CÁCH THIẾT KẾ_MINIMALISM STYLE - PHONG CÁCH TỐI GIẢN</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/indochine-style-phong-cach-dong-duong')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]indochine.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_INDOCHINE STYLE - PHONG CÁCH ĐÔNG DƯƠNG"></a><h3><a href="<?php echo esc_url(home_url('/indochine-style-phong-cach-dong-duong')); ?>">PHONG CÁCH THIẾT KẾ_INDOCHINE STYLE - PHONG CÁCH ĐÔNG DƯƠNG</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/neoclassical-style-phong-cach-ban-co-dien')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]neoclassical.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_NEOCLASSICAL STYLE - PHONG CÁCH BÁN CỔ ĐIỂN"></a><h3><a href="<?php echo esc_url(home_url('/neoclassical-style-phong-cach-ban-co-dien')); ?>">PHONG CÁCH THIẾT KẾ_NEOCLASSICAL STYLE - PHONG CÁCH BÁN CỔ ĐIỂN</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/scandinavian-style-phong-cach-bac-au')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]scandinavian.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_SCANDINAVIAN STYLE - PHONG CÁCH BẮC ÂU"></a><h3><a href="<?php echo esc_url(home_url('/scandinavian-style-phong-cach-bac-au')); ?>">PHONG CÁCH THIẾT KẾ_SCANDINAVIAN STYLE - PHONG CÁCH BẮC ÂU</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
						<div class="item"><a href="<?php echo esc_url(home_url('/modern-phong-cach-hien-dai-1')); ?>"><img src="<?php echo esc_url(dimhouse_asset_uri('thumbs/page/cover/[580x400-cr]modern.jpg__cv.webp')); ?>" alt="PHONG CÁCH THIẾT KẾ_MODERN STYLE - PHONG CÁCH HIỆN ĐẠI"></a><h3><a href="<?php echo esc_url(home_url('/modern-phong-cach-hien-dai-1')); ?>">PHONG CÁCH THIẾT KẾ_MODERN STYLE - PHONG CÁCH HIỆN ĐẠI</a></h3><span>TAHOME - Thiết Kế & Thi Công Công Trình</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
