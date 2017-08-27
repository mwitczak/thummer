<?php
use Thummer\Configuration;
use Thummer\ThumbnailGenerator\GDThumbnailGenerator;
use Thummer\Thummer;

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configuration = new Configuration();
$configuration->setJpegImageQuality(0);

$thumbnailGenerator = new GDThumbnailGenerator($configuration);

$thummer = new Thummer($configuration, $thumbnailGenerator);
$response = $thummer->makeThumbnail($_SERVER['REQUEST_URI']);
$response->send();
