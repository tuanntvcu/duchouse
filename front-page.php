<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
$clone_body = dimhouse_index_fallback_body();
?>
<?php if ($clone_body) : ?>
	<?php
	$GLOBALS['dimhouse_rendered_clone_body'] = true;
	echo $clone_body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
<?php else : ?>
	<?php get_template_part('template-parts/sections/default-home'); ?>
<?php endif; ?>
<?php
get_footer();
