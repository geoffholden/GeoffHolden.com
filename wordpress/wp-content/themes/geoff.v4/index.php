<?php get_header(); ?>

<?php get_sidebar(); ?>

	<?php if (have_posts()) : ?>
		<?php gkh_setFlickrMaxSize('Thumbnail'); ?>
		<?php gkh_setFlickrDefaultSize('Thumbnail'); ?>

		<?php query_posts("showposts=1"); ?>
		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="mainpost">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<small>Posted <?php the_time('F jS, Y') ?> in <?php the_category(', ') ?></small>

				<div class="entry">
					<?php the_excerpt(); ?>
				</div>
				<p><small><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></small></p>

			</div>

		<?php endwhile; ?>
				
		<img src="<?php bloginfo('stylesheet_directory'); ?>/images/line.png" alt="" width="600" height="32" />

		<?php query_posts("showposts=2&offset=1"); ?>

		<?php while (have_posts()) : the_post(); ?>

			<?php $postid = "smallpost" . (($wp_query->current_post == 0) ? 'left' : 'right'); ?>
			<div class="post smallpost" id="<?php echo $postid; ?>">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<small><?php the_time('F jS, Y') ?></small>

				<div class="entry">
					<?php the_excerpt(); ?>
				</div>
			</div>

		<?php endwhile; ?>

	<?php else : ?>

		<h2 class="center">Not Found</h2>

	<?php endif; ?>

	<?php get_footer(); ?>
