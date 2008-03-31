<?php
/*
Plugin Name: Posting Frequency
Version: 1.0
Plugin URI: http://www.geoffholden.com/projects/wp-postingfrequency/
Description: Displays the number of posts and post frequency over a time period.
Author: Geoff Holden
Author URI: http://www.geoffholden.com/
*/

function gh_postFrequency_block() {
	$title = get_option('gh_postcount_block_title');
	$link = get_option('gh_postcount_block_link');
	echo "<div><h3>";
	if ($link != "") {
		echo "<a href=\"$link\">$title</a>";
	} else {
		echo $title;
	}
	echo "</h3><ul>\n";
	$blockConfig = get_option('gh_postcount_blockconfig');
	foreach (explode(";", preg_replace("/;$/", "", $blockConfig)) as $item) {
		$itemArray = explode("|", $item);
		$count = gh_postCount($itemArray[0] . " " . $itemArray[1]);
		echo "<li>";
		echo $count;
		if ($count == 1) {
			echo " post";
		} else {
			echo " posts";
		}
		echo " in the last ";
		if ($itemArray[0] != 1) {
			echo "$itemArray[0] $itemArray[1]s";
		} else {
			echo "$itemArray[1]";
		}
		echo " (";
		printf("%01.2f", gh_postFrequency($itemArray[0] . " " . $itemArray[1], $itemArray[2]));
		echo " per $itemArray[2])</li>";
	}
	echo "</ul></div>";
}

function gh_postCount($timeFrame = "ALL") {
	global $wpdb;

	if ($timeFrame != "ALL") {
		$startDate = "AND post_date >= '" . date("Y-m-d", strtotime("today-" . $timeFrame)) . "'";
	}

	$categories = "WHERE";
	$catOption = get_option('gh_postcount_exclcats');
	if ($catOption != "-1,") {
		$categories = "INNER JOIN $wpdb->post2cat ON $wpdb->posts.ID = $wpdb->post2cat.post_id WHERE category_id NOT IN ($catOption) AND";
	}
	$numPosts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $wpdb->posts $categories post_status = 'publish' AND post_date <= NOW() $startDate");

	return $numPosts;
}

function gh_postFrequency($timeFrame = "ALL", $per = "day") {
	global $wpdb;

	if ($timeFrame != "ALL") {
		$startDate = strtotime("today-" . $timeFrame);
	} else {
		# Get the date of the first post, ever.
		$startDate = strtotime($wpdb->get_var("SELECT MIN(post_date) FROM $wpdb->posts WHERE post_status = 'publish'"));
	}

	switch($per) {
		case "year":
			$interval = floor((time() - $startDate)/31557600);
			break;
		case "quarter":
			$interval = floor((time() - $startDate)/7862400);
			break;
		case "month":
			$interval = floor((time() - $startDate)/2592000);
			break;
		case "week":
			$interval = floor((time() - $startDate)/604800);
			break;
		case "day":
		default:
			$interval = floor((time() - $startDate)/86400);
	}

	return gh_postCount($timeFrame) / $interval;
}

function gh_postcount_options_hook() {
	if (function_exists('add_options_page')) {
		add_options_page('Post Count/Frequency', 'Post Count/Frequency', 0, basename(__FILE__), 'gh_postcount_options_subpanel');
	}
}

