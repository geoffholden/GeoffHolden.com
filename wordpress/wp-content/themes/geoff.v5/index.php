<?php get_header(); ?>

<?php 
$count = 0;
$max_count = 10;
while (have_posts()) : the_post() ?>

<?php
if (is_home() || is_front_page() ):
  $syndicated = gkh_get_feeds();
  while (strcmp($syndicated[0]['date'], get_the_time("Y-m-d H:i:s")) > 0):
    $item = array_shift($syndicated);
    $count++;
    if ($count > $max_count) break;
?>

<div class="item <?php echo $item['class']; ?>">
  <div class="header">
  </div>
  <div class="content">
    <span class="date">
      <?php
      if ($item['class'] == "twitter") {
        echo date(get_option('date_format') . ", " . get_option('time_format'), strtotime($item['date']));
      } else {
        echo date(get_option('date_format'), strtotime($item['date']));
      }
      ?>
    </span>
    <h2 class="title">
      <?php if ($item['class'] == "twitter") { ?>
      <img src="<?php bloginfo('template_directory'); ?>/images/social/twitter_32.png" alt="Twitter" />
      Geoff said on Twitter:
      <?php } elseif ($item['class'] == "greader") { ?>
      <img src="<?php bloginfo('template_directory'); ?>/images/social/google_32.png" alt="Google Reader" />
      Geoff shared on Google Reader:
      <?php } else if ($item['class'] == "gkhphoto") { ?>
      <a href="<?php echo $item['link']; ?>">GKH Photography: <?php echo $item['title']; ?></a>
      <?php } ?>
    </h2>

    <?php if ($item['class'] == "greader") { ?>
      <h3 class="source"><a href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a></h3>
      <h4 class="source">via <a href="<?php echo $item['feedhome']; ?>"><?php echo $item['feedtitle']; ?></a></h4>
    <?php } ?>

    <?php echo $item['content']; ?>
  </div>
  <div class="footer">
  </div>
</div>




<?php endwhile; endif; ?>

<?php
$count++;
if ((is_home() || is_front_page()) && $count > $max_count) break;
?>
<div class="item">
  <div class="header">
  </div>
  <div class="content">
    <span class="date"><?php the_date() ?></span>
    <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_content(); ?>

    <?php if (comments_open()) : ?>
    <p class="comments-link">
      <?php comments_popup_link('No comments yet', '1 Comment', '% Comments') ?>
    </p>
    <?php endif; ?>
  </div>
  <div class="footer">
  </div>
</div>

<?php endwhile; ?>

<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
<?php if (!(is_home() || is_front_page())): ?>
<div class="item navigation">
  <div class="header"></div>
  <div class="content">
    <div class="prev"><?php next_posts_link('&laquo; Older Posts') ?></div>
    <div class="next"><?php previous_posts_link('Newer Posts &raquo;') ?></div>
  </div>
  <div class="footer"></div>
</div>
<?php endif; ?>
<?php } ?>


<?php get_footer(); ?>
