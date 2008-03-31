<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.00">
<channel>
<language>en-us</language> 

<?php
require_once('simplepie.inc');

$feed = new SimplePie();


$feed->set_feed_url(array(
	'http://www.geoffholden.com/feed/',
	'http://gkhphoto.com/feed/'
));

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

