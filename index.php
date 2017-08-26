<?php
require_once('thummer.php');
require_once('Configuration.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$configuration = new Configuration();
$configuration->setJpegImageQuality(0);

new Thummer($configuration);
