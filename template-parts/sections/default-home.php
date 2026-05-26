<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<section class="section section-0 section-default-home">
	<div class="container">
		<h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
		<div class="content">
			<?php echo dimhouse_kses_content(get_bloginfo('description')); ?>
		</div>
	</div>
</section>
