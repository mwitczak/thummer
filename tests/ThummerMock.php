<?php

require_once('src/Thummer.php');

class ThummerMock extends Thummer
{
    public $isFile = true;
    public $imageSize = [
        500,
        500,
        2,
        'width="500" height="500"',
        "bits" => 8,
        "channels" => 3,
        "mime" => "image/jpeg"
    ];

    protected function isFile($srcPath): bool
    {
        return $this->isFile;
    }

    protected function getImageSize($srcPath)
    {
        return $this->imageSize;
    }
}