<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['contact'];
$title = get_sub_field('title');
$text = get_sub_field('text');
$shortcode = get_sub_field('shortcode');
$items = get_sub_field('items');

$title = $title ? $title : $defaults['title'];
$text = $text ? $text : $defaults['text'];
$shortcode = $shortcode ? $shortcode : $defaults['shortcode'];
$items = (is_array($items) && !empty($items)) ? $items : $defaults['items'];
?>
<section class="section section-7 section-contact">
	<div class="container">
		<?php if ($title) : ?><h2><?php echo esc_html($title); ?></h2><?php endif; ?>
		<?php if ($text) : ?><div class="contact-text"><?php echo dimhouse_kses_content($text); ?></div><?php endif; ?>
		<div class="contact-grid row">
			<div class="col-lg-6">
				<?php if (have_rows('items')) : ?>
					<ul class="contact-items">
						<?php while (have_rows('items')) : the_row(); ?>
							<li>
								<strong><?php echo esc_html(get_sub_field('label')); ?></strong>
								<?php if (get_sub_field('url')) : ?>
									<a href="<?php echo esc_url(get_sub_field('url')); ?>"><?php echo esc_html(get_sub_field('value')); ?></a>
								<?php else : ?>
									<span><?php echo esc_html(get_sub_field('value')); ?></span>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
					<?php else : ?>
						<ul class="contact-items">
							<?php foreach ($items as $item) : ?>
								<li>
									<strong><?php echo esc_html(!empty($item['label']) ? $item['label'] : ''); ?></strong>
									<?php if (!empty($item['url'])) : ?>
										<a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html(!empty($item['value']) ? $item['value'] : ''); ?></a>
									<?php else : ?>
										<span><?php echo esc_html(!empty($item['value']) ? $item['value'] : ''); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
				<?php echo $shortcode ? do_shortcode($shortcode) : ''; ?>
			</div>
		</div>
	</div>
</section>
