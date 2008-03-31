<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<style type="text/css" media="screen">
	#page { background-image: url("<?php bloginfo('stylesheet_directory'); ?>/images/background.png"); }
</style>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/pngfix.js"></script>
<![endif]-->

<?php wp_head(); ?>
</head>
<body>
<div id="page">


<div id="header">
	<h1><a href="<?php echo get_option('home'); ?>/" title="Home" rel="me"><?php bloginfo('name'); ?></a></h1>
	<a href="<?php echo get_option('home'); ?>/" title="Home" class="image" rel="me"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/header.png" alt="Geoff Holden" width="324" height="75"/></a>
	<ul>
		<li><a href="<?php $page = get_page_by_title('Archives'); echo get_permalink($page->ID); ?>" title="Archives">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/archives.png" alt="Archives" height="23" width="76" /></a>
		</li>
		<li><a href="<?php $page = get_page_by_title('Projects'); echo get_permalink($page->ID); ?>" title="Projects">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/projects.png" alt="Projects" height="23" width="78" /></a>
		</li>
		<li><a href="<?php $page = get_page_by_title('Presentations'); echo get_permalink($page->ID); ?>" title="Presentations">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/presentations.png" alt="Presentations" height="23" width="120" /></a>
		</li>
		<li><a href="http://gkhphoto.com/" rel="me" title="Photography">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/photography.png" alt="Photography" height="23" width="111" /></a>
		</li>
		<li><a href="<?php $page = get_page_by_title('Links'); echo get_permalink($page->ID); ?>" title="Links" rel="me">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/links.png" alt="Links" height="23" width="47" /></a>
		</li>
	</ul>
</div>
