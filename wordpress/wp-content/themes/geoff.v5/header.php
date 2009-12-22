<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
  <head profile="http://gmpg.org/xfn/11">
    <title><?php
      if     ( is_single()) { bloginfo('name'); print ' | '; single_post_title(); }      
      elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
      elseif ( is_page() ) { single_post_title(''); }
      elseif ( is_search() ) { bloginfo('name'); print ' | Search results for ' . wp_specialchars($s); get_page_number(); }
      elseif ( is_404() ) { bloginfo('name'); print ' | Not Found'; }
      else { bloginfo('name'); wp_title('|'); get_page_number(); }
    ?></title>

    <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
 
    <?php wp_head(); ?>
 
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'your-theme' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!--[if lt IE 8]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
    <![endif]-->

  </head>
  <body>
    <div id="page">
      <div id="header">
        <div class="content">
          <ul>
            <li>My Sites
              <ul>
                <li><a rel="me" href="http://www.geoffholden.com/">GeoffHolden.com</a></li>
                <li><a rel="me" href="http://gkhphoto.com/">GKH Photography</a></li>
                <li><a rel="me" href="http://resume.geoffholden.com/">R&eacute;sum&eacute;</a></li>
              </ul>
            </li>
            <li><img src="<?php bloginfo('template_url'); ?>/images/social/rss_16.png" alt="RSS" /> Feeds
              <ul>
                <li><a href="http://www.geoffholden.com/rss/?i=all">Everything</a></li>
                <li><a href="http://www.geoffholden.com/rss/?i=abt">All But Twitter</a></li>
                <li><a href="http://www.geoffholden.com/rss/?i=blogs">Blogs Only</a></li>
                <li><a href="<?php bloginfo('rss2_url'); ?>">This Blog Only</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="footer"></div>
      </div>
      <div id="main">

<?php if ( is_home() || is_front_page() ) { ?>
    <div id="branding" class="item">
      <div class="header"></div>
      <div class="content">
        <h1><a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home"><?php bloginfo( 'name' ) ?></a></h1>
        <p><?php bloginfo('description'); ?></p>
      </div>
      <div class="footer"></div>
    </div><!-- #branding -->
<?php } ?>
