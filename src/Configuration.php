<?php

namespace Thummer;

class Configuration {
    protected const MIN_LENGTH = 10;
    protected const MAX_LENGTH = 1200;
    protected const BASE_SOURCE_DIR = 'image';
    protected const BASE_TARGET_DIR = 'imagethumb';
    protected const REQUEST_PREFIX_URL_PATH = 'imagethumb';

    protected const SHARPEN_THUMBNAIL = true;
    protected const JPEG_IMAGE_QUALITY = 75;
    protected const PNG_SAVE_TRANSPARENCY = false;

    protected const HTTP_THUMBNAIL_RESPONSE = false;

    protected const FAIL_IMAGE_URL_PATH = '/content/thumbfail.jpg';
    protected const FAIL_IMAGE_LOG = false;

    protected $minLength = self::MIN_LENGTH;
    protected $maxLength = self::MAX_LENGTH;
    protected $baseSourceDir = self::BASE_SOURCE_DIR;
    protected $baseTargetDir = self::BASE_TARGET_DIR;
    protected $requestPrefixUrlPath = self::REQUEST_PREFIX_URL_PATH;
    protected $sharpenThumbnail = self::SHARPEN_THUMBNAIL;
    protected $jpegImageQuality = self::JPEG_IMAGE_QUALITY;
    protected $pngSaveTransparency = self::PNG_SAVE_TRANSPARENCY;
    protected $httpThumbnailResponse = self::HTTP_THUMBNAIL_RESPONSE;
    protected $failImageUrlPath = self::FAIL_IMAGE_URL_PATH;
    protected $failImageLog = self::FAIL_IMAGE_LOG;

    /**
     * @return int
     */
    public function getMinLength(): int
    {
        return $this->minLength;
    }

    /**
     * @param int $minLength
     */
    public function setMinLength(int $minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * @return int
     */
    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @return string
     */
    public function getBaseSourceDir(): string
    {
        return $this->baseSourceDir;
    }

    /**
     * @param string $baseSourceDir
     */
    public function setBaseSourceDir(string $baseSourceDir)
    {
        $this->baseSourceDir = $baseSourceDir;
    }

    /**
     * @return string
     */
    public function getBaseTargetDir(): string
    {
        return $this->baseTargetDir;
    }

    /**
     * @param string $baseTargetDir
     */
    public function setBaseTargetDir(string $baseTargetDir)
    {
        $this->baseTargetDir = $baseTargetDir;
    }

    /**
     * @return string
     */
    public function getRequestPrefixUrlPath(): string
    {
        return $this->requestPrefixUrlPath;
    }

    /**
     * @param string $requestPrefixUrlPath
     */
    public function setRequestPrefixUrlPath(string $requestPrefixUrlPath)
    {
        $this->requestPrefixUrlPath = $requestPrefixUrlPath;
    }

    /**
     * @return bool
     */
    public function isSharpenThumbnail(): bool
    {
        return $this->sharpenThumbnail;
    }

    /**
     * @param bool $sharpenThumbnail
     */
    public function setSharpenThumbnail(bool $sharpenThumbnail)
    {
        $this->sharpenThumbnail = $sharpenThumbnail;
    }

    /**
     * @return int
     */
    public function getJpegImageQuality(): int
    {
        return $this->jpegImageQuality;
    }

    /**
     * @param int $jpegImageQuality
     */
    public function setJpegImageQuality(int $jpegImageQuality)
    {
        $this->jpegImageQuality = $jpegImageQuality;
    }

    /**
     * @return bool
     */
    public function isPngSaveTransparency(): bool
    {
        return $this->pngSaveTransparency;
    }

    /**
     * @param bool $pngSaveTransparency
     */
    public function setPngSaveTransparency(bool $pngSaveTransparency)
    {
        $this->pngSaveTransparency = $pngSaveTransparency;
    }

    /**
     * @return bool
     */
    public function isHttpThumbnailResponse(): bool
    {
        return $this->httpThumbnailResponse;
    }

    /**
     * @param bool $httpThumbnailResponse
     */
    public function setHttpThumbnailResponse(bool $httpThumbnailResponse)
    {
        $this->httpThumbnailResponse = $httpThumbnailResponse;
    }

    /**
     * @return string
     */
    public function getFailImageUrlPath(): string
    {
        return $this->failImageUrlPath;
    }

    /**
     * @param string $failImageUrlPath
     */
    public function setFailImageUrlPath(string $failImageUrlPath)
    {
        $this->failImageUrlPath = $failImageUrlPath;
    }

    /**
     * @return bool
     */
    public function isFailImageLog(): bool
    {
        return $this->failImageLog;
    }

    /**
     * @param bool $failImageLog
     */
    public function setFailImageLog(bool $failImageLog)
    {
        $this->failImageLog = $failImageLog;
    }
}