<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<?php if (empty($GLOBALS['dimhouse_rendered_clone_body'])) : ?>
	<?php echo dimhouse_render_floating_ui_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
