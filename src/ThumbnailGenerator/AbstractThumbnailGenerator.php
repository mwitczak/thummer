<?php

namespace Thummer\ThumbnailGenerator;

abstract class AbstractThumbnailGenerator {
    abstract public function generateThumbnail(string $fileName, string $width, string $height): array;

    protected function calculateDimensions($width, $height, $sourceWidth, $sourceHeight)
    {
        $targetAspectRatio = $width / $height;
        $copyWidth = intval($sourceHeight * $targetAspectRatio);
        $copyHeight = $sourceHeight;

        if ($copyWidth > $sourceWidth) {
            // resize copy height fixed to target aspect
            $copyWidth = $sourceWidth;
            $copyHeight = intval($sourceWidth / $targetAspectRatio);
        }

        return [$copyWidth, $copyHeight];
    }
}