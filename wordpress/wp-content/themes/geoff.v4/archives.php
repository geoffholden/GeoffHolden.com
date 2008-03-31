<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<?php get_sidebar(); ?>

<div class="narrowcolumn">

	<h2>Archives</h2>
	<div class="entry">
		<?php
			// Get all of the months that have posts
			$monthquery = "SELECT DISTINCT YEAR(post_date) AS year, MONTH(post_date) AS month, count(ID) as posts FROM " . $wpdb->posts . " WHERE post_date <'" . current_time('mysql') . "' AND post_status='publish' AND post_type='post' AND post_password=''";
			$monthquery .= " GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
			$monthresults = $wpdb->get_results($monthquery);

			if ($monthresults) {
				// Loop through each month
				foreach ($monthresults as $monthresult) {
					$thismonth  = zeroise($monthresult->month, 2); 
					$thisyear   = $monthresult->year;

					// Get all of the posts for the current month
					$postquery = "SELECT ID, post_date, post_title, comment_status FROM " . $wpdb->posts . " WHERE post_date LIKE '$thisyear-$thismonth-%' AND post_date AND post_status='publish' AND post_type='post'";
					if ($srg_show_passworded_posts != TRUE) $postquery .= " AND post_password=''";
					$postquery .= " ORDER BY post_date DESC";
					$postresults = $wpdb->get_results($postquery);

					if ($postresults) {
						// The month year title things
						$text = sprintf('%s %d', $month[zeroise($monthresult->month,2)], $monthresult->year);
						$postcount = count($postresults);
						echo '<p><strong>' . $text . '</strong></p>';

						echo "<ul class='postspermonth'>\n";
						foreach ($postresults as $postresult) {
							if ($postresult->post_date != '0000-00-00 00:00:00') {
								$url     = get_permalink($postresult->ID);
								$title   = $postresult->post_title;
								if ($title) $text = wptexturize(strip_tags($title));
								else $text = $postresult->ID;
								echo '   <li>' . mysql2date('d', $postresult->post_date) . ':&nbsp;' . "<a href=\"$url\">$text</a></li>\n";
							}
						}
						echo "</ul>\n";
					}
				}
			}
		?>
	</div>
</div>

<?php get_footer(); ?>
