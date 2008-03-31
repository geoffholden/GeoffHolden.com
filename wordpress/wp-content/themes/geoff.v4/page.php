<?php get_header(); ?>
<?php get_sidebar(); ?>

	<div class="narrowcolumn page">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			</div>
		</div>
		<?php endwhile; endif; ?>
	</div>

<?php get_footer(); ?>
