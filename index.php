<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>
<main id="content" class="site-content container">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<article <?php post_class('post-card'); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-summary"><?php the_excerpt(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e('No content found.', 'dimhouse'); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
