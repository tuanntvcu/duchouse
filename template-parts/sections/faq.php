<?php
if (!defined('ABSPATH')) {
	exit;
}

$defaults = dimhouse_home_defaults()['faq'];
$title = get_sub_field('title');
$items = get_sub_field('items');

$title = $title ? $title : $defaults['title'];
$items = (is_array($items) && !empty($items)) ? $items : $defaults['items'];
?>
<section class="section section-6 section-faq">
	<div class="container">
		<?php if ($title) : ?><h2><?php echo esc_html($title); ?></h2><?php endif; ?>
		<div class="faq-list">
			<?php if (have_rows('items')) : ?>
				<?php while (have_rows('items')) : the_row(); ?>
					<details class="faq-item">
						<summary><?php echo esc_html(get_sub_field('question')); ?></summary>
						<div class="faq-answer"><?php echo dimhouse_kses_content(get_sub_field('answer')); ?></div>
					</details>
				<?php endwhile; ?>
			<?php else : ?>
				<?php foreach ($items as $item) : ?>
					<details class="faq-item">
						<summary><?php echo esc_html(!empty($item['question']) ? $item['question'] : ''); ?></summary>
						<div class="faq-answer"><?php echo dimhouse_kses_content(!empty($item['answer']) ? $item['answer'] : ''); ?></div>
					</details>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
