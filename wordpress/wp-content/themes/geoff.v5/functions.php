<?php
function get_page_number() {
  if ( get_query_var('paged') ) {
    print ' | Page ' . get_query_var('paged');
  }
} // end get_page_number

function gkh_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
        <?php echo get_avatar($comment,$size='64',$default='<path_to_url>' ); ?>
        <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
        <em><?php _e('Your comment is awaiting moderation.') ?></em>
        <br />
      <?php endif; ?>

      <p class="date"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link('(Edit)','  ','') ?></p>

      <div class="comment-text">
        <?php comment_text() ?>
      </div>
    </div>
    <div style="clear: left;"></div>
<?php
}


require_once('simplepie.inc');

function gkh_get_twitter() {
  $feed = new SimplePie();
  $feed->set_feed_url('http://twitter.com/statuses/user_timeline/14185623.rss');
  $feed->init();

  $result = array();

  foreach($feed->get_items() as $item) {
    if (preg_match('/^[^:]*: @/', $item->get_content())) { continue; }
    $result[] = array(
      'class' => 'twitter',
      'content' => preg_replace(array('/^[^:]*: /', '#(http://([^\s\.]+\.)+[^\s\.\)]+)#'), array('', '<a href="\1">\1</a>'), $item->get_content() . '.'),
      'date' => $item->get_date("Y-m-d H:i:s")
    );
  }

  return $result;
}

function gkh_get_gkhphoto() {
  $feed = new SimplePie();
  $feed->set_feed_url('http://gkhphoto.com/feed/');
  $feed->init();

  $result = array();

  foreach($feed->get_items() as $item) {
    $result[] = array(
      'class' => 'gkhphoto',
      'content' => $item->get_content(),
      'date' => $item->get_date("Y-m-d H:i:s"),
      'title' => $item->get_title(),
      'link' => $item->get_link()
    );
  }

  return $result;
}

function gkh_get_greader() {
  $feed = new SimplePie();
  $feed->set_feed_url('http://www.google.com/reader/public/atom/user%2F01568910581268426924%2Fstate%2Fcom.google%2Fbroadcast');
  $feed->set_image_handler(get_bloginfo('template_url') . '/' . 'image.php');
  $feed->init();

  $result = array();

  foreach($feed->get_items() as $item) {
    $source = $item->get_source();
    $sourcetags = $source->get_source_tags(SIMPLEPIE_NAMESPACE_ATOM_10 , 'title');
    $sourcetitle = $sourcetags[0]['data'];
    $sourcetags = $source->get_source_tags(SIMPLEPIE_NAMESPACE_ATOM_10 , 'link');
    $sourcelink = $sourcetags[0]['attribs']['']['href'];
    $result[] = array(
      'class' => 'greader',
      'content' => preg_replace('/(height|width)="?[0-9]+"?/i', '', $item->get_description()),
      'date' => $item->get_date("Y-m-d H:i:s"),
      'title' => $item->get_title(),
      'link' => $item->get_link(),
      'feedhome' => $sourcelink,
      'feedtitle' => $sourcetitle
    );
  }

  return $result;
}

function gkh_get_feeds() {
  $result = array_merge(gkh_get_twitter(), gkh_get_gkhphoto(), gkh_get_greader());
  usort($result, "gkh_feeditem_cmp");
  return $result;
}

function gkh_feeditem_cmp($a, $b) {
  return strcmp($b['date'], $a['date']);
}

function gkh_widgets_init() {
  register_sidebar( array (
    'name' => 'Right Sidebar',
    'id' => 'primary_widget_area',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h4>',
    'after_title' => '</h4>',
  ) );
}

add_action( 'init', 'gkh_widgets_init' );


/* Flickr Integration */

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

add_filter('get_the_excerpt', 'filter_flickr_tags', 0);
add_filter('the_content', 'filter_flickr_tags');

?>
