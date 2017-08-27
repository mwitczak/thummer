<?php

require_once('src/thummer.php');
require_once('src/Configuration.php');

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
    public function testThumbnailOutOfBoundsFails()
    {
        $configuration = new Configuration();
        $thummer = new Thummer($configuration);
        $thummer->makeThumbnail('thumb/1000x1000/test.jpg');
    }
}