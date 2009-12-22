<?php get_header(); ?>

<?php the_post(); ?>

<div class="item">
  <div class="header">
  </div>
  <div class="content">
    <span class="date"><?php the_date() ?></span>
    <h2 class="title"><?php the_title(); ?></h2>
    <?php the_content(); ?>
    <?php comments_template(); ?>
  </div>
  <div class="footer">
  </div>
</div>

<?php get_footer(); ?>
