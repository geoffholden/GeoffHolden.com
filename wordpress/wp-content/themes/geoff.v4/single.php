<?php get_header(); ?>

	<div class="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post">
			<h2><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<small>Posted <?php the_time('F jS, Y') ?> in <?php the_category(', ') ?></small>

			<div class="entry">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			</div>	

		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

	</div>

<?php get_footer(); ?>
