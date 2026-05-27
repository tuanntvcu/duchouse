<?php
if (!defined('ABSPATH')) {
	exit;
}

$phone = dimhouse_option('header_phone', '0964158163');
$phone_href = dimhouse_option('header_phone_link', 'tel:0964158163');
?>
<?php if (empty($GLOBALS['dimhouse_rendered_clone_body'])) : ?>
<div id="ims-scroll_left" class=""></div>
<div id="ims-scroll_right" class=""></div>
<div id="ims-loading"><div class="nb-spinner"></div></div>
<div id="ims-data"></div>
<div id="BactoTop" class="bg-color text-color" style="display: none;"><i class="far fa-chevron-up"></i></div>
<div class="hotline sticky" onclick="document.location.href = '<?php echo esc_js($phone_href); ?>'">
	<div class="ring">
		<div class="phone">
			<div class="phone-circle"></div>
			<div class="phone-circle-fill"></div>
			<div class="phone-img-circle"><img src="<?php echo esc_url(dimhouse_asset_uri('resources/images/whatsapp1.png')); ?>" alt="phone"></div>
		</div>
	</div>
</div>
<div class="hotline sticky zalo">
	<div class="ring">
		<a href="<?php echo esc_url('https://zalo.me/' . preg_replace('/\D+/', '', $phone)); ?>" target="_blank" rel="noopener noreferrer">
			<div class="phone">
				<div class="phone-circle"></div>
				<div class="phone-circle-fill"></div>
				<div class="phone-img-circle"><img src="<?php echo esc_url(dimhouse_asset_uri('resources/images/izalo1.png')); ?>" alt="zalo"></div>
			</div>
		</a>
	</div>
</div>
<div class="hotline sticky facebook">
	<div class="ring">
		<a href="https://www.facebook.com/Dimhouse-Design-108306808017773" target="_blank" rel="noopener noreferrer">
			<div class="phone">
				<div class="phone-circle"></div>
				<div class="phone-circle-fill"></div>
				<div class="phone-img-circle"><div class="phone-img-circle"><img src="<?php echo esc_url(dimhouse_asset_uri('resources/images/ifacebook1.png')); ?>" alt="facebook"></div></div>
			</div>
		</a>
	</div>
</div>
<div class="overlay_bo"></div>
<aside class="sideMenu">
	<div id="ims-side-menu">
		<ul class="list_none ">
			<?php foreach (dimhouse_home_defaults()['menu_grid']['cards'] as $index => $item) : ?>
				<li class="menu_li <?php echo 0 === $index ? 'first' : ($index === 3 ? 'last' : ''); ?>">
					<a href="<?php echo esc_url($item['url']); ?>" target="" class="menu_link css_bo "><?php echo esc_html($item['title']); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</aside>
<div class="showMobile bottom_phone">
	<div class="menu_bottom">
		<?php
		$mobile_items = array(
			array('name' => 'Kiáº¿n TrÃºc', 'icon' => 'uploads/layout/2024_04/kientruc.png', 'url' => home_url('/thiet-ke-kien-truc')),
			array('name' => 'Ná»™i Tháº¥t', 'icon' => 'uploads/layout/2024_04/noithat.png', 'url' => home_url('/du-an-noi-that-2')),
			array('name' => 'Thi CÃ´ng', 'icon' => 'uploads/layout/2024_04/congtrinh.png', 'url' => home_url('/du-an-thi-cong-1')),
			array('name' => 'Bá»™ SÆ°u Táº­p', 'icon' => 'uploads/layout/2024_04/bosuutap.png', 'url' => home_url('/bo-suu-tap')),
		);
		?>
		<?php foreach ($mobile_items as $item) : ?>
			<div class="item">
				<a href="<?php echo esc_url($item['url']); ?>" target="_self">
					<span class="icon"><img src="<?php echo esc_url(dimhouse_asset_uri($item['icon'])); ?>" alt="<?php echo esc_attr($item['name']); ?>"></span>
					<span class="name"><?php echo esc_html($item['name']); ?></span>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
