<?php

interface ThumbnailGeneratorInterface {
    public function generateThumbnail(string $fileName, string $width, string $height): array;
}