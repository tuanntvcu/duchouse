<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['hero'];
$eyebrow = get_sub_field('eyebrow');
$title = get_sub_field('title');
$text = get_sub_field('text');
$video = get_sub_field('video');
$image = get_sub_field('image');
$buttons = get_sub_field('buttons');
$banners = get_sub_field('banners');

$eyebrow = $eyebrow ? $eyebrow : $defaults['eyebrow'];
$title = $title ? $title : $defaults['title'];
$text = $text ? $text : $defaults['text'];
$video = $video ? $video : $defaults['video'];
$image = $image ? $image : $defaults['image'];
$buttons = (is_array($buttons) && !empty($buttons)) ? $buttons : $defaults['buttons'];
$banners = (is_array($banners) && !empty($banners)) ? $banners : $defaults['banners'];
?>
<div class="section section-0" style="background: #FFF">
	<div class="slider banner_social">
		<div id="main_slide">
			<div class="row_item">
				<div class="item">
					<?php if ($video) : ?>
						<video src="<?php echo esc_url(dimhouse_image_url($video)); ?>" autoplay loop muted playsinline data-keepplaying style="width:100%;"></video>
					<?php elseif ($image) : ?>
						<?php echo dimhouse_image_html($image, 'full', array('class' => 'hero-image', 'alt' => $title ? $title : get_bloginfo('name'))); ?>
					<?php endif; ?>
					<div class="short">
						<div class="content">
							<div class="inner">
								<?php if ($title) : ?><h2><?php echo esc_html($title); ?></h2><?php endif; ?>
								<?php if ($text) : ?><?php echo dimhouse_kses_content($text); ?><?php endif; ?>
								<?php if (have_rows('buttons')) : ?>
									<div class="buttons">
										<?php while (have_rows('buttons')) : the_row(); ?>
											<?php echo dimhouse_render_button(array('url' => get_sub_field('url'), 'title' => get_sub_field('label'), 'style' => get_sub_field('style'))); ?>
										<?php endwhile; ?>
									</div>
								<?php elseif (is_array($buttons) && !empty($buttons)) : ?>
									<div class="buttons">
										<?php foreach ($buttons as $button) : ?>
											<?php echo dimhouse_render_button($button); ?>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if (have_rows('banners') || (is_array($banners) && !empty($banners))) : ?>
		<div class="hero-banners container">
			<?php if (have_rows('banners')) : ?>
				<?php while (have_rows('banners')) : the_row(); ?>
					<?php $banner_image = get_sub_field('image'); $banner_url = get_sub_field('url'); $banner_alt = get_sub_field('alt'); ?>
					<div class="banner_item">
						<a href="<?php echo esc_url($banner_url ?: '#'); ?>"<?php echo $banner_url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo dimhouse_image_html($banner_image, 'full', array('alt' => $banner_alt ? $banner_alt : 'Banner')); ?>
						</a>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php foreach ($banners as $banner) : ?>
					<div class="banner_item">
						<a href="<?php echo esc_url(!empty($banner['url']) ? $banner['url'] : '#'); ?>"<?php echo !empty($banner['url']) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php echo dimhouse_image_html(!empty($banner['image']) ? $banner['image'] : '', 'full', array('alt' => !empty($banner['alt']) ? $banner['alt'] : 'Banner')); ?>
						</a>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<lottie-player src="<?php echo esc_url(dimhouse_asset_uri('resources/images/mouse.json')); ?>" background="index.html" speed="1" style="width: 42px; height: 50px;margin: 15px auto 0" loop="" autoplay=""></lottie-player>
</div>
