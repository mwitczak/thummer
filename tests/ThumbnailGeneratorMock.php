<?php
namespace Thummer\Tests;

use Thummer\Exceptions\InvalidImageException;
use Thummer\ThumbnailGenerator\AbstractThumbnailGenerator;

class ThumbnailGeneratorMock extends AbstractThumbnailGenerator
{
    public const NOT_EXISTING_FILE = 'image/not-existing-file.jpg';
    public const INVALID_IMAGE_TYPE_FILE = 'image/test.bmp';

    public function generateThumbnail(string $fileName, string $width, string $height): array
    {
        if ($fileName === self::NOT_EXISTING_FILE) {
            throw new InvalidImageException();
        }

        if ($fileName === self::INVALID_IMAGE_TYPE_FILE) {
            throw new InvalidImageException();
        }

        return [
            'path' => 'image/apple.jpg',
            'fileType' => IMAGETYPE_JPEG
        ];
    }
}