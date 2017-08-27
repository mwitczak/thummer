<?php

require_once('src/Thummer.php');
require_once('src/Configuration.php');
require_once('ThummerMock.php');

class ThummerTest extends \PHPUnit\Framework\TestCase
{
    public function testObjectCreated()
    {
        $configuration = new Configuration();
        $thummer = new Thummer($configuration);

        $this->assertInstanceOf(Thummer::class, $thummer);
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Unprocessable thumbnail path
     */
    public function testUnprocessableThumbnailPathFails()
    {
        $configuration = new Configuration();
        $thummer = new Thummer($configuration);
        $thummer->makeThumbnail('abcde');
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThumbnailWidthOutOfBoundsFails()
    {
        $configuration = new Configuration();
        $thummer = new Thummer($configuration);
        $thummer->makeThumbnail('thumb/1000x100/test.jpg');
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThumbnailHeightOutOfBoundsFails()
    {
        $configuration = new Configuration();
        $thummer = new Thummer($configuration);
        $thummer->makeThumbnail('thumb/100x1000/test.jpg');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage File not found
     */
    public function testFileDoesNotExist()
    {
        $configuration = new Configuration();
        $thummerMock = new ThummerMock($configuration);
        $thummerMock->isFile = false;
        $thummerMock->makeThumbnail('thumb/100x100/test.jpg');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Not valid image
     */
    public function testImageTypeNotValid()
    {
        $configuration = new Configuration();
        $thummerMock = new ThummerMock($configuration);
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
        $thummerMock = new ThummerMock($configuration);
        $thummerMock->isFile = true;
        $thummerMock->makeThumbnail('thumb/100x100/apple.jpg');
    }
}