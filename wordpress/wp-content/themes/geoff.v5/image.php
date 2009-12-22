<?php
require_once('simplepie.inc');
$cache_dir='../../../../cache';

$identifier = $_GET['i'];

$filename = $cache_dir . '/' . $identifier;
if (!is_file($filename)) {
  $cache = call_user_func(array('SimplePie_Cache', 'create'), $cache_dir, $identifier, 'spi');
  if ($file = $cache->load()) {
    $image = imagecreatefromstring($file['body']);
    if (imagesx($image) > 500) {
      $oldimage = $image;
      $newheight = (500.0/imagesx($oldimage)) * imagesy($oldimage);
      $image = imagecreatetruecolor(500, $newheight);
      imagecopyresampled($image, $oldimage, 0, 0, 0, 0, 500, $newheight, imagesx($oldimage), imagesy($oldimage));
      imagedestroy($oldimage);

      if (function_exists('imageconvolution')) {
        $sharpenMatrix = array(
           array(-1,-1,-1),
           array(-1,16,-1),
           array(-1,-1,-1));
        imageconvolution($image, $sharpenMatrix, 8, 0);
      }
    } elseif (imagesx($image) == 1 && imagesy($image) == 1) {
      imagedestroy($image);
      $image = imagecreatetruecolor(1,1);
      imagefill($image, 0, 0, imagecolorallocate($image, 101, 117, 123));
    }
    imagejpeg($image, $filename);
    imagedestroy($image);
  }
}
header('Content-type: image/jpeg');
$file = fopen($filename, 'r');
fpassthru($file);
fclose($file);
?>
