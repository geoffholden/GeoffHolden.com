<?php
$gkh_flickr_size = 'Medium';
$gkh_flickr_max_size = 'Original';

function gkh_setFlickrDefaultSize($size) {
	global $gkh_flickr_size;

	$gkh_flickr_size = $size;
}

function gkh_setFlickrMaxSize($size) {
	global $gkh_flickr_max_size;

	$gkh_flickr_max_size = $size;
}

function gkh_capFlickrSize($size) {
	global $gkh_flickr_max_size;

	$gkh_flickr_sizes = array(
		'Square' => 0,
		'Thumbnail' => 1,
		'Small' => 2,
		'Medium' => 3,
		'Large' => 4,
		'Original' => 5
	);

	if ($gkh_flickr_sizes[$size] > $gkh_flickr_sizes[$gkh_flickr_max_size]) {
		return $gkh_flickr_max_size;
	} else {
		return $size;
	}
}

function gkh_getFlickrPhotoUrl($photo, $size = '') {
	global $gkh_flickr_size;

	if ($size == '') {
		$size = $gkh_flickr_size;
	}

	$size = gkh_capFlickrSize($size);

	$params = array(
		'api_key'	=> '70395e1e61f4050bae85f7bf985d7d27',
		'method'	=> 'flickr.photos.getSizes',
		'photo_id'	=> $photo,
		'format'	=> 'php_serial',
	);
	$encoded_params = array();

	foreach ($params as $k => $v){
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}

	$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
	$rsp = file_get_contents($url);
	$rsp_obj = unserialize($rsp);

	if ($rsp_obj['stat'] == 'ok'){
		foreach ($rsp_obj["sizes"]["size"] as $x) {
			if ($x["label"] == $size) {
				return $x["source"];
			}
		}
	} else {
		return "";
	}
}
function gkh_getFlickrPhotoTag($photo, $size = '') {
	global $gkh_flickr_size;

	if ($size == '') {
		$size = $gkh_flickr_size;
	}

	$size = gkh_capFlickrSize($size);

	$params = array(
		'api_key'	=> '70395e1e61f4050bae85f7bf985d7d27',
		'method'	=> 'flickr.photos.getInfo',
		'photo_id'	=> $photo,
		'format'	=> 'php_serial',
	);
	$encoded_params = array();

	foreach ($params as $k => $v){
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}

	$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
	$rsp = file_get_contents($url);
	$rsp_obj = unserialize($rsp);

	if ($rsp_obj['stat'] == 'ok'){
		$title = $rsp_obj['photo']['title']['_content'];
		$tag = '<a href="http://gkhphoto.com/gallery/photo/' . $photo . '/';
		$tag = $tag . urlencode($title) . '.html"><img src="';
		$tag = $tag . gkh_getFlickrPhotoUrl($photo, $size) . '" alt="';
		$tag = $tag . $title . '" ';
		if ($size == 'Original') {
			$tag = $tag . 'onload="this.parentNode.parentNode.scrollLeft = (this.width - this.parentNode.parentNode.clientWidth) / 2;" ';
		}
		$tag = $tag . '/></a>';
		return $tag;
	} else {
		return "Error fetching photo id $photo.";
	}
}

function filter_flickr_tags($content) {
	$content = preg_replace("/<!--flickr:([0-9]+)(,([A-Za-z]+))?-->/e", "gkh_getFlickrPhotoTag('\\1', '\\3')", $content);
	return $content;
}

function improved_trim_excerpt($text) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = strip_tags($text, '<p><a><img><div>');
		$excerpt_length = 80;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'improved_trim_excerpt');

add_filter('get_the_excerpt', 'filter_flickr_tags', 0);
add_filter('the_content', 'filter_flickr_tags');

if (function_exists('register_sidebar')) {
	register_sidebar();
}

/* Recent Posts Widget */

function gkh_widget_recent_entries($args) {
	if ( $output = wp_cache_get('gkh_widget_recent_entries') )
		return print($output);

	ob_start();
	extract($args);
	$options = get_option('gkh_widget_recent_entries');
	$title = empty($options['title']) ? __('Recent Posts (Offset)') : $options['title'];
	if ( !$number = (int) $options['number'] )
		$number = 10;
	else if ( $number < 1 )
		$number = 1;
	else if ( $number > 15 )
		$number = 15;
	if ( !$offset = (int) $options['offset'] )
		$offset = 0;

	$r = new WP_Query("showposts=$number&what_to_show=posts&nopaging=0&post_status=publish&offset=$offset");
	if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<li><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li>
			<?php endwhile; ?>
			</ul>
		<?php echo $after_widget; ?>
<?php
	endif;
	wp_cache_add('gkh_widget_recent_entries', ob_get_flush());
}

function gkh_flush_widget_recent_entries() {
	wp_cache_delete('gkh_widget_recent_entries');
}

add_action('save_post', 'gkh_flush_widget_recent_entries');
add_action('deleted_post', 'gkh_flush_widget_recent_entries');

function gkh_widget_recent_entries_control() {
	$options = $newoptions = get_option('gkh_widget_recent_entries');
	if ( $_POST["gkh_recent-entries-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["gkh_recent-entries-title"]));
		$newoptions['number'] = (int) $_POST["gkh_recent-entries-number"];
		$newoptions['offset'] = (int) $_POST["gkh_recent-entries-offset"];
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('gkh_widget_recent_entries', $options);
		gkh_flush_widget_recent_entries();
	}
	$title = attribute_escape($options['title']);
	if ( !$number = (int) $options['number'] )
		$number = 5;
	if ( !$offset = (int) $options['offset'] )
		$offset = 0;
?>
			<p><label for="gkh_recent-entries-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="gkh_recent-entries-title" name="gkh_recent-entries-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="gkh_recent-entries-number"><?php _e('Number of posts to show:'); ?> <input style="width: 25px; text-align: center;" id="gkh_recent-entries-number" name="gkh_recent-entries-number" type="text" value="<?php echo $number; ?>" /></label> <?php _e('(at most 15)'); ?></p>
			<p><label for="gkh_recent-entries-offset"><?php _e('Offset of posts to show:'); ?> <input style="width: 25px; text-align: center;" id="gkh_recent-entries-offset" name="gkh_recent-entries-offset" type="text" value="<?php echo $offset; ?>" /></label></p>
			<input type="hidden" id="gkh_recent-entries-submit" name="gkh_recent-entries-submit" value="1" />
<?php
}

	register_sidebar_widget('Recent Posts (Offset)', 'gkh_widget_recent_entries');
	register_widget_control('Recent Posts (Offset)', 'gkh_widget_recent_entries_control');

?>
