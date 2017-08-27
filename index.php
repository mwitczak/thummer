<?php
require_once('src/Thummer.php');
require_once('src/GDThumbnailGenerator.php');
require_once('src/Configuration.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configuration = new Configuration();
$configuration->setJpegImageQuality(0);

$thumbnailGenerator = new GDThumbnailGenerator($configuration);

try {
    $thummer = new Thummer($configuration, $thumbnailGenerator);
    $thummer->makeThumbnail($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    header('HTTP/1.0 404 Not Found');
}