function gh_postcount_options_subpanel() {
	global $wpdb;
	if (isset($_POST['info_update'])) {
		?><div class="updated"><?php 
		$categories = "-1";
		if ($_POST['categories']) {
			foreach ($_POST['categories'] as $cat) {
				$categories = $categories . "," . $cat;
			}
			update_option('gh_postcount_exclcats', $categories);
			echo "<p><strong>Updated.</strong></p>";
		}
		if ($_POST['buildindex']) {
			$wpdb->hide_errors();
			if ($wpdb->query("ALTER TABLE $wpdb->posts ADD INDEX id_status_date(id,post_status,post_date)") === FALSE) {
				echo "<p><strong>Index Previously Added.</p></strong>";
			} else {
				echo "<p><strong>Index Added.</p></strong>";
			}
			$wpdb->show_errors();
		}
		update_option('gh_postcount_blockconfig', $_POST['items']);
		update_option('gh_postcount_block_title', $_POST['block_title']);
		update_option('gh_postcount_block_link',  $_POST['block_link']);

		update_option('gh_postcount_backgroundcolour', $_POST['backgroundcolour']);
		update_option('gh_postcount_barcolour',        $_POST['barcolour']);
		update_option('gh_postcount_linecolour',       $_POST['linecolour']);
		update_option('gh_postcount_textcolour',       $_POST['textcolour']);
		update_option('gh_postcount_bordercolour',     $_POST['bordercolour']);
		@unlink(dirname(__FILE__) . '/image/cache/cache');
    ?></div><?php
	} else {
		add_option("gh_postcount_backgroundcolour", "#e6ecff");
		add_option("gh_postcount_barcolour", "#ff6600");
		add_option("gh_postcount_linecolour", "#001f7a");
		add_option("gh_postcount_textcolour", "#001f7a");
		add_option("gh_postcount_bordercolour", "#ffcc00");
	} ?>
	<div class=wrap>
		<script type="text/javascript">
			function addOption() {
				document.forms['options'].block_items.options[document.forms['options'].block_items.options.length] =
					new Option('Posts for the last ' + 
						document.forms['options'].time_length.value + ' ' +
						document.forms['options'].time_unit.options[document.forms['options'].time_unit.selectedIndex].text  +  ' (per ' +
						document.forms['options'].per.options[document.forms['options'].per.selectedIndex].text + ')',
						document.forms['options'].time_length.value + '|' +
						document.forms['options'].time_unit.value + '|' +
						document.forms['options'].per.value);
			}

			function deleteOption() {
				document.forms['options'].block_items.options[document.forms['options'].block_items.options.selectedIndex] = null;
			}

			function fill_items() {
				document.forms['options'].items.value = "";
				for (i = 0; i < document.forms['options'].block_items.options.length; i++) {
					document.forms['options'].items.value += document.forms['options'].block_items.options[i].value + ";";
				}
				return true;
			}
		</script>
		<form method="post" name="options">
			<h2>Post Count/Frequency</h2>
			<fieldset>
				<legend>Exclude categories:</legend>
				<select multiple="multiple" size="10" name="categories[]">
					<option value="-1" SELECTED>None</option>
					<?php wp_dropdown_cats(); ?>
				</select>
			</fieldset>
			<fieldset>
				<legend>Block Config:</legend>
				<input type="hidden" name="items" value="" />
				<select size="5" name="block_items">
				<?php
					$blockConfig = get_option('gh_postcount_blockconfig');
					foreach (explode(";", preg_replace("/;$/", "", $blockConfig)) as $item) {
						$itemArray = explode("|", $item);
						switch ($itemArray[1]) {
						case "day":
							$unit = "Days";
							break;
						case "week":
							$unit = "Weeks";
							break;
						case "month":
							$unit = "Months";
							break;
						case "year":
							$unit = "Years";
							break;
						}
						switch ($itemArray[2]) {
						case "day":
							$per = "Day";
							break;
						case "week":
							$per = "Week";
							break;
						case "month":
							$per = "Month";
							break;
						case "quarter":
							$per = "Quarter";
							break;
						case "year":
							$per = "Year";
							break;
						}
						echo "<option value=\"$item\">Posts for the last $itemArray[0] $unit (per $per)</option>";
					}
				?>
				</select>
				<input type="button" name="delete" value="Remove" onClick="deleteOption();" />
				<br />
				Posts for the last
				<input name="time_length" type="text"></input>
				<select name="time_unit">
					<option value="day">Days</option>
					<option value="week">Weeks</option>
					<option value="month" selected>Months</option>
					<option value="year">Years</option>
				</select>
				per
				<select name="per">
					<option value="day">Day</option>
					<option value="week" selected>Week</option>
					<option value="month">Month</option>
					<option value="quarter">Quarter</option>
					<option value="year">Year</option>
				</select>
				<input type="button" name="add" value="Add" onClick="addOption();"/>

				<br />
				Block Title: <input name="block_title" type="text" value="<?php echo get_option('gh_postcount_block_title');?>" />
				<br />
				Block Link:  <input name="block_link" type="text" value="<?php echo get_option('gh_postcount_block_link');?>" />
			</fieldset>
			<fieldset>
				<legend>Image Colours:</legend>
				Background Colour:
				<input type="text" name="backgroundcolour" value="<?php echo get_option('gh_postcount_backgroundcolour'); ?>" /><br />
				Bar Colour:
				<input type="text" name="barcolour" value="<?php echo get_option('gh_postcount_barcolour'); ?>" /><br />
				Line Colour:
				<input type="text" name="linecolour" value="<?php echo get_option('gh_postcount_linecolour'); ?>" /><br />
				Text Colour:
				<input type="text" name="textcolour" value="<?php echo get_option('gh_postcount_textcolour'); ?>" /><br />
				Border Colour:
				<input type="text" name="bordercolour" value="<?php echo get_option('gh_postcount_bordercolour'); ?>" />
			<fieldset>
				<legend>Performance:</legend>
				<input type="checkbox" name="buildindex" CHECKED> Add DB Index</input>
			</fieldset>
			<div class="submit">
				<input type="submit" name="info_update" value="Update options &raquo;" onClick="return fill_items();" />
			</div>
		</form>
	</div><?php
}

add_action('admin_menu', 'gh_postcount_options_hook');

?>
