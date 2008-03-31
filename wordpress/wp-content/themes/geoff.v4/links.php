<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<?php get_sidebar(); ?>

<div class="narrowcolumn">

	<h2>Links</h2>
	<div class="entry">
		<ul>
			<?php wp_list_bookmarks('show_images=0&show_description=1&title_before=<h3>&title_after=</h3>&between=<br/>'); ?>
		</ul>

	</div>
</div>

<?php get_footer(); ?>
