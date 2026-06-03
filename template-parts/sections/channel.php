<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['channel'];
$title = get_sub_field('title') ?: $defaults['title'];
$items = get_sub_field('items');
$items = (is_array($items) && !empty($items)) ? $items : $defaults['items'];
$articles_title = get_sub_field('articles_title') ?: 'Tư vấn chia sẻ';
$articles = get_sub_field('articles');
$articles = (is_array($articles) && !empty($articles)) ? $articles : dimhouse_default_channel_articles();
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
									<div class="img">
										<a href="<?php echo esc_url(!empty($item['url']) ? $item['url'] : '#'); ?>" title="<?php echo esc_attr(!empty($item['title']) ? $item['title'] : ''); ?>" data-fancybox="">
											<?php echo dimhouse_image_html(!empty($item['image']) ? $item['image'] : '', 'full', array('alt' => !empty($item['title']) ? $item['title'] : $title)); ?>
										</a>
									</div>
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
				<div class="focus_title"><?php echo esc_html($articles_title); ?></div>
				<div class="list_item_page">
					<div class="row_item">
						<?php foreach ($articles as $article) : ?>
							<?php
							$article_title = !empty($article['title']) ? $article['title'] : '';
							$article_url = !empty($article['url']) ? $article['url'] : '#';
							$article_subtitle = !empty($article['subtitle']) ? $article['subtitle'] : '';
							?>
							<div class="item">
								<a href="<?php echo esc_url($article_url); ?>"><?php echo dimhouse_image_html(!empty($article['image']) ? $article['image'] : '', 'full', array('alt' => $article_title)); ?></a>
								<h3><a href="<?php echo esc_url($article_url); ?>"><?php echo esc_html($article_title); ?></a></h3>
								<?php if ($article_subtitle) : ?><span><?php echo esc_html($article_subtitle); ?></span><?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
