<?php
namespace Thummer\Tests;

use PHPUnit\Framework\TestCase;
use Thummer\Configuration;
use Thummer\ThumbnailGenerator\GDThumbnailGenerator;

class GDThumbnailGeneratorTest extends TestCase
{
    public function testObjectCreated()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);

        $this->assertInstanceOf(GDThumbnailGenerator::class, $thumbnailGenerator);
    }

    public function testGenerateThumbnailSuccessfully()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);

        $thumbData = $thumbnailGenerator->generateThumbnail('image/apple.jpg', 200, 100);

        $this->assertEquals(
            ['path' => 'imagethumb/200x100/apple.jpg', 'fileType' => 2],
            $thumbData
        );

        $thumbDimensions = getimagesize($thumbData['path']);

        $this->assertEquals(200, $thumbDimensions[0]);
        $this->assertEquals(100, $thumbDimensions[1]);

        unlink($thumbData['path']);
        rmdir(dirname($thumbData['path']));
    }
}