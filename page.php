<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>
<main id="content" class="site-content">
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class('page-content'); ?>>
			<header class="entry-header container">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content container">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
