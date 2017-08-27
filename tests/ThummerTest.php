<?php

use Thummer\Configuration;
use Thummer\ThumbnailGenerator\GDThumbnailGenerator;
use Thummer\Thummer;

require_once('ThummerMock.php');

class ThummerTest extends \PHPUnit\Framework\TestCase
{
    public function testObjectCreated()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummer = new Thummer($configuration, $thumbnailGenerator);

        $this->assertInstanceOf(Thummer::class, $thummer);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnprocessableThumbnailPathFails()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummer = new Thummer($configuration, $thumbnailGenerator);
        $thummer->makeThumbnail('abcde');
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThumbnailWidthOutOfBoundsFails()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummer = new Thummer($configuration, $thumbnailGenerator);
        $thummer->makeThumbnail('thumb/1000x100/test.jpg');
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThumbnailHeightOutOfBoundsFails()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummer = new Thummer($configuration, $thumbnailGenerator);
        $thummer->makeThumbnail('thumb/100x1000/test.jpg');
    }

    /**
     * @expectedException \Thummer\Exceptions\FileNotFoundException
     */
    public function testFileDoesNotExist()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummerMock = new ThummerMock($configuration, $thumbnailGenerator);
        $thummerMock->isFile = false;
        $thummerMock->makeThumbnail('thumb/100x100/test.jpg');
    }

    /**
     * @expectedException \Thummer\Exceptions\InvalidImageException
     */
    public function testImageTypeNotValid()
    {
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummerMock = new ThummerMock($configuration, $thumbnailGenerator);
        $thummerMock->isFile = true;
        $thummerMock->imageSize[2] = IMAGETYPE_BMP;
        $thummerMock->makeThumbnail('thumb/100x100/test.bmp');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage File not found
     */
    public function testFileExist()
    {
        $this->markTestIncomplete();
        $configuration = new Configuration();
        $thumbnailGenerator = new GDThumbnailGenerator($configuration);
        $thummerMock = new ThummerMock($configuration, $thumbnailGenerator);
        $thummerMock->isFile = true;
        $thummerMock->makeThumbnail('thumb/100x100/apple.jpg');
    }
}