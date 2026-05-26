<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>
<main id="content" class="site-content container">
	<section class="error-404 not-found">
		<h1><?php esc_html_e('Page not found', 'dimhouse'); ?></h1>
		<p><?php esc_html_e('The page you are looking for does not exist.', 'dimhouse'); ?></p>
		<?php get_search_form(); ?>
	</section>
</main>
<?php
get_footer();
