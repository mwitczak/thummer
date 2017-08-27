<?php

require_once('src/ThumbnailGeneratorInterface.php');

class GDThumbnailGenerator implements ThumbnailGeneratorInterface
{
    /** @var Configuration */
    protected $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function generateThumbnail(string $filePath, string $width, string $height): array
    {
        $fileName = basename($filePath);
        $sourceImageDetail = $this->getSourceImageDetail($filePath);

// calculate source image copy dimensions, fixed to target requested thumbnail aspect ratio
        list($sourceWidth, $sourceHeight, $sourceType) = $sourceImageDetail;

        $targetAspectRatio = $width / $height;
        $copyWidth = intval($sourceHeight * $targetAspectRatio);
        $copyHeight = $sourceHeight;

        if ($copyWidth > $sourceWidth) {
            // resize copy height fixed to target aspect
            $copyWidth = $sourceWidth;
            $copyHeight = intval($sourceWidth / $targetAspectRatio);
        }

        // create source/target GD images and resize/resample
        $imageSrc = $this->createSourceGDImage($sourceType, $filePath);
        $imageDst = imagecreatetruecolor($width, $height);

        if (($sourceType == IMAGETYPE_PNG) && $this->configuration->isPngSaveTransparency()) {
            // save PNG transparency in target thumbnail
            imagealphablending($imageDst, false);
            imagesavealpha($imageDst, true);
        }

        imagecopyresampled(
            $imageDst, $imageSrc, 0, 0,
            $this->calcThumbnailSourceCopyPoint($sourceWidth, $copyWidth), $this->calcThumbnailSourceCopyPoint($sourceHeight, $copyHeight),
            $width, $height,
            $copyWidth, $copyHeight
        );

        // sharpen thumbnail
        $this->sharpenThumbnail($imageDst);

        // construct full path to target image on disk and temp filename
        $targetImagePathFull = sprintf('%s/%dx%d/%s', $this->configuration->getBaseTargetDir(), $width, $height, $fileName);
        $targetImagePathFullTemp = $targetImagePathFull . '.' . md5(uniqid());

        // if target image path doesn't exist, create it now
        if (!is_dir(dirname($targetImagePathFull))) {
            // setting a custom error handler to catch a possible warning if the directory already exists
            // this will happen if two PHP requests decide to create the new directory at the same time (race condition)
            set_error_handler(array($this, 'errorWarningSink'));
            mkdir(dirname($targetImagePathFull), 0777, true);
            restore_error_handler();
        }

        // save image to temp file
        switch ($sourceType) {
            case IMAGETYPE_GIF:
                imagegif($imageDst, $targetImagePathFullTemp);
                break;

            case IMAGETYPE_JPEG:
                imagejpeg($imageDst, $targetImagePathFullTemp, $this->configuration->getJpegImageQuality());
                break;

            default: // PNG image
                imagepng($imageDst, $targetImagePathFullTemp);
        }

        // destroy GD image instances
        imagedestroy($imageSrc);
        imagedestroy($imageDst);

        // move temp image file into place, avoiding race conditions between thummer requests and set modify timestamp to source image
        rename($targetImagePathFullTemp, $targetImagePathFull);
        touch($targetImagePathFull, filemtime($this->configuration->getBaseSourceDir() . $filePath));

        return [
            'path' => $targetImagePathFull,
            'fileType' => $sourceImageDetail[2]
        ];
    }

    private function getSourceImageDetail($source): array
    {
        $detail = $this->getImageSize($source);

        if (
            ($detail !== false) &&
            (($detail[2] == IMAGETYPE_GIF) || ($detail[2] == IMAGETYPE_JPEG) || ($detail[2] == IMAGETYPE_PNG))
        ) {
            return [
                $detail[0],
                $detail[1], // width/height
                $detail[2], // image type
                $detail['mime'] // MIME type
            ];
        } else {
            throw new Exception('Not valid image');
        }
    }

    /**
     * @param $srcPath
     * @return array|bool
     */
    protected function getImageSize($srcPath)
    {
        // valid web image? return width/height/type
        set_error_handler(array($this, 'errorWarningSink'));
        $detail = getimagesize($srcPath);
        restore_error_handler();
        return $detail;
    }

    private function createSourceGDImage($type, $path)
    {
        if ($type == IMAGETYPE_GIF) return imagecreatefromgif($path);
        if ($type == IMAGETYPE_JPEG) return imagecreatefromjpeg($path);
        return imagecreatefrompng($path);
    }

    private function calcThumbnailSourceCopyPoint($sourceLength, $copyLength)
    {
        $point = intval(($sourceLength - $copyLength) / 2);
        return max($point, 0);
    }

    private function sharpenThumbnail($image)
    {
        if (!$this->configuration->isSharpenThumbnail()) return;

        // build matrix and divisor
        $matrix = array(
            array(-1.2, -1, -1.2),
            array(-1, 20, -1),
            array(-1.2, -1, -1.2)
        );

        // apply to image
        imageconvolution(
            $image, $matrix,
            11.2, 0 // note: array_sum(array_map('array_sum',$matrix)) = 11.2;
        );
    }

    private function errorWarningSink()
    {
        // sink it
    }
}