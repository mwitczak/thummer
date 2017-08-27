<?php
require_once('thummer.php');
require_once('Configuration.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configuration = new Configuration();
$configuration->setJpegImageQuality(0);

try {
    $thummer = new Thummer($configuration);
    $thummer->makeThumbnail($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    header('HTTP/1.0 404 Not Found');
}
