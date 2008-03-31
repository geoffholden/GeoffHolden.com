<?php

define('WP_USE_THEMES', false);
require('../../../../wp-blog-header.php');

function get_avg($date) {
	global $wpdb;
	$end_month = strftime("%G%V", strtotime($date));
	$start_month = strftime("%G%V", strtotime("$date-3 weeks"));

	$categories = "WHERE";
	$catOption = get_option('gh_postcount_exclcats');
	if ($catOption != "-1,") {
		$categories = "INNER JOIN $wpdb->post2cat ON $wpdb->posts.ID = $wpdb->post2cat.post_id WHERE category_id NOT IN ($catOption) AND";
	}
	$result = $wpdb->get_var("select COUNT(*)/4 from $wpdb->posts $categories post_status='publish' and YEARWEEK(post_date, 3) >= $start_month and YEARWEEK(post_date, 3) <= $end_month");
	
	return $result;
}

function get_tot($date) {
	global $wpdb;
	$end_month = strftime("%G%V", strtotime($date));

	$categories = "WHERE";
	$catOption = get_option('gh_postcount_exclcats');
	if ($catOption != "-1,") {
		$categories = "INNER JOIN $wpdb->post2cat ON $wpdb->posts.ID = $wpdb->post2cat.post_id WHERE category_id NOT IN ($catOption) AND";
	}
	$result = $wpdb->get_var("select COUNT(*) from $wpdb->posts $categories post_status='publish' and YEARWEEK(post_date, 3) = $end_month");
	
	return $result;
}

$cacheDir = dirname(__FILE__) . '/cache';
@mkdir($cacheDir);
if (!is_dir($cacheDir)) {
	echo "Please create directory \"" . $cacheDir . "\" and make sure it's writable by the web server.";
	exit;
}
$post_time = strtotime($wpdb->get_var("select max(post_date) from $wpdb->posts where post_status='publish' and post_date <= NOW()"));
$cacheFile = $cacheDir . '/cache';
if (@filemtime($cacheFile) < $post_time) {
	$backgroundColour = get_option('gh_postcount_backgroundcolour');
	$barColour        = get_option('gh_postcount_barcolour');
	$lineColour       = get_option('gh_postcount_linecolour');
	$textColour       = get_option('gh_postcount_textcolour');
	$borderColour     = get_option('gh_postcount_bordercolour');

	$avg   = Array();
	$count = Array();
	$dates = Array();
	for($i = 51; $i >= 0; $i--) {
		$date = "now-$i weeks";
		$dates[] = strftime("%G-%V", strtotime($date));
		$avg[] = get_avg($date);
		$count[] = get_tot($date);
	}
	
	$php_vers = substr(phpversion(), 0, 1);
	
	if ($php_vers >= 5) {
		include ("jpgraph/2/jpgraph.php");
		include ("jpgraph/2/jpgraph_line.php");
		include ("jpgraph/2/jpgraph_bar.php");
	} else {
		include ("jpgraph/1/jpgraph.php");
		include ("jpgraph/1/jpgraph_line.php");
		include ("jpgraph/1/jpgraph_bar.php");
	}
	
	$graph = new Graph(600,400);
	$graph->SetScale("textlin");
	$graph->img->SetMargin(40,20,30,60);
	$graph->title->Set("Posting Frequency");
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->title->SetColor($textColour);
	$graph->SetFrame(true, $borderColour);
	$graph->SetMarginColor($backgroundColour);
	$graph->legend->SetFillColor($backgroundColour);
	$graph->legend->setColor($textColour);
	$graph->legend->setShadow(false);
	
	// Setup axis titles
	$graph->xaxis->title->Set("Week");
	$graph->xaxis->SetTickLabels($dates);
	$graph->xaxis->SetLabelAngle(90);
	$graph->xaxis->SetTextLabelInterval(4);
	$graph->xaxis->SetColor($textColour);
	$graph->xaxis->title->SetColor($textColour);
	
	$graph->yaxis->title->Set("Posts");
	$graph->yaxis->SetColor($textColour);
	$graph->yaxis->title->SetColor($textColour);
	
	// Create the linear plot
	$lineplot=new LinePlot($avg);
	$lineplot->setColor($lineColour);
	$lineplot->setLegend('Posts/Week (monthly avg)');
	$lineplot->setBarCenter();
	
	// Create line plot
	$barplot=new barPlot($count);
	$barplot->setWidth(1.0);
	$barplot->setLegend('Posts/Week');
	$barplot->setFillColor($barColour);
	$barplot->setColor($barColour);
	
	// Add the plots to the graph
	$graph->Add($barplot);
	$graph->Add($lineplot);
	
	$graph->Stroke($cacheFile);
}

header("Content-type: image/png");
header("Content-Length: " . filesize($cacheFile));
$image = fopen($cacheFile, "r");
fpassthru($image);
fclose($image);

?>
