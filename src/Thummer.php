<?php

class Thummer
{
    /** @var Configuration */
    protected $configuration;
    /** @var ThumbnailGeneratorInterface */
    protected $thumbnailGenerator;

    public function __construct(
        Configuration $configuration,
        ThumbnailGeneratorInterface $thumbnailGenerator
    ) {
        $this->configuration = $configuration;
        $this->thumbnailGenerator = $thumbnailGenerator;
    }

    public function makeThumbnail(string $filePath)
    {
        // get requested thumbnail from URI
        $requestURI = trim($filePath);

        /*try {*/
            $requestedThumb = $this->getRequestedThumb($requestURI);
            // fetch source image details
        /*} catch (Exception $e) {
            // unable to determine requested thumbnail from URL
            $this->send404header();
            return ;
        }*/

        /*if ($sourceImageDetail === false) {
            // unable to locate source image
            $this->send404header();
            return;
        }*/

        /*if ($sourceImageDetail === -1) {
            // source image invalid - redirect to fail image
            $this->redirectURL($this->configuration->getFailImageUrlPath());
            $this->logFailImage($requestedThumb[2]);

            return;
        }*/

        //image file exists?
        $srcPath = $this->configuration->getBaseSourceDir() . $requestedThumb[2];

        if (!$this->isFile($srcPath)) {
            throw new Exception('File not found');
        }

        // source image all good, create thumbnail on disk
        $thumbData = $this->thumbnailGenerator->generateThumbnail(
            $srcPath,
            $requestedThumb[0],
            $requestedThumb[1]
        );

        $targetImagePathFull = $thumbData['path'];

        if ($this->configuration->isHttpThumbnailResponse()) {
            // output the generated thumbnail binary to the client
            if (is_file($targetImagePathFull)) {
                header('Content-Length: ' . filesize($targetImagePathFull));
                header('Content-Type: ' . $thumbData['fileType']);
                readfile($targetImagePathFull);
            }

        } else {
            // redirect back to initial URL to display generated thumbnail image
            $this->redirectURL($requestURI);
        }
    }

    private function getRequestedThumb($requestPath): array
    {
        // check for URL prefix - remove if found
        $requestPath = (strpos($requestPath, $this->configuration->getRequestPrefixUrlPath()) === 0)
            ? substr($requestPath, strlen($this->configuration->getRequestPrefixUrlPath()))
            : $requestPath;

        // extract target thumbnail dimensions & source image
        if (!preg_match(
            '{^.+/([0-9]{1,4})x([0-9]{1,4})(/.+)$}',
            $requestPath, $requestMatch
        )
        ) {
            throw new Exception('Unprocessable thumbnail path');
        }

        // ensure width/height are within allowed bounds
        $width = intval($requestMatch[1]);
        $height = intval($requestMatch[2]);
        if (($width < $this->configuration->getMinLength()) || ($width > $this->configuration->getMaxLength())) {
            throw new OutOfBoundsException();
        }
        if (($height < $this->configuration->getMinLength()) || ($height > $this->configuration->getMaxLength())) {
            throw new OutOfBoundsException();
        }

        return [
            $width,
            $height,
            // remove parent path components if request is trying to be sneaky
            str_replace(
                array('../', './'), '',
                $requestMatch[3]
            )
        ];
    }

    private function logFailImage($source)
    {
        if ($this->configuration->isFailImageLog() === false) return;

        // write the requested file path to the error log
        $fp = fopen($this->configuration->isFailImageLog(), 'a');
        fwrite($fp, $this->configuration->getBaseSourceDir() . $source . "\n");
        fclose($fp);
    }

    /**
     * @param $srcPath
     * @return bool
     */
    protected function isFile($srcPath): bool
    {
        return is_file($srcPath);
    }

    private function redirectURL($targetPath)
    {
        header(
            sprintf(
                'Location: http%s://%s%s',
                ((isset($_SERVER['SERVER_PORT'])) && ($_SERVER['SERVER_PORT'] == 443)) ? 's' : '',
                $_SERVER['HTTP_HOST'],
                $targetPath
            ),
            true, 301
        );
    }
}