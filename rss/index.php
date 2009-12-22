<?php
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss
  version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<language>en-us</language> 

<?php
$feedurls = array();
switch ($_GET['i']) {
	case 'all':
		$feedurls = array(
			'http://www.geoffholden.com/feed/',
			'http://gkhphoto.com/feed/',
			'http://www.google.com/reader/public/atom/user%2F01568910581268426924%2Fstate%2Fcom.google%2Fbroadcast',
			'http://twitter.com/statuses/user_timeline/14185623.rss'
		);
		break;
	case 'abt':
		$feedurls = array(
			'http://www.geoffholden.com/feed/',
			'http://gkhphoto.com/feed/',
			'http://www.google.com/reader/public/atom/user%2F01568910581268426924%2Fstate%2Fcom.google%2Fbroadcast',
		);
		break;
	case 'blogs':
	default:
		$feedurls = array(
			'http://www.geoffholden.com/feed/',
			'http://gkhphoto.com/feed/',
		);
} 

require_once('simplepie.inc');

$feed = new SimplePie();


$feed->set_feed_url($feedurls);

$feed->set_cache_duration (600);
$success = $feed->init();
$feed->handle_content_type();
?>

<?php if ($success): ?>

	<?php foreach($feed->get_items() as $item): ?>
	<item>
		<title><?echo $item->get_title(); ?></title>
		<link><? echo $item->get_permalink(); ?></link>
		<description><![CDATA[<? echo $item->get_description(); ?>]]></description>
		<content:encoded><![CDATA[<? echo $item->get_content(); ?>]]></content:encoded>
	</item>
	<?php endforeach; ?>
<?php endif; ?>

</channel>
</rss>

