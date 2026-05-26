<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['footer'];
$footer_logo = dimhouse_option('footer_logo');
$footer_text = dimhouse_option('footer_text');
$footer_partners = dimhouse_option('footer_partners');
$footer_fanpage_iframe = dimhouse_option('footer_fanpage_iframe');
$footer_map_iframe = dimhouse_option('footer_map_iframe');
$footer_email = dimhouse_option('footer_email', 'info@RdMJAeP6qJeR.vn');
$footer_logo = $footer_logo ? $footer_logo : $defaults['footer_logo'];
$footer_text = $footer_text ? $footer_text : $defaults['footer_text'];
$footer_partners = is_array($footer_partners) && !empty($footer_partners) ? $footer_partners : array(
	array('image' => dimhouse_asset_uri('uploads/banner/baner/khuyen-mai-juno.png'), 'url' => '#', 'alt' => 'BRAND'),
	array('image' => dimhouse_asset_uri('uploads/banner/baner/logo-ngang.png'), 'url' => '', 'alt' => 'BRAND'),
	array('image' => dimhouse_asset_uri('uploads/banner/baner/logokatinat.jpg'), 'url' => '', 'alt' => 'BRAND'),
	array('image' => dimhouse_asset_uri('uploads/banner/baner/295762826_454329143369678_112143727416609569_n.jpg'), 'url' => '', 'alt' => 'BRAND'),
	array('image' => dimhouse_asset_uri('uploads/banner/baner/455778-kia-devoile-un-nouveau-logo-et-un-nouveau-slogan.jpg'), 'url' => '', 'alt' => 'BRAND'),
);
$footer_fanpage_iframe = $footer_fanpage_iframe ? $footer_fanpage_iframe : '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fprofile.php%3Fid%3D100065424102650&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="340" height="311" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
$footer_map_iframe = $footer_map_iframe ? $footer_map_iframe : '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.7037275541857!2d106.7471018153159!3d10.68008916388372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31753b730c677b63%3A0xfa33e39535626666!2zS2h1IETDom4gQ8awIEFuaCBUdeG6pW4gR3JlZW4gUml2ZXJzaWRl!5e0!3m2!1sen!2sus!4v1649149804440!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';
?>
<footer class="section section-7" data-anchor="section-7">
	<div class="bg-footer color-footer">
		<div class="brand_scroll">
			<div class="container">
				<div class="brand_scroll-title">Äá»‘i tÃ¡c</div>
				<div class="brand_scroll-content ">
					<?php foreach ($footer_partners as $partner) : ?>
						<?php $partner_url = !empty($partner['url']) ? $partner['url'] : ''; ?>
						<div class="item"><a target="_blank" href="<?php echo esc_url($partner_url); ?>"><img src="<?php echo esc_url(dimhouse_image_url(!empty($partner['image']) ? $partner['image'] : '')); ?>" alt="<?php echo esc_attr(!empty($partner['alt']) ? $partner['alt'] : 'BRAND'); ?>" title="<?php echo esc_attr(!empty($partner['alt']) ? $partner['alt'] : 'BRAND'); ?>" class="ibanner"></a></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="container main_content">
			<div class="top">
				<div class="footer_logo">
					<div class="content"><?php echo dimhouse_kses_content($footer_text); ?></div>
					<a href="<?php echo esc_url(home_url('/')); ?>" target="_self"><img src="<?php echo esc_url(dimhouse_image_url($footer_logo)); ?>" alt="Logo footer"></a>
				</div>
				<div class="fanpage">
					<?php echo dimhouse_kses_iframe($footer_fanpage_iframe); ?>
				</div>
				<div class="contact_map">
					<div class="footer_title">ThÃ´ng tin liÃªn há»‡</div>
					<?php echo dimhouse_kses_iframe($footer_map_iframe); ?>
					<div class="footer_title social_title">Káº¿t ná»‘i vá»›i chÃºng tÃ´i</div>
					<div class="box_social">
						<div class="row mx-0">
							<?php foreach (dimhouse_social_links('header') as $link) : ?>
								<?php if (empty($link['url'])) { continue; } ?>
								<a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo dimhouse_image_html($link['image'], 'thumbnail', array('alt' => !empty($link['label']) ? $link['label'] : 'Social')); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="email"><i class="fas fa-envelope"></i> Email: <?php echo esc_html($footer_email); ?></div>
				</div>
			</div>
		</div>
		<div class="bottom">
			<div class="copyright">Copyright Â© 2022. All rights reserved. <a href="http://thietkewebsite.info.vn" target="_blank">Thiáº¿t káº¿ web</a> <a href="http://imsvietnamese.com" target="_blank">IMS</a></div>
		</div>
		<div class="container"></div>
		<a id="click_popup" class="fancybox_popup" href="#popup_banner_a"></a><div id="popup_banner"><div class="" id="popup_banner_a"><div class="banner_item" style=""><div class="item"><a href="" target="_self"><img src="<?php echo esc_url(dimhouse_asset_uri('uploads/banner/baner/popup_2025_2.jpg')); ?>" alt="POPUP" style=""></a></div></div></div></div>
	</div>
</footer>
