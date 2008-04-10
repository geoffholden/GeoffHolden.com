<?php 
if ($_GET['content']) {
	header('Location: http://www.geoffholden.com/',TRUE,301);
	return;
}
/* Short and sweet */
define('WP_USE_THEMES', true);
require('./wordpress/wp-blog-header.php');
?>
