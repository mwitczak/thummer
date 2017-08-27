<?php

namespace Thummer;

use Exception;
use InvalidArgumentException;
use OutOfBoundsException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Thummer\Exceptions\FileNotFoundException;
use Thummer\ThumbnailGenerator\AbstractThumbnailGenerator;

class Thummer
{
    /** @var Configuration */
    protected $configuration;
    /** @var AbstractThumbnailGenerator */
    protected $thumbnailGenerator;

    public function __construct(
        Configuration $configuration,
        AbstractThumbnailGenerator $thumbnailGenerator
    ) {
        $this->configuration = $configuration;
        $this->thumbnailGenerator = $thumbnailGenerator;
    }

    public function getThumbnailResponse(string $filePath): Response
    {
        try {
            $requestURI = trim($filePath);
            $thumbData = $this->makeThumbnail($requestURI);
        } catch (Exception $e) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        if ($this->configuration->isHttpThumbnailResponse()) {
            // output the generated thumbnail binary to the client
            if ($this->isFile($thumbData['path'])) {
                $response = new Response();
                $response->headers->add([
                    'Content-Length: ' => filesize($thumbData['path']),
                    'Content-Type: ' => $thumbData['fileType']
                ]);
                $response->setContent(file_get_contents($thumbData['path']));
                return $response;
            }
        }

        // redirect back to initial URL to display generated thumbnail image
        return new RedirectResponse($requestURI);
    }

    public function makeThumbnail(string $filePath): array
    {
        // get requested thumbnail from URI
        $requestedThumb = $this->getRequestedThumbFromURI($filePath);

        //image file exists?
        $srcPath = $this->configuration->getBaseSourceDir() . $requestedThumb['file'];
        if (!$this->isFile($srcPath)) {
            throw new FileNotFoundException();
        }

        // source image all good, create thumbnail on disk
        $thumbData = $this->thumbnailGenerator->generateThumbnail(
            $srcPath,
            $requestedThumb['width'],
            $requestedThumb['height']
        );

        return $thumbData;
    }

    private function getRequestedThumbFromURI($requestPath): array
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
            throw new InvalidArgumentException();
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
            "width" => $width,
            "height" => $height,
            // remove parent path components if request is trying to be sneaky
            "file" => str_replace(
                array('../', './'), '',
                $requestMatch[3]
            )
        ];
    }

    /**
     * @param $srcPath
     * @return bool
     */
    protected function isFile($srcPath): bool
    {
        return is_file($srcPath);
    }
}
